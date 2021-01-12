
<div class="content-wrapper">
  <?php if(in_array('8', $subModule)) { ?>
    <section class="content-header">
      <h1>
        View All Loans
      </h1>
      <ol class="breadcrumb">
        <li><a href="#" class="active"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="#"></i>Loans</a></li>
        <li><a href="#"></i>View All Loans</a></li>
      </h1>
      </ol>
    </section>

    <section class="content">
      <div class="modal fade" id="modalFilter">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Filter</h4>
            </div>
            <div class="modal-body">
              <div class="row">
                <!-- <div class="col-md-12">
                  <div class="form-group">
                    <div class="form-group">
                      <label>Date Created <span class="text-red">*</span></label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control" name="DateCreated" required="" id="dateCreated">
                      </div>
                    </div>
                  </div>
                </div> -->
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" id="loanStatus" required="">
                      <option>All</option>
                      <?php 
                        echo $Status;
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Borrower Name</label>
                    <select class="form-control select2" style="width: 100%" id="borrowerId" required="">
                      <option>All</option>
                      <?php 
                        echo $borrowerList;
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Loan Type</label>
                    <select class="form-control select2" style="width: 100%" id="LoanId" required="">
                      <option>All</option>
                      <?php 
                        echo $LoanType;
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Branch</label>
                    <select class="form-control select2" style="width: 100%" id="BranchId" required="">
                      <option>All</option>
                      <?php 
                        echo $Branch;
                      ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <a onclick="filterPage()" class="btn btn-primary">Submit</a>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="modalImport2">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Import Borrower</h4>
            </div>
              <form role="form" id="upload_form3" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="txtHouseNo">Excel Attachment <span class="text-red">*</span></label>
                        <input type="file" id="form3UploadExcel" name="form3UploadExcel" accept=".xls, .xlsx" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Download Format <span class="text-red">*</span></label><br>
                        <a class="btn btn-sm btn-success" href="<?php echo base_url();?>/employeeUpload/BorrowerUpload.xlsx" title="Download">Download</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
          </div>
        </div>
      </div>
    	<!-- BORROWER DETAILS -->
  	    <div class="box">
  	      <div class="box-header with-border">
  	        <h3 class="box-title">List of Loans</h3>
  	      </div>
  		    <div class="box-body">
            <div class="pull-right">            
              <a data-toggle="modal" data-target="#modalFilter" class="btn btn-primary btn-md" >Filter</a>
              <a data-toggle="modal" data-target="#modalImport2" class="btn btn-primary btn-md" >Import</a>
            </div>
            <br>
            <br>
            <table id="dtblApproval" class="table table-bordered table-hover" style="width: 100%">
              <thead>
              <tr>
                <th>Reference No.</th>
                <th>Loan Type</th>
                <th>Borrower</th>
                <th>Principal</th>
                <th>Interest</th>
                <th>Last Payment</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
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

  function filterPage(){
    var url = '<?php echo base_url()."datatables_controller/filterLoans/"; ?>' + $('#loanStatus').val() + '/' + $('#borrowerId').val() + '/' + $('#LoanId').val() +  '/' + $('#BranchId').val();
    table.ajax.url(url).load();
    $('#modalFilter').modal('hide');
  }

  var TotalInterest = 0;
  table = $('#dtblApproval').DataTable({
    "pageLength": 10,
    "ajax": { url: '<?php echo base_url()."/datatables_controller/displayAllLoans/"; ?>', type: 'POST', "dataSrc": "" },
    "columns": [  { data: "TransactionNumber" }
      , { data: "LoanName" }
      , { data: "BorrowerName" }
      , { data: "PrincipalAmount" }
      , { data: "InterestRate" }
      , { data: "LastPayment" }
      , { data: "StatusId", "render": function (data, type, b) {
          if(b.IsApprovable == 1)
          {
            if(b.StatusId == 2)
            {
              return "<span class='badge bg-"+b.StatusColor+"'>"+b.StatusDescription+'</span>';
            }
            else
            {
              return "<span class='badge bg-"+b.StatusColor+"'>"+b.ProcessedApprovers+ '/' + b.TotalApprovers + ' in progress</span>';
            }
          }
          else
          {
            return "<span class='badge bg-"+b.StatusColor+"'>"+b.StatusDescription+'</span>';
          }
        }
      }
      , { data: "StatusId", "render": function (data, type, b) {
          if(b.StatusId == 4)
          {
            renewOption = ' <a class="btn btn-sm btn-success" href="<?php echo base_url(); ?>home/Renew/'+b.ApplicationId+'" title="Re-New"><span class="fa fa-refresh"></span></a>';
          }
          else
          {
            renewOption = '';
          }
            return '<a class="btn btn-sm btn-default" href="<?php echo base_url(); ?>home/loandetail/'+b.ApplicationId+'" title="View"><span class="fa fa-info-circle"></span></a>' + renewOption;
        }
      }
    ],
    // "aoColumnDefs": [{ "bVisible": false, "aTargets": [7] }],
    "order": [[6, "asc"], [0, "desc"]]
  });
</script>