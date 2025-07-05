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
                  <div class="col-md-12">
                    <h6>Assign Faculty</h6>
                    <select name="FacultyIds[]" id="FacultyIds" multiple class="form-control select2" style="width: 100%;">
                      <!-- Options will be populated via AJAX -->
                    </select>
                    <small class="form-text text-muted">Hold Ctrl to select multiple faculty members</small>
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
              <h4 class="modal-title">Edit Subject</h4>
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
                    <textarea class="form-control" name="Description" id="txtDescription"></textarea>
                  </div>
                  <div class="col-md-12">
                    <h6>Assign Faculty</h6>
                    <select name="FacultyIds[]" id="EditFacultyIds" multiple class="form-control select2" style="width: 100%;">
                     
                    </select>
                    <small class="form-text text-muted">Hold Ctrl to select multiple faculty members</small>
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

      <!-- Faculty Management Modal -->
      <div class="modal fade" id="modalFacultyManagement">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Manage Faculty Assignment</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="frmFacultyManagement" method="post">
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <h6>Subject: <span id="subjectNameDisplay"></span></h6>
                    <input type="hidden" id="manageFacultySubjectId" name="SubjectId">
                  </div>
                  <div class="col-md-12">
                    <h6>Assign Faculty</h6>
                    <select name="FacultyIds[]" id="ManageFacultyIds" multiple class="form-control select2" style="width: 100%;">
                    
                    </select>
                    <small class="form-text text-muted">Hold Ctrl to select multiple faculty members</small>
                  </div>
                </div>
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Faculty Assignment</button>
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
                    <th>Faculty Count</th>
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

  function loadFacultyList() {
    $.ajax({
      url: '<?php echo base_url()."admin_controller/getAllFacultyForSubjectAssignment/"; ?>',
      method: 'POST',
      dataType: 'json',
      success: function(data) {
        var facultySelect = $('#FacultyIds');
        var editFacultySelect = $('#EditFacultyIds');
        var manageFacultySelect = $('#ManageFacultyIds');
        
        facultySelect.empty();
        editFacultySelect.empty();
        manageFacultySelect.empty();
        
        $.each(data, function(index, faculty) {
          facultySelect.append('<option value="' + faculty.EmployeeNumber + '">' + faculty.Name + '</option>');
          editFacultySelect.append('<option value="' + faculty.EmployeeNumber + '">' + faculty.Name + '</option>');
          manageFacultySelect.append('<option value="' + faculty.EmployeeNumber + '">' + faculty.Name + '</option>');
        });
        
        // Refresh select2
        facultySelect.trigger('change');
        editFacultySelect.trigger('change');
        manageFacultySelect.trigger('change');
      },
      error: function() {
        swal({
          title: 'Error!',
          text: 'Failed to load faculty list',
          type: 'error',
          buttonsStyling: false,
          confirmButtonClass: 'btn btn-primary'
        });
      }
    });
  }

  function loadAssignedFaculty(subjectId) {
    $.ajax({
      url: '<?php echo base_url()."admin_controller/getAssignedFacultyBySubject/"; ?>',
      method: 'POST',
      data: { SubjectId: subjectId },
      dataType: 'json',
      success: function(data) {
        var selectedFaculty = [];
        $.each(data, function(index, faculty) {
          selectedFaculty.push(faculty.EmployeeNumber);
        });
        
        $('#EditFacultyIds').val(selectedFaculty);
        $('#EditFacultyIds').trigger('change');
      },
      error: function() {
        console.log('Error loading assigned faculty');
      }
    });
  }

  function manageFaculty(subjectId, subjectCode, subjectName) {
    $('#manageFacultySubjectId').val(subjectId);
    $('#subjectNameDisplay').text(subjectCode + ' - ' + subjectName);
    
    // Load assigned faculty
    $.ajax({
      url: '<?php echo base_url()."admin_controller/getAssignedFacultyBySubject/"; ?>',
      method: 'POST',
      data: { SubjectId: subjectId },
      dataType: 'json',
      success: function(data) {
        var selectedFaculty = [];
        $.each(data, function(index, faculty) {
          selectedFaculty.push(faculty.EmployeeNumber);
        });
        
        $('#ManageFacultyIds').val(selectedFaculty);
        $('#ManageFacultyIds').trigger('change');
      },
      error: function() {
        console.log('Error loading assigned faculty');
      }
    });
    
    $('#modalFacultyManagement').modal('show');
  }

  function updateRecord(Id, Type, Code, SubjectName, SubjectDescription, Units)
  {
    if(Type == 4) // update role
    {
      $('#txtId').val(Id);
      $('#txtCode').val(Code);
      $('#txtName').val(SubjectName);
      $('#txtUnit').val(Units);
      $('#txtDescription').val(SubjectDescription);
      
      // Load assigned faculty for editing
      loadAssignedFaculty(Id);
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

    // Load faculty list on page load
    loadFacultyList();

    // Handle faculty management form submission
    $('#frmFacultyManagement').on('submit', function(e) {
      e.preventDefault();
      
      swal({
        title: 'Confirm',
        text: 'Are you sure you want to update faculty assignments?',
        type: 'info',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-success',
        confirmButtonText: 'Confirm',
        cancelButtonClass: 'btn btn-secondary'
      }).then(function(){
        $.ajax({
          url: '<?php echo base_url()."admin_controller/manageSubjectFaculty/"; ?>',
          method: 'POST',
          data: $('#frmFacultyManagement').serialize(),
          dataType: 'json',
          success: function(data) {
            swal({
              title: 'Success!',
              text: 'Faculty assignments updated successfully!',
              type: 'success',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });
            $('#modalFacultyManagement').modal('hide');
            refreshPage();
          },
          error: function() {
            swal({
              title: 'Error!',
              text: 'Failed to update faculty assignments',
              type: 'error',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });
          }
        });
      });
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

    $('.select2').select2({
      placeholder: "Select Faculty",
      allowClear: true
    });

       UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/admin_controller/getSubjectList/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "Code" },
                    { data: "SubjectName" },
                    { data: "SubjectDescription" },
                    { data: "Units" },
                    { 
                      data: "FacultyCount",
                      "render": function (data, type, row) {
                        return '<span class="badge" style="background-color: #007bff; color: white; padding: 5px 10px; border-radius: 12px; font-size: 12px;">' + (data || 0) + ' Faculty</span>';
                      }
                    },
                    {
                      data: "StatusId", "render": function (data, type, row) {
                        return "<span class='badge bg-"+row.Color+"'>"+row.StatusDescription+"</span>";
                      }
                    },
                    {
                      data: "StatusId", "render": function (data, type, row) {
                        var actions = '';
                        if(row.StatusId == 1){
                          actions = '<a onclick="updateRecord('+row.Id+', 4, \''+row.Code+'\', \''+row.SubjectName+'\', \''+row.SubjectDescription+'\', \''+row.Units+'\')" data-toggle="modal" data-target="#modalEdit" class="btn btn-sm btn-primary" title="Edit"><i class="fa fa-edit"></i></a> ';
                          actions += '<a onclick="manageFaculty('+row.Id+', \''+row.Code+'\', \''+row.SubjectName+'\')" class="btn btn-sm btn-info" title="Manage Faculty"><i class="fa fa-users"></i></a> ';
                          actions += '<a onclick="updateRecord('+row.Id+', 1)" class="btn btn-sm btn-danger" title="Deactivate"><i class="fa fa-window-close"></i></a>';
                        }
                        else
                        {
                          actions = '<a onclick="updateRecord('+row.Id+', 2)" class="btn btn-sm btn-warning" title="Re-activate"><i class="fa fa-retweet"></i></a>';
                        }
                        return actions;
                      }
                    },
      ],
      "order": [[0, "asc"]]
    });
  });
</script>

<style>
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #007bff !important;
    color: white !important;
    border: 1px solid #007bff !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: white !important;
}

.select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #ffcccc !important;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #007bff !important;
}
</style>