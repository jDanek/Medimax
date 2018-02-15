<?php

/* --- kontrola jadra --- */
if (!defined('_core'))
    die;

use Medimax\Components\Filter\ContentFilter;
use Medimax\Components\Module\ModLoader;

/**
 * Ukazkovy zakladni MEDIMAX 2.x Modul
 */
class MedimaxModuleClass extends AdminBread
{
    /** @var string */
    public $activeModuleId;
    /** @var $filter ContentFilter */
    public $filter;

    /**
     * Pomocna metoda pro sestaveni podminky hledani vyrazu
     * @param $search_query
     * @param $alias
     * @param $cols
     * @return string
     */
    private function tmpSearchQuery($search_query, $alias, $cols)
    {
        $output = '(';
        for ($i = 0, $last = (sizeof($cols) - 1); isset($cols[$i]); ++$i) {
            $output .= $alias . '.' . $cols[$i] . ' LIKE \'' . DB::esc('%' . $search_query . '%') . '\'';
            if ($i !== $last) $output .= ' OR ';
        }
        $output .= ')';

        return $output;
    }

    protected function setup()
    {
        /**
         * ==========================
         * Nastaveni instance modulu
         * ==========================
         */
        $this->table = 'users';
        $this->path = __DIR__;

        /**
         * ===================================================
         * Predani informaci o modulu z nacteneho XML souboru
         * ===================================================
         */
        $that = $this; // PHP 5.3 Closure fix
        $modLoader = new ModLoader(MedimaxConfig::getDirectory('modules'));
        $activeModule = $modLoader->getModule(_get('m'));
        $this->activeModuleId = (string)$activeModule->id;
        $module_title = (string)$activeModule->name;

        // nastaveni identifikatoru modulu
        $this->module = MedimaxConfig::moduleUrl($activeModule->id);

        // nastaveni titulku vsem sablonam modulu
        $bread_actions = array_keys($this->actions);
        $bread_actions[] = 'confirm'; //pridat akci
        foreach ($bread_actions as $action_name) {
            $this->actions[$action_name]['on_before'] = function (&$params) use ($module_title) {
                $params['item_name'] = $module_title;
            };
        }

        //$cond pro ovlivneni dotazu
        $cond = "1";

        /**
         * ================================
         *         FILTROVANI
         * ================================
         */
        $this->filter = new ContentFilter((string)$activeModule->id);
        $this->filter->config['select.option.default'] = 'Filtrování položek';
        $this->filter->addItemsFromFile(require __DIR__ . '/modulefilter.php');

        // ovlivneni dotazu
        $cond = $this->filter->composeCondFromFilters();

        /**
         * ================================
         *         VYHLEDAVANI - HACK :/
         * ================================
         */
        $exp = "1";
        // protoze adminbread si sam escapuje dotazy a LIKE s % neprojde -- DEBILNI RESENI, ALE FUNKCNI
        if (isset($_SESSION['medimax']['search'][(string)$activeModule->id])) {
            $exp = $_SESSION['medimax']['search'][(string)$activeModule->id];
            $where = $this->tmpSearchQuery($exp, 's', array(
                'username', 'publicname', 'email'
            ));
            $query = DB::query("SELECT id FROM `" . $this->formatTable($this->table) . "` s WHERE " . $where);
            if (DB::size($query) > 0) {
                $ids = array();
                while ($i = DB::row($query)) {
                    $ids[] = $i['id'];
                }
                $cond = ($cond != "1" ? $cond . " AND " : "") . "t.id IN (" . DB::arr($ids) . ")";
            } else {
                // nenalezeny zadne zaznamy, ktere by odpovidali hledani;
                // tak nastavime zaporne id a tim se nevypise nic (blbe reseni, ale co uz)
                $cond = ($cond != "1" ? $cond . " AND " : "") . "t.id=-1";
            }
        }

        /**
         * ================================
         *         NASTAVENI SABLON
         * ================================
         */

        /* vypis */
        $this->actions['list']['title'] = '%s - ' . Medimax::lang('module', 'active.list');
        $this->actions['list']['columns'][] = 't.id, t.username, t.email, t.group';
        $this->actions['list']['paginator_size'] = 15;
        $this->actions['list']['query_orderby'] = 't.id DESC';

        // ovlivneni filtrovanim/vyhledavanim
        $this->actions['list']['query_cond'] = $cond;

        /* pridavani */
        $this->actions['create']['title'] = '%s - ' . Medimax::lang('module', 'active.create');
        $this->actions['create']['template'] = 'edit';
        $this->actions['create']['initial_data'] = array(
            'username' => '',
            'email' => '@',
            'group' => 3,
        );

        $this->actions['create']['handler'] = function ($args) use ($that) {

            // validovat odesilana data metodou
            $validateResult = $that->validate($_POST, true);
            $errors = array();
            do {
                // pokud jsou ve zpracovani chyby ... zastavit zpracovani
                if (sizeof($validateResult[0]) > 0) {
                    $errors[] = array(2, _eventList($validateResult[0], 'errors'), null, true);
                    break;
                }

                // vse ok?
                $args['success'] = true;
                return $validateResult[1];
            } while (false);
            return $errors;
        };

        /* mazaní */
        $this->actions['del']['title'] = '%s - ' . Medimax::lang('module', 'active.del');
        $this->actions['del']['extra_columns'][] = 't.username';

        /* editace */
        $this->actions['edit']['title'] = '%s - ' . Medimax::lang('module', 'active.edit');
        $this->actions['edit']['handler'] = function ($args) use ($that) {

            // validovat odesilana data metodou
            $validateResult = $that->validate($_POST, false);
            $errors = array();
            do {
                // pokud jsou ve zpracovani chyby ... zastavit zpracovani
                if (sizeof($validateResult[0]) > 0) {
                    $errors[] = array(2, _eventList($validateResult[0], 'errors'), null, true);
                    break;
                }

                // vse ok?
                $args['success'] = true;
                return $validateResult[1];
            } while (false);
            return $errors;
        };
    }

    /**
     * Validace dat odeslanych sablonymi
     *
     * @param array $postData $_POST data sablon
     * @param bool $createMode prepinac create/edit modu, lze validaci zacilit jen na create nebo jen edit
     * @return array [0=> array chyby, 1=> array zpracovana data]
     */
    function validate(array $postData, $createMode = false)
    {
        // log chyb
        $errors = array();

        // zpracovani dat
        $nick = $postData['username']; // kontrola titulku
        if (empty($nick)) {
            $errors[] = array(2, 'Vyplňte username');
        }

        $mail = $postData['email'];
        if (empty($mail)) {
            $errors[] = array(2, 'Vyplňte email');
        }

        // zpracovana data
        $validatedData = array(
            'username' => $nick,
            'email' => $mail,
        );

        return array($errors, $validatedData);
    }

}

/* --- akce --- */
$mmClass = new MedimaxModuleClass();
return $mmClass->run();
