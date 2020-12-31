<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LMS extends CI_Controller {

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
		$this->load->model('employee_model');
    	date_default_timezone_set('Asia/Manila');
	}

	function accessCheck()
	{
    $DateNow = date("Y-m-d H:i:s");
		if($_POST["btnProcess"] == 1) // login
		{
			$userDetails = array(
				'username' => $_POST["txtUsername"]
				, 'password' => $_POST["txtPassword"]
			);

			$result = $this->access->checkUser($userDetails);
			if($result > 0)
			{
				$result = $this->access->getUserData($userDetails);
	      $loginSession = array(
	        'EmployeeNumber' => $result[0]['EmployeeNumber'],
	        'Name' => $result[0]['Name'],
	        'Branch' => $result[0]['Branch'],
	        'BranchId' => $result[0]['BranchId'],
	        'EmployeeId' => $result[0]['EmployeeId'],
	        'Password' => $result[0]['Password'],
	        'ManagerId' => $result[0]['ManagerId'],
	        'IsManager' => $result[0]['isManager'],
        	'logged_in' => 1,
	      );
	      $this->session->set_userdata($loginSession);

	      $data = array(
	      	'Description' => 'Logged in.'
	      	, 'CreatedBy' => $_POST["txtUsername"]
	      );

	      $data2 = array(
	      	'Description' => 'Logged in.'
	      	, 'CreatedBy' => $_POST["txtUsername"]
	      	, 'EmployeeNumber' => $result[0]['EmployeeNumber']
	      );
	      $this->access->audit($data, 1);
	      $this->access->audit($data2, 2);

				redirect('home/Dashboard');
			}
			else
			{
	     	$this->session->set_flashdata('error','Incorrect user account'); 
				redirect('login');
			}
		}
		else if($_POST["btnProcess"] == 2) // forget password
		{

		}
		else /*if($_POST["btnProcess"] == 3)*/ // log out
		{
			if($this->session->userdata('EmployeeNumber') === '' || !empty($this->session->userdata('EmployeeNumber')))
			{
	     	$this->session->set_flashdata('error','Session expired.'); 
	   		redirect(site_url());
			}
			else
			{
	     	$this->session->set_flashdata('logout','Account successfully logged out.'); 
	      $data = array(
	      	'Description' => 'Logged out.'
	      	, 'CreatedBy' => $this->session->userdata('EmployeeNumber')
	      );

	      $data2 = array(
	      	'Description' => 'Logged out.'
	      	, 'CreatedBy' => $this->session->userdata('EmployeeNumber')
	      	, 'EmployeeNumber' => $this->session->userdata('EmployeeNumber')
	      );
	      $this->access->audit($data, 1);
	      $this->access->audit($data2, 2);
	      $loginSession = array(
	        'logged_in' => 0,
	      );
	      $this->session->set_userdata($loginSession);
	   		redirect(site_url());
			}
		}
	}
	
	public function index()
	{

		$data['securityQuestions'] = $this->employee_model->getSecurityQuestions();
		$this->load->view('login', $data);
	}

  function ResetPassword()
  {
    $totalCorrect = 0;
    $DateNow = date("Y-m-d H:i:s");
    // check if empNo is in r_employee
      $result = $this->employee_model->checkExisitingEmployee($_POST['EmployeeNumber']);
      if($result > 0) // exisitng employee
      {
        // check questions with answers
          $result1 = $this->employee_model->checkSecurity($_POST['Question1'], $_POST['Answer1'], 1);
          if($result1 > 0) // exisitng employee
          {
            $totalCorrect = $totalCorrect + 1;
          }
          $result2 = $this->employee_model->checkSecurity($_POST['Question2'], $_POST['Answer2'], 2);
          if($result2 > 0) // exisitng employee
          {
            $totalCorrect = $totalCorrect + 1;
          }
          $result3 = $this->employee_model->checkSecurity($_POST['Question3'], $_POST['Answer3'], 3);
          if($result3 > 0) // exisitng employee
          {
            $totalCorrect = $totalCorrect + 1;
          }

          if($totalCorrect == 3) // if correct lahat
          {
			      $set = array( 
			        'Password' => $_POST['EmployeeNumber'],
			        'IsNew' => 1
			      );

			      $condition = array( 
			        'EmployeeNumber' => $_POST['EmployeeNumber']
			      );
			      $table = 'r_userrole';
			      $this->maintenance_model->updateFunction1($set, $condition, $table);
	          // notification
	     				$this->session->set_flashdata('logout','Password successfully reset.');
	        
         		redirect();
          }
          else
          {
	          // notification
	     				$this->session->set_flashdata('error','Wrong/incomplete answers');
	        
         		redirect();
          }
      }
      else
      {
        // notification
   				$this->session->set_flashdata('error','Employee does not exist');
      
          redirect();
      }
  }
}
