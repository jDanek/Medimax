<?php

/* --- kontrola jadra --- */
if (!defined('_core'))
    die;

/**
 * Ukazkovy zakladni MEDIMAX 2.x Modul
 */
class MedimaxModuleClass extends AdminBread
{

    protected function setup()
    {
        /**
         * ===================================================
         * Predani informaci o modulu z nacteneho XML souboru
         * ===================================================
         */
        $modLoader = new Medimax\Components\Module\ModLoader(MedimaxConfig::getDirectory('modules'));
        $activeModule = $modLoader->getModule(_get('m'));
        $module_title = (string) $activeModule->name;

        // nastaveni identifikatoru modulu
        $this->module = MedimaxConfig::moduleUrl($activeModule->id);

        // nastaveni titulku vsem sablonam modulu
        $bread_actions = array_keys($this->actions);
        foreach ($bread_actions as $action_name)
        {
            $this->actions[$action_name]['on_before'] = function(&$params) use($module_title) {
                $params['item_name'] = $module_title;
            };
        }

        // registrace jazykoveho balicku (pristupny pres "mmm.nazevmodulu")
        _registerLangPack('mmm.' . $activeModule->id, $activeModule->path . DIRECTORY_SEPARATOR . 'languages/');

        /**
         * ==========================
         * Nastaveni instance modulu
         * ==========================
         */
        $this->table = 'users';
        $this->path = __DIR__;
        $that = $this; // closure PHP 5.3 FIX

        /* výpis */
        $this->actions['list']['title'] = '%s - ' . Medimax::lang('module', 'active.list');
        $this->actions['list']['columns'][] = 't.id, t.username, t.email';
        $this->actions['list']['paginator_size'] = 15;
        $this->actions['list']['query_orderby'] = 't.id DESC';

        /* pridavani */
        $this->actions['create']['title'] = '%s - ' . Medimax::lang('module', 'active.create');
        $this->actions['create']['template'] = 'edit';
        $this->actions['create']['initial_data'] = array(
            'username' => '',
            'email'    => '@'
        );

        $this->actions['create']['handler'] = function($args) use($that) {

            // validovat odesilana data metodou
            $validateResult = $that->validate($_POST, true);
			$errors = array();
            do
            {
                // pokud jsou ve zpracovani chyby ... zastavit zpracovani
                if (sizeof($validateResult[0]) > 0)
                {
                    $errors[] = array(2, _eventList($validateResult[0],'errors'), null, true);
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
        $this->actions['edit']['handler'] = function($args) use($that) {

            // validovat odesilana data metodou
            $validateResult = $that->validate($_POST, false);
			$errors = array();
            do
            {
                // pokud jsou ve zpracovani chyby ... zastavit zpracovani
                if (sizeof($validateResult[0]) > 0)
                {
                    $errors[] = array(2, _eventList($validateResult[0],'errors'), null, true);
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
        if (empty($nick))
        {
            $errors[] = array(2, 'Vyplňte username');
        }

        $mail = $postData['email'];
        if (empty($mail))
        {
            $errors[] = array(2, 'Vyplňte email');
        }

        // zpracovana data
        $validatedData = array(
            'username' => $nick,
            'email'    => $mail,
        );

        return array($errors, $validatedData);
    }

}

/* --- akce --- */
$mmClass = new MedimaxModuleClass();
return $mmClass->run();
