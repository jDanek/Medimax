<?php

namespace Medimax\Utils;

/**
 * MUrl class
 *
 * @author jDanek
 */
class QueryString
{

    private $properties = array();

    /**
     * Konstruktoe
     * 
     * @param array $queryString pole s query string daty (obvykle $_GET)
     */
    function __construct(array $queryString)
    {
        $this->properties = $queryString;
    }

    /**
     * Vraci pole s parametry
     * 
     * @return array
     */
    function getArray()
    {
        return $this->properties;
    }

    /**
     * Getter
     * 
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->properties[$key];
    }

    /**
     * Setter
     * 
     * @param string $key
     * @param mixed $val
     * @throws \RuntimeException
     */
    public function __set($key, $val)
    {
        throw new \RuntimeException('Set values is denied');
        //$this->properties[$key] = $val;
    }

    /**
     * Isset
     * 
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->properties[$key]);
    }

    /**
     * Unset
     * 
     * @param string $key
     */
    function __unset($key)
    {
        unset($this->properties[$key]);
    }

}
