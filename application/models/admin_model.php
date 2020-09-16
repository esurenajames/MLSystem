<?php
class admin_model extends CI_Model
{
    function __construct()
    {
      parent::__construct();
			$this->load->model('maintenance_model');
			$this->load->model('access');
    }

    function getAuditLogs()
    {
      $query_string = $this->db->query("SELECT  Description
                                                , CONCAT(FirstName, ' ', MiddleName, ' ', LastName, CASE WHEN ExtName != '' THEN CONCAT(', ', ExtName) ELSE '' END ) as Name
                                                , CASE
                                                  WHEN Remarks IS NULL
                                                  THEN 'N/A'
                                                  ELSE Remarks
                                                END as Remarks
                                                , DATE_FORMAT(L.DateCreated, '%d %b %Y %r') as DateCreated
                                                , LogId
                                                FROM R_Logs L
                                                  INNER JOIN R_Employee EMP
                                                    ON EMP.EmployeeNumber = L.CreatedBy
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getEmployees($keyword)
    {
      $query_result = [];
      $query = $this->db->query("SELECT   EmployeeNumber 'id'
                                          , CONCAT(EmployeeNumber, ' - ', FirstName, ' ', MiddleName, ' ', LastName, CASE WHEN ExtName != '' THEN CONCAT(', ', ExtName) ELSE '' END ) as 'text'
                                          FROM R_Employee 
                                            WHERE (
                                              StatusId = 1
                                              OR 
                                              StatusId = 2
                                            )
                                            AND 
                                            (
                                              EmployeeNumber LIKE '%$keyword%'
                                              OR FirstName LIKE '%$keyword%'
                                              OR LastName LIKE '%$keyword%'
                                              OR ExtName LIKE '%$keyword%'
                                              OR MiddleName LIKE '%$keyword%'
                                            )
                                            AND EmployeeNumber != '000000'
      ");
      return $query->result();
    }

    function getBorrowers($keyword)
    {
      $query_result = [];
      $query = $this->db->query("SELECT   BorrowerNumber 'id'
                                          , CONCAT(EmployeeNumber, ' - ', FirstName, ' ', MiddleName, ' ', LastName, CASE WHEN ExtName != '' THEN CONCAT(', ', ExtName) ELSE '' END ) as 'text'
                                          FROM R_Borrowers 
                                            WHERE (
                                              StatusId = 1
                                              OR 
                                              StatusId = 2
                                            )
                                            AND 
                                            (
                                              BorrowerNumber LIKE '%$keyword%'
                                              OR FirstName LIKE '%$keyword%'
                                              OR LastName LIKE '%$keyword%'
                                              OR ExtName LIKE '%$keyword%'
                                              OR MiddleName LIKE '%$keyword%'
                                            )
                                            AND BorrowerNumber != '000000'
      ");
      return $query->result();
    }

    function getRoles($keyword)
    {
      $query_result = [];
      $query = $this->db->query("SELECT   RoleId 'id'
                                          , Description as 'text'
                                          FROM R_Role 
                                            WHERE Description LIKE '%$keyword%'
      ");
      return $query->result();
    }

    function getGender()
    {
      $query_result = [];
      $query = $this->db->query("SELECT   SexId
                                          , Name 
                                          FROM R_Sex
                                            WHERE StatusId = 1
      ");
      return $query->result();
    }

    function countExistingUserRole($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_UserRole
                                                  WHERE EmployeeNumber = '".$data['EmployeeNumber']."'
                                                  AND RoleId = '".$data['RoleId']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function countBank($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_Bank
                                                  WHERE BankName = '".$data['BankName']."'
                                                  AND Description = '".$data['Description']."'
                                                  AND AccountNumber = '".$data['AccountNumber']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getBankDetails($Id)
    {
      $query_string = $this->db->query("SELECT  BNK.BankName
                                                , BNK.Description
                                                , BNK.AccountNumber
                                                FROM R_Bank BNK
                                                  WHERE BNK.BankId = '$Id'
      ");
      $BankDetail = $query_string->row_array();
      return $BankDetail;
    }

    function countBranch($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_Branch
                                                  WHERE Code = '".$data['Code']."'
                                                  AND Name = '".$data['Name']."'
                                                  AND Description = '".$data['Description']."'
                                                  AND LeaseMonthly = '".$data['LeaseMonthly']."'
                                                  AND Description = '".$data['Description']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getBranchDetails($Id)
    {
      $query_string = $this->db->query("SELECT BRNCH.Name as Name
                                                , BranchId
                                                , BRNCH.Description
                                                , BRNCH.Code
                                                , BRNCH.CreatedBy
                                                , BRNCH.StatusId
                                                , BRNCH.LeaseMonthly
                                                , DATE_FORMAT(BRNCH.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(BRNCH.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                , DATE_FORMAT(BRNCH.DateFromLease, '%d %b %Y %r') as DateFrom
                                                , DATE_FORMAT(BRNCH.DateToLease, '%d %b %Y %r') as DateTo
                                                FROM R_Branch BRNCH
                                                  WHERE BRNCH.BranchId = '$Id'
      ");
      $BranchDetail = $query_string->row_array();
      return $BranchDetail;
    }

    function countLoanType($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_Loans
                                                  WHERE Name = '".$data['Name']."'
                                                  AND Description = '".$data['Description']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getLoanTypeDetails($Id)
    {
      $query_string = $this->db->query("SELECT  L.Name as LoanName
                                                , L.Description
                                                FROM R_Loans L
                                                  WHERE L.LoanId = '$Id'
      ");
      $LoanTypeDetail = $query_string->row_array();
      return $LoanTypeDetail;
    }

    function countCharges($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_Charges
                                                  WHERE Name = '".$data['Name']."'
                                                  AND Description = '".$data['Description']."'
                                                  AND Frequency = '".$data['Frequency']."'
                                                  AND Type = '".$data['Type']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getChargeDetails($Id)
    {
      $query_string = $this->db->query("SELECT  Type
                                                , Name
                                                , Description
                                                , Frequency
                                                FROM R_Charges 
                                                  WHERE ChargeId = '$Id'
      ");
      $ChargeDetail = $query_string->row_array();
      return $ChargeDetail;
    }

    function countOptional($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_OptionalCharges
                                                  WHERE Name = '".$data['Name']."'
                                                  AND Description = '".$data['Description']."'

      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getOptionalDetails($Id)
    {
      $query_string = $this->db->query("SELECT  Name
                                                , Description
                                                FROM R_OptionalCharges 
                                                  WHERE OptionalId = '$Id'
      ");
      $OptionalDetail = $query_string->row_array();
      return $OptionalDetail;
    }

    function countRequirements($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_Requirements
                                                  WHERE Name = '".$data['Name']."'
                                                  AND Description = '".$data['Description']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getRequirementDetails($Id)
    {
      $query_string = $this->db->query("SELECT  Name
                                                FROM R_Requirements 
                                                  WHERE RequirementId = '$Id'
      ");
      $RequirementDetail = $query_string->row_array();
      return $RequirementDetail;
    }


    function countPositions($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_Position
                                                  WHERE Name = '".$data['Name']."'
                                                  AND Description = '".$data['Description']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getPositionDetails($Id)
    {
      $query_string = $this->db->query("SELECT  Name
                                                , Description
                                                FROM R_Position 
                                                  WHERE PositionId = '$Id'
      ");
      $PositionDetail = $query_string->row_array();
      return $PositionDetail;
    }

    function countPurpose($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_Purpose
                                                  WHERE Name = '".$data['Name']."'
                                                  AND Description = '".$data['Description']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getPurposeDetails($Id)
    {
      $query_string = $this->db->query("SELECT  Name
                                                , Description
                                                FROM R_Purpose 
                                                  WHERE PurposeId = '$Id'
      ");
      $PurposeDetail = $query_string->row_array();
      return $PurposeDetail;
    }

    function countMethod($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_MethodOfPayment
                                                  WHERE Name = '".$data['Name']."'
                                                  AND Description = '".$data['Description']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getMethodDetails($Id)
    {
      $query_string = $this->db->query("SELECT  Name
                                                , Description
                                                FROM R_MethodOfPayment 
                                                  WHERE MethodId = '$Id'
      ");
      $MethodDetail = $query_string->row_array();
      return $MethodDetail;
    }

    function countAsset($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_Category
                                                  WHERE Name = '".$data['Name']."'
                                                  AND Description = '".$data['Description']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getAssetDetails($Id)
    {
      $query_string = $this->db->query("SELECT  Name
                                                , Description
                                                FROM R_Category 
                                                  WHERE CategoryId = '$Id'
      ");
      $AssetDetail = $query_string->row_array();
      return $AssetDetail;
    }

    function countLoanStatus($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_LoanStatus
                                                  WHERE Name = '".$data['Name']."'
                                                  AND Description = '".$data['Description']."'

      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getLoanStatusDetails($Id)
    {
      $query_string = $this->db->query("SELECT  Name
                                                , Description
                                                FROM R_LoanStatus 
                                                  WHERE LoanStatusId = '$Id'
      ");
      $LoanStatusDetail = $query_string->row_array();
      return $LoanStatusDetail;
    }

    function countBorrowerStatus($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_Borrower_has_Status
                                                  WHERE Name = '".$data['Name']."'
                                                  AND Description = '".$data['Description']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getBorrowerStatusDetails($Id)
    {
      $query_string = $this->db->query("SELECT  Name
                                                , Description
                                                FROM R_Borrower_has_Status 
                                                  WHERE BorrowerStatusId = '$Id'
      ");
      $BorrowerStatusDetail = $query_string->row_array();
      return $BorrowerStatusDetail;
    }

    function countIndustry($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_Industry I
                                                  WHERE Name = '".$data['Name']."'
                                                  AND Description = '".$data['Description']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getIndustryDetails($Id)
    {
      $query_string = $this->db->query("SELECT  Name
                                                , Description
                                                FROM R_Industry 
                                                  WHERE IndustryId = '$Id'
      ");
      $IndustryDetail = $query_string->row_array();
      return $IndustryDetail;
    }

    function getEducationDetails($Id)
    {
      $query_string = $this->db->query("SELECT  EDU.Name
                                                , EDU.Description
                                                FROM R_Education EDU 
                                                  WHERE EducationId = '$Id'
      ");
      $EducationDetail = $query_string->row_array();
      return $EducationDetail;
    }

    function countEducation($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_Education EDU
                                                  WHERE EDU.Name = '".$data['Name']."'
                                                  AND EDU.Description = '".$data['Description']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function countTangibles($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_Tangibles T
                                                  WHERE T.SerialNumber = '".$data['SerialNumber']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function updateStatus($input)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");

      if($input['tableType'] == 'Bank')
      {
        $BankDetail = $this->db->query("SELECT  BankName
                                                    FROM R_Bank BNK
                                                      WHERE BankId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'BankId' => $input['Id']
          );
          $table = 'R_Bank';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated ' .$BankDetail['Name']. ' at the system setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated ' .$BankDetail['Name']. '  at the system setup'; // main log
          }
          $data2 = array(
            'Description'   => $Description,
            'CreatedBy'     => $EmployeeNumber,
            'DateCreated'   => $DateNow
          );
          $this->db->insert('R_Logs', $data2);
      }
      else if($input['tableType'] == 'Branch')
      {
        $BranchDetail = $this->db->query("SELECT  Name
                                                    FROM R_Branch BRNCH
                                                      WHERE BranchId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'BranchId' => $input['Id']
          );
          $table = 'R_Branch';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated ' .$BranchDetail['Name']. ' at the system setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated ' .$BranchDetail['Name']. '  at the system setup'; // main log
          }
          $data2 = array(
            'Description'   => $Description,
            'CreatedBy'     => $EmployeeNumber,
            'DateCreated'   => $DateNow
          );
          $this->db->insert('R_Logs', $data2);
      }
      else if($input['tableType'] == 'Loan')
      {
        $LoanDetail = $this->db->query("SELECT  Name
                                                    FROM R_Loans L
                                                      WHERE LoanId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'LoanId' => $input['Id']
          );
          $table = 'R_Loans';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated ' .$LoanDetail['Name']. ' at the system setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated ' .$LoanDetail['Name']. '  at the system setup'; // main log
          }
          $data2 = array(
            'Description'   => $Description,
            'CreatedBy'     => $EmployeeNumber,
            'DateCreated'   => $DateNow
          );
          $this->db->insert('R_Logs', $data2);
      }
      else if($input['tableType'] == 'Charge')
      {
        $ChargeDetail = $this->db->query("SELECT  Name
                                                    FROM R_Charges CH
                                                      WHERE ChargeId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'ChargeId' => $input['Id']
          );
          $table = 'R_Charges';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated ' .$ChargeDetail['Name']. ' at the system setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated ' .$ChargeDetail['Name']. '  at the system setup'; // main log
          }
          $data2 = array(
            'Description'   => $Description,
            'CreatedBy'     => $EmployeeNumber,
            'DateCreated'   => $DateNow
          );
          $this->db->insert('R_Logs', $data2);
      }
      else if($input['tableType'] == 'Requirement')
      {
        $RequirementDetail = $this->db->query("SELECT  Name
                                                    FROM R_Requirements R
                                                      WHERE RequirementId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'RequirementId' => $input['Id']
          );
          $table = 'R_Requirements';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated ' .$RequirementDetail['Name']. ' at the system setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated ' .$RequirementDetail['Name']. '  at the system setup'; // main log
          }
          $data2 = array(
            'Description'   => $Description,
            'CreatedBy'     => $EmployeeNumber,
            'DateCreated'   => $DateNow
          );
          $this->db->insert('R_Logs', $data2);
      }
      else if($input['tableType'] == 'Position')
      {
        $PositionDetail = $this->db->query("SELECT  Name
                                                    FROM R_Position PS
                                                      WHERE PositionId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'PositionId' => $input['Id']
          );
          $table = 'R_Position';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated ' .$PositionDetail['Name']. ' at the system setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated ' .$PositionDetail['Name']. '  at the system setup'; // main log
          }
          $data2 = array(
            'Description'   => $Description,
            'CreatedBy'     => $EmployeeNumber,
            'DateCreated'   => $DateNow
          );
          $this->db->insert('R_Logs', $data2);
      }
      else if($input['tableType'] == 'Optional')
      {
        $OptionalDetail = $this->db->query("SELECT  Name
                                                    FROM R_OptionalCharges OC
                                                      WHERE OptionalId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'OptionalId' => $input['Id']
          );
          $table = 'R_OptionalCharges';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated ' .$OptionalDetail['Name']. ' at the system setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated ' .$OptionalDetail['Name']. '  at the system setup'; // main log
          }
          $data2 = array(
            'Description'   => $Description,
            'CreatedBy'     => $EmployeeNumber,
            'DateCreated'   => $DateNow
          );
          $this->db->insert('R_Logs', $data2);
      }
      else if($input['tableType'] == 'Purpose')
      {
        $PurposeDetail = $this->db->query("SELECT  Name
                                                    FROM R_Purpose PP
                                                      WHERE PurposeId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'PurposeId' => $input['Id']
          );
          $table = 'R_Purpose';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated ' .$PurposeDetail['Name']. ' at the system setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated ' .$PurposeDetail['Name']. '  at the system setup'; // main log
          }
          $data2 = array(
            'Description'   => $Description,
            'CreatedBy'     => $EmployeeNumber,
            'DateCreated'   => $DateNow
          );
          $this->db->insert('R_Logs', $data2);
      }
      else if($input['tableType'] == 'Method')
      {
        $MethodDetail = $this->db->query("SELECT  Name
                                                    FROM R_MethodOfPayment M
                                                      WHERE MethodId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'MethodId' => $input['Id']
          );
          $table = 'R_MethodOfPayment';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated ' .$MethodDetail['Name']. ' at the system setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated ' .$MethodDetail['Name']. '  at the system setup'; // main log
          }
          $data2 = array(
            'Description'   => $Description,
            'CreatedBy'     => $EmployeeNumber,
            'DateCreated'   => $DateNow
          );
          $this->db->insert('R_Logs', $data2);
      }
      else if($input['tableType'] == 'Category')
      {
        $CategoryDetail = $this->db->query("SELECT  Name
                                                    FROM R_Category A
                                                      WHERE CategoryId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'CategoryId' => $input['Id']
          );
          $table = 'R_Category';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated ' .$CategoryDetail['Name']. ' at the system setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated ' .$CategoryDetail['Name']. '  at the system setup'; // main log
          }
          $data2 = array(
            'Description'   => $Description,
            'CreatedBy'     => $EmployeeNumber,
            'DateCreated'   => $DateNow
          );
          $this->db->insert('R_Logs', $data2);
      }
      else if($input['tableType'] == 'LoanStatus')
      {
        $LoanStatusDetail = $this->db->query("SELECT  Name
                                                    FROM R_LoanStatus LS
                                                      WHERE LoanStatusId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'LoanStatusId' => $input['Id']
          );
          $table = 'R_LoanStatus';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated ' .$LoanStatusDetail['Name']. ' at the system setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated ' .$LoanStatusDetail['Name']. '  at the system setup'; // main log
          }
          $data2 = array(
            'Description'   => $Description,
            'CreatedBy'     => $EmployeeNumber,
            'DateCreated'   => $DateNow
          );
          $this->db->insert('R_Logs', $data2);
      }
      else if($input['tableType'] == 'BorrowerStatus')
      {
        $BorrowerStatusDetail = $this->db->query("SELECT  Name
                                                    FROM R_Borrower_has_Status BS
                                                      WHERE BorrowerStatusId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'BorrowerStatusId' => $input['Id']
          );
          $table = 'R_Borrower_has_Status';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated ' .$BorrowerStatusDetail['Name']. ' at the system setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated ' .$BorrowerStatusDetail['Name']. '  at the system setup'; // main log
          }
          $data2 = array(
            'Description'   => $Description,
            'CreatedBy'     => $EmployeeNumber,
            'DateCreated'   => $DateNow
          );
          $this->db->insert('R_Logs', $data2);
      }
      else if($input['tableType'] == 'Industry')
      {
        $IndustryDetail = $this->db->query("SELECT  Name
                                                    FROM R_Industry
                                                      WHERE IndustryId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'IndustryId' => $input['Id']
          );
          $table = 'R_Industry';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated ' .$IndustryDetail['Name']. ' at the system setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated ' .$IndustryDetail['Name']. '  at the system setup'; // main log
          }
          $data2 = array(
            'Description'   => $Description,
            'CreatedBy'     => $EmployeeNumber,
            'DateCreated'   => $DateNow
          );
          $this->db->insert('R_Logs', $data2);
      }
    }



}