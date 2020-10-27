
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Database Management
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url(); ?>home/Dashboard" class="active"><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li><a href="#">System Setup</a></li>
      <li><a href="#">Database Management</a></li>
    </ol>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Truncate Database</h3>
      </div>
      <div class="box-body">
        <form autocomplete="off" action="<?php echo base_url(); ?>admin_controller/truncateBranchDB/" id="frmInsert" method="post">
              <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <label>Username</label>
              <input type="" placeholder="Username" name="Username" id="txtUsername" class="form-control">
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="exampleInputEmail1">Password<span class="text-red">*</span></label>
                <div class="form-group" id="colorSuccess">
                  <label class="control-label" id="lblSuccess" style="display: none" for="inputSuccess"><i class="fa fa-check"></i></label>
                  <input type="password" class="form-control" name="NewPassword" id="txtNewPassword" placeholder="Enter password">
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
                  <input type="password" class="form-control" placeholder="Confirm password" id="txtConfirmPassword" oninput="checkPasswordMatch(this.value);">
                  <span id="successMessage2" style="display: none" class="help-block"></span>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="pull-right">
            <button class="btn btn-sm btn-danger">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>

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
  
  function confirm(Text, BranchId, updateType)
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
          url: "<?php echo base_url();?>" + "/admin_controller/truncateBranchDB",
          method: "POST",
          data:   {
                    Id : BranchId
                    , updateType : updateType
                    , tableType : 'Branch'
                  },
          beforeSend: function(){
              $('.loading').show();
          },
          success: function(data)
          {
            refreshPage();
            swal({
              title: 'Success!',
              text: 'Branch successfully updated!',
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

  $("#frmInsert").on('submit', function (e) {
    if(varStatus == 1 && $('#txtNewPassword').val() == $('#txtConfirmPassword').val() && $('#txtOldPassword').val() != $('#txtNewPassword').val() && '<?php print_r($this->session->userdata('Password')); ?>' == $('#txtNewPassword').val() && '<?php print_r($this->session->userdata('Password')); ?>' == $('#txtConfirmPassword').val() && '<?php print_r($this->session->userdata('EmployeeNumber')); ?>' == $('#txtUsername').val())
    {
      e.preventDefault(); 
      swal({
        title: 'Confirm',
        text: 'Are you sure you want to truncate database content? Once confirmed, all data recorded will be deleted.',
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
        title: 'Info',
        text: 'Please make sure your username and password are valid!',
        type: 'info',
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary'
      });
      e.preventDefault();
    }
  });

  $(function () {

  })
</script>