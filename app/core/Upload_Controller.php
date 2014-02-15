<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Upload_Controller extends EX_Controller {

    public function __construct() 
    {
        parent::__construct();
    }
  
    public function _prepare_jquery_upload()
    {
        $this->styles[] = 'js/library/file-upload/css/jquery.fileupload-ui.css';
        $this->scripts[] = 'library/file-upload/js/vendor/jquery.ui.widget.js';
        $this->scripts[] = 'library/js-templates/tmpl.min.js';
        $this->scripts[] = 'library/load-image/load-image.min.js';
        $this->scripts[] = 'library/canvas-to-blob/canvas-to-blob.min.js';
        $this->scripts[] = 'library/bootstrap/bootstrap.min.js';
        $this->scripts[] = 'library/file-upload/js/jquery.iframe-transport.js';
        $this->scripts[] = 'library/file-upload/js/jquery.fileupload.js';
        $this->scripts[] = 'library/file-upload/js/jquery.fileupload-fp.js';
        $this->scripts[] = 'library/file-upload/js/jquery.fileupload-ui.js';
        $this->scripts[] = 'library/file-upload/js/locale.js';
    }
    
    public function upload($directory = false)
    {            
        $this->load->library('uploadhandler');
    }
    
    public function set_ids()
    {
        $file_data = array(
            'entity' => $this->input->get('entity'),
            'id' => $this->input->get('id')
        );
            
        $this->session->set_userdata('file_data', $file_data);
    }

    public function set_upload_path($path)
    {
        $this->session->set_userdata('upload_path', $path);
        echo $path;   
    }
}