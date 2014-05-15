<?php
/**
 * Created by PhpStorm.
 * User: jonmartin
 * Date: 15/02/2014
 * Time: 23:08
 */

function render_scripts($scripts)
{
    $html = array();

    if(!$scripts)
        return false;

    foreach($scripts AS $script)
    {
        if(strpos($script, 'module::') !== false)
        {
            $html[] = '<script type="text/javascript" src="' . site_url(APPPATH . 'modules/' . '/js/ ' . $script) . '"></script>';
        } else {
            $html[] = '<script type="text/javascript" src="' . site_url('assets/js/' . $script) . '"></script>';
        }
    }

    return $html ? implode("\n", $html) : false;
}

function render_styles($styles)
{
	$html = array();

    if(!$styles)
        return false;

    foreach($styles AS $style)
    {
        $html[] = '<link href="'. site_url('assets/' . $style) .'" rel="stylesheet" type="text/css">';
    }

    return $html ? implode("\n", $html) : false;
}