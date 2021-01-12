<div class="content-wrapper">

  <?php if(in_array('11', $subModule)) { ?>
  <section class="content-header">
    <h1>
      Loan Collection
    </h1>
    <ol class="breadcrumb">
      <li><a href="#" class="active"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li>Reports</li>
      <li>Loan Collection</a></li>
    </h1>
    </ol>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Generate Loan Collections</h3>
      </div>
      <div class="box-body">
        <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/generateReport/1" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Branch</label>
                    <select class="form-control select2" name="BranchId" style="width: 100%;">
                      <?php
                        echo $Branch;
                      ?>
                    </select>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label>Report Columns</label>
                    <select class="form-control select2" name="columnNames[]" style="width: 100%;" multiple="">
                      <option selected="">Loan Date</option>
                      <option selected="">Application No.</option>
                      <option selected="">Borrower Name</option>
                      <option selected="">Principal Per Collection</option>
                      <option selected="">Interest Per Collection</option>
                      <option selected="">Other Collections</option>
                      <option selected="">Amount Paid</option>
                      <option selected="">Change</option>
                      <option selected="">Repayment Date</option>
                      <option selected="">Penalty</option>
                      <option selected="">Collected By</option>
                      <option selected="">Collection Date</option>
                      <option selected="">Creation Date</option>
                    </select>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label>Report Name</label>
                  <input type="text" class="form-control" value="Loan Collections" name="reportName">
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label>Employee Name</label>
                  <select name="employeeReport[]" required="" multiple="" class="form-control select2" id="selectEmployeeReport" style="width: 100%" id="selectEmployee3">
                    <?php 
                      echo '<option value="All">All</option>';
                      foreach ($employee as $value) 
                      {
                        echo '<option value="'.$value['EmployeeNumber'].'">'.$value['Name'].'</option>';
                      }
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Date From</label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" placeholder="Date Collected" class="form-control" name="DateFrom" required="" id="dateFrom">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Date To</label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" placeholder="Date Collected" class="form-control" name="DateTo" required="" id="dateTo">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <label>Verified By</label>
                <select name="verifiedBy" required="" class="form-control"style="width: 100%"  id="selectEmployee">
                </select>
              </div>
              <div class="col-md-6">
                <label>Approved By</label>
                <select name="approvedBy" required="" class="form-control"style="width: 100%"  id="selectEmployee2">
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button class="btn btn-primary" type="submit">Submit</button>
          </div>
        </form>
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
  <strong>Copyright &copy; 2020 <a href="#">GIA Tech.</a>.</strong> All rights
  reserved.
</footer>

<div class="loading" style="display: none">Loading&#8230;</div>
<?php $this->load->view('includes/footer'); ?>

<script>
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
</script>

<script>

  function employeeSelect()
  {
    isSelected = 0;
    $('#selectEmployeeReport option:selected').each(function() {
      if($(this).val() == 'All')
      {
      }
    });
  }

  $('.select2').select2()

  $('#selectEmployee').select2({
    placeholder: 'Type an employee name or employee number to select.',
    dropdownCssClass : 'bigdrop',
      ajax: {
        url: '<?php echo base_url()?>admin_controller/getReportEmployees?>',
        dataType: 'json',
        delay: 250,
        processResults: function (data) 
        {
          return {
            results: data
          };
        },
        cache: true
      }
  });

  $('#selectEmployee2').select2({
    placeholder: 'Type an employee name or employee number to select.',
    dropdownCssClass : 'bigdrop',
      ajax: {
        url: '<?php echo base_url()?>admin_controller/getReportEmployees?>',
        dataType: 'json',
        delay: 250,
        processResults: function (data) 
        {
          return {
            results: data
          };
        },
        cache: true
      }
  });

  $('#dateFrom').daterangepicker({
      "startDate": moment().format('DD MMM YY'),
      "singleDatePicker": true,
      "showDropdowns": true,
      "timePicker": false,
      "linkedCalendars": false,
      "showCustomRangeLabel": false,
      "showCustomRangeLabel": false,
      // "maxDate": Start,
      "opens": "up",
      "locale": {
          format: 'DD MMM YYYY',
      },
  }, function(start, end, label){
    $('#dateTo').daterangepicker({
        "minDate": moment(start).format('DD MMM YY'),
        "singleDatePicker": true,
        "showDropdowns": true,
        "timePicker": false,
        "linkedCalendars": false,
        "showCustomRangeLabel": false,
        "showCustomRangeLabel": false,
        // "maxDate": Start,
        "opens": "up",
        "locale": {
            format: 'DD MMM YYYY',
        },
    }, function(start, end, label){
    });
  });

  $('#dateTo').daterangepicker({
      "startDate": moment().format('DD MMM YY'),
      "singleDatePicker": true,
      "showDropdowns": true,
      "timePicker": false,
      "linkedCalendars": false,
      "showCustomRangeLabel": false,
      "showCustomRangeLabel": false,
      // "maxDate": Start,
      "opens": "up",
      "locale": {
          format: 'DD MMM YYYY',
      },
  }, function(start, end, label){
  });

  $('#datepicker').daterangepicker({
        "startDate": moment().format('DD MMM YY hh:mm A'),
        "singleDatePicker": true,
        "showDropdowns": true,
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
</script>

