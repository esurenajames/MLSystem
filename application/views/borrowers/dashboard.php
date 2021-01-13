
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <?php if((in_array('21', $subModule) || in_array('23', $subModule))) { ?>

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
                      <label for="txtMother">Mother's Maiden Name <span class="text-red">*</span></label>
                      <input type="text" class="form-control" id="txtMother" required="" name="MotherName" placeholder="Maiden Name">
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
                      <input type="number" class="form-control" value="0" id="txtDependents" name="NoDependents" required="" placeholder="No. of dependents">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Birthplace <span class="text-red">*</span></label>
                      <input type="text" class="form-control" id="txtBirthPlace" name="BirthPlace" required="" placeholder="Birthplace">
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
            <form role="form" id="upload_form3" method="post" enctype="multipart/form-data">
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="txtHouseNo">Excel Attachment <span class="text-red">*</span></label>
                      <input type="file" id="form3UploadExcel" name="form3UploadExcel" accept=".xls, .xlsx" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Download Format <span class="text-red">*</span></label><br>
                      <a class="btn btn-sm btn-success" href="<?php echo base_url();?>/employeeUpload/BorrowerUpload.xlsx" title="Download">Download</a>
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
      </div>
    </div>

    <!-- <div class="modal fade" id="modalReport">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Generate Reports</h4>
          </div>
          <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/generateReport/4" method="post" enctype="multipart/form-data">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Report Columns</label>
                      <select class="form-control select2" required="" name="columnNames[]" style="width: 100%;" multiple="">
                        <option value="1">Age</option>
                        <option value="2">Education</option>
                        <option value="3">Gender/Sex</option>
                        <option value="4">Occupation</option>
                        <option value="5">Income Level</option>
                        <option value="6">Marital Status</option>
                        <option value="7">Risk Profile</option>
                        <option value="9">Number of Rollovers/Refinance borrowers and one-time borrowers</option>
                      </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Report Name</label>
                    <input type="text" class="form-control" value="Demographics" name="reportName">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Date From</label>
                    <select class="form-control" id="selectYearFrom" name="yearFrom">
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
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Date To</label>
                    <select class="form-control" id="selectYearFrom" name="yearFrom">
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
                <div class="col-md-6">
                  <label>Verified By</label>
                  <select name="verifiedBy" required="" class="form-control"style="width: 100%"  id="selectEmployee">
                  </select>
                </div>
                <div class="col-md-6">
                  <label>Approved By</label>
                  <select name="approvedBy" required="" class="form-control"style="width: 100%"  id="selectEmployee2">
                  </select>
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
    </div> -->

    <div class="modal fade" id="modalFilter">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Filter Borrowers</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <!-- <div class="col-md-12">
                <div class="form-group">
                  <div class="form-group">
                    <label>Date Created <span class="text-red">*</span></label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" class="form-control" name="DateCreated" required="" id="dateCreated">
                    </div>
                  </div>
                </div>
              </div> -->
              <div class="col-md-12">
                <div class="form-group">
                  <label>Status</label>
                  <select class="form-control" id="borrowerStatus" required="">
                    <option value="All">All</option>
                    <?php 
                      echo $Status;
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label>Created By</label>
                  <select style="width: 100%" required="" class="form-control select2" id="borrowerCreatedBy" required="">
                    <option value="All">All</option>
                    <?php 
                      echo $CreatedBy;
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Dependent Range From <span class="text-red">*</span></label>
                  <input type="number" required="" value="0" id="borrowerDependentsFrom" class="form-control" name="">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Dependent Range To <span class="text-red">*</span></label>
                  <input type="number" required="" value="10" id="borrowerDependentsTo" class="form-control" name="">
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label>Branch <span class="text-red">*</span></label>
                  <select class="form-control select2" required="" style="width: 100%" id="branchId" >
                    <option value="All">All</option>
                    <?php echo $Branch; ?>
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

    <section class="content">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">List of Borrowers</h3>
        </div>
        <div class="box-body">
          <div class="pull-right">
            <!-- <a href="<?php echo base_url(); ?>loanapplication_controller/generateReport/4" class="btn btn-primary btn-md" >Generate Report</a> -->

            <?php if((in_array('21', $subModule))) { ?>
              <button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#modalNewRecord">Add Borrower</button>
              <button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#modalImport2">Import Borrower</button>
            <?php } else { ?>

            <?php } ?>
            <a data-toggle="modal" data-target="#modalFilter" class="btn btn-primary btn-md" >Filter</a>
          </div>
          <br>
          <br>
          <form name="ApproverDocForm" method="post" id="ApproverDocForm">
            <table id="example1" class="table table-bordered table-hover">
              <thead>
              <tr>
                <th>Branch</th>
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

  $('#branchId').val(1).change();

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

  function filterPage(){
    if($('#borrowerDependentsFrom').val() >= 0 && $('#borrowerDependentsTo').val() >= 0 && $('#borrowerDependentsFrom').val() != '' && $('#borrowerDependentsTo').val() != '' && $('#borrowerCreatedBy').val() != '')
    {
      var url = '<?php echo base_url()."borrower_controller/filterBorrower/"; ?>' + $('#borrowerStatus').val()+ '/'+ $('#borrowerCreatedBy').val()+ '/'+ $('#borrowerDependentsFrom').val()+ '/'+ $('#borrowerDependentsTo').val()+ '/'+ $('#branchId').val();
      UserTable.ajax.url(url).load();
      $('#modalFilter').modal('hide');
    }
    else
    {
      swal({
        title: 'Info!',
        text: 'Please make sure that all required fields are filled out!',
        type: 'warning',
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary'
      });
    }
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

  $('#dateCreated').daterangepicker({
    "startDate": moment().format('DD MMM YY'),
    "maxDate": moment().format('DD MMM YY'),
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

  $('#upload_form3').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url: "<?php echo base_url(); ?>borrower_controller/uploadForm3Excel",
      method: "POST",
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      success: function(data){
        if(data != "Error inserting to Consolidated Damaged Item" || data != "File not set")
        {
          swal({
            title: 'Success!',
            text: data,
            type: 'success',
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary'
          });
          $('#upload_form3').each(function(){
            this.reset();
          });
          location.reload();
        }
        else
        {
          swal({
            title: 'Error!',
            text: 'Unable to import data.',
            type: 'error',
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary'
          });
        }
      },
      error: function(data){
        swal({
          title: 'Error!',
          text: 'Unable to import data. Please make sure all required fields are filled out or uploading file is valid.',
          type: 'error',
          buttonsStyling: false,
          confirmButtonClass: 'btn btn-primary'
        });
      }
    }); // AJAX END
  });

  UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/borrower_controller/getAllList/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  
                    { data: "Branch" }
                    , { data: "Name" }
                    , { data: "Dependents" }
                    , { data: "TotalLoans" }
                    , { data: "CreatedBy" }
                    , { data: "StatusId", "render": function (data, type, row) {
                        return "<span class='badge bg-"+row.statusColor+"'>"+row.StatusDescription+"</span>";
                      }
                    },
                    { data: "DateCreated" }, 
                    { data: "DateUpdated" }, 
                    {
                      data: "StatusId", "render": function (data, type, row) {
                      if(row.StatusId == 1){
                          if('<?php print_r(in_array('22', $subModule))?>' === '1')
                          {
                            editAction = '<a href="<?php echo base_url();?>home/BorrowerDetails/'+row.BorrowerId+'" class="btn btn-sm btn-default" title="View"><span class="fa fa-info-circle"></span></a> ';
                          }
                          else
                          {
                            editAction = '';
                          }
                          if('<?php print_r(in_array('9', $subModule))?>' === '1')
                          {
                            loanAction = '<a target="_blank" href="<?php echo base_url();?>home/createBorrowerLoan/'+row.BorrowerId+'" class="btn btn-sm btn-success" title="Create Loan"><span class="fa fa-plus-square"></span></a>';
                          }
                          else
                          {
                            loanAction = '';
                          }

                          return editAction + loanAction;
                        }
                        else
                        {
                          return '<a href="<?php echo base_url();?>home/BorrowerDetails/'+row.BorrowerId+'" class="btn btn-sm btn-default" title="View"><span class="fa fa-info-circle"></span></a> ';
                        }
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[3, "asc"]]
    });
</script>

