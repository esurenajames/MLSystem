  var base_url = window.location.origin;

  var baseUrl = base_url + '/LendingManagementSystem-John'; // initial url for javascripts


  var varStatus = 0;
  var varNewPassword = 0;

  function viewSpouse(SpouseId)
  {
    $.ajax({
      url: baseUrl + "/borrower_controller/getSpouseDetails",
      type: "POST",
      async: false,
      data: {
        Id : SpouseId
      },
      dataType: "JSON",
      beforeSend: function(){
          $('.loading').show();
      },
      success: function(data)
      {
        $('#displaySpouse').show();
        $('#borrowerSpouseForm').hide();

        $('#lblSpouseName').html(data['Name']);
        $('#lblSpouseGender').html(data['Sex']);
        $('#lblSpouseNationality').html(data['Nationality']);
        $('#lblSpouseCivil').html(data['CivilStatus']);
        $('#lblSpouseBirth').html(data['DateOfBirth']);
        $('#lblSpouseDependents').html(data['Dependents']);
        $('#lblSpousePlace').html(data['DateOfBirth']);
        $('#lblSpouseTelephone').html(data['TelephoneNo']);
        $('#lblSpouseMobile').html(data['MobileNo']);
        $('#lblSpouseBusinessAddress').html(data['DateOfBirth']);
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

  function viewComaker(BorrowerComakerId)
  {
    $.ajax({
      url: baseUrl + "/borrower_controller/getComakerDetails",
      type: "POST",
      async: false,
      data: {
        Id : BorrowerComakerId
      },
      dataType: "JSON",
      beforeSend: function(){
          $('.loading').show();
      },
      success: function(data)
      {
        $('#displayComaker').show();
        $('#ComakerForm').hide();

        $('#lblComakerName').html(data['Name']);
        $('#lblComakerEmployer').html(data['Employer']);
        $('#lblComakerBirthdate').html(data['Birthdate']);
        $('#lblComakerAddress').html(data['BusinessAddress']);
        $('#lblComakerPosition').html(data['PositionName']);
        $('#lblComakerTenure').html(data['TenureYear']);
        $('#lblComakerBusinessNo').html(data['BusinessNo']);
        $('#lblComakerMonth').html(data['TenureMonth']);
        $('#lblComakerTelephone').html(data['TelephoneNo']);
        $('#lblComakerBusiness').html(data['Nationality']);
        $('#lblComakerMobile').html(data['MobileNo']);
        $('#lblComakerMonthly').html(data['MonthlyIncome']);
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

  function AddComaker(BorrowerComakerId)
  {
        $('#displayComaker').hide();
        $('#ComakerForm').show();
  }

  function getDetail(borrowerId, formType)
  {
    if(formType == 1) // fpr editing of borrower
    {
      document.getElementById("selectStatusId").required = true;
      $('#divStatus').show();
      $('#divSpouseDetails').hide();
      $('#borrowerSpouseForm').show();
      $('#displaySpouse').hide();
      $.ajax({
        url: baseUrl + "/borrower_controller/getBorrowerDetails",
        type: "POST",
        async: false,
        data: {
          Id : borrowerId
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
      document.getElementById("frmBorrowerDetail").reset();
      $('#txtFormType').val(formType);
      $('#divStatus').hide();
      $('#divSpouseDetails').show();
      $('#borrowerSpouseForm').show();
      $('#displaySpouse').hide();
      document.getElementById("selectStatusId").required = false;
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
          url: baseUrl + "/borrower_controller/updateEmail",
          method: "POST",
          data:   {
                    Id : Id
                    , updateType : updateType
                    , tableType : tableType
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

  function requirementList()
  {
    $.ajax({
      url: baseUrl + "/borrower_controller/IDCategory",
      method: "POST",
      beforeSend: function(){
        $('.loading').show();
      },
      success: function(data)
      {
        $('#selectReqTypeId').html(data);
      }
    })
  }

  function chkFunction(value, chkType)
  {
    if(chkType == 1) // same as borrower address
    {
      // Get the checkbox
      var checkBox = document.getElementById("chkSameBorrowerAddress");
      // If the checkbox is checked, display the output text
      if (checkBox.checked == true){
        $('#divSpouseAddress').slideUp();
        $('#divProvincialAddress').slideUp();
        $('#txtAddress2').val(3);

        document.getElementById("txtHouseNo").required = false;
        document.getElementById("txtCellphoneCityAdd").required = false;
        document.getElementById("txtTelephoneCityAddress").required = false;
        document.getElementById("txtMonthsStayed").required = false;
        document.getElementById("txtYearsStayed").required = false;
        document.getElementById("selectBarangay").required = false;
        document.getElementById("selectCity").required = false;
        document.getElementById("selectProvince").required = false;
        document.getElementById("selectRegion").required = false;
      } else {
        $('#divSpouseAddress').slideDown();
        $('#divProvincialAddress').slideDown();
        $('#txtAddress2').val(0);

        document.getElementById("txtHouseNo").required = true;
        document.getElementById("txtCellphoneCityAdd").required = true;
        document.getElementById("txtTelephoneCityAddress").required = true;
        document.getElementById("txtMonthsStayed").required = true;
        document.getElementById("txtYearsStayed").required = true;
        document.getElementById("selectBarangay").required = true;
        document.getElementById("selectCity").required = true;
        document.getElementById("selectProvince").required = true;
        document.getElementById("selectRegion").required = true;
      }
    }
    else // same as city address
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
  }

  $(function () {

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

    $('.select2').select2();

    $('#CoMakerBirthday').daterangepicker({
        "startDate": moment().format('DD MMM YY'),
        "singleDatePicker": true,
        "showDropdowns": true,
        "timePicker": false,
        "linkedCalendars": false,
        "showCustomRangeLabel": false,
        "showCustomRangeLabel": false,
        // "maxDate": Start,
        "opens": "up",
        "locale": {
            format: 'DD MMM YYYY',
        },
    }, function(start, end, label){
    });

    $('#DateOfBirth').daterangepicker({
        "startDate": moment().format('DD MMM YY'),
        "singleDatePicker": true,
        "showDropdowns": true,
        "timePicker": false,
        "linkedCalendars": false,
        "showCustomRangeLabel": false,
        "showCustomRangeLabel": false,
        // "maxDate": Start,
        "opens": "up",
        "locale": {
            format: 'DD MMM YYYY',
        },
    }, function(start, end, label){
    });

    $('#dtpDateHired').daterangepicker({
      "startDate": moment().format('DD MMM YY'),
      "singleDatePicker": true,
      "showDropdowns": true,
      "timePicker": false,
      "linkedCalendars": false,
      "showCustomRangeLabel": false,
      "showCustomRangeLabel": false,
      // "maxDate": Start,
      "opens": "up",
      "locale": {
          format: 'DD MMM YYYY',
      },
    }, function(start, end, label){
    });

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

    $('#example1').DataTable({
      "pageLength": 10,
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[0, "desc"]]
    });
    $('#example2').DataTable();

    $('#example3').DataTable();
    $('#example4').DataTable({
      "pageLength": 10,
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[4, "asc"]]
    });

    $('#example5').DataTable({
      "pageLength": 10,
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[0, "desc"]]
    });

    $('#example6').DataTable();

    $('#example7').DataTable();

    $('#example8').DataTable();

    $('#example9').DataTable();

  })