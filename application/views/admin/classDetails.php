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
                    <select class="form-control select2" style="width: 100%" name="SubjectId" id="SubjectId" required>
                      <option value="">Select Subject</option>
                      <?php 
                        foreach ($subjects as $key => $value) {
                          echo '<option value="'.$value['Id'].'">'.$value['Code'].'-'.$value['Description'].'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-12">
                    <h6>Faculty</h6>
                    <select class="form-control select2" style="width: 100%" name="EmployeeNumber" id="EmployeeNumber" required>
                      <option value="">Select Subject First</option>
                    </select>
                    <small class="form-text text-muted">Only faculty assigned to the selected subject will be shown</small>
                  </div>
                  <div class="col-md-12">
                    <h6>Max no of Students</h6>
                    <input class="form-control" type="number" min="1" max="100" name="MaxNo" required>
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
              <h4 class="modal-title">Edit Subject/Faculty</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url(); ?>admin_controller/editSubjectClass/<?php print_r($this->uri->segment(3)); ?>" class="frminsert2" method="post">
              <div class="modal-body">
                <div class="row">
                  <input class="form-control" id="txtId" type="hidden" name="Id">
                  <div class="col-md-12">
                    <h6>Subject</h6>
                    <select class="form-control select2" id="txtSubject" style="width: 100%" name="SubjectId" required>
                      <?php 
                        foreach ($subjects as $key => $value) {
                          echo '<option value="'.$value['Id'].'">'.$value['Code'].'-'.$value['Description'].'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-12">
                    <h6>Faculty</h6>
                    <select class="form-control select2" id="txtFaculty" style="width: 100%" name="EmployeeNumber" required>
                      <option value="">Select Subject First</option>
                    </select>
                    <small class="form-text text-muted">Only faculty assigned to the selected subject will be shown</small>
                  </div>
                  <div class="col-md-12">
                    <h6>Max no of Students</h6>
                    <input class="form-control" id="txtMaxNo" type="number" min="1" max="100" name="MaxNo" required>
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
                <div class="float-right">
                  <a class="btn btn-md btn-primary" data-toggle="modal" data-target="#modal-default">Add Subject</a>
                </div>
                <br>
                <br>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th width="15%">Subject Code</th>
                    <th>Subject Name</th>
                    <th>Faculty</th>
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

  function loadFacultyBySubject(subjectId, targetSelect) {
    if(subjectId) {
      $.ajax({
        url: '<?php echo base_url()."admin_controller/getFacultyBySubjectForClass/"; ?>',
        method: 'POST',
        data: { SubjectId: subjectId },
        dataType: 'json',
        success: function(data) {
          var facultySelect = $(targetSelect);
          facultySelect.empty();
          
          if(data.length > 0) {
            facultySelect.append('<option value="">Select Faculty</option>');
            $.each(data, function(index, faculty) {
              facultySelect.append('<option value="' + faculty.EmployeeNumber + '">' + faculty.Name + '</option>');
            });
          } else {
            facultySelect.append('<option value="">No faculty assigned to this subject</option>');
          }
          
          facultySelect.trigger('change');
        },
        error: function() {
          var facultySelect = $(targetSelect);
          facultySelect.empty();
          facultySelect.append('<option value="">Error loading faculty</option>');
          
          swal({
            title: 'Error!',
            text: 'Failed to load faculty for this subject',
            type: 'error',
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary'
          });
        }
      });
    } else {
      var facultySelect = $(targetSelect);
      facultySelect.empty();
      facultySelect.append('<option value="">Select Subject First</option>');
    }
  }

  function updateRecord(Id, Type, Description, MaxStudents, SubjectId, FacultyId)
  {
    if(Type == 4) // update role
    {
      $('#txtId').val(Id);
      $('#txtSubject').val(SubjectId).change();
      $('#txtMaxNo').val(MaxStudents);
      $('#txtDescription').val(Description);
      
      // Load faculty for this subject and then select the current faculty
      loadFacultyBySubject(SubjectId, '#txtFaculty');
      
      // Set the faculty value after a short delay to ensure the options are loaded
      setTimeout(function() {
        $('#txtFaculty').val(FacultyId).change();
      }, 500);
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
                    Id : Id,
                    Type : Type
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

    // Handle subject change for add modal
    $('#SubjectId').on('change', function() {
      var subjectId = $(this).val();
      loadFacultyBySubject(subjectId, '#EmployeeNumber');
    });

    // Handle subject change for edit modal
    $('#txtSubject').on('change', function() {
      var subjectId = $(this).val();
      loadFacultyBySubject(subjectId, '#txtFaculty');
    });

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
      "ajax": { url: '<?php echo base_url()."/admin_controller/getSubjectClassList/". $this->uri->segment(3); ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "Code" },
                    { data: "SubjectName" },
                    { data: "FacultyName" },
                    { data: "Units" },
                    { data: "TotalStudents" },
                    { data: "MaxStudents" },
                    { data: "Description" },
                    {
                      data: "StatusId", "render": function (data, type, row) {
                        return "<span class='badge bg-"+row.Color+"'>"+row.StatusDescription+"</span>";
                      }
                    },
                    {
                      data: "StatusId", "render": function (data, type, row) {
                        if(row.StatusId == 1){
                          if(row.TotalStudents > 0)
                          {
                            return '<a href="<?php echo base_url() ?>home/FacultysubjectStudents/'+row.ClassSubjectId+'" class="btn btn-sm btn-info" title="View Students"><i class="fa fa-eye"></i></a> <a onclick="updateRecord('+row.Id+', 1)" class="btn btn-sm btn-danger" title="Deactivate"><i class="fa fa-window-close"></i></a>';
                          }
                          else
                          {
                            return '<a href="<?php echo base_url() ?>home/FacultysubjectStudents/'+row.ClassSubjectId+'" class="btn btn-sm btn-info" title="View Students"><i class="fa fa-eye"></i></a> <a onclick="updateRecord('+row.Id+', 4, \''+row.Description+'\', \''+row.MaxStudents+'\', \''+row.SubjectId+'\', \''+row.FacultyId+'\')" data-toggle="modal" data-target="#modalEdit" class="btn btn-sm btn-primary" title="Edit"><i class="fa fa-edit"></i></a> <a onclick="updateRecord('+row.Id+', 1)" class="btn btn-sm btn-danger" title="Deactivate"><i class="fa fa-window-close"></i></a>';
                          }
                        }
                        else
                        {
                          return '<a onclick="updateRecord('+row.Id+', 2)" class="btn btn-sm btn-warning" title="Re-activate"><i class="fa fa-retweet"></i></a>';
                        }
                      }
                    },
      ],
      "order": [[0, "asc"]]
    });
  });
</script>