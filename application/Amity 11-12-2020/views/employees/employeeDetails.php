
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Employee Details
    </h1>
    <ol class="breadcrumb">
      <li><a href="#" class="active"><i class="fa fa-dashboard"></i> Details</a></li>
      <li><a href="http://localhost/ELendingTool/home/addEmployees">Employees</a></li>
      <li><a href="#">Employee Details</a></li>
    </ol>
  </section>

  <div class="modal fade" id="modalNewRecord">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Employee Details</h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>employee_controller/employeeProcessing/6/<?php print_r($detail['EmployeeNumber'])?>" id="frmEmployeeDetail" method="post">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label for="selectNationality">Salutation</label><br>
                  <select class="form-control" style="width: 100%" required="" name="SalutationId" id="selectSalutation">
                    <?php print_r($Salutation); ?>
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
                    <?php print_r($Sex); ?>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="selectNationality">Nationality <span class="text-red">*</span></label><br>
                  <select class="form-control select2" style="width: 100%" required="" name="NationalityId" id="selectNationality">
                    <?php print_r($Nationality); ?>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="selectCivilStatus">Civil Status <span class="text-red">*</span></label><br>
                  <select class="form-control" style="width: 100%" required="" name="CivilStatusId" id="selectCivilStatus">
                    <?php print_r($CivilStatus); ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                    <div class="form-group">
                      <label>Date of Birth <span class="text-red">*</span></label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control" name="DateOfBirth" required="" id="datepicker">
                      </div>
                      <!-- /.input group -->
                    </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                    <div class="form-group">
                      <label>Date Hired <span class="text-red">*</span></label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control" name="DateHired" required="" id="dateHired">
                      </div>
                      <!-- /.input group -->
                    </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <div class="form-group">
                    <label>Position <span class="text-red">*</span></label>
                    <select style="width: 100%" required="" name="PositionId" id="selectPosition" class="form-control select2">
                      <?php print_r($Position)?>
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
  
  <div class="modal fade" id="modalNewContact">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Employee Details</h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>employee_controller/employeeProcessing/2/<?php print_r($detail['EmployeeNumber'])?>" id="frmInsert2" method="post">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div class="radio">
                  <label>
                    <input type="radio" name="ContactType" id="optionsRadios1" value="Mobile" checked="">
                    Mobile
                  </label>
                  <label>
                    <input type="radio" name="ContactType" id="optionsRadios2" value="Telephone">
                    Telephone
                  </label>
                </div>
              </div>
              <input type="hidden" required="" class="form-control" value="<?php print_r($detail['EmployeeId']) ?>" name="EmployeeId">
              <div class="col-md-12">
                <label>Number</label>
                <div class="input-group">
                  <span class="input-group-addon">
                    <input type="checkbox" name="isPrimary[]" value="1" title="Primary Number?">
                  </span>
                  <input type="number" name="FieldNumber" required="" class="form-control">
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
  
  <div class="modal fade" id="modalNewEmail">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Email Details</h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>employee_controller/employeeProcessing/3/<?php print_r($detail['EmployeeNumber'])?>" id="frmInsert3" method="post">
          <div class="modal-body">
            <div class="row">
              <input type="hidden" required="" class="form-control" value="<?php print_r($detail['EmployeeId']) ?>" name="EmployeeId">
              <div class="col-md-12">
                <label>Email Address</label>
                <div class="input-group">
                  <span class="input-group-addon">
                    <input type="checkbox" name="isPrimary[]" value="1" title="Primary Email Address?">
                  </span>
                  <input type="email" name="EmailAddress" required="" class="form-control">
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
  
  <div class="modal fade" id="modalNewAddress">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Employee Address</h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>employee_controller/employeeProcessing/4/<?php print_r($detail['EmployeeNumber'])?>" id="frmInsert4" method="post">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="select">Address Type <span class="text-red">*</span></label>
                  <select class="form-control select2"  required="" id="selectAddressType" name="AddressType" style="width: 100%">
                    <option value="City Address">City Address</option>
                    <option value="Province Address">Provincial Address</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Primary Address? <span class="text-red">*</span></label>
                  <select class="form-control select2"  required="" name="isPrimary" style="width: 100%">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="txtHouseNo">House No/Street/Subdivision <span class="text-red">*</span></label>
                  <input type="text" class="form-control" id="txtHouseNo" name="HouseNo" required="" placeholder="House No.">
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
                  <label for="selectRegion">Province <span class="text-red">*</span></label>
                  <select class="form-control select2"  required="" id="selectProvince" onchange="changeProvince(this.value)" name="ProvinceId" style="width: 100%">
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="selectCity">City <span class="text-red">*</span></label>
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
  
  <div class="modal fade" id="modalNewId">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Add Identification</h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>employee_controller/employeeProcessing/5/<?php print_r($detail['EmployeeNumber'])?>" id="frmInsert5" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Type of ID <span class="text-red">*</span></label>
                  <select class="form-control select2"  required="" name="TypeOfId" style="width: 100%">
                    <?php
                      echo $IDCategory;
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="txtHouseNo">ID Attachment <span class="text-red">*</span></label>
                  <input type="file" name="ID[]" required="" accept=".xlsx, .xls, .doc, .docx, .pdf, .jpeg, .jpg, .png">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="txtHouseNo">ID Number <span class="text-red">*</span></label>
                  <input type="text" class="form-control" name="IDNumber" required="" placeholder="ID Number">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="txtStreet">Description</label>
                  <input type="text" class="form-control" name="Description" placeholder="Description">
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

  <div class="modal fade" id="modalProfilePicture">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Add Identification</h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>employee_controller/employeeProcessing/7/<?php print_r($detail['EmployeeNumber'])?>" id="frmInsert6" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="txtHouseNo">Upload Profile Picture <span class="text-red">*</span></label>
                  <input type="file" name="ID[]" required="" id="Attachment" accept=".jpeg, .jpg, .png">
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

  <div class="modal fade" id="modalEmployeeDetails">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Borrower Details</h4>
          </div>
          <form autocomplete="off" action="<?php echo base_url(); ?>employee_controller/employeeProcessing/6/<?php print_r($detail['EmployeeId']); ?>" enctype="multipart/form-data" id="frmEmployeeDetail" method="post">
            <div class="modal-body">
              <input type="hidden" class="form-control" id="txtFormType" required="" name="formType" placeholder="First Name">
              <div id="displaySpouse" style="display: none">
                <div class="col-md-4">
                  <label>Full Name</label>
                  <h6 id="lblSpouseName"></h6>
                </div>
                <div class="col-md-4">
                  <label>Gender</label><br>
                  <h6 id="lblSpouseGender"></h6>
                </div>
                <div class="col-md-4">
                  <label>Nationality</label><br>
                  <h6 id="lblSpouseNationality"></h6>
                </div>
                <div class="col-md-4">
                  <label>Civil Status</label><br>
                  <h6 id="lblSpouseCivil"></h6>
                </div>
                <div class="col-md-4">
                  <label>Date of Birth</label><br>
                  <h6 id="lblSpouseBirth"></h6>
                </div>
                <div class="col-md-4">
                  <label>No. of Dependents</label><br>
                  <h6 id="lblSpouseDependents"></h6>
                </div>
                <div class="col-md-4">
                  <label>Birth Place</label><br>
                  <h6 id="lblSpousePlace"></h6>
                </div>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
              </div>
              <div id="borrowerSpouseForm">
                <div class="row">
                  <div class="col-md-12" id="divStatus">
                    <div class="form-group">
                      <label>Status</label><br>
                      <select class="form-control" style="width: 100%" required="" name="StatusId" id="selectStatusId">
                        <?php
                          echo $Status;
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Salutation</label><br>
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
                      <label>Nationality <span class="text-red">*</span></label><br>
                      <select class="form-control" style="width: 100%" required="" name="NationalityId" id="selectNationality">
                        <?php
                          echo $Nationality;
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Civil Status <span class="text-red">*</span></label><br>
                      <select class="form-control" style="width: 100%" required="" name="CivilStatusId" id="selectCivilStatus">
                        <?php
                          echo $CivilStatus;
                        ?>
                      </select>
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
                          <input type="text" placeholder="Date of Birth" class="form-control" name="DateOfBirth" required="" id="DateOfBirth">
                        </div>
                        <!-- /.input group -->
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                      <div class="form-group">
                        <label for="txtMother">Mother's Maiden Name</label>
                        <input type="text" class="form-control" id="txtMother" name="MotherName" placeholder="Maiden Name">
                      </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="txtDependents">No. of Dependents <span class="text-red">*</span></label>
                      <input type="text" class="form-control" id="txtDependents" name="NoDependents" required="" placeholder="No. of dependents">
                    </div>
                  </div>
                </div>
                <div id="divSpouseDetails" style="display: none">
                  <!-- EMPLOYER DETAILS -->
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Present Employer/Business</label>
                          <input type="text" class="form-control" id="txtPresentEmployer" name="SpouseEmployer" placeholder="Present employer/business">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Position Title</label>
                          <input type="text" class="form-control" id="txtPresentEmployer" name="PositionTitle" placeholder="Position Title">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Employment Tenure (Year)</label>
                          <input type="text" class="form-control" id="txtPresentEmployer" name="TenureYear" placeholder="Employment Tenure (Year)">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Employment Tenure (Month)</label>
                          <input type="text" class="form-control" id="txtPresentEmployer" name="TenureMonth" placeholder="Employment Tenure (Month)">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Email Address</label>
                          <input type="email" class="form-control" id="txtPresentEmployer" name="EmailAddress" placeholder="Email Address">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Telephone Number</label>
                          <input type="number" class="form-control" id="txtPresentEmployer" name="TelephoneNumber" placeholder="Telephone Number">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Cellphone Number</label>
                          <input type="number" class="form-control" id="txtPresentEmployer" name="ContactNumber" placeholder="Cellphone Number">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Business Address</label>
                          <textarea class="form-control" name="BusinessAddress"></textarea>
                        </div>
                      </div>
                    </div>
                  <!-- ADDRESSES -->
                    <div class="row">
                      <div class="col-md-12">
                        <center><label>CITY ADDRESS</label></center>
                        <center><label><input type="checkbox" class="minimal" id="chkSameBorrowerAddress" name="sameBorrowerAddress" value="1" onclick="chkFunction(this.value, 1)"> Same as borrower address </label></center>
                      </div>
                      <div id="divSpouseAddress">
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
                            <label for="selectCity">Municipality <span class="text-red">*</span></label>
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
                          <center><label>PROVINCIAL ADDRESS</label> <br> 
                            <label><input type="checkbox" class="minimal" id="chkAddress" name="SameAddress" value="1" onclick="chkFunction(this.value, 2)"> Same as city address </label>
                            <input type="hidden" class="form-control" required="" id="txtAddress2" name="IsSameAddress" required="">
                          </center>
                        </div>
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

  <!-- Main content -->
  <section class="content">    
    <div class="row">
      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body box-profile">
            <?php
              if($detail['FileName'] == null)
              {
                echo '<img src="'.base_url().'borrowerpicture/default.gif" class="profile-user-img img-responsive img-circle" alt="User Image" style="width: 100px">';
              }
              else
              {
                echo '<img class="profile-user-img img-responsive img-circle" src="'.base_url().'profilepicture/'. $detail["FileName"].'" class="user-image" alt="User Image">';
              }
            ?>
            <h3 class="profile-username text-center"><?php print_r($detail['Salutation'] . ' ' . $detail['LastName'] . ', ' . $detail['FirstName'] . ' '  . $detail['MiddleInitial']) ?></h3>

            <p class="text-muted text-center"><?php print_r($detail['EmployeeNumber']); ?></p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Gender</b> <h5 class="pull-right"><?php print_r($detail['Sex']) ?></h5>
              </li>
              <li class="list-group-item">
                <b>Nationality</b> <h5 class="pull-right"><?php print_r($detail['Nationality']) ?></h5>
              </li>
              <li class="list-group-item">
                <b>Birthdate</b> <h5 class="pull-right"><?php print_r($detail['DateOfBirth']); ?></h5>
              </li>
              <li class="list-group-item">
                <b>Date Hired</b> <h5 class="pull-right"><?php print_r($detail['DateHired']); ?></h5>
              </li>
              <li class="list-group-item">
                <b>Civil Status</b> <h5 class="pull-right"><?php print_r($detail['CivilStatus']); ?></h5>
              </li>
              <li class="list-group-item">
                <b>Status</b> <h5 class="pull-right"><?php print_r($detail['StatusDescription']); ?></h5>
              </li>
            </ul>

            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalEmployeeDetails" onclick="editEmployee(<?php print_r($detail['EmployeeId']); ?>, 1)"><b>Edit Profile</b>
            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalProfilePicture"><b>Change Profile Picture</b>
          </div>
          <!-- /.box-body -->
        </div>
      </div>
      <div class="col-md-9">
        <div class="box">
        <div class="box-body">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <!-- <li class="active"><a href="#RoleDetails" data-toggle="tab">Roles</a></li> -->
              <li class="active"><a href="#ContactDetails" data-toggle="tab" title="Contact Details"><span class="fa fa-phone"></span></a></li>
              <li><a href="#EmailDetails" data-toggle="tab" title="Email Address"><span class="fa fa-envelope"></span></a></li>
              <li><a href="#AddressDetails" data-toggle="tab" title="Address"><span class="fa fa-map"></span></a></li>
              <li><a href="#IdDetails" data-toggle="tab" title="ID"><span class="fa fa-user"></span></a></li>
              <li><a href="#AuditDetails" data-toggle="tab" title="Audit"><span class="fa fa-clipboard"></span></a></li>
            </ul>
            <div class="tab-content">
              <!-- <div class="active tab-pane" id="RoleDetails">
                <table class="table table-bordered table-hover" style="width: 100%">
                  <thead>
                  <tr>
                    <th>Module</th>
                    <th>Read</th>
                    <th>Update</th>
                    <th>View</th>
                    <th>Print</th>
                  </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Employee Management</td>
                      <td><input type="checkbox" class="minimal" id="chkAddress" name="SameAddress" value="1" onclick="chkFunction(this.value)"></td>
                      <td><input type="checkbox" class="minimal" id="chkAddress" name="SameAddress" value="1" onclick="chkFunction(this.value)"></td>
                      <td><input type="checkbox" class="minimal" id="chkAddress" name="SameAddress" value="1" onclick="chkFunction(this.value)"></td>
                      <td><input type="checkbox" class="minimal" id="chkAddress" name="SameAddress" value="1" onclick="chkFunction(this.value)"></td>
                    </tr>
                    <tr>
                      <td>Employee Management</td>
                      <td><input type="checkbox" class="minimal" id="chkAddress" name="SameAddress" value="1" onclick="chkFunction(this.value)"></td>
                      <td><input type="checkbox" class="minimal" id="chkAddress" name="SameAddress" value="1" onclick="chkFunction(this.value)"></td>
                      <td><input type="checkbox" class="minimal" id="chkAddress" name="SameAddress" value="1" onclick="chkFunction(this.value)"></td>
                      <td><input type="checkbox" class="minimal" id="chkAddress" name="SameAddress" value="1" onclick="chkFunction(this.value)"></td>
                    </tr>
                  </tbody>
                </table>
              </div> -->
              <div class="active tab-pane" id="ContactDetails">
                <br>
                <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewContact">Add Contact Number</button>
                <br>
                <?php $this->load->view('employees/contact'); ?>
              </div>
              <div class="tab-pane" id="ContactDetails">
                <br>
                <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewContact">Add Contact Number</button>
                <br>
                <?php $this->load->view('employees/contact'); ?>
              </div>
              <div class="tab-pane" id="EmailDetails">
                <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewEmail">Add Email</button>
                <br>
                <?php $this->load->view('employees/email'); ?>
              </div>
              <div class="tab-pane" id="AddressDetails">
                <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewAddress">Add Address</button>
                <br>
                <?php $this->load->view('employees/address'); ?>
              </div>
              <div class="tab-pane" id="IdDetails">
                <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewId">Add ID</button>
                <br>
                <?php $this->load->view('employees/identificationCards'); ?>
              </div>
              <div class="tab-pane" id="AuditDetails">
                <?php $this->load->view('employees/audit'); ?>
              </div>
            </div>
          </div>
        </div>
        </div>
    </div>
    </div>
  </section>
<div class="loading" style="display: none"></div>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0
  </div>
  <strong>Copyright &copy; 2020 <a href="https://giatechph.com" target="_blank">GIA Tech.</a></strong> All rights
  reserved.
</footer>

<?php $this->load->view('includes/footer'); ?>

<script src="<?php echo base_url(); ?>resources/functionalities/employeeDetails.js"></script>


<script type="text/javascript">
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