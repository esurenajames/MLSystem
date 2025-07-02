  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Details for class <a href="<?php echo base_url() ?>home/classDetails/<?php print_r($detail['ClassId']) ?>"><?php print_r($detail['ClassName']) ?></a></h1>
          </div>
        </div>
      </div>
    </div>

      <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Student(s)</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url(); ?>admin_controller/addSubjectStudent/<?php print_r($this->uri->segment(3)); ?>" class="frminsert2" method="post">
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <h6>Student</h6>
                    <select class="form-control" id="selectStudent" multiple="" style="width: 100%" name="StudentId[]">
                      <?php 
                        foreach ($students as $key => $value) {
                          echo '<option value="'.$value['Id'].'">'.$value['Name'].'</option>';
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
                <h5 class="m-0">Students for <?php print_r($detail['SubjectCode']) ?></h5>
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
                <h5 class="m-0"></h5>
              </div>
              <div class="card-body">
                <div class="card card-primary card-outline card-outline-tabs">
                  <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="false">Exam Proper</a>
                      </li>
                      <!-- <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-four-mockExam-tab" data-toggle="pill" href="#custom-tabs-four-mockExam" role="tab" aria-controls="custom-tabs-four-mockExam" aria-selected="false">Mock Exam</a>
                      </li> -->
                      <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Reviewers</a>
                      </li>
                    </ul>
                  </div>
                  <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                      <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                        <?php if($hasTakenExam != 1) {?>
                          <?php if($examSchedule > 0) {?>
                            <?php if($examSchedule['ForExam'] == 'For exam') {?>
                              <?php if($countExamCreated > 0) {?>
                                <center><a class="btn btn-md btn-primary" href="<?php echo base_url(); ?>home/takeExam/<?php print_r($this->uri->segment(3)); ?>">Take Exam for <?php print_r($detail['SubjectName']) ?></a></center>
                              <?php } else { ?>
                                <div class="alert alert-warning alert-dismissible">
                                  <h5><i class="icon fas fa-exclamation-triangle"></i> No exam has been created for this subject!</h5>
                                    Please contact your registrar to adjust examination dates.
                                </div>
                              <?php } ?>
                            <?php } else if($examSchedule['ForCreation'] == 'OK') { ?>
                              <div class="alert alert-warning alert-dismissible">
                                <h5><i class="icon fas fa-exclamation-triangle"></i> Exam is not yet finalized!</h5>
                                  Exam is still being prepared.
                              </div>
                            <?php } ?>
                          <?php } else { ?>
                            <div class="alert alert-warning alert-dismissible">
                              <h5><i class="icon fas fa-exclamation-triangle"></i> There is no created and scheduled exam for this subject!</h5>
                            </div>
                          <?php } ?>
                        <?php } else { ?>
                          <div class="row">  
                            <?php    
                              $correctAnswer = $this->admin_model->countCorrectAnswers($examDetails['ExamId']);
                              $totalQuestions = $this->admin_model->countQuestions($examDetails['ExamId']);
                            ?>
                            <div class="col-md-4">
                              <label>Date Exam Taken</label>
                              <h6><?php print_r($examDetails['DateTaken']) ?></h6>
                            </div>
                            <div class="col-md-4">
                              <label>Score</label>
                              <h6><?php print_r($correctAnswer) ?>/<?php print_r($totalQuestions) ?></h6>
                            </div>
                            <div class="col-md-4">
                              <label>Status</label>
                              <h6><?php 
                                $totalPercentage = 0;
                                $totalPercentage = ($correctAnswer/$totalQuestions)*100;

                                if($totalPercentage >= 70)
                                {
                                  $examResult = '<label style="color:#08A133">Passed</label>';
                                }
                                else
                                {
                                  $examResult = '<label style="color:#D92323">Failed</label>';
                                }
                                print_r($totalPercentage . '% - ' .$examResult); 
                              ?></h6>
                            </div>
                          </div>
                          <br>
                          <center><a class="btn btn-md btn-primary" href="<?php echo base_url(); ?>home/viewExam/<?php print_r($examDetails['ExamId']); ?>">View Examination Answers for <?php print_r($detail['SubjectName']) ?></a></center>
                        <?php } ?>
                      </div>
                      <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                        <table id="dtblReviewer" style="width:100%" class="table table-bordered table-striped">
                          <thead>
                          <tr>
                            <th>File Name</th>
                            <th>Notes</th>
                            <th>Created By</th>
                            <th>Date Created</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                          </thead>
                        </table>
                      </div>
                      <div class="tab-pane fade" id="custom-tabs-four-mockExam" role="tabpanel" aria-labelledby="custom-tabs-four-mockExam-tab">
                        <!-- <?php if($countMockExam != 0){ ?>
                            <center><a class="btn btn-md btn-primary" href="<?php echo base_url(); ?>home/takeExam/<?php print_r($this->uri->segment(3)); ?>">Take Exam for <?php print_r($detail['SubjectName']) ?></a></center>
                        <?php } else { ?>
                          <div class="alert alert-danger alert-dismissible">
                            <h5><i class="icon fas fa-exclamation-triangle"></i> No mock exam for this subject!</h5>
                          </div>
                        <?php } ?> -->
                      </div>
                    </div>
                  </div>
                </div>
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

    UserTable2 = $('#dtblReviewer').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/admin_controller/getExamReviewers/".$this->uri->segment(3); ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "FileTitle" }
                    , { data: "Notes" }
                    , { data: "CreatedBy" }
                    , { data: "DateCreated" }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        return "<span class='badge bg-"+row.Color+"'>"+row.StatusDescription+"</span>";
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        return '<a href="<?php echo base_url(); ?>/home/download/1/'+row.ID+'" class="btn btn-sm btn-success" title="Download"><span class="fa fa-download"></span></a>';
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[0, "asc"]]
    });

  });
</script>