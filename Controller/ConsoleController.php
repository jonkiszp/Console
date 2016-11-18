<?php

namespace jonkiszp\ConsoleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonDecode;

/**
 * Kontroler odpowiedzialny za obslugę konsoli
 * Class ConsoleController
 * @package jonkiszp\ConsoleBundle\Controller
 */
class ConsoleController extends Controller
{
    /**
     * Domyślna akcja wywołująca widok edytora
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('JPConsoleBundle:Console:index.html.twig');
    }

    /**
     * Akcja ajax uruchamiająca kod
     * @param Request $request
     * @param string $type typ kodu do przetworzenia parametr przygotowany do rozbudowy systemu
     * @return Response
     */
    public function runAction(Request $request, $type)
    {
        $this->shutdownRegister();

        if ($type != 'php') {
            return new Response();
        }

        $codeRun = $this->getCodeToRun($request);
        if ($codeRun === false) {
            return new Response();
        }

        # Pobranie serwisu twig do wykonania widoków
        $twig = $this->get('twig');

        # Wykonanie kodu
        try {
            ob_start();
            eval($this->stripPhpTag($codeRun->code));
            $dataResponse = ob_get_clean();
            $dataResponse = $twig->render('@JPConsole/Console/base.html.twig', array('body' => $dataResponse));
        } catch (\Exception $exception) {
            $dataResponse = $twig->render('@JPConsole/Console/exception.html.twig', array('exception' => $exception));
        }

        return new Response($dataResponse);
    }

    /**
     * Metoda wyświetlająca zawartość zmiennej do wywołania z poziomu konsoli.
     * metoda nie jest nigdzie używana lecz można ją użyć w samej konsoli
     * @param mixed $var dowolna zmienna
     */
    private function dump($var)
    {
        $twig = $this->get('twig');
        echo $twig->render('@JPConsole/Console/dump.html.twig', ['var' => $var]);
    }

    /**
     * Metoda rejestrująca metodę zamknięcia akcji
     */
    private function shutdownRegister() {
        register_shutdown_function(array($this, 'shutdown'));
    }

    /**
     * Metoda wyświetlająca błędy
     */
    private function shutdown()
    {
        echo error_get_last();
    }

    /**
     * Metoda usuwająca znaczniki php z kodu
     * @param string $code kod do przygotowania do przetworzenia
     * @return string
     */
    private function stripPhpTag($code)
    {
        return preg_replace('{^\s*<\?(php)?\s*}i', '', $code).PHP_EOL;
    }

    /**
     * Metoda pobiera kod do uruchomienia
     * @param Request $request
     * @return bool|mixed
     */
    private function getCodeToRun(Request $request)
    {
        if (!$request->request->has('codeRun')) {
            return false;
        }

        # Pobranie kodu do uruchomienia
        $jsonDecode = new JsonDecode();
        return $jsonDecode->decode($request->request->get('codeRun'), 'json');
    }
}
