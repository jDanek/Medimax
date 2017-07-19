<?php
/* --- kontrola jadra --- */
if (!defined('_core'))
    die;

return array(
    'Skupina' => array(
        array('name' => 'Čtenáři', 'cond' => 't.group=3'),
        array('name' => 'Radaktoři', 'cond' => 't.group=9'),
    ),
);