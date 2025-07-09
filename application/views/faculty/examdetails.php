<div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1 class="m-0">Exam Details for <?php print_r($detail['ClassName']) ?> - <?php print_r($detail['SubjectCode']) ?></h1>
          </div>
        </div>
      </div>
    </div>



    <!-- Add Category Modal -->
    <div class="modal fade" id="modal-default">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add Category</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="<?php echo base_url(); ?>admin_controller/addExamCategory/<?php print_r($this->uri->segment(3)); ?>/<?php print_r($this->uri->segment(4)); ?>" class="frminsert2" method="post">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <h6>Category Name</h6>
                  <input class="form-control" name="CategoryName" required="" type="text">
                </div>
                <div class="col-md-12">
                  <h6>Percentage</h6>
                  <input class="form-control" name="Percentage" required="" type="number" min="0" max="100">
                </div>
                <div class="col-md-12">
                  <h6>Instructions</h6>
                  <textarea class="form-control" name="Instructions"></textarea>
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

    <!-- Add Question Modal -->
    <div class="modal fade" id="modalAddQuestion">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add Question</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="formAddQuestion" action="" method="post" class="frminsert2">
            <div class="modal-body">
              <div class="col-lg-12" id="example2">
                <input type="hidden" class="form-control" value="1" required="" name="QuestionRow[]">
                <table id="dtblQuestions1" class="table table-bordered">
                  <tbody>
                    <tr>
                      <td><input type="text" class="form-control" required="" placeholder="Untitled Question" id="txtQuestion" name="Question1[]"></td>
                      <td width="10%"><a class="btn  btn-sm btn-success" onclick="addOption(1)" title="Add Option"><span class="fa fa-plus-square"></span></a></td>
                    </tr>
                    <tr id="OptionRow1" class="OptionRowClass11">
                      <td>
                        <h6>Option 1</h6>
                        <input type="hidden" class="form-control" value="1" required="" name="OptionRowCount[]">
                        <input type="hidden" name="Answer[]" value="0" class="answerClass" id="txtAnswer11">
                        <input class="form-control" required="" name="Options[]" type="text">
                      </td>
                      <td width="10%">
                        <a class="btn btn-sm btn-primary" onclick="setAnswer(1, 1)" title="Set as Answer"><span class="fa fa-check"></span></a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Add Question</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Upload Reviewer Modal -->
    <div class="modal fade" id="modalUploadReviewer">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Upload Reviewer</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="<?php echo base_url(); ?>admin_controller/uploadReviewer/<?php print_r($this->uri->segment(3)); ?>/<?php print_r($this->uri->segment(4)); ?>" class="frminsert2" method="post" enctype="multipart/form-data">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Upload Reviewer</label>
                    <br>
                    <input type="file" name="Attachment[]" id="Attachment" accept=".jpeg, .jpg, .png">
                  </div>
                </div>
                <div class="col-md-12">
                  <h6>Notes</h6>
                  <textarea class="form-control" name="Notes"></textarea>
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
                    <h6><?php print_r($detail['SubjectCode']) ?></h6>
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
                <h5 class="m-0">Details for Exam #<?php print_r($detail['ExamCode']); ?></h5>
              </div>
              <div class="card-body">
                <div class="card card-primary card-outline card-outline-tabs">
                  <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="false">Exam Category</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Reviewers</a>
                      </li>
                    </ul>
                  </div>
                  <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                      <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                        <?php if($examSchedule['ForCreation'] == "OK") { ?>
                          <div class="alert alert-success alert-dismissible">
                            <h5><i class="icon fas fa-check"></i> Exam scheduled!</h5>
                            NOTE: This exam has been scheduled to be participated by students enrolled on <?php print_r($examSchedule['ExamSchedule']) ?>. Please modify your exam on or before <?php print_r($examSchedule['LastDateToCreate']) ?>. <strong>Only exams tagged as EXAM PROPER are to be answered by the students.</strong>
                          </div>
                          <div class="float-right">
                            <a class="btn btn-md btn-primary" data-toggle="modal" data-target="#modal-default">Add Category</a>
                          </div>
                          <br>
                          <br>
                        <?php } else { ?>
                          <div class="alert alert-warning alert-dismissible">
                            <h5><i class="icon fas fa-exclamation-triangle"></i> Exam Editing Closed!</h5>
                              Deactivation/editing of created exam is closed. Exam is to start at <?php print_r($examSchedule['DateStart']) ?>
                          </div>
                        <?php } ?>
                        <table id="example1" class="table table-bordered table-striped">
                          <thead>
                          <tr>
                            <th>Category Name</th>
                            <th>Instructions</th>
                            <th>Percentage</th>
                            <th>Total Questions</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                          </thead>
                        </table>
                      </div>
                      <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                        <div class="float-right">
                          <a class="btn btn-md btn-primary" data-toggle="modal" data-target="#modalUploadReviewer">Upload Reviewer</a>
                        </div>
                        <br>
                        <br>
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

  var rowNo = 2;
  var varSelectedAnswer = 0;

  function refreshPage(){
    var url = '<?php echo base_url()."admin_controller/getExamCategories/".$this->uri->segment(3); ?>';
    UserTable.ajax.url(url).load();
  }

  function refreshPage2(){
    var url = '<?php echo base_url()."admin_controller/getExamReviewers/".$this->uri->segment(3); ?>';
    UserTable2.ajax.url(url).load();
  }

  function updateRecord(Id, Type, FirstName, MiddleName, LastName, ExtName)
  {
    if(Type == 4) // update role
    {
      $('#txtId').val(Id)
      $('#txtFirstName').val(FirstName)
      $('#txtMiddleName').val(MiddleName)
      $('#txtLastName').val(LastName)
      $('#txtExtName').val(ExtName)
    }
    else
    {
      var text = '';
      text = 'Are you sure you want to deactivate record?';

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
          url: "<?php echo base_url();?>" + "/admin_controller/updateExamCategoryRecords",
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
            if(Type == 1)
            {
              refreshPage2();
            }
            else
            {
              refreshPage();
            }
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

  var varOptionNo = 1;
  function addOption(QuestionNo)
  {
    var colCount = $("#dtblQuestions"+QuestionNo+" tr").length;
    varOptionNo = colCount;
    newRow = '';
    newRow += '<tr id="OptionRow'+QuestionNo+'" class="OptionRowClass'+QuestionNo+''+varOptionNo+'"><td><input type="hidden" class="form-control" value="1" required="" name="OptionRowCount[]"><input type="hidden"  value="0" name="Answer[]" class="answerClass" id="txtAnswer'+varOptionNo+''+QuestionNo+'"><h6>Option '+varOptionNo+'</h6><input class="form-control"  name="Options[]" required="" type="text"></td><td width="10%"><a class="btn btn-sm btn-primary" onclick="setAnswer('+varOptionNo+', '+QuestionNo+')" title="Set as Answer"><span class="fa fa-check"></span></a> <a class="btn btn-sm btn-danger" onclick="removeOption('+varOptionNo+', '+QuestionNo+')" title="Remove Option"><span class="fa fa-trash"></span></a></td></tr>';
    $('#dtblQuestions'+QuestionNo+'').append(newRow);
    varOptionNo++;
  }

  function removeOption(OptionNo, QuestionNo)
  {
    // If the removed option was the selected answer, reset varSelectedAnswer
    if($("#txtAnswer"+OptionNo+""+QuestionNo+"").val() == 1)
    {
      varSelectedAnswer = 0; 
    }
    $('.OptionRowClass'+QuestionNo+''+OptionNo+'').remove();
    // Check if any answer is still selected
    var anySelected = false;
    $(".answerClass").each(function() {
      if($(this).val() == 1) anySelected = true;
    });
    if(anySelected) {
      varSelectedAnswer = 1;
    }
  }

  function setAnswer(OptionNo, QuestionNo)
  {
    $("#dtblQuestions1 tr").css("background-color", "transparent")
    $(".OptionRowClass"+QuestionNo+""+OptionNo+"").css('background-color', '#A3FFBD');
    $(".answerClass").val(0);
    $("#txtAnswer"+OptionNo+""+QuestionNo+"").val(1);
    varSelectedAnswer = 1;
  }

  // Open Add Question Modal for a Category
  function addQuestion(categoryId)
  {
    $('#formAddQuestion')[0].reset();
    $('#formAddQuestion').attr('action', '<?php echo base_url(); ?>admin_controller/addCategoryQuestion/<?php echo $this->uri->segment(3); ?>/'+categoryId);
    varSelectedAnswer = 0; 
    // Reset all answerClass hidden inputs to 0
    $(".answerClass").val(0);
    $('#modalAddQuestion').modal('show');
  }

  $(function () {

  $("#formAddQuestion").on('submit', function (e) {
    e.preventDefault();
    if(typeof varSelectedAnswer !== 'undefined' && varSelectedAnswer == 0)
    {
      swal({
        title: 'Warning',
        text: 'Please select an answer for this question.',
        type: 'warning',
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary'
      });
      return false;
    }
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

  // For other forms (like Add Category), just show the confirm dialog
  $(".frminsert2").not("#formAddQuestion").on('submit', function (e) {
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
      "ajax": { url: '<?php echo base_url()."/admin_controller/getExamCategories/".$this->uri->segment(3); ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "Name" }
                    , { data: "Instructions" }
                    , { data: "Percentage" }
                    , { data: "TotalQuestions" }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        return "<span class='badge bg-"+row.Color+"'>"+row.StatusDescription+"</span>";
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        if(row.StatusId == 1){
                          return '<a href="<?php echo base_url() ?>home/categoryDetails/'+<?php echo $this->uri->segment(3); ?>+'/'+row.CategoryId+'" class="btn btn-sm btn-default" title="View Questions"><span class="fa fa-eye"></span></a> ' +
                                 '<a class="btn btn-sm btn-success" title="Add" onclick="addQuestion('+row.CategoryId+')"><span class="fa fa-plus"></span></a> ' +
                                 '<a onclick="updateRecord('+row.CategoryId+', 2)" class="btn btn-sm btn-danger" title="Deactivate"><span class="fa fa-window-close"></span></a>';
                        } else {
                          return '<a href="<?php echo base_url() ?>home/categoryDetails/'+<?php echo $this->uri->segment(3); ?>+'/'+row.CategoryId+'" class="btn btn-sm btn-default" title="View Exam"><span class="fa fa-eye"></span></a>';
                        }
                      }
                    },
      ],
      "order": [[0, "asc"]]
    });

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
                        return '<a href="<?php echo base_url(); ?>/home/download/1/'+row.ID+'" class="btn btn-sm btn-success" title="Download"><span class="fa fa-download"></span></a> <a onclick="updateRecord('+row.ID+', 1)" class="btn btn-sm btn-danger" title="Deactivate"><span class="fa fa-window-close"></span></a>';
                      }
                    },
      ],
      "order": [[0, "asc"]]
    });

  });
</script>