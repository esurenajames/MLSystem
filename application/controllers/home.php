<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class home extends CI_Controller {

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

	function Dashboard()
	{
		$sidebar['sidebar'] = 'Dashboard';
		$sidebar['sidebarMenu'] = 'Dashboard';
		$header['header'] = 'Dashboard';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$data['access'] = $this->sidebar_model->getAccess();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['securityQuestions'] = $this->employee_model->getSecurityQuestions();
		$data['totalBorrower'] = $this->maintenance_model->getTotalBorrower();
		$data['TotalMonthlyIncome'] = $this->maintenance_model->getTotalMonthlyIncome();
		$data['TotalInterestRate'] = $this->maintenance_model->getTotalInterestRate();
		$data['TotalExpense'] = $this->maintenance_model->getTotalExpense();
		$data['TotalApproved'] = $this->maintenance_model->getTotalApproved();
		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/dashboard', $data);
	}
	
	function AddBank()
	{
		$sidebar['sidebar'] = 'SystemSetup';
		$sidebar['sidebarMenu'] = 'Banks';
		$header['header'] = 'Banks';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$data['access'] = $this->sidebar_model->getAccess();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['securityQuestions'] = $this->employee_model->getSecurityQuestions();
		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddBank', $data);
	}

	function AddBranch()
	{
		$sidebar['sidebar'] = 1;
		$sidebar['sidebarMenu'] = 0;
		$header['header'] = 'Branches';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();
		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddBranch', $data);
	}

	function AddLoanType()
	{
		$sidebar['sidebar'] = 1;
		$sidebar['sidebarMenu'] = 0;
		$header['header'] = 'Loan Types';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();
		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddLoanType', $data);
	}


	function AddConditional()
	{
		$sidebar['sidebar'] = 1;
		$sidebar['sidebarMenu'] = 0;
		$header['header'] = 'Additional Charges';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddConditional', $data);
	}

	function AddOccupation()
	{
		$sidebar['sidebar'] = 2;
		$sidebar['sidebarMenu'] = 3;
		$header['header'] = 'Occupations';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddOccupation', $data);
	}

	function AddRequirement()
	{
		$sidebar['sidebar'] = 1;
		$sidebar['sidebarMenu'] = 0;
		$header['header'] = 'Requirements';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddRequirement', $data);
	}

	function AddPosition()
	{
		$sidebar['sidebar'] = 1;
		$sidebar['sidebarMenu'] = 0;
		$header['header'] = 'Positions';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddPosition', $data);
	}

	function AddOptional()
	{
		$sidebar['sidebar'] = 1;
		$sidebar['sidebarMenu'] = 0;
		$header['header'] = 'Optional Charges';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddOptional', $data);
	}

	function AddPurpose()
	{
		$sidebar['sidebar'] = 1;
		$sidebar['sidebarMenu'] = 0;
		$header['header'] = 'Purpose';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddPurpose', $data);
	}

	function AddMethod()
	{
		$sidebar['sidebar'] = 1;
		$sidebar['sidebarMenu'] = 0;
		$header['header'] = 'Methods for Payment';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddMethod', $data);
	}

	function AddCategory()
	{
		$sidebar['sidebar'] = 1;
		$sidebar['sidebarMenu'] = 0;
		$header['header'] = 'Asset Categories';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddCategory', $data);
	}

	function AddAssetManagement()
	{
		$sidebar['sidebar'] = 'Asset Management';
		$sidebar['sidebarMenu'] = 'Asset Management';
		$header['header'] = 'Asset Management';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['Category'] = $this->maintenance_model->getCategory();
		$data['Branch'] = $this->maintenance_model->getBranches();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddAssetManagement', $data);
	}

	function AddLoanStatus()
	{
		$sidebar['sidebar'] = 1;
		$sidebar['sidebarMenu'] = 0;
		$header['header'] = 'Loan Status';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddLoanStatus', $data);
	}

	function AddBorrowerStatus()
	{
		$sidebar['sidebar'] = 1;
		$sidebar['sidebarMenu'] = 0;
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddBorrowerStatus', $data);
	}

	function AddIndustry()
	{
		$sidebar['sidebar'] = 1;
		$sidebar['sidebarMenu'] = 0;
		$header['header'] = 'Industries';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddIndustry', $data);
	}

	function AddEducation()
	{
		$sidebar['sidebar'] = 1;
		$sidebar['sidebarMenu'] = 0;
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddEducation', $data);
	}

	function AddRepaymentCycle()
	{
		$sidebar['sidebar'] = 'SystemSetup';
		$sidebar['sidebarMenu'] = 'RepaymentCycle';
		$header['header'] = 'Repayment Cycles';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddRepaymentCycle', $data);
	}

	function AddDisbursement()
	{
		$sidebar['sidebar'] = 'SystemSetup';
		$sidebar['sidebarMenu'] = 'Disbursement';
		$header['header'] = 'Disbursements';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddDisbursement', $data);
	}

	function HistoryLogs()
	{
		$sidebar['sidebar'] = 'HistoryLog';
		$sidebar['sidebarMenu'] = 'HistoryLog';
		$header['header'] = 'History Logs';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/HistoryLog', $data);
	}

	function AddInitialCapital()
	{
		$sidebar['sidebar'] = 'SystemSetup';
		$sidebar['sidebarMenu'] = 'InitialCapital';
		$header['header'] = 'Set Initial Capital';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddInitialCapital', $data);
	}

	function AddExpenseType()
	{
		$sidebar['sidebar'] = 'SystemSetup';
		$sidebar['sidebarMenu'] = 'ExpenseType';
		$header['header'] = 'Types of Expenses';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddExpenseType', $data);
	}

	function AddExpense()
	{
		$sidebar['sidebar'] = 'Financial';
		$sidebar['sidebarMenu'] = 'Expenses';
		$header['header'] = 'Expenses';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['ExpenseType'] = $this->maintenance_model->getExpenseType();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddExpense', $data);
	}

	function AddWithdrawalType()
	{
		$sidebar['sidebar'] = 'SystemSetup';
		$sidebar['sidebarMenu'] = 'WithdrawalType';
		$header['header'] = 'Types of Withdrawals';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddWithdrawalType', $data);
	}

	function AddWithdrawal()
	{
		$sidebar['sidebar'] = 'Financial';
		$sidebar['sidebarMenu'] = 'Withdrawals';
		$header['header'] = 'Withdrawals';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['WithdrawalType'] = $this->maintenance_model->getWithdrawalType();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddWithdrawal', $data);
	}

	function ViewLoans()
	{
		$sidebar['sidebar'] = 'Loans';
		$sidebar['sidebarMenu'] = 'View Loans';
		$header['header'] = 'View Loans';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('transaction/dashboard', $data);
	}

	function LoanApplication()
	{
		$sidebar['sidebar'] = 'Loans';
		$sidebar['sidebarMenu'] = 'Loan Application';
		$header['header'] = 'Loan Application';
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$data['LoanType'] = $this->maintenance_model->getLoanTypes();
		$data['Purpose'] = $this->maintenance_model->getPurpose();
		$data['Source'] = $this->maintenance_model->getSource();
		$data['BorrowerId'] = $this->uri->segment(3);


		$data['Sex'] = $this->maintenance_model->getSex();
		$data['Nationality'] = $this->maintenance_model->getNationality();
		$data['CivilStatus'] = $this->maintenance_model->getCivilStatus();
		$data['Salutation'] = $this->maintenance_model->getSalutation();
		$data['Position'] = $this->maintenance_model->getBorrowerPosition();
		$data['Status'] = $this->maintenance_model->getBorrowerStatus();

		$data['repaymentCycle'] = $this->maintenance_model->getRepayments();
		$data['disbursements'] = $this->maintenance_model->getDisbursements();
		$data['requirementType'] = $this->maintenance_model->getRequirementType();
		$data['loanStatus'] = $this->maintenance_model->getLoanStatus();
		$data['borrowerList'] = $this->maintenance_model->getBorrowerList();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('transaction/LoanApplication', $data);
	}

	function Renew()
	{
		$Id = $this->uri->segment(3);
		$sidebar['sidebar'] = 'Loans';
		$sidebar['sidebarMenu'] = 'Loan Application';
		$header['header'] = 'Renew Loan Application';
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();
		$sidebar['access'] = $this->sidebar_model->checkSideBar();


		$data['detail'] = $this->loanapplication_model->getLoanApplicationDetails($Id);
		$data['LoanType'] = $this->loanapplication_model->getLoanTypes();
		$data['Purpose'] = $this->loanapplication_model->getPurpose();
		$data['disbursements'] = $this->loanapplication_model->getDisbursements();
		$data['repaymentCycle'] = $this->loanapplication_model->getRepaymentCycle();
		$data['charges'] = $this->loanapplication_model->getCharges($Id);
		$data['borrowerList'] = $this->loanapplication_model->getBorrowerList();


		$data['obligations'] = $this->loanapplication_model->getLoanObligations($Id);
		$data['expense'] = $this->loanapplication_model->getExpenses($Id);
		$data['income'] = $this->loanapplication_model->getIncome($Id);
		$data['approvers'] = $this->loanapplication_model->getApprovers($Id);


		$data['Source'] = $this->maintenance_model->getSource();
		$data['requirementType'] = $this->maintenance_model->getRequirementType();
		$data['loanStatus'] = $this->maintenance_model->getLoanStatus();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('transaction/renew', $data);
	}

	function createBorrowerLoan()
	{
		$Id = $this->uri->segment(3);
		$sidebar['sidebar'] = 'Loans';
		$sidebar['sidebarMenu'] = 'Loan Application';
		$header['header'] = 'Loan Application';
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$data['LoanType'] = $this->maintenance_model->getLoanTypes();
		$data['Purpose'] = $this->maintenance_model->getPurpose();
		$data['Source'] = $this->maintenance_model->getSource();


		$data['Sex'] = $this->maintenance_model->getSex();
		$data['Nationality'] = $this->maintenance_model->getNationality();
		$data['CivilStatus'] = $this->maintenance_model->getCivilStatus();
		$data['Salutation'] = $this->maintenance_model->getSalutation();
		$data['Position'] = $this->maintenance_model->getBorrowerPosition();
		$data['Status'] = $this->maintenance_model->getBorrowerStatus();

		$data['repaymentCycle'] = $this->maintenance_model->getRepayments();
		$data['disbursements'] = $this->maintenance_model->getDisbursements();
		$data['requirementType'] = $this->maintenance_model->getRequirementType();
		$data['loanStatus'] = $this->maintenance_model->getLoanStatus();
		$data['borrowerList'] = $this->maintenance_model->getBorrowerList();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('transaction/createBorrowerLoan', $data);
	}
	
	function LoanCalculator()
	{
		$sidebar['sidebar'] = 'Loans';
		$sidebar['sidebarMenu'] = 'Loan Calculator';
		$header['header'] = 'Loan Calculator';
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$data['LoanType'] = $this->maintenance_model->getLoanTypes();
		$data['Purpose'] = $this->maintenance_model->getPurpose();
		$data['Source'] = $this->maintenance_model->getSource();


		$data['Sex'] = $this->maintenance_model->getSex();
		$data['Nationality'] = $this->maintenance_model->getNationality();
		$data['CivilStatus'] = $this->maintenance_model->getCivilStatus();
		$data['Salutation'] = $this->maintenance_model->getSalutation();
		$data['Position'] = $this->maintenance_model->getBorrowerPosition();
		$data['Status'] = $this->maintenance_model->getBorrowerStatus();

		$data['repaymentCycle'] = $this->maintenance_model->getRepayments();
		$data['disbursements'] = $this->maintenance_model->getDisbursements();
		$data['requirementType'] = $this->maintenance_model->getRequirementType();
		$data['loanStatus'] = $this->maintenance_model->getLoanStatus();
		$data['borrowerList'] = $this->maintenance_model->getBorrowerList();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('transaction/LoanCalculator', $data);
	}

	function loandetail()
	{
		$Id = $this->uri->segment(3);
		$sidebar['sidebar'] = 'Loans';
		$sidebar['sidebarMenu'] = 'Loan Application';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$header['header'] = 'Loan Application Detail';
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();

		$data['requirements'] = $this->loanapplication_model->getRequirements($Id);
		$data['detail'] = $this->loanapplication_model->getLoanApplicationDetails($Id);
		$data['repayment'] = $this->loanapplication_model->getRepayments($Id);
		$data['charges'] = $this->loanapplication_model->getCharges($Id);
		$data['penalties'] = $this->loanapplication_model->getPenalties($Id);
		$data['DisplayPenalty'] = $this->loanapplication_model->DisplayPenalty($Id);
		$data['payments'] = $this->loanapplication_model->getPayments($Id);
		$data['approvers'] = $this->loanapplication_model->getApprovers($Id);
		$data['comments'] = $this->loanapplication_model->getLoanComments($Id);
		$data['requirementList'] = $this->loanapplication_model->displayRequirements($Id);
		$data['LoanHistory'] = $this->loanapplication_model->displayLoanHistory($Id);
		$data['obligations'] = $this->loanapplication_model->getLoanObligations($Id);
		$data['expense'] = $this->loanapplication_model->getExpenses($Id);
		$data['income'] = $this->loanapplication_model->getIncome($Id);
		$data['collateralType'] = $this->loanapplication_model->getCollateralType($Id);
		$data['collateralStatus'] = $this->loanapplication_model->getCollateralStatus($Id);
		$data['collateral'] = $this->loanapplication_model->getCollateral($Id);
		$data['chargeList'] = $this->loanapplication_model->displayCharges($Id);
		$data['selectCharges'] = $this->loanapplication_model->selectCharges($Id);
		$data['selectChanges'] = $this->loanapplication_model->selectChanges($Id);

		$data['Payments'] = $this->loanapplication_model->getPaymentsMade($Id);
		$data['disbursements'] = $this->maintenance_model->getPaymentMethod();
		$data['bank'] = $this->loanapplication_model->getBank();

		$data['paymentDates'] = $this->loanapplication_model->getPaymentDates($Id);
		$data['paymentDues'] = $this->loanapplication_model->getDue($Id);

		$data['DisplayPenalty'] = $this->loanapplication_model->DisplayPenalty($Id);
		$data['repaymentCycle'] = $this->loanapplication_model->getRepaymentCycle();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('transaction/loandetails', $data);
	}

	function loanDetailApproval()
	{
		$Id = $this->uri->segment(3);
		$sidebar['sidebar'] = 'Loans';
		$sidebar['sidebarMenu'] = 'Loan Application';
		$header['header'] = 'Loan Application Detail';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['detail'] = $this->loanapplication_model->getLoanApplicationDetails($Id);
		$data['repayment'] = $this->loanapplication_model->getRepayments($Id);
		$data['charges'] = $this->loanapplication_model->getCharges($Id);
		$data['penalties'] = $this->loanapplication_model->getPenalties($Id);
		$data['payments'] = $this->loanapplication_model->getPayments($Id);
		$data['approvers'] = $this->loanapplication_model->getApprovers($Id);
		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('transaction/approvals/loandetail', $data);
	}

	function LoanApprovals()
	{
		$sidebar['sidebar'] = 'Loans';
		$sidebar['sidebarMenu'] = 'Loan Approvals';
		$header['header'] = 'Loan Applications for Approval';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('transaction/Approvals/dashboard');
	}

	function employeeDetails()
	{
		$Id = $this->uri->segment(3);
		$sidebar['sidebar'] = 2;
		$sidebar['sidebarMenu'] = 1;
		$header['header'] = 'Employee Details';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$data['detail'] = $this->employee_model->getEmployeeDetails($Id);
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();

		$data['EmailAddress'] = $this->employee_model->employeeEmails($Id);
		$data['ContactNumber'] = $this->employee_model->contactNumbers($Id);
		$data['Address'] = $this->employee_model->employeeAddress($Id);
		$data['Ids'] = $this->employee_model->employeeIDs($Id);
		$data['Audit'] = $this->employee_model->employeeNotification($Id);

		$data['IDCategory'] = $this->maintenance_model->IDCategory();
		$data['Sex'] = $this->maintenance_model->getSex();
		$data['Nationality'] = $this->maintenance_model->getNationality();
		$data['CivilStatus'] = $this->maintenance_model->getCivilStatus();
		$data['Salutation'] = $this->maintenance_model->getSalutation();
		$data['Branch'] = $this->maintenance_model->getBranches();
		$data['Position'] = $this->maintenance_model->getPosition();
		$data['Roles'] = $this->maintenance_model->getRoles();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('employees/employeeDetails', $data);
	}

	function DashboardM()
	{
		$sidebar['sidebar'] = 1;
		$sidebar['sidebarMenu'] = 0;
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$data['access'] = $this->sidebar_model->getAccess();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('manager/dashboard', $data);
	}
	
	
	function NewBorrower()
	{
		$this->load->view('includes/header');
		$this->load->view('includes/sidebar');
		$this->load->view('borrower/NewCustomer');
	}
	
	function adminAuditLogs()
	{
		$sidebar['sidebar'] = 4;
		$sidebar['sidebarMenu'] = 2;
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$this->load->view('includes/header');
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/auditLogs');
	}
	
	function addUser()
	{
		$sidebar['sidebar'] = 'SystemSetup';
		$sidebar['sidebarMenu'] = 'Users';
		$header['header'] = 'users';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/addUser');
	}

	function addEmployees()
	{
		$sidebar['sidebar'] = 'EmployeeManagement';
		$sidebar['sidebarMenu'] = 'EmployeeManagement';
		$header['header'] = 'Employees';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['Sex'] = $this->maintenance_model->getSex();
		$data['Nationality'] = $this->maintenance_model->getNationality();
		$data['CivilStatus'] = $this->maintenance_model->getCivilStatus();
		$data['Salutation'] = $this->maintenance_model->getSalutation();
		$data['Branch'] = $this->maintenance_model->getBranches();
		$data['Position'] = $this->maintenance_model->getPosition();
		$data['Roles'] = $this->maintenance_model->getRoles();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('employees/addEmployees', $data);
	}
	
	function userProfile()
	{
		$Id = $this->uri->segment(3);
		$sidebar['sidebar'] = 0;
		$sidebar['sidebarMenu'] = 0;
		$header['header'] = 'User Profile';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['securityQuestions'] = $this->employee_model->getSecurityQuestions();
		$data['detail'] = $this->employee_model->getEmployeeProfile($Id);
		$data['Audit'] = $this->employee_model->employeeNotification($Id);
		$data['SecQuestion1'] = $this->employee_model->getEmployeeSecurityQuestions($Id, 1);
		$data['SecQuestion2'] = $this->employee_model->getEmployeeSecurityQuestions($Id, 2);
		$data['SecQuestion3'] = $this->employee_model->getEmployeeSecurityQuestions($Id, 3);

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('profile/userProfile', $data);
	}
	
	function borrowers()
	{
		$sidebar['sidebar'] = 'BorrowerManagement';
		$sidebar['sidebarMenu'] = 'BorrowerManagement';
		$header['header'] = 'Borrowers';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['Sex'] = $this->maintenance_model->getSex();
		$data['Nationality'] = $this->maintenance_model->getNationality();
		$data['CivilStatus'] = $this->maintenance_model->getCivilStatus();
		$data['Salutation'] = $this->maintenance_model->getSalutation();
		$data['Branch'] = $this->maintenance_model->getBranches();
		$data['Position'] = $this->maintenance_model->getPosition();
		$data['Roles'] = $this->maintenance_model->getRoles();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('borrowers/dashboard', $data);
	}

	function BorrowerDetails()
	{
		$Id = $this->uri->segment(3);
		$sidebar['sidebar'] = 'BorrowerManagement';
		$sidebar['sidebarMenu'] = 'BorrowerManagement';
		$header['header'] = 'Borrower Details';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['Sex'] = $this->maintenance_model->getSex();
		$data['detail'] = $this->borrower_model->getBorrowerDetails($Id);
		$data['Nationality'] = $this->maintenance_model->getNationality();
		$data['CivilStatus'] = $this->maintenance_model->getCivilStatus();
		$data['Salutation'] = $this->maintenance_model->getSalutation();
		$data['Position'] = $this->maintenance_model->getBorrowerPosition();
		$data['Occupation'] = $this->maintenance_model->getOccupation();
		$data['Industry'] = $this->maintenance_model->getIndustry();
		$data['Status'] = $this->maintenance_model->getBorrowerStatus();

		$data['Reference'] = $this->borrower_model->getReference($Id);
		$data['CoMaker'] = $this->borrower_model->getCoMaker($Id);
		$data['Audit'] = $this->borrower_model->getAudit($Id);
		$data['Ids'] = $this->borrower_model->getSupportingDocuments($Id);
		$data['Spouse'] = $this->borrower_model->getSpouseList($Id);
		$data['Employment'] = $this->borrower_model->getEmploymentList($Id);
		$data['Education'] = $this->borrower_model->getEducationList($Id);

		$data['EmailAddress'] = $this->borrower_model->getBorrowerEmails($Id);
		$data['ContactNumber'] = $this->borrower_model->getBorrowerNumber($Id);
		$data['Address'] = $this->borrower_model->getBorrowerAddress($Id);

		$data['Collections'] = $this->borrower_model->getCollectionsMade($Id);

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('borrowers/BorrowerDetails', $data);
	}

}
