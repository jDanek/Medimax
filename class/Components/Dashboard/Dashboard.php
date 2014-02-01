<?php

namespace Medimax\Components\Dashboard;

/**
 * Dashboard
 *
 * @author jDanek
 */
use Medimax,
    MedimaxConfig,
    Medimax\Utils\QueryString;

class Dashboard
{
    /** @var array */
    private $modules;

    /** @var string */
    private $defaultModuleIcon = "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";

    /** @var string */
    private $brokenModuleIcon = "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";

    /**
     * Konstruktor
     * 
     * @param int $moduleColumns pocet sloupcu vypsanych modulu
     */
    public function __construct($modules)
    {
        $this->modules = $modules->getModules();
    }

    /**
     * Vytvoreni backlinku
     * 
     * @return string
     */
    public function backlink()
    {
        $qs = new QueryString($_GET);

        if ((isset($qs->action) && 'list' === $qs->action) || (isset($qs->m) && !isset($qs->action)))
        {
            return "<a href='" . MedimaxConfig::$adminUrl['board'] . "' class='backlink'>&lt; {$GLOBALS['_lang']['global.return']}</a>";
        }
    }

    /**
     * Routovani modulu
     */
    public function routeContent()
    {
        $qs = new QueryString($_GET);
        // vykresleni modulu
        if (isset($qs->m))
        {

            if (isset($this->modules[$qs->m]))
            {
                if ($this->compareVersion($this->modules[$qs->m]['requireVersion'], Medimax::VERSION, '<='))
                {
                    return $this->moduleRender($qs->m);
                }
                else
                {
                    return _formMessage(2, Medimax::lang('module', 'requireVersion') . " " . Medimax::NAME . " " . $this->modules[$qs->m]['requireVersion']);
                }
            }
            else
            {
                // modul neexistuje / uziv. nema opravneni
                return _formMessage(3, str_replace('*module_id*', $qs->m, Medimax::lang('module', 'notExists')));
            }
        }
        else
        {
            // vykresleni obsahu misto modulu
            return $this->moduleList();
        }
    }

    /**
     * Vypise tabulku s nactenymi moduly
     * 
     * @return string
     */
    public function moduleList()
    {
        $return = "<div id='dashboard' class='dashboard'>";

        foreach ($this->modules as $mod)
        {
            /** @var bool */
            $isCorrectVersion = version_compare($mod['requireVersion'], Medimax::VERSION, '<=');

            // nacteni ikony, nebo default
            $icon_src = $mod->path . DIRECTORY_SEPARATOR . 'resources/icon.png';
            $mod_icon = (file_exists($icon_src) ? $icon_src : "data:image/jpeg;base64,{$this->defaultModuleIcon}");

            // zmenit ikonu pokud neni modul pouzitelny v aktualni verzi prostredi
            if (false === $isCorrectVersion)
            {
                $mod_icon = "data:image/jpeg;base64,{$this->brokenModuleIcon}";
            }

            // vypis modulu
            $return.="<div class='mod-container mod-{$mod->id}'>
                              <img class='mod-icon' alt='module-icon' src='{$mod_icon}' />
                      <div class='mod-data'>
                              <span class='mod-anchor mod-title'>"
                    . ($isCorrectVersion ? "<a href='" . MedimaxConfig::moduleUrl($mod->id, true) . "'>{$mod->name}</a>" : "<span class='a-disabled' title='" . Medimax::lang('module', 'requireVersion') . Medimax::NAME . " {$mod['requireVersion']}'>{$mod->name}</span>")
                    . "</span>
                              <span class='mod-description'>{$mod->description}</span>
                          </div>
                       </div>";
        }
        $return .= "</div>";

        return $return;
    }

    /**
     * Porovnani vzajemni dvou udanych verzi
     * 
     * @param string $version1
     * @param string $version2
     * @param string $operator porovnavaci operator (< | <= | => | > | == | = | != | <>)
     * @return boolean
     */
    private function compareVersion($version1, $version2, $operator = '<')
    {
        if (version_compare($version1, $version2, $operator))
        {
            return true;
        }
        return false;
    }

    /**
     * Vykresleni obsahu vybraneho modulu
     * 
     * @param string $module
     * @return string
     */
    public function moduleRender($module)
    {
        if (isset($this->modules[$module]))
        {
            $selectedModule = $this->modules[$module];
            $module_script = $selectedModule->path . DIRECTORY_SEPARATOR . $selectedModule->files->runable;

            if (null !== $selectedModule->files->runable && file_exists($module_script))
            {
                return require $module_script;
            }
            else
            {
                return _formMessage(3, str_replace('*module_id*', $module, Medimax::lang('module', 'notExists')));
            }
        }
    }

    /**
     * Vraci obsah pro bocni panel modulu pokud existuje
     * 
     * @param string $module idmodulu
     * @return mixed
     */
    public function sidebar($module)
    {
        if (isset($this->modules[$module]))
        {
            $selectedModule = $this->modules[$module];
            $sidebar_script = $selectedModule->path . DIRECTORY_SEPARATOR . $selectedModule->files->sidebar;

            if (null !== $selectedModule->files->sidebar && file_exists($sidebar_script))
            {
                return require $sidebar_script;
            }
        }
    }

}
