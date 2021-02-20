
<div class="content-wrapper">

  <?php if(in_array('53', $subModule)) { ?>
    <section class="content-header">
      <h1>
        Collateral Report
      </h1>
    </section>

    <section class="content">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Generate Collateral Report</h3>
        </div>
        <div class="box-body">
          <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/generateReport/12" method="post" enctype="multipart/form-data">
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
                    <label>Collateral Type</label>
                      <select class="form-control select2" id="selectExpenseType" name="expenseType[]" style="width: 100%;" multiple="">
                        <?php
                        foreach ($Collateral as $key => $value) 
                        {
                          echo '<option value="'.$value['CollateralTypeId'].'">'.$value['Name'].'</option>';
                        }
                        ?>
                      </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Report Name</label>
                    <input type="text" class="form-control" value="Collateral Report" name="reportName">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Date From</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" placeholder="Date Created From" class="form-control" name="DateFrom" required="" id="dateFrom">
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
                      <input type="text" placeholder="Date Created To" class="form-control" name="DateTo" required="" id="dateTo">
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
<!-- /.content-wrapper -->

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

  function expenseType(value)
  {
    isSelected = 0;
    $('#reportColumns option:selected').each(function() {
      if($(this).val() == 'Expense Type')
      {
        isSelected = isSelected + 1;
      }
    });

    if(isSelected > 0)
    {      
      document.getElementById("selectExpenseType").disabled = false;
    }
    else
    {
      document.getElementById("selectExpenseType").disabled = true;
    }
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

