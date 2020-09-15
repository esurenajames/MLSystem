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
                                          ELSE DATE_FORMAT(A.DateApproved, '%b %d, %Y')
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
                                      , A.TermNo as TermNo
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
                                        WHERE A.ApplicationId = $Id
                                        AND AHI.StatusId = 1
                                        AND BHE.IsPrimary = 1
                                        AND BHE.StatusId = 1
                                        AND BHC.IsPrimary = 1
                                        AND BHC.StatusId = 1
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
    $query = $this->db->query("SELECT   SUM(C.Amount) as TotalCharges
                                        FROM Application_Has_Charges AHC
                                          INNER JOIN R_Charges C
                                            ON C.ChargeId = AHC.ChargeId
                                          WHERE ApplicationId = $Id
                                          AND AHC.StatusId = 1
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
                                              , L.Name
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
                                                    FROM application_has_approvers
                                                      WHERE ApplicationId = A.ApplicationId
                                                      AND StatusId = 5
                                              ) as PendingApprovers
                                              , (SELECT COUNT(*) 
                                                    FROM application_has_approvers
                                                      WHERE ApplicationId = A.ApplicationId
                                                      AND StatusId != 6
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
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function displayLoanHistory($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  A.ApplicationId
                                              , AHN.Description
                                              , AHN.CreatedBy
                                              , DATE_FORMAT(AHN.DateCreated, '%b %d, %Y %r') as DateCreated
                                              FROM Application_has_notifications AHN
                                                INNER JOIN T_Application A
                                                  ON A.ApplicationId = AHN.ApplicationId
                                                    WHERE A.ApplicationId = $ID
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function getApprovers($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as ApproverName
                                              , AHA.ApplicationApprovalId
                                              , S.Description
                                              , S.StatusId
                                              FROM t_application A
                                                    INNER JOIN application_has_approvers AHA
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

}