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
        'StatusId'                  => 2,
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
            'StatusId'              => 2,
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
            'StatusId'              => 2,
            'CreatedBy'             => $EmployeeNumber
          );
          $auditTable = 'application_has_expense';
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
            'StatusId'              => 2,
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
              'StatusId'              => 2,
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
              'StatusId'              => 5,
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
        'StatusId'    => 1
      );
      $condition = array( 
        'ApplicationId'   => $this->uri->segment(3),
        'ApproverNumber'  => $EmployeeNumber
      );
      $table = 'application_has_approver';
      $this->maintenance_model->updateFunction1($set, $condition, $table);

      $totalApprovers = $this->loanapplication_model->getTotalApprovers($this->uri->segment(3));
      if($totalApprovers['PendingApprovers'] == $totalApprovers['ProcessedApprovers'])
      {
        $set = array( 
          'DateUpdated'   => $DateNow, 
          'DateApproved'  => $DateNow, 
          'StatusId'      => 1
        );

        $condition = array( 
          'ApplicationId'   => $this->uri->segment(3)
        );

        $table = 't_application';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      }

      $insertData = array(
        'ApplicationId'             => $this->uri->segment(3),
        'Description'               => 'Approved.',
        'Remarks'                   => $_POST['Description'],
        'CreatedBy'                 => $EmployeeNumber
      );
      $auditTable = 'application_has_notifications';
      $this->maintenance_model->insertFunction($insertData, $auditTable);
    }
    else if($_POST['ApprovalType'] == 2) // disapprove
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

      $setApplication = array( 
        'DateUpdated'   => $DateNow,
        'DateApproved'  => $DateNow,
        'StatusId'      => 2
      );

      $conditionApplication = array( 
        'ApplicationId'   => $this->uri->segment(3)
      );

      $tableApplication = 't_application';
      $this->maintenance_model->updateFunction1($setApplication, $conditionApplication, $tableApplication);

      $insertData = array(
        'ApplicationId'             => $this->uri->segment(3),
        'Description'               => 'Disapproved.',
        'Remarks'                   => $_POST['Description'],
        'CreatedBy'                 => $EmployeeNumber
      );
      $auditTable = 'application_has_notifications';
      $this->maintenance_model->insertFunction($insertData, $auditTable);
    }
    else if($_POST['ApprovalType'] == 3) // deactivated charge
    {
      // update status
        $set = array( 
          'DateUpdated' => $DateNow, 
          'StatusId'    => 6,
        );
        $condition = array( 
          'ApplicationChargeId'   => $_POST['ChargeId'],
        );
        $table = 'application_has_charges';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // loan audit
        $insertData = array(
          'ApplicationId'             => $this->uri->segment(3),
          'Description'               => 'Deactivated charge #CHG-'. sprintf('%05d', $_POST['ChargeId']).'.',
          'Remarks'                   => $_POST['Description'],
          'CreatedBy'                 => $EmployeeNumber
        );
        $auditTable = 'application_has_notifications';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
    }
    else if($_POST['ApprovalType'] == 4) // deactivated payment
    {
      // update status
        $set = array( 
          'DateUpdated' => $DateNow, 
          'StatusId'    => 2,
        );
        $condition = array( 
          'PaymentMadeId'   => $_POST['ChargeId'],
        );
        $table = 't_paymentsmade';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // loan audit
        $insertData = array(
          'ApplicationId'             => $this->uri->segment(3),
          'Description'               => 'Deactivated payment #PYM-'. sprintf('%05d', $_POST['ChargeId']).'.',
          'Remarks'                   => $_POST['Description'],
          'CreatedBy'                 => $EmployeeNumber
        );
        $auditTable = 'application_has_notifications';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
    }
    
    $this->session->set_flashdata('alertTitle','Success!'); 
    $this->session->set_flashdata('alertText','Successfully updated loan!'); 
    $this->session->set_flashdata('alertType','success'); 
    redirect('home/loandetail/' . $this->uri->segment(3));
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
        // insert Application_has_notification
          $insertNotification = array(
            'Description'                   => 'Added a comment to the Source of Other Income tab '
            , 'ApplicationId'               => $this->uri->segment(3)
            , 'CreatedBy'                   => $EmployeeNumber
          );
          $insertNotificationTable = 'Application_has_Notifications';
          $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);

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
    $ApplicationDetail = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
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
        // get generated application id
          $getData = array(
            'table'                 => 'application_has_monthlyobligation'
            , 'column'              => 'MonthlyObligationId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
          $ObligationDetail = $this->maintenance_model->selectSpecific('application_has_monthlyobligation', 'MonthlyObligationId', $generatedId['MonthlyObligationId']);
        // insert Application_has_notification
          $ObligationName = htmlentities($_POST['Obligation'], ENT_QUOTES);
          $insertNotification = array(
            'Description'                   => 'Added '.$ObligationName.' to the Obligations tab '
            , 'ApplicationId'               => $this->uri->segment(3)
            , 'CreatedBy'                   => $EmployeeNumber
          );
          $insertNotificationTable = 'Application_has_Notifications';
          $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
        // Insert Employee_has_Notifications
          $auditDetail = ' Added ' .$ObligationName. ' to Reference #' .$ApplicationDetail['TransactionNumber'];
          $insertData = array(
            'Description' => $auditDetail
            , 'CreatedBy' => $EmployeeNumber
          );
          $auditTable = 'Employee_has_Notifications';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        // Insert Main Logs
          $auditDetail = ' Added ' .$ObligationDetail['Source']. ' to Reference #' .$ApplicationDetail['TransactionNumber'];
          $insertData = array(
            'Description' => $auditDetail
            , 'CreatedBy' => $EmployeeNumber
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
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
            $auditDetail = 'Updated details of  '.$ObligationDetail['Source'];
            $insertAudit = array(
              'Description' => $auditDetail,
              'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertAudit, $auditTable);
            // add into Application_has_notifications
            $auditDetail = 'Updated details of '.$ObligationDetail['Source'].' to ' .htmlentities($_POST['Obligation'], ENT_QUOTES). 'at the Obligation tab ';
            $insertAudit = array(
              'ApplicationId'  => $this->uri->segment(3)
              , 'Description' => $auditDetail
              , 'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'Application_has_Notifications';
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
            // add into Application_has_notifications
            $auditDetail = 'Updated details of '.$ObligationDetail['Source'].' at the Obligation tab ';
            $insertAudit = array(
              'ApplicationId'  => $this->uri->segment(3)
              , 'Description' => $auditDetail
              , 'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'Application_has_Notifications';
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
            // add into Application_has_notifications
            $auditDetail = 'Updated details of '.$ObligationDetail['Source'].' at the Obligation tab ';
            $insertAudit = array(
              'ApplicationId'  => $this->uri->segment(3)
              , 'Description' => $auditDetail
              , 'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'Application_has_Notifications';
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
    $ApplicationDetail = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
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
        // get generated application id
          $getData = array(
            'table'                 => 'application_has_Expense'
            , 'column'              => 'ExpenseId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
          $ExpenseDetail = $this->maintenance_model->selectSpecific('application_has_Expense', 'ExpenseId', $generatedId['ExpenseId']);
        // insert Application_has_notification
          $ExpenseName = htmlentities($_POST['Expense'], ENT_QUOTES);
          $insertNotification = array(
            'Description'                   => 'Added '.$ExpenseName.' to the Expense tab '
            , 'ApplicationId'               => $this->uri->segment(3)
            , 'CreatedBy'                   => $EmployeeNumber
          );
          $insertNotificationTable = 'Application_has_Notifications';
          $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
        // Insert Employee_has_Notifications
          $auditDetail = ' Added ' .$ExpenseName. ' to Reference #' .$ApplicationDetail['TransactionNumber'];
          $insertData = array(
            'Description' => $auditDetail
            , 'CreatedBy' => $EmployeeNumber
          );
          $auditTable = 'Employee_has_Notifications';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        // Insert Main Logs
          $auditDetail = ' Added ' .$ExpenseDetail['Source']. ' to Reference #' .$ApplicationDetail['TransactionNumber'];
          $insertData = array(
            'Description' => $auditDetail
            , 'CreatedBy' => $EmployeeNumber
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
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
            // add into Application_has_notifications
            $auditDetail = 'Updated details of '.$ExpenseDetail['Source'].' to ' .htmlentities($_POST['Expense'], ENT_QUOTES). 'at the Expense tab ';
            $insertAudit = array(
              'ApplicationId'  => $this->uri->segment(3)
              , 'Description' => $auditDetail
              , 'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'Application_has_Notifications';
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
            // add into Application_has_notifications
            $auditDetail = 'Updated details of '.$ExpenseDetail['Source'].' at the Expense tab ';
            $insertAudit = array(
              'ApplicationId'  => $this->uri->segment(3)
              , 'Description' => $auditDetail
              , 'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'Application_has_Notifications';
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
            // add into Application_has_notifications
            $auditDetail = 'Updated details of '.$ExpenseDetail['Source'].' at the Expense tab ';
            $insertAudit = array(
              'ApplicationId'  => $this->uri->segment(3)
              , 'Description' => $auditDetail
              , 'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'Application_has_Notifications';
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
    $ApplicationDetail = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
    $IncomeDetail = $this->loanapplication_model->getIncomeDetails($_POST['IncomeId']);
    $DateNow = date("Y-m-d H:i:s");
    if ($_POST['FormType'] == 1) // add Income
    {
      $data = array(
        'Source'                 => htmlentities($_POST['Source'], ENT_QUOTES)
        , 'Details'              => htmlentities($_POST['Detail'], ENT_QUOTES)
        , 'ApplicationId'        => $this->uri->segment(3)
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
        // get generated application id
          $getData = array(
            'table'                 => 'application_has_monthlyIncome'
            , 'column'              => 'IncomeId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
          $ObligationDetail = $this->maintenance_model->selectSpecific('application_has_monthlyIncome', 'IncomeId', $generatedId['IncomeId']);
        // insert Application_has_notification
          $IncomeName = htmlentities($_POST['Source'], ENT_QUOTES);
          $insertNotification = array(
            'Description'                   => 'Added '.$IncomeName.' to the Source of Other Income tab '
            , 'ApplicationId'               => $this->uri->segment(3)
            , 'CreatedBy'                   => $EmployeeNumber
          );
          $insertNotificationTable = 'Application_has_Notifications';
          $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
        // Insert Employee_has_Notifications
          $auditDetail = ' Added ' .$IncomeName. ' to Reference #' .$ApplicationDetail['TransactionNumber'];
          $insertData = array(
            'Description' => $auditDetail
            , 'CreatedBy' => $EmployeeNumber
          );
          $auditTable = 'Employee_has_Notifications';
          $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
        // Insert Main Logs
          $auditDetail = ' Added ' .$IncomeName. ' to Reference #' .$ApplicationDetail['TransactionNumber'];
          $insertData = array(
            'Description' => $auditDetail
            , 'CreatedBy' => $EmployeeNumber
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
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
            // add into Application_has_notifications
            $auditDetail = 'Updated details of '.$IncomeDetail['Source'].' to ' .htmlentities($_POST['Source'], ENT_QUOTES). 'at the Other Source of Income tab ';
            $insertAudit = array(
              'ApplicationId'  => $this->uri->segment(3)
              , 'Description' => $auditDetail
              , 'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'Application_has_Notifications';
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
            // add into Application_has_notifications
            $auditDetail = 'Updated details of '.$IncomeDetail['Source'].' at the Other Source of Income tab ';
            $insertAudit = array(
              'ApplicationId'  => $this->uri->segment(3)
              , 'Description' => $auditDetail
              , 'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'Application_has_Notifications';
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
            // add into Application_has_notifications
            $auditDetail = 'Updated details of '.$IncomeDetail['Source'].' at the Other Source of Income tab ';
            $insertAudit = array(
              'ApplicationId'  => $this->uri->segment(3)
              , 'Description' => $auditDetail
              , 'CreatedBy' => $EmployeeNumber
            );
            $auditTable = 'Application_has_Notifications';
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
        'RequirementId'                 => htmlentities($_POST['RequirementId'], ENT_QUOTES)
        , 'ApplicationId'               => $this->uri->segment(3)
      );
      $query = $this->loanapplication_model->countRequirement($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Application_has_notification
          $RequirementName = $this->maintenance_model->selectSpecific('r_requirements', 'RequirementId', $_POST['Requirements']);
          $insertNotification = array(
            'Description'                   => 'Added '.$RequirementName['Name'].' to the Requirements tab '
            , 'ApplicationId'               => $this->uri->segment(3)
            , 'CreatedBy'                   => $EmployeeNumber
          );
          $insertNotificationTable = 'Application_has_Notifications';
          $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
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
    else if($_POST['FormType'] == 2) // edit Requirement Details 
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

  function uploadRequirements()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    $path = './uploads/';
    $config = array(
      'upload_path' => $path,
      'allowed_types' => 'jpg|jpeg|png|pdf|xlsx|docx|xls',
      'overwrite' => 1
    );
    $this->load->library('upload', $config);
    $files = $_FILES['RequirementFiles'];
    $fileName = "";
    $images = array();
    foreach ($files['name'] as $key => $image) 
    {
      $file_ext = pathinfo($image, PATHINFO_EXTENSION);
      $_FILES['RequirementFiles[]']['name']= $files['name'][$key];
      $_FILES['RequirementFiles[]']['type']= $files['type'][$key];
      $_FILES['RequirementFiles[]']['tmp_name']= $files['tmp_name'][$key];
      $_FILES['RequirementFiles[]']['error']= $files['error'][$key];
      $_FILES['RequirementFiles[]']['size']= $files['size'][$key];
      $uniq_id = uniqid();
      $fileName = $uniq_id.'.'.$file_ext;
      $fileName = str_replace(" ","_",$fileName);

      $config['file_name'] = $fileName;
      $Title = $_FILES['RequirementFiles[]']['name'];

      $this->upload->initialize($config);
      if ($this->upload->do_upload('RequirementFiles[]')) 
      {
        $this->upload->data();
        // update all attachmments deactivated
          $set = array( 
            'StatusId' => 0
          );
          $condition = array( 
            'ApplicationRequirementId' => $_POST['ApplicationRequirementId']
          );
          $table = 'requirements_has_attachments';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert attachments
          $insertComment = array(
            'ApplicationRequirementId'    => $_POST['ApplicationRequirementId']
            , 'FileName'                  => $fileName
            , 'Title'                     => $Title
            , 'CreatedBy'                 => $EmployeeNumber
            , 'UpdatedBy'                 => $EmployeeNumber
          );
          $insertCommentTable = 'requirements_has_attachments';
          $this->maintenance_model->insertFunction($insertComment, $insertCommentTable);
        // update as submitted
          $set = array( 
            'StatusId' => 7
          );
          $condition = array( 
            'ApplicationRequirementId' => $_POST['ApplicationRequirementId']
          );
          $table = 'application_has_requirements';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      else
      {
          $fileName = "";
      }
    }
    // notification
      $this->session->set_flashdata('alertTitle','Success!'); 
      $this->session->set_flashdata('alertText','Requirement successfully uploaded!'); 
      $this->session->set_flashdata('alertType','success'); 
      redirect('home/loandetail/'. $this->uri->segment(3));
  }

  function addCollateral()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    if($_POST['FormType'] == 1)
    {
      // collateral details
        $string1 = strtotime($_POST['dateRegistered']);
        $varDateRegistered = date('Y-m-d', $string1);
        $string2 = strtotime($_POST['dateAcquired']);
        $varDateAcquired = date('Y-m-d', $string2);
        $insertData = array(
          'ProductName'          => $_POST['ProductName'],
          'Value'                => $_POST['CollateralValue'],
          'DateRegistered'       => $varDateRegistered,
          'CollateralTypeId'     => $_POST['CollateralTypeId'],
          'DateAcquired'         => $varDateAcquired,
          'RegistrationNo'       => $_POST['RegistrationNo'],
          'Mileage'              => $_POST['Mileage'],
          'EngineNo'             => $_POST['EngineNo'],
          'StatusId'             => $_POST['CollateralStatusId'],
          'CreatedBy'            => $EmployeeNumber
        );
        $auditTable = 'r_collaterals';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // get generated id
        $getData = array(
          'table'                 => 'r_collaterals'
          , 'column'              => 'CollateralId'
          , 'CreatedBy'           => $EmployeeNumber
        );
        $generatedId = $this->maintenance_model->getGeneratedId2($getData);
      // insert collateral into loan
        $insertData2 = array(
          'CollateralId'         => $generatedId['CollateralId'],
          'ApplicationId'        => $this->uri->segment(3),
          'CreatedBy'            => $EmployeeNumber
        );
        $auditTable2 = 'application_has_Collaterals';
        $this->maintenance_model->insertFunction($insertData2, $auditTable2);
      // upload documents
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
            $insertComment = array(
              'CollateralId'                => $generatedId['CollateralId']
              , 'FileName'                  => $fileName
              , 'Title'                     => $Title
              , 'CreatedBy'                 => $EmployeeNumber
            );
            $insertCommentTable = 'collaterals_has_files';
            $this->maintenance_model->insertFunction($insertComment, $insertCommentTable);
          }
          else
          {
              $fileName = "";
          }
        }
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Collateral successfully added!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/loandetail/'. $this->uri->segment(3));
    }
    else // edit
    {
      $detail = $this->maintenance_model->selectSpecific('r_collaterals', 'CollateralId', $_POST['CollateralId']);
      $string1 = strtotime($_POST['dateRegistered']);
      $varDateRegistered = date('Y-m-d', $string1);
      $string2 = strtotime($_POST['dateAcquired']);
      $varDateAcquired = date('Y-m-d', $string2);
      if($detail['ProductName'] != $_POST['ProductName'])
      {
        $set = array( 
          'ProductName' => $_POST['ProductName'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow
        );
        $condition = array( 
          'CollateralId' => $_POST['CollateralId']
        );
        $table = 'r_collaterals';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($detail['Value'] != $_POST['CollateralValue'])
      {
        $set = array( 
          'Value' => $_POST['CollateralValue'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow
        );
        $condition = array( 
          'CollateralId' => $_POST['CollateralId']
        );
        $table = 'r_collaterals';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($detail['DateRegistered'] != $varDateRegistered)
      {
        $set = array( 
          'DateRegistered' => $varDateRegistered,
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow
        );
        $condition = array( 
          'CollateralId' => $_POST['CollateralId']
        );
        $table = 'r_collaterals';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($detail['DateAcquired'] != $varDateAcquired)
      {
        $set = array( 
          'DateAcquired' => $varDateAcquired,
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow
        );
        $condition = array( 
          'CollateralId' => $_POST['CollateralId']
        );
        $table = 'r_collaterals';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($detail['StatusId'] != $_POST['CollateralStatusId'])
      {
        $set = array( 
          'StatusId' => $_POST['CollateralStatusId'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow
        );
        $condition = array( 
          'CollateralId' => $_POST['CollateralId']
        );
        $table = 'r_collaterals';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($detail['RegistrationNo'] != $_POST['RegistrationNo'])
      {
        $set = array( 
          'RegistrationNo' => $_POST['RegistrationNo'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow
        );
        $condition = array( 
          'CollateralId' => $_POST['CollateralId']
        );
        $table = 'r_collaterals';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($detail['Mileage'] != $_POST['Mileage'])
      {
        $set = array( 
          'Mileage' => $_POST['Mileage'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow
        );
        $condition = array( 
          'CollateralId' => $_POST['CollateralId']
        );
        $table = 'r_collaterals';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      if($detail['EngineNo'] != $_POST['EngineNo'])
      {
        $set = array( 
          'EngineNo' => $_POST['EngineNo'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow
        );
        $condition = array( 
          'CollateralId' => $_POST['CollateralId']
        );
        $table = 'r_collaterals';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      // application has notifications -- to do
      // employee has notifications -- to do
      // system has notifications -- to do
      // upload documents
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
            $insertComment = array(
              'CollateralId'                => $_POST['CollateralId']
              , 'FileName'                  => $fileName
              , 'Title'                     => $Title
              , 'CreatedBy'                 => $EmployeeNumber
            );
            $insertCommentTable = 'collaterals_has_files';
            $this->maintenance_model->insertFunction($insertComment, $insertCommentTable);
          }
          else
          {
              $fileName = "";
          }
        }
      // notif
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Collateral successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/loandetail/'. $this->uri->segment(3));
    }
  }

  function penaltySettings()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    if(isset($_POST['PenaltyType']))
    {
      $set = array( 
        'IsPenalized'     => 1,
        'PenaltyType'     => $_POST['PenaltyType'],
        'PenaltyAmount'   => $_POST['PenaltyAmount'],
        'GracePeriod'     => $_POST['GracePeriod']
      );
      $condition = array( 
        'ApplicationId' => $this->uri->segment(3)
      );
      $table = 't_application';
      $this->maintenance_model->updateFunction1($set, $condition, $table);
    }

    $this->session->set_flashdata('alertTitle','Success!'); 
    $this->session->set_flashdata('alertText','Loan application updated!'); 
    $this->session->set_flashdata('alertType','success'); 
    redirect('home/loandetail/'. $this->uri->segment(3));
  }

  function addCharges()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    $Id = $this->uri->segment(3);
    // insert into charges
      $insertData = array(
        'ApplicationId'             => $Id,
        'ChargeId'                  => $_POST['ChargeId'],
        'StatusId'                  => 2,
        'CreatedBy'                 => $EmployeeNumber
      );
      $table = 'application_has_charges';
      $this->maintenance_model->insertFunction($insertData, $table);
    // get generated id
      $getData = array(
        'table'                 => 'application_has_charges'
        , 'column'              => 'ApplicationChargeId'
        , 'CreatedBy'           => $EmployeeNumber
      );
      $generatedId = $this->maintenance_model->getGeneratedId2($getData);
    // add into loan audit
      $TransactionNumber = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $Id);
      $Description = 'Added charge #CHG-' .sprintf('%05d', $generatedId['ApplicationChargeId']). '.';
      $insertAudit = array( 
        'Description' => $Description,
        'CreatedBy'   => $EmployeeNumber
      );
      $auditTable = 'application_has_notifications';
      $this->maintenance_model->insertFunction($insertAudit, $auditTable);

    // insert into employee notification and r_logs
      $mainAuditDesc = 'Added charge #CHG-' .sprintf('%05d', $generatedId['ApplicationChargeId']). ' to #' . $TransactionNumber['TransactionNumber'];
      $insertMainAudit = array( 
        'Description' => $mainAuditDesc,
        'CreatedBy'   => $EmployeeNumber
      );
      $auditTable1 = 'Employee_has_Notifications';
      $auditTable2 = 'R_Logs';
      $this->maintenance_model->insertFunction($insertMainAudit, $auditTable1);
      $this->maintenance_model->insertFunction($insertMainAudit, $auditTable2);

      $this->session->set_flashdata('alertTitle','Success!'); 
      $this->session->set_flashdata('alertText','Successfully added charge to loan!'); 
      $this->session->set_flashdata('alertType','success'); 
    
    redirect('home/loandetail/' . $Id);
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

  function getCollateralDetails()
  {
    $output = $this->loanapplication_model->getCollateralDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getTenure()
  {
    $output = $this->loanapplication_model->getTenure($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getChargeList()
  {
    $output = $this->loanapplication_model->getChargeList($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getRequirementsList()
  {
    $output = $this->loanapplication_model->getRequirementsList($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getChargeDetails()
  {
    $output = $this->loanapplication_model->getChargeDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getRepaymentCount()
  {
    $output = $this->loanapplication_model->getRepaymentCount($this->input->post('Id'));
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

  function addRepayment()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    $string2 = strtotime($_POST['dateCollected']);
    $varDateCollected = date('Y-m-d', $string2);
    // insert into payments
      $insertData = array( 
        'BankId'            => $_POST['BankId'],
        'ApplicationId'     => $this->uri->segment(3),
        'Amount'            => $_POST['Amount'],
        'Description'       => $_POST['Remarks'],
        'DateCollected'     => $varDateCollected,
        'CreatedBy'         => $EmployeeNumber
      );
      $table = 't_paymentsmade';
      $this->maintenance_model->insertFunction($insertData, $table);
    // get generated application id
      $getData = array(
        'table'                 => 't_paymentsmade'
        , 'column'              => 'PaymentMadeId'
        , 'CreatedBy'           => $EmployeeNumber
      );
      $generatedId = $this->maintenance_model->getGeneratedId2($getData);
    // insert Application_has_notification
      $insertNotification = array(
        'Description'                   => 'Added payment.'
        , 'ApplicationId'               => $this->uri->segment(3)
        , 'CreatedBy'                   => $EmployeeNumber
      );
      $insertNotificationTable = 'Application_has_Notifications';
      $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
    // main audits
      $RefNo = $this->maintenance_model->selectSpecific('t_application', 'ApplicationId', $this->uri->segment(3));
      $auditDetail = 'Added payment #PYM-'.sprintf('%05d', $generatedId['PaymentMadeId']).' to ' . $RefNo['TransactionNumber'];
      $insertData2 = array(
        'Description' => $auditDetail
        , 'CreatedBy' => $EmployeeNumber
      );
      $auditTable1 = 'Employee_has_Notifications';
      $auditTable2 = 'R_Logs';
      $this->maintenance_model->insertFunction($insertData2, $auditTable1);
      $this->maintenance_model->insertFunction($insertData2, $auditTable2);
    // notification
      $this->session->set_flashdata('alertTitle','Success!'); 
      $this->session->set_flashdata('alertText','Successfully added payment!'); 
      $this->session->set_flashdata('alertType','success'); 
      redirect('home/loandetail/' . $this->uri->segment(3));
  }

  
}
