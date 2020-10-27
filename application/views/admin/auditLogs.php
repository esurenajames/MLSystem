
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Audit Logs 
    </h1>
    <ol class="breadcrumb">
      <li><a href="#" class="active"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="#">Audit Logs</a></li>
    </ol>
  </section>

  <section class="content">
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Audit Logs</h3>
      </div>
      <div class="box-body">
        <table id="example1" class="table table-bordered table-hover">
          <thead>
          <tr>
            <th>Employee</th>
            <th>Action</th>
            <th>Date</th>
            <th>Remarks</th>
            <th>LogID</th>
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

<div class="loading" style="display: none">Loading&#8230;</div>
<?php $this->load->view('includes/footer'); ?>

<script>
  $(function () {
    auditLogs = $('#example1').DataTable(
    {
        "pageLength": 10,
        "ajax": { url: '<?php echo base_url()."/admin_controller/getAuditLogs/"; ?>', type: 'POST', "dataSrc": "" },
        "columns": [  { data: "Name" }
                      , { data: "Description" }
                      , { data: "DateCreated" }
                      , { data: "Remarks" }
                      , { data: "LogId" }
        ],
        "aoColumnDefs": [{ "bVisible": false, "aTargets": [4] }],
        "order": [[4, "desc"]]
    });  
  })
</script>