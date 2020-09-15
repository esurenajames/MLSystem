<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>LMS | Login</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>resources/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>resources/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>resources/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>resources/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>resources/plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
      <div class="col-md-12">
        <img src="<?php echo base_url(); ?>/resources/ELENDiNG.png" style="width: 100%; height: 100px" >
      </div>
      <br>
      <br>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
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
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Reset Password</h4>
          </div>
            <form action="<?php echo base_url(); ?>LMS/ResetPassword" autocomplete="off" method="post">
              <div class="modal-body">
                <div class="row">
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Employee No.</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="EmployeeNumber">
                    </div>
                  </div>
                  <br>
                  <br>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Question No. 1</label>
                    <div class="col-sm-10">
                      <select type="text" class="form-control" name="Question1" placeholder="Name">
                        <?php 
                          foreach($securityQuestions as $row)
                          {
                            echo "<option value='".$row['SecurityQuestionId']."'>".$row['Name']."</option>";
                          }
                        ?>
                      </select> 
                    </div>
                  </div>
                  <br>
                  <br>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Answer</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="Answer1" placeholder="Name">
                    </div>
                  </div><br>
                  <br>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Question No. 2</label>
                    <div class="col-sm-10">
                      <select type="text" class="form-control" name="Question2" placeholder="Name">
                        <?php 
                          foreach($securityQuestions as $row)
                          {
                            echo "<option value='".$row['SecurityQuestionId']."'>".$row['Name']."</option>";
                          }
                        ?>
                      </select> 
                    </div>
                  </div>
                  <br>
                  <br>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Answer</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="Answer2" placeholder="Name">
                    </div>
                  </div><br>
                  <br>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Question No. 3</label>
                    <div class="col-sm-10">
                      <select type="text" class="form-control" name="Question3" placeholder="Name">
                        <?php 
                          foreach($securityQuestions as $row)
                          {
                            echo "<option value='".$row['SecurityQuestionId']."'>".$row['Name']."</option>";
                          }
                        ?>
                      </select> 
                    </div>
                  </div>
                  <br>
                  <br>
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Answer</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="Answer3" placeholder="Name">
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit Answer</button>
              </div>
            </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>


    <form action="<?php echo base_url(); ?>LMS/accessCheck" id="formLogin" method="post">
      <div class="form-group has-feedback">
        <input type="text" name="txtUsername" class="form-control" placeholder="Employee Number">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="txtPassword" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-12">
          <button type="submit" name="btnProcess" value="1" class="btn btn-success btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <br>

      <button name="btnProcess" value="2" class="btn btn-primary btn-block btn-flat" data-toggle="modal" data-target="#modalResetPassword">Reset Password</button>


  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?php echo base_url(); ?>resources/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url(); ?>resources/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url(); ?>resources/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
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


  });
</script>
</body>
</html>
