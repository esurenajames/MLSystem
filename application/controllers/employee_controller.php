<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class employee_controller extends CI_Controller {

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
    $this->load->library('session');
    $this->load->library('excel');
    $this->load->helper('url');
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

	function SecurityQuestion()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    // audits
      $auditDetail = 'Security question updated.';
      $LogAuditData = array(
        'Description' => $auditDetail,
        'CreatedBy' => $EmployeeNumber
      );
      $employeeAuditData = array(
        'Description' => $auditDetail,
        'CreatedBy' => $EmployeeNumber
      );
      $logAuditTable = 'R_Logs';
      $employeeAuditTable = 'Employee_has_Notifications';
      $this->maintenance_model->insertFunction($LogAuditData, $logAuditTable);
      $this->maintenance_model->insertFunction($employeeAuditData, $employeeAuditTable);
    // Update Security Question
      $set = array( 
        'StatusId' => 0
      );

      $condition = array( 
        'EmployeeNumber' => $EmployeeNumber
      );
      $table = 'R_userrole_has_r_securityquestions';
      $this->maintenance_model->updateFunction1($set, $condition, $table);
    // insert user security question 1 
      $insertData2 = array
      (
        'SecurityQuestionId' => $_POST['Question1'],
        'EmployeeNumber' => $EmployeeNumber,
        'QuestionNumber' => 1,
        'Answer' => $_POST['Answer1'],
        'CreatedBy' => $EmployeeNumber
      );
      $auditTable2 = 'R_userrole_has_r_securityquestions';
      $this->maintenance_model->insertFunction($insertData2, $auditTable2);
    // insert user security question 2
      $insertData2 = array
      (
        'SecurityQuestionId' => $_POST['Question2'],
        'EmployeeNumber' => $EmployeeNumber,
        'QuestionNumber' => 2,
        'Answer' => $_POST['Answer2'],
        'CreatedBy' => $EmployeeNumber
      );
      $auditTable2 = 'R_userrole_has_r_securityquestions';
      $this->maintenance_model->insertFunction($insertData2, $auditTable2);
    // insert user security question 3
      $insertData2 = array
      (
        'SecurityQuestionId' => $_POST['Question3'],
        'EmployeeNumber' => $EmployeeNumber,
        'QuestionNumber' => 3,
        'Answer' => $_POST['Answer3'],
        'CreatedBy' => $EmployeeNumber
      );
      $auditTable2 = 'R_userrole_has_r_securityquestions';
      $this->maintenance_model->insertFunction($insertData2, $auditTable2);
    // main audits
      $mainAudits = array(
        'Description' => 'Updated sescurity question(s)'
        , 'CreatedBy' => $EmployeeNumber
      );
      $insertManagerAudit = array(
        'Description' => $EmployeeNumber.' updated sescurity question(s)'
        , 'CreatedBy' => $EmployeeNumber
      );
      $auditTable1 = 'Employee_has_Notifications';
      $auditTable2 = 'R_Logs';
      $auditTable3 = 'manager_has_notifications';
      $this->maintenance_model->insertFunction($mainAudits, $auditTable1);
      $this->maintenance_model->insertFunction($mainAudits, $auditTable2);
      $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable3);
    // notification
      $this->session->set_flashdata('alertTitle','Success!'); 
      $this->session->set_flashdata('alertText','Security question successfully set!'); 
      $this->session->set_flashdata('alertType','success'); 
    
    redirect('home/userprofile/'.$EmployeeNumber);
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

  function updateEmail()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $input = array( 
      'Id' => htmlentities($this->input->post('Id'), ENT_QUOTES)
      , 'updateType' => htmlentities($this->input->post('updateType'), ENT_QUOTES)
      , 'tableType' => htmlentities($this->input->post('tableType'), ENT_QUOTES)
    );

    $query = $this->employee_model->updateEmail($input);
  }

  function employeeProcessing()
  {
    $CreatedBy = $this->session->userdata('EmployeeNumber');
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    if($this->uri->segment(3) == 1) // add employee
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
        , 'BranchId'                    => htmlentities($_POST['BranchId'], ENT_QUOTES)
        , 'DateOfBirth'                 => htmlentities($newformat, ENT_QUOTES)
        , 'DateHired'                   => htmlentities($dateHired, ENT_QUOTES)
        , 'CreatedBy'                   => $CreatedBy
        , 'UpdatedBy'                   => $CreatedBy
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
            , 'PositionId'                  => htmlentities($_POST['PositionId'], ENT_QUOTES)
            , 'StatusId'                    => 2
            , 'CreatedBy'                   => $CreatedBy
            , 'UpdatedBy'                   => $CreatedBy
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
          $generatedEmployeeNumber = str_pad($EmployeeId['EmployeeId'], 6, '0', STR_PAD_LEFT);
          $set = array( 
            'EmployeeNumber' => $generatedEmployeeNumber
          );

          $condition = array( 
            'EmployeeId' => $EmployeeId['EmployeeId']
          );
          $table = 'R_Employee';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // Insert Employee Type
          if($_POST['EmployeeType'] == 'Manager') // Manager
          {
            // insert into table
              $insertManager = array(
                'EmployeeNumber'                => $generatedEmployeeNumber
                , 'BranchId'                    => htmlentities($_POST['BranchId'], ENT_QUOTES)
                , 'CreatedBy'                   => $CreatedBy
                , 'UpdatedBy'                   => $CreatedBy
              );
              $insertManagerTable = 'Branch_has_Manager';
              $this->maintenance_model->insertFunction($insertManager, $insertManagerTable);
            // get branch manager id
              $generatedBranchManagerID = array(
                'table'                         => 'Branch_has_Manager'
                , 'column'                      => 'ManagerBranchId'
                , 'CreatedBy'                   => $CreatedBy
              );
              $genId = $this->maintenance_model->getGeneratedId2($generatedBranchManagerID);
            // add to branch has employees
              $insertEmployeeBranch = array(
                'EmployeeNumber'                => $generatedEmployeeNumber
                , 'BranchId'                    => htmlentities($_POST['BranchId'], ENT_QUOTES)
                , 'ManagerBranchId'             => $genId['ManagerBranchId']
                , 'CreatedBy'                   => $CreatedBy
              );
              $insertEmployeeBranchTable = 'Branch_has_Employee';
              $this->maintenance_model->insertFunction($insertEmployeeBranch, $insertEmployeeBranchTable);
          }
          else if($_POST['EmployeeType'] == 'Employee')// employee
          {
            // set manager of employee
              $setMan = array( 
                'ManagerId' => htmlentities($_POST['ManagerId'], ENT_QUOTES)
              );

              $conditionMan = array( 
                'EmployeeNumber' => $generatedEmployeeNumber
              );
              $tableMan = 'R_Employee';
              $this->maintenance_model->updateFunction1($setMan, $conditionMan, $tableMan);
            // branch has employees
            $insertEmployeeBranch = array(
              'EmployeeNumber'                => $set['EmployeeNumber']
              , 'BranchId'                    => htmlentities($_POST['BranchId'], ENT_QUOTES)
              , 'ManagerBranchId'             => htmlentities($_POST['ManagerId'], ENT_QUOTES)
              , 'CreatedBy'                   => $CreatedBy
              , 'UpdatedBy'                   => $CreatedBy
            );
            $insertEmployeeBranchTable = 'Branch_has_Employee';
            $this->maintenance_model->insertFunction($insertEmployeeBranch, $insertEmployeeBranchTable);

            // insert manager_has_notification
              $EmployeeName = htmlentities($_POST['LastName'], ENT_QUOTES) . ', ' . htmlentities($_POST['FirstName'], ENT_QUOTES) ;
              $insertNotification = array(
                'Description'                   => 'Added '.$EmployeeName.' with employee number ' . $generatedEmployeeNumber . ' to your branch.'
                , 'ManagerBranchId'             => htmlentities($_POST['ManagerId'], ENT_QUOTES)
                , 'CreatedBy'                   => $CreatedBy
              );
              $insertNotificationTable = 'manager_has_notifications';
              $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
          }
        // insert mobile number
          // insert into contact numbers
            $insertContact1 = array(
              'PhoneType'                     => 'Mobile'
              , 'Number'                      => htmlentities($_POST['ContactNumber'], ENT_QUOTES)
              , 'CreatedBy'                   => $CreatedBy
            );
            $insertContactTable1 = 'r_contactnumbers';
            $this->maintenance_model->insertFunction($insertContact1, $insertContactTable1);
          // get mobile number id
            $generatedIdData1 = array(
              'table'                         => 'r_contactnumbers'
              , 'column'                      => 'ContactNumberId'
            );
            $mobileNumberId = $this->maintenance_model->getGeneratedId($generatedIdData1);
          // insert into employee contact numbers
            $insertContact2 = array(
              'EmployeeNumber'                => $generatedEmployeeNumber
              , 'ContactNumberId'             => $mobileNumberId['ContactNumberId']
              , 'isPrimary'                   => 1
              , 'CreatedBy'                   => $CreatedBy
              , 'UpdatedBy'                   => $CreatedBy
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
                , 'CreatedBy'                   => $CreatedBy
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
                'EmployeeNumber'                => $generatedEmployeeNumber
                , 'ContactNumberId'             => $TelephoneNumberId['ContactNumberId']
                , 'CreatedBy'                   => $CreatedBy
                , 'UpdatedBy'                   => $CreatedBy
              );
              $insertTelephoneTable2 = 'employee_has_contactnumbers';
              $this->maintenance_model->insertFunction($insertTelephone2, $insertTelephoneTable2);
          }
        // insert email address
          // insert into email addresses
            $insertDataEmail = array(
              'EmailAddress'                  => htmlentities($_POST['EmailAddress'], ENT_QUOTES)
              , 'CreatedBy'                   => $CreatedBy
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
              , 'isPrimary'                   => 1
              , 'CreatedBy'                   => $CreatedBy
              , 'UpdatedBy'                   => $CreatedBy
            );
            $insertTableEmail2 = 'employee_has_emails';
            $this->maintenance_model->insertFunction($insertDataEmail2, $insertTableEmail2);
        // insert city address
          // insert into addresses
            $insertDataAddress = array(
              'HouseNo'                           => htmlentities($_POST['HouseNo'], ENT_QUOTES)
              , 'AddressType'                     => 'City Address'
              , 'BarangayId'                      => htmlentities($_POST['BarangayId'], ENT_QUOTES)
              , 'CreatedBy'                       => $CreatedBy
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
                , 'CreatedBy'                       => $CreatedBy
                , 'isPrimary'                       => 1
                , 'UpdatedBy'                       => $CreatedBy
              );
            $insertTableAddress2 = 'employee_has_address';
            $this->maintenance_model->insertFunction($insertDataAddress2, $insertTableAddress2);
        // insert province address
          if(htmlentities($_POST['IsSameAddress'], ENT_QUOTES) == 1)
          {
            // insert into addresses
              $insertDataAddress = array(
                'HouseNo'                           => htmlentities($_POST['HouseNo'], ENT_QUOTES)
                , 'AddressType'                     => 'Province Address'
                , 'BarangayId'                      => htmlentities($_POST['BarangayId'], ENT_QUOTES)
                , 'CreatedBy'                       => $CreatedBy
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
                , 'CreatedBy'                       => $CreatedBy
                , 'UpdatedBy'                       => $CreatedBy
              );
              $insertTableAddress2 = 'employee_has_address';
              $this->maintenance_model->insertFunction($insertDataAddress2, $insertTableAddress2);
          }
          else
          {
            // insert into addresses
              $insertDataAddress = array(
                'HouseNo'                           => htmlentities($_POST['HouseNo2'], ENT_QUOTES)
                , 'AddressType'                     => 'Province Address'
                , 'BarangayId'                      => htmlentities($_POST['BarangayId2'], ENT_QUOTES)
                , 'CreatedBy'                       => $CreatedBy
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
                  , 'CreatedBy'                       => $CreatedBy
                  , 'UpdatedBy'                       => $CreatedBy
                );
              $insertTableAddress2 = 'employee_has_address';
              $this->maintenance_model->insertFunction($insertDataAddress2, $insertTableAddress2);
          }
        // admin audits finals
          $auditLogsManager = 'Added employee #'.$generatedEmployeeNumber.' in employee list.';
          $auditAffectedEmployee = 'Added in employee list.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $generatedEmployeeNumber);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Employee successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/employeeDetails/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Employee already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/addEmployees');
      }
    }
    else if($this->uri->segment(3) == 2) // add contact number 
    {
      $isPrimary = 0;
      if(isset($_POST['isPrimary']))
      {
        $isPrimary = 1;
        $set = array( 
          'IsPrimary' => 0
        );

        $condition = array( 
          'EmployeeNumber' => $this->uri->segment(4)
        );
        $table = 'employee_has_contactnumbers';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      else
      {
        $isPrimary = 0;
      }
      $data = array(
        'PhoneType'                     => htmlentities($_POST['ContactType'], ENT_QUOTES)
        , 'Number'                      => htmlentities($_POST['FieldNumber'], ENT_QUOTES)
        , 'EmployeeNumber'              => $this->uri->segment(4)
      );
      $query = $this->employee_model->countContactNumber($data);
      if($query == 0)
      {
        if($_POST['ContactType'] == 'Mobile')
        {
          // insert into contact numbers
            $insertContact1 = array(
              'PhoneType'                     => 'Mobile'
              , 'Number'                      => htmlentities($_POST['FieldNumber'], ENT_QUOTES)
              , 'CreatedBy'                   => $CreatedBy
            );
            $insertContactTable1 = 'r_contactnumbers';
            $this->maintenance_model->insertFunction($insertContact1, $insertContactTable1);
          // get mobile number id
            $generatedIdData1 = array(
              'table'                         => 'r_contactnumbers'
              , 'column'                      => 'ContactNumberId'
            );
            $mobileNumberId = $this->maintenance_model->getGeneratedId($generatedIdData1);
          // insert into employee contact numbers
            $insertContact2 = array(
              'EmployeeNumber'                => $this->uri->segment(4)
              , 'ContactNumberId'             => $mobileNumberId['ContactNumberId']
              , 'IsPrimary'                   => $isPrimary
              , 'CreatedBy'                   => $CreatedBy
              , 'UpdatedBy'                   => $CreatedBy
            );
            $insertContactTable2 = 'employee_has_contactnumbers';
            $this->maintenance_model->insertFunction($insertContact2, $insertContactTable2);
        }
        else // telephone number
        {
          // insert into telephone numbers
            $insertTelephone1 = array(
              'PhoneType'                     => 'Telephone'
              , 'Number'                      => htmlentities($_POST['FieldNumber'], ENT_QUOTES)
              , 'CreatedBy'                   => $CreatedBy
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
              'EmployeeNumber'                => $this->uri->segment(4)
              , 'ContactNumberId'             => $TelephoneNumberId['ContactNumberId']
              , 'IsPrimary'                   => $isPrimary
              , 'CreatedBy'                   => $CreatedBy
              , 'UpdatedBy'                   => $CreatedBy
            );
            $insertTelephoneTable2 = 'employee_has_contactnumbers';
            $this->maintenance_model->insertFunction($insertTelephone2, $insertTelephoneTable2);
        }

        // insert into notifications
          $employeeDetail = $this->employee_model->getEmployeeDetails($_POST['EmployeeId']);
          $getNewId = array(
            'table'                             => 'employee_has_contactnumbers'
            , 'column'                          => 'EmployeeContactId'
            , 'CreatedBy'                       => $CreatedBy
          );
          $NewId = $this->maintenance_model->getGeneratedId2($getNewId);
          $rowNumber = $this->db->query("SELECT LPAD(".$NewId['EmployeeContactId'].", 6, 0) as number")->row_array();
        // admin audits finals
          $TransactionNumber = 'CN-'.$rowNumber['number'];
          $auditLogsManager = 'Added new contact record #'.$TransactionNumber.' for employee #'.$this->uri->segment(4).' in contact tab.';
          $auditAffectedEmployee = 'Added new contact record #'.$TransactionNumber.' in contact tab.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $this->uri->segment(4));
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Contact number successfully added!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/employeeDetails/'. $_POST['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Contact number already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/employeeDetails/'. $_POST['EmployeeId']);
      }
    }
    else if($this->uri->segment(3) == 3) // add email 
    {
      $isPrimary = 0;
      if(isset($_POST['isPrimary']))
      {
        $isPrimary = 1;
        $set = array( 
          'IsPrimary' => 0
        );

        $condition = array( 
          'EmployeeNumber' => $this->uri->segment(4)
        );
        $table = 'employee_has_emails';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      else
      {
        $isPrimary = 0;
      }
      $data = array(
        'EmailAddress'                  => htmlentities($_POST['EmailAddress'], ENT_QUOTES)
        , 'EmployeeNumber'              => $this->uri->segment(4)
      );
      $query = $this->employee_model->countEmailAddress($data);
      if($query == 0)
      {
        // insert into email
          $insertEmail1 = array(
            'EmailAddress'                      => htmlentities($_POST['EmailAddress'], ENT_QUOTES)
            , 'CreatedBy'                       => $CreatedBy
          );
          $insertEmailTable1 = 'r_emails';
          $this->maintenance_model->insertFunction($insertEmail1, $insertEmailTable1);
        // get email id
          $generatedIdData1 = array(
            'table'                         => 'r_emails'
            , 'column'                      => 'EmailId'
          );
          $generatedIdNumber = $this->maintenance_model->getGeneratedId($generatedIdData1);
        // insert into employee email
          $insertEmail2 = array(
            'EmployeeNumber'                => $this->uri->segment(4)
            , 'EmailId'                     => $generatedIdNumber['EmailId']
            , 'IsPrimary'                   => $isPrimary
            , 'CreatedBy'                   => $CreatedBy
            , 'UpdatedBy'                   => $CreatedBy
          );
          $insertEmailTable2 = 'employee_has_emails';
          $this->maintenance_model->insertFunction($insertEmail2, $insertEmailTable2);

        // insert into notifications
          $employeeDetail = $this->employee_model->getEmployeeDetails($_POST['EmployeeId']);
          $getNewId = array(
            'table'                         => 'employee_has_emails'
            , 'column'                      => 'EmployeeEmailId'
            , 'CreatedBy'                      => $CreatedBy
          );
          $NewId = $this->maintenance_model->getGeneratedId2($getNewId);
          $rowNumber = $this->db->query("SELECT LPAD(".$NewId['EmployeeEmailId'].", 6, 0) as number")->row_array();
        // admin audits finals
          $TransactionNumber = 'EA-' .$rowNumber['number'];
          $auditLogsManager = 'Added new email record #'.$TransactionNumber.' for employee #'.$employeeDetail['EmployeeNumber'].' in email address tab.';
          $auditAffectedEmployee = 'Added new email record #'.$TransactionNumber.' in email address tab.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $employeeDetail['EmployeeNumber']);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Email address successfully added!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/employeeDetails/'. $_POST['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Email address already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/employeeDetails/'. $_POST['EmployeeId']);
      }
    }
    else if($this->uri->segment(3) == 4) // add address
    {
      $CreatedBy = $this->session->userdata('EmployeeNumber');
      $EmployeeNumber = $this->db->query("SELECT LPAD(".$this->uri->segment(4).", 6, 0) as EmployeeNumber")->row_array();
      $EmployeeDetail = $this->db->query("SELECT  EmployeeId
                                                  FROM R_Employee
                                                    WHERE EmployeeNumber = ".$EmployeeNumber['EmployeeNumber']."
      ")->row_array();
      $data = array(
        'HouseNo'                     => htmlentities($_POST['HouseNo'], ENT_QUOTES)
        , 'AddressType'                 => htmlentities($_POST['AddressType'], ENT_QUOTES)
        , 'BarangayId'                  => htmlentities($_POST['BarangayId'], ENT_QUOTES)
        , 'EmployeeNumber'              => $this->uri->segment(4)
      );
      $query = $this->employee_model->countAddress($data);
      if($query == 0)
      {
        // insert into address table
          $insertDataAddress = array(
            'HouseNo'                           => htmlentities($_POST['HouseNo'], ENT_QUOTES)
            , 'AddressType'                     => htmlentities($_POST['AddressType'], ENT_QUOTES)
            , 'BarangayId'                      => htmlentities($_POST['BarangayId'], ENT_QUOTES)
            , 'CreatedBy'                       => $CreatedBy
          );
          $insertTableAddress = 'r_address';
          $this->maintenance_model->insertFunction($insertDataAddress, $insertTableAddress);
        // Update existing primary address
          if($_POST['isPrimary'] == 1)
          {
            $set = array( 
              'isPrimary' => 0
            );

            $condition = array( 
              'EmployeeNumber' => $this->uri->segment(4)
              , 'isPrimary' => 1
            );
            $table = 'employee_has_address';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          }
        // get address id
          $generatedIdData = array(
            'table'                 => 'r_address'
            , 'column'              => 'AddressId'
          );
          $AddressId = $this->maintenance_model->getGeneratedId($generatedIdData);
        // insert into employee address      
          $insertDataAddress2 = array(
            'EmployeeNumber'                    => $this->uri->segment(4)
            , 'AddressId'                       => $AddressId['AddressId']
            , 'IsPrimary'                       => htmlentities($_POST['isPrimary'], ENT_QUOTES)
            , 'CreatedBy'                       => $CreatedBy
            , 'UpdatedBy'                       => $CreatedBy
          );
          $insertTableAddress2 = 'employee_has_address';
          $this->maintenance_model->insertFunction($insertDataAddress2, $insertTableAddress2);
        // get address id
          $generatedIdData2 = array(
            'table'                 => 'employee_has_address'
            , 'column'              => 'EmployeeAddressId'
          );
          $AddressId2 = $this->maintenance_model->getGeneratedId($generatedIdData2);

        // admin audits finals
          $TransactionNumber = 'ADD-'.sprintf('%06d', $AddressId2['EmployeeAddressId']);
          $auditLogsManager = 'Added new address #'.$TransactionNumber.' for employee #'.$EmployeeNumber['EmployeeNumber'].' in address tab.';
          $auditAffectedEmployee = 'Added new address #'.$TransactionNumber.' in address tab.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $this->uri->segment(4));

        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Employee Address successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/employeeDetails/'. $EmployeeDetail['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Address already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/employeeDetails/'. $EmployeeDetail['EmployeeId']);
      }
    }
    else if($this->uri->segment(3) == 5) // add identification cards
    {
      $EmployeeNumber = $this->db->query("SELECT LPAD(".$this->uri->segment(4).", 6, 0) as EmployeeNumber")->row_array();
      $EmployeeDetail = $this->db->query("SELECT  EmployeeId
                                                  FROM R_Employee
                                                    WHERE EmployeeNumber = ".$EmployeeNumber['EmployeeNumber']."
      ")->row_array();

      $path = './uploads';
      $config = array
      (
      'upload_path' => $path,
      'allowed_types' => 'doc|pdf|xlsx|xls|docx|png|jpg|jpeg',
      'overwrite' => 1
      );
      
      $this->load->library('upload', $config);

      $files = $_FILES['ID'];
      $fileName = "";
      $images = array();
      
      foreach ($files['name'] as $key => $image) 
      {
        $file_ext = pathinfo($image, PATHINFO_EXTENSION);

        $_FILES['ID[]']['name']= $files['name'][$key];
        $_FILES['ID[]']['type']= $files['type'][$key];
        $_FILES['ID[]']['tmp_name']= $files['tmp_name'][$key];
        $_FILES['ID[]']['error']= $files['error'][$key];
        $_FILES['ID[]']['size']= $files['size'][$key];
        $uniq_id = uniqid();
        $fileName = $uniq_id.'.'.$file_ext;
        $fileName = str_replace(" ","_",$fileName);
        $images[] = $fileName;

        $config['file_name'] = $fileName;
        $Title = $_FILES['ID[]']['name'];

        $this->upload->initialize($config);

        if ($this->upload->do_upload('ID[]')) 
        {
            $this->upload->data();
        } 
            else
        {
            $fileName = "";
        }
      }

      $attachment = $fileName;
      if($attachment != "")
      {
        $data = array(
          'TypeOfId'                    => htmlentities($_POST['TypeOfId'], ENT_QUOTES)
          , 'Attachment'                => htmlentities($Title, ENT_QUOTES)
          , 'IDNumber'                  => htmlentities($_POST['IDNumber'], ENT_QUOTES)
          , 'Description'               => htmlentities($_POST['Description'], ENT_QUOTES)
          , 'EmployeeNumber'            => $this->uri->segment(4)
        );
        $query = $this->employee_model->countAttachment($data);
        if($query == 0)
        {
          // insert into address table
            $insertData = array(
              'Id'                                => htmlentities($_POST['TypeOfId'], ENT_QUOTES)
              , 'Attachment'                      => htmlentities($Title, ENT_QUOTES)
              , 'IDNumber'                        => htmlentities($_POST['IDNumber'], ENT_QUOTES)
              , 'Description'                     => htmlentities($_POST['Description'], ENT_QUOTES)
              , 'FileName'                        => htmlentities($attachment, ENT_QUOTES)
              , 'CreatedBy'                       => $CreatedBy
            );
            $insertTable = 'r_identificationcards';
            $this->maintenance_model->insertFunction($insertData, $insertTable);
          // get address id
            $generatedIdData = array(
              'table'                     => 'r_identificationcards'
              , 'column'                  => 'IdentificationId'
              , 'CreatedBy'               => $CreatedBy
            );
            $NewId = $this->maintenance_model->getGeneratedId2($generatedIdData);
          // insert into employee address      
            $insertData2 = array(
              'EmployeeNumber'                    => $this->uri->segment(4)
              , 'IdentificationId'                => $NewId['IdentificationId']
              , 'CreatedBy'                       => $CreatedBy
              , 'UpdatedBy'                       => $CreatedBy
            );
            $insertTable2 = 'employee_has_identifications';
            $this->maintenance_model->insertFunction($insertData2, $insertTable2);
          // notifications
            $employeeDetail = $this->employee_model->getEmployeeDetails($EmployeeDetail['EmployeeId']);
            $getNewId = array(
              'table'                         => 'employee_has_identifications'
              , 'column'                      => 'EmployeeIdentificationId'
              , 'CreatedBy'                      => $CreatedBy
            );
            $NewId = $this->maintenance_model->getGeneratedId2($getNewId);
            $rowNumber = $this->db->query("SELECT LPAD(".$NewId['EmployeeIdentificationId'].", 6, 0) as number")->row_array();
        // admin audits finals
          $TransactionNumber = '#ID-' . $rowNumber['number'];
          $auditLogsManager = 'Added new ID record #'.$TransactionNumber.' for employee #'.$EmployeeNumber['EmployeeNumber'].' in identifications tab.';
          $auditAffectedEmployee = 'Added new ID record #'.$TransactionNumber.' in identifications tab.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $this->uri->segment(4));
          // notification
            $this->session->set_flashdata('alertTitle','Success!'); 
            $this->session->set_flashdata('alertText','ID successfully recorded!'); 
            $this->session->set_flashdata('alertType','success'); 
            redirect('home/employeeDetails/'. $EmployeeDetail['EmployeeId']);
        }
        else
        {
          // notification
            $this->session->set_flashdata('alertTitle','Warning!'); 
            $this->session->set_flashdata('alertText','ID already existing!'); 
            $this->session->set_flashdata('alertType','warning'); 
            redirect('home/employeeDetails/'. $EmployeeDetail['EmployeeId']);
        }
      }
      else
      {
        // Notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','File uploaded was unsuccessful! Please try again!'); 
          $this->session->set_flashdata('alertType','warning');

        redirect('home/employeeDetails/'. $EmployeeDetail['EmployeeId']);
      }
    }
    else if($this->uri->segment(3) == 6) // update employee information
    {
      $EmployeeNumber = $this->db->query("SELECT LPAD(".$this->uri->segment(4).", 6, 0) as EmployeeNumber")->row_array();
      $EmployeeDetail = $this->db->query("SELECT  EmployeeId
                                                  FROM R_Employee
                                                    WHERE EmployeeNumber = ".$EmployeeNumber['EmployeeNumber']."
      ")->row_array();
      $employeeDetail = $this->employee_model->getEmployeeDetails($this->uri->segment(4));
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
        , 'DateOfBirth'                 => htmlentities($newformat, ENT_QUOTES)
        , 'DateHired'                   => htmlentities($dateHired, ENT_QUOTES)
      );
      $query = $this->employee_model->countEmployee($data);
      // status
        if($employeeDetail['EmployeeStatusId'] != htmlentities($_POST['StatusId'], ENT_QUOTES))
        {
          // admin audits finalss
            $auditLogsManager = 'Updated employee status of employee #'. $EmployeeNumber['EmployeeNumber'];
            $auditAffectedEmployee = 'Updated employee status.';
            $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber['EmployeeNumber']);

          // update detail
            $set = array( 
              'StatusId' => htmlentities($_POST['StatusId'], ENT_QUOTES)
            );

            $condition = array( 
              'EmployeeId' => $this->uri->segment(4)
            );
            $table = 'R_Employee';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
      if($query == 0) // not existing
      {
        $this->updatePersonalInformation();
        $this->updateSupportingInfo();
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Personal information successfully updated!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/employeeDetails/'. $EmployeeDetail['EmployeeId']);
      }
      else
      {
        $this->updateSupportingInfo();
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Personal information successfully updated!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/employeeDetails/'. $EmployeeDetail['EmployeeId']);
      }
    }
    else if($this->uri->segment(3) == 7) // profile picture of employee
    {
      $EmployeeNumber = $this->db->query("SELECT LPAD(".$this->uri->segment(4).", 6, 0) as EmployeeNumber")->row_array();
      $EmployeeDetail = $this->db->query("SELECT  EmployeeId
                                                  FROM R_Employee
                                                    WHERE EmployeeNumber = ".$EmployeeNumber['EmployeeNumber']."
      ")->row_array();

      if(isset($_POST['uploadType'])) // camera
      {
        $path = './profilepicture';
        $config = array
        (
        'upload_path' => $path,
        'allowed_types' => 'png|jpg|jpeg',
        'overwrite' => 1
        );
        
        $this->load->library('upload', $config);

        $files = $_FILES['ID'];
        $fileName = "";
        $images = array();
        
        foreach ($files['name'] as $key => $image) 
        {
          $file_ext = pathinfo($image, PATHINFO_EXTENSION);

          $_FILES['ID[]']['name']= $files['name'][$key];
          $_FILES['ID[]']['type']= $files['type'][$key];
          $_FILES['ID[]']['tmp_name']= $files['tmp_name'][$key];
          $_FILES['ID[]']['error']= $files['error'][$key];
          $_FILES['ID[]']['size']= $files['size'][$key];
          $uniq_id = uniqid();
          $fileName = $uniq_id.'.'.$file_ext;
          $fileName = str_replace(" ","_",$fileName);
          $images[] = $fileName;

          $config['file_name'] = $fileName;
          $Title = $_FILES['ID[]']['name'];

          $this->upload->initialize($config);

          if ($this->upload->do_upload('ID[]')) 
          {
              $this->upload->data();
          } 
              else
          {
              $fileName = "";
          }
        }

        $attachment = $fileName;
        if($attachment != "")
        {
          // update detail
            $set = array( 
              'StatusId' => 0
            );

            $condition = array( 
              'EmployeeNumber' => $EmployeeNumber['EmployeeNumber']
              , 'StatusId' => 1
            );
            $table = 'r_ProfilePicture';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // insert into table
            $insertData = array(
              'FileName'                                => htmlentities($attachment, ENT_QUOTES)
              , 'EmployeeNumber'                        => $EmployeeNumber['EmployeeNumber']
            );
            $insertTable = 'r_ProfilePicture';
            $this->maintenance_model->insertFunction($insertData, $insertTable);
          // admin audits finalss
            $auditLogsManager = 'Employee #'.$CreatedBy . ' changed profile picture of #'.$EmployeeNumber['EmployeeNumber'].'.';
            $auditAffectedEmployee = 'Changed profile picture.';
            $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber['EmployeeNumber']);
          // notification
            $this->session->set_flashdata('alertTitle','Success!'); 
            $this->session->set_flashdata('alertText','Profile successfully updated!'); 
            $this->session->set_flashdata('alertType','success'); 
            if($this->uri->segment(3) == 7) // admin update
            {
              redirect('home/employeeDetails/'. $EmployeeDetail['EmployeeId']);
            }
            else 
            {
              redirect('home/userProfile/'. $EmployeeNumber['EmployeeNumber']);
            }
        }
        else
        {
          // Notification
            $this->session->set_flashdata('alertTitle','Warning!'); 
            $this->session->set_flashdata('alertText','File uploaded was unsuccessful! Please try again!'); 
            $this->session->set_flashdata('alertType','warning');
            if($this->uri->segment(3) == 7) // admin update
            {
              redirect('home/employeeDetails/'. $EmployeeDetail['EmployeeId']);
            }
            else 
            {
              redirect('home/userProfile/'. $EmployeeNumber['EmployeeNumber']);
            }
        }
      }
      else
      {
        $myfilename =  time() . '.jpg';
        $livefilepath = 'profilepicture/';
        move_uploaded_file($_FILES['webcam']['tmp_name'], $livefilepath.$myfilename);

        // update detail
          $set = array( 
            'StatusId' => 0
          );

          $condition = array( 
            'EmployeeNumber' => $this->uri->segment(4)
            , 'StatusId' => 1
          );
          $table = 'r_ProfilePicture';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into table
          $insertData = array(
            'FileName'                                => htmlentities($myfilename, ENT_QUOTES)
            , 'EmployeeNumber'                        => $this->uri->segment(4)
          );
          $insertTable = 'r_ProfilePicture';
          $this->maintenance_model->insertFunction($insertData, $insertTable);
        // admin audits finalss
          $auditLogsManager = 'Employee #'.$EmployeeNumber['EmployeeNumber'] . ' changed profile picture.';
          $auditAffectedEmployee = 'Changed profile picture.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber['EmployeeNumber']);
        echo $livefilepath.$myfilename;
      }
    }
    else if($this->uri->segment(3) == 8) // add profile pictre
    {
      $CreatedBy = $this->session->userdata('EmployeeNumber');
      $EmployeeNumber = $this->db->query("SELECT LPAD(".$this->uri->segment(4).", 6, 0) as EmployeeNumber")->row_array();
      $EmployeeDetail = $this->db->query("SELECT  EmployeeId
                                                  FROM R_Employee
                                                    WHERE EmployeeNumber = ".$EmployeeNumber['EmployeeNumber']."
      ")->row_array();
      $path = './profilepicture';
      $config = array
      (
      'upload_path' => $path,
      'allowed_types' => 'png|jpg|jpeg',
      'overwrite' => 1
      );
      
      $this->load->library('upload', $config);

      $files = $_FILES['ID'];
      $fileName = "";
      $images = array();
      
      foreach ($files['name'] as $key => $image) 
      {
        $file_ext = pathinfo($image, PATHINFO_EXTENSION);

        $_FILES['ID[]']['name']= $files['name'][$key];
        $_FILES['ID[]']['type']= $files['type'][$key];
        $_FILES['ID[]']['tmp_name']= $files['tmp_name'][$key];
        $_FILES['ID[]']['error']= $files['error'][$key];
        $_FILES['ID[]']['size']= $files['size'][$key];
        $uniq_id = uniqid();
        $fileName = $uniq_id.'.'.$file_ext;
        $fileName = str_replace(" ","_",$fileName);
        $images[] = $fileName;

        $config['file_name'] = $fileName;
        $Title = $_FILES['ID[]']['name'];

        $this->upload->initialize($config);

        if ($this->upload->do_upload('ID[]')) 
        {
            $this->upload->data();
        } 
            else
        {
            $fileName = "";
        }
      }

      $attachment = $fileName;
      if($attachment != "")
      {
        // update detail
          $set = array( 
            'StatusId' => 0
          );

          $condition = array( 
            'EmployeeNumber' => $EmployeeNumber['EmployeeNumber']
            , 'StatusId' => 1
          );
          $table = 'r_ProfilePicture';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into table
          $insertData = array(
            'FileName'                                => htmlentities($attachment, ENT_QUOTES)
            , 'EmployeeNumber'                        => $EmployeeNumber['EmployeeNumber']
          );
          $insertTable = 'r_ProfilePicture';
          $this->maintenance_model->insertFunction($insertData, $insertTable);
        // admin audits finalss
          $auditLogsManager = 'Employee #'.$EmployeeNumber['EmployeeNumber'] . ' changed profile picture.';
          $auditAffectedEmployee = 'Changed profile picture.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber['EmployeeNumber']);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Profile successfully updated!'); 
          $this->session->set_flashdata('alertType','success'); 
          if($this->uri->segment(3) == 7) // admin update
          {
            redirect('home/employeeDetails/'. $EmployeeDetail['EmployeeId']);
          }
          else 
          {
            redirect('home/userProfile/'. $EmployeeNumber['EmployeeNumber']);
          }
      }
      else
      {
        // Notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','File uploaded was unsuccessful! Please try again!'); 
          $this->session->set_flashdata('alertType','warning');
          if($this->uri->segment(3) == 7) // admin update
          {
            redirect('home/employeeDetails/'. $EmployeeDetail['EmployeeId']);
          }
          else 
          {
            redirect('home/userProfile/'. $EmployeeNumber['EmployeeNumber']);
          }
      }
    }
    else if($this->uri->segment(3) == 9) // add profile picture (borrower)
    {
      if($this->uri->segment(5) == 1) // camera
      {
        $myfilename =  time() . '.jpg';
        $livefilepath = 'borrowerpicture/';
        move_uploaded_file($_FILES['webcam']['tmp_name'], $livefilepath.$myfilename);
        // update detail
          $set = array( 
            'StatusId' => 0
          );

          $condition = array( 
            'BorrowerId' => $this->uri->segment(4)
            , 'StatusId' => 1
          );
          $table = 'borrower_has_picture';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into address table
          $insertData = array(
            'FileName'                                => htmlentities($myfilename, ENT_QUOTES)
            , 'BorrowerId'                            => $this->uri->segment(4)
          );
          $insertTable = 'borrower_has_picture';
          $this->maintenance_model->insertFunction($insertData, $insertTable);
        // admin audits finalss
          $borrowerDetail = $this->maintenance_model->selectSpecific('R_Borrowers', 'BorrowerId', $this->uri->segment(4));
          $auditLogsManager = 'Changed profile picture of borrower #'. $borrowerDetail['BorrowerNumber'];
          $auditAffectedEmployee = 'Changed profile picture of borrower #'. $borrowerDetail['BorrowerNumber'];
          $auditAffectedTable = 'Changed profile picture';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Profile successfully updated!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/BorrowerDetails/'. $this->uri->segment(4));
        echo $livefilepath.$myfilename;
      }
      else
      {
        $path = './borrowerpicture';
        $config = array
        (
          'upload_path' => $path,
          'allowed_types' => 'png|jpg|jpeg',
          'overwrite' => 1
        );
        
        $this->load->library('upload', $config);

        $files = $_FILES['ID'];
        $fileName = "";
        $images = array();
        
        foreach ($files['name'] as $key => $image) 
        {
          $file_ext = pathinfo($image, PATHINFO_EXTENSION);

          $_FILES['ID[]']['name']= $files['name'][$key];
          $_FILES['ID[]']['type']= $files['type'][$key];
          $_FILES['ID[]']['tmp_name']= $files['tmp_name'][$key];
          $_FILES['ID[]']['error']= $files['error'][$key];
          $_FILES['ID[]']['size']= $files['size'][$key];
          $uniq_id = uniqid();
          $fileName = $uniq_id.'.'.$file_ext;
          $fileName = str_replace(" ","_",$fileName);
          $images[] = $fileName;

          $config['file_name'] = $fileName;
          $Title = $_FILES['ID[]']['name'];

          $this->upload->initialize($config);

          if ($this->upload->do_upload('ID[]')) 
          {
              $this->upload->data();
          } 
              else
          {
              $fileName = "";
          }
        }

        $attachment = $fileName;
        if($attachment != "")
        {
          // update detail
            $set = array( 
              'StatusId' => 0
            );

            $condition = array( 
              'BorrowerId' => $this->uri->segment(4)
              , 'StatusId' => 1
            );
            $table = 'borrower_has_picture';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // insert into address table
            $insertData = array(
              'FileName'                                => htmlentities($attachment, ENT_QUOTES)
              , 'BorrowerId'                            => $this->uri->segment(4)
            );
            $insertTable = 'borrower_has_picture';
            $this->maintenance_model->insertFunction($insertData, $insertTable);
          // admin audits finalss
            $borrowerDetail = $this->maintenance_model->selectSpecific('R_Borrowers', 'BorrowerId', $this->uri->segment(4));
            $auditLogsManager = 'Changed profile picture of borrower #'. $borrowerDetail['BorrowerNumber'];
            $auditAffectedEmployee = 'Changed profile picture of borrower #'. $borrowerDetail['BorrowerNumber'];
            $auditAffectedTable = 'Changed profile picture';
            $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');
          // notification
            $this->session->set_flashdata('alertTitle','Success!'); 
            $this->session->set_flashdata('alertText','Profile successfully updated!'); 
            $this->session->set_flashdata('alertType','success'); 
            redirect('home/BorrowerDetails/'. $this->uri->segment(4));
        }
        else
        {
          // Notification
            $this->session->set_flashdata('alertTitle','Warning!'); 
            $this->session->set_flashdata('alertText','File uploaded was unsuccessful! Please try again!'); 
            $this->session->set_flashdata('alertType','warning');
            if($this->uri->segment(3) == 7) // admin update
            {
              redirect('home/employeeDetails/'. $EmployeeDetail['EmployeeId']);
            }
            else 
            {
              redirect('home/userProfile/'. $EmployeeNumber['EmployeeNumber']);
            }
        }
        
      }
    }
  }

  function updatePersonalInformation()
  {
    $CreatedBy = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    $EmployeeNumber = $this->db->query("SELECT LPAD(".$this->uri->segment(4).", 6, 0) as EmployeeNumber")->row_array();
    $employeeDetail = $this->employee_model->getEmployeeDetails($this->uri->segment(4));
    $time = strtotime($_POST['DateOfBirth']);
    $newformat = date('Y-m-d', $time);
    $time2 = strtotime($_POST['DateHired']);
    $dateHired = date('Y-m-d', $time2);
    $EmpDateHired = date('mdy', $time2);

    // first name
      if($employeeDetail['FirstName'] != htmlentities($_POST['FirstName'], ENT_QUOTES))
      {
        // admin audits finalss
          $auditLogsManager = 'Updated first name from '.$employeeDetail['FirstName'].' to '.htmlentities($_POST['FirstName'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $auditAffectedEmployee = 'Updated first name from '.$employeeDetail['FirstName'].' to '.htmlentities($_POST['FirstName'], ENT_QUOTES);
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $employeeDetail['EmployeeNumber']);
        // update detail
          $set = array( 
            'FirstName' => htmlentities($_POST['FirstName'], ENT_QUOTES)
          );

          $condition = array( 
            'EmployeeId' => $this->uri->segment(4)
          );
          $table = 'R_Employee';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
    // middle name
      if($employeeDetail['MiddleName'] != htmlentities($_POST['MiddleName'], ENT_QUOTES))
      {
        // admin audits finalss
          $auditLogsManager = 'Updated middle name from '.$employeeDetail['MiddleName'].' to '.htmlentities($_POST['MiddleName'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $auditAffectedEmployee = 'Updated middle name from '.$employeeDetail['MiddleName'].' to '.htmlentities($_POST['MiddleName'], ENT_QUOTES);
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $employeeDetail['EmployeeNumber']);

        // update detail
          $set = array( 
            'MiddleName' => htmlentities($_POST['MiddleName'], ENT_QUOTES)
          );

          $condition = array( 
            'EmployeeId' => $this->uri->segment(4)
          );
          $table = 'R_Employee';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
    // last name
      if($employeeDetail['LastName'] != htmlentities($_POST['LastName'], ENT_QUOTES))
      {
        // admin audits finalss
          $auditLogsManager = 'Updated last name from '.$employeeDetail['LastName'].' to '.htmlentities($_POST['LastName'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $auditAffectedEmployee = 'Updated last name from '.$employeeDetail['LastName'].' to '.htmlentities($_POST['LastName'], ENT_QUOTES);
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $employeeDetail['EmployeeNumber']);
        // update detail
          $set = array( 
            'LastName' => htmlentities($_POST['LastName'], ENT_QUOTES)
          );

          $condition = array( 
            'EmployeeId' => $this->uri->segment(4)
          );
          $table = 'R_Employee';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
    // ext name
      if($employeeDetail['ExtName'] != htmlentities($_POST['ExtName'], ENT_QUOTES))
      {
        // admin audits finalss
          $auditLogsManager = 'Updated extension name from '.$employeeDetail['ExtName'].' to '.htmlentities($_POST['ExtName'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $auditAffectedEmployee = 'Updated extension name from '.$employeeDetail['ExtName'].' to '.htmlentities($_POST['ExtName'], ENT_QUOTES);
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $employeeDetail['EmployeeNumber']);

        // update detail
          $set = array( 
            'ExtName' => htmlentities($_POST['ExtName'], ENT_QUOTES)
          );

          $condition = array( 
            'EmployeeId' => $this->uri->segment(4)
          );
          $table = 'R_Employee';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
    // birthdate
      $convertOldPost = strtotime($employeeDetail['RawDateOfBirth']);
      $convertNewPost = strtotime($_POST['DateOfBirth']);
      $oldData = date('d M Y', $convertOldPost);
      $newData = date('d M Y', $convertNewPost);
      $newUpdateData = date('Y-m-d', $convertNewPost);
      if($oldData != $newData)
      {
        // admin audits finalss
          $auditLogsManager = 'Updated birth date from '.$oldData.' to '.$newData.' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $auditAffectedEmployee = 'Updated birth date from '.$oldData.' to '.$newData;
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $employeeDetail['EmployeeNumber']);

        // update detail
          $set = array( 
            'DateOfBirth' => $newUpdateData
          );

          $condition = array( 
            'EmployeeId' => $this->uri->segment(4)
          );
          $table = 'R_Employee';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
  }

  function updateSupportingInfo()
  {
    $CreatedBy = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    $EmployeeNumber = $this->db->query("SELECT LPAD(".$this->uri->segment(4).", 6, 0) as EmployeeNumber")->row_array();
    $EmployeeDetail = $this->db->query("SELECT  EmployeeId
                                                FROM R_Employee
                                                  WHERE EmployeeNumber = ".$EmployeeNumber['EmployeeNumber']."
    ")->row_array();
    $employeeDetail = $this->employee_model->getEmployeeDetails($this->uri->segment(4));
    $time = strtotime($_POST['DateOfBirth']);
    $newformat = date('Y-m-d', $time);
    $time2 = strtotime($_POST['DateHired']);
    $dateHired = date('Y-m-d', $time2);
    $EmpDateHired = date('mdy', $time2);

    $convertOldPost2 = strtotime($employeeDetail['RawDH']);
    $convertNewPost2 = strtotime($_POST['DateHired']);
    $oldData2 = date('d M Y', $convertOldPost2);
    $newData2 = date('d M Y', $convertNewPost2);
    $newUpdateData2 = date('Y-m-d', $convertNewPost2);

    // salutation
      if($employeeDetail['SalutationId'] != htmlentities($_POST['SalutationId'], ENT_QUOTES))
      {
        $data1 = array(
          'column'                      => 'Name'
          , 'table'                     => 'R_Salutation'
          , 'query'                     => 'WHERE SalutationId = '. htmlentities($employeeDetail['SalutationId'], ENT_QUOTES)
        );
        $data2 = array(
          'column'                      => 'Name'
          , 'table'                     => 'R_Salutation'
          , 'query'                     => 'WHERE SalutationId = '. htmlentities($_POST['SalutationId'], ENT_QUOTES)
        );
        $oldDetail = $this->employee_model->getNameOfCategory($data1);
        $newDetail = $this->employee_model->getNameOfCategory($data2);
        // admin audits finalss
          $auditLogsManager = 'Updated salutation from '.$oldDetail['Name'].' to '.htmlentities($newDetail['Name'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $auditAffectedEmployee = 'Updated salutation from '.$oldDetail['Name'].' to '.htmlentities($newDetail['Name'], ENT_QUOTES);
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $this->uri->segment(4));

        // update detail
          $set = array( 
            'Salutation' => htmlentities($_POST['SalutationId'], ENT_QUOTES)
          );

          $condition = array( 
            'EmployeeId' => $this->uri->segment(4)
          );
          $table = 'R_Employee';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
    // sex
      if($employeeDetail['SexId'] != htmlentities($_POST['SexId'], ENT_QUOTES))
      {
        $data1 = array(
          'column'                      => 'Name'
          , 'table'                     => 'R_Sex'
          , 'query'                     => 'WHERE SexId = '. htmlentities($employeeDetail['SexId'], ENT_QUOTES)
        );

        $data2 = array(
          'column'                      => 'Name'
          , 'table'                     => 'R_Sex'
          , 'query'                     => 'WHERE SexId = '. htmlentities($_POST['SexId'], ENT_QUOTES)
        );
        $oldDetail = $this->employee_model->getNameOfCategory($data1);
        $newDetail = $this->employee_model->getNameOfCategory($data2);
        // admin audits finalss
          $auditLogsManager = 'Updated gender from '.$oldDetail['Name'].' to '.htmlentities($newDetail['Name'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $auditAffectedEmployee = 'Updated gender from '.$oldDetail['Name'].' to '.htmlentities($newDetail['Name'], ENT_QUOTES);
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber['EmployeeNumber']);

        // update detail
          $set = array( 
            'Sex' => htmlentities($_POST['SexId'], ENT_QUOTES)
          );

          $condition = array( 
            'EmployeeId' => $this->uri->segment(4)
          );
          $table = 'R_Employee';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
    // nationality
      if($employeeDetail['NationalityId'] != htmlentities($_POST['NationalityId'], ENT_QUOTES))
      {
        $data1 = array(
          'column'                      => 'Description'
          , 'table'                     => 'R_Nationality'
          , 'query'                     => 'WHERE NationalityId = '. htmlentities($employeeDetail['NationalityId'], ENT_QUOTES)
        );

        $data2 = array(
          'column'                      => 'Description'
          , 'table'                     => 'R_Nationality'
          , 'query'                     => 'WHERE NationalityId = '. htmlentities($_POST['NationalityId'], ENT_QUOTES)
        );
        $oldDetail = $this->employee_model->getNameOfCategory($data1);
        $newDetail = $this->employee_model->getNameOfCategory($data2);
        // admin audits finalss
          $auditLogsManager = 'Updated nationality from '.$oldDetail['Description'].' to '.htmlentities($newDetail['Description'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $auditAffectedEmployee = 'Updated nationality from '.$oldDetail['Description'].' to '.htmlentities($newDetail['Description'], ENT_QUOTES);
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber['EmployeeNumber']);

        // update detail
          $set = array( 
            'Nationality' => htmlentities($_POST['NationalityId'], ENT_QUOTES)
          );

          $condition = array( 
            'EmployeeId' => $this->uri->segment(4)
          );
          $table = 'R_Employee';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
    // civil status
      if($employeeDetail['CivilStatusId'] != htmlentities($_POST['CivilStatusId'], ENT_QUOTES))
      {
        $data1 = array(
          'column'                      => 'Name'
          , 'table'                     => 'r_civilstatus'
          , 'query'                     => 'WHERE CivilStatusId = '. htmlentities($employeeDetail['CivilStatusId'], ENT_QUOTES)
        );

        $data2 = array(
          'column'                      => 'Name'
          , 'table'                     => 'r_civilstatus'
          , 'query'                     => 'WHERE CivilStatusId = '. htmlentities($_POST['CivilStatusId'], ENT_QUOTES)
        );
        $oldDetail = $this->employee_model->getNameOfCategory($data1);
        $newDetail = $this->employee_model->getNameOfCategory($data2);
        // admin audits finalss
          $auditLogsManager = 'Updated civil status from '.$oldDetail['Name'].' to '.htmlentities($newDetail['Name'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $auditAffectedEmployee = 'Updated civil status from '.$oldDetail['Name'].' to '.htmlentities($newDetail['Name'], ENT_QUOTES);
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber['EmployeeNumber']);

        // update detail
          $set = array( 
            'CivilStatus' => htmlentities($_POST['CivilStatusId'], ENT_QUOTES)
          );

          $condition = array( 
            'EmployeeId' => $this->uri->segment(4)
          );
          $table = 'R_Employee';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
    // date hired
      if($oldData2 != $newData2)
      {
        // admin audits finalss
          $auditLogsManager = 'Updated date hired from '.$oldData2.' to '.$newData2.' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $auditAffectedEmployee = 'Updated date hired from '.$oldData2.' to '.$newData2;
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber['EmployeeNumber']);

        // update detail
          $set = array( 
            'DateHired' => $newUpdateData2
          );

          $condition = array( 
            'EmployeeId' => $this->uri->segment(4)
          );
          $table = 'R_Employee';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
    // position
      if($employeeDetail['PositionId'] != htmlentities($_POST['PositionId'], ENT_QUOTES))
      {
        $data1 = array(
          'column'                      => 'Name'
          , 'table'                     => 'r_position'
          , 'query'                     => 'WHERE PositionId = '. htmlentities($employeeDetail['PositionId'], ENT_QUOTES)
        );

        $data2 = array(
          'column'                      => 'Name'
          , 'table'                     => 'r_position'
          , 'query'                     => 'WHERE PositionId = '. htmlentities($_POST['PositionId'], ENT_QUOTES)
        );
        $oldDetail = $this->employee_model->getNameOfCategory($data1);
        $newDetail = $this->employee_model->getNameOfCategory($data2);
        // admin audits finalss
          $auditLogsManager = 'Updated position from '.$oldDetail['Name'].' to '.htmlentities($newDetail['Name'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $auditAffectedEmployee = 'Updated position from '.$oldDetail['Name'].' to '.htmlentities($newDetail['Name'], ENT_QUOTES);
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber['EmployeeNumber']);

        // update detail
          $set = array( 
            'PositionId' => htmlentities($_POST['PositionId'], ENT_QUOTES)
          );

          $condition = array( 
            'EmployeeId' => $this->uri->segment(4)
          );
          $table = 'R_Employee';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
    // branch
      if($employeeDetail['BranchId'] != htmlentities($_POST['BranchId'], ENT_QUOTES))
      {
        $data1 = array(
          'column'                      => 'Name'
          , 'table'                     => 'r_branches'
          , 'query'                     => 'WHERE BranchId = '. htmlentities($employeeDetail['BranchId'], ENT_QUOTES)
        );

        $data2 = array(
          'column'                      => 'Name'
          , 'table'                     => 'r_branches'
          , 'query'                     => 'WHERE BranchId = '. htmlentities($_POST['BranchId'], ENT_QUOTES)
        );
        $oldDetail = $this->employee_model->getNameOfCategory($data1);
        $newDetail = $this->employee_model->getNameOfCategory($data2);
        // admin audits finalss
          $auditLogsManager = 'Updated branch from '.$oldDetail['Name'].' to '.htmlentities($newDetail['Name'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $auditAffectedEmployee = 'Updated branch from '.$oldDetail['Name'].' to '.htmlentities($newDetail['Name'], ENT_QUOTES);
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber['EmployeeNumber']);

        // update detail
          $set = array( 
            'BranchId' => htmlentities($_POST['BranchId'], ENT_QUOTES)
          );

          $condition = array( 
            'EmployeeNumber' => $EmployeeNumber['EmployeeNumber']
          );
          $table = 'branch_has_employee';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
    // employee type
      if(htmlentities($_POST['EmployeeType'], ENT_QUOTES) == 'Employee')
      {
        if(htmlentities($_POST['ManagerId'], ENT_QUOTES) != $employeeDetail['ManagerBranchId'])
        {
          // update manager record as deactivated
            $set1 = array( 
              'StatusId' => 0
            );
            $condition1 = array( 
              'EmployeeNumber' => $EmployeeNumber['EmployeeNumber']
            );
            $table1 = 'branch_has_manager';
            $this->maintenance_model->updateFunction1($set1, $condition1, $table1);
          // details
            $oldDetail = $this->employee_model->getManagerDetails(htmlentities($employeeDetail['ManagerBranchId'], ENT_QUOTES));
            $newDetail = $this->employee_model->getManagerDetails(htmlentities($_POST['ManagerId'], ENT_QUOTES));
          // admin audits finalss
            $auditLogsManager = 'Updated manager assigned from '.$oldDetail['ManagerName'].' to '.htmlentities($newDetail['ManagerName'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
            $auditAffectedEmployee = 'Updated manager assigned from '.$oldDetail['ManagerName'].' to '.htmlentities($newDetail['ManagerName'], ENT_QUOTES);
            $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber['EmployeeNumber']);
          // set manager of employee
            $setMan = array( 
              'ManagerId' => htmlentities($_POST['ManagerId'], ENT_QUOTES)
            );
            $conditionMan = array( 
              'EmployeeNumber' => $EmployeeNumber['EmployeeNumber']
            );
            $tableMan = 'R_Employee';
            $this->maintenance_model->updateFunction1($setMan, $conditionMan, $tableMan);
            $setMan2 = array( 
              'ManagerBranchId' => htmlentities($_POST['ManagerId'], ENT_QUOTES)
            );
            $conditionMan2 = array( 
              'EmployeeNumber' => $EmployeeNumber['EmployeeNumber']
            );
            $tableMan2 = 'Branch_has_Employee';
            $this->maintenance_model->updateFunction1($setMan2, $conditionMan2, $tableMan2);
        }
      }
      else // manager
      {
        // insert into table
          $insertManager = array(
            'EmployeeNumber'                => $EmployeeNumber['EmployeeNumber']
            , 'BranchId'                    => htmlentities($_POST['BranchId'], ENT_QUOTES)
            , 'CreatedBy'                   => $CreatedBy
            , 'UpdatedBy'                   => $CreatedBy
          );
          $insertManagerTable = 'Branch_has_Manager';
          $this->maintenance_model->insertFunction($insertManager, $insertManagerTable);
        // get branch manager id
          $generatedBranchManagerID = array(
            'table'                         => 'Branch_has_Manager'
            , 'column'                      => 'ManagerBranchId'
            , 'CreatedBy'                   => $CreatedBy
          );
          $genId = $this->maintenance_model->getGeneratedId2($generatedBranchManagerID);
        // update branch manager assigned
          $set1 = array( 
            'ManagerBranchId' => $genId['ManagerBranchId']
          );
          $condition1 = array( 
            'EmployeeNumber' => $EmployeeNumber['EmployeeNumber']
          );
          $table1 = 'branch_has_employee';
          $this->maintenance_model->updateFunction1($set1, $condition1, $table1);
      }

  }

  function getAllList()
  {
    $Branch = $this->uri->segment(3);
    $Status = $this->uri->segment(4);
    $Manager = $this->uri->segment(5);
    $DateHiredFrom = $this->uri->segment(6);
    $DateHiredTo = $this->uri->segment(7);
    $result = $this->employee_model->getAllList($Branch, $Status, $Manager, $DateHiredFrom, $DateHiredTo);
    foreach($result as $key=>$row)
    {
      $result[$key]['CreatedBy'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
    }
    echo json_encode($result);
  }

  function getCurrentPassword()
  {
    $output = $this->employee_model->getCurrentPassword(htmlentities($this->input->post('Password'), ENT_QUOTES), htmlentities($this->input->post('EmployeeNumber'), ENT_QUOTES));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getEmployeeDetails()
  {
    $output = $this->employee_model->getEmployeeDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function accessmanagement()
  {
    $CreatedBy = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    $EmployeeNumber = sprintf('%06d', $this->uri->segment(3));
    for($count = 0; $count < count($this->input->post('countRow')); $count++)
    {
      $set = array( 
        'StatusId' => $_POST['isSelected'][$count]
      );

      $condition = array( 
        'SubModuleId'     => $_POST['SubModuleId'][$count],
        'EmployeeNumber'  => $EmployeeNumber
      );
      $table = 'R_UserAccess';
      $this->maintenance_model->updateFunction1($set, $condition, $table);
    }
    // notification
      $this->session->set_flashdata('alertTitle','Success!'); 
      $this->session->set_flashdata('alertText','Access successfully saved!'); 
      $this->session->set_flashdata('alertType','success'); 
      redirect('home/accessmanagement/'. $this->uri->segment(3));
  }

  function AuditFunction($auditLogsManager, $auditAffectedEmployee, $ManagerId, $AffectedEmployee)
  {
    $AssignedBranchId = $this->session->userdata('BranchId');
    $CreatedBy = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    // manager and main logs 
      $insertMainLog = array(
        'Description'       => $auditLogsManager
        , 'CreatedBy'       => $CreatedBy
        , 'BranchId'        => $AssignedBranchId
      );
      $auditTable1 = 'R_Logs';
      $this->maintenance_model->insertFunction($insertMainLog, $auditTable1);
      $insertManagerAudit = array(
        'Description'         => $auditLogsManager
        , 'ManagerBranchId'   => $ManagerId
        , 'CreatedBy'         => $CreatedBy
        , 'BranchId'          => $AssignedBranchId
      );
      $auditTable3 = 'manager_has_notifications';
      $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable3);
    // employee log
      $insertEmpLog = array(
        'Description'       => $auditLogsManager
        , 'EmployeeNumber'  => $CreatedBy
        , 'CreatedBy'       => $CreatedBy
        , 'BranchId'        => $AssignedBranchId
      );
      $auditTable2 = 'employee_has_notifications';
      $this->maintenance_model->insertFunction($insertEmpLog, $auditTable2);
    // edited employee
      $insertEmpLog = array(
        'Description'       => $auditAffectedEmployee
        , 'EmployeeNumber'  => $AffectedEmployee
        , 'CreatedBy'       => $CreatedBy
        , 'BranchId'        => $AssignedBranchId
      );
      $auditTable2 = 'employee_has_notifications';
      $this->maintenance_model->insertFunction($insertEmpLog, $auditTable2);
  }

  function finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $ManagerId, $AffectedEmployeeNumber, $auditLoanDets, $ApplicationId, $independentTable, $independentColumn)
  {
    $CreatedBy = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    $insertMainLog = array(
      'Description'       => $auditLogsManager
      , 'CreatedBy'       => $CreatedBy
      , 'BranchId'        => $AssignedBranchId
    );
    $auditTable1 = 'R_Logs';
    $this->maintenance_model->insertFunction($insertMainLog, $auditTable1);
    $insertManagerAudit = array(
      'Description'         => $auditLogsManager
      , 'ManagerBranchId'   => $ManagerId
      , 'CreatedBy'         => $CreatedBy
      , 'BranchId'          => $AssignedBranchId
    );
    $auditTable3 = 'manager_has_notifications';
    $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable3);
    $insertEmpLog = array(
      'Description'       => $auditAffectedEmployee
      , 'EmployeeNumber'  => $AffectedEmployeeNumber
      , 'CreatedBy'       => $CreatedBy
      , 'BranchId'        => $AssignedBranchId
    );
    $auditTable2 = 'employee_has_notifications';
    $this->maintenance_model->insertFunction($insertEmpLog, $auditTable2);

    if($auditLoanDets != null)
    {
      $insertApplicationLog = array(
        'Description'       => $auditLoanDets
        , ''.$independentColumn.''   => $ApplicationId
        , 'CreatedBy'       => $CreatedBy
        , 'BranchId'        => $AssignedBranchId
      );
      $auditLoanApplicationTable = $independentTable;
      $this->maintenance_model->insertFunction($insertApplicationLog, $auditLoanApplicationTable);
    }
  }

  public function uploadForm3Excel()
  {
    if(isset($_FILES["form3UploadExcel"]["name"]))
    {
      $path = $_FILES["form3UploadExcel"]["tmp_name"];
      $obj = PHPExcel_IOFactory::load($path);

      $EmployeeData = array();
      $consolidatedDamagedItem = array();

      $dateCreated = date('Y-m-d H:i:s');
      $createdBy = $this->session->userdata('EmployeeNumber');

      $execution_time_limit = 300;
      set_time_limit($execution_time_limit);

      $highestRow = 0;

      foreach($obj->getWorksheetIterator() as $worksheet)
      {
        $sheetName = $worksheet->getTitle();
        $highestRow = $worksheet->getHighestDataRow();
        $highestCol = $worksheet->getHighestDataColumn();

        $title = $worksheet->getCellByColumnAndRow(0, 1)->getValue(); // CELL A1
        $rowCount = 0;

        if($sheetName == 'DATA')
        {
          for($row = 2; $row <= $highestRow; $row++)
          {
            $Salutation = $worksheet->getCellByColumnandRow(0, $row)->getValue();
            $LastName = $worksheet->getCellByColumnandRow(1, $row)->getValue();
            $FirstName = $worksheet->getCellByColumnandRow(2, $row)->getValue();
            $ExtName = $worksheet->getCellByColumnandRow(3, $row)->getValue();
            $MiddleName = $worksheet->getCellByColumnandRow(4, $row)->getValue();
            $Gender = $worksheet->getCellByColumnandRow(5, $row)->getValue();
            $Nationality = $worksheet->getCellByColumnandRow(6, $row)->getValue();
            $CivilStatus = $worksheet->getCellByColumnandRow(7, $row)->getValue();
            $DOB = $worksheet->getCellByColumnandRow(8, $row)->getFormattedValue();
            $DH = $worksheet->getCellByColumnandRow(9, $row)->getFormattedValue();
            $Position = $worksheet->getCellByColumnandRow(10, $row)->getValue();
            $EmpType = str_replace(' ', '', strtolower($worksheet->getCellByColumnandRow(11, $row)->getValue()));
            $Manager = $worksheet->getCellByColumnandRow(12, $row)->getValue();
            $Branch = $worksheet->getCellByColumnandRow(13, $row)->getValue();

            if($LastName != '' && $FirstName != '' && $Salutation != '' && $Gender != '' && $Nationality != '' && $CivilStatus != '' && $DOB != '' && $DH != '' && $EmpType != '' && $Branch != '' && $Position != '')
            {
              $employeeName = str_replace(' ', '', strtolower($LastName. ', '. $FirstName. ' ' . $MiddleName. ' ' . $ExtName));

              $SalutationId = $this->maintenance_model->getReferenceId('SalutationId', 'R_Salutation', $Salutation, 'Name');
              $GenderId = $this->maintenance_model->getReferenceId('SexId', 'r_sex', $Gender, 'Name');
              $CivilStatusId = $this->maintenance_model->getReferenceId('CivilStatusId', 'r_civilstatus', $CivilStatus, 'Name');
              $NationalityId = $this->maintenance_model->getReferenceId('NationalityId', 'r_nationality', $Nationality, 'Description');
              $PositionId = $this->maintenance_model->getReferenceId('PositionId', 'r_position', $Position, 'Name');
              $BranchId = $this->maintenance_model->getReferenceId('BranchId', 'r_branches', $Branch, 'Name');
              $dbEmployeeName = $this->employee_model->getEmployeeDetailsByName($employeeName);

              $time = strtotime($DOB);
              $DateOfB = date('Y-m-d', $time);
              $time2 = strtotime($DH);
              $DateH = date('Y-m-d', $time2);

              if($dbEmployeeName['Name'] == null)
              {
                if($EmpType == 'employee') // get manager id
                {
                  if($Manager != '')
                  {
                    $ManagerId = $this->maintenance_model->getReferenceId('ManagerBranchId', 'branch_has_manager', $Manager, 'EmployeeNumber');
                    if($ManagerId['Id'] != null && $SalutationId['Id'] != null && $GenderId['Id'] != null && $NationalityId['Id'] != null && $CivilStatusId['Id'] != null && $PositionId['Id'] != null && $BranchId['Id'] != null)
                    {
                      // employee
                        $data = array(
                          'Salutation'    => $SalutationId['Id'],
                          'LastName'      => $LastName,
                          'FirstName'     => $FirstName,
                          'ExtName'       => $ExtName,
                          'MiddleName'    => $MiddleName,
                          'Sex'           => $GenderId['Id'],
                          'Nationality'   => $NationalityId['Id'],
                          'CivilStatus'   => $CivilStatusId['Id'],
                          'DateOfBirth'   => $DateOfB,
                          'DateHired'     => $DateH,
                          'PositionId'    => $PositionId['Id'],
                          'StatusId'      => 2,
                          'DateCreated'   => $dateCreated,
                          'CreatedBy'     => $createdBy,
                          'ManagerId'     => $ManagerId['Id'],
                        );
                        $table = 'R_Employee';
                        $this->maintenance_model->insertFunction($data, $table);
                      // get employee generated id
                        $auditData1 = array(
                          'table'                 => 'R_Employee'
                          , 'column'              => 'EmployeeId'
                        );
                        $EmployeeId = $this->maintenance_model->getGeneratedId($auditData1);
                        $EmployeeNumber = sprintf('%06d', $EmployeeId['EmployeeId']);
                      // update employee numbers
                        $set = array( 
                          'EmployeeNumber' => $EmployeeNumber
                        );

                        $condition = array( 
                          'EmployeeId' => $EmployeeId['EmployeeId']
                        );
                        $table = 'R_Employee';
                        $this->maintenance_model->updateFunction1($set, $condition, $table);
                      // employee
                        $data = array(
                          'EmployeeNumber'    => $EmployeeNumber,
                          'BranchId'      => $BranchId['Id'],
                          'StatusId'      => 1,
                          'DateCreated'   => $dateCreated,
                          'CreatedBy'     => $createdBy,
                          'ManagerBranchId'     => $ManagerId['Id'],
                        );
                        $table = 'branch_has_employee';
                        $this->maintenance_model->insertFunction($data, $table);
                      // admin audits finalss
                        $auditLogsManager = 'Imported employee #'. $EmployeeNumber . ' in employee list.';
                        $auditAffectedEmployee = 'Imported to employee list.';
                        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber);
                      $rowCount = $rowCount + 1;
                    }
                  }
                }
                else if($EmpType == 'manager') // insert into manager table
                {
                  $ManagerId = $this->maintenance_model->getReferenceId('ManagerBranchId', 'branch_has_manager', $Manager, 'EmployeeNumber');
                  if($ManagerId['Id'] != null && $SalutationId['Id'] != null && $GenderId['Id'] != null && $NationalityId['Id'] != null && $CivilStatusId['Id'] != null && $PositionId['Id'] != null && $BranchId['Id'] != null)
                  {
                    // employee
                      $data = array(
                        'Salutation'    => $SalutationId['Id'],
                        'LastName'      => $LastName,
                        'FirstName'     => $FirstName,
                        'ExtName'       => $ExtName,
                        'MiddleName'    => $MiddleName,
                        'Sex'           => $GenderId['Id'],
                        'Nationality'   => $NationalityId['Id'],
                        'CivilStatus'   => $CivilStatusId['Id'],
                        'DateOfBirth'   => $DateOfB,
                        'DateHired'     => $DateH,
                        'PositionId'    => $PositionId['Id'],
                        'StatusId'      => 2,
                        'DateCreated'   => $dateCreated,
                        'CreatedBy'     => $createdBy,
                      );
                      $table = 'R_Employee';
                      $this->maintenance_model->insertFunction($data, $table);

                      // get employee generated id
                        $auditData1 = array(
                          'table'                 => 'R_Employee'
                          , 'column'              => 'EmployeeId'
                        );
                        $EmployeeId = $this->maintenance_model->getGeneratedId($auditData1);
                        $EmployeeNumber = sprintf('%06d', $EmployeeId['EmployeeId']);
                      // update employee numbers
                        $set = array( 
                          'EmployeeNumber' => $EmployeeNumber
                        );

                        $condition = array( 
                          'EmployeeId' => $EmployeeId['EmployeeId']
                        );
                        $table = 'R_Employee';
                        $this->maintenance_model->updateFunction1($set, $condition, $table);
                      // insert into branch manager
                        $data2 = array(
                          'EmployeeNumber'    => $SalutationId['Id'],
                          'BranchId'          => $LastName,
                          'StatusId'          => 1,
                          'DateCreated'       => $dateCreated,
                          'CreatedBy'         => $createdBy,
                        );
                        $table2 = 'branch_has_manager';
                        $this->maintenance_model->insertFunction($data2, $table2);
                        $auditData2 = array(
                          'table'                 => 'branch_has_manager'
                          , 'column'              => 'ManagerBranchId'
                        );
                        $NewManagerId = $this->maintenance_model->getGeneratedId($auditData2);
                      // update manager id of manager
                        $set2 = array( 
                          'ManagerId' => $NewManagerId['ManagerBranchId']
                        );

                        $condition2 = array( 
                          'EmployeeId' => $EmployeeId['EmployeeId']
                        );
                        $table2 = 'R_Employee';
                        $this->maintenance_model->updateFunction1($set2, $condition2, $table2);
                      // admin audits finalss
                        $auditLogsManager = 'Imported employee #'. $EmployeeNumber;
                        $auditAffectedEmployee = 'Imported to employee list.';
                        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber);
                      $rowCount = $rowCount + 1;
                  }
                }
              }
            }
          } // END FOR LOOP

          echo "Employee record(s) successfully saved! " . $rowCount . " records inserted.";
        }

      } // END FOREACH
    }
    else
    {
      echo "File not set";
    }
  }

}
