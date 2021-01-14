<div class="content-wrapper">
  <?php if(in_array('60', $subModule)) { ?>
    <section class="content-header">
      <h1>
        Users
      </h1>
      <ol class="breadcrumb">
        <li><a href="http://localhost/ELendingTool/home/Dashboard" class="active"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#">System Set up</a></li>
        <li><a href="#">Add User</a></li>
      </ol>
    </section>

    <section class="content">
      <div class="modal fade" id="modalNewUser">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">User Details</h4>
            </div>
              <form action="<?php echo base_url(); ?>admin_controller/addUser/2" id="frminsert2" method="post">
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="selectEmployee">Employee</label><br>
                        <select name="selectEmployee" class="form-control"style="width: 100%"  id="selectEmployee">
                        </select>
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
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">List of Users</h3>
        </div>
        <div class="box-body">
          <button class="btn btn-primary pull-right" onclick="newUser()">Add User</button>
          <br>
          <br>
          <table id="example1" class="table table-bordered table-hover">
            <thead>
            <tr>
              <th>Employee Number</th>
              <th>Name</th>
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
        </div>
      </div>
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
</div>
<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0
  </div>
  <strong>Copyright &copy; 2020 <a href="https://giatechph.com" target="_blank">GIA Tech.</a></strong> All rights
  reserved.
</footer>

<div class="loading" style="display: none">Loading&#8230;</div>
<?php $this->load->view('includes/footer'); ?>

<script>
  function newUser()
  {
    $('#modalNewUser').modal('show');
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
  
  function confirm(Text, Id, updateType, tableType)
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
          url: "<?php echo base_url();?>" + "/employee_controller/updateEmail",
          method: "POST",
          data:   {
                    Id : Id
                    , updateType : updateType
                    , tableType : 'UserRoleUpdate'
                  },
          beforeSend: function(){
              $('.loading').show();
          },
          success: function(data)
          {
            refreshPage();
            swal({
              title: 'Success!',
              text: 'Resetting of password successful!',
              type: 'success',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });
            $('.loading').hide();
          },
          error: function (response) 
          {
            $('.loading').hide();
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

    $("#frmInsert").on('submit', function (e) {
      if($('#selectEmployee').val() == '' || $('#selectRoles').val() == '') 
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
      else
      {
        e.preventDefault(); 
        swal({
          title: 'Confirm',
          text: 'Are you sure you want to give access to this user? Once confirmed, existing password accounts will updated to the new password.',
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
    });

    $("#frminsert2").on('submit', function (e) {
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

    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/datatables_controller/Users/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "EmployeeNumber" }
                    , { data: "Name" }
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
                        else if(row.StatusId == 0){
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
                          return '<a href="<?php echo base_url()."home/accessManagement/"; ?>'+row.EmployeeId+'" class="btn btn-sm btn-primary" title="Access Management"><span class="fa fa-calendar-check-o"></span></a>  <a onclick="confirm(\'Are you sure you want to deactivate this user?\', \''+row.UserRoleId+'\', 0,\'UserRoleUpdate\')" class="btn btn-sm btn-danger" title="Deactivate"><span class="fa fa-close"></span></a> <a onclick="confirm(\'Are you sure you want to reset this user password?\', \''+row.UserRoleId+'\', 3)" class="btn btn-sm btn-warning" title="Reset Password"><span class="fa fa-exchange"></span></a>';
                        }
                        else if(row.StatusId == 0){
                          return '<a onclick="confirm(\'Are you sure you want to re-activate this user?\', \''+row.UserRoleId+'\', 1,\'UserRoleUpdate\')" class="btn btn-sm btn-success" title="Deactivate"><span class="fa fa-refresh"></span></a>';
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
  })
</script>