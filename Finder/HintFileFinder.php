<?php

namespace jonkiszp\ConsoleBundle\Finder;

use jonkiszp\ConsoleBundle\Model\Hint;
use jonkiszp\ConsoleBundle\Model\HintCollection;
use Symfony\Component\Finder\Finder;

/**
 * Klasa odpowiedzialna za wyszukiwanie obiektów i ich przestrzeni nazw
 * Class ObjectFinder
 * @package jonkiszp\ConsoleBundle\Finder
 */
class HintFileFinder
{
    /**
     * @var HintCollection kolekcja podpowiedzi
     */
    private $hintCollection;

    /**
     * @var string ścieżka od której będą wyszukiwane pliki
     */
    private $path;

    /**
     * @var string[] tablica wyłączony katalogów
     */
    private $excludeDir;

    /**
     * Konstruktor
     * @param string $path ścieżka od której zacznie się wyszukiwanie obiektów
     * @param array $excludeDir foldery wykluczone z poszukiwań
     */
    public function __construct($path, array $excludeDir = [])
    {
        $this->hintCollection = new HintCollection();
        $this->excludeDir = $excludeDir;
        $this->path = $path;
    }

    /**
     * Metoda przeszukuje rekursywnie folder w poszukiwaniu obiektów
     * @return HintCollection kolekcja podpowiedzi
     */
    public function getHintsFromFolder()
    {
        $finderFiles = Finder::create()->files()->in($this->path)->name('*.php')->exclude($this->excludeDir);
        foreach ($finderFiles as $finderFile) {
            $hint = $this->getHintsFromFile($finderFile->getRealPath());
            if ($hint->getName() != '') {
                $this->hintCollection->add($hint);
            }
        }
        return $this->hintCollection;
    }

    /**
     * Metoda wyszukuje podpowiedź w danym pliku
     * @param string $filename plik w którym szukamy
     * @return Hint metoda zwraca podpowiedź
     */
    public function getHintsFromFile($filename)
    {
        $hint = new Hint();
        # Otwarcie pliku i odczytanie linia po linii
        $lines = file($filename);
        # Wyciągnięcie przestrzeni nazw
        $namespace = $this->getNamespace($lines);

        $nameLine = preg_grep('/(class|interface|trait)\s([^\s]+)?\s/i', $lines);
        $nameLine = array_shift($nameLine);
        $match = [];
        preg_match('/(class|interface|trait)\s([^\s]+)?\s/i', $nameLine, $match);
        $objectName = array_pop($match);
        if (!is_null($namespace) && $namespace != '') {
            $objectName = $namespace.'\\'.$objectName;
        }
        $hint->setName($objectName);
        $hint->setValue($objectName);
        $hint->setMeta(array_pop($match));

        return $hint;
    }

    /**
     * Metoda wyszukująca przestrzeń nazw w pliku
     * @param string[] kolekcja wierszy pliku
     * @return mixed
     */
    public function getNamespace($lines)
    {
        $namespaceLine = preg_grep('/^namespace /', $lines);
        $namespaceLine = array_shift($namespaceLine);
        $match = [];
        preg_match('/^namespace (.*);$/', $namespaceLine, $match);
        return array_pop($match);
    }
}