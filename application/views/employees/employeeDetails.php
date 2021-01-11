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
              <div class="col-md-12">
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
                  <label for="selectRegion">Province/City <span class="text-red">*</span></label>
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
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Profile Picture</h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>employee_controller/employeeProcessing/7/<?php print_r($detail['EmployeeNumber'])?>" id="frmInsert6" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12" id="uploadPic">
              <input type="hidden" id="profileType" name="uploadType">
                <div class="form-group">
                  <label for="txtHouseNo">Upload Profile Picture <span class="text-red">*</span></label>
                  <input type="file" name="ID[]" id="Attachment" accept=".jpeg, .jpg, .png">
                </div>
              </div>
            </div>
            <a class="btn btn-primary" id="cameraDivBtn" onClick="setup(); $(this).hide().next().show();">Access Camera</a>
            <div class="row" id="cameraDiv" style="display: none">
              <div class="col-md-6">
                <div id="live_camera"></div>
                <a class="btn btn-primary" onClick="get_take_snap()">Capture Picture</a>
                <a class="btn btn-primary" id="cameraDivBtnOff" style="display: none" onClick="OffSetup()">Upload Picture</a>
              </div>
              <div class="col-md-6">
                <div id="img_output"></div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <a class="btn btn-primary" id="btnCameraSave" style="display: none" onclick="saveSnap()">Save</a>
            <button type="submit" id="btnCameraUpload" class="btn btn-primary">Submit</button>
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
            <h4 class="modal-title">Employee Details</h4>
          </div>
          <form autocomplete="off" action="<?php echo base_url(); ?>employee_controller/employeeProcessing/6/<?php print_r($detail['EmployeeId']); ?>" enctype="multipart/form-data" id="frmEmployeeDetail" method="post">
            <div class="modal-body">
              <input type="hidden" class="form-control" id="txtFormType" required="" name="formType" placeholder="First Name">
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
                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="form-group">
                        <label>Date Hired <span class="text-red">*</span></label>
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                          <input type="text" placeholder="Date Hired" class="form-control" name="DateHired" required="" id="dateHired">
                        </div>
                        <!-- /.input group -->
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
                echo '<img src="'.base_url().'employeeUpload/default.gif" class="profile-user-img img-responsive img-circle" alt="User Image" style="width: 100px">';
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
              <li class="active"><a href="#BranchDetails" data-toggle="tab" title="Branch Management"><span class="fa fa-building"></span></a></li>
              <li><a href="#ContactDetails" data-toggle="tab" title="Contact Details"><span class="fa fa-phone"></span></a></li>
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
              <div class="active tab-pane" id="BranchDetails">
                <h4>Branch Management</h4>
                <br>
                  <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewContact">Add Record</button>
                  <br>
                  <br>
                  <table id="dtblBranch" class="table table-bordered table-hover" style="width: 100%">
                    <thead>
                    <tr>
                      <th>Branch Code</th>
                      <th>Name</th>
                      <th>Created By</th>
                      <th>Date Creation</th>
                      <th>Date Updated</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                      <?php
                        $emailNo = 0;
                        foreach ($BranchManagement as $value) 
                        {
                          echo "<tr>";
                          echo "<td>".$value['rowNumber']."</td>";
                          echo "<td>".$value['EmailAddress']."</td>";
                          echo "<td>".$value['LastName'].", ".$value['FirstName']." ".$value['MiddleInitial']."</td>";
                          if($value['IsPrimary'] == 1)
                          {
                            echo "<td>Yes</td>";
                          }
                          else
                          {
                            echo "<td>No</td>";
                          }
                          echo "<td>".$value['DateCreated']."</td>";
                          echo "<td>".$value['DateUpdated']."</td>";

                          if($value['StatusId'] == 1)
                          {
                            $status = "<span class='badge bg-green'>Active</span>";
                          }
                          else if($value['StatusId'] == 0)
                          {
                            $status = "<span class='badge bg-red'>Deactivated</span>";
                          }


                          if($value['StatusId'] == 1 && $value['IsPrimary'] == 1)
                          {
                            $action = '<a onclick="confirmEmail(\'Are you sure you want to deactivate this email?\', \''.$value['EmployeeEmailId'].'\', 0)" class="btn btn-danger btn-sm" title="Deactivate"><span class="fa fa-close"></span></a>';
                          }
                          else if($value['StatusId'] == 1 && $value['IsPrimary'] == 0)
                          {
                            $action = '<a onclick="confirmEmail(\'Are you sure you want to deactivate this email?\', \''.$value['EmployeeEmailId'].'\', 0)" class="btn btn-danger btn-sm" title="Deactivate"><span class="fa fa-close"></span></a> <a onclick="confirmEmail(\'Are you sure you want to set this email as your primary email?\', \''.$value['EmployeeEmailId'].'\', 2)" class="btn btn-success btn-sm" title="Make as primary"><span class="fa fa-check-circle"></span></a>';
                          }
                          else
                          {
                            $action = '<a onclick="confirmEmail(\'Are you sure you want to re-activate this email?\', \''.$value['EmployeeEmailId'].'\', 1)" class="btn btn-warning btn-sm" title="Deactivate"><span class="fa fa-refresh"></span></a>';
                          }
                          echo "<td>".$status."</td>";
                          echo "<td>".$action."</td>";
                          echo "<td>".$value['rawDateCreated']."</td>";
                          echo "</tr>";
                        }
                      ?>
                    </tbody>
                  </table>
              </div>
              <div class="tab-pane" id="ContactDetails">
                <h4>Contact Details</h4>
                <br>
                  <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewContact">Add Record</button>
                  <br>
                  <br>
                  <?php $this->load->view('employees/contact'); ?>
              </div>
              <div class="tab-pane" id="EmailDetails">
                <h4>Email Details</h4>
                <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewEmail">Add Record</button>
                <br>
                <br>
                <?php $this->load->view('employees/email'); ?>
              </div>
              <div class="tab-pane" id="AddressDetails">
                <h4>Address Details</h4>
                <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewAddress">Add Record</button>
                <br>
                <br>
                <?php $this->load->view('employees/address'); ?>
              </div>
              <div class="tab-pane" id="IdDetails">
                <h4>Identifications Details</h4>
                <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#modalNewId">Add Record</button>
                <br>
                <br>
                <?php $this->load->view('employees/identificationCards'); ?>
              </div>
              <div class="tab-pane" id="AuditDetails">
                <h4>Audit Logs</h4>
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
  
  function get_take_snap() {
    // Simple call the take some your selfi and some get your live image data
    Webcam.snap( function(data_uri) {
      document.getElementById('img_output').innerHTML = 
        '<img id="imageprev" src="'+data_uri+'"/>';
    });
  }

  function OffSetup()
  {
    $('#uploadPic').show();
    Webcam.reset();
    $('#cameraDiv').hide();
    $('#cameraDivBtn').show();
    $('#cameraDivBtnOff').show();
    $('#btnCameraSave').hide();
    $('#btnCameraUpload').show();
    $('#img_output').html('');
    $('#profileType').val(0);
  }

  function setup() {
    $('#profileType').val(1);
    $('#uploadPic').hide();
    $('#btnCameraSave').show();
    $('#btnCameraUpload').hide();
    Webcam.reset();
    Webcam.set({
       width: 400,
       height: 300,
       image_format: 'jpeg',
       jpeg_quality: 100
    });
    $('#cameraDivBtnOff').show();
    Webcam.attach( '#live_camera' );
  }

  function saveSnap()
  {
    swal({
      title: 'Confirm',
      text: 'Are you sure you want to submit this picture?',
      type: 'info',
      showCancelButton: true,
      buttonsStyling: false,
      confirmButtonClass: 'btn btn-success',
      confirmButtonText: 'Confirm',
      cancelButtonClass: 'btn btn-secondary'
    }).then(function(){
       // Get base64 value from <img id='imageprev'> source
       var base64image = document.getElementById("imageprev").src;
      Webcam.upload( base64image, '<?php echo base_url()."/employee_controller/employeeProcessing/7/"; ?><?php print_r($detail['EmployeeNumber'])?>', function(code, text) {
          swal({
            title: 'Success!',
            text: 'Image successfully saved',
            type: 'success',
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary'
          });
          console.log(text)
          location.reload(true);
      });
    });
  }

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