<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**/
class Category extends CI_Controller {

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
		$this->load->model('category/category_model');
		$categories['query'] = $this->category_model->category_list();
		$categories['body'] = $this->load->view('category/category_list', $categories, TRUE);
		$this->load->view('main', $categories);
	}

	function edit() {
		$this->load->model('category/category_model');
		$categories['edit'] = $this->category_model->list_one($this->uri->segment(3));
		$categories['query'] = $this->category_model->category_list();
		$categories['body'] = $this->load->view('category/category_list', $categories, TRUE);
		$this->load->view('main', $categories);		
	}
	
	function newCat() {
		$this->load->model('category/category_model');
		$categories['newcat'] = 'newcategory';		
		$categories['query'] = $this->category_model->category_list();
		$categories['body'] = $this->load->view('category/category_list', $categories, TRUE);
		$this->load->view('main', $categories);		
	}

	function save() {
		if($_FILES['image']['tmp_name']) {
			move_uploaded_file($_FILES['image']['tmp_name'],'../images/categories/' . $_FILES['image']['name']);
			$_POST['image'] = '/images/kategorite/' . $_FILES['image']['name'];
		} 
		$this->db->where('id',$_POST['id']);
		$this->db->update('categories', $_POST);

		redirect('category');
	}
	
	function addnew() {
		if($_FILES['image']['tmp_name']) {
			move_uploaded_file($_FILES['image']['tmp_name'],'../images/categories/' . $_FILES['image']['name']);
			$_POST['image'] = '/images/kategorite/' . $_FILES['image']['name'];
		} 
		$this->db->insert('categories', $_POST);

		redirect('category');		
	}
	
	function delete() {
		$query = array('deleted' => 1);
		$id = $this->uri->segment(3);
		$this->db->where('id',$id);
		$this->db->update('categories', $query);

		redirect('category');
	}	
}

?>