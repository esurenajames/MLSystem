<div class="content-wrapper">
  <section class="content-header">
    <?php if(in_array('5', $subModule)) { ?>
      <h1>
        Collection Management
      </h1>
      <ol class="breadcrumb">
        <li><a href="#" class="active"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="#"> Collection Management</a></li>
      </ol>

      </section>
        <div class="modal fade" id="modalReport">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Borrower Details</h4>
              </div>
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
            </div>
          </div>
        </div>

      <section class="content">
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
            <div class="col-md-12">
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
        </div>
      </section>
    <?php } else { ?>
      <br>
      <br>
      <div class="col-md-12">
        <div class="callout callout-danger">
          <h4>You have no access to this module!</h4>
          <p>Please contact your admin to request for access!</p>
        </div>
      </div>
    <?php } ?>
</div>

<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0
  </div>
  <strong>Copyright &copy; 2020 <a href="https://adminlte.io">GIA Tech.</a>.</strong> All rights
  reserved.
</footer>

<!-- <div class="loading" style="display: none">Loading&#8230;</div> -->
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
                    { data: "AmountPaid" }, 
                    { data: "PaymentDate" }, 
                    { data: "CollectedBy" }, 
                    {
                      data: "ApplicationId", "render": function (data, type, row) {
                        return '<a class="btn btn-sm btn-default" href="<?php echo base_url(); ?>home/loandetail/'+row.ApplicationId+'" title="View"><span class="fa fa-info-circle"></span></a>';
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