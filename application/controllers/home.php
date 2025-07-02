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
		$this->load->model('access_model');
    $this->load->library('Pdf');
    date_default_timezone_set('Asia/Manila');

    if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'chrome') != TRUE || strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'edge') != FALSE){
        //Make a redirect to a page forcing the user to use Chrome (Message page)
        echo "Please use chrome";
    }
    else
    {
      if(empty($this->session->userdata("EmployeeNumber")) || $this->session->userdata("logged_in") == 0)
      {
        $DateNow = date("Y-m-d H:i:s");
        $this->session->set_flashdata('logout','Session timed out.'); 
        // audits
          $auditDetail = 'Session timed out.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy'   => $this->session->userdata("EmployeeNumber"),
            'DateCreated' => $DateNow
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        $loginSession = array(
          'logged_in' => 0,
        );
        redirect('');
      }
    }
	}

	function download()
	{
		if($this->uri->segment(3) == 1) // pr
		{
      $Detail = $this->maintenance_model->selectSpecific('exam_has_reviewers', 'ID', $this->uri->segment(4));
	  	$filepath1 = FCPATH.'/uploads/' . $Detail['FileName'];
		}
		else if($this->uri->segment(3) == 2) // hr
		{
      $Detail = $this->maintenance_model->selectSpecific('housing_has_requirements', 'HouseRequirementId', $this->uri->segment(4));
	  	$filepath1 = FCPATH.'/uploads/' . $Detail['FileName'];
		}

    if(file_exists($filepath1)) {
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename=' . basename($Detail['FileTitle']));
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize('uploads/' . $Detail['FileName']));
      readfile('uploads/' . $Detail['FileName']);
			exit(); 
    }
	}



	function Dashboard()
	{
		$sidebar['sidebar'] = 'Dashboard';
		$sidebar['sidebarMenu'] = 'Dashboard';
		$header['header'] = 'Dashboard';
		$data['questions'] = $this->admin_model->getQuestions();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/dashboard', $data);
	}

	function Users()
	{
		$sidebar['sidebar'] = 'Admin';
		$sidebar['sidebarMenu'] = 'Users List';
		$header['header'] = 'Users List';
		$data['questions'] = $this->admin_model->getQuestions();
		$data['employees'] = $this->admin_model->getEmployees();
		$data['students'] = $this->admin_model->getStudentsForUsers();
		$data['role'] = $this->admin_model->getRoles();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/userlist', $data);
	}

	function Employees()
	{
		$sidebar['sidebar'] = 'Admin';
		$sidebar['sidebarMenu'] = 'Employee List';
		$header['header'] = 'Employee List';
		$data['position'] = $this->admin_model->getPositions();
		$data['branch'] = $this->admin_model->getBranches();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/employees', $data);
	}

	function Audit()
	{
		$sidebar['sidebar'] = 'Admin';
		$sidebar['sidebarMenu'] = 'AuditLogs';
		$header['header'] = 'Audit Logs';
		$data['position'] = $this->admin_model->getPositions();
		$data['branch'] = $this->admin_model->getBranches();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/audit', $data);
	}

	function EmployeeDetail()
	{
		$Id = $this->uri->segment(3);
		$sidebar['sidebar'] = 'Admin';
		$sidebar['sidebarMenu'] = 'Employee List';
		$header['header'] = 'Employee List';
    $userDetails = $this->maintenance_model->selectSpecific('R_Employees', 'Id', $Id);
		$data['detail'] = $this->admin_model->getUserDetail($userDetails['EmployeeNumber']);
		$data['position'] = $this->admin_model->getPositions();
		$data['branch'] = $this->admin_model->getBranches();
		$data['status'] = $this->admin_model->getStatus();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/EmployeeDetail', $data);
	}

	function Profile()
	{
		$sidebar['sidebar'] = '';
		$sidebar['sidebarMenu'] = '';
		$header['header'] = 'Profile';
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    if($this->session->userdata('RoleId') == 4) // student
    {
			$data['detail'] = $this->admin_model->getStudentDetails($EmployeeNumber);
    }
    else
    {
			$data['detail'] = $this->admin_model->getUserDetail($EmployeeNumber);
    }
		$data['SecQuestion1'] = $this->admin_model->getEmployeeSecurityQuestions(1);
		$data['SecQuestion2'] = $this->admin_model->getEmployeeSecurityQuestions(2);
		$data['SecQuestion3'] = $this->admin_model->getEmployeeSecurityQuestions(3);
		$data['questions'] = $this->admin_model->getQuestions();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/profile', $data);
	}

	function ClassList()
	{
		$sidebar['sidebar'] = 'Registrar';
		$sidebar['sidebarMenu'] = 'Class list';
		$header['header'] = 'Class list';

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/classlist');
	}

	function FacultyClassList()
	{
		$sidebar['sidebar'] = 'Dashboard';
		$sidebar['sidebarMenu'] = 'Class list';
		$header['header'] = 'Class list';

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('faculty/FacultyClassList');
	}

	function StudentClassList()
	{
		$sidebar['sidebar'] = 'Dashboard';
		$sidebar['sidebarMenu'] = 'Class list';
		$header['header'] = 'Class list';

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('student/classList');
	}

	function SubjectList()
	{
		$sidebar['sidebar'] = 'Registrar';
		$sidebar['sidebarMenu'] = 'Subject list';
		$header['header'] = 'Subject list';

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/subjectList');
	}

	function StudentList()
	{
		$sidebar['sidebar'] = 'Registrar';
		$sidebar['sidebarMenu'] = 'Student list';
		$header['header'] = 'Student list';
		$data['maritalStatus'] = $this->admin_model->getMaritalStatus();
		$data['graduatingStatus'] = $this->admin_model->getGraduatingStatus();
		$data['occupations'] = $this->admin_model->getOccupations();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/studentList', $data);
	}

	function classDetails()
	{
		$sidebar['sidebar'] = 'Registrar';
		$sidebar['sidebarMenu'] = 'Class list';
		$header['header'] = 'Class Details';

		$data['detail'] = $this->admin_model->getClassDetail($this->uri->segment(3));
		$data['subjects'] = $this->admin_model->getSubjects();
		$data['faculty'] = $this->admin_model->getFaculty();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/classDetails', $data);
	}

	function facultyClassDetails()
	{
		$sidebar['sidebar'] = 'Dashboard';
		$sidebar['sidebarMenu'] = 'Class list';
		$header['header'] = 'Class list';

		$data['detail'] = $this->admin_model->getClassDetail($this->uri->segment(3));
		$data['subjects'] = $this->admin_model->getSubjects();
		$data['faculty'] = $this->admin_model->getFaculty();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('faculty/facultyClassDetails', $data);
	}

	function createExam()
	{
		$data['detail'] = $this->admin_model->getSubjectClassDetails($this->uri->segment(3));
		$data['examSchedule'] = $this->admin_model->getSubjectSchedule($this->uri->segment(3));
		$sidebar['sidebar'] = 'Dashboard';
		$sidebar['sidebarMenu'] = 'Class list';
		$header['header'] = 'Examination';

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('faculty/createExam', $data);
	}

	function examDetails()
	{
		$data['detail'] = $this->admin_model->getSubjectExamDetails($this->uri->segment(3));
		$data['examSchedule'] = $this->admin_model->getSubjectSchedule($this->uri->segment(4));
		$sidebar['sidebar'] = 'Dashboard';
		$sidebar['sidebarMenu'] = 'Class list';
		$header['header'] = 'Examination';

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('faculty/examdetails', $data);
	}

	function categoryDetails()
	{
		$data['detail'] = $this->admin_model->getSubjectExamCategoryDetails($this->uri->segment(4));
		$data['subCategories'] = $this->admin_model->getSubjectExamCategorySubDetails($this->uri->segment(4));
		$sidebar['sidebar'] = 'Dashboard';
		$sidebar['sidebarMenu'] = 'Class list';
		$header['header'] = 'Examination';

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('faculty/examcategorydetails', $data);
	}

	function subCategoryDetails()
	{
		$data['detail'] = $this->admin_model->getSubjectExamSubCategoryDetails($this->uri->segment(3));
		$data['subCategories'] = $this->admin_model->getSubjectExamCategorySubDetails($this->uri->segment(4));
		$sidebar['sidebar'] = 'Dashboard';
		$sidebar['sidebarMenu'] = 'Class list';
		$header['header'] = 'Subcategory Questionnaires';

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('faculty/examsubcategoryquestionnaire', $data);
	}

	function takeExam()
	{
		$data['detail'] = $this->admin_model->getSubjectExamDetails($this->uri->segment(3));
		$data['examCategory'] = $this->admin_model->getExamCategories($this->uri->segment(3));
		$sidebar['sidebar'] = 'Dashboard';
		$sidebar['sidebarMenu'] = 'Class list';
		$header['header'] = 'Examination';

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('Examination/takeExam', $data);
	}

	function viewClass()
	{
		$sidebar['sidebar'] = 'Dashboard';
		$sidebar['sidebarMenu'] = 'Class list';
		$header['header'] = 'Class list';

		$data['detail'] = $this->admin_model->getClassDetail($this->uri->segment(3));
		$data['subjects'] = $this->admin_model->getSubjects();
		$data['faculty'] = $this->admin_model->getFaculty();

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('student/classDetails', $data);
	}

	function FacultysubjectStudents()
	{
		$sidebar['sidebar'] = 'Dashboard';
		$sidebar['sidebarMenu'] = 'Class list';
		$header['header'] = 'Class list';

		$data['detail'] = $this->admin_model->getSubjectClassDetails($this->uri->segment(3));
		$data['students'] = $this->admin_model->getStudents($this->uri->segment(3));

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('admin/subjectStudents', $data);
	}

	function subjectStudents()
	{
		$sidebar['sidebar'] = 'Dashboard';
		$sidebar['sidebarMenu'] = 'Class list';
		$header['header'] = 'Class list';

		$data['detail'] = $this->admin_model->getSubjectClassDetails($this->uri->segment(3));
		$data['hasTakenExam'] = $this->admin_model->studentHasExam($this->uri->segment(3));
		$data['examDetails'] = $this->admin_model->studentExamDetails($this->uri->segment(3));
		$data['examSchedule'] = $this->admin_model->getSubjectSchedule($this->uri->segment(3));
		/* MOCK EXAM */
		$data['countExamCreated'] = $this->admin_model->countMockExam($this->uri->segment(3));

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('student/subjectDetails', $data);
	}

	function viewExam()
	{
		$data['detail'] = $this->admin_model->getSubjectExamDetails($this->uri->segment(3));
		$data['examCategory'] = $this->admin_model->getExamCategories($this->uri->segment(3));
		$data['correctAnswer'] = $this->admin_model->countCorrectAnswers($this->uri->segment(3));
		$data['wrongAnswer'] = $this->admin_model->countIncorrectAnswers($this->uri->segment(3));
		$sidebar['sidebar'] = 'Dashboard';
		$sidebar['sidebarMenu'] = 'Class list';
		$header['header'] = 'Examination';

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('Examination/viewExam', $data);
	}

	function viewExamFormat()
	{
		$data['detail'] = $this->admin_model->getSubjectExamDetails($this->uri->segment(3));
		$data['examCategory'] = $this->admin_model->getExamCategories($this->uri->segment(3));
		$data['correctAnswer'] = $this->admin_model->countCorrectAnswers($this->uri->segment(3));
		$data['wrongAnswer'] = $this->admin_model->countIncorrectAnswers($this->uri->segment(3));
		$sidebar['sidebar'] = 'Dashboard';
		$sidebar['sidebarMenu'] = 'Class list';
		$header['header'] = 'Examination';

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('Examination/viewExamFormat', $data);
	}

	function ScheduleExam()
	{
		$sidebar['sidebar'] = 'Registrar';
		$sidebar['sidebarMenu'] = 'Schedule list';
		$header['header'] = 'Examination Schedule List';

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('registrar/ScheduleExam');
	}

	function registrarClassList()
	{
		$data['detail'] = $this->admin_model->getSubjectClassDetails($this->uri->segment(3));
		$sidebar['sidebar'] = 'Registrar';
		$sidebar['sidebarMenu'] = 'Schedule list';
		$header['header'] = 'Examination Schedule';

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('registrar/scheduleClass', $data);
	}

	function FacultyRetakeApproval()
	{
		$sidebar['sidebar'] = 'Faculty';
		$sidebar['sidebarMenu'] = 'Exam Retake';
		$header['header'] = 'Approval for Retake of Examination';

		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('faculty/examRetake');
	}

	function generateStudents()
	{
		$sidebar['sidebar'] = 'Registrar Reports';
		$sidebar['sidebarMenu'] = 'Students with Subjects';
		$header['header'] = 'Generate Students with Subjects';

		$data['subjects'] = $this->admin_model->getClassSubjects();
		$this->load->view('includes/header', $header);
		$this->load->view('includes/sidebar', $sidebar);
		$this->load->view('registrar/studentList', $data);
	}
}