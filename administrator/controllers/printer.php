<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**/

class Printer extends CI_Controller {

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
		$this->load->model('printer/printer_model');
		$printer['query'] = $this->printer_model->printer_list();
		$printer['body'] = $this->load->view('printer/printer_list', $printer, TRUE);
		$this->load->view('main', $printer);
	}

	function edit() {
		$this->load->model('printer/printer_model');
		$printer['edit'] = $this->printer_model->list_one($this->uri->segment(3));
		$printer['query'] = $this->printer_model->printer_list();

		foreach(directory_map('../drivers/') as $tmp) 
			$drivers[substr($tmp, 0, -4)] = substr($tmp, 0, -4);

		$printer['driver'] = $drivers;
		
		foreach(directory_map('../templates/', TRUE) as $tmp) 
			$templates[$tmp]= $tmp;

		$printer['template'] = $templates;

		$printer['body'] = $this->load->view('printer/printer_list', $printer, TRUE);
		$this->load->view('main', $printer);		
	}
	
	function newPrinter() {
		$this->load->model('printer/printer_model');
		$printer['newprinter'] = 'newprinter';		
		$printer['query'] = $this->printer_model->printer_list();	
		
		foreach(directory_map('../drivers/') as $tmp) 
			$drivers[substr($tmp, 0, -4)] = substr($tmp, 0, -4);

		$printer['driver'] = $drivers;
		
		foreach(directory_map('../templates/', TRUE) as $tmp) 
			$templates[$tmp]= $tmp;

		$printer['template'] = $templates;		
		
		$printer['body'] = $this->load->view('printer/printer_list', $printer, TRUE);
		$this->load->view('main', $printer);		
	}

	function save() {				
		$this->db->where('id',$_POST['id']);
		$this->db->update('dests', $_POST);

		redirect('printer');
	}
	
	function addnew() {
		$this->db->insert('dests', $_POST);
		
		redirect('printer');		
	}
	
	function delete() {
		$query = array('deleted' => 1);
		$id = $this->uri->segment(3);
		$this->db->where('id',$id);
		$this->db->update('dests', $query);

		redirect('printer');
	}	
}

?>