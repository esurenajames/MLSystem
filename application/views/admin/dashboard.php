
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Dashboard
    </h1>
    <ol class="breadcrumb">
      <li><a href="#" class="active"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    </ol>
  </section>

  <?php 
    $record = $this->maintenance_model->passwordValidity();
    $password = $this->maintenance_model->getPassword();
    if($record['IsNew'] == 1) { ?>
      <div class="modal fade" data-backdrop="static" id="modalNewPassword">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">CHANGE TEMPORARY PASSWORD</h4>
            </div>
            <form autocomplete="off" action="<?php echo base_url(); ?>admin_controller/addUser/1" id="frmInsert" method="post">
              <div class="modal-body">
                <div class="row">
                  <input type="hidden" value="<?php print_r($password['Password']) ?>" id="txtOldPassword" class="form-control" id="exampleInputEmail1">
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">New Password<span class="text-red">*</span></label>
                      <div class="form-group" id="colorSuccess">
                        <label class="control-label" id="lblSuccess" style="display: none" for="inputSuccess"><i class="fa fa-check"></i></label>
                        <input type="password" class="form-control" name="NewPassword" id="txtNewPassword" oninput="checkNewPassword(this.value);" placeholder="Enter New password">
                        <span id="successMessage" style="display: none" class="help-block"></span>
                      </div>
                      <!-- <input type="password" id="txtNewPassword" class="form-control" id="exampleInputEmail1"> -->
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Confirm Password<span class="text-red">*</span></label>
                      <div class="form-group" id="colorSuccess2">
                        <label class="control-label" id="lblSuccess2" style="display: none" for="txtConfirmPassword"><i class="fa fa-check"></i></label>
                        <input type="password" class="form-control" id="txtConfirmPassword" oninput="checkPasswordMatch(this.value);">
                        <span id="successMessage2" style="display: none" class="help-block"></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputName" class="control-label">Question No. 1</label><br>
                      <select type="text" class="form-control" id="inputName" name="Question1" placeholder="Name">
                        <?php 
                          foreach($securityQuestions as $row)
                          {
                            echo "<option value='".$row['SecurityQuestionId']."'>".$row['Name']."</option>";
                          }
                        ?>
                      </select> 
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputExperience" class="control-label">Answer</label><br>
                      <input class="form-control" required="" name="Answer1" placeholder="Answer for question number 1">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputName" class="control-label">Question No. 2</label><br>
                      <select type="text" class="form-control" name="Question2">
                        <?php 
                          foreach($securityQuestions as $row)
                          {
                            echo "<option value='".$row['SecurityQuestionId']."'>".$row['Name']."</option>";
                          }
                        ?>
                      </select> 
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputExperience" class="control-label">Answer</label><br>
                      <input class="form-control" required="" name="Answer2" placeholder="Answer for question number 2">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputName" class="control-label">Question No. 3</label><br>
                      <select type="text" class="form-control" name="Question3">
                        <?php 
                          foreach($securityQuestions as $row)
                          {
                            echo "<option value='".$row['SecurityQuestionId']."'>".$row['Name']."</option>";
                          }
                        ?>
                      </select> 
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputExperience" class="control-label">Answer</label><br>
                      <input class="form-control" required="" placeholder="Answer for question number 3" name="Answer3">
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
    <?php } else { ?> 
    <?php }  ?> 

    <!-- Main content -->
    <section class="content">
      <?php foreach ($access as $rows){ if($rows['RoleId'] == 3 || $rows['RoleId'] == 4) /*employee | top management*/ { ?>    
        <div class="row">
          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people-outline"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total <br> Borrowers</span>
                <span class="info-box-number">10</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-info"><i class="fa fa-folder-open"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Loans Released</span>
                <span class="info-box-number">10</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix visible-sm-block"></div>

          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-green"><i class="ion ion-ios-calculator"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Payments <br> Collected</span>
                <span class="info-box-number">Php 23,000</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-yellow"><i class="ion ion-ios-briefcase-outline"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Due Amount</span>
                <span class="info-box-number">Php 2,000</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>     
      <?php } if($rows['RoleId'] == 1) /*admin*/ { ?>
        <!-- Default box -->
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">List of Users</h3>
          </div>
          <div class="box-body">
            <form name="ApproverDocForm" method="post" id="ApproverDocForm">
              <table id="example1" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Employee Number</th>
                  <th>Name</th>
                  <th>Role</th>
                  <th>Renewed Password?</th>
                  <th>Status</th>
                  <th>Date Created</th>
                  <th>Date Updated</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </form>
          </div>
        </div>
        <!-- /.box -->
      <?php } } ?> 
    </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0
  </div>
  <strong>Copyright &copy; 2020 <a href="https://adminlte.io">GIA Tech.</a>.</strong> All rights
  reserved.
