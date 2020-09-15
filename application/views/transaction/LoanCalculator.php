
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Loan Calculator
    </h1>
    <ol class="breadcrumb">
      <li><a href="#" class="active"><i class="fa fa-dashboard"></i>Loan Calculator</a></li>
    </ol>
  </section>

  <section class="content">
    <div class="box box-default">
      <div class="box-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="selectLoanType">Loan Type <span class="text-red">*</span></label><br>
              <select class="form-control select1" style="width: 100%" required="" onchange="loanSummary()" name="LoanTypeId" id="selectLoanType">
                <?php
                  echo $LoanType;
                ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Principal Amount<span class="text-red">*</span></label><br>
              <input type="number" class="form-control" placeholder="Principal Amount" oninput="btnRemoveCharges(); loanSummary()" id="txtPrincipalAmount" name="PrincipalAmount">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Term Type<span class="text-red">*</span></label><br>
              <select class="form-control" style="width: 100%" required="" onchange="getRepaymentDuration(); loanSummary()" name="TermType" id="selectTermType">
                <option value="" disabled="">Select Term Type</option>
                <option>Days</option>
                <option>Weeks</option>
                <option selected="" >Months</option>
                <option>Years</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Term<span class="text-red">*</span></label><br>
              <input type="number" class="form-control" oninput="getRepaymentDuration(); getPrincipalCollection(); getTotalCollection(); loanSummary()" name="TermNumber" id="txtTermNo">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Repayment Cycle<span class="text-red">*</span></label><br>
              <select class="form-control" style="width: 100%" required="" onchange="getRepaymentDuration(); getPrincipalCollection(); getTotalCollection(); loanSummary()" name="RepaymentCycle" id="selectRepaymentType">
                <?php
                  echo $repaymentCycle;
                ?>
              </select>
              <a href=""> Add/Edit Repayment Cycle</a>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Number of Repayments<span class="text-red">*</span></label><br>
              <input type="number" min="0" class="form-control" onchange="getPrincipalCollection(); getTotalCollection(); loanSummary()" name="RepaymentsNumber" required="" id="txtRepayments">
            </div>
          </div>
        </div>
        <h4>Interest</h4>
        <hr>
        <div class="row">
          <div class="col-md-2">
            <label>Interest Type</label>
            <select class="form-control" id="selectInterestType" name="interestType" onchange="getTotalInterest(); loanSummary()">
              <option selected="">Flat Rate</option>
              <option>Percentage</option>
            </select>
          </div>
          <div class="col-md-3">
            <label>Interest Amount</label>
            <input type="number" class="form-control" id="txtInterest" name="interestAmount" onchange="getTotalInterest(); loanSummary()">
          </div>
          <div class="col-md-3">
            <label>Interest Frequency</label>
            <select class="form-control" id="selectInterestFrequency" name="interestFrequency" onchange="getTotalInterest(); loanSummary()">
              <option selected="" disabled="">Select Interest Frequency</option>
              <option>Per Day</option>
              <option>Per Week</option>
              <option>Per Month</option>
              <option>Per Year</option>
              <option>Per Loan</option>
            </select>
          </div>
          <div class="col-md-2">
            <label>Add-On Interest Rate</label>
            <h6 class="lblTotalInterest">Php 0.00</h6>
          </div>
          <div class="col-md-2">
            <label>Total Interest</label>
            <h6 class="lblFinalInterest">Php 0.00</h6>
          </div>
        </div>
        <br>
        <h4>Additional Charges <small><a href=""> Add/Edit Additional Charges</a></small> <a class="btn btn-sm btn-primary pull-right" id="btnAddCharges" onclick="btnCharges()">Add Charges</a> <a class="btn btn-sm btn-primary pull-right" style="display: none" onclick="btnRemoveCharges()" id="btnRemoveCharges">Remove Charges</a></h4>
        <hr>
        <div id="divAdditionalCharges" style="display: none">
          <div class="row">
            <div class="col-md-12">
              <table id="example3" class="table table-bordered table-hover" style="width: 100%">
                <thead>
                <tr>
                  <th width="15px">Select</th>
                  <th>Charge</th>
                  <th>Amount</th>
                  <th>Total</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <br>
        <h4>Loan Summary</h4>
        <hr>
        <div class="row">
          <div class="col-md-4">
            <label>Total Additional Charges</label>
            <h6 class="lblTotalAdditionalCharges">Php 0.00</h6>
          </div>
          <div class="col-md-4">
            <label>Total Collections</label>
            <h6 class="lblTotalCollections">Php 0.00</h6>
          </div>
          <div class="col-md-4">
            <label>Total Cost of Loan</label>
            <h6 class="lblTotalLoanCost">Php 0.00</h6>
          </div>
          <div class="col-md-4">
            <label>Principal per Collection</label>
            <h6 class="lblPrincipalPerCollection">Php 0.00</h6>
          </div>
          <div class="col-md-4">
            <label>Interest per Collection</label>
            <h6 class="lblInterestPerCollection">Php 0.00</h6>
          </div>
          <div class="col-md-4">
            <label>Payable per Collection</label>
            <h6 class="lblPayablePerCollection">Php 0.00</h6>
          </div>
          <div class="col-md-4">
            <label>Net Loan Amount</label>
            <h6 class="lblNetLoanAmount">Php 0.00</h6>
          </div>
        </div>
      </div>
      <div class="box-footer">
        <div class="pull-right">
        <button type="submit" class="btn btn-default">Cancel</button>
        <button type="submit" class="btn btn-success">Apply Loan</button>
        </div>
      </div>
    </div>
  </section>
