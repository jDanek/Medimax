<?php

namespace Medimax\Components\Module;

/**
 * ModLoader class - trida pro dynamicke nacitani modulu
 *
 * @author jDanek <jdanek.eu>
 * @copyright (c) 2014, jDanek
 */
use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SimpleXMLElement;

class ModLoader
{

    /** @var string */
    private $directory;

    /**  @var array */
    private $foundModules = array();

    /**  @var array */
    private $modules = array();

    /**
     * Konstruktor
     *
     * @param string $directory cesta k adresari s moduly
     */
    public function __construct($directory)
    {
        $this->directory = $directory;
        $this->findModules($this->directory);
    }

    /**
     * Prohleda adresar s moduly
     *
     * @param string $modulesDirectory cesta k adresari s moduly
     * @param int $maxDepth maximalni hloubka hledani
     */
    private function findModules($modulesDirectory, $maxDepth = 1)
    {
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($modulesDirectory, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
        $it->setMaxDepth($maxDepth);

        foreach ($it as $fileinfo) {
            $isDot = substr($it->getSubPath(), 0, 1);
            if ('.' !== $isDot && $fileinfo->isFile() && 'xml' === $fileinfo->getExtension()) {
                $pathToFile = $fileinfo->getPathname();
                $loadedModule = simplexml_load_file($pathToFile);

                if ((int)$loadedModule->config->accessLevel <= _loginright_level) {
                    $this->addFoundModule($loadedModule, $pathToFile);
                }
            }
        }

        // zpracovat nalezene moduly
        $this->processFoundModules();
    }

    /**
     * Pridava nalezeny modul do fronty k zpracovani
     *
     * @param SimpleXMLElement $foundModule nalezeny modul
     * @param string $pathToModule cesta k souboru modulu
     */
    private function addFoundModule(SimpleXMLElement $foundModule, $pathToModule)
    {
        $moduleDir = dirname($pathToModule);
        $moduleId = strtolower(basename($moduleDir));

        // pridani parametru
        $foundModule->addChild('id', $moduleId);
        $foundModule->addChild('path', $moduleDir);

        // ulozeni modulu do pole
        $this->foundModules[(int)$foundModule->config->priority][$moduleId] = $foundModule;
    }

    /**
     * Zpracuje nalezene moduly
     */
    private function processFoundModules()
    {
        // seradit pole sestupne podle priority (vyssi priorita bude driv)
        krsort($this->foundModules);

        // vytvorit pole modulu serazene podle priority
        foreach ($this->foundModules as $priority => $modules) {
            foreach ($modules as $id => $mod) {
                $this->modules[$id] = $mod;
            }
        }

        // uklid
        unset($this->foundModules);
    }

    /**
     * Vraci modul podle identifikatoru
     *
     * @param string $module
     * @return object
     */
    public function getModule($module)
    {
        if (isset($this->modules[$module])) {
            return $this->modules[$module];
        }
        return null;
    }

    /**
     * Vraci pole s moduly
     *
     * @return array
     */
    public function getModules()
    {
        return $this->modules;
    }

}
