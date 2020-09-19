<?php
class loanapplication_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
		$this->load->model('maintenance_model');
		$this->load->model('access');
  }

  function getLoanApplicationDetails($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT BHP.FileName
                                      , CONCAT(BS.Name, ' ', B.FirstName, ' ', B.MiddleName, ' ', B.LastName, ', ', B.ExtName) as Name
                                      , B.BorrowerNumber
                                      , DATE_FORMAT(B.DateOfBirth, '%b %d, %Y') as DOB
                                      , TIMESTAMPDIFF(YEAR, B.DateOfBirth, CURDATE()) as Age
                                      , CN.Number as ContactNumber
                                      , E.EmailAddress

                                      , DATE_FORMAT(B.DateCreated, '%b %d, %Y') as DateCreated
                                      , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as CreatedBy

                                      , A.TransactionNumber
                                      , CASE
                                          WHEN A.DateApproved IS NULL
                                          THEN 'N/A'
                                          ELSE DATE_FORMAT(A.DateApproved, '%b %d, %Y %r')
                                        END as DateApproved
                                      , FORMAT(A.PrincipalAmount, 2) as PrincipalAmount
                                      , AHI.InterestType
                                      , CASE
                                          WHEN AHI.InterestType = 'Percentage'
                                          THEN CONCAT(AHI.Amount, '% /', AHI.Frequency)
                                          ELSE CONCAT('Php ', AHI.Amount, ' ', AHI.Frequency)
                                        END as InterestRate
                                      , AHI.Frequency
                                      , CASE
                                          WHEN AHI.InterestType = 'Percentage'
                                          THEN AHI.Amount/100 * PrincipalAmount
                                          ELSE PrincipalAmount + AHI.Amount
                                        END as TotalInterest
                                      , AHI.Amount
                                      , A.PrincipalAmount as RawPrincipalAmount
                                      , A.TermNo
                                      , A.TermType
                                      , A.RepaymentNo
                                      , RC.Type
                                      , A.StatusId
                                      , A.ApplicationId
                                      , LS.Name as StatusDescription
                                      , A.StatusId

                                      , L.Name as LoanType
                                      , D.Name as DisbursedBy
                                      , A.ApprovalType

                                      , A.IsPenalized
                                      , A.PenaltyType
                                      , A.PenaltyAmount
                                      , A.GracePeriod

                                      , A.RepaymentId
                                      , A.Source
                                      , DATE_FORMAT(A.LoanReleaseDate, '%b %d, %Y') as LoanReleaseDate
                                      , A.LoanReleaseDate as rawLoanReleaseDate
                                      , CASE
                                          WHEN AHI.InterestType = 'Percentage'
                                          THEN CONCAT(AHI.Amount * A.TermNo, '%')
                                          ELSE CONCAT('Php ', AHI.Amount)
                                        END as renewAddOnInterest
                                      , CASE
                                          WHEN AHI.InterestType = 'Percentage'
                                          THEN AHI.Amount/100 * A.TermNo
                                          ELSE AHI.Amount
                                        END as AddOnRate
                                      , A.TermNo * A.RepaymentNo as TotalCollections
                                      , B.BorrowerId
                                      , A.LoanId
                                      , A.PurposeId
                                      , (SELECT COUNT(*)
                                            FROM application_has_approver
                                              WHERE ApplicationId = A.ApplicationId
                                              AND StatusId = 5
                                              AND ApproverNumber = '000002'
                                      ) as IsApprover
                                      , A.SourceName
                                      , A.BorrowerMonthlyIncome
                                      , A.SpouseMonthlyIncome
                                      , P.Name as PurposeName
                                      , (SELECT RHT.RequirementTypeId 
                                                FROM Application_has_Requirements AHR
                                                  INNER JOIN R_Requirements R
                                                    ON R.RequirementId = AHR.RequirementId
                                                  INNER JOIN Requirement_has_type RHT
                                                    ON R.RequirementTypeId = RHT.RequirementTypeId
                                                      WHERE AHR.ApplicationId = A.ApplicationId
                                                      LIMIT 1
                                      ) as RequirementTypeId
                                      FROM T_Application A
                                        INNER JOIN Application_Has_Status LS
                                          ON A.StatusId = LS.LoanStatusId
                                        INNER JOIN R_Loans L 
                                          ON L.LoanId = A.LoanId
                                        INNER JOIN R_Disbursement D
                                          ON D.DisbursementId = A.DisbursementId
                                        LEFT JOIN R_Borrowers B
                                          ON B.BorrowerId = A.BorrowerId
                                        LEFT JOIN Borrower_Has_Picture BHP
                                          ON BHP.BorrowerId = B.BorrowerId
                                        LEFT JOIN R_Salutation BS
                                          ON BS.SalutationId = B.Salutation
                                        LEFT JOIN R_Employee EMP
                                          ON EMP.EmployeeNumber = A.CreatedBy
                                        LEFT JOIN Borrower_has_ContactNumbers BHC
                                          ON BHC.BorrowerId = B.BorrowerId
                                        LEFT JOIN R_ContactNumbers CN
                                          ON CN.ContactNumberId = BHC.ContactNumberId
                                        LEFT JOIN Borrower_Has_Emails BHE
                                          ON BHE.BorrowerId = B.BorrowerId
                                        LEFT JOIN R_Emails E
                                          ON E.EmailId = BHE.EmailId
                                        LEFT JOIN Application_has_interests AHI
                                          ON AHI.ApplicationId = A.ApplicationId
                                        LEFT JOIN R_RepaymentCycle RC
                                          ON RC.RepaymentId = A.RepaymentId
                                        LEFT JOIN R_Purpose P
                                          ON P.PurposeId = A.PurposeId
                                        WHERE A.ApplicationId = $Id
                                        -- AND AHI.StatusId = 1
                                        -- AND BHE.IsPrimary = 1
                                        -- AND BHE.StatusId = 1
                                        -- AND BHC.IsPrimary = 1
                                        -- AND BHC.StatusId = 1
                                        -- AND BHP.StatusId = 1
    ");

    $data = $query->row_array();
    return $data;
  }

  function getRepayments($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT   CASE
                                          WHEN RHC.RepaymentId IS NULL
                                                THEN RC.Type
                                                ELSE GROUP_CONCAT(RHC.Date)
                                              END as Name
                                          , RC.RepaymentId
                                          FROM r_repaymentcycle RC
                                          LEFT JOIN  repaymentcycle_has_content RHC
                                            ON RC.RepaymentId = RHC.RepaymentId
                                          LEFT JOIN T_Application A
                                            ON A.RepaymentId = RC.RepaymentId
                                              WHERE A.ApplicationId = $Id
                                              AND (
                                                RC.StatusId = 1
                                                OR 
                                                RHC.StatusId = 1
                                              )
                                              GROUP BY RC.RepaymentId
    ");

    $data = $query->row_array();
    return $data;
  }

  function getCharges($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT  SUM(CASE
                                            WHEN C.ChargeType = 'Flat Rate'
                                                  THEN C.Amount
                                                  ELSE C.Amount/100 * A.PrincipalAmount
                                              END
                                        ) as TotalCharges
                                        FROM Application_Has_Charges AHC
                                          INNER JOIN R_Charges C
                                            ON C.ChargeId = AHC.ChargeId
                                          INNER JOIN t_application A
                                            ON A.ApplicationId = AHC.ApplicationId
                                          WHERE A.ApplicationId = $Id
                                          AND AHC.StatusId = 2
    ");

    $data = $query->row_array();
    return $data;
  }

  function getBorrowerList()
  {
    $query = $this->db->query("SELECT   CONCAT(FirstName, ' ', MiddleName, ' ', LastName, CASE WHEN ExtName != '' THEN CONCAT(', ', ExtName) ELSE '' END ) as Name
                                        , BorrowerId
                                        FROM R_Borrowers
                                          WHERE StatusId = 1
    ");

    $data = $query->result_array();
    return $data;
  }

  function getChargeList($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT   DISTINCT AHC.ChargeId
                                        FROM Application_Has_Charges AHC
                                          INNER JOIN R_Charges C
                                            ON C.ChargeId = AHC.ChargeId
                                          INNER JOIN t_application A
                                            ON A.ApplicationId = AHC.ApplicationId
                                          WHERE A.ApplicationId = $Id
                                          AND AHC.StatusId = 2
    ");

    $data = $query->result_array();
    return $data;
  }

  function getRequirementsList($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT R.RequirementId 
                                      FROM Application_has_Requirements AHR
                                        INNER JOIN R_Requirements R
                                          ON R.RequirementId = AHR.RequirementId
                                        INNER JOIN Requirement_has_type RHT
                                          ON R.RequirementTypeId = RHT.RequirementTypeId
                                            WHERE AHR.ApplicationId = $Id
                                            AND AHR.StatusId = 5
    ");

    $data = $query->result_array();
    return $data;
  }

  function getChargeDetails($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT C.ChargeId
                                      , C.Name
                                      , C.Amount
                                      , A.PrincipalAmount
                                      , C.Description
                                      , CASE
                                          WHEN C.Description IS NULL
                                          THEN 'N/A'
                                          ELSE C.Description
                                        END as Description
                                      , C.ChargeType
                                      , CASE
                                          WHEN C.ChargeType = 'Flat Rate'
                                          THEN CONCAT(C.Amount)
                                          ELSE CONCAT(C.Amount / 100 * A.PrincipalAmount)
                                        END as TotalCharge
                                      , AHC.StatusId
                                      , AHC.ApplicationChargeId
                                      FROM Application_has_charges AHC
                                        INNER JOIN R_Charges C
                                          ON C.ChargeId = AHC.ChargeId
                                        INNER JOIN T_Application A
                                          ON A.ApplicationId = AHC.ApplicationId
                                        LEFT JOIN R_Employee EMP
                                          ON EMP.EmployeeNumber = AHC.CreatedBy
                                            WHERE AHC.ChargeId = $Id
    ");

    $data = $query->row_array();
    return $data;
  }

  function getPenalties($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT   SUM(Amount) as Total
                                        FROM Application_Has_Penalty AHP
                                          INNER JOIN R_Penalty P
                                            ON P.PenaltyId = AHP.PenaltyId
                                          WHERE ApplicationId = $Id
                                          AND AHP.StatusId = 1
    ");

    $data = $query->row_array();
    return $data;
  }

  function getPayments($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT   SUM(Amount) as Total
                                        FROM t_paymentsmade AHP
                                          WHERE ApplicationId = $Id
    ");

    $data = $query->row_array();
    return $data;
  }

  function displayAllLoans()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  A.TransactionNumber
                                              , L.Name as LoanName
                                              , CONCAT(B.FirstName, ' ', B.MiddleName, ' ', B.LastName, ', ', B.ExtName) as BorrowerName
                                              , FORMAT(A.PrincipalAmount, 2) PrincipalAmount
                                              , CASE
                                                  WHEN AHI.InterestType = 'Percentage'
                                                  THEN CONCAT(AHI.Amount, '% /', AHI.Frequency)
                                                  ELSE CONCAT('Php ', AHI.Amount, ' ', AHI.Frequency)
                                                END as InterestRate
                                              , CASE
                                                  WHEN AHI.InterestType = 'Percentage'
                                                  THEN AHI.Amount/100 * PrincipalAmount
                                                  ELSE PrincipalAmount + AHI.Amount
                                                END as TotalInterest
                                              , A.CreatedBy
                                              , AHI.Amount
                                              , A.PrincipalAmount as RawPrincipalAmount
                                              , A.TermNo as TermNo
                                              , A.TermType
                                              , A.RepaymentNo
                                              , RC.Type
                                              , AHI.Amount
                                              , AHI.InterestType
                                              , RC.Type
                                              , DATE_FORMAT(A.DateApproved, '%b %d, %Y') as DateApproved
                                              , LS.Name as StatusDescription
                                              , A.StatusId
                                              , A.ApplicationId
                                              , LS.IsApprovable
                                              , (SELECT COUNT(*) 
                                                    FROM application_has_approver
                                                      WHERE ApplicationId = A.ApplicationId
                                                      AND StatusId = 5
                                              ) as PendingApprovers
                                              , (SELECT COUNT(*) 
                                                    FROM application_has_approver
                                                      WHERE ApplicationId = A.ApplicationId
                                                      AND StatusId = 3
                                              ) as ProcessedApprovers
                                              , (SELECT MAX(DATE_FORMAT(DateCreated, '%b %d, %Y'))
                                                        FROM t_paymentsmade
                                              ) as LastPayment
                                              FROM T_Application A
                                                INNER JOIN R_Loans L 
                                                  ON L.LoanId = A.LoanId
                                                INNER JOIN R_Borrowers B
                                                  ON B.BorrowerId = A.BorrowerId
                                                INNER JOIN Application_has_interests AHI
                                                  ON AHI.ApplicationId = A.ApplicationId
                                                INNER JOIN Application_Has_Status LS
                                                  ON A.StatusId = LS.LoanStatusId
                                                LEFT JOIN R_RepaymentCycle RC
                                                  ON RC.RepaymentId = A.RepaymentId
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function displayLoanHistory($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  A.ApplicationId
                                              , AHN.Description
                                              , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as CreatedBy
                                              , DATE_FORMAT(AHN.DateCreated, '%b %d, %Y %r') as DateCreated
                                              FROM Application_has_notifications AHN
                                                INNER JOIN T_Application A
                                                  ON A.ApplicationId = AHN.ApplicationId
                                                INNER JOIN R_Employee EMP
                                                  ON EMP.EmployeeNumber = AHN.CreatedBy
                                                    WHERE A.ApplicationId = $ID
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function getLoanComments($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  AC.ApplicationId
                                              , AC.Comment
                                              , AC.CreatedBy
                                              , DATE_FORMAT(AC.DateCreated, '%b %d, %Y %r') as DateCreated
                                              , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as Name
                                              FROM Application_has_Comments AC
                                                INNER JOIN r_employee EMP
                                                  ON EMP.EmployeeNumber = AC.CreatedBy
                                                    WHERE AC.ApplicationId = $ID
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function displayRequirements($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  AR.ApplicationId
                                              , AR.RequirementId
                                              , R.Name
                                              , S.Description
                                              , AR.ApplicationRequirementId
                                              , AR.StatusId
                                              , DATE_FORMAT(AR.DateCreated, '%b %d, %Y %r') as DateCreated
                                              FROM Application_has_Requirements AR
                                                    INNER JOIN R_Requirements R
                                                      ON R.RequirementId = AR.RequirementId
                                                    INNER JOIN r_status S
                                                      ON S.StatusId = AR.StatusId
                                                     WHERE AR.ApplicationId = $ID
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function getCollateralType($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  Name
                                              , CollateralTypeId
                                              FROM R_CollateralType
                                                WHERE StatusId = 1 
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function getCollateralStatus($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  Name
                                              , CollateralStatusId
                                              FROM r_collateralStatus
                                                WHERE StatusId = 1 
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function getCollateral($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  CONCAT('CLR-', LPAD(C.CollateralId, 4, 0)) as ReferenceNo
                                              , C.ProductName
                                              , C.Value
                                              , DATE_FORMAT(C.DateRegistered, '%b %d, %Y') as DateRegistered
                                              , CT.Name as CollateralType
                                              , CS.Name as CurrentStatus
                                              , C.CollateralId
                                              FROM R_Collaterals C
                                                INNER JOIN application_has_collaterals AHC
                                                  ON AHC.CollateralId = C.CollateralId
                                                INNER JOIN r_collateralStatus CS
                                                  ON CS.CollateralStatusId = C.StatusId
                                                INNER JOIN R_CollateralType CT
                                                  ON CT.CollateralTypeId = C.CollateralTypeId
                                                    WHERE AHC.ApplicationId = $ID
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function getIncome($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  AI.ApplicationId
                                              , AI.Source
                                              , AI.IncomeId
                                              , AI.Details
                                              , AI.Amount
                                              , AI.CreatedBy
                                              , AI.StatusId
                                              , S.Description
                                              , DATE_FORMAT(AI.DateCreated, '%b %d, %Y %r') as DateCreated
                                              , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as Name
                                              FROM Application_has_MonthlyIncome AI
                                                INNER JOIN r_status S
                                                  ON S.StatusId = AI.StatusId
                                                LEFT JOIN R_Employee EMP
                                                  ON EMP.EmployeeNumber = AI.CreatedBy
                                                    WHERE AI.ApplicationId = $ID
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function displayCharges($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  C.ChargeId
                                              , AHC.ApplicationChargeId
                                              , C.Name
                                              , C.Amount
                                              , C.StatusId
                                              , A.PrincipalAmount
                                              , C.ChargeType
                                              , C.IsMandatory
                                              , CASE
                                                  WHEN C.ChargeType = 'Flat Rate'
                                                  THEN CONCAT(C.Amount)
                                                  ELSE CONCAT(C.Amount / 100 * A.PrincipalAmount)
                                                END as TotalCharge
                                              , AHC.StatusId
                                              , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as CreatedBy
                                              , DATE_FORMAT(AHC.DateCreated, '%b %d, %Y %r') as DateCreated
                                              FROM Application_has_charges AHC
                                                INNER JOIN R_Charges C
                                                  ON C.ChargeId = AHC.ChargeId
                                                INNER JOIN T_Application A
                                                  ON A.ApplicationId = AHC.ApplicationId
                                                LEFT JOIN R_Employee EMP
                                                  ON EMP.EmployeeNumber = AHC.CreatedBy
                                                    WHERE AHC.ApplicationId = $ID
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function getExpenses($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  AE.ApplicationId
                                              , AE.Source
                                              , AE.ExpenseId
                                              , AE.Details
                                              , AE.Amount
                                              , AE.StatusId
                                              , S.Description
                                              , DATE_FORMAT(AE.DateCreated, '%b %d, %Y %r') as DateCreated
                                              , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as CreatedBy
                                              FROM Application_has_Expense AE
                                                INNER JOIN r_status S
                                                  ON S.StatusId = AE.StatusId
                                                LEFT JOIN R_Employee EMP
                                                  ON EMP.EmployeeNumber = AE.CreatedBy
                                                    WHERE AE.ApplicationId = $ID
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function getLoanObligations($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  AO.ApplicationId
                                              , AO.Source
                                              , AO.MonthlyObligationId
                                              , AO.Details
                                              , AO.Amount
                                              , AO.StatusId
                                              , S.Description
                                              , DATE_FORMAT(AO.DateCreated, '%b %d, %Y %r') as DateCreated
                                              , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as CreatedBy
                                              FROM Application_has_MonthlyObligation AO
                                                INNER JOIN r_status S
                                                  ON S.StatusId = AO.StatusId
                                                LEFT JOIN R_Employee EMP
                                                  ON EMP.EmployeeNumber = AO.CreatedBy
                                                    WHERE AO.ApplicationId = $ID
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function countExpense($data)
  {
    $query_string = $this->db->query("SELECT  * 
                                              FROM Application_has_Expense
                                                WHERE Source = '".$data['Source']."'
                                                AND Details = '".$data['Details']."'
                                                AND Amount = '".$data['Amount']."'
                                                AND ApplicationId = '".$data['ApplicationId']."'
    ");
    $data = $query_string->num_rows();
    return $data;
  }

  function countObligation($data)
  {
    $query_string = $this->db->query("SELECT  * 
                                              FROM Application_has_MonthlyObligation
                                                WHERE Source = '".$data['Source']."'
                                                AND Details = '".$data['Details']."'
                                                AND Amount = '".$data['Amount']."'
                                                AND ApplicationId = '".$data['ApplicationId']."'
    ");
    $data = $query_string->num_rows();
    return $data;
  }

  function countMonthlyIncome($data)
  {
    $query_string = $this->db->query("SELECT  * 
                                              FROM Application_has_MonthlyIncome
                                                WHERE Source = '".$data['Source']."'
                                                AND Details = '".$data['Details']."'
                                                AND Amount = '".$data['Amount']."'
                                                AND ApplicationId = '".$data['ApplicationId']."'
    ");
    $data = $query_string->num_rows();
    return $data;
  }

  function countRequirement($data)
  {
    $query_string = $this->db->query("SELECT  * 
                                              FROM Application_has_Requirements
                                                WHERE RequirementId = '".$data['Requirement']."'
                                                AND ApplicationId = '".$data['ApplicationId']."'
    ");
    $data = $query_string->num_rows();
    return $data;
  }

  function getObligationDetails($Id)
  {
    $query_string = $this->db->query("SELECT  ApplicationId
                                              , MonthlyObligationId
                                              , Source
                                              , Amount
                                              , Details
                                              FROM Application_has_MonthlyObligation 
                                                WHERE MonthlyObligationId = '$Id'
    ");
    $ObligationDetail = $query_string->row_array();
    return $ObligationDetail;
  }

  function getExpenseDetails($Id)
  {
    $query_string = $this->db->query("SELECT  ApplicationId
                                              , ExpenseId
                                              , Source
                                              , Amount
                                              , Details
                                              FROM Application_has_Expense 
                                                WHERE ExpenseId = '$Id'
    ");
    $ExpenseDetail = $query_string->row_array();
    return $ExpenseDetail;
  }

  function getIncomeDetails($Id)
  {
    $query_string = $this->db->query("SELECT  ApplicationId
                                              , IncomeId
                                              , Source
                                              , Amount
                                              , Details
                                              FROM Application_has_MonthlyIncome 
                                                WHERE IncomeId = '$Id'
    ");
    $IncomeDetail = $query_string->row_array();
    return $IncomeDetail;
  }

  function getRequirementDetails($Id)
  {
    $query_string = $this->db->query("SELECT  ApplicationId
                                              , RequirementId
                                              FROM Application_has_Requirements 
                                                WHERE RequirementId = '$Id'
    ");
    $RequirementDetail = $query_string->row_array();
    return $RequirementDetail;
  }

  function getCollateralDetails($Id)
  {
    $query_string = $this->db->query("SELECT  ApplicationId
                                              , C.CollateralId
                                              , C.CollateralTypeId
                                              , C.StatusId
                                              , C.ProductName
                                              , C.Value
                                              , DATE_FORMAT(C.DateRegistered, '%b %d, %Y') as DateRegistered
                                              , DATE_FORMAT(C.DateAcquired, '%b %d, %Y') as DateAcquired
                                              , C.RegistrationNo
                                              , C.Mileage
                                              , C.EngineNo
                                              FROM application_has_collaterals AHC
                                                INNER JOIN R_Collaterals C
                                                ON C.CollateralId = AHC.CollateralId
                                                WHERE C.CollateralId = $Id
    ");
    $CollateralDetail = $query_string->row_array();
    return $CollateralDetail;
  }

  function getApprovers($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as ApproverName
                                              , AHA.ApplicationApprovalId
                                              , S.Description
                                              , S.StatusId
                                              FROM t_application A
                                                    INNER JOIN application_has_approver AHA
                                                      ON AHA.ApplicationId = A.ApplicationId
                                                    INNER JOIN r_employee EMP
                                                      ON EMP.EmployeeNumber = AHA.ApproverNumber
                                                    INNER JOIN r_status S
                                                      ON S.StatusId = AHA.StatusId
                                                      WHERE A.ApplicationId = $ID
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function getTenure($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT  COALESCE(AVG(TIMESTAMPDIFF(YEAR, DateHired, DateTo))) as AvgYears
                                        FROM borrower_has_employer
                                              WHERE BorrowerId = $Id
                                              AND StatusId = 1
    ");

    $data = $query->row_array();
    return $data;
  }

  function updateStatus($input)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");

    if($input['Type'] == 'Obligations')
    {

      $ObligationDetail = $this->db->query("SELECT  Source
                                                  , ApplicationId
                                                  FROM Application_has_MonthlyObligation
                                                    WHERE MonthlyObligationId = ".$input['Id']."
      ")->row_array();
      // update status
        $set = array(
          'StatusId' => $input['updateType'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'MonthlyObligationId' => $input['Id'],
        );
        $table = 'Application_has_MonthlyObligation';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // insert into Application_has_Notifications
        if($input['updateType'] == 2)
        {
          $ObligationDescription = 'Re-activated ' .$ObligationDetail['Source']. ' of ' .$ObligationDetail['ApplicationId']. ' at the Obligation tab '; // Application Notification
        }
        else if($input['updateType'] == 6)
        {
          $ObligationDescription = 'Deactivated ' .$ObligationDetail['Source']. '  of ' .$ObligationDetail['ApplicationId']. ' at the Obligation tab '; // Application Notification
        }
        $data3 = array(
          'Description'   => $ObligationDescription,
          'ApplicationId' => $ObligationDetail['ApplicationId'],
          'CreatedBy'     => $EmployeeNumber,
          'DateCreated'   => $DateNow
        );
        $this->db->insert('Application_has_Notifications', $data3);
      // insert into logs
        if($input['updateType'] == 2)
        {
          $Description = 'Re-activated ' .$ObligationDetail['Source']. ' at the system setup'; // main log
        }
        else if($input['updateType'] == 6)
        {
          $Description = 'Deactivated ' .$ObligationDetail['Source']. '  at the system setup'; // main log
        }
        $data2 = array(
          'Description'   => $Description,
          'CreatedBy'     => $EmployeeNumber,
          'DateCreated'   => $DateNow
        );
        $this->db->insert('R_Logs', $data2);
    }
    else if($input['Type'] == 'Expenses')
    {
      $ExpenseDetail = $this->db->query("SELECT  Source
                                                , ApplicationId
                                                  FROM Application_has_Expense
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
        $table = 'Application_has_Expense';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into Application_has_Notifications
        if($input['updateType'] == 2)
        {
          $ExpenseDescription = 'Re-activated ' .$ExpenseDetail['Source']. ' of ' .$ExpenseDetail['ApplicationId']. ' at the Expense tab '; // Application Notification
        }
        else if($input['updateType'] == 6)
        {
          $ExpenseDescription = 'Deactivated ' .$ExpenseDetail['Source']. '  of ' .$ExpenseDetail['ApplicationId']. ' at the Expense tab '; // Application Notification
        }
        $data3 = array(
          'Description'   => $ExpenseDescription,
          'ApplicationId' => $ExpenseDetail['ApplicationId'],
          'CreatedBy'     => $EmployeeNumber,
          'DateCreated'   => $DateNow
        );
        $this->db->insert('Application_has_Notifications', $data3);
      // insert into logs
        if($input['updateType'] == 2)
        {
          $Description = 'Re-activated ' .$ExpenseDetail['Source']. ' at the system setup'; // main log
        }
        else if($input['updateType'] == 6)
        {
          $Description = 'Deactivated ' .$ExpenseDetail['Source']. '  at the system setup'; // main log
        }
        $data2 = array(
          'Description'   => $Description,
          'CreatedBy'     => $EmployeeNumber,
          'DateCreated'   => $DateNow
        );
        $this->db->insert('R_Logs', $data2);
        // insert into Application_has_Notifications
        if($input['updateType'] == 2)
        {
          $Description = 'Re-activated ' .$ExpenseDetail['Source']. ' of Expense # '; // Application Notification
        }
        else if($input['updateType'] == 6)
        {
          $Description = 'Deactivated ' .$ExpenseDetail['Source']. '  of Expense #'; // Application Notification
        }
        $data2 = array(
          'Description'   => $Description,
          'CreatedBy'     => $EmployeeNumber,
          'DateCreated'   => $DateNow
        );
        $this->db->insert('Application_has_Notifications', $data2);
    }
    else if($input['Type'] == 'Incomes')
    {
      $IncomeDetail = $this->db->query("SELECT  Source
                                                , ApplicationId
                                                  FROM Application_has_MonthlyIncome
                                                    WHERE IncomeId = ".$input['Id']."
      ")->row_array();

      // update status
        $set = array(
          'StatusId' => $input['updateType'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'IncomeId' => $input['Id']
        );
        $table = 'Application_has_MonthlyIncome';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into Application_has_Notifications
        if($input['updateType'] == 2)
        {
          $IncomeDescription = 'Re-activated ' .$IncomeDetail['Source']. ' of ' .$IncomeDetail['ApplicationId']. ' at the Other Source of Income tab '; // Application Notification
        }
        else if($input['updateType'] == 6)
        {
          $IncomeDescription = 'Deactivated ' .$IncomeDetail['Source']. '  of ' .$IncomeDetail['ApplicationId']. ' at the Other Source of Income tab '; // Application Notification
        }
        $data3 = array(
          'Description'   => $IncomeDescription,
          'ApplicationId' => $IncomeDetail['ApplicationId'],
          'CreatedBy'     => $EmployeeNumber,
          'DateCreated'   => $DateNow
        );
        $this->db->insert('Application_has_Notifications', $data3);
      // insert into logs
        if($input['updateType'] == 2)
        {
          $Description = 'Re-activated ' .$IncomeDetail['Source']. ' at the system setup'; // main log
        }
        else if($input['updateType'] == 6)
        {
          $Description = 'Deactivated ' .$IncomeDetail['Source']. '  at the system setup'; // main log
        }
        $data2 = array(
          'Description'   => $Description,
          'CreatedBy'     => $EmployeeNumber,
          'DateCreated'   => $DateNow
        );
        $this->db->insert('R_Logs', $data2);
    }
    else if($input['Type'] == 'Requirements')
    {
      $RequirementDetail = $this->db->query("SELECT  ApplicationRequirementId
                                                , ApplicationId
                                                  FROM Application_has_Requirements
                                                    WHERE ApplicationRequirementId = ".$input['Id']."
      ")->row_array();

      // update status
        $set = array(
          'StatusId' => $input['updateType'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'ApplicationRequirementId' => $input['Id']
        );
        $table = 'Application_has_Requirements';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // insert into logs
        if($input['updateType'] == 2)
        {
          $Description = 'Re-activated ' .$RequirementDetail['ApplicationRequirementId']. ' at the system setup'; // main log
        }
        else if($input['updateType'] == 6)
        {
          $Description = 'Deactivated ' .$RequirementDetail['ApplicationRequirementId']. '  at the system setup'; // main log
        }
        $data2 = array(
          'Description'   => $Description,
          'CreatedBy'     => $EmployeeNumber,
          'DateCreated'   => $DateNow
        );
        $this->db->insert('R_Logs', $data2);
        // insert into Application_has_Notifications
        if($input['updateType'] == 2)
        {
          $Description = 'Re-activated ' .$RequirementDetail['ApplicationRequirementId']. ' of Requirement # '; // Application Notification
        }
        else if($input['updateType'] == 6)
        {
          $Description = 'Deactivated ' .$RequirementDetail['ApplicationRequirementId']. '  of Requirement #'; // Application Notification
        }
        $data2 = array(
          'Description'   => $Description,
          'CreatedBy'     => $EmployeeNumber,
          'DateCreated'   => $DateNow
        );
        $this->db->insert('Application_has_Notifications', $data2);
    }
  }
  
  function getRequirements($Id)
  {
    $query = $this->db->query("SELECT DISTINCT RequirementId
                                        , R.Name
                                        , Description
                                        , IsMandatory
                                        FROM r_requirements R
                                          INNER JOIN Requirement_has_type RT
                                          ON R.RequirementTypeId = RT.RequirementTypeId
                                          WHERE R.StatusId = 1 
                                          AND requirementId NOT IN (SELECT RequirementId FROM Application_has_Requirements AR WHERE AR.ApplicationId = $Id )
                                          AND RT.RequirementTypeId = (SELECT DISTINCT RR.RequirementTypeId FROM r_requirements RR
                                                                          INNER JOIN Application_has_Requirements AHR 
                                                                            ON AHR.RequirementId = RR.requirementId
                                                                              WHERE AHR.ApplicationId = $Id)
    ");
    $output = '<option selected disabled value="">Select Requirement Type</option>';
    foreach ($query->result() as $row)
    {
      $output .= '<option value="'.$row->RequirementId.'">'.$row->Name.'</option>';
    }
    return $output;
  } 
  
  function selectCharges($Id)
  {
    $query = $this->db->query("SELECT ChargeId
                                      , Name
                                      FROM R_Charges C
                                        WHERE ChargeId 
                                        NOT IN 
                                        (
                                          SELECT  ChargeId
                                                  FROM Application_Has_Charges
                                                    WHERE ApplicationId = $Id
                                                    AND StatusId = 2 
                                        )
                                        AND StatusId = 1
    ");
    $output = '<option selected disabled value="">Select Charges</option>';
    foreach ($query->result() as $row)
    {
      $output .= '<option value="'.$row->ChargeId.'">'.$row->Name.'</option>';
    }
    return $output;
  } 



  // RENEWAL OF LOANS
    function getLoanTypes()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query_string = $this->db->query("SELECT LoanId
                                        , Name
                                          FROM R_Loans
                                          WHERE StatusId = 1
                                            ORDER BY Name ASC
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getPurpose()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query_string = $this->db->query("SELECT PurposeId
                                        , Name
                                          FROM R_Purpose
                                          WHERE StatusId = 1
                                            ORDER BY Name ASC
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getDisbursements()
    {
      $query = $this->db->query("SELECT   Name
                                          , DisbursementId
                                          FROM r_disbursement 
                                          WHERE StatusId = 1
      ");
      $data = $query->result_array();
      return $data;
    }

    function getRepaymentCycle()
    {
      $query = $this->db->query("SELECT   CASE
                                          WHEN RHC.RepaymentId IS NULL
                                                THEN RC.Type
                                                ELSE GROUP_CONCAT(RHC.Date)
                                              END as Name
                                            , RC.RepaymentId
                                          FROM r_repaymentcycle RC
                                              LEFT JOIN  repaymentcycle_has_content RHC
                                                  ON RC.RepaymentId = RHC.RepaymentId
                                                    WHERE RC.StatusId = 1
                                                    OR RHC.StatusId = 1
                                                    GROUP BY RC.RepaymentId
      ");
      $data = $query->result_array();
      return $data;
    }

    function getTotalApprovers($Id)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT  (SELECT COUNT(*) 
                                                FROM application_has_approver
                                                  WHERE ApplicationId = A.ApplicationId
                                                  AND StatusId = 5
                                          ) as PendingApprovers
                                          , (SELECT COUNT(*) 
                                                FROM application_has_approver
                                                  WHERE ApplicationId = A.ApplicationId
                                                  AND StatusId = 3
                                          ) as ProcessedApprovers
                                          FROM T_Application A
                                            INNER JOIN R_Loans L 
                                              ON L.LoanId = A.LoanId
                                            INNER JOIN R_Borrowers B
                                              ON B.BorrowerId = A.BorrowerId
                                            INNER JOIN Application_has_interests AHI
                                              ON AHI.ApplicationId = A.ApplicationId
                                            INNER JOIN Application_Has_Status LS
                                              ON A.StatusId = LS.LoanStatusId
                                            LEFT JOIN R_RepaymentCycle RC
                                              ON RC.RepaymentId = A.RepaymentId
                                                WHERE A.ApplicationId = $Id
      ");

      $data = $query->row_array();
      return $data;
    }







}