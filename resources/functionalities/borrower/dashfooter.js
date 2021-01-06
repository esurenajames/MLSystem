  var base_url = window.location.origin;

  var baseUrl = base_url + '/ELendingTool'; // initial url for javascripts


  var varStatus = 0;
  var varNewPassword = 0;



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
      }
    })
  }

  function changeProvince2(ProvinceCode)
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
        $('#selectCity2').html(data);
      }
    })
  }

  function changeCity2(CityCode)
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

  function chkRent()
  {
    var radioValue = $("input[name='optionsRadios']:checked").val();
    if(radioValue == 'Rented'){
      $('#divRentedDetails').slideDown();
      $('#txtRentedType').val(1);
    }
    else
    {
      $('#divRentedDetails').slideUp();
      $('#txtRentedType').val(0);
    }
  }

  function confirm(Text, UserRoleId, updateType)
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
          url: baseUrl + "/employee_controller/updateStatus",
          method: "POST",
          data:   {
                    UserRoleId : UserRoleId
                    , updateType : updateType
                  },
          beforeSend: function(){
              $('.loading').show();
          },
          success: function(data)
          {
            refreshPage();
            swal({
              title: 'Success!',
              text: 'User role successfully deactivated!',
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


  

  $.ajax({
    url: baseUrl + "/admin_controller/getRegionList",
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

 // delete pababa


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
      }
    })
  }

  function changeRegion2(RegionId)
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
        $('#selectProvince2').html(data);
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

        $('#datepicker').daterangepicker({
            "startDate": moment(data['RawDOB']).format('DD MMM YY hh:mm A'),
            "singleDatePicker": true,
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
            "startDate": moment(data['RawDateHired']).format('DD MMM YY hh:mm A'),
            "singleDatePicker": true,
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

  dtblEmailAddress = $('#dtblEmailAddress').DataTable({
    "pageLength": 10,
    // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
    "order": [[6, "asc"], [3, "desc"]]
  });
  
  dtblAddress = $('#dtblAddress').DataTable({
    "pageLength": 10,
    // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
    "order": [[3, "asc"]]
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
  
  dtblAudit = $('#dtblAudit').DataTable({
    "pageLength": 10,
    // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
    "order": [[0, "desc"]]
  });

  $('.select2').select2();

})