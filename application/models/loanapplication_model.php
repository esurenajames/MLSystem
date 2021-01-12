<?php
class loanapplication_model extends CI_Model
{
  function __construct()
  {
    parent::__construct();
		$this->load->model('maintenance_model');
		$this->load->model('access');
    date_default_timezone_set('Asia/Manila');
  }

  function getLoanApplicationDetails($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $AssignedBranchId = $this->session->userdata('BranchId');
    $query = $this->db->query("SELECT CONCAT(BS.Name, ' ', B.FirstName, ' ', B.MiddleName, ' ', B.LastName, ', ', B.ExtName) as Name
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
                                      , DATE_FORMAT(A.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                      , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as CreatedBy
                                      , EMP.EmployeeNumber as EmployeeCreator
                                      , A.TransactionNumber
                                      , CASE
                                          WHEN A.DateApproved IS NULL
                                          THEN 'N/A'
                                          ELSE DATE_FORMAT(A.DateApproved, '%b %d, %Y %h:%i %p')
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
                                              AND ApproverNumber = '$EmployeeNumber'
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
                                      , B.Birthplace
                                      , BE.BranchId
                                      , DATE_FORMAT(B.DateOfBirth, '%m/%d/%Y') as ReportDOB
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
    ");

    $data = $query->row_array();
    return $data;
  }

  function getProfilePicture($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  FileName
                                              FROM Borrower_Has_Picture
                                                WHERE BorrowerId = $ID
                                                AND StatusId = 1
    ");
    $data = $query_string->row_array();
    return $data;
  }

  function getProvinceAddress($Id)
  {
    $query_string = $this->db->query("SELECT  MAX(A.AddressId) as AddressId
                                              , UPPER(A.HouseNo) as HouseNo
                                              , UPPER(BA.brgyDesc) as brgyDesc
                                              , UPPER(P.provDesc) as provDesc
                                              , UPPER(C.cityMunDesc) as cityMunDesc
                                              , UPPER(R.regDesc) as regDesc
                                              FROM application_has_address AHA
                                                INNER JOIN borroweraddresshistory BAH
                                                  ON BAH.BorrowerAddressHistoryId = AHA.BorrowerAddressHistoryId
                                                INNER JOIN r_borrowers B
                                                  ON B.BorrowerId = BAH.BorrowerId
                                                INNER JOIN r_address A
                                                  ON A.AddressId = BAH.AddressId
                                                INNER JOIN add_barangay BA
                                                  ON BA.brgyCode = A.BarangayId
                                                INNER JOIN add_province P
                                                  ON P.provCode = BA.provCode
                                                INNER JOIN add_city C
                                                  ON C.citymunCode = BA.citymunCode
                                                INNER JOIN add_region R 
                                                  ON R.regCode = BA.regCode
                                                WHERE B.BorrowerId = $Id
                                                AND A.AddressType = 'Province Address'
                                                AND AHA.StatusId = 1
                                                ORDER BY AHA.ApplicationAddressId DESC

    ");
    $data = $query_string->row_array();
    return $data;
  }

  function getCityAddress($Id)
  {
    $query_string = $this->db->query("SELECT DISTINCT  A.AddressId
                                              , BAH.AddressType
                                              , BAH.YearsStayed
                                              , BAH.MonthsStayed
                                              , BAH.NameOfLandlord
                                              , BAH.IsPrimary
                                              , UPPER(A.HouseNo) as HouseNo
                                              , UPPER(BA.brgyDesc) as brgyDesc
                                              , UPPER(P.provDesc) as provDesc
                                              , UPPER(C.cityMunDesc) as cityMunDesc
                                              , UPPER(R.regDesc) as regDesc
                                              , A.Telephone
                                              , A.ContactNumber
                                              , AHA.ApplicationAddressId
                                              FROM application_has_address AHA
                                                INNER JOIN borroweraddresshistory BAH
                                                  ON BAH.BorrowerAddressHistoryId = AHA.BorrowerAddressHistoryId
                                                INNER JOIN r_borrowers B
                                                  ON B.BorrowerId = BAH.BorrowerId
                                                INNER JOIN r_address A
                                                  ON A.AddressId = BAH.AddressId
                                                INNER JOIN add_barangay BA
                                                  ON BA.brgyCode = A.BarangayId
                                                INNER JOIN add_province P
                                                  ON P.provCode = BA.provCode
                                                INNER JOIN add_city C
                                                  ON C.citymunCode = BA.citymunCode
                                                INNER JOIN add_region R 
                                                  ON R.regCode = BA.regCode
                                                WHERE B.BorrowerId = $Id
                                                AND A.AddressType = 'City Address'
                                                AND AHA.StatusId = 1
                                                ORDER BY AHA.ApplicationAddressId DESC

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
                                              FROM application_has_spouse AHS
                                                INNER JOIN borrower_has_spouse BHS
                                                    ON BHS.BorrowerSpouseId = AHS.BorrowerSpouseId
                                                INNER JOIN r_spouse B
                                                    ON BHS.SpouseId = B.SpouseId
                                                INNER JOIN borrowerAddressHistory BAH
                                                  ON B.SpouseId = BAH.SpouseId
                                                INNER JOIN R_Address A
                                                  ON A.AddressId = BAH.AddressId
                                                INNER JOIN add_barangay BA
                                                  ON BA.BrgyCode = A.BarangayId
                                                WHERE B.SpouseId = $Id
                                                AND AHS.StatusId = 1
                                                AND A.AddressType = 'Province Address'

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
                                              FROM application_has_spouse AHS
                                                INNER JOIN borrower_has_spouse BHS
                                                    ON BHS.BorrowerSpouseId = AHS.BorrowerSpouseId
                                                INNER JOIN r_spouse B
                                                    ON BHS.SpouseId = B.SpouseId
                                                INNER JOIN borrowerAddressHistory BAH
                                                  ON B.SpouseId = BAH.SpouseId
                                                INNER JOIN R_Address A
                                                  ON A.AddressId = BAH.AddressId
                                                INNER JOIN add_barangay BA
                                                  ON BA.BrgyCode = A.BarangayId
                                                WHERE B.SpouseId = $Id
                                                AND AHS.StatusId = 1
                                                AND A.AddressType = 'City Address'

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
                                              , AHE.EmployerId
                                              , BHE.StatusId
                                              FROM application_has_employer AHE 
                                                INNER JOIN borrower_has_employer BHE
                                                  ON AHE.EmployerId = BHE.EmployerId
                                                INNER JOIN R_Borrowers B
                                                  ON B.BorrowerId = BHE.BorrowerId
                                                LEFT JOIN r_occupation BHP
                                                  ON BHP.OccupationId = BHE.PositionId
                                                LEFT JOIN R_Industry I
                                                  ON I.IndustryId = BHE.IndustryId
                                                    WHERE B.BorrowerId = $Id
                                                    AND EmployerStatus = $status
                                                    AND BHE.StatusId = 1
    ");
    $data = $query_string->row_array();
    return $data;
  }

  function getSpouseEmployer($Id, $status)
  {
    $query_string = $this->db->query("SELECT  EmployerName
                                              , SpousePosition as Position
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
                                              FROM application_has_spouse AHS
                                                INNER JOIN borrower_has_spouse BHS
                                                  ON BHS.BorrowerSpouseId = AHS.BorrowerSpouseId
                                                INNER JOIN borrower_has_employer BHE
                                                  ON BHE.SpouseId = BHS.SpouseId
                                                INNER JOIN r_spouse B
                                                  ON B.SpouseId = BHE.SpouseId
                                                LEFT JOIN Borrower_Has_Position BHP
                                                  ON BHP.BorrowerPositionId = BHE.PositionId
                                                LEFT JOIN R_Industry I
                                                  ON I.IndustryId = BHE.IndustryId
                                                    WHERE B.SpouseId = $Id
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
                                              FROM application_has_personalreference AHP
                                                INNER JOIN Borrower_has_reference BN
                                                  ON AHP.ReferenceId = BN.ReferenceId
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
                                                    WHERE StatusId = 2
                                                      AND ApplicationId = $AppId
    ");
    $data = $query_string->row_array();
    return $data;
  }

  function getCoMaker($borrowerId)
  {
    $query_string = $this->db->query("SELECT  BHC.Name
                                              , DATE_FORMAT(BHC.Birthdate, '%m/%d/%Y') as DateOfBirth
                                              , Employer
                                              , BusinessAddress
                                              , P.Name as PositionName
                                              , TenureYear
                                              , TenureMonth
                                              , TelephoneNo
                                              , BusinessNo
                                              , MobileNo
                                              , MonthlyIncome
                                              , AHC.ApplicationCoMakerId
                                              FROM application_has_comaker AHC
                                                INNER JOIN borrower_has_comaker BHC
                                                  ON BHC.BorrowerCoMakerId = AHC.BorrowerCoMakerId
                                                INNER JOIN r_occupation P
                                                  ON P.OccupationId = BHC.PositionId
                                                WHERE BorrowerId = $borrowerId
                                                AND BHC.StatusId = 1 
                                                ORDER BY AHC.ApplicationCoMakerId DESC

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
                                              , DATE_FORMAT(B.DateOfBirth, '%m/%d/%Y') as ReportDOB
                                              , E.EmailAddress
                                              , B.Birthplace

                                              , B.MiddleName
                                              , S.SalutationId
                                              , SX.SexId
                                              , N.NationalityId
                                              , N.Description as NationalityName
                                              , C.CivilStatusId
                                              FROM Application_has_spouse AHS
                                                INNER JOIN borrower_has_spouse BHS
                                                  ON BHS.BorrowerSpouseId = AHS.BorrowerSpouseId
                                                INNER JOIN R_Spouse B
                                                  ON B.SpouseId = BHS.SpouseId
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
                                                LEFT JOIN Borrower_Has_Emails BHE
                                                  ON BHE.BorrowerId = B.SpouseId
                                                LEFT JOIN R_Emails E
                                                  ON E.EmailId = BHE.EmailId
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
                                      , DATE_FORMAT(P.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                      , DATE_FORMAT(P.DateCollected, '%b %d, %Y') as DateCollected
                                      , DATE_FORMAT(P.PaymentDate, '%b %d, %Y') as PaymentDate
                                      , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName) as CreatedBy
                                      , P.StatusId
                                      , B.BankName
                                      , P.IsInterest
                                      , P.IsPrincipalCollection
                                      , P.IsOthers
                                      , P.Description 
                                      , PaymentMadeId
                                      FROM t_paymentsmade P
                                        INNER JOIN R_Employee EMP
                                          ON EMP.EmployeeNumber = P.CreatedBy
                                        INNER JOIN R_Bank B
                                          ON B.BankId = P.BankId
                                            WHERE P.ApplicationId = $Id
    ");
    if($query->num_rows() > 0)
    {
      $data = $query->result_array();
      return $data;
    }
    else 
    {
      return 0;
    }
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
                                                  THEN C.Amount/100 * AHC.LoanAmount
                                                  ELSE C.Amount
                                              END
                                        ) as TotalCharges
                                        , C.Amount
                                        , C.Name as ChargeName
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

  function getBorrowerLoanList()
  {
    $AssignedBranchId = $this->session->userdata('BranchId');
    $query = $this->db->query("SELECT DISTINCT  CONCAT(FirstName, ' ', MiddleName, ' ', LastName, CASE WHEN ExtName != '' THEN CONCAT(', ', ExtName) ELSE '' END ) as Name
                                        , B.BorrowerId
                                        FROM R_Borrowers B
                                          INNER JOIN T_Application A
                                            ON A.BorrowerId = B.BorrowerId
                                          WHERE B.StatusId = 1
    ");

    $output = '';
    foreach ($query->result() as $row)
    {
      $output .= '<option value="'.$row->BorrowerId.'">'.$row->Name.'</option>';
    }
    return $output;
  }

  function getLoanTypesList()
  {
    $AssignedBranchId = $this->session->userdata('BranchId');
    $query = $this->db->query("SELECT DISTINCT L.LoanId
                                        , L.Name
                                          FROM R_Loans L
                                            INNER JOIN T_Application A
                                              ON A.LoanId = L.LoanId
                                            INNER JOIN Branch_has_Employee BE
                                              ON BE.EmployeeNumber = A.CreatedBy
                                          WHERE L.StatusId = 1
                                          AND BE.BranchId = $AssignedBranchId 
                                            ORDER BY L.Name ASC
    ");


    $output = '';
    foreach ($query->result() as $row)
    {
      $output .= '<option value="'.$row->LoanId.'">'.$row->Name. '</option>';
    }
    return $output;
  }

  function getLoanApplications()
  {
    $AssignedBranchId = $this->session->userdata('BranchId');
    $query = $this->db->query("SELECT DISTINCT A.ApplicationId
                                      , A.TransactionNumber
                                      , CONCAT(B.FirstName, ' ', B.MiddleName, ' ', B.LastName) as Name
                                      FROM T_Application A
                                        INNER JOIN t_paymentsmade PM
                                          ON A.ApplicationId = PM.ApplicationId
                                        INNER JOIN R_Borrowers B
                                          ON B.BorrowerId = A.BorrowerId
                                            WHERE PM.StatusId = 1
    ");


    $output = '';
    foreach ($query->result() as $row)
    {
      $output .= '<option value="'.$row->ApplicationId.'">'.$row->TransactionNumber. ' | '.$row->Name. '</option>';
    }
    return $output;
  }

  function getCollectedBy()
  {
    $AssignedBranchId = $this->session->userdata('BranchId');
    $query = $this->db->query("SELECT DISTINCT EMP.EmployeeNumber
                                      , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName) as Name
                                      FROM t_paymentsmade PM
                                        INNER JOIN R_Employee EMP
                                          ON EMP.EmployeeNumber = PM.CreatedBy
                                            WHERE PM.StatusId = 1
    ");


    $output = '';
    foreach ($query->result() as $row)
    {
      $output .= '<option value="'.$row->EmployeeNumber.'">'.$row->Name. ' | '.$row->EmployeeNumber.'</option>';
    }
    return $output;
  }

  function getAssetCategory()
  {
    $AssignedBranchId = $this->session->userdata('BranchId');
    $query = $this->db->query("SELECT   C.CategoryId
                                      , C.Name
                                      FROM r_assetmanagement AM
                                            INNER JOIN r_category C
                                                ON C.CategoryId = AM.CategoryId
                                                  WHERE C.StatusId = 1
    ");


    $output = '';
    foreach ($query->result() as $row)
    {
      $output .= '<option value="'.$row->CategoryId.'">'.$row->Name. '</option>';
    }
    return $output;
  }

  function getCollectionDate()
  {
    $AssignedBranchId = $this->session->userdata('BranchId');
    $query = $this->db->query("SELECT DISTINCT DATE_FORMAT(PM.DateCollected, '%b %d, %Y') as CollectedDate
                                      , DATE_FORMAT(PM.DateCollected, '%b-%d-%Y') as varDate
                                      FROM t_paymentsmade PM
                                        INNER JOIN R_Employee EMP
                                          ON EMP.EmployeeNumber = PM.CreatedBy
                                            WHERE PM.StatusId = 1
                                            GROUP BY DateCollected
    ");


    $output = '';
    foreach ($query->result() as $row)
    {
      $output .= '<option value="'.$row->varDate.'">'.$row->CollectedDate. '</option>';
    }
    return $output;
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
                                      , DATE_FORMAT(AHA.DateUpdated, '%b %d, %Y %h:%i %p') as DateUpdated
                                      , CONCAT(EMP2.FirstName, ' ', EMP2.MiddleName, ' ', EMP2.LastName) as ProcessedBy
                                      FROM application_has_approver AHA
                                        INNER JOIN r_status S
                                          ON S.StatusId = AHA.StatusId
                                        INNER JOIN r_employee EMP
                                          ON EMP.EmployeeNumber = AHA.ApproverNumber
                                        LEFT JOIN r_employee EMP2
                                          ON EMP2.EmployeeNumber = AHA.ApproverNumber
                                        WHERE ApplicationId = $Id
                                        AND 
                                        (
                                            AHA.StatusId = 3
                                            OR
                                            AHA.StatusId = 4
                                            OR
                                            AHA.StatusId = 5
                                            OR
                                            AHA.StatusId = 1
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
                                          AND RequirementId NOT IN (SELECT RequirementId FROM Application_has_Requirements WHERE StatusId = 5 OR StatusId = 7)
    ");

    $data = $query->result_array();
    return $data;
  }

  function getRequirementForApplication2()
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
    $query = $this->db->query("SELECT   DISTINCT AHR.RequirementId
                                        , R.Name
                                        , R.Description
                                        , R.IsMandatory
                                        FROM application_has_requirements AHR
                                          INNER JOIN R_Requirements R
                                            ON R.RequirementId = AHR.RequirementId
                                          WHERE (AHR.StatusId = 5
                                          OR
                                          AHR.StatusId = 7
                                          )
                                          AND AHR.ApplicationId = $ID
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
                                              , DATE_FORMAT(A.DateApproved, '%b %d, %Y  %h:%i %p') as DateApproved
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

  function filterLoans($loanStatus, $borrowerId, $LoanId, $BranchId)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $AssignedBranchId = $this->session->userdata('BranchId');

    $search = '';
    if($loanStatus == 'All')
    {      
      $search .= '';
    }
    else if($loanStatus != null || $loanStatus != '')
    {
      $search .= ' AND A.StatusId = ' . $loanStatus;
    }

    if($borrowerId == 'All')
    {      
      $search .= '';
    }
    else if($borrowerId != null || $borrowerId != '')
    {
      $search .= ' AND A.BorrowerId = ' . $borrowerId;
    }

    if($LoanId == 'All')
    {      
      $search .= '';
    }
    else if($LoanId != null || $LoanId != '')
    {
      $search .= ' AND A.LoanId = ' . $LoanId;
    }


    if($BranchId == 'All')
    {      
      $search .= '';
    }
    else if($BranchId != null || $BranchId != '')
    {
      $search .= ' AND BE.BranchId = ' . $BranchId;
    }

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
                                              , DATE_FORMAT(A.DateApproved, '%b %d, %Y %h:%i %p') as DateApproved
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
                                                  WHERE B.StatusId = 1 
                                                  ".$search."
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
                                              , DATE_FORMAT(A.DateApproved, '%b %d, %Y %h:%i %p') as DateApproved
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
                                              , DATE_FORMAT(A.DateApproved, '%b %d, %Y %h:%i %p') as DateApproved
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

  function getCollections($dateFrom, $dateTo, $columns, $query, $branchId)
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
                                              , DATE_FORMAT(PM.dateCreated, '%b %d, %Y %h:%i %p') as DateCreated

                                              , PM.ChangeAmount as rawChangeAmount
                                              , PM.AmountPaid as rawAmountPaid
                                              , PM.InterestAmount as rawInterestCollection
                                              , PM.PrincipalAmount as rawPrincipalCollection
                                              , PM.IsInterest
                                              , PM.IsPrincipalCollection
                                              , PM.IsOthers

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
                                                    AND B.BranchId = $branchId
                                                    $query
                                                    ORDER BY PM.DateCollected DESC
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function getCollections2($dateFrom, $dateTo, $branchId)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $d1 = new DateTime($dateFrom);
    $d2 = new DateTime($dateTo);
    // $timestamp = $d->getTimestamp(); // Unix timestamp
    $formatted_date1 = $d1->format('Y-m-d'); // 2003-10-16
    $formatted_date2 = $d2->format('Y-m-d'); // 2003-10-16

    $query_string = $this->db->query("SELECT  PM.ChangeAmount as rawChangeAmount
                                              , PM.AmountPaid as rawAmountPaid
                                              , PM.InterestAmount as rawInterestCollection
                                              , PM.PrincipalAmount as rawPrincipalCollection
                                              , PM.IsInterest
                                              , PM.IsPrincipalCollection
                                              , PM.IsOthers
                                              , PM.AmountPaid as AmountPaid
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
                                                    AND DATE_FORMAT(PM.DateCollected, '%Y-%m-%d') BETWEEN '$formatted_date1' AND '$formatted_date2'
                                                    AND B.BranchId = $branchId
                                                    ORDER BY PM.DateCollected DESC
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function getCollectionsManagement($ApplicationId, $LoanId, $CollectedBy, $dateFrom, $dateTo, $BranchId)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $search = '';
    if($dateFrom != '' && $dateTo != '')
    {
      $search .= " AND DATE_FORMAT(PM.DateCollected, '%b-%d-%Y') BETWEEN '".$dateFrom."' AND '".$dateTo."'";
    }
    
    if($ApplicationId == 'All')
    {
      $search .= " ";
    }
    else if($ApplicationId != '')
    {
      $search .= " AND A.ApplicationId = " . $ApplicationId;
    }
    
    
    if($LoanId == 'All')
    {
      $search .= " ";
    }
    else if($LoanId != '')
    {
      $search .= " AND A.LoanId = " . $LoanId;
    }
    
    
    if($CollectedBy == 'All')
    {
      $search .= " ";
    }
    else if($CollectedBy != '')
    {
      $search .= " AND PM.CreatedBy = '" . $CollectedBy."'";
    }
    
    if($BranchId == 'All')
    {
      $search .= " ";
    }
    else if($BranchId != '')
    {
      $search .= " AND B.BranchId =  '" . $BranchId."'";
    }
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
                                              , PM.Description as Remarks
                                              , DATE_FORMAT(PM.DateCollected, '%b %d, %Y') as dateCollected
                                              , DATE_FORMAT(PM.dateCreated, '%b %d, %Y %h:%i %p') as DateCreated

                                              , PM.ChangeAmount as rawChangeAmount
                                              , PM.AmountPaid as rawAmountPaid
                                              , PM.InterestAmount as rawInterestCollection
                                              , PM.PrincipalAmount as rawPrincipalCollection
                                              , BRNCH.Name as Branch

                                              FROM t_paymentsmade PM
                                                INNER JOIN t_application A
                                                    ON A.ApplicationId = PM.ApplicationId
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = A.BorrowerId
                                                  INNER JOIN r_employee EMP
                                                    ON EMP.EmployeeNumber = PM.CreatedBy
                                                  INNER JOIN R_Bank BNK
                                                    ON BNK.BankId = PM.ChangeId
                                                  INNER JOIN R_Branches BRNCH
                                                    ON BRNCH.BranchId = B.BranchId
                                                    WHERE PM.StatusId = 1
                                                    ".$search."
                                                    GROUP BY A.ApplicationId, PM.DateCollected, PM.PaymentMadeId
                                                    ORDER BY PM.DateCollected DESC
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function getExpensesReport($dateFrom, $dateTo, $query, $branchId)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  CONCAT('EX-', LPAD(EX.ExpenseId, 6, 0)) as ReferenceNo
                                              , EXT.Name
                                              , EX.Amount
                                              , DATE_FORMAT(EX.DateExpense, '%b %d, %Y') as DateExpense
                                              , DATE_FORMAT(EX.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                              , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName) as CreatedBy
                                              , EX.Description
                                              FROM R_Expense EX
                                                    INNER JOIN r_expensetype EXT
                                                        ON EXT.ExpenseTypeId = EX.ExpenseTypeId
                                                      INNER JOIN r_employee EMP
                                                        ON EMP.EmployeeNumber = EX.CreatedBy
                                                          WHERE EX.StatusId = 1
                                                          AND EX.BranchId = $branchId
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
                                              , Remarks
                                              , FileName
                                              , FileTitle
                                              , DATE_FORMAT(AHN.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                              , AHN.DateCreated as rawDateCreated
                                              , AHN.NotificationId
                                              FROM Application_has_notifications AHN
                                                INNER JOIN T_Application A
                                                  ON A.ApplicationId = AHN.ApplicationId
                                                INNER JOIN R_Employee EMP
                                                  ON EMP.EmployeeNumber = AHN.CreatedBy
                                                    WHERE A.ApplicationId = $ID
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

  function DisplayPenalty($Id)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query = $this->db->query("SELECT  TotalPenalty
                                    , CONCAT('PLT-', LPAD(ApplicationPenaltyId, 6, 0)) as ReferenceNo
                                    , PenaltyType
                                    , Amount
                                    , GracePeriod
                                    , ApplicationPenaltyId
                                    , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as CreatedBy
                                    , AHP.StatusId
                                    , DATE_FORMAT(AHP.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
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
    $query_string = $this->db->query("SELECT  CONCAT('COM-', LPAD(AC.CommentId, 6, 0)) as ReferenceNo
                                              , AC.ApplicationId
                                              , AC.CommentId
                                              , AC.Comment
                                              , AC.CreatedBy
                                              , AC.CommentId
                                              , AC.StatusId
                                              , DATE_FORMAT(AC.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                              , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as Name
                                              , FileName
                                              FROM Application_has_Comments AC
                                                INNER JOIN r_employee EMP
                                                  ON EMP.EmployeeNumber = AC.CreatedBy
                                                LEFT JOIN comments_has_attachments CHA
                                                  ON CHA.CommentId = AC.CommentId
                                                    WHERE AC.ApplicationId = $ID
    ");
    $data = $query_string->result_array();
    return $data;
  }

  function displayRequirements($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  CONCAT('REQ-', LPAD(AR.ApplicationRequirementId, 6, 0)) as ReferenceNo
                                              , AR.ApplicationId
                                              , AR.RequirementId
                                              , R.Name
                                              , S.Description
                                              , AR.ApplicationRequirementId
                                              , AR.StatusId
                                              , DATE_FORMAT(AR.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                              , AR.DateCreated as rawDateCreated
                                              FROM Application_has_Requirements AR
                                                    INNER JOIN R_Requirements R
                                                      ON R.RequirementId = AR.RequirementId
                                                    INNER JOIN r_status S
                                                      ON S.StatusId = AR.StatusId
                                                     WHERE AR.ApplicationId = $ID
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
    $query_string = $this->db->query("SELECT  CONCAT('CLR-', LPAD(C.CollateralId, 6, 0)) as ReferenceNo
                                              , C.ProductName
                                              , C.Value
                                              , DATE_FORMAT(C.DateRegistered, '%b %d, %Y') as DateRegistered
                                              , DATE_FORMAT(C.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                              , C.DateCreated as rawDateCreated
                                              , CT.Name as CollateralType
                                              , CS.Name as CurrentStatus
                                              , C.CollateralId
                                              , CS.StatusId
                                              FROM R_Collaterals C
                                                INNER JOIN application_has_collaterals AHC
                                                  ON AHC.CollateralId = C.CollateralId
                                                INNER JOIN r_collateralStatus CS
                                                  ON CS.CollateralStatusId = C.StatusId
                                                INNER JOIN R_CollateralType CT
                                                  ON CT.CollateralTypeId = C.CollateralTypeId
                                                    WHERE AHC.ApplicationId = $ID
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

  function getDisbursementDisplay($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  CONCAT('DB-', LPAD(AHD.DisbursementId, 6, 0)) as ReferenceNo
                                              , DATE_FORMAT(AHD.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
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

  function getIncome($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  CONCAT('INC-', LPAD(AI.IncomeId, 6, 0)) as ReferenceNo
                                              , AI.ApplicationId
                                              , AI.Source
                                              , AI.IncomeId
                                              , AI.Details
                                              , AI.Amount
                                              , AI.CreatedBy
                                              , AI.StatusId
                                              , S.Description
                                              , DATE_FORMAT(AI.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                              , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as Name
                                              FROM Application_has_MonthlyIncome AI
                                                INNER JOIN r_status S
                                                  ON S.StatusId = AI.StatusId
                                                LEFT JOIN R_Employee EMP
                                                  ON EMP.EmployeeNumber = AI.CreatedBy
                                                    WHERE AI.ApplicationId = $ID
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

  function getDisbursements($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  CONCAT('DIS-', LPAD(C.CollateralId, 6, 0)) as ReferenceNo
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

  function displayCharges($ID)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $query_string = $this->db->query("SELECT  CONCAT('CHR-', LPAD(AHC.ApplicationChargeId, 6, 0)) as ReferenceNo
                                              , C.ChargeId
                                              , AHC.ApplicationChargeId
                                              , C.Name
                                              , C.Amount
                                              , C.StatusId
                                              , A.PrincipalAmount
                                              , C.ChargeType
                                              , C.IsMandatory
                                              , S.Description
                                              , AHC.LoanAmount
                                              , CASE
                                                  WHEN C.ChargeType = 1
                                                  THEN CONCAT(C.Amount)
                                                  ELSE CONCAT(C.Amount / 100 * AHC.LoanAmount)
                                                END as TotalCharge
                                              , AHC.StatusId
                                              , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as CreatedBy
                                              , DATE_FORMAT(AHC.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
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
    $query_string = $this->db->query("SELECT  CONCAT('EXP-', LPAD(AE.ExpenseId, 6, 0)) as ReferenceNo
                                              , AE.ApplicationId
                                              , AE.Source
                                              , AE.ExpenseId
                                              , AE.Details
                                              , AE.Amount
                                              , AE.StatusId
                                              , S.Description
                                              , DATE_FORMAT(AE.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
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
    $query_string = $this->db->query("SELECT  CONCAT('OBL-', LPAD(AO.MonthlyObligationId, 6, 0)) as ReferenceNo
                                              , AO.ApplicationId
                                              , AO.Source
                                              , AO.MonthlyObligationId
                                              , AO.Details
                                              , AO.Amount
                                              , AO.StatusId
                                              , CASE
                                                  WHEN AO.StatusId = 2
                                                  THEN 'Active'
                                                  ELSE 'Deactivated'
                                                END as Description
                                              , DATE_FORMAT(AO.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                              , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as CreatedBy
                                              FROM Application_has_MonthlyObligation AO
                                                LEFT JOIN R_Employee EMP
                                                  ON EMP.EmployeeNumber = AO.CreatedBy
                                                    WHERE AO.ApplicationId = $ID
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
                                                WHERE RequirementId = '".$data['RequirementId']."'
                                                AND ApplicationId = '".$data['ApplicationId']."'
                                                AND StatusId != 6
    ");
    $data = $query_string->num_rows();
    return $data;
  }

  function countComment($data)
  {
    $query_string = $this->db->query("SELECT  * 
                                              FROM Application_has_Comments
                                                WHERE Comment = '".$data['Comment']."'
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
    $query_string = $this->db->query("SELECT  DATE_FORMAT(AHP.DatePaid, '%b %d, %Y') as PaymentDate
                                              , DATE_FORMAT(AHP.DateCollected, '%b %d, %Y') as DateCollected
                                              , AHP.PenaltyType
                                              , AHP.Amount
                                              , AHP.AmountPaid
                                              , AHP.TotalPenalty
                                              , AHP.AmountPaid - AHP.TotalPenalty as AmountChange
                                              , AHP.GracePeriod
                                              , CM.Name as ChangeMethod
                                              , PM.Name as PaymentMethod
                                              , B.BankName
                                              , AHP.Remarks
                                              FROM application_has_penalty AHP
                                                INNER JOIN r_methodofpayment CM
                                                  ON CM.MethodId = AHP.ChangeMethod
                                                INNER JOIN r_disbursement PM
                                                  ON PM.DisbursementId = AHP.PaymentMethod
                                                INNER JOIN r_bank B
                                                  ON B.BankId = AHP.BankId
                                                WHERE AHP.applicationPenaltyId = $Id
    ");
    $data = $query_string->row_array();
    return $data;
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
                                              , CONCAT('INC-', LPAD(IncomeId, 6, 0)) as ReferenceNo
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
                                              , DisbursedBy
                                              FROM Application_has_Disbursement
                                                    WHERE DisbursementId = '$Id'
    ");
    $DisbursementDetail = $query_string->row_array();
    return $DisbursementDetail;
  }

  function getRequirementDetails($Id)
  {
    $query_string = $this->db->query("SELECT  AHR.ApplicationId
                                              , AHR.RequirementId
                                              , A.TransactionNumber
                                              , A.ApplicationId
                                              , A.StatusId
                                              FROM Application_has_Requirements AHR 
                                                INNER JOIN T_Application A
                                                  ON A.ApplicationId = AHR.ApplicationId
                                                  WHERE AHR.RequirementId = '$Id'
    ");
    $RequirementDetail = $query_string->row_array();
    return $RequirementDetail;
  }

  function getRequirementDetails2($ApplicationRequirementId)
  {
    $query_string = $this->db->query("SELECT  AHR.ApplicationId
                                              , AHR.RequirementId
                                              , A.TransactionNumber
                                              , A.ApplicationId
                                              FROM Application_has_Requirements AHR 
                                                INNER JOIN T_Application A
                                                  ON A.ApplicationId = AHR.ApplicationId
                                                  WHERE AHR.ApplicationRequirementId = $ApplicationRequirementId
    ");
    $RequirementDetail = $query_string->row_array();
    return $RequirementDetail;
  }

  function getCollateralDetails($Id)
  {
    $query_string = $this->db->query("SELECT DISTINCT  ApplicationId
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
                                              , (SELECT COUNT(*) FROM collaterals_has_files WHERE StatusId = 1 AND CollateralId = C.CollateralId) as withFiles
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
      $Detail = $this->db->query("SELECT  MonthlyObligationId
                                          , CONCAT('OBG-', LPAD(MonthlyObligationId, 6, 0)) as ReferenceNo
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
      // admin audits finals
        $loanDetails = $this->getLoanApplicationDetails($Detail['ApplicationId']);
        if($input['updateType'] == 2)
        {
          $auditLogsManager = 'Re-activated monthly obligation #'.$Detail['ReferenceNo'].' in monthly obligations tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Re-activated monthly obligation #'.$Detail['ReferenceNo'].' in monthly obligations tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Re-activated monthly obligation #'.$Detail['ReferenceNo'].' in monthly obligations tab.';
        }
        else if($input['updateType'] == 6)
        {
          $auditLogsManager = 'Deactivated monthly obligation #'.$Detail['ReferenceNo'].' in monthly obligations tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Deactivated monthly obligation #'.$Detail['ReferenceNo'].' in monthly obligations tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Deactivated monthly obligation #'.$Detail['ReferenceNo'].' in monthly obligations tab.';
        }
        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $Detail['ApplicationId'], 'Application_has_notifications', 'ApplicationId');
    }
    else if($input['Type'] == 'Expenses')
    {
      $Detail = $this->db->query("SELECT  Source
                                          , CONCAT('EXP-', LPAD(ExpenseId, 6, 0)) as ReferenceNo
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
      // admin audits finals
        $loanDetails = $this->getLoanApplicationDetails($Detail['ApplicationId']);
        if($input['updateType'] == 2)
        {
          $auditLogsManager = 'Re-activated monthly expense #'.$Detail['ReferenceNo'].' in monthly expenses tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Re-activated monthly expense #'.$Detail['ReferenceNo'].' in monthly expenses tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Re-activated monthly expense #'.$Detail['ReferenceNo'].' in monthly expenses tab.';
        }
        else if($input['updateType'] == 6)
        {
          $auditLogsManager = 'Deactivated monthly expense #'.$Detail['ReferenceNo'].' in monthly expenses tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Deactivated monthly expense #'.$Detail['ReferenceNo'].' in monthly expenses tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Deactivated monthly expense #'.$Detail['ReferenceNo'].' in monthly expenses tab.';
        }
        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $Detail['ApplicationId'], 'Application_has_notifications', 'ApplicationId');
    }
    else if($input['Type'] == 'Incomes')
    {
      $Detail = $this->db->query("SELECT  Source
                                          , CONCAT('INC-', LPAD(IncomeId, 6, 0)) as ReferenceNo
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
        $loanDetails = $this->getLoanApplicationDetails($Detail['ApplicationId']);
      // admin audits finals
        if($input['updateType'] == 2)
        {
          $auditLogsManager = 'Re-activated other source of income #'.$Detail['ReferenceNo'].' in other sources of income tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Re-activated other source of income #'.$Detail['ReferenceNo'].' in other sources of income tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Re-activated other source of income #'.$Detail['ReferenceNo'].' in other sources of income tab.';
        }
        else if($input['updateType'] == 6)
        {
          $auditLogsManager = 'Deactivated other source of income #'.$Detail['ReferenceNo'].' in other sources of income tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Deactivated other source of income #'.$Detail['ReferenceNo'].' in other sources of income tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Deactivated other source of income #'.$Detail['ReferenceNo'].' in other sources of income tab.';
        }
        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $Detail['ApplicationId'], 'Application_has_notifications', 'ApplicationId');
    }
    else if($input['Type'] == 'Disbursements')
    {
      $Detail = $this->db->query("SELECT  AHD.Description
                                                , ApplicationId
                                                , DisbursementId
                                                , CONCAT('DB-', LPAD(DisbursementId, 6, 0)) as ReferenceNo
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
      // admin audits finals
        $loanDetails = $this->getLoanApplicationDetails($Detail['ApplicationId']);
        if($input['updateType'] == 0)
        {
          $auditLogsManager = 'Deactivated disbursement #'.$Detail['ReferenceNo'].' in disbursement tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Deactivated disbursement #'.$Detail['ReferenceNo'].' in disbursement tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Deactivated disbursement #'.$Detail['ReferenceNo'].' in disbursement tab.';
        }
        else if($input['updateType'] == 1)
        {
          $auditLogsManager = 'Re-activated disbursement #'.$Detail['ReferenceNo'].' in disbursement tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Re-activated disbursement #'.$Detail['ReferenceNo'].' in disbursement tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Re-activated disbursement #'.$Detail['ReferenceNo'].' in disbursement tab.';
        }
        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $Detail['ApplicationId'], 'Application_has_notifications', 'ApplicationId');
    }
    else if($input['Type'] == 'Requirements')
    {
      $RequirementDetail = $this->db->query("SELECT  AHR.ApplicationRequirementId
                                                , CONCAT('REQ-', LPAD(AHR.ApplicationRequirementId, 6, 0)) as ReferenceNo
                                                , AHR.ApplicationId
                                                , B.BorrowerId
                                                  FROM Application_has_Requirements AHR
                                                    INNER JOIN T_Application A
                                                      ON A.ApplicationId = AHR.ApplicationId
                                                    INNER JOIN R_Borrowers B
                                                      ON B.BorrowerId = A.BorrowerId
                                                    WHERE ApplicationRequirementId = ".$input['Id']."
      ")->row_array();

      // update status
        $set = array(
          'StatusId' => 6,
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'ApplicationRequirementId' => $input['Id']
        );
        $table = 'Application_has_Requirements';
        $this->maintenance_model->updateFunction1($set, $condition, $table);

      // admin audits finals
        $loanDetails = $this->getLoanApplicationDetails($RequirementDetail['ApplicationId']);
        $TransactionNumber = 'REQ-'.sprintf('%06d', $RequirementDetail['ApplicationRequirementId']);
        $auditLogsManager = 'Deactivated requirement #'.$RequirementDetail['ReferenceNo'].' in requirement tab for application #'.$loanDetails['TransactionNumber'].'.';
        $auditAffectedEmployee = 'Deactivated requirement #'.$RequirementDetail['ReferenceNo'].' in requirement tab for application #'.$loanDetails['TransactionNumber'].'.';
        $auditAffectedTable = 'Deactivated requirement #'.$RequirementDetail['ReferenceNo'].' in requirement tab.';
        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $RequirementDetail['ApplicationId'], 'Application_has_notifications', 'ApplicationId');
    }
    else if($input['Type'] == 'Penalty')
    {
      $Detail = $this->db->query("SELECT  ApplicationId
                                          , ApplicationPenaltyId
                                          , CONCAT('PLT-', LPAD(ApplicationPenaltyId, 6, 0)) as ReferenceNo
                                            FROM Application_has_Penalty                                                    
                                            WHERE ApplicationPenaltyId = ".$input['Id']."
      ")->row_array();

      // update status
        $set = array(
          'StatusId' => $input['updateType'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'ApplicationPenaltyId' => $input['Id']
        );
        $table = 'Application_has_Penalty';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // admin audits finals
        $loanDetails = $this->getLoanApplicationDetails($Detail['ApplicationId']);
        $auditLogsManager = 'Deactivated penalty #'.$Detail['ReferenceNo'].' in penalty tab for application #'.$loanDetails['TransactionNumber'].'.';
        $auditAffectedEmployee = 'Deactivated penalty #'.$Detail['ReferenceNo'].' in penalty tab for application #'.$loanDetails['TransactionNumber'].'.';
        $auditAffectedTable = 'Deactivated penalty #'.$Detail['ReferenceNo'].' in penalty tab.';
        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $Detail['ApplicationId'], 'Application_has_notifications', 'ApplicationId');
    }
    else if($input['Type'] == 'Charge')
    {
      $ChargeDetail = $this->db->query("SELECT  AHC.ApplicationChargeId
                                                , AHC.ApplicationId
                                                , CONCAT('CHR-', LPAD(AHC.ApplicationChargeId, 6, 0)) as ReferenceNo
                                                , A.TransactionNumber
                                                , A.StatusId
                                                  FROM Application_Has_Charges AHC
                                                    INNER JOIN T_Application A
                                                      ON A.ApplicationId = AHC.ApplicationId
                                                        WHERE AHC.ApplicationChargeId = ".$input['Id']."
      ")->row_array();

      // update status
        $set = array(
          'StatusId' => 6,
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'ApplicationChargeId' => $input['Id']
        );
        $table = 'Application_Has_Charges';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // check if to restart
        $this->forRestart($ChargeDetail['ApplicationId'], $ChargeDetail['StatusId']);

      // admin audits finals
        $loanDetails = $this->getLoanApplicationDetails($ChargeDetail['ApplicationId']);
        $auditLogsManager = 'Deactivated charge #'.$ChargeDetail['ReferenceNo'].' in charge tab for application #'.$loanDetails['TransactionNumber'].'.';
        $auditAffectedEmployee = 'Deactivated charge #'.$ChargeDetail['ReferenceNo'].' in charge tab for application #'.$loanDetails['TransactionNumber'].'.';
        $auditAffectedTable = 'Deactivated charge #'.$ChargeDetail['ReferenceNo'].' in charge tab.';
        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $ChargeDetail['ApplicationId'], 'Application_has_notifications', 'ApplicationId');
    }
    else if($input['Type'] == 'Collateral')
    {
      $CollateralDetail = $this->db->query("SELECT  CONCAT('CLR-', LPAD(ApplicationCollateralId, 6, 0)) as ReferenceNo
                                                , ApplicationId
                                                  FROM Application_has_Collaterals
                                                    WHERE ApplicationCollateralId = ".$input['Id']."
      ")->row_array();

      // update status
        $set = array(
          'StatusId' => $input['updateType'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'ApplicationCollateralId' => $input['Id']
        );
        $table = 'Application_has_Collaterals';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
        // insert into Application_has_Notifications
        if($input['updateType'] == 2)
        {
          $CollateralDescription = 'Re-activated collateral record #' .$CollateralDetail['ReferenceNo']. '.'; // Application Notification
        }
        else if($input['updateType'] == 6)
        {
          $CollateralDescription = 'Deactivated collateral record #' .$CollateralDetail['ReferenceNo']. '.'; // Application Notification
        }
        $data3 = array(
          'Description'   => $CollateralDescription,
          'ApplicationId' => $CollateralDetail['ApplicationId'],
          'CreatedBy'     => $EmployeeNumber,
          'DateCreated'   => $DateNow
        );
        $this->db->insert('Application_has_Notifications', $data3);
      // insert into logs
        if($input['updateType'] == 2)
        {
          $Description = 'Re-activated collateral record #' .$CollateralDetail['Referenceno'].' from'.$CollateralDetail['ApplicationId']. ' at the loan collateral tab '; // main log
        }
        else if($input['updateType'] == 6)
        {
          $Description = 'Deactivated collateral record #' .$CollateralDetail['Referenceno'].' from' .$CollateralDetail['ApplicationId']. ' at the loan collateral tab '; // main log
        }
        $data2 = array(
          'Description'   => $Description,
          'CreatedBy'     => $EmployeeNumber,
          'DateCreated'   => $DateNow
        );
        $this->db->insert('R_Logs', $data2);
    }
    else if($input['Type'] == 'Comment')
    {
      $Details = $this->db->query("SELECT  CONCAT('COM-', LPAD(AHC.CommentId, 6, 0)) as ReferenceNo
                                                , AHC.ApplicationId
                                                  FROM Application_has_Comments AHC
                                                    INNER JOIN T_Application A
                                                      ON A.ApplicationId = AHC.ApplicationId
                                                      WHERE AHC.CommentId = ".$input['Id']."
      ")->row_array();

      // update status
        $set = array(
          'StatusId' => 6,
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'CommentId' => $input['Id']
        );
        $table = 'Application_has_Comments';
        $this->maintenance_model->updateFunction1($set, $condition, $table);

      // admin audits finals
        $loanDetails = $this->getLoanApplicationDetails($Details['ApplicationId']);
        $TransactionNumber = 'COM-'.sprintf('%06d', $Details['ApplicationRequirementId']);
        $auditLogsManager = 'Deactivated comment #'.$Details['ReferenceNo'].' in comments tab for application #'.$loanDetails['TransactionNumber'].'.';
        $auditAffectedEmployee = 'Deactivated comment #'.$Details['ReferenceNo'].' in comments tab for application #'.$loanDetails['TransactionNumber'].'.';
        $auditAffectedTable = 'Deactivated comment #'.$Details['ReferenceNo'].' in comments tab.';
        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $Details['ApplicationId'], 'Application_has_notifications', 'ApplicationId');
    }
    else if($input['Type'] == 'PersonalRef')
    {
      $Detail = $this->db->query("SELECT  CONCAT('RF-', LPAD(AHC.ReferenceId, 6, 0)) as ReferenceNo
                                                , AHC.ApplicationId
                                                  FROM application_has_personalreference AHC
                                                    INNER JOIN T_Application A
                                                      ON A.ApplicationId = AHC.ApplicationId
                                                      WHERE AHC.ApplicationPersonalReferenceId = ".$input['Id']."
      ")->row_array();

      // update status
        $set = array(
          'StatusId' => $input['updateType'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'ApplicationPersonalReferenceId' => $input['Id']
        );
        $table = 'application_has_personalreference';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // admin audits finals
        $loanDetails = $this->getLoanApplicationDetails($Detail['ApplicationId']);
        if($input['updateType'] == 1)
        {
          $auditLogsManager = 'Re-activated personal reference #'.$Detail['ReferenceNo'].' in personal reference tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Re-activated personal reference #'.$Detail['ReferenceNo'].' in personal reference tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Re-activated personal reference #'.$Detail['ReferenceNo'].' in personal reference tab.';
        }
        else if($input['updateType'] == 0)
        {
          $auditLogsManager = 'Deactivated personal reference #'.$Detail['ReferenceNo'].' in personal reference tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Deactivated personal reference #'.$Detail['ReferenceNo'].' in personal reference tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Deactivated personal reference #'.$Detail['ReferenceNo'].' in personal reference tab.';
        }
        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $Detail['ApplicationId'], 'Application_has_notifications', 'ApplicationId');
    }
    else if($input['Type'] == 'BorrowerCoMaker')
    {
      $Detail = $this->db->query("SELECT  CONCAT('CM-', LPAD(AHC.BorrowerCoMakerId, 6, 0)) as ReferenceNo
                                                , AHC.ApplicationId
                                                  FROM application_has_comaker AHC
                                                    INNER JOIN T_Application A
                                                      ON A.ApplicationId = AHC.ApplicationId
                                                      WHERE AHC.ApplicationCoMakerId = ".$input['Id']."
      ")->row_array();

      // update status
        $set = array(
          'StatusId' => $input['updateType'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'ApplicationCoMakerId' => $input['Id']
        );
        $table = 'application_has_comaker';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // admin audits finals
        $loanDetails = $this->getLoanApplicationDetails($Detail['ApplicationId']);
        if($input['updateType'] == 1)
        {
          $auditLogsManager = 'Re-activated co-maker #'.$Detail['ReferenceNo'].' in co-maker tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Re-activated co-maker #'.$Detail['ReferenceNo'].' in co-maker tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Re-activated co-maker #'.$Detail['ReferenceNo'].' in co-maker tab.';
        }
        else if($input['updateType'] == 0)
        {
          $auditLogsManager = 'Deactivated co-maker #'.$Detail['ReferenceNo'].' in co-maker tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Deactivated co-maker #'.$Detail['ReferenceNo'].' in co-maker tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Deactivated co-maker #'.$Detail['ReferenceNo'].' in co-maker tab.';
        }
        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $Detail['ApplicationId'], 'Application_has_notifications', 'ApplicationId');
    }
    else if($input['Type'] == 'BorrowerSpouse')
    {
      $Detail = $this->db->query("SELECT  CONCAT('SR-', LPAD(AHC.BorrowerSpouseId, 6, 0)) as ReferenceNo
                                                , AHC.ApplicationId
                                                  FROM application_has_spouse AHC
                                                    INNER JOIN T_Application A
                                                      ON A.ApplicationId = AHC.ApplicationId
                                                      WHERE AHC.ApplicationSpouseId = ".$input['Id']."
      ")->row_array();

      // update status
        $set = array(
          'StatusId' => $input['updateType'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'ApplicationSpouseId' => $input['Id']
        );
        $table = 'application_has_spouse';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // admin audits finals
        $loanDetails = $this->getLoanApplicationDetails($Detail['ApplicationId']);
        if($input['updateType'] == 1)
        {
          $auditLogsManager = 'Re-activated spouse #'.$Detail['ReferenceNo'].' in spouse tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Re-activated spouse #'.$Detail['ReferenceNo'].' in spouse tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Re-activated spouse #'.$Detail['ReferenceNo'].' in spouse tab.';
        }
        else if($input['updateType'] == 0)
        {
          $auditLogsManager = 'Deactivated spouse #'.$Detail['ReferenceNo'].' in spouse tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Deactivated spouse #'.$Detail['ReferenceNo'].' in spouse tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Deactivated spouse #'.$Detail['ReferenceNo'].' in spouse tab.';
        }
        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $Detail['ApplicationId'], 'Application_has_notifications', 'ApplicationId');
    }
    else if($input['Type'] == 'BorrowerEmployer')
    {
      $Detail = $this->db->query("SELECT  CONCAT('ER-', LPAD(AHC.EmployerId, 6, 0)) as ReferenceNo
                                                , AHC.ApplicationId
                                                  FROM application_has_employer AHC
                                                    INNER JOIN T_Application A
                                                      ON A.ApplicationId = AHC.ApplicationId
                                                      WHERE AHC.ApplicationEmployerId = ".$input['Id']."
      ")->row_array();

      // update status
        $set = array(
          'StatusId' => $input['updateType'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'ApplicationEmployerId' => $input['Id']
        );
        $table = 'application_has_employer';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // admin audits finals
        $loanDetails = $this->getLoanApplicationDetails($Detail['ApplicationId']);
        if($input['updateType'] == 1)
        {
          $auditLogsManager = 'Re-activated employment #'.$Detail['ReferenceNo'].' in employment tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Re-activated employment #'.$Detail['ReferenceNo'].' in employment tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Re-activated employment #'.$Detail['ReferenceNo'].' in employment tab.';
        }
        else if($input['updateType'] == 0)
        {
          $auditLogsManager = 'Deactivated employment #'.$Detail['ReferenceNo'].' in employment tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Deactivated employment #'.$Detail['ReferenceNo'].' in employment tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Deactivated employment #'.$Detail['ReferenceNo'].' in employment tab.';
        }
        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $Detail['ApplicationId'], 'Application_has_notifications', 'ApplicationId');
    }
    else if($input['Type'] == 'BorrowerContact')
    {
      $Detail = $this->db->query("SELECT  CONCAT('CN-', LPAD(AHC.BorrowerContactId, 6, 0)) as ReferenceNo
                                                , AHC.ApplicationId
                                                , AHC.ApplicationContactId
                                                FROM application_has_contact AHC
                                                  INNER JOIN T_Application A
                                                    ON A.ApplicationId = AHC.ApplicationId
                                                    WHERE AHC.ApplicationContactId = ".$input['Id']."
      ")->row_array();

      // update status
        $set = array(
          'StatusId' => 0,
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'ApplicationId' => $Detail['ApplicationId'],
          'StatusId' => 1,
        );
        $table = 'application_has_contact';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // update status
        $set = array(
          'StatusId' => $input['updateType'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'ApplicationContactId' => $input['Id']
        );
        $table = 'application_has_contact';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // admin audits finals
        $loanDetails = $this->getLoanApplicationDetails($Detail['ApplicationId']);
        if($input['updateType'] == 1)
        {
          $auditLogsManager = 'Re-activated contact #'.$Detail['ReferenceNo'].' in contact tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Re-activated contact #'.$Detail['ReferenceNo'].' in contact tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Re-activated contact #'.$Detail['ReferenceNo'].' in contact tab.';
        }
        else if($input['updateType'] == 0)
        {
          $auditLogsManager = 'Deactivated contact #'.$Detail['ReferenceNo'].' in contact tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Deactivated contact #'.$Detail['ReferenceNo'].' in contact tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Deactivated contact #'.$Detail['ReferenceNo'].' in contact tab.';
        }
        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $Detail['ApplicationId'], 'Application_has_notifications', 'ApplicationId');
    }
    else if($input['Type'] == 'BorrowerEmail')
    {
      $Detail = $this->db->query("SELECT  CONCAT('EA-', LPAD(AHC.BorrowerEmailId, 6, 0)) as ReferenceNo
                                                , AHC.ApplicationId
                                                , AHC.ApplicationEmailId
                                                FROM Application_has_email AHC
                                                  INNER JOIN T_Application A
                                                    ON A.ApplicationId = AHC.ApplicationId
                                                    WHERE AHC.ApplicationEmailId = ".$input['Id']."
      ")->row_array();

      // update status
        $set = array(
          'StatusId' => 0,
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'ApplicationId' => $Detail['ApplicationId'],
          'StatusId' => 1,
        );
        $table = 'Application_has_email';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // update status
        $set = array(
          'StatusId' => $input['updateType'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'ApplicationEmailId' => $input['Id']
        );
        $table = 'Application_has_email';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // admin audits finals
        $loanDetails = $this->getLoanApplicationDetails($Detail['ApplicationId']);
        if($input['updateType'] == 1)
        {
          $auditLogsManager = 'Re-activated emaill #'.$Detail['ReferenceNo'].' in email tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Re-activated emaill #'.$Detail['ReferenceNo'].' in email tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Re-activated emaill #'.$Detail['ReferenceNo'].' in email tab.';
        }
        else if($input['updateType'] == 0)
        {
          $auditLogsManager = 'Deactivated emaill #'.$Detail['ReferenceNo'].' in email tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Deactivated emaill #'.$Detail['ReferenceNo'].' in email tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Deactivated emaill #'.$Detail['ReferenceNo'].' in email tab.';
        }
        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $Detail['ApplicationId'], 'Application_has_notifications', 'ApplicationId');
    }
    else if($input['Type'] == 'BorrowerEducation')
    {
      $Detail = $this->db->query("SELECT  CONCAT('ED-', LPAD(AHC.BorrowerEducationId, 6, 0)) as ReferenceNo
                                                , AHC.ApplicationId
                                                , AHC.ApplicationEducationId
                                                FROM application_has_education AHC
                                                  INNER JOIN T_Application A
                                                    ON A.ApplicationId = AHC.ApplicationId
                                                    WHERE AHC.ApplicationEducationId = ".$input['Id']."
      ")->row_array();

      // update status
        $set = array(
          'StatusId' => 0,
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'ApplicationId' => $Detail['ApplicationId'],
          'StatusId' => 1,
        );
        $table = 'application_has_education';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // update status
        $set = array(
          'StatusId' => $input['updateType'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'ApplicationEducationId' => $input['Id']
        );
        $table = 'application_has_education';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // admin audits finals
        $loanDetails = $this->getLoanApplicationDetails($Detail['ApplicationId']);
        if($input['updateType'] == 1)
        {
          $auditLogsManager = 'Re-activated education #'.$Detail['ReferenceNo'].' in education tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Re-activated education #'.$Detail['ReferenceNo'].' in education tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Re-activated education #'.$Detail['ReferenceNo'].' in education tab.';
        }
        else if($input['updateType'] == 0)
        {
          $auditLogsManager = 'Deactivated education #'.$Detail['ReferenceNo'].' in education tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Deactivated education #'.$Detail['ReferenceNo'].' in education tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Deactivated education #'.$Detail['ReferenceNo'].' in education tab.';
        }
        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $Detail['ApplicationId'], 'Application_has_notifications', 'ApplicationId');
    }
    else if($input['Type'] == 'BorrowerAddress')
    {
      $Detail = $this->db->query("SELECT  CONCAT('ADD-', LPAD(AHC.BorrowerAddressHistoryId, 6, 0)) as ReferenceNo
                                                , AHC.ApplicationId
                                                , AHC.ApplicationAddressId
                                                FROM application_has_address AHC
                                                  INNER JOIN T_Application A
                                                    ON A.ApplicationId = AHC.ApplicationId
                                                    WHERE AHC.ApplicationAddressId = ".$input['Id']."
      ")->row_array();
      // update status
        $set = array(
          'StatusId' => $input['updateType'],
          'UpdatedBy' => $EmployeeNumber,
          'DateUpdated' => $DateNow,
        );
        $condition = array(
          'ApplicationAddressId' => $input['Id']
        );
        $table = 'application_has_address';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      // admin audits finals
        $loanDetails = $this->getLoanApplicationDetails($Detail['ApplicationId']);
        if($input['updateType'] == 1)
        {
          $auditLogsManager = 'Re-activated address #'.$Detail['ReferenceNo'].' in address tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Re-activated address #'.$Detail['ReferenceNo'].' in address tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Re-activated address #'.$Detail['ReferenceNo'].' in address tab.';
        }
        else if($input['updateType'] == 0)
        {
          $auditLogsManager = 'Deactivated address #'.$Detail['ReferenceNo'].' in address tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedEmployee = 'Deactivated address #'.$Detail['ReferenceNo'].' in address tab for application #'.$loanDetails['TransactionNumber'].'.';
          $auditAffectedTable = 'Deactivated address #'.$Detail['ReferenceNo'].' in address tab.';
        }
        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $Detail['ApplicationId'], 'Application_has_notifications', 'ApplicationId');
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
                                          AND requirementId NOT IN (SELECT RequirementId FROM Application_has_Requirements AR WHERE AR.ApplicationId = $Id AND StatusId != 6)
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
  
  function selectPersonalReference($Id)
  {
    $Id = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $Id);
    $query = $this->db->query("SELECT CONCAT('RF-', LPAD(BR.ReferenceId, 6, 0)) as ReferenceNo
                                      , BR.Name
                                      , BR.ReferenceId
                                      FROM Borrower_has_reference BR
                                      WHERE StatusId = 1
                                      AND BorrowerId = ".$Id['BorrowerId']."
                                      AND BR.ReferenceId NOT IN (SELECT ReferenceId FROM application_has_personalreference)
    ");
    $output = '<option selected disabled value="">Select Personal References</option>';
    foreach ($query->result() as $row)
    {
      $output .= '<option value="'.$row->ReferenceId.'">'.$row->ReferenceNo.' | '.$row->Name.'</option>';
    }
    return $output;
  } 
  
  function selectBorrowerDet($Type, $Id)
  {
    $Id = $this->maintenance_model->selectSpecific('T_Application', 'ApplicationId', $Id);
    if($Type == 'Comaker')
    {
      $query = $this->db->query("SELECT CONCAT('CM-', LPAD(BR.BorrowerCoMakerId, 6, 0)) as ReferenceNo
                                        , BR.Name
                                        , BR.BorrowerCoMakerId
                                        FROM borrower_has_comaker BR
                                        WHERE StatusId = 1
                                        AND BorrowerId = ".$Id['BorrowerId']."
                                        AND BR.BorrowerCoMakerId NOT IN (SELECT BorrowerCoMakerId FROM application_has_comaker)
      ");
      $output = '<option selected disabled value="">Select Co-Maker</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->BorrowerCoMakerId.'">'.$row->ReferenceNo.' | '.$row->Name.'</option>';
      }
      return $output;
    }
    else if($Type == 'Spouse')
    {
      $query = $this->db->query("SELECT CONCAT('SR-', LPAD(BR.BorrowerSpouseId, 6, 0)) as ReferenceNo
                                        , CONCAT(S.FirstName, ' ', S.MiddleName, ' ', S.LastName, ', ', S.ExtName) as Name
                                        , BR.BorrowerSpouseId
                                        FROM borrower_has_spouse BR
                                          INNER JOIN R_Spouse S
                                            ON S.SpouseId = BR.SpouseId
                                            WHERE BR.StatusId = 1
                                            AND BR.BorrowerId = ".$Id['BorrowerId']."
                                            AND BR.BorrowerSpouseId NOT IN (SELECT BorrowerSpouseId FROM Application_has_spouse)
      ");
      $output = '<option selected disabled value="">Select Spouse</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->BorrowerSpouseId.'">'.$row->ReferenceNo.' | '.$row->Name.'</option>';
      }
      return $output;
    }
    else if($Type == 'BorrowerEmployer')
    {
      $query = $this->db->query("SELECT CONCAT('ER-', LPAD(BHS.EmployerId, 6, 0)) as ReferenceNo
                                        , BHS.EmployerName
                                        , BHS.EmployerId
                                        , CASE
                                            WHEN EmployerStatus = 1
                                            THEN 'Present Employer'
                                            ELSE 'Previous Employer'
                                          END as EmployerStatus
                                        FROM borrower_has_employer BHS
                                        WHERE BHS.StatusId = 1
                                        AND BHS.BorrowerId = ".$Id['BorrowerId']."
                                        AND BHS.EmployerId NOT IN (SELECT EmployerId FROM application_has_employer)
      ");
      $output = '<option selected disabled value="">Select Employer</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->EmployerId.'">'.$row->ReferenceNo.' | '.$row->EmployerStatus.' - '.$row->EmployerName.'</option>';
      }
      return $output;
    }
    else if($Type == 'BorrowerContact')
    {
      $query = $this->db->query("SELECT CONCAT('CN-', LPAD(EC.BorrowerContactId, 6, 0)) as ReferenceNo
                                        , CN.PhoneType
                                        , Number
                                        , EC.BorrowerContactId
                                        FROM R_ContactNumbers CN
                                          INNER JOIN borrower_has_contactNumbers EC
                                            ON EC.ContactNumberId = CN.ContactNumberId
                                            WHERE EC.StatusId = 1
                                            AND EC.BorrowerId = ".$Id['BorrowerId']."
                                            AND EC.BorrowerContactId NOT IN (SELECT BorrowerContactId FROM application_has_contact)
      ");
      $output = '<option selected disabled value="">Select contact number</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->BorrowerContactId.'">'.$row->ReferenceNo.' | '.$row->PhoneType.' - '.$row->Number.'</option>';
      }
      return $output;
    }
    else if($Type == 'BorrowerEmail')
    {
      $query = $this->db->query("SELECT  E.EmailAddress
                                        , EE.StatusId
                                        , EE.BorrowerEmailId
                                        , EE.IsPrimary
                                        , EE.CreatedBy
                                        , DATE_FORMAT(EE.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                        , EE.DateCreated as rawDateCreated
                                        , CONCAT('EA-', LPAD(EE.BorrowerEmailId, 6, 0)) as ReferenceNo
                                        FROM R_Emails E
                                          INNER JOIN borrower_has_emails EE
                                            ON EE.EmailId = E.EmailId
                                              WHERE EE.BorrowerId = ".$Id['BorrowerId']."
                                              AND EE.BorrowerEmailId NOT IN (SELECT BorrowerEmailId FROM application_has_email)
                                              AND EE.StatusId = 1
      ");
      $output = '<option selected disabled value="">Select Email</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->BorrowerEmailId.'">'.$row->ReferenceNo.' | '.$row->EmailAddress.'</option>';
      }
      return $output;
    }
    else if($Type == 'BorrowerEducation')
    {
      $query = $this->db->query("SELECT   CONCAT('ED-', LPAD(BEDU.BorrowerEducationId, 6, 0)) as ReferenceNo
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
                                                WHERE B.BorrowerId = ".$Id['BorrowerId']."
                                                AND BEDU.BorrowerEducationId NOT IN (SELECT BorrowerEducationId FROM application_has_education)
                                                AND BEDU.StatusId = 1
      ");
      $output = '<option selected disabled value="">Select Education Record</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->BorrowerEducationId.'">'.$row->ReferenceNo.' | '.$row->SchoolName.' - '.$row->Name.'</option>';
      }
      return $output;
    }
    else if($Type == 'BorrowerAddress')
    {
      $query = $this->db->query("SELECT   DISTINCT  EA.BorrowerAddressHistoryId
                                          , IsPrimary
                                          , A.AddressType
                                          , UPPER(A.HouseNo) as HouseNo
                                          , UPPER(B.brgyDesc) as brgyDesc
                                          , UPPER(P.provDesc) as provDesc
                                          , UPPER(C.cityMunDesc) as cityMunDesc
                                          , UPPER(R.regDesc) as regDesc
                                          , EA.BorrowerId
                                          , CONCAT('ADD-', LPAD(EA.BorrowerAddressHistoryId, 6, 0)) as ReferenceNo
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
                                            WHERE EA.BorrowerId = ".$Id['BorrowerId']."
                                            AND EA.BorrowerAddressHistoryId NOT IN (SELECT BorrowerAddressHistoryId FROM application_has_address)
                                            AND EA.StatusId = 1
      ");
      $output = '<option selected disabled value="">Select Address Record</option>';
      foreach ($query->result() as $row)
      {
        $output .= '<option value="'.$row->BorrowerAddressHistoryId.'">'.$row->ReferenceNo.' | '.$row->HouseNo.', '.$row->brgyDesc.', '.$row->provDesc.', '.$row->regDesc.' </option>';
      }
      return $output;
    }
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
      $query_string = $this->db->query("SELECT  DISTINCT RequirementId
                                                , IdentificationId
                                                FROM borrower_has_supportdocuments
                                                  WHERE StatusId = 1
                                                  AND BorrowerId = $BorrowerId
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getSubmittedSupportDocuments($IdentificationId)
    {
      $query_string = $this->db->query("SELECT  DISTINCT Attachment
                                                , FileName
                                                , IdentificationId
                                                FROM r_identificationcards
                                                  WHERE IdentificationId = $IdentificationId
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

    function getYearFilter2($table, $YearFrom, $YearTo, $BranchId)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT DATE_FORMAT(DateCreated, '%Y') as Year
                                                FROM R_Borrowers
                                                  WHERE BranchId = $BranchId
                                                  AND DATE_FORMAT(DateCreated, '%Y') BETWEEN  '$YearFrom' AND '$YearTo'
                                                  GROUP BY DATE_FORMAT(DateCreated, '%Y')
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getLoansYear()
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT DATE_FORMAT(A.DateCreated, '%Y') as Year
                                                FROM T_Application A
                                                  INNER JOIN R_Employee EMP
                                                    ON EMP.EmployeeNumber = A.CreatedBy
                                                  INNER JOIN Branch_has_Employee BE
                                                    ON BE.EmployeeNumber = EMP.EmployeeNumber
                                                  WHERE BE.BranchId = $AssignedBranchId
                                                  GROUP BY DATE_FORMAT(BE.DateCreated, '%Y')
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getLoansYear2($YearFrom, $YearTo, $branchId)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT DATE_FORMAT(A.DateCreated, '%Y') as Year
                                                FROM T_Application A
                                                  INNER JOIN R_Employee EMP
                                                    ON EMP.EmployeeNumber = A.CreatedBy
                                                  INNER JOIN Branch_has_Employee BE
                                                    ON BE.EmployeeNumber = EMP.EmployeeNumber
                                                  WHERE BE.BranchId = $branchId
                                                  AND DATE_FORMAT(A.DateCreated, '%Y') BETWEEN  '$YearFrom' AND '$YearTo'
                                                  GROUP BY DATE_FORMAT(A.DateCreated, '%Y')
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getAge($Year, $query, $branchId)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT COUNT(BorrowerId) as TotalBorrowers
                                                    FROM r_borrowers
                                                      WHERE DATE_FORMAT(DateCreated, '%Y') = DATE_FORMAT(STR_TO_DATE('$Year','%Y'), '%Y')
                                                      AND $query
                                                      AND BranchId = $branchId
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

    function getEducationYearly($Year, $ID, $branchId)
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
                                                      AND B.BranchId = $branchId
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

    function getSexYearly($Year, $ID, $branchId)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  COUNT(BorrowerId) as TotalBorrowers
                                                FROM R_Borrowers B
                                                  WHERE DATE_FORMAT(B.DateCreated, '%Y') = '$Year'
                                                  AND Sex = $ID
                                                  AND B.StatusId = 1
                                                  AND B.BranchId = $branchId
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

    function getOccupationYearly($Year, $ID, $branchId)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  COUNT(B.BorrowerId) as TotalBorrowers
                                                FROM R_Borrowers B
                                                  INNER JOIN borrower_has_employer BE
                                                    ON BE.BorrowerId = B.BorrowerId
                                                  WHERE DATE_FORMAT(B.DateCreated, '%Y') = '$Year'
                                                  AND BE.PositionId = $ID
                                                  AND B.StatusId = 1
                                                  AND B.BranchId = $branchId
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

    function getIncomeReport($Year, $query, $branchId)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT COUNT(B.BorrowerId) as TotalBorrowers
                                                FROM T_Application A
                                                  INNER JOIN R_Borrowers B
                                                    ON A.BorrowerId = B.BorrowerId
                                                  WHERE B.StatusId = 1
                                                  AND B.BranchId = $branchId
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

    function getMaitalStatusYearly($Year, $ID, $branchId)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  COUNT(BorrowerId) as TotalBorrowers
                                                FROM R_Borrowers B
                                                  WHERE DATE_FORMAT(B.DateCreated, '%Y') = '$Year'
                                                  AND CivilStatus = $ID
                                                  AND B.StatusId = 1
                                                  AND B.BranchId = $branchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getRiskStatus($Year, $Type, $branchId)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  COUNT(B.BorrowerId) as TotalBorrowers
                                                FROM T_Application A
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = A.BorrowerId
                                                  WHERE DATE_FORMAT(B.DateCreated, '%Y') = '$Year'
                                                  AND RiskLevel = '$Type'
                                                  AND B.StatusId = 1
                                                  AND B.BranchId = $branchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalBorrowers($Year, $branchId)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT COUNT(DISTINCT B.BorrowerId) as TotalBorrowers
                                                FROM r_borrowers B
                                                  INNER JOIN T_Application A
                                                    ON A.BorrowerId = B.BorrowerId
                                                    WHERE DATE_FORMAT(A.DateCreated, '%Y') = '$Year'
                                                    AND B.BranchId = $branchId
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

    function getTotalBorrowerGeo($Year, $Island, $branchId)
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
                                                    AND B.BranchId = $branchId
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

    function getTotalLoans($Year, $branchId)
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
                                                      AND B.BranchId = $branchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalTypeofLoans($Year, $branchId)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  DISTINCT COUNT(DISTINCT L.LoanId) as Total
                                                FROM t_application A
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = A.BorrowerId
                                                  INNER JOIN R_Loans L
                                                    ON L.LoanId = A.LoanId
                                                    WHERE DATE_FORMAT(A.DateCreated, '%Y') = '$Year'
                                                      AND B.BranchId = $branchId
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

    function getTotalLoanAmount($Year, $branchId)
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
                                                      AND B.BranchId = $branchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalInterest($Year, $branchId)
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
                                                        AND B.BranchId = $branchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalCharges($Year, $branchId)
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
                                                  AND B.BranchId = $branchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getCurrentFund($Year, $branchId)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  COALESCE(SUM(Amount), 0) as Total
                                                FROM r_capital
                                                  WHERE BranchId = $branchId
                                                  AND DATE_FORMAT(DateCreated, '%Y') = '$Year'
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalGross($Year, $branchId)
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
                                                        AND B.BranchId = $branchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalExpenses($Year, $branchId)
    {
      $AssignedBranchId = $this->session->userdata('BranchId');
      $query_string = $this->db->query("SELECT  COALESCE(SUM(Amount), 0) as Total
                                                FROM r_expense
                                                    WHERE DATE_FORMAT(DateExpense, '%Y')  = '$Year'
                                                    AND StatusId = 1
                                                    AND BranchId = $branchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalCollections($DateFrom, $DateTo, $BranchId)
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
                                                    AND B.BranchId = $BranchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getTotalChargesStatement($DateFrom, $DateTo, $BranchId)
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
                                                  AND B.BranchId = $BranchId
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getExpensesStatement($dateFrom, $dateTo, $BranchId)
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
                                                , DATE_FORMAT(EX.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                                , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName) as CreatedBy
                                                FROM R_Expense EX
                                                      INNER JOIN r_expensetype EXT
                                                          ON EXT.ExpenseTypeId = EX.ExpenseTypeId
                                                        INNER JOIN r_employee EMP
                                                          ON EMP.EmployeeNumber = EX.CreatedBy
                                                            WHERE EX.StatusId = 1
                                                            AND EX.BranchId = $BranchId
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

    function auditFunction($auditLogsManager, $auditAffectedEmployee, $ManagerId, $AffectedEmployeeNumber, $auditLoanDets, $ApplicationId, $independentTable, $independentColumn)
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
        'Description'       => $auditLoanDets
        , ''.$independentColumn.''   => $ApplicationId
        , 'CreatedBy'       => $CreatedBy
      );
      $auditLoanApplicationTable = $independentTable;
      $this->maintenance_model->insertFunction($insertApplicationLog, $auditLoanApplicationTable);
    }

    function getSubmittedRequirment($Id)
    {
      $query = $this->db->query("SELECT   FileName
                                          , Title as FileTitle
                                              FROM requirements_has_attachments 
                                                  WHERE ApplicationRequirementId = $Id
                                                  AND StatusId = 1
      ");
      $data = $query->row_array();
      return $data;
    }

    function forRestart($ApplicationId)
    {
      $CreatedBy = $this->session->userdata('EmployeeNumber');
      $DateNow = date("Y-m-d H:i:s");
      $set = array( 
        'StatusId' => 5
      );
      $condition = array( 
        'ApplicationId' => $ApplicationId,
        'StatusId' => 1
      );
      $table = 'application_has_approver';
      $this->maintenance_model->updateFunction1($set, $condition, $table);  
    }

    function getApplicationReferences($Id)
    {
      $query_string = $this->db->query("SELECT  CONCAT('RF-', LPAD(AP.ReferenceId, 6, 0)) as rowNumber
                                                , AP.ApplicationPersonalReferenceId
                                                , AR.Name as RefName
                                                , AR.Address 
                                                , AR.ContactNumber 
                                                , AP.StatusId
                                                , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as Name
                                                , DATE_FORMAT(AP.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                                FROM application_has_personalreference AP
                                                  INNER JOIN T_Application A
                                                    ON A.ApplicationId = AP.ApplicationId
                                                  INNER JOIN Borrower_has_reference AR
                                                    ON AR.ReferenceId = AP.ReferenceId
                                                  INNER JOIN R_Employee EMP
                                                    ON EMP.EmployeeNumber = AP.CreatedBy
                                                    WHERE A.ApplicationId = $Id
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

    function getApplicationCoMaker($Id)
    {
      $query_string = $this->db->query("SELECT  CONCAT('CM-', LPAD(AP.BorrowerCoMakerId, 6, 0)) as rowNumber
                                                , AP.ApplicationCoMakerId
                                                , AR.Name as RefName
                                                , DATE_FORMAT(AR.Birthdate, '%b %d, %Y') as Birthdate
                                                , AR.Employer 
                                                , AR.BusinessAddress 
                                                , AR.MobileNo 
                                                , AP.StatusId
                                                , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as Name
                                                , DATE_FORMAT(AP.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                                FROM application_has_comaker AP
                                                  INNER JOIN T_Application A
                                                    ON A.ApplicationId = AP.ApplicationId
                                                  INNER JOIN borrower_has_comaker AR
                                                    ON AR.BorrowerCoMakerId = AP.BorrowerCoMakerId
                                                  INNER JOIN R_Employee EMP
                                                    ON EMP.EmployeeNumber = AP.CreatedBy
                                                    WHERE A.ApplicationId = $Id
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

    function getApplicationSpouse($Id)
    {
      $query_string = $this->db->query("SELECT  CONCAT('SR-', LPAD(AHS.BorrowerSpouseId, 6, 0)) as rowNumber
                                                , AHS.ApplicationSpouseId
                                                , CONCAT(EMP.FirstName, ' ', EMP.MiddleName, ' ', EMP.LastName, ', ', EMP.ExtName) as Name
                                                , DATE_FORMAT(AHS.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                                , CONCAT(S.FirstName, ' ', S.MiddleName, ' ', S.LastName, ', ', S.ExtName) as SpouseName
                                                , DATE_FORMAT(S.DateOfBirth, '%d %b %Y') as DateOfBirth
                                                , SX.Name as Sex
                                                , AHS.StatusId
                                                FROM application_has_spouse AHS
                                                    INNER JOIN borrower_has_spouse BHS
                                                      ON BHS.BorrowerSpouseId = AHS.BorrowerSpouseId
                                                    INNER JOIN t_application A
                                                      ON A.ApplicationId = AHS.ApplicationId
                                                    INNER JOIN r_spouse S
                                                      ON S.SpouseId = BHS.SpouseId
                                                    INNER JOIN r_sex SX
                                                      ON SX.SexId = S.Sex
                                                    INNER JOIN R_Employee EMP
                                                      ON EMP.EmployeeNumber = AHS.CreatedBy
                                                      WHERE A.ApplicationId = $Id
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

    function getApplicationEmployment($Id)
    {
      $query_string = $this->db->query("SELECT  CONCAT('ER-', LPAD(BHS.EmployerId, 6, 0)) as rowNumber
                                                , AHS.ApplicationEmployerId
                                                , EmployerName
                                                , BHP.Name as Position
                                                , I.Name as Industry
                                                , CASE
                                                    WHEN EmployerStatus = 1
                                                    THEN 'Present Employer'
                                                    ELSE 'Previous Employer'
                                                  END as EmployerStatus
                                                , DATE_FORMAT(BHS.DateHired, '%d %b %Y') as DateHired
                                                , DATE_FORMAT(AHS.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , TenureYear
                                                , TenureMonth
                                                , BusinessAddress
                                                , TelephoneNumber
                                                , BHS.EmployerId
                                                , AHS.StatusId
                                                FROM application_has_employer AHS
                                                  INNER JOIN borrower_has_employer BHS
                                                    ON BHS.EmployerId = AHS.EmployerId
                                                  INNER JOIN t_application A
                                                    ON A.ApplicationId = AHS.ApplicationId
                                                  INNER JOIN R_Employee EMP
                                                    ON EMP.EmployeeNumber = AHS.CreatedBy
                                                  LEFT JOIN Borrower_Has_Position BHP
                                                    ON BHP.BorrowerPositionId = BHS.PositionId
                                                  LEFT JOIN R_Industry I
                                                    ON I.IndustryId = BHS.IndustryId
                                                    WHERE A.ApplicationId = $Id
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

    function getApplicationContact($Id)
    {
      $query_string = $this->db->query("SELECT  CN.PhoneType
                                                , Number
                                                , AHC.StatusId
                                                , EC.CreatedBy
                                                , EC.BorrowerContactId
                                                , EC.IsPrimary
                                                , CONCAT('CN-', LPAD(EC.BorrowerContactId, 6, 0)) as rowNumber
                                                , AHC.ApplicationContactId
                                                , DATE_FORMAT(AHC.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                FROM Application_has_contact AHC
                                                  INNER JOIN T_Application A
                                                    ON A.ApplicationId = AHC.ApplicationId
                                                  INNER JOIN borrower_has_contactNumbers EC
                                                    ON AHC.BorrowerContactId = EC.BorrowerContactId
                                                  INNER JOIN R_ContactNumbers CN
                                                    ON EC.ContactNumberId = CN.ContactNumberId
                                                    WHERE A.ApplicationId = $Id
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

    function getApplicationEmail($Id)
    {
      $query_string = $this->db->query("SELECT  E.EmailAddress
                                                , EE.BorrowerEmailId
                                                , EE.IsPrimary
                                                , EE.CreatedBy
                                                , DATE_FORMAT(EE.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , EE.DateCreated as rawDateCreated
                                                , CONCAT('EA-', LPAD(EE.BorrowerEmailId, 6, 0)) as rowNumber
                                                , AHE.StatusId
                                                , AHE.ApplicationEmailId
                                                FROM Application_has_email AHE
                                                  INNER JOIN borrower_has_emails EE
                                                    ON AHE.BorrowerEmailId = EE.BorrowerEmailId
                                                  INNER JOIN T_Application A
                                                    ON A.ApplicationId = AHE.ApplicationId
                                                  INNER JOIN R_Emails E
                                                    ON EE.EmailId = E.EmailId
                                                      WHERE A.ApplicationId = $Id
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

    function getApplicationEducation($Id)
    {
      $query_string = $this->db->query("SELECT  CONCAT('ED-', LPAD(BEDU.BorrowerEducationId, 6, 0)) as rowNumber
                                                , AHE.ApplicationEducationId
                                                , BEDU.BorrowerEducationId
                                                , DATE_FORMAT(AHE.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , BEDU.DateCreated as rawDateCreated
                                                , BEDU.Level
                                                , BEDU.SchoolName
                                                , ED.Name
                                                , YearGraduated
                                                , AHE.StatusId
                                                FROM application_has_education AHE
                                                  INNER JOIN T_Application A
                                                    ON A.ApplicationId = AHE.ApplicationId
                                                  INNER JOIN Borrower_has_Education BEDU
                                                    ON AHE.BorrowerEducationId = BEDU.BorrowerEducationId
                                                  INNER JOIN R_Borrowers B
                                                    ON B.BorrowerId = BEDU.BorrowerId
                                                  INNER JOIN R_Education ED
                                                    ON ED.EducationId = BEDU.EducationId
                                                      WHERE A.ApplicationId = $Id
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

    function getApplicationAddress($Id)
    {
      $query_string = $this->db->query("SELECT DISTINCT  EA.BorrowerAddressHistoryId
                                                , IsPrimary
                                                , A.AddressType
                                                , UPPER(A.HouseNo) as HouseNo
                                                , UPPER(B.brgyDesc) as brgyDesc
                                                , UPPER(P.provDesc) as provDesc
                                                , UPPER(C.cityMunDesc) as cityMunDesc
                                                , UPPER(R.regDesc) as regDesc
                                                , AHA.StatusId
                                                , EA.BorrowerId
                                                , DATE_FORMAT(AHA.DateCreated, '%d %b %Y %h:%i %p') as DateCreated
                                                , EA.DateCreated as rawDateCreated
                                                , CONCAT('ADD-', LPAD(EA.BorrowerAddressHistoryId, 6, 0)) as rowNumber
                                                , AHA.ApplicationAddressId
                                                FROM application_has_address AHA
                                                  INNER JOIN borrowerAddressHistory EA
                                                    ON AHA.BorrowerAddressHistoryId = EA.BorrowerAddressHistoryId 
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
                                                      WHERE AHA.ApplicationId = $Id
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