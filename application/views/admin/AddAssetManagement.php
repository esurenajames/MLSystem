<div class="content-wrapper">

  <?php if(in_array('18', $subModule) || in_array('19', $subModule) || in_array('20', $subModule)) { ?>

    <input type="hidden" value="<?php print(in_array('18', $subModule))?>" id="txtAdd">
    <input type="hidden" value="<?php print(in_array('19', $subModule))?>" id="txtEdit">
    <input type="hidden" value="<?php print(in_array('20', $subModule))?>" id="txtRead">
    <section class="content-header">
      <h1>
        Asset Management
      </h1>
      <ol class="breadcrumb">
        <li><a href="#" class="active"><i class="fa fa-dashboard"></i>Asset Management</a></li>
        <li><a href="#">Asset Dashboard</a></li>
      </ol>
    </section>

    <div class="modal fade" id="modalNewTangible">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Asset Details</h4>
          </div>
          <form action="<?php echo base_url(); ?>admin_controller/AddAssetManagement/" class="modalReset"  id="frmInsert2" method="post">
            <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="Asset">Type of Asset</label><br>
                      <select class="form-control" required="" style="width: 100%" name="AssetType" id="selectType">
                        <option value="1">Tangible</option>
                        <option value="0">Intangible</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="Asset">Asset Category</label><br>
                      <select class="form-control select2" style="width: 100%" required="" name="CategoryId" id="SelectCategory">
                      <?php
                        echo $Category;
                      ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Asset Name</label>
                      <input type="text" class="form-control" id="txtAssetName" required="" name="AssetName" placeholder="Asset Name">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="Stock">Stock</label>
                      <input type="number" class="form-control" id="txtStock" required="" name="Stock" placeholder="Stocks">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Critical Level</label>
                      <input type="number" class="form-control" id="txtCriticalLevel" required="" name="CriticalLevel" placeholder="Critical Level">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="Asset">Purchase Price</label><br>
                      <input type="number" class="form-control" style="width: 100%" required="" name="PurchasePrice" id="txtPurchasePrice" placeholder="0.00">
                      <input type="hidden" name="FormType" id="txtFormType" value="1">
                      <input type="hidden" name="AssetManagementId" id="txtAssetManagementId">
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="Asset">Replacement Value</label><br>
                      <input type="number" class="form-control" style="width: 100%" required="" name="ReplacementValue" id="txtReplacementValue" placeholder="0.00">
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="Asset">Serial Number</label><br>
                      <input type="text" class="form-control" style="width: 100%" required="" name="SerialNumber" id="txtSerialNumber" placeholder="XXXXXXXXXXXX">
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="Description">Vendor</label>
                      <input type="text" class="form-control" id="txtBoughtFrom" name="BoughtFrom" placeholder="Vendor Name">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="Branch">Company Branch</label><br>
                      <select class="form-control" style="width: 100%" name="BranchId" required="" id="SelectBranch" onchange="selectEmployees(this.value)">
                      <?php
                        echo $Branch;
                      ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Assigned To</label><br>
                      <select class="form-control select2" style="width: 100%" required="" disabled="" name="AssignedTo" id="selectAssignedTo">
                      </select>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="Description">Description</label>
                      <textarea type="text" class="form-control" id="txtDescription" name="Description" placeholder="Description"></textarea>
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

    <div class="modal fade" id="modalStocks">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="modalStockTitle"></h4>
          </div>
          <form action="<?php echo base_url(); ?>admin_controller/AddAssetManagement/" id="frmInsert2" class="modalReset" method="post">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="Asset">Current Stock</label>
                    <h6 id="lblCurrentStock"></h6>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label> Stocks</label><br>
                    <input type="number" name="stockNo" class="form-control" placeholder="No. of Stocks">
                    <input type="hidden" name="stockType" id="txtStockType" class="form-control">
                    <input type="hidden" name="FormType" class="form-control" value="3">
                    <input type="hidden" name="assetId" id="txtAssetId" class="form-control" value="3">
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
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" id="selectStatus" required="">
                      <option value="2">Active</option>
                      <option value="6">Deactivated</option>
                      <option value="3">Critical</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Asset Category</label>
                    <select class="form-control select2" style="width: 100%" id="selectAssetCategory" required="">
                      <?php 
                        echo $Category;
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Purchase Range From</label>
                    <input type="number" class="form-control" min="0" id="txtPurchaseRangeFrom">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Purchase Range To</label>
                    <input type="number" class="form-control" min="0" id="txtPurchaseRangeTo">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Branch</label>
                    <select class="form-control select2" style="width: 100%" id="BranchId" required="">
                      <option selected="">All</option>
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
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">List of Assets</h3>
        </div>
        <div class="box-body">
          <div class="col-md-12">
            <div class="pull-right">
              <?php if(in_array('18', $subModule)) { ?>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNewTangible">Add Record</button>
              <?php }?>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalFilter">Filter</button>
            </div>
            <br>
            <br>
            <table id="example1" class="table table-bordered table-hover" width="100%">
              <thead>
              <tr>
                <th>Reference No</th>
                <th>Category</th>
                <th>Assigned To</th>
                <th>Asset</th>
                <th>Purchase Price</th>
                <th>Serial Number</th>
                <th>Vendor</th>
                <th>Company Branch</th>
                <th>Stocks</th>
                <th>Status</th>
                <th>Date Creation</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
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
  
  function confirm(Text, AssetManagementId, updateType)
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
                    Id : AssetManagementId
                    , updateType : updateType
                    , tableType : 'AssetManagement'
                  },
          beforeSend: function(){
              $('.loading').show();
          },
          success: function(data)
          {
            refreshPage();
            swal({
              title: 'Success!',
              text: 'Asset successfully updated!',
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

  function Edit(AssetManagementId)
  { 
    $.ajax({
      url: '<?php echo base_url()?>' + "/admin_controller/getAssetManagementDetails",
      type: "POST",
      async: false,
      data: {
        Id : AssetManagementId
      },
      dataType: "JSON",
      beforeSend: function(){
          $('.loading').show();
      },
      success: function(data)
      {
        $('#selectType').val(data['Type']).change();
        $('#SelectCategory').val(data['CategoryId']).change();
        $('#txtPurchasePrice').val(data['PurchaseValue']);
        $('#txtReplacementValue').val(data['ReplacementValue']);
        $('#txtSerialNumber').val(data['SerialNumber']);
        $('#txtBoughtFrom').val(data['BoughtFrom']);
        $('#txtDescription').val(data['Description']);
        $('#txtAssetName').val(data['AssetName']);
        $('#txtStock').val(data['Stock']);
        $('#txtCriticalLevel').val(data['CriticalLevel']);
        $('#SelectBranch').val(data['BranchId']).change();
        $('#txtAssetManagementId').val(AssetManagementId);
        $('#txtFormType').val(2);
        selectEmployees(data['BranchId'], data['AssignedTo'], 2);
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
    var url = '<?php echo base_url()."datatables_controller/Assets/"; ?>';
    UserTable.ajax.url(url).load();
  }

  function filterPage(){
    var url = '<?php echo base_url()."datatables_controller/Assets/"; ?>' + $('#selectStatus').val() + '/' + $('#selectAssetCategory').val() + '/' + $('#txtPurchaseRangeFrom').val() + '/' + $('#txtPurchaseRangeTo').val() + '/' + $('#BranchId').val();
    UserTable.ajax.url(url).load();
    $('#modalFilter').modal('hide');
  }

  function stockType(currentStock, stockType, assetId)
  {
    if(stockType == 1)
    {
      $('#modalStockTitle').html('Add Stocks');
      $('#lblCurrentStock').html(currentStock);
      $('#txtStockType').val(stockType);
      $('#txtAssetId').val(assetId);
    }
    else if(stockType == 2)
    {
      $('#modalStockTitle').html('Remove Stocks');
      $('#lblCurrentStock').html(currentStock);
      $('#txtStockType').val(stockType);
      $('#txtAssetId').val(assetId);
    }
  }

  function selectEmployees(value, selectedEmployee, option)
  {
    if(selectedEmployee == undefined)
    {
      if(option != 2)
      {
        $.ajax({
          url: "<?php echo base_url();?>" + "/admin_controller/getDropDownEmployees",
          method: "POST",
          data: { BranchId : value },
          beforeSend: function(){
            $('.loading').show();
          },
          success: function(data)
          {
            $('#selectAssignedTo').html(data);
            $('#selectAssignedTo').prop('disabled', false);
            $('.loading').hide();
          }
        })
      }
    }
    else
    {      
      $.ajax({
        url: "<?php echo base_url();?>" + "/admin_controller/getDropDownEmployees",
        method: "POST",
        data: { BranchId : value },
        beforeSend: function(){
          $('.loading').show();
        },
        success: function(data)
        {
          $('#selectAssignedTo').html(data);
          $('#selectAssignedTo').val(selectedEmployee).change();
          $('#selectAssignedTo').prop('disabled', false);
          $('.loading').hide();
        }
      })
    }
  }

  $(function () {

    $('.select2').select2();
    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/datatables_controller/Assets/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "ReferenceNo" }
                    , { data: "CategoryName" }
                    , { data: "AssignedTo" }
                    , { data: "AssetName" }
                    , { data: "PurchaseValue" }
                    , { data: "SerialNumber" }
                    , { data: "BoughtFrom" }
                    , { data: "BranchName" }
                    , { data: "Stock" }
                    , {
                      data: "StocksLevel", "render": function (data, type, row) {
                        if(row.StocksLevel == 'Critical')
                        {
                          return "<span class='badge bg-orange'>Critical</span>";
                        }
                        else
                        {
                          if(row.StatusId == 2){
                            return "<span class='badge bg-green'>Active</span>";
                          }
                          else if(row.StatusId == 6){
                            return "<span class='badge bg-red'>Deactivated</span>";
                          }
                        }
                      }
                    },
                    { data: "DateCreated" }, 
                    {
                      data: "StatusId", "render": function (data, type, row) {
                        if($('#txtAdd').val() == 1 || $('#txtEdit').val() == 1)
                        {
                          if(row.StatusId == 2){
                            return '<a class="btn btn-sm btn-success" title="Add Stock" onclick="stockType('+row.currentStock+', 1, '+row.AssetManagementId+')" data-toggle="modal" data-target="#modalStocks"><span class="fa fa-plus-circle"></span></a> <a class="btn btn-sm btn-warning" title="Remove Stock" onclick="stockType('+row.currentStock+', 2, '+row.AssetManagementId+')" data-toggle="modal" data-target="#modalStocks"><span class="fa fa-minus-circle"></span></a> <a onclick="Edit('+row.AssetManagementId+')" data-toggle="modal" data-target="#modalNewTangible" class="btn btn-sm btn-info" title="View/Edit"><span class="fa fa-edit"></span></a> <a onclick="confirm(\'Are you sure you want to deactivate this Asset?\', \''+row.AssetManagementId+'\', 6)" class="btn btn-sm btn-danger" title="Deactivate"><span class="fa fa-close"></span></a> ';
                          }
                          else if(row.StatusId == 6) {
                            return '<a onclick="confirm(\'Are you sure you want to re-activate this Asset?\', \''+row.AssetManagementId+'\', 2)" class="btn btn-warning" title="Deactivate"><span class="fa fa-refresh"></span></a>';
                          }
                          else{
                            return "N/A";
                          }
                        }
                        else if($('#txtRead').val() == 1)
                        {
                          return "N/A";
                        }
                        else
                        {
                          return "N/A";
                        }
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[0, "asc"]]
    });

    $(".modalReset").on('submit', function (e) {
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

    $('#modalNewTangible').on('hide.bs.modal', function () {
      document.getElementById('frmInsert2').reset()
    })

  })
</script>