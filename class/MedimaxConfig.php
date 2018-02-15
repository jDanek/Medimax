<?php

//namespace Medimax;

/**
 * Staticka trida nastaveni administracniho rozhrani Medimax
 *
 * @author jDanek <jdanek.eu>
 * @copyright (c) 2014, jDanek
 */
class MedimaxConfig
{
    /**
     * ====================
     * Nastaveni prostredi
     * ====================
     */

    /**
     * Identifikator MEDIMAXu
     * @var string
     */
    public static $identificator = 'medimax';

    /**
     * Indikator zobrazeni ikonky
     * @var bool
     */
    public static $showIcon = false;

    /**
     * Poradi zalozky v menu hlavni administrace
     * @var int
     */
    public static $menuPosition = 2;

    /**
     * Minimalni uroven pro pristup k modulu
     * @var int
     */
    public static $accessLevel = 600;

    /**
     * Url adresy v administraci
     *
     * Moznosti:
     * board, module
     *
     * @var array
     */
    public static $adminUrl = array(
        'board' => './index.php?p=medimax',
        'module' => './index.php?p=medimax&m=',
    );

    /**
     * ==================
     * Cesty k adresarum
     * ==================
     */

    /** @var array */
    private static $directoryList = array(
        /* --- Korenovy adresar --- */
        'root' => 'plugins/extend/medimax/',
        /* --- Zdroje --- */
        'resources' => 'resources/',
        'languages' => 'resources/languages/',
        'css' => 'resources/css/',
        'js' => 'resources/js/',
        'images' => 'resources/images/',
        'icons' => 'resources/images/icons/',
        /* --- Addony --- */
        //'addons'    => 'class/Addons/',
        /* --- Moduly --- */
        'modules' => 'class/Modules/',
        /* --- Extendy --- */
        'extends' => 'extends/',
    );

    /**
     * Vraci cestu k adresari podle klice
     * @param string $key nazev slozky
     *
     * Moznosti:
     * root,resources, configs, languages, css, js, images, icons, modules
     *
     * @return string
     * @throws \Exception
     */
    public static function getDirectory($key)
    {
        // vraci pouze root adresar
        if ('root' === $key) {
            return _indexroot . self::$directoryList['root'];
        } // vraci "root/adresar/"
        elseif (isset(self::$directoryList[$key])) {
            return _indexroot . self::$directoryList['root'] . self::$directoryList[$key];
        } else {
            throw new \Exception("Adresář s klíčem `{$key}` neexistuje.");
        }
    }

    /**
     * Vraci sestavenou url cast modulu (př.: medimax&m=modulname)
     *
     * @param string $moduleId id modulu
     * @param bool $withIndex indikator pro vraceni stringu "index.php&p=" pred sestavenou casti
     * @return string
     */
    public static function moduleUrl($moduleId, $withIndex = false)
    {
        return (true === $withIndex ? "./index.php?p=" : "")
            . self::$identificator . "&m=" . $moduleId;
    }

    /**
     * Generovat odkazy sidebaru z pole
     *
     * @param array $links asociativni pole s odkazy ([titulek=>adresa, titulek=>adresa])
     * @param string $class
     * @return string
     */
    public static function genSidebarMenu(array $links, $class = 'sidebar-menu')
    {
        $output = "<ul class='{$class}'>\n";
        foreach ($links as $title => $href) {
            $output .= "<li><a href='{$href}' class='{$class}-link'>{$title}</a></li>\n";
        }
        $output .= "</ul>";

        return $output;
    }

}
