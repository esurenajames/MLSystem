<?php
class admin_model extends CI_Model
{
    function __construct()
    {
      parent::__construct();
			$this->load->model('maintenance_model');
			$this->load->model('access_model');
      date_default_timezone_set('Asia/Manila');
    }
    public function get_all_student_scores()
    {
        $students = $this->db->query("
            SELECT 
                S.StudentNumber as student_number,
                CONCAT(S.LastName, ', ', S.FirstName, ' ', COALESCE(S.MiddleName,'N/A'), ' ', COALESCE(S.ExtName, '')) as student_name,
                CHS.Grade,
                ClassExam.Id as ExamId
            FROM classsubject_has_students CHS
            INNER JOIN r_students S ON S.Id = CHS.StudentId
            LEFT JOIN classsubject_has_exam ClassExam ON ClassExam.ClassSubjectId = CHS.ClassSubjectId
            WHERE CHS.Grade IS NOT NULL
        ")->result_array();

        foreach ($students as &$student) {
            if (!empty($student['ExamId'])) {
                $correct = $this->countCorrectAnswers($student['ExamId']);
                $total = $this->countQuestions($student['ExamId']);
                $student['exam_score'] = ($total > 0) ? round(($correct / $total) * 100, 2) : null;
            } else {
                $student['exam_score'] = null;
            }
            unset($student['ExamId']);
        }
        return $students;
    }
    
    function getEmployeeList()
    {
      $query = $this->db->query("SELECT   CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, '')) as Name
                                          , EMP.EmployeeNumber
                                          , S.Description as StatusDescription
                                          , EMP.StatusId
                                          , P.Description as Position
                                          , EMP.DateCreated
                                          , S.Color
                                          , EMP.ID
                                          FROM r_employees EMP
                                            INNER JOIN R_Status S
                                              ON S.Id = EMP.StatusId
                                            INNER JOIN R_Position P
                                              ON P.Id = EMP.PositionId
      ");
      return $query->result_array();
    }

    public function getCurrentSubjectsByStudent($studentId)
    {
        $this->db->select('s.Code as SubjectCode, s.Name as SubjectName');
        $this->db->from('classsubject_has_students cs');
        $this->db->join('class_has_subjects chs', 'chs.Id = cs.ClassSubjectId');
        $this->db->join('r_subjects s', 's.Id = chs.SubjectId');
        $this->db->where('cs.StudentId', $studentId);
        $this->db->where('cs.StatusId', 1); // Only active/enrolled
        $query = $this->db->get();
        return $query->result_array();
    }

    function getUserList()
    {
      $query = $this->db->query("SELECT   CASE
                                          WHEN U.RoleId != 4
                                                THEN CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, ''))
                                              ELSE CONCAT(SS.LastName, ', ', SS.FirstName, ' ', COALESCE(SS.MiddleName,'N/A'), ' ', COALESCE(SS.ExtName, ''))
                                           END as Name
                                          , U.EmployeeNumber
                                          , S.Description as StatusDescription
                                          , U.StatusId
                                          , CASE
                                              WHEN P.Description IS NULL
                                              THEN 'Student' 
                                              ELSE P.Description
                                          END as Position
                                          , U.DateCreated
                                          , S.Color
                                          , U.ID
                                          , U.IsNew
                                          , PP.Description as Role
                                          , PP.Id as RoleId
                                          FROM R_Users U 
                                            LEFT JOIN r_employees EMP
                                              ON EMP.EmployeeNumber = U.EmployeeNumber
                                            LEFT JOIN r_students SS
                                              ON SS.StudentNumber = U.EmployeeNumber
                                            LEFT JOIN R_Status S
                                              ON S.Id = U.StatusId
                                            LEFT JOIN R_Position P
                                              ON P.Id = EMP.PositionId
                                            LEFT JOIN R_Roles PP
                                              ON PP.Id = U.RoleId
      ");
      return $query->result_array();
    }

    function getUserLogs()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT   Description
                                          , Remarks
                                          , DATE_FORMAT(DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                          , DateCreated as rawDateCreated
                                          FROM r_logs
                                          WHERE NotifyTo = '$EmployeeNumber'
                                            ORDER BY DateCreated ASC
      ");
      return $query->result_array();
    }

    function getAuditLogs()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT   L.Description
                                          , L.Remarks
                                          , DATE_FORMAT(L.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                          , L.DateCreated as rawDateCreated
                                          , CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, '')) as Name
                                          FROM r_logs L
                                            INNER JOIN r_employees EMP
                                              ON EMP.EmployeeNumber = L.CreatedBy
                                              WHERE L.NotifyTo = ''
                                              ORDER BY L.DateCreated ASC
      ");
      return $query->result_array();
    }

    function getViewLogs($Id)
    {
      $userDetails = $this->maintenance_model->selectSpecific('R_Employees', 'Id', $Id);
      $query = $this->db->query("SELECT   Description
                                          , Remarks
                                          , DATE_FORMAT(DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                          , DateCreated as rawDateCreated
                                          FROM r_logs
                                          WHERE NotifyTo = '".$userDetails['EmployeeNumber']."'
                                          ORDER BY DateCreated ASC
      ");
      return $query->result_array();
    }

    function getUserDetail($Id)
    {
      $query = $this->db->query("SELECT   CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, '')) as Name
                                          , EMP.EmployeeNumber
                                          , EMP.MiddleName
                                          , EMP.LastName
                                          , EMP.FirstName
                                          , EMP.ExtName
                                          , EMP.BranchId
                                          , EMP.PositionId
                                          , S.Description as StatusDescription
                                          , EMP.StatusId
                                          , P.Description as Position
                                          , DATE_FORMAT(EMP.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                          , S.Color
                                          , EMP.ID
                                          , U.IsNew
                                          , R.Description as Role
                                          , B.Description as Branch
                                          FROM R_Users U 
                                            INNER JOIN r_employees EMP
                                              ON EMP.EmployeeNumber = U.EmployeeNumber
                                            INNER JOIN R_Status S
                                              ON S.Id = U.StatusId
                                            INNER JOIN R_Position P
                                              ON P.Id = EMP.PositionId
                                            INNER JOIN R_Roles R
                                              ON R.Id = U.RoleId
                                            INNER JOIN R_Branch B
                                              ON B.Id = EMP.BranchId
                                                WHERE EMP.ID = '$Id'
      ");
      return $query->row_array();
    }

    function getStudentDetails($Id)
    {
      $query = $this->db->query("SELECT   CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, '')) as Name
                                          , EMP.StudentNumber
                                          , 'Student' as Position
                                          , 'N/A' as Branch
                                          , EMP.MiddleName
                                          , EMP.LastName
                                          , EMP.FirstName
                                          , EMP.ExtName
                                          , S.Description as StatusDescription
                                          , EMP.StatusId
                                          , DATE_FORMAT(EMP.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                          , S.Color
                                          , EMP.ID
                                          , U.IsNew
                                          , R.Description as Role
                                          FROM R_Users U 
                                            INNER JOIN r_students EMP
                                              ON EMP.StudentNumber = U.EmployeeNumber
                                            INNER JOIN R_Status S
                                              ON S.Id = U.StatusId
                                            INNER JOIN R_Roles R
                                              ON R.Id = U.RoleId
                                                WHERE EMP.StudentNumber = '$Id'
      ");
      return $query->row_array();
    }

    function getPositions()
    {
      $query = $this->db->query("SELECT Id
                                        , Description
                                        FROM R_Position
                                          WHERE StatusId = 1
      ");
      return $query->result_array();
    }

    function getStatus()
    {
      $query = $this->db->query("SELECT Id
                                        , Description
                                        FROM R_Status
      ");
      return $query->result_array();
    }

    function getBranches()
    {
      $query = $this->db->query("SELECT Id
                                        , Description
                                        FROM r_branch
                                          WHERE StatusId = 1
      ");
      return $query->result_array();
    }

    function getQuestions()
    {
      $query = $this->db->query("SELECT Id
                                        , Name as Description
                                        FROM r_securityquestions
                                          WHERE StatusId = 1
      ");
      return $query->result_array();
    }

    function getEmployees()
    {
      $query = $this->db->query("SELECT   CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, '')) as Name
                                          , LPAD(EMP.ID, 5, 0) as EmployeeNumber
                                          , EMP.ID
                                          FROM r_employees EMP
                                            WHERE EMP.StatusId = 1
                                            AND NOT EXISTS(SELECT * FROM r_users WHERE EmployeeNumber = EMP.EmployeeNumber)
      ");
      return $query->result_array();
    }

    function getRoles()
    {
      $query = $this->db->query("SELECT   Id
                                        , Description
                                          FROM r_roles
                                            WHERE StatusId = 1
      ");
      return $query->result_array();
    }

    function countRecord($data)
    {
      $query_string = $this->db->query("SELECT  * 
                                                FROM ".$data['Table']."
                                                  ".$data['Column']."
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function getEmployeeSecurityQuestions($QuestionNo)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query_string = $this->db->query("SELECT  US.SecurityQuestionId as ID
                                                , US.Answer
                                                FROM question_has_answer US
                                                  INNER JOIN r_securityquestions SQ
                                                    ON US.SecurityQuestionId = SQ.Id
                                                    WHERE US.EmployeeNumber = '$EmployeeNumber'
                                                    AND US.QuestionNumber = ".$QuestionNo."
                                                    AND US.StatusId = 1

      ");
      $data = $query_string->row_array();
      return $data;
    }

    function getCurrentPassword($Password ,$EmployeeNumber)
    {
      $query_string = $this->db->query("SELECT Password
                                                FROM R_Users
                                                  WHERE EmployeeNumber = '$EmployeeNumber'
                                                  AND Password = '$Password'
      ");
      $data = $query_string->num_rows();
      return $data;
    }

    function checkExisitingEmployee($EmployeeNumber)
    {
      $query = $this->db->query("SELECT  EmployeeNumber
                                                FROM r_Employees
                                                  WHERE EmployeeNumber = '$EmployeeNumber'
                                                  AND StatusId = 1 
      ");
      
      $data = $query->num_rows();
      return $data;
    }

    function checkSecurity($Question, $Answer, $QuestionNumber)
    {
      $query = $this->db->query("SELECT *
                                        FROM question_has_answer
                                          WHERE SecurityQuestionId = $Question
                                          AND Answer = '".htmlentities($Answer, ENT_QUOTES)."'
                                          AND QuestionNumber = $QuestionNumber
                                          AND StatusId = 1
      ");
      
      
      $data = $query->num_rows();
      return $data;
    }

    function getSubjectList()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, '')) as Name
                                        , S.Name as SubjectName
                                        , SS.Description as StatusDescription
                                        , S.StatusId
                                        , S.Description as SubjectDescription
                                        , S.Code
                                        , S.Units
                                        , SS.Color
                                        , S.Id
                                        FROM R_Subjects S
                                          INNER JOIN R_Status SS
                                            ON SS.Id = S.StatusId
                                          INNER JOIN r_employees EMP
                                            ON EMP.EmployeeNumber = S.CreatedBy 
      ");
      return $query->result_array();
    }

    function getClassList()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, '')) as Name
                                        , S.MaxStudents as MaxStudents
                                        , SS.Description as StatusDescription
                                        , S.StatusId
                                        , S.Description as ClassDescription
                                        , S.Name as ClassName
                                        , SS.Color
                                        , S.Id
                                        FROM R_ClassList S
                                          INNER JOIN R_Status SS
                                            ON SS.Id = S.StatusId
                                          INNER JOIN r_employees EMP
                                            ON EMP.EmployeeNumber = S.CreatedBy 
                                            GROUP BY S.ID
      ");
      return $query->result_array();
    }

    function getFacultyClassList()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, '')) as Name
                                        , S.MaxStudents as MaxStudents
                                        , SS.Description as StatusDescription
                                        , S.StatusId
                                        , S.Description as ClassDescription
                                        , S.Name as ClassName
                                        , SS.Color
                                        , S.Id
                                        FROM R_ClassList S
                                          INNER JOIN R_Status SS
                                            ON SS.Id = S.StatusId
                                          INNER JOIN r_employees EMP
                                            ON EMP.EmployeeNumber = S.CreatedBy 
                                            WHERE EXISTS (SELECT * FROM class_has_subjects WHERE ClassId = S.Id AND StatusId = 1 AND FacultyId = '$EmployeeNumber')
                                            GROUP BY S.ID
      ");
      return $query->result_array();
    }

    function getSubjects()
    {
      $query = $this->db->query("SELECT Id
                                        , Name as Description
                                        , Code
                                        FROM r_subjects
                                          WHERE StatusId = 1
      ");
      return $query->result_array();
    }

    function getFaculty()
    {
      $query = $this->db->query("SELECT   CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, '')) as Name
                                          , EMP.EmployeeNumber
                                          FROM r_employees EMP
                                                INNER JOIN r_position P
                                                    ON P.ID = EMP.PositionId
                                                      WHERE P.Id = 1
                                                      AND EMP.StatusId = 1
      ");
      return $query->result_array();
    }

    function getStudentList()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, '')) as CreatedBy
                                        , CONCAT(S.LastName, ', ', S.FirstName, ' ', COALESCE(S.MiddleName, 'N/A'), ' ', COALESCE(S.ExtName, '')) as StudentName
                                        , S.StatusId
                                        , S.FirstName
                                        , S.MiddleName
                                        , S.LastName
                                        , S.ExtName
                                        , S.StudentNumber
                                        , DATE_FORMAT(S.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                        , S.DateCreated as rawDateCreated
                                        , SS.Description as StatusDescription
                                        , SS.Color
                                        , S.Id
                                        FROM R_Students S
                                          INNER JOIN R_Status SS
                                            ON SS.Id = S.StatusId
                                          INNER JOIN r_employees EMP
                                            ON EMP.EmployeeNumber = S.CreatedBy
      ");
      return $query->result_array();
    }

    function get_datatables_StudentList()
    {
      $this->_get_datatables_query_StudentList($filter);
      if($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
      $query = $this->db->get();
      return $query->result();
    }

    private function _get_datatables_query_StudentList()
    { 
      $UserId = $this->session->userdata('UserId');
      $EmployeeId = $this->session->userdata('EmployeeId');

      $table = 'R_Students S';
      $column_order = array(
          'S.StudentNumber'
          , 'S.LastName'
          , 'EMP.LastName'
          , 'S.DateCreated'
          ,'SS.Description'
        ); //set column field database for datatable orderable

      
      $column_search = array(
        'S.StudentNumber'
        ,'S.LastName'
        ,'S.FirstName'
        ,'S.MiddleName'
        ,'S.ExtName'
        ,'EMP.LastName'
        ,'S.DateCreated'
        ,'SS.Description'
      ); //set column field database for datatable searchable 


      $order = array("S.StudentNumber" => 'ASC');
      $this->db->distinct();
      $this->db->select("CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, '')) as CreatedBy
                                , CONCAT(S.LastName, ', ', S.FirstName, ' ', COALESCE(S.MiddleName, 'N/A'), ' ', COALESCE(S.ExtName, '')) as StudentName
                                , S.StatusId
                                , S.FirstName
                                , S.MiddleName
                                , S.LastName
                                , S.ExtName
                                , S.StudentNumber
                                , DATE_FORMAT(S.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                , S.DateCreated as rawDateCreated
                                , SS.Description as StatusDescription
                                , SS.Color
                                , S.Id
                                , S.AddressLine
                                , B.brgyCode as barangayId
                                , B.regCode
                                , B.provCode
                                , B.citymunCode as cityCode
                                , S.studentContactNo
                                , S.studentEmailAddress
                                , S.placeOfBirth
                                , S.dateOfBirth
                                , S.genderId
                                , S.maritalStatusId
                                , S.graduatingStatusId
                                , S.fatherName
                                , S.fatherOccupation
                                , S.motherName
                                , S.motherOccupation
                                , S.guardianName
                                , S.guardianOccupation
                                , S.guardianContactNumber
      ");

      $this->db->from($table);
      $this->db->join('R_Status SS', 'SS.Id = S.StatusId', 'INNER');
      $this->db->join('r_employees EMP', 'EMP.EmployeeNumber = S.CreatedBy', 'INNER');
      $this->db->join('add_barangay B', 'S.barangayId = B.brgyCode', 'INNER');
      $i = 0;
     
      foreach ($column_search as $item) // loop column 
      {
        if($_POST['search']['value']) // if datatable send POST for search
        {
          if($i===0) // first loop
          {
            $this->db->group_start(); 
            $this->db->like($item, $_POST['search']['value']);
          }
          else
          {
            $this->db->or_like($item, $_POST['search']['value']);
          }

          if(count($column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
            
        }
        $i = $i+1;
      }
       
      if(isset($_POST['order'])) // here order processing
      {
        $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
      } 
      else if(isset($order))
      {
        $this->db->order_by(key($order), $order[key($order)]);
      }
    }

    function countAllFilteredStudentList()
    {
      $UserId = $this->session->userdata('UserId'); 
      $EmployeeId = $this->session->userdata('EmployeeId');

      $query = $this->db->query("SELECT CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, '')) as CreatedBy
                                        , CONCAT(S.LastName, ', ', S.FirstName, ' ', COALESCE(S.MiddleName, 'N/A'), ' ', COALESCE(S.ExtName, '')) as StudentName
                                        , S.StatusId
                                        , S.FirstName
                                        , S.MiddleName
                                        , S.LastName
                                        , S.ExtName
                                        , S.StudentNumber
                                        , DATE_FORMAT(S.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                        , S.DateCreated as rawDateCreated
                                        , SS.Description as StatusDescription
                                        , SS.Color
                                        , S.Id
                                        FROM R_Students S
                                          INNER JOIN R_Status SS
                                            ON SS.Id = S.StatusId
                                          INNER JOIN r_employees EMP
                                            ON EMP.EmployeeNumber = S.CreatedBy
                                          INNER JOIN add_barangay B
                                            ON S.barangayId = B.brgyCode
      ");
      return $query->num_rows();
    }

    function countFilteredStudentList()
    {
      $this->_get_datatables_query_StudentList();
      $query = $this->db->get();
      return $query->num_rows();
    }

    function getStudentsForUsers()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT CONCAT(S.LastName, ', ', S.FirstName, ' ', COALESCE(S.MiddleName, 'N/A'), ' ', COALESCE(S.ExtName, '')) as StudentName
                                        , S.StudentNumber
                                        , S.Id
                                        FROM R_Students S
                                        WHERE S.StatusId = 1
      ");
      return $query->result_array();
    }

    function getClassDetail($Id)
    {
      $query = $this->db->query("SELECT Name
                                        , MaxStudents
                                        , Description
                                        FROM R_ClassList
                                          WHERE Id = $Id
      ");
      return $query->row_array();
    }

    function getSubjectClassList($Id)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT   S.Code
                                          , S.Name as SubjectName
                                          , S.Units
                                          , AHS.StatusId
                                          , S.Id
                                          , AHS.Description
                                          , AHS.MaxStudents
                                          , AHS.SubjectId
                                          , AHS.FacultyId
                                          , SS.Description as StatusDescription
                                          , SS.Color
                                          , AHS.Id as ClassSubjectId
                                          , COUNT(CHS.StudentId) as TotalStudents
                                          FROM class_has_subjects AHS
                                          INNER JOIN r_classlist C
                                            ON C.Id = AHS.ClassId
                                          INNER JOIN r_subjects S
                                            ON S.Id = AHS.SubjectId
                                          INNER JOIN R_Status SS
                                            ON SS.Id = AHS.StatusId
                                          LEFT JOIN ClassSubject_has_students CHS
                                            ON CHS.ClassSubjectId = AHS.Id
                                            WHERE C.Id = $Id
                                            GROUP BY AHS.Id
      ");
      return $query->result_array();
    }

    function getFacultySubjectClassList($Id)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT   S.Code
                                          , S.Name as SubjectName
                                          , S.Units
                                          , AHS.StatusId
                                          , S.Id
                                          , AHS.Description
                                          , AHS.MaxStudents
                                          , AHS.SubjectId
                                          , AHS.FacultyId
                                          , SS.Description as StatusDescription
                                          , SS.Color
                                          , AHS.Id as ClassSubjectId
                                          , COUNT(CHS.StudentId) as TotalStudents
                                          FROM class_has_subjects AHS
                                          INNER JOIN r_classlist C
                                            ON C.Id = AHS.ClassId
                                          INNER JOIN r_subjects S
                                            ON S.Id = AHS.SubjectId
                                          INNER JOIN R_Status SS
                                            ON SS.Id = AHS.StatusId
                                          LEFT JOIN ClassSubject_has_students CHS
                                            ON CHS.ClassSubjectId = AHS.Id
                                            WHERE C.Id = $Id
                                            AND AHS.FacultyId = '$EmployeeNumber'
                                            AND AHS.StatusId = 1
                                            GROUP BY AHS.Id
      ");
      return $query->result_array();
    }

    function getSubjectClassDetails($Id)
    {
      $query = $this->db->query("SELECT   S.Code
                                          , S.Name as SubjectName
                                          , AHS.Description as SubjectDescription
                                          , CONCAT(S.Code, '-', LPAD(AHS.Id, 5, 0)) as SubjectCode
                                          , S.Units
                                          , AHS.StatusId
                                          , S.Id
                                          , AHS.Description
                                          , AHS.MaxStudents
                                          , AHS.Description
                                          , SS.Description as StatusDescription
                                          , C.Name as ClassName
                                          , C.Id as ClassId
                                          , SS.Color
                                          , AHS.Id as ClassSubjectId
                                          FROM class_has_subjects AHS
                                          INNER JOIN r_classlist C
                                            ON C.Id = AHS.ClassId
                                          INNER JOIN r_subjects S
                                            ON S.Id = AHS.SubjectId
                                          INNER JOIN R_Status SS
                                            ON SS.Id = AHS.StatusId
                                            WHERE AHS.Id = $Id
      ");
      return $query->row_array();
    }

    function getSubjectStudentList($Id)
    {
      $query = $this->db->query("SELECT   CONCAT(S.LastName, ', ', S.FirstName, ' ', COALESCE(S.MiddleName,'N/A'), ' ', COALESCE(S.ExtName, '')) as Name
                                          , S.StudentNumber
                                          , CHS.StatusId
                                          , SS.Description as StatusDescription
                                          , SS.Color
                                          , CHS.StatusId
                                          , CHS.Grade
                                          , CHS.Id as ClassStudentId
                                          FROM classsubject_has_students CHS
                                                INNER JOIN r_students S
                                                    ON S.Id = CHS.StudentId
                                                  INNER JOIN r_status SS
                                                    ON SS.Id = S.StatusId
                                                      WHERE CHS.ClassSubjectId = $Id
      ");
      return $query->result_array();
    }

    function getStudents($Id)
    {
      $query = $this->db->query("SELECT   CONCAT(S.LastName, ', ', S.FirstName, ' ', COALESCE(S.MiddleName,'N/A'), ' ', COALESCE(S.ExtName, '')) as Name
                                          , S.Id
                                          FROM R_Students S
                                            WHERE StatusId = 1
                                            AND NOT EXISTS(SELECT * FROM ClassSubject_has_students WHERE ClassSubjectId = $Id AND StudentId = S.Id)
      ");
      return $query->result_array();
    }

    function getSubjectCreatedExam($Id)
    {
      $query = $this->db->query("SELECT   CSE.Id as ExamId
                                          , CSE.Description
                                          , S.Description as StatusDescription
                                          , S.Color
                                          , CSE.StatusId
                                          , CONCAT('EX-', LPAD(CSE.Id, 6, 0)) as ExamCode
                                          FROM classsubject_has_exam CSE
                                                INNER JOIN class_has_subjects CHS
                                                    ON CHS.ID = CSE.ClassSubjectId
                                                  INNER JOIN r_status S
                                                    ON S.Id = CSE.StatusId
                                                      WHERE CHS.ID = $Id
      ");
      return $query->result_array();
    }

    function getSubjectExamDetails($Id)
    {
      $query = $this->db->query("SELECT   S.Code
                                          , S.Name as SubjectName
                                          , AHS.Description as SubjectDescription
                                          , CONCAT(S.Code, '-', LPAD(AHS.Id, 5, 0)) as SubjectCode
                                          , S.Units
                                          , AHS.StatusId
                                          , S.Id
                                          , AHS.Description
                                          , AHS.MaxStudents
                                          , AHS.Description
                                          , SS.Description as StatusDescription
                                          , SS.Color
                                          , C.Name as ClassName
                                          , C.Id as ClassId
                                          , AHS.Id as ClassSubjectId
                                          , CSE.Description
                                          , CONCAT('EX-', LPAD(CSE.Id, 6, 0)) as ExamCode
                                          FROM classsubject_has_exam CSE
                                          INNER JOIN class_has_subjects AHS
                                            ON CSE.ClassSubjectId = AHS.Id
                                          INNER JOIN r_classlist C
                                            ON C.Id = AHS.ClassId
                                          INNER JOIN r_subjects S
                                            ON S.Id = AHS.SubjectId
                                          INNER JOIN R_Status SS
                                            ON SS.Id = AHS.StatusId
                                            WHERE CSE.Id = $Id
      ");
      return $query->row_array();
    }

    function getExamCategories($Id)
    {
      $query = $this->db->query("SELECT   EHC.Name
                                          , EHC.Instructions
                                          , EHC.Percentage
                                          , SS.Description as StatusDescription
                                          , SS.Color
                                          , EHC.StatusId
                                          , EHC.Id as CategoryId
                                          , COUNT(EHS.Id) as TotalSubCategory
                                          FROM exam_has_category EHC
                                            INNER JOIN classsubject_has_exam CHE
                                              ON CHE.ID = EHC.ExamId
                                            INNER JOIN R_Status SS
                                              ON SS.Id = EHC.StatusId
                                            LEFT JOIN exam_has_subcategory EHS
                                              ON EHS.ExamCategoryId = EHC.ID
                                              WHERE EHC.ExamId = $Id
                                              AND EHC.StatusId = 1
                                              GROUP BY EHC.ID
      ");
      return $query->result_array();
    }

    function getExamReviewers($Id)
    {
      $query = $this->db->query("SELECT   EH.FileName
                                          , EH.FileTitle
                                          , EH.Notes
                                          , EH.ID
                                          , EH.StatusId
                                          , S.Color
                                          , S.Description as StatusDescription
                                          , CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, '')) as CreatedBy
                                          , DATE_FORMAT(EH.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                          FROM exam_has_reviewers EH
                                                INNER JOIN r_employees EMP
                                                    ON EMP.EmployeeNumber = EH.CreatedBy
                                                  INNER JOIN r_status S
                                                    ON S.Id = EH.StatusId
                                                WHERE EH.ExamId = $Id
                                                  AND EH.StatusId = 1
      ");
      return $query->result_array();
    }

    function getSubjectExamCategoryDetails($Id)
    {
      $query = $this->db->query("SELECT   S.Code
                                          , S.Name as SubjectName
                                          , AHS.Description as SubjectDescription
                                          , CONCAT(S.Code, '-', LPAD(AHS.Id, 5, 0)) as SubjectCode
                                          , S.Units
                                          , AHS.StatusId
                                          , S.Id
                                          , AHS.Description
                                          , AHS.MaxStudents
                                          , AHS.Description
                                          , SS.Description as StatusDescription
                                          , SS.Color
                                          , C.Name as ClassName
                                          , C.Id as ClassId
                                          , AHS.Id as ClassSubjectId
                                          , CSE.Description
                                          , CONCAT('EX-', LPAD(CSE.Id, 6, 0)) as ExamCode
                                          , AHSS.Name as Category
                                          , AHSS.Percentage
                                          , COALESCE(AHSS.Percentage / COUNT(EHS.Id), 0) as PercentageBySubCategory
                                          , CSE.Id as ExamId
                                          FROM classsubject_has_exam CSE
                                          INNER JOIN class_has_subjects AHS
                                            ON CSE.ClassSubjectId = AHS.Id
                                          INNER JOIN r_classlist C
                                            ON C.Id = AHS.ClassId
                                          INNER JOIN r_subjects S
                                            ON S.Id = AHS.SubjectId
                                          INNER JOIN R_Status SS
                                            ON SS.Id = AHS.StatusId
                                          INNER JOIN exam_has_category AHSS
                                            ON AHSS.ExamId = CSE.Id
                                          LEFT JOIN exam_has_subcategory EHS
                                            ON EHS.ExamCategoryId = AHSS.ID
                                            WHERE AHSS.Id = $Id
                                            GROUP BY CSE.Id
      ");
      return $query->row_array();
    }

    function getSubjectExamCategorySubDetails($Id)
    {
      $query = $this->db->query("SELECT   ES.Id as SubCategoryId
                                          , ES.Name as SubCategory
                                          , ES.Instructions
                                          FROM exam_has_subcategory ES
                                                INNER JOIN exam_has_category EC
                                                    ON ES.ExamCategoryId = EC.Id
                                                        WHERE EC.ID = $Id
                                                        AND ES.StatusId = 1
      ");
      return $query->result_array();
    }

    function getExamSubCategories($Id)
    {
      $query = $this->db->query("SELECT   ES.Id as SubCategoryId
                                          , ES.Name as SubCategory
                                          , ES.Instructions
                                          , EC.Id as CategoryId
                                          , COUNT(SHQ.Id) as TotalQuestions
                                          , ES.Instructions
                                          FROM exam_has_subcategory ES
                                                INNER JOIN exam_has_category EC
                                                    ON ES.ExamCategoryId = EC.Id
                                                LEFT JOIN subcategory_has_questions SHQ
                                                  ON SHQ.SubCategoryId = ES.Id
                                                        WHERE EC.ID = $Id
                                                        AND ES.StatusId = 1
                                                        GROUP BY ES.Id
      ");
      return $query->result_array();
    }

    function getExamQuestions($Id)
    {
      $query = $this->db->query("SELECT  DISTINCT COUNT(DISTINCT SO.Id) as TotalOptions
                                        , SQ.Question
                                        , SQ.Id as QuestionId
                                        , SO2.OptionName
                                        , SQ.Id
                                        , SQ.StatusId
                                        FROM subcategory_has_questions SQ
                                          INNER JOIN subcategory_has_options SO
                                            ON SO.subquestionId = SQ.Id
                                          INNER JOIN subcategory_has_options SO2
                                            ON SO2.OptionNo = SQ.Answer
                                            AND SO2.subquestionId = SQ.Id
                                            WHERE SQ.SubCategoryId = $Id
                                            GROUP BY SQ.Id
      ");
      return $query->result_array();
    }

    function getSubjectExamSubCategoryDetails($Id)
    {
      $query = $this->db->query("SELECT   S.Code
                                          , S.Name as SubjectName
                                          , AHS.Description as SubjectDescription
                                          , CONCAT(S.Code, '-', LPAD(AHS.Id, 5, 0)) as SubjectCode
                                          , S.Units
                                          , AHS.StatusId
                                          , S.Id
                                          , AHS.Description
                                          , AHS.MaxStudents
                                          , AHS.Description
                                          , SS.Description as StatusDescription
                                          , SS.Color
                                          , C.Name as ClassName
                                          , C.Id as ClassId
                                          , AHS.Id as ClassSubjectId
                                          , CSE.Description
                                          , CONCAT('EX-', LPAD(CSE.Id, 6, 0)) as ExamCode
                                          , AHSS.Name as Category
                                          , AHSS.Percentage
                                          , AHSS.Percentage / COUNT(EHS.Id) as PercentageBySubCategory
                                          , EHS.Name as SubCategory
                                          , CSE.Id as ExamId
                                          , AHSS.Id as CategoryId
                                          FROM classsubject_has_exam CSE
                                          INNER JOIN class_has_subjects AHS
                                            ON CSE.ClassSubjectId = AHS.Id
                                          INNER JOIN r_classlist C
                                            ON C.Id = AHS.ClassId
                                          INNER JOIN r_subjects S
                                            ON S.Id = AHS.SubjectId
                                          INNER JOIN R_Status SS
                                            ON SS.Id = AHS.StatusId
                                          INNER JOIN exam_has_category AHSS
                                            ON AHSS.ExamId = CSE.Id
                                          LEFT JOIN exam_has_subcategory EHS
                                            ON EHS.ExamCategoryId = AHSS.ID
                                            WHERE CSE.Id = $Id
                                            GROUP BY CSE.Id
      ");
      return $query->row_array();
    }

    function getExamSubCategoryQuestions($Id)
    {
      $query = $this->db->query("SELECT   Question
                                          , ID
                                          , Answer
                                          FROM subcategory_has_questions EHQ
                                                WHERE EHQ.SubCategoryId = $Id
      ");
      return $query->result_array();
    }

    function getExamSubCategoryOptions($Id)
    {
      $query = $this->db->query("SELECT   OptionName
                                          , subquestionId
                                          , OptionNo
                                          FROM subcategory_has_options
                                                WHERE subquestionId = $Id
      ");
      return $query->result_array();
    }

    function getStudentClassList()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $StudentId = $this->session->userdata('EmployeeId');
      $query = $this->db->query("SELECT CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, '')) as Name
                                        , S.MaxStudents as MaxStudents
                                        , SS.Description as StatusDescription
                                        , S.StatusId
                                        , S.Description as ClassDescription
                                        , S.Name as ClassName
                                        , SS.Color
                                        , S.Id
                                        FROM R_ClassList S
                                          INNER JOIN R_Status SS
                                            ON SS.Id = S.StatusId
                                          INNER JOIN r_employees EMP
                                            ON EMP.EmployeeNumber = S.CreatedBy 
                                          INNER JOIN class_has_subjects CHS
                                            ON CHS.ClassId = S.Id
                                            WHERE EXISTS (SELECT * FROM classsubject_has_students WHERE ClassSubjectId = CHS.Id AND StatusId = 1 AND StudentId = '$StudentId')
                                            GROUP BY S.ID
      ");
      return $query->result_array();
    }

    function getStudentSubjectClassList($Id)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT   S.Code
                                          , S.Name as SubjectName
                                          , S.Units
                                          , AHS.StatusId
                                          , S.Id
                                          , AHS.Description
                                          , AHS.MaxStudents
                                          , AHS.SubjectId
                                          , AHS.FacultyId
                                          , SS.Description as StatusDescription
                                          , SS.Color
                                          , AHS.Id as ClassSubjectId
                                          , COUNT(CHS.StudentId) as TotalStudents
                                          FROM class_has_subjects AHS
                                          INNER JOIN r_classlist C
                                            ON C.Id = AHS.ClassId
                                          INNER JOIN r_subjects S
                                            ON S.Id = AHS.SubjectId
                                          INNER JOIN R_Status SS
                                            ON SS.Id = AHS.StatusId
                                          LEFT JOIN ClassSubject_has_students CHS
                                            ON CHS.ClassSubjectId = AHS.Id
                                            WHERE C.Id = $Id
                                            AND AHS.StatusId = 1
                                            GROUP BY AHS.Id
      ");
      return $query->result_array();
    }

    function studentHasExam($Id)
    {
      $StudentId = $this->session->userdata('EmployeeId');
      $query = $this->db->query("SELECT   * 
                                          FROM classsubject_has_exam CHE
                                                INNER JOIN classsubject_has_students CHS
                                                  ON CHS.ClassSubjectId = CHE.ClassSubjectId
                                                INNER JOIN student_has_exam HE
                                                  ON HE.StudentId = CHS.StudentId
                                                  WHERE CHE.ClassSubjectId = $Id
                                                  AND CHS.StudentId = $StudentId
      ");
      return $query->num_rows();
    }

    function studentExamDetails($Id)
    {
      $StudentId = $this->session->userdata('EmployeeId');
      $query = $this->db->query("SELECT   HE.DateCreated
                                          , HE.StatusId
                                          , S.Description as StatusDescription
                                          , S.Color
                                          , DATE_FORMAT(HE.DateCreated, '%b %d, %Y %h:%i %p') as DateTaken
                                          , HE.ExamId
                                          FROM classsubject_has_exam CHE
                                                INNER JOIN classsubject_has_students CHS
                                                    ON CHS.ClassSubjectId = CHE.ClassSubjectId
                                                  INNER JOIN student_has_exam HE
                                                    ON HE.StudentId = CHS.StudentId
                                                  INNER JOIN R_Status S
                                                      ON S.Id = HE.StatusId
                                                      WHERE CHE.ClassSubjectId = $Id
                                                      AND CHS.StudentId = $StudentId
      ");
      return $query->row_array();
    }

    function countMockExam($Id)
    {
      $StudentId = $this->session->userdata('EmployeeId');
      $query = $this->db->query("SELECT   * 
                                          FROM classsubject_has_exam
                                              WHERE ClassSubjectId = $Id
      ");
      return $query->num_rows();
    }

    function getExamAnswers($Id)
    {
      $query = $this->db->query("SELECT   EHA.IsCorrect
                                          , EHA.AnswerId
                                          , EHA.CorrectAnswer
                                          , EHA.QuestionId
                                          FROM student_has_exam SHE
                                                INNER JOIN exam_has_answers EHA
                                                    ON EHA.StudentExamId = SHE.ID
                                                        WHERE QuestionId = $Id
      ");
      return $query->row_array();
    }

    function countCorrectAnswers($Id)
    {
      $query = $this->db->query("SELECT   *
                                          FROM student_has_exam SHE
                                                INNER JOIN exam_has_answers EHA
                                                    ON EHA.StudentExamId = SHE.ID
                                                        WHERE SHE.ExamId = $Id
                                                        AND EHA.IsCorrect = 1
      ");
      return $query->num_rows();
    }

    function countQuestions($Id)
    {
      $query = $this->db->query("SELECT   DISTINCT SHQ.Id
                                          FROM classsubject_has_exam CHE
                                                INNER JOIN exam_has_category EHC
                                                    ON EHC.ExamId = CHE.ID
                                                  INNER JOIN exam_has_subcategory EHS
                                                    ON EHS.ExamCategoryId = EHC.ID
                                            INNER JOIN subcategory_has_questions SHQ
                                                    ON SHQ.SubCategoryId = EHS.Id
                                                        WHERE CHE.Id = $Id
      ");
      return $query->num_rows();
    }

    function countIncorrectAnswers($Id)
    {
      $query = $this->db->query("SELECT   *
                                          FROM student_has_exam SHE
                                                INNER JOIN exam_has_answers EHA
                                                    ON EHA.StudentExamId = SHE.ID
                                                        WHERE SHE.ExamId = $Id
                                                        AND EHA.IsCorrect = 0
      ");
      return $query->num_rows();
    }

    function getRegistrarSubjectList()
    {
      $query = $this->db->query("SELECT   S.Code
                                          , S.Name
                                          , CONCAT(S.Code, '-', LPAD(S.Id, 5, 0)) as SubjectCode
                                          , CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, '')) as Faculty
                                          , C.Name as ClassName
                                          , C.Description as ClassDescription
                                          , SS.Description as StatusDescription
                                          , SS.Color
                                          , CHS.StatusId
                                          , CHS.ClassId
                                          , CONCAT(DATE_FORMAT(EXS.StartDate, '%b %d, %Y %h:%i %p'), ' to ', DATE_FORMAT(EXS.EndDate, '%b %d, %Y %h:%i %p')) as ExamSchedule
                                          , CHS.Id as ClassSubjectId
                                          FROM class_has_subjects CHS
                                            INNER JOIN r_subjects S 
                                              ON CHS.SubjectId = S.Id
                                            INNER JOIN r_employees EMP
                                              ON EMP.EmployeeNumber = CHS.FacultyId
                                            INNER JOIN r_classlist C
                                              ON C.Id = CHS.ClassId
                                            INNER JOIN R_Status SS
                                              ON SS.Id = CHS.StatusId
                                            LEFT JOIN classsubject_has_examschedule EXS
                                              ON EXS.ClassSubjectId = CHS.Id
                                              AND EXS.StatusId = 1
                                              WHERE C.StatusId = 1
                                              AND EMP.StatusId = 1
                                              AND S.StatusId = 1
      ");
      return $query->result_array();
    }

    function getSubjectSchedule($Id)
    {
      $query = $this->db->query("SELECT   CONCAT(DATE_FORMAT(ES.StartDate, '%b %d, %Y %h:%i %p'), ' to ', DATE_FORMAT(ES.EndDate, '%b %d, %Y %h:%i %p')) as ExamSchedule
                                          , DATE_FORMAT(ES.StartDate - interval 1 hour, '%b %d, %Y %h:%i %p') as LastDateToCreate
                                          , DATE_FORMAT(ES.StartDate, '%b %d, %Y %h:%i %p') as DateStart
                                          , CASE
                                              WHEN ES.StartDate - interval 1 hour > NOW()
                                                THEN 'OK'
                                                ELSE 'NOT OKAY'
                                          END as ForCreation
                                          , CASE
                                              WHEN NOW() BETWEEN ES.StartDate AND ES.EndDate
                                                THEN 'For exam'
                                                ELSE 'NOT FOR EXAM'
                                          END as ForExam
                                          FROM classsubject_has_examschedule ES
                                                INNER JOIN class_has_subjects CS
                                                    ON CS.ID = ES.ClassSubjectId
                                                    WHERE CS.ID = $Id
                                                    AND ES.StatusId = 1
      ");
      if($query->num_rows() > 0)
      {
        return $query->row_array();
      }
      else
      {
        return 0;
      }
    }

    function getQuestionOptions($Id)
    {
      $query = $this->db->query("SELECT   subquestionId  as QuestionNo
                                          , OptionNo
                                          , OptionName
                                          , SHQ.Question
                                          , SHQ.Answer
                                          FROM subcategory_has_options SHO
                                            INNER JOIN subcategory_has_questions SHQ
                                              ON SHQ.Id = SHO.subquestionId
                                                WHERE subquestionId = $Id
      ");
      return $query->result_array();
    }

    function getStudentSubjectList()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $StudentId = $this->session->userdata('EmployeeId');
      $query = $this->db->query("SELECT  DISTINCT CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, '')) as Faculty
                                          , S.Name
                                          , CHS.Grade
                                          , CONCAT(S.Code, '-', LPAD(CHS2.Id, 5, 0)) as SubjectCode
                                          , CHS.ClassSubjectId
                                          , CHS2.ClassId
                                          , SHE.Id as ExamId
                                          , CSExam.Id as CreatedExamId
                                          , SHE.StatusId
                                          FROM classsubject_has_students CHS
                                            INNER JOIN class_has_subjects CHS2
                                              ON CHS.ClassSubjectId = CHS2.ID
                                            INNER JOIN r_subjects S
                                              ON S.Id = CHS2.SubjectId
                                            INNER JOIN r_employees EMP
                                              ON EMP.EmployeeNumber = CHS2.FacultyId
                                            LEFT JOIN classsubject_has_exam CSExam
                                              ON CSExam.ClassSubjectId = CHS.ClassSubjectId
                                            LEFT JOIN student_has_exam SHE
                                              ON SHE.StudentId = CHS.StudentId
                                              AND SHE.ExamId = CSExam.ID
                                              WHERE CHS.StudentId = $StudentId
      ");
      return $query->result_array();
    }

    function getExamGrade()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $StudentId = $this->session->userdata('EmployeeId');
      $query = $this->db->query("SELECT   CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, '')) as Faculty
                                          , S.Name
                                          , CHS.Grade
                                          , CONCAT(S.Code, '-', LPAD(CHS2.Id, 5, 0)) as SubjectCode
                                          FROM classsubject_has_students CHS
                                                INNER JOIN class_has_subjects CHS2
                                                    ON CHS.ClassSubjectId = CHS2.ID
                                                  INNER JOIN r_subjects S
                                                    ON S.Id = CHS2.SubjectId
                                                  INNER JOIN r_employees EMP
                                                    ON EMP.EmployeeNumber = CHS2.FacultyId
                                                    WHERE CHS.StudentId = $StudentId
      ");
      return $query->row_array();
    }

    function getExamsApproval()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT   CONCAT(SS.Code, '-', LPAD(SS.Id, 5, 0)) as SubjectCode
                                            , SS.Name
                                            , CONCAT(S.LastName, ', ', S.FirstName, ' ', COALESCE(S.MiddleName,'N/A'), ' ', COALESCE(S.ExtName, ''), ' - ', S.StudentNumber) as Student
                                            , C.Name as ClassName
                                            , C.Description as ClassDescription
                                            , CHE.Id as PreviousExamId
                                            , STAT.Color
                                            , STAT.Description as StatusDescription
                                            , SHE.StatusId
                                            FROM student_has_exam SHE
                                              INNER JOIN r_students S
                                                ON S.Id = SHE.StudentId
                                              INNER JOIN classsubject_has_exam CHE
                                                ON CHE.ID = SHE.ExamId
                                              INNER JOIN class_has_subjects SHS
                                                ON SHS.ID = CHE.ClassSubjectId
                                              INNER JOIN r_subjects SS
                                                ON SS.ID = SHS.SubjectId
                                              INNER JOIN r_classlist C
                                                ON C.Id = SHS.ClassId
                                              INNER JOIN R_Status STAT
                                                ON STAT.Id = SHE.StatusId
                                                  WHERE (
                                                    SHE.StatusId = 9
                                                    OR
                                                    SHE.StatusId = 10
                                                  )
                                                  AND SHS.FacultyId = '$EmployeeNumber'
      ");
      return $query->result_array();
    }

    function getExamSchedules($Id)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT   CONCAT(DATE_FORMAT(ES.StartDate, '%b %d, %Y %h:%i %p'), ' to ', DATE_FORMAT(ES.EndDate, '%b %d, %Y %h:%i %p')) as ExamSchedule
                                          , DATE_FORMAT(ES.StartDate - interval 1 hour, '%b %d, %Y %h:%i %p') as LastDateToCreate
                                          , DATE_FORMAT(ES.StartDate, '%b %d, %Y %h:%i %p') as DateStart
                                          , CASE
                                              WHEN ES.StartDate - interval 1 hour > NOW()
                                                THEN 'OK'
                                                ELSE 'NOT OKAY'
                                          END as ForCreation
                                          , CASE
                                              WHEN NOW() BETWEEN ES.StartDate AND ES.EndDate
                                                THEN 'For exam'
                                                ELSE 'NOT FOR EXAM'
                                          END as ForExam
                                          , S.Description as StatusDescription
                                          , S.Color
                                          , ES.StatusId
                                          , ES.Id as ScheduleId
                                          FROM classsubject_has_examschedule ES
                                            INNER JOIN class_has_subjects CS
                                              ON CS.ID = ES.ClassSubjectId
                                            INNER JOIN R_Status S
                                              ON S.Id = ES.StatusId
                                              WHERE CS.ID = $Id
      ");
      return $query->result_array();
    }

    function generateStudentList()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT CONCAT(EMP.LastName, ', ', EMP.FirstName, ' ', COALESCE(EMP.MiddleName,'N/A'), ' ', COALESCE(EMP.ExtName, '')) as CreatedBy
                                        , CONCAT(S.LastName, ', ', S.FirstName, ' ', COALESCE(S.MiddleName, 'N/A'), ' ', COALESCE(S.ExtName, '')) as StudentName
                                        , S.StatusId
                                        , S.FirstName
                                        , S.MiddleName
                                        , S.LastName
                                        , S.ExtName
                                        , S.StudentNumber
                                        , DATE_FORMAT(S.DateCreated, '%b %d, %Y %h:%i %p') as DateCreated
                                        , S.DateCreated as rawDateCreated
                                        , SS.Description as StatusDescription
                                        , SS.Color
                                        , S.Id
                                        FROM R_Students S
                                          INNER JOIN R_Status SS
                                            ON SS.Id = S.StatusId
                                          INNER JOIN r_employees EMP
                                            ON EMP.EmployeeNumber = S.CreatedBy
      ");
      return $query->result_array();
    }

    function getSearchResult($GradeFrom, $GradeTo, $SubjectId)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');

      $search = '';
      if($GradeFrom == 0 && $GradeTo == 0)
      {
        $search .= '';
      }
      else if($GradeFrom > 0 && $GradeTo > 0)
      {
        $search .= ' AND Grade BETWEEN '.$GradeFrom.' AND '.$GradeFrom.'';
      }


      if($SubjectId == 'All')
      {
        $search .= '';
      }
      else
      {
        $search .= ' AND ClassSubject.ID = '. $SubjectId;
      }

      $query = $this->db->query("SELECT   CONCAT(SS.Code, '-', LPAD(ClassSubject.Id, 5, 0)) as SubjectCode
                                          , SS.Name
                                          , CONCAT(S.LastName, ', ', S.FirstName, ' ', COALESCE(S.MiddleName,'N/A'), ' ', COALESCE(S.ExtName, '')) as StudentName
                                          , S.StudentNumber
                                          , ClassExam.Id as PreviousExamId
                                          , Grade
                                          FROM class_has_subjects ClassSubject
                                          INNER JOIN classsubject_has_students ClassStuds
                                              ON ClassStuds.ClassSubjectId = ClassSubject.ID
                                            INNER JOIN r_students S
                                              ON S.Id = ClassStuds.StudentId
                                            LEFT JOIN classsubject_has_exam ClassExam
                                              ON ClassExam.ClassSubjectId = ClassSubject.ID
                                            LEFT JOIN r_subjects SS
                                              ON SS.ID = ClassSubject.SubjectId
                                              WHERE SS.StatusId = 1
                                                ".$search."
      ");
      return $query->result_array();
    }

    function getSubjectDetails($GradeFrom, $GradeTo, $SubjectId)
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');

      $search = '';
      if($SubjectId == 'All')
      {
        $search .= '';
      }
      else
      {
        $search .= ' AND ClassSubject.ID = '. $SubjectId;
      }

      $query = $this->db->query("SELECT   CONCAT(SS.Code, '-', LPAD(ClassSubject.Id, 5, 0)) as SubjectCode
                                          , SS.Name
                                          , CONCAT(S.LastName, ', ', S.FirstName, ' ', COALESCE(S.MiddleName,'N/A'), ' ', COALESCE(S.ExtName, '')) as StudentName
                                          , S.StudentNumber
                                          , ClassExam.Id as PreviousExamId
                                          , Grade
                                          FROM class_has_subjects ClassSubject
                                          INNER JOIN classsubject_has_students ClassStuds
                                              ON ClassStuds.ClassSubjectId = ClassSubject.ID
                                            INNER JOIN r_students S
                                              ON S.Id = ClassStuds.StudentId
                                            LEFT JOIN classsubject_has_exam ClassExam
                                              ON ClassExam.ClassSubjectId = ClassSubject.ID
                                            LEFT JOIN r_subjects SS
                                              ON SS.ID = ClassSubject.SubjectId
                                              WHERE SS.StatusId = 1
                                                ".$search."
      ");
      return $query->row_array();
    }

    function getClassSubjects()
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      $query = $this->db->query("SELECT   CONCAT(S.Code, '-', LPAD(CHS.Id, 5, 0)) as SubjectCode
                                          , S.Name as SubjectName
                                          , CHS.ID as ClassSubjectId
                                          FROM class_has_subjects CHS
                                                INNER JOIN r_subjects S
                                                    ON S.ID = CHS.SubjectId
                                                  INNER JOIN r_classlist C
                                                    ON C.Id = CHS.ClassId
                                                      WHERE C.StatusId = 1
      ");
      return $query->result_array();
    }

    function getRegionList()
    {
      $query = $this->db->query("SELECT regDesc as 'text'
                                        , regCode as 'id'
                                        FROM add_region
      ");
      $output = '<option selected disabled value="">Select Region</option>';
      if($query)
      {
        foreach ($query->result() as $row)
        {
          $output .= '<option value="'.$row->id.'">'.$row->text.'</option>';
        }
      }
      return $output;
    }

    function getProvinces($RegionCode)
    {
      $query = $this->db->query("SELECT ProvDesc, provCode FROM add_province WHERE RegCode = '".$RegionCode."' ORDER BY provCode ASC");
      $output = '<option selected disabled value="">Select Province</option>';
      if($query)
      {
        foreach ($query->result() as $row)
        {
          $output .= '<option value="'.$row->provCode.'">'.$row->ProvDesc.'</option>';
        }
      }
      return $output;
    }

    function getCities($ProvinceCode)
    {
      $query = $this->db->query("SELECT citymunDesc, citymunCode FROM add_city WHERE provCode = '".$ProvinceCode."' ORDER BY citymunCode ASC");
      $output = '<option selected disabled value="">Select City</option>';
      if($query)
      {
        foreach ($query->result() as $row)
        {
          $output .= '<option value="'.$row->citymunCode.'">'.$row->citymunDesc.'</option>';
        }
      }
      return $output;
    }

    function getBarangays($cityMunCode)
    {
      $query = $this->db->query("SELECT brgyCode, brgyDesc FROM add_barangay WHERE cityMunCode = '".$cityMunCode."' ORDER BY brgyCode ASC");
      $output = '<option selected disabled value="">Select Barangay</option>';
      if($query)
      {
        foreach ($query->result() as $row)
        {
          $output .= '<option value="'.$row->brgyCode.'">'.$row->brgyDesc.'</option>';
        }
      }
      return $output;
    }

    function getMaritalStatus()
    {
      $query = $this->db->query("SELECT   id
                                          , description
                                          FROM r_marital_status
                                          WHERE statusId = 1
      ");
      $output = '<option selected disabled value="">Select Marital Status</option>';
      if($query)
      {
        foreach ($query->result() as $row)
        {
          $output .= '<option value="'.$row->id.'">'.$row->description.'</option>';
        }
      }
      return $output;
    }

    function getGraduatingStatus()
    {
      $query = $this->db->query("SELECT   id
                                          , description
                                          FROM r_graduating_status
                                          WHERE statusId = 1
      ");
      $output = '<option selected disabled value="">Select Graduating Status</option>';
      if($query)
      {
        foreach ($query->result() as $row)
        {
          $output .= '<option value="'.$row->id.'">'.$row->description.'</option>';
        }
      }
      return $output;
    }

    function getOccupations()
    {
      $query = $this->db->query("SELECT   id
                                          , description
                                          FROM r_occupation
                                          WHERE statusId = 1
      ");
      $output = '<option selected disabled value="">Select Occupation</option>';
      if($query)
      {
        foreach ($query->result() as $row)
        {
          $output .= '<option value="'.$row->id.'">'.$row->description.'</option>';
        }
      }
      return $output;
    }

}