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
      $auditDetail = 'Security Question updated.';
      $insertData = array(
        'Description' => $auditDetail,
        'CreatedBy' => $EmployeeNumber
      );
      $auditTable = 'R_Logs';
      $this->maintenance_model->insertFunction($insertData, $auditTable);
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
    // notification
      $this->session->set_flashdata('alertTitle','Success!'); 
      $this->session->set_flashdata('alertText','Security question successfully set!'); 
      $this->session->set_flashdata('alertType','success'); 
    
    redirect('home/userprofile');
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
            , 'StatusId'                    => 1
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

        // insert into r_userrole
          // foreach ($_POST['roleId'] as $value) 
          // {
          //   $insertRoles = array(
          //     'EmployeeNumber'                    => $generatedEmployeeNumber
          //     , 'RoleId'                          => $value
          //     , 'Password'                        => $generatedEmployeeNumber
          //     , 'CreatedBy'                       => $CreatedBy
          //     , 'UpdatedBy'                       => $CreatedBy
          //   );
          //   $insertRoleTable = 'R_Userrole';
          //   $this->maintenance_model->insertFunction($insertRoles, $insertRoleTable);
          // }

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
                'Description'                   => 'Added '.$EmployeeName.' with employee number ' . $generatedEmployeeNumber . ' to your branch. Please notify ' . $EmployeeName . ' that assigned password is assigned employee number.'
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

        // admin audits
          $auditquery = $this->employee_model->getEmployeeDetail($EmployeeId['EmployeeId']);
          $auditDetail = 'Added '.$auditquery['Name'].' in employee list.';
          $insertData = array(
            'Description'   => $auditDetail,
            'CreatedBy'     => $CreatedBy,
            'DateCreated'   => $DateNow
          );
          $this->maintenance_model->insertAdminLog($insertData);

        // employee audits
          $auditEmployee = 'Added in employee list.';
          $insertAuditEmployee = array(
            'Description'       => $auditEmployee,
            'CreatedBy'         => $CreatedBy,
            'EmployeeNumber'    => $generatedEmployeeNumber,
            'DateCreated'       => $DateNow
          );
          $auditTable = 'employee_has_notifications';
          $this->maintenance_model->insertFunction($insertAuditEmployee, $auditTable);

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
          $auditMainAndManagerLog = 'Added new contact record #CN-' . $rowNumber['number'].' for employee #'. $this->uri->segment(4);
          $auditEmpDetail = 'Added new contact record #CN-' . $rowNumber['number'];
          $insertEmpLog = array(
            'Description'       => $auditEmpDetail
            , 'EmployeeNumber'  => $this->uri->segment(4)
            , 'CreatedBy'       => $CreatedBy
          );
          $insertMainLog = array(
            'Description'       => $auditMainAndManagerLog
            , 'CreatedBy'       => $CreatedBy
          );
          $insertManagerAudit = array(
            'Description'         => $auditMainAndManagerLog
            , 'ManagerBranchId'   => $employeeDetail['ManagerBranchId']
            , 'CreatedBy'         => $CreatedBy
          );
          $auditTable2 = 'R_Logs';
          $this->maintenance_model->insertFunction($insertMainLog, $auditTable2);
          $auditTable3 = 'employee_has_notifications';
          $this->maintenance_model->insertFunction($insertEmpLog, $auditTable3);
          $auditTable4 = 'manager_has_notifications';
          $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable4);
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
          $auditMainAndManagerLog = 'Added new email record #EA-' . $rowNumber['number'].' for employee #'. $this->uri->segment(4);
          $auditEmpDetail = 'Added new email record #EA-' . $rowNumber['number'];
          $insertEmpLog = array(
            'Description'       => $auditEmpDetail
            , 'EmployeeNumber'  => $this->uri->segment(4)
            , 'CreatedBy'       => $CreatedBy
          );
          $insertMainLog = array(
            'Description'       => $auditMainAndManagerLog
            , 'CreatedBy'       => $CreatedBy
          );
          $insertManagerAudit = array(
            'Description'         => $auditMainAndManagerLog
            , 'ManagerBranchId'   => $employeeDetail['ManagerBranchId']
            , 'CreatedBy'         => $CreatedBy
          );
          $auditTable2 = 'R_Logs';
          $this->maintenance_model->insertFunction($insertMainLog, $auditTable2);
          $auditTable3 = 'employee_has_notifications';
          $this->maintenance_model->insertFunction($insertEmpLog, $auditTable3);
          $auditTable4 = 'manager_has_notifications';
          $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable4);
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
            , 'CreatedBy'                       => $EmployeeNumber['EmployeeNumber']
          );
          $insertTableAddress = 'r_address';
          $this->maintenance_model->insertFunction($insertDataAddress, $insertTableAddress);
        // get address id
          $generatedIdData = array(
            'table'                 => 'r_address'
            , 'column'              => 'AddressId'
          );
          $AddressId = $this->maintenance_model->getGeneratedId($generatedIdData);
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
        // insert into employee address      
          $insertDataAddress2 = array(
            'EmployeeNumber'                    => $this->uri->segment(4)
            , 'AddressId'                       => $AddressId['AddressId']
            , 'IsPrimary'                       => htmlentities($_POST['isPrimary'], ENT_QUOTES)
            , 'CreatedBy'                       => $EmployeeNumber['EmployeeNumber']
            , 'UpdatedBy'                       => $EmployeeNumber['EmployeeNumber']
          );
          $insertTableAddress2 = 'employee_has_address';
          $this->maintenance_model->insertFunction($insertDataAddress2, $insertTableAddress2);

        // insert into main logs
          $auditDetail = 'Added new address for employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertData = array(
            'Description' => $auditDetail
            , 'CreatedBy' => $CreatedBy
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        // insert into employee notification
          $auditDetail2 = 'Added new address.';
          $insertData2 = array(
            'Description' => $auditDetail2
            , 'CreatedBy' => $CreatedBy
          );
          $auditTable2 = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData2, $auditTable2);
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
            $auditMainAndManagerLog = 'Added new ID record #ID-' . $rowNumber['number'].' for employee #'. $this->uri->segment(4);
            $auditEmpDetail = 'Added new ID record #ID-' . $rowNumber['number'];
            $insertEmpLog = array(
              'Description'       => $auditEmpDetail
              , 'EmployeeNumber'  => $this->uri->segment(4)
              , 'CreatedBy'       => $CreatedBy
            );
            $insertMainLog = array(
              'Description'       => $auditMainAndManagerLog
              , 'CreatedBy'       => $CreatedBy
            );
            $insertManagerAudit = array(
              'Description'         => $auditMainAndManagerLog
              , 'ManagerBranchId'   => $employeeDetail['ManagerBranchId']
              , 'CreatedBy'         => $CreatedBy
            );
            $auditTable2 = 'R_Logs';
            $this->maintenance_model->insertFunction($insertMainLog, $auditTable2);
            $auditTable3 = 'employee_has_notifications';
            $this->maintenance_model->insertFunction($insertEmpLog, $auditTable3);
            $auditTable4 = 'manager_has_notifications';
            $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable4);
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
    else if($this->uri->segment(3) == 7 || $this->uri->segment(3) == 8) // add profile picture
    {
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
        // insert into address table
          $insertData = array(
            'FileName'                                => htmlentities($attachment, ENT_QUOTES)
            , 'EmployeeNumber'                        => $EmployeeNumber['EmployeeNumber']
          );
          $insertTable = 'r_ProfilePicture';
          $this->maintenance_model->insertFunction($insertData, $insertTable);
        // notifications
          $auditMainAndManagerLog = 'Changed profile picture.';
          $auditEmpDetail = 'Changed profile picture.';
          $insertEmpLog = array(
            'Description'       => $auditEmpDetail
            , 'EmployeeNumber'  => $this->uri->segment(4)
            , 'CreatedBy'       => $CreatedBy
          );
          $insertMainLog = array(
            'Description'       => $auditMainAndManagerLog
            , 'CreatedBy'       => $CreatedBy
          );
          $insertManagerAudit = array(
            'Description'         => $auditMainAndManagerLog
            , 'ManagerBranchId'   => $employeeDetail['ManagerBranchId']
            , 'CreatedBy'         => $CreatedBy
          );
          $auditTable2 = 'R_Logs';
          $this->maintenance_model->insertFunction($insertMainLog, $auditTable2);
          $auditTable3 = 'employee_has_notifications';
          $this->maintenance_model->insertFunction($insertEmpLog, $auditTable3);
          $auditTable4 = 'manager_has_notifications';
          $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable4);
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
    else if($this->uri->segment(3) == 9) // add profile picture
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
        // notifications
          $auditMainAndManagerLog = 'Changed profile picture.'; // of borrower name
          $auditBorrowerDetail = 'Changed profile picture.';
          $insertBorrowerAudit = array(
            'Description'       => $auditBorrowerDetail
            , 'BorrowerId'      => $this->uri->segment(4)
            , 'CreatedBy'       => $CreatedBy
          );
          $auditTable4 = 'borrower_has_notifications';
          $this->maintenance_model->insertFunction($insertBorrowerAudit, $auditTable4);
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
        // insert into main logs
          $auditDetail = 'Updated first name from '.$employeeDetail['FirstName'].' to '.htmlentities($_POST['FirstName'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertAudit = array(
            'Description' => $auditDetail,
            'CreatedBy' => $CreatedBy
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertAudit, $auditTable);
        // insert into employee notification
          $auditEmployee = 'Updated first name from '.$employeeDetail['FirstName'].' to '.htmlentities($_POST['FirstName'], ENT_QUOTES);
          $insertEmployeeAudit = array(
            'Description' => $auditEmployee,
            'CreatedBy' => $CreatedBy,
            'EmployeeNumber'  => $EmployeeNumber['EmployeeNumber']
          );
          $auditEmployeeTable = 'employee_has_notifications';
          $this->maintenance_model->insertFunction($insertEmployeeAudit, $auditEmployeeTable);
        // insert into manager notification
          $auditManager = 'Updated first name from '.$employeeDetail['FirstName'].' to '.htmlentities($_POST['FirstName'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertManagerAudit = array(
            'Description' => $auditManager,
            'CreatedBy' => $CreatedBy,
            'ManagerBranchId'  => $employeeDetail['ManagerBranchId']
          );
          $auditManagerTable = 'manager_has_notifications';
          $this->maintenance_model->insertFunction($insertManagerAudit, $auditManagerTable);

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
        // insert into main logs
          $auditDetail = 'Updated middle name from '.$employeeDetail['MiddleName'].' to '.htmlentities($_POST['MiddleName'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $CreatedBy
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        // insert into employee notification
          $auditEmployee = 'Updated middle name from '.$employeeDetail['MiddleName'].' to '.htmlentities($_POST['MiddleName'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertEmployeeAudit = array(
            'Description' => $auditEmployee,
            'CreatedBy' => $CreatedBy,
            'EmployeeNumber'  => $EmployeeNumber['EmployeeNumber']
          );
          $auditEmployeeTable = 'employee_has_notifications';
          $this->maintenance_model->insertFunction($insertEmployeeAudit, $auditEmployeeTable);
        // insert into manager notification
          $auditManager = 'Updated middle name from '.$employeeDetail['MiddleName'].' to '.htmlentities($_POST['MiddleName'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertManagerAudit = array(
            'Description' => $auditManager,
            'CreatedBy' => $CreatedBy,
            'ManagerBranchId'  => $employeeDetail['ManagerBranchId']
          );
          $auditManagerTable = 'manager_has_notifications';
          $this->maintenance_model->insertFunction($insertManagerAudit, $auditManagerTable);

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
        // insert into main logs
          $auditDetail = 'Updated last name from '.$employeeDetail['LastName'].' to '.htmlentities($_POST['LastName'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $CreatedBy
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        // insert into employee notification
          $auditEmployee = 'Updated last name from '.$employeeDetail['LastName'].' to '.htmlentities($_POST['LastName'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertEmployeeAudit = array(
            'Description' => $auditEmployee,
            'CreatedBy' => $CreatedBy,
            'EmployeeNumber'  => $EmployeeNumber['EmployeeNumber']
          );
          $auditEmployeeTable = 'employee_has_notifications';
          $this->maintenance_model->insertFunction($insertEmployeeAudit, $auditEmployeeTable);
        // insert into manager notification
          $auditManager = 'Updated last name from '.$employeeDetail['LastName'].' to '.htmlentities($_POST['LastName'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertManagerAudit = array(
            'Description' => $auditManager,
            'CreatedBy' => $CreatedBy,
            'ManagerBranchId'  => $employeeDetail['ManagerBranchId']
          );
          $auditManagerTable = 'manager_has_notifications';
          $this->maintenance_model->insertFunction($insertManagerAudit, $auditManagerTable);

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
        // insert into main logs
          $auditDetail = 'Updated extension name from '.$employeeDetail['ExtName'].' to '.htmlentities($_POST['ExtName'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $CreatedBy
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        // insert into employee notification
          $auditEmployee = 'Updated extension name from '.$employeeDetail['ExtName'].' to '.htmlentities($_POST['ExtName'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertEmployeeAudit = array(
            'Description' => $auditEmployee,
            'CreatedBy' => $CreatedBy,
            'EmployeeNumber'  => $EmployeeNumber['EmployeeNumber']
          );
          $auditEmployeeTable = 'employee_has_notifications';
          $this->maintenance_model->insertFunction($insertEmployeeAudit, $auditEmployeeTable);
        // insert into manager notification
          $auditManager = 'Updated extension name from '.$employeeDetail['ExtName'].' to '.htmlentities($_POST['ExtName'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertManagerAudit = array(
            'Description' => $auditManager,
            'CreatedBy' => $CreatedBy,
            'ManagerBranchId'  => $employeeDetail['ManagerBranchId']
          );
          $auditManagerTable = 'manager_has_notifications';
          $this->maintenance_model->insertFunction($insertManagerAudit, $auditManagerTable);

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
        // insert into main logs
          $auditDetail = 'Updated birth date from '.$oldData.' to '.$newData.' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $CreatedBy
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        // insert into employee notification
          $auditEmployee = 'Updated birth date from '.$oldData.' to '.$newData.' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertEmployeeAudit = array(
            'Description' => $auditEmployee,
            'CreatedBy' => $CreatedBy,
            'EmployeeNumber'  => $EmployeeNumber['EmployeeNumber']
          );
          $auditEmployeeTable = 'employee_has_notifications';
          $this->maintenance_model->insertFunction($insertEmployeeAudit, $auditEmployeeTable);
        // insert into manager notification
          $auditManager = 'Updated birth date from '.$oldData.' to '.$newData.' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertManagerAudit = array(
            'Description' => $auditManager,
            'CreatedBy' => $CreatedBy,
            'ManagerBranchId'  => $employeeDetail['ManagerBranchId']
          );
          $auditManagerTable = 'manager_has_notifications';
          $this->maintenance_model->insertFunction($insertManagerAudit, $auditManagerTable);

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
        // insert into main logs
          $auditDetail = 'Updated salutation from '.$oldDetail['Name'].' to '.htmlentities($newDetail['Name'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $CreatedBy
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        // insert into employee notification
          $auditEmployee = 'Updated salutation from '.$oldDetail['Name'].' to '.htmlentities($newDetail['Name'], ENT_QUOTES);
          $insertEmployeeAudit = array(
            'Description'     => $auditEmployee,
            'CreatedBy'       => $CreatedBy,
            'EmployeeNumber'  => $EmployeeNumber['EmployeeNumber']
          );
          $auditEmployeeTable = 'employee_has_notifications';
          $this->maintenance_model->insertFunction($insertEmployeeAudit, $auditEmployeeTable);
        // insert into manager notification
          $auditManager = 'Updated salutation from '.$oldDetail['Name'].' to '.htmlentities($newDetail['Name'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertManagerAudit = array(
            'Description' => $auditManager,
            'CreatedBy' => $CreatedBy,
            'ManagerBranchId' => $employeeDetail['ManagerBranchId']
          );
          $auditManagerTable = 'manager_has_notifications';
          $this->maintenance_model->insertFunction($insertManagerAudit, $auditManagerTable);

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
        // insert into main logs
          $auditDetail = 'Updated gender from '.$oldDetail['Name'].' to '.htmlentities($newDetail['Name'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $CreatedBy
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        // insert into employee notification
          $auditEmployee = 'Updated gender from '.$oldDetail['Name'].' to '.htmlentities($newDetail['Name'], ENT_QUOTES);
          $insertEmployeeAudit = array(
            'Description' => $auditEmployee,
            'CreatedBy' => $CreatedBy,
            'EmployeeNumber'  => $EmployeeNumber['EmployeeNumber']
          );
          $auditEmployeeTable = 'employee_has_notifications';
          $this->maintenance_model->insertFunction($insertEmployeeAudit, $auditEmployeeTable);
        // insert into manager notification
          $auditManager = 'Updated gender from '.$oldDetail['Name'].' to '.htmlentities($newDetail['Name'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertManagerAudit = array(
            'Description' => $auditManager,
            'CreatedBy' => $CreatedBy,
            'ManagerBranchId' => $employeeDetail['ManagerBranchId']
          );
          $auditManagerTable = 'manager_has_notifications';
          $this->maintenance_model->insertFunction($insertManagerAudit, $auditManagerTable);

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
        // insert into main logs
          $auditDetail = 'Updated nationality from '.$oldDetail['Description'].' to '.htmlentities($newDetail['Description'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $CreatedBy
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        // insert into employee notification
          $auditEmployee = 'Updated nationality from '.$oldDetail['Description'].' to '.htmlentities($newDetail['Description'], ENT_QUOTES);
          $insertEmployeeAudit = array(
            'Description' => $auditEmployee,
            'CreatedBy' => $CreatedBy,
            'EmployeeNumber'  => $EmployeeNumber['EmployeeNumber']
          );
          $auditEmployeeTable = 'employee_has_notifications';
          $this->maintenance_model->insertFunction($insertEmployeeAudit, $auditEmployeeTable);
        // insert into manager notification
          $auditManager = 'Updated nationality from '.$oldDetail['Description'].' to '.htmlentities($newDetail['Description'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertManagerAudit = array(
            'Description' => $auditManager,
            'CreatedBy' => $CreatedBy,
            'ManagerBranchId'  => $employeeDetail['ManagerBranchId']
          );
          $auditManagerTable = 'manager_has_notifications';
          $this->maintenance_model->insertFunction($insertManagerAudit, $auditManagerTable);

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
        // insert into main logs
          $auditDetail = 'Updated civil status from '.$oldDetail['Name'].' to '.htmlentities($newDetail['Name'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $CreatedBy
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        // insert into employee notification
          $auditEmployee = 'Updated civil status from '.$oldDetail['Name'].' to '.htmlentities($newDetail['Name'], ENT_QUOTES);
          $insertEmployeeAudit = array(
            'Description' => $auditEmployee,
            'CreatedBy' => $CreatedBy,
            'EmployeeNumber'  => $EmployeeNumber['EmployeeNumber']
          );
          $auditEmployeeTable = 'employee_has_notifications';
          $this->maintenance_model->insertFunction($insertEmployeeAudit, $auditEmployeeTable);
        // insert into manager notification
          $auditManager = 'Updated civil status from '.$oldDetail['Name'].' to '.htmlentities($newDetail['Name'], ENT_QUOTES).' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertManagerAudit = array(
            'Description' => $auditManager,
            'CreatedBy' => $CreatedBy,
            'ManagerBranchId'  => $employeeDetail['ManagerBranchId']
          );
          $auditManagerTable = 'manager_has_notifications';
          $this->maintenance_model->insertFunction($insertManagerAudit, $auditManagerTable);

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
        // insert into main logs
          $auditDetail = 'Updated date hired from '.$oldData2.' to '.$newData2.' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $CreatedBy
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        // insert into employee notification
          $auditEmployee = 'Updated date hired from '.$oldData2.' to '.$newData2;
          $insertEmployeeAudit = array(
            'Description' => $auditEmployee,
            'CreatedBy' => $CreatedBy,
            'EmployeeNumber'  => $EmployeeNumber['EmployeeNumber']
          );
          $auditEmployeeTable = 'employee_has_notifications';
          $this->maintenance_model->insertFunction($insertEmployeeAudit, $auditEmployeeTable);
        // insert into manager notification
          $auditManager = 'Updated date hired from '.$oldData2.' to '.$newData2.' of employee #'. $EmployeeNumber['EmployeeNumber'];
          $insertManagerAudit = array(
            'Description' => $auditManager,
            'CreatedBy' => $CreatedBy,
            'ManagerBranchId'  => $employeeDetail['ManagerBranchId']
          );
          $auditManagerTable = 'manager_has_notifications';
          $this->maintenance_model->insertFunction($insertManagerAudit, $auditManagerTable);

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
  }

  function getAllList()
  {
    $result = $this->employee_model->getAllList();
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

}
