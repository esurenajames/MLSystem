
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">User List</h1>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal-default">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="<?php echo base_url(); ?>admin_controller/addUser/2" class="frminsert2" method="post">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <h6>Employee Name</h6>
                  <select class="form-control select2" required="" name="EmployeeNumber" style="width: 100%;">
                    <option selected="" value="" disabled="">Select Employee</option>
                    <?php 
                      foreach ($employees as $key => $value) 
                      {
                        echo '<option value="'.$value['EmployeeNumber'].'">'.$value['EmployeeNumber'].' - '.$value['Name'].'</option>';
                      }
                    ?>
                  </select>
                </div>
                <div class="col-md-12">
                  <h6>Role</h6>
                  <select class="form-control select2" required="" name="RoleId" style="width: 100%;">
                    <?php 
                      foreach ($role as $key => $value) 
                      {
                        echo '<option value="'.$value['Id'].'">'.$value['Description'].'</option>';
                      }
                    ?>
                  </select>
                </div>
              </div>
              <br>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalStudents">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="<?php echo base_url(); ?>admin_controller/addUser/3" class="frminsert2" method="post">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <h6>Student Name</h6>
                  <select class="form-control select2" required="" name="EmployeeNumber" style="width: 100%;">
                    <option selected="" value="" disabled="">Select Student</option>
                    <?php 
                      foreach ($students as $key => $value) 
                      {
                        echo '<option value="'.$value['StudentNumber'].'">'.$value['StudentNumber'].' - '.$value['StudentName'].'</option>';
                      }
                    ?>
                  </select>
                </div>
              </div>
              <br>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalUpdateRole">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Change Role</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="<?php echo base_url(); ?>admin_controller/UpdateUser" class="frminsert2" method="post">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <h6>Role</h6>
                  <input type="hidden" id="txtId" name="ID">
                  <input type="hidden" value="4" name="Type">
                  <select class="form-control select2" required="" id="txtRole" name="RoleId" style="width: 100%;">
                    <?php 
                      foreach ($role as $key => $value) 
                      {
                        echo '<option value="'.$value['Id'].'">'.$value['Description'].'</option>';
                      }
                    ?>
                  </select>
                </div>
              </div>
              <br>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="m-0"></h5>
              </div>
              <div class="card-body">
                <div class="float-right">
                  <a class="btn btn-md btn-primary" data-toggle="modal" data-target="#modal-default">Add Employee Users</a>
                  <a class="btn btn-md btn-primary" data-toggle="modal" data-target="#modalStudents">Add Student Users</a>
                </div>
                <br>
                <br>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th width="15%">Employee Number</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Role</th>
                    <th>Is password renewed?</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
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

  function refreshPage(){
    var url = '<?php echo base_url()."admin_controller/getUserList/"; ?>';
    UserTable.ajax.url(url).load();
  }

  function UpdateUser(Id, Type, value)
  {
    if(Type == 4) // update role
    {
      $('#txtRole').val(value).change()
      $('#txtId').val(Id)
    }
    else
    {
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
        $.ajax({                
          url: "<?php echo base_url();?>" + "/admin_controller/UpdateUser",
          method: "POST",
          async: false,
          data:   {
                    Id : Id
                    , Type : Type
                  },  
          dataType: "JSON",
          beforeSend: function(){
              $('.loading').show();
          },
          success: function(data)
          {
            swal({
              title: 'Success!',
              text: 'Record successfully updated!',
              type: 'success',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });
            refreshPage();
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
      });
    }
  }

  $(function () {

    $(".frminsert2").on('submit', function (e) {
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
      "ajax": { url: '<?php echo base_url()."/admin_controller/getUserList/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "EmployeeNumber" }
                    , { data: "Name" }
                    , { data: "Position" }
                    , { data: "Role" }
                    , {
                      data: "IsNew", "render": function (data, type, row) {
                        if(row.IsNew == 1)
                        {
                          return "No";
                        }
                        else
                        {
                          return "Yes";
                        }
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        return "<span class='badge bg-"+row.Color+"'>"+row.StatusDescription+"</span>";
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        if(row.StatusId == 1){
                          return '<a onclick="UpdateUser('+row.ID+', 1)" class="btn btn-danger" title="Deactivate"><span class="fa fa-window-close"></span></a> <a onclick="UpdateUser('+row.ID+', 3)" class="btn btn-warning" title="Reset Password"><span class="fa fa-retweet"></span></a> <a onclick="UpdateUser('+row.ID+', 4, '+row.RoleId+')" data-toggle="modal" data-target="#modalUpdateRole" class="btn btn-success" title="Update Role"><span class="fa fa-user"></span></a>';
                        }
                        else if(row.StatusId == 2){
                          return '<a onclick="UpdateUser('+row.ID+', 2)" class="btn btn-success" title="Reactivate"><span class="fa fa-check"></span></a>';
                        }
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[0, "asc"]]
    });

  });
</script>