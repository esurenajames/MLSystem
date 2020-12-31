
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <?php if($this->uri->segment(3) == $this->session->userdata("EmployeeNumber")) { ?>

  <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        MY PROFILE
      </h1>
      <ol class="breadcrumb">
        <li><a href="#" class="active"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#">Profile</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

    <div class="modal fade" id="modalChangePassword">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">CHANGE PASSWORD</h4>
          </div>
            <form action="<?php echo base_url(); ?>admin_controller/ResetPassword/1" id="frmInsert" method="post">
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="colorCurrent">Current Password</label>
                      <div class="form-group" id="colorCurrent">
                        <label class="control-label" id="lblSuccess" style="display: none" for="inputSuccess"><i class="fa fa-check"></i></label>
                        <input type="password" class="form-control" name="NewPassword" id="txtCurrentPassword" oninput="checkCurrentPassword(this.value);" placeholder="Enter Current password">
                        <span id="successMessage3" style="display: none" class="help-block"></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="exampleInputEmail1">New Password</label>
                      <div class="form-group" id="colorSuccess">
                        <label class="control-label" id="lblSuccess" style="display: none" for="inputSuccess"><i class="fa fa-check"></i></label>
                        <input type="password" class="form-control" name="NewPassword" id="txtNewPassword" oninput="checkNewPassword(this.value);" placeholder="Enter New password">
                        <span id="successMessage" style="display: none" class="help-block"></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Confirm Password</label>
                      <div class="form-group" id="colorSuccess2">
                        <label class="control-label" id="lblSuccess2" style="display: none" for="txtConfirmPassword"><i class="fa fa-check"></i></label>
                        <input type="number" class="form-control" id="txtConfirmPassword" oninput="checkPasswordMatch(this.value);">
                        <span id="successMessage2" style="display: none" class="help-block"></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modalProfilePicture">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Edit User Profile Picture</h4>
          </div>
          <form autocomplete="off" action="<?php echo base_url(); ?>employee_controller/employeeProcessing/8/<?php print_r($detail['EmployeeNumber'])?>" id="frmInsert5" method="post" enctype="multipart/form-data">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="txtHouseNo">Upload Profile Picture<span class="text-red">*</span></label>
                    <input type="file" name="ID[]" required="" id="Attachment" accept=".jpeg, .jpg, .png">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <?php
                if($detail['FileName'] == null)
                {
                  echo '<img src="'.base_url().'/borrowerpicture/default.gif" class="profile-user-img img-responsive img-circle" alt="User Image" style="width: 100px">';
                }
                else
                {
                  echo '<img class="profile-user-img img-responsive img-circle" src="'.base_url().'/profilepicture/'. $detail["FileName"].'" class="user-image" alt="User Image">';
                }
              ?>

              <h3 class="profile-username text-center"><?php print_r($detail['Name']); ?></h3>

              <p class="text-muted text-center"><?php print_r($detail['Position']); ?></p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Employee Number</b> <h5 class="pull-right"><?php print_r($detail['EmployeeNumber']); ?></h5>
                </li>
                <li class="list-group-item">
                  <b>Birthdate</b> <h5 class="pull-right"><?php print_r($detail['DateOfBirth']); ?></h5>
                </li>
                <li class="list-group-item">
                  <b>Date Hired</b> <h5 class="pull-right"><?php print_r($detail['DateHired']); ?></h5>
                </li>
                <li class="list-group-item">
                  <b>Branch Assigned</b> <h5 class="pull-right"><?php print_r($detail['Branch']); ?></h5>
                </li>
                <li class="list-group-item">
                  <b>Manager</b> <h5 class="pull-right"><?php print_r($detail['MngLastName'] . ', ' . $detail['MngFirstName']); ?></h5>
                </li>
              </ul>

              <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalChangePassword"><b>Change Password</b>
              <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalProfilePicture"><b>Change Profile Picture</b>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#auditLogs" data-toggle="tab">Audit Logs</a></li>
              <li><a href="#securitySettings" data-toggle="tab">Security Settings</a></li>
              <?php if($this->session->userdata('IsManager') == 1) {?>
                <li><a href="#Notifications" data-toggle="tab">Employee Notifications</a></li>
              <?php } else {?>
              <?php }?>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="auditLogs">

                <?php $this->load->view('profile/profileAudit'); ?>
              </div>
              <div class="tab-pane" id="securitySettings">
                <form class="form-horizontal" action="<?php echo base_url(); ?>employee_controller/SecurityQuestion/" id="frmInsert" method="post">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Question No. 1</label>
                    <div class="col-sm-10">
                      <select type="text" class="form-control" id="inputName" name="Question1" placeholder="Name">
                        <?php 
                          foreach($securityQuestions as $row)
                          {
                            if($SecQuestion1['SecurityQuestionId'] == $row['SecurityQuestionId'])
                            {
                              $selected = 'selected';
                            }
                            else
                            {
                              $selected = '';
                            }
                            echo "<option ".$selected." value='".$row['SecurityQuestionId']."'>".$row['Name']."</option>";
                          }
                        ?>
                      </select> 
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Answer</label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <input type="password" name="Answer1" id="txtAnswer1" class="form-control" required="" value="<?php print_r($SecQuestion1['Answer']) ?>">
                        <span class="input-group-addon"><a onclick="answerClick(1)"> <i id="iconUpdate1" class="fa fa-eye-slash"></i></a></span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Question No. 2</label>
                    <div class="col-sm-10">
                      <select type="text" class="form-control" id="inputName" name="Question2" placeholder="Name">
                        <?php 
                          foreach($securityQuestions as $row)
                          {
                            if($SecQuestion2['SecurityQuestionId'] == $row['SecurityQuestionId'])
                            {
                              $selected = 'selected';
                            }
                            else
                            {
                              $selected = '';
                            }
                            echo "<option ".$selected." value='".$row['SecurityQuestionId']."'>".$row['Name']."</option>";
                          }
                        ?>
                      </select> 
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Answer</label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <input type="password" name="Answer2" id="txtAnswer2" class="form-control" required="" value="<?php print_r($SecQuestion2['Answer']) ?>">
                        <span class="input-group-addon"><a onclick="answerClick(2)"> <i id="iconUpdate2" class="fa fa-eye-slash"></i></a></span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Question No. 3</label>
                    <div class="col-sm-10">
                      <select type="text" class="form-control" id="inputName" name="Question3" placeholder="Name">
                        <?php 
                          foreach($securityQuestions as $row)
                          {
                            if($SecQuestion3['SecurityQuestionId'] == $row['SecurityQuestionId'])
                            {
                              $selected = 'selected';
                            }
                            else
                            {
                              $selected = '';
                            }
                            echo "<option ".$selected." value='".$row['SecurityQuestionId']."'>".$row['Name']."</option>";
                          }
                        ?>
                      </select> 
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Answer</label>
                    <div class="col-sm-10">
                      <div class="input-group">
                        <input type="password" name="Answer3" id="txtAnswer3" class="form-control" required="" value="<?php print_r($SecQuestion3['Answer']) ?>">
                        <span class="input-group-addon"><a onclick="answerClick(3)"> <i id="iconUpdate3" class="fa fa-eye-slash"></i></a></span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-success">Set Security Question</button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="tab-pane" id="Notifications">
                <table id="example2" width="100%" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>Date Created</th>
                    <th>Created By</th>
                    <th>Created By</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                      $row = 0;
                      foreach ($Audit2 as $value) 
                      {
                        $row = $row + 1;
                        echo "<tr>";
                        echo "<td>".$row."</td>";
                        echo "<td>".$value['Description']."</td>";
                        echo "<td>".$value['CreatedBy']."</td>";
                        echo "<td>".$value['DateCreated']."</td>";
                        echo "<td>".$value['rawDateCreated']."</td>";
                        echo "</tr>";
                      }
                    ?>
                  </tbody>
                </table>
              </div>

            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>

      <!-- /.box -->
    </section>
  <?php } else { ?>
    <br>
    <br>
    <div class="col-md-12">
      <div class="callout callout-danger">
        <h4>You have no access to this module!</h4>
        <p>Please contact your admin to request for access!</p>
      </div>
    </div>

  <?php } ?>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0
  </div>
  <strong>Copyright &copy; 2020 <a href="#">GIA Tech.</a>.</strong> All rights
  reserved.