</div>

<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0
  </div>
  <strong>Copyright &copy; 2020 <a href="https://adminlte.io">GIA Tech.</a>.</strong> All rights
  reserved.
</footer>

<!-- <div class="loading" style="display: none">Loading&#8230;</div> -->
<?php $this->load->view('includes/footer'); ?>

<script>

  // LOAN COMPUTATION
    var varPrincipalAmount, varTermType, varTermNo, varRepaymentType, varRepaymentNo;
    function getTotalInterest()
    {
      var interestAmount = $('#txtInterest').val();
      var termNo = $('#txtTermNo').val();
      var interestFrequency = $('#selectInterestFrequency').val();
      var interestType = $('#selectInterestType').val();

      var totalInterest = 0;

      if (interestFrequency == "Per Day")
      {
        totalInterest = interestAmount * termNo;
      }
      if (interestFrequency == "Per Week")
      {
        totalInterest = interestAmount * termNo;
      }
      if (interestFrequency == "Per Month")
      {
        totalInterest = interestAmount * termNo;
      }
      if (interestFrequency == "Per Year")
      {
        totalInterest = interestAmount * termNo;
      }

      totalInterest = Math.floor(totalInterest);
     
      if (totalInterest > 0)
      {
        if(interestType == 'Flat Rate')
        {
          finalInterest = parseInt($('#txtPrincipalAmount').val()) + parseInt(totalInterest);
          $('.lblTotalInterest').html('Php ' + parseInt(totalInterest).toLocaleString('en-US', {minimumFractionDigits: 2}));
        }
        else if(interestType == 'Percentage')
        {
          finalInterest = parseInt($('#txtPrincipalAmount').val()) * parseInt(totalInterest)/100;
          $('.lblTotalInterest').html(totalInterest + '%');
        }
        $('.lblFinalInterest').html('Php ' + parseInt(finalInterest).toLocaleString('en-US', {minimumFractionDigits: 2}));
        displayTotalLoanCost = parseInt(finalInterest) + parseInt(displayTotal);
        $('.lblTotalLoanCost').html("Php " + parseInt(displayTotalLoanCost).toLocaleString('en-US', {minimumFractionDigits: 2}));
        // display interest per collection [Total Interest / Total Collections]
          displayInterestPerCollection = parseInt(finalInterest / parseInt($('#txtTermNo').val() * $('#txtRepayments').val()));
          $('.lblInterestPerCollection').html('Php ' + displayInterestPerCollection).toLocaleString('en-US', {minimumFractionDigits: 2});
        // display payable per collection [Principal Per Collection + Interest per Collection]
          displayPayablePerCollection = parseInt(displayPrincipalPerCollection) + parseInt(displayInterestPerCollection);
          $('.lblPayablePerCollection').html('Php ' + parseInt(displayPayablePerCollection).toLocaleString('en-US', {minimumFractionDigits: 2}));
        // display net loan amount [loan amount - Processing Fee]
          displayNetLoan = parseInt($('#txtPrincipalAmount').val()) - parseInt(displayTotal);
          $('.lblNetLoanAmount').html('Php ' + parseInt(displayNetLoan).toLocaleString('en-US', {minimumFractionDigits: 2}));
      }
    }

    function getRepaymentDuration()
    {
      var repaymentType = $('#selectRepaymentType').val();
      var termType = $('#selectTermType').val();
      var loanRepaymentNo = $('#txtRepayments').val();
      var loanDurationNo = $('#txtTermNo').val();

      if (repaymentType != "")
      {
        var totalRepayments = 0;
        var yearly = 0;
        var monthly = 0;
        var weekly = 0;
        var daily = 0;
      
        if (repaymentType == 1) // daily
        {
          yearly = 360;
          monthly = 30;
          biweekly = 14;
          weekly = 7;
          daily = 1;
        }  
        else if (repaymentType == 2) // weekly
        {
          yearly = 52;
          monthly = 4;
          biweekly = 2;
          weekly = 1;
          daily = 1/7;
        }
        else if (repaymentType == 3) // monthly
        {
          yearly = 12;
          monthly = 1;
          biweekly = 1/2;
          weekly = 1/4;
          daily = 1/30;
        }
        else if (repaymentType == 4) // yearly
        {
          yearly = 1;
          monthly = 1/12;
          biweekly = 1/24;
          weekly = 1/38;
          daily = 1/360;
        } 
        else
        {
          yearly = 1;
          monthly = 1;
          weekly = 1;
          daily = 1;
        }
         
        if (termType == "Days")
        {
          totalRepayments = loanDurationNo * daily;
        }
        if (termType == "Weeks")
        {
          totalRepayments = loanDurationNo * weekly;
        }
        if (termType == "Months")
        {
          totalRepayments = loanDurationNo * monthly;
        }
        if (termType == "Years")
        {
          totalRepayments = loanDurationNo * yearly;
        }

        totalRepayments = Math.floor(totalRepayments);
       
        if (repaymentType == 5) // lump sum 
          totalRepayments = 1;
        
        if (totalRepayments > 0)
          $('#txtRepayments').val(totalRepayments);
          displayPrincipalPerCollection = $('#txtPrincipalAmount').val() / totalRepayments;
          $('.lblPrincipalPerCollection').html('Php ' + parseInt(displayPrincipalPerCollection).toLocaleString('en-US', {minimumFractionDigits: 2}));
        // display net loan amount [loan amount - Processing Fee]
          displayNetLoan = parseInt($('#txtPrincipalAmount').val()) - parseInt(displayTotal);
          $('.lblNetLoanAmount').html('Php ' + parseInt(displayNetLoan).toLocaleString('en-US', {minimumFractionDigits: 2}));
      }
    }

    function getPrincipalCollection()
    {
      // display Principal Per Collection [Loan Amount / Total Collection]
        displayPrincipalPerCollection = parseInt($('#txtPrincipalAmount').val()) / parseInt($('#txtTermNo').val() * $('#txtRepayments').val());
        $('.lblPrincipalPerCollection').html('Php ' + parseInt(displayPrincipalPerCollection).toLocaleString('en-US', {minimumFractionDigits: 2}));
      // display interest per collection [Total Interest / Total Collections]
        displayInterestPerCollection = parseInt(finalInterest / parseInt($('#txtTermNo').val() * $('#txtRepayments').val()));
        $('.lblInterestPerCollection').html('Php ' + displayInterestPerCollection).toLocaleString('en-US', {minimumFractionDigits: 2});
      // display Total Cost Loan [Processing Fees + Total Interest]
        displayTotalLoanCost = parseInt(displayTotal) + parseInt(finalInterest);
        $('.lblTotalLoanCost').html("Php " + parseInt(displayTotalLoanCost).toLocaleString('en-US', {minimumFractionDigits: 2}));
      // display payable per collection [Principal Per Collection + Interest per Collection]
        displayPayablePerCollection = parseInt(displayPrincipalPerCollection) + parseInt(displayInterestPerCollection);
        $('.lblPayablePerCollection').html('Php ' + parseInt(displayPayablePerCollection).toLocaleString('en-US', {minimumFractionDigits: 2}));
      // display net loan amount [loan amount - Processing Fee]
        displayNetLoan = parseInt($('#txtPrincipalAmount').val()) - parseInt(displayTotal);
        $('.lblNetLoanAmount').html('Php ' + parseInt(displayNetLoan).toLocaleString('en-US', {minimumFractionDigits: 2}));
    }

    function getTotalCollection()
    {
      // display total collections
        displayTotalCollections = $('#txtTermNo').val() * $('#txtRepayments').val();
        $('.lblTotalCollections').html(displayTotalCollections);
      // display interest per collection [Total Interest / Total Collections]
        displayInterestPerCollection = parseInt(finalInterest / parseInt($('#txtTermNo').val() * $('#txtRepayments').val()));
        $('.lblInterestPerCollection').html('Php ' + displayInterestPerCollection).toLocaleString('en-US', {minimumFractionDigits: 2});
      // display payable per collection [Principal Per Collection + Interest per Collection]
        displayPayablePerCollection = parseInt(displayPrincipalPerCollection) + parseInt(displayInterestPerCollection);
        $('.lblPayablePerCollection').html('Php ' + parseInt(displayPayablePerCollection).toLocaleString('en-US', {minimumFractionDigits: 2}));
      // display net loan amount [loan amount - Processing Fee]
        displayNetLoan = parseInt($('#txtPrincipalAmount').val()) - parseInt(displayTotal);
        $('.lblNetLoanAmount').html('Php ' + parseInt(displayNetLoan).toLocaleString('en-US', {minimumFractionDigits: 2}));
    }

    function loanSummary()
    {
      $('#lblLoanType').html($('#selectLoanType option:selected').data('city'));
      $('#lblSource').html($('#selectSource option:selected').data('city'));
      $('#lblPurpose').html($('#selectPurpose option:selected').data('city'));
      $('#lblReleaseDate').html($('#loanReleaseDate').val());
      $('#lblDisbursedType').html($('#selectDisbursedBy option:selected').data('city'));
      $('#lblPrincipalAmount').html($('#txtPrincipalAmount').val());
      $('#lblTerm').html($('#txtTermNo').val() + ' '  + $('#selectTermType').val());
      $('#lblRepayments').html($('#txtRepayments').val() + ' ' + $('#selectRepaymentType option:selected').data('city'));
      $('#lblInterestType').html($('#selectInterestType').val());
      $('#lblInterestAmount').html($('#txtInterest').val());
      $('#lblInterestFrequency').html($('#selectInterestFrequency').val());
    }
  // OTHERS
    var TotalIncome = 0;
    var TotalExpense = 0;
    var TotalObligation = 0;
    var displayTotal = 0;
    var displayTotalLoanCost = 0;
    var displayPrincipalPerCollection = 0;
    var displayInterestPerCollection = 0;
    var displayTotalLoanCost = 0;
    var finalInterest = 0; // total interest
    var displayPayablePerCollection = 0;
    var displayTotalCollections = 0;
    var displayNetLoan = 0;

    function onChangeLoanStatus(value)
    {
      isApprovable = $('#selectLoanStatus').val()
      if(isApprovable == 3)
      {
        $.ajax({
          url: "<?php echo base_url();?>" + "/admin_controller/getApprovers",
          method: "POST",
          beforeSend: function(){
            $('.loading').show();
          },
          success: function(data)
          {
            $('#selectApprovers').html(data);
          }
        })
        $('#divLoanApproval').slideDown();
      }
      else
      {
        $('#divLoanApproval').slideUp();
      }
    }

    function requirementType(value)
    {
      var table = $("#dtblRequirement tbody");
      $.ajax({
        url: "<?php echo base_url();?>" + "/admin_controller/getRequirements",
        type: "POST",
        async: false,
        data: {
          Id : value
        },
        dataType: "JSON",
        beforeSend: function(){
            $('.loading').show();
        },
        success: function(data)
        {
          if(data == 0)
          {
            table.empty();
            table.append("<tr><td colspan='3'><center>No data available</center></td>" +
                "</tr>");
          }
          else
          {
            var varDescription, isChecked;
            var rowCount = 0;
            var isSelected = 0; 
            table.empty();
            $.each(data, function (a, b) {
              rowCount = rowCount + 1;
              if(b.IsMandatory == 1)
              {
                isChecked = 'checked disabled'; 
                isSelected = 1;
              }
              else
              {
                isChecked = '';
                isSelected = 0;
              }

              if(b.Description == null)
              {
                varDescription = 'N/A'
              }
              else
              {
                varDescription = b.Description
              }
              table.append("<tr><td><center><label><input onclick='chkRequirements("+rowCount+")' id='selectCheckReq"+rowCount+"' "+isChecked+" type='checkbox' value='"+b.RequirementId+"'></label></center></td>" +
                "<td>"+b.Name+"</td>"+
                "<td>"+varDescription+
                "<input type='hidden' name='RequirementId[]' id='txtRequirementId"+rowCount+"' value='"+b.RequirementId+"'>"+
                "<input type='hidden' name='isRequirementSelected[]' id='isRequirementSelected"+rowCount+"' value='"+isSelected+"'>"+
                "<input type='hidden' name='RequirementNo[]' id='requirementRowCount"+rowCount+"' value='"+rowCount+"'>"+
                "</td>"+
                "</tr>");
            });
          }
          $('#divRequirementList').slideDown();
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
          }, 2000);
        }
      });
    }

    function changeAmount(value, type)
    {
      if(type == 1) // income
      {
        TotalIncome = 0;
        var items = document.getElementsByClassName('incomeAmount');
        for (var i = 0; i < items.length; i++)
        {
          TotalIncome = parseInt(TotalIncome) + parseInt(items[i].value);
        }
        $('.lblTotalIncome').html('Php ' + parseInt(TotalIncome).toLocaleString('en-US', {minimumFractionDigits: 2}));
        monthlySalaries();
      }
      else if(type == 2) // expenses
      {
        TotalExpense = 0;
        var items = document.getElementsByClassName('expenseAmount');
        for (var i = 0; i < items.length; i++)
        {
          TotalExpense = parseInt(TotalExpense) + parseInt(items[i].value);
        }
        $('.lblTotalExpense').html('Php ' + parseInt(TotalExpense).toLocaleString('en-US', {minimumFractionDigits: 2}));
        monthlySalaries();
      }
      else if(type == 3) // obiligations
      {
        TotalObligation = 0;
        var items = document.getElementsByClassName('obligationAmount');
        for (var i = 0; i < items.length; i++)
        {
          TotalObligation = parseInt(TotalObligation) + parseInt(items[i].value);
        }
        $('.lblTotalObligation').html('Php ' + parseInt(TotalObligation).toLocaleString('en-US', {minimumFractionDigits: 2}));
        monthlySalaries();
      }
    }

    function functionSourceChange(value)
    {
      if(value == 'Through Agent')
      {
        $('#agentDiv').slideDown();
      }
      else
      {
        $('#agentDiv').slideUp();
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

    function btnCharges() 
    {
      if($('#txtPrincipalAmount').val() == '')
      {
        swal({
          title: 'Warning!',
          text: 'Principal Amount cannot be blank!',
          type: 'warning',
          buttonsStyling: false,
          confirmButtonClass: 'btn btn-primary'
        });
      }
      else
      {
        $('#divAdditionalCharges').slideDown();
        $('#btnRemoveCharges').show();
        $('#btnAddCharges').hide();
        var table = $("#example3 tbody");
        var PrincipalAmount = $('#txtPrincipalAmount').val();
        $.ajax({
          url: "<?php echo base_url()?>/admin_controller/getCharges",
          type: "POST",
          async: false,
          dataType: "JSON",
          beforeSend: function(){
              $('.loading').show();
          },
          success: function(data)
          {
            if(data == 0)
            {
              table.empty();
              table.append("<tr><td colspan='4'><center>No data available</center></td></tr>");
            }
            else
            {
              var row = 0; 
              var isSelected = 0; 
              table.empty();
              var total = 0;
              $.each(data, function (a, b) {
                row = row + 1; 

                if(b.ChargeType == 'Percentage')
                {
                  total = parseInt(b.Amount)/100 * parseInt(PrincipalAmount);
                  amount = parseInt(b.Amount).toLocaleString('en-US', {minimumFractionDigits: 2}) + '%';
                }
                else
                {
                  total = parseInt(b.Amount);
                  amount = 'Php ' + parseInt(b.Amount).toLocaleString('en-US', {minimumFractionDigits: 2});
                }

                if(b.IsMandatory == 1)
                {
                  checked = 'checked disabled'; 
                  isSelected = 1;
                  displayTotal = parseInt(displayTotal) + parseInt(total);
                }
                else
                {
                  checked = '';
                  isSelected = 0;
                  displayTotal = displayTotal + 0;
                }

                table.append('<tr><td><center><label><input type="checkbox" id="selectCheck'+row+'" onclick="chkSelect('+row+')" class="checkCharges" '+checked+' value="'+row+'"></label></center></td>' +
                  "<td>"+b.Name+"</td>"+
                  "<td>"+amount+"</td>"+
                  "<td>"+
                    "<input type='hidden' name='ChargeNo[]' value='"+row+"'>"+
                    "<input type='hidden' name='ChargeId[]' value='"+b.ChargeId+"'>"+
                    "<input type='hidden' id='txtChargeAmount"+row+"' value='"+parseInt(b.Amount)+"'>"+
                    "<input type='hidden' name='IsSelected[]' id='isSelected"+row+"' value='"+isSelected+"'>"+
                    "<input type='hidden' class='chargeTotal[]' value='"+parseInt(total)+"'>"+
                    "<input type='hidden' id='txtChargeType"+row+"' value='"+b.ChargeType+"'> "+
                    "Php "+parseInt(total).toLocaleString('en-US', {minimumFractionDigits: 2})+"</td>"+
                  "</tr>"
                );
              });

              $('.lblTotalAdditionalCharges').html("Php " + parseInt(displayTotal).toLocaleString('en-US', {minimumFractionDigits: 2}))
              // display Principal Per Collection [Loan Amount / Total Collection]
                displayPrincipalPerCollection = parseInt($('#txtPrincipalAmount').val()) / parseInt($('#txtTermNo').val() * $('#txtRepayments').val());
                $('.lblPrincipalPerCollection').html('Php ' + parseInt(displayPrincipalPerCollection).toLocaleString('en-US', {minimumFractionDigits: 2}));
              // display interest per collection [Total Interest / Total Collections]
                displayInterestPerCollection = parseInt(finalInterest / parseInt($('#txtTermNo').val() * $('#txtRepayments').val()));
                $('.lblInterestPerCollection').html('Php ' + displayInterestPerCollection).toLocaleString('en-US', {minimumFractionDigits: 2});
              // display Total Cost Loan [Processing Fees + Total Interest]
                displayTotalLoanCost = parseInt(displayTotal) + parseInt(finalInterest);
                $('.lblTotalLoanCost').html("Php " + parseInt(displayTotalLoanCost).toLocaleString('en-US', {minimumFractionDigits: 2}));
              // display payable per collection [Principal Per Collection + Interest per Collection]
                displayPayablePerCollection = parseInt(displayPrincipalPerCollection) + parseInt(displayInterestPerCollection);
                $('.lblPayablePerCollection').html('Php ' + parseInt(displayPayablePerCollection).toLocaleString('en-US', {minimumFractionDigits: 2}));
              // display net loan amount [loan amount - Processing Fee]
                displayNetLoan = parseInt($('#txtPrincipalAmount').val()) - parseInt(displayTotal);
                $('.lblNetLoanAmount').html('Php ' + parseInt(displayNetLoan).toLocaleString('en-US', {minimumFractionDigits: 2}));
            }
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
    }

    function chkSelect(rowId)
    {
      var ChargeAmount = $('#txtChargeAmount'+rowId+'').val();
      var ChargeType = $('#txtChargeType'+rowId+'').val();
      var PrincipalAmount = $('#txtPrincipalAmount').val();
      if($('#selectCheck'+rowId+'').is(":checked") == true)
      {
        if(ChargeType == 'Percentage')
        {
          displayTotal = parseInt(displayTotal) +  parseInt(ChargeAmount)/100 * parseInt(PrincipalAmount);
        }
        else
        {
          displayTotal = parseInt(displayTotal) +  parseInt(ChargeAmount);
        }
      }
      else
      {
        if(ChargeType == 'Percentage')
        {
          displayTotal = parseInt(displayTotal) - parseInt(ChargeAmount)/100 * parseInt(PrincipalAmount);
        }
        else
        {
          displayTotal = parseInt(displayTotal) - parseInt(ChargeAmount);
        }
      }
      $('.lblTotalAdditionalCharges').html("Php " + parseInt(displayTotal).toLocaleString('en-US', {minimumFractionDigits: 2}))
      $('.lblTotalLoanCost').html("Php " + parseInt(displayTotalLoanCost).toLocaleString('en-US', {minimumFractionDigits: 2}));
      // display Principal Per Collection [Loan Amount / Total Collection]
        displayPrincipalPerCollection = parseInt($('#txtPrincipalAmount').val()) / parseInt($('#txtTermNo').val() * $('#txtRepayments').val());
        $('.lblPrincipalPerCollection').html('Php ' + parseInt(displayPrincipalPerCollection).toLocaleString('en-US', {minimumFractionDigits: 2}));
      // display interest per collection [Total Interest / Total Collections]
        displayInterestPerCollection = parseInt(finalInterest / parseInt($('#txtTermNo').val() * $('#txtRepayments').val()));
        $('.lblInterestPerCollection').html('Php ' + displayInterestPerCollection).toLocaleString('en-US', {minimumFractionDigits: 2});
      // display Total Cost Loan [Processing Fees + Total Interest]
        displayTotalLoanCost = parseInt(displayTotal) + parseInt(finalInterest);
        $('.lblTotalLoanCost').html("Php " + parseInt(displayTotalLoanCost).toLocaleString('en-US', {minimumFractionDigits: 2}));
      // display payable per collection [Principal Per Collection + Interest per Collection]
        displayPayablePerCollection = parseInt(displayPrincipalPerCollection) + parseInt(displayInterestPerCollection);
        $('.lblPayablePerCollection').html('Php ' + parseInt(displayPayablePerCollection).toLocaleString('en-US', {minimumFractionDigits: 2}));
      // display net loan amount [loan amount - Processing Fee]
        displayNetLoan = parseInt($('#txtPrincipalAmount').val()) - parseInt(displayTotal);
        $('.lblNetLoanAmount').html('Php ' + parseInt(displayNetLoan).toLocaleString('en-US', {minimumFractionDigits: 2}));
    }

    function btnRemoveCharges()
    {
      displayTotal = 0;
      $('#btnRemoveCharges').hide();
      $('#btnAddCharges').show();
      $('#divAdditionalCharges').slideUp();
      $('.lblTotalAdditionalCharges').html('Php 0.00');
      displayTotalLoanCost = parseInt(finalInterest) + parseInt(displayTotal);
      $('.lblTotalLoanCost').html("Php " + parseInt(displayTotalLoanCost).toLocaleString('en-US', {minimumFractionDigits: 2}));
      // display net loan amount [loan amount - Processing Fee]
        displayNetLoan = parseInt($('#txtPrincipalAmount').val()) - parseInt(displayTotal);
        $('.lblNetLoanAmount').html('Php ' + parseInt(displayNetLoan).toLocaleString('en-US', {minimumFractionDigits: 2}));
    }


  $(function () {
  })
</script>