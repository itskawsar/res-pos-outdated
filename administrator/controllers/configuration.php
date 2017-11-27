<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**/
class Configuration extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper(array('html','date','MY_url_helper','language'));		
		
		$language = $this->session->userdata('language');
		if($language == '' ) $language = 'english';
		$this->lang->load('smartrestaurant', $language);
				
		if($this->config->item('enable_app_debug'))
			$this->output->enable_profiler(TRUE);
	}
	
	function index() {
		if (!$this->site_sentry->is_logged_in())
			redirect('login');		
		$this->load->model('configuration/configuration_model');
		$configuration['query'] = $this->configuration_model->configuration_list();
		$configuration['body'] = $this->load->view('configuration/configuration_list', $configuration, TRUE);
		$this->load->view('main', $configuration);
	}
	
	function updateValue () {
		$data = array('value' => $_POST['value'] );
		$this->db->where('id', $_POST['value_id']);
		$this->db->update('conf',$data);
		if(isset($_POST['bool']) && $_POST['bool'] && $_POST['value'])
			print $_POST['value'] == 'win' ? 'Windows' : 'Linux'; 
		elseif(isset($_POST['bool']) && $_POST['bool'])
			print $_POST['value'] == '1' ? lang('yes') : lang('no');
		else
			print $_POST['value'];			
	}
	
	function timezoneList() {
		
		$zones = DateTimeZone::listIdentifiers();
		$zoneArray = array();
		foreach ($zones as $zone) {
			$zoneArray[$zone]= $zone;
		}		
		
		print json_encode($zoneArray);
		die();
			
	}
}
?>