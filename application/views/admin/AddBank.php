
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Banks
    </h1>
    <ol class="breadcrumb">
      <li><a href="http://localhost/ELendingTool/home/Dashboard" class="active"><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li><a href="#">System Setup</a></li>
      <li><a href="#">Banks</a></li>
    </ol>
  </section>
  <div class="modal fade" id="modalNewBank">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Bank Details</h4>
        </div>
        <form action="<?php echo base_url(); ?>admin_controller/AddBank/" id="frmInsert" method="post">
          <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="BankName">Name of the Bank <span class="text-red">*</span></label><br>
                    <input type="text" class="form-control" required="" id="txtBankName" required="" name="BankName">
                    <input type="hidden" class="form-control" id="txtFormType" name="FormType" value="1">
                    <input type="hidden" class="form-control" id="txtBankId" name="BankId">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Description">Description</label>
                    <textarea type="text" class="form-control" id="txtDescription" name="Description" placeholder="Description"></textarea>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="AccountNumber">Account Number</label>
                    <input type="text" class="form-control" id="txtAccountNumber" name="AccountNumber" placeholder="Optional">
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
      <!-- /.modal-content -->
    </div>
  </div>

    <!-- Main content -->
    <section class="content">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">List of Banks</h3>
        </div>
        <div class="box-body">
          <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewBank">Add Record</button>
          <br>
          <br>
          <form name="ApproverDocForm" method="post" id="ApproverDocForm">
            <table id="example1" class="table table-bordered table-hover">
              <thead>
              <tr>
                <th>#</th>
                <th>Bank</th>
                <th>Description</th>
                <th>Account No.</th>
                <th>Status</th>
                <th>Date Created</th>
                <th>Date Updated</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
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
  
  function confirm(Text, BankId, updateType)
  { 
    swal({
      title: 'Confirm',
      text: Text,
      type: 'info',
      showCancelButton: true,
      buttonsStyling: false,
      confirmButtonClass: 'btn btn-success',
      confirmButtonText: 'Confirm',
      cancelButtonClass: 'btn btn-secondary'
    }).then(function(){
      $.ajax({                
          url: "<?php echo base_url();?>" + "/admin_controller/updateStatus",
          method: "POST",
          data:   {
                      Id : BankId
                    , updateType : updateType
                    , tableType : 'Bank'
                  },
          beforeSend: function(){
              $('.loading').show();
          },
          success: function(data)
          {
            if(data == 1)
            {
              refreshPage();
              swal({
                title: 'Success!',
                text: 'Bank successfully updated!',
                type: 'success',
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary'
              });
            }
            else
            {
              swal({
                title: 'Info!',
                text: 'Record is in use, record cannot be updated!',
                type: 'info',
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary'
              });
            }
          },
          error: function (response) 
          {
            refreshPage();
            swal({
              title: 'Warning!',
              text: 'Something went wrong, please contact the administrator or refresh page!',
              type: 'warning',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });
          }
      });
    });
  }

  function Edit(BankId)
  {
    $.ajax({
      url: '<?php echo base_url()?>' + "/admin_controller/getBankDetails",
      type: "POST",
      async: false,
      data: {
        Id : BankId
      },
      dataType: "JSON",
      beforeSend: function(){
          $('.loading').show();
      },
      success: function(data)
      {
        $('#txtBankName').val(data['BankName']);
        $('#txtDescription').val(data['Description']);
        $('#txtAccountNumber').val(data['AccountNumber']);
        $('#txtBankId').val(BankId);
        $('#txtFormType').val(2);
      },

      error: function()
      {
        setTimeout(function() {
          swal({
            title: 'Warning!',
            text: 'Something went wrong, please contact the administrator or refresh page!',
            type: 'warning',
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary'
          });
          // location.reload();
        }, 2000);
      }
    });
  }

  function refreshPage(){
    var url = '<?php echo base_url()."datatables_controller/Banks/"; ?>';
    UserTable.ajax.url(url).load();
  }

  $(function () {
    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/datatables_controller/Banks/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "ReferenceNo" }
                    , { data: "BankName" }
                    , { data: "Description" }
                    , { data: "AccountNumber" }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        if(row.StatusId == 1){
                          return "<span class='badge bg-green'>Active</span>";
                        }
                        else if(row.StatusId == 0){
                          return "<span class='badge bg-red'>Deactivated</span>";
                        }
                        else{
                          return "N/A";
                        }
                      }
                    },
                    { data: "DateCreated" }, 
                    { data: "DateUpdated" }, 
                    {
                      data: "StatusId", "render": function (data, type, row) {
                      if(row.StatusId == 1){
                          return '<a onclick="confirm(\'Are you sure you want to deactivate this bank?\', \''+row.BankId+'\', 0)" class="btn btn-danger" title="Deactivate"><span class="fa fa-close"></span></a> <a onclick="Edit('+row.BankId+')" data-toggle="modal" data-target="#modalNewBank" class="btn btn-info" title="Edit"><span class="fa fa-edit"></span></a>';
                        }
                        else if(row.StatusId == 0){
                          return '<a onclick="confirm(\'Are you sure you want to re-activate this bank?\', \''+row.BankId+'\', 1)" class="btn btn-warning" title="Deactivate"><span class="fa fa-refresh"></span></a>';
                        }
                        else{
                          return "N/A";
                        }
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[0, "asc"]]
    });

    $('#modalNewBank').on('hide.bs.modal', function () {
      $('#txtFormType').val(1)
    })

    $("#frmInsert").on('submit', function (e) {
      e.preventDefault(); 
      swal({
        title: 'Confirm',
        text: 'Are you sure you want to submit this form?',
        type: 'info',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-success',
        confirmButtonText: 'Confirm',
        cancelButtonClass: 'btn btn-secondary'
      }).then(function(){
        e.currentTarget.submit();
      });
    });



  })
</script>