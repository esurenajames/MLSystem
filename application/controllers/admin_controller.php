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
		$this->load->model('borrower_model');
    date_default_timezone_set('Asia/Manila');
    $this->load->library('Pdf');

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

  public function getReportEmployees()
  {
    $json = [];
    if(!empty($this->input->get("q")))
    {
      $keyword = $this->input->get("q");
      $json = $this->admin_model->getReportEmployees($keyword);
    }
    echo json_encode($json);
  }

  public function getBorrowers()
  {
    $json = [];
    if(!empty($this->input->get("q")))
    {
      $keyword = $this->input->get("q");
      $json = $this->admin_model->getBorrowers($keyword);
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

  function getRegionList()
  {
    echo $this->maintenance_model->getRegionList();
  }

  function getProvinces()
  {
    echo $this->maintenance_model->getProvinces($this->input->post('RegionId'));
  }

  function getApprovers()
  {
    echo $this->maintenance_model->getApprovers();
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

  function getDropDownEmployees()
  {
    echo $this->maintenance_model->getDropDownEmployees($this->input->post('BranchId'));
  }

  function getBarangays()
  {
    echo $this->maintenance_model->getBarangays($this->input->post('Id'));
  }

  function getCharges()
  {
    $output = $this->maintenance_model->getCharges();
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function addEmployees()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    if($this->uri->segment(3) == 1) // update Security Question
    {
      // audits
        $auditDetail = 'Security question updated.';
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

  function addUser()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    if($this->uri->segment(3) == 1) // update Security Question
    {
      // admin audits finalss
        $auditLogsManager = 'Changed temporary password.';
        $auditAffectedEmployee = 'Changed temporary password.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
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

      // update temporary password
        $set = array( 
          'Password'  => $_POST['NewPassword'],
          'IsNew'     => 0
        );

        $condition = array( 
          'EmployeeNumber' => $EmployeeNumber
        );
        $table = 'r_userrole';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // notification
        $this->session->set_flashdata('logout','Temporary password successfully changed.'); 
      
        $loginSession = array(
          'logged_in' => 0,
        );
        $this->session->set_userdata($loginSession);
        redirect(site_url());
    }
    else // add user
    {
      // insert
        $insertRoles = array(
          'EmployeeNumber'                    => $_POST['selectEmployee']
          , 'Password'                        => $_POST['selectEmployee']
          , 'CreatedBy'                       => $EmployeeNumber
          , 'UpdatedBy'                       => $EmployeeNumber
        );
        $insertRoleTable = 'R_Userrole';
        $this->maintenance_model->insertFunction($insertRoles, $insertRoleTable);
      // insert roles
        $employeeRoles = $this->employee_model->getSubmodules();
        foreach ($employeeRoles as $roles) 
        {
          $insertData = array(
            'EmployeeNumber'              => $_POST['selectEmployee']
            , 'StatusId'                  => 0
            , 'SubModuleId'               => $roles['SubModuleId']
            , 'Code'                      => $roles['Code']
            , 'ModuleId'                  => $roles['ModuleId']
          );
          $insertTable = 'R_UserAccess';
          $this->maintenance_model->insertFunction($insertData, $insertTable);
        }
      // admin audits
        $auditLogsManager = 'Employee #' . $_POST['selectEmployee'] . ' has been added as user.';
        $auditAffectedEmployee = 'Added as user.';
        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber);
      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Successfully added user role!'); 
        $this->session->set_flashdata('alertType','success'); 
        
        redirect('home/addUser');

    }
  }

  function AddBank()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $BankDetail = $this->admin_model->getBankDetails($_POST['BankId']);
    $DateNow = date("Y-m-d H:i:s");
    if ($_POST['FormType'] == 1) // add Bank
    {
      $data = array(
        'BankName'                 => htmlentities($_POST['BankName'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
        , 'AccountNumber'          => htmlentities($_POST['AccountNumber'], ENT_QUOTES)
      );
      $query = $this->admin_model->countBank($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Bank details
          $insertBank = array(
            'BankName'                     => htmlentities($_POST['BankName'], ENT_QUOTES)
            , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'AccountNumber'          => htmlentities($_POST['AccountNumber'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertBankTable = 'R_Bank';
          $this->maintenance_model->insertFunction($insertBank, $insertBankTable);
        // get generated application id
          $getData = array(
            'table'                 => 'R_Bank'
            , 'column'              => 'BankId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $TransactionNumber = 'BNK-'.sprintf('%06d', $generatedId['BankId']);
          $auditLogsManager = 'Added bank #'.$TransactionNumber.' in bank setup.';
          $auditAffectedEmployee = 'Added bank #'.$TransactionNumber.' in bank setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Bank successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddBank');
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Bank already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddBank');
      }
    }
    else if($_POST['FormType'] == 2) // edit bank 
    {
      $data = array(
        'BankName'                 => htmlentities($_POST['BankName'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
        , 'AccountNumber'          => htmlentities($_POST['AccountNumber'], ENT_QUOTES)
      );
      $query = $this->admin_model->countBank($data);
      if($query == 0)
      {
        if($BankDetail['BankName'] != htmlentities($_POST['BankName'], ENT_QUOTES))
        {
          // update function
            $set = array( 
              'BankName' => htmlentities($_POST['BankName'], ENT_QUOTES)
            );
            $condition = array( 
              'BankId' => $_POST['BankId']
            );
            $table = 'R_Bank';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($BankDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
        {
          // update function
            $set = array(
              'Description' => htmlentities($_POST['Description'], ENT_QUOTES)
            );
            $condition = array(
              'BankId' => $_POST['BankId']
            );
            $table = 'R_Bank';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($BankDetail['AccountNumber'] != htmlentities($_POST['AccountNumber'], ENT_QUOTES))
        {
          // update function
            $set = array( 
              'AccountNumber' => htmlentities($_POST['AccountNumber'], ENT_QUOTES)
            );
            $condition = array( 
              'BankId' => $_POST['BankId']
            );
            $table = 'R_Bank';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        // admin audits finalss
          $TransactionNumber = 'BNK-'.sprintf('%06d', $_POST['BankId']);
          $auditLogsManager = 'Updated bank details #'.$TransactionNumber.' in bank setup.';
          $auditAffectedEmployee = 'Updated bank details #'.$TransactionNumber.' in bank setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notif
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Bank details successfully updated!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddBank/');
      }
    }
    else // if existing
    {
      // notif
      $this->session->set_flashdata('alertTitle','Warning!'); 
      $this->session->set_flashdata('alertText','Bank details already existing!'); 
      $this->session->set_flashdata('alertType','warning'); 
      redirect('home/AddBank/');
    }
  }

  function AddBranch()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $BranchDetail = $this->admin_model->getBranchDetails($_POST['BranchId']);
    $DateNow = date("Y-m-d H:i:s");
    $time = strtotime($_POST['DateFrom']);
    $DateFrom = date('Y-m-d', $time);
    $time = strtotime($_POST['DateTo']);
    $DateTo = date('Y-m-d', $time);
    if ($_POST['FormType'] == 1) // add Branch
    {
      $data = array(
        'Name'                     => htmlentities($_POST['Branch'], ENT_QUOTES)
        , 'Code'                   => htmlentities($_POST['Code'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
        , 'DateFromLease'          => htmlentities($DateFrom, ENT_QUOTES)
        , 'DateToLease'          => htmlentities($DateTo, ENT_QUOTES)
        , 'LeaseMonthly'          => htmlentities($_POST['Monthly'], ENT_QUOTES)
      );
      $query = $this->admin_model->countBranch($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Branch details
          $insertBranch = array(
            'Name'                     => htmlentities($_POST['Branch'], ENT_QUOTES)
            , 'Code'                   => htmlentities($_POST['Code'], ENT_QUOTES)
            , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'DateFromLease'          => htmlentities($DateFrom, ENT_QUOTES)
            , 'DateToLease'            => htmlentities($DateTo, ENT_QUOTES)
            , 'LeaseMonthly'           => htmlentities($_POST['Monthly'], ENT_QUOTES)
            , 'CompanyId'              => 1
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertBranchTable = 'R_Branches';
          $this->maintenance_model->insertFunction($insertBranch, $insertBranchTable);
        // get generated application id
          $getData = array(
            'table'                 => 'R_Branches'
            , 'column'              => 'BranchId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $TransactionNumber = 'BRNCH-'.sprintf('%06d', $generatedId['BranchId']);
          $auditLogsManager = 'Added branch #'.$TransactionNumber.' in branches setup.';
          $auditAffectedEmployee = 'Added branch #'.$TransactionNumber.' in branches setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Branch successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddBranch/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Branch already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddBranch');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Branch 
    {
      if($BranchDetail['Name'] != htmlentities($_POST['Branch'], ENT_QUOTES))
      {
        // update function
          $set = array( 
            'Name' => htmlentities($_POST['Branch'], ENT_QUOTES)
          );
          $condition = array( 
            'BranchId' => $_POST['BranchId']
          );
          $table = 'R_Branches';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($BranchDetail['Code'] != htmlentities($_POST['Code'], ENT_QUOTES))
      {
        // update function
          $set = array( 
            'Code' => htmlentities($_POST['Code'], ENT_QUOTES)
          );
          $condition = array( 
            'BranchId' => $_POST['BranchId']
          );
          $table = 'R_Branches';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($BranchDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
      {
        // update function
          $set = array( 
            'Description' => htmlentities($_POST['Description'], ENT_QUOTES)
          );
          $condition = array( 
            'BranchId' => $_POST['BranchId']
          );
          $table = 'R_Branches';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($BranchDetail['DateFromLease'] != htmlentities($DateFrom, ENT_QUOTES))
      {
        // update function
          $set = array( 
            'DateFromLease'          => htmlentities($DateFrom, ENT_QUOTES)
          );
          $condition = array( 
            'BranchId' => $_POST['BranchId']
          );
          $table = 'R_Branches';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($BranchDetail['DateToLease'] != htmlentities($DateTo, ENT_QUOTES))
      {
        // update function
          $set = array( 
            'DateToLease'          => htmlentities($DateTo, ENT_QUOTES)
          );
          $condition = array( 
            'BranchId' => $_POST['BranchId']
          );
          $table = 'R_Branches';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($BranchDetail['LeaseMonthly'] != htmlentities($_POST['Monthly'], ENT_QUOTES))
      {
        // update function
          $set = array( 
            'LeaseMonthly'          => htmlentities($_POST['Monthly'], ENT_QUOTES)
          );
          $condition = array( 
            'BranchId' => $_POST['BranchId']
          );
          $table = 'R_Branches';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      // admin audits finalss
        $TransactionNumber = 'BRNCH-'.sprintf('%06d', $_POST['BranchId']);
        $auditLogsManager = 'Updated branch detail #'.$TransactionNumber.' in branches setup.';
        $auditAffectedEmployee = 'Updated branch detail #'.$TransactionNumber.' in branches setup.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);

      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Branch details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddBranch/');
    }
  }

  function AddLoanType()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $LoanTypeDetail = $this->admin_model->getLoanTypeDetails($_POST['LoanTypeId']);
    if ($_POST['FormType'] == 1) // add LoanType
    {
      $data = array(
        'Name'                     => htmlentities($_POST['LoanType'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countLoanType($data);
      if($query == 0) // not existing
      {
        // insert LoanType details
          $insertLoanType = array(
            'Name'                     => htmlentities($_POST['LoanType'], ENT_QUOTES)
            , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertLoanTypeTable = 'R_Loans';
          $this->maintenance_model->insertFunction($insertLoanType, $insertLoanTypeTable);
        // get generated application id
          $getData = array(
            'table'                 => 'R_Loans'
            , 'column'              => 'LoanId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $TransactionNumber = 'LT-'.sprintf('%06d', $generatedId['LoanId']);
          $auditLogsManager = 'Added loan type #'.$TransactionNumber.' in loan type setup.';
          $auditAffectedEmployee = 'Added loan type #'.$TransactionNumber.' in loan type setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Loan type successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddLoanType/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Loan Type already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddLoanType');
      }
    }
    else if($_POST['FormType'] == 2) // Edit LoanType 
    {
      $data = array(
        'Name'                     => htmlentities($_POST['LoanType'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countLoanType($data);
      print_r($query);
      if($query == 0)
      {
        if($LoanTypeDetail['Name'] != htmlentities($_POST['LoanType'], ENT_QUOTES))
        {
          // update function
            $set = array( 
            'Name'                     => htmlentities($_POST['LoanType'], ENT_QUOTES)
            );
            $condition = array( 
              'LoanId' => $_POST['LoanId']
            );
            $table = 'R_Loans';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($LoanTypeDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
        {
          // update function
            $set = array( 
            'Description'                     => htmlentities($_POST['Description'], ENT_QUOTES)
            );
            $condition = array( 
              'LoanId' => $_POST['LoanId']
            );
            $table = 'R_Loans';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
      // admin audits finalss
        $TransactionNumber = 'LT-'.sprintf('%06d', $_POST['LoanId']);
        $auditLogsManager = 'Updated loan type #'.$TransactionNumber.' in loan type setup.';
        $auditAffectedEmployee = 'Updated loan type #'.$TransactionNumber.' in loan type setup.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Loan Type details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddLoanType/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Loan Type details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddLoanType/');
      }
    }
  }

  function AddCharge()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $ChargeDetail = $this->admin_model->getChargeDetails($_POST['ChargeId']);
    if ($_POST['FormType'] == 1) // add Bank
    {
      $data = array(
        'ChargeType'            => htmlentities($_POST['ChargeType'], ENT_QUOTES)
        , 'Name'                => htmlentities($_POST['ConditionalName'], ENT_QUOTES)
        , 'Description'         => htmlentities($_POST['Description'], ENT_QUOTES)
        , 'Amount'              => htmlentities($_POST['Amount'], ENT_QUOTES)
      );
      $query = $this->admin_model->countCharges($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Bank details
          $insertCharge = array(
            'ChargeType'            => htmlentities($_POST['ChargeType'], ENT_QUOTES)
            , 'Name'                => htmlentities($_POST['ConditionalName'], ENT_QUOTES)
            , 'Description'         => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'Amount'              => htmlentities($_POST['Amount'], ENT_QUOTES)
            , 'CreatedBy'           => $EmployeeNumber
            , 'UpdatedBy'           => $EmployeeNumber
          );
          $insertChargeTable = 'R_Charges';
          $this->maintenance_model->insertFunction($insertCharge, $insertChargeTable);
        // get generated application id
          $getData = array(
            'table'                 => 'R_Charges'
            , 'column'              => 'ChargeId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $TransactionNumber = 'CH-'.sprintf('%06d', $generatedId['ChargeId']);
          $auditLogsManager = 'Added additional charge #'.$TransactionNumber.' in additional charges setup.';
          $auditAffectedEmployee = 'Added additional charge #'.$TransactionNumber.' in additional charges setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Charge successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddConditional/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Charge already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddConditional');
      }
    }
    else if($_POST['FormType'] == 2) // Edit LoanType 
    {
      $data = array(
        'ChargeType'                     => htmlentities($_POST['ChargeType'], ENT_QUOTES)
        , 'Name'                   => htmlentities($_POST['ConditionalName'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
        , 'Amount'              => htmlentities($_POST['Amount'], ENT_QUOTES)
      );
      $query = $this->admin_model->countCharges($data);
      print_r($query);
      if($query == 0)
      {
        if($ChargeDetail['ChargeType'] != htmlentities($_POST['ChargeType'], ENT_QUOTES))
        {
          // update function
            $set = array( 
            'ChargeType'                     => htmlentities($_POST['ChargeType'], ENT_QUOTES)
            );
            $condition = array( 
              'ChargeId' => $_POST['ChargeId']
            );
            $table = 'R_Charges';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($ChargeDetail['Name'] != htmlentities($_POST['ConditionalName'], ENT_QUOTES))
        {
          // update function
            $set = array( 
            'Name'                     => htmlentities($_POST['ConditionalName'], ENT_QUOTES)
            );
            $condition = array( 
              'ChargeId' => $_POST['ChargeId']
            );
            $table = 'R_Charges';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($ChargeDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
        {
          // update function
            $set = array( 
            'Description'                     => htmlentities($_POST['Description'], ENT_QUOTES)
            );
            $condition = array( 
              'ChargeId' => $_POST['ChargeId']
            );
            $table = 'R_Charges';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($ChargeDetail['Amount'] != htmlentities($_POST['Amount'], ENT_QUOTES))
        {
          // update function
            $set = array( 
            'Amount'                     => htmlentities($_POST['Amount'], ENT_QUOTES)
            );
            $condition = array( 
              'ChargeId' => $_POST['ChargeId']
            );
            $table = 'R_Charges';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
      // admin audits finalss
        $TransactionNumber = 'CH-'.sprintf('%06d', $_POST['ChargeId']);
        $auditLogsManager = 'Updated additional charge #'.$TransactionNumber.' in additional charges setup.';
        $auditAffectedEmployee = 'Updated additional charge #'.$TransactionNumber.' in additional charges setup.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Conditional Charge details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddConditional/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Conditional Charge details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddConditional/');
      }
    }
  }

  function AddDisbursement()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DisbursementDetail = $this->admin_model->getDisbursementDetails($_POST['DisbursementId']);
    if ($_POST['FormType'] == 1) // add Disbursement
    {
      $data = array(
        'Name'                     => htmlentities($_POST['Disbursement'], ENT_QUOTES)
      );
      $query = $this->admin_model->countDisbursement($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Bank details
          $insertDisbursement = array(
            'Name'                     => htmlentities($_POST['Disbursement'], ENT_QUOTES)
            , 'CreatedBy'           => $EmployeeNumber
          );
          $insertDisbursementTable = 'R_DIsbursement';
          $this->maintenance_model->insertFunction($insertDisbursement, $insertDisbursementTable);
        // get generated application id
          $getData = array(
            'table'                 => 'R_DIsbursement'
            , 'column'              => 'DisbursementId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $TransactionNumber = 'DB-'.sprintf('%06d', $generatedId['DisbursementId']);
          $auditLogsManager = 'Added disbursement #'.$TransactionNumber.' in disbursement setup.';
          $auditAffectedEmployee = 'Added disbursement #'.$TransactionNumber.' in  disbursement setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Disbursement successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddDisbursement/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Disbursement already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddDisbursement');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Disbursement 
    {
      $data = array(
          'Name'                     => htmlentities($_POST['Disbursement'], ENT_QUOTES)
      );
      $query = $this->admin_model->countDisbursement($data);
      print_r($query);
      if($query == 0)
      {
        if($DisbursementDetail['Name'] != htmlentities($_POST['Disbursement'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$DisbursementDetail['Name'].' to '.htmlentities($_POST['Disbursement'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'Name'                     => htmlentities($_POST['Disbursement'], ENT_QUOTES)
            );
            $condition = array( 
              'DisbursementId' => $_POST['DisbursementId']
            );
            $table = 'R_Disbursement';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Disbursement details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddDisbursement/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Disbursement details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddDisbursement/');
      }
    }
  }

  function AddRequirement()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $RequirementDetail = $this->admin_model->getRequirementDetails($_POST['RequirementId']);
    if ($_POST['FormType'] == 1) // add Requirement
    {
      $data = array(
         'Name'                    => htmlentities($_POST['Requirement'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countRequirements($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Requirement details
          $insertRequirement = array(
             'Name'                    => htmlentities($_POST['Requirement'], ENT_QUOTES)
            , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'IsMandatory'            => htmlentities($_POST['isMandatory'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertRequirementTable = 'R_Requirements';
          $this->maintenance_model->insertFunction($insertRequirement, $insertRequirementTable);
        // get generated application id
          $getData = array(
            'table'                 => 'R_Requirements'
            , 'column'              => 'RequirementId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $TransactionNumber = 'REQ-'.sprintf('%06d', $generatedId['RequirementId']);
          $auditLogsManager = 'Added requirement #'.$TransactionNumber.' in requirements setup.';
          $auditAffectedEmployee = 'Added requirement #'.$TransactionNumber.' in requirements setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Requirement successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddRequirement/');
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Requirement already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddRequirement');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Requirement 
    {
      $data = array(
         'Name'                    => htmlentities($_POST['Requirement'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countRequirements($data);
      print_r($query);
      if($query == 0)
      {
        if($RequirementDetail['Name'] != htmlentities($_POST['Requirement'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$RequirementDetail['Name'].' to '.htmlentities($_POST['Requirement'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'Name'                     => htmlentities($_POST['Requirement'], ENT_QUOTES)
            );
            $condition = array( 
              'RequirementId' => $_POST['RequirementId']
            );
            $table = 'R_Requirements';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($RequirementDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$RequirementDetail['Description'].' to '.htmlentities($_POST['Description'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'Description'                     => htmlentities($_POST['Description'], ENT_QUOTES)
            );
            $condition = array( 
              'RequirementId' => $_POST['RequirementId']
            );
            $table = 'R_Requirements';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Requirement details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddRequirement/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Requirement details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddRequirement/');
      }
    }
  }

  function AddPosition()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $PositionDetail = $this->admin_model->getPositionDetails($_POST['PositionId']);
    if ($_POST['FormType'] == 1) // add Position
    {
      $data = array(
         'Name'                    => htmlentities($_POST['Position'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countPositions($data);
      if($query == 0) // not existing
      {
        // insert Bank details
          $insertPosition = array(
             'Name'                    => htmlentities($_POST['Position'], ENT_QUOTES)
            , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertPositionTable = 'R_Position';
          $this->maintenance_model->insertFunction($insertPosition, $insertPositionTable);
        // get generated application id
          $getData = array(
            'table'                 => 'R_Position'
            , 'column'              => 'PositionId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $detail = $this->maintenance_model->selectSpecific('R_Position', 'PositionId', $generatedId['PositionId']);
          $TransactionNumber = 'POS-'.sprintf('%06d', $generatedId['PositionId']);
          $auditLogsManager = 'Added employee position #'.$TransactionNumber.' in position setup.';
          $auditAffectedEmployee = 'Added employee position #'.$TransactionNumber.' in position setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Position successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddPosition/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Position already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddRequirement');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Position 
    {
      $count = $this->db->query("SELECT  COUNT(*) as ifUsed
                                                  FROM R_Employee
                                                        WHERE PositionId = ".$_POST['PositionId']."
      ")->row_array();
      if($count['ifUsed'] == 0)
      {
        $data = array(
           'Name'                    => htmlentities($_POST['Position'], ENT_QUOTES)
          , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
        );
        $query = $this->admin_model->countPositions($data);
        if($query == 0)
        {
          if($PositionDetail['Name'] != htmlentities($_POST['Position'], ENT_QUOTES))
          {
            // admin audits finalss
              $detail = $this->maintenance_model->selectSpecific('R_Position', 'PositionId', $generatedId['PositionId']);
              $TransactionNumber = 'POS-'.sprintf('%06d', $_POST['PositionId']);
              $auditLogsManager = 'Updated employee positions #'.$TransactionNumber.' in position setup.';
              $auditAffectedEmployee = 'Updated employee positions #'.$TransactionNumber.' in position setup.';
              $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
            // update function
              $set = array( 
              'Name'                     => htmlentities($_POST['Position'], ENT_QUOTES)
              );
              $condition = array( 
                'PositionId' => $_POST['PositionId']
              );
              $table = 'R_Position';
              $this->maintenance_model->updateFunction1($set, $condition, $table);
          }
          if($PositionDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
          {
            // add into audit table
              $auditDetail = 'Updated details of  '.$PositionDetail['Description'].' to '.htmlentities($_POST['Description'], ENT_QUOTES);
              $insertAudit = array(
                'Description' => $auditDetail,
                'CreatedBy' => $EmployeeNumber
              );
              $auditTable = 'R_Logs';
              $this->maintenance_model->insertFunction($insertAudit, $auditTable);
            // update function
              $set = array( 
              'Description'                     => htmlentities($_POST['Description'], ENT_QUOTES)
              );
              $condition = array( 
                'PositionId' => $_POST['PositionId']
              );
              $table = 'R_Position';
              $this->maintenance_model->updateFunction1($set, $condition, $table);
          }
          // notif
            $this->session->set_flashdata('alertTitle','Success!'); 
            $this->session->set_flashdata('alertText','Position details successfully updated!'); 
            $this->session->set_flashdata('alertType','success'); 
            redirect('home/AddPosition/');
        }
        else // if existing
        {
          // notif
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Position details already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddPosition/');
        }
      }
      else
      {
        // notif
        $this->session->set_flashdata('alertTitle','Info!'); 
        $this->session->set_flashdata('alertText','Record is in use, record cannot be updated!'); 
        $this->session->set_flashdata('alertType','info'); 
        redirect('home/AddPosition/');
      }
    }
  }

  function AddOptional()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $OptionalDetail = $this->admin_model->getOptionalDetails($_POST['OptionalId']);
    if ($_POST['FormType'] == 1) // add Optional CHarges
    {
      $data = array(
         'Name'                    => htmlentities($_POST['Optional'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countOptional($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Optional Charge details
          $insertOptional = array(
             'Name'                    => htmlentities($_POST['Optional'], ENT_QUOTES)
            , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertOptionalTable = 'R_OptionalCharges';
          $this->maintenance_model->insertFunction($insertOptional, $insertOptionalTable);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Optional charge successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddOptional/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Optional charge already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddOptional');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Optional Charges 
    {
      $data = array(
         'Name'                    => htmlentities($_POST['Optional'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countPositions($data);
      print_r($query);
      if($query == 0)
      {
        if($OptionalDetail['Name'] != htmlentities($_POST['Optional'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$OptionalDetail['Name'].' to '.htmlentities($_POST['Optional'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'Name'                     => htmlentities($_POST['Optional'], ENT_QUOTES)
            );
            $condition = array( 
              'OptionalId' => $_POST['OptionalId']
            );
            $table = 'R_OptionalCharges';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($OptionalDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$OptionalDetail['Description'].' to '.htmlentities($_POST['Description'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'Description'                     => htmlentities($_POST['Description'], ENT_QUOTES)
            );
            $condition = array( 
              'OptionalId' => $_POST['OptionalId']
            );
            $table = 'R_OptionalCharges';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Optional Charge details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddOptional/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Optional Charge details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddOptional/');
      }
    }
  }

  function AddPurpose()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $PurposeDetail = $this->admin_model->getPurposeDetails($_POST['PurposeId']);
    if ($_POST['FormType'] == 1) // add Purpose
    {
      $data = array(
         'Name'                    => htmlentities($_POST['Purpose'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countPurpose($data);
      if($query == 0) // not existing
      {
        // insert purpose details
          $insertPurpose = array(
             'Name'                    => htmlentities($_POST['Purpose'], ENT_QUOTES)
            , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertPurposeTable = 'R_Purpose';
          $this->maintenance_model->insertFunction($insertPurpose, $insertPurposeTable);
        // get generated application id
          $getData = array(
            'table'                 => 'R_Purpose'
            , 'column'              => 'PurposeId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $TransactionNumber = 'PP-'.sprintf('%06d', $generatedId['PurposeId']);
          $auditLogsManager = 'Added loan purpose #'.$TransactionNumber.' in purpose setup.';
          $auditAffectedEmployee = 'Added loan purpose #'.$TransactionNumber.' in purpose setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Purpose successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddPurpose/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Purpose already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddPurpose');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Purpose Details 
    {
      $data = array(
         'Name'                    => htmlentities($_POST['Purpose'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countPositions($data);
      print_r($query);
      if($query == 0)
      {
        if($PurposeDetail['Name'] != htmlentities($_POST['Purpose'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$PurposeDetail['Name'].' to '.htmlentities($_POST['Purpose'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'Name'                     => htmlentities($_POST['Purpose'], ENT_QUOTES)
            );
            $condition = array( 
              'PurposeId' => $_POST['PurposeId']
            );
            $table = 'R_Purpose';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($PurposeDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$PurposeDetail['Description'].' to '.htmlentities($_POST['Description'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'Description'                     => htmlentities($_POST['Description'], ENT_QUOTES)
            );
            $condition = array( 
              'PurposeId' => $_POST['PurposeId']
            );
            $table = 'R_Purpose';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Purpose details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddPurpose/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Purpose details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddPurpose/');
      }
    }
  }

  function AddMethod()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $MethodDetail = $this->admin_model->getMethodDetails($_POST['MethodId']);
    if ($_POST['FormType'] == 1) // add Bank
    {
      $data = array(
         'Name'                    => htmlentities($_POST['Method'], ENT_QUOTES)
      );
      $query = $this->admin_model->countMethod($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Bank details
          $insertMethod = array(
             'Name'                    => htmlentities($_POST['Method'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
          );
          $insertMethodTable = 'r_disbursement';
          $this->maintenance_model->insertFunction($insertMethod, $insertMethodTable);
        // get generated application id
          $getData = array(
            'table'                 => 'r_disbursement'
            , 'column'              => 'DisbursementId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $TransactionNumber = 'MP-'.sprintf('%06d', $generatedId['DisbursementId']);
          $auditLogsManager = 'Added method of payment #'.$TransactionNumber.' in method of payment setup.';
          $auditAffectedEmployee = 'Added method of payment #'.$TransactionNumber.' in method of payment setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Method of Payment successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddMethod/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Method of Payment already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddMethod');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Method of Payment Details 
    {
      $data = array(
         'Name'                    => htmlentities($_POST['Method'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countMethod($data);
      print_r($query);
      if($query == 0)
      {
        if($MethodDetail['Name'] != htmlentities($_POST['Method'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$MethodDetail['Name'].' to '.htmlentities($_POST['Method'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'Name'                     => htmlentities($_POST['Method'], ENT_QUOTES)
            );
            $condition = array( 
              'MethodId' => $_POST['MethodId']
            );
            $table = 'R_MethodOfPayment';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($MethodDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$MethodDetail['Description'].' to '.htmlentities($_POST['Description'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'Description'                     => htmlentities($_POST['Description'], ENT_QUOTES)
            );
            $condition = array( 
              'MethodId' => $_POST['MethodId']
            );
            $table = 'R_MethodOfPayment';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Method of Payment details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddMethod/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Method of Payment details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddMethod/');
      }
    }
  }

  function AddAsset()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $AssetDetail = $this->admin_model->getAssetDetails($_POST['CategoryId']);
    if ($_POST['FormType'] == 1) // add Asset Category
    {
      $data = array(
        'Name'                     => htmlentities($_POST['Asset'], ENT_QUOTES)
        , 'Description'          => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countAsset($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Asset Category details
          $insertCategory = array(
            'Name'                     => htmlentities($_POST['Asset'], ENT_QUOTES)
            , 'Description'                   => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertCategoryTable = 'R_Category';
          $this->maintenance_model->insertFunction($insertCategory, $insertCategoryTable);
        // get generated application id
          $getData = array(
            'table'                 => 'R_Category'
            , 'column'              => 'CategoryId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits
          $employeeDetail = $this->employee_model->getEmployeeProfile($EmployeeNumber);
          $TransactionNumber = 'CAT-'.sprintf('%05d', $generatedId['CategoryId']);
          $auditLogsManager = 'Added asset category #'. $TransactionNumber.' in asset management.';
          $auditAffectedEmployee = 'added asset category #'. $TransactionNumber.'.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Category successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddCategory/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Category already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddCategory');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Method of Payment Details 
    {
      $data = array(
         'Name'                    => htmlentities($_POST['Asset'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countAsset($data);
      print_r($query);
      if($query == 0)
      {
        if($AssetDetail['Name'] != htmlentities($_POST['Asset'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$AssetDetail['Name'].' to '.htmlentities($_POST['Asset'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'Name'                     => htmlentities($_POST['Asset'], ENT_QUOTES)
            );
            $condition = array( 
              'CategoryId' => $_POST['CategoryId']
            );
            $table = 'R_Category';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($AssetDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$AssetDetail['Description'].' to '.htmlentities($_POST['Description'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'Description'                     => htmlentities($_POST['Description'], ENT_QUOTES)
            );
            $condition = array( 
              'CategoryId' => $_POST['CategoryId']
            );
            $table = 'R_Category';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Asset Category details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddCategory/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Asset Category details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddCategory/');
      }
    }
  }

  function AddAssetManagement()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    if ($_POST['FormType'] == 1) // add Asset
    {
      $AssetManagementDetail = $this->admin_model->getAssetManagementDetails($_POST['AssetManagementId']);
      $data = array(
        'PurchaseValue'               => htmlentities($_POST['PurchasePrice'], ENT_QUOTES)
        , 'Type'                      => htmlentities($_POST['AssetType'], ENT_QUOTES)
        , 'Name'                      => htmlentities($_POST['AssetName'], ENT_QUOTES)
        , 'Stock'                     => htmlentities($_POST['Stock'], ENT_QUOTES)
        , 'CriticalLevel'             => htmlentities($_POST['CriticalLevel'], ENT_QUOTES)
        , 'CategoryId'                => htmlentities($_POST['CategoryId'], ENT_QUOTES)
        , 'ReplacementValue'          => htmlentities($_POST['ReplacementValue'], ENT_QUOTES)
        , 'SerialNumber'              => htmlentities($_POST['SerialNumber'], ENT_QUOTES)
        , 'AssignedTo'                => htmlentities($_POST['AssignedTo'], ENT_QUOTES)
        , 'BoughtFrom'                => htmlentities($_POST['BoughtFrom'], ENT_QUOTES)
        , 'BranchId'                  => htmlentities($_POST['BranchId'], ENT_QUOTES)
      );
      $query = $this->admin_model->countTangibles($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Tangible Asset details
          $insertTangible = array(
            'PurchaseValue'            => htmlentities($_POST['PurchasePrice'], ENT_QUOTES)
            , 'Type'                   => htmlentities($_POST['AssetType'], ENT_QUOTES)
            , 'Name'                   => htmlentities($_POST['AssetName'], ENT_QUOTES)
            , 'Stock'                  => htmlentities($_POST['Stock'], ENT_QUOTES)
            , 'CriticalLevel'          => htmlentities($_POST['CriticalLevel'], ENT_QUOTES)
            , 'CategoryId'             => htmlentities($_POST['CategoryId'], ENT_QUOTES)
            , 'ReplacementValue'       => htmlentities($_POST['ReplacementValue'], ENT_QUOTES)
            , 'AssignedTo'             => htmlentities($_POST['AssignedTo'], ENT_QUOTES)
            , 'SerialNumber'           => htmlentities($_POST['SerialNumber'], ENT_QUOTES)
            , 'BoughtFrom'             => htmlentities($_POST['BoughtFrom'], ENT_QUOTES)
            , 'BranchId'               => htmlentities($_POST['BranchId'], ENT_QUOTES)
            , 'StatusId'               => 2
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertTangibleTable = 'R_AssetManagement';
          $this->maintenance_model->insertFunction($insertTangible, $insertTangibleTable);
        // get generated application id
          $getData = array(
            'table'                 => 'R_AssetManagement'
            , 'column'              => 'AssetManagementId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits
          $employeeDetail = $this->employee_model->getEmployeeProfile($EmployeeNumber);
          $TransactionNumber = 'AM-'.sprintf('%05d', $generatedId['AssetManagementId']);
          $auditLogsManager = 'Added asset #'.htmlentities($TransactionNumber, ENT_QUOTES).' in asset management.';
          $auditAffectedEmployee = 'Added asset #'.htmlentities($TransactionNumber, ENT_QUOTES).'.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Asset supply successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddAssetManagement');
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Asset supply already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddAssetManagement');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Asset Details 
    {
      $AssetManagementDetail = $this->admin_model->getAssetManagementDetails($_POST['AssetManagementId']);
      if($AssetManagementDetail['AssetName'] != htmlentities($_POST['AssetName'], ENT_QUOTES))
      {
        // admin audits
          $employeeDetail = $this->employee_model->getEmployeeProfile($EmployeeNumber);
          $itemDetail = $this->maintenance_model->selectSpecific('r_assetmanagement', 'AssetManagementId', $_POST['AssetManagementId']);
          $TransactionNumber = 'AM-'.sprintf('%05d', $itemDetail['AssetManagementId']);
          $auditLogsManager = 'Updated asset name of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['AssetName'].' to '.htmlentities($_POST['AssetName'], ENT_QUOTES).' in asset management.';
          $auditAffectedEmployee = 'Updated asset name of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['AssetName'].' to '.htmlentities($_POST['AssetName'], ENT_QUOTES).'.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber);
        // update function
          $set = array( 
          'Name'                     => htmlentities($_POST['AssetName'], ENT_QUOTES)
          );
          $condition = array( 
            'AssetManagementId' => $_POST['AssetManagementId']
          );
          $table = 'R_AssetManagement';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($AssetManagementDetail['PurchaseValue'] != htmlentities($_POST['PurchasePrice'], ENT_QUOTES))
      {
        // admin audits
          $employeeDetail = $this->employee_model->getEmployeeProfile($EmployeeNumber);
          $itemDetail = $this->maintenance_model->selectSpecific('r_assetmanagement', 'AssetManagementId', $_POST['AssetManagementId']);
          $TransactionNumber = 'AM-'.sprintf('%05d', $itemDetail['AssetManagementId']);
          $auditLogsManager = 'Updated purchase value of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['PurchaseValue'].' to '.htmlentities($_POST['PurchasePrice'], ENT_QUOTES).' in asset management.';
          $auditAffectedEmployee = 'Updated purchase value of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['PurchaseValue'].' to '.htmlentities($_POST['PurchasePrice'], ENT_QUOTES).'.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber);
        // update function
          $set = array( 
          'PurchaseValue'                     => htmlentities($_POST['PurchasePrice'], ENT_QUOTES)
          );
          $condition = array( 
            'AssetManagementId' => $_POST['AssetManagementId']
          );
          $table = 'R_AssetManagement';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($AssetManagementDetail['Type'] != htmlentities($_POST['AssetType'], ENT_QUOTES))
      {
        // admin audits
          $employeeDetail = $this->employee_model->getEmployeeProfile($EmployeeNumber);
          $itemDetail = $this->maintenance_model->selectSpecific('r_assetmanagement', 'AssetManagementId', $_POST['AssetManagementId']);
          $TransactionNumber = 'AM-'.sprintf('%05d', $itemDetail['AssetManagementId']);
          $auditLogsManager = 'Updated type of asset of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['Type'].' to '.htmlentities($_POST['AssetType'], ENT_QUOTES).' in asset management.';
          $auditAffectedEmployee = 'Updated type of asset of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['Type'].' to '.htmlentities($_POST['AssetType'], ENT_QUOTES).'.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber);
        // update function
          $set = array( 
          'Type'                     => htmlentities($_POST['AssetType'], ENT_QUOTES)
          );
          $condition = array( 
            'AssetManagementId' => $_POST['AssetManagementId']
          );
          $table = 'R_AssetManagement';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($AssetManagementDetail['CategoryId'] != htmlentities($_POST['CategoryId'], ENT_QUOTES))
      {
        // admin audits
          $employeeDetail = $this->employee_model->getEmployeeProfile($EmployeeNumber);
          $itemDetail = $this->maintenance_model->selectSpecific('r_assetmanagement', 'AssetManagementId', $_POST['AssetManagementId']);
          $TransactionNumber = 'AM-'.sprintf('%05d', $itemDetail['AssetManagementId']);
          $auditLogsManager = 'Updated asset category of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['CategoryId'].' to '.htmlentities($_POST['CategoryId'], ENT_QUOTES).' in asset management.';
          $auditAffectedEmployee = 'Updated asset category of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['CategoryId'].' to '.htmlentities($_POST['CategoryId'], ENT_QUOTES).'.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber);
        // update function
          $set = array( 
          'CategoryId'                     => htmlentities($_POST['CategoryId'], ENT_QUOTES)
          );
          $condition = array( 
            'AssetManagementId' => $_POST['AssetManagementId']
          );
          $table = 'R_AssetManagement';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($AssetManagementDetail['ReplacementValue'] != htmlentities($_POST['ReplacementValue'], ENT_QUOTES))
      {
        // admin audits
          $employeeDetail = $this->employee_model->getEmployeeProfile($EmployeeNumber);
          $itemDetail = $this->maintenance_model->selectSpecific('r_assetmanagement', 'AssetManagementId', $_POST['AssetManagementId']);
          $TransactionNumber = 'AM-'.sprintf('%05d', $itemDetail['AssetManagementId']);
          $auditLogsManager = 'Updated replacement value of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['ReplacementValue'].' to '.htmlentities($_POST['ReplacementValue'], ENT_QUOTES).' in asset management.';
          $auditAffectedEmployee = 'Updated replacement value of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['ReplacementValue'].' to '.htmlentities($_POST['ReplacementValue'], ENT_QUOTES).'.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber);
        // update function
          $set = array( 
          'ReplacementValue'                     => htmlentities($_POST['ReplacementValue'], ENT_QUOTES)
          );
          $condition = array( 
            'AssetManagementId' => $_POST['AssetManagementId']
          );
          $table = 'R_AssetManagement';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($AssetManagementDetail['SerialNumber'] != htmlentities($_POST['SerialNumber'], ENT_QUOTES))
      {
        // admin audits
          $employeeDetail = $this->employee_model->getEmployeeProfile($EmployeeNumber);
          $itemDetail = $this->maintenance_model->selectSpecific('r_assetmanagement', 'AssetManagementId', $_POST['AssetManagementId']);
          $TransactionNumber = 'AM-'.sprintf('%05d', $itemDetail['AssetManagementId']);
          $auditLogsManager = 'Updated serial number of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['SerialNumber'].' to '.htmlentities($_POST['SerialNumber'], ENT_QUOTES).' in asset management.';
          $auditAffectedEmployee = 'Updated serial number of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['SerialNumber'].' to '.htmlentities($_POST['SerialNumber'], ENT_QUOTES).'.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber);
        // update function
          $set = array( 
          'SerialNumber'                     => htmlentities($_POST['SerialNumber'], ENT_QUOTES)
          );
          $condition = array( 
            'AssetManagementId' => $_POST['AssetManagementId']
          );
          $table = 'R_AssetManagement';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($AssetManagementDetail['BoughtFrom'] != htmlentities($_POST['BoughtFrom'], ENT_QUOTES))
      {
        // admin audits
          $employeeDetail = $this->employee_model->getEmployeeProfile($EmployeeNumber);
          $itemDetail = $this->maintenance_model->selectSpecific('r_assetmanagement', 'AssetManagementId', $_POST['AssetManagementId']);
          $TransactionNumber = 'AM-'.sprintf('%05d', $itemDetail['AssetManagementId']);
          $auditLogsManager = 'Updated vendor of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['BoughtFrom'].' to '.htmlentities($_POST['BoughtFrom'], ENT_QUOTES).' in asset management.';
          $auditAffectedEmployee = 'Updated vendor of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['BoughtFrom'].' to '.htmlentities($_POST['BoughtFrom'], ENT_QUOTES).'.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber);
        // update function
          $set = array( 
          'BoughtFrom'                     => htmlentities($_POST['BoughtFrom'], ENT_QUOTES)
          );
          $condition = array( 
            'AssetManagementId' => $_POST['AssetManagementId']
          );
          $table = 'R_AssetManagement';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($AssetManagementDetail['CriticalLevel'] != htmlentities($_POST['CriticalLevel'], ENT_QUOTES))
      {
        // admin audits
          $employeeDetail = $this->employee_model->getEmployeeProfile($EmployeeNumber);
          $itemDetail = $this->maintenance_model->selectSpecific('r_assetmanagement', 'AssetManagementId', $_POST['AssetManagementId']);
          $TransactionNumber = 'AM-'.sprintf('%05d', $itemDetail['AssetManagementId']);
          $auditLogsManager = 'Updated critical level of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['CriticalLevel'].' to '.htmlentities($_POST['CriticalLevel'], ENT_QUOTES).' in asset management.';
          $auditAffectedEmployee = 'Updated critical level of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['CriticalLevel'].' to '.htmlentities($_POST['CriticalLevel'], ENT_QUOTES).'.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber);
        // update function
          $set = array( 
          'CriticalLevel'                     => htmlentities($_POST['CriticalLevel'], ENT_QUOTES)
          );
          $condition = array( 
            'AssetManagementId' => $_POST['AssetManagementId']
          );
          $table = 'R_AssetManagement';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($AssetManagementDetail['BranchId'] != htmlentities($_POST['BranchId'], ENT_QUOTES))
      {
        // admin audits
          $employeeDetail = $this->employee_model->getEmployeeProfile($EmployeeNumber);
          $itemDetail = $this->maintenance_model->selectSpecific('r_assetmanagement', 'AssetManagementId', $_POST['AssetManagementId']);
          $branchName = $this->maintenance_model->selectSpecific('R_Branches', 'BranchId', $_POST['BranchId']);
          $TransactionNumber = 'AM-'.sprintf('%05d', $itemDetail['AssetManagementId']);
          $auditLogsManager = 'Updated assigned branch of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['Name'].' to '.htmlentities($branchName['Name'], ENT_QUOTES).' in asset management.';
          $auditAffectedEmployee = 'Updated assigned branch of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['Name'].' to '.htmlentities($branchName['Name'], ENT_QUOTES).'.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber);
        // update function
          $set = array( 
          'BranchId'                     => htmlentities($_POST['BranchId'], ENT_QUOTES)
          );
          $condition = array( 
            'AssetManagementId' => $_POST['AssetManagementId']
          );
          $table = 'R_AssetManagement';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($AssetManagementDetail['AssignedTo'] != htmlentities($_POST['AssignedTo'], ENT_QUOTES))
      {
        // admin audits
          $employeeDetail = $this->employee_model->getEmployeeProfile($EmployeeNumber);
          $fromEmployee = $this->employee_model->getEmployeeProfile($AssetManagementDetail['AssignedTo']);
          $toEmployee = $this->employee_model->getEmployeeProfile($_POST['AssignedTo']);
          $TransactionNumber = 'AM-'.sprintf('%05d', $itemDetail['AssetManagementId']);
          $auditLogsManager = 'Updated assigned employee to asset #'.$TransactionNumber.' from '.$fromEmployee['Name'].' to '.htmlentities($toEmployee['Name'], ENT_QUOTES).' in asset management.';
          $auditAffectedEmployee = 'Updated assigned employee to asset #'.$TransactionNumber.' from '.$fromEmployee['Name'].' to '.htmlentities($toEmployee['Name'], ENT_QUOTES).'.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber);
        // update function
          $set = array( 
          'AssignedTo'                     => htmlentities($_POST['AssignedTo'], ENT_QUOTES)
          );
          $condition = array( 
            'AssetManagementId' => $_POST['AssetManagementId']
          );
          $table = 'R_AssetManagement';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($AssetManagementDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
      {
        // admin audits
          $employeeDetail = $this->employee_model->getEmployeeProfile($EmployeeNumber);
          $itemDetail = $this->maintenance_model->selectSpecific('r_assetmanagement', 'AssetManagementId', $_POST['AssetManagementId']);
          $TransactionNumber = 'AM-'.sprintf('%05d', $itemDetail['AssetManagementId']);
          $auditLogsManager = 'Updated description of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['Description'].' to '.htmlentities($_POST['Description'], ENT_QUOTES).' in asset management.';
          $auditAffectedEmployee = 'Updated serial number of asset #'.$TransactionNumber.' from '.$AssetManagementDetail['Description'].' to '.htmlentities($_POST['Description'], ENT_QUOTES).'.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber);
        // update function
          $set = array( 
          'Description'                     => htmlentities($_POST['Description'], ENT_QUOTES)
          );
          $condition = array( 
            'AssetManagementId' => $_POST['AssetManagementId']
          );
          $table = 'R_AssetManagement';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Asset details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddAssetManagement/');
    }
    else if($_POST['FormType'] == 3) // stocks 
    {
      $employeeDetail = $this->employee_model->getEmployeeProfile($EmployeeNumber);
      $currentStock = $this->maintenance_model->selectSpecific('r_assetmanagement', 'AssetManagementId', $_POST['assetId']);
      $TransactionNumber = sprintf('%05d', $currentStock['AssetManagementId']);
      $newStock = 0;
      if($_POST['stockType'] == 1) // add
      {
        $newStock = htmlentities($_POST['stockNo'], ENT_QUOTES) + $currentStock['Stock'];
        // admin audits
          $auditLogsManager = 'Added '.$_POST['stockNo'].' stocks to asset #'.$TransactionNumber.' in asset management module.';
          $auditAffectedEmployee = 'Added '.$_POST['stockNo'].' stocks to asset #'.$TransactionNumber.' in asset management module.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber);
      }
      else
      {
        $newStock = $currentStock['Stock'] - htmlentities($_POST['stockNo'], ENT_QUOTES);
        // admin audits
          $auditLogsManager = 'Removed '.$_POST['stockNo'].' stocks from asset #'.$TransactionNumber.' in asset management module.';
          $auditAffectedEmployee = 'Removed '.$_POST['stockNo'].' stocks from asset #'.$TransactionNumber.' in asset management module.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber);
      }

      $set = array( 
        'Stock'                     => $newStock
      );
      $condition = array( 
        'AssetManagementId' => $_POST['assetId']
      );
      $table = 'r_assetmanagement';
      $this->maintenance_model->updateFunction1($set, $condition, $table);

      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Asset details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddAssetManagement/');
    }
  }

  function AddLoanStatus()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $LoanStatusDetail = $this->admin_model->getLoanStatusDetails($_POST['LoanStatusId']);
    if ($_POST['FormType'] == 1) // add LoanStatus
    {
      $data = array(
        'Name'                      => htmlentities($_POST['LoanStatus'], ENT_QUOTES)
      );
      $query = $this->admin_model->countLoanStatus($data);
      if($query == 0) // not existing
      {
        // insert LoanStatus details
          $insertLoanStatus = array(
            'Name'                     => htmlentities($_POST['LoanStatus'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
            , 'IsApprovable'           => $_POST['Approvable']
            , 'statusColor'            => $_POST['StatusColor']
            , 'IsEditable'             => 1
          );
          $insertLoanStatusTable = 'application_has_status';
          $this->maintenance_model->insertFunction($insertLoanStatus, $insertLoanStatusTable);
        // get generated application id
          $getData = array(
            'table'                 => 'application_has_status'
            , 'column'              => 'LoanStatusId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $TransactionNumber = 'ALS-'.sprintf('%06d', $generatedId['LoanStatusId']);
          $auditLogsManager = 'Added loan status #'.$TransactionNumber.' in loan status setup.';
          $auditAffectedEmployee = 'Added loan status #'.$TransactionNumber.' in loan status setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Loan Status successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddLoanStatus/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Loan Status already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddLoanStatus');
      }
    }
    else if($_POST['FormType'] == 2) // Edit LoanStatus Details 
    {
      $data = array(
         'Name'                    => htmlentities($_POST['LoanStatus'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countLoanStatus($data);
      print_r($query);
      if($query == 0)
      {
        if($LoanStatusDetail['Name'] != htmlentities($_POST['LoanStatus'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$LoanStatusDetail['Name'].' to '.htmlentities($_POST['LoanStatus'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'Name'                     => htmlentities($_POST['LoanStatus'], ENT_QUOTES)
            );
            $condition = array( 
              'LoanStatusId' => $_POST['LoanStatusId']
            );
            $table = 'R_LoanStatus';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($LoanStatusDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$LoanStatusDetail['Description'].' to '.htmlentities($_POST['Description'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'Description'                     => htmlentities($_POST['Description'], ENT_QUOTES)
            );
            $condition = array( 
              'LoanStatusId' => $_POST['LoanStatusId']
            );
            $table = 'R_LoanStatus';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Loan Status details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddLoanStatus/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Loan Status details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddLoanStatus/');
      }
    }
  }

  function AddBorrowerStatus()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    if ($_POST['FormType'] == 1) // add Borrower Status
    {
      $data = array(
        'Name'                     => htmlentities($_POST['BorrowerStatus'], ENT_QUOTES)
      );
      $query = $this->admin_model->countBorrowerStatus($data);
      if($query == 0) // not existing
      {
        // insert Borrower Status details
          $insertBorrowerStatus = array(
            'Name'                     => htmlentities($_POST['BorrowerStatus'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
          );
          $insertBorrowerStatusTable = 'r_borrowerstatus';
          $this->maintenance_model->insertFunction($insertBorrowerStatus, $insertBorrowerStatusTable);
        // get generated application id
          $getData = array(
            'table'                 => 'r_borrowerstatus'
            , 'column'              => 'BorrowerStatusId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $TransactionNumber = 'BST-'.sprintf('%06d', $generatedId['BorrowerStatusId']);
          $auditLogsManager = 'Added borrower status #'.$TransactionNumber.' in borrower status setup.';
          $auditAffectedEmployee = 'Added borrower status #'.$TransactionNumber.' in borrower status setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Borrower Status successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddBorrowerStatus/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Borrower Status already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddBorrowerStatus');
      }
    }
    else if($_POST['FormType'] == 2) // Edit BorrowerStatus Details 
    {
      $BorrowerStatusDetail = $this->admin_model->getBorrowerStatusDetails($_POST['BorrowerStatusId']);
      $data = array(
         'Name'                    => htmlentities($_POST['BorrowerStatus'], ENT_QUOTES)
      );
      $query = $this->admin_model->countBorrowerStatus($data); //Count if existing
      if($query == 0)
      {
        // update function
          $set = array( 
          'Name'                     => htmlentities($_POST['BorrowerStatus'], ENT_QUOTES)
          );
          $condition = array( 
            'BorrowerStatusId' => $_POST['BorrowerStatusId']
          );
          $table = 'r_borrowerstatus';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // admin audits finalss
          $TransactionNumber = 'BST-'.sprintf('%06d', $_POST['BorrowerStatusId']);
          $auditLogsManager = 'Updated borrower status #'.$TransactionNumber.' in borrower status setup.';
          $auditAffectedEmployee = 'Updated borrower status #'.$TransactionNumber.' in borrower status setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notif
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Borrower Status details successfully updated!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddBorrowerStatus/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Borrower Status details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddBorrowerStatus/');
      }
    }
  }

  function AddIndustry()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $IndustryDetail = $this->admin_model->getIndustryDetails($_POST['IndustryId']);
    if ($_POST['FormType'] == 1) // add Industry
    {
      $data = array(
        'Name'                     => htmlentities($_POST['Industry'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countIndustry($data); //Check if Existing
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Industry details
          $insertIndustry = array(
            'Name'                     => htmlentities($_POST['Industry'], ENT_QUOTES)
            , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertIndustryTable = 'R_Industry';
          $this->maintenance_model->insertFunction($insertIndustry, $insertIndustryTable);
        // get generated application id
          $getData = array(
            'table'                 => 'R_Industry'
            , 'column'              => 'IndustryId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $TransactionNumber = 'IND-'.sprintf('%06d', $generatedId['IndustryId']);
          $auditLogsManager = 'Added industry #'.$TransactionNumber.' in industry setup.';
          $auditAffectedEmployee = 'Added industry #'.$TransactionNumber.' in industry setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Industry successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddIndustry/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Industry already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddIndustry');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Industry Details 
    {
      $data = array(
         'Name'                    => htmlentities($_POST['Industry'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countIndustry($data); //Count if existing
      print_r($query);
      if($query == 0)
      {
        if($IndustryDetail['Name'] != htmlentities($_POST['Industry'], ENT_QUOTES))
        {
          // update function
            $set = array( 
            'Name'                     => htmlentities($_POST['Industry'], ENT_QUOTES)
            );
            $condition = array( 
              'IndustryId' => $_POST['IndustryId']
            );
            $table = 'R_Industry';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($IndustryDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
        {
          // update function
            $set = array( 
            'Description'                     => htmlentities($_POST['Description'], ENT_QUOTES)
            );
            $condition = array( 
              'IndustryId' => $_POST['IndustryId']
            );
            $table = 'R_Industry';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        // admin audits finalss
          $TransactionNumber = 'IND-'.sprintf('%06d', $_POST['IndustryId']);
          $auditLogsManager = 'Updated industry #'.$TransactionNumber.' in industry setup.';
          $auditAffectedEmployee = 'Updated industry #'.$TransactionNumber.' in industry setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Industry details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddIndustry/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Industry details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddIndustry/');
      }
    }
  }

  function AddEducation()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $EducationDetail = $this->admin_model->getPositionDetails($_POST['EducationId']);
    if ($_POST['FormType'] == 1) // add Education
    {
      $data = array(
         'Name'                    => htmlentities($_POST['Education'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countEducation($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Education details
          $insertEducation = array(
             'Name'                    => htmlentities($_POST['Education'], ENT_QUOTES)
            , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertEducationTable = 'R_Education';
          $this->maintenance_model->insertFunction($insertEducation, $insertEducationTable);
        // get generated application id
          $getData = array(
            'table'                 => 'R_Education'
            , 'column'              => 'EducationId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $TransactionNumber = 'EDU-'.sprintf('%06d', $generatedId['IndustryId']);
          $auditLogsManager = 'Added education #'.$TransactionNumber.' in education level setup.';
          $auditAffectedEmployee = 'Added education #'.$TransactionNumber.' in education level setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Education Level successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddEducation');
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Education Level already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddEducation');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Education 
    {
      $data = array(
         'Name'                    => htmlentities($_POST['Education'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countEducation($data);
      if($query == 0)
      {
        // update function
          $set = array( 
          'Name'                     => htmlentities($_POST['Education'], ENT_QUOTES)
          );
          $condition = array( 
            'EducationId' => $_POST['EducationId']
          );
          $table = 'R_Education';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // update function
          $set2 = array( 
          'Description'                     => htmlentities($_POST['Description'], ENT_QUOTES)
          );
          $condition2 = array( 
            'EducationId' => $_POST['EducationId']
          );
          $table2 = 'R_Education';
          $this->maintenance_model->updateFunction1($set2, $condition2, $table2);
        // admin audits finalss
          $TransactionNumber = 'EDU-'.sprintf('%06d', $_POST['EducationId']);
          $auditLogsManager = 'Updated education #'.$TransactionNumber.' in education level setup.';
          $auditAffectedEmployee = 'Updated education #'.$TransactionNumber.' in education level setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Education Level details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddEducation/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Education Level details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddEducation/');
      }
    }
  }

  function AddOccupation()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    if ($_POST['FormType'] == 1) // add Occupation
    {
      $data = array(
        'Name'                     => htmlentities($_POST['Occupation'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countOccupation($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Occupation details
          $insertOccupation = array(
            'Name'                     => htmlentities($_POST['Occupation'], ENT_QUOTES)
            , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertOccupationTable = 'R_Occupation';
          $this->maintenance_model->insertFunction($insertOccupation, $insertOccupationTable);
        // get generated application id
          $getData = array(
            'table'                 => 'R_Occupation'
            , 'column'              => 'OccupationId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $detail = $this->maintenance_model->selectSpecific('R_Occupation', 'OccupationId', $_POST['OccupationId']);
          $TransactionNumber = 'OCC-'.sprintf('%06d', $generatedId['OccupationId']);
          $auditLogsManager = 'Added occupation #'.$TransactionNumber.' in occupations setup.';
          $auditAffectedEmployee = 'Added occupation #'.$TransactionNumber.' in occupations setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Occupation detail successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddOccupation/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Occupation detail already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddOccupation');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Occupation 
    {
      $OccupationDetail = $this->admin_model->getOccupationDetails($_POST['OccupationId']);
      $data = array(
        'Name'                     => htmlentities($_POST['Occupation'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countOccupation($data);
      print_r($query);
      if($query == 0)
      {
        if($OccupationDetail['Name'] != htmlentities($_POST['Occupation'], ENT_QUOTES))
        {
          // update function
            $set = array( 
            'Name'                     => htmlentities($_POST['Occupation'], ENT_QUOTES)
            );
            $condition = array( 
              'OccupationId' => $_POST['OccupationId']
            );
            $table = 'R_Occupation';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($OccupationDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
        {
          // update function
            $set = array( 
            'Description'                     => htmlentities($_POST['Description'], ENT_QUOTES)
            );
            $condition = array( 
              'OccupationId' => $_POST['OccupationId']
            );
            $table = 'R_Occupation';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        // admin audits finalss
          $TransactionNumber = 'OCC-'.sprintf('%06d', $_POST['OccupationId']);
          $auditLogsManager = 'Updated details #'.$TransactionNumber.' in occupations setup.';
          $auditAffectedEmployee = 'Updated details #'.$TransactionNumber.' in occupations setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notif
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Occupation details successfully updated!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddOccupation/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Occupation details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddOccupation/');
      }
    }
  }

  function AddRepaymentCycle()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    // insert Repayment details
      $insertRepayment = array(
        'Type'                    => 'Date'
        , 'CreatedBy'               => $EmployeeNumber
        , 'UpdatedBy'               => $EmployeeNumber
      );
      $insertRepaymentTable = 'r_repaymentcycle';
      $this->maintenance_model->insertFunction($insertRepayment, $insertRepaymentTable);
    // get generated application id
      $getData = array(
        'table'                 => 'r_repaymentcycle'
        , 'column'              => 'RepaymentId'
        , 'CreatedBy'           => $EmployeeNumber
      );
      $generatedId = $this->maintenance_model->getGeneratedId2($getData);
    foreach ($_POST['DateSelected'] as $value) 
    {
      // insert Repayment details
        $insertRepayment = array(
          'RepaymentId'               => $generatedId['RepaymentId']
          , 'Date'                    => htmlentities($value, ENT_QUOTES)
        );
        $insertRepaymentTable = 'repaymentcycle_has_content';
        $this->maintenance_model->insertFunction($insertRepayment, $insertRepaymentTable);
    }
      // admin audits finalss
      $TransactionNumber = 'RC-'.sprintf('%06d', $generatedId['RepaymentId']);
      $auditLogsManager = 'Added repayment cycle #'.$TransactionNumber.' in repayment cycle setup.';
      $auditAffectedEmployee = 'Added repayment cycle #'.$TransactionNumber.' in  repayment cycle setup.';
      $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      // notification
      $this->session->set_flashdata('alertTitle','Success!'); 
      $this->session->set_flashdata('alertText','Repayment Cycle successfully recorded!'); 
      $this->session->set_flashdata('alertType','success'); 
      redirect('home/AddRepaymentCycle');
  }

  function AddCapital()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $AssignedBranch = $this->session->userdata('BranchId');
    $CapitalDetail = $this->admin_model->getCapitalDetails($_POST['CapitalId']);
    if ($_POST['FormType'] == 1) // add Capital
    {
      $data = array(
        'Amount'                     => htmlentities($_POST['Capital'], ENT_QUOTES)
      );
      $query = $this->admin_model->countCapital($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Capital detail
          $instertCapital = array(
            'Amount'                      => htmlentities($_POST['Capital'], ENT_QUOTES),
            'BranchId'                    => $AssignedBranch,
            'CreatedBy'                   => $EmployeeNumber
          );
          $insertCapitalTable = 'R_Capital';
          $this->maintenance_model->insertFunction($instertCapital, $insertCapitalTable);
        // get generated application id
          $getData = array(
            'table'                 => 'R_Capital'
            , 'column'              => 'CapitalId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $detail = $this->maintenance_model->selectSpecific('R_Capital', 'CapitalId', $_POST['CapitalId']);
          $TransactionNumber = 'IC-'.sprintf('%06d', $generatedId['CapitalId']);
          $auditLogsManager = 'Added initial capital #'.$TransactionNumber.' in initial capital setup.';
          $auditAffectedEmployee = 'Added initial capital #'.$TransactionNumber.' in initial capital setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Initial Capital successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddInitialCapital/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Initial Capital already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddInitialCapital');
      }
    }
  }

  function AddExpense()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $ExpenseDetail = $this->admin_model->getExpenseDetails($_POST['ExpenseId']);
    $DateExpense = date('Y-m-d');
    $time = strtotime($_POST['DateExpense']);
    $ExpenseDate = date('Y-m-d', $time);
    $AssignedBranch = $this->session->userdata('BranchId');
    if ($_POST['FormType'] == 1) // add Expense
    {
      // insert Expense detail
        $instertExpense = array(
          'ExpenseTypeId'              => htmlentities($_POST['Expense'], ENT_QUOTES)
          , 'Amount'                   => htmlentities($_POST['Amount'], ENT_QUOTES)
          , 'BranchId'                 => $AssignedBranch
          , 'DateExpense'              => htmlentities($ExpenseDate, ENT_QUOTES)
          , 'CreatedBy'                => $EmployeeNumber
        );
        $insertExpenseTable = 'R_Expense';
        $this->maintenance_model->insertFunction($instertExpense, $insertExpenseTable);
      // get generated application id
        $getData = array(
          'table'                 => 'R_Expense'
          , 'column'              => 'ExpenseId'
          , 'CreatedBy'           => $EmployeeNumber
        );
        $generatedId = $this->maintenance_model->getGeneratedId2($getData);
      // admin audits finalss
        $detail = $this->maintenance_model->selectSpecific('R_Expense', 'ExpenseId', $generatedId['ExpenseId']);
        $TransactionNumber = 'EXP-'.sprintf('%06d', $generatedId['ExpenseId']);
        $auditLogsManager = 'Added expense #'.$TransactionNumber.' in expenses setup.';
        $auditAffectedEmployee = 'Added expense #'.$TransactionNumber.' in expenses setup.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Expense details successfully recorded!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddExpense/'. $EmployeeId['EmployeeId']);
    }
  }

  function AddExpenseType()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $ExpenseTypeDetail = $this->admin_model->getExpenseTypeDetails($_POST['ExpenseTypeId']);
    if ($_POST['FormType'] == 1) // add ExpenseType
    {
      $data = array(
        'Name'                     => htmlentities($_POST['ExpenseType'], ENT_QUOTES),
        'Description'                     => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countExpenseType($data);
      if($query == 0) // not existing
      {
        // insert Expense Type detail
          $instertExpenseType = array(
            'Name'                     => htmlentities($_POST['ExpenseType'], ENT_QUOTES)
            , 'Description'              => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'CreatedBy'                => $EmployeeNumber
          );
          $insertExpenseTypeTable = 'R_ExpenseType';
          $this->maintenance_model->insertFunction($instertExpenseType, $insertExpenseTypeTable);
        // get generated application id
          $getData = array(
            'table'                 => 'R_ExpenseType'
            , 'column'              => 'ExpenseTypeId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $detail = $this->maintenance_model->selectSpecific('R_ExpenseType', 'ExpenseTypeId', $generatedId['ExpenseTypeId']);
          $TransactionNumber = 'EXT-'.sprintf('%06d', $generatedId['ExpenseTypeId']);
          $auditLogsManager = 'Added expense type #'.$TransactionNumber.' in types of expenses setup.';
          $auditAffectedEmployee = 'Added expense type #'.$TransactionNumber.' in types of expenses setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Type of expense successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddExpenseType/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Type of expense already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddExpenseType');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Expense Type 
    {
      $data = array(
        'Name'                     => htmlentities($_POST['ExpenseType'], ENT_QUOTES),
        'Description'                     => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countExpenseType($data);
      if($query == 0)
      {
        if($ExpenseTypeDetail['ExpenseType'] != htmlentities($_POST['ExpenseType'], ENT_QUOTES))
        {
          // update function
            $set = array( 
            'Name'                     => htmlentities($_POST['ExpenseType'], ENT_QUOTES)
            );
            $condition = array( 
              'ExpenseTypeId' => $_POST['ExpenseTypeId']
            );
            $table = 'R_ExpenseType';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        else if($ExpenseTypeDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
        {
          // update function
            $set = array( 
            'Description'                     => htmlentities($_POST['Description'], ENT_QUOTES)
            );
            $condition = array( 
              'ExpenseTypeId' => $_POST['ExpenseTypeId']
            );
            $table = 'R_ExpenseType';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        // admin audits finalss
          $detail = $this->maintenance_model->selectSpecific('R_ExpenseType', 'ExpenseTypeId', $_POST['ExpenseTypeId']);
          $TransactionNumber = 'EXT-'.sprintf('%06d', $_POST['ExpenseTypeId']);
          $auditLogsManager = 'Updated expense type #'.$TransactionNumber.' in types of expenses setup.';
          $auditAffectedEmployee = 'Updated expense type #'.$TransactionNumber.' in types of expenses setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Type of expense details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddExpenseType/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Type of expense details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddExpenseType/');
      }
    }
  }

  function AddDepositType()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    if ($_POST['FormType'] == 1) // add WithdrawalType
    {
      $data = array(
        'Name'                     => htmlentities($_POST['WithdrawalType'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countWithdrawalType($data);
      if($query == 0) // not existing
      {
        // insert Withdrawal Type detail
          $instertWithdrawalType = array(
            'Name'                     => htmlentities($_POST['WithdrawalType'], ENT_QUOTES)
            , 'Description'              => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'CreatedBy'                => $EmployeeNumber
          );
          $insertWithdrawalTypeTable = 'R_WithdrawalType';
          $this->maintenance_model->insertFunction($instertWithdrawalType, $insertWithdrawalTypeTable);
        // get generated application id
          $getData = array(
            'table'                 => 'R_WithdrawalType'
            , 'column'              => 'WithdrawalTypeId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $detail = $this->maintenance_model->selectSpecific('R_WithdrawalType', 'WithdrawalTypeId', $generatedId['WithdrawalTypeId']);
          $TransactionNumber = 'DET-'.sprintf('%06d', $generatedId['WithdrawalTypeId']);
          $auditLogsManager = 'Added deposit type #'.$TransactionNumber.' in types of deposit setup.';
          $auditAffectedEmployee = 'Added deposit type #'.$TransactionNumber.' in types of deposit setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Deposit type successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddDepositType/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Deposit type already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddDepositType');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Withdrawal Type 
    {
      $WithdrawalTypeDetail = $this->admin_model->getWithdrawalTypeDetails($_POST['WithdrawalTypeId']);
      $data = array(
        'Name'                     => htmlentities($_POST['WithdrawalType'], ENT_QUOTES)
        , 'Description'              => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countWithdrawalType($data);
      print_r($query);
      if($query == 0)
      {
        if($WithdrawalTypeDetail['WithdrawalType'] != htmlentities($_POST['WithdrawalType'], ENT_QUOTES))
        {
          // update function
            $set = array( 
            'Name'                     => htmlentities($_POST['WithdrawalType'], ENT_QUOTES)
            );
            $condition = array( 
              'WithdrawalTypeId' => $_POST['WithdrawalTypeId']
            );
            $table = 'R_WithdrawalType';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        else if($WithdrawalTypeDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
        {
          // update function
            $set = array( 
            'Description'                     => htmlentities($_POST['Description'], ENT_QUOTES)
            );
            $condition = array( 
              'WithdrawalTypeId' => $_POST['WithdrawalTypeId']
            );
            $table = 'R_WithdrawalType';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        // admin audits finalss
          $TransactionNumber = 'DET-'.sprintf('%06d', $_POST['WithdrawalTypeId']);
          $auditLogsManager = 'Updated deposit type #'.$TransactionNumber.' in types of deposit setup.';
          $auditAffectedEmployee = 'Updated deposit type #'.$TransactionNumber.' in types of deposit setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Type of deposit details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddDepositType/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Type of deposit details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddDepositType/');
      }
    }
  }

  function AddDeposit()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $AssignedBranch = $this->session->userdata('BranchId');
    $DateWithdrawal = date('Y-m-d');
    if ($_POST['FormType'] == 1) // add Withdrawal
    {
      $time = strtotime($_POST['DateWithdrawal']);
      $newformat = date('Y-m-d', $time);
      $data = array(
        'WithdrawalTypeId'                     => htmlentities($_POST['Withdrawal'], ENT_QUOTES)
        , 'Amount'                             => htmlentities($_POST['Amount'], ENT_QUOTES)
        , 'DateWithdrawal'                     => htmlentities($newformat, ENT_QUOTES)
      );
      $query = $this->admin_model->countWithdrawal($data);
      if($query == 0) // not existing
      {
        // insert Withdrawal detail
          $instertWithdrawal = array(
            'WithdrawalTypeId'                => htmlentities($_POST['Withdrawal'], ENT_QUOTES)
            , 'Amount'                        => htmlentities($_POST['Amount'], ENT_QUOTES)
            , 'BranchId'                      => $AssignedBranch
            , 'DateWithdrawal'                => htmlentities($newformat, ENT_QUOTES)
            , 'CreatedBy'                     => $EmployeeNumber
          );
          $insertWithdrawalTable = 'R_Withdrawal';
          $this->maintenance_model->insertFunction($instertWithdrawal, $insertWithdrawalTable);
        // get generated application id
          $getData = array(
            'table'                 => 'R_Withdrawal'
            , 'column'              => 'WithdrawalId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $detail = $this->maintenance_model->selectSpecific('R_Withdrawal', 'WithdrawalId', $generatedId['WithdrawalId']);
          $TransactionNumber = 'DEP-'.sprintf('%06d', $generatedId['WithdrawalId']);
          $auditLogsManager = 'Added deposit #'.$TransactionNumber.' in deposit finance management.';
          $auditAffectedEmployee = 'Added deposit #'.$TransactionNumber.' in deposit finance management.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Deposit details successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddDeposit/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Deposit details already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddDeposit');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Withdrawals 
    {
      $WithdrawalDetail = $this->admin_model->getWithdrawalDetails($_POST['WithdrawalId']);
      $time = strtotime($_POST['DateWithdrawal']);
      $newformat = date('Y-m-d', $time);
      if($WithdrawalDetail['WithdrawalTypeId'] != htmlentities($_POST['Withdrawal'], ENT_QUOTES))
      {
        // update function
          $set = array( 
          'WithdrawalTypeId'                     => htmlentities($_POST['Withdrawal'], ENT_QUOTES)
          );
          $condition = array( 
            'WithdrawalId' => $_POST['WithdrawalId']
          );
          $table = 'R_Withdrawal';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($WithdrawalDetail['Amount'] != htmlentities($_POST['Amount'], ENT_QUOTES))
      {
        // update function
          $set = array( 
          'Amount'                     => htmlentities($_POST['Amount'], ENT_QUOTES)
          );
          $condition = array( 
            'WithdrawalId' => $_POST['WithdrawalId']
          );
          $table = 'R_Withdrawal';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($WithdrawalDetail['rawDateWithdrawal'] != htmlentities($newformat, ENT_QUOTES))
      {
        // update function
          $set = array( 
          'DateWithdrawal'                     => htmlentities($newformat, ENT_QUOTES)
          );
          $condition = array( 
            'WithdrawalId' => $_POST['WithdrawalId']
          );
          $table = 'R_Withdrawal';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      // admin audits finalss
        $TransactionNumber = 'DEP-'.sprintf('%06d', $_POST['WithdrawalId']);
        $auditLogsManager = 'Updated deposit #'.$TransactionNumber.' in deposit finance management.';
        $auditAffectedEmployee = 'Updated deposit #'.$TransactionNumber.' in deposit finance management.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Deposit details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddDeposit/');
    }
  }

  function AddDisclosure()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DisclosureDetail = $this->admin_model->getDisclosureDetails($_POST['DisclosureId']);
    $DateNow = date("Y-m-d H:i:s");
    if ($_POST['FormType'] == 1) // add Disclosure
    {
      $data = array(
        'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countDisclosure($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Disclosure Agreement details
          $insertDisclosure = array(
           'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertDisclosureTable = 'R_DisclosureAgreement';
          $this->maintenance_model->insertFunction($insertDisclosure, $insertDisclosureTable);
        // get generated application id
          $getData = array(
            'table'                 => 'R_DisclosureAgreement'
            , 'column'              => 'DisclosureId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $TransactionNumber = 'DA-'.sprintf('%06d', $generatedId['DisclosureId']);
          $auditLogsManager = 'Added disclosure agreement #'.$TransactionNumber.' in disclosure agreement setup.';
          $auditAffectedEmployee = 'Added disclosure agreement #'.$TransactionNumber.' in disclosure agreement setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Disclosure agreement successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddDisclosure');
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Disclosure agreement already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddDisclosure');
      }
    }
    else if($_POST['FormType'] == 2) // edit Disclosure 
    {
      $data = array(
       'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countDisclosure($data);
      if($query == 0)
      {
        if($DisclosureDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
        {
          // update function
            $set = array(
              'Description' => htmlentities($_POST['Description'], ENT_QUOTES)
            );
            $condition = array(
              'DisclosureId' => $_POST['DisclosureId']
            );
            $table = 'R_DisclosureAgreement';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        // admin audits finalss
          $TransactionNumber = 'DA-'.sprintf('%06d', $_POST['DisclosureId']);
          $auditLogsManager = 'Updated disclosure agreement details #'.$TransactionNumber.' in disclosure agreement setup.';
          $auditAffectedEmployee = 'Updated disclosure agreement details #'.$TransactionNumber.' in disclosure agreement setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notif
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Disclosure agreement details successfully updated!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddDisclosure/');
      }
    }
    else // if existing
    {
      // notif
      $this->session->set_flashdata('alertTitle','Warning!'); 
      $this->session->set_flashdata('alertText','Disclosure agreement details already existing!'); 
      $this->session->set_flashdata('alertType','warning'); 
      redirect('home/AddDisclosure/');
    }
  }

  function AddSecurityQuestion()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $QuestionDetail = $this->admin_model->getQuestionDetails($_POST['SecurityQuestionId']);
    $DateNow = date("Y-m-d H:i:s");
    if ($_POST['FormType'] == 1) // add Security Question
    {
      $data = array(
        'Name'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countQuestion($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Security Question details
          $insertQuestion = array(
           'Name'            => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertQuestionTable = 'R_SecurityQuestions';
          $this->maintenance_model->insertFunction($insertQuestion, $insertQuestionTable);
        // get generated application id
          $getData = array(
            'table'                 => 'R_SecurityQuestions'
            , 'column'              => 'SecurityQuestionId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finalss
          $TransactionNumber = 'SQ-'.sprintf('%06d', $generatedId['SecurityQuestionId']);
          $auditLogsManager = 'Added security question #'.$TransactionNumber.' in security question setup.';
          $auditAffectedEmployee = 'Added security question #'.$TransactionNumber.' in security question setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Security question successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddSecurityQuestions');
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Security question already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddSecurityQuestions');
      }
    }
    else if($_POST['FormType'] == 2) // edit Question 
    {
      $data = array(
       'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countDisclosure($data);
      if($query == 0)
      {
        if($DisclosureDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
        {
          // update function
            $set = array(
              'Description' => htmlentities($_POST['Description'], ENT_QUOTES)
            );
            $condition = array(
              'SecurityQuestionId' => $_POST['SecurityQuestionId']
            );
            $table = 'R_SecurityQuestions';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        // admin audits finalss
          $TransactionNumber = 'DA-'.sprintf('%06d', $_POST['DisclosureId']);
          $auditLogsManager = 'Updated security question details #'.$TransactionNumber.' in security question setup.';
          $auditAffectedEmployee = 'Updated security question details #'.$TransactionNumber.' in security question setup.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        // notif
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Security question details successfully updated!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddSecurityQuestion/');
      }
    }
    else // if existing
    {
      // notif
      $this->session->set_flashdata('alertTitle','Warning!'); 
      $this->session->set_flashdata('alertText','Security question details already existing!'); 
      $this->session->set_flashdata('alertType','warning'); 
      redirect('home/AddSecurityQuestion/');
    }
  }

  function getBankDetails()
  {
    $output = $this->admin_model->getBankDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }
  
  function getBranchDetails()
  {
    $output = $this->admin_model->getBranchDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getLoanTypeDetails()
  {
    $output = $this->admin_model->getLoanTypeDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getChargeDetails()
  {
    $output = $this->admin_model->getChargeDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getRequirementDetails()
  {
    $output = $this->admin_model->getRequirementDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getPositionDetails()
  {
    $output = $this->admin_model->getPositionDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getOptionalDetails()
  {
    $output = $this->admin_model->getOptionalDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getQuestionDetails()
  {
    $output = $this->admin_model->getQuestionDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getPurposeDetails()
  {
    $output = $this->admin_model->getPurposeDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getMethodDetails()
  {
    $output = $this->admin_model->getMethodDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getAssetDetails()
  {
    $output = $this->admin_model->getAssetDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getEducationDetails()
  {
    $output = $this->admin_model->getEducationDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getLoanStatusDetails()
  {
    $output = $this->admin_model->getLoanStatusDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getBorrowerStatusDetails()
  {
    $output = $this->admin_model->getBorrowerStatusDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getIndustryDetails()
  {
    $output = $this->admin_model->getIndustryDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getDisbursementDetails()
  {
    $output = $this->admin_model->getDisbursementDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getDisclosureDetails()
  {
    $output = $this->admin_model->getDisclosureDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getAssetManagementDetails()
  {
    $output = $this->admin_model->getAssetManagementDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getOccupationDetails()
  {
    $output = $this->admin_model->getOccupationDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getRepaymentDetails()
  {
    $output = $this->admin_model->getRepaymentDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getExpenseTypeDetails()
  {
    $output = $this->admin_model->getExpenseTypeDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getWithdrawalTypeDetails()
  {
    $output = $this->admin_model->getWithdrawalTypeDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getWithdrawalDetails()
  {
    $output = $this->admin_model->getWithdrawalDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function updateStatus()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $input = array( 
      'Id' => htmlentities($this->input->post('Id'), ENT_QUOTES)
      , 'updateType' => htmlentities($this->input->post('updateType'), ENT_QUOTES)
      , 'tableType' => htmlentities($this->input->post('tableType'), ENT_QUOTES)
    );
    // $query = $this->admin_model->updateStatus($input);
    $output = $this->admin_model->updateStatus($input);
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function truncateBranchDB()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $Password = $this->session->userdata('Password');
    if($_POST['Username'] == $EmployeeNumber && $_POST['NewPassword'] == $Password && $_POST['confirmPassword'] == $Password)
    {
      $this->db->query("SET foreign_key_checks = 0;");
      $this->db->truncate('applicationfooter');
      $this->db->truncate('application_has_approver');
      $this->db->truncate('application_has_charges');
      $this->db->truncate('application_has_collaterals');
      $this->db->truncate('application_has_comaker');
      $this->db->truncate('application_has_conditionalcharges');
      $this->db->truncate('application_has_disclosurestatement');
      $this->db->truncate('application_has_expense');
      $this->db->truncate('application_has_interests');
      $this->db->truncate('application_has_monthlyincome');
      $this->db->truncate('application_has_monthlyobligation');
      $this->db->truncate('application_has_notifications');
      $this->db->truncate('application_has_penalty');
      $this->db->truncate('application_has_requirements');
      $this->db->truncate('application_has_interests');
      $this->db->truncate('application_has_notifications');
      $this->db->truncate('application_has_penalty');
      $this->db->truncate('application_has_status');
      $this->db->truncate('application_has_comments');
      $this->db->truncate('application_has_education');
      $this->db->truncate('application_has_contact');
      $this->db->truncate('application_has_email');
      $this->db->truncate('application_has_address');
      $this->db->truncate('application_has_disbursement');
      $this->db->truncate('application_has_employer');
      $this->db->truncate('application_has_personalreference');
      $this->db->truncate('application_has_spouse');
      $this->db->truncate('borroweraddresshistory');
      $this->db->truncate('borrower_has_comaker');
      $this->db->truncate('borrower_has_contactnumbers');
      $this->db->truncate('borrower_has_education');
      $this->db->truncate('borrower_has_emails');
      $this->db->truncate('borrower_has_employer');
      $this->db->truncate('borrower_has_notifications');
      $this->db->truncate('borrower_has_picture');
      $this->db->truncate('borrower_has_position');
      $this->db->truncate('borrower_has_reference');
      $this->db->truncate('borrower_has_spouse');
      $this->db->truncate('borrower_has_supportdocuments');
      $this->db->truncate('branch_has_address');
      $this->db->truncate('branch_has_contactnumbers');
      $this->db->truncate('comments_has_attachments');
      $this->db->truncate('company_has_logo');
      $this->db->truncate('employee_has_address');
      $this->db->truncate('employee_has_contactnumbers');
      $this->db->truncate('employee_has_emails');
      $this->db->truncate('employee_has_identifications');
      $this->db->truncate('employee_has_notifications');
      $this->db->truncate('l_employeelog');
      $this->db->truncate('manager_has_notifications');
      $this->db->truncate('repaymentcycle_has_content');
      $this->db->truncate('requirements_has_attachments');
      $this->db->truncate('r_address');
      $this->db->truncate('r_assetmanagement');
      $this->db->truncate('r_bank');
      $this->db->truncate('r_borrowers');
      $this->db->truncate('r_borrowerstatus');
      $this->db->truncate('r_borrower_has_status');
      $this->db->truncate('r_branches');
      $this->db->truncate('r_capital');
      $this->db->truncate('r_category');
      $this->db->truncate('r_charges');
      $this->db->truncate('r_collaterals');
      $this->db->truncate('r_companydetail');
      $this->db->truncate('r_contactnumbers');
      $this->db->truncate('r_emails');
      // admin audits finalss
        $auditLogsManager = 'Database reset.';
        $auditAffectedEmployee = 'Database reset.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      $this->db->truncate('branch_has_employee');
      $this->db->truncate('branch_has_manager');
      $this->db->truncate('r_employee');
      $this->db->truncate('r_expense');
      $this->db->truncate('r_expensetype');
      $this->db->truncate('r_idcategory');
      $this->db->truncate('r_identificationcards');
      $this->db->truncate('r_industry');
      $this->db->truncate('r_intangibles');
      $this->db->truncate('r_loans');
      $this->db->truncate('r_loanstatus');
      $this->db->truncate('r_loanundertaking');
      $this->db->truncate('r_logs');
      $this->db->truncate('r_methodofpayment');
      $this->db->truncate('r_optionalcharges');
      $this->db->truncate('r_penalty');
      $this->db->truncate('r_personalreference');
      $this->db->truncate('r_position');
      $this->db->truncate('r_profilepicture');
      $this->db->truncate('r_purpose');
      $this->db->truncate('r_repaymentcycle');
      $this->db->truncate('r_requirements');
      $this->db->truncate('r_riskagegroup');
      $this->db->truncate('r_riskloanpercentage');
      $this->db->truncate('r_risktenure');
      $this->db->truncate('r_role');
      $this->db->truncate('r_spouse');
      $this->db->truncate('r_userrole');
      $this->db->truncate('r_withdrawal');
      $this->db->truncate('r_withdrawaltype');
      $this->db->truncate('t_application');
      $this->db->truncate('t_changemade');
      $this->db->truncate('t_paymentdue');
      $this->db->truncate('t_paymentsmade');
      $this->db->truncate('R_DisclosureAgreement');
      $this->db->truncate('r_userrole_has_r_securityquestions');
      $this->db->truncate('R_UserAccess');
      $this->db->truncate('employee_has_status');
                          // SET foreign_key_checks = 1 ;
      // add bank
        $insertDataBank = array(
          'BankName' => 'Cash',
          'CreatedBy' => '000000',
          'StatusId' => 1,
        );
        $auditTableBank = 'application_has_status';
        $this->maintenance_model->insertFunction($insertDataBank, $auditTableBank);
      // application has status
        $insertData1 = array(
          'Name' => 'Approved',
          'IsApprovable' => 0,
          'statusColor' => 'green',
          'IsEditable' => 0,
          'StatusId' => 1,
        );
        $insertData2 = array(
          'Name' => 'Declined',
          'IsApprovable' => 0,
          'statusColor' => 'red',
          'IsEditable' => 0,
          'StatusId' => 1,
        );
        $insertData3 = array(
          'Name' => 'For Approval',
          'statusColor' => 'blue',
          'IsApprovable' => 1,
          'IsEditable' => 0,
          'StatusId' => 1,
        );
        $insertData4 = array(
          'Name' => 'Matured',
          'IsApprovable' => 0,
          'statusColor' => 'green',
          'IsEditable' => 0,
          'StatusId' => 1,
        );
        $auditTable = 'application_has_status';
        $this->maintenance_model->insertFunction($insertData1, $auditTable);
        $this->maintenance_model->insertFunction($insertData2, $auditTable);
        $this->maintenance_model->insertFunction($insertData3, $auditTable);
        $this->maintenance_model->insertFunction($insertData4, $auditTable);
      // borrower status
        $insertDataBS1 = array(
          'Name'          => 'Active',
          'statusColor' => 'green',
          'BranchId'      => 1,
          'IsApprovable'  => 0,
        );
        $insertDataBS2 = array(
          'Name' => 'Deactivated',
          'statusColor' => 'red',
          'BranchId' => 1,
          'IsApprovable'  => 0,
        );
        $auditTableBS = 'r_borrowerstatus';
        $this->maintenance_model->insertFunction($insertDataBS1, $auditTableBS);
        $this->maintenance_model->insertFunction($insertDataBS2, $auditTableBS);
      // employee status
        $insertDataES1 = array(
          'Name'          => 'Deactivated',
        );
        $insertDataES2 = array(
          'Name'          => 'Regular',
        );
        $auditTableES = 'employee_has_status';
        $this->maintenance_model->insertFunction($insertDataES1, $auditTableES);
        $this->maintenance_model->insertFunction($insertDataES2, $auditTableES);
      // branch
        $insertDataB = array(
          'Name' => 'Taytay',
          'Code' => 'TAY',
          'BranchId' => 1,
        );
        $auditTableB = 'r_branches';
        $this->maintenance_model->insertFunction($insertDataB, $auditTableB);
      // branch
        $insertDataP = array(
          'Name' => 'Owner',
          'CreatedBy' => '000000',
          'BranchId' => 1,
        );
        $auditTableP = 'r_position';
        $this->maintenance_model->insertFunction($insertDataP, $auditTableP);
      // employee
        $insertDataE = array(
          'FirstName' => 'GIA TECH',
          'LastName' => 'INFORMATION SOLUTIONS',
          'EmployeeNumber' => '000000',
          'StatusId' => 2,
        );
        $auditTablee = 'r_employee';
        $this->maintenance_model->insertFunction($insertDataE, $auditTablee);
        $insertDataE2 = array(
          'FirstName' => 'Myrna',
          'LastName' => 'Biliber',
          'EmployeeNumber' => '000001',
          'Nationality' => 1,
          'Sex' => 1,
          'CivilStatus' => 1,
          'Salutation' => 3,
          'PositionId' => 1,
          'ManagerId' => 1,
          'StatusId' => 2,
        );
        $auditTablee2 = 'r_employee';
        $this->maintenance_model->insertFunction($insertDataE2, $auditTablee2);
      // branch has employee
        $insertDataBHE = array(
          'EmployeeNumber' => '000000',
          'BranchId' => 1,
        );
        $auditTableBHE = 'branch_has_employee';
        $insertDataBHE2 = array(
          'EmployeeNumber' => '000001',
          'BranchId' => 1,
          'ManagerBranchId' => 1,
        );
        $this->maintenance_model->insertFunction($insertDataBHE, $auditTableBHE);
        $this->maintenance_model->insertFunction($insertDataBHE2, $auditTableBHE);
      // branch has manager
        $insertDataBHE3 = array(
          'EmployeeNumber' => '000001',
          'BranchId' => 1,
        );
        $auditTableBHE3 = 'branch_has_manager';
        $this->maintenance_model->insertFunction($insertDataBHE3, $auditTableBHE3);
      // r_loanundertaking
        $insertDataLU = array(
          'Description' => 'I hereby certify that all information herein, including all documents submitted along with this application, are genuine, true and correct. I authorize the Creditor and / or its representative to verify any and all information furnished by me, including any credit credit transactions with other institutions.',
          'BranchId' => 1,
        );
        $auditTableLU = 'r_loanundertaking';
        $this->maintenance_model->insertFunction($insertDataLU, $auditTableLU);
      // // r_repaymentcycle
      //   $insertDataRC = array(
      //     'Type' => 'Daily',
      //     'Type' => 'Weekly',
      //     'Type' => 'Monthly',
      //     'Type' => 'Yearly',
      //     'Type' => 'Lump Sum',
      //     'Type' => 'Dates',
      //   );
      //   $auditTableRC = 'r_repaymentcycle';
      //   $this->maintenance_model->insertFunction($insertDataRC, $auditTableBS);
      // r_userrole
        $insertDataUR = array(
          'EmployeeNumber' => '000000',
          'IsNew' => 0,
          'StatusId' => 1,
        );
        $auditTableUR = 'r_userrole';
        $this->maintenance_model->insertFunction($insertDataUR, $auditTableUR);
        $set = array( 
          'Password' => 'LookingGood!',
        );
        $condition = array( 
          'EmployeeNumber' => '000000'
        );
        $table = 'r_userrole';
        $this->maintenance_model->updateFunction1($set, $condition, $table);

        $employeeRoles = $this->employee_model->getSubmodules();
        foreach ($employeeRoles as $roles) 
        {
          $insertData = array(
            'EmployeeNumber'              => '000000'
            , 'StatusId'                  => 1
            , 'SubModuleId'               => $roles['SubModuleId']
            , 'Code'                      => $roles['Code']
            , 'ModuleId'                  => $roles['ModuleId']
          );
          $insertTable = 'R_UserAccess';
          $this->maintenance_model->insertFunction($insertData, $insertTable);
        }
        // for owner
        $insertDataUR2 = array(
          'EmployeeNumber' => '000001',
          'IsNew' => 1,
          'StatusId' => 1,
        );
        $auditTableUR2 = 'r_userrole';
        $this->maintenance_model->insertFunction($insertDataUR2, $auditTableUR2);
        $set2 = array( 
          'Password' => '000001',
        );
        $condition2 = array( 
          'EmployeeNumber' => '000001'
        );
        $table2 = 'r_userrole';
        $this->maintenance_model->updateFunction1($set2, $condition2, $table2);

        $employeeRoles = $this->employee_model->getSubmodules();
        foreach ($employeeRoles as $roles) 
        {
          $insertData = array(
            'EmployeeNumber'              => '000001'
            , 'StatusId'                  => 1
            , 'SubModuleId'               => $roles['SubModuleId']
            , 'Code'                      => $roles['Code']
            , 'ModuleId'                  => $roles['ModuleId']
          );
          $insertTable = 'R_UserAccess';
          $this->maintenance_model->insertFunction($insertData, $insertTable);
        }
      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','System successfully truncated!'); 
        $this->session->set_flashdata('alertType','success'); 
      
        redirect(site_url());
    }
    else
    {
      // notification
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','You have no access to reset database!'); 
        $this->session->set_flashdata('alertType','warning'); 
      
        redirect('home/branchDatabase/');
    }
  }

  function ResetPassword()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    // update password
      $set = array( 
        'Password' => $_POST['NewPassword'],
        'IsNew' => 0
      );

      $condition = array( 
        'EmployeeNumber' => $EmployeeNumber
      );
      $table = 'r_userrole';
      $this->maintenance_model->updateFunction1($set, $condition, $table);

    // admin audits finalss
      $auditLogsManager = 'Changed password.';
      $auditAffectedEmployee = 'Changed password.';
      $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
    // notification
      $this->session->set_flashdata('alertTitle','Success!'); 
      $this->session->set_flashdata('alertText','Password successfully changed!'); 
      $this->session->set_flashdata('alertType','success'); 
    
    redirect('home/userprofile/' . $EmployeeNumber);
  }

  public function download($id)
  {
    //load download helper
      $this->load->helper('download');
      $this->load->library('zip');
      if($this->uri->segment(3) == 1) // download id
      {
        //get file info from database
          $detail = $this->employee_model->getAttachment($this->uri->segment(4));

          // File path
          $filepath1 = FCPATH.'/uploads/' . $detail['FileName'];
          // Add file
          $fileName = 'Identification Attachment.zip';
          $this->zip->read_file($filepath1, $detail['Attachment']);
          $this->zip->download($fileName);
      }
      else if($this->uri->segment(3) == 2) // download supporting docs
      {
        //get file info from database
          $detail = $this->borrower_model->getAttachment($this->uri->segment(4));

          // File path
          $filepath1 = FCPATH.'/borrowerarchive/' . $detail['FileName'];
          // Add file
          $fileName = 'Supporting Document.zip';
          $this->zip->read_file($filepath1, $detail['Attachment']);
          $this->zip->download($fileName);
      }
  }

  function getFilteredDashboard()
  {
    $output = $this->maintenance_model->getFilteredDashboard($this->input->post('branchId'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getAgePopulation()
  {
    $output = $this->maintenance_model->getAge($this->input->post('yearFilter'), $this->input->post('branchId'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getEducationPopulation()
  {
    $output = $this->maintenance_model->getEducation($this->input->post('yearFilter'), $this->input->post('branchId'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getGenderPopulation()
  {
    $output = $this->maintenance_model->getGender($this->input->post('yearFilter'), $this->input->post('branchId'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getOccupationPopulation()
  {
    $output = $this->maintenance_model->getOccupationPopulation($this->input->post('yearFilter'), $this->input->post('branchId'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getIncomeLevelPopulation()
  {
    $output = $this->maintenance_model->getIncomeLevelPopulation($this->input->post('yearFilter'), $this->input->post('branchId'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getMonthlyCollection()
  {
    $output = $this->maintenance_model->getMonthlyCollection($this->input->post('yearFilter'), $this->input->post('branchId'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getMonthlyDisbursement()
  {
    $output = $this->maintenance_model->getMonthlyDisbursement($this->input->post('yearFilter'), $this->input->post('branchId'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getMonthlyInterest()
  {
    $output = $this->maintenance_model->getMonthlyInterest($this->input->post('yearFilter'), $this->input->post('branchId'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getMaritalStatusPopulation()
  {
    $output = $this->maintenance_model->getMaritalStatusPopulation($this->input->post('yearFilter'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getTotalBorrowers()
  {
    $output = $this->maintenance_model->getTotalBorrowers();
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getLoanType()
  {
    $output = $this->maintenance_model->getLoanType($this->input->post('yearFilter'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getTotalLoans()
  {
    $output = $this->maintenance_model->getTotalLoans();
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getTotalLoanAmount()
  {
    $output = $this->maintenance_model->getTotalLoanAmount();
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getChargesTotal()
  {
    $output = $this->maintenance_model->getChargesTotal($this->input->post('yearFilter'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getTenors()
  {
    $output = $this->maintenance_model->getTenors($this->input->post('yearFilter'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getRequirements()
  {
    $output = $this->maintenance_model->getRequirements();
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getTotalInterest()
  {
    $output = $this->maintenance_model->getTotalInterest();
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function AuditFunction($auditLogsManager, $auditAffectedEmployee, $ManagerId, $AffectedEmployeeNumber)
  {
    $AssignedBranchId = $this->session->userdata('BranchId');
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
      , 'BranchId'        => $AssignedBranchId
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
  }

  function finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $ManagerId, $AffectedEmployeeNumber, $auditLoanDets, $ApplicationId, $independentTable, $independentColumn)
  {
    $AssignedBranchId = $this->session->userdata('BranchId');
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
      , 'BranchId'        => $AssignedBranchId
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

  function generateReport()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'M.C Biliber Lending Corporation', "Report Generation");
    // set margins
    $pdf->SetMargins('10', '20', '10');
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->SetFont('dejavusans', '', 10);

    $employeeDetail = $this->employee_model->getEmployeeProfile($EmployeeNumber);
    $DateNow = date("m-d-Y");
    
    $pdf->AddPage('L', 'A4');
    $branchName = $this->maintenance_model->selectSpecific('R_Branches', 'BranchId', $this->session->userdata('BranchId'));

    $html = '
      <style>
      table {
        border-collapse: collapse;
      }

      table, td, th {
        border: 1px solid black;
      }

      p {
        text-align: center;
        font-size: 15px;
      }
      </style>

      <p>History Logs<br><small>'.$branchName['Name'].' Branch</small></p>

      <br>
      <br>
      ';
      $logs = $this->admin_model->getAuditLogs();
      $html .='
      <table>
        <tr>
        <td><strong>Row No.</strong></td>
        <td><strong>Description</strong></td>
        <td><strong>Remarks</strong></td>
        <td><strong>Date Created</strong> </td>
        <td><strong>Created By</strong></td>
        <td><strong>Branch</strong></td>
        </tr>
        <tbody>';
          $rowNumber = 0;
          foreach ($logs as $value)
          {
            $rowNumber = $rowNumber + 1;
            $html .='<tr>';
            $html .='<td>'.$rowNumber.'</td>';
            $html .='<td>'.$value['Description'].'</td>';
            $html .='<td>'.$value['Remarks'].'</td>';
            $html .='<td>'.$value['DateCreated'].'</td>';
            $html .='<td>'.$value['Name'].'</td>';
            $html .='<td>'.$value['Branch'].'</td>';
            $html .='</tr>';
          }
        $html .= '
        <tbody>
      </table>
      <br><br>
      <br><br>
    ';
    $pdf->writeHTML($html, true, false, true, false, '');

    // Close and output PDF document
    // $pdf->Output('Borrower Data.pdf', 'I');
    $pdf->Output('History Logs.pdf', 'D');
  }
}
