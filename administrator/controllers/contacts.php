<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**/
class Contacts extends CI_Controller {

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
		$this->load->model('contacts/contacts_model');
		$contacts['query'] = $this->contacts_model->contacts_list();
		$contacts['body'] = $this->load->view('contacts/contacts_list', $contacts, TRUE);
		$this->load->view('main', $contacts);
	}
	
	function edit() {
		$this->load->model('contacts/contacts_model');
		$contacts['edit'] = $this->contacts_model->list_one($this->uri->segment(3));
		$contacts['query'] = $this->contacts_model->contacts_list();
		$contacts['conttype'] = $this->contacts_model->contacts_dropdown();		
		$contacts['body'] = $this->load->view('contacts/contacts_list', $contacts, TRUE);
		$this->load->view('main', $contacts);		
	}

	function newContact() {
		$this->load->model('contacts/contacts_model');
		$contacts['newcontact'] = 'newcontact';		
		$contacts['query'] = $this->contacts_model->contacts_list();
		$contacts['conttype'] = $this->contacts_model->contacts_dropdown();			
		$contacts['body'] = $this->load->view('contacts/contacts_list', $contacts, TRUE);
		$this->load->view('main', $contacts);		
	}

	function save() {
		$this->db->where('id',$_POST['id']);
		$this->db->update('account_mgmt_addressbook', $_POST);

		redirect('contacts');
	}
	
	function addnew() {
		$this->db->insert('account_mgmt_addressbook', $_POST);

		redirect('contacts');		
	}
	
	function delete() {
		$id = $this->uri->segment(3);
		$this->db->where('id', $id);
		$this->db->delete('account_mgmt_addressbook'); 
		redirect('contacts');
	}		
}
?>