<?php
    /**
    * Returns a tick or cross depending on the passed variable
    * @param int $status the current status
    * @return string $html <img> tag for the status icon
    */
    function pubicon($status)  
    {
        if($status == 1)
            $icon = 'accept.png';
        if($status == 0)
            $icon = 'cancel.png';
        $html = '<img src="' . site_url('images/icons/') . $icon.'"/>';
        return $html;
    }
    
    /**
     * Returns an indexed array for a passing to a form_dropdown yes no select list
     * @return array
     */
    function get_yes_no_options($order = 'asc')
    {
    	return $order == 'asc' ? array(1 => 'Yes', 0 => 'No') : array(0 => 'No', 1 => 'Yes');	
    }
    
    /**
     * Returns either yes or no as text depending on whether a 1 or a 0 is passed
     * @param int $flag
     * @return string
     */
    function get_yes_no_text($flag)
    {
    	return $flag == 1 ? 'Yes' : 'No';
    }

    /**
    * Accepts a date and returns a UK format date, optional time parmaeter
    * @param string $date the date to be displayed
    * @param string $time if not false, a time will be displayed too
    * @return string $datestr
    */    
    function format_date($date, $time = false)
    {
        $datestr = '';
        
        if($time != false)
            $datestring = 'd/m/Y H:i';
        else
            $datestring = 'd/m/Y';
        
        if($date != null && $date != '0000-00-00 00:00:00' && $date != '0000-00-00')
            $datestr = date_format(date_create($date), $datestring);
        
        return $datestr;
    }
    
    /**
     * Generates the src element for an image tag
     * @param string $type the file type used to lookup the directory in upload.php config file
     * @param string $link the link to the actual file
     * @param string $size the image size required
     */
    function get_image($type, $link, $size = 'square-medium')
    {
    	if(is_null($link) || $link == '') // if the link provided is null or blank, use the default link 
    	{ 	
    		$link = 'default-300x300.jpg';
    		return site_url('assets/uploads/'. $link); 
    	}
    	else
    	{
    		$CI =& get_instance();
    		
    		$CI->config->load('images');
    		$CI->config->load('upload');
    		$sizes = $CI->config->item('sizes');
    		
    		if(array_key_exists($size, $sizes)) // check if the supplied size exists
    			$size = $sizes[$size];
    		
    		$directory = $CI->config->item($type); // get the upload directory for this image type\
    		$extension = strstr($link, '.'); // get the extension of the file
    		$filename = str_replace($extension, '', $link); // get the filename
    		
    		if(is_array($size) && !is_null($link)) // if we have a size, create the correct link to the file
    			$link = $filename . '-' . $size['width'] . 'x' . $size['height'] . $extension;
    		
    		return site_url('assets/uploads/' . $directory . '/' . $link);
    	}
    }

    /**
     * Tests a submitted file object to see if it is an image or not
     * @param object $file
     */
    function is_image($file)
    {
        $image_types = array('image/jpeg', 'image/png', 'image/gif', 'image/pjpeg');
        
        if(in_array($file->type, $image_types))
        {  
           return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Generates the src element for a thumbnail uploaded through jquery file uploads
     * @param string $link url to retrieve the file
     */
    function get_thumbnail($file)
    {
        $image_types = array('image/jpeg', 'image/png', 'image/gif', 'image/pjpeg');

        if(is_image($file))
        {   
           return $file->thumbnail_url;
        }
        elseif($file->type == 'application/pdf') // if it's a PDF 
        {
           //echo site_url('assets/images/filetypes/file-icon-pdf.jpg');
           return site_url('assets/images/filetypes/file-icon-pdf.jpg');
        }
        else // anything else
        {
           return site_url('assets/images/filetypes/file-icon-generic.jpg'); 
        }
    }
    
    /**
     * Generates the src element for an avatar uploaded through jquery file uploads
     * @param string $link url to retrieve the file
     */
    function get_avatar($user)
    {
        if(is_null($user->avatar) ||$user->avatar == '') // if the link provided is null or blank, use the default link 
        {   
            return site_url('assets/images/default.jpg'); 
        }
        else
        {
            return $user->avatar; 
        }
    }
        
    function render_scripts()
    {
    	$html = array();
        $CI =& get_instance();
        
        if(!$CI->scripts)
            return false;
    	
        foreach($CI->scripts AS $script)
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
    
    function render_styles()
    {
        $html = array();
        $CI =& get_instance();
        
        if(!$CI->styles)
            return false;
        
        foreach($CI->styles AS $style)
        {
            $html[] = '<link href="'. site_url('assets/' . $style) .'" rel="stylesheet" type="text/css">';    
        }   
        
        return $html ? implode("\n", $html) : false;
    }
    
    /**
     * https://github.com/porquero/multifile-array-ci-helper
     */
    function multifile_array()
    {
        if(count($_FILES) == 0)
            return;
       
        $files = array();
        $all_files = $_FILES['file']['name'];
        $i = 0;

        foreach ($all_files as $k => $filename) {
            
            if(is_array($filename)) // if yes, it must be a deep array so we need to iterate
            {
                foreach($filename AS $key => $file)
                {
                    if(is_numeric($k)) // if we've been provided with a numeric key, therefore not placeholder content
                    {
                        //echo 'k: ' . $k . '<br/>';
                        //echo 'key: ' . $key . '<br/>';
                            
                        $files[$i]['name'] = $file;
                        $files[$i]['type'] = $_FILES['file']['type'][$k][$key];
                        next($_FILES['file']['type']);
                        $files[$i]['tmp_name'] = $_FILES['file']['tmp_name'][$k][$key];
                        next($_FILES['file']['tmp_name']);
                        $files[$i]['error'] = $_FILES['file']['error'][$k][$key];
                        next($_FILES['file']['error']);
                        $files[$i]['size'] = $_FILES['file']['size'][$k][$key];
                        next($_FILES['file']['size']);
                        $i++;
                    }    
                } 
            }
            else
            {
                $files[$k]['name'] = $filename;
                $files[$k]['type'] = current($_FILES['file']['type']);
                next($_FILES['file']['type']);
                $files[$k]['tmp_name'] = current($_FILES['file']['tmp_name']);
                next($_FILES['file']['tmp_name']);
                $files[$k]['error'] = current($_FILES['file']['error']);
                next($_FILES['file']['error']);
                $files[$k]['size'] = current($_FILES['file']['size']);
                next($_FILES['file']['size']);
            }   
        }
    
        $_FILES = $files;
        
        return $files;
    }

    /**
     * Renders the body classes as a
     * @TODO Needs fixing as doesn't have scope over $this->body_classes
     */
    function body_classes()
    {
        $CI =& get_instance();

        if(!$CI->body_classes) return false;
        return implode(" ", $CI->body_classes);
    }