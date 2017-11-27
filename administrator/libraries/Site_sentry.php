<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**/


class Site_sentry {

	function __construct() {
		$this->obj =& get_instance();
	}

	function is_logged_in() {
		if ($this->obj->session) {
			
				// var_dump($this->obj->session->userdata('logged_in'));
				// exit;
			if ($this->obj->session->userdata('logged_in')) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	function login_routine() {

		$password = $this->obj->input->post('password');
		$username = $this->obj->input->post('username');

		$query = $this->obj->db->get_where('users',array('name'=>$username,'password'=>md5($password)));

		if($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$id = $row->id;
				$credentials = array('user_id' => $id, 'logged_in' => '1');
				$this->obj->session->set_userdata($credentials);

				var_dump($this->obj->session->userdata('logged_in'));
				exit;
				return TRUE;			
			}
		} else {
			return FALSE;
		}
	}
}
?>