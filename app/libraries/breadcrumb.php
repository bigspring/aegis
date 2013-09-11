<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * Breadcrumb library
 * @author Jon Martin <jon@bigspring.co.uk>
 * @version 1.0
 * @package codigniter libraries
 */
class EX_Breadcrumb
{
    /**
    * Accepts the current URI segments, controller and entity title to generate a breadcrumb
    * @param string $segments The current URI segments
    * @param string $controller the current controller in use
    * @param string $controller The current entity eg. job, product, etc
    * @return string $breadcrumb
    */
    function get_breadcrumb($segments, $controller, $entity)
    {
        $ci =& get_instance();
        $ci->load->helper('inflector');

        $separator = '/';
        // if we're on the dashboard, just return dashboard as the breadcrumb
        $html[] = '<div>';
        $html[] = 'You are here:';
        
        if($controller == 'dashboard'):
            $html[] = '<span>Dashboard</span>';
            $html[] = '</div>';
            return implode($html);
        else:
            $html[] = '<span>' . '<a href="' . base_url() . '">Dashboard' . '</a></span>';
        endif;
        
        // rewrite exceptions
        if($segments[$controller] == 'picklist')
            $segments[$controller] = 'Pick List';
        if($segments[$controller] == 'add')
            $segments[$controller] = 'Create ' . $entity;
        if($segments[$controller] == 'jobhistory')
            $segments[$controller] = 'Job History';
        
        // if we have an entity and an ID, build the correct breadcrumb
        if($entity != null && array_key_exists('id', $segments)):
            $html[] = $separator;
            $html[] = '<span>' . '<a href="' . site_url($controller). '">' . humanize($controller) . '</a></span>';
            $html[] = $separator;
            $html[] = '<span><a href="' . site_url($controller . '/view/id/' . $segments['id']) . '">' . $entity . '</a></span>';
            $html[] = $separator;
            $html[] = '<span>' . humanize($segments[$controller]) . '</span>';
        // if we have an entity but no id, we must be on a generic page (eg. add)
        elseif($entity != null && !array_key_exists('id', $segments)):
            $html[] = $separator;
            $html[] = '<span>' . '<a href="' . site_url($controller). '">' . humanize($controller) . '</a></span>';
            $html[] = $separator;
            $html[] = '<span>' . humanize($segments[$controller]) . '</span>';
        // otherwise we must be on an index page
        else:
            $html[] = $separator;
            $html[] = '<span>' . humanize($controller) . '</span>';
        endif;
            
        $html[] = '</div>';
        
        return implode($html);
    }
}