</footer>

<div class="loading" style="display: none">Loading&#8230;</div>
<?php $this->load->view('includes/footer'); ?>

<script>
  function answerClick(formNumber)
  {
    if($('#txtAnswer'+formNumber+'').attr("type") == "text"){
      $('#txtAnswer'+formNumber+'').attr('type', 'password');
      $('#iconUpdate'+formNumber+'').addClass( "fa-eye-slash" );
      $('#iconUpdate'+formNumber+'').removeClass( "fa-eye" );
    }
    else if($('#txtAnswer'+formNumber+'').attr("type") == "password"){
      $('#txtAnswer'+formNumber+'').attr('type', 'text');
      $('#iconUpdate'+formNumber+'').removeClass( "fa-eye-slash" );
      $('#iconUpdate'+formNumber+'').addClass( "fa-eye" );
    }

  }

  function ChangePassword()
  {
    $('#modalChangePassword').modal('show');
  }

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

  function refreshPage(){
    var url = '<?php echo base_url()."datatables_controller/Users/"; ?>';
    UserTable.ajax.url(url).load();
  }

  function checkCurrentPassword(currentValue)
  {
    var element = document.getElementById("colorCurrent");
    $.ajax({                
        url: "<?php echo base_url();?>" + "/employee_controller/getCurrentPassword",
        method: "POST",
        async: false,
        data:   {
                  Password : currentValue
                  , EmployeeNumber : '<?php print_r($this->session->userdata('EmployeeNumber')) ?>'
                },  
        dataType: "JSON",
        beforeSend: function(){
            $('.loading').show();
        },
        success: function(data)
        {
          if(data == 0)
          {
            element.classList.remove("has-success");
            element.classList.add("has-error");
            $('#successMessage3').slideDown();
            // $('#successMessage3').html('Password does not match');

            $('#txtNewPassword').prop('disabled', true);
            $('#txtConfirmPassword').prop('disabled', true);
          }
          else
          {
            element.classList.remove("has-error");
            element.classList.add("has-success");
            $('#successMessage3').slideDown();
            // $('#successMessage3').html('Password matches current');

            $('#txtNewPassword').prop('disabled', false);
            $('#txtConfirmPassword').prop('disabled', false);
          }
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
    
    $('#selectRoles').select2({
      placeholder: 'Type a role to select',
      dropdownCssClass : 'bigdrop',
        ajax: {
          url: '<?php echo base_url()?>admin_controller/getRoles?>',
          dataType: 'json',
          delay: 250,
          processResults: function (data) 
          {
            return {
              results: data
            };
          },
          cache: true
        }
    });

    $('#selectEmployee').select2({
      placeholder: 'Type an employee name or employee number to select.',
      dropdownCssClass : 'bigdrop',
        ajax: {
          url: '<?php echo base_url()?>admin_controller/getEmployees?>',
          dataType: 'json',
          delay: 250,
          processResults: function (data) 
          {
            return {
              results: data
            };
          },
          cache: true
        }
    });

    $('#txtNewPassword').prop('disabled', true);
    $('#txtConfirmPassword').prop('disabled', true);

    var rowNo = 0;
    $('#dtblAudit').DataTable({
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [4] }],
      "order": [[0, "desc"]]
    });
    $('#example2').DataTable({
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [4] }],
      "order": [[0, "desc"]]
    });
  })
</script>