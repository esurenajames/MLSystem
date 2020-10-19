<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Collection Management
    </h1>
    <ol class="breadcrumb">
      <li><a href="#" class="active"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="#"> Collection Management</a></li>
    </ol>

  </section>
    <!-- Main content -->
    <div class="modal fade" id="modalReport">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Borrower Details</h4>
          </div>

          <form autocomplete="off" action="<?php echo base_url(); ?>employee_controller/employeeProcessing/9/<?php print_r($detail['BorrowerId'])?>" id="frmInsert6" method="post" enctype="multipart/form-data">
            <div class="modal-body">
              <div class="col-md-6">
                <div class="form-group">
                    <div class="form-group">
                      <label>Date from</label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control" name="DateFrom" required="" id="DateFrom">
                      </div>
                      <!-- /.input group -->
                    </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                    <div class="form-group">
                      <label>Date to</label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control" name="DateTo" required="" id="DateTo">
                      </div>
                      <!-- /.input group -->
                    </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <section class="content">
      <?php foreach ($access as $rows){ if($rows['RoleId'] == 3 || $rows['RoleId'] == 4) /*employee | top management*/ { ?>
      <?php } if($rows['RoleId'] == 1) /*admin*/ { ?>
        <!-- Default box -->
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Collection</h3>
          </div>
          <div class="box-body">
            <div class="pull-right">
              <a class="btn btn-primary" data-toggle="modal" data-target="#modalReport">Generate Report</a>
            </div>
            <br>
            <br>
            <table id="example1" class="table table-bordered table-hover">
              <thead>
              <tr>
                <th>Application #</th>
                <th>Borrower Name</th>
                <th>Loan Amount</th>
                <th>Amount Paid</th>
                <th>Collection Date</th>
                <th>Collected By</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
        <!-- /.box -->
      <?php } } ?> 
    </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0
  </div>
  <strong>Copyright &copy; 2020 <a href="https://adminlte.io">GIA Tech.</a>.</strong> All rights
  reserved.
</footer>

<div class="loading" style="display: none">Loading&#8230;</div>
<?php $this->load->view('includes/footer'); ?>

<script>
  var varStatus = 0;
  var varNewPassword = 0;

  if("<?php print_r($this->session->flashdata('alertTitle')) ?>" != '')
  {
    swal({
      title: '<?php print_r($this->session->flashdata('alertTitle')) ?>',
      text: '<?php print_r($this->session->flashdata('alertText')) ?>',
      type: '<?php print_r($this->session->flashdata('alertType')) ?>',
      buttonsStyling: false,
      confirmButtonClass: 'btn btn-primary'
    });
  }

  $(function () {
    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/datatables_controller/Collection/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "TransactionNumber" },
                    { data: "BorrowerName" },
                    { data: "LoanAmount" },
                    { data: "PaymentAmount" }, 
                    { data: "PaymentDate" }, 
                    { data: "CollectedBy" }, 
                    {
                      data: "ApplicationId", "render": function (data, type, row) {
                        return '<a class="btn btn-default" href="<?php echo base_url(); ?>home/loandetail/'+row.ApplicationId+'" title="View"><span class="fa fa-info-circle"></span></a>';
                      }
                    },
                    { data: "DateCollected" }, 
      ],
      "aoColumnDefs": [{ "bVisible": false, "aTargets": [7] }],
      "order": [[7, "desc"]]
    });

    $("#frmInsert").on('submit', function (e) {
      if(varNewPassword = 1 && varStatus == 1 && $('#txtNewPassword').val() == $('#txtConfirmPassword').val() && $('#txtOldPassword').val() != $('#txtNewPassword').val())
      {
        e.preventDefault(); 
        swal({
          title: 'Confirm',
          text: 'Are you sure you sure with this password?',
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
        alert('please make sure your new password is not equal to your old password!')
        e.preventDefault();
      }
    });

    $('#DateFrom').daterangepicker({
        "startDate": moment().format('DD MMM YY hh:mm A'),
        "singleDatePicker": true,
        "timePicker": false,
        "linkedCalendars": false,
        "showCustomRangeLabel": false,
        // "maxDate": Start,
        "opens": "up",
        "locale": {
            format: 'DD MMM YYYY',
        },
    }, function(start, end, label){
    });

    $('#DateTo').daterangepicker({
        "startDate": moment().format('DD MMM YY hh:mm A'),
        "singleDatePicker": true,
        "timePicker": false,
        "linkedCalendars": false,
        "showCustomRangeLabel": false,
        // "maxDate": Start,
        "opens": "up",
        "locale": {
            format: 'DD MMM YYYY',
        },
    }, function(start, end, label){
    });
  })
</script>