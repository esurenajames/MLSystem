  
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
                      <th>Prediction</th>
                      <th>Prediction Analysis</th>
                      <th>Mock Exam Grade</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="font-weight: bold;">Predicted Result:</td>
                        <td style="font-weight: bold;" id="preboard-result" colspan="3"></td>                      </tr>
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
  var currentUserId = <?php echo json_encode($this->session->userdata('UserId')); ?>;
  var varStatus = 0;
  var varNewPassword = 0;

  function checkPasswordMatch(Password) {
    var element = document.getElementById("colorSuccess2");
    if ($('#txtNewPassword').val() != Password) {
      element.classList.remove("has-success");
      element.classList.add("has-error");
      $('#successMessage2').slideDown();
      $('#successMessage2').html('Password does not match');
      varStatus = 0;
    } else {
      element.classList.remove("has-error");
      element.classList.add("has-success");
      $('#successMessage2').slideDown();
      $('#successMessage2').html('Password matching');
      varStatus = 1;
    }
  }

  function checkNewPassword(Password) {
    var element = document.getElementById("colorSuccess2");
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W\_])[A-Za-z\d\W\_]{8,}$/;
    const str = $('#txtNewPassword').val();
    let m;
    if ($('#txtConfirmPassword').val() != Password) {
      element.classList.remove("has-success");
      element.classList.add("has-error");
      $('#successMessage2').slideDown();
      $('#successMessage2').html('Password does not match');
      varStatus = 0;
    } else {
      element.classList.remove("has-error");
      element.classList.add("has-success");
      $('#successMessage2').slideDown();
      $('#successMessage2').html('Password matching');
      varStatus = 1;
    }

    if ((m = regex.exec(str)) !== null) {
      m.forEach((match, groupIndex) => {
        var element = document.getElementById("colorSuccess");
        element.classList.remove("has-error");
        element.classList.add("has-success");
        $('#successMessage').slideDown();
        $('#successMessage').html('Valid Password');
        varNewPassword = 1;
      });
    } else {
      var element = document.getElementById("colorSuccess");
      element.classList.remove("has-success");
      element.classList.add("has-error");
      $('#successMessage').slideDown();
      $('#successMessage').html('Password must contain a special, numeric and an uppercase character');
      varNewPassword = 0;
    }
  }

  function refreshPage() {
    var url = '<?php echo base_url()."admin_controller/getEmployeeList/"; ?>';
    UserTable.ajax.url(url).load();
  }

  function refreshPage2() {
    var url = '<?php echo base_url()."admin_controller/getStudentSubjectList/"; ?>';
    Grades.ajax.url(url).load();
  }

  function clickRetakeExam(TakenExamId) {
    swal({
      title: 'Confirm',
      text: 'Are you sure you want to submit this request to re-take failed exam?',
      type: 'info',
      showCancelButton: true,
      buttonsStyling: false,
      confirmButtonClass: 'btn btn-success',
      confirmButtonText: 'Confirm',
      cancelButtonClass: 'btn btn-secondary'
    }).then(function () {
      $.ajax({
        url: "<?php echo base_url();?>" + "/admin_controller/requestRetakeExam",
        method: "POST",
        async: false,
        data: {
          TakenExamId: TakenExamId
        },
        dataType: "JSON",
        beforeSend: function () {
          $('.loading').show();
        },
        success: function (data) {
          swal({
            title: 'Success!',
            text: 'Record successfully updated!',
            type: 'success',
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary'
          });
          refreshPage2();
        },
        error: function (response) {
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
      if (varNewPassword = 1 && varStatus == 1 && $('#txtNewPassword').val() == $('#txtConfirmPassword').val() && '<?php echo $this->session->userdata('Password') ?>' != $('#txtNewPassword').val()) {
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
        }).then(function () {
          e.currentTarget.submit();
        });
      } else {
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

    if ($.fn.DataTable.isDataTable('#example1')) {
      $('#example1').DataTable().clear().destroy();
    }

    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/admin_controller/getStudentClassList/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [
        { data: "ClassName" },
        { data: "ClassDescription" },
        {
          data: "StatusId", "render": function (data, type, row) {
            return "<span class='badge bg-" + row.Color + "'>" + row.StatusDescription + "</span>";
          }
        },
        {
          data: "StatusId", "render": function (data, type, row) {
            return '<a href="<?php echo base_url() ?>home/viewClass/' + row.Id + '" class="btn btn-default" title="View"><span class="fa fa-eye"></span></a>';
          }
        }
      ],
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
          data: "ExamId",
          render: function (data, type, row) {
            if (row.totalQuestions != 0) {
              totalPercentage = (row.correctAnswer / row.totalQuestions) * 100;
              finalPrediction = (parseFloat(row.Grade) + parseFloat(totalPercentage)) / 2;
              return finalPrediction + '% rate';
            } else {
              totalPercentage = 0;
              finalPrediction = (parseFloat(row.Grade) + parseFloat(totalPercentage)) / 2;
              return finalPrediction + '% rate';
            }
          }
        },
        {
          data: null,
          title: "Prediction Analysis",
          render: function (data, type, row) {
          let totalPercentage = 0;
          let finalPrediction = 0;

          if (row.totalQuestions != 0) {
            totalPercentage = (row.correctAnswer / row.totalQuestions) * 100;
          }
          finalPrediction = (parseFloat(row.Grade) + parseFloat(totalPercentage)) / 2;

          let score = isNaN(finalPrediction) ? 0 : finalPrediction;

            if (score >= 85) return "Most Likely to Pass";
            if (score >= 75) return "Likely to Pass";
            if (score >= 65) return "Likely to Fail";
            return "Most Likely to Fail";
          }
        },
        {
          data: "ExamId",
          render: function (data, type, row) {
            if (row.UserId && row.UserId != currentUserId) return '';
            if (row.CreatedExamId !== null) {
              if (row.totalQuestions != 0 && row.StatusId == 1) {
                var totalPercentage = (row.correctAnswer / row.totalQuestions) * 100;
                var examResult = (totalPercentage >= 70)
                  ? '<label style="color:#08A133">Passed</label>'
                  : '<label style="color:#D92323">Failed</label>';
                return totalPercentage + '% - ' + examResult;
              } else {
                if (row.StatusId == 1) {
                  if (row.totalQuestions != 0) {
                    return 'No exam taken';
                  } else {
                    return 'For re-taking';
                  }
                } else {
                  return 'No exam taken';
                }
              }
            } else {
              return 'No exam has been created.';
            }
          }
        },
        {
          data: "ExamId",
          render: function (data, type, row) {
            if (row.CreatedExamId !== null) {
              if (row.StatusId == 10) {
                return '<a href="<?php echo base_url() ?>home/TakeExam/' + row.CreatedExamId + '" class="btn btn-primary" title="Take Exam"><span class="fa fa-pen-square"></span></a> ';
              } else {
                if (row.StatusId == 1 && row.totalQuestions == 0) {
                  return '<a href="<?php echo base_url() ?>home/TakeExam/' + row.CreatedExamId + '" class="btn btn-primary" title="Take Exam"><span class="fa fa-pen-square"></span></a> ';
                } else {
                  if (totalPercentage <= 70) {
                    retakeExam = '<a onclick="clickRetakeExam(' + row.CreatedExamId + ')" class="btn btn-primary" title="Request to retake exam"><span class="fa fa-money-check"></span></a>';
                  } else {
                    retakeExam = '';
                  }
                  if (row.ExamId !== null) {
                    return '<a href="<?php echo base_url() ?>home/viewExam/' + row.CreatedExamId + '" class="btn btn-default" title="View Exam"><span class="fa fa-eye"></span></a> ' + retakeExam;
                  } else {
                    return '<a href="<?php echo base_url() ?>home/TakeExam/' + row.CreatedExamId + '" class="btn btn-primary" title="Take Exam"><span class="fa fa-pen-square"></span></a> ';
                  }
                }
              }
            } else {
              return '<a href="<?php echo base_url() ?>home/subjectStudents/' + row.ClassSubjectId + '" class="btn btn-default" title="View Subject"><span class="fa fa-eye"></span></a> ';
            }
          }
        }
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[0, "asc"]]
    });

    Grades.on('xhr', function () {
      var data = Grades.ajax.json();
      if (!data) return;

      data = data.filter(function (row) {
        return row.UserId == currentUserId;
      });

      const subjects = {
        "psychological assessment": 0.40,
        "developmental psychology": 0.20,
        "abnormal psychology": 0.20,
        "industrial psychology": 0.20
      };

      let subjectScores = {
        "psychological assessment": [],
        "developmental psychology": [],
        "abnormal psychology": [],
        "industrial psychology": []
      };

      data.forEach(function (row) {
        let subj = row.Name ? row.Name.trim().toLowerCase() : "";
        if (subjects.hasOwnProperty(subj)) {
          let grade = !isNaN(parseFloat(row.Grade)) ? parseFloat(row.Grade) : 0;
          let mock = 0;
          if (row.totalQuestions != 0) {
            mock = !isNaN(row.correctAnswer) && !isNaN(row.totalQuestions) ? (row.correctAnswer / row.totalQuestions) * 100 : 0;
          }
          let prediction = (grade + mock) / 2;
          subjectScores[subj].push(prediction);
        }
      });

      let avgSubjectScores = {};
      Object.keys(subjectScores).forEach(function (subj) {
        if (subjectScores[subj].length > 0) {
          avgSubjectScores[subj] = subjectScores[subj].reduce((a, b) => a + b, 0) / subjectScores[subj].length;
        } else {
          avgSubjectScores[subj] = 0;
        }
      });

      let predictedScore = 0;
      let totalWeight = 0;
      Object.keys(subjects).forEach(function (subj) {
        predictedScore += avgSubjectScores[subj] * subjects[subj];
        totalWeight += subjects[subj];
      });

      let finalScore = totalWeight > 0 ? (predictedScore / totalWeight) : 0;

      let deliberation = "Most Likely to Fail";
      if (finalScore >= 85) deliberation = "Most Likely to Pass";
      else if (finalScore >= 75) deliberation = "Likely to Pass";
      else if (finalScore >= 65) deliberation = "Likely to Fail";

      document.getElementById('preboard-result').innerHTML =
        `PREDICTED PRE BOARD EXAMINATION RESULT: <span style="color:#007bff">${finalScore.toFixed(2)}%</span> <span style="color:#444;background:#eee;padding:2px 8px;border-radius:4px">${deliberation}</span>`;

      if (document.getElementById('prediction-analysis-grade')) {
        document.getElementById('prediction-analysis-grade').innerHTML = deliberation;
      }
    });

    table3 = $('#example3').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/admin_controller/getFacultyClassList/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [
        { data: "ClassName" },
        { data: "ClassDescription" },
        {
          data: "StatusId", "render": function (data, type, row) {
            return "<span class='badge bg-" + row.Color + "'>" + row.StatusDescription + "</span>";
          }
        },
        {
          data: "StatusId", "render": function (data, type, row) {
            return '<a href="<?php echo base_url() ?>home/facultyClassDetails/' + row.Id + '" class="btn btn-default" title="View"><span class="fa fa-eye"></span></a>';
          }
        }
      ],
      "order": [[0, "asc"]]
    });

    example4 = $('#example4').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/admin_controller/getClassList/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [
        { data: "ClassName" },
        { data: "ClassDescription" },
        {
          data: "StatusId", "render": function (data, type, row) {
            return "<span class='badge bg-" + row.Color + "'>" + row.StatusDescription + "</span>";
          }
        },
        {
          data: "StatusId", "render": function (data, type, row) {
            if (row.StatusId == 1) {
              return '<a href="<?php echo base_url() ?>home/classDetails/' + row.Id + '" class="btn btn-default" title="View"><span class="fa fa-eye"></span></a> <a onclick="updateRecord(' + row.Id + ', 4, \'' + row.ClassName + '\', \'' + row.MaxStudents + '\', \'' + row.ClassDescription + '\')"  data-toggle="modal" data-target="#modalEdit" class="btn btn-primary" title="Edit"><span class="fa fa-edit"></span></a> <a onclick="updateRecord(' + row.Id + ', 1)" class="btn btn-danger" title="Deactivate"><span class="fa fa-window-close"></span></a>';
            } else {
              return '<a onclick="updateRecord(' + row.Id + ', 2)" class="btn btn-warning" title="Re-activate"><span class="fa fa-retweet"></span></a>';
            }
          }
        }
      ],
      "order": [[0, "asc"]]
    });

    example5 = $('#example5').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/admin_controller/getUserList/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [
        { data: "EmployeeNumber" },
        { data: "Name" },
        { data: "Position" },
        { data: "Role" },
        {
          data: "IsNew", "render": function (data, type, row) {
            return row.IsNew == 1 ? "No" : "Yes";
          }
        },
        {
          data: "StatusId", "render": function (data, type, row) {
            return "<span class='badge bg-" + row.Color + "'>" + row.StatusDescription + "</span>";
          }
        },
        {
          data: "StatusId", "render": function (data, type, row) {
            return '';
          }
        }
      ],
      "order": [[0, "asc"]]
    });

  });

  setInterval(function () {
    fetch('/admin_controller/sendAnalyticsData')
      .then(response => response.text())
      .then(data => console.log('Analytics sent:', data));
  }, 300000); // every 5 minutes (300,000 ms)
</script>