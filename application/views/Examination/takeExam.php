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
            <h1 class="m-0">Exam Details for <a href="<?php echo base_url() ?>home/facultyClassDetails/<?php print_r($detail['ClassId']) ?>"><?php print_r($detail['ClassName']) ?> - <?php print_r($detail['SubjectCode']) ?></a></h1>
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
                <h5 class="m-0">Details for Exam #<?php print_r($detail['ExamCode']); ?></h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <h6>Answered:</h6>
                    <label id="lblAnswered"></label>
                  </div>
                  <div class="col-md-4">
                    <h6>Unanswered:</h6>
                    <label id="lblUnanswered"></label>
                  </div>
                  <div class="col-md-4">
                    <h6>Total Questions:</h6>
                    <label  id="lblTotalQuestions"></label>
                  </div>
                </div>
                <form action="<?php echo base_url(); ?>admin_controller/insertExamAnswers/<?php print_r($this->uri->segment(3)); ?>" class="frminsert2" method="post">
                  <?php 
                    $rowNo = 1;
                    $isActive = '';
                    $rowOption = 1;
                    $rowQuestionNo = 1;
                    foreach ($examCategory as $key => $value) 
                    {
                      if($rowNo == 1)
                      {
                        $isActive = 'show';
                      }
                      else
                      {
                        $isActive = '';
                      }
                      echo '
                        <div id="accordion">
                          <div class="card card-primary">
                            <div class="card-header">
                              <h4 class="card-title w-100">
                                <a class="d-block w-100" data-toggle="collapse" href="#collapse'.$rowNo.'" aria-expanded="false"> '.$value['Name'].'
                                </a>
                              </h4>
                            </div>
                            <div id="collapse'.$rowNo.'" class="collapse '.$isActive.'" data-parent="#accordion" style="">
                              <div class="card-body">
                      ';

                      $subCategories = $this->admin_model->getExamSubCategories($value['CategoryId']);
                      foreach ($subCategories as $key => $subcategory) 
                      {
                        echo '
                          <table class="table table-bordered">
                          <tbody>
                            <tr>
                              <td><label>'.strtoupper($subcategory['SubCategory']) .'</label></td>
                            </tr>
                        ';
                        $subQuestions = $this->admin_model->getExamSubCategoryQuestions($subcategory['SubCategoryId']);
                        foreach ($subQuestions as $key => $subquestion) 
                        {
                          echo '
                              <tr>
                                <td>
                                  <div class="question ml-sm-12 pl-sm-12 pt-2">
                                      <div class="py-2 h6"><b>'.$rowQuestionNo.'. '.$subquestion['Question'] .'</b> <input type="hidden" id="isAnswered'.$subquestion['ID'].'" class="classAnsweredSubCategory" value="0"> <input type="hidden" id="answerKey'.$subquestion['ID'].'" placeholder="Answer Key" value="" name="AnswerId[]"> <input type="hidden" id="subquestionoptionId'.$subquestion['ID'].'" value="'.$subquestion['ID'].'" name="questionId[]"></div>
                                      <div class="ml-md-3 ml-sm-3 pl-md-12 pt-sm-0 pt-3" id="options"> 
                          ';

                          $subOptions = $this->admin_model->getExamSubCategoryOptions($subquestion['ID']);
                          foreach ($subOptions as $key => $suboptions) 
                          {
                            echo '<label class="options">
                                    '.$suboptions['OptionName'].' 
                                    <input type="radio" name="radio'.$suboptions['subquestionId'].'" onclick="optionClick('.$suboptions['subquestionId'].', '.$suboptions['OptionNo'].')"> 
                                    <span class="checkmark"></span> 
                                  </label> 
                                  
                            ';
                            $rowOption ++;
                          }

                          echo'   </div>
                                </td>
                              </tr>
                          ';
                          $rowQuestionNo++;
                        }
                        echo '
                            </tbody>
                          </table>
                        ';
                      }

                      echo '</div>
                            </div>
                          </div>
                        </div>  ';


                      $rowNo++;

                    }
                  ?>

                  <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-primary">Submit Answers</button>
                  </div>
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

    $('#lblAnswered').text(totalAnswers);
    $('#lblUnanswered').text(totalUnAnswered);
    $('#lblTotalQuestions').text(TotalQuestions);
  }
  $(function () {

    optionClick(0)

    $(".frminsert2").on('submit', function (e) {
      e.preventDefault();
      if(totalUnAnswered == 0 && totalAnswers > 0)
      {
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
          text: 'You still have '+totalUnAnswered+' unanswered questions! Please make sure all questions are answered!',
          type: 'warning',
          buttonsStyling: false,
          confirmButtonClass: 'btn btn-primary'
        });
      }
    });


  });
</script>