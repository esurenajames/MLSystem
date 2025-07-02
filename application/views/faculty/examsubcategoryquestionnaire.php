
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Exam Details <a href="<?php echo base_url() ?>home/facultyClassDetails/<?php print_r($detail['ClassId']) ?>"><?php print_r($detail['ClassName']) ?> - <?php print_r($detail['SubjectCode']) ?></a></h1>
          </div>
        </div>
      </div>
    </div>

      <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Question</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url(); ?>admin_controller/addSubCategoryQuestion/<?php print_r($this->uri->segment(3)); ?>/<?php print_r($this->uri->segment(4)); ?>/<?php print_r($this->uri->segment(5)); ?>" class="frminsert2" method="post">
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

      <div class="modal fade" id="modalView">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Question</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url(); ?>admin_controller/addSubCategoryQuestion/<?php print_r($this->uri->segment(3)); ?>/<?php print_r($this->uri->segment(4)); ?>/<?php print_r($this->uri->segment(5)); ?>" class="frminsert2" method="post">
              <div class="modal-body">
                <div class="col-lg-12" id="example2">
                  <input type="hidden" class="form-control" value="1" required="" name="QuestionRow[]">
                  <table id="dtblQuestions2" class="table table-bordered">
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                <h5 class="m-0">Questions for sub-category <strong><a href="<?php echo base_url() ?>home/categoryDetails/<?php print_r($detail['ExamId']) ?>/<?php print_r($detail['CategoryId']) ?>"><?php print_r($detail['SubCategory']); ?></a></strong> for category <strong><a href="<?php echo base_url() ?>home/examDetails/<?php print_r($detail['ExamId']) ?>/<?php print_r($detail['ClassSubjectId']) ?>"><?php print_r($detail['Category']); ?> (<?php print_r($detail['Percentage']); ?>%)</a></strong> in exam #<?php print_r($detail['ExamCode']); ?></h5>
              </div>
              <div class="card-body">
                <div class="float-right">
                  <a class="btn btn-md btn-primary float-right"  data-toggle="modal" data-target="#modal-default">Add Question</a>
                </div>
                <br>
                <br>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Total Options</th>
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

  var varQuestionNo = 1;
  var optionNo = 1;
  var varOptionNo = 1;
  var varSelectedAnswer = 0;
  function refreshPage(){
    var url = '<?php echo base_url()."/admin_controller/getExamQuestions/". $this->uri->segment(5); ?>';
    UserTable.ajax.url(url).load();
  }

  function addOption(QuestionNo)
  {
    var colCount = $("#dtblQuestions"+QuestionNo+" tr").length;
    varOptionNo = colCount;
    newRow = '';
    newRow += '<tr id="OptionRow'+QuestionNo+'" class="OptionRowClass'+QuestionNo+''+varOptionNo+'"><td><input type="hidden" class="form-control" value="1" required="" name="OptionRowCount[]"><input type="hidden"  value="0" name="Answer[]" class="answerClass" id="txtAnswer'+varOptionNo+''+QuestionNo+'"><h6>Option '+varOptionNo+'</h6><input class="form-control"  name="Options[]" required="" type="text"></td><td width="10%"><a class="btn btn-sm btn-primary" onclick="setAnswer('+varOptionNo+', '+QuestionNo+')" title="Set as Answer"><span class="fa fa-check"></span></a> <a class="btn btn-sm btn-danger" onclick="removeOption('+varOptionNo+', '+QuestionNo+')" title="Remove Option"><span class="fa fa-trash"></span></a></td></tr>';
    $('#dtblQuestions'+QuestionNo+'').append(newRow);
    varOptionNo++;
  }

  function AddQuestion()
  {
    newRow = '';
    newRow += '<div class="card" id="cardRow'+varQuestionNo+'">';
    newRow += '<div class="card-header">';
    newRow += '<h5 class="m-0">Question '+varQuestionNo+' <a class="btn btn-sm btn-danger float-right" onclick="removeQuestion('+varQuestionNo+')" title="Remove Question">Remove Question</a></h5>';
    newRow += '</div>';
    newRow += '<div class="card-body"><input type="hidden" name="Answer[]" value="0" id="txtAnswer'+varQuestionNo+'"><input type="hidden" class="form-control" value="'+varQuestionNo+'" required="" name="QuestionRow[]">';
    newRow += '<table id="dtblQuestions'+varQuestionNo+'" class="table table-bordered"><tbody><tr><td><input type="text" class="form-control" required placeholder="Untitled Question" name="Question1[]"></td><td width="10%"><a class="btn btn-sm btn-success" onclick="addOption('+varQuestionNo+')" title="Add Option"><span class="fa fa-plus-square"></span></a></td></tr><tr id="OptionRow'+varQuestionNo+'"  class="OptionRowClass'+varQuestionNo+'1"><td><h6>Option 1</h6><input class="form-control"  name="Options[]" required="" type="text"></td><td width="10%"><a class="btn btn-sm btn-primary" onclick="setAnswer(1, '+varQuestionNo+')" title="Set as Answer"><span class="fa fa-check"></span></a></td></tr></tbody></table>';
    newRow += '</div>';
    newRow += '</div>';
    $('#example2').append(newRow);
    varQuestionNo++;
    varOptionNo = 1;
  }

  function removeQuestion(id)
  {
    $('#cardRow'+id+'').remove();
    varQuestionNo--;
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

  function updateRecord(Id)
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
        url: "<?php echo base_url();?>" + "/admin_controller/removeQuestions",
        method: "POST",
        async: false,
        data:   {
                  Id : Id
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

  function editOptions(Id, Question)
  {
    varQuestionNo = 1;
    optionNo = 1;
    varOptionNo = 1;
    var table = $("#dtblQuestions2 tbody");
    $.ajax({
      url: "<?php echo base_url()?>/admin_controller/getQuestionOptions",
      type: "POST",
      async: false,
      dataType: "JSON",
      data:   {
        Id : Id
      },  
      beforeSend: function(){
          $('.loading').show();
      },
      success: function(data)
      {
        table.empty();
        table.append('<tr><td>'+data[0]['Question']+'</td></tr>');
        $.each(data, function (a, b) {
          table.append('<tr id="OptionRow'+b.QuestionNo+'" class="OptionRowClass'+b.QuestionNo+''+b.OptionNo+'"><td><h6>Option '+b.OptionNo+'</h6><label>'+b.OptionName+'</label></td></tr>');


          if(b.Answer == b.OptionNo)
          {
            setAnswer(b.OptionNo, b.Answer)
          }
          varOptionNo++;
        });
      },
      error: function()
      {
        setTimeout(function() {
          swal({
            title: 'Warning!',
            text: 'Something went wrong, please contact the administrator or refresh page!',
            type: 'warning',
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary'
          });
          // location.reload();
        }, 2000);
      }
    });
  }

  $(function () {
    $(".frminsert2").on('submit', function (e) {
      e.preventDefault(); 
      if(varSelectedAnswer == 1)
      {
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
      }
      else
      {
        swal({
          title: 'Warning',
          text: 'Please select an answer for this question.',
          type: 'warning',
          buttonsStyling: false,
          confirmButtonClass: 'btn btn-primary'
        });
      }
    });


    $('.select2').select2();
    $('#selectStudent').select2({
      maximumSelectionLength: <?php print_r($detail['MaxStudents']) ?>
    })

    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/admin_controller/getExamQuestions/". $this->uri->segment(5); ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "Question" }
                    , { data: "OptionName" }
                    , { data: "TotalOptions" }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        return '<a data-toggle="modal" data-target="#modalView" class="btn btn-default" onclick="editOptions('+row.Id+')" title="View Options"><span class="fa fa-eye"></span></a> <a class="btn btn-danger" title="Remove"  onclick="updateRecord('+row.Id+')"><span class="fa fa-window-close"></span></a>';
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[0, "asc"]]
    });

  });
</script>