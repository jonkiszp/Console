<?php

namespace jonkiszp\ConsoleBundle\Model;

/**
 * Klasa reprezentująca kolekcje podpowiedzi
 * @package jonkiszp\ConsoleBundle\Model
 */
class HintCollection implements \Iterator
{
    /**
     * @var Hint[] kolekcja podpowiedzi
     */
    private $hints = [];

    /**
     * Metoda dodaje podpowiedź
     * @param Hint $hint
     */
    public function add(Hint $hint)
    {
        if (!$this->isExist($hint)) {
            $this->hints[] = $hint;
        }
    }

    /**
     * Metoda łącząca kolekcje podpowiedz
     * @param HintCollection $hintCollection
     */
    public function merge(HintCollection $hintCollection)
    {
        foreach ($hintCollection->toArray() as $hint) {
            $this->add($hint);
        }
    }

    /**
     * Metoda zwraca kolekcje podpowiedzi jako array
     * @return Hint[]
     */
    public function toArray()
    {
        return $this->hints;
    }

    /**
     * Metoda sprawdza czy taka podpowiedź istnieje w kolekcji
     * @param Hint $test
     * @return bool
     */
    public function isExist(Hint $test)
    {
        foreach ($this->hints as $hint) {
            if ($hint->getName() == $test->getName() && $hint->getValue() == $test->getValue()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return current($this->hints);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        next($this->hints);
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return key($this->hints);
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        $key = key($this->hints);
        return ($key !== null && $key !== false);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        reset($this->hints);
    }
}