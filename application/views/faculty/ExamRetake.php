
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Approval List for Retaking of Exams</h1>
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
                <h5 class="m-0"></h5>
              </div>
              <div class="card-body">
                <br>
                <br>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th width="15%">Subject Code</th>
                    <th>Subject Name</th>
                    <th>Student</th>
                    <th>Class Name</th>
                    <th>Class Description</th>
                    <th>Previous Score</th>
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
    var url = '<?php echo base_url()."admin_controller/getExamsApproval/"; ?>';
    UserTable.ajax.url(url).load();
  }

  function clickProcessRetake(PreviousExamId, isApproved)
  {
    var Text = '';
    if(isApproved == 1)
    {
      Text = 'Are you sure you want to approve this request for re-taking of exam?';
    }
    else
    {
      Text = 'Are you sure you want to disapprove this request for re-taking of exam?';
    }

    swal({
      title: 'Confirm',
      text: Text,
      type: 'info',
      showCancelButton: true,
      buttonsStyling: false,
      confirmButtonClass: 'btn btn-success',
      confirmButtonText: 'Confirm',
      cancelButtonClass: 'btn btn-secondary'
    }).then(function(){
      $.ajax({                
        url: "<?php echo base_url();?>" + "/admin_controller/processRetakingofExam",
        method: "POST",
        async: false,
        data:   {
                  PreviousExamId : PreviousExamId
                  , isApproved : isApproved
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


    var totalPercentage = 0;
    var finalPrediction = 0;
    var examResult = '';
    var retakeExam = '';
    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/admin_controller/getExamsApproval/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "SubjectCode" }
                    , { data: "Name" }
                    , { data: "Student" }
                    , { data: "ClassName" }
                    , { data: "ClassDescription" }
                    , {
                      data: "PreviousExamId", "render": function (data, type, row) {
                        if(row.totalQuestions != 0)
                        {
                          totalPercentage = (row.correctAnswer/row.totalQuestions)*100;
                          finalPrediction = 0;

                          finalPrediction = parseFloat(totalPercentage);


                          if(finalPrediction >= 70)
                          {
                            examResult = '<label style="color:#08A133">Passed</label>';
                          }
                          else
                          {
                            examResult = '<label style="color:#D92323">Failed</label>';
                          }
                          return finalPrediction + '%';
                        }
                        else
                        {
                          totalPercentage = 0;
                          finalPrediction = 0;

                          finalPrediction = totalPercentage;


                          if(finalPrediction >= 70)
                          {
                            examResult = '<label style="color:#08A133">Passed</label>';
                          }
                          else
                          {
                            examResult = '<label style="color:#D92323">Failed</label>';
                          }
                          return finalPrediction + '%';
                        }
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        return "<span class='badge bg-"+row.Color+"'>"+row.StatusDescription+"</span>";
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        return '<a href="<?php echo base_url() ?>home/ViewExam/'+row.PreviousExamId+'" class="btn btn-sm btn-default" title="View Exam"><span class="fa fa-eye"></span></a> <a onclick="clickProcessRetake('+row.PreviousExamId+', 1)" class="btn btn-sm btn-success" title="Approve Retake"><span class="fa fa-check"></span></a> <a onclick="clickProcessRetake('+row.PreviousExamId+', 2)" class="btn btn-sm btn-danger" title="Disapprove Retake"><span class="fa fa-window-close"></span></a>';
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[0, "asc"]]
    });

  });
</script>