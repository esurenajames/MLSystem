
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <h1 class="m-0">Schedule exam for <a href="<?php echo base_url() ?>home/facultyClassDetails/<?php print_r($detail['ClassId']) ?>"><?php print_r($detail['ClassName']) ?> - <?php print_r($detail['SubjectCode']) ?></a></h1>
          </div>
        </div>
      </div>
    </div>

      <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Schedule Exam</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url(); ?>admin_controller/addExamSchedule/<?php print_r($this->uri->segment(3)); ?>" class="frminsert2" method="post">
              <div class="modal-body">
                <div class="row">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="far fa-clock"></i></span>
                    </div>
                    <input type="text" class="form-control float-right" id="reservationtime">
                    <input type="hidden" name="startDate" class="form-control float-right" id="startDate">
                    <input type="hidden" name="endDate" class="form-control float-right" id="endDate">
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
                <h5 class="m-0">Schedule Exam for <?php print_r($detail['SubjectCode']) ?></h5>
              </div>
              <div class="card-body">
                <div class="float-right">
                  <a class="btn btn-md btn-primary" data-toggle="modal" data-target="#modal-default">Schedule Exam</a>
                </div>
                <br>
                <br>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Exam Date</th>
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
    var url = '<?php echo base_url()."/admin_controller/getExamSchedules/"; ?><?php print_r($this->uri->segment(3)); ?>';
    UserTable.ajax.url(url).load();
  }

  function clickToDeactivate(Id)
  {
    swal({
      title: 'Confirm',
      text: 'Are you sure you want to cancel scheduled exam?',
      type: 'info',
      showCancelButton: true,
      buttonsStyling: false,
      confirmButtonClass: 'btn btn-success',
      confirmButtonText: 'Confirm',
      cancelButtonClass: 'btn btn-secondary'
    }).then(function(){
      $.ajax({                
        url: "<?php echo base_url();?>" + "/admin_controller/deactivateExamSchedule",
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

  $(function () {
    // $('#reservationtime').daterangepicker({
    //   timePicker: true,
    //   timePickerIncrement: 30,
    //   locale: {
    //     format: 'MM/DD/YYYY hh:mm A'
    //   }
    // })

    $('#reservationtime').daterangepicker({
      "startDate": moment().format('MM/DD/YYYY hh:mm A'),
      "minDate": moment().format('MM/DD/YYYY hh:mm A'),
      "showDropdowns": true,
      "timePicker": true,
      "showCustomRangeLabel": false,
      // "maxDate": Start,
      "opens": "up",
      "locale": {
        format: 'MM/DD/YYYY hh:mm A'
      },
      }, function(start, end, label){
        $("#startDate").val(start.format('YYYY-MM-DD HH:MM:SS'))
        $("#endDate").val(end.format('YYYY-MM-DD HH:MM:SS'))
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
    $('#selectStudent').select2({
      maximumSelectionLength: <?php print_r($detail['MaxStudents']) ?>
    });

    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/admin_controller/getExamSchedules/"; ?><?php print_r($this->uri->segment(3)); ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "ExamSchedule" }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        return "<span class='badge bg-"+row.Color+"'>"+row.StatusDescription+"</span>";
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        if(row.ForExam == 'For exam')
                        {
                          // return '';
                          return '<a onclick="clickToDeactivate('+row.ScheduleId+')" class="btn btn-sm btn-danger" title="Cancel Schedule"><span class="fa fa-window-close"></span></a>';
                        }
                        else
                        {
                          if(row.StatusId == 2)
                          {
                            return '-';
                          }
                          else
                          {
                            return '<a onclick="clickToDeactivate('+row.ScheduleId+')" class="btn btn-sm btn-danger" title="Cancel Schedule"><span class="fa fa-window-close"></span></a>';
                          }
                        }
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[0, "asc"]]
    });

  });
</script>