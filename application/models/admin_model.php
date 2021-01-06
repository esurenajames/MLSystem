<?php
class admin_model extends CI_Model
{
    function __construct()
    {
      parent::__construct();
			$this->load->model('maintenance_model');
			$this->load->model('access');
      date_default_timezone_set('Asia/Manila');
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
                                                , DATE_FORMAT(L.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
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
                                            AND EmployeeNumber NOT IN (SELECT EmployeeNumber FROM R_UserRole)
      ");
      return $query->result();
    }

    function getReportEmployees($keyword)
    {
      $query_result = [];
      $query = $this->db->query("SELECT   CONCAT(EmployeeNumber, ' - ', FirstName, ' ', MiddleName, ' ', LastName, CASE WHEN ExtName != '' THEN CONCAT(', ', ExtName) ELSE '' END , ' - ', P.Name ) 'id'
                                          , CONCAT(EmployeeNumber, ' - ', FirstName, ' ', MiddleName, ' ', LastName, CASE WHEN ExtName != '' THEN CONCAT(', ', ExtName) ELSE '' END , ' - ', P.Name )  as 'text'
                                          FROM R_Employee EMP
                                            INNER JOIN R_Position P
                                              ON P.PositionId = EMP.PositionId
                                              WHERE (
                                                EMP.StatusId = 1
                                                OR 
                                                EMP.StatusId = 2
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
                                                FROM R_Branches
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
                                                , DATE_FORMAT(BRNCH.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                                , DATE_FORMAT(BRNCH.DateUpdated, '%b %d, %Y %h:%i %p') as DateUpdated
                                                , DATE_FORMAT(BRNCH.DateFromLease, '%b %d, %Y %h:%i %p') as DateFrom
                                                , DATE_FORMAT(BRNCH.DateToLease, '%b %d, %Y %h:%i %p') as DateTo
                                                FROM R_Branches BRNCH
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
                                                  AND ChargeType = '".$data['ChargeType']."'
                                                  AND Amount = '".$data['Amount']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getChargeDetails($Id)
    {
      $query_string = $this->db->query("SELECT  ChargeType
                                                , Name
                                                , Description
                                                , Amount
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
                                                  AND StatusId = 1
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getRequirementDetails($Id)
    {
      $query_string = $this->db->query("SELECT  Name
                                                , Description
                                                , IsMandatory
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
                                                FROM R_Disbursement
                                                  WHERE Name = '".$data['Name']."'
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
      $query_string = $this->db->query("SELECT  AM.AssetManagementId
                                                , AM.Type
                                                , AM.Name as AssetName
                                                , CONCAT('AM-', LPAD(AM.AssetManagementId, 6, 0)) as rowNumber
                                                , CONCAT(AM.Stock, '/', AM.CriticalLevel) as Stock
                                                , AM.CategoryId
                                                , AM.PurchaseValue
                                                , AM.ReplacementValue
                                                , AM.SerialNumber
                                                , AM.BoughtFrom
                                                , AM.Description
                                                , AM.BranchId
                                                , AM.Stock
                                                , AM.CriticalLevel
                                                , AssignedTo
                                                , BRNCH.Name
                                                FROM R_AssetManagement AM
                                                INNER JOIN R_Branches BRNCH
                                                  ON BRNCH.BranchId = AM.BranchId
                                                  WHERE AM.AssetManagementId = '$Id'
      ");
      $AssetManagementId = $query_string->row_array();
      return $AssetManagementId;
    }

    function countLoanStatus($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_LoanStatus
                                                  WHERE Name = '".$data['Name']."'

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
                                                FROM R_BorrowerStatus
                                                  WHERE Name = '".$data['Name']."'
                                                  AND StatusId = 1
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getBorrowerStatusDetails($Id)
    {
      $query_string = $this->db->query("SELECT  Name
                                                FROM R_BorrowerStatus 
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

    function getDisbursementDetails($Id)
    {
      $query_string = $this->db->query("SELECT  Name
                                                FROM R_Disbursement D 
                                                  WHERE DisbursementId = '$Id'
      ");
      $DisbursementDetail = $query_string->row_array();
      return $DisbursementDetail;
    }

    function countDisbursement($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM R_Disbursement D
                                                  WHERE D.Name = '".$data['Name']."'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getCapitalDetails($Id)
    {
      $query_string = $this->db->query("SELECT  C.Amount
                                                , CapitalId
                                                , CONCAT('IC-', LPAD(C.CapitalId, 6, 0)) as ReferenceNo
                                                , C.BranchId
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
                                                , DATE_FORMAT(EX.DateExpense, '%b %d, %Y %h:%i %p') as DateExpense
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
                                                  AND StatusId = 1
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
                                                , CONCAT('DEP-', LPAD(W.WithdrawalId, 6, 0)) as ReferenceNo
                                                , DATE_FORMAT(W.DateWithdrawal, '%d %b %Y') as DateWithdrawal
                                                , DATE_FORMAT(W.DateWithdrawal, '%Y-%m-%d') as rawDateWithdrawal
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
                                                  AND WithdrawalTypeId = '".$data['WithdrawalTypeId']."'
                                                  AND DateWithdrawal = '".$data['DateWithdrawal']."'
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
        $count = $this->db->query("SELECT  COUNT(*) as ifUsed
                                                    FROM t_paymentsmade
                                                      WHERE BankId = ".$input['Id']."
                                                      AND StatusId = 1
        ")->row_array();
        if($count['ifUsed'] == 0)
        {
          $Detail = $this->db->query("SELECT  BankName
                                              , CONCAT('BNK-', LPAD(BNK.BankId, 6, 0)) as ReferenceNo
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
          // admin audits finalss
            if($input['updateType'] == 1)
            {
              $auditLogsManager = 'Re-activated bank #' .$Detail['ReferenceNo']. ' at the bank setup'; // main log
              $auditAffectedEmployee = 'Re-activated bank #' .$Detail['ReferenceNo']. ' at the bank setup'; // main log
            }
            else if($input['updateType'] == 0)
            {
              $auditLogsManager = 'Deactivated bank #' .$Detail['ReferenceNo']. ' at the bank setup'; // main log
              $auditAffectedEmployee = 'Deactivated bank #' .$Detail['ReferenceNo']. ' at the bank setup'; // main log
            }
            $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
            return 1;
        }
        else
        {
          return 0;
        }
      }
      else if($input['tableType'] == 'Branch')
      {
        $count = $this->db->query("SELECT  COUNT(*) as ifUsed
                                                    FROM branch_has_employee
                                                      WHERE BranchId = ".$input['Id']."
                                                      AND StatusId != 3
        ")->row_array();
        if($count['ifUsed'] == 0)
        {
          $Detail = $this->db->query("SELECT  Name
                                                    , CONCAT('BRNCH-', LPAD(BRNCH.BranchId, 6, 0)) as ReferenceNo
                                                      FROM R_Branches BRNCH
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
            $table = 'R_Branches';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // admin audits finalss
            if($input['updateType'] == 1)
            {
              $auditLogsManager = 'Re-activated branch #' .$Detail['ReferenceNo']. ' at the branch setup'; // main log
              $auditAffectedEmployee = 'Re-activated branch #' .$Detail['ReferenceNo']. ' at the branch setup'; // main log
            }
            else if($input['updateType'] == 0)
            {
              $auditLogsManager = 'Deactivated branch #' .$Detail['ReferenceNo']. ' at the branch setup'; // main log
              $auditAffectedEmployee = 'Deactivated branch #' .$Detail['ReferenceNo']. ' at the branch setup'; // main log
            }
            $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
            return 1;
        }
        else
        {
          return 0;
        }
      }
      else if($input['tableType'] == 'Loan')
      {
        $Detail = $this->db->query("SELECT  Name
                                            , CONCAT('LT-', LPAD(L.LoanId, 6, 0)) as ReferenceNo
                                            FROM R_Loans L
                                              WHERE LoanId = ".$input['Id']."
        ")->row_array();

        $count = $this->db->query("SELECT  COUNT(*) as ifUsed
                                                    FROM t_application
                                                          WHERE LoanId = ".$input['Id']."
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
              'LoanId' => $input['Id']
            );
            $table = 'R_Loans';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // admin audits finalss
            if($input['updateType'] == 1)
            {
              $auditLogsManager = 'Re-activated loan #' .$Detail['ReferenceNo']. ' at the loan setup'; // main log
              $auditAffectedEmployee = 'Re-activated loan #' .$Detail['ReferenceNo']. ' at the loan setup'; // main log
            }
            else if($input['updateType'] == 0)
            {
              $auditLogsManager = 'Deactivated loan #' .$Detail['ReferenceNo']. ' at the loan setup'; // main log
              $auditAffectedEmployee = 'Deactivated loan #' .$Detail['ReferenceNo']. ' at the loan setup'; // main log
            }
            $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        }
      }
      else if($input['tableType'] == 'Charge')
      {
        $Detail = $this->db->query("SELECT  Name
                                                  , CONCAT('CHRG-', LPAD(CH.ChargeId, 6, 0)) as ReferenceNo
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
        // admin audits finalss
          if($input['updateType'] == 1)
          {
            $auditLogsManager = 'Re-activated charge #' .$Detail['ReferenceNo']. ' at the charges setup'; // main log
            $auditAffectedEmployee = 'Re-activated charge #' .$Detail['ReferenceNo']. ' at the charges setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $auditLogsManager = 'Deactivated charge #' .$Detail['ReferenceNo']. ' at the charges setup'; // main log
            $auditAffectedEmployee = 'Deactivated charge #' .$Detail['ReferenceNo']. ' at the charges setup'; // main log
          }
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      }
      else if($input['tableType'] == 'Education')
      {
        $Detail = $this->db->query("SELECT  Name
                                            , CONCAT('EDU-', LPAD(EDU.EducationId, 6, 0)) as ReferenceNo
                                              FROM R_Education EDU
                                                WHERE EducationId= ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'EducationId' => $input['Id']
          );
          $table = 'R_Education';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // admin audits finalss
          if($input['updateType'] == 1)
          {
            $auditLogsManager = 'Re-activated education #' .$Detail['ReferenceNo']. ' at the education setup'; // main log
            $auditAffectedEmployee = 'Re-activated education #' .$Detail['ReferenceNo']. ' at the education setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $auditLogsManager = 'Deactivated education #' .$Detail['ReferenceNo']. ' at the education setup'; // main log
            $auditAffectedEmployee = 'Deactivated education #' .$Detail['ReferenceNo']. ' at the education setup'; // main log
          }
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      }
      else if($input['tableType'] == 'Requirement')
      {
        $Detail = $this->db->query("SELECT  Name
                                            , CONCAT('REQ-', LPAD(R.RequirementId, 6, 0)) as ReferenceNo
                                            FROM R_Requirements R
                                              WHERE RequirementId = ".$input['Id']."
        ")->row_array();
        $count = $this->db->query("SELECT  COUNT(*) as ifUsed
                                                    FROM application_has_requirement
                                                      WHERE RequirementId = ".$input['Id']."
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
              'RequirementId' => $input['Id']
            );
            $table = 'R_Requirements';
            $this->maintenance_model->updateFunction1($set, $condition, $table);

          // admin audits finalss
            if($input['updateType'] == 1)
            {
              $auditLogsManager = 'Re-activated requirement #' .$Detail['ReferenceNo']. ' at the requirement setup'; // main log
              $auditAffectedEmployee = 'Re-activated requirement #' .$Detail['ReferenceNo']. ' at the requirement setup'; // main log
            }
            else if($input['updateType'] == 0)
            {
              $auditLogsManager = 'Deactivated requirement #' .$Detail['ReferenceNo']. ' at the requirement setup'; // main log
              $auditAffectedEmployee = 'Deactivated requirement #' .$Detail['ReferenceNo']. ' at the requirement setup'; // main log
            }
            $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        }
      }
      else if($input['tableType'] == 'Position')
      {
        $count = $this->db->query("SELECT  COUNT(*) as ifUsed
                                                    FROM R_Employee
                                                          WHERE PositionId = ".$input['Id']."
        ")->row_array();
        if($count['ifUsed'] == 0)
        {
          $PositionDetail = $this->db->query("SELECT  Name
                                                      , CONCAT('POS-', LPAD(PS.PositionId, 6, 0)) as ReferenceNo
                                                      FROM R_Position PS
                                                        WHERE PositionId = ".$input['Id']."
          ")->row_array();

          // update status
            $set = array(
              'StatusId' => $input['updateType'],
              'UpdatedBy' => $EmployeeNumber,
            );
            $condition = array(
              'PositionId' => $input['Id']
            );
            $table = 'R_Position';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // admin audits finalss
            if($input['updateType'] == 1)
            {
              $auditLogsManager = 'Re-activated position #' .$PositionDetail['ReferenceNo']. ' at the positions setup'; // main log
              $auditAffectedEmployee = 'Re-activated position #' .$PositionDetail['ReferenceNo']. ' at the positions setup'; // main log
            }
            else if($input['updateType'] == 0)
            {
              $auditLogsManager = 'Deactivated position #' .$PositionDetail['ReferenceNo']. ' at the positions setup'; // main log
              $auditAffectedEmployee = 'Deactivated position #' .$PositionDetail['ReferenceNo']. ' at the positions setup'; // main log
            }
            $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
          return 1;
        }
        else
        {
          return 0;
        }
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
        $Detail = $this->db->query("SELECT  Name
                                            , CONCAT('PP-', LPAD(PP.PurposeId, 6, 0)) as ReferenceNo
                                              FROM R_Purpose PP
                                                WHERE PurposeId = ".$input['Id']."
        ")->row_array();


        $count = $this->db->query("SELECT  COUNT(*) as ifUsed
                                                    FROM t_application
                                                          WHERE PurposeId = ".$input['Id']."
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
              'PurposeId' => $input['Id']
            );
            $table = 'R_Purpose';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // admin audits finalss
            if($input['updateType'] == 1)
            {
              $auditLogsManager = 'Re-activated purpose #' .$Detail['ReferenceNo']. ' at the purpose setup'; // main log
              $auditAffectedEmployee = 'Re-activated purpose #' .$Detail['ReferenceNo']. ' at the purpose setup'; // main log
            }
            else if($input['updateType'] == 0)
            {
              $auditLogsManager = 'Deactivated purpose #' .$Detail['ReferenceNo']. ' at the purpose setup'; // main log
              $auditAffectedEmployee = 'Deactivated purpose #' .$Detail['ReferenceNo']. ' at the purpose setup'; // main log
            }
            $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        }
      }
      else if($input['tableType'] == 'Method')
      {
        $Detail = $this->db->query("SELECT  Name
                                                  , CONCAT('MP-', LPAD(M.DisbursementId, 6, 0)) as ReferenceNo
                                                    FROM R_Disbursement M
                                                      WHERE DisbursementId = ".$input['Id']."
        ")->row_array();

        $count = $this->db->query("SELECT  COUNT(*) as ifUsed
                                                    FROM t_paymentsmade
                                                      WHERE PaymentMethod = ".$input['Id']."
                                                      AND StatusId = 1
        ")->row_array();
        if($count['ifUsed'] == 0)
        {
          // update status
            $set = array(
              'StatusId' => $input['updateType'],
            );
            $condition = array(
              'DisbursementId' => $input['Id']
            );
            $table = 'R_Disbursement';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // admin audits finalss
            if($input['updateType'] == 1)
            {
              $auditLogsManager = 'Re-activated method of payment #' .$Detail['ReferenceNo']. ' at the method of payment setup'; // main log
              $auditAffectedEmployee = 'Re-activated method of payment #' .$Detail['ReferenceNo']. ' at the method of payment setup'; // main log
            }
            else if($input['updateType'] == 0)
            {
              $auditLogsManager = 'Deactivated method of payment #' .$Detail['ReferenceNo']. ' at the method of payment setup'; // main log
              $auditAffectedEmployee = 'Deactivated method of payment #' .$Detail['ReferenceNo']. ' at the method of payment setup'; // main log
            }
            $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        }
      }
      else if($input['tableType'] == 'Category')
      {
        $Detail = $this->db->query("SELECT  Name
                                                  , CONCAT('CAT-', LPAD(A.CategoryId, 6, 0)) as ReferenceNo
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
        // admin audits finalss
          if($input['updateType'] == 1)
          {
            $auditLogsManager = 'Re-activated asset category #' .$Detail['ReferenceNo']. ' at the asset category setup'; // main log
            $auditAffectedEmployee = 'Re-activated asset category #' .$Detail['ReferenceNo']. ' at the asset category setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $auditLogsManager = 'Deactivated asset category #' .$Detail['ReferenceNo']. ' at the asset category setup'; // main log
            $auditAffectedEmployee = 'Deactivated asset category #' .$Detail['ReferenceNo']. ' at the asset category setup'; // main log
          }
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      }
      else if($input['tableType'] == 'LoanStatus')
      {
        $Detail = $this->db->query("SELECT  Name
                                            , CONCAT('ALS-', LPAD(LS.LoanStatusId, 6, 0)) as ReferenceNo
                                            FROM Application_has_Status LS
                                              WHERE LoanStatusId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
          );
          $condition = array(
            'LoanStatusId' => $input['Id']
          );
          $table = 'Application_has_Status';
          $this->maintenance_model->updateFunction1($set, $condition, $table);

        // admin audits finalss
          if($input['updateType'] == 1)
          {
            $auditLogsManager = 'Re-activated loan status #' .$Detail['ReferenceNo']. ' at the loan status setup'; // main log
            $auditAffectedEmployee = 'Re-activated loan status #' .$Detail['ReferenceNo']. ' at the loan status setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $auditLogsManager = 'Deactivated loan status #' .$Detail['ReferenceNo']. ' at the loan status setup'; // main log
            $auditAffectedEmployee = 'Deactivated loan status #' .$Detail['ReferenceNo']. ' at the loan status setup'; // main log
          }
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      }
      else if($input['tableType'] == 'BorrowerStatus')
      {
        $Detail = $this->db->query("SELECT  Name
                                            , CONCAT('BST-', LPAD(BS.BorrowerStatusId, 6, 0)) as ReferenceNo
                                            FROM R_BorrowerStatus BS
                                              WHERE BorrowerStatusId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
          );
          $condition = array(
            'BorrowerStatusId' => $input['Id']
          );
          $table = 'R_BorrowerStatus';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // admin audits finalss
          if($input['updateType'] == 1)
          {
            $auditLogsManager = 'Re-activated borrower status #' .$Detail['ReferenceNo']. ' at the borrower status setup'; // main log
            $auditAffectedEmployee = 'Re-activated borrower status #' .$Detail['ReferenceNo']. ' at the borrower status setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $auditLogsManager = 'Deactivated borrower status #' .$Detail['ReferenceNo']. ' at the borrower status setup'; // main log
            $auditAffectedEmployee = 'Deactivated borrower status #' .$Detail['ReferenceNo']. ' at the borrower status setup'; // main log
          }
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      }
      else if($input['tableType'] == 'Industry')
      {
        $Detail = $this->db->query("SELECT  Name
                                            , CONCAT('IND-', LPAD(IndustryId, 6, 0)) as ReferenceNo
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
        // admin audits finalss
          if($input['updateType'] == 1)
          {
            $auditLogsManager = 'Re-activated industry #' .$Detail['ReferenceNo']. ' at the industry setup'; // main log
            $auditAffectedEmployee = 'Re-activated industry #' .$Detail['ReferenceNo']. ' at the industry setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $auditLogsManager = 'Deactivated industry #' .$Detail['ReferenceNo']. ' at the industry setup'; // main log
            $auditAffectedEmployee = 'Deactivated industry #' .$Detail['ReferenceNo']. ' at the industry setup'; // main log
          }
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
          return 1;
      }
      else if($input['tableType'] == 'AssetManagement')
      {
        $Detail = $this->db->query("SELECT  SerialNumber
                                                  , CONCAT('AM-', LPAD(AM.AssetManagementId, 6, 0)) as ReferenceNo
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

        // admin audits finalss
          if($input['updateType'] == 2)
          {
            $auditLogsManager = 'Re-activated asset #' .$Detail['ReferenceNo']. ' at the asset management module'; // main log
            $auditAffectedEmployee = 'Re-activated asset #' .$Detail['ReferenceNo']. ' at the asset management module'; // main log
          }
          else if($input['updateType'] == 6)
          {
            $auditLogsManager = 'Deactivated asset #' .$Detail['ReferenceNo']. ' at the asset management module'; // main log
            $auditAffectedEmployee = 'Deactivated asset #' .$Detail['ReferenceNo']. ' at the asset management module'; // main log
          }
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      }
      else if($input['tableType'] == 'Occupation')
      {
        $count = $this->db->query("SELECT  COUNT(*) as ifUsed
                                                    FROM borrower_has_employer
                                                      WHERE PositionId = ".$input['Id']."
                                                      AND StatusId = 1
        ")->row_array();
        if($count['ifUsed'] == 0)
        {
          $OccupationDetail = $this->db->query("SELECT  Name
                                                      , CONCAT('OCC-', LPAD(OCCU.OccupationId, 6, 0)) as ReferenceNo
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
          // admin audits finalss
            if($input['updateType'] == 1)
            {
              $auditLogsManager = 'Re-activated occupation #' .$OccupationDetail['ReferenceNo']. ' at the occupations setup'; // main log
              $auditAffectedEmployee = 'Re-activated occupation #' .$OccupationDetail['ReferenceNo']. ' at the occupations setup'; // main log
            }
            else if($input['updateType'] == 0)
            {
              $auditLogsManager = 'Deactivated occupation #' .$OccupationDetail['ReferenceNo']. ' at the occupations setup'; // main log
              $auditAffectedEmployee = 'Deactivated occupation #' .$OccupationDetail['ReferenceNo']. ' at the occupations setup'; // main log
            }
            $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
            return 1;
        }
        else
        {
          return 0;
        }
      }
      else if($input['tableType'] == 'Repayment')
      {
        $Detail = $this->db->query("SELECT  Type
                                            , CONCAT('RC-', LPAD(RC.RepaymentId, 6, 0)) as ReferenceNo
                                              FROM R_RepaymentCycle RC
                                                WHERE RepaymentId = ".$input['Id']."
        ")->row_array();


        $count = $this->db->query("SELECT  COUNT(*) as ifUsed
                                                    FROM t_application
                                                      WHERE RepaymentId = ".$input['Id']."
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
              'RepaymentId' => $input['Id']
            );
            $table = 'R_RepaymentCycle';
            $this->maintenance_model->updateFunction1($set, $condition, $table);

          // admin audits finalss
            if($input['updateType'] == 1)
            {
              $auditLogsManager = 'Re-activated repayment cycle #' .$Detail['ReferenceNo']. ' at the repayment cycle setup'; // main log
              $auditAffectedEmployee = 'Re-activated repayment cycle #' .$Detail['ReferenceNo']. ' at the repayment cycle setup'; // main log
            }
            else if($input['updateType'] == 0)
            {
              $auditLogsManager = 'Deactivated repayment cycle #' .$Detail['ReferenceNo']. ' at the repayment cycle setup'; // main log
              $auditAffectedEmployee = 'Deactivated repayment cycle #' .$Detail['ReferenceNo']. ' at the repayment cycle setup'; // main log
            }
            $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
            return 1;
        }
        else
        {
          return 0;
        }
      }
      else if($input['tableType'] == 'Disbursement')
      {
        $Detail = $this->db->query("SELECT  Name
                                            , CONCAT('DIS-', LPAD(RC.DisbursementId, 6, 0)) as ReferenceNo
                                            FROM R_Disbursement RC
                                              WHERE DisbursementId = ".$input['Id']."
        ")->row_array();

        $count = $this->db->query("SELECT  COUNT(*) as ifUsed
                                                    FROM application_has_disbursement
                                                      WHERE DisbursedBy = ".$input['Id']."
                                                      AND StatusId = 1
        ")->row_array();
        if($count['ifUsed'] == 0)
        {
          // update status
            $set = array(
              'StatusId' => $input['updateType'],
            );
            $condition = array(
              'DisbursementId' => $input['Id']
            );
            $table = 'R_Disbursement';
            $this->maintenance_model->updateFunction1($set, $condition, $table);

          // admin audits finalss
            if($input['updateType'] == 1)
            {
              $auditLogsManager = 'Re-activated disbursement type #' .$Detail['ReferenceNo']. ' at the disbursements setup'; // main log
              $auditAffectedEmployee = 'Re-activated disbursement type #' .$Detail['ReferenceNo']. ' at the disbursements setup'; // main log
            }
            else if($input['updateType'] == 0)
            {
              $auditLogsManager = 'Deactivated disbursement type #' .$Detail['ReferenceNo']. ' at the disbursements setup'; // main log
              $auditAffectedEmployee = 'Deactivated disbursement type #' .$Detail['ReferenceNo']. ' at the disbursements setup'; // main log
            }
            $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
        }
      }
      else if($input['tableType'] == 'Capital')
      {
        $Detail = $this->db->query("SELECT  Amount
                                            , CONCAT('CAP-', LPAD(C.CapitalId, 6, 0)) as ReferenceNo
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

        // admin audits finalss
          if($input['updateType'] == 1)
          {
            $auditLogsManager = 'Re-activated capital #' .$Detail['ReferenceNo']. ' at the initial capital setup'; // main log
            $auditAffectedEmployee = 'Re-activated capital #' .$Detail['ReferenceNo']. ' at the initial capital setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $auditLogsManager = 'Deactivated capital #' .$Detail['ReferenceNo']. ' at the initial capital setup'; // main log
            $auditAffectedEmployee = 'Deactivated capital #' .$Detail['ReferenceNo']. ' at the initial capital setup'; // main log
          }
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      }
      else if($input['tableType'] == 'ExpenseType')
      {
        $Detail = $this->db->query("SELECT  Name
                                            , CONCAT('EXT-', LPAD(ET.ExpenseTypeId, 6, 0)) as ReferenceNo
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

        // admin audits finalss
          if($input['updateType'] == 1)
          {
            $auditLogsManager = 'Re-activated expense type #' .$Detail['ReferenceNo']. ' at the types of expenses setup'; // main log
            $auditAffectedEmployee = 'Re-activated expense type #' .$Detail['ReferenceNo']. ' at the types of expenses setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $auditLogsManager = 'Deactivated expense type #' .$Detail['ReferenceNo']. ' at the types of expenses setup'; // main log
            $auditAffectedEmployee = 'Deactivated expense type #' .$Detail['ReferenceNo']. ' at the types of expenses setup'; // main log
          }
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      }
      else if($input['tableType'] == 'Expense')
      {
        $Detail = $this->db->query("SELECT  EX.ExpenseTypeId as Name
                                                , CONCAT('EXP-', LPAD(EX.ExpenseId, 6, 0)) as ReferenceNo
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
        // admin audits finalss
          if($input['updateType'] == 1)
          {
            $auditLogsManager = 'Re-activated expense #' .$Detail['ReferenceNo']. ' at the expense finance management module'; // main log
            $auditAffectedEmployee = 'Re-activated expense #' .$Detail['ReferenceNo']. ' at the expense finance management module'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $auditLogsManager = 'Deactivated expense #' .$Detail['ReferenceNo']. ' at the expense finance management module'; // main log
            $auditAffectedEmployee = 'Deactivated expense #' .$Detail['ReferenceNo']. ' at the expense finance management module'; // main log
          }
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      }
      else if($input['tableType'] == 'WithdrawalType')
      {
        $Detail = $this->db->query("SELECT  Name
                                            , CONCAT('DET-', LPAD(ET.WithdrawalTypeId, 6, 0)) as ReferenceNo
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
        // admin audits finalss
          if($input['updateType'] == 1)
          {
            $auditLogsManager = 'Re-activated deposit type #' .$Detail['ReferenceNo']. ' at the types of deposit setup'; // main log
            $auditAffectedEmployee = 'Re-activated deposit type #' .$Detail['ReferenceNo']. ' at the types of deposit setup'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $auditLogsManager = 'Deactivated deposit type #' .$Detail['ReferenceNo']. ' at the types of deposit setup'; // main log
            $auditAffectedEmployee = 'Deactivated deposit type #' .$Detail['ReferenceNo']. ' at the types of deposit setup'; // main log
          }
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      }
      else if($input['tableType'] == 'Withdrawal')
      {
        $Detail = $this->db->query("SELECT  W.WithdrawalId as Name
                                                , CONCAT('DEP-', LPAD(W.WithdrawalId, 6, 0)) as ReferenceNo
                                                FROM R_Withdrawal W
                                                  WHERE WithdrawalId = ".$input['Id']."
        ")->row_array();

        // update status
          $set = array(
            'StatusId' => $input['updateType'],
            'UpdatedBy' => $EmployeeNumber,
            'DateUpdated' => $DateNow,
          );
          $condition = array(
            'WithdrawalId' => $input['Id']
          );
          $table = 'R_Withdrawal';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        // admin audits finalss
          if($input['updateType'] == 1)
          {
            $auditLogsManager = 'Re-activated deposit #' .$Detail['ReferenceNo']. ' at the deposit finance management'; // main log
            $auditAffectedEmployee = 'Re-activated deposit #' .$Detail['ReferenceNo']. ' at the deposit finance management'; // main log
          }
          else if($input['updateType'] == 0)
          {
            $auditLogsManager = 'Deactivated deposit #' .$Detail['ReferenceNo']. ' at the deposit finance management'; // main log
            $auditAffectedEmployee = 'Deactivated deposit #' .$Detail['ReferenceNo']. ' at the deposit finance management'; // main log
          }
          $this->finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, null, null, null, null);
      }
    }

    function finalAuditFunction($auditLogsManager, $auditAffectedEmployee, $ManagerId, $AffectedEmployeeNumber, $auditLoanDets, $ApplicationId, $independentTable, $independentColumn)
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

      if($auditLoanDets != null)
      {
        $insertApplicationLog = array(
          'Description'       => $auditLoanDets
          , ''.$independentColumn.''   => $ApplicationId
          , 'CreatedBy'       => $CreatedBy
        );
        $auditLoanApplicationTable = $independentTable;
        $this->maintenance_model->insertFunction($insertApplicationLog, $auditLoanApplicationTable);
      }
    }

    function getLogs()
    {
      $query_string = $this->db->query("SELECT  L.Description
                                                , L.Remarks
                                                , DATE_FORMAT(L.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                                , CONCAT(FirstName, ' ', MiddleName, ' ', LastName, CASE WHEN ExtName != '' THEN CONCAT(', ', ExtName) ELSE '' END ) as Name
                                                FROM R_Logs L
                                                  INNER JOIN R_Employee EMP
                                                    ON EMP.EmployeeNumber = L.CreatedBy
                                                      ORDER BY DateCreated DESC
      ");
      $data = $query_string->result_array();
      return $data;
    }
    



}