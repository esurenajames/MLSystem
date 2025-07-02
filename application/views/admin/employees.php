
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Employee List</h1>
          </div>
        </div>
      </div>
    </div>

      <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Employee</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url(); ?>admin_controller/addEmployee/" class="frminsert2" method="post">
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                    <h6>First Name</h6>
                    <input type="text" required="" name="FirstName" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <h6>Middle Name</h6>
                    <input type="text" name="MiddleName" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <h6>Last Name</h6>
                    <input type="text" required="" name="LastName" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <h6>Ext. Name</h6>
                    <input type="text" name="ExtName" class="form-control">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <h6>Position</h6>
                    <select class="form-control select2" name="PositionId" style="width: 100%;">
                      <?php 
                        foreach ($position as $key => $value) 
                        {
                          echo '<option value="'.$value['Id'].'">'.$value['Description'].'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <h6>Branch Assigned</h6>
                    <select class="form-control select2" name="BranchId" style="width: 100%;">
                      <?php 
                        foreach ($branch as $key => $value) 
                        {
                          echo '<option value="'.$value['Id'].'">'.$value['Description'].'</option>';
                        }
                      ?>
                    </select>
                  </div>
                </div>
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
                  <a class="btn btn-md btn-primary" data-toggle="modal" data-target="#modal-default">Add Record</a>
                </div>
                <br>
                <br>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th width="15%">Employee Number</th>
                    <th>Name</th>
                    <th>Position</th>
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
    var url = '<?php echo base_url()."admin_controller/getEmployeeList/"; ?>';
    UserTable.ajax.url(url).load();
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
      "ajax": { url: '<?php echo base_url()."/admin_controller/getEmployeeList/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "EmployeeNumber" }
                    , { data: "Name" }
                    , { data: "Position" }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        return "<span class='badge bg-"+row.Color+"'>"+row.StatusDescription+"</span>";
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        if(row.StatusId == 1){
                          return '<a href="<?php echo base_url() ?>home/EmployeeDetail/'+row.ID+'" class="btn btn-default" title="View"><span class="fa fa-eye"></span></a>';
                        }
                        else if(row.StatusId == 2){
                          return '<a href="<?php echo base_url() ?>home/EmployeeDetail/'+row.ID+'" class="btn btn-default" title="View"><span class="fa fa-eye"></span></a>';
                        }
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[0, "asc"]]
    });

  });
</script>