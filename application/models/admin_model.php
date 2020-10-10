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

    function getAssetManagementDetails($Id)
    {
      $query_string = $this->db->query("SELECT  AssetManagementId
                                                , Type
                                                , CategoryId
                                                , PurchaseValue
                                                , ReplacementValue
                                                , SerialNumber
                                                , BoughtFrom
                                                , Description
                                                FROM R_AssetManagement
                                                  WHERE AssetManagementId = '$Id'
      ");
      $AssetManagementId = $query_string->row_array();
      return $AssetManagementId;
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
                                                FROM R_AssetManagement T
                                                  WHERE T.SerialNumber = '".$data['SerialNumber']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getOccupationDetails($Id)
    {
      $query_string = $this->db->query("SELECT  OCCU.Name
                                                , OCCU.Description
                                                FROM R_Occupation OCCU 
                                                  WHERE OccupationId = '$Id'
      ");
      $OccupationDetail = $query_string->row_array();
      return $OccupationDetail;
    }

    function countOccupation($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_Occupation OCCU
                                                  WHERE OCCU.Name = '".$data['Name']."'
                                                  AND OCCU.Description = '".$data['Description']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getRepaymentDetails($Id)
    {
      $query_string = $this->db->query("SELECT  RC.Type
                                                FROM R_RepaymentCycle RC 
                                                  WHERE RepaymentId = '$Id'
      ");
      $RepaymentDetail = $query_string->row_array();
      return $RepaymentDetail;
    }

    function countRepayment($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_RepaymentCycle RC
                                                  WHERE RC.Type = '".$data['Name']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getCapitalDetails($Id)
    {
      $query_string = $this->db->query("SELECT  C.Amount
                                                , CapitalId
                                                , CONCAT('IC-', LPAD(C.CapitalId, 6, 0)) as ReferenceNo
                                                FROM R_Capital C 
                                                  WHERE CapitalId = '$Id'
      ");
      $CapitalDetail = $query_string->row_array();
      return $CapitalDetail;
    }

    function countCapital($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_Capital C
                                                  WHERE C.Amount = '".$data['Amount']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getExpenseTypeDetails($Id)
    {
      $query_string = $this->db->query("SELECT  ET.Name as ExpenseType
                                                , ET.Description
                                                , ExpenseTypeId
                                                , CONCAT('ET-', LPAD(ET.ExpenseTypeId, 6, 0)) as ReferenceNo
                                                FROM R_ExpenseType ET 
                                                  WHERE ExpenseTypeId = '$Id'
      ");
      $ExpenseTypeDetail = $query_string->row_array();
      return $ExpenseTypeDetail;
    }

    function countExpenseType($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_ExpenseType ET
                                                  WHERE ET.Name = '".$data['Name']."'
                                                  AND ET.Description = '".$data['Description']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getExpenseDetails($Id)
    {
      $query_string = $this->db->query("SELECT  EX.ExpenseTypeId
                                                , EX.Amount
                                                , ET.Name as ExpenseName
                                                , ExpenseId
                                                , EX.ExpenseTypeId
                                                , EX.DateExpense
                                                , CONCAT('EX-', LPAD(EX.ExpenseId, 6, 0)) as ReferenceNo
                                                FROM R_Expense EX 
                                                  INNER JOIN R_ExpenseType ET
                                                   ON EX.ExpenseTypeId = ET.ExpenseTypeId
                                                  WHERE ExpenseId = '$Id'
      ");
      $ExpenseDetail = $query_string->row_array();
      return $ExpenseDetail;
    }

    function countExpense($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_Expense EX
                                                  WHERE EX.Amount = '".$data['Amount']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getWithdrawalTypeDetails($Id)
    {
      $query_string = $this->db->query("SELECT  WT.Name as WithdrawalType
                                                , WT.Description
                                                , WithdrawalTypeId
                                                , CONCAT('WT-', LPAD(WT.WithdrawalTypeId, 6, 0)) as ReferenceNo
                                                FROM R_WithdrawalType WT 
                                                  WHERE WithdrawalTypeId = '$Id'
      ");
      $WithdrawalTypeDetail = $query_string->row_array();
      return $WithdrawalTypeDetail;
    }

    function countWithdrawalType($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_WithdrawalType WT
                                                  WHERE WT.Name = '".$data['Name']."'
                                                  AND WT.Description = '".$data['Description']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getWithdrawalDetails($Id)
    {
      $query_string = $this->db->query("SELECT  WT.WithdrawalTypeId
                                                , W.Amount
                                                , WT.Name as WithdrawalName
                                                , WithdrawalId
                                                , W.WithdrawalTypeId
                                                , W.DateWithdrawal
                                                , CONCAT('W-', LPAD(W.WithdrawalId, 6, 0)) as ReferenceNo
                                                FROM R_Withdrawal W 
                                                  INNER JOIN R_WithdrawalType WT
                                                   ON W.WithdrawalTypeId = WT.WithdrawalTypeId
                                                  WHERE WithdrawalId = '$Id'
      ");
      $WithdrawalDetail = $query_string->row_array();
      return $WithdrawalDetail;
    }

    function countWithdrawal($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_Withdrawal W
                                                  WHERE W.Amount = '".$data['Amount']."'
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
      else if($input['tableType'] == 'AssetManagement')
      {
        $AssetDetail = $this->db->query("SELECT  SerialNumber
                                                    FROM R_AssetManagement AM
                                                      WHERE AssetManagementId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'AssetManagementId' => $input['Id']
          );
          $table = 'R_AssetManagement';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 2)
          {
            $Description = 'Re-activated ' .$AssetDetail['SerialNumber']. ' at the Asset Management'; // main log
          }
          else if($input['updateType'] == 6)
          {
            $Description = 'Deactivated ' .$AssetDetail['SerialNumber']. '  at the Asset Management'; // main log
          }
          $data2 = array(
            'Description'   => $Description,
            'CreatedBy'     => $EmployeeNumber,
            'DateCreated'   => $DateNow
          );
          $this->db->insert('R_Logs', $data2);
      }
      else if($input['tableType'] == 'Occupation')
      {
        $OccupationDetail = $this->db->query("SELECT  Name
                                                    FROM R_Occupation OCCU
                                                      WHERE OccupationId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'OccupationId' => $input['Id']
          );
          $table = 'R_Occupation';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated ' .$OccupationDetail['Name']. ' at the Occupations Module'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated ' .$OccupationDetail['Name']. '  at the Occupations Module'; // main log
          }
          $data2 = array(
            'Description'   => $Description,
            'CreatedBy'     => $EmployeeNumber,
            'DateCreated'   => $DateNow
          );
          $this->db->insert('R_Logs', $data2);
      }
      else if($input['tableType'] == 'Repayment')
      {
        $RepaymentDetail = $this->db->query("SELECT  Type
                                                    FROM R_RepaymentCycle RC
                                                      WHERE RepaymentId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'RepaymentId' => $input['Id']
          );
          $table = 'R_RepaymentCycle';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated ' .$RepaymentDetail['Type']. ' at the Repayment Cycles in System Setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated ' .$RepaymentDetail['Type']. '  at the Repayment Cycles in System Setup'; // main log
          }
          $data2 = array(
            'Description'   => $Description,
            'CreatedBy'     => $EmployeeNumber,
            'DateCreated'   => $DateNow
          );
          $this->db->insert('R_Logs', $data2);
      }
      else if($input['tableType'] == 'Capital')
      {
        $CapitalDetail = $this->db->query("SELECT  Amount
                                                    FROM R_Capital C
                                                      WHERE CapitalId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'CapitalId' => $input['Id']
          );
          $table = 'R_Capital';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated ' .$CapitalDetail['Amount']. ' at the Initial Capital in System Setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated ' .$CapitalDetail['Amount']. '  at the Initial Capital in System Setup'; // main log
          }
          $data2 = array(
            'Description'   => $Description,
            'CreatedBy'     => $EmployeeNumber,
            'DateCreated'   => $DateNow
          );
          $this->db->insert('R_Logs', $data2);
      }
      else if($input['tableType'] == 'ExpenseType')
      {
        $ExpenseTypeDetail = $this->db->query("SELECT  Name
                                                    FROM R_ExpenseType ET
                                                      WHERE ExpenseTypeId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'ExpenseTypeId' => $input['Id']
          );
          $table = 'R_ExpenseType';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated ' .$ExpenseTypeDetail['Name']. ' at the Types of Expenses in System Setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated ' .$ExpenseTypeDetail['Name']. '  at the Types of Expenses in System Setup'; // main log
          }
          $data2 = array(
            'Description'   => $Description,
            'CreatedBy'     => $EmployeeNumber,
            'DateCreated'   => $DateNow
          );
          $this->db->insert('R_Logs', $data2);
      }
      else if($input['tableType'] == 'Expense')
      {
        $ExpenseDetail = $this->db->query("SELECT  EX.ExpenseTypeId as Name
                                                    FROM R_Expense EX
                                                      WHERE ExpenseId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'ExpenseId' => $input['Id']
          );
          $table = 'R_Expense';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated ' .$ExpenseDetail['Name']. ' at the Expenses in Finance'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated ' .$ExpenseDetail['Name']. '  at the Expenses in Financ'; // main log
          }
          $data2 = array(
            'Description'   => $Description,
            'CreatedBy'     => $EmployeeNumber,
            'DateCreated'   => $DateNow
          );
          $this->db->insert('R_Logs', $data2);
      }
      else if($input['tableType'] == 'WithdrawalType')
      {
        $WithdrawalTypeDetail = $this->db->query("SELECT  Name
                                                    FROM R_WithdrawalType ET
                                                      WHERE WithdrawalTypeId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'WithdrawalTypeId' => $input['Id']
          );
          $table = 'R_WithdrawalType';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into logs
          if($input['updateType'] == 1)
          {
            $Description = 'Re-activated ' .$WithdrawalTypeDetail['Name']. ' at the Types of Withdrawal in System Setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $Description = 'Deactivated ' .$WithdrawalTypeDetail['Name']. '  at the Types of Withdrawal in System Setup'; // main log
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