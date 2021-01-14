
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Branches
    </h1>
    <ol class="breadcrumb">
      <li><a href="http://localhost/ELendingTool/home/Dashboard" class="active"><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li><a href="#">System Setup</a></li>
      <li><a href="#">Branches</a></li>
    </ol>
  </section>


  <div class="modal fade" id="modalNewBranch">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Branch Details</h4>
        </div>
        <form action="<?php echo base_url(); ?>admin_controller/AddBranch/2" id="frmInsert2" method="post">
          <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="Branch">Branch <span class="text-red">*</span></label><br>
                    <input type="text" class="form-control" required=""  id="txtBranch" name="Branch">
                    <input type="hidden" class="form-control" id="txtFormType" name="FormType" value="1">
                    <input type="hidden" class="form-control" id="txtBranchId" name="BranchId">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="Code">Branch Code <span class="text-red">*</span></label><br>
                    <input type="text" class="form-control" required=""  id="txtCode" name="Code">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Description">Description</label>
                    <textarea type="text" class="form-control" id="txtDescription" name="Description" placeholder="Description"></textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                      <div class="form-group">
                        <label>Date from Lease <span class="text-red">*</span></label>
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" required=""  class="form-control" name="DateFrom" required="" id="DateFrom">
                        </div>
                        <!-- /.input group -->
                      </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                      <div class="form-group">
                        <label>Date to Lease <span class="text-red">*</span></label>
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" required=""  class="form-control" name="DateTo" required="" id="DateTo">
                        </div>
                        <!-- /.input group -->
                      </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Monthly">Monthly Lease <span class="text-red">*</span></label>
                    <input type="number" class="form-control" id="txtMonthly" required="" name="Monthly" placeholder="Amount">
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
          <h3 class="box-title">List of Branches</h3>
        </div>
        <div class="box-body">
          <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewBranch">Add Record</button>
          <br>
          <br>
          <table id="example1" style="width: 100%" class="table table-bordered table-hover">
            <thead>
            <tr>
              <th>Reference No</th>
              <th>Branch</th>
              <th>Code</th>
              <th>Description</th>
              <th>Lease from</th>
              <th>Lease to</th>
              <th>Monthly Lease</th>
              <th>Status</th>
              <th>Date Created</th>
              <th>Date Updated</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
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
  
  function confirm(Text, BranchId, updateType)
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
                    Id : BranchId
                    , updateType : updateType
                    , tableType : 'Branch'
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
                text: 'Branch successfully updated!',
                type: 'success',
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary'
              });
              $('.loading').hide();
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
              $('.loading').hide();
            }
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

  function Edit(BranchId)
  {
    $.ajax({
      url: '<?php echo base_url()?>' + "/admin_controller/getBranchDetails",
      type: "POST",
      async: false,
      data: {
        Id : BranchId
      },
      dataType: "JSON",
      beforeSend: function(){
          $('.loading').show();
      },
      success: function(data)
      {
        $('#txtBranch').val(data['Name']);
        $('#txtCode').val(data['Code']);
        $('#txtDescription').val(data['Description']);
        $('#DateFrom').val(data['DateFrom']);
        $('#DateTo').val(data['DateTo']);
        $('#txtMonthly').val(data['LeaseMonthly']);
        $('#txtBranchId').val(BranchId);
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
    var url = '<?php echo base_url()."datatables_controller/Branches/"; ?>';
    UserTable.ajax.url(url).load();
  }

  $(function () {
    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/datatables_controller/Branches/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "ReferenceNo" }
                    , { data: "BranchName" }
                    , { data: "Code" }
                    , { data: "Description" }
                    , { data: "DateFrom" }
                    , { data: "DateTo" }
                    , {
                      data: "LeaseMonthly", "render": function (data, type, row) {
                        return parseInt(row.LeaseMonthly).toLocaleString('en-US', {minimumFractionDigits: 2});
                      }
                    }
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
                          return '<a onclick="confirm(\'Are you sure you want to deactivate this branch?\', \''+row.BranchId+'\', 0)" class="btn btn-sm btn-danger" title="Deactivate"><span class="fa fa-close"></span></a> <a onclick="Edit('+row.BranchId+')" data-toggle="modal" data-target="#modalNewBranch" class="btn btn-sm btn-info" title="Edit"><span class="fa fa-edit"></span></a>';
                        }
                        else if(row.StatusId == 0){
                          return '<a onclick="confirm(\'Are you sure you want to re-activate this branch?\', \''+row.BranchId+'\', 1)" class="btn btn-sm btn-warning" title="Deactivate"><span class="fa fa-refresh"></span></a>';
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

    $('#DateFrom').daterangepicker({
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

    $('#DateTo').daterangepicker({
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

    $('#modalNewBranch').on('hide.bs.modal', function () {
      $('#txtFormType').val(1)
    })

    $("#frmInsert2").on('submit', function (e) {
      e.preventDefault(); 
      swal({
        title: 'Confirm',
        text: 'Are you sure you want to confirm?',
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