<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class datatables_controller extends CI_Controller {

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
		$this->load->model('loanApplication_model');

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

	function Users()
	{
		$result = $this->maintenance_model->getAllUsers();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['EmployeeNumber']);
		}
		echo json_encode($result);
	}

	function Employees()
	{
		$result = $this->maintenance_model->getAllEmployees();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['EmployeeNumber']);
		}
		echo json_encode($result);
	}

	function Borrowers()
	{
		$result = $this->maintenance_model->getAllBorrowers();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['EmployeeNumber']);
		}
		echo json_encode($result);
	}

	function Banks()
	{
		$result = $this->maintenance_model->getAllBanks();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function Branches()
	{
		$result = $this->maintenance_model->getAllBranches();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function Loans()
	{
		$result = $this->maintenance_model->getAllLoans();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function Charges()
	{
		$result = $this->maintenance_model->getAllCharges();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function Repayments()
	{
		$result = $this->maintenance_model->getAllRepayments();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function Disbursements()
	{
		$result = $this->maintenance_model->getAllDisbursements();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function OptionalCharges()
	{
		$result = $this->maintenance_model->getAllOptional();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function Requirements()
	{
		$result = $this->maintenance_model->getAllRequirements();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function Positions()
	{
		$result = $this->maintenance_model->getAllPositions();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function Purposes()
	{
		$result = $this->maintenance_model->getAllPurposes();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function Methods()
	{
		$result = $this->maintenance_model->getAllMethods();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function Categories()
	{
		$result = $this->maintenance_model->getAllCategories();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function Assets()
	{
		$result = $this->maintenance_model->getAllAssets();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function LoanStatus()
	{
		$result = $this->maintenance_model->getAllLoanStatus();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function BorrowerStatus()
	{
		$result = $this->maintenance_model->getAllBorrowerStatus();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function Industry()
	{
		$result = $this->maintenance_model->getAllIndustry();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function Education()
	{
		$result = $this->maintenance_model->getAllEducation();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['EmployeeNumber']);
		}
		echo json_encode($result);
	}

	function Occupations()
	{
		$result = $this->maintenance_model->getAllOccupation();
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function managerNotifications()
	{
		$Id = $this->uri->segment(3);
		$result = $this->employee_model->managerNotifications($Id);
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function employeeAudit()
	{
		$Id = $this->uri->segment(3);
		$result = $this->employee_model->employeeAudit($Id);
		foreach($result as $key=>$row)
		{
			$result[$key]['Name'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function displayAllLoans()
	{
		$result = $this->loanApplication_model->displayAllLoans();
		foreach($result as $key=>$row)
		{
			$result[$key]['CreatedBy'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function displayBorrowerLoans()
	{
		$Id = $this->uri->segment(3);
		$result = $this->loanApplication_model->displayBorrowerLoans($Id);
		foreach($result as $key=>$row)
		{
			$result[$key]['CreatedBy'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

	function displayAllApprovals()
	{
		$result = $this->loanApplication_model->displayAllApprovals();
		foreach($result as $key=>$row)
		{
			$result[$key]['CreatedBy'] = $this->maintenance_model->getUserCreated($row['CreatedBy']);
		}
		echo json_encode($result);
	}

}
