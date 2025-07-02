<?php
class access_model extends CI_Model
{
    function __construct()
    {
      parent::__construct();
      date_default_timezone_set('Asia/Manila');
    }

    function checkUser($input)
    {
      $query_string = $this->db->query("SELECT  *
                                                FROM R_users U
                                                  INNER JOIN R_Employees EMP
                                                    ON EMP.EmployeeNumber = U.EmployeeNumber
                                                      WHERE U.EmployeeNumber = '".$input['username']."'
                                                      AND CAST(Password AS CHAR(10000) CHARACTER SET utf8) = '".$input['password']."'
                                                      AND U.StatusId = 1
                                                      AND EMP.StatusId = 1
      ");
      if($query_string->num_rows() > 0) // employee
      {
        $data = 1;
      }
      else if($query_string->num_rows() == 0)
      {
        $data = 2;
      }
      else
      {
        $data = 3;
      }
      return $data;
    }

    function getUserData($input)
    {
      $query_string = $this->db->query("SELECT  EMP.EmployeeNumber
                                                , CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, '')) as Name
                                                , EMP.ID
                                                , U.Password
                                                , U.RoleId
                                                , U.IsNew
                                                FROM r_users U
                                                  INNER JOIN R_Employees EMP
                                                      ON EMP.EmployeeNumber = U.EmployeeNumber
                                                    WHERE U.EmployeeNumber = '".$input['username']."'
                                                    AND CAST(Password AS CHAR(10000) CHARACTER SET utf8) = '".$input['password']."'
                                                    LIMIT 1  
      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getStudentData($input)
    {
      $query_string = $this->db->query("SELECT CONCAT(S.LastName, ', ', S.FirstName, ' ', COALESCE(S.MiddleName, 'N/A'), ' ', COALESCE(S.ExtName, '')) as StudentName
                                        , S.StatusId
                                        , S.FirstName
                                        , S.MiddleName
                                        , S.LastName
                                        , S.ExtName
                                        , S.StudentNumber
                                        , DATE_FORMAT(S.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                        , S.DateCreated as rawDateCreated
                                        , S.Id
                                        , U.Password
                                        , U.RoleId
                                        , U.IsNew
                                        FROM R_Students S
                                          INNER JOIN r_users U
                                              ON U.EmployeeNumber = S.StudentNumber
                                                    WHERE U.EmployeeNumber = '".$input['username']."'
                                                    AND CAST(Password AS CHAR(10000) CHARACTER SET utf8) = '".$input['password']."'
      ");
      if($query_string->num_rows() > 0) // employee
      {
        $data = $query_string->row_array();
        return $data;
      }
      else
      {
        $data = 0;
        return $data;
      }
    }

}