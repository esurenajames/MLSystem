<div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1 class="m-0">Create Exam for <a href="<?php echo base_url() ?>home/facultyClassDetails/<?php print_r($detail['ClassId']) ?>"><?php print_r($detail['ClassName']) ?> - <?php print_r($detail['SubjectCode']) ?></a></h1>
          </div>
        </div>
      </div>
    </div>

      <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Create Exam</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url(); ?>admin_controller/addSubjectExam/<?php print_r($this->uri->segment(3)); ?>" class="frminsert2" method="post">
              <div class="modal-body">
                <div class="row">
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

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="m-0">Subject Details</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3">
                    <label>Subject Code</label>
                    <h6><?php print_r($detail['Code']) ?></h6>
                  </div>
                  <div class="col-md-3">
                    <label>Subject Name</label>
                    <h6><?php print_r($detail['SubjectName']) ?></h6>
                  </div>
                  <div class="col-md-3">
                    <label>Subject Description</label>
                    <h6><?php print_r($detail['SubjectDescription']) ?></h6>
                  </div>
                  <div class="col-md-3">
                    <label>Max no. of Students</label>
                    <h6><?php print_r($detail['MaxStudents']) ?></h6>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="m-0">Exam List for <?php print_r($detail['SubjectCode']) ?></h5> <small>* Only 1 exam is valid for student to take.</small>
              </div>
              <div class="card-body">
                <?php if(isset($examSchedule) && is_array($examSchedule)) { ?>
                  <?php if(isset($examSchedule['ForCreation']) && $examSchedule['ForCreation'] == "OK") { ?>
                    <div class="alert alert-success alert-dismissible">
                      <h5><i class="icon fas fa-check"></i> Exam scheduled!</h5>
                      NOTE: This exam has been scheduled to be participated by students enrolled on <?php echo isset($examSchedule['ExamSchedule']) ? $examSchedule['ExamSchedule'] : 'N/A' ?>. Please create your exam on or before <?php echo isset($examSchedule['LastDateToCreate']) ? $examSchedule['LastDateToCreate'] : 'N/A' ?>. <strong>Only exams tagged as EXAM PROPER are to be answered by the students.</strong>
                    </div>
                    <div class="float-right">
                      <a class="btn btn-md btn-primary" data-toggle="modal" data-target="#modal-default">Add Exam</a>
                    </div>
                    <br>
                    <br>
                  <?php } else { ?>
                    <div class="alert alert-warning alert-dismissible">
                      <h5><i class="icon fas fa-exclamation-triangle"></i> Exam Creation Closed!</h5>
                        Exam creation/editing has been closed. <?php echo isset($examSchedule['DateStart']) ? 'Exam is to start at ' . $examSchedule['DateStart'] : '' ?>
                    </div>
                  <?php } ?>
                <?php } else { ?>
                    <div class="alert alert-warning alert-dismissible">
                      <h5><i class="icon fas fa-exclamation-triangle"></i> Registrar has not yet scheduled examination date!</h5>
                    </div>
                <?php } ?>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th width="15%">Exam Code</th>
                    <th>Description</th>
                    <th>No. of Students Participated</th>
                    <th>Total no. of Students</th>
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

  function updateRecord(Id, Type, ClassName, MaxStudents, ClassDescription)
  {
    if(Type == 4) // update role
    {
      $('#txtId').val(Id)
      $('#txtName').val(ClassName)
      $('#txtMaxNo').val(MaxStudents)
      $('#txtDescription').val(ClassDescription)
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
    $('#selectStudent').select2({
      maximumSelectionLength: <?php print_r($detail['MaxStudents']) ?>
    })

    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/admin_controller/getSubjectCreatedExam/".$this->uri->segment(3); ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "ExamCode" }
                    , { data: "Description" }
                    , { data: "Description" }
                    , { data: "Description" }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        return "<span class='badge bg-"+row.Color+"'>"+row.StatusDescription+"</span>";
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {

                        <?php if(isset($examSchedule) && is_array($examSchedule)) { ?>
                          <?php if(isset($examSchedule['ForCreation']) && $examSchedule['ForCreation'] == 'OK') { ?>
                            if(row.StatusId == 3){ // EXAM PROPER
                              return '<a href="<?php echo base_url() ?>home/examDetails/'+row.ExamId+'/'+<?php echo $this->uri->segment(3); ?>+'" class="btn btn-primary" title="Edit Exam"><span class="fa fa-pen-square"></span></a> <a href="<?php echo base_url() ?>home/viewExamFormat/'+row.ExamId+'/'+<?php echo $this->uri->segment(3); ?>+'" class="btn btn-default" title="View Exam"><span class="fa fa-eye"></span></a> <a href="<?php echo base_url() ?>home/takeExam/'+row.ExamId+'" class="btn btn-danger" title="Deactivate"><span class="fa fa-window-close"></span></a>';
                            }
                            else if(row.StatusId == 4) // MOCK EXAM
                            {
                              return '<a href="<?php echo base_url() ?>home/examDetails/'+row.ExamId+'/'+<?php echo $this->uri->segment(3); ?>+'" class="btn btn-default" title="View Exam"><span class="fa fa-eye"></span></a> <a href="<?php echo base_url() ?>home/takeExam/'+row.ExamId+'" class="btn btn-primary" title="View as Examinee"><span class="fa fa-pen-square"></span></a> <a href="<?php echo base_url() ?>home/takeExam/'+row.ExamId+'" class="btn btn-success" title="Set as proper exam"><span class="fa fa-check-circle"></span></a> <a href="<?php echo base_url() ?>home/takeExam/'+row.ExamId+'" class="btn btn-danger" title="Deactivate"><span class="fa fa-window-close"></span></a>';
                            }
                            else
                            {
                              return '';
                            }
                          <?php } else { ?>
                            return '<a href="<?php echo base_url() ?>home/viewExam/'+row.ExamId+'" class="btn btn-default" title="View Exam"><span class="fa fa-eye"></span></a>';
                          <?php } ?>
                        <?php } else { ?>
                          return '<a href="<?php echo base_url() ?>home/viewExam/'+row.ExamId+'" class="btn btn-default" title="View Exam"><span class="fa fa-eye"></span></a>';
                        <?php } ?>
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[4, "DESC"]]
    });

  });
</script>