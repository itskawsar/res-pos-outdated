<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**/
class Table extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper(array('html','MY_url_helper','language'));	
		
		$language = $this->session->userdata('language');
		if($language == '' ) $language = 'english';
		$this->lang->load('smartrestaurant', $language);		
		
		if($this->config->item('enable_app_debug'))			
			$this->output->enable_profiler(TRUE);
	}

	function index() {
		if (!$this->site_sentry->is_logged_in())
			redirect('login');			
		$this->load->model('table/table_model');
		$table['query'] = $this->table_model->table_list();
		$table['body'] = $this->load->view('table/table_list', $table, TRUE);
		$this->load->view('main', $table);
	}

	function edit() {
		$this->load->model('table/table_model');
		$table['edit'] = $this->table_model->list_one($this->uri->segment(3));
		$table['query'] = $this->table_model->table_list();
		$this->load->model('user/user_model');
		$table['users'] = $this->user_model->user_list();
		$table['body'] = $this->load->view('table/table_list', $table, TRUE);
		$this->load->view('main', $table);		
	}
	
	function newTable() {
		$this->load->model('table/table_model');
		$table['newtable'] = 'newtable';		
		$table['query'] = $this->table_model->table_list();
		$this->load->model('user/user_model');
		$table['users'] = $this->user_model->user_list();		
		$table['body'] = $this->load->view('table/table_list', $table, TRUE);
		$this->load->view('main', $table);		
	}

	function save() {
		$str = "";
		foreach($_POST['locktouser'] as $lockto )
			$str .= $lockto . "," ;
		
		$_POST['locktouser'] =  substr($str,0,-1);
				
		$this->db->where('id',$_POST['id']);
		$this->db->update('sources', $_POST);

		redirect('table');
	}
	
	function addnew() {
		$str = "";
		foreach($_POST['locktouser'] as $lockto )
			$str .= $lockto . "," ;
		
		$_POST['locktouser'] =  substr($str,0,-1);
		$this->db->insert('sources', $_POST);
		redirect('table');		
	}
	
	function delete() {
		$id = $this->uri->segment(3);
		$this->db->where('id',$id);
		$this->db->delete('sources');

		redirect('table');
	}	
}

?>