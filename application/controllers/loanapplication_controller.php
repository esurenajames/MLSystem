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

	function getAuditLogs()
	{
		$result = $this->admin_model->getAuditLogs();
		echo json_encode($result);
	}


  function submitApplication()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $AssignedBranchId = $this->session->userdata('BranchId');
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

        'RiskLevel'                 => $_POST['RiskLevel'],
        'RiskAssessment'            => $_POST['RiskAssessment'],

        'PurposeId'                 => $_POST['PurposeId'],
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
      $branchCode = $this->maintenance_model->selectSpecific('R_Branches', 'BranchId', $borrowerDetail['BranchId']);
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
              'LoanAmount'            => $_POST['PrincipalAmount'],
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
                'PaymentMethod'     => 1,
                'IsInterest'        => 0,
                'IsOthers'          => 1,
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
              'CreatedBy'             => $EmployeeNumber,
              'StatusId'              => 5
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
              if($reqID['RequirementId'] == $_POST['RequirementId'][$count])
              {
                $set = array( 
                  'DateUpdated'       => $DateNow, 
                  'UpdatedBy'         => $EmployeeNumber, 
                  'StatusId'          => 7, 
                );
                $condition = array( 
                  'RequirementId'   => $reqID['RequirementId'],
                  'ApplicationId'   =>$generatedId['ApplicationId']
                );
                $table = 'application_has_requirements';
                $this->maintenance_model->updateFunction1($set, $condition, $table);

                $requirementFiles = $this->loanapplication_model->getSubmittedSupportDocuments($reqID['IdentificationId']);
                foreach ($requirementFiles as $files) 
                {
                  $insertData = array(
                    'ApplicationRequirementId'  => $generatedId2['ApplicationRequirementId'],
                    'Name'                      => $files['FileName'],
                    'Title'                     => $files['Attachment'],
                    'FileName'                  => $files['FileName'],
                    'StatusId'                  => 1,
                    'CreatedBy'                 => $EmployeeNumber
                  );
                  $auditTable = 'requirements_has_attachments';
                  $this->maintenance_model->insertFunction($insertData, $auditTable);
                }
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
    // borrower details
      // personal references
        $personalReferences = $this->borrower_model->getPersonalReferences($_POST['borrowerId']);
        if($personalReferences > 0)
        {
          foreach ($personalReferences as $value) 
          {
            $insertDataPR = array(
              'ApplicationId'         => $generatedId['ApplicationId'],
              'ReferenceId'           => $value['ReferenceId'],
              'StatusId'              => 1,
              'CreatedBy'             => $EmployeeNumber
            );
            $insertTablePR = 'application_has_personalreference';
            $this->maintenance_model->insertFunction($insertDataPR, $insertTablePR);
          }
        }
      // co maker
        $CoMaker = $this->borrower_model->getActiveCoMaker($_POST['borrowerId']);
        if($CoMaker > 0)
        {
          foreach ($CoMaker as $value) 
          {
            $insertDataPR = array(
              'ApplicationId'         => $generatedId['ApplicationId'],
              'BorrowerComakerId'     => $value['BorrowerComakerId'],
              'StatusId'              => 1,
              'CreatedBy'             => $EmployeeNumber
            );
            $insertTablePR = 'application_has_comaker';
            $this->maintenance_model->insertFunction($insertDataPR, $insertTablePR);
          }
        }
      // Spouse Info
        $Spouse = $this->borrower_model->getActiveSpouse($_POST['borrowerId']);
        if($Spouse > 0)
        {
          foreach ($Spouse as $value) 
          {
            $insertDataPR = array(
              'ApplicationId'         => $generatedId['ApplicationId'],
              'BorrowerSpouseId'      => $value['BorrowerSpouseId'],
              'StatusId'              => 1,
              'CreatedBy'             => $EmployeeNumber
            );
            $insertTablePR = 'application_has_spouse';
            $this->maintenance_model->insertFunction($insertDataPR, $insertTablePR);
          }
        }
      // Employer
        $Employment = $this->borrower_model->getActiveEmployment($_POST['borrowerId']);
        if($Employment > 0)
        {
          foreach ($Employment as $value) 
          {
            $insertDataPR = array(
              'ApplicationId'         => $generatedId['ApplicationId'],
              'EmployerId'            => $value['EmployerId'],
              'StatusId'              => 1,
              'CreatedBy'             => $EmployeeNumber
            );
            $insertTablePR = 'application_has_employer';
            $this->maintenance_model->insertFunction($insertDataPR, $insertTablePR);
          }
        }
      // contact info
        $Contact = $this->borrower_model->getActiveContact($_POST['borrowerId']);
        if($Contact > 0)
        {
          foreach ($Contact as $value) 
          {
            $insertDataPR = array(
              'ApplicationId'         => $generatedId['ApplicationId'],
              'BorrowerContactId'     => $value['BorrowerContactId'],
              'StatusId'              => 1,
              'CreatedBy'             => $EmployeeNumber
            );
            $insertTablePR = 'application_has_contact';
            $this->maintenance_model->insertFunction($insertDataPR, $insertTablePR);
          }
        }
      // address
        $Address = $this->borrower_model->getActiveAddress($_POST['borrowerId']);
        if($Address > 0)
        {
          foreach ($Address as $value) 
          {
            $insertDataPR = array(
              'ApplicationId'             => $generatedId['ApplicationId'],
              'BorrowerAddressHistoryId'  => $value['BorrowerAddressHistoryId'],
              'StatusId'                  => 1,
              'CreatedBy'                 => $EmployeeNumber
            );
            $insertTablePR = 'application_has_address';
            $this->maintenance_model->insertFunction($insertDataPR, $insertTablePR);
          }
        }
      // email
        $Email = $this->borrower_model->getActiveEmail($_POST['borrowerId']);
        if($Email > 0)
        {
          foreach ($Email as $value) 
          {
            $insertDataPR = array(
              'ApplicationId'             => $generatedId['ApplicationId'],
              'BorrowerEmailId'           => $value['BorrowerEmailId'],
              'StatusId'                  => 1,
              'CreatedBy'                 => $EmployeeNumber
            );
            $insertTablePR = 'application_has_email';
            $this->maintenance_model->insertFunction($insertDataPR, $insertTablePR);
          }
        }
      // education
        $Education = $this->borrower_model->getActiveEducation($_POST['borrowerId']);
        if($Education > 0)
        {
          foreach ($Education as $value) 
          {
            $insertDataPR = array(
              'ApplicationId'             => $generatedId['ApplicationId'],
              'BorrowerEducationId'       => $value['BorrowerEducationId'],
              'StatusId'                  => 1,
              'CreatedBy'                 => $EmployeeNumber
            );
            $insertTablePR = 'application_has_education';
            $this->maintenance_model->insertFunction($insertDataPR, $insertTablePR);
          }
        }
    // admin audits finals
      $transNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
      $auditLogsManager = 'Created loan application #'.$TransactionNumber.'.';
      $auditAffectedEmployee = 'Created loan application #'.$TransactionNumber.'.';
      $auditAffectedTable = 'Created loan application.';
      $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(3), 'application_has_notifications', 'ApplicationId');
    // notifications
      $this->session->set_flashdata('alertTitle','Success!'); 
      $this->session->set_flashdata('alertText','Successfully submitted loan application!'); 
      $this->session->set_flashdata('alertType','success'); 
    
    redirect('home/loandetail/' . $generatedId['ApplicationId']);
  }

  function restructureLoan()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");

    // application
      $ApplicationDetail = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
      if($_POST['PrincipalAmount'] != $ApplicationDetail['PrincipalAmount'])
      {
        // admin audits
          $transNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
          $auditApplication = 'Restructured loan amount from Php' . number_format($ApplicationDetail['PrincipalAmount'], 2) . ' to Php' . number_format($_POST['PrincipalAmount'], 2);
          $auditLogsManager = 'Restructured loan amount of application #'.$RefNo.' from Php' . number_format($ApplicationDetail['PrincipalAmount'], 2) . ' to Php' . number_format($_POST['PrincipalAmount'], 2). ' in application #' . $transNo['TransactionNumber'];
          $this->auditLoanApplication($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditApplication, $this->uri->segment(3), null);
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
        // admin audits
          $transNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
          $auditApplication = 'Restructured term type from ' . $ApplicationDetail['TermType'] . ' to ' . $_POST['TermType'];
          $auditLogsManager = 'Restructured term type from ' . $ApplicationDetail['TermType'] . ' to ' . $_POST['TermType'] . ' in application #' . $transNo['TransactionNumber'];
          $this->auditLoanApplication($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditApplication, $this->uri->segment(3), null);

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
        // admin audits
          $transNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
          $auditApplication = 'Restructured term number from ' . $ApplicationDetail['TermNo'] . ' to ' . $_POST['TermNumber'];
          $auditLogsManager = 'Restructured term number from ' . $ApplicationDetail['TermNo'] . ' to ' . $_POST['TermNumber']. ' in application #' . $transNo['TransactionNumber'];
          $this->auditLoanApplication($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditApplication, $this->uri->segment(3), null);
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
        // admin audits
          $transNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
          $auditApplication = 'Restructured repayment cycle from ' . $oldRepayment['Name'] . ' to ' . $newRepayment['Name'];
          $auditLogsManager = 'Restructured repayment cycle from ' . $oldRepayment['Name'] . ' to ' . $newRepayment['Name']. ' in application #' . $transNo['TransactionNumber'];
          $this->auditLoanApplication($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditApplication, $this->uri->segment(3), null);
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
        // admin audits
          $transNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
          $auditApplication = 'Restructured repayment number from ' . $ApplicationDetail['RepaymentNo'] . ' to ' . $_POST['RepaymentsNumber'];
          $auditLogsManager = 'Restructured repayment number from ' . $ApplicationDetail['RepaymentNo'] . ' to ' . $_POST['RepaymentsNumber']. ' in application #' . $transNo['TransactionNumber'];
          $this->auditLoanApplication($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditApplication, $this->uri->segment(3), null);
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
      // restructure amount
        // admin audits
          $transNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
          $auditApplication = 'Set '.number_format($_POST['RestructureFee'], 2).' as monthly re-structure payment.';
          $auditLogsManager = 'Set '.number_format($_POST['RestructureFee'], 2).' as monthly re-structure payment for application #'.$transNo['TransactionNumber'].'.';
          $this->auditLoanApplication($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditApplication, $this->uri->segment(3), null);
        // update
          $set = array( 
            'DateUpdated'       => $DateNow, 
            'UpdatedBy'         => $EmployeeNumber, 
            'RestructureFee'    => $_POST['RestructureFee'], 
            'ForRestructuring'  => 2, 
          );
          $condition = array( 
            'ApplicationId'   => $this->uri->segment(3),
          );
          $table = 'T_Application';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
    // interest
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
    // additional charges
      if(isset($_POST['ChargeNo']))
      {
        for($chargeRow = 0; $chargeRow < count($_POST['ChargeNo']); $chargeRow++)
        {
          if($_POST['IsSelected'][$chargeRow] == 1)
          {
            $insertData = array(
              'ApplicationId'         => $this->uri->segment(3),
              'ChargeId'              => $_POST['ChargeId'][$chargeRow],
              'LoanAmount'            => $_POST['PrincipalAmount'],
              'Amount'                => $_POST['chargeTotal'][$chargeRow],
              'StatusId'              => 2,
              'CreatedBy'             => $EmployeeNumber
            );
            $auditTable = 'application_has_charges';
            $this->maintenance_model->insertFunction($insertData, $auditTable);

            $charge = $this->maintenance_model->selectSpecific('R_Charges', 'ChargeId', $_POST['ChargeId'][$chargeRow]);

            // get generated application id
              $getData = array(
                'table'                 => 'application_has_charges'
                , 'column'              => 'ApplicationChargeId'
                , 'CreatedBy'           => $EmployeeNumber
              );
              $generatedId = $this->maintenance_model->getGeneratedId2($getData);
            // insert into payments
              $insertData1 = array( 
                'BankId'            => 1,
                'ApplicationId'     => $this->uri->segment(3),
                'Amount'            => $_POST['chargeTotal'][$chargeRow],
                'Description'       => 'Payment for ' . $charge['Name'],
                'AmountPaid'        => $_POST['chargeTotal'][$chargeRow],
                'ApplicationChargeId' => $generatedId['ApplicationChargeId'],
                'IsInterest'        => 0,
                'IsPrincipalCollection' => 0,
                'IsOthers'              => 1,
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
    // loan status code
      // // loan status
      //   $loanDetails = $this->maintenance_model->selectSpecific('t_application', 'ApplicationId', $_POST['ApplicationId']);
      //   if($loanDetails['ApprovalType'] != $_POST['ApprovalType'])
      //   {
      //     // update loan status
      //       $set1 = array( 
      //         'ApprovalType' => $_POST['ApprovalType']
      //       );
      //       $condition1 = array( 
      //         'ApplicationId' => $_POST['ApplicationId']
      //       );
      //       $table1 = 't_application';
      //       $this->maintenance_model->updateFunction1($set1, $condition1, $table1);
      //   }

      //   if($_POST['LoanStatusId'] == 1) // approved
      //   {
      //     // admin
      //       $oldStatusDesc = $this->maintenance_model->selectSpecific('application_has_status', 'loanStatusId', $loanDetails['StatusId']);
      //       $newtatusDesc = $this->maintenance_model->selectSpecific('application_has_status', 'loanStatusId', $_POST['LoanStatusId']);
      //       $transNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $_POST['ApplicationId']);
      //       $auditApplication = 'Restructured loan.';
      //       $auditLogsManager = 'Restructured loan application #'.$transNo['TransactionNumber'].'.';
      //       $this->auditLoanApplication($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditApplication, $_POST['ApplicationId']);
      //     // update loan status
      //       $set1 = array( 
      //         'StatusId' => $_POST['LoanStatusId']
      //         , 'ForRestructuring' => 3
      //       );
      //       $condition1 = array( 
      //         'ApplicationId' => $_POST['ApplicationId']
      //       );
      //       $table1 = 't_application';
      //       $this->maintenance_model->updateFunction1($set1, $condition1, $table1);
      //     // update approvers
      //       $set2 = array( 
      //         'StatusId' => 6 // deactivated
      //       );
      //       $condition2 = array( 
      //         'ApplicationId' => $_POST['ApplicationId']
      //       );
      //       $table2 = 'application_has_approver';
      //       $this->maintenance_model->updateFunction1($set2, $condition2, $table2);
      //     // additional charges
      //       if(isset($_POST['ChargeNo']))
      //       {
      //         for($chargeRow = 0; $chargeRow < count($_POST['ChargeNo']); $chargeRow++)
      //         {
      //           if($_POST['IsSelected'][$chargeRow] == 1)
      //           {
      //             $insertData = array(
      //               'ApplicationId'         => $_POST['ApplicationId'],
      //               'ChargeId'              => $_POST['ChargeId'][$chargeRow],
      //               'Amount'                => $_POST['chargeTotal'][$chargeRow],
      //               'StatusId'              => 2,
      //               'CreatedBy'             => $EmployeeNumber
      //             );
      //             $auditTable = 'application_has_charges';
      //             $this->maintenance_model->insertFunction($insertData, $auditTable);

      //             $charge = $this->maintenance_model->selectSpecific('R_Charges', 'ChargeId', $_POST['ChargeId'][$chargeRow]);

      //             // get generated application id
      //               $getData = array(
      //                 'table'                 => 'application_has_charges'
      //                 , 'column'              => 'ApplicationChargeId'
      //                 , 'CreatedBy'           => $EmployeeNumber
      //               );
      //               $generatedId = $this->maintenance_model->getGeneratedId2($getData);
      //             // insert into payments
      //               $insertData1 = array( 
      //                 'BankId'            => 1,
      //                 'ApplicationId'     => $_POST['ApplicationId'],
      //                 'Amount'            => $_POST['chargeTotal'][$chargeRow],
      //                 'Description'       => 'Payment for ' . $charge['Name'],
      //                 'AmountPaid'        => $_POST['chargeTotal'][$chargeRow],
      //                 'ApplicationChargeId' => $generatedId['ApplicationChargeId'],
      //                 'IsInterest'        => 0,
      //                 'IsPrincipalCollection' => 0,
      //                 'InterestAmount'    => 0,
      //                 'PrincipalAmount'   => 0,
      //                 'ChangeId'          => 1,
      //                 'ChangeAmount'      => 0,
      //                 'DateCollected'     => date("Y-m-d"),
      //                 'PaymentDate'       => date("Y-m-d"),
      //                 'CreatedBy'         => $EmployeeNumber
      //               );
      //               $table = 't_paymentsmade';
      //               $this->maintenance_model->insertFunction($insertData1, $table);
      //           }
      //         }
      //       }
      //   }
      //   if($_POST['LoanStatusId'] == 3) // for approval
      //   {
      //     // update loan status
      //       $set1 = array( 
      //         'StatusId' => $_POST['LoanStatusId']
      //         , 'ForRestructuring' => 1
      //       );
      //       $condition1 = array( 
      //         'ApplicationId' => $_POST['ApplicationId']
      //       );
      //       $table1 = 't_application';
      //       $this->maintenance_model->updateFunction1($set1, $condition1, $table1);
      //     // approvers
      //       if(isset($_POST['Approvers']))
      //       {
      //         $set1 = array(
      //           'StatusId' => 8 // parked
      //         );
      //         $condition1 = array(
      //           'ApplicationId' => $_POST['ApplicationId'],
      //           'StatusId' => 1,
      //         );
      //         $table1 = 'application_has_approver';
      //         $this->maintenance_model->updateFunction1($set1, $condition1, $table1);
      //         foreach ($_POST['Approvers'] as $value) 
      //         {          
      //           $insertData = array(
      //             'ApplicationId'         => $_POST['ApplicationId'],
      //             'ApproverNumber'        => $value,
      //             'StatusId'              => 5,
      //             'CreatedBy'             => $EmployeeNumber
      //           );
      //           $auditTable = 'application_has_approver';
      //           $this->maintenance_model->insertFunction($insertData, $auditTable);
      //         }
      //       }
      //     // additional charges
      //       if(isset($_POST['ChargeNo']))
      //       {
      //         $set1 = array(
      //           'StatusId' => 3 // deactivated
      //         );
      //         $condition1 = array(
      //           'ApplicationId' => $_POST['ApplicationId'],
      //           'StatusId' => 1, // pending
      //         );
      //         $table1 = 'application_has_charges';
      //         $this->maintenance_model->updateFunction1($set1, $condition1, $table1);
      //         for($chargeRow = 0; $chargeRow < count($_POST['ChargeNo']); $chargeRow++)
      //         {
      //           if($_POST['IsSelected'][$chargeRow] == 1)
      //           {
      //             $insertData = array(
      //               'ApplicationId'         => $_POST['ApplicationId'],
      //               'ChargeId'              => $_POST['ChargeId'][$chargeRow],
      //               'Amount'                => $_POST['chargeTotal'][$chargeRow],
      //               'StatusId'              => 1,
      //               'CreatedBy'             => $EmployeeNumber
      //             );
      //             $auditTable = 'application_has_charges';
      //             $this->maintenance_model->insertFunction($insertData, $auditTable);
      //           }
      //         }
      //       }
      //     // admin
      //       $oldStatusDesc = $this->maintenance_model->selectSpecific('application_has_status', 'loanStatusId', $loanDetails['StatusId']);
      //       $newtatusDesc = $this->maintenance_model->selectSpecific('application_has_status', 'loanStatusId', $_POST['LoanStatusId']);
      //       $transNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $_POST['ApplicationId']);
      //       $auditApplication = 'Applied loan for restructuring.';
      //       $auditLogsManager = 'Applied loan application #'.$transNo['TransactionNumber'].' for restructuring.';
      //       $this->auditLoanApplication($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditApplication, $_POST['ApplicationId']);
      //   }
    // notif
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
        'ApproverNumber'  => $EmployeeNumber,
        'StatusId'  => 5
      );
      $table = 'application_has_approver';
      $this->maintenance_model->updateFunction1($set, $condition, $table);

      $detail = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
      if($detail['ForRestructuring'] == 1) // for approval
      {
        // restructured
          $isRestructured = 2;
        // admin audits finals
          $transNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
          $auditLogsManager = 'Approved re-structuring for application #'.$transNo['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Approved re-structuring for application #'.$transNo['TransactionNumber'].'.';
          $auditAffectedTable = 'Approved re-structuring.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(3), 'application_has_notifications', 'ApplicationId');
      }
      else
      {
        // normal
          $isRestructured = 0;
        // admin audits finals
          $transNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
          $auditLogsManager = 'Approved application #'.$transNo['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Approved application #'.$transNo['TransactionNumber'].'.';
          $auditAffectedTable = 'Approved.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(3), 'application_has_notifications', 'ApplicationId');
      }

      $ProcessedApprovers = $this->loanapplication_model->getProcessedApprovers($this->uri->segment(3));
      $pendingApprovers = $this->loanapplication_model->getPendingApprovers($this->uri->segment(3));
      if($pendingApprovers['Total'] == $ProcessedApprovers['Total'])
      {
        $set = array( 
          'DateUpdated'   => $DateNow, 
          'DateApproved'  => $DateNow, 
          'ForRestructuring'  => $isRestructured, 
          'StatusId'      => 1
        );

        $condition = array( 
          'ApplicationId'   => $this->uri->segment(3)
        );

        $table = 't_application';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
        // set as overridden parked status
          $set = array( 
            'DateUpdated' => $DateNow,
            'StatusId'    => 9
          );
          $condition = array( 
            'ApplicationId'   => $this->uri->segment(3),
            'StatusId'  => 8
          );
          $table = 'application_has_approver';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      // tag as paid yung mga pending charges
        $pendingCharges = $this->loanapplication_model->getPendingCharges($this->uri->segment(3));
        foreach ($pendingCharges as $chargeId) 
        {
          // insert into payments
            $insertData1 = array(
              'BankId'            => 1,
              'ApplicationId'     => $this->uri->segment(3),
              'Amount'            => $chargeId['Amount'],
              'Description'       => 'Payment for ' . $chargeId['Name'],
              'AmountPaid'        => $chargeId['Amount'],
              'ApplicationChargeId'   => $chargeId['ApplicationChargeId'],
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
          // update pending charges
            $set = array( 
              'DateUpdated' => $DateNow, 
              'StatusId'    => 2
            );
            $condition = array( 
              'ApplicationId'   => $this->uri->segment(3),
              'StatusId'  => 1
            );
            $table = 'application_has_charges';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
    }
    else if($_POST['ApprovalType'] == 2) // disapprove
    {
      $detail = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
      if($detail['ForRestructuring'] == 1) // for restructuring disapproval
      {
        $isRestructured = 0; // normal
        $newStatus = 1; // approved

        $set1 = array( 
          'DateUpdated' => $DateNow, 
          'StatusId'    => 1
        );
        $condition1 = array( 
          'ApplicationId'   => $this->uri->segment(3),
          'StatusId'  => 8
        );

        $set2 = array(
          'DateUpdated' => $DateNow, 
          'StatusId'    => 6
        );
        $condition2 = array( 
          'ApplicationId'   => $this->uri->segment(3),
          'StatusId'  => 5
        );
        $this->maintenance_model->updateFunction1($set2, $condition2, $table1);
      }
      else // for normal disapproval
      {
        $isRestructured = 0;
        $newStatus = 2; // disapproved

        $set1 = array( 
          'DateUpdated' => $DateNow, 
          'StatusId'    => 4
        );
        $condition1 = array( 
          'ApplicationId'   => $this->uri->segment(3),
          'ApproverNumber'  => $EmployeeNumber,
          'StatusId'  => 5
        );
      }

      $table1 = 'application_has_approver';
      $this->maintenance_model->updateFunction1($set1, $condition1, $table1);

      $setApplication = array( 
        'DateUpdated'   => $DateNow, 
        'DateApproved'  => $DateNow, 
        'ForRestructuring'  => $isRestructured, 
        'StatusId'      => $newStatus
      );

      $conditionApplication = array( 
        'ApplicationId'   => $this->uri->segment(3)
      );

      $tableApplication = 't_application';
      $this->maintenance_model->updateFunction1($setApplication, $conditionApplication, $tableApplication);
      // admin audits finals
        $transNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
        $auditLogsManager = 'Disapproved application #'.$transNo['TransactionNumber'].'.';
        $auditAffectedEmployee = 'Disapproved application #'.$transNo['TransactionNumber'].'.';
        $auditAffectedTable = 'Disapproved.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(3), 'application_has_notifications', 'ApplicationId');
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
      // admin audits finals
        $TransactionNumber = 'CHG-' .sprintf('%06d', $_POST['ChargeId']);
        $loanDetail = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
        $auditLogsManager = 'Deactivated charge #'.$TransactionNumber . ' in charges tab ' .$loanDetail['TransactionNumber'].'.';
        $auditAffectedEmployee = 'Deactivated charge #'.$TransactionNumber . ' in charges tab ' .$loanDetail['TransactionNumber'].'.';
        $auditAffectedTable = 'Deactivated charge #'.$TransactionNumber . ' in charges tab.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $loanDetail['ApplicationId'], 'application_has_notifications', 'ApplicationId');
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
        // check if to restart
          $this->forRestart($this->uri->segment(3));
      // attachments
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
          }
          else
          {
              $fileName = "";
          }
        }

        $newFileName = '';
        $fileTitle = '';
        if($fileName != null || $fileName != '')
        {
          $newFileName = $fileName;
          $fileTitle = $Title;
        }
      // admin audits finals
        $TransactionNumber = 'PYM-' .sprintf('%06d', $_POST['ChargeId']);
        $loanDetail = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
        $auditLogsManager = 'Deactivated payment #'.$TransactionNumber . ' in collections tab ' .$loanDetail['TransactionNumber'].'.';
        $auditAffectedEmployee = 'Deactivated payment #'.$TransactionNumber . ' in collections tab ' .$loanDetail['TransactionNumber'].'.';
        $auditAffectedTable = 'Deactivated payment #'.$TransactionNumber . ' in collections tab.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $loanDetail['ApplicationId'], 'application_has_notifications', 'ApplicationId');

        // get generated application id
          $getData2 = array(
            'table'                 => 'application_has_notifications'
            , 'column'              => 'NotificationId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId2 = $this->maintenance_model->getGeneratedId2($getData2);
        // update function
          $set = array( 
            'Remarks' => htmlentities($_POST['Description'], ENT_QUOTES),
            'FileName'                  => htmlentities($newFileName, ENT_QUOTES),
            'FileTitle'                 => htmlentities($fileTitle, ENT_QUOTES)
          );
          $condition = array( 
            'NotificationId' => $generatedId2['NotificationId']
          );
          $table = 'application_has_notifications';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
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
    $query = $this->loanapplication_model->countExpense($data);
    if($query == 0) // not existing
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
        // get generated application id
          $getData = array(
            'table'                 => 'Application_has_Comments'
            , 'column'              => 'CommentId'
            , 'CreatedBy'           => $EmployeeNumber
          );
          $generatedId = $this->maintenance_model->getGeneratedId2($getData);
        // admin audits finals
          $ApplicationDetail = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
          $TransactionNumber = 'COM-'.sprintf('%06d', $generatedId['CommentId']);
          $auditLogsManager = 'Added comment #'.$TransactionNumber.' in monthly comments for application #'.$ApplicationDetail['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Added comment #'.$TransactionNumber.' in comments tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
          $auditAffectedTable = 'Added comment #'.$TransactionNumber.' in comments tab.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $ApplicationDetail['ApplicationId'], 'application_has_notifications', 'ApplicationId');

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
          $detail = $this->maintenance_model->selectSpecific('application_has_monthlyobligation', 'MonthlyObligationId', $generatedId['MonthlyObligationId']);
        // admin audits finals
          $TransactionNumber = 'OBL-'.sprintf('%06d', $generatedId['MonthlyObligationId']);
          $auditLogsManager = 'Added monthly obligation #'.$TransactionNumber.' in monthly obligations tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Added monthly obligation #'.$TransactionNumber.' in monthly obligations tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
          $auditAffectedTable = 'Added monthly obligation #'.$TransactionNumber.' in monthly obligations tab.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $ApplicationDetail['ApplicationId'], 'application_has_notifications', 'ApplicationId');
          $this->forRestart($this->uri->segment(3));
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Monthly obligation successfully Added!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/loandetail/'. $this->uri->segment(3));
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Monthly obligation already existing!'); 
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
        // admin audits finals
          $detail = $this->maintenance_model->selectSpecific('application_has_monthlyobligation', 'MonthlyObligationId', $_POST['MonthlyObligationId']);
          $TransactionNumber = 'OBL-'.sprintf('%06d', $detail['MonthlyObligationId']);
          $auditLogsManager = 'Updated monthly obligation #'.$TransactionNumber.' in monthly obligations tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Updated monthly obligation #'.$TransactionNumber.' in monthly obligations tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
          $auditAffectedTable = 'Updated monthly obligation #'.$TransactionNumber.' in monthly obligations tab.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $ApplicationDetail['ApplicationId'], 'application_has_notifications', 'ApplicationId');
        // notif
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Monthly obligation details successfully updated!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/loandetail/'. $this->uri->segment(3));
      }
    }
    else // if existing
    {
      // notif
      $this->session->set_flashdata('alertTitle','Warning!'); 
      $this->session->set_flashdata('alertText','Monthly obligation details already existing!'); 
      $this->session->set_flashdata('alertType','warning'); 
      redirect('home/loandetail/'. $this->uri->segment(3));
    }
  }

  function AddExpense()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $ApplicationDetail = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
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
          $detail = $this->maintenance_model->selectSpecific('application_has_Expense', 'ExpenseId', $generatedId['ExpenseId']);
        // admin audits finals
          $TransactionNumber = 'EXP-'.sprintf('%06d', $generatedId['ExpenseId']);
          $auditLogsManager = 'Added monthly expense #'.$TransactionNumber.' in monthly expenses tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Added monthly expense #'.$TransactionNumber.' in monthly expenses tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
          $auditAffectedTable = 'Added monthly expense #'.$TransactionNumber.' in monthly expenses tab.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $ApplicationDetail['ApplicationId'], 'application_has_notifications', 'ApplicationId');
          $this->forRestart($this->uri->segment(3));
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
        $detail = $this->loanapplication_model->getExpenseDetails($_POST['ExpenseId']);
        if($ExpenseDetail['Source'] != htmlentities($_POST['Expense'], ENT_QUOTES))
        {
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
        // admin audits finals
          $TransactionNumber = 'EXP-'.sprintf('%06d', $detail['ExpenseId']);
          $auditLogsManager = 'Updated monthly expense #'.$TransactionNumber.' in monthly expenses tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Updated monthly expense #'.$TransactionNumber.' in monthly expenses tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
          $auditAffectedTable = 'Updated monthly expense #'.$TransactionNumber.' in monthly expenses tab.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $ApplicationDetail['ApplicationId'], 'application_has_notifications', 'ApplicationId');
        // notif
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Monthly expense details successfully updated!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/loandetail/'. $this->uri->segment(3));
      }
    }
  }

  function Addincome()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $ApplicationDetail = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
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
          $IncomeDetail = $this->maintenance_model->selectSpecific('application_has_monthlyIncome', 'IncomeId', $generatedId['IncomeId']);
        // admin audits finals
          $TransactionNumber = 'INC-'.sprintf('%06d', $IncomeDetail['IncomeId']);
          $auditLogsManager = 'Added other source of income #'.$TransactionNumber.' in other sources of income tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Added other source of income #'.$TransactionNumber.' in other sources of income tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
          $auditAffectedTable = 'Added other source of income #'.$TransactionNumber.' in other sources of income tab.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $ApplicationDetail['ApplicationId'], 'application_has_notifications', 'ApplicationId');
          $this->forRestart($this->uri->segment(3));
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Other source of income successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/loandetail/'. $this->uri->segment(3));
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Other source of income already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/loandetail'. $this->uri->segment(3));
      }
    }
    else if($_POST['FormType'] == 2) // edit Income Details 
    {
      $IncomeDetail = $this->loanapplication_model->getIncomeDetails($_POST['IncomeId']);
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
        // admin audits finals
          $TransactionNumber = 'INC-'.sprintf('%06d', $IncomeDetail['IncomeId']);
          $auditLogsManager = 'Updated other source of income #'.$TransactionNumber.' in other sources of income tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Updated other source of income #'.$TransactionNumber.' in other sources of income tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
          $auditAffectedTable = 'Updated other source of income #'.$TransactionNumber.' in other sources of income tab.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $ApplicationDetail['ApplicationId'], 'application_has_notifications', 'ApplicationId');
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
            , 'DisbursedBy'               => htmlentities($_POST['disbursedThrough'], ENT_QUOTES)
            , 'StatusId'                  => 1
            , 'CreatedBy'                 => $EmployeeNumber
            , 'UpdatedBy'                 => $EmployeeNumber
            , 'DateCreated'               => $DateNow
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
        // admin audits finals
          $loanDetails = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
          $TransactionNumber = 'DB-'.sprintf('%06d', $generatedId['DisbursementId']);
          $auditLogsManager = 'Added disbursement #'.$TransactionNumber.' in disbursements tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Added disbursement #'.$TransactionNumber.' in disbursements tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Added disbursement #'.$TransactionNumber.' in disbursements tab.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $loanDetails['ApplicationId'], 'application_has_notifications', 'ApplicationId');
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
    $RequirementDetail = $this->loanapplication_model->getRequirementDetails($_POST['Requirements']);
    $loanDetails = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
    $DateNow = date("Y-m-d H:i:s");
    if ($_POST['FormType'] == 1) // add Requirement
    {
      $data = array(
        'RequirementId'                 => htmlentities($_POST['Requirements'], ENT_QUOTES)
        , 'ApplicationId'               => $this->uri->segment(3)
      );
      $query = $this->loanapplication_model->countRequirement($data);
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
        // admin audits finals
          $generatedIdData1 = array(
            'table'                 => 'application_has_requirements'
            , 'column'              => 'ApplicationRequirementId'
          );
          $newId = $this->maintenance_model->getGeneratedId($generatedIdData1);
          $TransactionNumber = 'REQ-'.sprintf('%06d', $newId['ApplicationRequirementId']);
          $auditLogsManager = 'Added requirement #'.$TransactionNumber.' in requirements tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Added requirement #'.$TransactionNumber.' in requirements tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Added requirement #'.$TransactionNumber.' in requirements tab.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(3), 'application_has_notifications', 'ApplicationId');
        // check if to restart
            $this->forRestart($this->uri->segment(3));
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
          redirect('home/loandetail/'. $this->uri->segment(3));
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
    $path = './borrowerarchive/';
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
        // admin audits finals
          $RequirementDetail = $this->loanapplication_model->getRequirementDetails2($_POST['ApplicationRequirementId']);
          $TransactionNumber = 'REQ-'.sprintf('%06d', $_POST['ApplicationRequirementId']);
          $auditLogsManager = 'Uploaded document for requirement #'.$TransactionNumber.' in requirements tab for application #'.$RequirementDetail['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Uploaded document for #'.$TransactionNumber.' in requirements tab for application #'.$RequirementDetail['TransactionNumber'].'.';
          $auditAffectedTable = 'Uploaded document for #'.$TransactionNumber.' in requirements tab.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $RequirementDetail['ApplicationId'], 'application_has_notifications', 'ApplicationId');
          $this->forRestart($this->uri->segment(3));
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
          'BranchId'             => $this->session->userdata('BranchId'),
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
      // admin audits finals
        $ApplicationDetail = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
        $TransactionNumber = 'CLR-'.sprintf('%06d', $generatedId['CollateralId']);
        $auditLogsManager = 'Added collateral #'.$TransactionNumber.' in collaterals tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
        $auditAffectedEmployee = 'Added collateral #'.$TransactionNumber.' in collaterals tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
        $auditAffectedTable = 'Added collateral #'.$TransactionNumber.' in collaterals tab.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(3), 'application_has_notifications', 'ApplicationId');
      // insert collateral into loan
        $insertData2 = array(
          'CollateralId'         => $generatedId['CollateralId'],
          'ApplicationId'        => $this->uri->segment(3),
          'CreatedBy'            => $EmployeeNumber
        );
        $auditTable2 = 'application_has_Collaterals';
        $this->maintenance_model->insertFunction($insertData2, $auditTable2);
        $this->forRestart($this->uri->segment(3));

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
      // admin audits finals
        $appId = $this->maintenance_model->selectSpecific('application_has_collaterals', 'CollateralId', $_POST['CollateralId']);
        $ApplicationDetail = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $appId['ApplicationId']);
        $TransactionNumber = 'CLR-'.sprintf('%06d', $_POST['CollateralId']);
        $auditLogsManager = 'Updated collateral details #'.$TransactionNumber.' in collaterals tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
        $auditAffectedEmployee = 'Updated collateral details #'.$TransactionNumber.' in collaterals tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
        $auditAffectedTable = 'Updated collateral details #'.$TransactionNumber.' in collaterals tab.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $appId['ApplicationId'], 'application_has_notifications', 'ApplicationId');
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
    $loanDetails = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $Id);
    // insert into charges
      $insertData = array(
        'ApplicationId'             => $Id,
        'ChargeId'                  => $_POST['ChargeId'],
        'LoanAmount'                => $loanDetails['PrincipalAmount'],
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
      $charge = $this->maintenance_model->selectSpecific('R_Charges', 'ChargeId', $_POST['ChargeId']);
      // insert into payments
        $insertData1 = array( 
          'BankId'            => 1,
          'ApplicationId'     => $Id,
          'Amount'            => $_POST['chargeTotal'],
          'Description'       => 'Payment for ' . $charge['Name'],
          'AmountPaid'        => $_POST['chargeTotal'],
          'IsInterest'        => 0,
          'IsOthers'          => 1,
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
    // admin audits finals
      $TransactionNumber = 'CHG-'.sprintf('%06d', $generatedId['ApplicationChargeId']);
      $auditLogsManager = 'Added charge #'.$TransactionNumber.' in charge tab for application #'.$loanDetails['TransactionNumber'].'.';
      $auditAffectedEmployee = 'Added charge #'.$TransactionNumber.' in charge tab for application #'.$loanDetails['TransactionNumber'].'.';
      $auditAffectedTable = 'Added charge #'.$TransactionNumber.' in charges tab.';
      $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $Id, 'application_has_notifications', 'ApplicationId');
    // check if to restart
        $this->forRestart($this->uri->segment(3));

      $this->session->set_flashdata('alertTitle','Success!'); 
      $this->session->set_flashdata('alertText','Successfully added charge to loan!'); 
      $this->session->set_flashdata('alertType','success');
      redirect('home/loandetail/'. $this->uri->segment(3));
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

  function getDisbursementDetails()
  {
    $output = $this->loanapplication_model->getDisbursementDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getPenaltyPaymentDetails()
  {
    $output = $this->loanapplication_model->getPenaltyPaymentDetails($this->input->post('Id'));
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
          'ChangeMethod'      => $_POST['ChangeMethod'],
          'PaymentMethod'     => $_POST['PaymentMethod'],
          'BankId'            => $_POST['BankId'],
          'Remarks'           => htmlentities($_POST['Remarks'], ENT_QUOTES),
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
        // admin audits finals
          $ApplicationDetail = $this->maintenance_model->selectSpecific('t_application', 'ApplicationId', $this->uri->segment(3));
          $TransactionNumber = 'PLT-'.sprintf('%06d', $generatedId2['ApplicationPenaltyId']);
          $auditLogsManager = 'Added penalty #'.$TransactionNumber.' in penalty tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Added penalty #'.$TransactionNumber.' in penalty tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
          $auditAffectedTable = 'Added penalty #'.$TransactionNumber.' in penalty tab.';
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $ApplicationDetail['ApplicationId'], 'application_has_notifications', 'ApplicationId');

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
            'PaymentMethod'     => $_POST['PaymentMethod'],
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
            'PaymentMethod'     => $_POST['PaymentMethod'],
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

          // admin audits finals
            $ApplicationDetail = $this->maintenance_model->selectSpecific('t_application', 'ApplicationId', $this->uri->segment(3));
            $TransactionNumber = 'PLT-'.sprintf('%06d', $generatedId2['ApplicationPenaltyId']);
            $auditLogsManager = 'Added penalty #'.$TransactionNumber.' in penalty tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
            $auditAffectedEmployee = 'Added penalty #'.$TransactionNumber.' in penalty tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
            $auditAffectedTable = 'Added penalty #'.$TransactionNumber.' in penalty tab.';
            $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $ApplicationDetail['ApplicationId'], 'application_has_notifications', 'ApplicationId');
        }
      // admin audits finals
        $ApplicationDetail = $this->maintenance_model->selectSpecific('t_application', 'ApplicationId', $this->uri->segment(3));
        $TransactionNumber = 'PYM-'.sprintf('%06d', $generatedId['PaymentMadeId']);
        $auditLogsManager = 'Added payment #'.$TransactionNumber.' in collections tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
        $auditAffectedEmployee = 'Added payment #'.$TransactionNumber.' in collections tab for application #'.$ApplicationDetail['TransactionNumber'].'.';
        $auditAffectedTable = 'Added payment #'.$TransactionNumber.' in collections tab.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $ApplicationDetail['ApplicationId'], 'application_has_notifications', 'ApplicationId');
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

  function auditLoanApplication($auditLogsManager, $auditAffectedEmployee, $ManagerId, $AffectedEmployeeNumber, $auditLoanDets ,$ApplicationId, $Remarks)
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
    if($this->uri->segment(3) == 3) // loan application
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
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A29', $details['Birthplace']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C25', $details['FirstName']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('E25', $details['MiddleName']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('G25', $details['ExtName']);

          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A27', $details['ReportDOB']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C27', $details['CivilStatus']);

          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('E27', $details['Dependents']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('F27', $details['EmailAddress']);

          $cityAddress = $this->loanapplication_model->getCityAddress($details['BorrowerId']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A31', $cityAddress['HouseNo'] . ', ' . $cityAddress['brgyDesc'] . ', ' . $cityAddress['provDesc'] . ', ' . $cityAddress['regDesc']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('E32', $cityAddress['YearsStayed']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('F32', $cityAddress['MonthsStayed']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('E34', $cityAddress['Telephone']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C34', $cityAddress['ContactNumber']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C35', $cityAddress['AddressType'] . ' : ' . $cityAddress['NameOfLandlord']);

          $provBorrowerAddress = $this->loanapplication_model->getProvinceAddress($details['BorrowerId']);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C31', $provBorrowerAddress['HouseNo'] . ', ' . $provBorrowerAddress['brgyDesc'] . ', ' . $provBorrowerAddress['provDesc'] . ', ' . $provBorrowerAddress['regDesc']);

        // present employer
          $presentEmployer = $this->loanapplication_model->getEmployer($details['BorrowerId'], 1);
          if(isset($presentEmployer['EmployerName']))
          {
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A38', $presentEmployer['EmployerName']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('D38', $presentEmployer['BusinessAddress']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A41', $presentEmployer['Position']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C41', $presentEmployer['DateHired']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('E41', $presentEmployer['TelephoneNumber']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('G41', $presentEmployer['TenureYear'] . 'yr ' . $presentEmployer['TenureMonth'] . ' mts');
          }
          else
          {
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A38', 'N/A');
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('D38', 'N/A');
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A41', 'N/A');
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C41', 'N/A');
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('E41', 'N/A');
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('G41', 'N/A');
          }

        // previous employer
          $prevEmployer = $this->loanapplication_model->getEmployer($details['BorrowerId'], 2);
          if(isset($prevEmployer['EmployerName']))
          {
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A43', $prevEmployer['EmployerName']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('D43', $prevEmployer['BusinessAddress']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A46', $prevEmployer['Position']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C46', $prevEmployer['DateHired']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('E46', $prevEmployer['TelephoneNumber']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('G46', $prevEmployer['TenureYear'] . 'yr ' . $prevEmployer['TenureMonth'] . ' mts');
          }
          else
          {
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A43', 'N/A');
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('D43', 'N/A');
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('A46', 'N/A');
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('C46', 'N/A');
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('E46', 'N/A');
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('G46', 'N/A');
          }

        // Personal References
          $personalRef = $this->loanapplication_model->getReferences($details['BorrowerId']);
          $rowss = 3;
          if($rowss <= 4)
          {
            foreach ($personalRef as $row)
            {
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue("H$rowss", $row['Name']);
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue("K$rowss", $row['Address']);
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue("P$rowss", $row['ContactNumber']);
              $rowss++;
            }
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
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H13', $spouseDetails['Birthplace']);
          // spouse address
            $cityAddressSpouse = $this->loanapplication_model->getCityAddressSpouse($spouseId['SpouseId']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H15', $cityAddressSpouse['HouseNo'] . ' ' . $cityAddressSpouse['BrgyDesc']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('O16', $cityAddressSpouse['YearsStayed']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('P16', $cityAddressSpouse['MonthsStayed']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('O18', $cityAddressSpouse['Telephone']);

            $provAddress = $this->loanapplication_model->getProvinceAddressSpouse($spouseId['SpouseId']);
            if(isset($provAddress['HouseNo']))
            {
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('K15', $provAddress['HouseNo'] . ' ' . $provAddress['BrgyDesc']);
            }
            else
            {
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('K15', 'N/A');
            }


            // present employer
              $presentSpouseEmployer = $this->loanapplication_model->getSpouseEmployer($spouseId['SpouseId'], 1);
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H20', $presentSpouseEmployer['EmployerName']);
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('K20', $presentSpouseEmployer['BusinessAddress']);
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H23', $presentSpouseEmployer['Position']);
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('L23', $presentSpouseEmployer['DateHired']);
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('O23', $presentSpouseEmployer['TelephoneNumber']);
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('Q23', $presentSpouseEmployer['TenureYear'] . 'yr ' . $presentSpouseEmployer['TenureMonth'] . ' mts');
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

              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('K15', 'N/A');
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H20', 'N/A');
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('K20', 'N/A');
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H23', 'N/A');
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('L23', 'N/A');
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('O23', 'N/A');
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('Q23', 'N/A');
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

          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('L30', floatval($details['BorrowerMonthlyIncome']) + floatval($details['SpouseMonthlyIncome']) + floatval($totalIncome['Total']) - (floatval($totalObligation['Total']) + floatval($totalExpense['Total'])));

        // co maker
          $comaker = $this->loanapplication_model->getCoMaker($details['BorrowerId']);
          if(isset($comaker['Name']))
          {
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H33', $comaker['Name']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('L33', $comaker['DateOfBirth']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('O33', $comaker['PositionName']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H35', $comaker['Employer']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('L35', $comaker['TenureYear'] . 'yr ' . $comaker['TenureMonth'].' mts');
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('O35', $comaker['TelephoneNo']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H37', $comaker['BusinessAddress']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue('O37', number_format($comaker['MonthlyIncome']));
          }
          else
          {            
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H33', 'N/A');
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('L33', 'N/A');
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('O33', 'N/A');
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H35', 'N/A');

              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('L35', 'N/A');
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('O35', 'N/A');

              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H37', 'N/A');
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue('O37', 'N/A');
          }

        // loan undertaking
          $LoanUndertaking = $this->maintenance_model->selectSpecific('r_loanundertaking', 'StatusId', 1);
          $objPHPExcel->setActiveSheetIndex($index)->setCellValue('H40', $LoanUndertaking['Description']);

        // Requierements
          $requirements = $this->loanapplication_model->getRequirementReport($this->uri->segment(4));
          $reqRow = 3;
          foreach ($requirements as $reqRowData)
          {
            if($reqRow < 10)
            {
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue("R$reqRow", $reqRowData['Name']);
            }
            else
            {
              $objPHPExcel->setActiveSheetIndex($index)->setCellValue("X$reqRow", $reqRowData['Name']);
            }
            $reqRow++;
          }

        // approvers
          $approvers = $this->loanapplication_model->getApproversReport($this->uri->segment(4));
          $approverRow = 13;
          foreach ($approvers as $rows2)
          {
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue("R$approverRow", $rows2['Description']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue("W$approverRow", $rows2['DateUpdated']);
            $objPHPExcel->setActiveSheetIndex($index)->setCellValue("Y$approverRow", $rows2['ProcessedBy']);
            $approverRow++;
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
    if($this->uri->segment(3) == 4) // demographics
    {
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

        <p>Historical Data on Borrowers of the Company<br><small>'.$branchName['Name'].' Branch</small></p>

        <br>
        <br>
        ';
          $years = $this->loanapplication_model->getYearFilter2('r_borrowers', $_POST['YearFrom'], $_POST['YearTo']);
          $totalColumns = count($years) + 1;
          $blankColumns = count($years) + 2;
          // for ($i=0; $i < count($years); $i++) { 
          //   $html .='<td>'.$years[$i]['Year'].'</td>';
          // }
        $html .='
        <table>
          <tbody>';
          $html .= '
          <tr>
            <td>Year</td>';
            foreach ($years as $value) 
            {
              $html .='<td>'.$value['Year'].'</td>';
            }
        $html .='
          </tr>

          <tr>
          <td colspan="'.$totalColumns.'">a. Demographics</td>
          </tr>
          <tr>
            <td colspan="'.$totalColumns.'">i. Age</td>';
          $html .= '
          </tr>
          <tr>
            <td>* 18 - 24 years old</td>';
            foreach ($years as $value) 
            {
              $result = $this->loanapplication_model->getAge($value['Year'], 'YEAR(CURDATE()) - YEAR(DateOfBirth) BETWEEN 18 AND 24');
              $html .='<td>'.$result['TotalBorrowers'].'</td>';
            }
          $html .= '
          </tr>
          <tr>
            <td>* 25 - 31 years old</td>';
            foreach ($years as $value) 
            {
              $result = $this->loanapplication_model->getAge($value['Year'], 'YEAR(CURDATE()) - YEAR(DateOfBirth) BETWEEN 25 AND 31');
              $html .='<td>'.$result['TotalBorrowers'].'</td>';
            }
          $html .= '
          </tr>
          <tr>
            <td>* 32 - 39 years old</td>';
            foreach ($years as $value) 
            {
              $result = $this->loanapplication_model->getAge($value['Year'], 'YEAR(CURDATE()) - YEAR(DateOfBirth) BETWEEN 32 AND 39');
              $html .='<td>'.$result['TotalBorrowers'].'</td>';
            }
          $html .= '
          </tr>
          <tr>
            <td>* 40 - 47 years old</td>';
            foreach ($years as $value) 
            {
              $result = $this->loanapplication_model->getAge($value['Year'], 'YEAR(CURDATE()) - YEAR(DateOfBirth) BETWEEN 40 AND 47');
              $html .='<td>'.$result['TotalBorrowers'].'</td>';
            }
          $html .= '
          </tr>
          <tr>
            <td>* 48 - 55 years old</td>';
            foreach ($years as $value) 
            {
              $result = $this->loanapplication_model->getAge($value['Year'], 'YEAR(CURDATE()) - YEAR(DateOfBirth) BETWEEN 48 AND 55');
              $html .='<td>'.$result['TotalBorrowers'].'</td>';
            }
          $html .= '
          </tr>
          <tr>
            <td>* 56 - 65 years old</td>';
            foreach ($years as $value) 
            {
              $result = $this->loanapplication_model->getAge($value['Year'], 'YEAR(CURDATE()) - YEAR(DateOfBirth) BETWEEN 56 AND 65');
              $html .='<td>'.$result['TotalBorrowers'].'</td>';
            }
          $html .= '
          </tr>
          <tr>
            <td>* Above 65 years old</td>';
            foreach ($years as $value) 
            {
              $result = $this->loanapplication_model->getAge($value['Year'], 'YEAR(CURDATE()) - YEAR(DateOfBirth) > 65');
              $html .='<td>'.$result['TotalBorrowers'].'</td>';
            }
          $html .= '
          </tr>
          <tr>
          <td colspan="'.$blankColumns.'"> </td>
          </tr>
          <tr>
            <td colspan="'.$totalColumns.'">ii. Education</td>';
          $html .= '
          </tr>';
            $education = $this->loanapplication_model->getEducation();
            foreach ($education as $educationValues) 
            {
              $html .='<tr>';
              $html .='<td>* '.$educationValues['Name'].'</td>';
                foreach ($years as $yearlyValue) 
                {
                  $results = $this->loanapplication_model->getEducationYearly($yearlyValue['Year'], $educationValues['EducationId']);
                  $html .='<td>'.$results['TotalBorrowers'].'</td>';
                }
              $html .='</tr>';
            }
          $html .= '
          <tr>
          <td colspan="'.$blankColumns.'"> </td>
          </tr>
          <tr>
            <td colspan="'.$totalColumns.'">iii. Gender/Sex</td>';
          $html .= '
          </tr>';
            $sex = $this->loanapplication_model->getSex();
            foreach ($sex as $sexValues) 
            {
              $html .='<tr>';
              $html .='<td>* '.$sexValues['Name'].'</td>';
                foreach ($years as $yearlyValue)
                {
                  $sexResult = $this->loanapplication_model->getSexYearly($yearlyValue['Year'], $sexValues['SexId']);
                  $html .='<td>'.$sexResult['TotalBorrowers'].'</td>';
                }
              $html .='</tr>';
            }
          $html .= '
          <tr>
          <td colspan="'.$blankColumns.'"> </td>
          </tr>
          <tr>
            <td colspan="'.$totalColumns.'">iv. Occupation</td>';
          $html .= '
          </tr>';
            $occupation = $this->loanapplication_model->getOccupation();
            foreach ($occupation as $occupationValues) 
            {
              $html .='<tr>';
              $html .='<td>* '.$occupationValues['Name'].'</td>';
                foreach ($years as $yearlyValue)
                {
                  $occupationResult = $this->loanapplication_model->getOccupationYearly($yearlyValue['Year'], $occupationValues['Id']);
                  $html .='<td>'.$occupationResult['TotalBorrowers'].'</td>';
                }
              $html .='</tr>';
            }
          $html .= '
          <tr>
          <td colspan="'.$blankColumns.'"> </td>
          </tr>
          <tr>
            <td colspan="'.$totalColumns.'">v. Income Level</td>';
          $html .= '
          </tr>';
            $income = $this->loanapplication_model->getIncomeLevelPopulation();
            foreach ($income as $incomeValues) 
            {
              $html .='<tr>';
              $html .='<td>* '.$incomeValues['IncomeLevel'].'</td>';
                foreach ($years as $yearlyValue)
                {
                  $incomeResult = $this->loanapplication_model->getIncomeReport($yearlyValue['Year'], ' < 9250');
                  $html .='<td>'.$incomeResult['TotalBorrowers'].'</td>';
                }
              $html .='</tr>';
            }
          $html .= '
          <tr>
          <td colspan="'.$blankColumns.'"> </td>
          </tr>
          <tr>
            <td colspan="'.$totalColumns.'">vi. Marital Status</td>';
          $html .= '
          </tr>';
            $sex = $this->loanapplication_model->getMaitalStatus();
            foreach ($sex as $sexValues) 
            {
              $html .='<tr>';
              $html .='<td>* '.$sexValues['Name'].'</td>';
                foreach ($years as $yearlyValue)
                {
                  $sexResult = $this->loanapplication_model->getMaitalStatusYearly($yearlyValue['Year'], $sexValues['Id']);
                  $html .='<td>'.$sexResult['TotalBorrowers'].'</td>';
                }
              $html .='</tr>';
            }
          $html .= '
          <tr>
          <td colspan="'.$blankColumns.'"> </td>
          </tr>
          <tr>
            <td colspan="'.$totalColumns.'">b. Risk Profile of Borrowers</td>';
          $html .= '
          </tr>';
          $html .='<tr>';
          $html .='<td>* Low Risk</td>';
            foreach ($years as $yearlyValue)
            {
              $sexResult = $this->loanapplication_model->getRiskStatus($yearlyValue['Year'], 'Low Risk');
              $html .='<td>'.$sexResult['TotalBorrowers'].'</td>';
            }
          $html .='</tr>';
          $html .='<tr>';
          $html .='<td>* Medium Risk</td>';
            foreach ($years as $yearlyValue)
            {
              $sexResult = $this->loanapplication_model->getRiskStatus($yearlyValue['Year'], 'Medium Risk');
              $html .='<td>'.$sexResult['TotalBorrowers'].'</td>';
            }
          $html .='</tr>';
          $html .='<tr>';
          $html .='<td>* High Risk</td>';
            foreach ($years as $yearlyValue)
            {
              $sexResult = $this->loanapplication_model->getRiskStatus($yearlyValue['Year'], 'High Risk');
              $html .='<td>'.$sexResult['TotalBorrowers'].'</td>';
            }
          $html .='</tr>';
          $html .= '
          <tbody>
        </table>
        <br><br>
        <br><br>
      ';
      $pdf->writeHTML($html, true, false, true, false, '');

      // Close and output PDF document
      // $pdf->Output('Borrower Data.pdf', 'I');
      $pdf->Output('Borrower Data.pdf', 'D');
    }
    if($this->uri->segment(3) == 5) // loans
    {
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

        <p>Historical Data on Loans Extended by the Company<br><small>'.$branchName['Name'].' Branch</small></p>

        <br>
        <br>
        ';
          $years = $this->loanapplication_model->getLoansYear2($_POST['YearFrom'], $_POST['YearTo']);
          $totalColumns = count($years) + 1;
          $blankColumns = count($years) + 2;
          // for ($i=0; $i < count($years); $i++) { 
          //   $html .='<td>'.$years[$i]['Year'].'</td>';
          // }
        $html .='
        <table>
          <tbody>';
          $html .= '
          <tr>
            <td>Year</td>';
            foreach ($years as $value) 
            {
              $html .='<td>'.$value['Year'].'</td>';
            }
        $html .='</tr>';
        $html .='<tr>';
        $html .='<td>a. Total Number of Borrowers</td>';
          foreach ($years as $yearlyValue)
          {
            $result = $this->loanapplication_model->getTotalBorrowers($yearlyValue['Year']);
            $html .='<td>'.$result['TotalBorrowers'].'</td>';
          }
        $html .='</tr>';
        // $html .='
        //   <tr>
        //   <td colspan="'.$blankColumns.'"> </td>
        //   </tr>';
          $html .='<tr>';
          $html .='<td>b. Total Number of Loans</td>';
            foreach ($years as $yearlyValue)
            {
              $result = $this->loanapplication_model->getTotalLoans($yearlyValue['Year']);
              $html .='<td>'.$result['Total'].'</td>';
            }
          $html .='</tr>';
          $html .='<tr>';
          $html .='<td colspan="'.$totalColumns.'">c. Geographical Concentration</td>';
          $html .='</tr>';
          $html .='<tr>';
          $html .='<td>i. National Capital Region (NCR)</td>';
            foreach ($years as $yearlyValue)
            {
              $result = $this->loanapplication_model->getTotalBorrowerGeo($yearlyValue['Year'], 'NCR');
              $html .='<td>'.$result['TotalBorrowers'].'</td>';
            }
          $html .='</tr>';
          $html .='<tr>';
          $html .='<td>ii. Luzon</td>';
            foreach ($years as $yearlyValue)
            {
              $result = $this->loanapplication_model->getTotalBorrowerGeo($yearlyValue['Year'], 'Luzon');
              $html .='<td>'.$result['TotalBorrowers'].'</td>';
            }
          $html .='</tr>';
          $html .='<tr>';
          $html .='<td>iii. Visayas</td>';
            foreach ($years as $yearlyValue)
            {
              $result = $this->loanapplication_model->getTotalBorrowerGeo($yearlyValue['Year'], 'Visayas');
              $html .='<td>'.$result['TotalBorrowers'].'</td>';
            }
          $html .='</tr>';
          $html .='<tr>';
          $html .='<td>iv. Mindanao</td>';
            foreach ($years as $yearlyValue)
            {
              $result = $this->loanapplication_model->getTotalBorrowerGeo($yearlyValue['Year'], 'Mindanao');
              $html .='<td>'.$result['TotalBorrowers'].'</td>';
            }
          $html .='</tr>';
          $html .='<tr>';
          $html .='<td>d. Type of Loans</td>';
            foreach ($years as $yearlyValue)
            {
              $result = $this->loanapplication_model->getTotalTypeofLoans($yearlyValue['Year']);
              $html .='<td>'.$result['Total'].'</td>';
            }
          $html .='</tr>';
          $html .='<tr>';
          $html .='<td>e. Total Loan Amount</td>';
            foreach ($years as $yearlyValue)
            {
              $result = $this->loanapplication_model->getTotalLoanAmount($yearlyValue['Year']);
              $html .='<td>Php '.number_format($result['Total'], 2).'</td>';
            }
          $html .='</tr>';
          $html .='<tr>';
          $html .='<td>f. Tenors</td>';
            foreach ($years as $yearlyValue)
            {
              $result = $this->loanapplication_model->getTotalLoanAmount($yearlyValue['Year']);
              $html .='<td>Php '.$result['Total'].'</td>';
            }
          $html .='</tr>';
          $html .='<tr>';
          $html .='<td>g. Interest Rates</td>';
            foreach ($years as $yearlyValue)
            {
              $result = $this->loanapplication_model->getTotalInterest($yearlyValue['Year']);
              $html .='<td>Php '.number_format($result['Total'], 2).'</td>';
            }
          $html .='</tr>';
          $html .='<tr>';
          $html .='<td>h. Fees and Other Charges</td>';
            foreach ($years as $yearlyValue)
            {
              $result = $this->loanapplication_model->getTotalCharges($yearlyValue['Year']);
              $html .='<td>Php '.number_format($result['Total'], 2).'</td>';
            }
          $html .='</tr>';
          $html .= '
          <tbody>
        </table>

        <small>Generated By: '.$employeeDetail['Name'].'</small>
        <small>('.$DateNow.')</small>
        <br><br>
        <br><br>
      ';
      $pdf->writeHTML($html, true, false, true, false, '');

      // Close and output PDF document
      // $pdf->Output('Borrower Data.pdf', 'I');
      $pdf->Output('Loans Extended.pdf', 'D');
    }
    if($this->uri->segment(3) == 6) // financial health
    {
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

        a {
          text-align: center;
          font-size: 15px;
        }
        </style>

        <p>Financial Health by the Company<br><small>'.$branchName['Name'].' Branch</small></p>

        <br>
        <br>
        ';
          $years = $this->loanapplication_model->getLoansYear2($_POST['YearFrom'], $_POST['YearTo']);
          $totalColumns = count($years) + 1;
          $blankColumns = count($years) + 2;
          // for ($i=0; $i < count($years); $i++) { 
          //   $html .='<td>'.$years[$i]['Year'].'</td>';
          // }
        $html .='
        <table>
          <tbody>';
          $html .= '
          <tr>
            <td>Year</td>';
            foreach ($years as $value) 
            {
              $html .='<td>'.$value['Year'].'</td>';
            }
          $html .='</tr>';
          $html .='<tr>';
          $html .='<td>a. Total Loan Portfolio</td>';
            foreach ($years as $yearlyValue)
            {
              $result = $this->loanapplication_model->getTotalLoanAmount($yearlyValue['Year']);
              $result2 = $this->loanapplication_model->getTotalInterest($yearlyValue['Year']);
              $html .='<td>Php '.number_format($result['Total'] + $result2['Total'], 2).'</td>';
            }
          $html .='</tr>';
          $html .='<tr>';
          $html .='<td>b. Ratio of Non-Performing Loans to Total Loan Portfolio</td>';
            foreach ($years as $yearlyValue)
            {
              $result = $this->loanapplication_model->getTotalLoanAmount($yearlyValue['Year']);
              $html .='<td>Php '.number_format($result['Total'], 2).'</td>';
            }
          $html .='</tr>';
          $html .='<tr>';
          $html .='<td>c. Past Due Ratio and Write-Off Ratio to Total Loan Portfolio</td>';
            foreach ($years as $yearlyValue)
            {
              $result = $this->loanapplication_model->getTotalLoanAmount($yearlyValue['Year']);
              $html .='<td>Php '.number_format($result['Total'], 2).'</td>';
            }
          $html .='</tr>';
          $html .='<tr>';
          $html .='<td>d. Total Assets</td>';
            foreach ($years as $yearlyValue)
            {
              $result = $this->loanapplication_model->getCurrentFund($yearlyValue['Year']);
              $html .='<td>Php '.number_format($result['Total'], 2).'</td>';
            }
          $html .='</tr>';
          $html .='<tr>';
          $html .='<td>e. Gross Revenue</td>';
            foreach ($years as $yearlyValue)
            {
              $result = $this->loanapplication_model->getTotalGross($yearlyValue['Year']);
              $html .='<td>Php '.number_format($result['Total'], 2).'</td>';
            }
          $html .='</tr>';
          $html .='<tr>';
          $html .='<td>f. Net Income</td>';
            foreach ($years as $yearlyValue)
            {
              $result = $this->loanapplication_model->getTotalGross($yearlyValue['Year']);
              $result2 = $this->loanapplication_model->getTotalExpenses($yearlyValue['Year']);
              $html .='<td>Php '.number_format($result['Total'] - $result2['Total'], 2).'</td>';
            }
          $html .='</tr>';
          $html .= '
          <tbody>
        </table>

        <small>Generated By: '.$employeeDetail['Name'].'</small>
        <small>('.$DateNow.')</small>

        <br><br>
        <br><br>
      ';
      $pdf->writeHTML($html, true, false, true, false, '');

      // Close and output PDF document
      // $pdf->Output('Borrower Data.pdf', 'I');
      $pdf->Output('Financial Health.pdf', 'D');
    }
    if($this->uri->segment(3) == 7) // income statement
    {
      $pdf->AddPage('L', 'A4');
      $branchName = $this->maintenance_model->selectSpecific('R_Branches', 'BranchId', $this->session->userdata('BranchId'));

      $html = '
        <style>
        table, td, th {
          border: 1px solid black;
          border-collapse: collapse;
        }

        p {
          text-align: center;
          font-size: 15px;
        }

        a {
          text-align: center;
          font-size: 15px;
        }
        </style>

        <p>'.htmlentities($_POST['reportName'], ENT_QUOTES).'<br><small>'.$branchName['Name'].' Branch</small><br><small>'.htmlentities($_POST['DateFrom'], ENT_QUOTES).' - '.htmlentities($_POST['DateTo'], ENT_QUOTES).'</small><br><small>(Date From - Date To)</small></p>

        <br>
        <br>
        ';
          $years = $this->loanapplication_model->getLoansYear();
          $totalColumns = count($years) + 1;
          $blankColumns = count($years) + 2;
        $html .='
        <table>
          <tbody>';
          // // capital
          //   $html .= '<tr>';
          //   $html .= '<td colspan="3">';
          //   $html .= 'Capital';
          //   $html .= '</td>';
          //   $html .='</tr>';
          //   // capital amount
          //     $totalCapital = $this->loanapplication_model->getCurrentFundStatement($_POST['DateFrom'], $_POST['DateTo']);

          //     $html .= '<tr>';
          //     $html .= '<td style="width: 5%">';
          //     $html .= '</td>';
          //     $html .= '<td>';
          //     $html .= 'Current Fund';
          //     $html .= '</td>';
          //     $html .= '<td style="width: 5%">';
          //     $html .= '<small>PHP</small> ';
          //     $html .= '</td>';
          //     $html .= '<td style="text-align: right">';
          //     $html .= number_format($totalCapital['Total'], 2);
          //     $html .= '</td>';
          //     $html .= '</tr>';
          // income
            $html .= '<tr>';
            $html .= '<td colspan="3">';
            $html .= 'Income';
            $html .= '</td>';
            $html .='</tr>';
            // income amount
              $totalIncome = $this->loanapplication_model->getTotalCollections($_POST['DateFrom'], $_POST['DateTo']);
              $totalCharges = $this->loanapplication_model->getTotalChargesStatement($_POST['DateFrom'], $_POST['DateTo']);

              $html .= '<tr>';
              $html .= '<td style="width: 5%">';
              $html .= '</td>';
              $html .= '<td>';
              $html .= 'Collections';
              $html .= '</td>';
              $html .= '<td style="width: 5%">';
              $html .= '<small>PHP</small> ';
              $html .= '</td>';
              $html .= '<td style="text-align: right">';
              $html .= number_format($totalIncome['Total'], 2);
              $html .= '</td>';
              $html .='</tr>';

              $html .= '<tr>';
              $html .= '<td>';
              $html .= '</td>';
              $html .= '<td>';
              $html .= 'Charges and Other Fees';
              $html .= '</td>';
              $html .= '<td style="width: 5%">';
              $html .= '<small>PHP</small> ';
              $html .= '</td>';
              $html .= '<td style="text-align: right">';
              $html .= number_format($totalCharges['Total'], 2);
              $html .= '</td>';
              $html .='</tr>';

              $html .= '<tr>';
              $html .= '<td>';
              $html .= '</td>';
              $html .= '<td style="background-color:#ccd5dc">';
              $html .= 'Total';
              $html .= '</td>';
              $html .= '<td style="width: 5%; background-color:#ccd5dc">';
              $html .= '<small>PHP</small>';
              $html .= '</td>';
              $html .= '<td style="text-align: right;background-color:#ccd5dc">';
              $html .= number_format($totalIncome['Total'] + $totalCharges['Total'], 2);
              $html .= '</td>';
              $html .='</tr>';

          // Expenses
            $html .= '<tr>';
            $html .= '<td colspan="3">';
            $html .= 'Expenses';
            $html .= '</td>';
            $html .= '</tr>';
            // expense amount
              $totalExpenses = 0;
              $expensesDesc = $this->loanapplication_model->getExpensesStatement($_POST['DateFrom'], $_POST['DateTo']);
              foreach ($expensesDesc as $key => $value) 
              {
                $totalExpenses = $totalExpenses + $value['Amount'];
                $html .= '<tr>';
                $html .= '<td style="width: 5%">';
                $html .= '</td>';
                $html .= '<td>';
                $html .= $value['Name'];
                $html .= '</td>';
                $html .= '<td style="width: 5%">';
                $html .= '<small>PHP</small> ';
                $html .= '</td>';
                $html .= '<td style="text-align: right">';
                $html .= number_format($value['Amount'], 2);
                $html .= '</td>';
                $html .='</tr>';
              }

              $html .= '<tr>';
              $html .= '<td>';
              $html .= '</td>';
              $html .= '<td style="background-color:#ccd5dc">';
              $html .= 'Total';
              $html .= '</td>';
              $html .= '<td style="width: 5%;background-color:#ccd5dc">';
              $html .= '<small>PHP</small> ';
              $html .= '</td>';
              $html .= '<td style="text-align: right;background-color:#ccd5dc">';
              $html .= number_format($totalExpenses, 2);
              $html .= '</td>';
              $html .='</tr>';

          // Gross Income
            $html .= '<tr>';
            $html .= '<td colspan="3" style="background-color:#ccd5dc">';
            $html .= 'Gross Income';
            $html .= '</td>';
            $html .= '<td style="text-align: right; background-color:#ccd5dc" colspan="2">';
            $html .= number_format(($totalIncome['Total'] + $totalCharges['Total']), 2);
            $html .= '</td>';
            $html .='</tr>';

          // Net Income
            $html .= '<tr>';
            $html .= '<td colspan="3" style="background-color:#ccd5dc">';
            $html .= 'Net Income';
            $html .= '</td>';
            $html .= '<td style="text-align: right; background-color:#ccd5dc" colspan="2">';
            $html .= number_format(($totalIncome['Total'] + $totalCharges['Total']) - $totalExpenses, 2);
            $html .= '</td>';
            $html .='</tr>';

          $html .= '
          </tbody>
        </table>

        <br><br>
        <table>
          <thead>
          <tr>
            <th><strong>Prepared By</strong></th>
            <th>'.$this->session->userdata('Name').' - '.$employeeDetail['Position'].'</th>
            <th><strong>Verified By</strong></th>
            <th>'.$_POST['verifiedBy'].'</th>
            <th><strong>Approved By</strong></th>
            <th>'.$_POST['approvedBy'].'</th>
          </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
        <br>
        <small>Date Generated: '.$DateNow.'</small>

        <br><br>
        <br><br>
      ';
      $pdf->writeHTML($html, true, false, true, false, '');

      // Close and output PDF document
      // $pdf->Output('Income Statement.pdf', 'I');
      $pdf->Output('Income Statement.pdf', 'D');
    }
  }

  function getSelectedApprovers()
  {
    $output = $this->loanapplication_model->getSelectedApprovers($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function editStatus()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $AssignedBranchId = $this->session->userdata('BranchId');
    $DateNow = date("Y-m-d H:i:s");
    $loanDetails = $this->maintenance_model->selectSpecific('t_application', 'ApplicationId', $_POST['ApplicationId']);

    if($loanDetails['ApprovalType'] != $_POST['ApprovalType'])
    {
      // admin
        $transNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $_POST['ApplicationId']);
        $auditApplication = 'Changed approval status from '.$loanDetails['ApprovalType'].' to '.$_POST['ApprovalType'].'.';
        $auditLogsManager = 'Changed approval status from '.$loanDetails['ApprovalType'].' to '.$_POST['ApprovalType'].' of application #'.$transNo['TransactionNumber'].'.';
        $this->auditLoanApplication($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditApplication, $_POST['ApplicationId'], null);
      // update loan status
        $set1 = array( 
          'ApprovalType' => $_POST['ApprovalType']
        );
        $condition1 = array( 
          'ApplicationId' => $_POST['ApplicationId']
        );
        $table1 = 't_application';
        $this->maintenance_model->updateFunction1($set1, $condition1, $table1);
    }

    if($_POST['LoanStatusId'] == 1 || $_POST['LoanStatusId'] == 2) // approved/declined
    {
      print_r($_POST['LoanStatusId']);
      // admin
        $oldStatusDesc = $this->maintenance_model->selectSpecific('application_has_status', 'loanStatusId', $loanDetails['StatusId']);
        $newtatusDesc = $this->maintenance_model->selectSpecific('application_has_status', 'loanStatusId', $_POST['LoanStatusId']);
        $transNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $_POST['ApplicationId']);
        $auditApplication = 'Changed status from '.$oldStatusDesc['Name'].' to '.$newtatusDesc['Name'].'.';
        $auditLogsManager = 'Changed status from '.$oldStatusDesc['Name'].' to '.$newtatusDesc['Name'].' of application #'.$transNo['TransactionNumber'].'.';
        $this->auditLoanApplication($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditApplication, $_POST['ApplicationId'], null);
      // update loan status
        $set1 = array( 
          'StatusId' => $_POST['LoanStatusId']
        );
        $condition1 = array( 
          'ApplicationId' => $_POST['ApplicationId']
        );
        $table1 = 't_application';
        $this->maintenance_model->updateFunction1($set1, $condition1, $table1);
      // update approvers
        $set2 = array( 
          'StatusId' => 6 // deactivated
        );
        $condition2 = array( 
          'ApplicationId' => $_POST['ApplicationId']
        );
        $table2 = 'application_has_approver';
        $this->maintenance_model->updateFunction1($set2, $condition2, $table2);
    }
    if($_POST['LoanStatusId'] == 3) // for approval
    {
      // admin
        $oldStatusDesc = $this->maintenance_model->selectSpecific('application_has_status', 'loanStatusId', $loanDetails['StatusId']);
        $newtatusDesc = $this->maintenance_model->selectSpecific('application_has_status', 'loanStatusId', $_POST['LoanStatusId']);
        $transNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $_POST['ApplicationId']);
        $auditApplication = 'Updated list of approvers.';
        $auditLogsManager = 'Changed list of approvers of application #'.$transNo['TransactionNumber'].'.';
        $this->auditLoanApplication($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditApplication, $_POST['ApplicationId'], null);
      // update loan status
        $set1 = array( 
          'StatusId' => $_POST['LoanStatusId']
        );
        $condition1 = array( 
          'ApplicationId' => $_POST['ApplicationId']
        );
        $table1 = 't_application';
        $this->maintenance_model->updateFunction1($set1, $condition1, $table1);
      if(isset($_POST['Approvers']))
      {
        $set1 = array(
          'StatusId' => 6
        );
        $condition1 = array(
          'ApplicationId' => $_POST['ApplicationId'],
        );
        $table1 = 'application_has_approver';
        $this->maintenance_model->updateFunction1($set1, $condition1, $table1);
        foreach ($_POST['Approvers'] as $value) 
        {          
          $insertData = array(
            'ApplicationId'         => $_POST['ApplicationId'],
            'ApproverNumber'        => $value,
            'StatusId'              => 5,
            'CreatedBy'             => $EmployeeNumber
          );
          $auditTable = 'application_has_approver';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        }
      }
    }

    $this->session->set_flashdata('alertTitle','Success!'); 
    $this->session->set_flashdata('alertText','Successfully updated record!'); 
    $this->session->set_flashdata('alertType','success'); 
    redirect('home/loandetail/' . $_POST['ApplicationId']);
  }

  function finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $ManagerId, $AffectedEmployeeNumber, $auditLoanDets, $ApplicationId, $independentTable, $independentColumn)
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
      , ''.$independentColumn.''   => $ApplicationId
      , 'CreatedBy'       => $CreatedBy
    );
    $auditLoanApplicationTable = $independentTable;
    $this->maintenance_model->insertFunction($insertApplicationLog, $auditLoanApplicationTable);
  }

  function forRestart($ApplicationId)
  {
    $CreatedBy = $this->session->userdata('EmployeeNumber');
    $detail = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $ApplicationId);
    $DateNow = date("Y-m-d H:i:s");
    if($detail['StatusId'] == 3)
    {
      $set = array( 
        'StatusId' => 5
      );
      $condition = array( 
        'ApplicationId' => $ApplicationId,
        'StatusId' => 1
      );
      $table = 'application_has_approver';
      $this->maintenance_model->updateFunction1($set, $condition, $table);  
    }
  }

  function AddPersonalRef()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    // insert data
      $insertData = array(
        'ReferenceId'               => $_POST['ReferenceId'],
        'ApplicationId'             => $this->uri->segment(3),
        'CreatedBy'                 => $EmployeeNumber,
        'DateCreated'               => $DateNow,      
      );
      $auditTable = 'application_has_personalreference';
      $this->maintenance_model->insertFunction($insertData, $auditTable);
    // admin audits finals
      $TransNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
      $ReferenceNo = $this->borrower_model->getPersonalDetails($_POST['ReferenceId']);
      $auditLogsManager = 'Added personal reference #'.$ReferenceNo['RefNo'].' in personal reference tab to application #'.$TransNo['TransactionNumber'].'.';
      $auditAffectedEmployee = 'Added personal reference #'.$ReferenceNo['RefNo'].' in personal reference tab to application #'.$TransNo['TransactionNumber'].'.';
      $auditAffectedTable = 'Added personal reference #'.$ReferenceNo['RefNo'].'.';
      $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(3), 'application_has_notifications', 'ApplicationId');

    // notification
      $this->session->set_flashdata('alertTitle','Success!'); 
      $this->session->set_flashdata('alertText','Successfully added record!'); 
      $this->session->set_flashdata('alertType','success'); 
      redirect('home/loandetail/' . $this->uri->segment(3));
  }

  function selectBorrowerDet()
  {
    $output = $this->loanapplication_model->selectBorrowerDet($this->input->post('Type'), $this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function addBorrowerDets()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    if($_POST['ReferenceType'] == 'Comaker')
    {
      // update exisitng co maker
        $set = array( 
          'StatusId' => 0
        );
        $condition = array( 
          'ApplicationId' => $this->uri->segment(3),
          'StatusId'      => 1
        );
        $table = 'application_has_comaker';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // insert data
        $insertData = array(
          'BorrowerCoMakerId'         => $_POST['ReferenceId'],
          'ApplicationId'             => $this->uri->segment(3),
          'CreatedBy'                 => $EmployeeNumber,
          'DateCreated'               => $DateNow,      
        );
        $auditTable = 'application_has_comaker';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // admin audits finals
        $TransNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
        $ReferenceNo = $this->borrower_model->getComakerDetails($_POST['ReferenceId']);
        $auditLogsManager = 'Added co-maker #'.$ReferenceNo['RefNo'].' in co-maker tab to application #'.$TransNo['TransactionNumber'].'.';
        $auditAffectedEmployee = 'Added co-maker #'.$ReferenceNo['RefNo'].' in co-maker tab to application #'.$TransNo['TransactionNumber'].'.';
        $auditAffectedTable = 'Added co-maker #'.$ReferenceNo['RefNo'].' co-maker tab.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(3), 'application_has_notifications', 'ApplicationId');

      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Successfully added record!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/loandetail/' . $this->uri->segment(3));
    }
    if($_POST['ReferenceType'] == 'Spouse')
    {
      // update exisitng co maker
        $set = array( 
          'StatusId' => 0
        );
        $condition = array( 
          'ApplicationId' => $this->uri->segment(3),
          'StatusId'      => 1
        );
        $table = 'application_has_spouse';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // insert data
        $insertData = array(
          'BorrowerSpouseId'          => $_POST['ReferenceId'],
          'ApplicationId'             => $this->uri->segment(3),
          'CreatedBy'                 => $EmployeeNumber,
          'DateCreated'               => $DateNow,      
        );
        $auditTable = 'application_has_spouse';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // admin audits finals
        $TransNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
        $ReferenceNo = $this->borrower_model->getSpouseDetails2($_POST['ReferenceId']);
        $auditLogsManager = 'Added spouse #'.$ReferenceNo['RefNo'].' in spouse tab to application #'.$TransNo['TransactionNumber'].'.';
        $auditAffectedEmployee = 'Added spouse #'.$ReferenceNo['RefNo'].' in spouse tab to application #'.$TransNo['TransactionNumber'].'.';
        $auditAffectedTable = 'Added spouse #'.$ReferenceNo['RefNo'].' in spouse tab.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(3), 'application_has_notifications', 'ApplicationId');

      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Successfully added record!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/loandetail/' . $this->uri->segment(3));
    }
    if($_POST['ReferenceType'] == 'BorrowerEmployer')
    {
      // insert data
        $insertData = array(
          'EmployerId'                => $_POST['ReferenceId'],
          'ApplicationId'             => $this->uri->segment(3),
          'CreatedBy'                 => $EmployeeNumber,
          'DateCreated'               => $DateNow,      
        );
        $auditTable = 'application_has_employer';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // admin audits finals
        $TransNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
        $ReferenceNo = $this->borrower_model->getBorrowerEmployment($_POST['ReferenceId']);
        $auditLogsManager = 'Added employment #'.$ReferenceNo['RefNo'].' in employment tab to application #'.$TransNo['TransactionNumber'].'.';
        $auditAffectedEmployee = 'Added employment #'.$ReferenceNo['RefNo'].' in employment tab to application #'.$TransNo['TransactionNumber'].'.';
        $auditAffectedTable = 'Added employment #'.$ReferenceNo['RefNo'].' employment tab.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(3), 'application_has_notifications', 'ApplicationId');

      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Successfully added record!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/loandetail/' . $this->uri->segment(3));
    }
    if($_POST['ReferenceType'] == 'BorrowerContact')
    {
      // update exisitng co maker
        $set = array( 
          'StatusId' => 0
        );
        $condition = array( 
          'ApplicationId' => $this->uri->segment(3),
          'StatusId'      => 1
        );
        $table = 'application_has_contact';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // insert data
        $insertData = array(
          'BorrowerContactId'         => $_POST['ReferenceId'],
          'ApplicationId'             => $this->uri->segment(3),
          'CreatedBy'                 => $EmployeeNumber,
          'DateCreated'               => $DateNow,      
        );
        $auditTable = 'application_has_contact';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // admin audits finals
        $TransNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
        $ReferenceNo = $this->borrower_model->getNumberDetails($_POST['ReferenceId']);
        $auditLogsManager = 'Added contact #'.$ReferenceNo['RefNo'].' in contact tab to application #'.$TransNo['TransactionNumber'].'.';
        $auditAffectedEmployee = 'Added contact #'.$ReferenceNo['RefNo'].' in contact tab to application #'.$TransNo['TransactionNumber'].'.';
        $auditAffectedTable = 'Added contact #'.$ReferenceNo['RefNo'].' contact tab.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(3), 'application_has_notifications', 'ApplicationId');

      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Successfully added record!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/loandetail/' . $this->uri->segment(3));
    }
    if($_POST['ReferenceType'] == 'BorrowerEmail')
    {
      // update exisitng co maker
        $set = array( 
          'StatusId' => 0
        );
        $condition = array( 
          'ApplicationId' => $this->uri->segment(3),
          'StatusId'      => 1
        );
        $table = 'application_has_email';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // insert data
        $insertData = array(
          'BorrowerEmailId'         => $_POST['ReferenceId'],
          'ApplicationId'             => $this->uri->segment(3),
          'CreatedBy'                 => $EmployeeNumber,
          'DateCreated'               => $DateNow,      
        );
        $auditTable = 'application_has_email';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // admin audits finals
        $TransNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
        $ReferenceNo = $this->borrower_model->getEmailDetails($_POST['ReferenceId']);
        $auditLogsManager = 'Added email #'.$ReferenceNo['RefNo'].' in email tab to application #'.$TransNo['TransactionNumber'].'.';
        $auditAffectedEmployee = 'Added email #'.$ReferenceNo['RefNo'].' in email tab to application #'.$TransNo['TransactionNumber'].'.';
        $auditAffectedTable = 'Added email #'.$ReferenceNo['RefNo'].' email tab.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(3), 'application_has_notifications', 'ApplicationId');

      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Successfully added record!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/loandetail/' . $this->uri->segment(3));
    }
    if($_POST['ReferenceType'] == 'BorrowerEducation')
    {
      // update exisitng co maker
        $set = array( 
          'StatusId' => 0
        );
        $condition = array( 
          'ApplicationId' => $this->uri->segment(3),
          'StatusId'      => 1
        );
        $table = 'application_has_education';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // insert data
        $insertData = array(
          'BorrowerEducationId'       => $_POST['ReferenceId'],
          'ApplicationId'             => $this->uri->segment(3),
          'CreatedBy'                 => $EmployeeNumber,
          'DateCreated'               => $DateNow,      
        );
        $auditTable = 'application_has_education';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // admin audits finals
        $TransNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
        $ReferenceNo = $this->borrower_model->getEducationDetails($_POST['ReferenceId']);
        $auditLogsManager = 'Added education #'.$ReferenceNo['RefNo'].' in education tab to application #'.$TransNo['TransactionNumber'].'.';
        $auditAffectedEmployee = 'Added education #'.$ReferenceNo['RefNo'].' in education tab to application #'.$TransNo['TransactionNumber'].'.';
        $auditAffectedTable = 'Added education #'.$ReferenceNo['RefNo'].' education tab.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(3), 'application_has_notifications', 'ApplicationId');

      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Successfully added record!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/loandetail/' . $this->uri->segment(3));
    }
    if($_POST['ReferenceType'] == 'BorrowerAddress')
    {
      // insert data
        $insertData = array(
          'BorrowerAddressHistoryId'       => $_POST['ReferenceId'],
          'ApplicationId'             => $this->uri->segment(3),
          'CreatedBy'                 => $EmployeeNumber,
          'DateCreated'               => $DateNow,      
        );
        $auditTable = 'application_has_address';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // admin audits finals
        $TransNo = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $this->uri->segment(3));
        $ReferenceNo = $this->borrower_model->getAddressDetails($_POST['ReferenceId']);
        $auditLogsManager = 'Added address #'.$ReferenceNo['RefNo'].' in address tab to application #'.$TransNo['TransactionNumber'].'.';
        $auditAffectedEmployee = 'Added address #'.$ReferenceNo['RefNo'].' in address tab to application #'.$TransNo['TransactionNumber'].'.';
        $auditAffectedTable = 'Added address #'.$ReferenceNo['RefNo'].' address tab.';
        $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(3), 'application_has_notifications', 'ApplicationId');

      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Successfully added record!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/loandetail/' . $this->uri->segment(3));
    }
  }
}
