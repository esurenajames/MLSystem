
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Details</h1>
          </div>
        </div>
      </div>
    </div>

      <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Subject/Faculty</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url(); ?>admin_controller/addSubjectClass/<?php print_r($this->uri->segment(3)); ?>" class="frminsert2" method="post">
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <h6>Subject</h6>
                    <select class="form-control select2" style="width: 100%" name="SubjectId">
                      <?php 
                        foreach ($subjects as $key => $value) {
                          echo '<option value="'.$value['Id'].'">'.$value['Code'].'-'.$value['Description'].'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-12">
                    <h6>Faculty</h6>
                    <select class="form-control select2" style="width: 100%" name="EmployeeNumber">
                      <?php 
                        foreach ($faculty as $key => $value) {
                          echo '<option value="'.$value['EmployeeNumber'].'">'.$value['Name'].'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-12">
                    <h6>Max no of Students</h6>
                    <input class="form-control" type="number" min="1" max="100" name="MaxNo">
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
              <h4 class="modal-title">Add Subject/Faculty</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url(); ?>admin_controller/editSubjectClass/<?php print_r($this->uri->segment(3)); ?>" class="frminsert2" method="post">
              <div class="modal-body">
                <div class="row">
                  <input class="form-control" id="txtId" type="" name="Id">
                  <div class="col-md-12">
                    <h6>Subject</h6>
                    <select class="form-control select2" readonly id="txtSubject" style="width: 100%" name="SubjectId">
                      <?php 
                        foreach ($subjects as $key => $value) {
                          echo '<option value="'.$value['Id'].'">'.$value['Code'].'-'.$value['Description'].'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-12">
                    <h6>Faculty</h6>
                    <select class="form-control select2" readonly id="txtFaculty" style="width: 100%" name="EmployeeNumber">
                      <?php 
                        foreach ($faculty as $key => $value) {
                          echo '<option value="'.$value['EmployeeNumber'].'">'.$value['Name'].'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-12">
                    <h6>Max no of Students</h6>
                    <input class="form-control" id="txtMaxNo" type="number" min="1" max="100" name="MaxNo">
                  </div>
                  <div class="col-md-12">
                    <h6>Description</h6>
                    <textarea class="form-control" id="txtDescription" name="Description"></textarea>
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
                <h5 class="m-0">Class Details</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <label>Class Name</label>
                    <h6><?php print_r($detail['Name']) ?></h6>
                  </div>
                  <div class="col-md-4">
                    <label>Max no. of Students</label>
                    <h6><?php print_r($detail['MaxStudents']) ?></h6>
                  </div>
                  <div class="col-md-4">
                    <label>Description</label>
                    <h6><?php print_r($detail['Description']) ?></h6>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="m-0">Subjects</h5>
              </div>
              <div class="card-body">
                <br>
                <br>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th width="15%">Subject Code</th>
                    <th>Subject Name</th>
                    <th>Units</th>
                    <th>Total Enrolled Students</th>
                    <th>Max Students</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
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
    var url = '<?php echo base_url()."admin_controller/getSubjectClassList/"; ?>';
    UserTable.ajax.url(url).load();
  }

  function updateRecord(Id, Type, Description, MaxStudents, SubjectId, FacultyId)
  {
    if(Type == 4) // update role
    {
      $('#txtId').val(Id)
      $('#txtSubject').val(SubjectId).change()
      $('#txtFaculty').val(FacultyId).change()
      $('#txtMaxNo').val(MaxStudents)
      $('#txtDescription').val(Description)
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
          url: "<?php echo base_url();?>" + "/admin_controller/updateSubjectClassRecord",
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
      "ajax": { url: '<?php echo base_url()."/admin_controller/getFacultySubjectClassList/". $this->uri->segment(3); ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "Code" }
                    , { data: "SubjectName" }
                    , { data: "Units" }
                    , { data: "TotalStudents" }
                    , { data: "MaxStudents" }
                    , { data: "Description" }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        return "<span class='badge bg-"+row.Color+"'>"+row.StatusDescription+"</span>";
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        if(row.StatusId == 1){
                          if(row.TotalStudents > 0)
                          {
                            return '<a href="<?php echo base_url() ?>home/FacultysubjectStudents/'+row.ClassSubjectId+'" class="btn btn-default" title="View"><span class="fa fa-eye"></span></a> <a href="<?php echo base_url() ?>home/createExam/'+row.ClassSubjectId+'" class="btn btn-primary" title="Examination List"><span class="fa fa-diagnoses"></span></a>';
                          }
                          else
                          {
                            return '<a href="<?php echo base_url() ?>home/FacultysubjectStudents/'+row.ClassSubjectId+'" class="btn btn-default" title="View"><span class="fa fa-eye"></span></a>';
                          }
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