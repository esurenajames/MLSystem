<style type="text/css">
  .nav-tabs-custom>.nav-tabs>li>a {
    color: #2a4384;
    border-radius: 0;
  }
  .nav-tabs-custom>.nav-tabs>li.active>a, .nav-tabs-custom>.nav-tabs>li.active:hover>a {
    background-color: #fff;
    color: #000;
  }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Borrower Details
    </h1>
    <ol class="breadcrumb">
      <li><a href="#" class="active"><i class="fa fa-dashboard"></i> Borrower's Management</a></li>
      <li><a href="#">Borrowers</a></li>
      <li><a href="#">Borrower Details</a></li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <div class="modal fade" id="modalProfilePicture">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Profile Picture</h4>
          </div>
          <form autocomplete="off" action="<?php echo base_url(); ?>employee_controller/employeeProcessing/9/<?php print_r($detail['BorrowerId'])?>" id="frmInsert6" method="post" enctype="multipart/form-data">
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

    <div class="modal fade" id="modalNewEmployment">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Employment Record</h4>
          </div>
          <form autocomplete="off" action="<?php echo base_url(); ?>borrower_controller/borrowerProcessing/6/<?php print_r($detail['BorrowerId']); ?>" enctype="multipart/form-data" id="frmBorrowerDetail" method="post">
            <div class="modal-body">
              <input type="hidden" class="form-control" id="txtEmploymentFormType" name="formType" placeholder="Form Type">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Employment Type<span class="text-red">*</span></label>
                    <select class="form-control"  required="" name="EmploymentType" id="selectEmploymentType" style="width: 100%">
                      <option value="1">Present Employer</option>
                      <option value="2">Previous Employer</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Industry<span class="text-red">*</span></label>
                    <select class="form-control"  required="" name="EmploymentIndustry" id="selectEmploymentIndustry" style="width: 100%">
                      <option value="1">Technology</option>
                      <option value="2">Sales</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Present Employer/Business <span class="text-red">*</span></label>
                    <input type="text" class="form-control" required="" id="txtBorrowerEmployer" name="BorrowerEmployer" placeholder="Present employer/business">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Occupation <span class="text-red">*</span></label>
                    <select class="form-control"  required="" name="PositionId" id="selectPosition" style="width: 100%">
                      <option value="1">Technology</option>
                      <option value="2">Sales</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <div class="form-group">
                      <label>Date Hired <span class="text-red">*</span></label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control" placeholder="Date Hired" name="DateHired" required="" id="dtpDateHired">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Telephone Number <span class="text-red">*</span></label>
                    <input type="number" class="form-control" required="" id="txtEmployerTelephone" name="TelephoneNumber" placeholder="Telephone Number">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Employment Tenure (Year)</label>
                    <input type="text" class="form-control" required="" min="0" value="0" sid="txtBorrowerYear" name="TenureYear" placeholder="Employment Tenure (Year)">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Employment Tenure (Month) <span class="text-red">*</span></label>
                    <input type="text" class="form-control" required="" min="0" value="0" maxlength="2" max="2" id="txtBorrowerMonth" name="TenureMonth" placeholder="Employment Tenure (Month)">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Business Address <span class="text-red">*</span></label>
                    <textarea class="form-control" required="" name="BusinessAddress"></textarea>
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

    <div class="modal fade" id="modalSupportingDocument">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Supporting Document</h4>
          </div>
          <form autocomplete="off" action="<?php echo base_url(); ?>borrower_controller/BorrowerProcessing/4/<?php print_r($detail['BorrowerId'])?>" id="frmInsert6" method="post" enctype="multipart/form-data">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Requirement Type<span class="text-red">*</span></label>
                    <select class="form-control select2"  required="" name="ReqTypeId" id="selectReqTypeId" style="width: 100%">
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Upload Attachment <span class="text-red">*</span></label>
                    <input type="file" name="Attachment[]" required="" id="Attachment" accept=".jpeg, .jpg, .png">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Description</label>
                    <input type="text" class="form-control" name="Description">
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
      <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">ADD EMAIL ADDRESS</h4>
        </div>
          <form action="<?php echo base_url(); ?>borrower_controller/AddEmail/1" id="frmInsert" method="post">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="colorCurrent">Email Address</label>
                    <div class="form-group" id="colorCurrent">
                      <input type="text" class="form-control" name="Email" id="txtEmail" placeholder="@yahoo.com">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Submit Email</button>
            </div>
          </form>
      </div>
      <!-- /.modal-content -->
      </div>
    </div>

    <div class="modal fade" id="modalNewPersonal">
      <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">ADD PERSONAL REFERENCE</h4>
        </div>
          <form autocomplete="off" action="<?php echo base_url(); ?>borrower_controller/BorrowerProcessing/2/<?php print_r($detail['BorrowerId']) ?>" id="frmInsert" method="post">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Name<span class="text-red">*</span></label>
                    <input type="text" class="form-control" required="" id="txtReferenceName" name="Name">
                    <input type="hidden" class="form-control" id="txtReferenceId" name="ReferenceId">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Address<span class="text-red">*</span></label>
                    <input type="text" class="form-control" required="" id="txtReferenceAddress" name="Address">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Contact Number<span class="text-red">*</span></label>
                    <input type="number" class="form-control" required="" id="txtReferenceNumber" name="ContactNumber">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Submit Reference</button>
            </div>
          </form>
      </div>
      <!-- /.modal-content -->
      </div>
    </div>

    <div class="modal fade" id="modalNewEducation">
      <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">ADD EDUCATIONAL BACKGROUND</h4>
        </div>
          <form autocomplete="off" action="<?php echo base_url(); ?>borrower_controller/BorrowerProcessing/8/<?php print_r($detail['BorrowerId']) ?>" id="frmInsert" method="post">
            <div class="modal-body">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Education Level<span class="text-red">*</span></label>
                    <input type="hidden" class="form-control" id="txtBorrowerEducationId" name="BorrowerEducationId">
                    <select class="form-control" style="width: 100%" name="EducationLevel" id="SelectEducationLevel">
                      <option>No Grade Completed</option>
                      <option>Pre School</option>
                      <option>Some Elementary</option>
                      <option>Elementary Graudate</option>
                      <option>Some High-School</option>
                      <option>High-School Graduate</option>
                      <option>Post-Secondary</option>
                      <option>College Undergraduate</option>
                      <option>College Graduate</option>
                      <option>Post-Baccalaureate</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>School Name<span class="text-red">*</span></label>
                    <input type="text" class="form-control" required="" id="txtSchoolName" name="SchoolName">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Year Graduated<span class="text-red">*</span></label>
                    <input type="text" class="form-control" required="" id="txtEducationYear" name="EducationYear">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Submit Education Background</button>
            </div>
          </form>
      </div>
      <!-- /.modal-content -->
      </div>
    </div>

    <div class="modal fade" id="modalNewCoMaker">
      <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">ADD CO-MAKER</h4>
        </div>
          <form action="<?php echo base_url(); ?>borrower_controller/BorrowerProcessing/3/<?php print_r($detail['BorrowerId']) ?>" id="frmInsert" method="post">
            <div class="modal-body">
            <div id="displayComaker" style="display: none">
              <div class="col-md-4">
                <label>Full Name</label>
                <h6 id="lblComakerName"></h6>
              </div>
              <div class="col-md-4">
                <label>Date of Birth</label><br>
                <h6 id="lblComakerBirthdate"></h6>
              </div>
              <div class="col-md-4">
                <label>Position</label><br>
                <h6 id="lblComakerPosition"></h6>
              </div>
              <div class="col-md-4">
                <label>Employer</label><br>
                <h6 id="lblComakerEmployer"></h6>
              </div>
              <div class="col-md-4">
                <label>Monthly Income</label><br>
                <h6 id="lblComakerMonthly"></h6>
              </div>
              <div class="col-md-4">
                <label>Tenure (Year)</label><br>
                <h6 id="lblComakerTenure"></h6>
              </div>
              <div class="col-md-4">
                <label>Tenure (Month)</label><br>
                <h6 id="lblComakerMonth"></h6>
              </div>
              <div class="col-md-4">
                <label>Business Address</label><br>
                <h6 id="lblComakerAddress"></h6>
              </div>
              <div class="col-md-4">
                <label>Business No.</label><br>
                <h6 id="lblComakerBusinessNo"></h6>
              </div>
              <div class="col-md-4">
                <label>Telephone No.</label><br>
                <h6 id="lblComakerTelephone"></h6>
              </div>
              <div class="col-md-4">
                <label>Mobile No.</label><br>
                <h6 id="lblComakerMobile"></h6>
              </div>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
            </div>
            <div id="ComakerForm">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Name<span class="text-red">*</span></label>
                    <input type="text" class="form-control" placeholder="Complete Name" name="Name">
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
                        <input type="text" class="form-control" name="DOB" required="" id="CoMakerBirthday">
                      </div>
                      <!-- /.input group -->
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Position<span class="text-red">*</span></label>
                    <select class="form-control" style="width: 100%" required="" name="PositionId">
                      <?php print_r($Position); ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Employer/Business<span class="text-red">*</span></label>
                    <input type="text" class="form-control" placeholder="Employer/Business" name="Employer">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Monthly Income<span class="text-red">*</span></label>
                    <input type="number" min="0" value="0" class="form-control" placeholder="Monthly Income" name="MonthlyIncome">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Tenure (Years)</label>
                    <input type="number" min="0" value="0" class="form-control" name="TenureYear">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Tenure (Months)<span class="text-red">*</span></label>
                    <input type="number" min="0" value="0" required="" class="form-control" name="TenureMonth">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="txtHouseNo">Business Address<span class="text-red">*</span></label>
                    <textarea class="form-control" placeholder="Business Address" name="BusinessAddress"></textarea>
                  </div>
                </div>
                <br>
                <div class="col-md-12">
                  <center><label>CONTACT DETAILS</label></center>
                </div>
                <br>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Telephone Number</label>
                    <input type="text" class="form-control" name="TelephoneNo">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Business Number</label>
                    <input type="text" class="form-control" name="BusinessNo">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Cellphone Number <span class="text-red">*</span></label>
                    <input type="text" class="form-control" name="CellphoneNo" required="">
                  </div>
                </div>
                <br>
              </div>
            </div>
          </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Submit Co-Maker</button>
            </div>
          </form>
      </div>
      <!-- /.modal-content -->
      </div>
    </div>

    <div class="modal fade" id="modalBorrowerDetails">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Borrower Details</h4>
          </div>
          <form autocomplete="off" action="<?php echo base_url(); ?>borrower_controller/borrowerProcessing/5/<?php print_r($detail['BorrowerId']); ?>" enctype="multipart/form-data" id="frmBorrowerDetail" method="post">
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
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="txtDependents">Birth Place <span class="text-red">*</span></label>
                      <input type="text" class="form-control" id="txtDependents" name="NoDependents" required="" placeholder="Birth Place">
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

    <div class="modal fade" id="modalNewContact">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Contact Details</h4>
          </div>
          <form autocomplete="off" action="<?php echo base_url(); ?>borrower_controller/BorrowerProcessing/7/<?php print_r($detail['BorrowerId'])?>" method="post">
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
                <input type="hidden" required="" class="form-control" value="<?php print_r($detail['BorrowerId']) ?>" name="BorrowerId">
                <div class="col-md-12">
                  <label>Number<span class="text-red">*</span></label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <input type="checkbox" name="isPrimary[]" value="1" required="" title="Primary Number?">
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

    <div class="row">
      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body box-profile">
            <?php
              if($detail['FileName'] == null)
              {
                echo '<img src="'.base_url().'/borrowerpicture/default.gif" class="profile-user-img img-responsive img-circle" alt="User Image" style="width: 100px">';
              }
              else
              {
                echo '<img class="profile-user-img img-responsive img-circle" src="'.base_url().'/borrowerpicture/'. $detail["FileName"].'" class="user-image" alt="User Image">';
              }
            ?>
            <h3 class="profile-username text-center"><?php print_r($detail['Salutation'] . ' ' . $detail['LastName'] . ', ' . $detail['FirstName'] . ' '  . $detail['MiddleInitial']) ?></h3>

            <p class="text-muted text-center"><?php print_r($detail['BorrowerNumber']); ?></p>

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
                <b>Date Added</b> <h5 class="pull-right"><?php print_r($detail['DateAdded']); ?></h5>
              </li>
              <li class="list-group-item">
                <b>Civil Status</b> <h5 class="pull-right"><?php print_r($detail['CivilStatus']); ?></h5>
              </li>
              <li class="list-group-item">
                <b>Status</b> <h5 class="pull-right"><?php print_r($detail['StatusDescription']); ?></h5>
              </li>
              <li class="list-group-item">
                <b>No. of Dependents</b> <h5 class="pull-right"><?php print_r($detail['Dependents']); ?></h5>
              </li>
            </ul>

            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalBorrowerDetails" onclick="getDetail(<?php print_r($detail['BorrowerId']); ?>, 1)"><b>Edit Profile</b>
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
                <!-- <li class="active"><a href="#tabLoanApplications" data-toggle="tab">Loan Applications</a></li> -->
                <li class="active"><a href="#tabHistory" data-toggle="tab" title="History"><span class="fa fa-clipboard"></span></a></li>
                <li><a href="#tabLoanApplications" data-toggle="tab" title="Loans"><span class="fa fa-list-alt"></span></a></li>
                <li><a href="#tabReference" data-toggle="tab" title="Personal Reference"><span class="fa fa-users"></span></a></li>
                <li><a href="#tabCoMaker" data-toggle="tab" title="Co-Maker Info"><span class="fa fa-link"></span></a></li>
                <li><a href="#tabSpouseInfo" data-toggle="tab" title="Spouse Info"><span class="fa fa-male"></span><span class="fa fa-female"></span></a></li>
                <li><a href="#tabEmployment" data-toggle="tab" title="Employment Info"><span class="fa fa-briefcase"></span></a></li>
                <li><a href="#tabContactInfo" data-toggle="tab" title="Contact Info"><span class="fa fa-phone"></span></a></li>
                <li><a href="#tabAddress" data-toggle="tab" title="Address Info"><span class="fa fa-map"></span></a></li>
                <li><a href="#tabEmail" data-toggle="tab" title="Email Info"><span class="fa fa-envelope"></span></a></li>
                <li><a href="#tabSupportingDocuments" data-toggle="tab" title="Supporting Documents"><span class="fa fa-file-text"></span></a></li>
                <li><a href="#Education" data-toggle="tab" title="Education"><span class="fa fa-book"></span></a></li>
              </ul>
              <div class="tab-content">
                <div class="active tab-pane" id="tabHistory">
                  <h4>HISTORY</h4>
                  <br>
                  <table id="example1" class="table table-bordered table-hover" style="width: 100%">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>Description</th>
                      <th>Created By</th>
                      <th>Date Created</th>
                    </tr>
                    </thead>
                    <tbody>
                      <?php
                        if($Audit != 0)
                        {
                          $rowNumber = 0;
                          foreach ($Audit as $value) 
                          {
                            $rowNumber = $rowNumber + 1;
                            echo "<tr>";
                            echo "<td>".$rowNumber."</td>";
                            echo "<td>".$value['Description']."</td>";
                            echo "<td>".$value['CreatedBy']."</td>";
                            echo "<td>".$value['DateCreated']."</td>";
                            echo "</tr>";
                          }
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
                <div class="tab-pane" id="tabLoanApplications">
                  <!-- <button type="button" class="btn btn-sm btn-primary pull-right">Add Loan Application</button> -->
                  <br>
                  <br>
                  <br>
                  <table id="dtblLoans" class="table table-bordered table-hover" style="width: 100%">
                    <thead>
                    <tr>
                      <th>Reference No</th>
                      <th>Loan Type</th>
                      <th>Principal</th>
                      <th>Status</th>
                      <th>Created By</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
                <div class="tab-pane" id="tabReference">
                  <h4>PERSONAL REFERENCE INFORMATION</h4>
                  <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewPersonal">Add Personal Reference</button>
                  <br>
                  <br>
                  <br>
                  <?php $this->load->view('borrowers/Personal'); ?>
                </div>
                <div class="tab-pane" id="tabCoMaker">
                  <h4>CO-MAKER INFORMATION</h4>
                  <button type="button" onclick="AddComaker()" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewCoMaker">Add Co-Maker</button>
                  <br>
                  <br>
                  <br>
                  <?php $this->load->view('borrowers/CoMaker'); ?>
                </div>
                <div class="tab-pane" id="tabSpouseInfo">
                  <h4>SPOUSE INFORMATION</h4>
                  <button type="button" class="btn btn-primary pull-right" data-toggle="modal" onclick="getDetail(<?php print_r($detail['BorrowerId']); ?>, 2)" data-target="#modalBorrowerDetails">Add Spouse</button>
                  <br>
                  <br>
                  <br>
                  <?php $this->load->view('borrowers/spouse'); ?>
                </div>
                <div class="tab-pane" id="tabEmployment">
                  <h4>EMPLOYMENT INFORMATION</h4>
                  <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewEmployment">Add Employment Record</button>
                  <br>
                  <br>
                  <br>
                  <?php $this->load->view('borrowers/EmploymentRecord'); ?>
                </div>
                <div class="tab-pane" id="tabContactInfo">
                  <h4>CONTACT INFORMATION</h4>
                  <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewContact">Add Contact</button>
                  <br>
                  <br>
                  <br>
                  <?php $this->load->view('borrowers/contact'); ?>
                </div>
                <div class="tab-pane" id="tabAddress">
                  <h4>ADDRESS INFORMATION</h4>
                  <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewAddress">Add Address</button>
                  <br>
                  <br>
                  <br>
                  <?php $this->load->view('borrowers/address'); ?>
                </div>
                <div class="tab-pane" id="tabEmail">
                  <h4>EMAIL INFORMATION</h4>
                  <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewEmail">Add Email</button>
                  <br>
                  <br>
                  <br>
                  <?php $this->load->view('borrowers/email'); ?>
                </div>
                <div class="tab-pane" id="tabSupportingDocuments">
                  <h4>SUPPORTING DOCUMENTS</h4>
                  <button type="button" class="btn btn-primary pull-right" onclick="requirementList()" data-toggle="modal" data-target="#modalSupportingDocument">Add Supporting Doc</button>
                  <br>
                  <br>
                  <br>
                  <?php $this->load->view('borrowers/supportingDocs'); ?>
                </div>
                <div class="tab-pane" id="Education">
                  <h4>EDUCATIONAL</h4>
                  <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewEducation">Add Educational Background</button>
                  <br>
                  <br>
                  <br>
                  <?php $this->load->view('borrowers/Education'); ?>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- <div class="col-md-9">
        <div class="box">
          <div class="box-body">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#tabContactInfo" data-toggle="tab">Contact Information</a></li>
                <li><a href="#Contact" data-toggle="tab">Address Information</a></li>
                <li><a href="#Contact" data-toggle="tab">Email Information</a></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane" id="tabContactInfo">
                  <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewAddress">Add Address</button>
                  <br>
                  <br>
                  <table id="example3" class="table table-bordered table-hover" style="width: 100%">
                    <thead>
                    <tr>
                      <th>Address</th>
                      <th>Type</th>
                      <th>Contact Number</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> -->
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

<script src="<?php echo base_url(); ?>resources/functionalities/borrower/detailfooter.js"></script>
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

  var TotalInterest = 0;
  $('#dtblLoans').DataTable({
    "pageLength": 10,
    "ajax": { url: '<?php echo base_url()."/datatables_controller/displayAllLoans/"; ?>', type: 'POST', "dataSrc": "" },
    "columns": [  { data: "TransactionNumber" }
      , { data: "LoanName" }
      , { data: "PrincipalAmount" }
      , { data: "StatusId", "render": function (data, type, b) {
          if(b.IsApprovable == 1)
          {
            return b.ProcessedApprovers+ '/' + b.PendingApprovers + ' in progress';
          }
          else
          {
            return b.StatusDescription;
          }
        }
      }
      , { data: "CreatedBy" }
      , { data: "StatusId", "render": function (data, type, b) {
          return '<a class="btn btn-sm btn-default" target="_blank" href="<?php echo base_url(); ?>home/loandetail/'+b.ApplicationId+'" title="View"><span class="fa fa-info-circle"></span></a> <a target="_blank" class="btn btn-sm btn-success" href="<?php echo base_url(); ?>home/Renew/'+b.ApplicationId+'" title="Re-New"><span class="fa fa-refresh"></span></a>';
        }
      }
    ],
    // "aoColumnDefs": [{ "bVisible": false, "aTargets": [7] }],
    "order": [[0, "desc"]]
  });
</script>