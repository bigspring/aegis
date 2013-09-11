<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * DB Helper Class
 * 
 * This class provides additional DB functionality
 * @author Jon Martin <jon@bigspring.co.uk>
 * @version 1.0
 * @package codigniter libraries
 */
class EX_Dbhelper 
{
    /**
    * Clean form fields
    * @param array $data contains the form data
    * @param array $fields the array keys to be unset
    * @return array
    */
    public function cleanFormFields($data, $fields)
    {
        foreach($fields AS $key)
        {
            unset($data[$key]);
        }
        return $data;
    }

    /**
    * Prepares the whereor statement for a form based search
    * @param array $data contains the form data
    * @param array $fields the array keys to be unset
    * @return array
    */    
    function prepareSearch($fields, $search)
    {
        // if we're using a get based form, replace %20 used in the url with a space
        $search = str_replace('%20', ' ', $search);
        $search = explode(' ', $search);
        $searchstring = '(';
        foreach($search AS $term):
            foreach($fields AS $field):
                $whereor[] = ' ' . $field . ' LIKE "%' . $term . '%" OR';
            endforeach;
        endforeach;            
        
        $searchstring .= implode('', $whereor);
        
        // remove the last 3 letters and close the brackets( OR)
        $searchstring = substr($searchstring, 0, strlen($searchstring) - 3) . ')';
        return $searchstring;
    }    
}
