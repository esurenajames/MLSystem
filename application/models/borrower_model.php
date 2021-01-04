<?php
class borrower_model extends CI_Model
{
    function __construct()
    {
      parent::__construct();
			$this->load->model('maintenance_model');
			$this->load->model('access');
      date_default_timezone_set('Asia/Manila');
    }

    function getAllUsers()
    {
      $query_string = $this->db->query("SELECT 	DISTINCT UR.EmployeeNumber
      																					, UR.StatusId
                                                , CASE 
                                                    WHEN (SELECT DISTINCT COUNT(Description) 
                                                                          FROM R_Logs 
                                                                          WHERE CreatedBy = UR.EmployeeNumber 
                                                                          AND 
                                                                          (
                                                                            Description = 'Logged in.'
                                                                            OR
                                                                            Description = 'Changed password'
                                                                          )
                                                          ) < 1
                                                    THEN CAST(Password AS CHAR(10000) CHARACTER SET utf8)
                                                    ELSE 'N/A'
                                                END as Password2
                                                , CAST(Password AS CHAR(10000) CHARACTER SET utf8) as Password
      																					, UR.UserRoleId
      																					, R.Description
                                                , DATE_FORMAT(UR.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , DATE_FORMAT(UR.DateUpdated, '%d %b %Y %h:%i %p') as DateUpdated
      																					FROM R_UserRole UR
      																						INNER JOIN R_Role R
      																							ON R.RoleId = UR.RoleId
                                                      AND UR.EmployeeNumber != 'sysad'
			");
      $data = $query_string->result_array();
      return $data;
    }

    // COUNTS //

    function countBorrower($input)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM r_Borrowers
                                                  WHERE FirstName = '".$input['FirstName']."'
                                                  AND MiddleName = '".$input['MiddleName']."'
                                                  AND ExtName = '".$input['ExtName']."'
                                                  AND LastName = '".$input['LastName']."'
                                                  AND DateOfBirth = '".$input['DOB']."'
                                                  AND MotherName = '".$input['MotherName']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function countEmploymentRecord($input)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM borrower_has_employer
                                                  WHERE EmployerName = '".$input['EmployerName']."'
                                                  AND PositionId = '".$input['PositionId']."'
                                                  AND DateHired = '".$input['DateHired']."'
                                                  AND TelephoneNumber = '".$input['TelephoneNumber']."'
                                                  AND TenureYear = '".$input['TenureYear']."'
                                                  AND TenureMonth = '".$input['TenureMonth']."'
                                                  AND BusinessAddress = '".$input['BusinessAddress']."'
                                                  AND IndustryId = '".$input['IndustryId']."'
                                                  AND EmployerStatus  = '".$input['EmployerStatus']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function countSpouse($input)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM r_spouse
                                                WHERE FirstName = '".$input['FirstName']."'
                                                AND MiddleName = '".$input['MiddleName']."'
                                                AND ExtName = '".$input['ExtName']."'
                                                AND LastName = '".$input['LastName']."'
                                                AND DateOfBirth = '".$input['DateOfBirth']."'

      ");
      $data = $query_string->row_array();
      if($data['SpouseId'] != null)
      {
        $query2 = $this->db->query("SELECT  *
                                            FROM Borrower_has_spouse
                                              WHERE SpouseId = ".$data['SpouseId']."
                                              AND BorrowerId = '".$input['BorrowerId']."'
        ");
        $data2 = $query2->num_rows();
        return $data2;
      }
      else
      {
        return 0;
      }
    }

    function countCoMaker($input)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM borrower_has_comaker
                                                  WHERE BorrowerId = '".$input['BorrowerId']."'
                                                  AND Birthdate = '".$input['DOB']."'
                                                  AND PositionId = '".$input['PositionId']."'
                                                  AND Employer = '".$input['Employer']."'

      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function countContact($input)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM r_contactnumbers
                                                  WHERE ContactNumberId = '".$input['FirstName']."'
                                                  AND MiddleName = '".$input['MiddleName']."'
                                                  AND ExtName = '".$input['ExtName']."'
                                                  AND LastName = '".$input['LastName']."'
                                                  AND DateOfBirth = '".$input['DOB']."'

      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function countEducation($input)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM Borrower_has_Education EDU
                                                  WHERE SchoolName = '".$input['SchoolName']."'
                                                  AND Level = '".$input['Level']."'
                                                  AND YearGraduated = '".$input['YearGraduated']."'

      ");
      $data = $query_string->num_rows();
      return $data;
    }

    // GET BORROWER //

    function getBorrowerDetails($Id)
    {
      $query_string = $this->db->query("SELECT DISTINCT B.BorrowerId
                                                , S.name as Salutation
                                                , B.FirstName
                                                , CONCAT(B.LastName, ', ', B.FirstName, ' ', B.MiddleName, ', ', B.ExtName) as Name
                                                , acronym (B.MiddleName) as MiddleInitial
                                                , B.LastName
                                                , B.ExtName
                                                , B.BranchId
                                                , B.Dependents
                                                , SX.Name as Sex
                                                , N.Description as Nationality
                                                , C.name as CivilStatus
                                                , DATE_FORMAT(B.DateOfBirth, '%d %b %Y') as DateOfBirth
                                                , DATE_FORMAT(B.DateCreated, '%d %b %Y %h:%i %p') as DateAdded
                                                , DATE_FORMAT(B.DateOfBirth, '%Y-%b-%d') as RawDateOfBirth
                                                , SS.Name as StatusDescription
                                                , B.StatusId
                                                , BP.FileName
                                                , B.BorrowerNumber
                                                , B.MotherName

                                                , B.MiddleName
                                                , S.SalutationId
                                                , SX.SexId
                                                , N.NationalityId
                                                , N.Description as NationalityName
                                                , C.CivilStatusId
                                                , B.Birthplace

                                                , CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', EMP.MiddleName, ', ', EMP.ExtName) as AddedBy
                                                , (SELECT E.EmailAddress 
                                                          FROM Borrower_has_emails BHE
                                                            INNER JOIN R_Emails E
                                                              ON E.EmailId = BHE.EmailId
                                                                WHERE BorrowerId = B.BorrowerId
                                                                AND IsPrimary = 1 
                                                                LIMIT 1) as EmailAddress
                                                , (SELECT Number 
                                                          FROM Borrower_has_Contactnumbers BHC
                                                            INNER JOIN R_ContactNumbers CN
                                                              ON BHC.ContactNumberId = CN.ContactNumberId
                                                                WHERE BorrowerId = B.BorrowerId
                                                                AND IsPrimary = 1
                                                                LIMIT 1) as ContactNumber
                                                , TIMESTAMPDIFF(YEAR, B.DateOfBirth, CURDATE()) as Age
                                                FROM R_Borrowers B
                                                  INNER JOIN R_Salutation S
                                                    ON S.SalutationId = B.Salutation
                                                  INNER JOIN R_Sex SX
                                                    ON SX.SexId = B.Sex
                                                  INNER JOIN r_nationality N
                                                    ON N.NationalityId = B.Nationality
                                                  INNER JOIN r_civilstatus C
                                                    ON C.CivilStatusId = B.CivilStatus
                                                  INNER JOIN R_BorrowerStatus SS
                                                    ON SS.BorrowerStatusId = B.StatusId
                                                  INNER JOIN r_employee EMP
                                                    ON EMP.EmployeeNumber = B.CreatedBy
                                                  LEFT JOIN borrower_has_picture BP
                                                    ON BP.BorrowerId = B.BorrowerId
                                                    AND BP.StatusId = 1
                                                  WHERE B.BorrowerId = $Id

      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getBorrowerEmails($BorrowerID)
    {
      $query_string = $this->db->query("SELECT  E.EmailAddress
                                                , EE.StatusId
                                                , EE.BorrowerEmailId
                                                , EE.IsPrimary
                                                , EE.CreatedBy
                                                , DATE_FORMAT(EE.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , EE.DateCreated as rawDateCreated
                                                , CONCAT('EA-', LPAD(EE.BorrowerEmailId, 6, 0)) as rowNumber
                                                FROM R_Emails E
                                                  INNER JOIN borrower_has_emails EE
                                                    ON EE.EmailId = E.EmailId
                                                      WHERE EE.BorrowerId = '".$BorrowerID."'
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getBorrowerNumber($BorrowerID)
    {
      $query_string = $this->db->query("SELECT  CN.PhoneType
                                                , Number
                                                , EC.StatusId
                                                , EC.CreatedBy
                                                , EC.BorrowerContactId
                                                , EC.IsPrimary
                                                , CONCAT('CN-', LPAD(EC.BorrowerContactId, 6, 0)) as rowNumber
                                                , DATE_FORMAT(EC.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                FROM R_ContactNumbers CN
                                                  INNER JOIN borrower_has_contactNumbers EC
                                                    ON EC.ContactNumberId = CN.ContactNumberId
                                                      WHERE EC.BorrowerId = ".$BorrowerID."
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getNumberDetails($Id)
    {
      $query_string = $this->db->query("SELECT  CN.PhoneType
                                                , Number
                                                , EC.StatusId
                                                , EC.CreatedBy
                                                , EC.BorrowerContactId
                                                , EC.IsPrimary
                                                , CONCAT('CN-', LPAD(EC.BorrowerContactId, 6, 0)) as RefNo
                                                , DATE_FORMAT(EC.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                FROM R_ContactNumbers CN
                                                  INNER JOIN borrower_has_contactNumbers EC
                                                    ON EC.ContactNumberId = CN.ContactNumberId
                                                      WHERE EC.BorrowerContactId = ".$Id."
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getEmailDetails($Id)
    {
      $query_string = $this->db->query("SELECT  E.EmailAddress
                                                , EE.StatusId
                                                , EE.BorrowerEmailId
                                                , EE.IsPrimary
                                                , EE.CreatedBy
                                                , DATE_FORMAT(EE.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , EE.DateCreated as rawDateCreated
                                                , CONCAT('EA-', LPAD(EE.BorrowerEmailId, 6, 0)) as RefNo
                                                FROM R_Emails E
                                                  INNER JOIN borrower_has_emails EE
                                                    ON EE.EmailId = E.EmailId
                                                      WHERE EE.BorrowerEmailId = '".$Id."'
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getBorrowerAddress($BorrowerID)
    {
      $query_string = $this->db->query("SELECT DISTINCT  EA.BorrowerAddressHistoryId
                                                , IsPrimary
                                                , A.AddressType
                                                , UPPER(A.HouseNo) as HouseNo
                                                , UPPER(B.brgyDesc) as brgyDesc
                                                , UPPER(P.provDesc) as provDesc
                                                , UPPER(C.cityMunDesc) as cityMunDesc
                                                , UPPER(R.regDesc) as regDesc
                                                , EA.StatusId
                                                , EA.BorrowerId
                                                , DATE_FORMAT(EA.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , EA.DateCreated as rawDateCreated
                                                , CONCAT('ADD-', LPAD(EA.BorrowerAddressHistoryId, 6, 0)) as rowNumber
                                                FROM borrowerAddressHistory EA
                                                  INNER JOIN r_address A
                                                    ON A.AddressId = EA.AddressId
                                                  INNER JOIN add_barangay B
                                                    ON B.brgyCode = A.BarangayId
                                                  INNER JOIN add_province P
                                                    ON P.provCode = B.provCode
                                                  INNER JOIN add_city C
                                                    ON C.citymunCode = B.citymunCode
                                                  INNER JOIN add_region R 
                                                    ON R.regCode = B.regCode
                                                      WHERE EA.BorrowerId = ".$BorrowerID."
      ");
      $data = $query_string->result_array();
      return $data;
    }


    function getAddressDetails($Id)
    {
      $query_string = $this->db->query("SELECT DISTINCT  EA.BorrowerAddressHistoryId
                                                , IsPrimary
                                                , A.AddressType
                                                , UPPER(A.HouseNo) as HouseNo
                                                , UPPER(B.brgyDesc) as brgyDesc
                                                , UPPER(P.provDesc) as provDesc
                                                , UPPER(C.cityMunDesc) as cityMunDesc
                                                , UPPER(R.regDesc) as regDesc
                                                , EA.StatusId
                                                , EA.BorrowerId
                                                , DATE_FORMAT(EA.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , EA.DateCreated as rawDateCreated
                                                , CONCAT('ADD-', LPAD(EA.BorrowerAddressHistoryId, 6, 0)) as RefNo
                                                FROM borrowerAddressHistory EA
                                                  INNER JOIN r_address A
                                                    ON A.AddressId = EA.AddressId
                                                  INNER JOIN add_barangay B
                                                    ON B.brgyCode = A.BarangayId
                                                  INNER JOIN add_province P
                                                    ON P.provCode = B.provCode
                                                  INNER JOIN add_city C
                                                    ON C.citymunCode = B.citymunCode
                                                  INNER JOIN add_region R 
                                                    ON R.regCode = B.regCode
                                                      WHERE EA.BorrowerAddressHistoryId = ".$Id."
      ");
      $data = $query_string->row_array();
      return $data;
    }


    function getBorrowerCityAddress($Id)
    {
      $query_string = $this->db->query("SELECT DISTINCT  MAX(EA.BorrowerAddressHistoryId) as BorrowerAddressHistoryId
                                                , IsPrimary
                                                , A.AddressType
                                                , EA.AddressType as Address
                                                , UPPER(A.HouseNo) as HouseNo
                                                , UPPER(B.brgyDesc) as brgyDesc
                                                , UPPER(P.provDesc) as provDesc
                                                , UPPER(C.cityMunDesc) as cityMunDesc
                                                , UPPER(R.regDesc) as regDesc
                                                , EA.YearsStayed
                                                , EA.MonthsStayed
                                                , EA.NameOfLandlord
                                                , EA.StatusId
                                                , EA.BorrowerId
                                                , DATE_FORMAT(EA.DateCreated, '%d %b %Y %r') as DateCreated
                                                , CONCAT('ADD-', LPAD(EA.BorrowerAddressHistoryId, 6, 0)) as rowNumber
                                                , A.ContactNumber
                                                , A.Telephone
                                                , EA.ContactNumber as AddressContactNumber
                                                FROM borrowerAddressHistory EA
                                                  INNER JOIN r_address A
                                                    ON A.AddressId = EA.AddressId
                                                  INNER JOIN add_barangay B
                                                    ON B.brgyCode = A.BarangayId
                                                  INNER JOIN add_province P
                                                    ON P.provCode = B.provCode
                                                  INNER JOIN add_city C
                                                    ON C.citymunCode = B.citymunCode
                                                  INNER JOIN add_region R 
                                                    ON R.regCode = B.regCode
                                                      WHERE EA.BorrowerAddressHistoryId = ".$Id."
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getBorrowerEmployment($Id)
    {
      $query_string = $this->db->query("SELECT  CONCAT('ER-', LPAD(BHE.EmployerId, 6, 0)) as rowNumber
                                                , CONCAT('ER-', LPAD(BHE.EmployerId, 6, 0)) as RefNo
                                                , EmployerName
                                                , BHP.Name as Position
                                                , I.Name as Industry
                                                , CASE
                                                    WHEN EmployerStatus = 1
                                                    THEN 'Present Employer'
                                                    ELSE 'Previous Employer'
                                                  END as EmployerStatus
                                                , DATE_FORMAT(BHE.DateHired, '%d %b %Y') as DateHired
                                                , DATE_FORMAT(BHE.DateCreated, '%d %b %Y') as DateCreated
                                                , TenureYear
                                                , TenureMonth
                                                , BusinessAddress
                                                , TelephoneNumber
                                                , EmployerId
                                                , BHE.StatusId
                                                FROM borrower_has_employer BHE
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = BHE.BorrowerId
                                                  LEFT JOIN r_occupation BHP
                                                    ON BHP.OccupationId = BHE.PositionId
                                                  LEFT JOIN R_Industry I
                                                    ON I.IndustryId = BHE.IndustryId
                                                      WHERE BHE.EmployerId = $Id

      ");

      $data = $query_string->row_array();
      return $data;
    }

    function updateEmail($input)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");

      if($input['tableType'] == 'BorrowerAddress')
      {
        $BorrowerDetail = $this->db->query("SELECT  BA.BorrowerAddressHistoryId
                                                    , A.AddressId
                                                    , B.BorrowerId
                                                    , B.BorrowerNumber
                                                    FROM borrowerAddressHistory BA
                                                      INNER JOIN r_address A
                                                        ON A.AddressId = BA.AddressId
                                                      INNER JOIN R_Borrowers B
                                                        ON B.BorrowerId = BA.BorrowerId
                                                      WHERE BA.BorrowerAddressHistoryId = ".$input['Id']."
        ")->row_array();
        $AddressTransactionNumber = $this->db->query("SELECT LPAD(".$input['Id'].", 6, 0) as Id")->row_array();
        if($input['updateType'] == 1 || $input['updateType'] == 0) // deactivate and re-activate address of Borrower
        {
          $count = $this->db->query("SELECT  COUNT(*) as ifUsed
                                                      FROM application_has_address
                                                            WHERE BorrowerAddressHistoryId = ".$input['Id']."
                                                            AND StatusId = 1
          ")->row_array();
          if($count['ifUsed'] == 0)
          {
            // update status
              $set = array( 
                'StatusId' => $input['updateType'],
                'UpdatedBy' => $EmployeeNumber,
                'DateUpdated' => $DateNow,
              );
              $condition = array( 
                'BorrowerAddressHistoryId' => $input['Id']
              );
              $table = 'borrowerAddressHistory';
              $this->maintenance_model->updateFunction1($set, $condition, $table);
            // admin audits finalss
              if($input['updateType'] == 1)
              {
                $auditLogsManager = 'Re-activated address record #ADD-' .$AddressTransactionNumber['Id']. ' of borrower #'.$BorrowerDetail['BorrowerNumber'].'.';
                $auditBorrowerDetails = 'Re-activated address record #ADD-' .$AddressTransactionNumber['Id']. ' in address record tab.';
              }
              else if($input['updateType'] == 0)
              {
                $auditLogsManager = 'Deactivated address record #ADD-' .$AddressTransactionNumber['Id']. ' of borrower #'.$BorrowerDetail['BorrowerNumber'].'.';
                $auditBorrowerDetails = 'Deactivated address record #ADD-' .$AddressTransactionNumber['Id']. ' in address record tab.';
              }
              $this->auditBorrowerDetails($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditBorrowerDetails, $BorrowerDetail['BorrowerId']);
            return 1;
          }
          else
          {
            return 0;
          }
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
              'BorrowerId' => $BorrowerDetail['BorrowerId'],
              'IsPrimary' => 1
            );
            $table = 'borrowerAddressHistory';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // update status selected id to yes
            $set = array( 
              'IsPrimary' => 1,
              'UpdatedBy' => $EmployeeNumber,
              'DateUpdated' => $DateNow,
            );
            $condition = array( 
              'BorrowerAddressHistoryId' => $input['Id']
            );
            $table = 'borrowerAddressHistory';
            $this->maintenance_model->updateFunction1($set, $condition, $table);            
          // admin audits finalss
            $auditLogsManager = 'Set address record #ADD-' .$AddressTransactionNumber['Id']. ' of borrower #'.$BorrowerDetail['BorrowerNumber'].' as primary.';
            $auditBorrowerDetails = 'Set address record #ADD-' .$AddressTransactionNumber['Id']. ' as primary in address record tab.';
            $this->auditBorrowerDetails($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditBorrowerDetails, $BorrowerDetail['BorrowerId']);
            return 1;
        }
      }
      else if($input['tableType'] == 'BorrowerEmail')
      {
        $BorrowerDetail = $this->db->query("SELECT  BE.BorrowerEmailId 
                                                    , B.BorrowerId
                                                    , B.BorrowerNumber
                                                    FROM borrower_has_emails BE
                                                      INNER JOIN R_Borrowers B
                                                        ON B.BorrowerId = BE.BorrowerId
                                                      WHERE BE.BorrowerEmailId = ".$input['Id']."
        ")->row_array();
        $EmailTransaction = $this->db->query("SELECT LPAD(".$input['Id'].", 6, 0) as Id")->row_array();
        if($input['updateType'] == 1 || $input['updateType'] == 0) // deactivate and re-activate email of borrower
        {
          $count = $this->db->query("SELECT  COUNT(*) as ifUsed
                                                      FROM application_has_email
                                                            WHERE BorrowerEmailId = ".$input['Id']."
                                                            AND StatusId = 1
          ")->row_array();
          if($count['ifUsed'] == 0)
          {
          // update status
            $set = array( 
              'StatusId' => $input['updateType'],
              'UpdatedBy' => $EmployeeNumber,
              'DateUpdated' => $DateNow,
            );
            $condition = array( 
              'BorrowerEmailId' => $input['Id']
            );
            $table = 'borrower_has_emails';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // admin audits finalss
            if($input['updateType'] == 1)
            {
              $auditLogsManager = 'Re-activated email address record #ADD-' .$EmailTransaction['Id']. ' of borrower #'.$BorrowerDetail['BorrowerNumber'].'.';
              $auditBorrowerDetails = 'Re-activated email address record #ADD-' .$EmailTransaction['Id']. ' in email address record tab.';
            }
            else if($input['updateType'] == 0)
            {
              $auditLogsManager = 'Deactivated email address record #ADD-' .$EmailTransaction['Id']. ' of borrower #'.$BorrowerDetail['BorrowerNumber'].'.';
              $auditBorrowerDetails = 'Deactivated email address record #ADD-' .$EmailTransaction['Id']. ' in email address record tab.';
            }
            $this->auditBorrowerDetails($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditBorrowerDetails, $BorrowerDetail['BorrowerId']);
            return 1;
          }
          else
          {
            return 0;
          }
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
              'BorrowerId' => $BorrowerDetail['BorrowerId'],
              'IsPrimary' => 1
            );
            $table = 'borrower_has_emails';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // update status
            $set = array( 
              'IsPrimary' => 1,
              'UpdatedBy' => $EmployeeNumber,
              'DateUpdated' => $DateNow,
            );
            $condition = array( 
              'BorrowerEmailId' => $input['Id']
            );
            $table = 'Borrower_has_emails';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // admin audits finalss
            $auditLogsManager = 'Set email record #EA-' .$EmailTransaction['Id']. ' of borrower #'.$BorrowerDetail['BorrowerNumber'].' as primary.';
            $auditBorrowerDetails = 'Set email record #EA-' .$EmailTransaction['Id']. ' as primary in email record tab.';
            $this->auditBorrowerDetails($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditBorrowerDetails, $BorrowerDetail['BorrowerId']);
            return 1;
        }
      }
      else if($input['tableType'] == 'BorrowerContact')
      {
        $BorrowerDetail = $this->db->query("SELECT  BC.BorrowerContactId 
                                                  , C.Number
                                                  , B.BorrowerId
                                                  , B.BorrowerNumber
                                                  FROM Borrower_has_Contactnumbers BC
                                                    INNER JOIN R_ContactNumbers C
                                                      ON BC.ContactNumberId = C.ContactNumberId
                                                    INNER JOIN R_Borrowers B
                                                      ON B.BorrowerId = BC.BorrowerId
                                                    WHERE BC.BorrowerContactId = ".$input['Id']."
        ")->row_array();
        $TransactionNumber = $this->db->query("SELECT LPAD(".$input['Id'].", 6, 0) as Id")->row_array();
        if($input['updateType'] == 1 || $input['updateType'] == 0) // activate and deactivate Contact Number of Borrower
        {
          $count = $this->db->query("SELECT  COUNT(*) as ifUsed
                                                      FROM application_has_contact
                                                            WHERE BorrowerContactId = ".$input['Id']."
                                                            AND StatusId = 1
          ")->row_array();
          if($count['ifUsed'] == 0)
          {
            // update status
              $set = array(
                'StatusId' => $input['updateType'],
                'UpdatedBy' => $EmployeeNumber,
                'DateUpdated' => $DateNow,
              );
              $condition = array(
                'BorrowerContactId' => $input['Id']
              );
              $table = 'Borrower_has_contactnumbers';
              $this->maintenance_model->updateFunction1($set, $condition, $table);
            // admin audits finalss
              if($input['updateType'] == 1)
              {
                $auditLogsManager = 'Re-activated contact record #CN-'.$TransactionNumber['Id'].' of borrower #'.$BorrowerDetail['BorrowerNumber'].'.';
                $auditBorrowerDetails = 'Re-activated contact record #CN-'.$TransactionNumber['Id'].'  in contact record tab.';
              }
              else if($input['updateType'] == 0)
              {
                $auditLogsManager = 'Deactivated contact record #CN-'.$TransactionNumber['Id'].'  of borrower #'.$BorrowerDetail['BorrowerNumber'].'.';
                $auditBorrowerDetails = 'Deactivated contact record #CN-'.$TransactionNumber['Id'].'  in contact record tab.';
              }
              $this->auditBorrowerDetails($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditBorrowerDetails, $BorrowerDetail['BorrowerId']);
            return 1;
          }
          else
          {
            return 0;
          }
        }
        else // set as PRIMARY
        {
          // update primary to NOT PRIMARY status
            $set = array( 
              'IsPrimary' => 0,
              'UpdatedBy' => $EmployeeNumber,
              'DateUpdated' => $DateNow,
            );
            $condition = array( 
              'BorrowerId' => $BorrowerDetail['BorrowerId'],
              'IsPrimary' => 1
            );
            $table = 'Borrower_has_contactnumbers';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // update to PRIMARY status
            $set2 = array( 
              'IsPrimary' => 1,
              'UpdatedBy' => $EmployeeNumber,
              'DateUpdated' => $DateNow,
            );
            $condition2 = array( 
              'BorrowerContactId' => $input['Id']
            );
            $table2 = 'Borrower_has_contactnumbers';
            $this->maintenance_model->updateFunction1($set2, $condition2, $table2);
          // admin audits finalss
            $auditLogsManager = 'Set contact number #CN-' .$TransactionNumber['Id']. ' of borrower #'.$BorrowerDetail['BorrowerNumber'].' as primary.';
            $auditBorrowerDetails = 'Set contact number #CN-' .$TransactionNumber['Id']. ' as primary in contact record tab.';
            $this->auditBorrowerDetails($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditBorrowerDetails, $BorrowerDetail['BorrowerId']);
            return 1;
        }
      }
      else if($input['tableType'] == 'BorrowerDocuments')
      {
        $BorrowerDetail = $this->db->query("SELECT  BS.BorrowerIdentificationId 
                                                  , IC.IdentificationId
                                                  , B.BorrowerId
                                                  , B.BorrowerNumber
                                                  FROM borrower_has_supportdocuments BS
                                                    INNER JOIN R_IdentificationCards IC
                                                      ON BS.IdentificationId = IC.IdentificationId
                                                    INNER JOIN R_Borrowers B
                                                      ON B.BorrowerId = BS.BorrowerId
                                                    WHERE BS.BorrowerIdentificationId = ".$input['Id']."
        ")->row_array();
        $DocumentNumber = $this->db->query("SELECT LPAD(".$input['Id'].", 6, 0) as Id")->row_array();
        if($input['updateType'] == 1 || $input['updateType'] == 0) // activate and deactivate Contact Number of Borrower
        {
          // $count = $this->db->query("SELECT  COUNT(*) as ifUsed
          //                                             FROM application_has_documents
          //                                                   WHERE BorrowerIdentificationId = ".$input['Id']."
          //                                                   AND StatusId = 1
          // ")->row_array();
          // if($count['ifUsed'] == 0)
          // {
            // update status
              $set = array(
                'StatusId' => $input['updateType'],
                'UpdatedBy' => $EmployeeNumber,
                'DateUpdated' => $DateNow,
              );
              $condition = array(
                'BorrowerIdentificationId' => $input['Id']
              );
              $table = 'borrower_has_supportdocuments';
              $this->maintenance_model->updateFunction1($set, $condition, $table);
            // admin audits finalss
              if($input['updateType'] == 1)
              {
                $auditLogsManager = 'Re-activated supporting document #SD-'.$DocumentNumber['Id'].' of borrower #'.$BorrowerDetail['BorrowerNumber'].'.';
                $auditBorrowerDetails = 'Re-activated supporting document #SD-'.$DocumentNumber['Id'].'  in supporting document record tab.';
              }
              else if($input['updateType'] == 0)
              {
                $auditLogsManager = 'Deactivated supporting document #SD-'.$DocumentNumber['Id'].'  of borrower #'.$BorrowerDetail['BorrowerNumber'].'.';
                $auditBorrowerDetails = 'Deactivated supporting document #SD-'.$DocumentNumber['Id'].'  in supporting document  record tab.';
              }
              $this->auditBorrowerDetails($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditBorrowerDetails, $BorrowerDetail['BorrowerId']);
            return 1;
          // }
          // else
          // {
          //   return 0;
          // }
        }
      }
      else if($input['tableType'] == 'BorrowerSpouse')
      {
        $BorrowerDetail = $this->db->query("SELECT  BSP.BorrowerSpouseId 
                                                  , SP.SpouseId
                                                  , B.BorrowerId
                                                  , B.BorrowerNumber
                                                  FROM Borrower_has_spouse BSP
                                                    INNER JOIN R_Spouse SP
                                                      ON BSP.SpouseId = SP.SpouseId
                                                    INNER JOIN R_Borrowers B
                                                      ON B.BorrowerId = BSP.BorrowerId
                                                    WHERE BSP.SpouseId = ".$input['Id']."
        ")->row_array();
        $SpouseNumber = $this->db->query("SELECT LPAD(".$input['Id'].", 6, 0) as Id")->row_array();
        if($input['updateType'] == 1 || $input['updateType'] == 0) // activate and deactivate Contact Number of Borrower
        {
          $count = $this->db->query("SELECT  COUNT(*) as ifUsed
                                                      FROM application_has_spouse
                                                            WHERE BorrowerSpouseId = ".$input['Id']."
                                                            AND StatusId = 1
          ")->row_array();
          if($count['ifUsed'] == 0)
          {
            // admin audits finalss
              if($input['updateType'] == 1)
              {
                // update active
                  $set = array(
                    'StatusId' => 0,
                    'UpdatedBy' => $EmployeeNumber,
                    'DateUpdated' => $DateNow,
                  );
                  $condition = array(
                    'BorrowerId' => $BorrowerDetail['BorrowerId']
                    , 'StatusId' => 1
                  );
                  $table = 'Borrower_has_spouse';
                  $this->maintenance_model->updateFunction1($set, $condition, $table);
                // update status
                  $set = array(
                    'StatusId' => $input['updateType'],
                    'UpdatedBy' => $EmployeeNumber,
                    'DateUpdated' => $DateNow,
                  );
                  $condition = array(
                    'SpouseId' => $input['Id']
                  );
                  $table = 'Borrower_has_spouse';
                  $this->maintenance_model->updateFunction1($set, $condition, $table);
                $auditLogsManager = 'Re-activated spouse record #SP-'.$SpouseNumber['Id'].' of borrower #'.$BorrowerDetail['BorrowerNumber'].'.';
                $auditBorrowerDetails = 'Re-activated spouse record #SP-'.$SpouseNumber['Id'].' in spouse tab.';
              }
              else if($input['updateType'] == 0)
              {
                // update status
                  $set = array(
                    'StatusId' => $input['updateType'],
                    'UpdatedBy' => $EmployeeNumber,
                    'DateUpdated' => $DateNow,
                  );
                  $condition = array(
                    'SpouseId' => $input['Id']
                  );
                  $table = 'Borrower_has_spouse';
                  $this->maintenance_model->updateFunction1($set, $condition, $table);
                $auditLogsManager = 'Deactivated spouse record #SP-'.$SpouseNumber['Id'].' of borrower #'.$BorrowerDetail['BorrowerNumber'].' in spouse tab.';
                $auditBorrowerDetails = 'Deactivated spouse record #SP-'.$SpouseNumber['Id'].' in spouse tab.';
              }
              $this->auditBorrowerDetails($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditBorrowerDetails, $BorrowerDetail['BorrowerId']);
            return 1;
          }
          else
          {
            return 0;
          }
        }
      }
      else if($input['tableType'] == 'BorrowerEmployer')
      {
        $BorrowerDetail = $this->db->query("SELECT  BEMP.EmployerId
                                                  , B.BorrowerId
                                                  , B.BorrowerNumber
                                                  FROM borrower_has_employer BEMP
                                                    INNER JOIN R_Borrowers B
                                                      ON B.BorrowerId = BEMP.BorrowerId
                                                    WHERE BEMP.EmployerId = ".$input['Id']."
        ")->row_array();
        $EmployerNumber = $this->db->query("SELECT LPAD(".$input['Id'].", 6, 0) as Id")->row_array();
        if($input['updateType'] == 1 || $input['updateType'] == 0) // activate and deactivate Employer of Borrower
        {
          $count = $this->db->query("SELECT  COUNT(*) as ifUsed
                                                      FROM application_has_employer
                                                            WHERE EmployerId = ".$input['Id']."
                                                            AND StatusId = 1
          ")->row_array();
          if($count['ifUsed'] == 0)
          {
            // update status
              $set = array(
                'StatusId' => $input['updateType'],
                'UpdatedBy' => $EmployeeNumber,
                'DateUpdated' => $DateNow,
              );
              $condition = array(
                'EmployerId' => $input['Id']
              );
              $table = 'borrower_has_employer';
              $this->maintenance_model->updateFunction1($set, $condition, $table);
            // admin audits finalss
              if($input['updateType'] == 1)
              {
                $auditLogsManager = 'Re-activated employment record #EP-'.$EmployerNumber['Id'].' of borrower #'.$BorrowerDetail['BorrowerNumber'].'.';
                $auditBorrowerDetails = 'Re-activated employment record #EP-'.$EmployerNumber['Id'].' in employment record tab.';
              }
              else if($input['updateType'] == 0)
              {
                $auditLogsManager = 'Deactivated employment record #EP-'.$EmployerNumber['Id'].' of borrower #'.$BorrowerDetail['BorrowerNumber'].'.';
                $auditBorrowerDetails = 'Deactivated employment record #EP-'.$EmployerNumber['Id'].' in employment record tab.';
              }
              $this->auditBorrowerDetails($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditBorrowerDetails, $BorrowerDetail['BorrowerId']);

            return 1;
          }
          else
          {
            return 0;
          }
        }
      }
      else if($input['tableType'] == 'BorrowerCoMaker')
      {
        $BorrowerDetail = $this->db->query("SELECT  BCM.BorrowerComakerId 
                                                  , B.BorrowerId
                                                  , B.BorrowerNumber
                                                  FROM borrower_has_comaker BCM
                                                    INNER JOIN R_Borrowers B
                                                      ON B.BorrowerId = BCM.BorrowerId
                                                    WHERE BCM.BorrowerComakerId = ".$input['Id']."
        ")->row_array();
        $ComakerNumber = $this->db->query("SELECT LPAD(".$input['Id'].", 6, 0) as Id")->row_array();
        if($input['updateType'] == 1 || $input['updateType'] == 0) // activate and deactivate Contact Number of Borrower
        {
          $count = $this->db->query("SELECT  COUNT(*) as ifUsed
                                                      FROM application_has_comaker
                                                            WHERE BorrowerCoMakerId = ".$input['Id']."
                                                            AND StatusId = 1
          ")->row_array();
          if($count['ifUsed'] == 0)
          {
            // update status
              $set = array(
                'StatusId' => $input['updateType'],
                'UpdatedBy' => $EmployeeNumber,
                'DateUpdated' => $DateNow,
              );
              $condition = array(
                'BorrowerComakerId' => $input['Id']
              );
              $table = 'borrower_has_comaker';
              $this->maintenance_model->updateFunction1($set, $condition, $table);
            // admin audits finalss
              if($input['updateType'] == 1)
              {
                $auditLogsManager = 'Re-activated co-maker record #CM-'.$ComakerNumber['Id'].' of borrower #'.$BorrowerDetail['BorrowerNumber'].'.';
                $auditBorrowerDetails = 'Re-activated co-maker record #CM-'.$ComakerNumber['Id'].' in co-maker tab.';
              }
              else if($input['updateType'] == 0)
              {
                $auditLogsManager = 'Deactivated co-maker record #CM-'.$ComakerNumber['Id'].' of borrower #'.$BorrowerDetail['BorrowerNumber'].'.';
                $auditBorrowerDetails = 'Deactivated co-maker record #CM-'.$ComakerNumber['Id'].' in co-maker tab.';
              }
              $this->auditBorrowerDetails($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditBorrowerDetails, $BorrowerDetail['BorrowerId']);
            return 1;
          }
          else
          {
            return 0;
          }
        }
      }
      else if($input['tableType'] == 'BorrowerPersonal')
      {
        $count = $this->db->query("SELECT  COUNT(*) as ifUsed
                                                    FROM application_has_personalReference
                                                          WHERE ReferenceId = ".$input['Id']."
                                                          AND StatusId = 1
        ")->row_array();
        if($count['ifUsed'] == 0)
        {
          $BorrowerDetail = $this->db->query("SELECT  BRF.ReferenceId
                                                    , B.BorrowerId
                                                    , B.BorrowerNumber
                                                    FROM borrower_has_reference BRF
                                                      INNER JOIN R_Borrowers B
                                                        ON B.BorrowerId = BRF.BorrowerId
                                                      WHERE BRF.ReferenceId = ".$input['Id']."
          ")->row_array();
          $PersonalNumber = $this->db->query("SELECT LPAD(".$input['Id'].", 6, 0) as Id")->row_array();
          if($input['updateType'] == 1 || $input['updateType'] == 0) // activate and deactivate Contact Number of Borrower
          {
            // update status
              $set = array(
                'StatusId' => $input['updateType'],
                'UpdatedBy' => $EmployeeNumber,
                'DateUpdated' => $DateNow,
              );
              $condition = array(
                'ReferenceId' => $input['Id']
              );
              $table = 'borrower_has_reference';
              $this->maintenance_model->updateFunction1($set, $condition, $table);
            // admin audits finalss
              if($input['updateType'] == 1)
              {
                $auditLogsManager = 'Re-activated personal reference record #RF-'.$PersonalNumber['Id'].' of borrower #'.$BorrowerDetail['BorrowerNumber'].'.';
                $auditBorrowerDetails = 'Re-activated personal reference record #RF-'.$PersonalNumber['Id'].' in personal reference tab.';
              }
              else if($input['updateType'] == 0)
              {
                $auditLogsManager = 'Deactivated personal reference record #RF-'.$PersonalNumber['Id'].' of borrower #'.$BorrowerDetail['BorrowerNumber'].'.';
                $auditBorrowerDetails = 'Deactivated personal reference record #RF-'.$PersonalNumber['Id'].' in personal reference tab.';
              }
              $this->auditBorrowerDetails($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditBorrowerDetails, $BorrowerDetail['BorrowerId']);

              return 1;
          }

          return 1;
        }
        else
        {
          return 0;
        }
      }
      else if($input['tableType'] == 'BorrowerEducation')
      {
        $BorrowerDetail = $this->db->query("SELECT  BEDU.BorrowerEducationId
                                                  , B.BorrowerId
                                                  , B.BorrowerNumber
                                                  FROM borrower_has_Education BEDU
                                                    INNER JOIN R_Borrowers B
                                                      ON B.BorrowerId = BEDU.BorrowerId
                                                    WHERE BEDU.BorrowerEducationId = ".$input['Id']."
        ")->row_array();
        $EducationNumber = $this->db->query("SELECT LPAD(".$input['Id'].", 6, 0) as Id")->row_array();
        if($input['updateType'] == 1 || $input['updateType'] == 0) // activate and deactivate Contact Number of Borrower
        {
          $count = $this->db->query("SELECT  COUNT(*) as ifUsed
                                                      FROM application_has_education
                                                            WHERE BorrowerEducationId = ".$input['Id']."
                                                            AND StatusId = 1
          ")->row_array();
          if($count['ifUsed'] == 0)
          {
            // update status
              $set = array(
                'StatusId' => $input['updateType'],
                'UpdatedBy' => $EmployeeNumber,
                'DateUpdated' => $DateNow,
              );
              $condition = array(
                'BorrowerEducationId' => $input['Id']
              );
              $table = 'borrower_has_Education';
              $this->maintenance_model->updateFunction1($set, $condition, $table);
            // admin audits finalss
              if($input['updateType'] == 1)
              {
                $auditLogsManager = 'Re-activated educational background #ED-'.$EducationNumber['Id'].' of borrower #'.$BorrowerDetail['BorrowerNumber'].'.';
                $auditBorrowerDetails = 'Re-activated educational background #ED-'.$EducationNumber['Id'].'  in educational background record tab.';
              }
              else if($input['updateType'] == 0)
              {
                $auditLogsManager = 'Deactivated educational background #ED-'.$EducationNumber['Id'].'  of borrower #'.$BorrowerDetail['BorrowerNumber'].'.';
                $auditBorrowerDetails = 'Deactivated educational background #ED-'.$EducationNumber['Id'].'  in educational background record tab.';
              }
              $this->auditBorrowerDetails($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditBorrowerDetails, $BorrowerDetail['BorrowerId']);
            return 1;
          }
          else
          {
            return 0;
          }
        }
      }
      else if($input['tableType'] == 'BorrowerUpdate')
      {
        $BorrowerDetail = $this->db->query("SELECT  B.BorrowerNumber
                                                    FROM R_Borrowers B
                                                    WHERE B.BorrowerId = ".$input['Id']."
        ")->row_array();
        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'BorrowerId' => $input['Id']
          );
          $table = 'R_Borrowers';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated borrower #'.$BorrowerDetail['BorrowerNumber'].'.';
            $EmployeeNotification = 'Re-activated borrower.';
          }
          else if($input['updateType'] == 2)
          {
            $Description = 'Deactivated borrower #'.$BorrowerDetail['BorrowerNumber'].'.';
            $EmployeeNotification = 'Deactivated borrower.';
          }
          $ManagerBranchId = $this->employee_model->getEmployeeDetails($input['Id']);
          $insertEmpLog = array(
            'Description'       => $EmployeeNotification
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

    function auditBorrower($borrowerDesc, $staffDesc, $BorrowerId)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      $employeeDetail = $this->maintenance_model->getCreatorDetails();
      // insert into borrower notification
        $insertEmployeeAudit = array(
          'Description' => $borrowerDesc,
          'CreatedBy'   => $EmployeeNumber,
          'BorrowerId'  => $BorrowerId
        );
        $auditEmployeeTable = 'borrower_has_notifications';
        $this->maintenance_model->insertFunction($insertEmployeeAudit, $auditEmployeeTable);
      // insert into manager and employee notification
        $insertManagerAudit = array(
          'Description'       => $staffDesc,
          'CreatedBy'         => $EmployeeNumber,
          'ManagerBranchId'   => $employeeDetail['ManagerBranchId']
        );
        $auditManagerTable = 'manager_has_notifications';
        $this->maintenance_model->insertFunction($insertManagerAudit, $auditManagerTable);

        $insertAudit = array(
          'Description'       => $staffDesc,
          'CreatedBy'         => $EmployeeNumber,
          'EmployeeNumber'    => $EmployeeNumber
        );
        $auditTable = 'employee_has_notifications';
        $this->maintenance_model->insertFunction($insertAudit, $auditTable);
    }

    function auditBorrowerDetails($auditLogsManager, $auditAffectedEmployee, $ManagerId, $AffectedEmployeeNumber, $auditBorrowerDets ,$borrowerId)
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
        'Description'       => $auditBorrowerDets
        , 'BorrowerId'      => $borrowerId
        , 'CreatedBy'       => $CreatedBy
      );
      $auditLoanApplicationTable = 'borrower_has_notifications';
      $this->maintenance_model->insertFunction($insertApplicationLog, $auditLoanApplicationTable);
    }

    function getProvinceAddress($Id)
    {
      $query_string = $this->db->query("SELECT  MAX(A.AddressId) as AddressId
                                                , BAH.AddressType
                                                , BAH.YearsStayed
                                                , BAH.MonthsStayed
                                                , BAH.NameOfLandlord
                                                FROM R_Borrowers B
                                                  INNER JOIN borrowerAddressHistory BAH
                                                    ON B.BorrowerId = BAH.BorrowerId
                                                  INNER JOIN R_Address A
                                                    ON A.AddressId = BAH.AddressId
                                                  WHERE B.BorrowerId = $Id
                                                  AND A.AddressType = 'Province Address'
                                                  AND BAH.StatusId = 1

      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getCityAddress($Id)
    {
      $query_string = $this->db->query("SELECT  MAX(A.AddressId) as AddressId
                                                , BAH.AddressType
                                                , BAH.YearsStayed
                                                , BAH.MonthsStayed
                                                , BAH.NameOfLandlord
                                                , BAH.IsPrimary
                                                FROM R_Borrowers B
                                                  INNER JOIN borrowerAddressHistory BAH
                                                    ON B.BorrowerId = BAH.BorrowerId
                                                  INNER JOIN R_Address A
                                                    ON A.AddressId = BAH.AddressId
                                                  WHERE B.BorrowerId = $Id
                                                  AND A.AddressType = 'City Address'
                                                  AND BAH.StatusId = 1

      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getSpouseDetails($Id)
    {
      $query_string = $this->db->query("SELECT DISTINCT B.SpouseId
                                                , S.name as Salutation
                                                , B.FirstName
                                                , CONCAT(LastName, ', ', FirstName, ' ', MiddleName, ', ', ExtName) as Name
                                                , acronym (B.MiddleName) as MiddleInitial
                                                , B.LastName
                                                , B.ExtName
                                                , B.Dependents
                                                , SX.Name as Sex
                                                , N.Description as Nationality
                                                , C.name as CivilStatus
                                                , DATE_FORMAT(B.DateOfBirth, '%d %b %Y') as DateOfBirth
                                                , DATE_FORMAT(B.DateCreated, '%d %b %Y') as DateAdded
                                                , DATE_FORMAT(B.DateOfBirth, '%Y-%b-%d') as RawDateOfBirth
                                                , SS.Name as StatusDescription
                                                , B.StatusId

                                                , B.MiddleName
                                                , S.SalutationId
                                                , SX.SexId
                                                , N.NationalityId
                                                , N.Description as NationalityName
                                                , C.CivilStatusId
                                                FROM R_Spouse B
                                                  INNER JOIN R_Salutation S
                                                    ON S.SalutationId = B.Salutation
                                                  INNER JOIN R_Sex SX
                                                    ON SX.SexId = B.Sex
                                                  INNER JOIN r_nationality N
                                                    ON N.NationalityId = B.Nationality
                                                  INNER JOIN r_civilstatus C
                                                    ON C.CivilStatusId = B.CivilStatus
                                                  INNER JOIN R_BorrowerStatus SS
                                                    ON SS.BorrowerStatusId = B.StatusId
                                                  WHERE B.SpouseId = $Id

      ");

      $data = $query_string->row_array();
      return $data;
    }

    function getSpouseDetails2($Id)
    {
      $query_string = $this->db->query("SELECT DISTINCT CONCAT('SR-', LPAD(BHS.BorrowerSpouseId, 6, 0)) as RefNo
                                                , CONCAT(LastName, ', ', FirstName, ' ', MiddleName, ', ', ExtName) as Name
                                                FROM R_Spouse S
                                                  INNER JOIN Borrower_has_spouse BHS
                                                    ON BHS.SpouseId = S.SpouseId
                                                  WHERE BHS.BorrowerSpouseId = $Id

      ");

      $data = $query_string->row_array();
      return $data;
    }

    function getSpouseEmployer($Id)
    {
      $query_string = $this->db->query("SELECT DISTINCT SP.SpouseId
                                                , BEMP.EmployerName as Name
                                                , BEMP.SpousePosition
                                                , BEMP.TenureYear
                                                , BEMP.TenureMonth
                                                , BEMP.BusinessAddress
                                                , SP.EmailAddress
                                                , BEMP.TelephoneNumber
                                                , BEMP.ContactNumber
                                                , BEMP.BusinessAddress
                                                FROM R_Spouse SP
                                                  INNER JOIN Borrower_has_Employer BEMP
                                                    ON BEMP.SpouseId = SP.SpouseId
                                                  WHERE BEMP.SpouseId = $Id

      ");

      $data = $query_string->row_array();
      return $data;
    }


    function getSpouseCityAddress($Id)
    {
      $query_string = $this->db->query("SELECT DISTINCT  MAX(EA.BorrowerAddressHistoryId) as BorrowerAddressHistoryId
                                                , IsPrimary
                                                , A.AddressType
                                                , UPPER(A.HouseNo) as HouseNo
                                                , UPPER(B.brgyDesc) as brgyDesc
                                                , UPPER(P.provDesc) as provDesc
                                                , UPPER(C.cityMunDesc) as cityMunDesc
                                                , UPPER(R.regDesc) as regDesc
                                                , EA.YearsStayed
                                                , EA.MonthsStayed
                                                , EA.NameOfLandlord
                                                , EA.StatusId
                                                , EA.BorrowerId
                                                , DATE_FORMAT(EA.DateCreated, '%d %b %Y %r') as DateCreated
                                                , CONCAT('ADD-', LPAD(EA.BorrowerAddressHistoryId, 6, 0)) as rowNumber
                                                , A.ContactNumber
                                                , A.Telephone
                                                FROM borrowerAddressHistory EA
                                                  INNER JOIN r_address A
                                                    ON A.AddressId = EA.AddressId
                                                  INNER JOIN add_barangay B
                                                    ON B.brgyCode = A.BarangayId
                                                  INNER JOIN add_province P
                                                    ON P.provCode = B.provCode
                                                  INNER JOIN add_city C
                                                    ON C.citymunCode = B.citymunCode
                                                  INNER JOIN add_region R 
                                                    ON R.regCode = B.regCode
                                                      WHERE EA.SpouseId = ".$Id."
                                                      AND A.AddressType = 'City Address'
      ");
      $data = $query_string->row_array();
      return $data;
    }


    function getSpouseProvAddress($Id)
    {
      $query_string = $this->db->query("SELECT DISTINCT  MAX(EA.BorrowerAddressHistoryId) as BorrowerAddressHistoryId
                                                , IsPrimary
                                                , A.AddressType
                                                , UPPER(A.HouseNo) as HouseNo
                                                , UPPER(B.brgyDesc) as brgyDesc
                                                , UPPER(P.provDesc) as provDesc
                                                , UPPER(C.cityMunDesc) as cityMunDesc
                                                , UPPER(R.regDesc) as regDesc
                                                , EA.YearsStayed
                                                , EA.MonthsStayed
                                                , EA.NameOfLandlord
                                                , EA.StatusId
                                                , EA.BorrowerId
                                                , DATE_FORMAT(EA.DateCreated, '%d %b %Y %r') as DateCreated
                                                , CONCAT('ADD-', LPAD(EA.BorrowerAddressHistoryId, 6, 0)) as rowNumber
                                                , A.ContactNumber
                                                , A.Telephone
                                                FROM borrowerAddressHistory EA
                                                  INNER JOIN r_address A
                                                    ON A.AddressId = EA.AddressId
                                                  INNER JOIN add_barangay B
                                                    ON B.brgyCode = A.BarangayId
                                                  INNER JOIN add_province P
                                                    ON P.provCode = B.provCode
                                                  INNER JOIN add_city C
                                                    ON C.citymunCode = B.citymunCode
                                                  INNER JOIN add_region R 
                                                    ON R.regCode = B.regCode
                                                      WHERE EA.SpouseId = ".$Id."
                                                      AND A.AddressType = 'Province Address'
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getSpouseEmail($Id)
    {
      $query_string = $this->db->query("SELECT DISTINCT SP.SpouseId
                                                , POS.Position
                                                , CONCAT(LastName, ', ', FirstName, ' ', MiddleName, ', ', ExtName) as Name
                                                , acronym (B.MiddleName) as MiddleInitial
                                                , B.LastName
                                                , B.ExtName
                                                , BEMP.TenureYear
                                                , BEMP.TenureMonth
                                                , BEMP.BusinessAddress
                                                , B.Dependents
                                                , SX.Name as Sex
                                                , N.Description as Nationality
                                                , C.name as CivilStatus
                                                , DATE_FORMAT(B.DateOfBirth, '%d %b %Y') as DateOfBirth
                                                , DATE_FORMAT(B.DateCreated, '%d %b %Y') as DateAdded
                                                , DATE_FORMAT(B.DateOfBirth, '%Y-%b-%d') as RawDateOfBirth
                                                , SS.Name as StatusDescription
                                                , B.StatusId

                                                , B.MiddleName
                                                , S.SalutationId
                                                , SX.SexId
                                                , N.NationalityId
                                                , N.Description as NationalityName
                                                , C.CivilStatusId
                                                FROM R_Spouse SP
                                                  INNER JOIN Borrower_has_Employer BEMP
                                                    ON BEMP.SpouseId = SP.SpouseId
                                                  INNER JOIN R_Position POS
                                                    ON POS.PositionId = BEMP.PositionId
                                                  WHERE SP.SpouseId = $Id

      ");

      $data = $query_string->row_array();
      return $data;
    }

    function getComakerDetails($Id)
    {
      $query_string = $this->db->query("SELECT DISTINCT BCM.BorrowerComakerId
                                                , BCM.Name 
                                                , BCM.Employer
                                                , BCM.TenureMonth
                                                , BCM.TenureYear
                                                , BCM.MonthlyIncome
                                                , BCM.BusinessAddress
                                                , BCM.BusinessNo
                                                , BCM.TelephoneNo
                                                , POS.name as PositionName
                                                , BCM.MobileNo
                                                , BCM.StatusId
                                                , DATE_FORMAT(BCM.Birthdate, '%d %b %Y') as Birthdate
                                                , CONCAT('CM-', LPAD(BCM.BorrowerComakerId, 6, 0)) as RefNo
                                                FROM Borrower_has_Comaker BCM
                                                  INNER JOIN r_occupation POS
                                                    ON BCM.PositionId = POS.OccupationId
                                                  WHERE BCM.BorrowerComakerId = $Id

      ");

      $data = $query_string->row_array();
      return $data;
    }

    function getPersonalDetails($Id)
    {
      $query_string = $this->db->query("SELECT DISTINCT BRF.ReferenceId
                                                , BRF.Name 
                                                , BRF.Address
                                                , BRF.ContactNumber
                                                , B.BorrowerId
                                                , CONCAT('RF-', LPAD(BRF.ReferenceId, 6, 0)) as RefNo
                                                FROM Borrower_has_reference BRF
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = BRF.BorrowerId
                                                  WHERE BRF.ReferenceId = $Id

      ");

      $data = $query_string->row_array();
      return $data;
    }

    function getSpouseList($Id)
    {
      $query_string = $this->db->query("SELECT  CONCAT(S.LastName, ', ', S.FirstName, ' ', S.MiddleName, ', ', S.ExtName) as Name
                                                , CONCAT('SR-', LPAD(S.SpouseId, 6, 0)) as rowNumber
                                                , DATE_FORMAT(B.DateOfBirth, '%d %b %Y') as DateOfBirth
                                                , DATE_FORMAT(B.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , BHS.StatusId
                                                , SX.Name as Sex
                                                , N.Description as Nationality
                                                , C.name as CivilStatus
                                                , S.SpouseId
                                                , (SELECT COUNT(*) 
                                                          FROM R_Borrowers
                                                          WHERE FirstName = S.FirstName 
                                                          AND MiddleName = S.MiddleName
                                                          AND LastName = S.LastName
                                                          AND DateOfBirth = S.DateOfBirth
                                                          AND BranchId = S.BranchId
                                                ) as IsBorrower
                                                FROM Borrower_has_spouse BHS
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = BHS.BorrowerId
                                                  INNER JOIN R_Spouse S
                                                    ON S.SpouseId = BHS.SpouseId
                                                  INNER JOIN R_Sex SX
                                                    ON SX.SexId = S.Sex
                                                  INNER JOIN r_nationality N
                                                    ON N.NationalityId = S.Nationality
                                                  INNER JOIN r_civilstatus C
                                                    ON C.CivilStatusId = S.CivilStatus
                                                  INNER JOIN R_BorrowerStatus SS
                                                    ON SS.BorrowerStatusId = S.StatusId
                                                      WHERE B.BorrowerId = $Id

      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getEmploymentList($Id)
    {
      $query_string = $this->db->query("SELECT  CONCAT('ER-', LPAD(BHE.EmployerId, 6, 0)) as rowNumber
                                                , EmployerName
                                                , BHP.Name as Position
                                                , I.Name as Industry
                                                , CASE
                                                    WHEN EmployerStatus = 1
                                                    THEN 'Present Employer'
                                                    ELSE 'Previous Employer'
                                                  END as EmployerStatus
                                                , DATE_FORMAT(BHE.DateHired, '%d %b %Y') as DateHired
                                                , DATE_FORMAT(BHE.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , TenureYear
                                                , TenureMonth
                                                , BusinessAddress
                                                , TelephoneNumber
                                                , EmployerId
                                                , BHE.StatusId
                                                FROM borrower_has_employer BHE
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = BHE.BorrowerId
                                                  LEFT JOIN Borrower_Has_Position BHP
                                                    ON BHP.BorrowerPositionId = BHE.PositionId
                                                  LEFT JOIN R_Industry I
                                                    ON I.IndustryId = BHE.IndustryId
                                                      WHERE B.BorrowerId = $Id
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getEducationList($Id)
    {
      $query_string = $this->db->query("SELECT  CONCAT('ED-', LPAD(BEDU.BorrowerEducationId, 6, 0)) as rowNumber
                                                , BEDU.BorrowerEducationId
                                                , DATE_FORMAT(BEDU.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , BEDU.DateCreated as rawDateCreated
                                                , BEDU.Level
                                                , BEDU.SchoolName
                                                , ED.Name
                                                , YearGraduated
                                                , BEDU.StatusId
                                                FROM Borrower_has_Education BEDU
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = BEDU.BorrowerId
                                                  INNER JOIN R_Education ED
                                                    ON ED.EducationId = BEDU.EducationId
                                                      WHERE B.BorrowerId = $Id
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getEducationDetails($Id)
    {
      $query_string = $this->db->query("SELECT  CONCAT('ED-', LPAD(BEDU.BorrowerEducationId, 6, 0)) as RefNo
                                                , BEDU.BorrowerEducationId
                                                , DATE_FORMAT(BEDU.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , BEDU.DateCreated as rawDateCreated
                                                , BEDU.Level
                                                , BEDU.SchoolName
                                                , ED.Name
                                                , YearGraduated
                                                , BEDU.StatusId
                                                FROM Borrower_has_Education BEDU
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = BEDU.BorrowerId
                                                  INNER JOIN R_Education ED
                                                    ON ED.EducationId = BEDU.EducationId
                                                      WHERE BEDU.BorrowerEducationId = $Id
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getAllList()
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT DISTINCT B.FirstName
                                                , acronym (B.MiddleName) as MI
                                                , B.LastName
                                                , B.ExtName
                                                , CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', EMP.MiddleName, ', ', EMP.ExtName) as CreatedBy
                                                , B.StatusId
                                                , BS.Name as StatusDescription
                                                , BS.statusColor
                                                , B.Dependents
                                                , DATE_FORMAT(B.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , DATE_FORMAT(B.DateUpdated, '%d %b %Y %h:%i %p') as DateUpdated
                                                , B.BorrowerId
                                                , CONCAT(B.LastName, ', ', B.FirstName) as Name 
                                                FROM r_Borrowers B
                                                  INNER JOIN r_BorrowerStatus BS
                                                      ON BS.BorrowerStatusId = B.StatusId
                                                    INNER JOIN r_employee EMP
                                                      ON EMP.EmployeeNumber = B.CreatedBy
                                                    LEFT JOIN R_UserRole R
                                                      ON R.EmployeeNumber = EMP.EmployeeNumber
                                                    LEFT JOIN Branch_Has_Employee BHE
                                                      ON BHE.EmployeeNumber = EMP.EmployeeNumber
                                                      WHERE EMP.StatusId = 2
                                                      AND BHE.BranchId = $AssignedBranchId
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function filterBorrower($StatusID)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT DISTINCT B.FirstName
                                                , acronym (B.MiddleName) as MI
                                                , B.LastName
                                                , B.ExtName
                                                , CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', EMP.MiddleName, ', ', EMP.ExtName) as CreatedBy
                                                , B.StatusId
                                                , BS.Name as StatusDescription
                                                , BS.statusColor
                                                , B.Dependents
                                                , DATE_FORMAT(B.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , DATE_FORMAT(B.DateUpdated, '%d %b %Y %h:%i %p') as DateUpdated
                                                , B.BorrowerId
                                                , CONCAT(B.LastName, ', ', B.FirstName) as Name 
                                                FROM r_Borrowers B
                                                  INNER JOIN r_BorrowerStatus BS
                                                      ON BS.BorrowerStatusId = B.StatusId
                                                    INNER JOIN r_employee EMP
                                                      ON EMP.EmployeeNumber = B.CreatedBy
                                                    LEFT JOIN R_UserRole R
                                                      ON R.EmployeeNumber = EMP.EmployeeNumber
                                                    LEFT JOIN Branch_Has_Employee BHE
                                                      ON BHE.EmployeeNumber = EMP.EmployeeNumber
                                                      WHERE EMP.StatusId = 2
                                                      AND BHE.BranchId = $AssignedBranchId
                                                      AND B.StatusId = $StatusID
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getTotalLoans($BorrowerId)
    {
      $query = $this->db->query("SELECT   COUNT(A.ApplicationId) as Record
                                          FROM t_application A
                                                WHERE 
                                                (
                                                  A.StatusId = 1
                                                  OR
                                                  A.StatusId = 4
                                                )
                                                AND A.BorrowerId = $BorrowerId
      ");
      $data = $query->row_array();
      return $data['Record'];
    }

    function getReference($BorrowerId)
    {
      $query_string = $this->db->query("SELECT  Name
                                                , CONCAT('RF-', LPAD(BN.ReferenceId, 6, 0)) as rowNumber
                                                , Address
                                                , ContactNumber
                                                , DATE_FORMAT(BN.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , CONCAT(EMP.LastName, ', ', EMP.FirstName) as CreatedBy 
                                                , BN.StatusId
                                                , BorrowerId
                                                , ReferenceId
                                                FROM Borrower_has_reference BN
                                                  INNER JOIN r_employee EMP
                                                    ON EMP.EmployeeNumber = BN.CreatedBy
                                                      WHERE BorrowerId = $BorrowerId
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getComaker($BorrowerId)
    {
      $query_string = $this->db->query("SELECT  Name
                                                , Employer
                                                , CONCAT('CM-', LPAD(BC.BorrowerComakerId, 6, 0)) as rowNumber
                                                , TelephoneNo
                                                , MobileNo
                                                , BC.StatusId
                                                , DATE_FORMAT(BC.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , CONCAT(EMP.LastName, ', ', EMP.FirstName) as CreatedBy 
                                                , BorrowerId
                                                , BorrowerComakerId
                                                FROM borrower_has_comaker BC
                                                  INNER JOIN r_employee EMP
                                                    ON EMP.EmployeeNumber = BC.CreatedBy
                                                      WHERE BorrowerId = $BorrowerId
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAudit($BorrowerId)
    {
      $query_string = $this->db->query("SELECT  BN.BorrowerLogId
                                                , BN.Description
                                                , CONCAT(EMP.LastName, ', ', EMP.FirstName) as CreatedBy 
                                                , DATE_FORMAT(BN.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , BN.DateCreated as rawDateCreated
                                                FROM Borrower_has_notifications BN
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = BN.BorrowerId
                                                  INNER JOIN r_employee EMP
                                                    ON EMP.EmployeeNumber = BN.CreatedBy
                                                  WHERE BN.BorrowerId = $BorrowerId
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getSupportingDocuments($BorrowerId)
    {
$query_string = $this->db->query("SELECT  CONCAT('SD-', LPAD(BS.BorrowerIdentificationId, 6, 0)) as rowNumber
                                        , R.Name
                                        , I.Attachment
                                        , I.IdentificationId
                                        , I.Description
                                        , I.IdNumber
                                        , BS.StatusId
                                        , BS.BorrowerIdentificationId
                                        , DATE_FORMAT(BS.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                        , DATE_FORMAT(BS.DateUpdated, '%d %b %Y %h:%i %p') as DateUpdated
                                        , EMP.FirstName
                                        , EMP.LastName
                                        , acronym(EMP.MiddleName) as MiddleInitial
                                        FROM borrower_has_supportdocuments BS
                                        INNER JOIN r_identificationcards I
                                          ON I.IdentificationId = BS.IdentificationId
                                        INNER JOIN r_requirements R
                                          ON R.RequirementId = BS.RequirementId
                                        INNER JOIN r_employee EMP
                                            ON EMP.EmployeeNumber = BS.CreatedBy
                                            WHERE BS.BorrowerId = $BorrowerId
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAttachment($BorrowerIdentificationId)
    {
      $query_string = $this->db->query("SELECT  IC.Attachment
                                                , IC.FileName
                                                FROM r_identificationcards IC
                                                      INNER JOIN borrower_has_supportdocuments EI
                                                          ON EI.IdentificationId = IC.IdentificationId
                                                              WHERE EI.BorrowerIdentificationId = $BorrowerIdentificationId

      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getCollectionsMade($Id)
    {
      $query = $this->db->query("SELECT   Amount
                                          , A.TransactionNumber
                                          , CONCAT('PYM-', LPAD(PM.PaymentMadeId, 6, 0)) as ReferenceNo
                                          , DATE_FORMAT(PM.DateCreated, '%d %b, %Y %h:%i %p') as DateCreated
                                          , PM.DateCreated as rawDateCreated
                                          , DATE_FORMAT(PM.DateCollected, '%b %d, %Y') as DateCollected
                                          , DATE_FORMAT(PM.PaymentDate, '%b %d, %Y') as PaymentDate
                                          , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName) as CreatedBy
                                          , PM.StatusId
                                          , BNK.BankName
                                          , PM.PaymentMadeId
                                          , A.ApplicationId
                                          FROM t_application A
                                            INNER JOIN t_paymentsmade PM
                                              ON A.ApplicationId = PM.ApplicationId
                                            INNER JOIN r_borrowers B
                                              ON B.BorrowerId = A.BorrowerId
                                            INNER JOIN R_Bank BNK
                                              ON BNK.BankId = PM.BankId
                                            INNER JOIN R_Employee EMP
                                              ON EMP.EmployeeNumber = PM.CreatedBy
                                                WHERE A.BorrowerId = $Id
                                                AND PM.StatusId = 1
      ");
      $data = $query->result_array();
      return $data;
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
                                            FROM borrower_has_emails
                                              WHERE EmailId = ".$data['EmailId']."
                                              AND BorrowerId = '".$input['BorrowerId']."'
        ");
        $data2 = $query2->num_rows();
        return $data2;
      }
      else
      {
        return 0;
      }
    }

    function getPersonalReferences($Id)
    {
      $query_string = $this->db->query("SELECT DISTINCT BRF.ReferenceId
                                                FROM Borrower_has_reference BRF
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = BRF.BorrowerId
                                                  WHERE BRF.BorrowerId = $Id
                                                  AND BRF.StatusId = 1

      ");

      if($query_string->num_rows() > 0)
      {
        $data = $query_string->result_array();
        return $data;
      }
      else
      {
        return 0;
      }
    }

    function getActiveCoMaker($BorrowerId)
    {
      $query_string = $this->db->query("SELECT  DISTINCT BorrowerComakerId
                                                FROM borrower_has_comaker BC
                                                  WHERE BorrowerId = $BorrowerId
                                                  AND BC.StatusId = 1
      ");

      if($query_string->num_rows() > 0)
      {
        $data = $query_string->result_array();
        return $data;
      }
      else
      {
        return 0;
      }
    }

    function getActiveSpouse($Id)
    {
      $query_string = $this->db->query("SELECT  DISTINCT BorrowerSpouseId
                                                FROM Borrower_has_spouse
                                                      WHERE BorrowerId = $Id
                                                      AND StatusId = 1

      ");

      if($query_string->num_rows() > 0)
      {
        $data = $query_string->result_array();
        return $data;
      }
      else
      {
        return 0;
      }
    }

    function getActiveEmployment($Id)
    {
      $query_string = $this->db->query("SELECT  DISTINCT EmployerId
                                                FROM borrower_has_employer                                                  
                                                  WHERE BorrowerId = $Id
                                                  AND StatusId = 1
      ");

      if($query_string->num_rows() > 0)
      {
        $data = $query_string->result_array();
        return $data;
      }
      else
      {
        return 0;
      }
    }

    function getActiveContact($Id)
    {
      $query_string = $this->db->query("SELECT  DISTINCT BorrowerContactId
                                                FROM borrower_has_contactnumbers                                                  
                                                  WHERE BorrowerId = $Id
                                                  AND StatusId = 1
      ");

      if($query_string->num_rows() > 0)
      {
        $data = $query_string->result_array();
        return $data;
      }
      else
      {
        return 0;
      }
    }

    function getActiveAddress($Id)
    {
      $query_string = $this->db->query("SELECT  DISTINCT BorrowerAddressHistoryId
                                                FROM borroweraddresshistory                                                  
                                                  WHERE BorrowerId = $Id
                                                  AND StatusId = 1
      ");

      if($query_string->num_rows() > 0)
      {
        $data = $query_string->result_array();
        return $data;
      }
      else
      {
        return 0;
      }
    }

    function getActiveEmail($Id)
    {
      $query_string = $this->db->query("SELECT  DISTINCT BorrowerEmailId
                                                FROM borrower_has_emails                                                  
                                                  WHERE BorrowerId = $Id
                                                  AND StatusId = 1
      ");

      if($query_string->num_rows() > 0)
      {
        $data = $query_string->result_array();
        return $data;
      }
      else
      {
        return 0;
      }
    }

    function getActiveEducation($Id)
    {
      $query_string = $this->db->query("SELECT  DISTINCT BorrowerEducationId
                                                FROM borrower_has_education                                                  
                                                  WHERE BorrowerId = $Id
                                                  AND StatusId = 1
      ");

      if($query_string->num_rows() > 0)
      {
        $data = $query_string->result_array();
        return $data;
      }
      else
      {
        return 0;
      }
    }

}