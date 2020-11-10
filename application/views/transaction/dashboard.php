
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
    	<!-- BORROWER DETAILS -->
  	    <div class="box">
  	      <div class="box-header with-border">
  	        <h3 class="box-title">List of Loans</h3>
  	      </div>
  		    <div class="box-body">
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
  <strong>Copyright &copy; 2020 <a href="https://adminlte.io">GIA Tech.</a>.</strong> All rights
  reserved.
</footer>

<!-- <div class="loading" style="display: none">Loading&#8230;</div> -->
<?php $this->load->view('includes/footer'); ?>
<script type="text/javascript">

  var TotalInterest = 0;
  $('#dtblApproval').DataTable({
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
              return "<span class='badge bg-"+b.StatusColor+"'>"+b.ProcessedApprovers+ '/' + b.PendingApprovers + ' in progress</span>';
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