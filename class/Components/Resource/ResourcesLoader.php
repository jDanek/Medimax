<?php

namespace Medimax\Components\Resource;

/**
 * ResourcesLoader - trida pro nacitani zdroju
 *
 * @author jDanek <jdanek.eu>
 * @copyright (c) 2014, jDanek
 */
class ResourcesLoader
{

    /** @var array */
    protected $loadedFiles;

    /**
     * Konstruktor
     * @param string $dir cesta k adresari se soubory
     * @param array $extensions pole pripon ktere se maji nacist
     */
    public function __construct($dir, array $extensions, $filename = null)
    {
        $this->loadedFiles = array();
        /* --- projit pripony --- */
        foreach ($extensions as $key => $extension) {
            $this->fileFinder($dir, $extension, $filename);
        }
    }

    /**
     * Vratit pole souboru podle pripony
     * @param string $extension pripona, podle ktere se vrati soubory
     * @return array
     */
    public function getFiles($extension)
    {
        if (isset($this->loadedFiles[$extension])) {
            return $this->loadedFiles[$extension];
        } else {
            return array();
        }
    }

    /**
     * Vyhledani vsech souboru v ceste podle pripony
     * @param string $path cesta k adresari se soubory
     * @param string $extension nacitana pripona souboru
     * @param type $filename konkretni jmeno hledaneho souboru (bez pripony)
     */
    private function fileFinder($path, $extension, $filename)
    {
        //$out = array();
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::CURRENT_AS_FILEINFO | \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $file) {
            if (strtolower($file->getExtension()) === $extension) {
                if (null === $filename) {
                    $this->loadedFiles[$extension][] = $file->getPathname();
                } else {
                    if ($filename . "." . $extension === $file->getFilename()) {
                        $this->loadedFiles[$extension][] = $file->getPathname();
                    }
                }
            }
        }
    }

}
