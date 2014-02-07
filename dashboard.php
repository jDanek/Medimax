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

/* --- priprava promennych --- */
static $descAfter, $sTitleBefore, $sTitleAfter, $sContBefore, $sContAfter, $contentBefore, $contentAfter, $footerLinks;

/* --- rozsireni dashboard --- */
_extend('call', 'medimax.dashboard', array(
    'header-after' => &$descAfter,
    'sidebar'      => array('title' => array('before' => &$sTitleBefore, 'after' => &$sTitleAfter), 'content' => array('before' => &$sContBefore, 'after' => &$sContAfter)),
    'content'      => array('before' => &$contentBefore, 'after' => &$contentAfter),
    'footer-links' => &$footerLinks,
));

/* --- header --- */
$output.="<p class='bborder'>" . Medimax::lang('description') . "</p>{$descAfter}";

/* --- content wraper --- */
$output.="<div class='medimax-dashboard-wrapper'>";

/* --- sidebar --- */
$output.="<div id='medimax-dashboard-sidebar' class='medimax-dashboard-sidebar" . (null === $sidebar_content ? "-hidden" : "") . "'>
{$sTitleBefore}<span style='font-weight:bold; display: block;'>" . Medimax::lang('sidebar', 'title') . "</span>{$sTitleAfter}
{$sContBefore}{$sidebar_content}{$sContAfter}
</div>";

/* --- content --- */
$output.="<div class='medimax-dashboard-content" . (null === $sidebar_content ? "-full" : "") . "'>
{$dashboard->backlink()}
{$contentBefore}{$dashboard->routeContent()}{$contentAfter}
</div>";

/* --- cleaner --- */
$output.="<div class='cleaner'></div>
</div>";

/* --- footer --- */
$salogo = "http://www.studioart.cz/pictures/pr/cms/medimax.png";
$header_response = @get_headers($salogo, 1);
$studioart = (strpos($header_response[0], "200") ? "<img src='http://www.studioart.cz/pictures/pr/cms/medimax.png' alt='StudioArt.cz' />" : "StudioArt.cz");

$output.="<div class='medimax-dashboard-footer'>
              <div class='links'>{$footerLinks}</div>
              <div class='informations'><a href='http://www.studioart.cz' target='_blank'>{$studioart}</a><br />" . Medimax::NAME . " " . Medimax::VERSION . " " . strtoupper(Medimax::STATE) . "</div>
              <div class='cleaner'></div>
          </div>";
