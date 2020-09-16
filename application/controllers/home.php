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
		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/dashboard', $data);
	}
	
	function AddBank()
	{
		$sidebar['sidebar'] = 1;
		$sidebar['sidebarMenu'] = 0;
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
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddConditional', $data);
	}

	function AddRequirement()
	{
		$sidebar['sidebar'] = 1;
		$sidebar['sidebarMenu'] = 0;
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
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddCategory', $data);
	}

	function ViewAssetManagement()
	{
		$sidebar['sidebar'] = 1;
		$sidebar['sidebarMenu'] = 'AssetManagement';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/ViewAssetManagement', $data);
	}

	function AddAssetManagement()
	{
		$sidebar['sidebar'] = 2;
		$sidebar['sidebarMenu'] = 'AssetManagement';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['Category'] = $this->maintenance_model->getCategory();
		$data['access'] = $this->sidebar_model->getAccess();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/AddAssetManagement', $data);
	}

	function AddLoanStatus()
	{
		$sidebar['sidebar'] = 1;
		$sidebar['sidebarMenu'] = 0;
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
	
	function LoanCalculator()
	{
		$sidebar['sidebar'] = 'Loans';
		$sidebar['sidebarMenu'] = 'Loan Calculator';
		$header['header'] = 'Loan Calculator';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();

		$data['repaymentCycle'] = $this->maintenance_model->getRepayments();
		$data['disbursements'] = $this->maintenance_model->getDisbursements();
		$data['requirementType'] = $this->maintenance_model->getRequirementType();
		$data['loanStatus'] = $this->maintenance_model->getLoanStatus();
		$data['borrowerList'] = $this->maintenance_model->getBorrowerList();
		$data['LoanType'] = $this->maintenance_model->getLoanTypes();

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
		$header['header'] = 'Loan Application Detail';
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
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/addUser');
	}

	function addEmployees()
	{
		$sidebar['sidebar'] = 'SystemSetup';
		$sidebar['sidebarMenu'] = 'Employees';
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
		$sidebar['sidebar'] = 'SystemSetup';
		$sidebar['sidebarMenu'] = 'Borrowers';
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
		$sidebar['sidebar'] = 'SystemSetup';
		$sidebar['sidebarMenu'] = 'Borrowers';
		$sidebar['access'] = $this->sidebar_model->checkSideBar();
		$sidebar['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$header['profilePicture'] = $this->sidebar_model->getProfilePicture();
		$data['Sex'] = $this->maintenance_model->getSex();
		$data['detail'] = $this->borrower_model->getBorrowerDetails($Id);
		$data['Nationality'] = $this->maintenance_model->getNationality();
		$data['CivilStatus'] = $this->maintenance_model->getCivilStatus();
		$data['Salutation'] = $this->maintenance_model->getSalutation();
		$data['Position'] = $this->maintenance_model->getBorrowerPosition();
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

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('borrowers/BorrowerDetails', $data);
	}

}
