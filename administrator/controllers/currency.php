<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**/
class Currency extends CI_Controller {

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
		$this->load->model('currency/currency_model');
		$currency['query'] = $this->currency_model->currency_list();
		$currency['body'] = $this->load->view('currency/currency_list', $currency, TRUE);
		$this->load->view('main', $currency);
	}

	function edit() {
		$this->load->model('currency/currency_model');
		$currency['edit'] = $this->currency_model->list_one($this->uri->segment(3));
		$currency['query'] = $this->currency_model->currency_list();
		$currency['body'] = $this->load->view('currency/currency_list', $currency, TRUE);
		$this->load->view('main', $currency);		
	}
	
	function newCurrency() {
		$this->load->model('currency/currency_model');
		$currency['newcurrency'] = 'newcurrency';		
		$currency['query'] = $this->currency_model->currency_list();
		$currency['body'] = $this->load->view('currency/currency_list', $currency, TRUE);
		$this->load->view('main', $currency);		
	}

	function save() {
		$this->db->where('id',$_POST['id']);
		$this->db->update('currency', $_POST);

		redirect('currency');
	}
	
	function addnew() {
		$this->db->insert('currenc', $_POST);

		redirect('currency');		
	}
	
	function delete() {
		$id = $this->uri->segment(3);
		$this->db->where('id',$id);
		$this->db->delete('currency');

		redirect('currency');
	}	
}

?>