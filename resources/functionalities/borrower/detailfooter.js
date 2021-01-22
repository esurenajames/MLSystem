  var base_url = window.location.origin;

  var baseUrl = base_url + '/ELendingTool'; // initial url for javascripts


  var varStatus = 0;
  var varNewPassword = 0;

  function viewSpouse(SpouseId)
  {
    varDurationStayed = '';
    // spouse details
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
          $('#btnSpouseView').hide();

          $('#lblBorrowerSpouse').html('View Spouse Details');
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
    // spouse city address
      $.ajax({
        url: baseUrl + "/borrower_controller/getSpouseCityAddress",
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
          $('#lblSpouseCityAddress').html(data['HouseNo'] + ', ' + data['brgyDesc'] + ', ' + data['cityMunDesc'] + ', ' + data['provDesc'] + ', ' + data['regDesc']);
          if(data['YearsStayed'] != 0 || data['YearsStayed'] != undefined)
          {
            $('#lblSpouseStay').html(data['YearsStayed'] + ' years and ' + data['MonthsStayed'] + ' months');
          }
          else
          {
            $('#lblSpouseStay').html(data['MonthsStayed'] + ' months');
          }
          
          if(data['ContactNumber'] != 0 || data['ContactNumber'] != '')
          {
            $('#lblSpouseHomeCelNo').html(data['ContactNumber']);
          }
          else
          {
            $('#lblSpouseHomeCelNo').html('N/A');
          }
          
          if(data['Telephone'] != 0 || data['Telephone'] != '')
          {
            $('#lblSpouseHomeTelNo').html(data['Telephone']);
          }
          else
          {
            $('#lblSpouseHomeTelNo').html('N/A');
          }
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
    // spouse provincial address
      $.ajax({
        url: baseUrl + "/borrower_controller/getSpouseProvAddress",
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
          $('#lblSpouseProvAddress').html(data['HouseNo'] + ', ' + data['brgyDesc'] + ', ' + data['cityMunDesc'] + ', ' + data['provDesc'] + ', ' + data['regDesc']);
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
    // spouse employer details
      $.ajax({
        url: baseUrl + "/borrower_controller/getSpouseEmployer",
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
          $('#lblSpouseEmployer').html(data['Name']);
          $('#lblSpousePosition').html(data['SpousePosition']);
          if(data['TenureYear'] != 0 || data['TenureYear'] != undefined)
          {
            $('#lblSpouseTenure').html(data['TenureYear'] + ' years and ' + data['TenureMonth'] + ' months');
          }
          else
          {
            $('#lblSpouseTenure').html(data['TenureMonth'] + ' months');
          }
          $('#lblSpouseEmail').html(data['EmailAddress']);
          $('#lblSpouseTelNo').html(data['TelephoneNumber']);
          $('#lblSpouseCelNo').html(data['ContactNumber']);
          $('#lblBusinessAddress').html(data['BusinessAddress']);
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

  function chkRent()
  {
    var radioValue = $("input[name='optionsRadios']:checked").val();
    if(radioValue == 'Rented'){
      $('#divRentedDetails').slideDown();
      $('#divLivingWithRelatives').slideUp();
      $('#txtRentedType').val(1);
    }
    else if(radioValue == 'Living with relatives')
    {
      $('#divRentedDetails').slideUp();
      $('#divLivingWithRelatives').slideDown();
      $('#txtRentedType2').val(2);
    }
    else
    {
      $('#divLivingWithRelatives').slideUp();
      $('#divRentedDetails').slideUp();
      $('#txtRentedType').val(0);
    }
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
        $('#lblComakerMonthly').html(parseInt(Math.ceil(data['MonthlyIncome'])).toLocaleString('en-US', {minimumFractionDigits: 2}));
        $('#btnSubmitCoMaker').hide();
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

  function AddComaker(BorrowerComakerId)
  {
    $('#displayComaker').hide();
    $('#ComakerForm').show();
  }

  function getDetail(borrowerId, formType)
  {
    if(formType == 1) // fpr editing of borrower
    {
      $('#lblBorrowerSpouse').html('Edit Borrower');
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
          $('#txtBirthPlace').val(data['Birthplace']);
          $('#selectGender').val(data['SexId']).change();
          $('#selectNationality').val(data['NationalityId']).change();
          $('#selectCivilStatus').val(data['CivilStatusId']).change();
          $('#selectStatusId').val(data['StatusId']).change();

          $('#DateOfBirth').daterangepicker({
              "startDate": moment(data['RawDateOfBirth']).format('DD MMM YY'),
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
    else if(formType == 2) // for viewing of employement record
    {
      $('#divEmploymentView').show();
      $('#divEmploymentForm').hide();
      $('#btnSubmitEmployer').hide();
      $.ajax({
        url: baseUrl + "/borrower_controller/getBorrowerEmployment",
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
          $('#lblEmploymentType').html(data['EmployerStatus']);
          $('#lblEmploymentIndustry').html(data['Industry']);
          $('#lblEmploymentTelNo').html(data['TelephoneNumber']);
          $('#lblEmploymentEmployer').html(data['EmployerName']);
          $('#lblEmploymentOccupation').html(data['Position']);
          $('#lblEmploymentDateHired').html(data['DateHired']);
          $('#lblEmploymentAddress').html(data['BusinessAddress']);

          if(data['TenureYear'] != 0 || data['TenureYear'] != undefined)
          {
            $('#lblEmploymentTenure').html(data['TenureYear'] + ' years and ' + data['TenureMonth'] + ' months');
          }
          else
          {
            $('#lblEmploymentTenure').html(data['TenureMonth'] + ' months');
          }
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
    else if(formType == 3) // for viewing of address record
    {
      $('#divAddressView').show();
      $('#divAddressForm').hide();
      $('#btnSubmitAddress').hide();
      // city address
        $.ajax({
          url: baseUrl + "/borrower_controller/getBorrowerCityAddress",
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
            $('#lblAddressType').html(data['AddressType']);
            $('#lblAddressTypeOfResidence').html(data['Address']);
            if(data['AddressType'] == 'City Address')
            {
              $('#divLengthOfStay').slideDown();
              $('#divTypeOfResidence').slideDown();
              if(data['Address'] == 'Rented')
              {
                $('#divTypeOfResidenceDets').slideDown();
                $('#lblAddressLandLord').html(data['NameOfLandlord']);
                $('#lblAddressTypeOfAddressTelNo').html(data['ContactNumber']);
              }
              else
              {
                $('#divTypeOfResidenceDets').slideUp();
                $('#divLengthOfStay').slideDown();
                $('#divTypeOfResidence').slideDown();
              }
            }
            else
            {
              $('#divTypeOfResidenceDets').slideUp();
              $('#divLengthOfStay').slideUp();
              $('#divTypeOfResidence').slideUp();
            }

            $('#lblAddressRecord').html(data['HouseNo'] + ', ' + data['brgyDesc'] + ', ' + data['cityMunDesc'] + ', ' + data['provDesc'] + ', ' + data['regDesc']);

            if(data['YearsStayed'] != 0 || data['YearsStayed'] != undefined)
            {
              $('#lblAddressTenure').html(data['YearsStayed'] + ' years and ' + data['MonthsStayed'] + ' months');
            }
            else
            {
              $('#lblAddressTenure').html(data['MonthsStayed'] + ' months');
            }
            
            if(data['AddressContactNumber'] != 0 || data['AddressContactNumber'] != '')
            {
              $('#lblAddressTypeOfAddressTelNo').html(data['AddressContactNumber']);
            }
            else
            {
              $('#lblAddressTypeOfAddressTelNo').html('N/A');
            }
            
            if(data['ContactNumber'] != 0 || data['ContactNumber'] != '')
            {
              $('#lblAddressCelNo').html(data['ContactNumber']);
            }
            else
            {
              $('#lblAddressCelNo').html('N/A');
            }

            if(data['IsPrimary'] == 1)
            {
              $('#lblPrimary').html('Yes');
            }
            else
            {
              $('#lblPrimary').html('No');
            }
            
            if(data['Telephone'] != 0 || data['Telephone'] != '')
            {
              $('#lblAddressTelNo').html(data['Telephone']);
            }
            else
            {
              $('#lblAddressTelNo').html('N/A');
            }
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
      // // provincial address
      //   $.ajax({
      //     url: baseUrl + "/borrower_controller/getSpouseProvAddress",
      //     type: "POST",
      //     async: false,
      //     data: {
      //       Id : SpouseId
      //     },
      //     dataType: "JSON",
      //     beforeSend: function(){
      //         $('.loading').show();
      //     },
      //     success: function(data)
      //     {
      //       $('#lblSpouseProvAddress').html(data['HouseNo'] + ', ' + data['brgyDesc'] + ', ' + data['cityMunDesc'] + ', ' + data['provDesc'] + ', ' + data['regDesc']);
      //     },
      //     error: function()
      //     {
      //       setTimeout(function() {
      //         swal({
      //           title: 'Warning!',
      //           text: 'Something went wrong, please contact the administrator or refresh page!',
      //           type: 'warning',
      //           buttonsStyling: false,
      //           confirmButtonClass: 'btn btn-primary'
      //         });
      //         // location.reload();
      //       }, 2000);
      //     }
      //   });
    }
    else // for adding of spouse
    {
      $('#lblBorrowerSpouse').html('Add Spouse');
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
            if(data == 1)
            {
              location.reload();
              swal({
                title: 'Success!',
                text: 'Successfully updated!',
                type: 'success',
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary'
              });
            }
            else
            {
              swal({
                title: 'Info!',
                text: 'Record is in use, record cannot be updated!',
                type: 'info',
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-primary'
              });
            }
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
        $('.loading').hide();
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
        $('.loading').hide();
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
        $('.loading').hide();
      }
    })
  }

  function changeRegion3(RegionId)
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
        $('#selectProvince3').html(data);
        $('.loading').hide();
      }
    })
  }

  function changeProvince3(ProvinceCode)
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
        $('#selectCity3').html(data);
        $('.loading').hide();
      }
    })
  }

  function changeCity3(CityCode)
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
        $('#selectBarangay3').html(data);
        $('.loading').hide();
      }
    })
  }

  function requirementList(ID)
  {
    $.ajax({
      url: baseUrl + "/borrower_controller/IDCategory3",
      method: "POST",
      data: { Id :  ID},
      beforeSend: function(){
        $('.loading').show();
      },
      success: function(data)
      {
        $('#selectReqTypeId').html(data);
        $('.loading').hide();
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

  function addressType(value)
  {
    if(value == 'City Address')
    {
      $('#divCityAddress').slideDown();
      $('#divResidenceType').slideDown();
    }
    else
    {
      $('#divCityAddress').slideUp();
      $('#divResidenceType').slideUp();
    }
  }

  $(function () {

    $('#divCityAddress').slideDown();

    $(".frmCheck").on('submit', function (e) {
      e.preventDefault(); 
      swal({
        title: 'Confirm',
        text: 'Submit Form?',
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

    $("#frmInsert").on('submit', function (e) {
      if(varNewPassword = 1 && varStatus == 1 && $('#txtNewPassword').val() == $('#txtConfirmPassword').val() && $('#txtOldPassword').val() != $('#txtNewPassword').val())
      {
        e.preventDefault(); 
        swal({
          title: 'Confirm',
          text: 'Are you sure with this password?',
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
        "maxDate": moment().format('DD MMM YY'),
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

    $('#dtpSpouseDateHired').daterangepicker({
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
        $('#selectRegion3').html(data);
        $('.loading').hide();
      }
    })

    $('#example1').DataTable({
      "pageLength": 10,
      "aoColumnDefs": [{ "bVisible": false, "aTargets": [4] }],
      "order": [[4, "desc"]]
    });

    $('#dtblCollections').DataTable({
      "pageLength": 10,
      "aoColumnDefs": [{ "bVisible": false, "aTargets": [7] }],
      "order": [[5, "desc"]]
    });
    
    $('#example2').DataTable();

    $('#example3').DataTable();

    $('#example4').DataTable();
    $('#example10').DataTable({
      "pageLength": 10,
      "aoColumnDefs": [{ "bVisible": false, "aTargets": [7] }],
      "order": [[3, "desc"], [4, "desc"]]
    });

    $('#example5').DataTable({
      "pageLength": 10,
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[0, "desc"]]
    });

    $('#example6').DataTable();

    $('#example7').DataTable({
      "pageLength": 10,
      "aoColumnDefs": [{ "bVisible": false, "aTargets": [7] }],
      "order": [[3, "desc"], [4, "desc"]]
    });

    $('#example8').DataTable({
      "pageLength": 10,
      "aoColumnDefs": [{ "bVisible": false, "aTargets": [6] }],
      "order": [[3, "desc"], [4, "desc"]]
    });

    $('#example9').DataTable();

    $('#modalNewCoMaker').on('hidden.bs.modal', function () {
      $('#btnSubmitCoMaker').show();
    })

    $('#modalNewEmployment').on('hidden.bs.modal', function () {
      document.getElementById("frmEmploymentRecord").reset();
      $('#divEmploymentView').hide();
      $('#divEmploymentForm').show();
      $('#btnSubmitEmployer').show();
    })

    $('#modalNewAddress').on('hidden.bs.modal', function () {
      document.getElementById("frmAddressRecord").reset();
      $('#divAddressView').hide();
      $('#divAddressForm').show();
      $('#btnSubmitAddress').show();
      $('#divResidenceType').show();
    })

    $('#modalProfilePicture').on('hidden.bs.modal', function () {
      Webcam.reset();
      $('#cameraDiv').hide();
      $('#cameraDivBtn').show();
      $('#cameraDivBtnOff').hide();
      $('#btnCameraSave').hide();
      $('#btnCameraUpload').show();
      $('#uploadPic').show();
    })

    $('#modalBorrowerDetails').on('hidden.bs.modal', function () {
      $('#btnSpouseView').show();
    })



  })