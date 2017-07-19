<?php
/* ----  priprava  ---- */
header("Content-Type: text/css; charset=UTF-8");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + 2592000) . " GMT");

/* ----  konfigurace  ---- */
$dark = isset($_GET['d']);
if (isset($_GET['s']))
    $s = intval($_GET['s']);
else
    $s = 0;

/* ----  vypocet barev  ---- */

// rucni nacteni tridy (v tomto skriptu se nepouziva jadro SL)
require_once '../../../../../../require/class/color.php';

// funkce pro rychle vytvoreni barvy
function _admin_color($loff = 0, $satc = null, $sat_abs = false)
{
    // nacteni a uprava barev
    $h = $GLOBALS['hue'];
    if ($GLOBALS['dark'])
        $l = $GLOBALS['light'] - $loff;
    else
        $l = $GLOBALS['light'] + $loff;
    $s = (isset($satc) ? ($sat_abs ? $satc : $GLOBALS['sat'] * $satc) : $GLOBALS['sat']);

    // vytvoreni hex kodu barvy
    $color = new Color(array(
        $h,
        $l,
        $s), 1);

    return $color->getRGBStr();
}

// vychozi HLS hodnoty
$hue = 0;
$light = 127;
$sat = 255;

// vychozi barevne hodnoty
$scheme_link = null;
if ($dark)
{
    $scheme_white = "#000";
    $scheme_black = "#fff";
}
else
{
    $scheme_white = "#fff";
    $scheme_black = "#000";
}
$scheme_bar_loff = 30;
$scheme_text = $scheme_black;
if ($dark)
{
    $scheme_contrast = $scheme_black;
    $scheme_contrast2 = $scheme_white;
}
else
{
    $scheme_contrast = $scheme_white;
    $scheme_contrast2 = $scheme_black;
}
$scheme_link_loff = ($dark ? -20 : -10);
$dark_suffix = ($dark ? '_dark' : '');

// uprava podle schematu
switch ($s) {

    // modry
    case 1:
        $hue = 145;
        $sat -= 10;
        break;

    // zeleny
    case 2:
        $hue = 70;
        if (!$dark)
        {
            $light -= 20;
        }
        $sat *= 0.7;
        break;

    // cerveny
    case 3:
        $hue = 5;
        if (!$dark)
        {
            $light -= 10;
        }
        break;

    // zluty
    case 4:
        $hue = 35;
        $scheme_contrast = $scheme_black;
        $scheme_link = "#BE9B02";
        if (!$dark)
        {
            $light -= 20;
        }
        else
        {
            $light += 5;
        }
        break;

    // purpurovy
    case 5:
        $hue = 205;
        break;

    // azurovy
    case 6:
        $hue = 128;
        if (!$dark)
        {
            $light -= 10;
            $sat -= 70;
            $scheme_link_loff -= 10;
        }
        break;

    // fialovy
    case 7:
        $hue = 195;
        if ($dark)
        {
            $light += 10;
        }
        break;

    // hnedy
    case 8:
        $hue = 20;
        $light -= 10;
        $sat *= 0.6;
        break;

    // tmave modry
    case 9:
        $hue = 170;
        if (!$dark)
        {
            $light -= 10;
        }
        else
        {
            $scheme_link_loff -= 20;
        }
        $sat *= 0.5;
        break;

    // sedy
    case 10:
        $hue = 150;
        $sat = 0;
        $scheme_link = "#67939F";
        $scheme_bar_loff = 50;
        break;

    // oranzovy
    default:
        $hue = 17;
        break;
}

// vypocet barev
$scheme = _admin_color(($dark ? 40 : 0));
//$scheme_light = _admin_color(70);
$scheme_lighter = _admin_color(80);
$scheme_lightest = _admin_color(100);
$scheme_smoke = _admin_color(115, 0);
$scheme_smoke_text = _admin_color($light * 0.2, 0);
$scheme_smoke_gray = _admin_color(100, 0);
$scheme_smoke_gray_med = _admin_color(90, 0);
$scheme_smoke_gray_dark = _admin_color(60, 0);
$scheme_smoke_gray_darker = _admin_color($dark ? -20 : -10, 0);
$scheme_smoke_gray_light = _admin_color(110, 0);
$scheme_med = _admin_color(30);
/* $scheme_med_dark = _admin_color(15); */
$scheme_bar = _admin_color($scheme_bar_loff);
$scheme_bar_text = _admin_color($dark ? -100 : -70, 0);
if ($scheme_link == null)
{
    $scheme_link = _admin_color($scheme_link_loff, 255, true);
}
?>
<?php
if ($dark)
{
    ?>
    input, textarea, button, select {
    background-color: <?php echo $scheme_white; ?>;
    color: <?php echo $scheme_black; ?>;
    border: 1px solid <?php echo $scheme_smoke_gray_dark; ?>;
    }
<?php } ?>


/* codemirror */
div.CodeMirror {
<?php if ($dark): ?>
    border: 1px solid <?php echo $scheme_smoke_gray_dark ?>;
<?php else: ?>
    outline: 1px solid <?php echo $scheme_white ?>;
    border-width: 1px;
    border-style: solid;
    border-color: <?php echo $scheme_smoke_gray_dark ?> <?php echo $scheme_smoke_gray ?> <?php echo $scheme_smoke_gray ?> <?php echo $scheme_smoke_gray_dark ?>;
<?php endif ?>
line-height: 1.5;
cursor: text;
background-color: #fff;
}
div.CodeMirror span.cm-hcm {color: <?php echo $dark ? '#ff0' : '#f60' ?>;}

