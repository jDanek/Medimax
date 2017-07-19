<?php

/* ----  kontrola jadra  ---- */
if (!defined('_core'))
    die;

/**
 * Hlavni trida administraniho modulu MEDIMAX
 */
abstract class Medimax
{

    const NAME = "Medimax";
    const VERSION = "2.0.1";
    const STATE = "";

    /** @var array  */
    private static $subModules = array(
        'medimax' => 'dashboard.php',
    );

    /**
     * Vraci lokalizovany text podle nacteneho jazykoveho balicku
     * 
     * @param string $name
     * @param string $index
     * @return string
     */
    public static function lang($name, $index = null)
    {
        if (null === $index)
        {
            if (isset($GLOBALS['_lang'][MedimaxConfig::$identificator][$name]))
            {
                return $GLOBALS['_lang'][MedimaxConfig::$identificator][$name];
            }
        }
        elseif (isset($GLOBALS['_lang'][MedimaxConfig::$identificator][$name][$index]))
        {
            return $GLOBALS['_lang'][MedimaxConfig::$identificator][$name][$index];
        }
    }

    /**
     * Registrace Medimaxu do administrace a jejiho menu
     * 
     * @param string $args argumenty (viz. dokumentace Sunlight)
     */
    public static function registerMedimax($args)
    {
        /* --- Dynamicky CSS soubor --- */
        $GLOBALS['admin_extra_css'][] = "<link rel='stylesheet' type='text/css' href='" . MedimaxConfig::getDirectory('css') . "medimax" . DIRECTORY_SEPARATOR . "medimaxadmin.css.php" . "?s=" . _adminscheme . (_admin_schemeIsDark() ? '&amp;d' : '') . "&amp;" . _cacheid . "' />";

        /* vlozeni Medimaxu do hlavniho menu administrace */
        $sl_admin_menu = $args['menu'];
        $args['menu'] = array_merge(array_slice($sl_admin_menu, 0, MedimaxConfig::$menuPosition), array(MedimaxConfig::$identificator), array_slice($sl_admin_menu, MedimaxConfig::$menuPosition));

        /* registrace Medimax modulu */
        $args['modules'][MedimaxConfig::$identificator] = array(
            (true === MedimaxConfig::$showIcon ? "<img src='" . MedimaxConfig::getDirectory('icons') . "medimax.png' class='medimax-tabicon' />" : "")
            . self::lang('publicName'),
            MedimaxConfig::$accessLevel,
            null, array()
        );
    }
    
    /**
     * Nacitani souboru inicializovaneho modulu administrace
     * 
     * @param string $args argumenty (viz. dokumentace Sunlight)
     */
    public static function initMedimaxModule($args)
    {
        if (key_exists($args['extra']['name'], self::$subModules))
        {
            $args['extra']['file'] = dirname(__FILE__) . DIRECTORY_SEPARATOR . self::$subModules[$args['extra']['name']];
        }
    }

    /**
     * Nacitani souboru s registracemi Extendu
     */
    public static function loadResources()
    {
        self::autoloadFiles('extends', 'php');
        self::autoloadFiles('css', 'css');
        self::autoloadFiles('js', 'js');
    }

    /**
     * Automaticke nacitani souboru ze slozek
     * 
     * @param string $where nazev slozky nacteny z configu Medimaxu $cesta = $this->params['dirs'][$where];
     * @param string $what lowercase pripona nacitnych souboru
     * @todo pro verzi SL 7.6.0 nahradit nacitanim z prototypu MM2 na Gitu
     */
    private static function autoloadFiles($where = 'extends', $what = 'php')
    {
        $cesta = MedimaxConfig::getDirectory($where);
        $dir = new \DirectoryIterator($cesta);
        foreach ($dir as $file)
        {
            if (!$file->isDir() && !$file->isDot() && $what === $file->getExtension())
            {
                if ('css' === $what)
                {
                    $GLOBALS['admin_extra_css'][] = "<link rel='stylesheet' type='text/css' href='" . MedimaxConfig::getDirectory('css') . $file->getFilename() . "?" . _cacheid . "' />";
                }
                elseif ('js' === $what)
                {
                    $GLOBALS['admin_extra_js'][] = "<script type='text/javascript' src='" . MedimaxConfig::getDirectory('js') . $file->getFilename() . "?" . _cacheid . "'></script>";
                }
                else
                {
                    require_once $cesta . $file->getFilename();
                }
            }
        }
    }

}

/**
 * Integrace Medimaxu do Sunlight CMS
 */
// class autoloader
SL::$classLoader->registerBaseNamespace("Medimax", __DIR__ . DIRECTORY_SEPARATOR . 'class')
        ->registerClass('MedimaxConfig', __DIR__ . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'MedimaxConfig.php');

// registrace jazykoveho balicku
_registerLangPack(MedimaxConfig::$identificator, MedimaxConfig::getDirectory('languages'));

// registrace udalosti administrace
$className = 'Medimax';
_extend('regm', array(
    // registrace doplnkovych extendu
    'admin.start'    => array($className, 'loadResources'),
    // registrace medimaxu
    'admin.init'     => array($className, 'registerMedimax'),
    'admin.mod.init' => array($className, 'initMedimaxModule'),
        ), 100);