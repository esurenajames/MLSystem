
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Student List</h1>
          </div>
        </div>
      </div>
    </div>

      <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Student</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url(); ?>admin_controller/addStudentList/" class="frminsert2" method="post">
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-4">
                    <h6>First Name<span style="color:red">*</span> </h6>
                    <input type="text" required="" name="FirstName" class="form-control">
                  </div>
                  <div class="col-md-3">
                    <h6>Middle Name</h6>
                    <input type="text" name="MiddleName" class="form-control">
                  </div>
                  <div class="col-md-3">
                    <h6>Last Name<span style="color:red">*</span> </h6>
                    <input type="text" required="" name="LastName" class="form-control">
                  </div>
                  <div class="col-md-2">
                    <h6>Ext. Name</h6>
                    <input type="text" name="ExtName" class="form-control">
                  </div>
                </div>


                <div class="row">
                  <div class="col-md-12">
                    <h6>Address <span style="color:red">*</span> </h6>
                    <input type="text" required="" name="studentAddressLine" class="form-control">
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <h6 for="selectRegion">Region <span class="text-red">*</span></h6>
                      <select class="form-control select2"  required="" onchange="changeRegion2(this.value)" id="selectRegion2" name="RegionId" style="width: 100%">
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <h6 for="selectProvince">Province/City<span class="text-red">*</span></h6>
                      <select class="form-control select2"  required="" id="selectProvince2" onchange="changeProvince2(this.value)" name="ProvinceId" style="width: 100%">
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <h6 for="selectCity">Municipality<span class="text-red">*</span></h6>
                      <select class="form-control select2" required="" id="selectCity2" onchange="changeCity2(this.value)" name="CityId" style="width: 100%">
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <h6 for="selectBarangay">Barangay <span class="text-red">*</span></h6>
                      <select class="form-control select2" required="" id="selectBarangay2" name="BarangayId" style="width: 100%">
                      </select>
                    </div>
                  </div>


                  <div class="col-md-6">
                    <h6>Contact Number</h6>
                    <input type="text" name="studentContactNumber" class="form-control phonenumber" maxlength="11" placeholder="0935*******">
                  </div>
                  <div class="col-md-6">
                    <h6>Email Address</h6>
                    <input type="email" name="studentEmail" class="form-control">
                  </div>
                  <div class="col-md-12">
                    <h6>Place of Birth <span style="color:red">*</span> </h6>
                    <input type="text" required="" name="studentPlaceOfBirth" class="form-control">
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <h6>Date of Birth <span style="color:red">*</span> </h6>
                    <input type="text" required="" id="dtpDateOfBirth" name="studentDOB" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <h6>Age</h6>
                    <span id="lblAge">- years old</span>
                  </div>
                  <div class="col-md-4">
                    <h6>Gender <span style="color:red">*</span> </h6>
                    <select name="genderId" required class="form-control select2" style="width: 100%;">
                      <option value="" selected disabled>Select Gender</option>
                      <option value="1">Male</option>
                      <option value="0">Female</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <h6>Marital Status <span style="color:red">*</span> </h6>
                    <select name="studentMaritalStatusId" required class="form-control select2" style="width: 100%;">
                      <?php echo $maritalStatus ?>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <h6>Graduating Status</h6>
                    <select name="studentGraduatingStatusId" required class="form-control select2" style="width: 100%;">
                      <?php echo $graduatingStatus ?>
                    </select>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <h6>Father's Name</h6>
                    <input type="text" name="fatherName" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <h6>Father's Occupation</h6>
                    <select name="fatherOccupation" class="form-control select2" style="width: 100%;">
                      <?php echo $occupations ?>
                    </select>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <h6>Mother's Name</h6>
                    <input type="text" name="motherName" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <h6>Mother's Occupation</h6>
                    <select name="motherOccupation" class="form-control select2" style="width: 100%;">
                      <?php echo $occupations ?>
                    </select>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <h6>Guardian Name</h6>
                    <input type="text" name="guardianName" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <h6>Guardian's Occupation</h6>
                    <select name="guardianOccupation" class="form-control select2" style="width: 100%;">
                      <?php echo $occupations ?>
                    </select>
                  </div>
                  <div class="col-md-12">
                    <h6>Guardian's Contact Number</h6>
                    <input type="text" name="guardianNumber" class="form-control phonenumber" maxlength="11" placeholder="0935*******">
                  </div>
                </div>



              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="modal fade" id="modalEdit">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Create Subject</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url(); ?>admin_controller/editStudentList/" class="frminsert2" method="post">
              <div class="modal-body">
                <input type="hidden" name="Id" id="txtId">
                <div class="row">
                  <div class="col-md-4">
                    <h6>First Name<span style="color:red">*</span> </h6>
                    <input type="text" required="" name="FirstName" id="txtFirstName" class="form-control">
                  </div>
                  <div class="col-md-3">
                    <h6>Middle Name</h6>
                    <input type="text" name="MiddleName" id="txtMiddleName" class="form-control">
                  </div>
                  <div class="col-md-3">
                    <h6>Last Name<span style="color:red">*</span> </h6>
                    <input type="text" required="" name="LastName" id="txtLastName" class="form-control">
                  </div>
                  <div class="col-md-2">
                    <h6>Ext. Name</h6>
                    <input type="text" name="ExtName" id="txtExtName" class="form-control">
                  </div>
                </div>


                <div class="row">
                  <div class="col-md-12">
                    <h6>Address <span style="color:red">*</span> </h6>
                    <input type="text" required="" id="txtAddressLine" name="studentAddressLine" class="form-control">
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <h6 for="selectRegion">Region <span class="text-red">*</span></h6>
                      <select class="form-control select2" id="editRegionId"  required="" onchange="changeRegion3(this.value)" id="selectRegion2" name="RegionId" style="width: 100%">
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <h6 for="selectProvince">Province/City<span class="text-red">*</span></h6>
                      <select class="form-control select2"  required="" id="selectProvince3" onchange="changeProvince3(this.value)" name="ProvinceId" style="width: 100%">
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <h6 for="selectCity">Municipality<span class="text-red">*</span></h6>
                      <select class="form-control select2" required="" id="selectCity3" onchange="changeCity3(this.value)" name="CityId" style="width: 100%">
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <h6 for="selectBarangay">Barangay <span class="text-red">*</span></h6>
                      <select class="form-control select2" required="" id="selectBarangay3" name="BarangayId" style="width: 100%">
                      </select>
                    </div>
                  </div>


                  <div class="col-md-6">
                    <h6>Contact Number</h6>
                    <input type="text" name="studentContactNumber" id="editContactNo" class="form-control phonenumber" maxlength="11" placeholder="0935*******">
                  </div>
                  <div class="col-md-6">
                    <h6>Email Address</h6>
                    <input type="email" name="studentEmail" id="editStudentMail" class="form-control">
                  </div>
                  <div class="col-md-12">
                    <h6>Place of Birth <span style="color:red">*</span> </h6>
                    <input type="text" required=""  id="editPlaceOfBirth" name="studentPlaceOfBirth" class="form-control">
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <h6>Date of Birth <span style="color:red">*</span> </h6>
                    <input type="text" required="" id="editDtpDateOfBirth" name="studentDOB" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <h6>Age</h6>
                    <span id="lblEditAge">- years old</span>
                  </div>
                  <div class="col-md-4">
                    <h6>Gender <span style="color:red">*</span> </h6>
                    <select name="genderId"  id="editGenderId" required class="form-control select2" style="width: 100%;">
                      <option value="" selected disabled>Select Gender</option>
                      <option value="1">Male</option>
                      <option value="0">Female</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <h6>Marital Status <span style="color:red">*</span> </h6>
                    <select name="studentMaritalStatusId" id="editMaritalStatusId" required class="form-control select2" style="width: 100%;">
                      <?php echo $maritalStatus ?>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <h6>Graduating Status</h6>
                    <select name="studentGraduatingStatusId" id="editGraduatingStatusId" required class="form-control select2" style="width: 100%;">
                      <?php echo $graduatingStatus ?>
                    </select>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <h6>Father's Name</h6>
                    <input type="text" name="fatherName" id="editFatherName" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <h6>Father's Occupation</h6>
                    <select name="fatherOccupation" id="editFatherOccupation" class="form-control select2" style="width: 100%;">
                      <?php echo $occupations ?>
                    </select>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <h6>Mother's Name</h6>
                    <input type="text" name="motherName" id="editMotherName" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <h6>Mother's Occupation</h6>
                    <select name="motherOccupation" id="editMotherOccupation" class="form-control select2" style="width: 100%;">
                      <?php echo $occupations ?>
                    </select>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <h6>Guardian Name</h6>
                    <input type="text" name="guardianName" id="editGuardianName" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <h6>Guardian's Occupation</h6>
                    <select name="guardianOccupation" id="editGuardianOccupation" class="form-control select2" style="width: 100%;">
                      <?php echo $occupations ?>
                    </select>
                  </div>
                  <div class="col-md-12">
                    <h6>Guardian's Contact Number</h6>
                    <input type="text" name="guardianNumber" id="editGuardianNumber" class="form-control phonenumber" maxlength="11" placeholder="0935*******">
                  </div>
                </div>
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="m-0"></h5>
              </div>
              <div class="card-body">
                <div class="float-right">
                  <a class="btn btn-md btn-primary" data-toggle="modal" data-target="#modal-default">Add Record</a>
                </div>
                <br>
                <br>
                <table id="example1" class="table table-bordered table-striped" style="width:100%">
                  <thead>
                  <tr>
                    <th>Student No.</th>
                    <th>Name</th>
                    <th>Created By</th>
                    <th>Date Created</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content -->

  </div>

  <?php $this->load->view('includes/footer'); ?>

