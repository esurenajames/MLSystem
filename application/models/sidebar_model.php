<?php
class sidebar_model extends CI_Model
{
    function __construct()
    {
      parent::__construct();
			$this->load->model('maintenance_model');
			$this->load->model('access');
    }

    function getAccess()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query_string = $this->db->query("SELECT  DISTINCT RoleId
                                                FROM R_UserRole
                                                WHERE EmployeeNumber = '$EmployeeNumber'
                                                AND StatusId = 1
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function getProfilePicture()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query_string = $this->db->query("SELECT  ProfileId
                                                , FileName
                                                  FROM R_ProfilePicture
                                                    WHERE EmployeeNumber = $EmployeeNumber
                                                    AND StatusId = 1
      ");
      $data = $query_string->result_array();
      return $data;
    }

    function checkSideBar()
    {
      $EmployeeNumber = sprintf('%06d', $this->session->userdata('EmployeeNumber'));
      $query_string = $this->db->query("SELECT  DISTINCT ModuleId
                                                FROM R_UserAccess
                                                WHERE EmployeeNumber = '$EmployeeNumber'
                                                  AND StatusId = 1
      ");
      $data = $query_string->result_array();
      $New_Array = array();
      foreach ( $data as $Items ) 
      {
        $New_Array[] = $Items["ModuleId"];
      }
      return $New_Array;
    }

    function checkSubModules()
    {
      $EmployeeNumber = sprintf('%06d', $this->session->userdata('EmployeeNumber'));
      $query_string = $this->db->query("SELECT  DISTINCT SubModuleId
                                                FROM R_UserAccess
                                                WHERE EmployeeNumber = '$EmployeeNumber'
                                                  AND StatusId = 1
      ");
      $data = $query_string->result_array();
      $New_Array = array();
      foreach ( $data as $Items ) 
      {
        $New_Array[] = $Items["SubModuleId"];
      }
      return $New_Array;
    }

    function getUserCreated($EmployeeNumber)
    {
      $query = $this->db->query("SELECT CONCAT(FirstName, ' ', MiddleName, ' ', LastName, CASE WHEN ExtName != '' THEN CONCAT(', ', ExtName) ELSE '' END ) as Name 
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

}