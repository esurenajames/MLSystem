<div class="modal fade show" id="modalRenew" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Change Temporary Password</h4>
        </div>
        <form action="<?php echo base_url(); ?>admin_controller/addUser/1" class="frminsert2" method="post">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">New Password<span class="text-red">*</span></label>
                  <div class="form-group" id="colorSuccess">
                    <label class="control-label" id="lblSuccess" style="display: none" for="inputSuccess"><i class="fa fa-check"></i></label>
                    <input type="password" class="form-control"  required="" name="NewPassword" id="txtNewPassword" oninput="checkNewPassword(this.value);" placeholder="Enter New password">
                    <span id="successMessage" style="display: none" class="help-block"></span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="exampleInputEmail1">Confirm Password<span class="text-red">*</span></label>
                  <div class="form-group" id="colorSuccess2">
                    <label class="control-label" id="lblSuccess2" style="display: none" for="txtConfirmPassword"><i class="fa fa-check"></i></label>
                    <input type="password" class="form-control" required="" id="txtConfirmPassword" oninput="checkPasswordMatch(this.value);">
                    <span id="successMessage2" style="display: none" class="help-block"></span>
                  </div>
                </div>
              </div>
            </div>
            <h6>SECURITY QUESTIONS</h6>
            <div class="row">
              <div class="col-md-6">
                <h6>1st Question</h6>
                <select class="form-control select2" name="Question1" style="width: 100%;">
                  <?php 
                    foreach ($questions as $key => $value) 
                    {
                      echo '<option value="'.$value['Id'].'">'.$value['Description'].'</option>';
                    }
                  ?>
                </select>
              </div>
              <div class="col-md-6">
                <h6>Answer</h6>
                <input type="text" name="Answer1" required="" class="form-control">
              </div>
              <div class="col-md-6">
                <h6>2nd Question</h6>
                <select class="form-control select2" name="Question2" style="width: 100%;">
                  <?php 
                    foreach ($questions as $key => $value) 
                    {
                      echo '<option value="'.$value['Id'].'">'.$value['Description'].'</option>';
                    }
                  ?>
                </select>
              </div>
              <div class="col-md-6">
                <h6>Answer</h6>
                <input type="text" name="Answer2" required="" class="form-control">
              </div>
              <div class="col-md-6">
                <h6>3rd Question</h6>
                <select class="form-control select2" name="Question3" style="width: 100%;">
                  <?php 
                    foreach ($questions as $key => $value) 
                    {
                      echo '<option value="'.$value['Id'].'">'.$value['Description'].'</option>';
                    }
                  ?>
                </select>
              </div>
              <div class="col-md-6">
                <h6>Answer</h6>
                <input type="text" name="Answer3" required="" class="form-control">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div class="float-right">
              <button type="submit" class="btn btn-primary">Save changes</button>              
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

    <!-- Main content -->
    <?php if($this->session->userdata('RoleId') == 1) { ?> <!-- FACULTY -->
      <div class="content-wrapper">
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
              </div>
            </div>
          </div>
        </div>
        <div class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h5 class="m-0"></h5>
                  </div>
                  <div class="card-body">
                    <br>
                    <br>
                    <table id="example3" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th width="15%">Class Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    <?php } ?>

    <?php if($this->session->userdata('RoleId') == 2) { ?> <!-- REGISTRAR -->
      <div class="content-wrapper">
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
              </div>
            </div>
          </div>
        </div>
        <div class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h5 class="m-0"></h5>
                  </div>
                  <div class="card-body">
                    <br>
                    <br>
                      <table id="example4" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th width="15%">Class Name</th>
                          <th>Description</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                        </thead>
                      </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    <?php } ?>

    <?php if($this->session->userdata('RoleId') == 3) { ?> <!-- ADMIN -->
      <div class="content-wrapper">
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
              </div>
            </div>
          </div>
        </div>
        <div class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h5 class="m-0">User List</h5>
                  </div>
                  <div class="card-body">
                    <br>
                    <br>
                    <table id="example5" class="table table-bordered table-striped">
                      <thead>
                      <tr>
                        <th width="15%">Employee Number</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Role</th>
                        <th>Is password renewed?</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    <?php } ?>

    <?php if($this->session->userdata('RoleId') == 4) { ?> <!-- STUDENT -->
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Dashboard</h1>
            </div>
          </div>
        </div>
      </div>
      <div class="content">
        <div class="container-fluid">
          <!-- GRADE PREDICTION -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h5 class="m-0">Grade Prediction per Subject</h5>
                </div>
                <div class="card-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th width="15%">Subject Code</th>
                      <th>Subject</th>
                      <th>Faculty</th>
                      <th>Grades</th>
                      <th>Exam Grade</th>
                      <th>Prediction</th>
                      <th>Result</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-- CLASSES ENROLLED -->
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header">
                  <h5 class="m-0">Classes Enrolled</h5>
                </div>
                <div class="card-body">
                  <br>
                  <br>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th width="15%">Class Name</th>
                      <th>Description</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
    <!-- /.content -->

  </div>

  <?php $this->load->view('includes/footer'); ?>

<script type="text/javascript">
  var varStatus = 0;
  var varNewPassword = 0;

  function checkPasswordMatch(Password)
  {
    var element = document.getElementById("colorSuccess2");
    if($('#txtNewPassword').val() != Password)
    {
      element.classList.remove("has-success");
      element.classList.add("has-error");
      $('#successMessage2').slideDown();
      $('#successMessage2').html('Password does not match');
      varStatus = 0;
    }
    else
    {
      element.classList.remove("has-error");
      element.classList.add("has-success");
      $('#successMessage2').slideDown();
      $('#successMessage2').html('Password matching');
      varStatus = 1;
    }
  }

  function checkNewPassword(Password)
  {
    var element = document.getElementById("colorSuccess2");
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W\_])[A-Za-z\d\W\_]{8,}$/;
    const str = $('#txtNewPassword').val();
    let m;
    if($('#txtConfirmPassword').val() != Password)
    {
      element.classList.remove("has-success");
      element.classList.add("has-error");
      $('#successMessage2').slideDown();
      $('#successMessage2').html('Password does not match');
      varStatus = 0;
    }
    else
    {
      element.classList.remove("has-error");
      element.classList.add("has-success");
      $('#successMessage2').slideDown();
      $('#successMessage2').html('Password matching');
      varStatus = 1;
    }

    if ((m = regex.exec(str)) !== null) {
        // The result can be accessed through the `m`-variable.
        m.forEach((match, groupIndex) => {
          var element = document.getElementById("colorSuccess");
          element.classList.remove("has-error");
          element.classList.add("has-success");
          $('#successMessage').slideDown();
          $('#successMessage').html('Valid Password');
          varNewPassword = 1;
        });
    }
    else
    {
      var element = document.getElementById("colorSuccess");
      element.classList.remove("has-success");
      element.classList.add("has-error");
      $('#successMessage').slideDown();
      $('#successMessage').html('Password must contain a special, numeric and an uppercase character');
      varNewPassword = 0;
    }
  }

  function refreshPage(){
    var url = '<?php echo base_url()."admin_controller/getEmployeeList/"; ?>';
    UserTable.ajax.url(url).load();
  }

  function refreshPage2(){
    var url = '<?php echo base_url()."admin_controller/getStudentSubjectList/"; ?>';
    Grades.ajax.url(url).load();
  }

  function clickRetakeExam(TakenExamId)
  {
    swal({
      title: 'Confirm',
      text: 'Are you sure you want to submit this request to re-take failed exam?',
      type: 'info',
      showCancelButton: true,
      buttonsStyling: false,
      confirmButtonClass: 'btn btn-success',
      confirmButtonText: 'Confirm',
      cancelButtonClass: 'btn btn-secondary'
    }).then(function(){
      $.ajax({                
        url: "<?php echo base_url();?>" + "/admin_controller/requestRetakeExam",
        method: "POST",
        async: false,
        data:   {
                  TakenExamId : TakenExamId
                },  
        dataType: "JSON",
        beforeSend: function(){
            $('.loading').show();
        },
        success: function(data)
        {
          swal({
            title: 'Success!',
            text: 'Record successfully updated!',
            type: 'success',
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary'
          });
          refreshPage2();
        },
        error: function (response) 
        {
          swal({
            title: 'Warning!',
            text: 'Something went wrong, please contact the administrator or refresh page!',
            type: 'warning',
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary'
          });
        }
      });
    });
  }

  $(function () {
    <?php if($this->session->userdata('IsNew') == 1) { ?>
      $('#modalRenew').modal('show');
    <?php } ?>

    $(".frminsert2").on('submit', function (e) {
      if(varNewPassword = 1 && varStatus == 1 && $('#txtNewPassword').val() == $('#txtConfirmPassword').val() && '<?php echo $this->session->userdata('Password') ?>' != $('#txtNewPassword').val())
      {
        e.preventDefault(); 
        swal({
          title: 'Confirm',
          text: 'Are you sure with this password?',
          type: 'info',
          showCancelButton: true,
          buttonsStyling: false,
          confirmButtonClass: 'btn btn-success',
          confirmButtonText: 'Confirm',
          cancelButtonClass: 'btn btn-secondary'
        }).then(function(){
          e.currentTarget.submit();
        });
      }
      else
      {
        swal({
          title: 'Warning',
          text: 'Password not valid!',
          type: 'warning',
          buttonsStyling: false,
          confirmButtonClass: 'btn btn-primary'
        });
        e.preventDefault();
      }
    });


    $('.select2').select2();



    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/admin_controller/getStudentClassList/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "ClassName" }
                    , { data: "ClassDescription" }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        return "<span class='badge bg-"+row.Color+"'>"+row.StatusDescription+"</span>";
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        if(row.StatusId == 1){
                          return '<a href="<?php echo base_url() ?>home/viewClass/'+row.Id+'" class="btn btn-default" title="View"><span class="fa fa-eye"></span></a>';
                        }
                        else
                        {
                          return '<a href="<?php echo base_url() ?>home/viewClass/'+row.Id+'" class="btn btn-default" title="View"><span class="fa fa-eye"></span></a>';
                        }
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[0, "asc"]]
    });

    var totalPercentage = 0;
    var finalPrediction = 0;
    var examResult = '';
    var retakeExam = '';

    Grades = $('#example2').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/admin_controller/getStudentSubjectList/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [
        { data: "SubjectCode" },
        { data: "Name" },
        { data: "Faculty" },
        { data: "Grade" },
        {
          data: "ExamId", "render": function (data, type, row) {
            // Your original Exam Grade logic
            if(row.CreatedExamId !== null)
            {
              if(row.totalQuestions != 0 && row.StatusId == 1)
              {
                totalPercentage = (row.correctAnswer/row.totalQuestions) * 100;
                if(totalPercentage >= 70)
                {
                  examResult = '<label style="color:#08A133">Passed</label>';
                }
                else
                {
                  examResult = '<label style="color:#D92323">Failed</label>';
                }
                return totalPercentage + '% - ' + examResult;
              }
              else
              {
                if(row.StatusId == 1)
                {
                  if(row.totalQuestions != 0)
                  {
                    totalPercentage = 0;
                    return 'No exam taken';
                  }
                  else
                  {
                    totalPercentage = 0;
                    return 'For re-taking';
                  }
                }
                else
                {
                  totalPercentage = 0;
                  return 'No exam taken';
                }
              }
            }
            else
            {
              return 'No exam has been created.';
            }
          }
        },
        // NEW: Prediction from analytics_results
        {
          data: "Prediction", render: function(data, type, row) {
            return data !== null ? data : 0;
          }
        },
        // NEW: Result from analytics_results
        {
          data: "Result", render: function(data, type, row) {
            return data !== null ? data : 0;
          }
        },
        // Action column (keep as is)
        {
          data: "ExamId", "render": function (data, type, row) {
            // Your original Action logic
            if(row.CreatedExamId !== null)
            {
              if(row.StatusId == 10)
              {
                return '<a href="<?php echo base_url() ?>home/TakeExam/'+row.CreatedExamId+'" class="btn btn-primary" title="Take Exam"><span class="fa fa-pen-square"></span></a> ';
              }
              else
              {
                if(row.StatusId == 1 && row.totalQuestions == 0)
                {
                  return '<a href="<?php echo base_url() ?>home/TakeExam/'+row.CreatedExamId+'" class="btn btn-primary" title="Take Exam"><span class="fa fa-pen-square"></span></a> ' ;
                }
                else
                {
                  if(totalPercentage <= 70)
                  {
                    retakeExam = '<a onclick="clickRetakeExam('+row.CreatedExamId+')" class="btn btn-primary" title="Request to retake exam"><span class="fa fa-money-check"></span></a>';
                  }
                  else
                  {
                    retakeExam = '';
                  }

                  if(row.ExamId !== null){
                    return '<a href="<?php echo base_url() ?>home/viewExam/'+row.CreatedExamId+'" class="btn btn-default" title="View Exam"><span class="fa fa-eye"></span></a> ' + retakeExam;
                  }
                  else
                  {
                    return '<a href="<?php echo base_url() ?>home/TakeExam/'+row.CreatedExamId+'" class="btn btn-primary" title="Take Exam"><span class="fa fa-pen-square"></span></a> ' ;
                  }
                }
              }
            }
            else
            {
              return '<a href="<?php echo base_url() ?>home/subjectStudents/'+row.ClassSubjectId+'" class="btn btn-default" title="View Subject"><span class="fa fa-eye"></span></a> ' ;
            }
          }
        }
      ],
      "order": [[0, "asc"]]
    });

    table3 = $('#example3').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/admin_controller/getFacultyClassList/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "ClassName" }
                    , { data: "ClassDescription" }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        return "<span class='badge bg-"+row.Color+"'>"+row.StatusDescription+"</span>";
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        if(row.StatusId == 1){
                          return '<a href="<?php echo base_url() ?>home/facultyClassDetails/'+row.Id+'" class="btn btn-default" title="View"><span class="fa fa-eye"></span></a>';
                        }
                        else
                        {
                          return '<a href="<?php echo base_url() ?>home/facultyClassDetails/'+row.Id+'" class="btn btn-default" title="View"><span class="fa fa-eye"></span></a>';
                        }
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[0, "asc"]]
    });

    example4 = $('#example4').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/admin_controller/getClassList/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "ClassName" }
                    , { data: "ClassDescription" }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        return "<span class='badge bg-"+row.Color+"'>"+row.StatusDescription+"</span>";
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        if(row.StatusId == 1){
                          return '<a href="<?php echo base_url() ?>home/classDetails/'+row.Id+'" class="btn btn-default" title="View"><span class="fa fa-eye"></span></a> <a onclick="updateRecord('+row.Id+', 4, \''+row.ClassName+'\', \''+row.MaxStudents+'\', \''+row.ClassDescription+'\')"  data-toggle="modal" data-target="#modalEdit" class="btn btn-primary" title="Edit"><span class="fa fa-edit"></span></a> <a onclick="updateRecord('+row.Id+', 1)" class="btn btn-danger" title="Deactivate"><span class="fa fa-window-close"></span></a>';
                        }
                        else
                        {
                          return '<a onclick="updateRecord('+row.Id+', 2)" class="btn btn-warning" title="Re-activate"><span class="fa fa-retweet"></span></a>';
                        }
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[0, "asc"]]
    });

    example5 = $('#example5').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/admin_controller/getUserList/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "EmployeeNumber" }
                    , { data: "Name" }
                    , { data: "Position" }
                    , { data: "Role" }
                    , {
                      data: "IsNew", "render": function (data, type, row) {
                        if(row.IsNew == 1)
                        {
                          return "No";
                        }
                        else
                        {
                          return "Yes";
                        }
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        return "<span class='badge bg-"+row.Color+"'>"+row.StatusDescription+"</span>";
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        return '';
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[0, "asc"]]
    });

  });

// Immediately send analytics data on page load
fetch('<?php echo base_url("admin_controller/sendAnalyticsData"); ?>', {
    credentials: 'same-origin'
})
.then(response => response.text())
.then(data => console.log('Analytics sent (on load):', data));

// Continue sending every 5 minutes as before
setInterval(function() {
    fetch('<?php echo base_url("admin_controller/sendAnalyticsData"); ?>', {
        credentials: 'same-origin'
    })
    .then(response => response.text())
    .then(data => console.log('Analytics sent:', data));
}, 300000); // every 5 minutes

</script>