<?php
namespace jonkiszp\ConsoleBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Klasa odpowiedzialna za dodanie kolektora nasłuchującego do paska developerskiego
 */
class ConsoleCollector extends DataCollector
{
    /**
     * Metoda kolektora danych.
     * Zostawiam pustą gdyz nie zbieram danych z systemu
     *
     * @param Request $request A Request instance
     * @param Response $response A Response instance
     * @param \Exception $exception An Exception instance
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
    }

    /**
     * Returns the name of the collector
     *
     * @return string The collector name
     */
    public function getName()
    {
        return 'console_collector';
    }
}
?>
