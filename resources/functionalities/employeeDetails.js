  var base_url = window.location.origin;

  var baseUrl = base_url + '/ELendingTool'; // initial url for javascripts


  function chkEmployeeType(radioValue)
  {
    if(radioValue == 'Manager'){
      $('#DivEmployee').slideUp();
    }
    else
    {
      $('#DivEmployee').slideDown();
    }
  }

  
  function changeBranch(BranchId, ManagerBranchId)
  {
    if(ManagerBranchId != null)
    {
      $.ajax({
        url: baseUrl + "/admin_controller/getManagers",
        method: "POST",
        data: { BranchId : BranchId },
        beforeSend: function(){
          $('.loading').show();
        },
        success: function(data)
        {
          $('#selectManager').html(data);
          $('#selectManager').val(ManagerBranchId).change();
          $('.loading').hide();
        }
      })
    }
    else
    {
      $.ajax({
        url: baseUrl + "/admin_controller/getManagers",
        method: "POST",
        data: { BranchId : BranchId },
        beforeSend: function(){
          $('.loading').show();
        },
        success: function(data)
        {
          $('#selectManager').html(data);
          $('.loading').hide();
        }
      })
    }
  }

  function confirm(Text, EmployeeContactId, updateType)
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
          url: baseUrl + "/employee_controller/updateEmail",
          method: "POST",
          data:   {
                    Id : EmployeeContactId
                    , updateType : updateType
                    , tableType : 'EmployeeContact'
                  },
          beforeSend: function(){
              $('.loading').show();
          },
          success: function(data)
          {
            location.reload();
            swal({
              title: 'Success!',
              text: 'Successfully updated!',
              type: 'success',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });
            $('.loading').hide();
          },
          error: function (response) 
          {
            location.reload();
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

  function confirmEmail(Text, EmployeeEmailId, updateType)
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
          url: baseUrl + "/employee_controller/updateEmail",
          method: "POST",
          data:   {
                    Id : EmployeeEmailId
                    , updateType : updateType
                    , tableType : 'EmployeeEmail'
                  },
          beforeSend: function(){
            $('.loading').show();
          },
          success: function(data)
          {
            location.reload();
            swal({
              title: 'Success!',
              text: 'Successfully updated!',
              type: 'success',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });
            $('.loading').hide();
          },
          error: function (response) 
          {
            location.reload();
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

  function changeRegion(RegionId)
  {
    $.ajax({
      url: baseUrl + "/admin_controller/getProvinces",
      method: "POST",
      data: { RegionId : RegionId },
      beforeSend: function(){
        $('.loading').show();
      },
      success: function(data)
      {
        $('#selectProvince').html(data);
            $('.loading').hide();
      }
    })
  }

  function changeProvince(ProvinceCode)
  {
    $.ajax({
      url: baseUrl + "/admin_controller/getCities",
      method: "POST",
      data: { Id : ProvinceCode },
      beforeSend: function(){
        $('.loading').show();
      },
      success: function(data)
      {
        $('#selectCity').html(data);
        $('.loading').hide();
      }
    })
  }

  function changeCity(CityCode)
  {
    $.ajax({
      url: baseUrl + "/admin_controller/getBarangays",
      method: "POST",
      data: { Id : CityCode },
      beforeSend: function(){
        $('.loading').show();
      },
      success: function(data)
      {
        $('#selectBarangay').html(data);
        $('.loading').hide();
      }
    })
  }

  function confirmAddress(Text, EmployeeAddressId, updateType, EmployeeNumber)
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
          url: baseUrl + "/employee_controller/updateEmail",
          method: "POST",
          data:   {
                    Id : EmployeeAddressId
                    , updateType : updateType
                    , tableType : 'EmployeeAddress'
                  },
          beforeSend: function(){
            $('.loading').show();
          },
          success: function(data)
          {
            swal({
              title: 'Success!',
              text: 'Successfully updated!',
              type: 'success',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });

            location.reload();
            $('.loading').hide();
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

  function confirmID(Text, EmployeeAddressId, updateType, EmployeeNumber)
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
          url: baseUrl + "/employee_controller/updateEmail",
          method: "POST",
          data:   {
                    Id : EmployeeAddressId
                    , updateType : updateType
                    , tableType : 'EmployeeId'
                  },
          beforeSend: function(){
            $('.loading').show();
          },
          success: function(data)
          {
            swal({
              title: 'Success!',
              text: 'Successfully updated!',
              type: 'success',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });

            location.reload();
            $('.loading').hide();
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

  function editEmployee(EmployeeId)
  {
    $.ajax({
      url: baseUrl + "/employee_controller/getEmployeeDetails",
      type: "POST",
      async: false,
      data: {
        Id : EmployeeId
      },
      dataType: "JSON",
      beforeSend: function(){
          $('.loading').show();
      },
      success: function(data)
      {
        $('#selectSalutation').val(data['SalutationId']).change();
        $('#txtFirstName').val(data['FirstName']);
        $('#txtMiddleName').val(data['MiddleName']);
        $('#txtLastName').val(data['LastName']);
        $('#txtExtensionName').val(data['ExtName']);
        $('#selectGender').val(data['SexId']).change();
        $('#selectNationality').val(data['NationalityId']).change();
        $('#selectCivilStatus').val(data['CivilStatusId']).change();
        $('#selectPosition').val(data['PositionId']).change();
        $('#selectStatusId').val(data['StatusId']).change();
        if(data['EmployeeType'] > 0)
        {
          $('#selectEmpType').val('Manager').change();
        }
        else
        {
          $('#selectEmpType').val('Employee').change();
          $('#selectBranch').val(data['BranchId']).change();
          changeBranch(data['BranchId'], data['ManagerBranchId']);
        }

        $('#DateOfBirth').daterangepicker({
            "startDate": moment(data['RawDOB']).format('DD MMM YY'),
            "singleDatePicker": true,
            "timePicker": false,
            "showDropdowns": true,
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
            "startDate": moment(data['RawDateHired']).format('DD MMM YY'),
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
          // location.reload();
        }, 2000);
      }
    });
  }

  function getDetail(EmployeeId, formType)
  {
    if(formType == 1) // for editing of Employee
    {
      document.getElementById("selectStatusId").required = true;
      $('#divStatus').show();
      $('#divSpouseDetails').hide();
      $('#borrowerSpouseForm').show();
      $('#displaySpouse').hide();
      $.ajax({
        url: baseUrl + "/employee_controller/getEmployeeDetails",
        type: "POST",
        async: false,
        data: {
          Id : EmployeeId
        },
        dataType: "JSON",
        beforeSend: function(){
            $('.loading').show();
        },
        success: function(data)
        {
          $('#txtFormType').val(formType);
          $('#selectSalutation').val(data['SalutationId']).change();
          $('#txtFirstName').val(data['FirstName']);
          $('#txtMiddleName').val(data['MiddleName']);
          $('#txtLastName').val(data['LastName']);
          $('#txtExtensionName').val(data['ExtName']);
          $('#txtDependents').val(data['Dependents']);
          $('#txtMother').val(data['MotherName']);
          $('#selectGender').val(data['SexId']).change();
          $('#selectNationality').val(data['NationalityId']).change();
          $('#selectCivilStatus').val(data['CivilStatusId']).change();
          $('#selectStatusId').val(data['StatusId']).change();

          $('#datepicker').daterangepicker({
              "startDate": moment(data['RawDateOfBirth']).format('DD MMM YY hh:mm A'),
              "singleDatePicker": true,
              "timePicker": false,
              "linkedCalendars": false,
              "showCustomRangeLabel": false,
              "showDropdowns": true,
              // "maxDate": Start,
              "opens": "up",
              "locale": {
                  format: 'DD MMM YYYY',
              },
          }, function(start, end, label){
          });
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
            // location.reload();
          }, 2000);
        }
      });
    }
    else // for adding of spouse
    {
      document.getElementById("frmEmployeeDetail").reset();
      $('#txtFormType').val(formType);
      $('#divStatus').hide();
      $('#divSpouseDetails').show();
      $('#EmployeeSpouseForm').show();
      $('#displaySpouse').hide();
      document.getElementById("selectStatusId").required = false;
    }
  }



$(function () {

  $("#frmEmployeeDetail").on('submit', function (e) {
    e.preventDefault(); 
    swal({
      title: 'Confirm',
      text: 'Are you sure you sure you want to update employee detail(s)?',
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

  $("#frmInsert2").on('submit', function (e) {
    e.preventDefault(); 
    swal({
      title: 'Confirm',
      text: 'Are you sure you sure you want to add record?',
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

  $("#frmInsert3").on('submit', function (e) {
    e.preventDefault(); 
    swal({
      title: 'Confirm',
      text: 'Are you sure you sure you want to add record?',
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

  $("#frmInsert4").on('submit', function (e) {
    e.preventDefault(); 
    swal({
      title: 'Confirm',
      text: 'Are you sure you sure you want to add record?',
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

  $("#frmInsert5").on('submit', function (e) {
    e.preventDefault(); 
    swal({
      title: 'Confirm',
      text: 'Are you sure you sure you want to add record?',
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

  $("#frmInsert6").on('submit', function (e) {
    e.preventDefault(); 
    swal({
      title: 'Confirm',
      text: 'Are you sure you sure you want to update profile picutre?',
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
  
  $('#selectRoles').select2({
    placeholder: 'Type a role to select',
    dropdownCssClass : 'bigdrop',
      ajax: {
        url: '<?php echo base_url()?>admin_controller/getRoles?>',
        dataType: 'json',
        delay: 250,
        processResults: function (data) 
        {
          return {
            results: data
          };
        },
        cache: true
      }
  });

  $('#selectEmployee').select2({
    placeholder: 'Type an employee name or employee number to select.',
    dropdownCssClass : 'bigdrop',
      ajax: {
        url: '<?php echo base_url()?>admin_controller/getEmployees?>',
        dataType: 'json',
        delay: 250,
        processResults: function (data) 
        {
          return {
            results: data
          };
        },
        cache: true
      }
  });

  $('.select2').select2();

  $.ajax({
    url: baseUrl + "/admin_controller/getRegionList",
    method: "POST",
    beforeSend: function(){
      $('.loading').show();
    },
    success: function(data)
    {
      $('#selectRegion').html(data);
            $('.loading').hide();
    }
  })

  dtblEmailAddress = $('#dtblEmailAddress').DataTable({
    "pageLength": 10,
    "aoColumnDefs": [{ "bVisible": false, "aTargets": [8] }],
    "order": [[4, "desc"], [6, "asc"]]
  });
  
  dtblAddress = $('#dtblAddress').DataTable({
    "pageLength": 10,
    "aoColumnDefs": [{ "bVisible": false, "aTargets": [9] }],
    "order": [[4, "desc"], [9, "desc"]]
  });
  
  dtblContactNumber = $('#dtblContactNumber').DataTable({
    "pageLength": 10,
    // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
    "order": [[3, "desc"]]
  });
  
  dtblIDs = $('#dtblIDs').DataTable({
    "pageLength": 10,
    // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
    "order": [[3, "desc"]]
  });
  
  $('#dtblBranch').DataTable({
    "pageLength": 10,
    // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
    "order": [[3, "desc"]]
  });


  
  dtblAudit = $('#dtblAudit').DataTable({
    "pageLength": 10,
    "aoColumnDefs": [{ "bVisible": false, "aTargets": [4] }],
    "order": [[4, "desc"]]
  });

  $('#modalProfilePicture').on('hidden.bs.modal', function () {
    Webcam.reset();
    $('#cameraDiv').hide();
    $('#cameraDivBtn').show();
    $('#cameraDivBtnOff').hide();
    $('#btnCameraSave').hide();
    $('#btnCameraUpload').show();
    $('#uploadPic').show();
  })

})