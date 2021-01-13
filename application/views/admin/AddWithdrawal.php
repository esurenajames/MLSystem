<div class="content-wrapper">

  <?php if(in_array('14', $subModule)) { ?>

    <section class="content-header">
      <h1>
        Deposits
      </h1>
      <ol class="breadcrumb">
        <li><a href="http://localhost/ELendingTool/home/Dashboard" class="active"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="#">System Setup</a></li>
        <li><a href="#">Add Deposit</a></li>
      </ol>
    </section>
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
              <div class="col-md-12">
                <div class="form-group">
                  <label>Status</label>
                  <select class="form-control" id="Status" required="">
                    <option>All</option>
                    <option value="1">Active</option>
                    <option value="0">Deactivated</option>
                  </select>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label>Deposit Type</label>
                  <select class="form-control select2" style="width: 100%" id="selectDepositType" required="">
                    <option>All</option>
                    <?php 
                      echo $DepositType;
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label>Created By</label>
                  <select class="form-control select2" style="width: 100%" id="selectCreatedBy" required="">
                    <option>All</option>
                    <?php 
                      echo $CreatedBy;
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Deposit Range From</label>
                  <input type="number" min="0" value="0" class="form-control" id="txtDepositFrom" required="">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Deposit Range To</label>
                  <input type="number" min="0" value="0" class="form-control" id="txtDepositTo" required="">
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Date of Deposit From</label>
                  <select class="form-control select2" style="width: 100%" id="dateDepositFrom" required="">
                    <?php 
                      echo $DepositDate;
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Date of Deposit To</label>
                  <select class="form-control select2" style="width: 100%" id="dateDepositTo" required="">
                    <?php 
                      echo $DepositDate;
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label>Branch</label>
                  <select class="form-control select2" style="width: 100%" id="selectBranch" required="">
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

    <div class="modal fade" id="modalNewWithdrawal">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Deposit</h4>
          </div>
          <form action="<?php echo base_url(); ?>admin_controller/AddDeposit/" class="frminsert" method="post">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Withdrawal">Type of Deposit</label><br>
                    <select class="form-control" style="width: 100%" required="" name="Withdrawal" id="SelectWithdrawal">
                    <?php
                      echo $WithdrawalType;
                    ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Amount">Amount</label><br>
                    <input type="number" class="form-control" step="0.25" required="" id="txtAmount" name="Amount">
                    <input type="hidden" class="form-control"  id="txtFormType" name="FormType" value="1">
                    <input type="hidden" class="form-control"  id="txtWithdrawalId" name="WithdrawalId">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <div class="form-group">
                      <label>Date of Deposit</label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control" required="" name="DateWithdrawal" id="DateWithdrawal">
                      </div>
                      <!-- /.input group -->
                    </div>
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
          <h3 class="box-title">List of Deposits</h3>
        </div>
        <div class="box-body">
          <div class="pull-right">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNewWithdrawal">Add Deposit</button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalFilter">Filter</button>
          </div>
          <br>
          <br>
          <form name="ApproverDocForm" method="post" id="ApproverDocForm">
            <table id="example1" class="table table-bordered table-hover">
              <thead>
              <tr>
                <th>Reference No</th>
                <th>Deposit Type</th>
                <th>Amount</th>
                <th>Date of Deposit</th>
                <th>Date Creation</th>
                <th>Created By</th>
                <th>Branch</th>
                <th>Status</th>
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

<script src="<?php echo base_url(); ?>resources/functionalities/AddNotif.js"></script>
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

  function filterPage(){
    var url = '<?php echo base_url()."datatables_controller/Withdrawals/"; ?>' + $('#Status').val() + '/' + $('#selectDepositType').val() + '/' + $('#selectCreatedBy').val() + '/' + $('#txtDepositFrom').val() + '/' + $('#txtDepositTo').val() + '/' + $('#dateDepositFrom').val() + '/' + $('#dateDepositTo').val() + '/' + $('#selectBranch').val();
    UserTable.ajax.url(url).load();
    $('#modalFilter').modal('hide');
  }
  
  function confirm(Text, WithdrawalId, updateType)
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
                    Id : WithdrawalId
                    , updateType : updateType
                    , tableType : 'Withdrawal'
                  },
          beforeSend: function(){
              $('.loading').show();
          },
          success: function(data)
          {
            refreshPage();
            swal({
              title: 'Success!',
              text: 'Deposit successfully updated!',
              type: 'success',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });
            $('.loading').hide();
          },
          error: function (response) 
          {
            $('.loading').hide();
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

  function Edit(WithdrawalId)
  {
    $.ajax({
      url: '<?php echo base_url()?>' + "/admin_controller/getWithdrawalDetails",
      type: "POST",
      async: false,
      data: {
        Id : WithdrawalId
      },
      dataType: "JSON",
      beforeSend: function(){
          $('.loading').show();
      },
      success: function(data)
      {
        $('#SelectWithdrawal').val(data['WithdrawalTypeId']).change();
        $('#txtAmount').val(data['Amount']);
        $('#DateWithdrawal').val(data['DateWithdrawal']);
        $('#txtWithdrawalId').val(WithdrawalId);
        $('#txtFormType').val(2);
        $('.loading').hide();
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
    var url = '<?php echo base_url()."datatables_controller/Withdrawals/"; ?>';
    UserTable.ajax.url(url).load();
  }

  $(function () {
    $('.select2').select2();
    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/datatables_controller/Withdrawals/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "ReferenceNo" }
                    , { data: "Withdrawal" }
                    , { data: "Amount" }
                    , { data: "DateWithdrawal" } 
                    , { data: "DateCreated" } 
                    , { data: "CreatedBy" }
                    , { data: "Branch" }
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
                    {
                      data: "StatusId", "render": function (data, type, row) {
                      if(row.StatusId == 1){
                          return '<a onclick="confirm(\'Are you sure you want to deactivate this Withdrawal?\', \''+row.WithdrawalId+'\', 0)" class="btn btn-danger" title="Deactivate"><span class="fa fa-close"></span></a> <a onclick="Edit('+row.WithdrawalId+')" data-toggle="modal" data-target="#modalNewWithdrawal" class="btn btn-primary" title="Set Primary"><span class="fa fa-edit"></span></a>';
                        }
                        else if(row.StatusId == 0){
                          return '<a onclick="confirm(\'Are you sure you want to re-activate this Withdrawal?\', \''+row.WithdrawalId+'\', 1)" class="btn btn-warning" title="Deactivate"><span class="fa fa-refresh"></span></a>';
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

    $('#DateWithdrawal').daterangepicker({
        "startDate": moment().format('DD MMM YY hh:mm A'),
        "singleDatePicker": true,
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

    $('#modalNewWithdrawal').on('hide.bs.modal', function () {
      $('#txtFormType').val(1)
    })

  })
</script>