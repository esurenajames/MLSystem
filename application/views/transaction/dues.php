
<div class="content-wrapper">
  <?php if(in_array('8', $subModule)) { ?>
    <section class="content-header">
      <h1>
        Generate Dues
      </h1>
    </section>

    <section class="content">
      <!-- BORROWER DETAILS -->
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Generate Due Report</h3>
          </div>
          <div class="box-body">
            <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/generateReport/11" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Borrower Name</label>
                    <select class="form-control select2" style="width: 100%" name="borrowerId" id="borrowerId" required="">
                      <option>All</option>
                      <?php 
                        echo $borrowerList;
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Loan Type</label>
                    <select class="form-control select2" style="width: 100%" name="LoanId" id="LoanId" required="">
                      <option>All</option>
                      <?php 
                        echo $LoanType;
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Branch</label>
                    <select class="form-control select2" style="width: 100%" name="BranchId" id="BranchId" required="">
                      <option>All</option>
                      <?php 
                        echo $Branch;
                      ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Aging From</label>
                    <input type="number" min="0" placeholder="0" value="0" max="365" name="AgeFrom" id="txtAgeFrom" class="form-control">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Aging To</label>
                    <input type="number" min="0" placeholder="0" value="0" max="365" name="AgeTo" id="txtAgeTo" class="form-control">
                  </div>
                </div>
              </div>
              <div class="pull-right">            
                <button class="btn btn-primary" type="submit">Submit</button>
              </div>
            </form>
            <br>
            <br>
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

<!-- <div class="loading" style="display: none">Loading&#8230;</div> -->
<?php $this->load->view('includes/footer'); ?>
<script type="text/javascript">
  $('.select2').select2();
</script>