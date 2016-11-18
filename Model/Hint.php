<?php

namespace jonkiszp\ConsoleBundle\Model;

/**
 * Klasa reprezentująca podpowiedź
 * @package jonkiszp\ConsoleBundle\Model
 */
class Hint
{
    /**
     * @var string nazwa podpowiedzi
     */
    private $name;

    /**
     * @var string wartość podpowiedzi
     */
    private $value;

    /**
     * @var string meta podpowiedzi
     */
    private $meta;

    /**
     * @var int pole punktacji ustawione na 1 dla zachowania spójności z interfejsem ACE
     */
    private $score = 1;

    /**
     * zwraza nazwę podpowiedzi
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Metoda ustawia nazwę podpowiedzi
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Metoda zwraca wartość podpowiedzi
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Metoda ustawia wartość podpowiedzi
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Metoda zwraca typ podpowiedzi
     * @return string
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Metoda ustawia typ podpowiedzi
     * @param string $meta
     * @return $this
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * Zwraca punktacje dla danej frazy
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }
}