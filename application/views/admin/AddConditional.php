<div class="content-wrapper">

  <?php if(in_array('40', $subModule)) { ?>
    <section class="content-header">
      <h1>
        Additional Charges
      </h1>
      <ol class="breadcrumb">
        <li><a href="http://localhost/ELendingTool/home/Dashboard" class="active"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li><a href="#">System Setup</a></li>
        <li><a href="#">Additional Chaarges</a></li>
      </ol>
    </section>


    <div class="modal fade" id="modalNewCharges">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Additional Charge Details</h4>
          </div>
          <form action="<?php echo base_url(); ?>admin_controller/AddCharge/" id="frmInsert2" method="post">
            <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="ConditionalType">Type <span class="text-red">*</span></label><br>
                      <select class="form-control" required="" style="width: 100%" name="ChargeType" id="selectCharges">
                        <option value="1">Percentage</option>
                        <option value="0">Fixed Amount</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="ConditionalName">Name <span class="text-red">*</span></label><br>
                      <input type="text" class="form-control" required="" id="txtConditionalName" name="ConditionalName">
                      <input type="hidden" class="form-control" name="FormType" id="txtFormType" value="1">
                      <input type="hidden" class="form-control" id="txtChargeId" name="ChargeId">
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
                      <label for="ConditionalName">Amount <span class="text-red">*</span></label><br>
                      <input type="number" class="form-control" required="" id="txtAmount" name="Amount">
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
          <h3 class="box-title">List of Additional Charges</h3>
        </div>
        <div class="box-body">
          <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewCharges">Add Record</button>
          <br>
          <br>
          <form name="ApproverDocForm" method="post" id="ApproverDocForm">
            <table id="example1" class="table table-bordered table-hover">
              <thead>
              <tr>
                <th>#</th>
                <th>Type of Charge</th>
                <th>Name</th>
                <th>Description</th>
                <th>Amount</th>
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
  
  function confirm(Text, ChargeId, updateType)
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
                    Id : ChargeId
                    , updateType : updateType
                    , tableType : 'Charge'
                  },
          beforeSend: function(){
              $('.loading').show();
          },
          success: function(data)
          {
            refreshPage();
            swal({
              title: 'Success!',
              text: 'Charge successfully updated!',
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

  function Edit(ChargeId)
  {
    $.ajax({
      url: '<?php echo base_url()?>' + "/admin_controller/getChargeDetails",
      type: "POST",
      async: false,
      data: {
        Id : ChargeId
      },
      dataType: "JSON",
      beforeSend: function(){
          $('.loading').show();
      },
      success: function(data)
      {
        $('#selectCharges').val(data['ChargeType']).change();
        $('#txtConditionalName').val(data['Name']);
        $('#txtDescription').val(data['Description']);
        $('#txtAmount').val(data['Amount']);
        $('#txtChargeId').val(ChargeId);
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
          $('.loading').hide();
        }, 2000);
      }
    });
  }

  function refreshPage(){
    var url = '<?php echo base_url()."datatables_controller/Charges/"; ?>';
    UserTable.ajax.url(url).load();
  }

  $(function () {
    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/datatables_controller/Charges/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "ReferenceNo" }
                    , { data: "ChargeType" }
                    , { data: "ChargeName" }
                    , { data: "Description" }
                    , {
                      data: "Amount", "render": function (data, type, row) {
                        return parseInt(row.Amount).toLocaleString('en-US', {minimumFractionDigits: 2});
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
                          return '<a onclick="confirm(\'Are you sure you want to deactivate this charge?\', \''+row.ChargeId+'\', 0)" class="btn btn-danger" title="Deactivate"><span class="fa fa-close"></span></a> <a onclick="Edit('+row.ChargeId+')" data-toggle="modal" data-target="#modalNewCharges" class="btn btn-info" title="Edit"><span class="fa fa-edit"></span></a>';
                        }
                        else if(row.StatusId == 0){
                          return '<a onclick="confirm(\'Are you sure you want to re-activate this charge?\', \''+row.ChargeId+'\', 1)" class="btn btn-warning" title="Deactivate"><span class="fa fa-refresh"></span></a>';
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

    $("#frmInsert").on('submit', function (e) {
      if(varNewPassword = 1 && varStatus == 1 && $('#txtNewPassword').val() == $('#txtConfirmPassword').val() && $('#txtOldPassword').val() != $('#txtNewPassword').val())
      {
        e.preventDefault(); 
        swal({
          title: 'Confirm',
          text: 'Are you sure you sure with this password?',
          type: 'info',
          showCancelButton: true,
          buttonsStyling: false,
          confirmButtonClass: 'btn btn-success',
          confirmButtonText: 'Confirm',
          cancelButtonClass: 'btn btn-secondary'
        }).then(function(){
          e.currentTarget.submit();
        });
      }
      else
      {
        alert('please make sure your new password is not equal to your old password!')
        e.preventDefault();
      }
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

    $('#modalNewCharges').on('hide.bs.modal', function () {
      $('#txtFormType').val(1)
    })

  })
</script>