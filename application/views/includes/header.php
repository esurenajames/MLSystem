<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>LMS | <?php print_r($header) ?></title>
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
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>resources/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- daterange picker -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>resources/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>resources/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>resources/bower_components/jvectormap/jquery-jvectormap.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>resources/dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>resources/bower_components/sweetalert2/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>resources/plugins/iCheck/all.css">

  <link href="<?php echo base_url(); ?>resources/plugins/jquery-smartwizard-master/dist/css/smart_wizard_all.min.css" rel="stylesheet" type="text/css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>resources/bower_components/select2/dist/css/select2.min.css">

  <link href="<?php echo base_url(); ?>resources/bower_components/wizard/smart_wizard_theme_arrows.css" rel="stylesheet" type="text/css" /> 
  <link href="<?php echo base_url(); ?>resources/bower_components/wizard/smart_wizard_theme_dots.css" rel="stylesheet" type="text/css" /> 
  <link href="<?php echo base_url(); ?>resources/bower_components/wizard/smart_wizard_theme_circles.css" rel="stylesheet" type="text/css" /> 
  <link href="<?php echo base_url(); ?>resources/bower_components/wizard/smart_wizard.css" rel="stylesheet" type="text/css" />

  <!-- <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/drilldown.js"></script> -->

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?php echo base_url(); ?>resources/index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>L</b>MS</span>
      <!-- logo for regular state and mobile devices -->
      <!-- <span class="logo-lg"><b>LendingMS</b></span> -->
      <span class="logo-lg"><img src="<?php echo base_url(); ?>/resources/ELENDiNG.png" style="width: 100%; height: 100%" ></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="hidden-xs"> <?php print_r($this->session->userdata('Name')) ?> </span>
              </a>
              <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                  <?php
                    if(!isset($profilePicture[0]['FileName']))
                    {
                      echo '<img src="'.base_url().'borrowerpicture/default.gif" class="profile-user-img img-responsive img-circle" alt="User Image" style="width: 100px">';
                    }
                    else
                    {
                      echo '<img src="'.base_url().'profilepicture/'.$profilePicture[0]["FileName"].'" class="profile-user-img img-responsive img-circle" alt="User Image" style="width: 100px">';
                    }
                  ?>
                  
                  <p>
                    <?php print_r($this->session->userdata('Name')) ?> <br> 
                  </p>
                </li>
                <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="<?php echo base_url(); ?>home/userProfile/<?php print_r($this->session->userdata('EmployeeNumber')) ?>" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <form action="<?php echo base_url(); ?>LMS/accessCheck" method="post">
                        <button type="submit" name="btnProcess" value="3" class="btn btn-default btn-flat">Sign out</button>
                      </form>
                    </div>
                  </li>
              </ul>
            </li>
          </ul>
      </div>
    </nav>
  </header>

  <style type="text/css">
    
  .select2-container--default .select2-selection--multiple .select2-selection__choice {
    color: #000;
  }

  </style>