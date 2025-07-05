<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//test
class admin_controller extends CI_Controller {

  /**
   * Index Page for this controller.
   *
   * Maps to the following URL
   *    http://example.com/index.php/welcome
   *  - or -
   *    http://example.com/index.php/welcome/index
   *  - or -
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
    $this->load->model('admin_model');
    date_default_timezone_set('Asia/Manila');
    $this->load->library('Pdf');

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

  /* EMPLOYEES */
  function getEmployeeList()
  {
    $result = $this->admin_model->getEmployeeList();
    echo json_encode($result);
  }

  function addEmployee()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    $data = array(
      'Column' => " WHERE FirstName = '".$_POST['FirstName']."'
                    AND MiddleName = '".$_POST['MiddleName']."'
                    AND LastName = '".$_POST['LastName']."'
                    AND ExtName = '".$_POST['ExtName']."'",
      'Table' => 'R_Employees',
    );
    $query = $this->admin_model->countRecord($data);
    if($query == 0) // not existing
    {
      // audits
        $auditDetail = 'Added employee.';
        $insertData = array(
          'Description' => $auditDetail,
          'CreatedBy'   => $EmployeeNumber,
        );
        $auditTable = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // insert
        $insertData2 = array(
          'FirstName'     => $_POST['FirstName'],
          'MiddleName'    => $_POST['MiddleName'],
          'LastName'      => $_POST['LastName'],
          'ExtName'       => $_POST['ExtName'],
          'BranchId'      => $_POST['BranchId'],
          'PositionId'    => $_POST['PositionId'],
          'CreatedBy'     => $EmployeeNumber,
          'StatusId'      => 1,
        );
        $insertTable2 = 'R_Employees';
        $this->maintenance_model->insertFunction($insertData2, $insertTable2);
      // get generated id
        $generatedIdData = array(
          'table'                     => 'R_Employees'
          , 'column'                  => 'Id'
          , 'CreatedBy'               => $EmployeeNumber
        );
        $NewId = $this->maintenance_model->getGeneratedId2($generatedIdData);
        $TransactionNumber = sprintf('%05d', $NewId['Id']);
        $set = array( 
          'EmployeeNumber'            => $TransactionNumber,
        );

        $condition = array( 
          'ID' => $NewId['Id'],
        );
        $table = 'R_Employees';
        $this->maintenance_model->updateFunction1($set, $condition, $table);

      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Record successfully added!'); 
        $this->session->set_flashdata('alertType','success'); 
    }
    else
    {
      // notification
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Record already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
    }
    
