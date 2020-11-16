<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class admin_controller extends CI_Controller {

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
		$this->load->model('admin_model');

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

	function getAuditLogs()
	{
		$result = $this->admin_model->getAuditLogs();
		echo json_encode($result);
	}

  public function getEmployees()
  {
    $json = [];
    if(!empty($this->input->get("q")))
    {
      $keyword = $this->input->get("q");
      $json = $this->admin_model->getEmployees($keyword);
    }
    echo json_encode($json);
  }

  public function getRoles()
  {
    $json = [];
    if(!empty($this->input->get("q")))
    {
      $keyword = $this->input->get("q");
      $json = $this->admin_model->getRoles($keyword);
    }
    echo json_encode($json);
  }

  function ResetPassword()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    if($this->uri->segment(3) == 1) // update temporary passowrd
    {
      // update password
        $set = array( 
          'Password' => $_POST['NewPassword']
          , 'DateUpdated' => $DateNow
          , 'UpdatedBy' => $EmployeeNumber
        );
        $condition = array( 
          'EmployeeNumber' => $EmployeeNumber
        );
        $table = 'R_UserRole';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // audits
        $auditDetail = 'Changed temporary password.';
        $insertData = array(
          'Description' => $auditDetail,
          'CreatedBy' => $EmployeeNumber,
          'DateCreated' => $DateNow
        );
        $auditTable = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
        // insert manager_has_notification
        $insertNotification = array(
          'EmployeeNumber'                => $set['EmployeeNumber']
          , 'Description'                 => 'Employee reset password'
          , 'ManagerBranchId'             => htmlentities($_POST['ManagerBranchId'], ENT_QUOTES)
          , 'CreatedBy'                   => $EmployeeNumber
        );
        $insertNotificationTable = 'manager_has_notification';
        $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Temporary password successfully changed!'); 
        $this->session->set_flashdata('alertType','success'); 
      
      redirect('home/Dashboard');
    }
    else // user management
    {
      $data = array(
        'EmployeeNumber'                => $_POST['selectEmployee']
        , 'RoleId'                      => $_POST['selectRoleId']
        , 'Password'                    => $_POST['selectEmployee']
        , 'CreatedBy'                   => $EmployeeNumber
        , 'UpdatedBy'                   => $EmployeeNumber
      );
      $query = $this->admin_model->countExistingUserRole($data);
      if($query == 0)
      {
      	// audits
  	      $auditquery = $this->maintenance_model->getCreatedUserDetails($this->maintenance_model->getGeneratedUserRoleId());
  	      $auditDetail = 'Added '.$auditquery['Name'].' for '. $auditquery['Description'] .' role.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $EmployeeNumber,
            'DateCreated' => $DateNow
          );
  		    $auditTable = 'R_Logs';
  		    $this->maintenance_model->insertFunction($insertData, $auditTable);

  	    $table = 'R_UserRole';
  	    $this->maintenance_model->insertFunction($data, $table);
  	    // update password
          $generatedPassword = $this->maintenance_model->getGeneratedPassword($this->maintenance_model->getGeneratedUserRoleId());

  		    $set = array( 
  		      'Password' => $_POST['selectEmployee']
  		      , 'DateUpdated' => $DateNow
  		      , 'UpdatedBy' => $EmployeeNumber
  		    );

  		    $condition = array( 
  		      'EmployeeNumber' => $_POST['selectEmployee']
  		    );
  		    $table = 'R_UserRole';
  		    $this->maintenance_model->updateFunction1($set, $condition, $table);
  		  // notification
  		   	$this->session->set_flashdata('alertTitle','Success!'); 
  		   	$this->session->set_flashdata('alertText','User successfully added! Plase take note of the temporary password for user ' .$generatedPassword['EmployeeNumber']. ' : ' . $generatedPassword['Password']); 
  		   	$this->session->set_flashdata('alertType','success'); 
      }
      else
      {
  	   	$this->session->set_flashdata('alertTitle','Info!'); 
  	   	$this->session->set_flashdata('alertText','User already exists or is deactivated!'); 
  	   	$this->session->set_flashdata('alertType','info'); 
      }

    	redirect('home/addUser');
    }
  }

  function SecurityQuestion()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    if($this->uri->segment(3) == 1) // update Security Question
    {
      // audits
        $auditDetail = 'Security Question updated.';
        $insertData = array(
          'Description' => $auditDetail,
          'SecurityQuestionId' => $_POST['SecurityId'],
        );
        $auditTable = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // insert user security question
        $insertData = array
        (
          'EmployeeNumber' => $EmployeeNumber,
          'SecurityQuestionId' => $_POST['SecurityId'],
          'Answer' => $_POST['Answer'],
        );
        $auditTable = 'R_userrole_has_r_securityquestions';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Security question successfully set!'); 
        $this->session->set_flashdata('alertType','success'); 
      
      redirect('home/userprofile');
    }
  }

  public function getRegion()
  {
    $json = [];
    if(!empty($this->input->get("q")))
    {
      $keyword = $this->input->get("q");
      $json = $this->maintenance_model->getRegion($keyword);
    }
    echo json_encode($json);
  }

  function getProvinces()
  {
    echo $this->maintenance_model->getProvinces($this->input->post('RegionId'));
  }

  function getCities()
  {
    echo $this->maintenance_model->getCities($this->input->post('Id'));
  }

  function getBranches()
  {
    echo $this->maintenance_model->getBranches($this->input->post('Id'));
  }

  function getManagers()
  {
    echo $this->maintenance_model->getManagers($this->input->post('BranchId'));
  }

  function getBarangays()
  {
    echo $this->maintenance_model->getBarangays($this->input->post('Id'));
  }

  function addEmployees()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    if($this->uri->segment(3) == 1) // update Security Question
    {
      // audits
        $auditDetail = 'Security Question updated.';
        $insertData = array(
          'Description' => $auditDetail,
          'SecurityQuestionId' => $_POST['SecurityId'],
        );
        $auditTable = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData, $auditTable);

      // Insert Employees
        $insertData = array
        (
          'EmployeeNumber' => $EmployeeNumber,
          'SecurityQuestionId' => $_POST['SecurityId'],
          'Answer' => $_POST['Answer'],
        );
        $auditTable = 'R_Employee';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Security question successfully set!'); 
        $this->session->set_flashdata('alertType','success'); 
      
      redirect('home/userprofile');
    }
  }

  function notification()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    if($this->uri->segment(3) == 1) // add client
    {
      $time = strtotime($_POST['DateOfBirth']);
      $newformat = date('Y-m-d', $time);
      $time2 = strtotime($_POST['DateHired']);
      $dateHired = date('Y-m-d', $time2);
      $EmpDateHired = date('mdy', $time2);

      $data = array(
        'FirstName'                     => htmlentities($_POST['FirstName'], ENT_QUOTES)
        , 'MiddleName'                  => htmlentities($_POST['MiddleName'], ENT_QUOTES)
        , 'LastName'                    => htmlentities($_POST['LastName'], ENT_QUOTES)
        , 'ExtName'                     => htmlentities($_POST['ExtName'], ENT_QUOTES)
        , 'DateOfBirth'                         => htmlentities($newformat, ENT_QUOTES)
        , 'DateHired'                         => htmlentities($dateHired, ENT_QUOTES)
        , 'CreatedBy'                   => $EmployeeNumber
        , 'UpdatedBy'                   => $EmployeeNumber
      );
      $query = $this->employee_model->countEmployee($data);
      if($query == 0) // not existing
      {
        // insert employee details
          $insertEmployee = array(
            'Salutation'                    => htmlentities($_POST['SalutationId'], ENT_QUOTES)
            , 'FirstName'                   => htmlentities($_POST['FirstName'], ENT_QUOTES)
            , 'MiddleName'                  => htmlentities($_POST['MiddleName'], ENT_QUOTES)
            , 'LastName'                    => htmlentities($_POST['LastName'], ENT_QUOTES)
            , 'ExtName'                     => htmlentities($_POST['ExtName'], ENT_QUOTES)
            , 'Sex'                         => htmlentities($_POST['SexId'], ENT_QUOTES)
            , 'Nationality'                 => htmlentities($_POST['NationalityId'], ENT_QUOTES)
            , 'CivilStatus'                 => htmlentities($_POST['CivilStatusId'], ENT_QUOTES)
            , 'DateOfBirth'                 => htmlentities($newformat, ENT_QUOTES)
            , 'DateHired'                   => htmlentities($dateHired, ENT_QUOTES)
            , 'StatusId'                    => 2
            , 'CreatedBy'                   => $EmployeeNumber
            , 'UpdatedBy'                   => $EmployeeNumber
          );
          $insertEmployeeTable = 'R_Employee';
          $this->maintenance_model->insertFunction($insertEmployee, $insertEmployeeTable);
        // get employee generated id
          $auditData1 = array(
            'table'                 => 'R_Employee'
            , 'column'              => 'EmployeeId'
          );
          $EmployeeId = $this->maintenance_model->getGeneratedId($auditData1);

        // generate employee code number
          $branchCode = $this->maintenance_model->getBranchCode($_POST['BranchId']);
          $generatedEmployeeNumber = $branchCode['Code'] . $EmpDateHired  . '-' . $EmployeeId['EmployeeId'];
          $set = array( 
            'EmployeeNumber' => $generatedEmployeeNumber
          );

          $condition = array( 
            'EmployeeId' => $EmployeeId['EmployeeId']
          );
          $table = 'R_Employee';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // Insert Employee Type
          if($_POST['EmployeeType'] != 'Manager') // Employee
          {
            $insertEmployeeBranch = array(
              'EmployeeNumber'                => $set['EmployeeNumber']
              , 'BranchId'                    => htmlentities($_POST['BranchId'], ENT_QUOTES)
              , 'ManagerBranchId'             => htmlentities($_POST['ManagerBranchId'], ENT_QUOTES)
              , 'CreatedBy'                   => $EmployeeNumber
              , 'UpdatedBy'                   => $EmployeeNumber
            );
            $insertEmployeeBranchTable = 'Branch_has_Employees';
            $this->maintenance_model->insertFunction($insertEmployeeBranch, $insertEmployeeBranchTable);
        // insert manager_has_notification
            $insertNotification = array(
              'EmployeeNumber'                => $set['EmployeeNumber']
              , 'Description'                 => 'Added new employee'
              , 'ManagerBranchId'             => htmlentities($_POST['ManagerBranchId'], ENT_QUOTES)
              , 'CreatedBy'                   => $EmployeeNumber
            );
            $insertNotificationTable = 'manager_has_notification';
            $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
          }
          else // Manager
          {
            $insertManager = array(
              'EmployeeNumber'                    => $set['EmployeeNumber']
              , 'BranchId'                   => htmlentities($_POST['BranchId'], ENT_QUOTES)
              , 'CreatedBy'                   => $EmployeeNumber
              , 'UpdatedBy'                   => $EmployeeNumber
            );
            $insertManagerTable = 'Branch_has_Manager';
            $this->maintenance_model->insertFunction($insertManager, $insertManagerTable);
          }
        // insert mobile number
          // insert into contact numbers
            $insertContact1 = array(
              'PhoneType'                     => 'Mobile'
              , 'Number'                      => htmlentities($_POST['ContactNumber'], ENT_QUOTES)
              , 'CreatedBy'                      => $EmployeeNumber
            );
            $insertContactTable1 = 'r_contactnumbers';
            $this->maintenance_model->insertFunction($insertContact1, $insertContactTable1);
          // get mobile number id
            $generatedIdData1 = array(
              'table'                 => 'r_contactnumbers'
              , 'column'              => 'ContactNumberId'
            );
            $mobileNumberId = $this->maintenance_model->getGeneratedId($generatedIdData1);
          // insert into employee contact numbers
            $insertContact2 = array(
              'EmployeeNumber'                => $generatedEmployeeNumber
              , 'ContactNumberId'             => $mobileNumberId['ContactNumberId']
              , 'CreatedBy'                   => $EmployeeNumber
              , 'UpdatedBy'                   => $EmployeeNumber
            );
            $insertContactTable2 = 'employee_has_contactnumbers';
            $this->maintenance_model->insertFunction($insertContact2, $insertContactTable2);

        // insert telephone number
          if(htmlentities($_POST['TelephoneNumber'], ENT_QUOTES) != '')
          {
            // insert into telephone numbers
              $insertTelephone1 = array(
                'PhoneType'                     => 'Telephone'
                , 'Number'                      => htmlentities($_POST['TelephoneNumber'], ENT_QUOTES)
                , 'CreatedBy'                   => $EmployeeNumber
              );
              $insertTelephoneTable1 = 'r_contactnumbers';
              $this->maintenance_model->insertFunction($insertTelephone1, $insertTelephoneTable1);
            // get mobile number id
              $generatedIdData2 = array(
                'table'                 => 'r_contactnumbers'
                , 'column'              => 'ContactNumberId'
              );
              $TelephoneNumberId = $this->maintenance_model->getGeneratedId($generatedIdData2);
            // insert into client contact numbers
              $insertTelephone2 = array(
                'EmployeeNumber'                     => $generatedEmployeeNumber
                , 'ContactNumberId'              => $TelephoneNumberId['ContactNumberId']
                , 'CreatedBy'                   => $EmployeeNumber
                , 'UpdatedBy'                   => $EmployeeNumber
              );
              $insertTelephoneTable2 = 'employee_has_contactnumbers';
              $this->maintenance_model->insertFunction($insertTelephone2, $insertTelephoneTable2);
          }

        // insert email address
          // insert into email addresses
            $insertDataEmail = array(
              'EmailAddress'                  => htmlentities($_POST['EmailAddress'], ENT_QUOTES)
              , 'CreatedBy'                   => $EmployeeNumber
            );
            $insertTableEmail = 'r_emails';
            $this->maintenance_model->insertFunction($insertDataEmail, $insertTableEmail);
          // get email address id
            $generatedIdData3 = array(
              'table'                 => 'r_emails'
              , 'column'              => 'EmailId'
            );
            $EmailId = $this->maintenance_model->getGeneratedId($generatedIdData3);
          // insert into employee contact numbers
            $insertDataEmail2 = array(
              'EmployeeNumber'                      => $generatedEmployeeNumber
              , 'EmailId'                     => $EmailId['EmailId']
              , 'CreatedBy'                   => $EmployeeNumber
              , 'UpdatedBy'                   => $EmployeeNumber
            );
            $insertTableEmail2 = 'employee_has_emails';
            $this->maintenance_model->insertFunction($insertDataEmail2, $insertTableEmail2);

        // insert city address
          // insert into addresses
            $insertDataAddress = array(
              'HouseNo'                           => htmlentities($_POST['HouseNo'], ENT_QUOTES)
              , 'Street'                          => htmlentities($_POST['StreetNo'], ENT_QUOTES)
              , 'AddressType'                     => 'City Address'
              , 'BarangayId'                      => htmlentities($_POST['BarangayId'], ENT_QUOTES)
              , 'CreatedBy'                   => $EmployeeNumber
            );
            $insertTableAddress = 'r_address';
            $this->maintenance_model->insertFunction($insertDataAddress, $insertTableAddress);
          // get address id
            $generatedIdData4 = array(
              'table'                 => 'r_address'
              , 'column'              => 'AddressId'
            );
            $AddressId = $this->maintenance_model->getGeneratedId($generatedIdData4);
          // insert into Employee addresses
              $insertDataAddress2 = array(
                'EmployeeNumber'                    => $generatedEmployeeNumber
                , 'AddressId'                       => $AddressId['AddressId']
                , 'CreatedBy'                       => $EmployeeNumber
                , 'UpdatedBy'                       => $EmployeeNumber
              );
            $insertTableAddress2 = 'employee_has_address';
            $this->maintenance_model->insertFunction($insertDataAddress2, $insertTableAddress2);

        // insert province address
          if(htmlentities($_POST['IsSameAddress'], ENT_QUOTES) == 1)
          {
            // insert into addresses
              $insertDataAddress = array(
                'HouseNo'                           => htmlentities($_POST['HouseNo'], ENT_QUOTES)
                , 'Street'                          => htmlentities($_POST['StreetNo'], ENT_QUOTES)
                , 'AddressType'                     => 'Province Address'
                , 'BarangayId'                      => htmlentities($_POST['BarangayId'], ENT_QUOTES)
                , 'CreatedBy'                       => $EmployeeNumber
              );
              $insertTableAddress = 'r_address';
              $this->maintenance_model->insertFunction($insertDataAddress, $insertTableAddress);
            // get address id
              $generatedIdData4 = array(
                'table'                 => 'r_address'
                , 'column'              => 'AddressId'
              );
              $AddressId = $this->maintenance_model->getGeneratedId($generatedIdData4);
            // insert into employee addresses
              $insertDataAddress2 = array(
                'EmployeeNumber'                          => $generatedEmployeeNumber
                , 'AddressId'                       => $AddressId['AddressId']
                , 'CreatedBy'                       => $EmployeeNumber
                , 'UpdatedBy'                       => $EmployeeNumber
              );
              $insertTableAddress2 = 'employee_has_address';
              $this->maintenance_model->insertFunction($insertDataAddress2, $insertTableAddress2);
          }
          else
          {
            // insert into addresses
              $insertDataAddress = array(
                'HouseNo'                           => htmlentities($_POST['HouseNo2'], ENT_QUOTES)
                , 'Street'                          => htmlentities($_POST['StreetNo2'], ENT_QUOTES)
                , 'AddressType'                     => 'Province Address'
                , 'BarangayId'                      => htmlentities($_POST['BarangayId2'], ENT_QUOTES)
                , 'CreatedBy'                       => $EmployeeNumber
              );
              $insertTableAddress = 'r_address';
              $this->maintenance_model->insertFunction($insertDataAddress, $insertTableAddress);
            // get address id
              $generatedIdData4 = array(
                'table'                 => 'r_address'
                , 'column'              => 'AddressId'
              );
              $AddressId = $this->maintenance_model->getGeneratedId($generatedIdData4);
            // insert into employee addresses
                $insertDataAddress2 = array(
                  'EmployeeNumber'                          => $generatedEmployeeNumber
                  , 'AddressId'                       => $AddressId['AddressId']
                  , 'CreatedBy'                       => $EmployeeNumber
                  , 'UpdatedBy'                       => $EmployeeNumber
                );
              $insertTableAddress2 = 'employee_has_address';
              $this->maintenance_model->insertFunction($insertDataAddress2, $insertTableAddress2);
          }

        // admin audits
          $auditquery = $this->borrower_model->getEmployeeDetail($EmployeeId['EmployeeId']);
          $auditDetail = 'Added '.$auditquery['Name'].' in employee list.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $EmployeeNumber,
            'DateCreated' => $DateNow
          );
          $this->maintenance_model->insertAdminLog($insertData);

        // employee audits
          $auditEmployee = 'Added in employee list.';
          $insertAuditEmployee = array(
            'Description' => $auditEmployee,
            'CreatedBy' => $EmployeeNumber,
            'DateCreated' => $DateNow
          );
          $auditTable = 'L_EmployeeLog';
          $this->maintenance_model->insertFunction($insertAuditEmployee, $auditTable);

        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Employee successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/Employees/');
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Employee already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/Employees');
      }
    }
    else if($this->uri->segment(3) == 2) // Employee 
    {
    }
  }
}
