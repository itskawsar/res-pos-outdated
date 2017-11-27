<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**/

class Main extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper(array('html','MY_url_helper','language'));			
		
		$language = $this->session->userdata('language');
		if($language == '' ) $language = 'english';
		$this->lang->load('smartrestaurant', $language);

	}

	function index() {
		if (!$this->site_sentry->is_logged_in())
			redirect('login');		
		$data['page_title'] = "Smart Restaurant";
		$this->load->view('main', $data);

	}

	function userinfo($userid) {
		$this->load->helper( 'gravatar' ); 
		$data['left_menu'] = $this->load->view('menu', '', TRUE);
		$this->load->view('main', $data);
	}
	
}
?>