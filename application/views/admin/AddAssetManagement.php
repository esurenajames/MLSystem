
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Add Asset Management
    </h1>
    <ol class="breadcrumb">
      <li><a href="#" class="active"><i class="fa fa-dashboard"></i>Asset Management</a></li>
      <li><a href="#">Add Asset</a></li>
    </ol>
  </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="box">
          <div class="box-header with-border">
            <div class="panel-body bg-gray text-bold">INTANGIBLE SUPPLIES</div>
          </div>
          <div class="box-body">
          <div class="col-md-3">
            <div class="form-group">
              <label for="selectCategory">Asset Category</label><br>
              <select class="form-control select1" style="width: 100%" required="" name="Category" id="selectCategory">
                <?php
                  echo $Category;
                ?>
              </select>
            </div>
          </div>
            <form name="ApproverDocForm" method="post" id="ApproverDocForm" action="<?php echo base_url(); ?>admin_controller/AddIntangible/">
            <button type="button" class="btn btn-primary pull-right" id="btnAsset">Add Row</button>
              <table id="tblAsset" style="width: 100%" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>ITEM NO.</th>
                  <th>DATE OF VALUATION</th>
                  <th>VALUE AMOUNT</th>
                  <th>ACTION</th>
                </tr>
                </thead>
                <tbody>
                    <tr id="rowAssetId">
                      <td id="rowNumber1">1</td>
                      <td><input type="text" required="" id="DateAsset" class="form-control" placeholder="MM/DD/YYYY" name="DateAsset[]"><input type="hidden" required="" class="form-control" name="countRow[]" value="1"></td>
                      <td><input type="number" required="" id="Value" class="form-control" placeholder="0.00" name="Value[]"></td>
                      <td></td>
                    </tr>
                </tbody>
              </table>
              <div class="box-footer">
                <button type="button" class="btn btn-primary pull-right" id="btnIntangible">Submit Intangible</button>
              </div>
            </form>
          </div>
        </div>

      <form action="<?php echo base_url(); ?>admin_controller/AddTangible/" id="frmInsert2" method="post">
        <div class="box">
          <div class="box-header with-border">
            <div class="panel-body bg-gray text-bold">TANGIBLE SUPPLIES</div>
          </div>
          <div class="box-body">
            <div class="col-md-4">
              <div class="form-group">
                <label for="PurchasePrice">Purchase Price</label><br>
                <input type="Number" class="form-control" id="txtPurchasePrice" name="PurchasePrice" placeholder="0.00">
                <input type="hidden" class="form-control" id="txtFormType" name="FormType" value="1">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="ReplacementValue">Replacement Value</label><br>
                <input type="Number" class="form-control" id="txtReplacementValue" name="ReplacementValue" placeholder="0.00">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="SerialNumber">Serial Number</label><br>
                <input type="text" class="form-control" id="txtSerialNumber" name="SerialNumber" placeholder="XXXXXXX">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="BoughtFrom">Bought from</label><br>
                <input type="text" class="form-control" id="txtBoughtFrom" name="BoughtFrom" placeholder="Enter place">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="Description">Description of the Asset</label><br>
                <input type="text" class="form-control" id="txtDescription" name="Description">
              </div>
            </div>
          </div>
          <div class="box-footer">
            <button type="button" class="btn btn-primary pull-right" id="btnTangible">Submit Tangible</button>
          </div>
        </div>
      </form>
        <!-- /.box -->
    </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

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
            refreshPage();
            swal({
              title: 'Success!',
              text: 'Bank successfully updated!',
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

  var AssetCount = 1;
    $('#btnAsset').click(function(){
      AssetCount = AssetCount + 1;
      output = '<tr id="rowAssetId' + AssetCount + '" value="' + AssetCount + '">'
      output += '<td id="rowNumber' + AssetCount + '">' + AssetCount + '</td>'
      output += '<td><input type="text" class="form-control" id="DateAsset" name="DateAsset[]"><input type="hidden" required="" class="form-control" name="countRow[]" value="' + AssetCount + '"></td>'
      output += '<td><input required="" type="text" class="form-control" name="Value[]"></td>'
      output += '<td><a id="' + AssetCount + '" class="btn btnRemoveAsset btn-sm btn-danger" title="Remove"><span class="fa fa-minus"></span></a> </td>'
      output += '</tr>'
      $('#tblAsset').append(output);

    });

    $(document).on('click', '.btnRemoveAsset', function(){
      var row_id = $(this).attr("id");

      AssetCount = AssetCount - 1;
      $('#rowAssetId'+ row_id +'').remove();
    });

    $('#DateAsset').daterangepicker({
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
</script>