/*//////////////////////////////////////////////////////////////////////////
MEDIMAX DYNAMIC 
////////////////////////////////////////////////////////////////////////*/
.cleaner {clear: both;}

/* ZALOZKA */
.medimax-tabicon {height: 14px; margin-right: 3px; margin-bottom: -2px; width: 14px;}
#content .medimax-tabicon{display: none;}

/* SIDEBAR */
.medimax-dashboard-sidebar{width:225px; margin-right: 12px; float: left; margin-bottom: 20px;}
.medimax-dashboard-sidebar-hidden{display:none;}
.sidebar-list li {padding-top: 5px;}

#medimaxsidebar {width: 20%; float: left; margin-right: 1em;}
#medimaxsidebar, #medimaxsidebar a {font-size: 12px;}
#medimaxsidebar div.scroll-fix {position: fixed; top: 10px; z-index: 100;}
#medimaxsidebar input {width: 100%; padding: 0.5em;}
#medimaxsidebar ul {padding: 0; margin: 0.5em 0 0 0; border: 1px solid <?php echo $scheme_smoke_gray; ?>; background-color: <?php echo $scheme_lighter; ?>;}
#medimaxsidebar li {display: block; list-style-type: none;}
#medimaxsidebar li a {display: block; padding: 11px; border-bottom: 1px solid <?php echo $scheme_lightest; ?>; font-weight: bold; color: <?php echo $scheme_text; ?>;}
#medimaxsidebar li.active a {background-color: <?php echo $scheme; ?>; color: <?php echo $scheme_white; ?>;}

#medimaxsidebarform {float: left; padding-bottom: 30em; width: 78%;}
#medimaxsidebarform fieldset {margin: 0 0 5em 0;}
#medimaxsidebarform table {border-collapse: collapse;}
#medimaxsidebarform table td {padding: 4px 8px; border: 1px solid <?php echo $scheme_smoke_gray_med; ?>;}
#medimaxsidebarform table td:first-child {white-space: nowrap;}
#medimaxsidebarform table td.rpad {padding-right: 8px; padding-left: 4px;}

/* CONTENT */
.medimax-dashboard-content{float: left; width: 930px;}
.medimax-dashboard-content-full{float: left; width: 100%;}

/* POZICOVANI DASHBOARDU */
.medimax-dashboard-wraper{width: 100%;}

#dashboard {}
#dashboard .mod-container{float: left; width: 260px; padding: 4px; margin: 8px 15px 8px 0; border: 1px solid <?php echo $scheme_smoke_gray; ?>;}
#dashboard img.mod-icon { float: left; padding: 0px 8px 0px 0px; width: 48px; height: 48px;}
#dashboard .mod-data {display: block; float: left; padding: 4px 10px 0 0;}
#dashboard .mod-title a {color: <?php echo $scheme_link; ?>;font-size: 17px; display: block;}
#dashboard .mod-title span.a-disabled {color: <?php echo $scheme_smoke_text; ?>;font-size: 17px; display: block; cursor:pointer;}


/* PATKA */
.medimax-dashboard-footer{margin-top: 15px;}
.medimax-dashboard-footer .informations{padding-top: 15px; font-size: smaller;text-align: right;color: <?php echo $scheme_text; ?>;}

/* OSTATNI */
.disabled-action {color: <?php echo $scheme_smoke_text; ?>;}
.medimax-dashboard-footer a{font-size: 11px; text-decoration: underline;}

/* 
=============================
BREAD SABLONY
=============================
*/

/* LIST */

.minimal-width{width:1%; white-space:nowrap;}

.bread-list-before {/*margin: 10px 0 20px 0;*/ padding: 5px 0; /*border-top: 1px solid <?php echo $scheme_smoke_gray; ?>;*/ border-bottom: 1px solid <?php echo $scheme_smoke_gray; ?>;}
.bread-list-before .new-item, .new-item {float:left; margin-top: 10px; margin-bottom: 10px; width: 29%;}
.bread-list-before .paging {float: left; width: 40%;}
.bread-list-before .paging a {background-color: <?php echo $scheme_white; ?>; padding: 2px 2px; margin: 0 !important;}
.bread-list-before .paging a.act, .bread-list-before .paging a:hover {padding: 4px 2px; color: <?php echo $scheme_text; ?>;}
.bread-list-before .filter {float:right; text-align: right;}

.medimax-dashboard-wrapper .list td {padding: 5px 10px 5px 5px;}

.bread-actions {width:1%; white-space:nowrap; padding: 5px 10px !important;}
.bread-actions span{display:none;}
.list-action-btn .icon {height: 14px; width: 14px;}
.list-action-btn .icon {background-color: #F5F5F5; padding: 2px 5px; margin: -1px 2px 0 0 !important; border: 1px solid <?php echo $scheme_smoke_text; ?>;}
<?php if ($dark) { ?>
.list-action-btn .icon {background-color: <?php echo $scheme_white; ?>; padding: 2px 5px; margin: -1px 2px 0 0 !important; border: 1px solid <?php echo $scheme_smoke_gray_dark; ?>;}
<?php } ?>

/* FILTROVANI */
option.item-selected {color: blue;}
