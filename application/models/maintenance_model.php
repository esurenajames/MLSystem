<?php
class maintenance_model extends CI_Model
{
    function __construct()
    {
      parent::__construct();
			$this->load->model('maintenance_model');
			$this->load->model('access');
    }

    function getLoggedInRoles()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT DISTINCT GROUP_CONCAT(RoleId) as Roles
                                                FROM R_UserRole
                                                  WHERE EmployeeNumber = '".$EmployeeNumber."'
                                                  AND StatusId = 1

      ");

      return $query->row_array();
    }

    function IDCategory()
    {
      $query = $this->db->query("SELECT Name
                                        , RequirementId as ID 
                                        FROM r_requirements
                                          WHERE StatusId = 1
      ");
      $output = '<option disabled selected value="">Supporting Documents</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->ID.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getAllUsers()
    {
      $query_string = $this->db->query("SELECT 	DISTINCT UR.EmployeeNumber
      																					, UR.StatusId
                                                , IsNew
                                                , DATE_FORMAT(UR.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(UR.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                , UserRoleId
                                                , EmployeeId
      																					FROM R_UserRole UR
                                                  INNER JOIN R_Employee EMP
                                                    ON EMP.EmployeeNumber = UR.EmployeeNumber
                                                  WHERE UR.EmployeeNumber != '000000'
			");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllEmployees()
    {
      $query_string = $this->db->query("SELECT 
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllBanks()
    {
      $query_string = $this->db->query("SELECT BNK.BankName as BankName
                                                , BankId
                                                , BNK.Description
                                                , BNK.AccountNumber
                                                , BNK.CreatedBy
                                                , BNK.StatusId
                                                , DATE_FORMAT(BNK.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(BNK.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                FROM R_Bank BNK
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getTotalBorrower()
    {
      $BranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT DISTINCT COUNT(DISTINCT BorrowerId) as Total 
                                              FROM r_borrowers
                                                WHERE BranchId = $BranchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalInterestCollected()
    {
      $query_string = $this->db->query("SELECT DISTINCT COALESCE(SUM(DISTINCT PM.Amount), 0) as Total
                                                FROM t_paymentsmade PM
                                                    WHERE PM.IsInterest = 1
                                                    AND DATE_FORMAT(DateCreated, '%Y-%m-%d')  = DATE_FORMAT(NOW(), '%Y-%m-%d')
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalExpense()
    {
      $query_string = $this->db->query("SELECT  FORMAT(COALESCE(SUM(Amount), 0), 2) as Total
                                                FROM r_expense
                                                    WHERE DATE_FORMAT(DateCreated, '%Y-%m-%d')  = DATE_FORMAT(NOW(), '%Y-%m-%d')
                                                    AND StatusId = 1
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getDailyIncome()
    {
      $query_string = $this->db->query("SELECT  FORMAT(COALESCE(SUM(Amount), 0), 2) as Total
                                                FROM t_paymentsmade
                                                    WHERE DATE_FORMAT(DateCreated, '%Y-%m-%d')  = DATE_FORMAT(NOW(), '%Y-%m-%d')
                                                    AND StatusId = 1
                                                    AND Amount > 0
      ");
      $data = $query_string->row_array();
      return $data;
    }


    function getTransactions()
    {
      $query_string = $this->db->query("SELECT  COUNT(*) as Total
                                                FROM application_has_notifications
                                                    WHERE DATE_FORMAT(DateCreated, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getAllBranches()
    {
      $query_string = $this->db->query("SELECT BRNCH.Name as BranchName
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
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllLoans()
    {
      $query_string = $this->db->query("SELECT L.Name as LoanName
                                                , LoanId
                                                , L.Description
                                                , L.CreatedBy
                                                , L.StatusId
                                                , DATE_FORMAT(L.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(L.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                FROM R_Loans L
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllCharges()
    {
      $query_string = $this->db->query("SELECT CH.Name as ChargeName
                                                , ChargeType
                                                , CASE 
                                                    WHEN ChargeType = 0
                                                    THEN 'Fixed Amount'
                                                    ELSE 'Percentage'
                                                  END as ChargeType
                                                , ChargeId
                                                , CH.Description
                                                , CH.Amount
                                                , CH.CreatedBy
                                                , CH.StatusId
                                                , DATE_FORMAT(CH.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(CH.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                FROM R_Charges CH
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllOccupation()
    {
      $query_string = $this->db->query("SELECT OCCU.Name as OccupationName
                                                , OCCU.Description
                                                , OCCU.CreatedBy
                                                , OCCU.StatusId
                                                , OccupationId
                                                , DATE_FORMAT(OCCU.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(OCCU.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                FROM R_Occupation OCCU
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllRepayments()
    {
      $query_string = $this->db->query("SELECT RC.Type as RepaymentName
                                                , RepaymentId
                                                , RC.CreatedBy
                                                , RC.StatusId
                                                , DATE_FORMAT(RC.DateCreated, '%d %b %Y %r') as DateCreated
                                                FROM R_RepaymentCycle RC
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllDisbursements()
    {
      $query_string = $this->db->query("SELECT DB.Name as DisbursementName
                                                , DisbursementId
                                                , DB.CreatedBy
                                                , DB.StatusId
                                                , DATE_FORMAT(DB.DateCreated, '%d %b %Y %r') as DateCreated
                                                FROM R_Disbursement DB
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllOptional()
    {
      $query_string = $this->db->query("SELECT OC.Name as OptionalName
                                                , OptionalId
                                                , OC.Description
                                                , OC.CreatedBy
                                                , OC.StatusId
                                                , DATE_FORMAT(OC.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(OC.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                FROM R_OptionalCharges OC
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllRequirements()
    {
      $query_string = $this->db->query("SELECT RQ.Name as RequirementName
                                                , RequirementId
                                                , RQ.IsMandatory
                                                , RQ.Description
                                                , RQ.CreatedBy
                                                , RQ.StatusId
                                                , DATE_FORMAT(RQ.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(RQ.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                FROM R_Requirements RQ
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllPositions()
    {
      $query_string = $this->db->query("SELECT PS.Name as PositionName
                                                , PositionId
                                                , PS.Description
                                                , PS.CreatedBy
                                                , PS.StatusId
                                                , DATE_FORMAT(PS.DateCreated, '%d %b %Y %r') as DateCreated
                                                FROM R_Position PS
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllPurposes()
    {
      $query_string = $this->db->query("SELECT PP.Name as Purpose
                                                , PurposeId
                                                , PP.Description
                                                , PP.CreatedBy
                                                , PP.StatusId
                                                , DATE_FORMAT(PP.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(PP.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                FROM R_Purpose PP
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllMethods()
    {
      $query_string = $this->db->query("SELECT M.Name as Method
                                                , MethodId
                                                , M.Description
                                                , M.CreatedBy
                                                , M.StatusId
                                                , DATE_FORMAT(M.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(M.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                FROM R_MethodOfPayment M
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllCategories()
    {
      $query_string = $this->db->query("SELECT A.Name as Category
                                                , CategoryId
                                                , A.Description
                                                , A.CreatedBy
                                                , A.StatusId
                                                , DATE_FORMAT(A.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(A.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                FROM R_Category A
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllAssets()
    {
      $query_string = $this->db->query("SELECT PurchaseValue 
                                                , AssetManagementId
                                                , ReplacementValue
                                                , AM.Type
                                                , CASE 
                                                    WHEN AM.Type = 1
                                                    THEN 'Tangible'
                                                    ELSE 'Intangible'
                                                  END as Type
                                                , AM.Description
                                                , CONCAT (AM.Stock, '/' , AM.CriticalLevel) as Stock
                                                , AM.Name as AssetName
                                                , AM.BoughtFrom
                                                , AM.CategoryId
                                                , AM.SerialNumber
                                                , AM.StatusId
                                                , AM.Name
                                                , BRNCH.Name as BranchName
                                                , AM.BranchId
                                                , AM.CreatedBy
                                                , C.Name as CategoryName
                                                , DATE_FORMAT(AM.DateCreated, '%d %b %Y %r') as DateCreated
                                                FROM R_AssetManagement AM
                                                 INNER JOIN R_Category C
                                                  ON C.CategoryId = AM.CategoryId
                                                    INNER JOIN R_Branch BRNCH
                                                      ON BRNCH.BranchId = AM.BranchId
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllLoanStatus()
    {
      $query_string = $this->db->query("SELECT LS.Name as LoanStatus
                                                , LoanStatusId
                                                , LS.Description
                                                , LS.CreatedBy
                                                , LS.StatusId
                                                , DATE_FORMAT(LS.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(LS.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                FROM R_LoanStatus LS
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllBorrowerStatus()
    {
      $query_string = $this->db->query("SELECT BS.Name as BorrowerStatus
                                                , BorrowerStatusId
                                                , BS.Description
                                                , BS.CreatedBy
                                                , BS.StatusId
                                                , DATE_FORMAT(BS.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(BS.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                FROM R_Borrower_has_Status BS
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllIndustry()
    {
      $query_string = $this->db->query("SELECT I.Name as Industry
                                                , IndustryId
                                                , I.Description
                                                , I.CreatedBy
                                                , I.StatusId
                                                , DATE_FORMAT(I.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(I.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                FROM R_Industry I
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllEducation()
    {
      $query_string = $this->db->query("SELECT EDU.Name as EducationName
                                                , EducationId
                                                , EDU.Description
                                                , EDU.CreatedBy
                                                , EDU.StatusId
                                                , DATE_FORMAT(EDU.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(EDU.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                FROM R_Education EDU
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllCapital()
    {
      $query_string = $this->db->query("SELECT C.Amount
                                                , CONCAT('IC-', LPAD(C.CapitalId, 6, 0)) as ReferenceNo
                                                , CapitalId
                                                , C.CreatedBy
                                                , C.StatusId
                                                , DATE_FORMAT(C.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(C.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                FROM R_Capital C
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllType()
    {
      $query_string = $this->db->query("SELECT ET.Name as ExpenseType
                                                , CONCAT('ET-', LPAD(ET.ExpenseTypeId, 6, 0)) as ReferenceNo
                                                , ExpenseTypeId
                                                , ET.CreatedBy
                                                , ET.Description
                                                , ET.StatusId
                                                , DATE_FORMAT(ET.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(ET.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                FROM R_ExpenseType ET
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllExpenses()
    {
      $query_string = $this->db->query("SELECT ET.Name as Expense
                                                , CONCAT('EX-', LPAD(EX.ExpenseId, 6, 0)) as ReferenceNo
                                                , EX.ExpenseTypeId
                                                , EX.ExpenseId
                                                , EX.Amount
                                                , EX.CreatedBy
                                                , EX.StatusId
                                                , EX.DateExpense
                                                , DATE_FORMAT(EX.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(EX.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                FROM R_Expense EX
                                                  INNER JOIN R_ExpenseType ET
                                                    ON ET.ExpenseTypeId = EX.ExpenseTypeId
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllWithdrawalType()
    {
      $query_string = $this->db->query("SELECT WT.Name as WithdrawalType
                                                , CONCAT('WT-', LPAD(WT.WithdrawalTypeId, 6, 0)) as ReferenceNo
                                                , WithdrawalTypeId
                                                , WT.CreatedBy
                                                , WT.Description
                                                , WT.StatusId
                                                , DATE_FORMAT(WT.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(WT.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                FROM R_WithdrawalType WT
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllWithdrawals()
    {
      $query_string = $this->db->query("SELECT WT.Name as Withdrawal
                                                , CONCAT('W-', LPAD(W.WithdrawalId, 6, 0)) as ReferenceNo
                                                , W.WithdrawalTypeId
                                                , W.WithdrawalId
                                                , W.Amount
                                                , W.CreatedBy
                                                , W.StatusId
                                                , W.DateWithdrawal
                                                , DATE_FORMAT(W.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(W.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                FROM R_Withdrawal W
                                                  INNER JOIN R_WithdrawalType WT
                                                    ON W.WithdrawalTypeId = WT.WithdrawalTypeId
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllBorrowers()
    {
      $query_string = $this->db->query("SELECT CONCAT(FirstName, ' ', MiddleName, ' ', LastName, CASE WHEN ExtName != '' THEN CONCAT(', ', ExtName) ELSE '' END ) as Name
                                                , B.StatusId
                                                , B.Sex
                                                , B.Dependents
                                                , DATE_FORMAT(B.DateCreated, '%d %b %Y %r') as DateCreated
                                                , DATE_FORMAT(B.DateUpdated, '%d %b %Y %r') as DateUpdated
                                                FROM R_Borrowers B
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAllHistoryLogs()
    {
      $query_string = $this->db->query("SELECT  LogId
                                                , LG.Description
                                                , LG.CreatedBy
                                                , DATE_FORMAT(LG.DateCreated, '%d %b %Y %r') as DateCreated
                                                FROM R_Logs LG
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getUserCreated($EmployeeNumber)
    {
      $query = $this->db->query("SELECT CONCAT(LastName, ', ', FirstName) as Name 
                                        FROM R_Employee 
                                        WHERE EmployeeNumber = '$EmployeeNumber' 
                                        LIMIT 1
      ");
      $data = $query->row_array();
      if($data['Name']!= null)
      {
        return $data['Name'];
      }
      else
      {
        return "--N/A--";
      }
    }

    function insertFunction($data, $table)
    {
      $this->db->insert($table, $data);
    }

    function insertAdminLog($data)
    {
      $this->db->insert('R_Logs', $data);
    }

    function updateFunction1($set, $condition, $table)
    {
      $this->db->where($condition);
      $this->db->update($table, $set);
    }

    function getGeneratedUserRoleId()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT  MAX(UserRoleId) as UserRoleId
                                FROM R_UserRole
                                  WHERE CreatedBy = '$EmployeeNumber'
      ");

      $data = $query->row_array();
      return $data;
    }

    function getCreatedUserDetails($input)
    {
      $auditquery = $this->db->query("SELECT  CONCAT(FirstName, ' ', MiddleName, ' ', LastName, CASE WHEN ExtName != '' THEN CONCAT(', ', ExtName) ELSE '' END ) as Name
                                              , LOWER(R.Description) as Description
                                              FROM r_userrole UR
                                                INNER JOIN r_employee EMP
                                                  ON EMP.EmployeeNumber = UR.EmployeeNumber
                                                INNER JOIN R_role R
                                                  ON R.RoleId = UR.RoleId
                                                    WHERE UR.UserRoleId = ".$input['UserRoleId']."
                                                    LIMIT 1
      ");

      $data = $auditquery->row_array();
      return $data;
    }

    function getGeneratedPassword($input)
    {
      $auditquery = $this->db->query("SELECT Password
                                              , EmployeeNumber
                                              FROM r_userrole UR
                                                    WHERE UR.UserRoleId = ".$input['UserRoleId']."
                                                    LIMIT 1
      ");

      $data = $auditquery->row_array();
      return $data;
    }

    function passwordValidity()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT IsNew
                                        FROM r_userrole
                                          WHERE EmployeeNumber = '$EmployeeNumber'
      ");

      $data = $query->row_array();
      return $data;
    }

    function getPassword()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT CAST(Password AS CHAR(10000) CHARACTER SET utf8) as Password 
                                        FROM R_UserRole
                                          WHERE EmployeeNumber = '$EmployeeNumber'
      ");

      $data = $query->row_array();
      return $data;
    }

    function getNewGeneratedId($input)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT  MAX(".$input['column'].") as ".$input['column']."
                                        FROM ".$input['table']."
                                          WHERE CreatedBy = '$EmployeeNumber'
      ");


      $data = $query->row_array();
      return $data;
    }

    function getSex()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT SexId
                                        , Name
                                          FROM R_Sex
                                            ORDER BY Name ASC
      ");
      $output = '<option selected value="">Select Gender</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->SexId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getBranches()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT BranchId
                                        , Name
                                          FROM R_Branch
                                          WHERE StatusId = 1
                                            ORDER BY Name ASC
      ");
      $output = '<option selected value="">Select Branch</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->BranchId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getLoanTypes()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT LoanId
                                        , Name
                                          FROM R_Loans
                                          WHERE StatusId = 1
                                            ORDER BY Name ASC
      ");
      $output = '<option selected value="">Select Loan Type</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option data-city="'.$row->Name.'" value="'.$row->LoanId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getPurpose()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT PurposeId
                                        , Name
                                          FROM R_Purpose
                                          WHERE StatusId = 1
                                            ORDER BY Name ASC
      ");
      $output = '<option selected value="">Select Purpose</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option data-city="'.$row->Name.'" value="'.$row->PurposeId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getCategory()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT CategoryId
                                        , Name
                                          FROM R_Category
                                          WHERE StatusId = 1
                                            ORDER BY Name ASC
      ");
      $output = '<option selected value="">Select Category</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option data-city="'.$row->Name.'"  value="'.$row->CategoryId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getSource()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT SourceId
                                        , Name
                                          FROM R_Source
                                          WHERE StatusId = 1
                                            ORDER BY Name ASC
      ");
      $output = '<option selected value="">Select Source</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option data-city="'.$row->Name.'"  value="'.$row->SourceId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getExpenseType()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT ExpenseTypeId
                                        , Name
                                          FROM R_ExpenseType
                                          WHERE StatusId = 1
                                            ORDER BY Name ASC
      ");
      $output = '<option selected value="">Select Expense Type</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option data-city="'.$row->Name.'"  value="'.$row->ExpenseTypeId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getExpenseTypeReport()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT ExpenseTypeId
                                        , Name
                                          FROM R_ExpenseType
                                          WHERE StatusId = 1
                                            ORDER BY Name ASC
      ");
      $output = '';
      foreach ($query->result() as $row)
      {
        $output .= '<option data-city="'.$row->Name.'"  value="'.$row->ExpenseTypeId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getWithdrawalType()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT WithdrawalTypeId
                                        , Name
                                          FROM R_WithdrawalType
                                          WHERE StatusId = 1
                                            ORDER BY Name ASC
      ");
      $output = '<option selected value="">Select Withdrawal Type</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option data-city="'.$row->Name.'"  value="'.$row->WithdrawalTypeId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getPosition()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT PositionId
                                        , Name
                                          FROM R_Position
                                            WHERE StatusId = 1
                                            ORDER BY Name ASC
      ");
      $output = '<option selected value="">Select Position</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->PositionId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getOccupation()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT OccupationId
                                        , OCCU.Name
                                          FROM R_Occupation OCCU
                                            WHERE StatusId = 1
                                            ORDER BY OCCU.Name ASC
      ");
      $output = '<option selected value="">Select Occupation</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->OccupationId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getIndustry()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT IndustryId
                                        , Name
                                          FROM R_Industry
                                            WHERE StatusId = 1
                                            ORDER BY Name ASC
      ");
      $output = '<option selected value="">Select Industry</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->IndustryId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getBorrowerPosition()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT BorrowerPositionId
                                        , Name
                                          FROM borrower_has_position
                                            WHERE StatusId = 1
                                            ORDER BY Name ASC
      ");
      $output = '<option selected value="">Select Position</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->BorrowerPositionId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getBorrowerStatus()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT BorrowerStatusId
                                        , Name
                                          FROM r_borrowerStatus
                                            WHERE StatusId = 1
                                            ORDER BY Name ASC
      ");
      $output = '<option selected value="">Select Status</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->BorrowerStatusId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getRoles()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT RoleId
                                        , Description
                                          FROM R_Role
                                            ORDER BY Description ASC
      ");
      $output = '<option disabled value="">Select Role(s)</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->RoleId.'">'.$row->Description.'</option>';
      }
      return $output;
    }

    function getBranchCode($BranchId)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT BranchId
                                        , Code
                                          FROM R_Branch
                                          WHERE BranchId = '".$BranchId."'
                                            ORDER BY Code ASC
      ");

      $data = $query->row_array();
      return $data;
    }

    function getManagers($BranchId)
    {
      $query = $this->db->query("SELECT ManagerBranchId
                                        , CONCAT(EMP.LastName, ', ', EMP.FirstName) as Name 
                                        FROM branch_has_manager BR
                                        INNER JOIN r_employee EMP
                                          ON EMP.EmployeeNumber = BR.EmployeeNumber
                                          WHERE BR.BranchId = '".$BranchId."' 
                                          AND BR.StatusId = 1 
                                          ORDER BY BR.EmployeeNumber ASC");
      $output = '<option selected value="">Select Manager</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->ManagerBranchId.'">'.$row->Name.'</option>';
      }
      return $output;
    }
    
    function getNationality()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT NationalityId
                                        , Description
                                          FROM R_Nationality
                                            WHERE StatusId = 1
                                            ORDER BY Description ASC
      ");
      $output = '<option selected value="">Select Nationality</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->NationalityId.'">'.$row->Description.'</option>';
      }
      return $output;
    }
    
    function getCivilStatus()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT CivilStatusId
                                        , Name
                                          FROM R_CivilStatus
                                            WHERE StatusId = 1
                                            ORDER BY Name ASC
      ");
      $output = '<option selected value="">Select Civil Status</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->CivilStatusId.'">'.$row->Name.'</option>';
      }
      return $output;
    }
    
    function getSalutation()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT SalutationId
                                        , Name
                                          FROM r_salutation
                                            WHERE StatusId = 1
                                            ORDER BY Name ASC
      ");
      $output = '<option selected value="">Select Salutation</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->SalutationId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getRegion($keyword)
    {
      $query_result = [];
      $query = $this->db->query("SELECT regDesc as 'text'
                                        , regCode as 'id'
                                        FROM add_region
                                          WHERE regDesc LIKE '%$keyword%'
      ");
      return $query->result();
    }

    function getRegionList()
    {
      $query = $this->db->query("SELECT regDesc as 'text'
                                        , regCode as 'id'
                                        FROM add_region
                                          WHERE StatusId = 1
      ");
      $output = '<option selected value="">Select Region</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->id.'">'.$row->text.'</option>';
      }
      return $output;
    }

    function getProvinces($RegionCode)
    {
      $query = $this->db->query("SELECT ProvDesc, provCode FROM add_province WHERE RegCode = '".$RegionCode."' AND StatusId = 1 ORDER BY ProvDesc ASC");
      $output = '<option selected value="">Select Province</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->provCode.'">'.$row->ProvDesc.'</option>';
      }
      return $output;
    }

    function getApprovers()
    {
      $query = $this->db->query("SELECT   EmployeeNumber
                                          , LastName
                                          , FirstName
                                          FROM r_employee
                                            WHERE StatusId = 1
                                              AND EmployeeNumber != 000000
      ");
      $output = '<option disabled>Select Approver</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->EmployeeNumber.'">'.$row->LastName.', '.$row->FirstName.'</option>';
      }
      return $output;
    }

    function getCities($ProvinceCode)
    {
      $query = $this->db->query("SELECT citymunDesc, citymunCode FROM add_city WHERE provCode = '".$ProvinceCode."' AND StatusId = 1 ORDER BY citymunDesc ASC");
      $output = '<option selected value="">Select City</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->citymunCode.'">'.$row->citymunDesc.'</option>';
      }
      return $output;
    }

    function getBarangays($cityMunCode)
    {
      $query = $this->db->query("SELECT brgyCode, brgyDesc FROM add_barangay WHERE cityMunCode = '".$cityMunCode."' AND StatusId = 1 ORDER BY brgyDesc ASC");
      $output = '<option selected value="">Select Barangay</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->brgyCode.'">'.$row->brgyDesc.'</option>';
      }
      return $output;
    }

    function getGeneratedId($input)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT  MAX(".$input['column'].") as ".$input['column']."
                                FROM ".$input['table']."
                                  WHERE CreatedBy = $EmployeeNumber
      ");

      $data = $query->row_array();
      return $data;
    }

    function getCreatorDetails()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT   B.Code
                                          , B.BranchId
                                          , BE.ManagerBranchId
                                          FROM branch_has_employee BE
                                            INNER JOIN R_Branch B
                                              ON B.BranchId = BE.BranchId
                                            INNER JOIN R_Employee EMP
                                              ON EMP.EmployeeNumber = BE.EmployeeNumber
                                            WHERE BE.EmployeeNumber = $EmployeeNumber
                                            AND BE.StatusId = 1
      ");

      $data = $query->row_array();
      return $data;
    }

    function getGeneratedId2($input)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT  MAX(".$input['column'].") as ".$input['column']."
                                FROM ".$input['table']."
                                  WHERE CreatedBy = ".$input['CreatedBy']."
      ");

      $data = $query->row_array();
      return $data;
    }

    function getCharges()
    {
      $query = $this->db->query("SELECT Name
                                        , ChargeId 
                                        , ChargeType
                                        , Amount
                                        , IsMandatory
                                        FROM r_charges
                                          WHERE StatusId = 1
      ");
      $data = $query->result_array();
      return $data;
    }

    function getRepayments()
    {
      $query = $this->db->query("SELECT   CASE
                                          WHEN RHC.RepaymentId IS NULL
                                                THEN RC.Type
                                                ELSE GROUP_CONCAT(RHC.Date ORDER BY RHC.Date ASC)
                                              END as Name
                                            , RC.RepaymentId
                                          FROM r_repaymentcycle RC
                                              LEFT JOIN  repaymentcycle_has_content RHC
                                                  ON RC.RepaymentId = RHC.RepaymentId
                                                    WHERE RC.StatusId = 1
                                                    OR RHC.StatusId = 1
                                                    GROUP BY RC.RepaymentId
                                                    ORDER BY RHC.Date DESC
      ");
      $output = '<option selected disabled value="">Select Repayment Cycle</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option data-city="'.$row->Name.'" value="'.$row->RepaymentId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getDisbursements()
    {
      $query = $this->db->query("SELECT   Name
                                          , DisbursementId
                                          FROM r_disbursement 
                                          WHERE StatusId = 1
      ");
      $output = '<option selected disabled value="">Select Disbursement Type</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option data-city="'.$row->Name.'" value="'.$row->DisbursementId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getPaymentMethod()
    {
      $query = $this->db->query("SELECT   Name
                                          , DisbursementId
                                          FROM r_disbursement 
                                          WHERE StatusId = 1
      ");
      $output = '<option selected disabled value="">Select Payment Method</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option data-city="'.$row->Name.'" value="'.$row->DisbursementId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getRequirementType()
    {
      $query = $this->db->query("SELECT   DISTINCT RHT.RequirementTypeId
                                          , RHT.Name
                                          FROM requirement_has_type RHT
                                              WHERE RHT.StatusId = 1
      ");
      $output = '<option selected disabled value="">Select Requirement Type</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->RequirementTypeId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getLoanStatus()
    {
      $query = $this->db->query("SELECT   LoanStatusId
                                          , Name
                                          , IsApprovable
                                          FROM application_has_status
                                            WHERE StatusId = 1
      ");
      $output = '<option selected disabled value="">Select Loan Status</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option data-city="'.$row->Name.'"  value="'.$row->LoanStatusId.'" data-city="'.$row->IsApprovable.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function getBorrowerList()
    {
      $query = $this->db->query("SELECT   CONCAT(FirstName, ' ', MiddleName, ' ', LastName, CASE WHEN ExtName != '' THEN CONCAT(', ', ExtName) ELSE '' END ) as Name
                                          , BorrowerId
                                          FROM R_Borrowers
                                            WHERE StatusId = 1
      ");
      $output = '<option selected disabled value="">Select Borrower Name</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->BorrowerId.'">'.$row->Name.'</option>';
      }
      return $output;
    }

    function selectSpecific($tableName, $Condition, $Id)
    {
      $query = $this->db->query("select * from $tableName where $Condition = '$Id' LIMIT 1");
      return $query->row_array();
    }

    function selectSpecific2($tableName, $Condition, $Id)
    {
      $query = $this->db->query("select * from $tableName where $Condition = '$Id' AND StatusId = 1 LIMIT 1");
      return $query->row_array();
    }

    /*SEC REPORTS*/
      function getYearFilter($table)
      {
        $query_string = $this->db->query("SELECT  DISTINCT DATE_FORMAT(DateCreated, '%Y') as Year
                                                  FROM $table
                                                    GROUP BY DATE_FORMAT(DateCreated, '%Y')
        ");
        $data = $query_string->result_array();
        return $data;
      }

    /*AGE*/
    function getAge()
    {
      $query_string = $this->db->query("SELECT  DISTINCT CASE
                                                  WHEN YEAR(CURDATE()) - YEAR(DateOfBirth) BETWEEN 18 AND 24
                                                    THEN '18-24 years old'
                                                  WHEN YEAR(CURDATE()) - YEAR(DateOfBirth) BETWEEN 25 AND 31
                                                    THEN '25-31 years old'
                                                  WHEN YEAR(CURDATE()) - YEAR(DateOfBirth) BETWEEN 32 AND 39
                                                    THEN '32-39 years old'
                                                  WHEN YEAR(CURDATE()) - YEAR(DateOfBirth) BETWEEN 40 AND 47
                                                    THEN '40-47 years old'
                                                  WHEN YEAR(CURDATE()) - YEAR(DateOfBirth) BETWEEN 48 AND 55
                                                    THEN '48-55 years old'
                                                  WHEN YEAR(CURDATE()) - YEAR(DateOfBirth) BETWEEN 56 AND 65
                                                    THEN '56-65 years old'
                                                  WHEN YEAR(CURDATE()) - YEAR(DateOfBirth) > 65
                                                    THEN 'Above 65 years old'
                                                  ELSE ''
                                                END as AgeBracket
                                                , COUNT(YEAR(CURDATE()) - YEAR(DateOfBirth)) as TotalAge
                                                    FROM r_borrowers
                                                      GROUP BY AgeBracket
      ");
      $data = $query_string->result_array();
      return $data;
    }
    /*EDUCATION*/
    function getEducation($Year)
    {
      $query_string = $this->db->query("SELECT  DISTINCT COUNT(BHE.BorrowerEducationId) as TotalBorrower
                                                , ED.Name as Level
                                                FROM r_education ED
                                                  LEFT JOIN borrower_has_education BHE
                                                      ON BHE.EducationId = ED.EducationId
                                                        AND BHE.StatusId = 1
                                                        AND DATE_FORMAT(BHE.DateCreated, '%Y') = '$Year'
                                                          GROUP BY ED.EducationId
      ");
      $data = $query_string->result_array();
      return $data;
    }
    /*GENDER*/
    function getGender($Year)
    {
      $query_string = $this->db->query("SELECT  S.name
                                                , COUNT(B.BorrowerId) as TotalGender
                                                FROM r_borrowers B
                                                      INNER JOIN r_sex S
                                                          ON S.SexId = B.Sex
                                                            AND DATE_FORMAT(B.DateCreated, '%Y') = '$Year'
                                                            GROUP BY S.name
      ");
      $data = $query_string->result_array();
      return $data;
    }
    /*OCCUPATION*/
    function getOccupationPopulation($Year)
    {
      $query_string = $this->db->query("SELECT  DISTINCT COUNT(BHE.BorrowerId) as TotalBorrowers
                                                , O.Name as Occupation
                                                FROM r_occupation O
                                                  LEFT JOIN borrower_has_employer BHE
                                                      ON O.OccupationId = BHE.PositionId
                                                        AND DATE_FORMAT(BHE.DateCreated, '%Y') = '$Year'
                                                        AND BHE.StatusId = 1
                                                        GROUP BY O.OccupationId
      ");
      $data = $query_string->result_array();
      return $data;
    }
    /*INCOME LEVEL*/
    function getIncomeLevelPopulation($Year)
    {
      $query_string = $this->db->query("SELECT  DISTINCT CASE
                                                WHEN SUM(AMOUNT) < 9250
                                                      THEN 'Less than PHP 9,250'
                                                WHEN SUM(AMOUNT) BETWEEN 9520 AND 19040
                                                      THEN 'PHP 9,520 - PHP 19,040'
                                                WHEN SUM(AMOUNT) BETWEEN 19041 AND 38080
                                                      THEN 'PHP 19,041 - PHP 38,080'
                                                WHEN SUM(AMOUNT) BETWEEN 38081 AND 66640
                                                      THEN 'PHP 38,081 - PHP 66,640'
                                                WHEN SUM(AMOUNT) BETWEEN 66644 AND 114240
                                                      THEN 'PHP 66,644 - PHP 114,240'
                                                WHEN SUM(AMOUNT) BETWEEN 114241 AND 190400
                                                      THEN 'PHP 114,241 - PHP 190,400'
                                                WHEN SUM(AMOUNT) > 190400
                                                      THEN 'More than PHP 190,400'
                                                  END as IncomeLevel
                                                  , COUNT(A.BorrowerId) as TotalBorrowers
                                              FROM application_has_monthlyincome AHM
                                                    INNER JOIN t_application A
                                                        ON A.ApplicationId = AHM.ApplicationId
                                                        AND DATE_FORMAT(AHM.DateCreated, '%Y') = '$Year'
                                                          GROUP BY BorrowerId
      ");
      $data = $query_string->result_array();
      return $data;
    }
    /*MARITAL STATUS*/
    function getMaritalStatusPopulation($Year)
    {
      $query_string = $this->db->query("SELECT  CS.Name
                                                , COUNT(B.BorrowerId) as TotalBorrowers
                                                FROM r_civilstatus CS
                                                      LEFT JOIN r_borrowers B
                                                          ON B.CivilStatus = CS.CivilStatusId
                                                            AND DATE_FORMAT(B.DateCreated, '%Y') = '$Year'
                                                            WHERE CS.StatusId = 1
                                                            GROUP BY CS.CivilStatusId
      ");
      $data = $query_string->result_array();
      return $data;
    }
    /*NUMBER OF BORROWERS*/
    function getTotalBorrowers()
    {
      $query_string = $this->db->query("SELECT  COUNT(BorrowerId) as TotalBorrowers
                                                , DATE_FORMAT(DateCreated, '%Y') as DateCreated
                                                FROM r_borrowers
                                                      WHERE StatusId = 1
                                                        GROUP BY DATE_FORMAT(DateCreated, '%Y')
      ");
      $data = $query_string->result_array();
      return $data;
    }
    /*LOAN TYPE*/
    function getLoanType($Year)
    {
      $query_string = $this->db->query("SELECT  DISTINCT L.Name
                                                , COUNT(A.ApplicationId) as Total
                                                FROM r_loans L
                                                      LEFT JOIN t_application A
                                                          ON A.LoanId = L.LoanId
                                                            AND A.StatusId = 1
                                                            AND DATE_FORMAT(A.DateCreated, '%Y') = '$Year'
                                                            GROUP BY L.LoanId
      ");
      $data = $query_string->result_array();
      return $data;
    }
    /*NUMBER OF LOANS*/
    function getTotalLoans()
    {
      $query_string = $this->db->query("SELECT  COUNT(ApplicationId) as Total
                                                , DATE_FORMAT(DateCreated, '%Y') as DateCreated
                                                FROM t_application
                                                      WHERE StatusId = 1
                                                        GROUP BY DATE_FORMAT(DateCreated, '%Y')
      ");
      $data = $query_string->result_array();
      return $data;
    }
    /*TOTAL LOAN AMOUNT*/
    function getTotalLoanAmount()
    {
      $query_string = $this->db->query("SELECT  SUM(PrincipalAmount) Total
                                                , DATE_FORMAT(DateCreated, '%Y') as DateCreated
                                                , FORMAT(SUM(PrincipalAmount), 2) TotalLabel
                                                FROM t_application
                                                    WHERE StatusId = 1
                                                    GROUP BY DATE_FORMAT(DateCreated, '%Y')
      ");
      $data = $query_string->result_array();
      return $data;
    }
    /*Total Charges*/
    function getChargesTotal($Year)
    {
      $query_string = $this->db->query("SELECT  DISTINCT L.Name
                                                , COUNT(A.ApplicationId) as Total
                                                FROM r_loans L
                                                      LEFT JOIN t_application A
                                                          ON A.LoanId = L.LoanId
                                                            AND A.StatusId = 1
                                                            AND DATE_FORMAT(A.DateCreated, '%Y') = '$Year'
                                                            GROUP BY L.LoanId
      ");
      $data = $query_string->result_array();
      return $data;
    }
    /*Total Charges*/
    function getTenors($Year)
    {
      $query_string = $this->db->query("SELECT  COUNT(TermType) as TermTypes
                                                , CASE
                                                  WHEN TermType = 'Months'
                                                    THEN 'Monthly'
                                                  WHEN TermType = 'Days'
                                                    THEN 'Daily'
                                                  WHEN TermType = 'Weeks'
                                                    THEN 'Weekly'
                                                  WHEN TermType = 'Years'
                                                    THEN 'Yearly'
                                                 END as TermType
                                                FROM t_application A
                                                  WHERE StatusId = 1
                                                  AND DATE_FORMAT(A.DateCreated, '%Y') = '$Year'
                                                  GROUP BY DATE_FORMAT(A.DateCreated, '%Y'), TermType
      ");
      $data = $query_string->result_array();
      return $data;
    }
    /*TOTAL INTEREST*/
    function getTotalInterest()
    {
      $query_string = $this->db->query("SELECT  DISTINCT DATE_FORMAT(PM.DateCreated, '%Y') as Year
                                                , SUM(Amount) as Total
                                                FROM t_paymentsmade PM
                                                      INNER JOIN t_application A
                                                          ON A.ApplicationId = PM.ApplicationId
                                                            WHERE IsInterest = 1
                                                            AND PM.StatusId = 1
                                                            AND A.StatusId = 1
                                                            GROUP BY DATE_FORMAT(PM.DateCreated, '%Y')
      ");
      $data = $query_string->result_array();
      return $data;
    }
}