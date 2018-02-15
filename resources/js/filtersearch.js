$(document).ready(function () {

    //cookie
    var medimax_filter_form = $.cookie('mx-filter-form');
    var medimax_search_form = $.cookie('mx-search-form');

    if( typeof medimax_filter_form !== "undefined" && medimax_filter_form == "visible" ) {
        $('form[name="filterform"]').show(); // visible, and cookie is set
        $('button[id="fshow"]').hide();
        $('button[id="fhide"]').show();
    } else {
        $('form[name="filterform"]').hide();// hidden or cookie is not set, set just in case
        $.cookie('mx-filter-form', 'hidden'); // update (or set) the cookie
        $('button[id="fhide"]').hide();
        $('button[id="fshow"]').show();
    }

    if( typeof medimax_search_form !== "undefined" && medimax_search_form == "visible" ) {
        $('form[name="searchform"]').show(); // visible, and cookie is set
        $('button[id="sshow"]').hide();
        $('button[id="shide"]').show();
    } else {
        $('form[name="searchform"]').hide(); // hidden or cookie is not set, set just in case
        $.cookie('mx-search-form', 'hidden'); // update (or set) the cookie
        $('button[id="shide"]').hide();
        $('button[id="sshow"]').show();
    }

    //switch
    $("#fshow").click(function () {
        $('form[name="filterform"]').show();
        $.cookie('mx-filter-form', 'visible');
        $('button[id="fshow"]').hide();
        $('button[id="fhide"]').show();
    });
    $("#fhide").click(function () {
        $('form[name="filterform"]').hide();
        $.cookie('mx-filter-form', 'hidden');
        $('button[id="fhide"]').hide();
        $('button[id="fshow"]').show();
    });
    $("#sshow").click(function () {
        $('form[name="searchform"]').show();
        $.cookie('mx-search-form', 'visible');
        $('button[id="sshow"]').hide();
        $('button[id="shide"]').show();
    });
    $("#shide").click(function () {
        $('form[name="searchform"]').hide();
        $.cookie('mx-search-form', 'hidden');
        $('button[id="shide"]').hide();
        $('button[id="sshow"]').show();
    });
});