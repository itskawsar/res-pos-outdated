<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**/
class User extends CI_Controller {

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
		if (!$this->site_sentry->is_logged_in())
			redirect('login');			
		$this->load->model('user/user_model');
		$user['query'] = $this->user_model->list_users();
		$user['body'] = $this->load->view('user/user_list', $user, TRUE);
		$this->load->view('main', $user);
	}

	function edit() {
		$this->load->model('user/user_model');
		$user['edit'] = $this->user_model->list_one($this->uri->segment(3));
		$user['query'] = $this->user_model->list_users();
		
		$user['dest_type'] = array('pos' => 'pos', 'palm' => 'palm');
		
		foreach(directory_map('../templates/', TRUE) as $tmp) 
			$templates[$tmp]= $tmp;

		$user['template'] = $templates;

		$user['body'] = $this->load->view('user/user_list', $user, TRUE);
		$this->load->view('main', $user);		
	}
	
	function newUser() {
		$this->load->model('user/user_model');
		$user['newuser'] = 'newuser';		
		$user['query'] = $this->user_model->list_users();	

		$user['dest_type'] = array('pos' => 'pos', 'palm' => 'palm');		
		
		foreach(directory_map('../templates/', TRUE) as $tmp) 
			$templates[$tmp]= $tmp;

		$user['template'] = $templates;		
		
		$user['body'] = $this->load->view('user/user_list', $user, TRUE);
		$this->load->view('main', $user);		
	}

	function save() {
		if( isset($_POST['password']) && $_POST['password'] == '') 
			$_POST['password'] = $_POST['oldpass'];
		elseif( isset($_POST['password']) && $_POST['password'] != '')
			$_POST['password'] = md5($_POST['password']);			
			
		if( isset($_POST['waiter']) && $_POST['waiter'] == 'waiter') 
			$_POST['level'] = '515';
		elseif( isset($_POST['administrator']) && $_POST['administrator'] == 'administrator')
			$_POST['level'] = '1022';
		
		if(isset($_POST['oldpass'])) unset($_POST['oldpass']);		
		if(isset($_POST['waiter'])) unset($_POST['waiter']);			
		if(isset($_POST['administrator'])) unset($_POST['administrator']);
		
		$this->db->where('id',$_POST['id']);
		$this->db->update('users', $_POST);

		redirect('user');
	}
	
	function addnew() {
		if( isset($_POST['password']) && $_POST['password'] != '')
			$_POST['password'] = md5($_POST['password']);			
			
		if( isset($_POST['waiter']) && $_POST['waiter'] == 'waiter') 
			$_POST['level'] = '515';
		elseif( isset($_POST['administrator']) && $_POST['administrator'] == 'administrator')
			$_POST['level'] = '1022';
		
		$_POST['language'] = 'sq';
			
		if(isset($_POST['oldpass'])) unset($_POST['oldpass']);		
		if(isset($_POST['waiter'])) unset($_POST['waiter']);			
		if(isset($_POST['administrator'])) unset($_POST['administrator']);
		
		$this->db->insert('users', $_POST);
		
		redirect('user');		
	}
	
	function delete() {
		$query = array('deleted' => 1);
		$id = $this->uri->segment(3);
		$this->db->where('id',$id);
		$this->db->update('users', $query);

		redirect('user');
	}	
}

?>