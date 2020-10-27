
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
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
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Asset Details</h4>
        </div>
        <form action="<?php echo base_url(); ?>admin_controller/AddAssetManagement/" id="frmInsert2" method="post">
          <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="Asset">Type of Asset</label><br>
                    <select class="form-control" style="width: 100%" name="AssetType" id="selectType">
                      <option value="1">Tangible</option>
                      <option value="0">Intangible</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="Asset">Asset Category</label><br>
                    <select class="form-control" style="width: 100%" name="CategoryId" id="SelectCategory">
                    <?php
                      echo $Category;
                    ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="AssetName">Asset Name</label>
                    <input type="text" class="form-control" id="txtAssetName" name="AssetName" placeholder="Asset Name">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="Stock">Stock</label>
                    <input type="number" class="form-control" id="txtStock" name="Stock" placeholder="00">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="CriticalLevel">Critical Level</label>
                    <input type="number" class="form-control" id="txtCriticalLevel" name="CriticalLevel" placeholder="00">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="Asset">Purchase Price</label><br>
                    <input type="number" class="form-control" style="width: 100%" name="PurchasePrice" id="txtPurchasePrice" placeholder="0.00">
                    <input type="hidden" name="FormType" id="txtFormType" value="1">
                    <input type="hidden" name="AssetManagementId" id="txtAssetManagementId">
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="Asset">Replacement Value</label><br>
                    <input type="number" class="form-control" style="width: 100%" name="ReplacementValue" id="txtReplacementValue" placeholder="0.00">
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="Asset">Serial Number</label><br>
                    <input type="text" class="form-control" style="width: 100%" name="SerialNumber" id="txtSerialNumber" placeholder="XXXXXXXXXXXX">
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="Description">Vendor</label>
                    <input type="text" class="form-control" id="txtBoughtFrom" name="BoughtFrom">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="Branch">Company Branch</label><br>
                    <select class="form-control" style="width: 100%" name="BranchId" id="SelectBranch">
                    <?php
                      echo $Branch;
                    ?>
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

    <!-- Main content -->
    <section class="content">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">List of Assets</h3>
        </div>
        <div class="box-body">
          <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewTangible">Add Asset</button>
          <br>
          <br>
          <form name="ApproverDocForm" method="post" id="ApproverDocForm">
            <table id="example1" class="table table-bordered table-hover">
              <thead>
              <tr>
                <th>Type</th>
                <th>Category</th>
                <th>Asset</th>
                <th>Purchase Price</th>
                <th>Replacement Value</th>
                <th>Serial Number</th>
                <th>Vendor</th>
                <th>Company Branch</th>
                <th>Stocks</th>
                <th>Status</th>
                <th>Date Created</th>
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
        $('#selectType').change(data['AssetType']);
        $('#SelectCategory').change(data['CategoryId']);
        $('#txtPurchasePrice').val(data['PurchasePrice']);
        $('#txtReplacementValue').val(data['ReplacementValue']);
        $('#txtSerialNumber').val(data['SerialNumber']);
        $('#txtBoughtFrom').val(data['BoughtFrom']);
        $('#txtDescription').val(data['Description']);
        $('#txtAssetManagementId').val(AssetManagementId);
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
    var url = '<?php echo base_url()."datatables_controller/Assets/"; ?>';
    UserTable.ajax.url(url).load();
  }

  $(function () {
    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/datatables_controller/Assets/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "Type" }
                    , { data: "CategoryName" }
                    , { data: "AssetName" }
                    , { data: "PurchaseValue" }
                    , { data: "ReplacementValue" }
                    , { data: "SerialNumber" }
                    , { data: "BoughtFrom" }
                    , { data: "BranchName" }
                    , { data: "Stock" }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        if(row.StatusId == 2){
                          return "<span class='badge bg-green'>Active</span>";
                        }
                        else if(row.StatusId == 6){
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
                      if(row.StatusId == 2){
                          return '<a onclick="confirm(\'Are you sure you want to deactivate this Asset?\', \''+row.AssetManagementId+'\', 6)" class="btn btn-danger" title="Deactivate"><span class="fa fa-close"></span></a> <a onclick="Edit('+row.AssetManagementId+')" data-toggle="modal" data-target="#modalNewTangible" class="btn btn-info" title="Edit"><span class="fa fa-edit"></span></a>';
                        }
                        else if(row.StatusId == 6){
                          return '<a onclick="confirm(\'Are you sure you want to re-activate this Asset?\', \''+row.AssetManagementId+'\', 2)" class="btn btn-warning" title="Deactivate"><span class="fa fa-refresh"></span></a>';
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

    $('#modalNewCategory').on('hide.bs.modal', function () {
      $('#txtFormType').val(1)
    })

  })
</script>