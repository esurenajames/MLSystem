
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Subject List</h1>
          </div>
        </div>
      </div>
    </div>

      <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Create Subject</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url(); ?>admin_controller/addSubject/" class="frminsert2" method="post">
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <h6>Code</h6>
                    <input type="text" maxlength="3" minlength="3" required="" name="Code" class="form-control">
                  </div>
                  <div class="col-md-12">
                    <h6>Subject Name</h6>
                    <input type="text" name="Name" required="" class="form-control">
                  </div>
                  <div class="col-md-12">
                    <h6>Units</h6>
                    <input type="number" required="" min="0" name="Units" class="form-control">
                  </div>
                  <div class="col-md-12">
                    <h6>Description</h6>
                    <textarea class="form-control" name="Description"></textarea>
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

      <div class="modal fade" id="modalEdit">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Create Subject</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url(); ?>admin_controller/editSubject/" class="frminsert2" method="post">
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <h6>Code</h6>
                    <input type="hidden" required="" id="txtId" name="Id" class="form-control">
                    <input type="text" maxlength="3" minlength="3" required="" readonly="" id="txtCode" name="Code" class="form-control">
                  </div>
                  <div class="col-md-12">
                    <h6>Subject Name</h6>
                    <input type="text" name="Name" required="" id="txtName" class="form-control">
                  </div>
                  <div class="col-md-12">
                    <h6>Units</h6>
                    <input type="number" required="" min="0" name="Units" id="txtUnit" class="form-control">
                  </div>
                  <div class="col-md-12">
                    <h6>Description</h6>
                    <textarea class="form-control" name="Description"  id="txtDescription"></textarea>
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
                    <th width="15%">Subject Code</th>
                    <th>Subject Name</th>
                    <th>Description</th>
                    <th>Units</th>
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
    var url = '<?php echo base_url()."admin_controller/getSubjectList/"; ?>';
    UserTable.ajax.url(url).load();
  }

  function updateRecord(Id, Type, Code, SubjectName, SubjectDescription, Units)
  {
    if(Type == 4) // update role
    {
      $('#txtId').val(Id)
      $('#txtCode').val(Code)
      $('#txtName').val(SubjectName)
      $('#txtUnit').val(Units)
      $('#txtDescription').val(SubjectDescription)
    }
    else
    {
      var text = '';
      if(Type == 1) // deactivate
      {
        text = 'Are you sure you want to deactivate record?';
      }
      else // reactivate
      {
        text = 'Are you sure you want to re-activate record?';
      }

      swal({
        title: 'Confirm',
        text: text,
        type: 'info',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-success',
        confirmButtonText: 'Confirm',
        cancelButtonClass: 'btn btn-secondary'
      }).then(function(){
        $.ajax({                
          url: "<?php echo base_url();?>" + "/admin_controller/updateSubjectRecord",
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
      "ajax": { url: '<?php echo base_url()."/admin_controller/getSubjectList/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "Code" }
                    , { data: "SubjectName" }
                    , { data: "SubjectDescription" }
                    , { data: "Units" }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        return "<span class='badge bg-"+row.Color+"'>"+row.StatusDescription+"</span>";
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        if(row.StatusId == 1){
                          return '<a onclick="updateRecord('+row.Id+', 4, \''+row.Code+'\', \''+row.SubjectName+'\', \''+row.SubjectDescription+'\', \''+row.Units+'\')"  data-toggle="modal" data-target="#modalEdit" class="btn btn-primary" title="Edit"><span class="fa fa-edit"></span></a> <a onclick="updateRecord('+row.Id+', 1)" class="btn btn-danger" title="Deactivate"><span class="fa fa-window-close"></span></a>';
                        }
                        else
                        {
                          return '<a onclick="updateRecord('+row.Id+', 2)" class="btn btn-warning" title="Re-activate"><span class="fa fa-retweet"></span></a>';
                        }
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[0, "asc"]]
    });

  });
</script>