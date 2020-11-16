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
    $AssignedBranchId = $this->session->userdata('BranchId');
    $query = $this->db->query("SELECT BHP.FileName
                                      , CONCAT(BS.Name, ' ', B.FirstName, ' ', B.MiddleName, ' ', B.LastName, ', ', B.ExtName) as Name
                                      , B.BorrowerNumber
                                      , DATE_FORMAT(B.DateOfBirth, '%b %d, %Y') as DOB
                                      , TIMESTAMPDIFF(YEAR, B.DateOfBirth, CURDATE()) as Age
                                      , CN.Number as ContactNumber
                                      , A.ForRestructuring
                                      , A.RestructureFee
                                      , E.EmailAddress
                                      , A.Source
                                      , CASE
                                          WHEN A.SourceName = ''
                                          THEN ''
                                          ELSE CONCAT('- ',  A.SourceName)
                                        END as SourceName
                                      , P.Name as PurposeName
                                      , A.BorrowerMonthlyIncome
                                      , COALESCE(A.SpouseMonthlyIncome, 0) SpouseMonthlyIncome
                                      , A.RiskLevel
                                      , A.RiskAssessment
                                      , B.Dependents

                                      , A.PrincipalAmount/(A.TermNo * A.RepaymentNo) as PrincipalPerCollection
                                      , C.name as CivilStatus
                                      , CASE
                                          WHEN AHI.InterestType = 'Percentage'
                                          THEN AHI.Amount/100 * PrincipalAmount
                                          ELSE PrincipalAmount + AHI.Amount
                                        END / (A.TermNo * A.RepaymentNo) as totalInterestPerCollection
                                      , DATE_FORMAT(B.DateCreated, '%b %d, %Y') as DateCreated
                                      , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as CreatedBy
                                      , EMP.EmployeeNumber as EmployeeCreator
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
                                      , B.FirstName
                                      , B.LastName
                                      , B.ExtName
                                      , B.MiddleName
                                      , BE.BranchId
                                      , DATE_FORMAT(B.DateOfBirth, '%m-%d-%Y') as ReportDOB
                                      , LU.Description as LoanUndertaking
                                      , (SELECT DISTINCT ApproverNumber FROM application_has_approver WHERE ApplicationId = A.ApplicationId AND StatusId = 5 ORDER BY ApplicationApprovalId LIMIT 1) as CurrentApprover
                                      FROM T_Application A
                                        INNER JOIN Application_Has_Status LS
                                          ON A.StatusId = LS.LoanStatusId
                                        INNER JOIN R_Loans L 
                                          ON L.LoanId = A.LoanId
                                        INNER JOIN R_Disbursement D
                                          ON D.DisbursementId = A.DisbursementId
                                        INNER JOIN R_LoanUndertaking LU
                                          ON LU.UndertakingId = A.UndertakingId
                                        LEFT JOIN R_Borrowers B
                                          ON B.BorrowerId = A.BorrowerId
                                        INNER JOIN r_civilstatus C
                                          ON C.CivilStatusId = B.CivilStatus
                                        LEFT JOIN Borrower_Has_Picture BHP
                                          ON BHP.BorrowerId = B.BorrowerId
                                        LEFT JOIN R_Salutation BS
                                          ON BS.SalutationId = B.Salutation
                                        LEFT JOIN R_Employee EMP
                                          ON EMP.EmployeeNumber = A.CreatedBy
                                        LEFT JOIN Branch_has_Employee BE
                                          ON BE.EmployeeNumber = EMP.EmployeeNumber
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

  function getProvinceAddress($Id)
  {
    $query_string = $this->db->query("SELECT  MAX(A.AddressId) as AddressId
                                              , BAH.AddressType
                                              , BAH.YearsStayed
                                              , BAH.MonthsStayed
                                              , BAH.NameOfLandlord
                                              , A.HouseNo
                                              , BA.BrgyDesc
                                              FROM R_Borrowers B
                                                INNER JOIN borrowerAddressHistory BAH
                                                  ON B.BorrowerId = BAH.BorrowerId
                                                INNER JOIN R_Address A
                                                  ON A.AddressId = BAH.AddressId
                                                INNER JOIN add_barangay BA
                                                  ON BA.BrgyCode = A.BarangayId
                                                WHERE B.BorrowerId = $Id
                                                AND A.AddressType = 'Province Address'
                                                AND BAH.StatusId = 1

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
                                              , A.HouseNo
                                              , BA.BrgyDesc
                                              , A.Telephone
                                              , A.ContactNumber
                                              FROM R_Borrowers B
                                                INNER JOIN borrowerAddressHistory BAH
                                                  ON B.BorrowerId = BAH.BorrowerId
                                                INNER JOIN R_Address A
                                                  ON A.AddressId = BAH.AddressId
                                                INNER JOIN add_barangay BA
                                                  ON BA.BrgyCode = A.BarangayId
                                                WHERE B.BorrowerId = $Id
                                                AND BAH.IsPrimary = 1
                                                AND A.AddressType = 'City Address'
                                                AND BAH.StatusId = 1
                                                AND BAH.IsPrimary = 1

    ");
    $data = $query_string->row_array();
    return $data;
  }

  function getProvinceAddressSpouse($Id)
  {
    $query_string = $this->db->query("SELECT  MAX(A.AddressId) as AddressId
                                              , BAH.AddressType
                                              , BAH.YearsStayed
                                              , BAH.MonthsStayed
                                              , BAH.NameOfLandlord
                                              , A.HouseNo
                                              , BA.BrgyDesc
                                              FROM R_Spouse B
                                                INNER JOIN borrowerAddressHistory BAH
                                                  ON B.BorrowerId = BAH.BorrowerId
                                                INNER JOIN R_Address A
                                                  ON A.AddressId = BAH.AddressId
                                                INNER JOIN add_barangay BA
                                                  ON BA.BrgyCode = A.BarangayId
                                                WHERE B.SpouseId = $Id
                                                AND A.AddressType = 'Province Address'
                                                AND BAH.StatusId = 1

    ");
    $data = $query_string->row_array();
    return $data;
  }

  function getCityAddressSpouse($Id)
  {
    $query_string = $this->db->query("SELECT  A.AddressId
                                              , BAH.AddressType
                                              , BAH.YearsStayed
                                              , BAH.MonthsStayed
                                              , BAH.NameOfLandlord
                                              , BAH.IsPrimary
                                              , A.HouseNo
                                              , BA.BrgyDesc
                                              , A.Telephone
                                              , A.ContactNumber
                                              FROM R_Spouse B
                                                INNER JOIN borrowerAddressHistory BAH
                                                  ON B.BorrowerId = BAH.BorrowerId
                                                INNER JOIN R_Address A
                                                  ON A.AddressId = BAH.AddressId
                                                INNER JOIN add_barangay BA
                                                  ON BA.BrgyCode = A.BarangayId
                                                WHERE B.SpouseId = $Id
                                                AND BAH.IsPrimary = 1
                                                AND A.AddressType = 'City Address'
                                                AND BAH.StatusId = 1
                                                AND BAH.IsPrimary = 1

    ");
    $data = $query_string->row_array();
    return $data;
  }

  function getEmployer($Id, $status)
  {
    $query_string = $this->db->query("SELECT  EmployerName
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
                                                    AND EmployerStatus = $status
                                                    AND BHE.StatusId = 1
    ");
    $data = $query_string->row_array();
    return $data;
  }

  function getReferences($BorrowerId)
  {
    $query_string = $this->db->query("SELECT  Name
                                              , Address
                                              , ContactNumber
                                              FROM Borrower_has_reference BN
                                                INNER JOIN r_employee EMP
                                                  ON EMP.EmployeeNumber = BN.CreatedBy
                                                    WHERE BorrowerId = $BorrowerId
                                                    AND BN.StatusId = 1
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function getHouseholdMoney($AppId, $tableName)
  {
    $query_string = $this->db->query("SELECT  COALESCE(SUM(Amount) ,0) as Total
                                              FROM $tableName
                                                    WHERE StatusId = 1
                                                      AND ApplicationId = $AppId
    ");
    $data = $query_string->row_array();
    return $data;
  }

  function getCoMaker($borrowerId)
  {
    $query_string = $this->db->query("SELECT  BHC.Name
                                              , DATE_FORMAT(BHC.Birthdate, '%m/%b/%Y') as DateOfBirth
                                              , Employer
                                              , BusinessAddress
                                              , P.Name as PositionName
                                              , TenureYear
                                              , TenureMonth
                                              , TelephoneNo
                                              , BusinessNo
                                              , MobileNo
                                              , MonthlyIncome
                                              FROM borrower_has_comaker BHC
                                                INNER JOIN R_Position P
                                                  ON P.PositionId = BHC.PositionId
                                                WHERE BorrowerId = $borrowerId

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
                                                AND B.StatusId = 1

    ");

    $data = $query_string->row_array();
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

  function getPaymentsMade($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT Amount
                                      , CONCAT('PYM-', LPAD(P.PaymentMadeId, 6, 0)) as ReferenceNo
                                      , DATE_FORMAT(P.DateCreated, '%b %d, %Y %r') as DateCreated
                                      , DATE_FORMAT(P.DateCollected, '%b %d, %Y') as DateCollected
                                      , DATE_FORMAT(P.PaymentDate, '%b %d, %Y') as PaymentDate
                                      , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName) as CreatedBy
                                      , P.StatusId
                                      , B.BankName
                                      , P.Description 
                                      , PaymentMadeId
                                      FROM t_paymentsmade P
                                        INNER JOIN R_Employee EMP
                                          ON EMP.EmployeeNumber = P.CreatedBy
                                        INNER JOIN R_Bank B
                                          ON B.BankId = P.BankId
                                            WHERE P.ApplicationId = $Id
    ");

    $data = $query->result_array();
    return $data;
  }

  function getInterestPaid($Id)
  {
    $query = $this->db->query("SELECT DISTINCT  COALESCE(SUM(InterestAmount), 0) as Total
                                                FROM T_PAYMENTSMADE 
                                                  WHERE APPLICATIONID = $Id 
                                                  AND ISINTEREST = 1 
                                                  AND STATUSID = 1
    ");

    $data = $query->row_array();
    return $data;
  }

  function getPrincipalPaid($Id)
  {
    $query = $this->db->query("SELECT DISTINCT  COALESCE(SUM(PrincipalAmount), 0) as Total
                                                FROM T_PAYMENTSMADE 
                                                  WHERE APPLICATIONID = $Id 
                                                  AND IsPrincipalCollection = 1 
                                                  AND STATUSID = 1
    ");

    $data = $query->row_array();
    return $data;
  }

  function getBalance($Id)
  {
    $query = $this->db->query("SELECT DISTINCT  COALESCE(SUM(AMOUNT), 0) as Total
                                                FROM T_PAYMENTSMADE 
                                                  WHERE APPLICATIONID = $Id 
                                                  AND IsPrincipalCollection = 1 
                                                  AND STATUSID = 1
    ");

    $data = $query->row_array();
    return $data;
  }

  function getOtherPaid($Id)
  {
    $query = $this->db->query("SELECT DISTINCT  COALESCE(SUM(AMOUNT), 0) as Total
                                                FROM T_PAYMENTSMADE 
                                                  WHERE APPLICATIONID = $Id 
                                                  AND IsOthers = 1 
                                                  AND STATUSID = 1
    ");

    $data = $query->row_array();
    return $data;
  }

  function getTotalDisbursed($Id)
  {
    $query = $this->db->query("SELECT DISTINCT  COALESCE(SUM(AMOUNT), 0) as Total
                                                FROM Application_has_Disbursement 
                                                  WHERE APPLICATIONID = $Id
                                                  AND STATUSID = 1
    ");

    $data = $query->row_array();
    return $data;
  }

  function getCharges($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT DISTINCT SUM(CASE
                                            WHEN C.ChargeType = 1
                                                  THEN C.Amount
                                                  ELSE C.Amount/100 * A.PrincipalAmount
                                              END
                                        ) as TotalCharges
                                        , C.Amount
                                        , C.Name as ChargeName
                                        FROM Application_Has_Charges AHC
                                          INNER JOIN R_Charges C
                                            ON C.ChargeId = AHC.ChargeId
                                          INNER JOIN t_application A
                                            ON A.ApplicationId = AHC.ApplicationId
                                          WHERE A.ApplicationId = 5
                                          AND AHC.StatusId = 2
    ");

    $data = $query->row_array();
    return $data;
  }

  function getBorrowerList()
  {
    $AssignedBranchId = $this->session->userdata('BranchId');
    $query = $this->db->query("SELECT   CONCAT(FirstName, ' ', MiddleName, ' ', LastName, CASE WHEN ExtName != '' THEN CONCAT(', ', ExtName) ELSE '' END ) as Name
                                        , BorrowerId
                                        FROM R_Borrowers
                                          WHERE StatusId = 1
                                          AND BranchId = $AssignedBranchId
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

  function getRequirementReport($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT R.RequirementId
                                      , R.Name
                                      FROM Application_has_Requirements AHR
                                        INNER JOIN R_Requirements R
                                          ON R.RequirementId = AHR.RequirementId
                                            WHERE AHR.ApplicationId = $Id
                                            AND AHR.StatusId = 5
    ");

    $data = $query->result_array();
    return $data;
  }

  function getApproversReport($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName) as Name
                                      , S.Description
                                      , DATE_FORMAT(AHA.DateUpdated, '%b %d, %Y') as DateUpdated
                                      , CONCAT(EMP2.FirstName, ' ', EMP2.MiddleName, ' ', EMP2.LastName) as ProcessedBy
                                      FROM application_has_approver AHA
                                        INNER JOIN r_status S
                                          ON S.StatusId = AHA.StatusId
                                        INNER JOIN r_employee EMP
                                          ON EMP.EmployeeNumber = AHA.ApproverNumber
                                        LEFT JOIN r_employee EMP2
                                          ON EMP2.EmployeeNumber = AHA.ApprovedBy
                                        WHERE ApplicationId = $Id
                                        AND 
                                        (
                                            AHA.StatusId = 3
                                            OR
                                            AHA.StatusId = 4
                                            OR
                                            AHA.StatusId = 5
                                        )
    ");

    $data = $query->result_array();
    return $data;
  }

  function getRequirementForApplication()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT   DISTINCT RequirementId
                                        , Name
                                        , Description
                                        , IsMandatory
                                        FROM r_requirements
                                          WHERE StatusId = 1
    ");

    $data = $query->result_array();
    return $data;
  }

  function getRequirementSelected($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT   DISTINCT RequirementId
                                        FROM application_has_requirements
                                          WHERE StatusId = 1
                                          AND ApplicationId = $ID
    ");

    $data = $query->result_array();
    return $data;
  }

  function getChargeDetails($Id, $Type)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    if($Type == 1) // display detail
    {
      $query = $this->db->query("SELECT C.ChargeId
                                        , C.Name
                                        , C.Amount
                                        , C.ChargeType
                                        FROM R_Charges C
                                              WHERE ChargeId = $Id
      ");
    }
    else // display existing
    {
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
                                            WHEN C.ChargeType = 1
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
    }

    $data = $query->row_array();
    return $data;
  }

  function getPenalties($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT   COALESCE(SUM(AHP.TotalPenalty), 0) as Total
                                        FROM Application_Has_Penalty AHP
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
                                          AND 
                                          (
                                            IsPrincipalCollection = 1
                                            OR IsInterest = 1
                                            OR isOthers = 1
                                          )
                                          AND AHP.StatusId = 1
    ");

    $data = $query->row_array();
    return $data;
  }

  function displayAllLoans()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $AssignedBranchId = $this->session->userdata('BranchId');
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
                                              , LS.StatusColor
                                              , (SELECT COUNT(*) 
                                                    FROM application_has_approver
                                                      WHERE ApplicationId = A.ApplicationId
                                                      AND 
                                                    (
                                                          StatusId = 1
                                                          OR
                                                          StatusId = 2
                                                      )
                                              ) as ProcessedApprovers
                                              , (SELECT COUNT(*) 
                                                    FROM application_has_approver
                                                      WHERE ApplicationId = A.ApplicationId
                                                      AND StatusId != 6
                                              ) as TotalApprovers
                                              , (SELECT MAX(DATE_FORMAT(DateCreated, '%b %d, %Y'))
                                                        FROM t_paymentsmade
                                                          WHERE ApplicationId = A.ApplicationId
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
                                                LEFT JOIN R_Employee EMP
                                                  ON EMP.EmployeeNumber = A.CreatedBy
                                                LEFT JOIN Branch_has_Employee BE
                                                  ON BE.EmployeeNumber = EMP.EmployeeNumber
                                                    WHERE BE.BranchId = $AssignedBranchId
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function displayBorrowerLoans($Id)
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
                                                    WHERE B.BorrowerId = $Id
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function displayAllApprovals()
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
                                              ) as TotalApprovers
                                              , (SELECT COUNT(*) 
                                                    FROM application_has_approver
                                                      WHERE ApplicationId = A.ApplicationId
                                                      AND StatusId = 1
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
                                                  WHERE A.StatusId = 3
                                                  AND (SELECT COUNT(*) 
                                                    FROM application_has_approver
                                                      WHERE ApplicationId = A.ApplicationId
                                                      AND ApproverNumber = '$EmployeeNumber'
                                                    ) > 0
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function getCollections($dateFrom, $dateTo, $columns, $query)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  $columns
                                              , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as CollectedBy
                                              , DATE_FORMAT(PM.DateCollected, '%b %d, %Y') as PaymentDate
                                              , PM.DateCollected
                                              , A.ApplicationId
                                              , PM.Description
                                              , PM.AmountPaid as AmountPaid
                                              , BNK.BankName
                                              , DATE_FORMAT(PM.DateCollected, '%b %d, %Y') as dateCollected
                                              , DATE_FORMAT(PM.dateCreated, '%b %d, %Y %r') as dateCreated

                                              , PM.ChangeAmount as rawChangeAmount
                                              , PM.AmountPaid as rawAmountPaid
                                              , PM.InterestAmount as rawInterestCollection
                                              , PM.PrincipalAmount as rawPrincipalCollection

                                              FROM t_paymentsmade PM
                                                INNER JOIN t_application A
                                                    ON A.ApplicationId = PM.ApplicationId
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = A.BorrowerId
                                                  INNER JOIN r_employee EMP
                                                    ON EMP.EmployeeNumber = PM.CreatedBy
                                                  INNER JOIN R_Bank BNK
                                                    ON BNK.BankId = PM.ChangeId
                                                    WHERE A.StatusId = 1
                                                    AND PM.StatusId = 1
                                                    AND DATE_FORMAT(PM.DateCollected, '%Y-%m-%d') BETWEEN  DATE_FORMAT('$dateFrom', '%Y-%m-%d') AND DATE_FORMAT('$dateTo', '%Y-%m-%d')
                                                    $query
                                                    ORDER BY PM.DateCollected DESC
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function getCollectionsManagement($dateFrom, $dateTo)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName) as CollectedBy
                                              , TransactionNumber
                                              , CONCAT(B.FirstName, ' ', B.MiddleName, ' ', B.LastName) as BorrowerName
                                              , FORMAT(A.PrincipalAmount, 2) as LoanAmount
                                              , FORMAT(PM.AmountPaid, 2) as AmountPaid
                                              , DATE_FORMAT(PM.DateCollected, '%b %d, %Y') as PaymentDate
                                              , PM.DateCollected
                                              , A.ApplicationId
                                              , PM.Description
                                              , BNK.BankName
                                              , DATE_FORMAT(PM.DateCollected, '%b %d, %Y') as dateCollected
                                              , DATE_FORMAT(PM.dateCreated, '%b %d, %Y %r') as dateCreated

                                              , PM.ChangeAmount as rawChangeAmount
                                              , PM.AmountPaid as rawAmountPaid
                                              , PM.InterestAmount as rawInterestCollection
                                              , PM.PrincipalAmount as rawPrincipalCollection

                                              FROM t_paymentsmade PM
                                                INNER JOIN t_application A
                                                    ON A.ApplicationId = PM.ApplicationId
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = A.BorrowerId
                                                  INNER JOIN r_employee EMP
                                                    ON EMP.EmployeeNumber = PM.CreatedBy
                                                  INNER JOIN R_Bank BNK
                                                    ON BNK.BankId = PM.ChangeId
                                                    WHERE A.StatusId = 1
                                                    AND PM.StatusId = 1
                                                    ORDER BY PM.DateCollected DESC
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function getExpensesReport($dateFrom, $dateTo, $query)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  CONCAT('EX-', LPAD(EX.ExpenseId, 6, 0)) as ReferenceNo
                                              , EXT.Name
                                              , EX.Amount
                                              , DATE_FORMAT(EX.DateExpense, '%b %d, %Y') as DateExpense
                                              , DATE_FORMAT(EX.DateCreated, '%b %d, %Y') as DateCreated
                                              , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName) as CreatedBy
                                              FROM R_Expense EX
                                                    INNER JOIN r_expensetype EXT
                                                        ON EXT.ExpenseTypeId = EX.ExpenseTypeId
                                                      INNER JOIN r_employee EMP
                                                        ON EMP.EmployeeNumber = EX.CreatedBy
                                                          WHERE EX.StatusId = 1
                                                          AND DATE_FORMAT(EX.DateExpense, '%Y-%m-%d') BETWEEN  DATE_FORMAT('$dateFrom', '%Y-%m-%d') AND DATE_FORMAT('$dateTo', '%Y-%m-%d')
                                                          $query
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

  function DisplayPenalty($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT  TotalPenalty
                                    , CONCAT('PNT-', LPAD(ApplicationPenaltyId, 6, 0)) as ReferenceNo
                                    , PenaltyType
                                    , Amount
                                    , GracePeriod
                                    , ApplicationPenaltyId
                                    , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as CreatedBy
                                    , AHP.StatusId
                                    , DATE_FORMAT(AHP.DateCreated, '%b %d, %Y %r') as DateCreated
                                      FROM Application_has_Penalty AHP
                                        INNER JOIN r_status S
                                          ON S.StatusId = AHP.StatusId
                                        INNER JOIN R_Employee EMP
                                          ON EMP.EmployeeNumber = AHP.CreatedBy
                                        WHERE AHP.ApplicationId = $Id
    ");

    $data = $query->result_array();
    return $data;
  }

  function getLoanComments($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  CONCAT('COM-', LPAD(CommentId, 6, 0)) as ReferenceNo
                                              , AC.ApplicationId
                                              , AC.CommentId
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
    $query_string = $this->db->query("SELECT  CONCAT('REQ-', LPAD(AR.RequirementId, 4, 0)) as ReferenceNo
                                              , AR.ApplicationId
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

  function getDisbursementDisplay($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  CONCAT('DIS-', LPAD(AHD.DisbursementId, 6, 0)) as ReferenceNo
                                              , DATE_FORMAT(AHD.DateCreated, '%b %d, %Y') as DateCreated
                                              , AHD.Amount
                                              , AHD.Description
                                              , AHD.ApplicationId
                                              , AHD.DisbursementId
                                              , AHD.StatusId
                                              , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as Name
                                              , D.Name as DisbursedThrough
                                              FROM Application_has_Disbursement AHD
                                                LEFT JOIN R_Employee EMP
                                                  ON EMP.EmployeeNumber = AHD.CreatedBy
                                                LEFT JOIN R_Disbursement D
                                                  ON D.DisbursementId = AHD.DisbursedBy
                                                LEFT JOIN r_status S
                                                  ON S.StatusId = AHD.StatusId
                                                    WHERE AHD.ApplicationId = $ID
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function getIncome($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  CONCAT('INC-', LPAD(AI.IncomeId, 4, 0)) as ReferenceNo
                                              , AI.ApplicationId
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

  function getDisbursements($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  CONCAT('DIS-', LPAD(C.CollateralId, 4, 0)) as ReferenceNo
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

  function displayCharges($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  CONCAT('CHR-', LPAD(AHC.ApplicationChargeId, 4, 0)) as ReferenceNo
                                              , C.ChargeId
                                              , AHC.ApplicationChargeId
                                              , C.Name
                                              , C.Amount
                                              , C.StatusId
                                              , A.PrincipalAmount
                                              , C.ChargeType
                                              , C.IsMandatory
                                              , S.Description
                                              , CASE
                                                  WHEN C.ChargeType = 1
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
                                                INNER JOIN R_Status S
                                                  ON S.StatusId = AHC.StatusId
                                                LEFT JOIN R_Employee EMP
                                                  ON EMP.EmployeeNumber = AHC.CreatedBy
                                                    WHERE AHC.ApplicationId = $ID
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function getRepaymentCount($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT   COUNT(*) as RepaymentNo
                                        FROM repaymentcycle_has_content RHC
                                          INNER JOIN R_RepaymentCycle RC
                                            ON RHC.RepaymentId = RC.RepaymentId
                                              WHERE RC.RepaymentId = $Id
                                              AND RHC.StatusId = 1
    ");

    $data = $query->row_array();
    return $data;
  }

  function getExpenses($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  CONCAT('EXP-', LPAD(AE.ExpenseId, 4, 0)) as ReferenceNo
                                              , AE.ApplicationId
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
    $query_string = $this->db->query("SELECT  CONCAT('OBL-', LPAD(AO.MonthlyObligationId, 4, 0)) as ReferenceNo
                                              , AO.ApplicationId
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

  function countDisbursement($data)
  {
    $query_string = $this->db->query("SELECT  * 
                                              FROM Application_has_Disbursement
                                                WHERE Description = '".$data['Description']."'
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

  function getDetails($Type, $Id)
  {
    if($Type == 1) // collection
    {
      $query_string = $this->db->query("SELECT  DATE_FORMAT(PM.DateCreated, '%b %d, %Y') as PaymentDate
                                              , DATE_FORMAT(PM.DateCollected, '%b %d, %Y') as DateCollected
                                              , AmountPaid
                                              , ChangeAmount
                                              , CB.BankName as ChangeThrough
                                              , PB.BankName as PaymentThrough
                                              , PM.Description
                                              , CONCAT('PYM-', LPAD(PM.PaymentMadeId, 6, 0)) as TransactionNumber 
                                              FROM t_paymentsmade PM
                                                    INNER JOIN r_bank CB
                                                        ON CB.BankId = PM.ChangeId
                                                    INNER JOIN r_bank PB
                                                        ON PB.BankId = PM.BankId
                                                            WHERE PM.PaymentMadeId = $Id
      ");
    }
    $detail = $query_string->row_array();
    return $detail;
  }

  function getPenaltyPaymentDetails($Id)
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

  function getDisbursementDetails($Id)
  {
    $query_string = $this->db->query("SELECT  ApplicationId
                                              , DisbursementId
                                              , Amount
                                              , Description
                                              FROM Application_has_Disbursement 
                                                WHERE DisbursementId = '$Id'
    ");
    $DisbursementDetail = $query_string->row_array();
    return $DisbursementDetail;
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
                                              , C.DateRegistered as rawDateRegistered
                                              , C.DateAcquired as rawDateAcquired
                                              , DATE_FORMAT(C.DateAcquired, '%b %d, %Y') as DateAcquired
                                              , C.RegistrationNo
                                              , C.Mileage
                                              , C.EngineNo
                                              , CT.Name as CollateralType
                                              , CS.Name as CollateralStatus
                                              FROM application_has_collaterals AHC
                                                INNER JOIN R_Collaterals C
                                                  ON C.CollateralId = AHC.CollateralId
                                                INNER JOIN R_CollateralType CT
                                                  ON CT.CollateralTypeId = C.CollateralTypeId
                                                INNER JOIN r_collateralStatus CS
                                                  ON CS.CollateralStatusId = C.StatusId
                                                WHERE C.CollateralId = $Id
    ");
    $CollateralDetail = $query_string->row_array();
    return $CollateralDetail;
  }

  function getApprovers($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT DISTINCT CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as ApproverName
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
                                                      AND AHA.StatusId != 6 
                                                      AND AHA.StatusId != 8 
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function getTenure($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT  COALESCE(AVG(TIMESTAMPDIFF(YEAR, DateHired, DateTo)), 0) as AvgYears
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
    else if($input['Type'] == 'Disbursements')
    {
      $DisbursementDetail = $this->db->query("SELECT  AHD.Description
                                                , ApplicationId
                                                  FROM Application_has_Disbursement AHD
                                                    WHERE DisbursementId = ".$input['Id']."
      ")->row_array();

      // update status
        $set = array(
          'StatusId' => $input['updateType'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'DisbursementId' => $input['Id']
        );
        $table = 'Application_has_Disbursement';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into Application_has_Notifications
        if($input['updateType'] == 0)
        {
          $DisbursementDescription = 'Re-activated ' .$DisbursementDetail['Description']. ' of ' .$DisbursementDetail['ApplicationId']. ' at the Disbursement tab '; // Application Notification
        }
        else if($input['updateType'] == 1)
        {
          $DisbursementDescription = 'Deactivated ' .$DisbursementDetail['Description']. '  of ' .$DisbursementDetail['ApplicationId']. ' at the Disbursement tab '; // Application Notification
        }
        $data3 = array(
          'Description'   => $DisbursementDescription,
          'ApplicationId' => $DisbursementDetail['ApplicationId'],
          'CreatedBy'     => $EmployeeNumber,
          'DateCreated'   => $DateNow
        );
        $this->db->insert('Application_has_Notifications', $data3);
      // insert into logs
        if($input['updateType'] == 2)
        {
          $DisbursementDescription = 'Re-activated ' .$DisbursementDetail['Source']. ' at the Disbursement tab'; // main log
        }
        else if($input['updateType'] == 6)
        {
          $DisbursementDescription = 'Deactivated ' .$DisbursementDetail['Source']. '  at the Disbursement tab'; // main log
        }
        $data2 = array(
          'Description'   => $DisbursementDescription,
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
    else if($input['Type'] == 'Charge')
    {
      $ChargeDetail = $this->db->query("SELECT  ApplicationChargeId
                                                , ApplicationId
                                                  FROM Application_Has_Charges
                                                    WHERE ApplicationChargeId = ".$input['Id']."
      ")->row_array();

      // update status
        $set = array(
          'StatusId' => $input['updateType'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'ApplicationChargeId' => $input['Id']
        );
        $table = 'Application_Has_Charges';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // insert into logs
        if($input['updateType'] == 7)
        {
          $Description = 'Re-activated ' .$ChargeDetail['ApplicationChargeId']. ' of Application #'; // main log
        }
        else if($input['updateType'] == 6)
        {
          $Description = 'Deactivated ' .$ChargeDetail['ApplicationChargeId']. '  of Application #'; // main log
        }
        $data2 = array(
          'Description'   => $Description,
          'CreatedBy'     => $EmployeeNumber,
          'DateCreated'   => $DateNow
        );
        $this->db->insert('R_Logs', $data2);
        // insert into Application_has_Notifications
        if($input['updateType'] == 7)
        {
          $Description = 'Re-activated ' .$ChargeDetail['ApplicationChargeId']. ' of Charge # '; // Application Notification
        }
        else if($input['updateType'] == 6)
        {
          $Description = 'Deactivated ' .$ChargeDetail['ApplicationChargeId']. '  of Charge #'; // Application Notification
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
                                          WHERE R.StatusId = 1 
                                          AND requirementId NOT IN (SELECT RequirementId FROM Application_has_Requirements AR WHERE AR.ApplicationId = $Id )
    ");
    $output = '<option selected disabled value="">Select Requirement Type</option>';
    foreach ($query->result() as $row)
    {
      $output .= '<option value="'.$row->RequirementId.'">'.$row->Name.'</option>';
    }
    return $output;
  } 
  
  function checkEmployeeApprover($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT   * 
                                        FROM application_has_approver
                                              WHERE ApproverNumber = '$EmployeeNumber'
                                                AND StatusId = 5
                                                AND ApplicationId = $Id
    ");

    $data = $query->num_rows();
    return $data;
  } 

  function getBank()
  {
    $query = $this->db->query("SELECT BNK.BankName as Name
                                              , BankId
                                              , BNK.Description
                                              , BNK.AccountNumber
                                              FROM R_Bank BNK
                                              WHERE StatusId = 1
    ");
    $output = '<option selected disabled value="">Select Bank</option>';
    foreach ($query->result() as $row)
    {
      $output .= '<option value="'.$row->BankId.'">'.$row->Name.'</option>';
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
  
  function selectChanges($Id)
  {
    $query = $this->db->query("SELECT BankId
                                      , BankName
                                      FROM R_Bank
                                        WHERE StatusId = 1
    ");
    $output = '<option selected disabled value="">Select Change Sent Through</option>';
    foreach ($query->result() as $row)
    {
      $output .= '<option value="'.$row->BankId.'">'.$row->BankName.'</option>';
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

    // function getDisbursements()
    // {
    //   $query = $this->db->query("SELECT   DIS.Name
    //                                       , DIS.DisbursementId
    //                                       FROM r_disbursement DIS
    //                                       WHERE DIS.StatusId = 1
    //   ");
    //   $data = $query->result_array();
    //   return $data;
    // }

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

    function getRepaymentDets($AppId, $RepaymentId)
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
                                                    WHERE RC.RepaymentId = $RepaymentId 
                                                    (
                                                      RC.StatusId = 1
                                                      OR 
                                                      RHC.StatusId = 1
                                                    )
                                                    GROUP BY RC.RepaymentId
      ");
      $data = $query->row_array();
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

    function getProcessedApprovers($Id)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT DISTINCT COUNT(DISTINCT ApproverNumber) as Total
                                        FROM application_has_approver
                                          WHERE ApplicationId = $Id
                                          AND StatusId = 3
      ");

      $data = $query->row_array();
      return $data;
    }

    function getPendingApprovers($Id)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT DISTINCT COUNT(DISTINCT ApproverNumber)  as Total
                                        FROM application_has_approver
                                          WHERE ApplicationId = $Id
                                          AND StatusId = 5
      ");

      $data = $query->row_array();
      return $data;
    }

    function getPaymentsMaid($Id)
    {
      $query = $this->db->query("SELECT DATE_FORMAT(DateCollected, '%d') as DatePaid 
                                        , Amount
                                        FROM t_paymentsmade 
                                              WHERE ApplicationId = $Id 
                                              AND StatusId = 1
      ");
      $data = $query->result_array();
      return $data;
    }

    function getPaymentDates($Id)
    {
      $query = $this->db->query("SELECT DISTINCT Date as Dates
                                        FROM r_repaymentcycle RC
                                          INNER JOIN repaymentcycle_has_content RHC
                                            ON RHC.RepaymentId = RC.RepaymentId
                                          INNER JOIN t_application A
                                            ON A.RepaymentId = RC.RepaymentId
                                            WHERE A.ApplicationId = $Id
                                            AND RC.StatusId = 1
      ");
      $data = $query->result_array();
      return $data;
    }

    function getDue($Id)
    {
      $query = $this->db->query("SELECT  A.TermNo * A.RepaymentNo as TotalCollections
                                        , A.PrincipalAmount
                                        , A.TermNo * AHI.Amount as AddOnInterest
                                        , CASE
                                          WHEN AHI.InterestType = 'Percentage'
                                            THEN A.PrincipalAmount * (A.TermNo * AHI.Amount) / 100
                                            ELSE AHI.Amount + A.PrincipalAmount
                                        END as TotalInterest  
                                        , A.PrincipalAmount / (A.TermNo * A.RepaymentNo) as PrincipalPerCollection
                                        , CASE
                                          WHEN AHI.InterestType = 'Percentage'
                                            THEN A.PrincipalAmount * (A.TermNo * AHI.Amount) / 100
                                            ELSE AHI.Amount + A.PrincipalAmount
                                        END / (A.TermNo * A.RepaymentNo) as InterestPerCollection
                                        FROM t_application A
                                              INNER JOIN application_has_interests AHI
                                                  ON A.ApplicationId = AHI.ApplicationId
                                                    AND AHI.StatusId = 2
                                              WHERE A.ApplicationId = $Id
      ");
      $data = $query->row_array();
      return $data;
    }

    function getSubmittedReqs($BorrowerId)
    {
      $query_string = $this->db->query("SELECT  DISTINCT IdentificationId
                                                FROM borrower_has_supportdocuments
                                                  WHERE StatusId = 1
                                                  AND BorrowerId = $BorrowerId
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getSelectedApprovers($Id)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query_string = $this->db->query("SELECT  CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as ApproverName
                                                , EMP.EmployeeNumber
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
                                                    WHERE A.ApplicationId = $Id
                                                    AND AHA.StatusId != 6
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getYearFilter($table)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT DATE_FORMAT(DateCreated, '%Y') as Year
                                                FROM R_Borrowers
                                                  WHERE BranchId = $AssignedBranchId
                                                  GROUP BY DATE_FORMAT(DateCreated, '%Y')
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getLoansYear()
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT DATE_FORMAT(BE.DateCreated, '%Y') as Year
                                                FROM R_Employee EMP
                                                  INNER JOIN Branch_has_Employee BE
                                                    ON BE.EmployeeNumber = EMP.EmployeeNumber
                                                  WHERE BE.BranchId = $AssignedBranchId
                                                  GROUP BY DATE_FORMAT(BE.DateCreated, '%Y')
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAge($Year, $query)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT COUNT(BorrowerId) as TotalBorrowers
                                                    FROM r_borrowers
                                                      WHERE DATE_FORMAT(DateCreated, '%Y') = DATE_FORMAT(STR_TO_DATE('$Year','%Y'), '%Y')
                                                      AND $query
                                                      AND BranchId = $AssignedBranchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getEducation()
    {
      $query_string = $this->db->query("SELECT  EducationId
                                                , Name
                                                  FROM r_education
                                                    WHERE StatusId = 1
                                                    ORDER BY EducationId ASC
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getEducationYearly($Year, $ID)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT COUNT(B.BorrowerId) as TotalBorrowers
                                                , ED.Name as Level
                                                FROM r_education ED
                                                  LEFT JOIN borrower_has_education BHE
                                                    ON BHE.EducationId = ED.EducationId
                                                  LEFT JOIN R_Borrowers B
                                                    ON B.BorrowerId =  BHE.BorrowerId
                                                      AND BHE.StatusId = 1
                                                      AND DATE_FORMAT(B.DateCreated, '%Y') = '$Year'
                                                      AND ED.EducationId = $ID
                                                      AND B.BranchId = $AssignedBranchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getSex()
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  SexId
                                                , Name
                                                FROM R_Sex
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getSexYearly($Year, $ID)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  COUNT(BorrowerId) as TotalBorrowers
                                                FROM R_Borrowers B
                                                  WHERE DATE_FORMAT(B.DateCreated, '%Y') = '$Year'
                                                  AND Sex = $ID
                                                  AND B.StatusId = 1
                                                  AND B.BranchId = $AssignedBranchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getOccupation()
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  OccupationId as Id
                                                , Name
                                                FROM r_occupation
                                                  WHERE StatusId = 1
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getOccupationYearly($Year, $ID)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  COUNT(B.BorrowerId) as TotalBorrowers
                                                FROM R_Borrowers B
                                                  INNER JOIN borrower_has_employer BE
                                                    ON BE.BorrowerId = B.BorrowerId
                                                  WHERE DATE_FORMAT(B.DateCreated, '%Y') = '$Year'
                                                  AND BE.PositionId = $ID
                                                  AND B.StatusId = 1
                                                  AND B.BranchId = $AssignedBranchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getIncomeLevelPopulation()
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT CASE
                                                WHEN SUM(BorrowerMonthlyIncome) < 9250
                                                      THEN 'Less than PHP 9,250'
                                                WHEN SUM(BorrowerMonthlyIncome) BETWEEN 9520 AND 19040
                                                      THEN 'PHP 9,520 - PHP 19,040'
                                                WHEN SUM(BorrowerMonthlyIncome) BETWEEN 19041 AND 38080
                                                      THEN 'PHP 19,041 - PHP 38,080'
                                                WHEN SUM(BorrowerMonthlyIncome) BETWEEN 38081 AND 66640
                                                      THEN 'PHP 38,081 - PHP 66,640'
                                                WHEN SUM(BorrowerMonthlyIncome) BETWEEN 66644 AND 114240
                                                      THEN 'PHP 66,644 - PHP 114,240'
                                                WHEN SUM(BorrowerMonthlyIncome) BETWEEN 114241 AND 190400
                                                      THEN 'PHP 114,241 - PHP 190,400'
                                                WHEN SUM(BorrowerMonthlyIncome) > 190400
                                                      THEN 'More than PHP 190,400'
                                                END as IncomeLevel
                                                FROM T_Application A
                                                  INNER JOIN R_Borrowers B
                                                    ON A.BorrowerId = B.BorrowerId
                                                  WHERE B.StatusId = 1
                                                  AND B.BranchId = $AssignedBranchId
                                                  GROUP BY B.BorrowerId
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getIncomeReport($Year, $query)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT COUNT(B.BorrowerId) as TotalBorrowers
                                                FROM T_Application A
                                                  INNER JOIN R_Borrowers B
                                                    ON A.BorrowerId = B.BorrowerId
                                                  WHERE B.StatusId = 1
                                                  AND B.BranchId = $AssignedBranchId
                                                  GROUP BY B.BorrowerId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getMaitalStatus()
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  CivilStatusId as Id
                                                , Name
                                                FROM r_civilstatus
                                                  WHERE StatusId = 1
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getMaitalStatusYearly($Year, $ID)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  COUNT(BorrowerId) as TotalBorrowers
                                                FROM R_Borrowers B
                                                  WHERE DATE_FORMAT(B.DateCreated, '%Y') = '$Year'
                                                  AND CivilStatus = $ID
                                                  AND B.StatusId = 1
                                                  AND B.BranchId = $AssignedBranchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getRiskStatus($Year, $Type)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  COUNT(B.BorrowerId) as TotalBorrowers
                                                FROM T_Application A
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = A.BorrowerId
                                                  WHERE DATE_FORMAT(B.DateCreated, '%Y') = '$Year'
                                                  AND RiskLevel = '$Type'
                                                  AND B.StatusId = 1
                                                  AND B.BranchId = $AssignedBranchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalBorrowers($Year)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT COUNT(DISTINCT B.BorrowerId) as TotalBorrowers
                                                FROM r_borrowers B
                                                  INNER JOIN T_Application A
                                                    ON A.BorrowerId = B.BorrowerId
                                                    WHERE DATE_FORMAT(A.DateCreated, '%Y') = '$Year'
                                                    AND B.BranchId = $AssignedBranchId
                                                    AND 
                                                    (
                                                      A.StatusId = 1
                                                      OR 
                                                      A.StatusId = 4
                                                    )
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalBorrowerGeo($Year, $Island)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT COUNT(DISTINCT B.BorrowerId) as TotalBorrowers
                                                FROM r_borrowers B
                                                  INNER JOIN T_Application A
                                                    ON A.BorrowerId = B.BorrowerId
                                                  INNER JOIN borrowerAddressHistory BAH
                                                    ON BAH.BorrowerId = B.BorrowerId
                                                  INNER JOIN R_Address ADDD
                                                    ON ADDD.AddressId = BAH.AddressId
                                                  INNER JOIN add_barangay AB
                                                    ON ADDD.BarangayId = AB.BrgyCode
                                                  INNER JOIN Add_Region AR
                                                    ON AR.RegCode = AB.RegCode
                                                    WHERE DATE_FORMAT(A.DateCreated, '%Y') = '$Year'
                                                    AND AR.Island = '$Island'
                                                    AND B.BranchId = $AssignedBranchId
                                                    AND 
                                                    (
                                                      A.StatusId = 1
                                                      OR 
                                                      A.StatusId = 4
                                                    )
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalLoans($Year)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT COUNT(DISTINCT ApplicationId) as Total
                                                FROM t_application A
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = A.BorrowerId
                                                    WHERE DATE_FORMAT(A.DateCreated, '%Y') = '$Year'
                                                      AND 
                                                      (
                                                        A.StatusId = 1
                                                        OR 
                                                        A.StatusId = 4
                                                      )
                                                      AND B.BranchId = $AssignedBranchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalTypeofLoans($Year)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT COUNT(DISTINCT L.LoanId) as Total
                                                FROM t_application A
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = A.BorrowerId
                                                  INNER JOIN R_Loans L
                                                    ON L.LoanId = A.LoanId
                                                    WHERE DATE_FORMAT(A.DateCreated, '%Y') = '$Year'
                                                      AND B.BranchId = $AssignedBranchId
                                                      AND 
                                                      (
                                                        A.StatusId = 1
                                                        OR 
                                                        A.StatusId = 4
                                                      )
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalLoanAmount($Year)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT SUM(DISTINCT PrincipalAmount) as Total
                                                FROM t_application A
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = A.BorrowerId
                                                    WHERE DATE_FORMAT(A.DateCreated, '%Y') = '$Year'
                                                      AND 
                                                      (
                                                        A.StatusId = 1
                                                        OR 
                                                        A.StatusId = 4
                                                      )
                                                      AND B.BranchId = $AssignedBranchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalInterest($Year)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT DATE_FORMAT(PM.DateCreated, '%Y') as Year
                                                , SUM(Amount) as Total
                                                FROM t_paymentsmade PM
                                                      INNER JOIN t_application A
                                                        ON A.ApplicationId = PM.ApplicationId
                                                      INNER JOIN R_Borrowers B
                                                        ON B.BorrowerId = A.BorrowerId
                                                        WHERE IsInterest = 1
                                                        AND DATE_FORMAT(A.DateCreated, '%Y') = '$Year'
                                                        AND PM.StatusId = 1
                                                        AND 
                                                        (
                                                          A.StatusId = 1
                                                          OR 
                                                          A.StatusId = 4
                                                        )
                                                        AND B.BranchId = $AssignedBranchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalCharges($Year)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT SUM(Amount) as Total
                                                FROM Application_Has_Charges AC
                                                  INNER JOIN t_application A
                                                    ON A.ApplicationId = AC.ApplicationId
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = A.BorrowerId
                                                  WHERE AC.StatusId = 2
                                                  AND DATE_FORMAT(AC.DateCreated, '%Y') = '$Year'
                                                  AND 
                                                  (
                                                    A.StatusId = 1
                                                    OR 
                                                    A.StatusId = 4
                                                  ) 
                                                  AND B.BranchId = $AssignedBranchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getCurrentFund($Year)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  COALESCE(SUM(Amount), 0) as Total
                                                FROM r_capital
                                                  WHERE BranchId = $AssignedBranchId
                                                  AND DATE_FORMAT(DateCreated, '%Y') = '$Year'
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalGross($Year)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT DATE_FORMAT(PM.DateCreated, '%Y') as Year
                                                , SUM(Amount) as Total
                                                FROM t_paymentsmade PM
                                                      INNER JOIN t_application A
                                                        ON A.ApplicationId = PM.ApplicationId
                                                      INNER JOIN R_Borrowers B
                                                        ON B.BorrowerId = A.BorrowerId
                                                        WHERE DATE_FORMAT(A.DateCreated, '%Y') = '$Year'
                                                        AND PM.StatusId = 1
                                                        AND 
                                                        (
                                                          A.StatusId = 1
                                                          OR 
                                                          A.StatusId = 4
                                                        )
                                                        AND B.BranchId = $AssignedBranchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalExpenses($Year)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  COALESCE(SUM(Amount), 0) as Total
                                                FROM r_expense
                                                    WHERE DATE_FORMAT(DateExpense, '%Y')  = '$Year'
                                                    AND StatusId = 1
                                                    AND BranchId = $AssignedBranchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalCollections($DateFrom, $DateTo)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $d1 = new DateTime($DateFrom);
      $d2 = new DateTime($DateTo);
      // $timestamp = $d->getTimestamp(); // Unix timestamp
      $formatted_date1 = $d1->format('Y-m-d'); // 2003-10-16
      $formatted_date2 = $d2->format('Y-m-d'); // 2003-10-16

      $query_string = $this->db->query("SELECT  COALESCE(SUM(PM.Amount), 0) as Total
                                                FROM t_paymentsmade PM
                                                  INNER JOIN T_Application A
                                                    ON A.ApplicationId = PM.ApplicationId
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = A.BorrowerId
                                                    WHERE DATE_FORMAT(PM.DateCollected, '%Y-%m-%d') BETWEEN '$formatted_date1' AND '$formatted_date2'
                                                    AND PM.StatusId = 1
                                                    AND B.BranchId = $AssignedBranchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalChargesStatement($DateFrom, $DateTo)
    {
      $d1 = new DateTime($DateFrom);
      $d2 = new DateTime($DateTo);
      // $timestamp = $d->getTimestamp(); // Unix timestamp
      $formatted_date1 = $d1->format('Y-m-d'); // 2003-10-16
      $formatted_date2 = $d2->format('Y-m-d'); // 2003-10-16
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT SUM(Amount) as Total
                                                FROM Application_Has_Charges AC
                                                  INNER JOIN t_application A
                                                    ON A.ApplicationId = AC.ApplicationId
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = A.BorrowerId
                                                  WHERE AC.StatusId = 2
                                                  AND DATE_FORMAT(AC.DateCreated, '%Y-%m-%d') BETWEEN '$formatted_date1' AND '$formatted_date2'
                                                  AND 
                                                  (
                                                    A.StatusId = 1
                                                    OR 
                                                    A.StatusId = 4
                                                  ) 
                                                  AND B.BranchId = $AssignedBranchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getExpensesStatement($dateFrom, $dateTo)
    {
      $d1 = new DateTime($dateFrom);
      $d2 = new DateTime($dateTo);
      // $timestamp = $d->getTimestamp(); // Unix timestamp
      $formatted_date1 = $d1->format('Y-m-d'); // 2003-10-16
      $formatted_date2 = $d2->format('Y-m-d'); // 2003-10-16
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query_string = $this->db->query("SELECT  CONCAT('EX-', LPAD(EX.ExpenseId, 6, 0)) as ReferenceNo
                                                , EXT.Name
                                                , SUM(EX.Amount) as Amount
                                                , DATE_FORMAT(EX.DateExpense, '%b %d, %Y') as DateExpense
                                                , DATE_FORMAT(EX.DateCreated, '%b %d, %Y') as DateCreated
                                                , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName) as CreatedBy
                                                FROM R_Expense EX
                                                      INNER JOIN r_expensetype EXT
                                                          ON EXT.ExpenseTypeId = EX.ExpenseTypeId
                                                        INNER JOIN r_employee EMP
                                                          ON EMP.EmployeeNumber = EX.CreatedBy
                                                            WHERE EX.StatusId = 1
                                                            AND DATE_FORMAT(EX.DateExpense, '%Y-%m-%d') BETWEEN '$formatted_date1' AND '$formatted_date2'
                                                            GROUP BY Name
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getCurrentFundStatement($dateFrom, $dateTo)
    {
      $d1 = new DateTime($dateFrom);
      $d2 = new DateTime($dateTo);
      // $timestamp = $d->getTimestamp(); // Unix timestamp
      $formatted_date1 = $d1->format('Y-m-d'); // 2003-10-16
      $formatted_date2 = $d2->format('Y-m-d'); // 2003-10-16
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  COALESCE(SUM(Amount), 0) as Total
                                                FROM r_capital
                                                  WHERE BranchId = $AssignedBranchId
                                                  AND DATE_FORMAT(DateCreated, '%Y-%m-%d') BETWEEN '$formatted_date1' AND '$formatted_date2'
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getPendingCharges($Id)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  AHC.Amount
                                                , AHC.ChargeId
                                                , AHC.ApplicationChargeId
                                                , C.Name
                                                FROM Application_Has_Charges AHC
                                                  INNER JOIN R_Charges C
                                                  WHERE AHC.StatusId = 1
                                                  AND AHC.ApplicationId = $Id
      ");
      $data = $query_string->result_array();
      return $data;
    }
}