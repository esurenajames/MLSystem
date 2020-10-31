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


  function submitApplication()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    // loan product details
      $UndertakingId = $this->maintenance_model->selectSpecific('r_loanundertaking', 'StatusId', 1);

      $time = strtotime($_POST['loanReleaseDate']);
      $newformat = date('Y-m-d', $time);
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

        'RiskLevel'                 => $_POST['RiskLevel'],
        'RiskAssessment'            => $_POST['RiskAssessment'],

        'PurposeId'                 => $_POST['PurposeId'],
        'LoanReleaseDate'           => $newformat,
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
        for($chargeRow = 0; $chargeRow < count($_POST['ChargeNo']); $chargeRow++)
        {
          if($_POST['IsSelected'][$chargeRow] == 1)
          {
            $insertData = array(
              'ApplicationId'         => $generatedId['ApplicationId'],
              'ChargeId'              => $_POST['ChargeId'][$chargeRow],
              'Amount'                => $_POST['chargeTotal'][$chargeRow],
              'StatusId'              => 2,
              'CreatedBy'             => $EmployeeNumber
            );
            $auditTable = 'application_has_charges';
            $this->maintenance_model->insertFunction($insertData, $auditTable);

            $charge = $this->maintenance_model->selectSpecific('R_Charges', 'ChargeId', $_POST['ChargeId'][$chargeRow]);
            // insert into payments
              $insertData1 = array( 
                'BankId'            => 1,
                'ApplicationId'     => $generatedId['ApplicationId'],
                'Amount'            => $_POST['chargeTotal'][$chargeRow],
                'Description'       => 'Payment for ' . $charge['Name'],
                'AmountPaid'        => $_POST['chargeTotal'][$chargeRow],
                'IsInterest'        => 0,
                'IsPrincipalCollection' => 0,
                'InterestAmount'    => 0,
                'PrincipalAmount'   => 0,
                'ChangeId'          => 1,
                'ChangeAmount'      => 0,
                'DateCollected'     => date("Y-m-d"),
                'PaymentDate'       => date("Y-m-d"),
                'CreatedBy'         => $EmployeeNumber
              );
              $table = 't_paymentsmade';
              $this->maintenance_model->insertFunction($insertData1, $table);
          }
        }
      }
    // requirements needed
      for($count = 0; $count < count($_POST['RequirementNo']); $count++)
      {
        if($_POST['isRequirementSelected'][$count] == 1)
        {
          // check if already submitted or not
            $insertData = array(
              'ApplicationId'         => $generatedId['ApplicationId'],
              'RequirementId'         => $_POST['RequirementId'][$count],
              'CreatedBy'             => $EmployeeNumber
            );
            $auditTable = 'application_has_requirements';
            $this->maintenance_model->insertFunction($insertData, $auditTable);
            $requirementId = $this->loanapplication_model->getSubmittedReqs($_POST['borrowerId']);
            foreach ($requirementId as $reqID) 
            {
              $getData2 = array(
                'table'                 => 'application_has_requirements'
                , 'column'              => 'ApplicationRequirementId'
                , 'CreatedBy'           => $EmployeeNumber
              );
              $generatedId2 = $this->maintenance_model->getGeneratedId2($getData2);
              if($reqID['IdentificationId'] == $_POST['RequirementId'][$count])
              {
                $set = array( 
                  'DateUpdated'       => $DateNow, 
                  'UpdatedBy'         => $EmployeeNumber, 
                  'StatusId'          => 7, 
                );
                $condition = array( 
                  'RequirementId'   => $reqID['IdentificationId'],
                  'ApplicationId'   =>$generatedId['ApplicationId']
                );
                $table = 'application_has_requirements';
                $this->maintenance_model->updateFunction1($set, $condition, $table);
                print_r($reqID['IdentificationId'] . ' : ' . $_POST['RequirementId'][$count] . ' : '. '7' .'<br>');
              }
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
    
    // redirect('home/loandetail/' . $generatedId['ApplicationId']);
  }

  function restructureLoan()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");

    $ApplicationDetail = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
    if($_POST['PrincipalAmount'] != $ApplicationDetail['PrincipalAmount'])
    {
      // insert Application_has_notification
        $insertNotification = array(
          'Description'                   => 'Restructured loan amount from Php' . number_format($ApplicationDetail['PrincipalAmount'], 2) . ' to Php' . number_format($_POST['PrincipalAmount'], 2)
          , 'ApplicationId'               => $this->uri->segment(3)
          , 'CreatedBy'                   => $EmployeeNumber
        );
        $insertNotificationTable = 'Application_has_Notifications';
        $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
      // main audits
        $RefNo = $this->maintenance_model->selectSpecific('t_application', 'ApplicationId', $this->uri->segment(3));
        $insertData2 = array(
          'Description' => 'Restructured loan amount of application #'.$RefNo.' from Php' . number_format($ApplicationDetail['PrincipalAmount'], 2) . ' to Php' . number_format($_POST['PrincipalAmount'], 2)
          , 'CreatedBy' => $EmployeeNumber
        );
        $auditTable1 = 'Employee_has_Notifications';
        $auditTable2 = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData2, $auditTable1);
        $this->maintenance_model->insertFunction($insertData2, $auditTable2);
      // update
        $set = array( 
          'DateUpdated'       => $DateNow, 
          'UpdatedBy'         => $EmployeeNumber, 
          'PrincipalAmount'   => $_POST['PrincipalAmount'], 
        );
        $condition = array( 
          'ApplicationId'   => $this->uri->segment(3),
        );
        $table = 'T_Application';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
    }
    if($_POST['TermType'] != $ApplicationDetail['TermType'])
    {
      // insert Application_has_notification
        $insertNotification = array(
          'Description'                   => 'Restructured term type from ' . $ApplicationDetail['TermType'] . ' to ' . $_POST['TermType']
          , 'ApplicationId'               => $this->uri->segment(3)
          , 'CreatedBy'                   => $EmployeeNumber
        );
        $insertNotificationTable = 'Application_has_Notifications';
        $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
      // main audits
        $RefNo = $this->maintenance_model->selectSpecific('t_application', 'ApplicationId', $this->uri->segment(3));
        $insertData2 = array(
          'Description'   => 'Restructured term type from ' . $ApplicationDetail['TermType'] . ' to ' . $_POST['TermType']
          , 'CreatedBy'   => $EmployeeNumber
        );
        $auditTable1 = 'Employee_has_Notifications';
        $auditTable2 = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData2, $auditTable1);
        $this->maintenance_model->insertFunction($insertData2, $auditTable2);
      // update
        $set = array( 
          'DateUpdated' => $DateNow, 
          'UpdatedBy'   => $EmployeeNumber, 
          'TermType'    => $_POST['TermType']
        );
        $condition = array( 
          'ApplicationId'   => $this->uri->segment(3),
        );
        $table = 'T_Application';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
    }
    if($_POST['TermNumber'] != $ApplicationDetail['TermNo'])
    {
      // insert Application_has_notification
        $insertNotification = array(
          'Description'                   => 'Restructured term number from ' . $ApplicationDetail['TermNo'] . ' to ' . $_POST['TermNumber']
          , 'ApplicationId'               => $this->uri->segment(3)
          , 'CreatedBy'                   => $EmployeeNumber
        );
        $insertNotificationTable = 'Application_has_Notifications';
        $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
      // main audits
        $RefNo = $this->maintenance_model->selectSpecific('t_application', 'ApplicationId', $this->uri->segment(3));
        $insertData2 = array(
          'Description'   => 'Restructured term number from ' . $ApplicationDetail['TermNo'] . ' to ' . $_POST['TermNumber']
          , 'CreatedBy'   => $EmployeeNumber
        );
        $auditTable1 = 'Employee_has_Notifications';
        $auditTable2 = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData2, $auditTable1);
        $this->maintenance_model->insertFunction($insertData2, $auditTable2);
      // update
        $set = array( 
          'DateUpdated'       => $DateNow, 
          'UpdatedBy'         => $EmployeeNumber, 
          'TermNo'            => $_POST['TermNumber'], 
        );
        $condition = array( 
          'ApplicationId'   => $this->uri->segment(3),
        );
        $table = 'T_Application';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
    }
    if($_POST['RepaymentCycle'] != $ApplicationDetail['RepaymentId'])
    {
      // get repayment details
        $oldRepayment = $this->loanapplication_model->getRepaymentDets($this->uri->segment(3), $ApplicationDetail['RepaymentId']);
        $newRepayment = $this->loanapplication_model->getRepaymentDets($this->uri->segment(3), $_POST['RepaymentCycle']);
      // insert Application_has_notification
        $insertNotification = array(
          'Description'                   => 'Restructured repayment cycle from ' . $oldRepayment['Name'] . ' to ' . $newRepayment['Name']
          , 'ApplicationId'               => $this->uri->segment(3)
          , 'CreatedBy'                   => $EmployeeNumber
        );
        $insertNotificationTable = 'Application_has_Notifications';
        $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
      // main audits
        $RefNo = $this->maintenance_model->selectSpecific('t_application', 'ApplicationId', $this->uri->segment(3));
        $insertData2 = array(
          'Description'   => 'Restructured repayment cycle from ' . $oldRepayment['Name'] . ' to ' . $newRepayment['Name']
          , 'CreatedBy'   => $EmployeeNumber
        );
        $auditTable1 = 'Employee_has_Notifications';
        $auditTable2 = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData2, $auditTable1);
        $this->maintenance_model->insertFunction($insertData2, $auditTable2);
      // update
        $set = array( 
          'DateUpdated'       => $DateNow, 
          'UpdatedBy'         => $EmployeeNumber, 
          'RepaymentId'       => $_POST['RepaymentCycle'], 
        );
        $condition = array( 
          'ApplicationId'   => $this->uri->segment(3),
        );
        $table = 'T_Application';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
    }
    if($_POST['RepaymentsNumber'] != $ApplicationDetail['RepaymentNo'])
    {
      // insert Application_has_notification
        $insertNotification = array(
          'Description'                   => 'Restructured repayment number from ' . $ApplicationDetail['RepaymentNo'] . ' to ' . $_POST['RepaymentsNumber']
          , 'ApplicationId'               => $this->uri->segment(3)
          , 'CreatedBy'                   => $EmployeeNumber
        );
        $insertNotificationTable = 'Application_has_Notifications';
        $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
      // main audits
        $RefNo = $this->maintenance_model->selectSpecific('t_application', 'ApplicationId', $this->uri->segment(3));
        $insertData2 = array(
          'Description'   => 'Restructured repayment number from ' . $ApplicationDetail['RepaymentNo'] . ' to ' . $_POST['RepaymentsNumber']
          , 'CreatedBy'   => $EmployeeNumber
        );
        $auditTable1 = 'Employee_has_Notifications';
        $auditTable2 = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData2, $auditTable1);
        $this->maintenance_model->insertFunction($insertData2, $auditTable2);
      // update
        $set = array( 
          'DateUpdated'       => $DateNow, 
          'UpdatedBy'         => $EmployeeNumber, 
          'RepaymentNo'       => $_POST['RepaymentsNumber'], 
        );
        $condition = array( 
          'ApplicationId'   => $this->uri->segment(3),
        );
        $table = 'T_Application';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
    }

    $interestDetail = $this->maintenance_model->selectSpecific('application_has_interests', 'ApplicationId', $this->uri->segment(3));
    if($_POST['interestType'] != $interestDetail['InterestType'])
    {
      // insert Application_has_notification
        $insertNotification = array(
          'Description'                   => 'Restructured repayment number from ' . $interestDetail['InterestType'] . ' to ' . $_POST['interestType']
          , 'ApplicationId'               => $this->uri->segment(3)
          , 'CreatedBy'                   => $EmployeeNumber
        );
        $insertNotificationTable = 'Application_has_Notifications';
        $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
      // main audits
        $RefNo = $this->maintenance_model->selectSpecific('t_application', 'ApplicationId', $this->uri->segment(3));
        $insertData2 = array(
          'Description'   => 'Restructured interest type from ' . $interestDetail['InterestType'] . ' to ' . $_POST['interestType']
          , 'CreatedBy'   => $EmployeeNumber
        );
        $auditTable1 = 'Employee_has_Notifications';
        $auditTable2 = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData2, $auditTable1);
        $this->maintenance_model->insertFunction($insertData2, $auditTable2);
      // update
        $set = array( 
          'DateUpdated'         => $DateNow, 
          'UpdatedBy'           => $EmployeeNumber, 
          'InterestType'        => $_POST['interestType'], 
        );
        $condition = array( 
          'ApplicationId'   => $this->uri->segment(3),
        );
        $table = 'application_has_interests';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
    }
    if($_POST['interestAmount'] != $interestDetail['Amount'])
    {
      // insert Application_has_notification
        $insertNotification = array(
          'Description'                   => 'Restructured interest amount from ' . $interestDetail['Amount'] . ' to ' . $_POST['interestAmount']
          , 'ApplicationId'               => $this->uri->segment(3)
          , 'CreatedBy'                   => $EmployeeNumber
        );
        $insertNotificationTable = 'Application_has_Notifications';
        $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
      // main audits
        $RefNo = $this->maintenance_model->selectSpecific('t_application', 'ApplicationId', $this->uri->segment(3));
        $insertData2 = array(
          'Description'   => 'Restructured interest amount from ' . $interestDetail['Amount'] . ' to ' . $_POST['interestAmount']
          , 'CreatedBy'   => $EmployeeNumber
        );
        $auditTable1 = 'Employee_has_Notifications';
        $auditTable2 = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData2, $auditTable1);
        $this->maintenance_model->insertFunction($insertData2, $auditTable2);
      // update
        $set = array( 
          'DateUpdated'         => $DateNow, 
          'UpdatedBy'           => $EmployeeNumber, 
          'Amount'              => $_POST['interestAmount'], 
        );
        $condition = array( 
          'ApplicationId'   => $this->uri->segment(3),
        );
        $table = 'application_has_interests';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
    }
    if($_POST['interestFrequency'] != $interestDetail['Frequency'])
    {
      // insert Application_has_notification
        $insertNotification = array(
          'Description'                   => 'Restructured interest frequency from ' . $interestDetail['Frequency'] . ' to ' . $_POST['interestType']
          , 'ApplicationId'               => $this->uri->segment(3)
          , 'CreatedBy'                   => $EmployeeNumber
        );
        $insertNotificationTable = 'Application_has_Notifications';
        $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
      // main audits
        $RefNo = $this->maintenance_model->selectSpecific('t_application', 'ApplicationId', $this->uri->segment(3));
        $insertData2 = array(
          'Description'   => 'Restructured interest frequency from ' . $interestDetail['Frequency'] . ' to ' . $_POST['interestFrequency']
          , 'CreatedBy'   => $EmployeeNumber
        );
        $auditTable1 = 'Employee_has_Notifications';
        $auditTable2 = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData2, $auditTable1);
        $this->maintenance_model->insertFunction($insertData2, $auditTable2);
      // update
        $set = array( 
          'DateUpdated'      => $DateNow, 
          'UpdatedBy'        => $EmployeeNumber, 
          'Frequency'        => $_POST['interestFrequency'], 
        );
        $condition = array( 
          'ApplicationId'   => $this->uri->segment(3),
        );
        $table = 'application_has_interests';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
    }
    
    $this->session->set_flashdata('alertTitle','Success!'); 
    $this->session->set_flashdata('alertText','Successfully restructured loan!'); 
    $this->session->set_flashdata('alertType','success'); 
    redirect('home/loandetail/' . $this->uri->segment(3));

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
      $detail = $this->maintenance_model->selectSpecific('t_paymentsmade', 'PaymentMadeId', $_POST['ChargeId']);
      if($detail['IsOthers'] == 1)
      {
        $newBalance = $detail['AmountPaid'] + $_POST['CurrentBalance'];
      }
      else
      {
        $newBalance = $detail['Amount'] + $_POST['CurrentBalance'];
      }
      if($newBalance > 0)
      {
        $set = array( 
          'StatusId' => 1
        );
        $condition = array( 
          'ApplicationId' => $this->uri->segment(3)
        );
        $table = 't_application';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
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
            'Description'                   => 'Added a comment '
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

  function AddDisbursement()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $ApplicationDetail = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
    $DisbursementDetail = $this->loanapplication_model->getDisbursementDetails($_POST['DisbursementId']);
    $DateNow = date("Y-m-d H:i:s");
    if ($_POST['FormType'] == 1) // add Disbursement
    {
      $data = array(
         'Amount'               => htmlentities($_POST['DisbursementAmount'], ENT_QUOTES)
        , 'Description'          => htmlentities($_POST['Description'], ENT_QUOTES)
        , 'ApplicationId'        => $this->uri->segment(3)
      );
      $query = $this->loanapplication_model->countDisbursement($data);
      print_r($query);
      if($query == 0) // not existing
      {
        // insert Disbursement details
          $insertDisbursement = array(
            'Amount'                      => htmlentities($_POST['DisbursementAmount'], ENT_QUOTES)
            , 'Description'               => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'StatusId'                  => 1
            , 'CreatedBy'                 => $EmployeeNumber
            , 'UpdatedBy'                 => $EmployeeNumber
            , 'ApplicationId'             => $this->uri->segment(3)
          );
          $insertDisbursementTable = 'application_has_Disbursement';
          $this->maintenance_model->insertFunction($insertDisbursement, $insertDisbursementTable);
        // get generated application id
          $getData = array(
            'table'                 => 'application_has_Disbursement'
            , 'column'              => 'DisbursementId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
          $DisbursementDetail = $this->maintenance_model->selectSpecific('application_has_Disbursement', 'DisbursementId', $generatedId['DisbursementId']);
        // insert Application_has_notification
          $insertNotification = array(
            'Description'                   => 'Added a Disbursement to the Disbursement tab '
            , 'ApplicationId'               => $this->uri->segment(3)
            , 'CreatedBy'                   => $EmployeeNumber
          );
          $insertNotificationTable = 'Application_has_Notifications';
          $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
        // Insert Main Logs
          $auditDetail = ' Added a Disbursement to Reference #' .$ApplicationDetail['TransactionNumber'];
          $insertData = array(
            'Description' => $auditDetail
            , 'CreatedBy' => $EmployeeNumber
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Disbursement details successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/loandetail/'. $this->uri->segment(3));
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Disbursement details already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/loandetail'. $this->uri->segment(3));
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
    if($_POST['modalType'] == '') // add
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
      // admin audits
        $transNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
        $auditApplication = 'Added collateral #CLR-' .sprintf('%05d', $generatedId['CollateralId']). ' in collateral list.';
        $auditLogsManager = 'Added collateral #CLR-' .sprintf('%05d', $generatedId['CollateralId']). ' to application #'.$transNo['TransactionNumber'].'.';
        $this->auditLoanApplication($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditApplication, $this->uri->segment(3));
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
    else if($_POST['modalType'] == 1) // edit
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
      if($detail['CollateralTypeId'] != $_POST['CollateralTypeId'])
      {
        $set = array( 
          'CollateralTypeId'  => $_POST['CollateralTypeId'],
          'UpdatedBy'         => $EmployeeNumber,
          'DateUpdated'       => $DateNow
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
            if($fileName != null)
            {
              $this->upload->data();
              $set = array( 
                'StatusId'     => 0,
              );
              $condition = array( 
                'CollateralId' => $_POST['CollateralId'],
                'StatusId' => 1
              );
              $table = 'collaterals_has_files';
              $this->maintenance_model->updateFunction1($set, $condition, $table);

              $insertComment = array(
                'CollateralId'                => $_POST['CollateralId']
                , 'FileName'                  => $fileName
                , 'Title'                     => $Title
                , 'CreatedBy'                 => $EmployeeNumber
              );
              $insertCommentTable = 'collaterals_has_files';
              $this->maintenance_model->insertFunction($insertComment, $insertCommentTable);
            }
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

  // function penaltySettings()
  // {
  //   $EmployeeNumber = $this->session->userdata('EmployeeNumber');
  //   $DateNow = date("Y-m-d H:i:s");
  //   if(isset($_POST['PenaltyType']))
  //   {
  //     $set = array( 
  //       'IsPenalized'     => 1,
  //       'PenaltyType'     => $_POST['PenaltyType'],
  //       'PenaltyAmount'   => $_POST['PenaltyAmount'],
  //       'GracePeriod'     => $_POST['GracePeriod']
  //     );
  //     $condition = array( 
  //       'ApplicationId' => $this->uri->segment(3)
  //     );
  //     $table = 't_application';
  //     $this->maintenance_model->updateFunction1($set, $condition, $table);
  //   }

  //   $this->session->set_flashdata('alertTitle','Success!'); 
  //   $this->session->set_flashdata('alertText','Loan application updated!'); 
  //   $this->session->set_flashdata('alertType','success'); 
  //   redirect('home/loandetail/'. $this->uri->segment(3));
  // }

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

  function getDetails()
  {
    $output = $this->loanapplication_model->getDetails($this->input->post('Type'), $this->input->post('Id'));
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
    $output = $this->loanapplication_model->getChargeDetails($this->input->post('Id'), $this->input->post('Type'));
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
    $string1 = strtotime($_POST['datePayment']);
    $varDatePayment = date('Y-m-d', $string1);
    $string2 = strtotime($_POST['dateCollected']);
    $varDateCollected = date('Y-m-d', $string2);
    if($this->uri->segment(4) == 1) // penalty
    {
      // insert into penalty
        $insertData2 = array( 
          'ApplicationId'     => $this->uri->segment(3),
          'PenaltyType'       => $_POST['PenaltyType'],
          'Amount'            => $_POST['PenaltyAmount'],
          'AmountPaid'        => $_POST['Amount'],
          'GracePeriod'       => $_POST['GracePeriod'],
          'TotalPenalty'      => $_POST['TotalPenalty'],
          'DateCollected'     => $varDateCollected,
          'DatePaid'          => $varDatePayment,
          'CreatedBy'         => $EmployeeNumber
        );
        $table2 = 'application_has_penalty';
        $this->maintenance_model->insertFunction($insertData2, $table2);

        // get generated id
          $getData2 = array(
            'table'                 => 'application_has_penalty'
            , 'column'              => 'ApplicationPenaltyId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId2 = $this->maintenance_model->getGeneratedId2($getData2);

        // insert Application_has_notification
          $insertNotification = array(
            'Description'                   => 'Added penalty #PLT-'.sprintf('%05d', $generatedId2['ApplicationPenaltyId']).'.'
            , 'ApplicationId'               => $this->uri->segment(3)
            , 'CreatedBy'                   => $EmployeeNumber
          );
          $insertNotificationTable = 'Application_has_Notifications';
          $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
        // main audits
          $RefNo = $this->maintenance_model->selectSpecific('t_application', 'ApplicationId', $this->uri->segment(3));
          $auditDetail = 'Added penalty #PLT-'.sprintf('%05d', $generatedId2['ApplicationPenaltyId']).' to ' . $RefNo['TransactionNumber'];
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
          $this->session->set_flashdata('alertText','Successfully added penalty!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/loandetail/' . $this->uri->segment(3));
    }
    else
    {
      // status update
        if($_POST['updateStatus'] == 1) // update to matured
        {
          $set = array( 
            'StatusId' => 4
          );
          $condition = array( 
            'ApplicationId' => $this->uri->segment(3)
          );
          $table = 't_application';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
      // insert into payments
        if(isset($_POST['chkPayment3'][0]))
        {        
          $insertData = array( 
            'BankId'            => $_POST['BankId'],
            'ApplicationId'     => $this->uri->segment(3),
            'Amount'            => $_POST['Amount'],
            'Description'       => $_POST['Remarks'],
            'AmountPaid'        => $_POST['Amount'],
            'IsOthers'          => isset($_POST['chkPayment3'][0]),
            'ChangeId'          => $_POST['ChangeMethod'],
            'DateCollected'     => $varDateCollected,
            'PaymentDate'       => $varDatePayment,
            'CreatedBy'         => $EmployeeNumber
          );
        }
        else
        {        
          $insertData = array( 
            'BankId'            => $_POST['BankId'],
            'ApplicationId'     => $this->uri->segment(3),
            'Amount'            => $_POST['AmountDue'],
            'Description'       => $_POST['Remarks'],
            'AmountPaid'        => $_POST['Amount'],
            'IsInterest'        => isset($_POST['chkPayment2'][0]),
            'IsPrincipalCollection' => isset($_POST['chkPayment1'][0]),
            'InterestAmount'    => $_POST['InterestAmountCollected'],
            'PrincipalAmount'   => $_POST['PrincipalAmountCollected'],
            'ChangeId'          => $_POST['ChangeMethod'],
            'ChangeAmount'      => $_POST['ChangeAmount'],
            'DateCollected'     => $varDateCollected,
            'PaymentDate'       => $varDatePayment,
            'CreatedBy'         => $EmployeeNumber
          );
        }
        $table = 't_paymentsmade';
        $this->maintenance_model->insertFunction($insertData, $table);
      // get generated application id
        $getData = array(
          'table'                 => 't_paymentsmade'
          , 'column'              => 'PaymentMadeId'
          , 'CreatedBy'           => $EmployeeNumber
        );
        $generatedId = $this->maintenance_model->getGeneratedId2($getData);
      // insert into penalty
        if($_POST['IsPenalized'] == 1)
        {
          $insertData2 = array( 
            'PaymentMadeId'     => $generatedId['PaymentMadeId'],
            'ApplicationId'     => $this->uri->segment(3),
            'PenaltyType'       => $_POST['PenaltyType'],
            'Amount'            => $_POST['PenaltyAmount'],
            'GracePeriod'       => $_POST['GracePeriod'],
            'TotalPenalty'      => $_POST['TotalPenalty'],
            'CreatedBy'         => $EmployeeNumber
          );
          $table2 = 'application_has_penalty';
          $this->maintenance_model->insertFunction($insertData2, $table2);

          // get generated id
            $getData2 = array(
              'table'                 => 'application_has_penalty'
              , 'column'              => 'ApplicationPenaltyId'
              , 'CreatedBy'           => $EmployeeNumber
            );
            $generatedId2 = $this->maintenance_model->getGeneratedId2($getData);

          // insert Application_has_notification
            $insertNotification = array(
              'Description'                   => 'Added penalty #PLT-'.sprintf('%05d', $generatedId2['ApplicationPenaltyId']).'.'
              , 'ApplicationId'               => $this->uri->segment(3)
              , 'CreatedBy'                   => $EmployeeNumber
            );
            $insertNotificationTable = 'Application_has_Notifications';
            $this->maintenance_model->insertFunction($insertNotification, $insertNotificationTable);
          // main audits
            $RefNo = $this->maintenance_model->selectSpecific('t_application', 'ApplicationId', $this->uri->segment(3));
            $auditDetail = 'Added penalty #PLT-'.sprintf('%05d', $generatedId2['ApplicationPenaltyId']).' to ' . $RefNo['TransactionNumber'];
            $insertData2 = array(
              'Description' => $auditDetail
              , 'CreatedBy' => $EmployeeNumber
            );
            $auditTable1 = 'Employee_has_Notifications';
            $auditTable2 = 'R_Logs';
            $this->maintenance_model->insertFunction($insertData2, $auditTable1);
            $this->maintenance_model->insertFunction($insertData2, $auditTable2);
        }
      // insert Application_has_notification
        $insertNotification = array(
          'Description'                   => 'Added payment #PYM-'.sprintf('%05d', $generatedId['PaymentMadeId']).'.'
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

  function getPaymentsMaid()
  {
    $output = $this->loanapplication_model->getPaymentsMaid($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getPaymentDates()
  {
    $output = $this->loanapplication_model->getPaymentDates($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getDue()
  {
    $output = $this->loanapplication_model->getDue($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function AuditFunction($auditLogsManager, $auditAffectedEmployee, $ManagerId, $AffectedEmployeeNumber)
  {
    $CreatedBy = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    $insertMainLog = array(
      'Description'       => $auditLogsManager
      , 'CreatedBy'       => $CreatedBy
    );
    $auditTable1 = 'R_Logs';
    $this->maintenance_model->insertFunction($insertMainLog, $auditTable1);
    $insertManagerAudit = array(
      'Description'         => $auditLogsManager
      , 'ManagerBranchId'   => $ManagerId
      , 'CreatedBy'         => $CreatedBy
    );
    $auditTable3 = 'manager_has_notifications';
    $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable3);
    $insertEmpLog = array(
      'Description'       => $auditAffectedEmployee
      , 'EmployeeNumber'  => $AffectedEmployeeNumber
      , 'CreatedBy'       => $CreatedBy
    );
    $auditTable2 = 'employee_has_notifications';
    $this->maintenance_model->insertFunction($insertEmpLog, $auditTable2);
  }

  function auditLoanApplication($auditLogsManager, $auditAffectedEmployee, $ManagerId, $AffectedEmployeeNumber, $auditLoanDets ,$ApplicationId)
  {
    $CreatedBy = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    $insertMainLog = array(
      'Description'       => $auditLogsManager
      , 'CreatedBy'       => $CreatedBy
    );
    $auditTable1 = 'R_Logs';
    $this->maintenance_model->insertFunction($insertMainLog, $auditTable1);
    $insertManagerAudit = array(
      'Description'         => $auditLogsManager
      , 'ManagerBranchId'   => $ManagerId
      , 'CreatedBy'         => $CreatedBy
    );
    $auditTable3 = 'manager_has_notifications';
    $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable3);
    $insertEmpLog = array(
      'Description'       => $auditAffectedEmployee
      , 'EmployeeNumber'  => $AffectedEmployeeNumber
      , 'CreatedBy'       => $CreatedBy
    );
    $auditTable2 = 'employee_has_notifications';
    $this->maintenance_model->insertFunction($insertEmpLog, $auditTable2);
    $insertApplicationLog = array(
      'Description'       => $auditLoanDets
      , 'ApplicationId'   => $ApplicationId
      , 'CreatedBy'       => $CreatedBy
    );
    $auditLoanApplicationTable = 'application_has_notifications';
    $this->maintenance_model->insertFunction($insertApplicationLog, $auditLoanApplicationTable);
  }

  function generateReport()
  {
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'M.C Biliber Lending Corporation', "Report Generation");
    // set margins
    $pdf->SetMargins('10', '20', '10');
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->SetFont('dejavusans', '', 10);

    
    if($this->uri->segment(3) == 1) // loan collections
    {
      $width = 300;  
      $height = 500; 
      $pageLayout = array($width, $height); //  or array($height, $width) 
      $pdf->AddPage('L', $pageLayout);

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

        <p>'.htmlentities($_POST['reportName'], ENT_QUOTES).'</p>
        <p>'.htmlentities($_POST['DateFrom'], ENT_QUOTES).' - '.htmlentities($_POST['DateTo'], ENT_QUOTES).'</p>

        <br>
        <br>

        ';

        $comma_separated = implode("','", $_POST['employeeReport']);
        $employeeNumbers = "'".$comma_separated."'";
        $employeeQuery = 'AND EmployeeNumber IN ('.$employeeNumbers.')';
        $string = implode(", ", $_POST['columnNames']);
        $stringArray = array();

        foreach($_POST['columnNames'] as $column) 
        {
          if($column == 'Loan Date')
          {
            $stringArray[] = "DATE_FORMAT(A.DateCreated, '%b %d, %Y') as LoanDate";
          }
          else if($column == 'Application No.')
          {
            $stringArray[] = 'A.TransactionNumber';
          }
          else if($column == 'Borrower Name')
          {
            $stringArray[] = "CONCAT(B.FirstName, ' ', B.MiddleName, ' ', B.LastName, ', ', B.ExtName) as BorrowerName";
          }
          else if($column == 'Principal Per Collection')
          {
            $stringArray[] = "FORMAT(PM.PrincipalAmount, 2) as principalCollection";
          }
          else if($column == 'Interest Per Collection')
          {
            $stringArray[] = "FORMAT(PM.InterestAmount, 2) as interestPerCollection";
          }
          else if($column == 'Other Collections')
          {
            $stringArray[] = "FORMAT(PM.AmountPaid, 2) as otherCollection";
          }
          else if($column == 'Amount Paid')
          {
            $stringArray[] = "FORMAT(PM.AmountPaid, 2) as AmountPaid";
          }
          else if($column == 'Change')
          {
            $stringArray[] = "FORMAT(PM.ChangeAmount, 2) as ChangeAmount";
          }
          else if($column == 'Amount Paid')
          {
            $stringArray[] = "PM.Amount as AmountToPay";
          }
        }
        $html .='
        <table>
          <thead>
          <tr>
        ';
            foreach($_POST['columnNames'] as $column) 
            {
                if($column == 'Loan Date')
                {
                  $html .= '<th><strong>'.$column.'</strong></th>';
                }
                if($column == 'Application No.')
                {
                  $html .= '<th><strong>'.$column.'</strong></th>';
                }
                if($column == 'Borrower Name')
                {
                  $html .= '<th><strong>'.$column.'</strong></th>';
                }
                if($column == 'Principal Per Collection')
                {
                  $html .= '<th><strong>'.$column.'</strong></th>';
                }
                if($column == 'Interest Per Collection')
                {
                  $html .= '<th><strong>'.$column.'</strong></th>';
                }
                if($column == 'Other Collections')
                {
                  $html .= '<th><strong>'.$column.'</strong></th>';
                }
                if($column == 'Repayment Date')
                {
                  $html .= '<th><strong>'.$column.'</strong></th>';
                }
                if($column == 'Change')
                {
                  $html .= '<th><strong>'.$column.'</strong></th>';
                }
                if($column == 'Penalty')
                {
                  $html .= '<th><strong>'.$column.'</strong></th>';
                }
                if($column == 'Amount Paid')
                {
                  $html .= '<th><strong>'.$column.'</strong></th>';
                }
                if($column == 'Collected By')
                {
                  $html .= '<th><strong>'.$column.'</strong></th>';
                }
                if($column == 'Collection Date')
                {
                  $html .= '<th><strong>'.$column.'</strong></th>';
                }
                if($column == 'Creation Date')
                {
                  $html .= '<th><strong>'.$column.'</strong></th>';
                }
            }
            $html .='
          </tr>
          </thead>
          <tbody>';
            $totalPrincipalCollection = 0;
            $toalInterestCollection = 0;
            $totalOtherCollection = 0;
            $totalChangeAmount = 0;
            $totalAmountPaid = 0;
            $time = strtotime($_POST['DateFrom']);
            $newformat = date('Y-m-d', $time);
            $time2 = strtotime($_POST['DateTo']);
            $newformat2 = date('Y-m-d', $time2);
            $details = $this->loanapplication_model->getCollections($newformat, $newformat2, implode(", ", $stringArray), $employeeQuery);
            foreach($details as $key => $current) {
              $repayment = $this->loanapplication_model->getRepayments($current['ApplicationId']);
              $penalty = $this->loanapplication_model->getPenalties($current['ApplicationId']);

              $totalPrincipalCollection = $totalPrincipalCollection  + floatval($current['rawPrincipalCollection']);
              $toalInterestCollection = $toalInterestCollection  + floatval($current['rawInterestCollection']);
              $totalOtherCollection = $totalOtherCollection  + floatval($current['rawAmountPaid']);
              $totalChangeAmount = $totalChangeAmount  + floatval($current['rawChangeAmount']);
              $totalAmountPaid = $totalAmountPaid + floatval($current['AmountPaid']);

              $html .= '<tr>';

              foreach($_POST['columnNames'] as $column) 
              {
                if($column == 'Loan Date')
                {
                  $html .= '<td>' . $current['LoanDate'] . '</td>';
                }
                else if($column == 'Application No.')
                {
                  $html .= '<td>' . $current['TransactionNumber'] . '</td>';
                }
                else if($column == 'Borrower Name')
                {
                  $html .= '<td>' . $current['BorrowerName'] . '</td>';
                }
                else if($column == 'Principal Per Collection')
                {
                  $html .= '<td>' . $current['principalCollection'] . '</td>';
                }
                else if($column == 'Interest Per Collection')
                {
                  $html .= '<td>' . $current['interestPerCollection'] . '</td>';
                }
                else if($column == 'Other Collections')
                {
                  $html .= '<td>' . $current['otherCollection'] . '</td>';
                }
                else if($column == 'Repayment Date')
                {
                  $html .= '<td>' . $repayment['Name'] . '</td>';
                }
                else if($column == 'Change')
                {
                  $html .= '<td>' . $current['ChangeAmount'] . '</td>';
                }
                else if($column == 'Penalty')
                {
                  $html .= '<td>' . $penalty['Total'] . '</td>';
                }
                else if($column == 'Amount Paid')
                {
                  $html .= '<td>' . number_format($current['AmountPaid'], 2) . '</td>';
                }
                if($column == 'Collected By')
                {
                  $html .= '<td>' . $current['CollectedBy'] . '</td>';
                }
                if($column == 'Collection Date')
                {
                  $html .= '<td>' . $current['dateCollected'] . '</td>';
                }
                if($column == 'Creation Date')
                {
                  $html .= '<td>' . $current['dateCreated'] . '</td>';
                }
              }
              $html .= '</tr>';
            }
          $html .= '
          <tbody>
          <tfoot>
            <tr>
            ';

              foreach($_POST['columnNames'] as $column) 
              {
                if($column == 'Loan Date')
                {
                  $html .= '<td></td>';
                }
                else if($column == 'Application No.')
                {
                  $html .= '<td></td>';
                }
                else if($column == 'Borrower Name')
                {
                  $html .= '<td></td>';
                }
                else if($column == 'Principal Per Collection')
                {
                  $html .= '<td>Php '.number_format($totalPrincipalCollection, 2).'</td>';
                }
                else if($column == 'Interest Per Collection')
                {
                  $html .= '<td>Php '.number_format($toalInterestCollection, 2).'</td>';
                }
                else if($column == 'Other Collections')
                {
                  $html .= '<td>Php '.number_format($totalOtherCollection, 2).'</td>';
                }
                else if($column == 'Repayment Date')
                {
                  $html .= '<td></td>';
                }
                else if($column == 'Change')
                {
                  $html .= '<td>Php '.number_format($totalChangeAmount, 2).'</td>';
                }
                else if($column == 'Amount Paid')
                {
                  $html .= '<td>Php '.number_format($totalAmountPaid, 2).'</td>';
                }
              }

            $html .= '
            </tr>
          </tfoot>
        </table>
        <br><br>
        <br><br>

        <table>
          <thead>
          <tr>
            <th><strong>Prepared By</strong></th>
            <th>'.$this->session->userdata('Name').'</th>
            <th><strong>Verified By</strong></th>
            <th>'.$_POST['verifiedBy'].'</th>
            <th><strong>Approved By</strong></th>
            <th>'.$_POST['approvedBy'].'</th>
          </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      ';
      $pdf->writeHTML($html, true, false, true, false, '');

      // Close and output PDF document
      // $pdf->Output('Form3.pdf', 'I');
      $pdf->Output(htmlentities($_POST['reportName'], ENT_QUOTES) .'.pdf', 'D');
    }
    if($this->uri->segment(3) == 2) // expenses
    {
      $width = 300;  
      $height = 500; 
      $pageLayout = array($width, $height); //  or array($height, $width) 
      $pdf->AddPage('L', $pageLayout);


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

        <p>'.htmlentities($_POST['reportName'], ENT_QUOTES).'</p>
        <p>'.htmlentities($_POST['DateFrom'], ENT_QUOTES).' - '.htmlentities($_POST['DateTo'], ENT_QUOTES).'</p>

        <br>
        <br>

        ';
        $html .='
        <table>
          <thead>
          <tr>
        ';
            foreach($_POST['columnNames'] as $column) 
            {
                if($column == 'Expense No.')
                {
                  $html .= '<th><strong>'.$column.'</strong></th>';
                }
                if($column == 'Expense Type')
                {
                  $comma_separated = implode("','", $_POST['expenseType']);
                  $expenseTypes = "'".$comma_separated."'";
                  $query = 'AND EXT.ExpenseTypeId IN ('.$expenseTypes.')';
                  $html .= '<th><strong>'.$column.'</strong></th>';
                }
                if($column == 'Amount')
                {
                  $html .= '<th><strong>'.$column.'</strong></th>';
                }
                if($column == 'Date of Expense')
                {
                  $html .= '<th><strong>'.$column.'</strong></th>';
                }
                if($column == 'Date of Creation')
                {
                  $html .= '<th><strong>'.$column.'</strong></th>';
                }
                if($column == 'Created By')
                {
                  $html .= '<th><strong>'.$column.'</strong></th>';
                }
            }
            $html .='
          </tr>
          </thead>
          <tbody>';
            $totalExpense = 0;
            $time = strtotime($_POST['DateFrom']);
            $newformat = date('Y-m-d', $time);
            $time2 = strtotime($_POST['DateTo']);
            $newformat2 = date('Y-m-d', $time2);
            $details = $this->loanapplication_model->getExpensesReport($newformat, $newformat2, $query);
            foreach($details as $key => $current) {
              $totalExpense = $totalExpense  + floatval($current['Amount']);

              $html .= '<tr>';

              foreach($_POST['columnNames'] as $column) 
              {
                if($column == 'Expense No.')
                {
                  $html .= '<td>' . $current['ReferenceNo'] . '</td>';
                }
                if($column == 'Expense Type')
                {
                  $html .= '<td>' . $current['Name'] . '</td>';
                }
                if($column == 'Amount')
                {
                  $html .= '<td>Php ' . number_format($current['Amount'], 2) . '</td>';
                }
                if($column == 'Date of Expense')
                {
                  $html .= '<td>' . $current['DateExpense'] . '</td>';
                }
                if($column == 'Date of Creation')
                {
                  $html .= '<td>' . $current['DateCreated'] . '</td>';
                }
                if($column == 'Created By')
                {
                  $html .= '<td>' . $current['CreatedBy'] . '</td>';
                }
              }
              $html .= '</tr>';
            }
          $html .= '
          <tbody>
          <tfoot>
            <tr>
            ';

              foreach($_POST['columnNames'] as $column) 
              {
                if($column == 'Expense No.')
                {
                  $html .= '<td></td>';
                }
                if($column == 'Expense Type')
                {
                  $html .= '<td></td>';
                }
                if($column == 'Amount')
                {
                  $html .= '<td>Php ' . number_format($totalExpense, 2) . '</td>';
                }
                if($column == 'Date of Expense')
                {
                  $html .= '<td></td>';
                }
                if($column == 'Date of Creation')
                {
                  $html .= '<td></td>';
                }
                if($column == 'Created By')
                {
                  $html .= '<td></td>';
                }
              }

            $html .= '
            </tr>
          </tfoot>
        </table>
        <br><br>
        <br><br>

        <table>
          <thead>
          <tr>
            <th><strong>Prepared By</strong></th>
            <th>'.$this->session->userdata('Name').'</th>
            <th><strong>Verified By</strong></th>
            <th>'.$_POST['verifiedBy'].'</th>
            <th><strong>Approved By</strong></th>
            <th>'.$_POST['approvedBy'].'</th>
          </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      ';
      $pdf->writeHTML($html, true, false, true, false, '');

      // Close and output PDF document
      $pdf->Output('Form3.pdf', 'I');
      // $pdf->Output(htmlentities($_POST['reportName'], ENT_QUOTES) .'.pdf', 'D');
    }
    if($this->uri->segment(3) == 3) // loan pplication
    {
      $this->load->library('excel');
      require_once(APPPATH . 'third_party\PHPExcel\Classes\PHPExcel\IOFactory.php');

      //set the desired name of the excel file

      $inputFileName = APPPATH . 'excelforms\Loan Application.xls';
      $proceed = 0;

      /*check point*/

      $Day = date('d');
      $Month = date('m');
      $Year = date('Y');
      $DayNo = date('w');
      $Time = date('G');
      $Minute = date('i');

      $CurrentDate = $Day. $Month . $Year . $DayNo . $Time . $Minute ;

      $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
      $objReader = PHPExcel_IOFactory::createReader($inputFileType);
      $objPHPExcel = $objReader->load($inputFileName);

      // Set active sheet index to the first sheet, so Excel opens this as the first sheet
      $objPHPExcel->setActiveSheetIndex(0);


      $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
      $objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
      $objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);
      $objPHPExcel->getActiveSheet()->getProtection()->setFormatCells(true);

      // $objPHPExcel->getActiveSheet()->getProtection()->setPassword('101419961213');

      unset($sheet1);

      foreach($objPHPExcel->getWorksheetIterator() as $sheet) 
      {
        $index =  $objPHPExcel->getIndex($sheet);

        // Details
          $details = $this->loanapplication_model->getLoanApplicationDetails($this->uri->segment(4));
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('B12', $details['DateCreated']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('D12', $details['Source'] . ' ' . $details['SourceName']);
          
          $gdImage = imagecreatefromjpeg('borrowerpicture/' . $details['FileName']);
          $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
          $objDrawing->setImageResource($gdImage);
          $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
          $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
          $objDrawing->setCoordinates('F11');

          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A22', number_format($details['RawPrincipalAmount'], 2));
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C22', $details['TermNo'] . ' ' . $details['TermType']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('E22', $details['PurposeName']);

          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A25', $details['LastName']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C25', $details['FirstName']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('E25', $details['MiddleName']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('G25', $details['ExtName']);

          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A27', $details['ReportDOB']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C27', $details['CivilStatus']);

          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('E27', $details['Dependents']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('F27', $details['EmailAddress']);

          $cityAddress = $this->loanapplication_model->getCityAddress($details['BorrowerId']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A31', $cityAddress['HouseNo'] . ' ' . $cityAddress['BrgyDesc']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('E32', $cityAddress['YearsStayed']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('F32', $cityAddress['MonthsStayed']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('E34', $cityAddress['Telephone']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C34', $cityAddress['ContactNumber']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C34', $cityAddress['AddressType'] . ' ' . $cityAddress['NameOfLandlord']);

          $provAddress = $this->loanapplication_model->getProvinceAddress($details['BorrowerId']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C31', $provAddress['HouseNo'] . ' ' . $provAddress['BrgyDesc']);

          $presentEmployer = $this->loanapplication_model->getEmployer($details['BorrowerId'], 1);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A38', $presentEmployer['EmployerName']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('D38', $presentEmployer['BusinessAddress']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A41', $presentEmployer['Position']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C41', $presentEmployer['DateHired']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('E41', $presentEmployer['TelephoneNumber']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('G41', $presentEmployer['TenureYear'] . 'yr ' . $presentEmployer['TenureMonth'] . ' mts');

          $prevEmployer = $this->loanapplication_model->getEmployer($details['BorrowerId'], 2);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A43', $prevEmployer['EmployerName']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('D43', $prevEmployer['BusinessAddress']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A46', $prevEmployer['Position']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C46', $prevEmployer['DateHired']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('E46', $prevEmployer['TelephoneNumber']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('G46', $prevEmployer['TenureYear'] . 'yr ' . $prevEmployer['TenureMonth'] . ' mts');

        // Personal References
          $personalRef = $this->loanapplication_model->getReferences($details['BorrowerId']);
          $rowss = 3;
          foreach ($personalRef as $row)
          {
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue("H$rowss", $row['Name']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue("K$rowss", $row['Address']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue("P$rowss", $row['ContactNumber']);
            $rowss++;
          }

        // spouse
          $spouseId = $this->maintenance_model->selectSpecific2('borrower_has_spouse', 'BorrowerId', $details['BorrowerId']);
          if(isset($spouseId['SpouseId']))
          {
            $spouseDetails = $this->loanapplication_model->getSpouseDetails($spouseId['SpouseId'], 2);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H9', $spouseDetails['LastName']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('J9', $spouseDetails['FirstName']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('N9', $spouseDetails['MiddleName']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('Q9', $spouseDetails['ExtName']);

            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H11', $spouseDetails['ReportDOB']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('J11', $spouseDetails['CivilStatus']);

            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('N11', $spouseDetails['Dependents']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('P11', $spouseDetails['EmailAddress']);
          // spouse address
            $cityAddressSpouse = $this->loanapplication_model->getCityAddressSpouse($spouseId['SpouseId']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H15', $cityAddressSpouse['HouseNo'] . ' ' . $cityAddressSpouse['BrgyDesc']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('O16', $cityAddressSpouse['YearsStayed']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('P16', $cityAddressSpouse['MonthsStayed']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('O18', $cityAddressSpouse['Telephone']);

            $provAddress = $this->loanapplication_model->getProvinceAddressSpouse($spouseId['SpouseId']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C31', $provAddress['HouseNo'] . ' ' . $provAddress['BrgyDesc']);
          }
          else
          {            
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H9', 'N/A');
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('J9', 'N/A');
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('N9', 'N/A');
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('Q9', 'N/A');

              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H11', 'N/A');
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('J11', 'N/A');

              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('N11', 'N/A');
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('P11', 'N/A');
            // spouse address
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H15', 'N/A');
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('O16', 'N/A');
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('P16', 'N/A');
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('O18', 'N/A');

              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C31', 'N/A');
          }

        // net income
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H26', number_format($details['BorrowerMonthlyIncome'], 2));
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('L26', number_format($details['SpouseMonthlyIncome'], 2));
          
          $totalIncome = $this->loanapplication_model->getHouseholdMoney($this->uri->segment(4), 'application_has_monthlyincome');
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('O26', number_format($totalIncome['Total'], 2));

          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('L27', floatval($details['BorrowerMonthlyIncome']) + floatval($details['SpouseMonthlyIncome']) + floatval($totalIncome['Total']));

          $totalExpense = $this->loanapplication_model->getHouseholdMoney($this->uri->segment(4), 'application_has_expense');
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('L28', number_format($totalExpense['Total'], 2));

          $totalObligation = $this->loanapplication_model->getHouseholdMoney($this->uri->segment(4), 'application_has_monthlyobligation');
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('L29', number_format($totalObligation['Total'], 2));

        // co maker
          $comaker = $this->loanapplication_model->getCoMaker($details['BorrowerId']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H33', $comaker['Name']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('L33', $comaker['DateOfBirth']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('O33', $comaker['PositionName']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H35', $comaker['Employer']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('L35', $comaker['TenureYear'] . 'yr ' . $comaker['TenureMonth'].' mts');
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('O35', $comaker['TelephoneNo']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H37', $comaker['BusinessAddress']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('O37', number_format($comaker['MonthlyIncome']));
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H40', $comaker['LoanUndertaking']);

        // Requierements
          $requirements = $this->loanapplication_model->getRequirementReport($this->uri->segment(4));
          $rowsss = 3;
          foreach ($requirements as $rows)
          {
            if($rowsss < 10)
            {
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue("R$rowss", $rows['Name']);
            }
            else
            {
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue("X$rowss", $rows['Name']);
            }
            $rowsss++;
          }

        // approvers
          $approvers = $this->loanapplication_model->getApproversReport($this->uri->segment(4));
          $rowsss = 13;
          foreach ($approvers as $rows2)
          {
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue("R$rowss", $rows2['Description']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue("W$rowss", $rows2['DateUpdated']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue("Y$rowss", $rows2['ProcessedBy']);
            $rowsss++;
          }


      }

      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="Loan Application'. $CurrentDate .'.xls"');
      header('Cache-Control: max-age=0');
      // $objWriter->save('php://output');

      $objWriter->save(APPPATH . 'excelforms/Loan Application'. $CurrentDate .'.xls');
    
      //file path
      $file = APPPATH . 'excelforms/Loan Application'. $CurrentDate .'.xls';

      force_download($file, NULL);
    }
  }

  
}
