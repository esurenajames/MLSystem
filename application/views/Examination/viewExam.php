<style type="text/css">
  @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box
}

body {
    background-color: #333
}

.container {
    background-color: #555;
    color: #ddd;
    border-radius: 10px;
    padding: 20px;
    font-family: 'Montserrat', sans-serif;
    max-width: 700px
}

.container>p {
    font-size: 32px
}

.question {
    width: 75%
}

.options {
    position: relative;
    padding-left: 40px
}

#options label {
    display: block;
    margin-bottom: 15px;
    font-size: 14px;
    cursor: pointer
}

.options input {
    opacity: 0
}

.checkmark {
    position: absolute;
    top: -1px;
    left: 0;
    height: 25px;
    width: 25px;
    background-color: #555;
    border: 1px solid #ddd;
    border-radius: 50%
}

.options input:checked~.checkmark:after {
    display: block
}

.options .checkmark:after {
    content: "";
    width: 10px;
    height: 10px;
    display: block;
    background: white;
    position: absolute;
    top: 50%;
    left: 50%;
    border-radius: 50%;
    transform: translate(-50%, -50%) scale(0);
    transition: 300ms ease-in-out 0s
}

.options input[type="radio"]:checked~.checkmark {
    background: #21bf73;
    transition: 300ms ease-in-out 0s
}

.options input[type="radio"]:checked~.checkmark:after {
    transform: translate(-50%, -50%) scale(1)
}

.btn-primary {
    background-color: #555;
    color: #ddd;
    border: 1px solid #ddd
}

.btn-primary:hover {
    background-color: #21bf73;
    border: 1px solid #21bf73
}

.btn-success {
    padding: 5px 25px;
    background-color: #21bf73
}

