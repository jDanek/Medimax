<?php

/* ----  kontrola jadra  ---- */
if (!defined('_core'))
    die;

use Medimax\Components\Dashboard\Dashboard,
    Medimax\Components\Module\ModLoader,
    Medimax\Utils\QueryString;

/* --- zpracovani modulu --- */
$qs = new QueryString($_GET);
$dashboard = new Dashboard(new ModLoader(MedimaxConfig::getDirectory('modules')));
$sidebar_content = $dashboard->sidebar((!isset($qs->m) ? : $qs->m));

/* --- header --- */
$output.="<p class='bborder'>" . Medimax::lang('description') . "</p>";
_extend('call', 'medimax.description.after', _extendArgs($output));
/* --- /header --- */

/* --- content wraper --- */
$output.="<div class='medimax-dashboard-wrapper'>";

/* --- sidebar --- */
$output.="<div id='medimax-dashboard-sidebar' class='medimax-dashboard-sidebar" . (null === $sidebar_content ? "-hidden" : "") . "'>";

_extend('call', 'medimax.sidebar.title.before', _extendArgs($output));
$output.= "<span style='font-weight:bold; display: block;'>" . Medimax::lang('sidebar', 'title') . "</span>";
_extend('call', 'medimax.sidebar.title.after', _extendArgs($output));

_extend('call', 'medimax.sidebar.content.before', _extendArgs($output));
$output.=$sidebar_content;
_extend('call', 'medimax.sidebar.content.after', _extendArgs($output));

$output.= "</div>";
/* --- /sidebar --- */

/* --- content --- */
$output.="<div class='medimax-dashboard-content" . (null === $sidebar_content ? "-full" : "") . "'>";

/* --- backlink --- */
$output.=$dashboard->backlink();
/* --- /backlink --- */

_extend('call', 'medimax.dashboard.content.before', _extendArgs($output));
$output.=$dashboard->routeContent();
_extend('call', 'medimax.dashboard.content.after', _extendArgs($output));

$output.="</div>";
/* --- /content --- */

$output.="<div style='clear: both'></div>
    </div>";
/* --- /content wraper --- */



/* --- footer --- */
$salogo = "http://www.studioart.cz/pictures/pr/cms/medimax.png";
$header_response = get_headers($salogo, 1);
$studioart = (strpos($header_response[0], "200") ? "<img src='http://www.studioart.cz/pictures/pr/cms/medimax.png' alt='StudioArt.cz' />" : "StudioArt.cz");

$output.="<div class='medimax-dashboard-footer'>
              <div class='links'></div>
              <div class='informations'><a href='http://www.studioart.cz' target='_blank'>{$studioart}</a><br />" . Medimax::NAME . " " . Medimax::VERSION . " " . strtoupper(Medimax::STATE) . "</div>
              <div style='clear: both'></div>
          </div>";

/* --- /footer --- */
