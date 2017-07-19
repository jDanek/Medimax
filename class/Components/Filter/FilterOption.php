<?php

namespace Medimax\Components\Filter;

/**
 * Class FilterOption
 * @autor Jirka Daněk <jdanek.eu>
 */
class FilterOption
{
    const BASEGROUP = "_common_";

    /** @var string */
    private $id;

    /** @var  string */
    private $name;

    /** @var  string */
    private $cond;

    /** @var  string */
    private $group;

    /** @var bool */
    private $is_active = false;

    /**
     * FilterOption constructor
     */
    public function __construct()
    {
        // prepare default
        $this->setName('option');
        $this->getGroup(self::BASEGROUP);
        $this->setCond(null);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param $key
     * @return $this
     */
    public function generateId($key)
    {
        $this->id = substr(md5($key), 0, 8);
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {

        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getCond()
    {
        return $this->cond;
    }

    /**
     * @param string $cond
     * @return $this
     */
    public function setCond($cond)
    {
        $this->cond = $cond;
        return $this;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param string $group
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->is_active;
    }

    /**
     * @param bool $is_active
     * @return $this
     */
    public function setActive($is_active)
    {
        $this->is_active = $is_active;
        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        return "<option value='" . $this->id . "'" . ($this->isActive() ? "class='item-selected' selected" : "") . ">" . ($this->isActive() ? "&#10004; " : "") . $this->name . "</option>\n";
    }

}
