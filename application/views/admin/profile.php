
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalChangePassword">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Change Password</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url(); ?>admin_controller/ResetPassword/1" id="frmInsert2" method="post">
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="colorCurrent">Current Password</label>
                      <div class="form-group" id="colorCurrent">
                        <label class="control-label" id="lblSuccess" style="display: none" for="inputSuccess"><i class="fa fa-check"></i></label>
                        <input type="password" class="form-control" name="NewPassword" id="txtCurrentPassword" oninput="checkCurrentPassword(this.value);" placeholder="Enter current password">
                        <span id="successMessage3" style="display: none" class="help-block"></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="exampleInputEmail1">New Password</label>
                      <div class="form-group" id="colorSuccess">
                        <label class="control-label" id="lblSuccess" style="display: none" for="inputSuccess"><i class="fa fa-check"></i></label>
                        <input type="password" class="form-control" name="NewPassword" id="txtNewPassword" oninput="checkNewPassword(this.value);" placeholder="Enter new password">
                        <span id="successMessage" style="display: none" class="help-block"></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Confirm Password</label>
                      <div class="form-group" id="colorSuccess2">
                        <label class="control-label" id="lblSuccess2" style="display: none" for="txtConfirmPassword"><i class="fa fa-check"></i></label>
                        <input type="password" class="form-control" id="txtConfirmPassword" placeholder="Confirm new password" oninput="checkPasswordMatch(this.value);">
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

    <!-- Main content -->
    <div class="content">
      <div class="row">
        <div class="col-md-3">
          <div class="card card-primary card-outline">
            <div class="card-body box-profile">
              <div class="text-center">
                <!-- <img class="profile-user-img img-fluid img-circle" src="<?php echo base_url(); ?>resources/dist/img/user4-128x128.jpg" alt="User profile picture"> -->
              </div>

              <h3 class="profile-username text-center"><?php echo $this->session->userdata('Name') ?></h3>

              <p class="text-muted text-center"><?php print_r($detail['Position']) ?> - <?php echo $this->session->userdata('EmployeeNumber') ?></p>

              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b>Branch Assigned</b> <a class="float-right"><?php print_r($detail['Branch']) ?></a>
                </li>
                <li class="list-group-item">
                  <b>Role</b> <a class="float-right"><?php print_r($detail['Role']) ?></a>
                </li>
              </ul>
              <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalChangePassword"><b>Change Password</b>
              <!-- <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalProfilePicture"><b>Change Profile Picture</b> -->
            </div>
          </div>
        </div>

        <div class="col-md-9">
          <div class="card">
            <div class="card-header p-2">
              <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li>
                <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content">
                <div class="active tab-pane" id="activity">
                  <div class="row">
                    <div class="col-sm-12">
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th>Description</th>
                          <th>Remarks</th>
                          <th>Date/Time</th>
                          <th>Date/Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="tab-pane" id="settings">
                  <form class="form-horizontal" action="<?php echo base_url(); ?>admin_controller/updateSecurityQuestions/" id="frmInsert" method="post">
                    <div class="form-group">
                      <label for="inputName" class="col-sm-2 control-label">Question No. 1</label>
                      <div class="col-sm-12">
                        <select type="text" class="form-control" name="Question1" placeholder="Name">
                          <?php 
                            foreach($questions as $row)
                            {
                              if($SecQuestion1['ID'] == $row['Id'])
                              {
                                $selected = 'selected';
                              }
                              else
                              {
                                $selected = '';
                              }
                              echo "<option ".$selected." value='".$row['Id']."'>".$row['Description']."</option>";
                            }
                          ?>
                        </select> 
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="inputExperience" class="col-sm-2 control-label">Answer</label>
                        <div class="input-group mb-3">
                          <input type="password" name="Answer1" id="txtAnswer1" class="form-control" required="" value="<?php print_r($SecQuestion1['Answer']) ?>" class="form-control">
                          <div class="input-group-append">
                            <span class="input-group-text"><a onclick="answerClick(1)"> <i id="iconUpdate1" class="fa fa-eye-slash"></i></a></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputName" class="col-sm-2 control-label">Question No. 2</label>
                      <div class="col-sm-12">
                        <select type="text" class="form-control" name="Question2" placeholder="Name">
                          <?php 
                            foreach($questions as $row)
                            {
                              if($SecQuestion2['ID'] == $row['Id'])
                              {
                                $selected = 'selected';
                              }
                              else
                              {
                                $selected = '';
                              }
                              echo "<option ".$selected." value='".$row['Id']."'>".$row['Description']."</option>";
                            }
                          ?>
                        </select> 
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="inputExperience" class="col-sm-2 control-label">Answer</label>
                        <div class="input-group mb-3">
                          <input type="password" name="Answer2" id="txtAnswer2" class="form-control" required="" value="<?php print_r($SecQuestion2['Answer']) ?>" class="form-control">
                          <div class="input-group-append">
                            <span class="input-group-text"><a onclick="answerClick(2)"> <i id="iconUpdate2" class="fa fa-eye-slash"></i></a></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="inputName" class="col-sm-2 control-label">Question No. 3</label>
                      <div class="col-sm-12">
                        <select type="text" class="form-control" name="Question3" placeholder="Name">
                          <?php 
                            foreach($questions as $row)
                            {
                              if($SecQuestion3['ID'] == $row['Id'])
                              {
                                $selected = 'selected';
                              }
                              else
                              {
                                $selected = '';
                              }
                              echo "<option ".$selected." value='".$row['Id']."'>".$row['Description']."</option>";
                            }
                          ?>
                        </select> 
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="inputExperience" class="col-sm-2 control-label">Answer</label>
                        <div class="input-group mb-3">
                          <input type="password" name="Answer3" id="txtAnswer3" class="form-control" required="" value="<?php print_r($SecQuestion3['Answer']) ?>" class="form-control">
                          <div class="input-group-append">
                            <span class="input-group-text"><a onclick="answerClick(3)"> <i id="iconUpdate3" class="fa fa-eye-slash"></i></a></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-offset-2 col-sm-12">
                        <button type="submit" class="btn btn-success">Set Security Question</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>
    <!-- /.content -->

  </div>

  <?php $this->load->view('includes/footer'); ?>

<script type="text/javascript">
  var varNewPassword = 0;
  var varStatus = 0;
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

  function checkCurrentPassword(currentValue)
  {
    var element = document.getElementById("colorCurrent");
    $.ajax({                
        url: "<?php echo base_url();?>" + "/admin_controller/getCurrentPassword",
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

    $("#frmInsert2").on('submit', function (e) {
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


    $("#frmInsert").on('submit', function (e) {
      e.preventDefault(); 
      swal({
        title: 'Confirm',
        text: 'Are you sure you want to confirm?',
        type: 'info',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-success',
        confirmButtonText: 'Confirm',
        cancelButtonClass: 'btn btn-secondary'
      }).then(function(){
        e.currentTarget.submit();
      });
    });


    $('.select2').select2();

    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/admin_controller/getUserLogs/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "Description" }
                    , { data: "Remarks" }
                    , { data: "DateCreated" }
                    , { data: "rawDateCreated" }
      ],
      "aoColumnDefs": [{ "bVisible": false, "aTargets": [3] }],
      "order": [[3, "DESC"]]
    });

  });
</script>