<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class detail_controller extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
	{
		parent::__construct();		
		$this->load->model('maintenance_model');
		$this->load->model('access');
		date_default_timezone_set('Asia/Manila');

	   	if(empty($this->session->userdata("EmployeeNumber")) || $this->session->userdata("logged_in") == 0)
	   	{
	      $DateNow = date("Y-m-d H:i:s");
	     	$this->session->set_flashdata('logout','Account successfully logged out.'); 
	      $data = array(
	      	'Description' => 'Session timed out.'
	      	, 'DateCreated' => $DateNow
	      	, 'CreatedBy' => $this->session->userdata('EmployeeNumber')
	      );
	      $this->access->audit($data);
	      $loginSession = array(
	        'logged_in' => 0,
	      );
	   		redirect(site_url());
	   	}
	}

	function Users()
	{
		$result = $this->maintenance_model->getAllUsers();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['EmployeeNumber']);
		}
		echo json_encode($result);
	}

}
