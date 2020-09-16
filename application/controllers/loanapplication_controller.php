<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class loanapplication_controller extends CI_Controller {

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
    $this->load->model('maintenance_model');

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


  function submitApplication()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    // loan product details
      $UndertakingId = $this->maintenance_model->selectSpecific('r_loanundertaking', 'StatusId', 1);
      $insertData = array(
        'LoanId'                    => $_POST['LoanTypeId'],
        'BorrowerId'                => $_POST['borrowerId'],

        'Source'                    => $_POST['SourceType'],
        'SourceName'                => $_POST['AgentName'],

        'TermType'                  => $_POST['TermType'],
        'TermNo'                    => $_POST['TermNumber'],
        'RepaymentId'               => $_POST['RepaymentCycle'],
        'RepaymentNo'               => $_POST['RepaymentsNumber'],

        'BorrowerMonthlyIncome'     => $_POST['BorrowerMonthlySalary'],
        'SpouseMonthlyIncome'       => $_POST['SpouseMonthlySalary'],

        'IsPenalized'               => isset($_POST['IsPenalized']),
        'PenaltyType'               => $_POST['PenaltyType'],
        'PenaltyAmount'             => $_POST['PenaltyAmount'],
        'GracePeriod'               => $_POST['GracePeriod'],


        'PurposeId'                 => $_POST['PurposeId'],
        'LoanReleaseDate'           => $_POST['loanReleaseDate'],
        'DisbursementId'            => $_POST['DisbursedBy'],
        'PrincipalAmount'           => $_POST['PrincipalAmount'],
        'Notes'                     => $_POST['Notes'],
        'UndertakingId'             => $UndertakingId['UndertakingId'],
        'CreatedBy'                 => $EmployeeNumber,
        'StatusId'                  => $_POST['LoanStatusId'],
        'ApprovalType'              => $_POST['ApprovalType'],
      );
      $auditTable = 't_application';
      $this->maintenance_model->insertFunction($insertData, $auditTable);
    // get generated application id
      $getData = array(
        'table'                 => 't_application'
        , 'column'              => 'ApplicationId'
        , 'CreatedBy'           => $EmployeeNumber
      );
      $generatedId = $this->maintenance_model->getGeneratedId2($getData);
    // set application transaction number and check loan status if approved
      $borrowerDetail = $this->maintenance_model->selectSpecific('R_Borrowers', 'BorrowerId', $_POST['borrowerId']);
      $branchCode = $this->maintenance_model->selectSpecific('R_Branch', 'BranchId', $borrowerDetail['BranchId']);
      $TransactionNumber = $branchCode['Code'] .'-'.date("Ymd"). $_POST['borrowerId'] . sprintf('%05d', $generatedId['ApplicationId']);
      // if loan is approved
        if($_POST['LoanStatusId'] == 1) // approved
        {
          $set = array( 
            'DateApproved' => $DateNow
          );
          $condition = array( 
            'ApplicationId' => $generatedId['ApplicationId']
          );
          $table = 't_application';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
      // set transaction number
        $set = array( 
          'TransactionNumber' => $TransactionNumber
        );
        $condition = array( 
          'ApplicationId' => $generatedId['ApplicationId']
        );
        $table = 't_application';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
    // interest details
      $insertData = array(
        'ApplicationId'             => $generatedId['ApplicationId'],
        'InterestType'              => $_POST['interestType'],
        'Amount'                    => $_POST['interestAmount'],
        'Frequency'                 => $_POST['interestFrequency'],
        'CreatedBy'                 => $EmployeeNumber
      );
      $auditTable = 'application_has_interests';
      $this->maintenance_model->insertFunction($insertData, $auditTable);
    // sources of income
      if(isset($_POST['countMonthlyIncome']))
      {
        for($count = 0; $count < count($_POST['countMonthlyIncome']); $count++)
        {
          $insertData = array(
            'ApplicationId'         => $generatedId['ApplicationId'],
            'Source'                => $_POST['MISourceIncome'][$count],
            'Amount'                => $_POST['MIAmount'][$count],
            'Details'               => $_POST['MIDetails'][$count],
            'CreatedBy'             => $EmployeeNumber
          );
          $auditTable = 'application_has_monthlyincome';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        }
      }
    // monthly expense
      if(isset($_POST['countRow']))
      {
        for($count = 0; $count < count($_POST['countRow']); $count++)
        {
          $insertData = array(
            'ApplicationId'         => $generatedId['ApplicationId'],
            'Source'                => $_POST['SourceExpenses'][$count],
            'Amount'                => $_POST['Amount'][$count],
            'Details'               => $_POST['Details'][$count],
            'CreatedBy'             => $EmployeeNumber
          );
          $auditTable = 'application_has_monthlyexpense';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        }
      }
    // monthly obligation
      if(isset($_POST['countObligationRow']))
      {
        for($count = 0; $count < count($_POST['countObligationRow']); $count++)
        {
          $insertData = array(
            'ApplicationId'         => $generatedId['ApplicationId'],
            'Source'                => $_POST['SourceObligations'][$count],
            'Amount'                => $_POST['ObligationAmount'][$count],
            'Details'               => $_POST['ObligationDetails'][$count],
            'CreatedBy'             => $EmployeeNumber
          );
          $auditTable = 'application_has_monthlyobligation';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        }
      }
    // additional charges
      if(isset($_POST['ChargeNo']))
      {
        for($count = 0; $count < count($_POST['ChargeNo']); $count++)
        {
          if($_POST['IsSelected'][$count] == 1)
          {
            $insertData = array(
              'ApplicationId'         => $generatedId['ApplicationId'],
              'ChargeId'              => $_POST['ChargeId'][$count],
              'CreatedBy'             => $EmployeeNumber
            );
            $auditTable = 'application_has_charges';
            $this->maintenance_model->insertFunction($insertData, $auditTable);
          }
        }
      }
    // requirements needed
      if(isset($_POST['RequirementNo']))
      {
        for($count = 0; $count < count($_POST['RequirementNo']); $count++)
        {
          if($_POST['isRequirementSelected'][$count] == 1)
          {
            $insertData = array(
              'ApplicationId'         => $generatedId['ApplicationId'],
              'RequirementId'         => $_POST['RequirementId'][$count],
              'CreatedBy'             => $EmployeeNumber
            );
            $auditTable = 'application_has_requirements';
            $this->maintenance_model->insertFunction($insertData, $auditTable);
          }
        }
      }
    // loan status
      $statusDetails = $this->maintenance_model->selectSpecific('application_has_status', 'LoanStatusId', $_POST['LoanStatusId']);
      if($statusDetails['IsApprovable'] == 1) // is approvable
      {
        if(isset($_POST['Approvers']))
        {
          foreach ($_POST['Approvers'] as $value) 
          {
            $insertData = array(
              'ApplicationId'         => $generatedId['ApplicationId'],
              'ApproverNumber'        => $value,
              'StatusId'              => 5,
              'CreatedBy'             => $EmployeeNumber
            );
            $auditTable = 'application_has_approver';
            $this->maintenance_model->insertFunction($insertData, $auditTable);
          }
        }
      }
    // notifications
    // history
      $insertData = array(
        'ApplicationId'             => $generatedId['ApplicationId'],
        'Description'               => 'Created loan application.',
        'CreatedBy'                 => $EmployeeNumber
      );
      $auditTable = 'application_has_notifications';
      $this->maintenance_model->insertFunction($insertData, $auditTable);
      $this->session->set_flashdata('alertTitle','Success!'); 
      $this->session->set_flashdata('alertText','Successfully submitted loan application!'); 
      $this->session->set_flashdata('alertType','success'); 
    
    redirect('home/loandetail/' . $generatedId['ApplicationId']);
  }

  function loanapproval()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    if($_POST['ApprovalType'] == 1) // approved
    {
      $set = array( 
        'DateUpdated' => $DateNow, 
        'StatusId'    => 3
      );
      $condition = array( 
        'ApplicationId'   => $this->uri->segment(3),
        'ApproverNumber'  => $EmployeeNumber
      );
      $table = 'application_has_approver';
      $this->maintenance_model->updateFunction1($set, $condition, $table);

      $insertData = array(
        'ApplicationId'             => $this->uri->segment(3),
        'Description'               => 'Approved.',
        'CreatedBy'                 => $EmployeeNumber
      );
      $auditTable = 'application_has_notifications';
      $this->maintenance_model->insertFunction($insertData, $auditTable);
    }
    else
    {
      $set = array( 
        'DateUpdated' => $DateNow, 
        'StatusId'    => 4
      );
      $condition = array( 
        'ApplicationId'   => $this->uri->segment(3),
        'ApproverNumber'  => $EmployeeNumber
      );
      $table = 'application_has_approver';
      $this->maintenance_model->updateFunction1($set, $condition, $table);

      $insertData = array(
        'ApplicationId'             => $this->uri->segment(3),
        'Description'               => 'Disapproved.',
        'CreatedBy'                 => $EmployeeNumber
      );
      $auditTable = 'application_has_notifications';
      $this->maintenance_model->insertFunction($insertData, $auditTable);
    }
    
    $this->session->set_flashdata('alertTitle','Success!'); 
    $this->session->set_flashdata('alertText','Successfully updated loan!'); 
    $this->session->set_flashdata('alertType','success'); 
    redirect('home/loanDetailApproval/' . $this->uri->segment(3));
  }

  function AddComment()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    if ($_POST['FormType'] == 1) // add Comment
    {
      // insert Comment
        $insertComment = array(
           'ApplicationId'              => $this->uri->segment(3)
          , 'Comment'                   => htmlentities($_POST['Comment'], ENT_QUOTES)
          , 'CreatedBy'                 => $EmployeeNumber
          , 'UpdatedBy'                 => $EmployeeNumber
        );
        $insertCommentTable = 'Application_has_Comments';
        $this->maintenance_model->insertFunction($insertComment, $insertCommentTable);

      $path = './uploads/';

      $config = array(
        'upload_path' => $path,
        'allowed_types' => 'jpg|jpeg|png|pdf|xlsx|docx|xls',
        'overwrite' => 1
      );

      $this->load->library('upload', $config);

      $files = $_FILES['Attachment'];
      $fileName = "";
      $images = array();
      foreach ($files['name'] as $key => $image) 
      {
        $file_ext = pathinfo($image, PATHINFO_EXTENSION);
        $_FILES['Attachment[]']['name']= $files['name'][$key];
        $_FILES['Attachment[]']['type']= $files['type'][$key];
        $_FILES['Attachment[]']['tmp_name']= $files['tmp_name'][$key];
        $_FILES['Attachment[]']['error']= $files['error'][$key];
        $_FILES['Attachment[]']['size']= $files['size'][$key];
        $uniq_id = uniqid();
        $fileName = $uniq_id.'.'.$file_ext;
        $fileName = str_replace(" ","_",$fileName);

        $config['file_name'] = $fileName;
        $Title = $_FILES['Attachment[]']['name'];

        $this->upload->initialize($config);
        if ($this->upload->do_upload('Attachment[]')) 
        {
          $this->upload->data();
          // get generated application id
            $getData = array(
              'table'                 => 'Application_has_Comments'
              , 'column'              => 'CommentId'
              , 'CreatedBy'           => $EmployeeNumber
            );
            $generatedId = $this->maintenance_model->getGeneratedId2($getData);
            $insertComment = array(
              'CommentId'                 => $generatedId['CommentId']
              , 'FileName'                  => $fileName
              , 'Title'                     => $Title
              , 'CreatedBy'                 => $EmployeeNumber
              , 'UpdatedBy'                 => $EmployeeNumber
            );
            $insertCommentTable = 'comments_has_attachments';
            $this->maintenance_model->insertFunction($insertComment, $insertCommentTable);
        }
        else
        {
            $fileName = "";
        }
      }
      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Comment successfully Added!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/loandetail/'. $this->uri->segment(3));
    }
  }

  function AddObligation()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $ObligationDetail = $this->loanapplication_model->getObligationDetails($_POST['MonthlyObligationId']);
    $DateNow = date("Y-m-d H:i:s");
    if ($_POST['FormType'] == 1) // add Obligation
    {
      $data = array(
        'Source'                     => htmlentities($_POST['Obligation'], ENT_QUOTES)
        , 'Details'                  => htmlentities($_POST['Detail'], ENT_QUOTES)
        , 'Amount'                   => htmlentities($_POST['Amount'], ENT_QUOTES)
        , 'ApplicationId'            => $this->uri->segment(3)
      );
      $query = $this->loanapplication_model->countObligation($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Obligation Details
          $insertObligation = array(
             'ApplicationId'              => $this->uri->segment(3)
            , 'Source'                    => htmlentities($_POST['Obligation'], ENT_QUOTES)
            , 'Details'                   => htmlentities($_POST['Detail'], ENT_QUOTES)
            , 'Amount'                    => htmlentities($_POST['Amount'], ENT_QUOTES)
            , 'StatusId'                  => 2
            , 'CreatedBy'                 => $EmployeeNumber
            , 'UpdatedBy'                 => $EmployeeNumber
          );
          $insertObligationTable = 'application_has_monthlyobligation';
          $this->maintenance_model->insertFunction($insertObligation, $insertObligationTable);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Obligation successfully Added!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/loandetail/'. $this->uri->segment(3));
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Obligation already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/loandetail/'. $this->uri->segment(3));
      }
    }
    else if($_POST['FormType'] == 2) // edit Obligation Details 
    {
      $data = array(
        'Source'                    => htmlentities($_POST['Obligation'], ENT_QUOTES)
        , 'Details'                 => htmlentities($_POST['Detail'], ENT_QUOTES)
        , 'Amount'                  => htmlentities($_POST['Amount'], ENT_QUOTES)
        , 'ApplicationId'           => $this->uri->segment(3)
      );
      $query = $this->loanapplication_model->countObligation($data);
      if($query == 0)
      {
        if($ObligationDetail['Source'] != htmlentities($_POST['Obligation'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$ObligationDetail['Source'].' to '.htmlentities($_POST['Obligation'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
              'Source' => htmlentities($_POST['Obligation'], ENT_QUOTES)
            );
            $condition = array( 
              'MonthlyObligationId' => $_POST['MonthlyObligationId']
            );
            $table = 'application_has_monthlyobligation';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($ObligationDetail['Details'] != htmlentities($_POST['Detail'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$ObligationDetail['Details'].' to '.htmlentities($_POST['Detail'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array(
              'Details' => htmlentities($_POST['Detail'], ENT_QUOTES)
            );
            $condition = array(
              'MonthlyObligationId' => $_POST['MonthlyObligationId']
            );
            $table = 'application_has_monthlyobligation';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($ObligationDetail['Amount'] != htmlentities($_POST['Amount'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$ObligationDetail['Amount'].' to '.htmlentities($_POST['Amount'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
              'Amount' => htmlentities($_POST['Amount'], ENT_QUOTES)
            );
            $condition = array( 
              'MonthlyObligationId' => $_POST['MonthlyObligationId']
            );
            $table = 'application_has_monthlyobligation';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        // notif
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Obligation details successfully updated!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/loandetail/'. $this->uri->segment(3));
      }
    }
    else // if existing
    {
      // notif
      $this->session->set_flashdata('alertTitle','Warning!'); 
      $this->session->set_flashdata('alertText','Obligation details already existing!'); 
      $this->session->set_flashdata('alertType','warning'); 
      redirect('home/loandetail/'. $this->uri->segment(3));
    }
  }

  function AddExpense()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $ExpenseDetail = $this->loanapplication_model->getExpenseDetails($_POST['ExpenseId']);
    $DateNow = date("Y-m-d H:i:s");
    if ($_POST['FormTypeExpense'] == 1) // add Expense
    {
      $data = array(
        'Source'                     => htmlentities($_POST['Expense'], ENT_QUOTES)
        , 'Details'                  => htmlentities($_POST['Detail'], ENT_QUOTES)
        , 'Amount'                   => htmlentities($_POST['Amount'], ENT_QUOTES)
        , 'ApplicationId'            => $this->uri->segment(3)
      );
      $query = $this->loanapplication_model->countExpense($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Expense Details
          $insertExpense = array(
             'ApplicationId'              => $this->uri->segment(3)
            , 'Source'                    => htmlentities($_POST['Expense'], ENT_QUOTES)
            , 'Details'                   => htmlentities($_POST['Detail'], ENT_QUOTES)
            , 'Amount'                    => htmlentities($_POST['Amount'], ENT_QUOTES)
            , 'StatusId'                  => 2
            , 'CreatedBy'                 => $EmployeeNumber
            , 'UpdatedBy'                 => $EmployeeNumber
          );
          $insertExpenseTable = 'application_has_Expense';
          $this->maintenance_model->insertFunction($insertExpense, $insertExpenseTable);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Expense successfully Added!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/loandetail/'. $this->uri->segment(3));
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Expense already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/loandetail/'. $this->uri->segment(3));
      }
    }
    else if($_POST['FormTypeExpense'] == 2) // edit Expense Details 
    {
      $data = array(
        'Source'                    => htmlentities($_POST['Expense'], ENT_QUOTES)
        , 'Details'                 => htmlentities($_POST['Detail'], ENT_QUOTES)
        , 'Amount'                  => htmlentities($_POST['Amount'], ENT_QUOTES)
        , 'ApplicationId'           => $this->uri->segment(3)
      );
      $query = $this->loanapplication_model->countExpense($data);
      if($query == 0)
      {
        if($ExpenseDetail['Source'] != htmlentities($_POST['Expense'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$ExpenseDetail['Source'].' to '.htmlentities($_POST['Expense'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
              'Source' => htmlentities($_POST['Expense'], ENT_QUOTES)
            );
            $condition = array( 
              'ExpenseId' => $_POST['ExpenseId']
            );
            $table = 'application_has_Expense';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($ExpenseDetail['Details'] != htmlentities($_POST['Detail'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$ExpenseDetail['Details'].' to '.htmlentities($_POST['Detail'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array(
              'Details' => htmlentities($_POST['Detail'], ENT_QUOTES)
            );
            $condition = array(
              'ExpenseId' => $_POST['ExpenseId']
            );
            $table = 'application_has_Expense';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($ExpenseDetail['Amount'] != htmlentities($_POST['Amount'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$ExpenseDetail['Amount'].' to '.htmlentities($_POST['Amount'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
              'Amount' => htmlentities($_POST['Amount'], ENT_QUOTES)
            );
            $condition = array( 
              'ExpenseId' => $_POST['ExpenseId']
            );
            $table = 'application_has_Expense';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        // notif
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Expense details successfully updated!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/loandetail/'. $this->uri->segment(3));
      }
    }
  }

  function Addincome()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $IncomeDetail = $this->loanapplication_model->getIncomeDetails($_POST['IncomeId']);
    $DateNow = date("Y-m-d H:i:s");
    if ($_POST['FormType'] == 1) // add Income
    {
      $data = array(
        'Source'                 => htmlentities($_POST['Source'], ENT_QUOTES)
        , 'Details'              => htmlentities($_POST['Detail'], ENT_QUOTES)
        , 'Amount'               => htmlentities($_POST['Amount'], ENT_QUOTES)
      );
      $query = $this->loanapplication_model->countMonthlyIncome($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Income details
          $insertIncome = array(
            'ApplicationId'               => $this->uri->segment(3)
            , 'Source'                    => htmlentities($_POST['Source'], ENT_QUOTES)
            , 'Details'                   => htmlentities($_POST['Detail'], ENT_QUOTES)
            , 'Amount'                    => htmlentities($_POST['Amount'], ENT_QUOTES)
            , 'StatusId'                  => 2
            , 'CreatedBy'                 => $EmployeeNumber
            , 'UpdatedBy'                 => $EmployeeNumber
          );
          $insertIncomeTable = 'application_has_monthlyIncome';
          $this->maintenance_model->insertFunction($insertIncome, $insertIncomeTable);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Source of Income details successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/loandetail/'. $this->uri->segment(3));
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Source of Income details already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/loandetail'. $this->uri->segment(3));
      }
    }
    else if($_POST['FormType'] == 2) // edit Income Details 
    {
      $data = array(
        'Source'                    => htmlentities($_POST['Source'], ENT_QUOTES)
        , 'Details'                 => htmlentities($_POST['Detail'], ENT_QUOTES)
        , 'Amount'                  => htmlentities($_POST['Amount'], ENT_QUOTES)
        , 'ApplicationId'           => $this->uri->segment(3)
      );
      $query = $this->loanapplication_model->countExpense($data);
      if($query == 0)
      {
        if($IncomeDetail['Source'] != htmlentities($_POST['Source'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$IncomeDetail['Source'].' to '.htmlentities($_POST['Source'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
              'Source' => htmlentities($_POST['Source'], ENT_QUOTES)
            );
            $condition = array( 
              'IncomeId' => $_POST['IncomeId']
            );
            $table = 'application_has_MonthlyIncome';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($IncomeDetail['Details'] != htmlentities($_POST['Detail'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$IncomeDetail['Details'].' to '.htmlentities($_POST['Detail'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array(
              'Details' => htmlentities($_POST['Detail'], ENT_QUOTES)
            );
            $condition = array(
              'IncomeId' => $_POST['IncomeId']
            );
            $table = 'application_has_monthlyincome';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($IncomeDetail['Amount'] != htmlentities($_POST['Amount'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$IncomeDetail['Amount'].' to '.htmlentities($_POST['Amount'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
              'Amount' => htmlentities($_POST['Amount'], ENT_QUOTES)
            );
            $condition = array( 
              'IncomeId' => $_POST['IncomeId']
            );
            $table = 'application_has_MonthlyIncome';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        // notif
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Source of Income details successfully updated!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/loandetail/'. $this->uri->segment(3));
      }
    }
  }

  function AddRequirement()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $RequirementDetail = $this->loanapplication_model->getRequirementDetails($_POST['RequirementId']);
    $DateNow = date("Y-m-d H:i:s");
    if ($_POST['FormType'] == 1) // add Requirement
    {
      $data = array(
        'RequirementId'                 => htmlentities($_POST['Requirements'], ENT_QUOTES)
      );
      $query = $this->loanapplication_model->countRequirement($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Requirement details
          $insertRequirement = array(
            'ApplicationId'               => $this->uri->segment(3)
            , 'RequirementId'             => htmlentities($_POST['Requirements'], ENT_QUOTES)
            , 'StatusId'                  => 5
            , 'CreatedBy'                 => $EmployeeNumber
            , 'UpdatedBy'                 => $EmployeeNumber
          );
          $insertRequirementTable = 'application_has_requirements';
          $this->maintenance_model->insertFunction($insertRequirement, $insertRequirementTable);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Requirement successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/loandetail/'. $this->uri->segment(3));
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Requirement already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/loandetail'. $this->uri->segment(3));
      }
    }
    else if($_POST['FormType'] == 2) // edit Income Details 
    {
      $data = array(
        'Source'                    => htmlentities($_POST['Source'], ENT_QUOTES)
        , 'Details'                 => htmlentities($_POST['Detail'], ENT_QUOTES)
        , 'Amount'                  => htmlentities($_POST['Amount'], ENT_QUOTES)
        , 'ApplicationId'           => $this->uri->segment(3)
      );
      $query = $this->loanapplication_model->countExpense($data);
      if($query == 0)
      {
        if($IncomeDetail['Source'] != htmlentities($_POST['Source'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$IncomeDetail['Source'].' to '.htmlentities($_POST['Source'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
              'Source' => htmlentities($_POST['Source'], ENT_QUOTES)
            );
            $condition = array( 
              'IncomeId' => $_POST['IncomeId']
            );
            $table = 'application_has_MonthlyIncome';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($IncomeDetail['Details'] != htmlentities($_POST['Detail'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$IncomeDetail['Details'].' to '.htmlentities($_POST['Detail'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array(
              'Details' => htmlentities($_POST['Detail'], ENT_QUOTES)
            );
            $condition = array(
              'IncomeId' => $_POST['IncomeId']
            );
            $table = 'application_has_monthlyincome';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        if($IncomeDetail['Amount'] != htmlentities($_POST['Amount'], ENT_QUOTES))
        {
          // add into audit table
            $auditDetail = 'Updated details of  '.$IncomeDetail['Amount'].' to '.htmlentities($_POST['Amount'], ENT_QUOTES);
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
          // update function
            $set = array( 
              'Amount' => htmlentities($_POST['Amount'], ENT_QUOTES)
            );
            $condition = array( 
              'IncomeId' => $_POST['IncomeId']
            );
            $table = 'application_has_MonthlyIncome';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        // notif
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Source of Income details successfully updated!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/loandetail/'. $this->uri->segment(3));
      }
    }
  }

  function getObligationDetails()
  {
    $output = $this->loanapplication_model->getObligationDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getExpenseDetails()
  {
    $output = $this->loanapplication_model->getExpenseDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getIncomeDetails()
  {
    $output = $this->loanapplication_model->getIncomeDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getTenure()
  {
    $output = $this->loanapplication_model->getTenure($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function updateStatus()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $input = array( 
      'Id' => htmlentities($this->input->post('Id'), ENT_QUOTES)
      , 'updateType' => htmlentities($this->input->post('updateType'), ENT_QUOTES)
      , 'Type' => htmlentities($this->input->post('Type'), ENT_QUOTES)
    );

    $query = $this->loanapplication_model->updateStatus($input);
  }

  
}
