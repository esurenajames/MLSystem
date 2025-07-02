<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class access_controller extends CI_Controller {

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
		$this->load->model('access_model');
  	date_default_timezone_set('Asia/Manila');
	}

	function accessCheck()
	{
    $DateNow = date("Y-m-d H:i:s");
		if($this->uri->segment(3) == 1) // login
		{
			$userDetails = array(
				'username' => $_POST["Username"]
				, 'password' => $_POST["Password"]
			);

			$result = $this->access_model->checkUser($userDetails);
			if($result == 1) // emploee
			{
				$result = $this->access_model->getUserData($userDetails);
	      $loginSession = array(
	        'EmployeeNumber' => $result['EmployeeNumber'],
	        'Name' => $result['Name'],
	        'EmployeeId' => $result['EmployeeId'],
	        'Password' => $result['Password'],
	        'RoleId' => $result['RoleId'],
	        'IsNew' => $result['IsNew'],
        	'logged_in' => 1,
	      );
	      $this->session->set_userdata($loginSession);
    		$EmployeeNumber = $this->session->userdata('EmployeeNumber');
	      // audits
	        $auditDetail = 'Logged in.';
	        $insertData = array(
	          'Description' => $auditDetail,
	          'CreatedBy'   => $EmployeeNumber,
	        );
	        $auditTable = 'R_Logs';
	        $this->maintenance_model->insertFunction($insertData, $auditTable);

				redirect('home/Dashboard');
			}
			else if($result == 2) // student
			{
				$result2 = $this->access_model->getStudentData($userDetails);
	      if($result2 != 0)
	    	{
		      $loginSession = array(
		        'EmployeeNumber' => $result2['StudentNumber'],
		        'Name' => $result2['StudentName'],
		        'EmployeeId' => $result2['Id'],
		        'Password' => $result2['Password'],
		        'RoleId' => $result2['RoleId'],
		        'IsNew' => $result2['IsNew'],
	        	'logged_in' => 1,
		      );
		      $this->session->set_userdata($loginSession);
	    		$EmployeeNumber = $this->session->userdata('EmployeeNumber');
		      // audits
		        $auditDetail = 'Logged in.';
		        $insertData = array(
		          'Description' => $auditDetail,
		          'CreatedBy'   => $EmployeeNumber,
		        );
		        $auditTable = 'R_Logs';
		        $this->maintenance_model->insertFunction($insertData, $auditTable);

					redirect('home/Dashboard');
	    	}
	    	else
	    	{
		     	$this->session->set_flashdata('error','User not found.'); 
					redirect('');
	    	}
			}
			else
			{
	     	$this->session->set_flashdata('error','User not found.'); 
				redirect('');
			}
		}
		else if($this->uri->segment(3) == 2) // forget password
		{

		}
		else /*if($this->uri->segment(3) == 3)*/ // log out
		{
			if($this->uri->segment(3) == 3)
			{
    		$EmployeeNumber = $this->session->userdata('EmployeeNumber');
     		$this->session->set_flashdata('logout','Account successfully logged out.'); 
	      // audits
	        $auditDetail = 'Logged out.';
	        $insertData = array(
	          'Description' => $auditDetail,
	          'CreatedBy'   => $EmployeeNumber,
	        );
	        $auditTable = 'R_Logs';
	        $this->maintenance_model->insertFunction($insertData, $auditTable);
		      $loginSession = array(
		        'logged_in' => 0,
		      );
		      $this->session->set_userdata($loginSession);
		   		redirect(site_url());
	        session_destroy();
			}
			else if($this->session->userdata('EmployeeNumber') === '' || !empty($this->session->userdata('EmployeeNumber')))
			{
	     	$this->session->set_flashdata('error','Session expired.'); 
	   		redirect(site_url());
        session_destroy();
			}
		}
	}
	
	public function index()
	{
		$data['questions'] = $this->admin_model->getQuestions();
		$this->load->view('login', $data);
	}

  function ResetPassword()
  {
    $totalCorrect = 0;
    $DateNow = date("Y-m-d H:i:s");
    // check if empNo is in r_employee
      $result = $this->admin_model->checkExisitingEmployee($_POST['EmployeeNumber']);
      if($result > 0) // exisitng employee
      {
        // check questions with answers
          $result1 = $this->admin_model->checkSecurity($_POST['Question1'], $_POST['Answer1'], 1);
          if($result1 > 0) // exisitng employee
          {
            $totalCorrect = $totalCorrect + 1;
          }
          $result2 = $this->admin_model->checkSecurity($_POST['Question2'], $_POST['Answer2'], 2);
          if($result2 > 0) // exisitng employee
          {
            $totalCorrect = $totalCorrect + 1;
          }
          $result3 = $this->admin_model->checkSecurity($_POST['Question3'], $_POST['Answer3'], 3);
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
			      $table = 'r_users';
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
