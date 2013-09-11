<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * JSload Class
 * 
 * Used for generating JS and jQuery script HTML
 * @author Jon Martin <jon@bigspring.co.uk>
 * @version 1.0
 * @package codigniter libraries
 */
class EX_Jsload
{
    /**
    * Loads jQueryUI from Google APIs
    * @global script path for jqueryUI
    * @return $string
    */
    public function jQueryUI()
    {
        $scripts[] = '<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js" type="text/javascript"></script>';
        $scripts[] = '<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/themes/base/jquery-ui.css" type="text/css" media="all" />';
        return implode($scripts);
    }

    /**
    * Loads fancybox from local path SITEROOT/js/fancybox/
    * @param string $mode the mode of fancybox (eg. inline, iframe, etc)- see fancydox documentation for more details
    * @return string
    */
    function fancybox($mode)
    {
        $scripts[] = '<script type="text/javascript" src="' . base_url() . 'js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>';
        $scripts[] = '<script type="text/javascript" src="' . base_url() . 'js/fancybox/jquery.easing-1.3.pack.js"></script>';
        $scripts[] = '<script type="text/javascript" src="' . base_url() . 'js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>';
        $scripts[] = '<link rel="stylesheet" href="' . base_url() . 'js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />';
        
        if($mode = 'inline')
            $scripts[] = '<script>$(document).ready(function() {$("a#inline").fancybox({"hideOnContentClick": false, "titleShow": false});});</script>';
        return implode($scripts);
    }

    /**
    * Loads a datepicker script that sets another form element onchange
    * @param string $altfields the alternative (hidden) form field to be changed
    * @return string
    */
    function datePicker($altfields)
    {
        $scripts[] = loadjQueryUI();
        
        foreach($altfields AS $origfield => $altfield):
            $scripts[] = '<script>$(function() {$( "#' . $origfield . '" ).datepicker({dateFormat: "dd/mm/yy", altField: "#' . $altfield . '", altFormat: "yy-mm-dd"});});</script>';
        endforeach;
        
        return implode($scripts);
    }

    /**
    * Loads a javascript form submission script so we can submit forms with <a> links
    * @param string $submit the id of the submit <a> link
    * @param array $form the id of the form to be submitted
    * @return string
    */
    function formSubmit($submit, $form)
    {
        $script = '<script>$(document).ready(function() {$("#' . $submit . '").click(function(){$("#' . $form . '").submit();return false; });});</script>';
        return $script;
    }
}