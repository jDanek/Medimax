<?php

namespace Medimax\Utils;


class SessionStorage
{
    /** @var string */
    protected $delimeter = '/';

    /**
     * SessionStorage constructor
     */
    public function __construct()
    {

    }

    /**
     * Set variable
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
        return $this;
    }

    /**
     * Get variable
     * @param string $key
     * @return null
     */
    public function get($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }

    /**
     * Get variable
     * @param string $key
     * @return null
     */
    public function has($key)
    {
        return (isset($_SESSION[$key]));
    }

    /**
     * Unset variable
     * @param string $key
     * @return $this
     */
    public function del($key)
    {
        unset($_SESSION[$key]);
        return $this;
    }

    /**
     * Set delimeter
     * @param string $delimeter
     * @return $this
     */
    public function setDelimeter($delimeter = '/')
    {
        $this->delimeter = $delimeter;
        return $this;
    }

    /**
     * Get prarts from path
     * @param string $path
     * @return array
     */
    private function getPathParts($path)
    {
        $path = trim($path, $this->delimeter);
        $parts = explode($this->delimeter, $path);
        return $parts;
    }

    /**
     * Return a nested array value (ex: path/in/arr)
     * @param string $path
     * @param mixed $default
     * @return mixed previous value
     */
    private function getPathValue($path, $default = null)
    {
        $path = trim($path, $this->delimeter);
        $exploded = explode($this->delimeter, $path);
        $source = &$_SESSION;
        foreach ($exploded as $key) {
            $source = &$source[$key];
        }
        return $source;
    }
}