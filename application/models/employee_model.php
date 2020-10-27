<?php
class employee_model extends CI_Model
{
    function __construct()
    {
      parent::__construct();
			$this->load->model('maintenance_model');
			$this->load->model('access');
    }

    function countEmployee($input)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM r_Employee EMP
                                                  INNER JOIN branch_has_employee BE
                                                    ON BE.EmployeeNumber = EMP.EmployeeNumber
                                                      WHERE EMP.FirstName = '".$input['FirstName']."'
                                                      AND EMP.MiddleName = '".$input['MiddleName']."'
                                                      AND EMP.ExtName = '".$input['ExtName']."'
                                                      AND EMP.LastName = '".$input['LastName']."'
                                                      AND EMP.DateOfBirth = '".$input['DateOfBirth']."'

      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function countContactNumber($input)
    {
      $query_string = $this->db->query("SELECT  DISTINCT ContactNumberId
                                                FROM R_ContactNumbers
                                                  WHERE PhoneType = '".$input['PhoneType']."'
                                                  AND Number = '".$input['Number']."'

      ");
      $data = $query_string->row_array();
      if($data['ContactNumberId'] != null)
      {
        $query2 = $this->db->query("SELECT  *
                                            FROM employee_has_contactnumbers
                                              WHERE ContactNumberId = ".$data['ContactNumberId']."
                                              AND EmployeeNumber = '".$input['EmployeeNumber']."'
        ");
        $data2 = $query2->num_rows();
        return $data2;
      }
      else
      {
        return 0;
      }
    }

    function countEmailAddress($input)
    {
      $query_string = $this->db->query("SELECT  EmailId
                                                FROM r_emails
                                                WHERE EmailAddress = '".$input['EmailAddress']."'

      ");
      $data = $query_string->row_array();
      if($data['EmailId'] != null)
      {
        $query2 = $this->db->query("SELECT  *
                                            FROM employee_has_emails
                                              WHERE EmailId = ".$data['EmailId']."
                                              AND EmployeeNumber = '".$input['EmployeeNumber']."'
        ");
        $data2 = $query2->num_rows();
        return $data2;
      }
      else
      {
        return 0;
      }
    }

    function countAddress($input)
    {
      $query_string = $this->db->query("SELECT  AddressId
                                                FROM r_address
                                                WHERE HouseNo = '".$input['HouseNo']."'
                                                AND AddressType = '".$input['AddressType']."'
                                                AND BarangayId = '".$input['BarangayId']."'

      ");
      $data = $query_string->row_array();
      if($data['AddressId'] != null)
      {
        $query2 = $this->db->query("SELECT  *
                                            FROM employee_has_address
                                              WHERE AddressId = ".$data['AddressId']."
                                              AND EmployeeNumber = '".$input['EmployeeNumber']."'
        ");
        $data2 = $query2->num_rows();
        return $data2;
      }
      else
      {
        return 0;
      }
    }

    function countAttachment($input)
    {
      $query_string = $this->db->query("SELECT  IdentificationId
                                                FROM r_identificationCards
                                                WHERE ID = '".$input['TypeOfId']."'
                                                AND IdNumber = '".$input['IDNumber']."'
                                                AND Attachment = '".$input['Attachment']."'

      ");
      $data = $query_string->row_array();
      if($data['IdentificationId'] != null)
      {
        $query2 = $this->db->query("SELECT  *
                                            FROM employee_has_identifications
                                              WHERE IdentificationId = ".$data['IdentificationId']."
                                              AND EmployeeNumber = '".$input['EmployeeNumber']."'
        ");
        $data2 = $query2->num_rows();
        return $data2;
      }
      else
      {
        return 0;
      }
    }

    function getSecurityQuestions()
    {
      $query_string = $this->db->query("SELECT SecurityQuestionId
                                              , Name
                                                FROM R_SecurityQuestions
                                                  WHERE StatusId = 1
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getEmployeeSecurityQuestions($EmployeeId, $QuestionNo)
    {
      $EmpNo = $this->db->query("SELECT EmployeeNumber 
                                        FROM R_Employee
                                          WHERE EmployeeId = $EmployeeId 
      ")->row_array();
      $query_string = $this->db->query("SELECT  US.SecurityQuestionId
                                                , US.Answer
                                                FROM r_userrole_has_r_securityquestions US
                                                  INNER JOIN r_securityquestions SQ
                                                    ON US.SecurityQuestionId = SQ.SecurityQuestionId
                                                    WHERE US.EmployeeNumber = '".$EmpNo['EmployeeNumber']."'
                                                    AND US.QuestionNumber = ".$QuestionNo."
                                                    AND US.StatusId = 1

      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getEmployeeDetail($EmployeeId)
    {
      $query_string = $this->db->query("SELECT  CONCAT(LastName, ', ', FirstName, ' ', ExtName) as Name
                                                , EmployeeNumber
                                                , EmployeeId
                                                FROM r_Employee
                                                  WHERE EmployeeId = '".$EmployeeId."'

      ");
      $data = $query_string->row_array();
      return $data;
    }

    function checkExisitingEmployee($EmployeeNumber)
    {
      $query = $this->db->query("SELECT  EmployeeNumber
                                                FROM r_Employee
                                                  WHERE EmployeeNumber = '$EmployeeNumber'
                                                  AND StatusId = 2 
      ");
      
      $data = $query->num_rows();
      return $data;
    }

    function checkSecurity($Question, $Answer, $QuestionNumber)
    {
      $query = $this->db->query("SELECT *
                                        FROM r_userrole_has_r_securityquestions
                                          WHERE SecurityQuestionId = $Question
                                          AND Answer = '".htmlentities($Answer, ENT_QUOTES)."'
                                          AND QuestionNumber = $QuestionNumber
                                          AND StatusId = 1
      ");
      
      
      $data = $query->num_rows();
      return $data;
    }

    function getEmployeeDetails($Id)
    {
      $query_string = $this->db->query("SELECT DISTINCT EMP.EmployeeId
                                                , EMP.EmployeeNumber
                                                , S.name as Salutation
                                                , EMP.FirstName
                                                , acronym(EMP.MiddleName) as MiddleInitial
                                                , EMP.LastName
                                                , EMP.ExtName
                                                , SX.Name as Sex
                                                , N.Description as Nationality
                                                , C.name as CivilStatus
                                                , DATE_FORMAT(EMP.DateOfBirth, '%d %b %Y') as DateOfBirth
                                                , DATE_FORMAT(EMP.DateHired, '%d %b %Y') as DateHired
                                                , EMP.StatusId
                                                , SS.Description as StatusDescription


                                                , S.SalutationId
                                                , EMP.MiddleName
                                                , SX.SexId
                                                , N.NationalityId
                                                , N.Description as NationalityName
                                                , C.CivilStatusId
                                                , EMP.DateOfBirth as RawDOB
                                                , EMP.DateHired as RawDateHired
                                                , P.PositionId
                                                , P.Name as PositionName
                                                , DATE_FORMAT(EMP.DateOfBirth, '%Y-%b-%d') as RawDateOfBirth
                                                , DATE_FORMAT(EMP.DateHired, '%Y-%b-%d') as RawDH
                                                , B.Name
                                                , B.Code
                                                , B.Description as BranchDesc
                                                , MNG.FirstName as MngFirstName
                                                , acronym(MNG.MiddleName) as MngMiddleInitial
                                                , MNG.LastName as MngLastName
                                                , MNG.EmployeeNumber as MngEmployeeNumber
                                                , BM.ManagerBranchId
                                                , PP.FileName
                                                FROM r_Employee EMP
                                                  INNER JOIN R_Salutation S
                                                    ON S.SalutationId = EMP.Salutation
                                                  INNER JOIN R_Sex SX
                                                    ON SX.SexId = EMP.Sex
                                                  INNER JOIN r_nationality N
                                                    ON N.NationalityId = EMP.Nationality
                                                  INNER JOIN r_civilstatus C
                                                    ON C.CivilStatusId = EMP.CivilStatus
                                                  INNER JOIN R_Position P
                                                    ON P.PositionId = EMP.PositionId
                                                  INNER JOIN branch_has_employee BE
                                                    ON BE.EmployeeNumber = EMP.EmployeeNumber
                                                  INNER JOIN R_Status SS
                                                    ON SS.StatusId = EMP.StatusId
                                                  LEFT JOIN branch_has_manager BM
                                                    ON BM.ManagerBranchId = BE.ManagerBranchId
                                                  LEFT JOIN r_branch B
                                                    ON B.BranchId = BE.BranchId
                                                  LEFT JOIN r_employee MNG
                                                    ON MNG.EmployeeNumber = BM.EmployeeNumber
                                                  LEFT JOIN r_profilepicture PP
                                                    ON PP.EmployeeNumber = EMP.EmployeeNumber
                                                    AND PP.StatusId = 1
                                                    WHERE EMP.EmployeeId = $Id

      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getNameOfCategory($input)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT  ".$input['column']."
                                        FROM ".$input['table']."
                                        ".$input['query']."
      ");


      $data = $query->row_array();
      return $data;
    }

    function getEmployeeProfile($Id)
    {
      $query_string = $this->db->query("SELECT DISTINCT EMP.EmployeeId
                                                , EMP.EmployeeNumber
                                                , S.name as Salutation
                                                , CONCAT(EMP.LastName, ', ', EMP.FirstName) as Name
                                                , EMP.FirstName
                                                , acronym(EMP.MiddleName) as MiddleInitial
                                                , EMP.LastName
                                                , EMP.ExtName
                                                , PP.FileName
                                                , SX.Name as Sex
                                                , N.Description as Nationality
                                                , C.name as CivilStatus
                                                , DATE_FORMAT(EMP.DateOfBirth, '%d %b %Y') as DateOfBirth
                                                , DATE_FORMAT(EMP.DateHired, '%d %b %Y') as DateHired
                                                , SS.Description as StatusDescription
                                                , EMP.StatusId
                                                , P.Description as Position
                                                , acronym(MNG.MiddleName) as MngMiddleInitial
                                                , MNG.FirstName as MngFirstName
                                                , MNG.LastName as MngLastName
                                                , MNG.EmployeeNumber as MngEmployeeNumber
                                                , B.Name as Branch
                                                , B.Code
                                                FROM r_Employee EMP
                                                  INNER JOIN R_Salutation S
                                                    ON S.SalutationId = EMP.Salutation
                                                  INNER JOIN R_Sex SX
                                                    ON SX.SexId = EMP.Sex
                                                  INNER JOIN r_nationality N
                                                    ON N.NationalityId = EMP.Nationality
                                                  INNER JOIN r_civilstatus C
                                                    ON C.CivilStatusId = EMP.CivilStatus
                                                  INNER JOIN R_Status SS
                                                    ON SS.StatusId = EMP.StatusId
                                                  INNER JOIN R_Position P
                                                    ON P.PositionId = EMP.PositionId
                                                  INNER JOIN branch_has_employee BE
                                                    ON BE.EmployeeNumber = EMP.EmployeeNumber
                                                  LEFT JOIN branch_has_manager BM
                                                    ON BM.ManagerBranchId = BE.ManagerBranchId
                                                  LEFT JOIN r_branch B
                                                    ON B.BranchId = BE.BranchId
                                                  LEFT JOIN r_employee MNG
                                                    ON MNG.EmployeeNumber = BM.EmployeeNumber
                                                  LEFT JOIN r_ProfilePicture PP
                                                    ON PP.EmployeeNumber = EMP.EmployeeNumber
                                                    AND PP.StatusId = 1
                                                  WHERE EMP.EmployeeNumber = '$Id'

      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getEmployeeProfileBranchDetails($Id)
    {
      $query_string = $this->db->query("SELECT  B.Code
                                                , B.Name
                                                , B.Description
                                                , MEMP.FirstName
                                                , MEMP.LastName
                                                , acronym(MEMP.MiddleName) as MiddleInitial
                                                FROM branch_has_employee BE
                                                  INNER JOIN r_employee EMP
                                                    ON BE.EmployeeNumber = EMP.EmployeeNumber
                                                  LEFT JOIN branch_has_manager BM
                                                    ON BM.ManagerBranchId = BE.ManagerBranchId
                                                  LEFT JOIN r_employee MEMP
                                                    ON MEMP.EmployeeNumber = BM.EmployeeNumber
                                                  INNER JOIN r_branch B
                                                    ON B.BranchId = BM.BranchId
                                                        WHERE EMP.EmployeeNumber = '$Id'
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getAllList()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $Roles = $this->maintenance_model->getLoggedInRoles(); // to check if may access syang maview
      $query_string = $this->db->query("SELECT DISTINCT EMP.EmployeeId
                                                , EMP.EmployeeNumber
                                                , S.name as Salutation
                                                , EMP.FirstName
                                                , acronym(EMP.MiddleName) as MI
                                                , EMP.LastName
                                                , EMP.ExtName
                                                , SX.Name as Sex
                                                , N.Description as Nationality
                                                , C.name as CivilStatus
                                                , EMP.DateOfBirth
                                                , EMP.DateHired
                                                , DATE_FORMAT(EMP.DateHired, '%d %b %Y') as DateHired
                                                , DATE_FORMAT(EMP.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , DATE_FORMAT(EMP.DateCreated, '%d %b %Y %h:%i %p') as DateUpdated
                                                , EMP.StatusId
                                                , EMP.CreatedBy
                                                , B.Name as Branch                   
                                                FROM r_Employee EMP
                                                  INNER JOIN R_Salutation S
                                                    ON S.SalutationId = EMP.Salutation
                                                  INNER JOIN R_Sex SX
                                                    ON SX.SexId = EMP.Sex
                                                  INNER JOIN r_nationality N
                                                    ON N.NationalityId = EMP.Nationality
                                                  INNER JOIN r_civilstatus C
                                                    ON C.CivilStatusId = EMP.CivilStatus
                                                  INNER JOIN Branch_has_Employee BE
                                                    ON BE.EmployeeNumber = EMP.EmployeeNumber
                                                  INNER JOIN R_Branch B
                                                    ON B.BranchId = BE.BranchId
                                                    WHERE EMP.EmployeeNumber != '000000'
                                                      ORDER BY EMP.LastName ASC
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getCurrentPassword($Password ,$EmployeeNumber)
    {
      $Roles = $this->maintenance_model->getLoggedInRoles(); // to check if may access syang maview
      $query_string = $this->db->query("SELECT Password
                                                FROM R_UserRole
                                                  WHERE EmployeeNumber = '$EmployeeNumber'
                                                  AND Password = '$Password'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function managerNotifications($EmployeeNumber)
    {
      $query_string = $this->db->query("SELECT  MN.Description
                                                , MN.CreatedBy
                                                , DATE_FORMAT(MN.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , DATE_FORMAT(MN.DateCreated, '%d %b %Y %h:%i %p') as rawDateCreated
                                                , MN.NotificationId
                                                FROM Manager_has_Notifications MN
                                                  INNER JOIN branch_has_manager BM
                                                    ON BM.ManagerBranchId = MN.ManagerBranchId
                                                  INNER JOIN R_Employee EMP
                                                    ON EMP.EmployeeNumber = BM.EmployeeNumber
                                                      WHERE EMP.EmployeeNumber = '$EmployeeNumber' 
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function employeeAudit($EmployeeNumber)
    {
      $query_string = $this->db->query("SELECT  Description
                                                , DATE_FORMAT(DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , DATE_FORMAT(DateCreated, '%d %b %Y %h:%i %p') as rawDateCreated
                                                , CreatedBy
                                                , EmployeeLogId
                                                FROM l_employeelog 
                                                  WHERE CreatedBy = '$EmployeeNumber' 
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function updateEmail($input)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $CreatedBy = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");

      if($input['tableType'] == 'EmployeeAddress')
      {
        $EmployeeDetail = $this->db->query("SELECT  EA.EmployeeNumber
                                                    , A.AddressId
                                                    , EmployeeId
                                                    FROM employee_has_address EA
                                                      INNER JOIN r_address A
                                                        ON A.AddressId = EA.AddressId
                                                      INNER JOIN R_Employee EMP
                                                        ON EMP.EmployeeNumber = EA.EmployeeNumber
                                                      WHERE EA.EmployeeAddressId = ".$input['Id']."
        ")->row_array();
        $AddressTransactionNumber = $this->db->query("SELECT LPAD(".$input['Id'].", 6, 0) as Id")->row_array();
        if($input['updateType'] == 1 || $input['updateType'] == 0) // deactivate and re-activate address of employee
        {
          // update status
            $set = array( 
              'StatusId' => $input['updateType'],
              'UpdatedBy' => $EmployeeNumber,
              'DateUpdated' => $DateNow,
            );
            $condition = array( 
              'EmployeeAddressId' => $input['Id']
            );
            $table = 'employee_has_address';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // insert into logs
            if($input['updateType'] == 1)
            {
              $Description = 'Re-activated address record #ADD-' .$AddressTransactionNumber['Id']. ' of employee #'.$EmployeeDetail['EmployeeNumber']; // main log
              $EmployeeNotification = 'Re-activated address record #ADD-' .$AddressTransactionNumber['Id']. '.'; // employee notification
            }
            else if($input['updateType'] == 0)
            {
              $Description = 'Deactivated address record #ADD-' .$AddressTransactionNumber['Id']. ' of employee #'.$EmployeeDetail['EmployeeNumber']; // main log
              $EmployeeNotification = 'Deactivated address record #ADD-' .$AddressTransactionNumber['Id']. '.'; // employee notification
            }
            $data2 = array(
              'Description'   => $Description,
              'CreatedBy'     => $EmployeeNumber,
              'DateCreated'   => $DateNow
            );
            $this->db->insert('R_Logs', $data2);
            $data3 = array(
              'Description'         => $EmployeeNotification,
              'CreatedBy'           => $EmployeeNumber,
              'EmployeeNumber'      => $EmployeeDetail['EmployeeNumber'],
              'DateCreated'         => $DateNow
            );
            $this->db->insert('employee_has_notifications', $data3);
            $this->db->insert('Manager_has_notifications', $data2);
        }
        else // set as primary address
        {
          // update primary to not primary status
            $set = array( 
              'IsPrimary' => 0,
              'UpdatedBy' => $EmployeeNumber,
              'DateUpdated' => $DateNow,
            );
            $condition = array( 
              'EmployeeNumber' => $EmployeeDetail['EmployeeNumber'],
              'IsPrimary' => 1
            );
            $table = 'employee_has_address';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // update status selected id to yes
            $set = array( 
              'IsPrimary' => 1,
              'UpdatedBy' => $EmployeeNumber,
              'DateUpdated' => $DateNow,
            );
            $condition = array( 
              'EmployeeAddressId' => $input['Id']
            );
            $table = 'employee_has_address';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // insert into logs
            $ManagerBranchId = $this->employee_model->getEmployeeDetails($EmployeeDetail['EmployeeId']);
            $Description = 'Set address record #ADD-' .$AddressTransactionNumber['Id']. ' of employee #'.$EmployeeDetail['EmployeeNumber'] . ' as primary.'; // main log
            $EmployeeNotification = 'Set address record #ADD-' .$AddressTransactionNumber['Id']. ' as primary address.';
            $insertEmpLog = array(
              'Description'       => $EmployeeNotification
              , 'EmployeeNumber'  => $EmployeeDetail['EmployeeNumber']
              , 'CreatedBy'       => $EmployeeNumber
            );
            $insertMainLog = array(
              'Description'       => $Description
              , 'CreatedBy'       => $EmployeeNumber
            );
            $insertManagerAudit = array(
              'Description'         => $Description
              , 'ManagerBranchId'   => $ManagerBranchId['ManagerBranchId']
              , 'CreatedBy'         => $EmployeeNumber
            );
            $auditTable2 = 'R_Logs';
            $this->maintenance_model->insertFunction($insertMainLog, $auditTable2);
            $auditTable3 = 'employee_has_notifications';
            $this->maintenance_model->insertFunction($insertEmpLog, $auditTable3);
            $auditTable4 = 'manager_has_notifications';
            $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable4);
        }
      }
      else if($input['tableType'] == 'EmployeeEmail')
      {
        $EmployeeDetail = $this->db->query("SELECT  EE.EmployeeNumber 
                                                    , E.EmployeeId
                                                    FROM employee_has_emails EE
                                                      INNER JOIN R_Employee E
                                                        ON E.EmployeeNumber = EE.EmployeeNumber
                                                      WHERE EE.EmployeeEmailId = ".$input['Id']."
        ")->row_array();
        $TransactionNumber = $this->db->query("SELECT LPAD(".$input['Id'].", 6, 0) as Id")->row_array();
        if($input['updateType'] == 1 || $input['updateType'] == 0) // deactivate and re-activate email of employee
        {
          // update status
            $set = array( 
              'StatusId' => $input['updateType'],
              'UpdatedBy' => $EmployeeNumber,
              'DateUpdated' => $DateNow,
            );
            $condition = array( 
              'EmployeeEmailId' => $input['Id']
            );
            $table = 'employee_has_emails';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // insert into logs
            if($input['updateType'] == 1)
            {
              $Description = 'Re-activated email record #EA-' .$TransactionNumber['Id']. ' of employee #'.$EmployeeDetail['EmployeeNumber']; // main log
              $EmployeeNotification = 'Re-activated email record #EA-' .$TransactionNumber['Id']. ' as email address.'; // employee notification
            }
            else if($input['updateType'] == 0)
            {
              $Description = 'Deactivated email record #EA-' .$TransactionNumber['Id']. ' of employee #'.$EmployeeDetail['EmployeeNumber']; // main log
              $EmployeeNotification = 'Deactivated email record #EA-' .$TransactionNumber['Id']. ' as email address.'; // employee notification
            }
          // insert into logs
            $ManagerBranchId = $this->employee_model->getEmployeeDetails($EmployeeDetail['EmployeeId']);
            $insertEmpLog = array(
              'Description'       => $EmployeeNotification
              , 'EmployeeNumber'  => $EmployeeDetail['EmployeeNumber']
              , 'CreatedBy'       => $EmployeeNumber
            );
            $insertMainLog = array(
              'Description'       => $Description
              , 'CreatedBy'       => $EmployeeNumber
            );
            $insertManagerAudit = array(
              'Description'         => $Description
              , 'ManagerBranchId'   => $ManagerBranchId['ManagerBranchId']
              , 'CreatedBy'         => $EmployeeNumber
            );
            $auditTable2 = 'R_Logs';
            $this->maintenance_model->insertFunction($insertMainLog, $auditTable2);
            $auditTable3 = 'employee_has_notifications';
            $this->maintenance_model->insertFunction($insertEmpLog, $auditTable3);
            $auditTable4 = 'manager_has_notifications';
            $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable4);
        }
        else // set as primary email
        {
          // update primary to not primary status
            $set = array( 
              'IsPrimary' => 0,
              'UpdatedBy' => $EmployeeNumber,
              'DateUpdated' => $DateNow,
            );
            $condition = array( 
              'EmployeeNumber' => $EmployeeDetail['EmployeeNumber'],
              'IsPrimary' => 1
            );
            $table = 'employee_has_emails';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // update status
            $set = array( 
              'IsPrimary' => 1,
              'UpdatedBy' => $EmployeeNumber,
              'DateUpdated' => $DateNow,
            );
            $condition = array( 
              'EmployeeEmailId' => $input['Id']
            );
            $table = 'employee_has_emails';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // insert into logs
            $Description = 'Set email record #EA-' .$TransactionNumber['Id']. ' of employee #'.$EmployeeDetail['EmployeeNumber'] . ' as primary.';
            $EmployeeNotification = 'Set email record #EA-' .$TransactionNumber['Id']. ' as primary email address.';
            $ManagerBranchId = $this->employee_model->getEmployeeDetails($EmployeeDetail['EmployeeId']);
            $insertEmpLog = array(
              'Description'       => $EmployeeNotification
              , 'EmployeeNumber'  => $EmployeeDetail['EmployeeNumber']
              , 'CreatedBy'       => $EmployeeNumber
            );
            $insertMainLog = array(
              'Description'       => $Description
              , 'CreatedBy'       => $EmployeeNumber
            );
            $insertManagerAudit = array(
              'Description'         => $Description
              , 'ManagerBranchId'   => $ManagerBranchId['ManagerBranchId']
              , 'CreatedBy'         => $EmployeeNumber
            );
            $auditTable2 = 'R_Logs';
            $this->maintenance_model->insertFunction($insertMainLog, $auditTable2);
            $auditTable3 = 'employee_has_notifications';
            $this->maintenance_model->insertFunction($insertEmpLog, $auditTable3);
            $auditTable4 = 'manager_has_notifications';
            $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable4);
        }
      }
      else if($input['tableType'] == 'EmployeeId')
      {
        $EmployeeDetail = $this->db->query("SELECT  EI.EmployeeNumber 
                                                    , EmployeeId
                                                    FROM employee_has_identifications EI
                                                      INNER JOIN R_Employee I
                                                        ON EI.EmployeeNumber = I.EmployeeNumber
                                                      WHERE EI.EmployeeIdentificationId = ".$input['Id']."
        ")->row_array();
        $TransactionNumber = $this->db->query("SELECT LPAD(".$input['Id'].", 6, 0) as Id")->row_array();
        if($input['updateType'] == 1 || $input['updateType'] == 0) // deactivate and re-activate email of employee
        {
          // update status
            $set = array(
              'StatusId' => $input['updateType'],
              'UpdatedBy' => $EmployeeNumber,
              'DateUpdated' => $DateNow,
            );
            $condition = array(
              'EmployeeIdentificationId' => $input['Id']
            );
            $table = 'employee_has_identifications';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // insert into logs
            if($input['updateType'] == 1)
            {
              $Description = 'Re-activated identification record #ID-'.$TransactionNumber['Id'].' of employee #'.$EmployeeDetail['EmployeeNumber'];
              $EmployeeNotification = 'Re-activated identification record #ID-'.$TransactionNumber['Id'].'.';
            }
            else if($input['updateType'] == 0)
            {
              $Description = 'Deactivated identification record #ID-'.$TransactionNumber['Id'].' of employee #'.$EmployeeDetail['EmployeeNumber'].'.';
              $EmployeeNotification = 'Deactivated identification record #ID-'.$TransactionNumber['Id'].'.';
            }
            $ManagerBranchId = $this->employee_model->getEmployeeDetails($EmployeeDetail['EmployeeId']);
            $insertEmpLog = array(
              'Description'       => $EmployeeNotification
              , 'EmployeeNumber'  => $EmployeeDetail['EmployeeNumber']
              , 'CreatedBy'       => $EmployeeNumber
            );
            $insertMainLog = array(
              'Description'       => $Description
              , 'CreatedBy'       => $EmployeeNumber
            );
            $insertManagerAudit = array(
              'Description'         => $Description
              , 'ManagerBranchId'   => $ManagerBranchId['ManagerBranchId']
              , 'CreatedBy'         => $EmployeeNumber
            );
            $auditTable2 = 'R_Logs';
            $this->maintenance_model->insertFunction($insertMainLog, $auditTable2);
            $auditTable3 = 'employee_has_notifications';
            $this->maintenance_model->insertFunction($insertEmpLog, $auditTable3);
            $auditTable4 = 'manager_has_notifications';
            $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable4);
        }
      }
      else if($input['tableType'] == 'EmployeeContact')
      {
        $EmployeeDetail = $this->db->query("SELECT  EC.EmployeeNumber 
                                                  , C.Number
                                                  , EMP.EmployeeId
                                                  FROM employee_has_contactnumbers EC
                                                    INNER JOIN R_ContactNumbers C
                                                      ON EC.ContactNumberId = C.ContactNumberId
                                                    INNER JOIN R_Employee EMP
                                                      ON EMP.EmployeeNumber = EC.EmployeeNumber
                                                    WHERE EC.EmployeeContactId = ".$input['Id']."
        ")->row_array();
        $TransactionNumber = $this->db->query("SELECT LPAD(".$input['Id'].", 6, 0) as Id")->row_array();
        if($input['updateType'] == 1 || $input['updateType'] == 0) // deactivate and re-activate email of employee
        {
          // update status
            $set = array(
              'StatusId' => $input['updateType'],
              'UpdatedBy' => $EmployeeNumber,
              'DateUpdated' => $DateNow,
            );
            $condition = array(
              'EmployeeContactId' => $input['Id']
            );
            $table = 'employee_has_contactnumbers';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // insert into logs
            if($input['updateType'] == 1)
            {
              $Description = 'Re-activated contact record #CN-'.$TransactionNumber['Id'].' of employee #'.$EmployeeDetail['EmployeeNumber'];
              $EmployeeNotification = 'Re-activated contact record #CN-'.$TransactionNumber['Id'].'.';
            }
            else if($input['updateType'] == 0)
            {
              $Description = 'Deactivated contact record #CN-'.$TransactionNumber['Id'].' of employee #'.$EmployeeDetail['EmployeeNumber'].'.';
              $EmployeeNotification = 'Deactivated contact record #CN-'.$TransactionNumber['Id'].'.';
            }
            $ManagerBranchId = $this->employee_model->getEmployeeDetails($EmployeeDetail['EmployeeId']);
            $insertEmpLog = array(
              'Description'       => $EmployeeNotification
              , 'EmployeeNumber'  => $EmployeeDetail['EmployeeNumber']
              , 'CreatedBy'       => $EmployeeNumber
            );
            $insertMainLog = array(
              'Description'       => $Description
              , 'CreatedBy'       => $EmployeeNumber
            );
            $insertManagerAudit = array(
              'Description'         => $Description
              , 'ManagerBranchId'   => $ManagerBranchId['ManagerBranchId']
              , 'CreatedBy'         => $EmployeeNumber
            );
            $auditTable2 = 'R_Logs';
            $this->maintenance_model->insertFunction($insertMainLog, $auditTable2);
            $auditTable3 = 'employee_has_notifications';
            $this->maintenance_model->insertFunction($insertEmpLog, $auditTable3);
            $auditTable4 = 'manager_has_notifications';
            $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable4);
        }
        else // set as primary
        {
          // update primary to not primary status
            $set = array( 
              'IsPrimary' => 0,
              'UpdatedBy' => $EmployeeNumber,
              'DateUpdated' => $DateNow,
            );
            $condition = array( 
              'EmployeeNumber' => $EmployeeDetail['EmployeeNumber'],
              'IsPrimary' => 1
            );
            $table = 'employee_has_contactnumbers';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // update status
            $set = array( 
              'IsPrimary' => 1,
              'UpdatedBy' => $EmployeeNumber,
              'DateUpdated' => $DateNow,
            );
            $condition = array( 
              'EmployeeContactId' => $input['Id']
            );
            $table = 'employee_has_contactnumbers';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // insert into logs
            $Description = 'Set contact record #CN-' .$TransactionNumber['Id']. ' of employee #'.$EmployeeDetail['EmployeeNumber'] . ' as primary.';
            $EmployeeNotification = 'Set contact record #CN-' .$TransactionNumber['Id']. ' as primary email address.';
            $ManagerBranchId = $this->employee_model->getEmployeeDetails($EmployeeDetail['EmployeeId']);
            $insertEmpLog = array(
              'Description'       => $EmployeeNotification
              , 'EmployeeNumber'  => $EmployeeDetail['EmployeeNumber']
              , 'CreatedBy'       => $EmployeeNumber
            );
            $insertMainLog = array(
              'Description'       => $Description
              , 'CreatedBy'       => $EmployeeNumber
            );
            $insertManagerAudit = array(
              'Description'         => $Description
              , 'ManagerBranchId'   => $ManagerBranchId['ManagerBranchId']
              , 'CreatedBy'         => $EmployeeNumber
            );
            $auditTable2 = 'R_Logs';
            $this->maintenance_model->insertFunction($insertMainLog, $auditTable2);
            $auditTable3 = 'employee_has_notifications';
            $this->maintenance_model->insertFunction($insertEmpLog, $auditTable3);
            $auditTable4 = 'manager_has_notifications';
            $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable4);
        }
      }
      else if($input['tableType'] == 'EmployeeUpdate')
      {
        $EmployeeDetail = $this->db->query("SELECT  EMP.EmployeeNumber
                                                    FROM R_Employee EMP
                                                    WHERE EMP.EmployeeId = ".$input['Id']."
        ")->row_array();
        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $CreatedBy,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'EmployeeId' => $input['Id']
          );
          $table = 'R_Employee';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated employee #'.$EmployeeDetail['EmployeeNumber'].'.';
            $EmployeeNotification = 'Re-activated employee.';
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated employee #'.$EmployeeDetail['EmployeeNumber'].'.';
            $EmployeeNotification = 'Deactivated employee.';
          }
          $ManagerBranchId = $this->employee_model->getEmployeeDetails($input['Id']);
          $insertEmpLog = array(
            'Description'       => $EmployeeNotification
            , 'EmployeeNumber'  => $EmployeeDetail['EmployeeNumber']
            , 'CreatedBy'       => $CreatedBy
          );
          $insertMainLog = array(
            'Description'       => $Description
            , 'CreatedBy'       => $CreatedBy
          );
          $insertManagerAudit = array(
            'Description'         => $Description
            , 'ManagerBranchId'   => $ManagerBranchId['ManagerBranchId']
            , 'CreatedBy'         => $CreatedBy
          );
          $auditTable2 = 'R_Logs';
          $this->maintenance_model->insertFunction($insertMainLog, $auditTable2);
          $auditTable3 = 'employee_has_notifications';
          $this->maintenance_model->insertFunction($insertEmpLog, $auditTable3);
          $auditTable4 = 'manager_has_notifications';
          $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable4);
      }
      else if($input['tableType'] == 'UserRoleUpdate')
      {
        $EmployeeDetail = $this->db->query("SELECT  UR.UserRoleId
                                                  , UR.EmployeeNumber
                                                    FROM R_UserRole UR
                                                    WHERE UR.UserRoleId = ".$input['Id']."
        ")->row_array();
        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $CreatedBy,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'UserRoleId' => $input['Id']
          );
          $table = 'R_UserRole';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated employee #'.$EmployeeDetail['EmployeeNumber'].'.';
            $EmployeeNotification = 'Re-activated employee.';
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated employee #'.$EmployeeDetail['EmployeeNumber'].'.';
            $EmployeeNotification = 'Deactivated employee.';
          }
          $ManagerBranchId = $this->employee_model->getEmployeeDetails($input['Id']);
          $insertEmpLog = array(
            'Description'       => $EmployeeNotification
            , 'EmployeeNumber'  => $EmployeeDetail['EmployeeNumber']
            , 'CreatedBy'       => $CreatedBy
          );
          $insertMainLog = array(
            'Description'       => $Description
            , 'CreatedBy'       => $CreatedBy
          );
          $insertManagerAudit = array(
            'Description'         => $Description
            , 'ManagerBranchId'   => $ManagerBranchId['ManagerBranchId']
            , 'CreatedBy'         => $CreatedBy
          );
          $auditTable2 = 'R_Logs';
          $this->maintenance_model->insertFunction($insertMainLog, $auditTable2);
          $auditTable3 = 'employee_has_notifications';
          $this->maintenance_model->insertFunction($insertEmpLog, $auditTable3);
          $auditTable4 = 'manager_has_notifications';
          $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable4);
      }
    }

    function employeeEmails($EmployeeNumber)
    {
      $EmployeeNumber = $this->db->query("SELECT LPAD($EmployeeNumber, 6, 0) as EmployeeNumber")->row_array();
      $query_string = $this->db->query("SELECT  E.EmailAddress
                                                , EE.StatusId
                                                , EE.EmployeeEmailId
                                                , EE.IsPrimary
                                                , EE.CreatedBy
                                                , DATE_FORMAT(EE.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , DATE_FORMAT(EE.DateUpdated, '%d %b %Y %h:%i %p') as DateUpdated
                                                , CONCAT('EA-', LPAD(EE.EmployeeEmailId, 6, 0)) as rowNumber
                                                , EMP.FirstName
                                                , EMP.LastName
                                                , acronym(EMP.MiddleName) as MiddleInitial
                                                FROM R_Emails E
                                                  INNER JOIN employee_has_emails EE
                                                    ON EE.EmailId = E.EmailId
                                                  INNER JOIN R_Employee EMP
                                                    ON EMP.EmployeeNumber = EE.CreatedBy
                                                      WHERE EE.EmployeeNumber = '".$EmployeeNumber['EmployeeNumber']."'
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function contactNumbers($EmployeeNumber)
    {
      $EmployeeNumber = $this->db->query("SELECT LPAD($EmployeeNumber, 6, 0) as EmployeeNumber")->row_array();
      $query_string = $this->db->query("SELECT  CN.PhoneType
                                                , Number
                                                , EC.StatusId
                                                , EC.CreatedBy
                                                , EC.EmployeeContactId
                                                , EC.IsPrimary
                                                , EMP.FirstName
                                                , EMP.LastName
                                                , CONCAT('CN-', LPAD(EC.EmployeeContactId, 6, 0)) as rowNumber
                                                , acronym(EMP.MiddleName) as MiddleInitial
                                                , DATE_FORMAT(EC.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , DATE_FORMAT(EC.DateUpdated, '%d %b %Y %h:%i %p') as DateUpdated
                                                FROM R_ContactNumbers CN
                                                  INNER JOIN Employee_has_contactNumbers EC
                                                    ON EC.ContactNumberId = CN.ContactNumberId
                                                  INNER JOIN R_Employee EMP
                                                    ON EMP.EmployeeNumber = EC.CreatedBy
                                                      WHERE EC.EmployeeNumber = ".$EmployeeNumber['EmployeeNumber']."
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function employeeAddress($EmployeeId)
    {
      $EmployeeNumber = $this->db->query("SELECT LPAD($EmployeeId, 6, 0) as EmployeeNumber")->row_array();
      $query_string = $this->db->query("SELECT DISTINCT  EA.EmployeeAddressId
                                                , IsPrimary
                                                , A.AddressType
                                                , UPPER(A.HouseNo) as HouseNo
                                                , UPPER(B.brgyDesc) as brgyDesc
                                                , UPPER(P.provDesc) as provDesc
                                                , UPPER(C.cityMunDesc) as cityMunDesc
                                                , UPPER(R.regDesc) as regDesc
                                                , EMP.FirstName
                                                , EMP.LastName
                                                , EA.StatusId
                                                , EA.EmployeeNumber
                                                , CONCAT('ADD-', LPAD(EA.EmployeeAddressId, 6, 0)) as rowNumber
                                                , acronym(EMP.MiddleName) as MiddleInitial
                                                , DATE_FORMAT(EA.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , DATE_FORMAT(EA.DateUpdated, '%d %b %Y %h:%i %p') as DateUpdated
                                                FROM employee_has_address EA
                                                  INNER JOIN r_address A
                                                    ON A.AddressId = EA.AddressId
                                                  INNER JOIN R_Employee EMP
                                                    ON EMP.EmployeeNumber = EA.CreatedBy
                                                  INNER JOIN add_barangay B
                                                    ON B.brgyCode = A.BarangayId
                                                  INNER JOIN add_province P
                                                    ON P.provCode = B.provCode
                                                  INNER JOIN add_city C
                                                    ON C.citymunCode = B.citymunCode
                                                  INNER JOIN add_region R 
                                                    ON R.regCode = B.regCode
                                                      WHERE EA.EmployeeNumber = ".$EmployeeNumber['EmployeeNumber']."
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function employeeIDs($EmployeeNumber)
    {
      $EmployeeNumber = $this->db->query("SELECT LPAD($EmployeeNumber, 6, 0) as EmployeeNumber")->row_array();
      $query_string = $this->db->query("SELECT  IC.Name
                                                , I.Attachment
                                                , I.IdentificationId
                                                , I.Description
                                                , I.IdNumber
                                                , EI.StatusId
                                                , EI.EmployeeIdentificationId
                                                , DATE_FORMAT(EI.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , DATE_FORMAT(EI.DateUpdated, '%d %b %Y %h:%i %p') as DateUpdated
                                                , CONCAT('ID-', LPAD(EI.EmployeeIdentificationId, 6, 0)) as rowNumber
                                                , EMP.FirstName
                                                , EMP.LastName
                                                , acronym(EMP.MiddleName) as MiddleInitial
                                                FROM r_identificationCards I
                                                  INNER JOIN employee_has_identifications EI
                                                    ON EI.IdentificationId = I.IdentificationId
                                                  INNER JOIN R_Employee EMP
                                                    ON EMP.EmployeeNumber = EI.CreatedBy
                                                  INNER JOIN r_IDCategory IC
                                                    ON IC.ID = I.ID
                                                      WHERE EI.EmployeeNumber = '".$EmployeeNumber['EmployeeNumber']."'
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function employeeNotification($EmployeeNumber)
    {
      $EmployeeNumber = $this->db->query("SELECT LPAD($EmployeeNumber, 6, 0) as EmployeeNumber")->row_array();
      $query_string = $this->db->query("SELECT  Description
                                                , DATE_FORMAT(EN.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , EMP.FirstName
                                                , EMP.LastName
                                                , acronym(EMP.MiddleName) as MiddleInitial
                                                FROM employee_has_notifications EN
                                                  INNER JOIN R_Employee EMP
                                                    ON EMP.EmployeeNumber = EN.CreatedBy
                                                      WHERE EN.EmployeeNumber = '".$EmployeeNumber['EmployeeNumber']."'
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAttachment($EmployeeIdentificationId)
    {
      $query_string = $this->db->query("SELECT  IC.Attachment
                                                , IC.FileName
                                                FROM r_identificationcards IC
                                                      INNER JOIN employee_has_identifications EI
                                                          ON EI.IdentificationId = IC.IdentificationId
                                                              WHERE EI.EmployeeIdentificationId = $EmployeeIdentificationId

      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getAccessManagement()
    {
      $query_string = $this->db->query("SELECT  Description
                                                , ModuleId
                                                , StatusId
                                                , ModuleId
                                                FROM r_modules
                                                      WHERE StatusId = 1
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getSubmodules()
    {
      $query_string = $this->db->query("SELECT  DISTINCT Description
                                                , ModuleId
                                                , StatusId
                                                , Code
                                                , SubModuleId
                                                FROM r_submodules
                                                      WHERE StatusId = 1
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getModuleAccess($Id)
    {
      $EmployeeNumber = sprintf('%06d', $Id);
      $query_string = $this->db->query("SELECT  Description
                                                , ModuleId
                                                , StatusId
                                                , Code
                                                , SubModuleId
                                                FROM R_UserAccess
                                                WHERE EmployeeNumber = '$EmployeeNumber'
                                                  AND StatusId = 1
      ");
      $data = $query_string->result_array();
      return $data;
    }

}