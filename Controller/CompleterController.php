<?php
namespace JP\ConsoleBundle\Controller;

use JP\ConsoleBundle\Finder\HintFileFinder;
use JP\ConsoleBundle\Model\Hint;
use JP\ConsoleBundle\Model\HintCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Kontroler odpowiedzialny za podpowiedź składni
 * Class CompleterController
 * @package JP\ConsoleBundle\Controller
 */
class CompleterController extends Controller
{
    /**
     * Metod wyszukująca obiektu po nazwie
     * @return Response
     */
    public function findAction()
    {
        $hintCollection = new HintCollection();
        $this->getHintInDeclaredObject($hintCollection);
        $hintFileFinder = $this->get('jp_console.hint_file_finder');
        $hintCollection->merge($hintFileFinder->getHintsFromFolder());

        $serializer = new Serializer([new ObjectNormalizer()], array(new JsonEncoder()));
        return new Response($serializer->serialize($hintCollection->toArray(), 'json'));
    }

    /**
     * Metoda wyszukuje w zadeklarowanych obiektach podpowiedzi
     * @param HintCollection $hintCollection kolekcja podpowiedzi
     */
    private function getHintInDeclaredObject(HintCollection $hintCollection)
    {
        $this->findHintInDeclared($hintCollection, get_declared_classes(), 'class');
        $this->findHintInDeclared($hintCollection, get_declared_interfaces(), 'interface');
        $this->findHintInDeclared($hintCollection, get_declared_traits(), 'trait');
    }


    /**
     * Metoda wyszukująca klasę po nazwie
     * @param HintCollection $hintCollection kolekcja podpowiedzi
     * @param array $items lista klas
     * @param string $type typ obiektu
     */
    private function findHintInDeclared(HintCollection $hintCollection, array $items, $type)
    {
        foreach ($items as $item) {
            $hint = new Hint();
            $hint->setName($item)
                ->setValue($item)
                ->setMeta($type)
            ;
            $hintCollection->add($hint);
        }
    }
}