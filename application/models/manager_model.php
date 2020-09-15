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
                                            WHERE StatusId = 1 
                                            AND 
                                            (
                                              EmployeeNumber LIKE '%$keyword%'
                                              OR FirstName LIKE '%$keyword%'
                                              OR LastName LIKE '%$keyword%'
                                              OR ExtName LIKE '%$keyword%'
                                              OR MiddleName LIKE '%$keyword%'
                                            )
                                            AND EmployeeNumber != 'sysad'
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

}