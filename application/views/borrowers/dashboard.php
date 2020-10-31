
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Borrowers
    </h1>
    <ol class="breadcrumb">
      <li><a href="#" class="active"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li>Borrower's Management</li>
      <li>Borrowers</a></li>
    </h1>
    </ol>
  </section>

  <div class="modal fade" id="modalNewRecord">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Borrower Details</h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>borrower_controller/borrowerProcessing/1" enctype="multipart/form-data" id="frmInsert2" method="post">
          <div class="modal-body">
              <div class="row">
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="selectNationality">Salutation</label><br>
                    <select class="form-control" style="width: 100%" required="" name="SalutationId" id="selectSalutation">
                      <?php
                        echo $Salutation;
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="txtFirstName">First Name <span class="text-red">*</span> </label>
                    <input type="text" class="form-control" id="txtFirstName" required="" name="FirstName" placeholder="First Name">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="txtMiddleName">Middle Name</label>
                    <input type="text" class="form-control" id="txtMiddleName" name="MiddleName" placeholder="Middle Name">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="txtLastName">Last Name <span class="text-red">*</span></label>
                    <input type="text" class="form-control" id="txtLastName" required="" name="LastName"  placeholder="Last Name">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="txtExtensionName">Ext. Name</label>
                    <input type="text" class="form-control" id="txtExtensionName" name="ExtName" placeholder="Ext Name">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="selectGender">Gender <span class="text-red">*</span></label><br>
                    <select class="form-control" style="width: 100%" required="" name="SexId" id="selectGender">
                      <?php
                        echo $Sex;
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="selectNationality">Nationality <span class="text-red">*</span></label><br>
                    <select class="form-control select2" style="width: 100%" required="" name="NationalityId" id="selectNationality">
                      <?php
                        echo $Nationality;
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="selectCivilStatus">Civil Status <span class="text-red">*</span></label><br>
                    <select class="form-control" style="width: 100%" required="" name="CivilStatusId" id="selectCivilStatus">
                      <?php
                        echo $CivilStatus;
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="txtContactNumber">Cellphone Number</label>
                    <input type="number" maxlength="11" class="form-control" id="txtContactNumber" required="" name="ContactNumber" placeholder="09xxxxxxxxx">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="txtTelephone">Telephone Number</label>
                    <input type="number" class="form-control" id="txtTelephone" name="TelephoneNumber" placeholder="Telephone Number">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="txtEmail">Email Address <span class="text-red">*</span></label>
                    <input type="email" class="form-control" required="" id="txtEmail" name="EmailAddress" required="" placeholder="Email Address">
                  </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                      <label for="txtMother">Mother's Maiden Name</label>
                      <input type="text" class="form-control" id="txtMother" name="MotherName" placeholder="Maiden Name">
                    </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                      <div class="form-group">
                        <label>Date of Birth <span class="text-red">*</span></label>
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" class="form-control" name="DOB" required="" id="datepicker">
                        </div>
                        <!-- /.input group -->
                      </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="txtDependents">No. of Dependents <span class="text-red">*</span></label>
                    <input type="number" class="form-control" id="txtDependents" name="NoDependents" required="" placeholder="No. of dependents">
                  </div>
                </div>
                <div class="col-md-12">
                  <center><label>CITY ADDRESS</label></center>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="txtHouseNo">House No/Street/Subdivision <span class="text-red">*</span></label>
                    <input type="text" class="form-control" id="txtHouseNo" name="HouseNo" required="" placeholder="House No/Street/Subdivision">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="selectRegion">Region <span class="text-red">*</span></label>
                    <select class="form-control select2"  required="" onchange="changeRegion(this.value)" id="selectRegion" name="RegionId" style="width: 100%">
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="selectProvince">Province/City<span class="text-red">*</span></label>
                    <select class="form-control select2"  required="" id="selectProvince" onchange="changeProvince(this.value)" name="ProvinceId" style="width: 100%">
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="selectCity">Municipality<span class="text-red">*</span></label>
                    <select class="form-control select2" required="" id="selectCity" onchange="changeCity(this.value)" name="CityId" style="width: 100%">
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="selectBarangay">Barangay <span class="text-red">*</span></label>
                    <select class="form-control select2" required="" id="selectBarangay" name="BarangayId" style="width: 100%">
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <label>Length of stay in city address</label><br>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="txtYearsStayed">Years<span class="text-red">*</span></label>
                    <input type="number" min="0" class="form-control" id="txtYearsStayed" name="YearsStayed" required="" placeholder="Years stayed">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="txtMonthsStayed">Months <span class="text-red">*</span></label>
                    <input type="number" min="0" max="11" maxlength="2" class="form-control" id="txtMonthsStayed" name="MonthsStayed" required="" placeholder="Months stayed">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="txtTelephoneCityAddress">Telephone at city address</label>
                    <input type="text" class="form-control" id="txtTelephoneCityAddress" name="TelephoneCityAddress" placeholder="Telephone number for city address">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="txtTelephoneCityAddress">Cellphone at city address</label>
                    <input type="text" class="form-control" id="txtCellphoneCityAdd" name="CellphoneCityAdd" placeholder="Cellphone number for city address">
                  </div>
                </div>
                <div class="col-md-12">
                  <label>Type of Residence</label><br>
                  <div class="form-group">
                    <div class="radio">
                      <label>
                        <input type="radio" name="optionsRadios" id="optionsRadios1" onclick="chkRent(this.value)" value="Owned" checked="">
                        Owned - Mortgage
                      </label>
                      <label>
                        <input type="radio" name="optionsRadios" id="optionsRadios2" onclick="chkRent(this.value)" value="Living with relatives">
                        Living with relatives
                      </label>
                      <label>
                        <input type="radio" name="optionsRadios" id="optionsRadios3" onclick="chkRent(this.value)" value="Rented">
                        Rented
                      </label>
                    </div>
                    <div class="row">
                      <div id="divRentedDetails" style="display: none">
                        <input type="hidden" class="form-control" id="txtRentedType" name="isRented" required="">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="txtLandLord">Name of Landlord</label>
                            <input type="text" class="form-control" id="txtLandLord" name="LandLord" placeholder="Name of Landlord/Lessor">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="txtLandLordNumber">Telephone No.</label>
                            <input type="text" class="form-control" id="txtLandLordNumber" name="LandLordNumber" placeholder="Telephone Number">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <center><label>PROVINCIAL ADDRESS</label> <br> 
                    <label><input type="checkbox" class="minimal" id="chkAddress" name="SameAddress" value="1" onclick="chkFunction(this.value)"> Same as city address </label>
                    <input type="hidden" class="form-control" required="" id="txtAddress2" name="IsSameAddress" required="">
                  </center>
                </div>
              </div>
              <div class="row">
                <div id="divProvincialAddress">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="txtHouseNo2">House No/Street/Subdivision<span class="text-red">*</span></label>
                      <input type="text" class="form-control" id="txtHouseNo2" name="HouseNo2" placeholder="House No/Street/Subdivision">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="selectRegion2">Region <span class="text-red">*</span></label>
                      <select class="form-control select2"  onchange="changeRegion2(this.value)" id="selectRegion2" name="RegionId2" style="width: 100%">
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="selectProvince2">Province/City<span class="text-red">*</span></label>
                      <select class="form-control select2"  id="selectProvince2" onchange="changeProvince2(this.value)" name="ProvinceId2" style="width: 100%">
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="selectCity2">Municipality<span class="text-red">*</span></label>
                      <select class="form-control select2" id="selectCity2" onchange="changeCity2(this.value)" name="CityId2" style="width: 100%">
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="selectBarangay2">Barangay <span class="text-red">*</span></label>
                      <select class="form-control select2" id="selectBarangay2" name="BarangayId2" style="width: 100%">
                      </select>
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

  <div class="modal fade" id="modalImport2">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Import Borrower</h4>
        </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="txtHouseNo">Excel Attachment <span class="text-red">*</span></label>
                  <input type="file" name="Attachment[]" required="" id="Attachment" accept=".xlsx, .xls, .doc, .docx, .pdf, .jpeg, .jpg, .png">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Download Format <span class="text-red">*</span></label><br>
                  <a class="btn btn-sm btn-success" href="<?php echo base_url();?>/borrowerUpload/borrowerUpload.xls" title="Download">Download</a>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalReport">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Generate Reports</h4>
        </div>
          <form autocomplete="off" action="<?php echo base_url(); ?>borrower_controller/generateReport/" method="post" enctype="multipart/form-data">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <select class="form-control" id="selectReportType" onchange="ReportType(this.value)">
                    <option selected="" disabled="">Select Report Type</option>
                    <option value="1">Demographics</option>
                    <option value="2">Custom</option>
                  </select>
                </div>
                <div id="divDemographics">
                  <div class="col-md-6">
                    <label>Select Year From</label>
                    <select class="form-control" id="yearFrom" name="yearFrom">
                      <?php 
                        foreach ($ageYear as $value) 
                        {
                          $selected = (date("Y") == $value['Year']) ? 'selected' : '';
                          echo '<option '.$selected.'>'.$value['Year'].'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label>Select Year To</label>
                    <select class="form-control" id="yearTo" name="yearTo">
                      <?php 
                        foreach ($ageYear as $value) 
                        {
                          $selected = (date("Y") == $value['Year']) ? 'selected' : '';
                          echo '<option '.$selected.'>'.$value['Year'].'</option>';
                        }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button class="btn btn-primary" type="submit">Submit</button>
            </div>
          </form>
      </div>
    </div>
  </div>

    <!-- Main content -->
    <section class="content">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">List of Borrowers</h3>
        </div>
        <div class="box-body">
          <div class="pull-right">
            <button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#modalReport">Generate Report</button>
            <button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#modalNewRecord">Add Borrower</button>
            <button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#modalImport2">Import Borrower</button>
          </div>
          <br>
          <br>
          <form name="ApproverDocForm" method="post" id="ApproverDocForm">
            <table id="example1" class="table table-bordered table-hover">
              <thead>
              <tr>
                <th>Borrower Name</th>
                <th>No. of Dependents</th>
                <th>No. of Loans</th>
                <th>Added By</th>
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
  <strong>Copyright &copy; 2020 <a href="https://adminlte.io">GIA Tech.</a>.</strong> All rights
  reserved.
</footer>

<div class="loading" style="display: none">Loading&#8230;</div>
<?php $this->load->view('includes/footer'); ?>

<script src="<?php echo base_url(); ?>resources/functionalities/borrower/dashfooter.js"></script>
<script>
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
</script>

<script>

  function ReportType(value)
  {
    if(value == 1) // demo graphics
    {

    }
  }

  function confirm(Text, Id, updateType, tableType)
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
          url: "<?php echo base_url();?>" + "/borrower_controller/updateEmail",
          method: "POST",
          data:   {
                    Id : Id
                    , updateType : updateType
                    , tableType : 'BorrowerUpdate'
                  },
          beforeSend: function(){
              $('.loading').show();
          },
          success: function(data)
          {
            refreshPage();
            swal({
              title: 'Success!',
              text: 'Borrower successfully updated!',
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

  function refreshPage(){
    var url = '<?php echo base_url()."borrower_controller/getAllList/"; ?>';
    UserTable.ajax.url(url).load();
  }

  $('#datepicker').daterangepicker({
        "startDate": moment().format('DD MMM YY hh:mm A'),
        "singleDatePicker": true,
        "showDropdowns": true,
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



  UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/borrower_controller/getAllList/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  
                    { data: "Name" }
                    , { data: "Dependents" }
                    , { data: "TotalLoans" }
                    , { data: "CreatedBy" }
                    , { data: "StatusId", "render": function (data, type, row) {
                        if(row.StatusId == 1){
                          return "<span class='badge bg-green'>Active</span>";
                        }
                        else if(row.StatusId == 2){
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
                          return '<a href="<?php echo base_url();?>home/BorrowerDetails/'+row.BorrowerId+'" class="btn btn-sm btn-default" title="View"><span class="fa fa-info-circle"></span></a> <a href="<?php echo base_url();?>home/createBorrowerLoan/'+row.BorrowerId+'" class="btn btn-sm btn-success" title="Create Loan"><span class="fa fa-plus-square"></span></a> <a onclick="confirm(\'Are you sure you want to deactivate this borrower?\', \''+row.BorrowerId+'\', 2, \'BorrowerUpdate\')" class="btn btn-sm btn-danger" title="Deactivate"><span class="fa fa-close"></span></a>';
                        }
                        else
                        {
                          return '<a onclick="confirm(\'Are you sure you want to re-activate this borrower?\', \''+row.BorrowerId+'\', 1, \'BorrowerUpdate\')" class="btn btn-warning" title="Re-activate"><span class="fa fa-refresh"></span></a>';
                        }
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[3, "asc"]]
    });
</script>

