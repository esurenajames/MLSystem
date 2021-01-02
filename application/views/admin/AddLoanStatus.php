<div class="content-wrapper">

  <?php if(in_array('38', $subModule)) { ?>
    <section class="content-header">
      <h1>
        Loan Status
      </h1>
      <ol class="breadcrumb">
        <li><a href="http://localhost/ELendingTool/home/Dashboard" class="active"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="#">System Setup</a></li>
        <li><a href="#">Loan Status</a></li>
      </ol>
    </section>


    <div class="modal fade" id="modalNewStatus">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Loan Status Details</h4>
          </div>
          <form action="<?php echo base_url(); ?>admin_controller/AddLoanStatus/" id="frmInsert2" method="post">
            <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="LoanStatus">Loan Status</label><br>
                      <input type="text" class="form-control" id="txtLoanStatus" name="LoanStatus">
                      <input type="hidden" class="form-control" id="txtFormType" name="FormType" value="1">
                      <input type="hidden" class="form-control" id="txtLoanStatusId" name="LoanStatusId" value="1">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Is Approvable?</label>
                      <select class="form-control" id="txtApprovable" name="Approvable">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Status Color</label>
                      <select class="form-control" id="txtStatusColor" name="StatusColor">
                        <option value="green">Green</option>
                        <option value="red">Red</option>
                        <option value="blue">Blue</option>
                        <option value="yellow">Yellow</option>
                        <option value="orange">Orange</option>
                      </select>
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
      <!-- /.modal-dialog -->
    </div>

    <section class="content">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">List of Loan Status</h3>
        </div>
        <div class="box-body">
          <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewStatus">Add Record</button>
          <br>
          <br>
          <table id="example1" class="table table-bordered table-hover">
            <thead>
            <tr>
              <th>Reference No</th>
              <th>Status Name</th>
              <th>Status</th>
              <th>Date Created</th>
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
  
  function confirm(Text, LoanStatusId, updateType)
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
                    Id : LoanStatusId
                    , updateType : updateType
                    , tableType : 'LoanStatus'
                  },
          beforeSend: function(){
              $('.loading').show();
          },
          success: function(data)
          {
            refreshPage();
            swal({
              title: 'Success!',
              text: 'Loan status successfully updated!',
              type: 'success',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });
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

  function Edit(LoanStatusId)
  {
    $.ajax({
      url: '<?php echo base_url()?>' + "/admin_controller/getLoanStatusDetails",
      type: "POST",
      async: false,
      data: {
        Id : LoanStatusId
      },
      dataType: "JSON",
      beforeSend: function(){
          $('.loading').show();
      },
      success: function(data)
      {
        $('#txtLoanStatus').val(data['Name']);
        $('#txtDescription').val(data['Description']);
        $('#txtLoanStatusId').val(LoanStatusId);
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
    var url = '<?php echo base_url()."datatables_controller/LoanStatus/"; ?>';
    UserTable.ajax.url(url).load();
  }

  $(function () {
    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/datatables_controller/LoanStatus/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "ReferenceNo" }
                    , { data: "LoanStatus" }
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
                    {
                      data: "StatusId", "render": function (data, type, row) {
                        if(row.IsEditable == 1)
                        {
                          if(row.StatusId == 1){
                              return '<a onclick="confirm(\'Are you sure you want to deactivate this loan status?\', \''+row.LoanStatusId+'\', 0)" class="btn btn-sm btn-danger" title="Deactivate"><span class="fa fa-close"></span></a>';
                            }
                            else if(row.StatusId == 0){
                              return '<a onclick="confirm(\'Are you sure you want to re-activate this loan status?\', \''+row.LoanStatusId+'\', 1)" class="btn btn-sm btn-warning" title="Deactivate"><span class="fa fa-refresh"></span></a>';
                            }
                            else{
                              return "N/A";
                            }
                          }
                          else
                          {
                            return 'N/A';
                          }
                        }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[0, "asc"]]
    });

    $('#modalNewStatus').on('hide.bs.modal', function () {
      $('#txtFormType').val(1)
    })

  })
</script>