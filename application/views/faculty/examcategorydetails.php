<div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Questions for Category: <a href="<?php echo base_url() ?>home/examDetails/<?php print_r($detail['ExamId']) ?>/<?php print_r($detail['ClassSubjectId']) ?>"><?php print_r($detail['Category']); ?> (<?php print_r($detail['Percentage']); ?>%)</a></h1>
          </div>
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

    <!-- Edit Question Modal -->
    <div class="modal fade" id="modalEditQuestion">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Question</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="formEditQuestion" action="" method="post">
            <div class="modal-body" id="editQuestionBody">
              <!-- Content loaded by JS -->
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success"><span class="fa fa-save"></span> Save</button>
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
                <h5 class="m-0">Questions List</h5>
                <div class="float-right">
                  <a class="btn btn-md btn-primary" onclick="addQuestion(<?php echo $detail['CategoryId']; ?>)"><span class="fa fa-plus"></span> Add Question</a>
                </div>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Question</th>
                    <th>Options</th>
                    <th>Correct Answer</th>
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

  var rowNo = 2;
  var varSelectedAnswer = 0;

  function refreshPage(){
    var url = '<?php echo base_url()."admin_controller/getCategoryQuestionsWithOptions/".$detail['CategoryId']; ?>';
    UserTable.ajax.url(url).load();
  }

  function addQuestion(categoryId)
  {
    $('#formAddQuestion')[0].reset();
    $('#formAddQuestion').attr('action', '<?php echo base_url(); ?>admin_controller/addCategoryQuestion/<?php echo $detail['ExamId']; ?>/'+categoryId);
    $('#modalAddQuestion').modal('show');
  }

  function deleteQuestion(questionId)
  {
    swal({
      title: 'Confirm',
      text: 'Are you sure you want to delete this question?',
      type: 'warning',
      showCancelButton: true,
      buttonsStyling: false,
      confirmButtonClass: 'btn btn-danger',
      confirmButtonText: 'Delete',
      cancelButtonClass: 'btn btn-secondary'
    }).then(function(result){
      if(result.value !== false) {
        $.ajax({
          url: "<?php echo base_url(); ?>admin_controller/removeCategoryQuestion",
          method: "POST",
          data: { Id: questionId },
          dataType: "json",
          success: function(response) {
            if(response.status === 'OK' || response === 'OK!') {
              swal({
                title: 'Deleted!',
                text: 'Question has been deleted.',
                type: 'success',
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary'
              });
              refreshPage();
            } else {
              swal({
                title: 'Error!',
                text: 'Failed to delete question.',
                type: 'error',
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary'
              });
            }
          },
          error: function() {
            swal({
              title: 'Error!',
              text: 'Failed to delete question.',
              type: 'error',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });
          }
        });
      }
    });
  }

  function editQuestion(questionId)
  {
    // Load question and options via AJAX
    $.ajax({
      url: "<?php echo base_url(); ?>admin_controller/getQuestionOptions",
      method: "POST",
      data: { Id: questionId },
      dataType: "json",
      success: function(response) {
        // response: { question, options: [{OptionNo, OptionName}], correct }
        var html = '';
        html += '<input type="hidden" name="QuestionId" value="'+questionId+'">';
        html += '<div class="form-group">';
        html += '<label>Question</label>';
        html += '<input type="text" class="form-control" name="Question" value="'+response.question+'" required>';
        html += '</div>';
        html += '<label>Options</label>';
        html += '<div class="form-group">';
        response.options.forEach(function(opt, idx){
          var checked = (opt.OptionNo == response.correct) ? 'checked' : '';
          var letter = String.fromCharCode(96 + parseInt(opt.OptionNo));
          html += '<div class="input-group mb-2">';
          html += '<div class="input-group-prepend"><span class="input-group-text">'+letter+'.</span></div>';
          html += '<input type="text" class="form-control" name="Options['+opt.OptionNo+']" value="'+opt.OptionName+'" required>';
          html += '<div class="input-group-append">';
          html += '<div class="input-group-text">';
          html += '<input type="radio" name="CorrectAnswer" value="'+opt.OptionNo+'" '+checked+' title="Set as correct">';
          html += '</div></div>';
          html += '</div>';
        });
        html += '</div>';
        $('#editQuestionBody').html(html);
        $('#modalEditQuestion').modal('show');
      }
    });
  }

  $(function () {

    // Handle edit question submit
    $('#formEditQuestion').on('submit', function(e){
      e.preventDefault();
      $.ajax({
        url: "<?php echo base_url(); ?>admin_controller/updateCategoryQuestion",
        method: "POST",
        data: $(this).serialize(),
        dataType: "json",
        success: function(response){
          if(response.status === 'OK') {
            swal({
              title: 'Updated!',
              text: 'Question updated successfully.',
              type: 'success',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });
            $('#modalEditQuestion').modal('hide');
            refreshPage();
          } else {
            swal({
              title: 'Error!',
              text: 'Failed to update question.',
              type: 'error',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });
          }
        },
        error: function(){
          swal({
            title: 'Error!',
            text: 'Failed to update question.',
            type: 'error',
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary'
          });
        }
      });
    });

    $(".frminsert2").on('submit', function (e) {
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

    $('.select2').select2();

    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."admin_controller/getCategoryQuestionsWithOptions/".$detail['CategoryId']; ?>', type: 'POST', "dataSrc": "" },
      "columns": [
        { data: "Question" },
        { data: "Options" },
        { data: "CorrectAnswer" },
        { 
          data: "QuestionId", 
          "render": function(data, type, row){
            return `
              <a href="#" class="btn btn-danger btn-sm" onclick="deleteQuestion(${data})" title="Delete"><span class="fa fa-trash"></span></a>
            `;
          }
        }
      ],
      "order": [[0, "asc"]]
    });

  });

  // Option management for add modal (unchanged)
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
    if($("#txtAnswer"+OptionNo+""+QuestionNo+"").val() == 1)
    {
      varSelectedAnswer = 0; 
    }
    else
    {
      varSelectedAnswer = 1;
    }
    $('.OptionRowClass'+QuestionNo+''+OptionNo+'').remove();
  }

  function setAnswer(OptionNo, QuestionNo)
  {
    $("#dtblQuestions1 tr").css("background-color", "transparent")
    $(".OptionRowClass"+QuestionNo+""+OptionNo+"").css('background-color', '#A3FFBD');
    $(".answerClass").val(0);
    $("#txtAnswer"+OptionNo+""+QuestionNo+"").val(1);
    varSelectedAnswer = 1;
  }

</script>