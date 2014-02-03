<?php/** * User_model DataMapper Model * * Use this basic model as a user_model for creating new models. * It is not recommended that you include this file with your application, * especially if you use a User_model library (as the classes may collide). * * To use: * 1) Copy this file to the lowercase name of your new model. * 2) Find-and-replace (case-sensitive) 'User_model' with 'Your_model' * 3) Find-and-replace (case-sensitive) 'user_model' with 'your_model' * 4) Find-and-replace (case-sensitive) 'user_models' with 'your_models' * 5) Edit the file as desired. * * @license		MIT License * @category	Models * @author		Phil DeJarnett * @link		http://www.overzealous.com */class User extends DataMapper {	var $has_one = array('user_group');	var $has_many = array('user_login');	var $validation = array(			'email' => array(				// example is required, and cannot be more than 120 characters long.				'rules' => array('trim', 'required', 'max_length' => 100, 'valid_email', 'is_unique[zlm2012_users.email]'),				'label' => 'email'				),			'firstname' => array(				'rules' => array('trim', 'required', 'max_length' => 100),
				'label' => 'first name'				),			'lastname' => array(				'rules' => array('trim', 'required', 'max_length' => 100),
				'label' => 'last name'				),			'password' => array(
				'rules' => array('required', 'min_length' => 6, 'max_length' => 50, 'encrypt'),
				'label' => 'password'
				),			'confirmpassword' => array(
				'rules' => array('min_length' => 6, 'max_length' => 50, 'encrypt', 'matches' => 'password'),
				'label' => 'confirm password'
				),			'group_id' => array(
					'rules' => array('numeric'),
					'label' => 'group'
			)	);	// --------------------------------------------------------------------	// Default Ordering	//   Uncomment this to always sort by 'name', then by	//   id descending (unless overridden)	// --------------------------------------------------------------------	var $default_order_by = array('lastname' => 'asc');	// --------------------------------------------------------------------	/**	 * Constructor: calls parent constructor	 */    function __construct($id = NULL)	{		parent::__construct($id);    }	// --------------------------------------------------------------------	// Post Model Initialisation	//   Add your own custom initialisation code to the Model	// The parameter indicates if the current config was loaded from cache or not	// --------------------------------------------------------------------	function post_model_init($from_cache = FALSE)	{	}		/**	 * Custom callback for enrypting passwords	 * @param string $field	 */
	public function _encrypt($field, $value) // optional second parameter is not used
	{				// Don't encrypt an empty string
		if (!empty($this->{$field}))
		{										$this->{$field} = $this->salt($this->{$field});		}
	}		/**
	 * @author Adam Griffiths
	 * @param string
	 * @return string
	 *
	 * Uses the encryption key set in application/config/config.php to salt the password passed.
	 */
	public function salt($password)
	{
		return hash("haval256,5", $this->config->item('encryption_key') . $password);
	}		/**	 * Checks whether the given username has a valid and enabled account	 * @param string $username	 * @param string $field_type	 */	public function login_check($username, $field_type)
	{        $user = new user();        $user = $user->where(array($field_type => $username, 'enabled' => 1))->get();			
		return $user;
	}			/**	 * Returns the user's full name based on their ID	 * @param int $userid	 */	public function get_fullname($id = null)	{		// if we've been provided an ID, return the full name for that user		if($id)		{			$user = new user();			$user = $user->get_where(array('id' => $id));			$fullname = $user->firstname . ' ' . $user->lastname;		} 		else // else return the current user		{			$fullname = $this->firstname . ' ' . $this->lastname;		}					return $fullname;	}		/**	 * Returns the group description for a user	 * @param int $value	 */	public function get_group($id = null)	{		if($id)		{			$user = new user();
			$user = $user->get_where(array('id' => $id));			$groupid = $user->group_id;		}		else		{			$groupid = $this->group_id;		}				$group = new user_group();		$group = $group->get_where(array('id' => $groupid));				return $group->title;	}		/**	 * Returns the last login datetime for a user	 * @param unknown_type $value	 */	public function get_last_login()	{		$user_login = new User_login();		$user_login->where(array('user_id' => $this->id))->order_by('login_datetime', 'desc')->limit(1)->get();				return format_date($user_login->login_datetime, true);	}        /**     * Verifies a user's email address based on the token provided     */    public function verify_user($user_id = null)    {        if(!$user_id)        {            $this->token = '';            $this->enabled = 1;            $this->save();                            return true;        }        else         {
            $user = new user($user_id);            $user->token = '';            $user->enabled = 1;            $user->save();                            return true;            
        }                return false;    }        public function generate_token()    {        $length = 20;        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';        $token = "";                    for ($i = 0; $i < $length; $i++)        {            $token .= $characters[mt_rand(0, strlen($characters)-1)];        }                return $token;    }        public function get_avatar($size = null, $id = null)    {            }        /**     * Sends a verification email to the user     */    public function send_verification()    {        $CI =& get_instance();                    $data = array(                'subject' => 'Thanks for registering',                'view' => 'user/verify/verify-email'        );                $this->handle_verification($data);                $CI->session->set_userdata('verify_send', false);                return true;    }        public function resend_verification()    {        $data = array(                'subject' => 'Thanks for registering',                'view' => 'user/verify/verify-email'        );                $this->handle_verification($data);                return true;    }        public function handle_verification($data = array())    {        $CI =& get_instance();                    // send confirmation email        $CI->load->library('email');        $CI->email->from('hello@bigspring.com', 'bigspring');        $CI->email->to($this->email);                        $CI->email->subject($data['subject']);                $url = site_url('user/verify/verification/' . $this->token);                $message = $CI->load->view($data['view'], array('url' => $url), true);        $CI->email->message($message);                         $CI->email->send();                return true;    }}/* End of file user_model.php *//* Location: ./application/models/user_model.php */