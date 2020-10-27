<style type="text/css">
  .select2-container--default .select2-selection--multiple .select2-selection__choice{color:black;}
</style>
<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">

    <?php if(in_array('1', $subModule)) { ?>
      <section class="content-header">
        <h1>
          Employee List
        </h1>
        <ol class="breadcrumb">
          <li><a href="#" class="active"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li>Employee List</a></li>
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
            <form autocomplete="off" action="<?php echo base_url(); ?>employee_controller/employeeProcessing/1" id="frmInsert2" method="post">
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
                        <label for="txtContactNumber">Cellphone Number <span class="text-red">*</span></label>
                        <input type="number" class="form-control" id="txtContactNumber" required="" name="ContactNumber" placeholder="Cellphone Number">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="txtTelephone">Telephone Number</label>
                        <input type="number" class="form-control" id="txtTelephone" name="TelephoneNumber" placeholder="Mobile Number">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="txtEmail">Email Address <span class="text-red">*</span></label>
                        <input type="email" class="form-control" required="" id="txtEmail" name="EmailAddress" required="" placeholder="Email Address">
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
                          <select style="width: 100%" required="" name="PositionId" class="form-control select2">
                            <?php print_r($Position)?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <!-- <div class="col-md-6">
                      <div class="form-group">
                        <div class="form-group">
                          <label>Role(s) <span class="text-red">*</span></label>
                          <select multiple="" required="" style="width: 100%" name="roleId[]" class="form-control select2">
                            <?php print_r($Roles)?>
                          </select>
                        </div>
                      </div>
                    </div> -->
                    <div class="col-md-12">
                      <label>Type of Employee</label><br>
                        <div class="form-group">
                          <div class="radio">
                            <label>
                              <input type="radio" name="EmployeeType" id="optionsRadios1" onclick="chkEmployeeType(this.value)" value="Manager" checked="">
                              Manager
                            </label>
                            <label>
                              <input type="radio" name="EmployeeType" id="optionsRadios2" onclick="chkEmployeeType(this.value)" value="Employee">
                              Employee
                            </label>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <label>Branch</label>
                              <select class="form-control select2"  required="" onchange="changeBranch(this.value)" id="selectBranch" name="BranchId" style="width: 100%">
                                <?php
                                  echo $Branch;
                                ?>
                              </select>
                            </div>
                            <div class="col-md-6" id="DivEmployee" style="display: hidden">
                              <label>Manager</label>
                              <select class="form-control select2"  id="selectManager" name="ManagerId" style="width: 100%">
                              </select>
                            </div>
                          </div>
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
                          <label for="txtHouseNo2">House No/Street/Subdivision <span class="text-red">*</span></label>
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

      <div class="modal fade" id="modalImport">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Import Employees</h4>
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
                      <a class="btn btn-sm btn-success" href="<?php echo base_url();?>/employeeUpload/EmployeeUpload.xls" title="Download">Download</a>
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

      <section class="content">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">List of Employees</h3>
          </div>
          <div class="box-body">
            <div class="pull-right">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNewRecord">Add Employee</button> <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalImport">Import Employee</button>
            </div>
            <br>
            <br>
            <form name="ApproverDocForm" method="post" id="ApproverDocForm">
              <table id="example1" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Branch</th>
                  <th>Employee Number</th>
                  <th>Name</th>
                  <th>Added By</th>
                  <th>Status</th>
                  <th>Date Hired</th>
                  <th>Date Created</th>
                  <th width="100px">Action</th>
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
<div class="loading" style="display: none"></div>
</div>

<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0
  </div>
  <strong>Copyright &copy; 2020 <a href="https://giatechph.com" target="_blank">GIA Tech.</a></strong> All rights
  reserved.
</footer>

<?php $this->load->view('includes/footer'); ?>

<script>
  var varStatus = 0;
  var varNewPassword = 0;
  function changeBranch(BranchId)
  {
    $.ajax({
      url: "<?php echo base_url();?>" + "/admin_controller/getManagers",
      method: "POST",
      data: { BranchId : BranchId },
      beforeSend: function(){
        $('.loading').show();
      },
      success: function(data)
      {
        $('#selectManager').html(data);
      }
    })
  }


  function changeRegion(RegionId)
  {
    $.ajax({
      url: "<?php echo base_url();?>" + "/admin_controller/getProvinces",
      method: "POST",
      data: { RegionId : RegionId },
      beforeSend: function(){
        $('.loading').show();
      },
      success: function(data)
      {
        $('#selectProvince').html(data);
      }
    })
  }

  function changeProvince(ProvinceCode)
  {
    $.ajax({
      url: "<?php echo base_url();?>" + "/admin_controller/getCities",
      method: "POST",
      data: { Id : ProvinceCode },
      beforeSend: function(){
        $('.loading').show();
      },
      success: function(data)
      {
        $('#selectCity').html(data);
      }
    })
  }

  function changeCity(CityCode)
  {
    $.ajax({
      url: "<?php echo base_url();?>" + "/admin_controller/getBarangays",
      method: "POST",
      data: { Id : CityCode },
      beforeSend: function(){
        $('.loading').show();
      },
      success: function(data)
      {
        $('#selectBarangay').html(data);
      }
    })
  }
  
  function changeRegion2(RegionId)
  {
    $.ajax({
      url: "<?php echo base_url();?>" + "/admin_controller/getProvinces",
      method: "POST",
      data: { RegionId : RegionId },
      beforeSend: function(){
        $('.loading').show();
      },
      success: function(data)
      {
        $('#selectProvince2').html(data);
      }
    })
  }

  function changeProvince2(ProvinceCode)
  {
    $.ajax({
      url: "<?php echo base_url();?>" + "/admin_controller/getCities",
      method: "POST",
      data: { Id : ProvinceCode },
      beforeSend: function(){
        $('.loading').show();
      },
      success: function(data)
      {
        $('#selectCity2').html(data);
      }
    })
  }

  function changeCity2(CityCode)
  {
    $.ajax({
      url: "<?php echo base_url();?>" + "/admin_controller/getBarangays",
      method: "POST",
      data: { Id : CityCode },
      beforeSend: function(){
        $('.loading').show();
      },
      success: function(data)
      {
        $('#selectBarangay2').html(data);
      }
    })
  }

  function chkFunction()
  {
    // Get the checkbox
    var checkBox = document.getElementById("chkAddress");
    // If the checkbox is checked, display the output text
    if (checkBox.checked == true){
      $('#divProvincialAddress').slideUp();
      $('#txtAddress2').val(1);
    } else {
      $('#divProvincialAddress').slideDown();
      $('#txtAddress2').val(0);
    }
  }

  function chkEmployeeType()
  {
    var radioValue = $("input[name='EmployeeType']:checked").val();
    if(radioValue == 'Manager'){
      $('#DivEmployee').slideUp();
    }
    else
    {
      $('#DivEmployee').slideDown();
    }
  }

  function confirm(Text, EmployeeId, updateType)
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
          url: "<?php echo base_url();?>" + "/employee_controller/updateEmail",
          method: "POST",
          data:   {
                    Id : EmployeeId
                    , updateType : updateType
                    , tableType : 'EmployeeUpdate'
                  },
          beforeSend: function(){
              $('.loading').show();
          },
          success: function(data)
          {
            refreshPage();
            swal({
              title: 'Success!',
              text: 'Employee successfully updated!',
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
    var url = '<?php echo base_url()."employee_controller/getAllList/"; ?>';
    UserTable.ajax.url(url).load();
  }

  $(function () {

    $('#DivEmployee').hide();
    $('.select2').select2();

    // $('#selectRegion').select2({
    //   placeholder: 'Type region',
    //   minimumInputLength: 3, // only start searching when the user has input 3 or more characters
    //   ajax: {
    //     url: '<?php echo base_url()?>/admin_controller/getRegion?>',
    //     dataType: 'json',
    //     delay: 250,
    //     processResults: function (data) 
    //     { 
    //       return {
    //         results: data
    //       };
    //     },
    //     cache: true
    //   }
    // });

    // $('#selectRegion2').select2({
    //   placeholder: 'Type region',
    //   minimumInputLength: 3, // only start searching when the user has input 3 or more characters
    //   ajax: {
    //     url: '<?php echo base_url()?>/admin_controller/getRegion?>',
    //     dataType: 'json',
    //     delay: 250,
    //     processResults: function (data) 
    //     { 
    //       return {
    //         results: data
    //       };
    //     },
    //     cache: true
    //   }
    // });
    
    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/employee_controller/getAllList/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  
                    { data: "Branch" }
                    , { data: "EmployeeNumber" }
                    , { 
                        data: "FirstName", "render": function (data, type, row) {
                          if(row.ExtName == '' && row.MI != '')
                          {
                            return row.LastName + ', ' +  row.FirstName + ' ' +  row.ExtName;
                          }
                          else if(row.MI == '')
                          {
                            return row.LastName + ', ' +  row.FirstName + ' ' +  row.ExtName;
                          }
                          else if(row.MI == undefined)
                          {
                            return row.LastName + ', ' +  row.FirstName + ' ' +  row.ExtName;
                          }
                          else
                          {
                            return row.LastName + ', ' +  row.FirstName + ' ' +  row.MI;
                          }
                      }
                    }
                    , { data: "CreatedBy" }
                    , { data: "StatusId", "render": function (data, type, row) {
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
                    { data: "DateHired" }, 
                    { data: "DateCreated" }, 
                    {
                      data: "StatusId", "render": function (data, type, row) 
                      {
                        if(row.StatusId == 1)
                        {
                          // <a href="<?php echo base_url()."home/accessManagement/"; ?>'+row.EmployeeId+'" class="btn btn-sm btn-primary" title="Access Management"><span class="fa fa-calendar-check-o"></span></a> 
                          return '<a href="<?php echo base_url()."home/employeeDetails/"; ?>'+row.EmployeeId+'" class="btn btn-sm btn-default" title="View"><span class="fa fa-info-circle"></span></a> <a onclick="confirm(\'Are you sure you want to deactivate this employee?\', \''+row.EmployeeId+'\', 0)" class="btn btn-sm btn-danger" title="Deactivate"><span class="fa fa-close"></span></a>';
                        }
                        else
                        {
                          return '<a href="<?php echo base_url()."home/employeeDetails/"; ?>'+row.EmployeeId+'" class="btn btn-sm btn-default" title="View"><span class="fa fa-info-circle"></span></a> <a onclick="confirm(\'Are you sure you want to re-activate this employee?\', \''+row.EmployeeId+'\', 1)" class="btn btn-sm btn-success" title="Re-activate"><span class="fa fa-refresh"></span></a>';
                        }
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[3, "asc"]]
    });

    $("#frmInsert2").on('submit', function (e) {
      e.preventDefault(); 
      swal({
        title: 'Confirm',
        text: 'Are you sure you want to add this to the employee list?',
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

    $('#dateHired').daterangepicker({
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

    $.ajax({
      url: '<?php echo base_url();?>' + "/admin_controller/getRegionList",
      method: "POST",
      beforeSend: function(){
        $('.loading').show();
      },
      success: function(data)
      {
        $('#selectRegion').html(data);
        $('#selectRegion2').html(data);
      }
    })

    $('#modalNewPassword').modal('show')
  })
</script>