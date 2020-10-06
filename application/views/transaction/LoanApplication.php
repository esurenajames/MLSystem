<style type="text/css">
  .sw-theme-dots > ul.step-anchor > li {
    border: none;
    margin-left: 30px;
    z-index: 500;
  }
  .sw-theme-dots > ul.step-anchor:before {
    content: " ";
    position: absolute;
    top: 70px;
    bottom: 0;
    width: 100%;
    height: 5px;
    background-color: #ffffff;
    border-radius: 3px;
    z-order: 0;
    z-index: 95;
  }

  .table-bordereds>thead>tr>th, .table-bordereds>tbody>tr>th, .table-bordereds>tfoot>tr>th, .table-bordereds>thead>tr>td, .table-bordereds>tbody>tr>td, .table-bordereds>tfoot>tr>td {
      border: 1px solid #4e4b4b;
  }
</style>

<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Create Loan Application
    </h1>
    <ol class="breadcrumb">
      <li><a href="#" class="active"><i class="fa fa-dashboard"></i>Loans</a></li>
      <li><a href="#"></i>Loan Application</a></li>
    </h1>
    </ol>
  </section>

    <section class="content">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Loan Details</h3>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/submitApplication" id="frmSubmitForm" class="frmSubmit" method="post">
          <div class="box-body">
            <div id="smartwizard">
              <ul>
                <li><a href="#LP">Loan Product<br/></a></li>
                <li><a href="#BD">Borrower Detail<br/></a></li>
                <li><a href="#SI">Source Of Other Income<br/></a></li>
                <li><a href="#ME">Monthly Expense<br/></a></li>
                <li><a href="#MO">Monthly Obligation<br/></a></li>
                <li><a href="#SU">Summary<br/></a></li>
              </ul>
              <div>
                <div id="LP" class="">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="selectLoanType">Loan Type <span class="text-red">*</span></label><br>
                        <select class="form-control select1" style="width: 100%" required="" onchange="loanSummary()" name="LoanTypeId" id="selectLoanType">
                          <?php
                            echo $LoanType;
                          ?>
                        </select>
                        <a href=""> Add/Edit Loan Products</a>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <label></label>
                      <label><input id='chkPenalty' name="IsPenalized" onclick='onchangeIsPenalized()' type='checkbox'> Enable Late Repayment Penalty?</label>
                    </div>
                    <br>
                    <div id="divPenalty" style="display: none">
                      <div class="col-md-4">
                        <label>Penalty Type</label>
                        <select class="form-control" id="selectPenaltyType" onchange="onchangePenaltyType()" name="PenaltyType">
                          <option>Flat Rate</option>
                          <option>Percentage</option>
                        </select>
                      </div>
                      <div class="col-md-4">
                        <label id="inputLblPenaltyType">Amount</label>
                        <input type="number" min="0" class="form-control" name="PenaltyAmount" id="txtPenaltyAmount">
                      </div>
                      <div class="col-md-4">
                        <label>Grace Period</label>
                        <input type="number" min="0" class="form-control" name="GracePeriod">
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Source<span class="text-red">*</span></label><br>
                        <select class="form-control" onchange="functionSourceChange(this.value); loanSummary()" id="selectSource" style="width: 100%" required="" name="SourceType">
                          <option selected="" value="" disabled="">Select Borrower Source</option>
                          <option value="Walk-in">Walk-in</option>
                          <option value="Through Agent">Through Agent</option>
                        </select>
                        <div id="agentDiv" style="display: none">
                          <br>
                          <label>Agent's Name<span class="text-red">*</span></label><br>
                          <input type="text" class="form-control" id="txtAgentName" name="AgentName">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Purpose<span class="text-red">*</span></label><br>
                        <select class="form-control" style="width: 100%" onchange="loanSummary()" id="selectPurpose" name="PurposeId">
                          <?php
                            echo $Purpose;
                          ?>
                        </select>
                        <a href=""> Add/Edit Purpose</a>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <div class="form-group">
                            <div class="form-group">
                              <label>Loan Release Date <span class="text-red">*</span></label>
                              <div class="input-group date">
                                <div class="input-group-addon">
                                  <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" placeholder="Date of Birth" class="form-control" onchange="loanSummary()" name="loanReleaseDate" required="" id="loanReleaseDate">
                              </div>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Disbursed By<span class="text-red">*</span></label><br>
                        <select class="form-control" style="width: 100%" required="" id="selectDisbursedBy" onchange="loanSummary()" name="DisbursedBy">
                          <?php
                            echo $disbursements;
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Loan Amount<span class="text-red">*</span></label><br>
                        <input type="number" class="form-control" placeholder="Loan Amount" oninput="getTotalInterest(); btnRemoveCharges(); loanSummary()" id="txtPrincipalAmount" name="PrincipalAmount">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Term Type<span class="text-red">*</span></label><br>
                        <select class="form-control" style="width: 100%" required="" onchange="getTotalInterest(); getRepaymentDuration(); loanSummary()" name="TermType" id="selectTermType">
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
                        <input type="number" class="form-control" oninput="getRepaymentDuration(); getTotalInterest(); getPrincipalCollection(); getTotalCollection(); loanSummary()" name="TermNumber" id="txtTermNo">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Repayment Cycle<span class="text-red">*</span></label><br>
                        <select class="form-control" style="width: 100%" required="" onchange="getTotalInterest(); getRepaymentDuration(); getPrincipalCollection(); getTotalCollection(); loanSummary()" name="RepaymentCycle" id="selectRepaymentType">
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
                        <input type="number" min="0" class="form-control" onchange="getTotalInterest(); getPrincipalCollection(); getTotalCollection(); loanSummary()" name="RepaymentsNumber" required="" id="txtRepayments">
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
                  <input type="hidden" id="txtIsCharged">
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
                <div id="BD" class="">
                  <div class="row">
                    <div class="col-md-12" id="divSelectBorrower">
                      <label>Select Borrower</label>
                      <select class="form-control select2" name="borrowerId" style="width: 100%" onchange="displayBorrowerDetails(this.value)" id="selectBorrowerNumber">
                        <?php
                          echo $borrowerList;
                        ?>
                      </select>
                    </div>
                    <div class="col-md-12">
                      <div id="divBorrowerDetails" style="display: none">
                        <br>
                        <br>
                        <table class="table table-bordereds">
                          <tbody>
                            <tr>
                              <td colspan="4">PERSONAL INFORMATION</td>
                            </tr>
                            <tr>
                              <td><label> Borrower Number</label></td>
                              <td><h6 class="lblBorrowerNumber"></h6></td>
                              <td width="200px"><label> Date Added</label></td>
                              <td><h6 class="lblDateAdded"></h6></td>
                            </tr>
                            <tr>
                              <td><label> Status</label></td>
                              <td><h6 class="lblBorrowerStatus"></h6></td>
                              <td><label> Added By</label></td>
                              <td><h6 class="lblAddedBy"></h6></td>
                            </tr>
                            <tr>
                              <td width="200px"><label> First Name</label></td>
                              <td><h6 class="lblFName"></h6></td>
                              <td><label> No. of Dependents</label></td>
                              <td><h6 class="lblDependents"></h6></td>
                            </tr>
                            <tr>
                              <td><label> Middle Name</label></td>
                              <td><h6 class="lblMName"></h6></td>
                              <td><label> Nationality</label></td>
                              <td><h6 class="lblNationality"></h6></td>
                            </tr>
                            <tr>
                              <td><label> Last Name</label></td>
                              <td><h6 class="lblLName"></h6></td>
                              <td><label> Gender</label></td>
                              <td><h6 class="lblGender"></h6></td>
                            </tr>
                            <tr>
                              <td><label> Extension Name</label></td>
                              <td><h6 class="lblEName"></h6></td>
                              <td><label> Email Address</label></td>
                              <td><h6 class="lblEmailAddress"></h6></td>
                            </tr>
                            <tr>
                              <td><label> Contact Number</label></td>
                              <td><h6 class="lblContactNumber"></h6></td>
                              <td><label> Date of Birth</label></td>
                              <td><h6 class="lblDOB"></h6></td>
                            </tr>
                          </tbody>
                        </table>
                        <div class="divBorrowerBtn">
                          
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="SI" class="">
                  <div class="box-header with-border">
                    <h3 class="box-title">Source of Other Income</h3> <button type="button" class="btn btn-primary pull-right" id="btnMonthlyIncome">Add Row</button>
                  </div>
                  <br>
                  <br>
                  <table id="tblMonthlyIncome" style="width: 100%" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                      <th>ITEM NO.</th>
                      <th>SOURCE</th>
                      <th>DETAILS</th>
                      <th>AMOUNT</th>
                      <th>ACTION</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td>Amount</td>
                        <td></td>
                        <td></td>
                        <td><label class="lblTotalIncome">Php 0.00</label></td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div id="ME" class="">
                  <div class="box-header with-border">
                    <h3 class="box-title">Monthly Expense</h3> <button type="button" class="btn btn-primary pull-right" id="btnMonthlyExpenses">Add Row</button>
                  </div>
                  <br>
                  <br>
                  <table id="tblMonthlyExpenses" style="width: 100%" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                      <th>ITEM NO.</th>
                      <th>EXPENSES</th>
                      <th>DETAILS</th>
                      <th>AMOUNT</th>
                      <th>ACTION</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td>Amount</td>
                        <td></td>
                        <td></td>
                        <td><label class="lblTotalExpense">Php 0.00</label></td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div id="MO" class="">
                  <div class="box-header with-border">
                  <h3 class="box-title">Monthly Obligations</h3> <button type="button" class="btn btn-primary pull-right" id="btnMonthlyObligations">Add Row</button>
                  </div>
                  <br>
                  <br>
                  <table id="tblMonthlyObligations" style="width: 100%" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                      <th>ITEM NO.</th>
                      <th>OBLIGATIONS</th>
                      <th>DETAILS</th>
                      <th>AMOUNT</th>
                      <th>ACTION</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                        <td>Amount</td>
                        <td></td>
                        <td></td>
                        <td><label class="lblTotalObligation">Php 0.00</label></td>
                        <td></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                <div id="SU" class="">
                  <h4>Loan Product</h4>
                  <hr>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Loan Type</label>
                      <h6 id="lblLoanType"></h6>
                    </div>
                    <div class="col-md-3">
                      <label>Source</label>
                      <h6 id="lblSource"></h6>
                    </div>
                    <div class="col-md-3">
                      <label>Purpose</label>
                      <h6 id="lblPurpose"></h6>
                    </div>
                    <div class="col-md-3">
                      <label>Loan Release Date</label>
                      <h6 id="lblReleaseDate"></h6>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Disbursed By</label>
                      <h6 id="lblDisbursedType"></h6>
                    </div>
                    <div class="col-md-3">
                      <label>Loan Amount</label>
                      <h6 id="lblPrincipalAmount"></h6>
                    </div>
                    <div class="col-md-3">
                      <label>Term</label>
                      <h6 id="lblTerm"></h6>
                    </div>
                    <div class="col-md-3">
                      <label>Number of Repayments</label>
                      <h6 id="lblRepayments"></h6>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Interest Type</label>
                      <h6 id="lblInterestType"></h6>
                    </div>
                    <div class="col-md-3">
                      <label>Interest Amount</label>
                      <h6 id="lblInterestAmount"></h6>
                    </div>
                    <div class="col-md-3">
                      <label>Interest Frequency</label>
                      <h6 id="lblInterestFrequency"></h6>
                    </div>
                    <div class="col-md-3">
                      <label>Add-On Interest Rate</label>
                      <h6 class="lblTotalInterest">Php 0.00</h6>
                    </div>
                    <div class="col-md-3">
                      <label>Total Interest</label>
                      <h6 class="lblFinalInterest">Php 0.00</h6>
                    </div>     
                    <div class="col-md-3">
                      <label>Total Additional Charges</label>
                      <h6 class="lblTotalAdditionalCharges">Php 0.00</h6>
                    </div>
                    <div class="col-md-3">
                      <label>Total Collections</label>
                      <h6 class="lblTotalCollections">Php 0.00</h6>
                    </div>
                    <div class="col-md-3">
                      <label>Total Cost of Loan</label>
                      <h6 class="lblTotalLoanCost">Php 0.00</h6>
                    </div>
                    <div class="col-md-3">
                      <label>Principal per Collection</label>
                      <h6 class="lblPrincipalPerCollection">Php 0.00</h6>
                    </div>
                    <div class="col-md-3">
                      <label>Interest per Collection</label>
                      <h6 class="lblInterestPerCollection">Php 0.00</h6>
                    </div>
                    <div class="col-md-3">
                      <label>Payable per Collection</label>
                      <h6 class="lblPayablePerCollection">Php 0.00</h6>
                    </div>
                    <div class="col-md-3">
                      <label>Net Loan Amount</label>
                      <h6 class="lblNetLoanAmount">Php 0.00</h6>
                    </div>
                  </div>
                  <h4>Borrower Detail</h4>
                  <hr>
                  <table class="table table-bordereds">
                    <tbody>
                      <tr>
                        <td colspan="4">PERSONAL INFORMATION</td>
                      </tr>
                      <tr>
                        <td><label> Borrower Number</label></td>
                        <td><h6 class="lblBorrowerNumber"></h6></td>
                        <td width="200px"><label> Date Added</label></td>
                        <td><h6 class="lblDateAdded"></h6></td>
                      </tr>
                      <tr>
                        <td><label> Status</label></td>
                        <td><h6 class="lblBorrowerStatus"></h6></td>
                        <td><label> Added By</label></td>
                        <td><h6 class="lblAddedBy"></h6></td>
                      </tr>
                      <tr>
                        <td width="200px"><label> First Name</label></td>
                        <td><h6 class="lblFName"></h6></td>
                        <td><label> No. of Dependents</label></td>
                        <td><h6 class="lblDependents"></h6></td>
                      </tr>
                      <tr>
                        <td><label> Middle Name</label></td>
                        <td><h6 class="lblMName"></h6></td>
                        <td><label> Nationality</label></td>
                        <td><h6 class="lblNationality"></h6></td>
                      </tr>
                      <tr>
                        <td><label> Last Name</label></td>
                        <td><h6 class="lblLName"></h6></td>
                        <td><label> Gender</label></td>
                        <td><h6 class="lblGender"></h6></td>
                      </tr>
                      <tr>
                        <td><label> Extension Name</label></td>
                        <td><h6 class="lblEName"></h6></td>
                        <td><label> Email Address</label></td>
                        <td><h6 class="lblEmailAddress"></h6></td>
                      </tr>
                      <tr>
                        <td><label> Contact Number</label></td>
                        <td><h6 class="lblContactNumber"></h6></td>
                        <td><label> Date of Birth</label></td>
                        <td><h6 class="lblDOB"></h6></td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="divBorrowerBtn">
                    
                  </div>
                  <h4>Monthly Household Net Income</h4>
                  <hr>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Borrower Monthly Salary</label>
                      <input type="number" min="0" value="0" oninput="monthlySalaries()" id="txtBorrowerMonthlySalary" class="form-control" name="BorrowerMonthlySalary">
                    </div>
                    <div class="col-md-3">
                      <label>Spouse's Monthly Salary</label>
                      <input type="number" min="0" value="0" oninput="monthlySalaries()" id="txtSpouseMonthlySalary" class="form-control" name="SpouseMonthlySalary">
                    </div>
                    <div class="col-md-3">
                      <label>Total Monthly Household Income</label>
                      <h6 id="lblHouseholdMonthlyIncome">Php 0.00</h6>
                    </div>
                    <div class="col-md-3">
                      <label>Total Sources of Other Income</label>
                      <h6 class="lblTotalIncome">Php 0.00</h6>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-3">
                      <label>Total Monthly Expenses</label>
                      <h6 class="lblTotalExpense">Php 0.00</h6>
                    </div>
                    <div class="col-md-3">
                      <label>Total Monthly Obligations</label>
                      <h6 class="lblTotalObligation">Php 0.00</h6>
                    </div>
                    <div class="col-md-3">
                      <label>Net Monthly Household Income</label>
                      <h6 id="lblNetIncome">Php 0.00</h6>
                    </div>
                    <div class="col-md-3">
                      <label>Risk Assessment Level</label>
                      <h6 id="lblRiskAssessment"></h6>
                    </div>
                  </div>
                  <h4>Requirements <small><a href=""> Add/Edit Requirements</a></small> </h4>
                  <hr>
                  <label>Select Requirement Type<span class="text-red">*</span></label><br>
                  <select class="form-control" style="width: 100%" onchange="requirementType(this.value)" required="" name="RequirementType" id="selectRequirementType">
                    <?php
                      echo $requirementType;
                    ?>
                  </select>
                  <br>
                  <div id="divRequirementList" style="display: none">
                    <label>Select Requirements to be Submitted:<span class="text-red">*</span></label><br>
                    <table id="dtblRequirement" class="table table-bordered table-hover" style="width: 100%">
                      <thead>
                      <tr>
                        <th width="15px">Select</th>
                        <th>Name</th>
                        <th>Description</th>
                      </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                  <h4>Workflow</h4>
                  <hr>
                  <div class="row">
                    <div class="col-md-12">
                      <label>Loan Status</label>
                      <select class="form-control" onchange="onChangeLoanStatus(this.value, this.name)" name="LoanStatusId" id="selectLoanStatus">
                        <?php
                          echo $loanStatus; 
                        ?>
                      </select>
                      <div id="divLoanApproval" style="display: none">
                        <br>
                        <label>Approval Type</label>
                        <select class="form-control" name="ApprovalType">
                          <option>Heirarchical</option>
                          <option>Simultaenous</option>
                        </select>
                        <label>Select employee for approval</label><br>
                        <select class="form-control select2" style="width: 100%" name="Approvers[]" multiple="" id="selectApprovers">
                        </select>
                      </div>
                    </div>
                  </div>
                  <h4>Notes</h4>
                  <hr>
                  <div class="row">
                    <div class="col-md-12">
                      <textarea class="form-control" name="Notes" placeholder="Notes"></textarea>
                    </div>
                  </div>
                </div>
                <button class="btn btn-secondary sw-btn-prev disabled" style="margin-left: 85%" type="button">Back</button>
                <button class="btn btn-secondary sw-btn-next"  style="display:" type="button" id="BtnNext">Next</button>
                <button class="btn btn-primary" type="submit" id="BtnSubmitRequest" style="display:none;">Submit</button>
              </div>
            </div>
          </div>
        </form>
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
  // SUMMARY FOR MONTHLY HOUSEHOLD SALARY
    var MonthlyHouseholdIncome = 0
    var NetMonthlyIncome = 0;
    function monthlySalaries()
    {
      MonthlyHouseholdIncome = parseInt($('#txtBorrowerMonthlySalary').val()) + parseInt($('#txtSpouseMonthlySalary').val())
      $('#lblHouseholdMonthlyIncome').html('Php ' + parseInt(MonthlyHouseholdIncome).toLocaleString('en-US', {minimumFractionDigits: 2}));

      NetMonthlyIncome = (parseInt(MonthlyHouseholdIncome) + parseInt(TotalIncome)) - (parseInt(TotalExpense) + parseInt(TotalObligation));
      $('#lblNetIncome').html('Php ' + parseInt(NetMonthlyIncome).toLocaleString('en-US', {minimumFractionDigits: 2}));
      computeRiskAssessment();
    }

    ageRisk = 0;
    incomeRisk = 0;
    tenureRisk = 0;
    riskAssessment = 0;
    function computeRiskAssessment()
    {
      // age
        if(varBorrowerAge >= 20 && varBorrowerAge <= 40)
        {
          ageRisk = 10;
        }
        else if(varBorrowerAge >= 41 && varBorrowerAge <= 60)
        {
          ageRisk = 50;
        }
        else if(varBorrowerAge > 61)
        {
          ageRisk = 100;
        }
      // income
        loanAmount = $('#txtPrincipalAmount').val()
        if(loanAmount < NetMonthlyIncome)
        {
          incomeRisk = 0;
        }
        else if(loanAmount == NetMonthlyIncome)
        {
          incomeRisk = 25
        }
        else if(loanAmount > NetMonthlyIncome)
        {
          incomeRisk = 50
        }
        else if(NetMonthlyIncome == 0)
        {
          incomeRisk = 100
        }
      // tenure 
        if(varTenure <= 1)
        {
          tenureRisk = 100
        }
        else if(varTenure >= 2 && varTenure <= 3)
        {
          tenureRisk = 50
        }
        else if(varTenure >= 4 && varTenure <= 5)
        {
          tenureRisk = 25
        }
        else if(varTenure >= 6)
        {
          tenureRisk = 0
        }
      // compute risk
      console.log('NETMONTHLYINCOME: '+NetMonthlyIncome)
      console.log('TENURE: '+varTenure)
        riskAssessment = (incomeRisk + ageRisk + tenureRisk) / 3
        if(Math.ceil(riskAssessment) <= 25)
        {
          riskLevel = 'Low Risk'
        }
        else if(Math.ceil(riskAssessment) >= 26 && Math.ceil(riskAssessment) <= 55)
        {
          riskLevel = 'Medium Risk'
        }
        else if(Math.ceil(riskAssessment) >= 56 && Math.ceil(riskAssessment) <= 100)
        {
          riskLevel = 'High Risk'
        }
        $('#lblRiskAssessment').html(parseInt(Math.ceil(riskAssessment)).toLocaleString('en-US', {minimumFractionDigits: 2}) + '% - ' + riskLevel);
    }
  // SUMMARY 
    function loanSummary()
    {
      $('#lblLoanType').html($('#selectLoanType option:selected').data('city'));
      $('#lblSource').html($('#selectSource option:selected').val());
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

    function chkRequirements(rowId)
    {
      if($('#selectCheckReq'+rowId+'').is(":checked") == true)
      {
        $('#isRequirementSelected'+rowId+'').val(1);
      }
      else
      {
        $('#isRequirementSelected'+rowId+'').val(0);
      }
    }

    function onchangeIsPenalized()
    {
      if($('#chkPenalty').is(":checked") == true)
      {
        $('#divPenalty').show();
      }
      else
      {
        $('#divPenalty').hide();
      }
    }

    function onchangePenaltyType()
    {
      if($('#selectPenaltyType').val() == 'Flat Rate')
      {
        $('#inputLblPenaltyType').html('Amount');
      }
      else
      {
        $('#inputLblPenaltyType').html('Percentage');
      }
    }
  // BORROWERS
    var varBorrowerId = 0;
    var varBorrowerAge = 0;
    var varTenure;
    function displayBorrowerDetails(value)
    {
      varBorrowerId = value;
      $('#divBorrowerDetails').slideDown();
      $.ajax({
        url: "<?php echo base_url();?>" + "/borrower_controller/getBorrowerDetails",
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
          varBorrowerAge = data['Age'];
          $('.lblBorrowerNumber').html(data['BorrowerNumber']);
          $('.lblDateAdded').html(data['DateAdded']);
          $('.lblAddedBy').html(data['AddedBy']);
          $('.lblFName').html(data['FirstName']);
          $('.lblDependents').html(data['Dependents']);
          $('.lblMName').html(data['MiddleName']);
          $('.lblNationality').html(data['Nationality']);
          $('.lblLName').html(data['LastName']);
          $('.lblGender').html(data['Sex']);
          $('.lblEName').html(data['ExtName']);
          $('.lblEmailAddress').html(data['EmailAddress']);
          $('.lblContactNumber').html(data['ContactNumber']);
          $('.lblDOB').html(data['DateOfBirth']);
          $('.lblBorrowerStatus').html(data['StatusDescription']);
          $('.divBorrowerBtn').html('<a target="_blank" href="<?php echo base_url();?>home/BorrowerDetails/'+data['BorrowerId']+'">View Borrower Details</a>');

          $.ajax({
            url: "<?php echo base_url();?>" + "/loanapplication_controller/getTenure",
            type: "POST",
            async: false,
            data: {
              Id : varBorrowerId
            },
            dataType: "JSON",
            beforeSend: function(){
                $('.loading').show();
            },
            success: function(data)
            {
              varTenure = data['AvgYears'];
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
  // LOAN COMPUTATION
    var varPrincipalAmount, varTermType, varTermNo, varRepaymentType, varRepaymentNo;
    function getTotalInterest()
    {
      computeRiskAssessment();
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
      computeRiskAssessment();
      var repaymentType = $('#selectRepaymentType').val();
      var termType = $('#selectTermType').val();
      var loanRepaymentNo = $('#txtRepayments').val();
      var loanDurationNo = $('#txtTermNo').val();

      if (repaymentType != null)
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
          $.ajax({
            url: "<?php echo base_url();?>" + "/loanapplication_controller/getRepaymentCount",
            type: "POST",
            async: false,
            data: {
              Id : repaymentType
            },
            dataType: "JSON",
            beforeSend: function(){
                $('.loading').show();
            },
            success: function(data)
            {
              $('#txtRepayments').val($('#txtTermNo').val() * data['RepaymentNo'])
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
      computeRiskAssessment();
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
      computeRiskAssessment();
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
      computeRiskAssessment();
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
          text: 'Loan Amount cannot be blank!',
          type: 'warning',
          buttonsStyling: false,
          confirmButtonClass: 'btn btn-primary'
        });
      }
      else
      {
        $('#txtIsCharged').val(1);
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
        $('#isSelected'+rowId+'').val(1)
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
        $('#isSelected'+rowId+'').val(0)
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
        $('#txtIsCharged').val(0);
    }

  $(function () {
    $("#selectApprovers").on("select2:select", function (evt) {
      var element = evt.params.data.element;
      var $element = $(element);
      $element.detach();
      $(this).append($element);
      $(this).trigger("change");
    });
    $('.select2').select2();

    var MonthlyIncomeCount = 0;
      $('#btnMonthlyIncome').click(function(){
        MonthlyIncomeCount = MonthlyIncomeCount + 1;
        output = '<tr id="rowIncomeId' + MonthlyIncomeCount + '" value="' + MonthlyIncomeCount + '">'
        output += '<td id="rowNumber' + MonthlyIncomeCount + '">' + MonthlyIncomeCount + '</td>'
        output += '<td><input type="text" class="form-control incomeSource" name="MISourceIncome[]"><input type="hidden" required="" class="form-control" name="countMonthlyIncome[]" value="' + MonthlyIncomeCount + '"></td>'
        output += '<td><input type="text" class="form-control" name="MIDetails[]"></td>'
        output += '<td><input required="" type="number" class="form-control incomeAmount" min="0"  placeholder="0.00" oninput="changeAmount(this.value, 1, '+MonthlyIncomeCount+')" name="MIAmount[]"></td>'
        output += '<td><a id="' + MonthlyIncomeCount + '" class="btn btnRemoveIncome btn-sm btn-danger" title="Remove"><span class="fa fa-minus"></span></a> </td>'
        output += '</tr>'
        $('#tblMonthlyIncome').append(output);
      });

      $(document).on('click', '.btnRemoveIncome', function(){
        var row_id = $(this).attr("id");

        MonthlyIncomeCount = MonthlyIncomeCount - 1;
        $('#rowIncomeId'+ row_id +'').remove();

        TotalIncome = 0;
        var items = document.getElementsByClassName('incomeAmount');
        for (var i = 0; i < items.length; i++)
        {
          TotalIncome = parseInt(TotalIncome) + parseInt(items[i].value);
        }
        $('.lblTotalIncome').html('Php ' + parseInt(TotalIncome).toLocaleString('en-US', {minimumFractionDigits: 2}));
      });

    var MonthlyExpensesCount = 0;
      $('#btnMonthlyExpenses').click(function(){
        MonthlyExpensesCount = MonthlyExpensesCount + 1;
        output = '<tr id="rowExpenseId' + MonthlyExpensesCount + '" value="' + MonthlyExpensesCount + '">'
        output += '<td id="rowNumber' + MonthlyExpensesCount + '">' + MonthlyExpensesCount + '</td>'
        output += '<td><input type="text" class="form-control expenseSource" name="SourceExpenses[]"><input type="hidden" required="" class="form-control" name="countRow[]" value="' + MonthlyExpensesCount + '"></td>'
        output += '<td><input type="text" class="form-control" name="Details[]"></td>'
        output += '<td><input required="" type="number" class="form-control expenseAmount" name="Amount[]" placeholder="0.00" oninput="changeAmount(this.value, 2, ' + MonthlyExpensesCount + ')"></td>'
        output += '<td><a id="' + MonthlyExpensesCount + '" class="btn btnRemoveExpense btn-sm btn-danger" title="Remove"><span class="fa fa-minus"></span></a> </td>'
        output += '</tr>'
        $('#tblMonthlyExpenses').append(output);
      });

      $(document).on('click', '.btnRemoveExpense', function(){
        var row_id = $(this).attr("id");

        MonthlyExpensesCount = MonthlyExpensesCount - 1;
        $('#rowExpenseId'+ row_id +'').remove();

        TotalExpense = 0;
        var items = document.getElementsByClassName('expenseAmount');
        for (var i = 0; i < items.length; i++)
        {
          TotalExpense = parseInt(TotalExpense) + parseInt(items[i].value);
        }
        $('.lblTotalExpense').html('Php ' + parseInt(TotalExpense).toLocaleString('en-US', {minimumFractionDigits: 2}));
      });

    var MonthlyObligationsCount = 0;
      $('#btnMonthlyObligations').click(function(){
        MonthlyObligationsCount = MonthlyObligationsCount + 1;
        output = '<tr id="rowObligationId' + MonthlyObligationsCount + '" value="' + MonthlyObligationsCount + '">'
        output += '<td id="rowNumber' + MonthlyObligationsCount + '">' + MonthlyObligationsCount + '</td>'
        output += '<td><input type="text" class="form-control obligationSource" name="SourceObligations[]"><input type="hidden" required="" class="form-control" name="countObligationRow[]" value="' + MonthlyObligationsCount + '"></td>'
        output += '<td><input type="text" class="form-control" name="ObligationDetails[]"></td>'
        output += '<td><input required="" type="text" class="form-control obligationAmount" name="ObligationAmount[]" placeholder="0.00" oninput="changeAmount(this.value, 3, ' + MonthlyObligationsCount + ')"></td>'
        output += '<td><a id="' + MonthlyObligationsCount + '" class="btn btnRemoveObligation btn-sm btn-danger" title="Remove"><span class="fa fa-minus"></span></a> </td>'
        output += '</tr>'
        $('#tblMonthlyObligations').append(output);
      });

      $(document).on('click', '.btnRemoveObligation', function(){
        var row_id = $(this).attr("id");

        MonthlyObligationsCount = MonthlyObligationsCount - 1;
        $('#rowObligationId'+ row_id +'').remove();

        TotalObligation = 0;
        var items = document.getElementsByClassName('obligationAmount');
        for (var i = 0; i < items.length; i++)
        {
          TotalObligation = parseInt(TotalObligation) + parseInt(items[i].value);
        }
        $('.lblTotalObligation').html('Php ' + parseInt(TotalObligation).toLocaleString('en-US', {minimumFractionDigits: 2}));
      });

    $("#frmSubmitForm").on('submit', function (e) {
      e.preventDefault(); 
      swal({
        title: 'Confirm',
        text: 'Are you sure you want to submit application?',
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

    $('#btnBorrowerDetail').click(function() {
      window.location.href = 'BorrowerDetails/'+ $('#SelectBorrower').val();
      return false;
    });

    $('#selectBorrower').select2({
      placeholder: 'Type an borrower name or borrower number to select.',
      dropdownCssClass : 'bigdrop',
      ajax: {
        url: '<?php echo base_url()?>admin_controller/getBorrowers?>',
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

    $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
      if(stepPosition === 'final')
      {
        var y = document.getElementById("BtnSubmitRequest");
        y.style.display = "-webkit-inline-box";
        // $("#BtnProcessRequest").show();
        $('.sw-btn-next').hide();
      }
      else
      {
        var y = document.getElementById("BtnSubmitRequest");
        y.style.display = "none";

        // $("#BtnProcessRequest").hide();
        $('.sw-btn-next').show();
      }
    });

    $('#loanReleaseDate').daterangepicker({
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

    $('#smartwizard').smartWizard({
      theme: 'dots',
        selected: 0,  // Initial selected step, 0 = first step 
        keyNavigation:true, // Enable/Disable keyboard navigation(left and right keys are used if enabled)
        autoAdjustHeight:true, // Automatically adjust content height
        justified: true, // Nav menu justification. true/false
        cycleSteps: false, // Allows to cycle the navigation of steps
        backButtonSupport: true, // Enable the back button support
        useURLhash: true, // Enable selection of the step based on url hash
        lang: {  // Language variables
            next: 'Next', 
            previous: 'Back'
        },
        showStepURLhash: false,
        toolbarSettings: {
          toolbarPosition: 'bottom', // none, top, bottom, both
          toolbarButtonPosition: 'right', // left, right
          showNextButton: false, // show/hide a Next button
          showPreviousButton: false, // show/hide a Previous button
        //             toolbarExtraButtons: [
            //  $('<button></button>').text('Finish')
            //              .addClass('btn btn-info')
            //              .on('click', function(){ 
            //                alert('OnChangeSC')
            //              }),
            // ]
       //              toolbarExtraButtons: [
          // // $('<button></button>').text('Finish')
          // //           .addClass('btn btn-success')
          // //           .on('click', function(){ 
          // //       alert('Finsih button click');                            
          // //           })
       //                    ]
        }, 
        anchorSettings: {
          anchorClickable: false, // Enable/Disable anchor navigation
          enableAllAnchors: false, // Activates all anchors clickable all times
          markDoneStep: true, // add done css
          enableAnchorOnDoneStep: false, // Enable/Disable the done steps navigation,
          removeDoneStepOnNavigateBack: true
        },            
        contentURL: null, // content url, Enables Ajax content loading. can set as data data-content-url on anchor
        disabledSteps: [],    // Array Steps disabled
        errorSteps: [],    // Highlight step with errors
        transitionEffect: 'fade', // Effect on navigation, none/slide/fade
        transitionSpeed: '400'
    });

    $("#smartwizard").on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {
      if(stepNumber == 0 && stepDirection == 'forward') // loan product
      {
        varIsPenalized = 0;
        IsGo = 0;
        var varChargeNo;
        // for sources
          if($('#selectSource').val() == 'Through Agent')
          {
            if($('#txtAgentName').val() == '')
            {
              IsGo = 0;
            }
            else
            {
              IsGo = 1;
            }
          }
          else
          {
            IsGo = 1;
          }
        // for penalties
          if($('#chkPenalty').is(":checked") == true)
          {
            if($('#selectPenaltyType').val() == '' || $('#txtPenaltyAmount').val() <= 0 || $('#txtPenaltyAmount').val() < 0)
            {
              varIsPenalized = 0;
            }
            else
            {
              varIsPenalized = 1;
            }
          }
          else
          {
            varIsPenalized = 1;
          }
        // for charges
          if($('#txtIsCharged').val() == 1)
          {
            $('input[type="checkbox"]').click(function(){
              if($('.checkCharges:checked').length > 0)
              {
                varChargeNo = 1;
              }
              else
              {
                varChargeNo = 0
              }
            });
          }
          else
          {
            varChargeNo = 1;
          }
        if($('#selectLoanType').val() == '' || IsGo == 0 || $('#selectPurpose').val() == '' || $('#selectDisbursedBy').val() == '' || $('#txtPrincipalAmount').val() == '' || $('#selectTerm').val() == '' || $('#selectTermType').val() == '' || $('#txtRepayments').val() == '' || $('#selectRepaymentType').val() == '' || $('#selectInterestType').val() == '' || $('#txtInterest').val() == '' || $('#selectInterestFrequency').val() == '' || varIsPenalized == 0 || varChargeNo == 0)
        {
          swal({
            title: 'Warning',
            text: 'Please make sure all required fields are filled out.',
            type: 'warning',
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary'
          });
          return false;
        }
        else
        {
          return true;
        }
      }
      else if(stepNumber == 1 && stepDirection == 'forward') // borrower
      {
        if($('#selectBorrower').val() == '')
        {
          swal({
            title: 'Warning',
            text: 'Please make sure all required fields are filled out.',
            type: 'warning',
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary'
          });
          return false;
        }
        else
        {
          return true;
        }
      }
      if(stepNumber == 2 && stepDirection == 'forward') // income
      {
        varContinue = 0;
        varTotalSource = 0;

        varIncomeSource = 0;
        varIncomeAmount = 0;
        $('.incomeSource').each(function(){
          varTotalSource = varTotalSource + 1;
          if($(this).val() != '')
          {
            varIncomeSource = varIncomeSource + 1;
          }
        });
        $('.incomeAmount').each(function(){
          if($(this).val() != '' || $(this).val() > 0)
          {
            varIncomeAmount = varIncomeAmount + 1;
          }
        });

        if(varTotalSource >= 1)
        {
          if(varIncomeSource == varTotalSource && varIncomeAmount == varTotalSource)
          {
            varContinue = 1;
            return true;
          }
          else 
          {
            swal({
              title: 'Warning',
              text: 'Please make sure all required fields are filled out.',
              type: 'warning',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });
            return false;
          }
        }
        else
        {
          varContinue = 1;
          return true;
        }
      }
      else if(stepNumber == 3 && stepDirection == 'forward') // expense
      {
        varContinue = 0;
        varTotalSource = 0;

        varIncomeSource = 0;
        varIncomeAmount = 0;
        $('.expenseSource').each(function(){
          varTotalSource = varTotalSource + 1;
          if($(this).val() != '')
          {
            varIncomeSource = varIncomeSource + 1;
          }
        });
        $('.expenseAmount').each(function(){
          if($(this).val() != '' || $(this).val() > 0)
          {
            varIncomeAmount = varIncomeAmount + 1;
          }
        });

        console.log(varIncomeAmount)

        if(varTotalSource >= 1)
        {
          if(varIncomeSource == varTotalSource && varIncomeAmount == varTotalSource)
          {
            varContinue = 1;
            return true;
          }
          else 
          {
            swal({
              title: 'Warning',
              text: 'Please make sure all required fields are filled out.',
              type: 'warning',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });
            return false;
          }
        }
        else
        {
          varContinue = 1;
          return true;
        }
      }
      else if(stepNumber == 4 && stepDirection == 'forward') // obligation
      {
        varContinue = 0;
        varTotalSource = 0;

        varIncomeSource = 0;
        varIncomeAmount = 0;
        $('.obligationSource').each(function(){
          varTotalSource = varTotalSource + 1;
          if($(this).val() != '')
          {
            varIncomeSource = varIncomeSource + 1;
          }
        });
        $('.obligationAmount').each(function(){
          if($(this).val() != '' || $(this).val() > 0)
          {
            varIncomeAmount = varIncomeAmount + 1;
          }
        });

        if(varTotalSource >= 1)
        {
          if(varIncomeSource == varTotalSource && varIncomeAmount == varTotalSource)
          {
            varContinue = 1;
            return true;
          }
          else 
          {
            swal({
              title: 'Warning',
              text: 'Please make sure all required fields are filled out.',
              type: 'warning',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });
            return false;
          }
        }
        else
        {
          varContinue = 1;
          return true;
        }
      }
    });

// FOR SPECIFIC BORROWER LOAN APPLICATION
  if('<?php print_r($BorrowerId) ?>' != '')
  {
    $('#divSelectBorrower').hide();
    $('#selectBorrowerNumber').val('<?php print_r($BorrowerId) ?>').change();
  }
  else
  {
    $('#divSelectBorrower').show();
    $('#selectBorrowerNumber').val(0);
  }

  })

</script>