@media(max-width:576px) {
    .question {
        width: 100%;
        word-spacing: 2px
    }
}
</style>
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">
              Exam Details for 
              <a href="<?php echo base_url() ?>home/facultyClassDetails/<?php echo (is_array($detail) && isset($detail['ClassId'])) ? $detail['ClassId'] : ''; ?>">
                <?php echo (is_array($detail) && isset($detail['ClassName'])) ? $detail['ClassName'] : 'N/A'; ?> - 
                <?php echo (is_array($detail) && isset($detail['SubjectCode'])) ? $detail['SubjectCode'] : 'N/A'; ?>
              </a>
            </h1>
          </div>
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
                    <h6><?php echo (is_array($detail) && isset($detail['Code'])) ? $detail['Code'] : 'N/A'; ?></h6>
                  </div>
                  <div class="col-md-3">
                    <label>Subject Name</label>
                    <h6><?php echo (is_array($detail) && isset($detail['SubjectName'])) ? $detail['SubjectName'] : 'N/A'; ?></h6>
                  </div>
                  <div class="col-md-3">
                    <label>Subject Description</label>
                    <h6><?php echo (is_array($detail) && isset($detail['SubjectDescription'])) ? $detail['SubjectDescription'] : 'N/A'; ?></h6>
                  </div>
                  <div class="col-md-3">
                    <label>Max no. of Students</label>
                    <h6><?php echo (is_array($detail) && isset($detail['MaxStudents'])) ? $detail['MaxStudents'] : 'N/A'; ?></h6>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="m-0">Details for Exam #<?php echo (is_array($detail) && isset($detail['ExamCode'])) ? $detail['ExamCode'] : 'N/A'; ?></h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3">
                    <h6>Correct Answer:</h6>
                    <label><?php print_r($correctAnswer) ?></label>
                  </div>
                  <div class="col-md-3">
                    <h6>Wrong Answer:</h6>
                    <label><?php print_r($wrongAnswer) ?></label>
                  </div>
                  <div class="col-md-3">
                    <h6>Total Questions:</h6>
                    <label class="lblTotalQuestions"></label>
                  </div>
                  <div class="col-md-3">
                    <h6>Total Score:</h6>
                    <label><?php print_r($correctAnswer) ?></label>/<label id="lblTotalQuestionsPercentage"></label>
                  </div>
                </div>
                <form>
                  <?php 
                    $rowNo = 1;
                    $isActive = '';
                    $rowOption = 1;
                    $rowQuestionNo = 1;
                    $isCorrect = 1;
                    foreach ($examCategory as $key => $value) 
                    {
                      $isActive = 'show';
                      echo '
                        <div id="accordion">
                          <div class="card card-primary">
                            <div class="card-header">
                              <h4 class="card-title w-100">
                                <a class="d-block w-100" data-toggle="collapse" href="#collapse'.$rowNo.'" aria-expanded="false"> '.$value['Name'].' - '.$value['Percentage'].'% <br><small>'.$value['Instructions'].'</small>
                                </a>
                              </h4>
                            </div>
                            <div id="collapse'.$rowNo.'" class="collapse '.$isActive.'" data-parent="#accordion" style="">
                              <div class="card-body">
                      ';

                      // Get all questions for this category
                      $questions = $this->admin_model->getCategoryQuestions($value['CategoryId']);
                      foreach ($questions as $q) {
                        // Get answer info for this question
                        $answerOption = $this->admin_model->getExamAnswers($q['QuestionId']);
                        if(is_array($answerOption) && ($answerOption['IsCorrect'] ?? 0) == 1)
                        {
                          $isCorrect = 'style="color:#08A133"';
                        }
                        else
                        {
                          $isCorrect = 'style="color:#D92323"';
                        }
                        echo '
                          <table class="table table-bordered">
                          <tbody>
                            <tr>
                              <td>
                                <div '.$isCorrect.' class="question ml-sm-12 pl-sm-12 pt-2">
                                  <div class="py-2 h6"><b>'.$rowQuestionNo.'. '.$q['Question'].'</b>
                                    <input type="hidden" id="isAnswered'.$q['QuestionId'].'" class="classAnsweredSubCategory" value="0">
                                    <input type="hidden" id="answerKey'.$q['QuestionId'].'" placeholder="Answer Key" value="" name="AnswerId[]">
                                    <input type="hidden" id="subquestionoptionId'.$q['QuestionId'].'" value="'.$q['QuestionId'].'" name="questionId[]">
                                  </div>
                                  <div class="ml-md-3 ml-sm-3 pl-md-12 pt-sm-0 pt-3" id="options">';
                        // Render options
                        $options = $this->admin_model->getCategoryQuestionOptions($q['QuestionId']);
                        foreach ($options as $opt) {
                          // Highlight correct/wrong answers if needed
                          $labelStyle = '';
                          if(is_array($answerOption) && isset($answerOption['CorrectAnswer']) && $answerOption['CorrectAnswer'] == $opt['OptionNo'] && ($answerOption['IsCorrect'] ?? 0) == 1) {
                            $labelStyle = 'style="color:#08A133"'; // correct
                          } else if(is_array($answerOption) && isset($answerOption['AnswerId']) && $answerOption['AnswerId'] == $opt['OptionNo']) {
                            $labelStyle = 'style="color:#D92323"'; // wrong
                          }
                          echo '<label '.$labelStyle.'>'.$opt['OptionName'].'</label>';
                          $rowOption++;
                        }
                        echo '        </div>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                          </table>
                        ';
                        $rowQuestionNo++;
                      }

                      echo '</div>
                            </div>
                          </div>
                        </div>  ';


                      $rowNo++;

                    }
                  ?>
                </form>
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
  var totalAnswers = 0;
  var totalUnAnswered = 0;
  var TotalQuestions = 0;

  function optionClick(id, OptionNo)
  {
    totalAnswers = 0;
    totalUnAnswered = 0;
    TotalQuestions = 0;
    $("#isAnswered"+id+"").val(1);
    $("#answerKey"+id+"").val(OptionNo);

    $(".classAnsweredSubCategory").each(function( index ) {
      if($(this).val() == 1)
      {
        totalAnswers = totalAnswers + 1;
      }
      else
      {
        totalUnAnswered = totalUnAnswered + 1;
      }
      TotalQuestions++;
    });

    $('.lblTotalQuestions').text(TotalQuestions);

    // Prevent division by zero
    var correctAnswer = Number('<?php print_r($correctAnswer) ?>');
    var totalPercentage = 0;
    var examResult = '';
    if (TotalQuestions > 0) {
      totalPercentage = (correctAnswer / TotalQuestions) * 100;
      totalPercentage = Math.round(totalPercentage * 100) / 100; // round to 2 decimals
      if(totalPercentage >= 70)
      {
        examResult = '<label style="color:#08A133">Passed</label>';
      }
      else
      {
        examResult = '<label style="color:#D92323">Failed</label>';
      }
      $('#lblTotalQuestionsPercentage').html(TotalQuestions + ' = ' + totalPercentage + "% (" + examResult + ")");
    } else {
      $('#lblTotalQuestionsPercentage').html('0 = 0% (No questions)');
    }
  }


  $(function () {
    optionClick(0)
  });
</script>