
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Loans Extended Report
    </h1>
  </section>

    <!-- Main content -->
    <section class="content">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Generate Loans Extended Report</h3>
        </div>
        <div class="box-body">
          <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/generateReport/5" method="post" enctype="multipart/form-data" id="generateReport">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <label>Year From</label>
                  <select class="form-control" id="selectYearFrom" name="YearFrom" required="">
                    <?php 
                      foreach ($Year as $value) 
                      {
                        $selected = (date("Y") == $value['Year']) ? 'selected' : '';
                        echo '<option '.$selected.'>'.$value['Year'].'</option>';
                      }
                    ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <label>Year To</label>
                  <select class="form-control" id="selectYearTo" name="YearTo" required="">
                    <?php 
                      foreach ($Year as $value) 
                      {
                        $selected = (date("Y") == $value['Year']) ? 'selected' : '';
                        echo '<option '.$selected.'>'.$value['Year'].'</option>';
                      }
                    ?>
                  </select>
                </div>
                <!-- <div class="col-md-6">
                  <label>Verified By</label>
                  <select name="verifiedBy" required="" class="form-control"style="width: 100%"  id="selectEmployee">
                  </select>
                </div>
                <div class="col-md-6">
                  <label>Approved By</label>
                  <select name="approvedBy" required="" class="form-control"style="width: 100%"  id="selectEmployee2">
                  </select>
                </div> -->
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
  <!-- /.content -->
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


  $('#generateReport').on('submit', function(event){
    event.preventDefault();
    if($('#selectYearFrom').val() <= $('#selectYearTo').val())
    {
      swal({
        title: 'Confirm',
        text: 'Are you sure you want to generate report?',
        type: 'info',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-success',
        confirmButtonText: 'Confirm',
        cancelButtonClass: 'btn btn-secondary'
      }).then(function(){
        event.currentTarget.submit();
      });
    }
    else
    {
      swal({
        title: 'Warning!',
        text: 'Please make sure that year from is greater than year to!',
        type: 'warning',
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary'
      });
    }
  });

</script>

