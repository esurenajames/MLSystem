<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ML System</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>resources/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>resources/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>resources/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>resources/bower_components/select2/dist/css/select2.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="#" class="h1"><b>ML</b>System</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>
    
    <?php if($this->session->flashdata('error'))
      {
        echo '
          <div class="alert alert-danger alert-dismissible">
                  '.$this->session->flashdata('error').'
                </div>
        ';
      } else if($this->session->flashdata('logout'))
      {
        echo '
          <div class="alert alert-success alert-dismissible">
                  '.$this->session->flashdata('logout').'
                </div>
        ';
      }
    ?>


    <div class="modal fade" id="modalResetPassword">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Forgot password</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url(); ?>access_controller/ResetPassword" autocomplete="off" method="post">
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <h6>Employee No.</h6>
                      <input type="text" required="" class="form-control" name="EmployeeNumber">
                  </div>
                  <div class="col-md-6">
                    <h6>1st Question</h6>
                    <select class="form-control select2" required="" name="Question1" style="width: 100%;">
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
                    <select class="form-control select2" required="" name="Question2" style="width: 100%;">
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
                    <select class="form-control select2" required="" name="Question3" style="width: 100%;">
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
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit Answer</button>
              </div>
            </form>
        </div>
      </div>
    </div>

      <form action="<?php echo base_url(); ?>access_controller/accessCheck/1" id="formLogin" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="Username" placeholder="Username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="Password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mb-1">
        <a href="#" data-toggle="modal" data-target="#modalResetPassword">I forgot my password</a>
      </p>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="<?php echo base_url(); ?>resources/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url(); ?>resources/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>resources/dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url(); ?>resources/bower_components/select2/dist/js/select2.full.min.js"></script>

<script>
  $(function () {
    $('.select2').select2();

    
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });


    $("#formLogin").on('submit', function (e) {
      
      if($('#selectEmployee').val() == '' || $('#selectRoles').val() == '' || $('#txtPassword').val() == '') 
      {
        e.preventDefault(); 
        swal({
          title: 'Warning',
          text: 'Please make sure all required fields are filled out!',
          type: 'warning',
          buttonsStyling: false,
          confirmButtonClass: 'btn btn-primary'
        });
      }
    });

    $("#formLogin").on('submit', function (e) {
      
      if($('#selectEmployee').val() == '' || $('#selectRoles').val() == '' || $('#txtPassword').val() == '') 
      {
        e.preventDefault(); 
        swal({
          title: 'Warning',
          text: 'Please make sure all required fields are filled out!',
          type: 'warning',
          buttonsStyling: false,
          confirmButtonClass: 'btn btn-primary'
        });
      }
    });

    <?php session_destroy(); ?>
  });
</script>
</body>
</html>
