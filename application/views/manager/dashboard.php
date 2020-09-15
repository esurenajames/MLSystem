
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Manager's Dashboard
    </h1>
    <ol class="breadcrumb">
      <li><a href="#" class="active"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    </ol>
  </section>

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
            <h3 class="box-title">List of Employees</h3>
          </div>
          <div class="box-body">
            <form name="ApproverDocForm" method="post" id="ApproverDocForm">
              <table id="example1" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Employee Number</th>
                  <th>Name</th>
                  <th>Role</th>
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
          url: "<?php echo base_url();?>" + "/manager_controller/updateStatus",
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
              text: 'User role successfully deactivated!',
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


  $(function () {
    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/datatables_controller/Users/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "EmployeeNumber" }
                    , { data: "Name" }
                    , { data: "Description" }
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
                          return '<a onclick="confirm(\'Are you sure you want to deactivate this user?\', \''+row.UserRoleId+'\', 1)" class="btn btn-danger" title="Deactivate"><span class="fa fa-close"></span></a>';
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