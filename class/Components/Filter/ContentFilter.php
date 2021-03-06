<?php

namespace Medimax\Components\Filter;

/**
 * Class ContentFilter
 * @autor Jirka Daněk <jdanek.eu>
 */
class ContentFilter
{
    /** @var array */
    public $config = array(
        'select.name' => 'filter-select',
        'select.option.default' => '--- Select ---',
    );
    /** @var string */
    private $identifier;
    /** @var array */
    private $items = array();

    /**
     * ContentFilter
     * @param $identifier
     */
    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->identifier;
    }

    public function setDefaultOptionText($text)
    {
        $this->defaultOptionText = $text;
    }

    /**
     * @param array $items [['name'=>'Title 1', 'cond'=>'column=1'], ...]
     * @param string $group
     */
    public function addItems(array $items, $group = FilterOption::BASEGROUP)
    {
        foreach ($items as $item) {
            $this->addItem($item['name'], $item['cond'], $group);
        }
    }

    /**
     * @param $name
     * @param $cond
     * @param string $group
     */
    public function addItem($name, $cond, $group = FilterOption::BASEGROUP)
    {
        $option = new FilterOption();
        $option->generateId($name, $group)
            ->setName($name)
            ->setCond($cond)
            ->setGroup($group);
        $this->items[$group][$option->getId()] = $option;
    }

    /**
     * @param $filter_map (require file return ['Group'=>[['name'=>'Title 1', 'cond'=>'column=1']...],...])
     */
    public function addItemsFromFile($filter_map)
    {
        foreach ($filter_map as $group => $items) {
            foreach ($items as $x => $item) {
                $this->addItem($item['name'], $item['cond'], $group);
            }
        }
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return string
     */
    public function composeCondFromFilters()
    {
        $filters = array();
        /*if ($this->getActiveFromSession() !== null)
        {
            $filters = array_keys($this->getActiveFromSession());
        }*/

        if ($this->getActiveFromSession() !== null) {
            $filters = $this->getActiveFromSession();
        }

        $act = array();
        $items = $this->getOnlyItems();
        foreach ($filters as $g => $f) {
            if (isset($items[$f])) {
                $act[] = $items[$f]->getCond();
            }
        }
        return count($act) > 0 ? implode(' AND ', $act) : "1";
    }

    /**
     * @return mixed
     */
    public function getActiveFromSession()
    {
        if (isset($_SESSION['medimax']['filters'][$this->identifier]) && count($_SESSION['medimax']['filters'][$this->identifier]) > 0) {
            return $_SESSION['medimax']['filters'][$this->identifier];
        }
        return null;
    }

    /**
     * @return array
     */
    public function getOnlyItems()
    {
        $items = array();
        foreach ($this->items as $g => $i) {
            $items = array_merge($items, $i);
        }
        return $items;
    }

    /**
     * @return string
     */
    public function generateSelect()
    {
        $select = new FilterSelect($this->config['select.name']);
        // default option
        $defaultOpt = new FilterOption();
        $defaultOpt->setId(-1)->setGroup(FilterOption::BASEGROUP)->setName($this->config['select.option.default']);
        $select->setOption($defaultOpt);

        if ($this->getActiveFromSession() != null) {
            $select->setActiveOption($this->getActiveFromSession());
        }
        foreach ($this->items as $i) {
            $select->setOptions($i);
        }

        return $select->render();
    }

    /**
     * @param $mode set/del/clear
     * @param $key
     */
    public function refreshFilters($mode, $key)
    {
        switch ($mode) {
            case 'set':
                if ($key != '-1') {
                    $this->saveActiveToSession($key);
                }
                break;
            case 'del':
                $this->removeActiveFromSession($key);
                break;
            case 'clear':
                $this->clearActiveInSession();
                break;
            case 'search':
                if ($key != null) {
                    $_SESSION['medimax']['search'][$this->identifier] = $key;
                } else {
                    unset($_SESSION['medimax']['search'][$this->identifier]);
                }

                break;
        }
        header("Refresh:0");
    }

    /**
     * @param $active
     * @return int
     */
    public function saveActiveToSession($active)
    {
        $value = explode(':', $active);
        $_SESSION['medimax']['filters'][$this->identifier][$value[0]] = $active;
    }

    /**
     * @param $active
     */
    public function removeActiveFromSession($active)
    {
        $value = explode(':', $active);
        if (isset($_SESSION['medimax']['filters'][$this->identifier][$value[0]])) {
            unset($_SESSION['medimax']['filters'][$this->identifier][$value[0]]);
        }
    }

    /**
     * Unset module filters
     */
    public function clearActiveInSession()
    {
        unset($_SESSION['medimax']['filters'][$this->identifier]);
    }

}
