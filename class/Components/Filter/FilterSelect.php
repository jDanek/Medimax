<?php

namespace Medimax\Components\Filter;

/**
 * Class FilterSelect
 * @autor Jirka Daněk <jdanek.eu>
 */
class FilterSelect
{
    /** @var string */
    public $default_title = '--- Select ---';

    /** @var string */
    private $identifier;

    /** @var FilterOption[] */
    private $options_map = array();

    /** @var array */
    private $active_options = array();

    /**
     * FilterSelect
     * @param $identifier
     */
    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @param FilterOption[] $options
     */
    public function setOptions(array $options)
    {
        foreach ($options as $option)
        {
            $this->setOption($option);
        }
    }

    /**
     * @param FilterOption $option
     */
    public function setOption(FilterOption $option)
    {
        $this->options_map[$option->getGroup()][$option->getId()] = $option;
    }

    /**
     * @param string|array $id
     */
    public function setActiveOption($id)
    {
        $this->active_options += (array)$id;
    }

    /**
     * @param $id
     */
    public function removeActiveOption($id)
    {
        $map = $this->getActiveOptions();
        if (isset($map[$id]))
        {
            unset($map[$id]);
        }
    }

    /**
     * @param bool $flipped
     * @return array|bool
     */
    public function getActiveOptions($flipped = false)
    {
        return ($flipped ? array_flip($this->active_options) : $this->active_options);
    }

    /**
     * Clear active options
     */
    public function clearActiveOprions()
    {
        $this->active_options = array();
    }

    /**
     * @return string
     */
    public function render()
    {
        $output = "<select name='" . $this->identifier . "'>\n";
        foreach ($this->options_map as $_group => $_items)
        {
            if ($_group != FilterOption::BASEGROUP)
            {
                $output .= "<optgroup label='" . $_group . "'>\n";
            }
            foreach ($_items as $id => $item)
            {
                $item->setActive($this->isActive($id));
                $output .= $item->render();
            }
            if ($_group != FilterOption::BASEGROUP)
            {
                $output .= "</optgroup>\n";
            }

        }
        $output .= "</select>\n";

        return $output;
    }

    /**
     * @param $id
     * @return bool
     */
    public function isActive($id)
    {
        $map = $this->getActiveOptions(true);
        return isset($map[$id]);
    }
}
