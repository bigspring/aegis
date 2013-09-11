<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * HTML Element Filter Class based on URI vars
 * - category & tag filters, table 
 * This class provides additional DB functionality
 * @author Jon Martin <jon@bigspring.co.uk>
 * @version 1.0
 * @package codigniter libraries
 */
class EX_Filter extends CI_Uri
{
    /**
    * Returns a string of category IDs separated by a hypen for passing arrays of categories through the URL
    * @param array URI segments from $this->uri->uri_to_assoc();
    * @return $string
    */
    function getCategoryFilter($segments)
    {
        $categories = null;
        
        if(array_key_exists('category', $segments))
            $categories = explode('-', $segments['category']);
              
        return $categories;    
    }

    /**
    * Returns a string of tag IDs separated by a hypen for passing arrays of tag through the URL
    * @param array URI segments from $this->uri->uri_to_assoc();
    * @return $string
    */
    function getTagFilter($segments)
    {
        $tags = null;
        
        if(array_key_exists('tag', $segments))
            $tags = explode('-', $segments['tag']);

        return $tags;    
    }

    /**
    * Returns a URI string for a category filter selector, based on current URI variables
    * Accounts for page, tag, category & search URL vars
    * @param int category id for the current category
    * @return $string uri
    */    
    function getCategoryFilterLinks($categoryid = null)
    {
        $ci =& get_instance();
        $segments = $this->uri_to_assoc();
        $search = $ci->input->get('search');
        
        if(array_key_exists('page', $segments) == true)
            unset($segments['page']);
            
        if(array_key_exists('tag', $segments) == true)
            unset($segments['tag']);
            
        // added this so users can only select one category at a time
        if(array_key_exists('category', $segments) == true)
            unset($segments['category']);
        
        
        if(array_key_exists('category', $segments) == true):
            $uripos = array_search($categoryid, explode('-', $segments['category']));
        
            if($categoryid != null && !in_array($categoryid, explode('-', $segments['category']))):
                // if we have segments and a categoryid, append the categoryid to the category part of the array
                $categories = $segments['category'] . '-' . $categoryid;
                $segments['category'] = $categories;
            elseif($categoryid != null && in_array($categoryid, explode('-', $segments['category']))):
                // if the category id already exists in the string, we must remove it as the user has deselected this category
                if(strlen($segments['category']) > 1 && $uripos > 0)
                    // if the string is longer than 1 and the category ID is not the first item
                    $segments['category'] = str_replace('-' . $categoryid, '', $segments['category']);
                elseif(strlen($segments['category']) > 1 && $uripos == 0)
                    // if the string is longer than 1 and the category ID is the first item
                    $segments['category'] = str_replace($categoryid . '-', '', $segments['category']);
                else
                    // otherwise we must have only one category defined and therefore remove the category param
                    unset($segments['category']);
            endif;
        elseif($categoryid != null):
                // else just set the category part of the array to the categoryid
                $segments['category'] = $categoryid;
        endif;
        
        $uri = $this->assoc_to_uri($segments);
        if($search != '')
            $uri .=   '?search=' . $search;
        
        return $uri;  
    }

    /**
    * Returns a URI string for a tag filter selector, based on current URI variables
    * Accounts for page, tag, category & search URL vars
    * @param int tag id for the current tag
    * @return $string uri
    */     
    function getTagFilterLinks($tagid = null)
    {
        $ci =& get_instance();
        $segments = $this->uri_to_assoc();
        $search = $ci->input->get('search');
        
        if(array_key_exists('page', $segments) == true)
            unset($segments['page']);
        
        
        if(array_key_exists('tag', $segments) == true):
            $uripos = array_search($tagid, explode('-', $segments['tag']));
        
            if($tagid != null && !in_array($tagid, explode('-', $segments['tag']))):
                // if we have segments and a tagid, append the tagid to the tag part of the array
                $tags = $segments['tag'] . '-' . $tagid;
                $segments['tag'] = $tags;
            elseif($tagid != null && in_array($tagid, explode('-', $segments['tag']))):
                // if the tag id already exists in the string, we must remove it as the user has deselected this tag
                if(strpos($segments['tag'], '-') != false && $uripos > 0)
                    // if the string has multiple tags and the tag ID is not the first item
                    $segments['tag'] = str_replace('-' . $tagid, '', $segments['tag']);
                elseif(strpos($segments['tag'], '-') != false && $uripos == 0)
                    // if the has mutiple ids and the tag ID is the first item
                    $segments['tag'] = str_replace($tagid . '-', '', $segments['tag']);
                else
                    // otherwise we must have only one tag defined and therefore remove the tag param
                    unset($segments['tag']);
            endif;
        elseif($tagid != null):
                // else just set the tag part of the array to the tagid
                $segments['tag'] = $tagid;
        endif;
        
        $uri = $this->assoc_to_uri($segments);  
        if($search != '')
            $uri .=   '?search=' . $search;
            
        return $uri;  
    }
    
