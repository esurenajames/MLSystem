<?php
class access extends CI_Model
{
    function __construct()
    {
      parent::__construct();
    }

    function checkUser($input)
    {
      $query_string = $this->db->query("SELECT  *
                                                FROM R_Userrole U
                                                  INNER JOIN R_Employee EMP
                                                      ON EMP.EmployeeNumber = U.EmployeeNumber
                                                      WHERE U.EmployeeNumber = '".$input['username']."'
                                                      AND CAST(Password AS CHAR(10000) CHARACTER SET utf8) = '".$input['password']."'
                                                      AND U.StatusId = 1
                                                      AND EMP.StatusId = 1
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getUserData($input)
    {
      $query_string = $this->db->query("SELECT  EMP.EmployeeNumber
                                                , CONCAT(FirstName, ' ', LastName) as Name
                                                , CASE
                                                  WHEN BMM.BranchId IS NULL
                                                    THEN B.Name
                                                    ELSE BMM.Name
                                                END as Branch
                                                , B.BranchId
                                                , EmployeeId
                                                , U.Password
                                                , EMP.ManagerId
                                                FROM R_Userrole U
                                                  INNER JOIN R_Employee EMP
                                                      ON EMP.EmployeeNumber = U.EmployeeNumber
                                                  LEFT JOIN branch_has_employee BE
                                                    ON BE.EmployeeNumber = EMP.EmployeeNumber
                                                  LEFT JOIN r_branch B
                                                    ON B.BranchId = BE.BranchId
                                                  LEFT JOIN branch_has_manager BM
                                                    ON BM.EmployeeNumber = EMP.EmployeeNumber
                                                  LEFT JOIN r_branch BMM
                                                    ON BMM.BranchId = BM.BranchId
                                                    WHERE U.EmployeeNumber = '".$input['username']."'
                                                    AND CAST(Password AS CHAR(10000) CHARACTER SET utf8) = '".$input['password']."'
                                                    LIMIT 1  
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function audit($data2)
    {
      $this->db->insert('R_Logs', $data2);
    }

}