    redirect('home/Employees');
  }

  function editEmployee()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $Id = $this->uri->segment(3);
    $DateNow = date("Y-m-d H:i:s");
    $data = array(
      'Column' => " WHERE FirstName = '".$_POST['FirstName']."'
                    AND MiddleName = '".$_POST['MiddleName']."'
                    AND LastName = '".$_POST['LastName']."'
                    AND ExtName = '".$_POST['ExtName']."'",
      'Table' => 'R_Employees',
    );
    $query = $this->admin_model->countRecord($data);
    $userDetails = $this->maintenance_model->selectSpecific('R_Employees', 'Id', $Id);
    if($query == 0) // not existing
    {
      // audits
        $auditDetail = 'Edited employee number #'.$userDetails['EmployeeNumber'].'.';
        $insertData = array(
          'Description' => $auditDetail,
          'CreatedBy'   => $EmployeeNumber,
        );
        $auditTable = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData, $auditTable);

      // audits 2
        $auditDetail2 = 'Edited information.';
        $insertData2 = array(
          'Description' => $auditDetail2,
          'CreatedBy' => $EmployeeNumber,
          'NotifyTo' => $userDetails['EmployeeNumber'],
        );
        $auditTable2 = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData2, $auditTable2);
      // update
        $set = array( 
          'FirstName'     => $_POST['FirstName'],
          'MiddleName'    => $_POST['MiddleName'],
          'LastName'      => $_POST['LastName'],
          'ExtName'       => $_POST['ExtName'],
          'BranchId'      => $_POST['BranchId'],
          'PositionId'    => $_POST['PositionId'],
          'StatusId'      => $_POST['StatusId'],
        );

        $condition = array( 
          'ID' => $Id
        );
        $table = 'R_Employees';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Record successfully added!'); 
        $this->session->set_flashdata('alertType','success'); 
    }
    else
    {
      // audits
        $auditDetail = 'Edited employee number #'.$userDetails['EmployeeNumber'].'.';
        $insertData = array(
          'Description' => $auditDetail,
          'CreatedBy'   => $EmployeeNumber,
        );
        $auditTable = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData, $auditTable);

      // audits 2
        $auditDetail2 = 'Edited information.';
        $insertData2 = array(
          'Description' => $auditDetail2,
          'CreatedBy' => $EmployeeNumber,
          'NotifyTo' => $userDetails['EmployeeNumber'],
        );
        $auditTable2 = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData2, $auditTable2);
      // update
        $set = array( 
          'BranchId'      => $_POST['BranchId'],
          'PositionId'    => $_POST['PositionId'],
          'StatusId'      => $_POST['StatusId'],
        );

        $condition = array( 
          'ID' => $Id
        );
        $table = 'R_Employees';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // notification
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Employee data already existing! Only updated branch, position and status update!'); 
        $this->session->set_flashdata('alertType','warning'); 
    }
    
    redirect('home/EmployeeDetail/'. $Id);
  }
  /* END OF EMPLOYEES */

  /* USERS */
  function getUserList()
  {
    $result = $this->admin_model->getUserList();
    echo json_encode($result);
  }

  function getAuditLogs()
  {
    $result = $this->admin_model->getAuditLogs();
    echo json_encode($result);
  }

  function getUserLogs()
  {
    $result = $this->admin_model->getUserLogs();
    echo json_encode($result);
  }

  function getViewLogs()
  {
    $result = $this->admin_model->getViewLogs($this->uri->segment(3));
    echo json_encode($result);
  }

  function updateSecurityQuestions()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    // audits
      $auditDetail = 'Security question updated.';
      $LogAuditData = array(
        'Description' => $auditDetail,
        'CreatedBy' => $EmployeeNumber
      );
      $logAuditTable = 'R_Logs';
      $this->maintenance_model->insertFunction($LogAuditData, $logAuditTable);
    // Update Security Question
      $set = array( 
        'StatusId' => 0
      );

      $condition = array( 
        'EmployeeNumber' => $EmployeeNumber
      );
      $table = 'question_has_answer';
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
      $auditTable2 = 'question_has_answer';
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
      $auditTable2 = 'question_has_answer';
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
      $auditTable2 = 'question_has_answer';
      $this->maintenance_model->insertFunction($insertData2, $auditTable2);
    // notification
      $this->session->set_flashdata('alertTitle','Success!'); 
      $this->session->set_flashdata('alertText','Security question successfully set!'); 
      $this->session->set_flashdata('alertType','success'); 
    
    redirect('home/Profile/');
  }

  function addUser()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");

    print_r($this->uri->segment(3));
    if($this->uri->segment(3) == 1) // update Security Question
    {
      // audits
        $auditDetail = 'Changed temporary password.';
        $insertData = array(
          'Description' => $auditDetail,
          'CreatedBy' => $EmployeeNumber,
        );
        $auditTable = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // Update Security Question
        $set = array( 
          'StatusId' => 0
        );

        $condition = array( 
          'EmployeeNumber' => $EmployeeNumber
        );
        $table = 'question_has_answer';
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
        $auditTable2 = 'question_has_answer';
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
        $auditTable2 = 'question_has_answer';
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
        $auditTable2 = 'question_has_answer';
        $this->maintenance_model->insertFunction($insertData2, $auditTable2);

      // update temporary password
        $set = array( 
          'Password'  => $_POST['NewPassword'],
          'IsNew'     => 0
        );

        $condition = array( 
          'EmployeeNumber' => $EmployeeNumber
        );
        $table = 'r_users';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // notification
        $this->session->set_flashdata('logout','Temporary password successfully changed.'); 
      
        $loginSession = array(
          'logged_in' => 0,
        );
        $this->session->set_userdata($loginSession);
        redirect(site_url());
        session_destroy();
    }
    else if($this->uri->segment(3) == 2) // add employee user
    {
      $data = array(
        'Column'  => " WHERE 
                      EmployeeNumber = '".$_POST['EmployeeNumber']."'
        ",
        'Table'   => 'r_users',
      );
      $query = $this->admin_model->countRecord($data);
      if($query == 0) // not existing
      {
        // insert
          $insertRoles = array(
            'EmployeeNumber'                    => $_POST['EmployeeNumber']
            , 'Password'                        => $_POST['EmployeeNumber']
            , 'RoleId'                          => $_POST['RoleId']
            , 'isNew'                           => 1
            , 'StatusId'                        => 1
            , 'CreatedBy'                       => $EmployeeNumber
          );
          $insertRoleTable = 'r_users';
          $this->maintenance_model->insertFunction($insertRoles, $insertRoleTable);
        // admin audits
          $auditDetail = 'Employee #' . $_POST['EmployeeNumber'] . ' has been added as user.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy'   => $EmployeeNumber,
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Successfully added user!'); 
          $this->session->set_flashdata('alertType','success'); 
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Record already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
      }
        
        redirect('home/users');
    }
    else // add student user
    {
      $data = array(
        'Column'  => " WHERE 
                      EmployeeNumber = '".$_POST['EmployeeNumber']."'
        ",
        'Table'   => 'r_users',
      );
      $query = $this->admin_model->countRecord($data);
      if($query == 0) // not existing
      {
        // insert
          $insertRoles = array(
            'EmployeeNumber'                    => $_POST['EmployeeNumber']
            , 'Password'                        => $_POST['EmployeeNumber']
            , 'RoleId'                          => 4
            , 'isNew'                           => 1
            , 'StatusId'                        => 1
            , 'CreatedBy'                       => $EmployeeNumber
          );
          $insertRoleTable = 'r_users';
          $this->maintenance_model->insertFunction($insertRoles, $insertRoleTable);
        // admin audits
          $auditDetail = 'Student #' . $_POST['EmployeeNumber'] . ' has been added as user.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy'   => $EmployeeNumber,
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Successfully added user!'); 
          $this->session->set_flashdata('alertType','success'); 
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Record already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
      }
        
        redirect('home/users');
    }
  }

  function getCurrentPassword()
  {
    $output = $this->admin_model->getCurrentPassword(htmlentities($this->input->post('Password'), ENT_QUOTES), htmlentities($this->input->post('EmployeeNumber'), ENT_QUOTES));
    $this->output->set_output(print(json_encode($output)));
    exit();
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
      $table = 'r_users';
      $this->maintenance_model->updateFunction1($set, $condition, $table);

    // audits
      $auditDetail = 'Changed password.';
      $insertData = array(
        'Description' => $auditDetail,
        'CreatedBy' => $EmployeeNumber,
        'NotifyTo'  => $EmployeeNumber,
      );
      $auditTable = 'R_Logs';
      $this->maintenance_model->insertFunction($insertData, $auditTable);
    // notification
      $this->session->set_flashdata('alertTitle','Success!'); 
      $this->session->set_flashdata('alertText','Password successfully changed!'); 
      $this->session->set_flashdata('alertType','success'); 
    
    redirect('');
    session_destroy();
  }

  function UpdateUser()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    if($_POST['Type'] == 1) // deactivate
    {
        $set = array( 
          'StatusId' => 2
        );

        $condition = array( 
          'Id' => $_POST['Id']
        );
        $table = 'r_users';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // audits
        $userDetails = $this->maintenance_model->selectSpecific('r_users', 'Id', $_POST['Id']);
        $auditDetail = 'Deactivated employee number #'.$userDetails['EmployeeNumber'].' from users.';
        $insertData = array(
          'Description' => $auditDetail,
          'CreatedBy' => $EmployeeNumber,
        );
        $auditTable = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // audits 2
        $auditDetail2 = 'Deactivated account.';
        $insertData2 = array(
          'Description' => $auditDetail2,
          'CreatedBy' => $EmployeeNumber,
          'NotifyTo' => $userDetails['EmployeeNumber'],
        );
        $auditTable2 = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData2, $auditTable2);
    }
    else if($_POST['Type'] == 2) // reactivate
    {
        $set = array( 
          'StatusId' => 1
        );

        $condition = array( 
          'Id' => $_POST['Id']
        );
        $table = 'r_users';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // audits
        $userDetails = $this->maintenance_model->selectSpecific('r_users', 'Id', $_POST['Id']);
        $auditDetail = 'Re-activated employee number #'.$userDetails['EmployeeNumber'].' from users.';
        $insertData = array(
          'Description' => $auditDetail,
          'CreatedBy' => $EmployeeNumber,
        );
        $auditTable = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // audits 2
        $auditDetail2 = 'Re-activated account.';
        $insertData2 = array(
          'Description' => $auditDetail2,
          'CreatedBy' => $EmployeeNumber,
          'NotifyTo' => $userDetails['EmployeeNumber'],
        );
        $auditTable2 = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData2, $auditTable2);
    }
    else if($_POST['Type'] == 3) // reset password
    {
      $userDetails = $this->maintenance_model->selectSpecific('r_users', 'Id', $_POST['Id']);
        $set = array( 
          'Password' => $userDetails['EmployeeNumber'],
          'IsNew' => 1
        );

        $condition = array( 
          'Id' => $_POST['Id']
        );
        $table = 'r_users';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // audits
        $auditDetail = 'Reset password for employee number #'.$userDetails['EmployeeNumber'].' from users.';
        $insertData = array(
          'Description' => $auditDetail,
          'CreatedBy' => $EmployeeNumber,
        );
        $auditTable = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // audits 2
        $auditDetail2 = 'Reset password.';
        $insertData2 = array(
          'Description' => $auditDetail2,
          'CreatedBy' => $EmployeeNumber,
          'NotifyTo' => $userDetails['EmployeeNumber'],
        );
        $auditTable2 = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData2, $auditTable2);
    }
    else if($_POST['Type'] == 4) // update role
    {
      $userDetails = $this->maintenance_model->selectSpecific('r_users', 'Id', $_POST['ID']);
      $newRoleDetails = $this->maintenance_model->selectSpecific('r_roles', 'Id', $_POST['RoleId']);
      $oldRoleDetails = $this->maintenance_model->selectSpecific('r_roles', 'Id', $userDetails['RoleId']);
      // audits
        $auditDetail = 'Changed role for employee number #'.$userDetails['EmployeeNumber'].' from '.$oldRoleDetails['Description'].' to '.$newRoleDetails['Description'].'';
        $insertData = array(
          'Description' => $auditDetail,
          'CreatedBy' =>  $EmployeeNumber,
        );
        $auditTable = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // audits 2
        $auditDetail2 = 'Changed role from '.$oldRoleDetails['Description'].' to '.$newRoleDetails['Description'].'';
        $insertData2 = array(
          'Description' => $auditDetail2,
          'CreatedBy' => $EmployeeNumber,
          'NotifyTo' => $userDetails['EmployeeNumber'],
        );
        $auditTable2 = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData2, $auditTable2);
      // update
        $set = array( 
          'RoleId' => $_POST['RoleId'],
        );

        $condition = array( 
          'Id' => $_POST['ID']
        );
        $table = 'r_users';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Record successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/users');
    }
    $output = 'OK';
    $this->output->set_output(print(json_encode($output)));
    exit();
  }
  /* END OF USERS */

  /* SUBJECTS */
  function addSubject()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    $data = array(
      'Column' => " WHERE Code = '".htmlentities(preg_replace('/\s+/', ' ', $_POST['Code']), ENT_QUOTES)."'",
      'Table' => 'r_subjects',
    );
    $query = $this->admin_model->countRecord($data);
    if($query == 0) // not existing
    {
      // audits
        $auditDetail = 'Added subject '.htmlentities(preg_replace('/\s+/', ' ', $_POST['Code']), ENT_QUOTES).'.';
        $insertData = array(
          'Description' => $auditDetail,
          'CreatedBy'   => $EmployeeNumber,
        );
        $auditTable = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // insert subject
        $insertData2 = array(
          'Code'            => htmlentities(preg_replace('/\s+/', ' ', $_POST['Code']), ENT_QUOTES),
          'Name'            => htmlentities(preg_replace('/\s+/', ' ', $_POST['Name']), ENT_QUOTES),
          'Units'           => htmlentities(preg_replace('/\s+/', ' ', $_POST['Units']), ENT_QUOTES),
          'Description'     => htmlentities(preg_replace('/\s+/', ' ', $_POST['Description']), ENT_QUOTES),
          'CreatedBy'       => $EmployeeNumber,
          'StatusId'        => 1,
        );
        $insertTable2 = 'r_subjects';
        $this->maintenance_model->insertFunction($insertData2, $insertTable2);
      
      // get generated subject id
        $generatedIdData = array(
          'table'     => 'r_subjects',
          'column'    => 'Id',
          'CreatedBy' => $EmployeeNumber
        );
        $NewSubjectId = $this->maintenance_model->getGeneratedId2($generatedIdData);
      
      // insert faculty assignments if any
        if(isset($_POST['FacultyIds']) && is_array($_POST['FacultyIds'])) {
          foreach($_POST['FacultyIds'] as $facultyId) {
            // Check if faculty assignment already exists
            $checkData = array(
              'Column' => " WHERE SubjectId = '".$NewSubjectId['Id']."' AND FacultyId = '".$facultyId."'",
              'Table' => 'subject_has_faculty',
            );
            $existingRecord = $this->admin_model->countRecord($checkData);
            
            if($existingRecord > 0) {
              // Update existing record to active status
              $updateData = array('StatusId' => 1);
              $updateCondition = array('SubjectId' => $NewSubjectId['Id'], 'FacultyId' => $facultyId);
              $this->maintenance_model->updateFunction1($updateData, $updateCondition, 'subject_has_faculty');
            } else {
              // Insert new record
              $insertFacultyData = array(
                'SubjectId'   => $NewSubjectId['Id'],
                'FacultyId'   => $facultyId,
                'StatusId'    => 1,
                'CreatedBy'   => $EmployeeNumber,
              );
              $this->maintenance_model->insertFunction($insertFacultyData, 'subject_has_faculty');
            }
          }
        }
      
      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Record successfully added!'); 
        $this->session->set_flashdata('alertType','success'); 
    }
    else
    {
      // notification
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Record already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
    }
    
    redirect('home/SubjectList');
  }

  function getFacultyBySubject()
  {
    $subjectId = $this->input->post('SubjectId');
    $result = $this->admin_model->getFacultyBySubject($subjectId);
    echo json_encode($result);
  }

  function getAllFacultyForSubjectAssignment()
  {
    $result = $this->admin_model->getAllFacultyForSubjectAssignment();
    echo json_encode($result);
  }

  function getAssignedFacultyBySubject()
  {
    $subjectId = $this->input->post('SubjectId');
    $result = $this->admin_model->getAssignedFacultyBySubject($subjectId);
    echo json_encode($result);
  }

  function getSubjectList()
  {
    $result = $this->admin_model->getSubjectList();
    echo json_encode($result);
  }

  function editSubject()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    $SubjectId = $_POST['Id'];
    
    // audits
      $auditDetail = 'Edited subject '.htmlentities(preg_replace('/\s+/', ' ', $_POST['Code']), ENT_QUOTES).'.';
      $insertData = array(
        'Description' => $auditDetail,
        'CreatedBy'   => $EmployeeNumber,
      );
      $auditTable = 'R_Logs';
      $this->maintenance_model->insertFunction($insertData, $auditTable);
    
    // edit subject details
      $set = array( 
          'Name'            => htmlentities(preg_replace('/\s+/', ' ', $_POST['Name']), ENT_QUOTES),
          'Units'           => htmlentities(preg_replace('/\s+/', ' ', $_POST['Units']), ENT_QUOTES),
          'Description'     => htmlentities(preg_replace('/\s+/', ' ', $_POST['Description']), ENT_QUOTES),
      );

      $condition = array( 
        'ID' => $SubjectId,
      );
      $table = 'R_Subjects';
      $this->maintenance_model->updateFunction1($set, $condition, $table);
    
    // Update faculty assignments
      // First, deactivate existing faculty assignments
      $setFaculty = array('StatusId' => 2);
      $conditionFaculty = array('SubjectId' => $SubjectId);
      $this->maintenance_model->updateFunction1($setFaculty, $conditionFaculty, 'subject_has_faculty');
      
      // Then activate selected faculty assignments
      if(isset($_POST['FacultyIds']) && is_array($_POST['FacultyIds'])) {
        foreach($_POST['FacultyIds'] as $facultyId) {
          // Check if faculty assignment already exists
          $checkData = array(
            'Column' => " WHERE SubjectId = '".$SubjectId."' AND FacultyId = '".$facultyId."'",
            'Table' => 'subject_has_faculty',
          );
          $existingRecord = $this->admin_model->countRecord($checkData);
          
          if($existingRecord > 0) {
            // Update existing record to active status
            $updateData = array('StatusId' => 1);
            $updateCondition = array('SubjectId' => $SubjectId, 'FacultyId' => $facultyId);
            $this->maintenance_model->updateFunction1($updateData, $updateCondition, 'subject_has_faculty');
          } else {
            // Insert new record
            $insertFacultyData = array(
              'SubjectId'   => $SubjectId,
              'FacultyId'   => $facultyId,
              'StatusId'    => 1,
              'CreatedBy'   => $EmployeeNumber,
            );
            $this->maintenance_model->insertFunction($insertFacultyData, 'subject_has_faculty');
          }
        }
      }
    
    // notification
      $this->session->set_flashdata('alertTitle','Success!'); 
      $this->session->set_flashdata('alertText','Record successfully updated!'); 
      $this->session->set_flashdata('alertType','success'); 
    
    redirect('home/SubjectList');
  }

  function updateSubjectRecord()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    if($_POST['Type'] == 1) // deactivate
    {
        $set = array( 
          'StatusId' => 2
        );

        $condition = array( 
          'Id' => $_POST['Id']
        );
        $table = 'r_subjects';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // audits
        $userDetails = $this->maintenance_model->selectSpecific('r_subjects', 'Id', $_POST['Id']);
        $auditDetail = 'Deactivated '.$userDetails['Code'].'-'.$userDetails['Name'].' from subject list.';
        $insertData = array(
          'Description' => $auditDetail,
          'CreatedBy' => $EmployeeNumber,
        );
        $auditTable = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
    }
    else if($_POST['Type'] == 2) // reactivate
    {
        $set = array( 
          'StatusId' => 1
        );

        $condition = array( 
          'Id' => $_POST['Id']
        );
        $table = 'r_subjects';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // audits
        $userDetails = $this->maintenance_model->selectSpecific('r_subjects', 'Id', $_POST['Id']);
        $auditDetail = 'Re-activated '.$userDetails['Code'].'-'.$userDetails['Name'].' from subject list.';
        $insertData = array(
          'Description' => $auditDetail,
          'CreatedBy' => $EmployeeNumber,
        );
        $auditTable = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
    }
    $output = 'OK';
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function manageSubjectFaculty()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $SubjectId = $_POST['SubjectId'];
    
    // Deactivate existing faculty assignments
    $set = array('StatusId' => 2);
    $condition = array('SubjectId' => $SubjectId);
    $this->maintenance_model->updateFunction1($set, $condition, 'subject_has_faculty');
    
    // Activate selected faculty assignments
    if(isset($_POST['FacultyIds']) && is_array($_POST['FacultyIds'])) {
      foreach($_POST['FacultyIds'] as $facultyId) {
        // Check if faculty assignment already exists
        $checkData = array(
          'Column' => " WHERE SubjectId = '".$SubjectId."' AND FacultyId = '".$facultyId."'",
          'Table' => 'subject_has_faculty',
        );
        $existingRecord = $this->admin_model->countRecord($checkData);
        
        if($existingRecord > 0) {
          // Update existing record to active status
          $updateData = array('StatusId' => 1);
          $updateCondition = array('SubjectId' => $SubjectId, 'FacultyId' => $facultyId);
          $this->maintenance_model->updateFunction1($updateData, $updateCondition, 'subject_has_faculty');
        } else {
          // Insert new record
          $insertData = array(
            'SubjectId'   => $SubjectId,
            'FacultyId'   => $facultyId,
            'StatusId'    => 1,
            'CreatedBy'   => $EmployeeNumber,
          );
          $this->maintenance_model->insertFunction($insertData, 'subject_has_faculty');
        }
      }
    }
    
    // audit trail
    $subjectDetails = $this->maintenance_model->selectSpecific('r_subjects', 'Id', $SubjectId);
    $auditDetail = 'Updated faculty assignments for subject '.$subjectDetails['Code'].' - '.$subjectDetails['Name'];
    $insertData = array(
      'Description' => $auditDetail,
      'CreatedBy'   => $EmployeeNumber,
    );
    $auditTable = 'R_Logs';
    $this->maintenance_model->insertFunction($insertData, $auditTable);
    
    $output = 'OK';
    echo json_encode($output);
  }

  function validateSubjectFaculty()
  {
    $subjectId = $this->input->post('SubjectId');
    $facultyId = $this->input->post('FacultyId');
    
    // Check if faculty is assigned to this subject
    $checkData = array(
      'Column' => " WHERE SubjectId = '$subjectId' AND FacultyId = '$facultyId' AND StatusId = 1",
      'Table' => 'subject_has_faculty',
    );
    $result = $this->admin_model->countRecord($checkData);
    
    echo json_encode(array('isValid' => $result > 0));
  }
  /* END OF SUBJECT */

  /* CLASS LIST */
    function addClassList()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      $data = array(
        'Column' => " WHERE Name = '".htmlentities(preg_replace('/\s+/', ' ', $_POST['Name']), ENT_QUOTES)."'",
        'Table' => 'r_classlist',
      );
      $query = $this->admin_model->countRecord($data);
      if($query == 0) // not existing
      {
        // audits
        $auditDetail = 'Added class '.htmlentities(preg_replace('/\s+/', ' ', $_POST['Name']), ENT_QUOTES).'.';
        $insertData = array(
          'Description' => $auditDetail,
          'CreatedBy'   => $EmployeeNumber,
        );
        $auditTable = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
        // insert
        $insertData2 = array(
          'Name'        => htmlentities(preg_replace('/\s+/', ' ', $_POST['Name']), ENT_QUOTES),
          'Description' => htmlentities(preg_replace('/\s+/', ' ', $_POST['Description']), ENT_QUOTES),
          'FacultyId'   => $_POST['FacultyId'], 
          'CreatedBy'   => $EmployeeNumber,
          'StatusId'    => 1,
        );
        $insertTable2 = 'r_classlist';
        $this->maintenance_model->insertFunction($insertData2, $insertTable2);
        // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Record successfully added!'); 
        $this->session->set_flashdata('alertType','success'); 
      }
      else
      {
        // notification
        $this->session->set_flashdata('alertTitle','Warning!'); 
        $this->session->set_flashdata('alertText','Record already existing!'); 
        $this->session->set_flashdata('alertType','warning'); 
      }
      redirect('home/ClassList');
    }

    function getClassList()
    {
      $result = $this->admin_model->getClassList();
      echo json_encode($result);
    }

    function editClassList()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      // audits
      $auditDetail = 'Edited class '.$_POST['Name'].'.';
      $insertData = array(
        'Description' => $auditDetail,
        'CreatedBy'   => $EmployeeNumber,
      );
      $auditTable = 'R_Logs';
      $this->maintenance_model->insertFunction($insertData, $auditTable);
      // edit
      $set = array( 
        'Name'        => htmlentities(preg_replace('/\s+/', ' ', $_POST['Name']), ENT_QUOTES),
        'Description' => htmlentities(preg_replace('/\s+/', ' ', $_POST['Description']), ENT_QUOTES),
        'FacultyId'   => $_POST['FacultyId'], 
      );

      $condition = array( 
        'ID' => $_POST['Id'],
      );
      $table = 'r_classlist';
      $this->maintenance_model->updateFunction1($set, $condition, $table);
      // notification
      $this->session->set_flashdata('alertTitle','Success!'); 
      $this->session->set_flashdata('alertText','Record successfully added!'); 
      $this->session->set_flashdata('alertType','success'); 
      redirect('home/ClassList');
    }

    function updateClassListRecord()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      if($_POST['Type'] == 1) // deactivate
      {
          $set = array( 
            'StatusId' => 2
          );

          $condition = array( 
            'Id' => $_POST['Id']
          );
          $table = 'r_classlist';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // audits
          $userDetails = $this->maintenance_model->selectSpecific('r_classlist', 'Id', $_POST['Id']);
          $auditDetail = 'Deactivated '.$userDetails['Name'].' from class list.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $EmployeeNumber,
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
      }
      else if($_POST['Type'] == 2) // reactivate
      {
          $set = array( 
            'StatusId' => 1
          );

          $condition = array( 
            'Id' => $_POST['Id']
          );
          $table = 'r_classlist';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // audits
          $userDetails = $this->maintenance_model->selectSpecific('r_classlist', 'Id', $_POST['Id']);
          $auditDetail = 'Re-activated '.$userDetails['Name'].' from class list.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $EmployeeNumber,
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
      }
      $output = 'OK';
      $this->output->set_output(print(json_encode($output)));
      exit();
    }
  /* END OF CLASS LIST */

  /* STUDENT LIST */
    function addStudentList()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      $data = array(
        'Column' => " WHERE FirstName = '".$_POST['FirstName']."'
                      AND MiddleName = '".$_POST['MiddleName']."'
                      AND LastName = '".$_POST['LastName']."'
                      AND ExtName = '".$_POST['ExtName']."'
        ",
        'Table' => 'r_students',
      );
      $query = $this->admin_model->countRecord($data);
      if($query == 0) // not existing
      {
        // insert
          $insertData2 = array(
            'FirstName'     => htmlentities(preg_replace('/\s+/', ' ', $_POST['FirstName']), ENT_QUOTES),
            'MiddleName'    => htmlentities(preg_replace('/\s+/', ' ', $_POST['MiddleName']), ENT_QUOTES),
            'LastName'      => htmlentities(preg_replace('/\s+/', ' ', $_POST['LastName']), ENT_QUOTES),
            'ExtName'       => htmlentities(preg_replace('/\s+/', ' ', $_POST['ExtName']), ENT_QUOTES),

            'addressLine'       => $_POST['studentAddressLine'],
            'barangayId'       => $_POST['BarangayId'],
            'studentContactNo'       => $_POST['studentContactNumber'] ?? null,
            'studentEmailAddress'       => $_POST['studentEmail'] ?? null,
            'placeOfBirth'       => $_POST['studentPlaceOfBirth'],
            'dateOfBirth'       => $_POST['studentDOB'],
            'genderId'       => $_POST['genderId'],
            'maritalStatusId'       => $_POST['studentMaritalStatusId'],
            'graduatingStatusId'       => $_POST['studentGraduatingStatusId'],
            'fatherName'       => $_POST['fatherName'] ?? null,
            'fatherOccupation'       => $_POST['fatherOccupation'] ?? null,
            'motherName'       => $_POST['motherName'] ?? null,
            'motherOccupation'       => $_POST['motherOccupation'] ?? null,
            'guardianName'       => $_POST['guardianName'] ?? null,
            'guardianOccupation'       => $_POST['guardianOccupation'] ?? null,
            'guardianContactNumber'       => $_POST['guardianNumber'] ?? null,

            'CreatedBy'     => $EmployeeNumber,
            'StatusId'      => 1,
          );
          $insertTable2 = 'r_students';
          $this->maintenance_model->insertFunction($insertData2, $insertTable2);
        // get generated id
          $generatedIdData = array(
            'table'                     => 'r_students'
            , 'column'                  => 'Id'
            , 'CreatedBy'               => $EmployeeNumber
          );
          $NewId = $this->maintenance_model->getGeneratedId2($generatedIdData);
          $TransactionNumber = 'SS-'.sprintf('%06d', $NewId['Id']);
          $set = array( 
            'StudentNumber'            => $TransactionNumber,
          );

          $condition = array( 
            'ID' => $NewId['Id'],
          );
          $table = 'r_students';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // // audits
        //   $auditDetail = 'Added student #'.$TransactionNumber.' to student list.';
        //   $insertData = array(
        //     'Description' => $auditDetail,
        //     'CreatedBy'   => $EmployeeNumber,
        //   );
        //   $auditTable = 'R_Logs';
        //   $this->maintenance_model->insertFunction($insertData, $auditTable);

        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Record successfully added!'); 
          $this->session->set_flashdata('alertType','success');
          
          header('Location: ' . $_SERVER['HTTP_REFERER']);
          exit;
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Record already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/StudentList');
      }
      
    }

    function getStudentList()
    {
      header('Access-Control-Allow-Origin: *');
      error_reporting(0);
      $UserId = $this->session->userdata('UserId');
      $list = $this->admin_model->get_datatables_StudentList();
      $num_rows = count($list);
      $data = array();
      $no = $_POST['start'];
      $approvalBtn = '';
      $editBtn = '';
      $tag = '';
      $rowNo = 0;
      $no = $_POST['start'];
      $actionBtn = '';
      foreach ($list as $row) 
      {
        $rowNo++;
        $docDetails = array();
        // $docDetails[] = '<div class="custom-control custom-checkbox">
        // <input class="custom-control-input chkAllClass" type="checkbox" id="customCheckbox'.$rowNo.'" value="'.urlencode(base64_encode($row->Id)).'">
        // <label for="customCheckbox'.$rowNo.'" class="custom-control-label chkAllClass"></label>
        // </div>';
        $docDetails[] = $row->StudentNumber;
        $docDetails[] = $row->StudentName;
        $docDetails[] = $row->CreatedBy;
        $docDetails[] = $row->DateCreated;
        $docDetails[] = $row->StatusDescription;
        if($row->StatusId == 1)
        {
          $docDetails[] = '<a onclick="updateRecord('.$row->Id.', 4, \''.$row->FirstName.'\', \''.$row->MiddleName.'\', \''.$row->LastName.'\', \''.$row->ExtName.'\', \''.$row->AddressLine.'\', \''.$row->regCode.'\', \''.$row->provCode.'\', \''.$row->cityCode.'\', \''.$row->barangayId.'\', \''.$row->studentContactNo.'\', \''.$row->studentEmailAddress.'\', \''.$row->placeOfBirth.'\', \''.$row->dateOfBirth.'\', \''.$row->genderId.'\', \''.$row->maritalStatusId.'\', \''.$row->graduatingStatusId.'\', \''.$row->fatherName.'\', \''.$row->fatherOccupation.'\', \''.$row->motherName.'\', \''.$row->motherOccupation.'\', \''.$row->guardianName.'\', \''.$row->guardianOccupation.'\', \''.$row->guardianContactNumber.'\')"  data-toggle="modal" data-target="#modalEdit" class="btn btn-primary" title="Edit"><span class="fa fa-edit"></span></a> <a onclick="updateRecord('.$row->Id.', 1)" class="btn btn-danger" title="Deactivate"><span class="fa fa-window-close"></span></a>';
        }
        else
        {
          $docDetails[] = '<a onclick="updateRecord('.$row->Id.', 2)" class="btn btn-warning" title="Re-activate"><span class="fa fa-retweet"></span></a>';
        }
        $data[] = $docDetails;
      }
      $output = array(
        "draw" => $_POST['draw'],
        "recordsTotal" => $this->admin_model->countAllFilteredStudentList(),
        'iTotalDisplayRecords' => $this->admin_model->countFilteredStudentList(),
        "recordsFiltered" => $num_rows,
        "data" => $data,
      );

      echo json_encode($output);
    }

    function editStudentList()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      // audits
        // $userDetails = $this->maintenance_model->selectSpecific('r_students', 'Id', $_POST['Id']);
        // $auditDetail = 'Edited student #'.$userDetails['StudentNumber'].'.';
        // $insertData = array(
        //   'Description' => $auditDetail,
        //   'CreatedBy'   => $EmployeeNumber,
        // );
        // $auditTable = 'R_Logs';
        // $this->maintenance_model->insertFunction($insertData, $auditTable);
      // edit
        $set = array( 
          'FirstName'     => htmlentities(preg_replace('/\s+/', ' ', $_POST['FirstName']), ENT_QUOTES),
          'MiddleName'    => htmlentities(preg_replace('/\s+/', ' ', $_POST['MiddleName']), ENT_QUOTES),
          'LastName'      => htmlentities(preg_replace('/\s+/', ' ', $_POST['LastName']), ENT_QUOTES),
          'ExtName'       => htmlentities(preg_replace('/\s+/', ' ', $_POST['ExtName']), ENT_QUOTES),

          'addressLine'       => $_POST['studentAddressLine'],
          'barangayId'       => $_POST['BarangayId'],
          'studentContactNo'       => $_POST['studentContactNumber'] ?? null,
          'studentEmailAddress'       => $_POST['studentEmail'] ?? null,
          'placeOfBirth'       => $_POST['studentPlaceOfBirth'],
          'dateOfBirth'       => $_POST['studentDOB'],
          'genderId'       => $_POST['genderId'],
          'maritalStatusId'       => $_POST['studentMaritalStatusId'],
          'graduatingStatusId'       => $_POST['studentGraduatingStatusId'],
          'fatherName'       => $_POST['fatherName'] ?? null,
          'fatherOccupation'       => $_POST['fatherOccupation'] ?? null,
          'motherName'       => $_POST['motherName'] ?? null,
          'motherOccupation'       => $_POST['motherOccupation'] ?? null,
          'guardianName'       => $_POST['guardianName'] ?? null,
          'guardianOccupation'       => $_POST['guardianOccupation'] ?? null,
          'guardianContactNumber'       => $_POST['guardianNumber'] ?? null,
        );

        $condition = array( 
          'ID' => $_POST['Id'],
        );
        $table = 'r_students';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Record successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
      
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    function updateStudentListRecord()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      if($_POST['Type'] == 1) // deactivate
      {
          $set = array( 
            'StatusId' => 2
          );

          $condition = array( 
            'Id' => $_POST['Id']
          );
          $table = 'r_students';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // audits
          $userDetails = $this->maintenance_model->selectSpecific('r_students', 'Id', $_POST['Id']);
          $auditDetail = 'Deactivated #'.$userDetails['StudentNumber'].' from student list.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $EmployeeNumber,
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
      }
      else if($_POST['Type'] == 2) // reactivate
      {
          $set = array( 
            'StatusId' => 1
          );

          $condition = array( 
            'Id' => $_POST['Id']
          );
          $table = 'r_students';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // audits
          $userDetails = $this->maintenance_model->selectSpecific('r_students', 'Id', $_POST['Id']);
          $auditDetail = 'Re-activated #'.$userDetails['StudentNumber'].' from student list.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $EmployeeNumber,
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
      }
      $output = 'OK';
      $this->output->set_output(print(json_encode($output)));
      exit();
    }
  /* END OF STUDENT LIST */

  /* CLASS SUBJECT */
    function addSubjectClass()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      $data = array(
        'Column' => " WHERE SubjectId = '".htmlentities(preg_replace('/\s+/', ' ', $_POST['SubjectId']), ENT_QUOTES)."' AND FacultyId = '".htmlentities(preg_replace('/\s+/', ' ', $_POST['EmployeeNumber']), ENT_QUOTES)."' AND ClassId = '".$this->uri->segment(3)."'
        ",
        'Table' => 'class_has_subjects',
      );
      $query = $this->admin_model->countRecord($data);
      if($query == 0) // not existing
      {
        $userDetails = $this->maintenance_model->selectSpecific('r_classlist', 'Id', $this->uri->segment(3));
        // audits
          $auditDetail = 'Added subject and faculty to class '.$userDetails['Name'].'.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy'   => $EmployeeNumber,
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        // insert
          $insertData2 = array(
            'SubjectId'       => htmlentities(preg_replace('/\s+/', ' ', $_POST['SubjectId']), ENT_QUOTES),
            'FacultyId'       => htmlentities(preg_replace('/\s+/', ' ', $_POST['EmployeeNumber']), ENT_QUOTES),
            'ClassId'         => htmlentities(preg_replace('/\s+/', ' ', $this->uri->segment(3)), ENT_QUOTES),
            'MaxStudents'     => htmlentities(preg_replace('/\s+/', ' ', $_POST['MaxNo']), ENT_QUOTES),
            'Description'     => htmlentities(preg_replace('/\s+/', ' ', $_POST['Description']), ENT_QUOTES),
            'CreatedBy'       => $EmployeeNumber,
            'StatusId'        => 1,
          );
          $insertTable2 = 'class_has_subjects';
          $this->maintenance_model->insertFunction($insertData2, $insertTable2);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Record successfully added!'); 
          $this->session->set_flashdata('alertType','success'); 
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Record already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
      }
      
      redirect('home/classDetails/'.$this->uri->segment(3));
    }

    function getSubjectClassList()
    {
      $result = $this->admin_model->getSubjectClassList($this->uri->segment(3));
      echo json_encode($result);
    }

    function getStudentSubjectClassList()
    {
      $result = $this->admin_model->getStudentSubjectClassList($this->uri->segment(3));
      echo json_encode($result);
    }

    function editSubjectClass()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      // audits
        $auditDetail = 'Edited class '.$_POST['Name'].'.';
        $insertData = array(
          'Description' => $auditDetail,
          'CreatedBy'   => $EmployeeNumber,
        );
        $auditTable = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // edit
        $set = array( 
          'MaxStudents'     => htmlentities(preg_replace('/\s+/', ' ', $_POST['MaxNo']), ENT_QUOTES),
          'Description'     => htmlentities(preg_replace('/\s+/', ' ', $_POST['Description']), ENT_QUOTES),
        );

        $condition = array( 
          'ID' => $_POST['Id'],
        );
        $table = 'class_has_subjects';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Record successfully added!'); 
        $this->session->set_flashdata('alertType','success'); 
      
      redirect('home/classDetails/'.$this->uri->segment(3));
    }

    function updateSubjectClassRecord()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      if($_POST['Type'] == 1) // deactivate
      {
          $set = array( 
            'StatusId' => 2
          );

          $condition = array( 
            'Id' => $_POST['Id']
          );
          $table = 'class_has_subjects';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // audits
          $userDetails = $this->maintenance_model->selectSpecific('class_has_subjects', 'Id', $_POST['Id']);
          $classDetail = $this->maintenance_model->selectSpecific('r_classlist', 'Id', $userDetails['ClassId']);
          $auditDetail = 'Deactivated subject/faculty from '.$classDetail['Name'].'.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $EmployeeNumber,
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
      }
      else if($_POST['Type'] == 2) // reactivate
      {
          $set = array( 
            'StatusId' => 1
          );

          $condition = array( 
            'Id' => $_POST['Id']
          );
          $table = 'class_has_subjects';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // audits
          $userDetails = $this->maintenance_model->selectSpecific('class_has_subjects', 'Id', $_POST['Id']);
          $classDetail = $this->maintenance_model->selectSpecific('r_classlist', 'Id', $userDetails['ClassId']);
          $auditDetail = 'Re-activated subject/faculty from '.$classDetail['Name'].'.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $EmployeeNumber,
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
      }
      $output = $_POST['Type'];
      $this->output->set_output(print(json_encode($output)));
      exit();
    }
  /* END OF CLASS SUBJECT */

  /* SUBJECT STUDENTS */
    function addSubjectStudent()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      foreach ($_POST['StudentId'] as $key => $value) 
      {
        $data = array(
        'Column' => " WHERE ClassSubjectId = '".$this->uri->segment(3)."' AND StudentId = '".htmlentities(preg_replace('/\s+/', ' ', $value), ENT_QUOTES)."'
        ",
        'Table' => 'classsubject_has_students',
        );
        $query = $this->admin_model->countRecord($data);
        if($query == 0) // not existing
        {
          // $studentDetail = $this->maintenance_model->selectSpecific('r_students', 'Id', $value);
          // $classDetail = $this->maintenance_model->selectSpecific('class_has_subjects', 'Id', $this->uri->segment(3));
          // $subjectDetail = $this->maintenance_model->selectSpecific('class_has_subjects', 'Id', $this->uri->segment(3));
          // // audits
          //   $auditDetail = 'Added student #'.$studentDetail['StudentNumber'].' to subject '.$subjectDetail['Code'].'-'.sprintf('%06d', $subjectDetail['Id']).'.';
          //   $insertData = array(
          //     'Description' => $auditDetail,
          //     'CreatedBy'   => $EmployeeNumber,
          //   );
          //   $auditTable = 'R_Logs';
          //   $this->maintenance_model->insertFunction($insertData, $auditTable);
          // insert
            $insertData2 = array(
              'StudentId'       => htmlentities(preg_replace('/\s+/', ' ', $value), ENT_QUOTES),
              'ClassSubjectId'  => htmlentities(preg_replace('/\s+/', ' ', $this->uri->segment(3)), ENT_QUOTES),
              'CreatedBy'       => $EmployeeNumber,
              'StatusId'        => 1,
            );
            $insertTable2 = 'classsubject_has_students';
            $this->maintenance_model->insertFunction($insertData2, $insertTable2);
          // notification
            $this->session->set_flashdata('alertTitle','Success!'); 
            $this->session->set_flashdata('alertText','Record successfully added!'); 
            $this->session->set_flashdata('alertType','success'); 
        }
        else
        {
          // notification
            $this->session->set_flashdata('alertTitle','Success!'); 
            $this->session->set_flashdata('alertText','Record successfully added!'); 
            $this->session->set_flashdata('alertType','success'); 
        }
      }
      
      
      redirect('home/FacultysubjectStudents/'.$this->uri->segment(3));
    }

    function getSubjectStudentList()
    {
      $result = $this->admin_model->getSubjectStudentList($this->uri->segment(3));
      echo json_encode($result);
    }

    function editSubjectStudent()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      // audits
        $auditDetail = 'Edited class '.$_POST['Name'].'.';
        $insertData = array(
          'Description' => $auditDetail,
          'CreatedBy'   => $EmployeeNumber,
        );
        $auditTable = 'R_Logs';
        $this->maintenance_model->insertFunction($insertData, $auditTable);
      // edit
        $set = array( 
          'MaxStudents'     => htmlentities(preg_replace('/\s+/', ' ', $_POST['MaxNo']), ENT_QUOTES),
          'Description'     => htmlentities(preg_replace('/\s+/', ' ', $_POST['Description']), ENT_QUOTES),
        );

        $condition = array( 
          'ID' => $_POST['Id'],
        );
        $table = 'class_has_subjects';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Record successfully added!'); 
        $this->session->set_flashdata('alertType','success'); 
      
      redirect('home/classDetails/'.$this->uri->segment(3));
    }

    function updateSubjectStudentRecord()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      if($_POST['Type'] == 1) // deactivate
      {
          $set = array( 
            'StatusId' => 2
          );

          $condition = array( 
            'Id' => $_POST['Id']
          );
          $table = 'class_has_subjects';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // audits
          $userDetails = $this->maintenance_model->selectSpecific('class_has_subjects', 'Id', $_POST['Id']);
          $classDetail = $this->maintenance_model->selectSpecific('r_classlist', 'Id', $userDetails['ClassId']);
          $auditDetail = 'Deactivated subject/faculty from '.$classDetail['Name'].'.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $EmployeeNumber,
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
      }
      else if($_POST['Type'] == 2) // reactivate
      {
          $set = array( 
            'StatusId' => 1
          );

          $condition = array( 
            'Id' => $_POST['Id']
          );
          $table = 'class_has_subjects';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // audits
          $userDetails = $this->maintenance_model->selectSpecific('class_has_subjects', 'Id', $_POST['Id']);
          $classDetail = $this->maintenance_model->selectSpecific('r_classlist', 'Id', $userDetails['ClassId']);
          $auditDetail = 'Re-activated subject/faculty from '.$classDetail['Name'].'.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $EmployeeNumber,
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
      }
      $output = $_POST['Type'];
      $this->output->set_output(print(json_encode($output)));
      exit();
    }
  /* END OF SUBJECT STUDENTS */

  /* FACULTY CLASS LIST */
    function getFacultyClassList()
    {
      $result = $this->admin_model->getFacultyClassList();
      echo json_encode($result);
    }
  /* END OF FACULTY CLASS LIST */

  /* FACULTY CLASS SUBJECT */
    function getFacultySubjectClassList()
    {
      $result = $this->admin_model->getFacultySubjectClassList($this->uri->segment(3));;
      echo json_encode($result);
    }

    function insertStudentGrade()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      if($this->uri->segment(3)  !== null)
      {
        // update
          $set = array( 
            'Grade'     => $_POST['Grade']
          );

          $condition = array( 
            'Id' => $_POST['ClassStudentId']
          );
          $table = 'classsubject_has_students';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Record successfully added!'); 
          $this->session->set_flashdata('alertType','success'); 
        
        redirect('home/FacultysubjectStudents/'.$this->uri->segment(3));
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Error!'); 
          $this->session->set_flashdata('alertText','No data submitted!'); 
          $this->session->set_flashdata('alertType','error'); 
        
        redirect('home/FacultysubjectStudents/'.$this->uri->segment(3));
      }
    }

    function updateClassStudent()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      if($_POST['Type'] == 1) // deactivate
      {
        $this->maintenance_model->deleteFunction($_POST['Id'], 'Id', 'classsubject_has_students');
      }
      $output = $_POST['Type'];
      $this->output->set_output(print(json_encode($output)));
      exit();
    }
  /* END OF FACULTY CLASS SUBJECT */

  /* FACULTY CLASS SUBJECT EXAM */
    function addSubjectExam()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      $data = array(
        'Column' => " WHERE Description = '".htmlentities(preg_replace('/\s+/', ' ', $_POST['Description']), ENT_QUOTES)."'
                      AND ClassSubjectId  = '".$this->uri->segment(3)."'
        ",
        'Table' => 'classsubject_has_exam',
      );
      $query = $this->admin_model->countRecord($data);
      if($query == 0) // not existing
      {
        // update 
          $set = array( 
            'StatusId'     => 2,
          );

          $condition = array( 
            'ClassSubjectId' => $this->uri->segment(3),
            'StatusId' => 3
          );
          $table = 'classsubject_has_exam';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert
          $insertData2 = array(
            'ClassSubjectId'    => $this->uri->segment(3),
            'Description'       => htmlentities(preg_replace('/\s+/', ' ', $_POST['Description']), ENT_QUOTES),
            'CreatedBy'         => $EmployeeNumber,
            // 'StatusId'          => htmlentities(preg_replace('/\s+/', ' ', $_POST['StatusId']), ENT_QUOTES),
            'StatusId'          => 3,
          );
          $insertTable2 = 'classsubject_has_exam';
          $this->maintenance_model->insertFunction($insertData2, $insertTable2);
        // audits
          $generatedIdData = array(
            'table'                     => 'classsubject_has_exam'
            , 'column'                  => 'Id'
            , 'CreatedBy'               => $EmployeeNumber
          );
          $NewId = $this->maintenance_model->getGeneratedId2($generatedIdData);
          $TransactionNumber = 'EX-'.sprintf('%06d', $NewId['Id']);

          $classDetail = $this->maintenance_model->selectSpecific('class_has_subjects', 'Id', $this->uri->segment(3));
          $subjectDetail = $this->maintenance_model->selectSpecific('class_has_subjects', 'Id', $classDetail['SubjectId']);
          $auditDetail = 'Created exam #'.$TransactionNumber.' to '.$subjectDetail['Code'].'-'.sprintf('%06d', $subjectDetail['Id']).'.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy'   => $EmployeeNumber,
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Record successfully added!'); 
          $this->session->set_flashdata('alertType','success'); 
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Record already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
      }
      
      redirect('home/createExam/'.$this->uri->segment(3));
    }

    function getSubjectCreatedExam()
    {
      $result = $this->admin_model->getSubjectCreatedExam($this->uri->segment(3));
      echo json_encode($result);
    }
  /* END FACULTY CLASS SUBJECT EXAM */

  /* FACULTY EXAM */
    function addExamCategory()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      $data = array(
        'Column' => " WHERE Name = '".htmlentities(preg_replace('/\s+/', ' ', $_POST['CategoryName']), ENT_QUOTES)."'
                      AND ExamId  = '".$this->uri->segment(3)."'
                      AND Percentage  = '".htmlentities(preg_replace('/\s+/', ' ', $_POST['Instructions']), ENT_QUOTES)."'
        ",
        'Table' => 'exam_has_category',
      );
      $query = $this->admin_model->countRecord($data);
      if($query == 0) // not existing
      {
        // insert
          $insertData2 = array(
            'ExamId'            => $this->uri->segment(3),
            'Name'              => htmlentities(preg_replace('/\s+/', ' ', $_POST['CategoryName']), ENT_QUOTES),
            'Percentage'        => htmlentities(preg_replace('/\s+/', ' ', $_POST['Percentage']), ENT_QUOTES),
            'Instructions'      => htmlentities(preg_replace('/\s+/', ' ', $_POST['Instructions']), ENT_QUOTES),
            'CreatedBy'         => $EmployeeNumber,
            'StatusId'          => 1,
          );
          $insertTable2 = 'exam_has_category';
          $this->maintenance_model->insertFunction($insertData2, $insertTable2);
        // audits
          $details = $this->maintenance_model->selectSpecific('classsubject_has_exam', 'Id', $this->uri->segment(3));
          $auditDetail = 'Added category to exam #EX-'.sprintf('%06d', $details['ID']).'.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy'   => $EmployeeNumber,
          );
          $auditTable = 'R_Logs';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Record successfully added!'); 
          $this->session->set_flashdata('alertType','success'); 
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Record already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
      }
      
      redirect('home/examDetails/'.$this->uri->segment(3).'/'.$this->uri->segment(4));
    }

    function addExamSubCategory()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");

      $totalInserted = 0;
      $totalRecords = 0;
      for($count = 0; $count < count($this->input->post('rowCount')); $count++)
      {
        $totalRecords++;
        $data = array(
          'Column' => " WHERE ExamCategoryId  = '".htmlentities(preg_replace('/\s+/', ' ', $_POST['CategoryId']), ENT_QUOTES)."'
                        AND Name  = '".htmlentities(preg_replace('/\s+/', ' ', $_POST['SubName'][$count]), ENT_QUOTES)."'
          ",
          'Table' => 'exam_has_subcategory',
        );
        $query = $this->admin_model->countRecord($data);
        if($query == 0) // not existing
        {
          // insert
            $insertData2 = array(
              'ExamCategoryId'    => htmlentities(preg_replace('/\s+/', ' ', $_POST['CategoryId']), ENT_QUOTES),
              'Name'              => htmlentities(preg_replace('/\s+/', ' ', $_POST['SubName'][$count]), ENT_QUOTES),
              'Instructions'      => htmlentities(preg_replace('/\s+/', ' ', $_POST['Instructions'][$count]), ENT_QUOTES),
              'CreatedBy'         => $EmployeeNumber,
              'StatusId'          => 1,
            );
            $insertTable2 = 'exam_has_subcategory';
            $this->maintenance_model->insertFunction($insertData2, $insertTable2);
          // audits
            $details = $this->maintenance_model->selectSpecific('classsubject_has_exam', 'Id', $this->uri->segment(3));
            $category = $this->maintenance_model->selectSpecific('exam_has_category', 'Id', htmlentities(preg_replace('/\s+/', ' ', $_POST['CategoryId']), ENT_QUOTES));
            $auditDetail = 'Added sub category to category '.$category['Name'].' and exam #EX-'.sprintf('%06d', $details['ID']).'.';
            $insertData = array(
              'Description' => $auditDetail,
              'CreatedBy'   => $EmployeeNumber,
            );
            $auditTable = 'R_Logs';
            $this->maintenance_model->insertFunction($insertData, $auditTable);
          $totalInserted++;
        }
      }
      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText', $totalInserted .' out of '.$totalRecords.' sub-category successfully added!'); 
        $this->session->set_flashdata('alertType','success'); 
      redirect('home/examDetails/'.$this->uri->segment(3).'/'.$this->uri->segment(4));
    }

    function getExamReviewers()
    {
      $result = $this->admin_model->getExamReviewers($this->uri->segment(3));
      echo json_encode($result);
    }

    function getExamCategories()
    {
      $result = $this->admin_model->getExamCategories($this->uri->segment(3));
      echo json_encode($result);
    }

    function getExamSubCategories()
    {
      $result = $this->admin_model->getExamSubCategories($this->uri->segment(3));
      echo json_encode($result);
    }

    function addSubCategoryQuestion()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");

      $totalInserted = 0;
      $totalRecords = 0;


      for($count = 0; $count < count($this->input->post('QuestionRow')); $count++)
      {
        // insert
          $insertData2 = array(
            'SubCategoryId'        => htmlentities(preg_replace('/\s+/', ' ', $this->uri->segment(5)), ENT_QUOTES),
            'Question'             => htmlentities(preg_replace('/\s+/', ' ', $_POST['Question1'][$count]), ENT_QUOTES),
            'Answer'               => htmlentities(preg_replace('/\s+/', ' ', $_POST['Answer'][$count]), ENT_QUOTES),
            'StatusId'             => 1,
            'CreatedBy'            => $EmployeeNumber,
          );
          $insertTable2 = 'subcategory_has_questions';
          $this->maintenance_model->insertFunction($insertData2, $insertTable2);
      }

      // get generated id
        $generatedIdData = array(
          'table'                     => 'subcategory_has_questions'
          , 'column'                  => 'Id'
          , 'CreatedBy'               => $EmployeeNumber
        );
        $NewId = $this->maintenance_model->getGeneratedId2($generatedIdData);

      $totalOptions = 1;
      for($QOptions = 0; $QOptions < count($this->input->post('OptionRowCount')); $QOptions++)
      {
        // insert
          $insertData2 = array(
            'subquestionId'        => htmlentities(preg_replace('/\s+/', ' ', $NewId['Id']), ENT_QUOTES),
            'OptionNo'             => $totalOptions,
            'OptionName'           => htmlentities(preg_replace('/\s+/', ' ', $_POST['Options'][$QOptions]), ENT_QUOTES),
            'StatusId'             => 1,
          );
          $insertTable2 = 'subcategory_has_options';
          $this->maintenance_model->insertFunction($insertData2, $insertTable2);
        // update
          if($_POST['Answer'][$QOptions] == 1)
          {
            $set = array( 
              'Answer'     => $totalOptions,
            );

            $condition = array( 
              'ID' => $NewId['Id']
            );
            $table = 'subcategory_has_questions';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          }
          $totalOptions++;
      }
      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText', 'Record successfully added!'); 
        $this->session->set_flashdata('alertType','success'); 
      redirect('home/subCategoryDetails/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5));
    }

    function getExamQuestions()
    {
      $result = $this->admin_model->getExamQuestions($this->uri->segment(3));
      echo json_encode($result);
    }

    function insertExamAnswers()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeId');
      $DateNow = date("Y-m-d H:i:s");

      if($this->uri->segment(3) !== null)
      {
        // // delete previous exam
        //   $prevStudentExamId = $this->maintenance_model->selectSpecific('student_has_exam', 'StudentId', $EmployeeNumber);
        //   if($prevStudentExamId != 0)
        //   {
        //     $this->maintenance_model->deleteFunction($prevStudentExamId['ID'], 'StudentExamId', 'exam_has_answers');
        //     $this->maintenance_model->deleteFunction($EmployeeNumber, 'StudentId', 'student_has_exam');
        //   }
        // exam details
          $insertData1 = array(
            'StudentId'             => $EmployeeNumber,
            'ExamId'                => $this->uri->segment(3),
            'StatusId'              => 1,
            'DateCreated'           => $DateNow,
            'CreatedBy'             => $EmployeeNumber,
          );
          $insertTable1 = 'student_has_exam';
          $this->maintenance_model->insertFunction($insertData1, $insertTable1);
        // get generated id
          $generatedIdData = array(
            'table'                     => 'student_has_exam'
            , 'column'                  => 'Id'
            , 'CreatedBy'               => $EmployeeNumber
          );
          $NewId = $this->maintenance_model->getGeneratedId2($generatedIdData);
        $isCorrect = 0;
        foreach ($_POST['questionId'] as $key => $questioId) 
        {
          $answerKey = $this->maintenance_model->selectSpecific('subcategory_has_questions', 'Id', $questioId);
          if($answerKey['Answer'] == $_POST['AnswerId'][$key])
          {
            $isCorrect = 1;
          }
          else
          {
            $isCorrect = 0;
          }

          $insertData = array(
            'StudentExamId'         => $NewId['Id'],
            'QuestionId'            => $questioId,
            'AnswerId'              => $_POST['AnswerId'][$key],
            'CorrectAnswer'         => $answerKey['Answer'],
            'IsCorrect'             => $isCorrect,
            'DateCreated'           => $DateNow,
            'CreatedBy'             => $EmployeeNumber,
          );
          $auditTable = 'exam_has_answers';
          $this->maintenance_model->insertFunction($insertData, $auditTable);
        }

        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText', 'Exam successfully submitted!'); 
          $this->session->set_flashdata('alertType','success'); 
        redirect('home/viewExam/'.$this->uri->segment(3));
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText', 'No data submitted!'); 
          $this->session->set_flashdata('alertType','warning'); 
        redirect('home/viewExam/'.$this->uri->segment(3));
      }
    }

    function uploadReviewer()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");

      if($this->uri->segment(3) !== null)
      {
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
            $insertData = array(
              'ExamId'          => $this->uri->segment(3),
              'FileName'        => htmlentities($fileName, ENT_QUOTES),
              'FileTitle'       => htmlentities($Title, ENT_QUOTES),
              'Notes'           => htmlentities($_POST['Notes'], ENT_QUOTES),
              'StatusId'        => 1,
              'CreatedBy'       => $EmployeeNumber,
              'DateCreated'     => $DateNow
            );
            $dataTable = 'exam_has_reviewers';
            $this->maintenance_model->insertFunction($insertData, $dataTable);

          }
          else
          {
            $fileName = "";
          }
        }

        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Record successfully added!'); 
          $this->session->set_flashdata('alertType','success'); 
        
        redirect('home/examDetails/'.$this->uri->segment(3).'/'.$this->uri->segment(4));
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Error!'); 
          $this->session->set_flashdata('alertText','No data submitted!'); 
          $this->session->set_flashdata('alertType','error'); 
        
        redirect('home/examDetails/'.$this->uri->segment(3).'/'.$this->uri->segment(4));
      }
    }

    function updateExamCategoryRecords()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      if($_POST['Type'] == 1) // deactivate reviewer
      {
        $set = array( 
          'StatusId' => 2
        );

        $condition = array( 
          'Id' => $_POST['Id']
        );
        $table = 'exam_has_reviewers';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      else
      {
        $set = array( 
          'StatusId' => 2
        );

        $condition = array( 
          'Id' => $_POST['Id']
        );
        $table = 'exam_has_category';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      $output = 'OK';
      $this->output->set_output(print(json_encode($output)));
      exit();
    }

    function getQuestionOptions()
    {
      $output = $this->admin_model->getQuestionOptions($_POST['Id']);
      $this->output->set_output(print(json_encode($output)));
      exit();
    }

    function getExamsApproval()
    {
      $result = $this->admin_model->getExamsApproval();
      foreach ($result as $key => $value) 
      {
        // $result[$key]['ExamGrade'] = $this->admin_model->getExamGrade($value['ClassSubjectId']);
        if(isset($value['PreviousExamId']))
        {
          $result[$key]['correctAnswer'] = $this->admin_model->countCorrectAnswers($value['PreviousExamId']);
          $result[$key]['totalQuestions'] = $this->admin_model->countQuestions($value['PreviousExamId']);
        }
        else
        {
          $result[$key]['correctAnswer'] = 0;
          $result[$key]['totalQuestions'] = 0;
        }
      }
      echo json_encode($result);
    }

    function removeSubCategory()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");

      $this->maintenance_model->deleteFunction($_POST['Id'], 'Id', 'exam_has_subcategory');
      $output = 'OK!';
      $this->output->set_output(print(json_encode($output)));
      exit();
    }

    function removeQuestions()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");

      $this->maintenance_model->deleteFunction($_POST['Id'], 'Id', 'subcategory_has_questions');
      $output = 'OK!';
      $this->output->set_output(print(json_encode($output)));
      exit();
    }
  /* END FACULTY EXAM */

  /* STUDENTS */
    function getStudentClassList()
    {
      $result = $this->admin_model->getStudentClassList();
      echo json_encode($result);
    }

    function getStudentSubjectList()
    {
      $result = $this->admin_model->getStudentSubjectList();
      foreach ($result as $key => $value) 
      {
        // $result[$key]['ExamGrade'] = $this->admin_model->getExamGrade($value['ClassSubjectId']);
        if(isset($value['CreatedExamId']))
        {
          $result[$key]['correctAnswer'] = $this->admin_model->countCorrectAnswers($value['CreatedExamId']);
          $result[$key]['totalQuestions'] = $this->admin_model->countQuestions($value['CreatedExamId']);
        }
        else
        {
          $result[$key]['correctAnswer'] = 0;
          $result[$key]['totalQuestions'] = 0;
        }
      }
      echo json_encode($result);
    }

    function requestRetakeExam()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $StudentId = $this->session->userdata('EmployeeId');
      $DateNow = date("Y-m-d H:i:s");

      // update current exam
        $set = array( 
          'StatusId'     => 9,
        );

        $condition = array( 
          'ID' => $_POST['TakenExamId'],
        );
        $table = 'student_has_exam';
        $this->maintenance_model->updateFunction1($set, $condition, $table);

      $output = 'OK';
      $this->output->set_output(print(json_encode($output)));
      exit();
    }

    function processRetakingofExam()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $StudentId = $this->session->userdata('EmployeeId');
      $DateNow = date("Y-m-d H:i:s");

      // update current exam
        if($_POST['isApproved'] == 1)
        {
          $set = array( 
            'StatusId'     => 10,
          );

          $this->maintenance_model->deleteFunction($_POST['PreviousExamId'], 'StudentExamId', 'exam_has_answers');
        }
        else
        {
          $set = array( 
            'StatusId'     => 1,
          );
        }

        $condition = array( 
          'ID' => $_POST['PreviousExamId'],
        );
        $table = 'student_has_exam';
        $this->maintenance_model->updateFunction1($set, $condition, $table);

      $output = 'OK';
      $this->output->set_output(print(json_encode($output)));
      exit();
    }
  /* END OF STUDENTS */

  /* REGISTRAR */

    function getRegistrarSubjectList()
    {
      $result = $this->admin_model->getRegistrarSubjectList();
      echo json_encode($result);
    }

    function addExamSchedule()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      if($this->uri->segment(3)  !== null)
      {
        // update
          $set = array( 
            'StatusId'     => 0
          );

          $condition = array( 
            'ClassSubjectId' => $this->uri->segment(3)
          );
          $table = 'classsubject_has_examschedule';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert data
          $insertData = array(
            'ClassSubjectId'  => $this->uri->segment(3),
            'StartDate'       => $_POST['startDate'],
            'EndDate'         => $_POST['endDate'],
            'StatusId'        => 1,
            'CreatedBy'       => $EmployeeNumber,
            'DateCreated'     => $DateNow
          );
          $dataTable = 'classsubject_has_examschedule';
          $this->maintenance_model->insertFunction($insertData, $dataTable);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Record successfully added!'); 
          $this->session->set_flashdata('alertType','success'); 
        
        redirect('home/registrarClassList/'.$this->uri->segment(3));
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Error!'); 
          $this->session->set_flashdata('alertText','No data submitted!'); 
          $this->session->set_flashdata('alertType','error'); 
        
        redirect('home/registrarClassList/'.$this->uri->segment(3));
      }
    }

    function getExamSchedules()
    {
      $result = $this->admin_model->getExamSchedules($this->uri->segment(3));
      echo json_encode($result);
    }

    function deactivateExamSchedule()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      $this->maintenance_model->deleteFunction($_POST['Id'], 'Id', 'classsubject_has_examschedule');
      $output = 'OK';
      $this->output->set_output(print(json_encode($output)));
      exit();
    }

    function generateReport()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");

      $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      // set default monospaced font
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->setPrintHeader(false);
      // set margins
      $pdf->SetMargins(10, 10, 10);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->SetFont('dejavusans', '', 10);


      $pdf->setPrintHeader(false);
      // set default header data
      // $pdf->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, '');
      $pdf->AddPage('L', 'A4');
      if($_POST['StatusId'] == 'All' && $_POST['GradeFrom'] == 0 && $_POST['GradeTo'] == 0 && $_POST['SubjectId'] == 'None') // student list only
      {
        $html ='
          <style>
          p {
            text-align: center;
            font-size: 10px;
          }

          a {
            text-align: center;
            font-size: 15px;
          }
          </style>
          <p><u><strong>STUDENT LIST</strong></u><br></p>
          <br>
          <br>
          <table nobr="true" cellspacing="0" cellpadding="2" border="1">
            <tbody>
              <tr>
                <td><strong>Student Number</strong></td>
                <td><strong>Student Name</strong></td>
              </tr>
              ';
                $studentList = $this->admin_model->generateStudentList();
                if($studentList)
                {
                  foreach ($studentList as $key => $value) 
                  {

                    // $result[$key]['ExamGrade'] = $this->admin_model->getExamGrade($value['ClassSubjectId']);
                    if(isset($value['PreviousExamId']))
                    {
                      $result[$key]['correctAnswer'] = $this->admin_model->countCorrectAnswers($value['PreviousExamId']);
                      $result[$key]['totalQuestions'] = $this->admin_model->countQuestions($value['PreviousExamId']);
                    }
                    else
                    {
                      $result[$key]['correctAnswer'] = 0;
                      $result[$key]['totalQuestions'] = 0;
                    }
                    $html .= '
                      <tr>
                        <td>'.strtoupper($value['StudentNumber']).'</td>
                        <td>'.strtoupper($value['StudentName']).'</td>
                      </tr>
                    ';
                  }
                }
            $html .= '
          </tbody>
        </table>
        ';
      }
      else if($_POST['StatusId'] == 'Passed') // passed student list only
      {
        $html ='
          <style>
          p {
            text-align: center;
            font-size: 10px;
          }

          a {
            text-align: center;
            font-size: 15px;
          }
          </style>
          <p><u><strong>STUDENT LIST</strong></u><br></p>
          <br>
          <br>
          <table nobr="true" cellspacing="0" cellpadding="2" border="1">
            <tbody>
              <tr>
                <td><strong>Student Number</strong></td>
                <td><strong>Student Name</strong></td>
              </tr>
              ';
                $studentList = $this->admin_model->getSearchResult($_POST['StatusId'], $_POST['GradeFrom'], $_POST['GradeTo'], $_POST['SubjectId']);
                if($studentList)
                {
                  foreach ($studentList as $key => $value) 
                  {
                    $html .= '
                      <tr>
                        <td>'.strtoupper($value['StudentNumber']).'</td>
                        <td>'.strtoupper($value['StudentName']).'</td>
                      </tr>
                    ';
                  }
                }
            $html .= '
          </tbody>
        </table>
        ';
      }
      $pdf->writeHTML($html, true, false, true, false, '');

      $pdf->Output('Income Statement.pdf', 'I');
      // $pdf->Output('Payment Dues.pdf', 'D');
    }

  function generateStudentList()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->setPrintHeader(false);
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->SetFont('dejavusans', '', 10);

    $pdf->setPrintHeader(false);
    $pdf->AddPage('L', 'A4');
    $html ='
      <style>
      p {
        text-align: center;
        font-size: 10px;
      }
      a {
        text-align: center;
        font-size: 15px;
      }
      </style>
      <p><u><strong>STUDENT LIST</strong></u><br></p>
      <br>
      <br>
      <table nobr="true" cellspacing="0" cellpadding="2" border="1">
        <tbody>
          <tr>
            <td><strong>Student Number</strong></td>
            <td><strong>Student Name</strong></td>
            <td><strong>Current Enrolled Subjects</strong></td>
          </tr>
    ';

    $studentList = $this->admin_model->generateStudentList();
    if($studentList)
    {
      foreach ($studentList as $key => $value) 
      {
        // Fetch current enrolled subjects for this student
        $subjects = $this->admin_model->getCurrentSubjectsByStudent($value['Id']); // Make sure 'Id' is available in $value
        $subjectStr = '';
        if ($subjects && count($subjects) > 0) {
          $subjectArr = [];
          foreach ($subjects as $subj) {
            $subjectArr[] = strtoupper($subj['SubjectCode']) . ' - ' . strtoupper($subj['SubjectName']);
          }
          $subjectStr = implode('<br>', $subjectArr);
        } else {
          $subjectStr = 'None';
        }

        $html .= '
          <tr>
            <td>'.strtoupper($value['StudentNumber']).'</td>
            <td>'.strtoupper($value['StudentName']).'</td>
            <td>'.$subjectStr.'</td>
          </tr>
        ';
      }
    }
    $html .= '
        </tbody>
      </table>
      ';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('Student List.pdf', 'D');
  }

    function generateStudentListSubjects()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");

      $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      // set default monospaced font
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->setPrintHeader(false);
      // set margins
      $pdf->SetMargins(10, 10, 10);
      $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      $pdf->SetFont('dejavusans', '', 10);


      $pdf->setPrintHeader(false);
      // set default header data
      // $pdf->SetHeaderData('', PDF_HEADER_LOGO_WIDTH, '');
      $pdf->AddPage('L', 'A4');
      if($_POST['SubjectId'] == 'All')
      {
        $subjectName = ' ALL SUBJECTS';
      }
      else
      {
        $subjectDetails = $this->admin_model->getSubjectDetails($_POST['GradeFrom'], $_POST['GradeTo'], $_POST['SubjectId']);
        $subjectName = $subjectDetails['SubjectCode'].' - '.$subjectDetails['Name'];
      }
      $html ='
        <style>
        p {
          text-align: center;
          font-size: 10px;
        }

        a {
          text-align: center;
          font-size: 15px;
        }
        </style>
        <p><u><strong>STUDENT LIST FOR '.$subjectName.'</strong></u><br></p>
        <br>
        <br>
        <table nobr="true" cellspacing="0" cellpadding="2" border="1">
          <tbody>
            <tr>
              <td><strong>Student Number</strong></td>
              <td><strong>Student Name</strong></td>
              <td><strong>Subject Code</strong></td>
              <td><strong>Subject Name</strong></td>
              <td><strong>Grade</strong></td>
              <td><strong>Exam Grades</strong></td>
            </tr>
            ';
              $correctAnswer = 0;
              $totalQuestions = 0;
              $finalGrade = 0;
              $studentList = $this->admin_model->getSearchResult($_POST['GradeFrom'], $_POST['GradeTo'], $_POST['SubjectId']);
              if($studentList)
              {
                foreach ($studentList as $key => $value) 
                {
                  $html .= '
                    <tr>
                      <td>'.strtoupper($value['StudentNumber']).'</td>
                      <td>'.strtoupper($value['StudentName']).'</td>
                      <td>'.strtoupper($value['SubjectCode']).'</td>
                      <td>'.strtoupper($value['Name']).'</td>
                      <td>'.strtoupper($value['Grade']).'</td>
                  ';

                  if($value['PreviousExamId'] !== null)
                  {
                    $correctAnswer = $this->admin_model->countCorrectAnswers($value['PreviousExamId']);
                    $totalQuestions = $this->admin_model->countQuestions($value['PreviousExamId']);

                    $finalGrade = ($correctAnswer/$totalQuestions)*100;
                  }
                  else
                  {
                    $finalGrade = 0;
                  }

                  $html .= '
                      <td>'.$finalGrade.'%</td>
                    </tr>
                  ';
                }
              }
          $html .= '
        </tbody>
      </table>
      ';
      $pdf->writeHTML($html, true, false, true, false, '');

      $pdf->Output('Income Statement.pdf', 'I');
      // $pdf->Output('Student List.pdf', 'D');
    }
  /* END OF REGISTRAR */



  function getRegionList()
  {
    echo $this->admin_model->getRegionList();
  }

  function getProvinces()
  {
    echo $this->admin_model->getProvinces($this->input->post('RegionId'));
  }

  function getCities()
  {
    echo $this->admin_model->getCities($this->input->post('Id'));
  }

  function getBarangays()
  {
    echo $this->admin_model->getBarangays($this->input->post('Id'));
  }

}
