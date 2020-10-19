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
      // audits
        $auditDetail = 'Changed temporary password.';
        $insertData = array(
          'Description' => $auditDetail,
          'CreatedBy' => $EmployeeNumber
        );
        $auditTable = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
        $insertEmpNotif = array(
          'Description'     => $auditDetail,
          'CreatedBy'       => $EmployeeNumber,
          'EmployeeNumber'  => $EmployeeNumber
        );
        $auditTableEmpNotif = 'employee_has_notifications';
        $this->maintenance_model->insertFunction($insertEmpNotif, $auditTableEmpNotif);
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
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Temporary password successfully changed!'); 
        $this->session->set_flashdata('alertType','success'); 
      
      redirect('home/userprofile/' . $EmployeeNumber);
    }
    else // add user
    {
      // insert
        $insertRoles = array(
          'EmployeeNumber'                    => $_POST['selectEmployee']
          , 'RoleId'                          => $_POST['selectRoleId']
          , 'Password'                        => $_POST['selectEmployee']
          , 'CreatedBy'                       => $EmployeeNumber
          , 'UpdatedBy'                       => $EmployeeNumber
        );
        $insertRoleTable = 'R_Userrole';
        $this->maintenance_model->insertFunction($insertRoles, $insertRoleTable);
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
        'BankName'                     => htmlentities($_POST['BankName'], ENT_QUOTES)
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
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Bank successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddBank/'. $EmployeeId['EmployeeId']);
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
        'BankName'                     => htmlentities($_POST['BankName'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
        , 'AccountNumber'          => htmlentities($_POST['AccountNumber'], ENT_QUOTES)
      );
      $query = $this->admin_model->countBank($data);
      if($query == 0)
      {
        if($BankDetail['BankName'] != htmlentities($_POST['BankName'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$BankDetail['BankName'].' to '.htmlentities($_POST['BankName'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
          // add into audit table
            $auditDetail = 'Updated details of  '.$BankDetail['Description'].' to '.htmlentities($_POST['Description'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
          // add into audit table
            $auditDetail = 'Updated details of  '.$BankDetail['AccountNumber'].' to '.htmlentities($_POST['AccountNumber'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
          $insertBranchTable = 'R_Branch';
          $this->maintenance_model->insertFunction($insertBranch, $insertBranchTable);
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
      $data = array(
        'Name'                     => htmlentities($_POST['Branch'], ENT_QUOTES)
        , 'Code'                   => htmlentities($_POST['Code'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
        , 'DateFromLease'          => htmlentities($DateFrom, ENT_QUOTES)
        , 'DateToLease'          => htmlentities($DateTo, ENT_QUOTES)
        , 'LeaseMonthly'          => htmlentities($_POST['Monthly'], ENT_QUOTES)
        , 'CompanyId'              => 1
        , 'CreatedBy'              => $EmployeeNumber
        , 'UpdatedBy'              => $EmployeeNumber
      );
      $query = $this->admin_model->countBranch($data);
      print_r($query);
      if($query == 0)
      {
        if($BranchDetail['Name'] != htmlentities($_POST['Branch'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$BranchDetail['Name'].' to '.htmlentities($_POST['Branch'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
              'Name' => htmlentities($_POST['Branch'], ENT_QUOTES)
            );
            $condition = array( 
              'BranchId' => $_POST['BranchId']
            );
            $table = 'R_Branch';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($BranchDetail['Code'] != htmlentities($_POST['Code'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$BranchDetail['Code'].' to '.htmlentities($_POST['Code'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
              'Code' => htmlentities($_POST['Code'], ENT_QUOTES)
            );
            $condition = array( 
              'BranchId' => $_POST['BranchId']
            );
            $table = 'R_Branch';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($BranchDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$BranchDetail['Description'].' to '.htmlentities($_POST['Description'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
              'Description' => htmlentities($_POST['Description'], ENT_QUOTES)
            );
            $condition = array( 
              'BranchId' => $_POST['BranchId']
            );
            $table = 'R_Branch';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($BranchDetail['DateFromLease'] != htmlentities($DateFrom, ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$BranchDetail['DateFromLease'].' to '.htmlentities($DateFrom, ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
              'DateFromLease'          => htmlentities($DateFrom, ENT_QUOTES)
            );
            $condition = array( 
              'BranchId' => $_POST['BranchId']
            );
            $table = 'R_Branch';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($BranchDetail['DateToLease'] != htmlentities($DateTo, ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$BranchDetail['DateToLease'].' to '.htmlentities($DateTo, ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
              'DateToLease'          => htmlentities($DateTo, ENT_QUOTES)
            );
            $condition = array( 
              'BranchId' => $_POST['BranchId']
            );
            $table = 'R_Branch';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($BranchDetail['LeaseMonthly'] != htmlentities($_POST['Monthly'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$BranchDetail['LeaseMonthly'].' to '.htmlentities($_POST['Monthly'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
              'LeaseMonthly'          => htmlentities($_POST['Monthly'], ENT_QUOTES)
            );
            $condition = array( 
              'BranchId' => $_POST['BranchId']
            );
            $table = 'R_Branch';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }

      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Branch details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddBranch/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Branch details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddBranch/');
      }
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
      print_r($query);
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
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Loan Type successfully recorded!'); 
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
          // add into audit table
            $auditDetail = 'Updated details of  '.$LoanTypeDetail['Name'].' to '.htmlentities($_POST['LoanType'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
          // add into audit table
            $auditDetail = 'Updated details of  '.$LoanTypeDetail['Description'].' to '.htmlentities($_POST['Description'], ENT_QUOTES);
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
              'LoanId' => $_POST['LoanId']
            );
            $table = 'R_Loans';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
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
        'Type'                     => htmlentities($_POST['ChargeType'], ENT_QUOTES)
        , 'Name'                   => htmlentities($_POST['ConditionalName'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
        , 'Amount'              => htmlentities($_POST['Amount'], ENT_QUOTES)
      );
      $query = $this->admin_model->countCharges($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Bank details
          $insertCharge = array(
            'Type'                     => htmlentities($_POST['ChargeType'], ENT_QUOTES)
            , 'Name'                   => htmlentities($_POST['ConditionalName'], ENT_QUOTES)
            , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'Amount'              => htmlentities($_POST['Amount'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertChargeTable = 'R_Charges';
          $this->maintenance_model->insertFunction($insertCharge, $insertChargeTable);
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
        'Type'                     => htmlentities($_POST['ChargeType'], ENT_QUOTES)
        , 'Name'                   => htmlentities($_POST['ConditionalName'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
        , 'Amount'              => htmlentities($_POST['Amount'], ENT_QUOTES)
      );
      $query = $this->admin_model->countCharges($data);
      print_r($query);
      if($query == 0)
      {
        if($ChargeDetail['Type'] != htmlentities($_POST['ChargeType'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$ChargeDetail['Type'].' to '.htmlentities($_POST['ChargeType'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'Type'                     => htmlentities($_POST['ChargeType'], ENT_QUOTES)
            );
            $condition = array( 
              'ChargeId' => $_POST['ChargeId']
            );
            $table = 'R_Charges';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($ChargeDetail['Name'] != htmlentities($_POST['ConditionalName'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$ChargeDetail['Name'].' to '.htmlentities($_POST['ConditionalName'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
          // add into audit table
            $auditDetail = 'Updated details of  '.$ChargeDetail['Description'].' to '.htmlentities($_POST['Description'], ENT_QUOTES);
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
              'ChargeId' => $_POST['ChargeId']
            );
            $table = 'R_Charges';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($ChargeDetail['Amount'] != htmlentities($_POST['Amount'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$ChargeDetail['Amount'].' to '.htmlentities($_POST['Amount'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertRequirementTable = 'R_Requirements';
          $this->maintenance_model->insertFunction($insertRequirement, $insertRequirementTable);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Requirement successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddRequirement/'. $EmployeeId['EmployeeId']);
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
      print_r($query);
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
      $data = array(
         'Name'                    => htmlentities($_POST['Position'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countPositions($data);
      print_r($query);
      if($query == 0)
      {
        if($PositionDetail['Name'] != htmlentities($_POST['Position'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$PositionDetail['Name'].' to '.htmlentities($_POST['Position'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Bank details
          $insertPurpose = array(
             'Name'                    => htmlentities($_POST['Purpose'], ENT_QUOTES)
            , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertPurposeTable = 'R_Purpose';
          $this->maintenance_model->insertFunction($insertPurpose, $insertPurposeTable);
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
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countMethod($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Bank details
          $insertMethod = array(
             'Name'                    => htmlentities($_POST['Method'], ENT_QUOTES)
            , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertMethodTable = 'R_MethodOfPayment';
          $this->maintenance_model->insertFunction($insertMethod, $insertMethodTable);
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
    $AssetManagementDetail = $this->admin_model->getAssetManagementDetails($_POST['AssetManagementId']);
    if ($_POST['FormType'] == 1) // add Asset
    {
      $data = array(
        'PurchaseValue'               => htmlentities($_POST['PurchasePrice'], ENT_QUOTES)
        , 'Type'                      => htmlentities($_POST['AssetType'], ENT_QUOTES)
        , 'Name'                      => htmlentities($_POST['AssetName'], ENT_QUOTES)
        , 'Stock'                     => htmlentities($_POST['Stock'], ENT_QUOTES)
        , 'CriticalLevel'             => htmlentities($_POST['CriticalLevel'], ENT_QUOTES)
        , 'CategoryId'                => htmlentities($_POST['CategoryId'], ENT_QUOTES)
        , 'ReplacementValue'          => htmlentities($_POST['ReplacementValue'], ENT_QUOTES)
        , 'SerialNumber'              => htmlentities($_POST['SerialNumber'], ENT_QUOTES)
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
            , 'SerialNumber'           => htmlentities($_POST['SerialNumber'], ENT_QUOTES)
            , 'BoughtFrom'             => htmlentities($_POST['BoughtFrom'], ENT_QUOTES)
            , 'BranchId'               => htmlentities($_POST['BranchId'], ENT_QUOTES)
            , 'StatusId'               => 2
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertTangibleTable = 'R_AssetManagement';
          $this->maintenance_model->insertFunction($insertTangible, $insertTangibleTable);
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
      $data = array(
         'PurchaseValue'               => htmlentities($_POST['PurchasePrice'], ENT_QUOTES)
        , 'Type'                      => htmlentities($_POST['AssetType'], ENT_QUOTES)
        , 'CategoryId'                => htmlentities($_POST['CategoryId'], ENT_QUOTES)
        , 'ReplacementValue'          => htmlentities($_POST['ReplacementValue'], ENT_QUOTES)
        , 'SerialNumber'              => htmlentities($_POST['SerialNumber'], ENT_QUOTES)
        , 'BoughtFrom'                => htmlentities($_POST['BoughtFrom'], ENT_QUOTES)
        , 'Description'               => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countTangibles($data);
      print_r($query);
      if($query == 0)
      {
        if($AssetManagementDetail['PurchaseValue'] != htmlentities($_POST['PurchasePrice'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$AssetManagementDetail['PurchaseValue'].' to '.htmlentities($_POST['PurchasePrice'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
          // add into audit table
            $auditDetail = 'Updated details of  '.$AssetManagementDetail['Type'].' to '.htmlentities($_POST['AssetType'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
          // add into audit table
            $auditDetail = 'Updated details of  '.$AssetManagementDetail['CategoryId'].' to '.htmlentities($_POST['CategoryId'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
          // add into audit table
            $auditDetail = 'Updated details of  '.$AssetManagementDetail['ReplacementValue'].' to '.htmlentities($_POST['ReplacementValue'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
          // add into audit table
            $auditDetail = 'Updated details of  '.$AssetManagementDetail['SerialNumber'].' to '.htmlentities($_POST['SerialNumber'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
          // add into audit table
            $auditDetail = 'Updated details of  '.$AssetManagementDetail['BoughtFrom'].' to '.htmlentities($_POST['BoughtFrom'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Asset details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddAssetManagement/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Asset details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddAssetManagement/');
      }
    }
  }

  function AddLoanStatus()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $LoanStatusDetail = $this->admin_model->getLoanStatusDetails($_POST['LoanStatusId']);
    if ($_POST['FormType'] == 1) // add LoanStatus
    {
      $data = array(
        'Name'                     => htmlentities($_POST['LoanStatus'], ENT_QUOTES)
        , 'Description'          => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countLoanStatus($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert LoanStatus details
          $insertLoanStatus = array(
            'Name'                     => htmlentities($_POST['LoanStatus'], ENT_QUOTES)
            , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertLoanStatusTable = 'R_LoanStatus';
          $this->maintenance_model->insertFunction($insertLoanStatus, $insertLoanStatusTable);
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
    $BorrowerStatusDetail = $this->admin_model->getBorrowerStatusDetails($_POST['BorrowerStatusId']);
    if ($_POST['FormType'] == 1) // add Borrower Status
    {
      $data = array(
        'Name'                     => htmlentities($_POST['BorrowerStatus'], ENT_QUOTES)
        , 'Description'          => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countBorrowerStatus($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Borrower Status details
          $insertBorrowerStatus = array(
            'Name'                     => htmlentities($_POST['BorrowerStatus'], ENT_QUOTES)
            , 'Description'                   => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertBorrowerStatusTable = 'R_Borrower_has_Status';
          $this->maintenance_model->insertFunction($insertBorrowerStatus, $insertBorrowerStatusTable);
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
      $data = array(
         'Name'                    => htmlentities($_POST['BorrowerStatus'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countBorrowerStatus($data); //Count if existing
      print_r($query);
      if($query == 0)
      {
        if($BorrowerStatusDetail['Name'] != htmlentities($_POST['BorrowerStatus'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$BorrowerStatusDetail['Name'].' to '.htmlentities($_POST['BorrowerStatus'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'Name'                     => htmlentities($_POST['BorrowerStatus'], ENT_QUOTES)
            );
            $condition = array( 
              'BorrowerStatusId' => $_POST['BorrowerStatusId']
            );
            $table = 'R_Borrower_has_Status';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($BorrowerStatusDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$BorrowerStatusDetail['Description'].' to '.htmlentities($_POST['Description'], ENT_QUOTES);
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
              'BorrowerStatusId' => $_POST['BorrowerStatusId']
            );
            $table = 'R_Borrower_has_Status';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
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
          // add into audit table
            $auditDetail = 'Updated details of  '.$IndustryDetail['Name'].' to '.htmlentities($_POST['Industry'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
          // add into audit table
            $auditDetail = 'Updated details of  '.$IndustryDetail['Description'].' to '.htmlentities($_POST['Description'], ENT_QUOTES);
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
              'IndustryId' => $_POST['IndustryId']
            );
            $table = 'R_Industry';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
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
      $query = $this->admin_model->countPositions($data);
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
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Education Level successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddEducation/'. $EmployeeId['EmployeeId']);
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
      print_r($query);
      if($query == 0)
      {
        if($EducationDetail['Name'] != htmlentities($_POST['Education'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$EducationDetail['Name'].' to '.htmlentities($_POST['Education'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'Name'                     => htmlentities($_POST['Education'], ENT_QUOTES)
            );
            $condition = array( 
              'EducationId' => $_POST['EducationId']
            );
            $table = 'R_Education';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($EducationDetail['Description'] != htmlentities($_POST['Description'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$EducationDetail['Description'].' to '.htmlentities($_POST['Description'], ENT_QUOTES);
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
              'EducationId' => $_POST['EducationId']
            );
            $table = 'R_Education';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
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
    $OccupationDetail = $this->admin_model->getOccupationDetails($_POST['OccupationId']);
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
          // add into audit table
            $auditDetail = 'Updated details of  '.$OccupationDetail['Name'].' to '.htmlentities($_POST['Occupation'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
          // add into audit table
            $auditDetail = 'Updated details of  '.$OccupationDetail['Description'].' to '.htmlentities($_POST['Description'], ENT_QUOTES);
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
              'OccupationId' => $_POST['OccupationId']
            );
            $table = 'R_Occupation';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
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
    $RepaymentDetail = $this->admin_model->getRepaymentDetails($_POST['RepaymentId']);
    if ($_POST['FormType'] == 1) // add Repayment
    {
      $data = array(
        'Type'                     => htmlentities($_POST['Repayment'], ENT_QUOTES)
      );
      $query = $this->admin_model->countRepayment($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Repayment details
          $insertRepayment = array(
            'Type'                     => htmlentities($_POST['Repayment'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
            , 'UpdatedBy'              => $EmployeeNumber
          );
          $insertRepaymentTable = 'R_RepaymentCycle';
          $this->maintenance_model->insertFunction($insertRepayment, $insertRepaymentTable);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Repayment Cycle successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddRepaymentCycle/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Repayment Cycle already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddRepaymentCycle');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Repayments 
    {
      $data = array(
        'Type'                     => htmlentities($_POST['Repayment'], ENT_QUOTES)
      );
      $query = $this->admin_model->countOccupation($data);
      print_r($query);
      if($query == 0)
      {
        if($RepaymentDetail['Name'] != htmlentities($_POST['Repayment'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$RepaymentDetail['Type'].' to '.htmlentities($_POST['Repayment'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'Type'                     => htmlentities($_POST['Repayment'], ENT_QUOTES)
            );
            $condition = array( 
              'RepaymentId' => $_POST['RepaymentId']
            );
            $table = 'R_RepaymentCycle';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Repayment Cycle details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddRepaymentCycle/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Repayment Cycle details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddRepaymentCycle/');
      }
    }
  }

  function AddCapital()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
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
            'Amount'                     => htmlentities($_POST['Capital'], ENT_QUOTES)
            , 'CreatedBy'              => $EmployeeNumber
          );
          $insertCapitalTable = 'R_Capital';
          $this->maintenance_model->insertFunction($instertCapital, $insertCapitalTable);
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
    else if($_POST['FormType'] == 2) // Edit Initial Capital 
    {
      $data = array(
        'Type'                     => htmlentities($_POST['Repayment'], ENT_QUOTES)
      );
      $query = $this->admin_model->countOccupation($data);
      print_r($query);
      if($query == 0)
      {
        if($RepaymentDetail['Name'] != htmlentities($_POST['Repayment'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$RepaymentDetail['Type'].' to '.htmlentities($_POST['Repayment'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'Type'                     => htmlentities($_POST['Repayment'], ENT_QUOTES)
            );
            $condition = array( 
              'RepaymentId' => $_POST['RepaymentId']
            );
            $table = 'R_RepaymentCycle';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Repayment Cycle details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddRepaymentCycle/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Repayment Cycle details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddRepaymentCycle/');
      }
    }
  }

  function AddExpense()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $ExpenseDetail = $this->admin_model->getExpenseDetails($_POST['ExpenseId']);
    if ($_POST['FormType'] == 1) // add Expense
    {
      $data = array(
        'ExpenseTypeId'                     => htmlentities($_POST['Expense'], ENT_QUOTES)
        , 'Amount'                        => htmlentities($_POST['Amount'], ENT_QUOTES)
        , 'DateExpense'                   => htmlentities($_POST['DateExpense'], ENT_QUOTES)
      );
      $query = $this->admin_model->countExpense($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Expense detail
          $instertExpense = array(
            'ExpenseTypeId'                => htmlentities($_POST['Expense'], ENT_QUOTES)
            , 'Amount'                   => htmlentities($_POST['Amount'], ENT_QUOTES)
            , 'DateExpense'              => htmlentities($_POST['DateExpense'], ENT_QUOTES)
            , 'CreatedBy'                => $EmployeeNumber
          );
          $insertExpenseTable = 'R_Expense';
          $this->maintenance_model->insertFunction($instertExpense, $insertExpenseTable);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Expense details successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddExpense/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Expense details already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddExpense');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Initial Capital 
    {
      $data = array(
        'Type'                     => htmlentities($_POST['Repayment'], ENT_QUOTES)
      );
      $query = $this->admin_model->countOccupation($data);
      print_r($query);
      if($query == 0)
      {
        if($RepaymentDetail['Name'] != htmlentities($_POST['Repayment'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$RepaymentDetail['Type'].' to '.htmlentities($_POST['Repayment'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'Type'                     => htmlentities($_POST['Repayment'], ENT_QUOTES)
            );
            $condition = array( 
              'RepaymentId' => $_POST['RepaymentId']
            );
            $table = 'R_RepaymentCycle';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Repayment Cycle details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddRepaymentCycle/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Repayment Cycle details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddRepaymentCycle/');
      }
    }
  }

  function AddExpenseType()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $ExpenseTypeDetail = $this->admin_model->getExpenseTypeDetails($_POST['ExpenseTypeId']);
    if ($_POST['FormType'] == 1) // add ExpenseType
    {
      $data = array(
        'Name'                     => htmlentities($_POST['ExpenseType'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countExpenseType($data);
      print_r($query);
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
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Expense Type successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddExpenseType/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Expense Type already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddExpenseType');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Expense Type 
    {
      $data = array(
        'Name'                     => htmlentities($_POST['ExpenseType'], ENT_QUOTES)
      );
      $query = $this->admin_model->countExpenseType($data);
      print_r($query);
      if($query == 0)
      {
        if($ExpenseTypeDetail['ExpenseType'] != htmlentities($_POST['ExpenseType'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$ExpenseTypeDetail['ExpenseType'].' to '.htmlentities($_POST['ExpenseType'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
          // add into audit table
            $auditDetail = 'Updated details of  '.$ExpenseTypeDetail['Description'].' to '.htmlentities($_POST['Description'], ENT_QUOTES);
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
              'ExpenseTypeId' => $_POST['ExpenseTypeId']
            );
            $table = 'R_ExpenseType';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Type of Expense details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddExpenseType/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Type of Expense details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddExpenseType/');
      }
    }
  }

  function AddWithdrawalType()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $WithdrawalTypeDetail = $this->admin_model->getWithdrawalTypeDetails($_POST['WithdrawalTypeId']);
    if ($_POST['FormType'] == 1) // add WithdrawalType
    {
      $data = array(
        'Name'                     => htmlentities($_POST['WithdrawalType'], ENT_QUOTES)
        , 'Description'            => htmlentities($_POST['Description'], ENT_QUOTES)
      );
      $query = $this->admin_model->countWithdrawalType($data);
      print_r($query);
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
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Withdrawal Type successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddWithdrawalType/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Withdrawal Type already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddWithdrawalType');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Withdrawal Type 
    {
      $data = array(
        'Name'                     => htmlentities($_POST['WithdrawalType'], ENT_QUOTES)
      );
      $query = $this->admin_model->countWithdrawalType($data);
      print_r($query);
      if($query == 0)
      {
        if($WithdrawalTypeDetail['WithdrawalType'] != htmlentities($_POST['WithdrawalType'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$WithdrawalTypeDetail['WithdrawalType'].' to '.htmlentities($_POST['WithdrawalType'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
          // add into audit table
            $auditDetail = 'Updated details of  '.$WithdrawalTypeDetail['Description'].' to '.htmlentities($_POST['Description'], ENT_QUOTES);
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
              'WithdrawalTypeId' => $_POST['WithdrawalTypeId']
            );
            $table = 'R_WithdrawalType';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Type of Withdrawal details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddWithdrawalType/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Type of Withdrawal details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddWithdrawalType/');
      }
    }
  }

  function AddWithdrawal()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $WithdrawalDetail = $this->admin_model->getWithdrawalDetails($_POST['WithdrawalId']);
    if ($_POST['FormType'] == 1) // add Withdrawal
    {
      $data = array(
        'WithdrawalTypeId'                     => htmlentities($_POST['Withdrawal'], ENT_QUOTES)
        , 'Amount'                             => htmlentities($_POST['Amount'], ENT_QUOTES)
        , 'DateWithdrawal'                     => htmlentities($_POST['DateWithdrawal'], ENT_QUOTES)
      );
      $query = $this->admin_model->countWithdrawal($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Expense detail
          $instertWithdrawal = array(
            'WithdrawalTypeId'                => htmlentities($_POST['Withdrawal'], ENT_QUOTES)
            , 'Amount'                        => htmlentities($_POST['Amount'], ENT_QUOTES)
            , 'DateWithdrawal'                => htmlentities($_POST['DateWithdrawal'], ENT_QUOTES)
            , 'CreatedBy'                     => $EmployeeNumber
          );
          $insertWithdrawalTable = 'R_Withdrawal';
          $this->maintenance_model->insertFunction($instertWithdrawal, $insertWithdrawalTable);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Withdrawal details successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/AddWithdrawal/'. $EmployeeId['EmployeeId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Withdrawal details already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/AddWithdrawal');
      }
    }
    else if($_POST['FormType'] == 2) // Edit Withdrawals 
    {
      $data = array(
        'WithdrawalTypeId'                     => htmlentities($_POST['Withdrawal'], ENT_QUOTES)
      );
      $query = $this->admin_model->countWithdrawal($data);
      print_r($query);
      if($query == 0)
      {
        if($WithdrawalDetail['WithdrawalTypeId'] != htmlentities($_POST['Withdrawal'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$WithdrawalDetail['WithdrawalTypeId'].' to '.htmlentities($_POST['Withdrawal'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
        else if($WithdrawalDetail['Amount'] != htmlentities($_POST['Amount'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$WithdrawalDetail['Amount'].' to '.htmlentities($_POST['Amount'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
        else if($WithdrawalDetail['DateWithdrawal'] != htmlentities($_POST['DateWithdrawal'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$WithdrawalDetail['DateWithdrawal'].' to '.htmlentities($_POST['DateWithdrawal'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
            'DateWithdrawal'                     => htmlentities($_POST['DateWithdrawal'], ENT_QUOTES)
            );
            $condition = array( 
              'WithdrawalId' => $_POST['WithdrawalId']
            );
            $table = 'R_Withdrawal';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Withdrawal details successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/AddWithdrawal/');
      }
      else // if existing
      {
        // notif
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Withdrawal details already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
        redirect('home/AddWithdrawal/');
      }
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

    $query = $this->admin_model->updateStatus($input);
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

    // audits
      $auditDetail = 'Changed password.';
      $insertData = array(
        'Description' => $auditDetail,
        'CreatedBy' => $EmployeeNumber
      );
      $auditTable = 'R_Logs';
      $this->maintenance_model->insertFunction($insertData, $auditTable);
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

  function submitApplication()
  {
    print_r($_POST['Approvers']);
    print_r($_POST['Details']);
    print_r($_POST['ObligationDetails']);
  }

  function getAgePopulation()
  {
    $output = $this->maintenance_model->getAge();
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getEducationPopulation()
  {
    $output = $this->maintenance_model->getEducation($this->input->post('yearFilter'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getGenderPopulation()
  {
    $output = $this->maintenance_model->getGender($this->input->post('yearFilter'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getOccupationPopulation()
  {
    $output = $this->maintenance_model->getOccupationPopulation($this->input->post('yearFilter'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getIncomeLevelPopulation()
  {
    $output = $this->maintenance_model->getIncomeLevelPopulation($this->input->post('yearFilter'));
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
}
