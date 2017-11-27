<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**/
class Login extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper(array('html','directory','MY_url_helper','language'));		
		
		$language = $this->session->userdata('language');
		if($language == '' ) $language = 'english';
		$this->lang->load('smartrestaurant', $language);
		
		if($this->config->item('enable_app_debug'))
			$this->output->enable_profiler(TRUE);
	}

	function index() {
		$data['langDropDown'] = $this->doLanguageDropDown();
		
		if ($this->site_sentry->is_logged_in()) {
			$this->load->view('main');
		} else {
			$this->load->view('login/login',$data);
		}
	}
	
	function doLogin() {
		if(isset($_POST['language']))
			$this->session->set_userdata('language' , $_POST['language']);

		if($this->site_sentry->login_routine()) {
			redirect('main');
		} else {
			$login['message'] = "Login Failed";
			$login['langDropDown'] = $this->doLanguageDropDown();
			$this->load->view('login/login', $login);
		}
	}
	
	function doLogout() {
		if(isset($_POST['language']))
			$this->session->set_userdata('language' , $_POST['language']);
		
		$data['langDropDown'] = $this->doLanguageDropDown();
		
		$this->session->sess_destroy();
		$this->load->view('login/login',$data);
	}
	
	function doLanguageDropDown() {
		$map = directory_map(APPPATH .'language', TRUE);

		$dropDown = ''; 
		foreach($map as $id => $langName) {
			if($langName != 'index.html')
				$dropDown .= '<option value="' . $langName . '">' . $langName . '</option>';
		}
		
		return $dropDown;
	}
}
?>