</footer>

<div class="loading" style="display: none">Loading&#8230;</div>
<?php $this->load->view('includes/footer'); ?>

<script>
  var varStatus = 0;
  var varNewPassword = 0;

  if("<?php print_r($this->session->flashdata('alertTitle')) ?>" != '')
  {
    swal({
      title: '<?php print_r($this->session->flashdata('alertTitle')) ?>',
      text: '<?php print_r($this->session->flashdata('alertText')) ?>',
      type: '<?php print_r($this->session->flashdata('alertType')) ?>',
      buttonsStyling: false,
      confirmButtonClass: 'btn btn-primary'
    });
  }
  
  function confirm(Text, UserRoleId, updateType)
  { 
    swal({
      title: 'Confirm',
      text: Text,
      type: 'info',
      showCancelButton: true,
      buttonsStyling: false,
      confirmButtonClass: 'btn btn-success',
      confirmButtonText: 'Confirm',
      cancelButtonClass: 'btn btn-secondary'
    }).then(function(){
      $.ajax({                
          url: "<?php echo base_url();?>" + "/employee_controller/updateStatus",
          method: "POST",
          data:   {
                    UserRoleId : UserRoleId
                    , updateType : updateType
                  },
          beforeSend: function(){
              $('.loading').show();
          },
          success: function(data)
          {
            refreshPage();
            swal({
              title: 'Success!',
              text: 'User role successfully updated!',
              type: 'success',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });
          },
          error: function (response) 
          {
            refreshPage();
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

  function refreshPage(){
    var url = '<?php echo base_url()."datatables_controller/Users/"; ?>';
    UserTable.ajax.url(url).load();
  }

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

  $(function () {
    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/datatables_controller/Users/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "EmployeeNumber" }
                    , { data: "Name" }
                    , { data: "Description" }
                    , {
                      data: "IsNew", "render": function (data, type, row) {
                        if(row.IsNew == 1){
                          return "<span class='badge bg-warning'>No</span>";
                        }
                        else if(row.IsNew == 0){
                          return "<span class='badge bg-green'>Yes</span>";
                        }
                        else{
                          return "N/A";
                        }
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        if(row.StatusId == 1){
                          return "<span class='badge bg-green'>Active</span>";
                        }
                        else if(row.StatusId == 2){
                          return "<span class='badge bg-red'>Deactivated</span>";
                        }
                        else{
                          return "N/A";
                        }
                      }
                    },
                    { data: "DateCreated" }, 
                    { data: "DateUpdated" }, 
                    {
                      data: "StatusId", "render": function (data, type, row) {
                      if(row.StatusId == 1){
                          return '<a onclick="confirm(\'Are you sure you want to deactivate this user?\', \''+row.UserRoleId+'\', 1)" class="btn btn-danger" title="Deactivate"><span class="fa fa-close"></span></a> <a onclick="confirm(\'Are you sure you want to reset this user password?\', \''+row.UserRoleId+'\', 3)" class="btn btn-warning" title="Reset Password"><span class="fa fa-refresh"></span></a>';
                        }
                        else if(row.StatusId == 2){
                          return '<a onclick="confirm(\'Are you sure you want to re-activate this user?\', \''+row.UserRoleId+'\', 2)" class="btn btn-success" title="Deactivate"><span class="fa fa-refresh"></span></a>';
                        }
                        else{
                          return "N/A";
                        }
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[3, "asc"]]
    });

    $("#frmInsert").on('submit', function (e) {
      if(varNewPassword = 1 && varStatus == 1 && $('#txtNewPassword').val() == $('#txtConfirmPassword').val() && $('#txtOldPassword').val() != $('#txtNewPassword').val())
      {
        e.preventDefault(); 
        swal({
          title: 'Confirm',
          text: 'Are you sure you sure with this password?',
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
        alert('please make sure your new password is not equal to your old password!')
        e.preventDefault();
      }
    });




    $('#modalNewPassword').modal('show')

  })
</script>