<script type="text/javascript">

  function refreshPage(){
    var url = '<?php echo base_url()."admin_controller/getStudentList/"; ?>';
    UserTable.ajax.url(url).load();
  }

  function updateRecord(Id, Type, FirstName, MiddleName, LastName, ExtName, addressLine, regCode, provCode, cityCode, barangayId, studentContactNo, studentEmailAddress, placeOfBirth, dateOfBirth, genderId, maritalStatusId, graduatingStatusId, fatherName, fatherOccupation, motherName, motherOccupation, guardianName, guardianOccupation, guardianContactNumber)
  {
    if(Type == 4) // update role
    {
      $('#txtId').val(Id)
      $('#txtFirstName').val(FirstName)
      $('#txtMiddleName').val(MiddleName)
      $('#txtLastName').val(LastName)
      $('#txtExtName').val(ExtName)
      $('#txtAddressLine').val(addressLine)

      $('#editContactNo').val(studentContactNo)
      $('#editStudentMail').val(studentEmailAddress)
      $('#editPlaceOfBirth').val(placeOfBirth)
      $('#editDtpDateOfBirth').val(dateOfBirth)
      $('#editGenderId').val(genderId).change()
      $('#editMaritalStatusId').val(maritalStatusId).change()
      $('#editGraduatingStatusId').val(graduatingStatusId).change()
      $('#editFatherName').val(fatherName)
      $('#editFatherOccupation').val(fatherOccupation).change()
      $('#editMotherName').val(motherName)
      $('#editMotherOccupation').val(motherOccupation).change()
      $('#editGuardianName').val(guardianName)
      $('#editGuardianOccupation').val(guardianOccupation).change()
      $('#editGuardianNumber').val(guardianContactNumber)

      $.ajax({
        url: '<?php echo base_url();?>' + "admin_controller/getRegionList",
        async: true,
        method: "POST",
        beforeSend: function(){
          $('.loading').show();
        },
        success: function(data)
        {
          $('#editRegionId').html(data);
          $('#editRegionId').val(regCode).change();
          changeRegion3(regCode, provCode)
          changeProvince3(provCode, cityCode)
          changeCity3(cityCode, barangayId)


          $('#editDtpDateOfBirth').daterangepicker({
            timePicker: false,
            singleDatePicker: true,
            showDropdowns: true,
            startDate: moment().format('MM-DD-YYYY'),
            maxDate: moment().format('MM-DD-YYYY'),
            locale: {
              format: 'MM-DD-YYYY'
            }}, function(start, end, label) {
              $('#lblEditAge').html(calculate_age(new Date(start.format('YYYY'), start.format('DD'), start.format('MM'))) + ' years old');
            });



          $('.loading').hide();
        }
      })

    }
    else
    {
      var text = '';
      if(Type == 1) // deactivate
      {
        text = 'Are you sure you want to deactivate record?';
      }
      else // reactivate
      {
        text = 'Are you sure you want to re-activate record?';
      }

      swal({
        title: 'Confirm',
        text: text,
        type: 'info',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-success',
        confirmButtonText: 'Confirm',
        cancelButtonClass: 'btn btn-secondary'
      }).then(function(){
        $.ajax({                
          url: "<?php echo base_url();?>" + "/admin_controller/updateStudentListRecord",
          method: "POST",
          async: false,
          data:   {
                    Id : Id
                    , Type : Type
                  },  
          dataType: "JSON",
          beforeSend: function(){
              $('.loading').show();
          },
          success: function(data)
          {
            swal({
              title: 'Success!',
              text: 'Record successfully updated!',
              type: 'success',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });
            refreshPage();
          },
          error: function (response) 
          {
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
  }

  function changeRegion2(RegionId)
  {
    $.ajax({
      url: "<?php echo base_url();?>" + "admin_controller/getProvinces",
      method: "POST",
      data: { RegionId : RegionId },
      beforeSend: function(){
        $('.loading').show();
      },
      success: function(data)
      {
        $('#selectProvince2').html(data);
        $('#selectCity2').empty();
        $('#selectBarangay2').empty();
        $('.loading').hide();
      }
    })
  }

  function changeProvince2(ProvinceCode)
  {
    $.ajax({
      url: "<?php echo base_url();?>" + "admin_controller/getCities",
      method: "POST",
      data: { Id : ProvinceCode },
      beforeSend: function(){
        $('.loading').show();
      },
      success: function(data)
      {
        $('#selectCity2').html(data);
        $('#selectBarangay2').empty();
        $('.loading').hide();
      }
    })
  }

  function changeCity2(CityCode)
  {
    $.ajax({
      url: "<?php echo base_url();?>" + "admin_controller/getBarangays",
      method: "POST",
      data: { Id : CityCode },
      beforeSend: function(){
        $('.loading').show();
      },
      success: function(data)
      {
        $('#selectBarangay2').html(data);
        $('.loading').hide();
      }
    })
  }

  /* EDITING */

    function changeRegion3(RegionId, provCode)
    {
      $.ajax({
        url: "<?php echo base_url();?>" + "admin_controller/getProvinces",
        method: "POST",
        async: false,
        data: { RegionId : RegionId },
        beforeSend: function(){
          $('.loading').show();
        },
        success: function(data)
        {
          $('#selectProvince3').html(data);
          $('#selectProvince3').val(provCode).change();
          $('#selectCity3').empty();
          $('#selectBarangay3').empty();
          $('.loading').hide();
        }
      })
    }

    function changeProvince3(ProvinceCode, cityCode)
    {
      $.ajax({
        url: "<?php echo base_url();?>" + "admin_controller/getCities",
        method: "POST",
        async: false,
        data: { Id : ProvinceCode },
        beforeSend: function(){
          $('.loading').show();
        },
        success: function(data)
        {
          $('#selectCity3').html(data);
          $('#selectCity3').val(cityCode).change();
          $('#selectBarangay3').empty();
          $('.loading').hide();
        }
      })
    }

    function changeCity3(CityCode, barangayId)
    {
      $.ajax({
        url: "<?php echo base_url();?>" + "admin_controller/getBarangays",
        method: "POST",
        async: false,
        data: { Id : CityCode },
        beforeSend: function(){
          $('.loading').show();
        },
        success: function(data)
        {
          $('#selectBarangay3').html(data);
          $('#selectBarangay3').val(barangayId).change();
          $('.loading').hide();
        }
      })
    }

  /* END OF EDIT */

  function calculate_age(dob) { 
      var diff_ms = Date.now() - dob.getTime();
      var age_dt = new Date(diff_ms); 
    
      return Math.abs(age_dt.getUTCFullYear() - 1970);
  }

  $(function () {
    const phoneInputs = document.querySelectorAll('.phonenumber');
    
    phoneInputs.forEach(input => {
      input.addEventListener('input', () => {
        input.value = input.value.replace(/\D/g, '').replace(/(\d{3})(\d{3})(\d{4})/, '$1$2$3');
      });
    });


    $.ajax({
      url: '<?php echo base_url();?>' + "admin_controller/getRegionList",
      method: "POST",
      beforeSend: function(){
        $('.loading').show();
      },
      success: function(data)
      {
        $('#selectRegion2').html(data);
        $('.loading').hide();
      }
    })

    $('#dtpDateOfBirth').daterangepicker({
      timePicker: false,
      singleDatePicker: true,
      showDropdowns: true,
      startDate: moment().format('MM-DD-YYYY'),
      maxDate: moment().format('MM-DD-YYYY'),
      locale: {
        format: 'MM-DD-YYYY'
      }}, function(start, end, label) {
        $('#lblAge').html(calculate_age(new Date(start.format('YYYY'), start.format('DD'), start.format('MM'))) + ' years old');
      });

    $(".frminsert2").on('submit', function (e) {
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


    $('.select2').select2();

    // UserTable = $('#example1').DataTable({
    //   "pageLength": 10,
    //   "ajax": { url: '<?php echo base_url()."/admin_controller/getStudentList/"; ?>', type: 'POST', "dataSrc": "" },
    //   "columns": [  { data: "StudentNumber" }
    //                 , { data: "StudentName" }
    //                 , { data: "CreatedBy" }
    //                 , { data: "DateCreated" }
    //                 , { data: "rawDateCreated" }
    //                 , {
    //                   data: "StatusId", "render": function (data, type, row) {
    //                     return "<span class='badge bg-"+row.Color+"'>"+row.StatusDescription+"</span>";
    //                   }
    //                 }
    //                 , {
    //                   data: "StatusId", "render": function (data, type, row) {
    //                     if(row.StatusId == 1){
    //                       return '<a onclick="updateRecord('+row.Id+', 4, \''+row.FirstName+'\', \''+row.MiddleName+'\', \''+row.LastName+'\', \''+row.ExtName+'\')"  data-toggle="modal" data-target="#modalEdit" class="btn btn-primary" title="Edit"><span class="fa fa-edit"></span></a> <a onclick="updateRecord('+row.Id+', 1)" class="btn btn-danger" title="Deactivate"><span class="fa fa-window-close"></span></a>';
    //                     }
    //                     else
    //                     {
    //                       return '<a onclick="updateRecord('+row.Id+', 2)" class="btn btn-warning" title="Re-activate"><span class="fa fa-retweet"></span></a>';
    //                     }
    //                   }
    //                 },
    //   ],
    //   "aoColumnDefs": [{ "bVisible": false, "aTargets": [4] }],
    //   "order": [[4, "DESC"]]
    // });



    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    var dataJson ={[csrfName]: csrfHash};

    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "scrollX": true,
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50]],
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "order": [], //Initial no order.
      "ajax": {
          "url": "<?php echo base_url()?>admin_controller/getStudentList",
          "type": "POST",
          "data": dataJson 
      }
    });

  });
</script>