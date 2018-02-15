<?php

namespace Medimax\Components\Dashboard;

/**
 * Dashboard
 *
 * @author jDanek
 */
use Medimax;
use Medimax\Utils\QueryString;
use MedimaxConfig;

class Dashboard
{

    /** @var array */
    private $modules;

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

        if ((isset($qs->action) && 'list' === $qs->action) || (isset($qs->m) && !isset($qs->action))) {
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
        if (isset($qs->m)) {

            if (isset($this->modules[$qs->m])) {
                if ($this->compareVersion($this->modules[$qs->m]['requireVersion'], Medimax::VERSION, '<=')) {
                    return $this->moduleRender($qs->m);
                } else {
                    return _formMessage(2, Medimax::lang('module', 'requireVersion') . " " . Medimax::NAME . " " . $this->modules[$qs->m]['requireVersion']);
                }
            } else {
                // modul neexistuje / uziv. nema opravneni
                return _formMessage(3, str_replace('*module_id*', $qs->m, Medimax::lang('module', 'notExists')));
            }
        } else {
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
        /** @var string */
        static $modId, $modIcon, $modTitle, $modDescription;

        $return = "<div id='dashboard' class='dashboard'>";

        foreach ($this->modules as $mod) {
            /** @var bool */
            $isCorrectVersion = $this->compareVersion($mod['requireVersion'], Medimax::VERSION, '<=');

            // priprava informaci modulu
            $icon_src = $mod->path . DIRECTORY_SEPARATOR . 'resources/icon.png';

            $modId = $mod->id;
            $modIcon = (file_exists($icon_src) ? $icon_src : MedimaxConfig::getDirectory('icons') . "modicon_red.png");
            $modTitle = "<a href='" . MedimaxConfig::moduleUrl($mod->id, true) . "'>{$mod->name}</a>";
            $modDescription = $mod->description;

            // zmenit ikonu pokud neni modul pouzitelny v aktualni verzi prostredi
            if (false === $isCorrectVersion) {
                $modIcon = MedimaxConfig::getDirectory('icons') . "modicon_red.png";
                $modTitle = "<span class='a-disabled' title='" . Medimax::lang('module', 'requireVersion') . Medimax::NAME . " {$mod['requireVersion']}'>{$mod->name}</span>";
            }

            // vypis modulu
            $return .= "<div class='mod-container mod-{$modId}'>
                          <img class='mod-icon' alt='module-icon' src='{$modIcon}' />
                          <div class='mod-data'>
                              <span class='mod-anchor mod-title'>{$modTitle}</span>
                              <span class='mod-description'>{$modDescription}</span>
                          </div>
                       </div>";
        }
        $return .= "</div>";

        return $return;
    }

    /**
     * Porovnani dvou udanych verzi
     *
     * @param string $version1
     * @param string $version2
     * @param string $operator porovnavaci operator (< | <= | => | > | == | = | != | <>)
     * @return boolean
     */
    private function compareVersion($version1, $version2, $operator = '<')
    {
        if (version_compare($version1, $version2, $operator)) {
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
        if (isset($this->modules[$module])) {
            $selectedModule = $this->modules[$module];
            $moduleScript = $selectedModule->path . DIRECTORY_SEPARATOR . $selectedModule->files->runable;

            if (null !== $selectedModule->files->runable && file_exists($moduleScript)) {
                return require $moduleScript;
            } else {
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
        if (isset($this->modules[$module])) {
            $selectedModule = $this->modules[$module];
            $sidebarScript = $selectedModule->path . DIRECTORY_SEPARATOR . $selectedModule->files->sidebar;

            if (isset($selectedModule->files->sidebar) && file_exists($sidebarScript)) {
                return require $sidebarScript;
            }
        }
    }

}