    /**
    * Generates <a> links for clearing the current category URI filter but leaving all others in place
    * @return $string uri
    */
    function getCategoryClearLink()
    {
        $segments = $this->uri_to_assoc();
        
        if(array_key_exists('category', $segments))
            unset($segments['category']);

        $uri = $this->assoc_to_uri($segments);
        
        return $uri;           
    }

    /**
    * Generates <a> links for clearing the current category URI filter but leaving all others in place
    * @return string $uri
    */
    function getTagClearLink()
    {
        $segments = $this->uri_to_assoc();
        
        if(array_key_exists('tag', $segments))
            unset($segments['tag']);
            
        $uri = $this->assoc_to_uri($segments);
        
        return $uri;          
    }
    
    /**
    * Generates a "selected" CSS hook for the currently selected category
    * @param int $categoryid optional the categoryid to show as selected
    * @return string $selected a string saying "selected" for use as CSS hook
    */
    function checkCategorySelected($categoryid = null)
    {
        $segments = $this->uri_to_assoc();
        
        if(array_key_exists('category', $segments) && in_array($categoryid, explode('-', $segments['category'])))
            return 'selected';
        else
            return '';  
    }

    /**
    * Generates a "selected" CSS hook for the currently selected tag
    * @param int $tagid optional the tagid to show as selected
    * @return string $selected a string saying "selected" for use as CSS hook
    */    
    function checkTagSelected($tagid = null)
    {
        $segments = $this->uri_to_assoc();
        
        if(array_key_exists('tag', $segments) && in_array($tagid, explode('-', $segments['tag'])))
            return 'selected';
        else
            return null;  
    }

    /**
    * Generates <a> link for table column headers to allow ordering by clicking on the title
    * @param string $col name of the column
    * @param string $name the column title to be displayed
    * @return string $selected a string saying "selected" for use as CSS hook
    */    
    function getColumnHeader($col, $name)
    {
        $segments = $this->uri_to_assoc();
        
        if(array_key_exists('order', $segments) && $segments['order'] == 'ASC' && in_array($col, $segments) && $segments['sort'] == $col)
            $segments['order'] = 'DESC';
        else
            $segments['order'] = 'ASC';

        $segments['sort'] = $col;            
            
        return '<a href="'. site_url($this->assoc_to_uri($segments)) .'" >' . $name . '</a>';
    }

    /**
    * Prepare the action element for a search form based on the current uri
    * @param string $controller the currnet controller
    * @param string $name the column title to be displayed
    * @return string $selected a string saying "selected" for use as CSS hook
    */       
    function prepareSearchFormAction($controller)
    {
        $segments = $this->uri_to_assoc(1);
        $segments[$controller] = 'search';
        $uri = $this->assoc_to_uri($segments);
        
        return site_url($uri);
    }

    /**
    * Returns a URI string excluding the current page URI var
    * @param array $segments
    * @return string $uri
    */           
    function getPaginationSegments($segments = null)
    {
        $segments = $this->uri_to_assoc();
        
        if(array_key_exists('page', $segments))
            unset($segments['page']);

        return $this->assoc_to_uri($segments);
    }

    /**
    * Return the offset parameter from the current uri 
    * @param string $segments optional - the uri to find the offset in
    * @return string $offset
    */       
    function getOffset($segments = null)
    {
        $segments = $this->uri_to_assoc();
        $offset = 0;
       
        if(array_key_exists('page', $segments))
            $offset = $segments['page'];
        
        return $offset;  
    }

    /**
    * Returns the page number based on the current page URI parm
    * @param string $col name of the column
    * @param string $name the column title to be displayed
    * @return string $selected a string saying "selected" for use as CSS hook
    */       
    function getPageKey($segments = null)
    {
        $segments = $this->uri_to_assoc();
        
        $counter = 0;
        foreach($segments as $key => $value)
        {
            $counter++;
            if($key == 'page')
            {
                $counter = ($counter * 2) + 2;
                return $counter;
            }
        }
    }            
}