<?php
class borrower_model extends CI_Model
{
    function __construct()
    {
      parent::__construct();
			$this->load->model('maintenance_model');
			$this->load->model('access');
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
                                                , DATE_FORMAT(UR.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(UR.DateUpdated, '%d %b %Y %r') as DateUpdated
      																					FROM R_UserRole UR
      																						INNER JOIN R_Role R
      																							ON R.RoleId = UR.RoleId
                                                      AND UR.EmployeeNumber != 'sysad'
			");
      $data = $query_string->result_array();
      return $data;
    }

    function countBorrower($input)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM r_Borrowers
                                                  WHERE FirstName = '".$input['FirstName']."'
                                                  AND MiddleName = '".$input['MiddleName']."'
                                                  AND ExtName = '".$input['ExtName']."'
                                                  AND LastName = '".$input['LastName']."'
                                                  AND DateOfBirth = '".$input['DateOfBirth']."'

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

    function countPersonalReference($input)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM borrower_has_reference
                                                  WHERE BorrowerId = '".$input['BorrowerId']."'
                                                  AND Name = '".$input['Name']."'
                                                  AND Address = '".$input['Address']."'
                                                  AND ContactNumber = '".$input['ContactNumber']."'

      ");
      $data = $query_string->num_rows();
      return $data;
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

    function getBorrowerDetails($Id)
    {
      $query_string = $this->db->query("SELECT DISTINCT B.BorrowerId
                                                , S.name as Salutation
                                                , B.FirstName
                                                , CONCAT(B.LastName, ', ', B.FirstName, ' ', B.MiddleName, ', ', B.ExtName) as Name
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
                                                , BP.FileName
                                                , B.BorrowerNumber
                                                , B.MotherName

                                                , B.MiddleName
                                                , S.SalutationId
                                                , SX.SexId
                                                , N.NationalityId
                                                , N.Description as NationalityName
                                                , C.CivilStatusId

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
                                                FROM R_ContactNumbers CN
                                                  INNER JOIN borrower_has_contactNumbers EC
                                                    ON EC.ContactNumberId = CN.ContactNumberId
                                                      WHERE EC.BorrowerId = ".$BorrowerID."
      ");
      $data = $query_string->result_array();
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
          // insert into logs
            if($input['updateType'] == 1)
            {
              $auditBorrower = 'Re-activated address record #ADD-' .$AddressTransactionNumber['Id']; // main log
              $auditStaff = 'Re-activated address record #ADD-' .$AddressTransactionNumber['Id']. '.'; // employee notification
            }
            else if($input['updateType'] == 0)
            {
              $auditBorrower = 'Deactivated address record #ADD-' .$AddressTransactionNumber['Id']; // main log
              $auditStaff = 'Deactivated address record #ADD-' .$AddressTransactionNumber['Id']. '.'; // employee notification
            }
            $this->auditBorrower($auditBorrower, $auditStaff, $BorrowerDetail['BorrowerId']);
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
          // insert into logs
            $auditBorrower = 'Set address record #ADD-' .$AddressTransactionNumber['Id']. ' of borrower #'.$BorrowerDetail['BorrowerNumber'] . ' as primary.';
            $auditStaff = 'Set address record #ADD-' .$AddressTransactionNumber['Id']. ' as primary address.';
            $this->auditBorrower($auditBorrower, $auditStaff, $BorrowerDetail['BorrowerId']);
        }
      } // DONE
      else if($input['tableType'] == 'BorrowerEmail')
      {
        $BorrowerDetail = $this->db->query("SELECT  BE.BorrowerEmailId 
                                                    , B.BorrowerId
                                                    FROM borrower_has_emails BE
                                                      INNER JOIN R_Borrowers B
                                                        ON B.BorrowerId = BE.BorrowerId
                                                      WHERE BE.BorrowerEmailId = ".$input['Id']."
        ")->row_array();
        $EmailTransaction = $this->db->query("SELECT LPAD(".$input['Id'].", 6, 0) as Id")->row_array();
        if($input['updateType'] == 1 || $input['updateType'] == 0) // deactivate and re-activate email of borrower
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
          // insert into logs
            if($input['updateType'] == 1)
            {
              $auditBorrower = 'Re-activated address record #EM-' .$EmailTransaction['Id']; // main log
              $auditStaff = 'Re-activated address record #EM-' .$EmailTransaction['Id']. '.'; // employee notification
            }
            else if($input['updateType'] == 0)
            {
              $auditBorrower = 'Deactivated address record #EM-' .$EmailTransaction['Id']; // main log
              $auditStaff = 'Deactivated address record #EM-' .$EmailTransaction['Id']. '.'; // employee notification
            }
          // insert into logs
            $auditBorrower = 'Set address record #EM-' .$EmailTransaction['Id']. ' of borrower #'.$BorrowerDetail['BorrowerNumber'] . ' as primary.';
            $auditStaff = 'Set address record #EM-' .$EmailTransaction['Id']. ' as primary address.';
            $this->auditBorrower($auditBorrower, $auditStaff, $BorrowerDetail['BorrowerId']);
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
          // insert into logs
            $auditBorrower = 'Set Email record #EM-' .$EmailTransaction['Id']. ' of borrower #'.$BorrowerDetail['BorrowerId'] . ' as primary.';
            $auditStaff = 'Set Email record #EM-' .$EmailTransaction['Id']. ' as primary address.';
            $this->auditBorrower($auditBorrower, $auditStaff, $BorrowerDetail['BorrowerId']);
        }
      } // DONE
      else if($input['tableType'] == 'BorrowerContact')
      {
        $BorrowerDetail = $this->db->query("SELECT  BC.BorrowerContactId 
                                                  , C.Number
                                                  , B.BorrowerId
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
          // insert into logs
            if($input['updateType'] == 1)
            {
              $auditBorrower = 'Re-activated contact record #CN-'.$TransactionNumber['Id'].' of '.$BorrowerDetail['BorrowerId'];
              $auditStaff = 'Re-activated contact record #CN-'.$TransactionNumber['Id'].'.';
            }
            else if($input['updateType'] == 0)
            {
              $auditBorrower = 'Deactivated contact record #CN-'.$TransactionNumber['Id'].' of '.$BorrowerDetail['BorrowerId'].'.';
              $auditStaff = 'Deactivated contact record #CN-'.$TransactionNumber['Id'].'.';
            }
            $this->auditBorrower($auditBorrower, $auditStaff, $BorrowerDetail['BorrowerId']);
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
          // insert into logs
            $auditBorrower = 'Set contact number record #CN-' .$TransactionNumber['Id']. ' of borrower #'.$BorrowerDetail['BorrowerId'] . ' as primary.';
            $auditStaff = 'Set contact number record #CN-' .$TransactionNumber['Id']. ' as primary contact number.';
            $this->auditBorrower($auditBorrower, $auditStaff, $BorrowerDetail['BorrowerId']);
        }
      } //DONE
      else if($input['tableType'] == 'BorrowerDocuments')
      {
        $BorrowerDetail = $this->db->query("SELECT  BS.BorrowerIdentificationId 
                                                  , IC.IdentificationId
                                                  , B.BorrowerId
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
          // insert into logs
            if($input['updateType'] == 1)
            {
              $auditBorrower = 'Re-activated supporting document record #SD-'.$DocumentNumber['Id'].' of '.$BorrowerDetail['BorrowerId'];
              $auditStaff = 'Re-activated supporting document record #SD-'.$DocumentNumber['Id'].'.';
            }
            else if($input['updateType'] == 0)
            {
              $auditBorrower = 'Deactivated supporting document record #SD-'.$DocumentNumber['Id'].' of '.$BorrowerDetail['BorrowerId'].'.';
              $auditStaff = 'Deactivated supporting document record #SD-'.$DocumentNumber['Id'].'.';
            }
            $this->auditBorrower($auditBorrower, $auditStaff, $BorrowerDetail['BorrowerId']);
        }
      } //DONE
      else if($input['tableType'] == 'BorrowerSpouse')
      {
        $BorrowerDetail = $this->db->query("SELECT  BSP.BorrowerSpouseId 
                                                  , SP.SpouseId
                                                  , B.BorrowerId
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
          // insert into logs
            if($input['updateType'] == 1)
            {
              $auditBorrower = 'Re-activated spouse record #SP-'.$SpouseNumber['Id'].' of '.$BorrowerDetail['BorrowerId'];
              $auditStaff = 'Re-activated spouse record #SP-'.$SpouseNumber['Id'].'.';
            }
            else if($input['updateType'] == 0)
            {
              $auditBorrower = 'Deactivated spouse record #SP-'.$SpouseNumber['Id'].' of '.$BorrowerDetail['BorrowerId'];
              $auditStaff = 'Deactivated spouse record #SP-'.$SpouseNumber['Id'].'.';
            }
            $this->auditBorrower($auditBorrower, $auditStaff, $BorrowerDetail['BorrowerId']);
        }
      } //DONE
      else if($input['tableType'] == 'BorrowerEmployer')
      {
        $BorrowerDetail = $this->db->query("SELECT  BEMP.EmployerId
                                                  , B.BorrowerId
                                                  FROM borrower_has_employer BEMP
                                                    INNER JOIN R_Borrowers B
                                                      ON B.BorrowerId = BEMP.BorrowerId
                                                    WHERE BEMP.EmployerId = ".$input['Id']."
        ")->row_array();
        $EmployerNumber = $this->db->query("SELECT LPAD(".$input['Id'].", 6, 0) as Id")->row_array();
        if($input['updateType'] == 1 || $input['updateType'] == 0) // activate and deactivate Contact Number of Borrower
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
          // insert into logs
            if($input['updateType'] == 1)
            {
              $auditBorrower = 'Re-activated employer record #EP-'.$EmployerNumber['Id'].' of '.$BorrowerDetail['BorrowerId'];
              $auditStaff = 'Re-activated employer record #EP-'.$EmployerNumber['Id'].'.';
            }
            else if($input['updateType'] == 0)
            {
              $auditBorrower = 'Deactivated employer record #EP-'.$EmployerNumber['Id'].' of '.$BorrowerDetail['BorrowerId'];
              $auditStaff = 'Deactivated employer record #EP-'.$EmployerNumber['Id'].'.';
            }
            $this->auditBorrower($auditBorrower, $auditStaff, $BorrowerDetail['BorrowerId']);
        }
      } //DONE
      else if($input['tableType'] == 'BorrowerCoMaker')
      {
        $BorrowerDetail = $this->db->query("SELECT  BCM.BorrowerComakerId 
                                                  , B.BorrowerId
                                                  FROM borrower_has_comaker BCM
                                                    INNER JOIN R_Borrowers B
                                                      ON B.BorrowerId = BCM.BorrowerId
                                                    WHERE BCM.BorrowerComakerId = ".$input['Id']."
        ")->row_array();
        $ComakerNumber = $this->db->query("SELECT LPAD(".$input['Id'].", 6, 0) as Id")->row_array();
        if($input['updateType'] == 1 || $input['updateType'] == 0) // activate and deactivate Contact Number of Borrower
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
          // insert into logs
            if($input['updateType'] == 1)
            {
              $auditBorrower = 'Re-activated Co-maker record #CM-'.$ComakerNumber['Id'].' of '.$BorrowerDetail['BorrowerId'];
              $auditStaff = 'Re-activated Co-maker record #CM-'.$ComakerNumber['Id'].'.';
            }
            else if($input['updateType'] == 0)
            {
              $auditBorrower = 'Deactivated Co-maker record #CM-'.$ComakerNumber['Id'].' of '.$BorrowerDetail['BorrowerId'];
              $auditStaff = 'Deactivated Co-maker record #CM-'.$ComakerNumber['Id'].'.';
            }
            $this->auditBorrower($auditBorrower, $auditStaff, $BorrowerDetail['BorrowerId']);
        }
      } //DONE
      else if($input['tableType'] == 'BorrowerPersonal')
      {
        $BorrowerDetail = $this->db->query("SELECT  BRF.ReferenceId
                                                  , B.BorrowerId
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
          // insert into logs
            if($input['updateType'] == 1)
            {
              $auditBorrower = 'Re-activated Personal Reference record #RF-'.$PersonalNumber['Id'];
              $auditStaff = 'Re-activated Personal Reference record #RF-'.$PersonalNumber['Id'].' of '.$BorrowerDetail['BorrowerId'];
            }
            else if($input['updateType'] == 0)
            {
              $auditBorrower = 'Deactivated Personal Reference record #RF-'.$PersonalNumber['Id'].' of '.$BorrowerDetail['BorrowerId'];
              $auditStaff = 'Deactivated Personal Reference record #RF-'.$PersonalNumber['Id'].'.';
            }
            $this->auditBorrower($auditBorrower, $auditStaff, $BorrowerDetail['BorrowerId']);
        }
      } //DONE
      else if($input['tableType'] == 'BorrowerEducation')
      {
        $BorrowerDetail = $this->db->query("SELECT  BEDU.BorrowerEducationId
                                                  , B.BorrowerId
                                                  FROM borrower_has_Education BEDU
                                                    INNER JOIN R_Borrowers B
                                                      ON B.BorrowerId = BEDU.BorrowerId
                                                    WHERE BEDU.BorrowerEducationId = ".$input['Id']."
        ")->row_array();
        $EducationNumber = $this->db->query("SELECT LPAD(".$input['Id'].", 6, 0) as Id")->row_array();
        if($input['updateType'] == 1 || $input['updateType'] == 0) // activate and deactivate Contact Number of Borrower
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
          // insert into logs
            if($input['updateType'] == 1)
            {
              $auditBorrower = 'Re-activated Education Background record #ED-'.$EducationNumber['Id'];
              $auditStaff = 'Re-activated Educational Background record #ED-'.$EducationNumber['Id'].' of '.$BorrowerDetail['BorrowerId'];
            }
            else if($input['updateType'] == 0)
            {
              $auditBorrower = 'Deactivated Educational Background record #ED-'.$EducationNumber['Id'].' of '.$BorrowerDetail['BorrowerId'];
              $auditStaff = 'Deactivated Educational Background record #ED-'.$EducationNumber['Id'].'.';
            }
            $this->auditBorrower($auditBorrower, $auditStaff, $BorrowerDetail['BorrowerId']);
        }
      } //DONE
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

      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getCityAddress($Id)
    {
      $query_string = $this->db->query("SELECT  A.AddressId
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
                                                  AND BAH.IsPrimary = 1
                                                  AND A.AddressType = 'City Address'

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

    function getSpouseEmployer($Id)
    {
      $query_string = $this->db->query("SELECT DISTINCT SP.SpouseId
                                                , BEMP.Name
                                                , POS.PositionId
                                                , BEMP.TenureYear
                                                , BEMP.TenureMonth
                                                , BEMP.BusinessAddress
                                                FROM R_Spouse SP
                                                  INNER JOIN Borrower_has_Employer BEMP
                                                    ON BEMP.SpouseId = SP.SpouseId
                                                  INNER JOIN R_Position POS
                                                    ON POS.PositionId = BEMP.PositionId
                                                  WHERE BEMP.SpouseId = $Id

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
                                                FROM Borrower_has_Comaker BCM
                                                  INNER JOIN borrower_has_position POS
                                                    ON BCM.PositionId = POS.BorrowerPositionId
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
                                                , BEDU.Level
                                                , BEDU.SchoolName
                                                , YearGraduated
                                                , BEDU.StatusId
                                                FROM Borrower_has_Education BEDU
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = BEDU.BorrowerId
                                                      WHERE B.BorrowerId = $Id
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllList()
    {
      $query_string = $this->db->query("SELECT DISTINCT B.FirstName
                                                , acronym (B.MiddleName) as MI
                                                , B.LastName
                                                , B.ExtName
                                                , CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', EMP.MiddleName, ', ', EMP.ExtName) as CreatedBy
                                                , B.StatusId
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
                                                              WHERE EMP.StatusId = 1
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getTotalLoans($BorrowerId)
    {      
      $query = $this->db->query("SELECT   COUNT(A.ApplicationId) as Record
                                          FROM t_application A
                                                WHERE A.StatusId = 2
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
                                                , IC.Name
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
                                                FROM r_identificationcards I
                                                    INNER JOIN borrower_has_supportdocuments BS
                                                        ON BS.IdentificationId = I.IdentificationId
                                                    INNER JOIN r_employee EMP
                                                        ON EMP.EmployeeNumber = I.CreatedBy
                                                    INNER JOIN R_Requirements IC
                                                        ON IC.RequirementId = I.ID
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
                                          , DATE_FORMAT(PM.DateCreated, '%b %d, %Y %r') as DateCreated
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
      ");
      $data = $query->result_array();
      return $data;
    }


}