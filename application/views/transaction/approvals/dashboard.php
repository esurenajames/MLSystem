
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Loan Approvals
    </h1>
    <ol class="breadcrumb">
      <li><a href="#" class="active"><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li><a href="#"></i>Loans</a></li>
      <li><a href="#"></i>List of for approvals</a></li>
    </h1>
    </ol>
  </section>

  <section class="content">
    <!-- BORROWER DETAILS -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">List of for Approvals</h3>
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
            return b.ProcessedApprovers+ '/' + b.PendingApprovers + ' in progress';
          }
          else
          {
            return b.StatusDescription;
          }
        }
      }
      , { data: "StatusId", "render": function (data, type, b) {
          return '<a class="btn btn-sm btn-default" href="<?php echo base_url(); ?>home/loandetail/'+b.ApplicationId+'" title="View"><span class="fa fa-info-circle"></span></a>';
        }
      }
    ],
    // "aoColumnDefs": [{ "bVisible": false, "aTargets": [7] }],
    "order": [[0, "desc"]]
  });
</script>