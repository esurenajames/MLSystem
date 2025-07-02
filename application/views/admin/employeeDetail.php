
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="content">
      <div class="row">
        <div class="col-md-3">
          <div class="card card-primary card-outline">
            <div class="card-body box-profile">
              <div class="text-center">
                <img class="profile-user-img img-fluid img-circle"
                     src="<?php echo base_url(); ?>resources/dist/img/user4-128x128.jpg"
                     alt="User profile picture">
              </div>

              <h3 class="profile-username text-center"><?php print_r($detail['Name']) ?></h3>

              <p class="text-muted text-center"><?php print_r($detail['Position']) ?></p>

              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                  <b>Branch Assigned</b> <a class="float-right"><?php print_r($detail['Branch']) ?></a>
                </li>
                <li class="list-group-item">
                  <b>Role</b> <a class="float-right"><?php print_r($detail['Role']) ?></a>
                </li>
                <li class="list-group-item">
                  <b>Status</b> <a class="float-right"><?php print_r($detail['StatusDescription']) ?></a>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="col-md-9">
          <div class="card">
            <div class="card-header p-2">
              <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li>
                <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Update Information</a></li>
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
                  <form class="form-horizontal" action="<?php echo base_url(); ?>admin_controller/editEmployee/<?php echo $this->uri->segment(3)?>" id="frmInsert" method="post">
                    <div class="row">
                      <div class="col-md-6">
                        <h6>First Name</h6>
                        <input type="text" required="" name="FirstName" value="<?php print_r($detail['FirstName']) ?>" class="form-control">
                      </div>
                      <div class="col-md-6">
                        <h6>Middle Name</h6>
                        <input type="text" name="MiddleName" value="<?php print_r($detail['MiddleName']) ?>" class="form-control">
                      </div>
                      <div class="col-md-6">
                        <h6>Last Name</h6>
                        <input type="text" required="" name="LastName" value="<?php print_r($detail['LastName']) ?>" class="form-control">
                      </div>
                      <div class="col-md-6">
                        <h6>Ext. Name</h6>
                        <input type="text" name="ExtName" value="<?php print_r($detail['ExtName']) ?>" class="form-control">
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <h6>Position</h6>
                        <select class="form-control select2" name="PositionId" style="width: 100%;">
                          <?php 
                            $selectedPosition = '';
                            foreach ($position as $key => $value) 
                            {
                              if($value['Id'] == $detail['PositionId'])
                              {
                                $selectedPosition = 'selected';
                              }
                              else
                              {
                                $selectedPosition = '';
                              }
                              echo '<option '.$selectedPosition.' value="'.$value['Id'].'">'.$value['Description'].'</option>';
                            }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <h6>Branch Assigned</h6>
                        <select class="form-control select2" name="BranchId" style="width: 100%;">
                          <?php 
                            $selectedBranch = '';
                            foreach ($branch as $key => $value) 
                            {
                              if($value['Id'] == $detail['PositionId'])
                              {
                                $selectedBranch = 'selected';
                              }
                              else
                              {
                                $selectedBranch = '';
                              }
                              echo '<option '.$selectedBranch.' value="'.$value['Id'].'">'.$value['Description'].'</option>';
                            }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <h6>Status</h6>
                        <select class="form-control select2" name="StatusId" style="width: 100%;">
                          <?php 
                            $selectedStatus = '';
                            foreach ($status as $key => $value) 
                            {
                              if($value['Id'] == $detail['StatusId'])
                              {
                                $selectedStatus = 'selected';
                              }
                              else
                              {
                                $selectedStatus = '';
                              }
                              echo '<option '.$selectedStatus.' value="'.$value['Id'].'">'.$value['Description'].'</option>';
                            }
                          ?>
                        </select>
                      </div>
                    </div>
                    <br>
                    <div class="form-group float-right">
                      <div class="col-sm-offset-2 col-sm-12">
                        <button type="submit" class="btn btn-success">Save Changes</button>
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
      "ajax": { url: '<?php echo base_url()."/admin_controller/getViewLogs/".$this->uri->segment(3).""; ?>', type: 'POST', "dataSrc": "" },
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