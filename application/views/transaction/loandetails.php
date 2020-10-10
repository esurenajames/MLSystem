
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Loan Application Details for </label> <?php print_r($detail['TransactionNumber']) ?>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#" class="active"><i class="fa fa-dashboard"></i>Loans</a></li>
      <li><a href="#"></i>Loan Application</a></li>
      <li><a href="#"></i>Details</a></li>
    </h1>
    </ol>
  </section>

  <div class="modal fade" id="modalUpdate">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalApprovalUpdateTitle"></h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/loanapproval/<?php print_r($detail['ApplicationId']) ?>" method="post">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <label>Remarks</label>
                <textarea class="form-control" name="Description"></textarea>
                <input type="hidden" name="ApprovalType" id="txtApprovalType">
                <input type="hidden" name="ChargeId" id="txtChargeId">

              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label>Upload Attachment</label>
                  <input type="file" name="Attachment[]" id="Attachment" accept=".jpeg, .jpg, .png">
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
    </div>
  </div>

  <div class="modal fade" id="modalPaymentDetails">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalApprovalUpdateTitle"></h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/loanapproval/<?php print_r($detail['ApplicationId']) ?>" method="post">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-3">
                <label>Term:</label><br> <?php print_r($detail['TermNo']) ?> / <?php print_r($detail['TermType']) ?><br>
              </div>
              <div class="col-md-3">
                <label>Repayment:</label><br> <?php print_r($detail['RepaymentNo']) ?> / <?php print_r($repayment['Name']) ?><br>
              </div>
              <div class="col-md-3">
                <label>Principal Per Collection:</label><br> Php <?php print_r( number_format($paymentDues['PrincipalPerCollection'], 2)) ?><br>
              </div>
              <div class="col-md-3">
                <label>Interest Per Collection:</label><br> Php <?php print_r( number_format($paymentDues['InterestPerCollection'], 2)) ?><br>
              </div>              
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Payment for:</label>
                  <h6 id="lblPaymentForDate"></h6>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Collection Date:</label>
                  <h6 id="lblCollectionDate"></h6>
                </div>
              </div>
              <div class="col-md-6">
                <label>Amount Paid:</label>
                <h6 id="lblAmountPaid"></h6>
              </div>
              <div class="col-md-6">
                <label>Change:</label><br>
                <h6 id="lblDisplayChange"></h6>
              </div>
              <div class="col-md-6">
                <label>Change sent through:</label>
                <h6 id="lblChangedThroughId"></h6>
              </div>
              <div class="col-md-6">
                <label>Payment Method:</label>
                <h6 id="lblPaymentMethod"></h6>
              </div>
              <div class="col-md-6">
                <label>Bank</label>
                <h6 id="lblBankMethod"></h6>
              </div>
              <div class="col-md-12">
                <label>Remarks</label>
                <h6 id="lblRemarks"></h6>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalRepayment">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Add Collection</h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/addRepayment/<?php print_r($detail['ApplicationId']) ?>" method="post" id="frmSubmitRepayment">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-3">
                <label>Term:</label><br> <?php print_r($detail['TermNo']) ?> / <?php print_r($detail['TermType']) ?><br>
              </div>
              <div class="col-md-3">
                <label>Repayment:</label><br> <?php print_r($detail['RepaymentNo']) ?> / <?php print_r($repayment['Name']) ?><br>
              </div>
              <div class="col-md-3">
                <label>Principal Per Collection:</label><br> Php <?php print_r( number_format($paymentDues['PrincipalPerCollection'], 2)) ?><br>
              </div>
              <div class="col-md-3">
                <label>Interest Per Collection:</label><br> Php <?php print_r( number_format($paymentDues['InterestPerCollection'], 2)) ?><br>
              </div>              
            </div>
            <br>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Payment for <span class="text-red">*</span></label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" placeholder="Date Collected" class="form-control" onchange="onchangePenaltyType()" name="datePayment" required="" id="datePayment">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Collection Date <span class="text-red">*</span></label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" placeholder="Date Collected" class="form-control" onchange="onchangePenaltyType()" name="dateCollected" required="" id="dateCollected">
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <input id='txtIsPenalized' name="IsPenalized" type='hidden' onclick="onchangePenaltyType()">
                <input id='txtTotalPenalty' name="TotalPenalty" type='hidden' onclick="onchangePenaltyType()">
              </div>
              <div class="col-md-12">
                <label><input id='chkPayment1' name="chkPayment[]" value="PC" type='checkbox' onclick="onchangePrincipalPayment()" checked=""> Principal Collection</label> 
                <label><input id='chkPayment2' name="chkPayment[]" value="IC" type='checkbox' onclick="onchangePrincipalInterestPayment()" checked=""> Interest</label><br>
                <label>Total Amount Due:</label><br>
                <h6 id="lblTotalAmountDue"></h6>
                <input type="hidden" id="txtAmountDue" value="<?php print_r(round($paymentDues['InterestPerCollection'], 2) + round($paymentDues['PrincipalPerCollection'], 2)) ?>" name="AmountDue"> 
                <input type="hidden" id="txtTotalDue" value="" name="totalDue">
                <input type="hidden" id="txtInterestAmountCollected" name="InterestAmountCollected" value="<?php print_r(round($paymentDues['InterestPerCollection'], 2)) ?>">
                <input type="hidden" id="txtPrincipalAmountCollected" name="PrincipalAmountCollected" value="<?php print_r(round($paymentDues['PrincipalPerCollection'], 2)) ?>">
              </div>
              <div class="col-md-12">
                <label>Amount Paid<span class="text-red">*</span></label>
                <input type="number" value="0" required="" step="0.25" min="0" oninput="computePayment()" class="form-control" name="Amount" id="txtAmountPaid">
              </div>
              <div class="col-md-6">
                <label>Change:</label><br>
                <h6 id="lblChange">Php 0.00</h6>
                <input type="hidden" id="txtChangeAmount" name="ChangeAmount">
              </div>
              <div class="col-md-6">
                <label>Change sent through: <span class="text-red">*</span></label>
                <select class="form-control" required="" name="ChangeMethod">
                  <?php
                    echo $selectChanges;
                  ?>
                </select>
              </div>
              <div class="col-md-6">
                <label>Payment Method <span class="text-red">*</span></label>
                <select class="form-control" required="" name="PaymentMethod">
                  <?php
                    echo $disbursements;
                  ?>
                </select>
              </div>
              <div class="col-md-6">
                <label>Bank <span class="text-red">*</span></label>
                <select class="form-control" required="" name="BankId">
                  <?php
                    echo $bank;
                  ?>
                </select>
              </div>
              <div class="col-md-12">
                <label>Remarks</label>
                <textarea class="form-control" name="Remarks"></textarea>
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

  <div class="modal fade" id="modalPenalty">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Add Penalty</h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/addRepayment/<?php print_r($detail['ApplicationId']) ?>/1" method="post" id="frmSubmitPenalty">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-3">
                <label>Term:</label><br> <?php print_r($detail['TermNo']) ?> / <?php print_r($detail['TermType']) ?><br>
              </div>
              <div class="col-md-3">
                <label>Repayment:</label><br> <?php print_r($detail['RepaymentNo']) ?> / <?php print_r($repayment['Name']) ?><br>
              </div>
              <div class="col-md-3">
                <label>Principal Per Collection:</label><br> Php <?php print_r( number_format($paymentDues['PrincipalPerCollection'])) ?><br>
              </div>
              <div class="col-md-3">
                <label>Interest Per Collection:</label><br> Php <?php print_r( number_format($paymentDues['InterestPerCollection'])) ?><br>
              </div>              
            </div>
            <br>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Payment for <span class="text-red">*</span></label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" placeholder="Date Collected" class="form-control" onchange="onchangePenaltyType2()" name="datePayment" required="" id="datePayment2">
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Collection Date <span class="text-red">*</span></label>
                  <div class="input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" placeholder="Date Collected" class="form-control" onchange="onchangePenaltyType2()" name="dateCollected" required="" id="dateCollected2">
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <label>Penalty Type</label>
                <select class="form-control" id="selectPenaltyType2" onchange="onchangePenaltyType2()" name="PenaltyType">
                  <option>Flat Rate</option>
                  <option>Percentage</option>
                </select>
              </div>
              <div class="col-md-3">
                <label id="inputLblPenaltyType2">Amount</label>
                <input type="number" min="0" class="form-control" oninput="onchangePenaltyType2()" id="txtPenaltyAmount2" name="PenaltyAmount" value="">
              </div>
              <div class="col-md-3">
                <label>Grace Period</label>
                <input type="number" min="0" class="form-control" oninput="onchangePenaltyType2()" id="txtGracePeriod2" name="GracePeriod" value="0">
              </div>
              <div class="col-md-3">
                <label>Total Penalty</label>
                <h6 id="lblTotalPenalty2"></h6>
                <input id='txtTotalPenalty2' name="TotalPenalty" type='hidden' onclick="onchangePenaltyType()">
              </div>
              <div class="col-md-12">
                <label>Amount Paid<span class="text-red">*</span></label>
                <input type="number" value="0" required="" min="0" maxlength="100000" oninput="computePayment2()" class="form-control" name="Amount" id="txtAmountPaid2"> <input type="hidden" id="txtTotalDue2" value="" name="totalDue">
              </div>
              <div class="col-md-6">
                <label>Change:</label><br>
                <h6 id="lblChange2">Php 0.00</h6>
                <input type="hidden" id="txtChangeAmount2" name="ChangeAmount">
              </div>
              <div class="col-md-6">
                <label>Change sent through: <span class="text-red">*</span></label>
                <select class="form-control" required="" name="ChangeMethod">
                  <?php
                    echo $selectChanges;
                  ?>
                </select>
              </div>
              <div class="col-md-6">
                <label>Payment Method <span class="text-red">*</span></label>
                <select class="form-control" required="" name="PaymentMethod">
                  <?php
                    echo $disbursements;
                  ?>
                </select>
              </div>
              <div class="col-md-6">
                <label>Bank <span class="text-red">*</span></label>
                <select class="form-control" required="" name="BankId">
                  <?php
                    echo $bank;
                  ?>
                </select>
              </div>
              <div class="col-md-12">
                <label>Remarks</label>
                <textarea class="form-control" name="Remarks"></textarea>
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

  <div class="modal fade" id="modalUpload">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Upload Requirement</h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/uploadRequirements/<?php print_r($detail['ApplicationId']) ?>" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <label>Document Attachment</label>
                <input type="file" name="RequirementFiles[]" multiple="" accept=".jpeg, .jpg, .png">
                <input type="hidden" name="ApplicationRequirementId" id="txtApplicationRequirementId">
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

  <div class="modal fade" id="modalRestructure">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Re-Structure Loan Application</h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/restructureLoan/<?php print_r($detail['ApplicationId']) ?>" method="post" enctype="multipart/form-data" id="frmRestructure">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Loan Amount<span class="text-red">*</span></label><br>
                  <input type="number" class="form-control" placeholder="Loan Amount" value="<?php print_r($detail['RawPrincipalAmount']) ?>" oninput="getTotalInterest();" id="txtPrincipalAmount" name="PrincipalAmount">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Term Type<span class="text-red">*</span></label><br>
                  <select class="form-control" style="width: 100%" required="" onchange="getTotalInterest(); getRepaymentDuration();" name="TermType" id="selectTermType">
                    <option value="" disabled="">Select Term Type</option>
                    <option <?php if($detail['TermType'] == 'Days') echo "selected"; else { echo "";} ?>>Days</option>
                    <option <?php if($detail['TermType'] == 'Weeks') echo "selected"; else { echo "";} ?>>Weeks</option>
                    <option <?php if($detail['TermType'] == 'Months') echo "selected"; else { echo "";} ?>>Months</option>
                    <option <?php if($detail['TermType'] == 'Years') echo "selected"; else { echo "";} ?>>Years</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Term<span class="text-red">*</span></label><br>
                  <input type="number" class="form-control" oninput="getRepaymentDuration(); getTotalInterest(); getPrincipalCollection(); getTotalCollection();" name="TermNumber" id="txtTermNo" value="<?php print_r($detail["TermNo"]) ?>">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Repayment Cycle<span class="text-red">*</span></label><br>
                  <select class="form-control" style="width: 100%" required="" onchange="getTotalInterest(); getRepaymentDuration(); getPrincipalCollection(); getTotalCollection();" name="RepaymentCycle" id="selectRepaymentType">
                    <?php
                      foreach ($repaymentCycle as $value) 
                      {
                        if($detail['RepaymentId'] == $value['RepaymentId'])
                        {
                          $selected = 'selected';
                        }
                        else
                        {
                          $selected = '';                                
                        }
                        echo "<option ".$selected." value='".$value['RepaymentId']."'>".$value['Name']."</option>";
                      }
                    ?>
                  </select>
                  <a href=""> Add/Edit Repayment Cycle</a>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Number of Repayments<span class="text-red">*</span></label><br>
                  <input type="number" min="0" class="form-control" onchange="getTotalInterest(); getPrincipalCollection(); getTotalCollection();" name="RepaymentsNumber" required="" value="<?php print_r($detail["RepaymentNo"]) ?>"  id="txtRepayments">
                </div>
              </div>
            </div>
            <h4>Interest</h4>
            <hr>
            <div class="row">
              <div class="col-md-2">
                <label>Interest Type</label>
                <select class="form-control" id="selectInterestType" name="interestType" onchange="getTotalInterest();">
                  <option <?php if($detail['InterestType'] == 'Flat Rate') echo "selected"; else { echo "";} ?>>Flat Rate</option>
                  <option <?php if($detail['InterestType'] == 'Percentage') echo "selected"; else { echo "";} ?>>Percentage</option>
                </select>
              </div>
              <div class="col-md-3">
                <label>Interest Amount</label>
                <input type="number" class="form-control" id="txtInterest" value="<?php print_r($detail["Amount"]) ?>" name="interestAmount" onchange="getTotalInterest();">
              </div>
              <div class="col-md-3">
                <label>Interest Frequency</label>
                <select class="form-control" id="selectInterestFrequency" name="interestFrequency" onchange="getTotalInterest();">
                  <option selected="" disabled="">Select Interest Frequency</option>
                  <option <?php if($detail['Frequency'] == 'Per Day') echo "selected"; else { echo "";} ?>>Per Day</option>
                  <option <?php if($detail['Frequency'] == 'Per Week') echo "selected"; else { echo "";} ?>>Per Week</option>
                  <option <?php if($detail['Frequency'] == 'Per Month') echo "selected"; else { echo "";} ?>>Per Month</option>
                  <option <?php if($detail['Frequency'] == 'Per Year') echo "selected"; else { echo "";} ?>>Per Year</option>
                  <option <?php if($detail['Frequency'] == 'Per Loan') echo "selected"; else { echo "";} ?>>Per Loan</option>
                </select>
              </div>
              <div class="col-md-2">
                <label>Add-On Interest Rate</label>
                <h6 class="lblTotalInterest"><?php print_r($detail["renewAddOnInterest"]) ?></h6>
              </div>
              <div class="col-md-2">
                <label>Total Interest</label>
                <h6 class="lblFinalInterest">
                  <?php
                    $TotalInterest = 0;
                    if($detail['InterestType'] == 'Percentage')
                    {
                      $TotalInterest = ($detail['RawPrincipalAmount'] * ($detail['Amount']/100)) * $detail['TermNo'];
                    }
                    else
                    {
                      $TotalInterest = (($detail['Amount'])) * $detail['TermNo'];
                    }
                    $totalDue = $TotalInterest + $detail['RawPrincipalAmount'];
                    print_r('Php '. number_format($TotalInterest, 2));
                  ?>
                </h6>
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

  <div class="modal fade" id="modalComment">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalComment">Add Comment</h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/AddComment/<?php print_r($detail['ApplicationId']) ?>" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <label>Comment</label>
                <textarea class="form-control" name="Comment"></textarea>
                <input type="hidden" id="txtComment">
                <input type="hidden" name="FormType" id="txtObligationForm" value="1">
                <label>Document Attachment</label>
                <input type="file" name="Attachment[]" multiple="" id="Attachment" accept=".jpeg, .jpg, .png">
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

  <div class="modal fade" id="modalCollateral">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="CollateralTitle"></h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/addCollateral/<?php print_r($detail['ApplicationId']) ?>" id="frmCollateral" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <div id="divCollateralForm">
              <div class="row">
                <div class="col-md-12">
                  <label>Type</label>
                  <select class="form-control" name="CollateralTypeId" id="SelectCollateralTypeId" onchange="collateralTypeChange(this.value)">
                    <?php 
                      foreach ($collateralType as $value) 
                      {
                        echo "<option value='".$value['CollateralTypeId']."'>".$value['Name']."</option>";
                      }
                    ?>
                  </select>
                </div>
                <div class="col-md-12">
                  <label>Product Name</label>
                  <input type="text" name="ProductName" class="form-control" id="txtProductName">
                  <input type="hidden" name="CollateralId" class="form-control" id="txtCollateralId">
                  <input type="hidden" name="modalType" class="form-control" id="txtModalType">
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Register Date <span class="text-red">*</span></label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" placeholder="Register Date" class="form-control" name="dateRegistered" required="" id="dateRegistered">
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <label>Value</label>
                  <input type="number" name="CollateralValue" id="txtCollaretalValue" class="form-control">
                </div>
                <div class="col-md-6">
                  <label>Current Status</label>
                  <select class="form-control" name="CollateralStatusId" id="SelectCollateralStatus">
                    <?php 
                      foreach ($collateralStatus as $value) 
                      {
                        echo "<option value='".$value['CollateralStatusId']."'>".$value['Name']."</option>";
                      }
                    ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Date Acquired <span class="text-red">*</span></label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input type="text" placeholder="Date Acquired" class="form-control" name="dateAcquired" required="" id="dateAcquired">
                    </div>
                  </div>
                </div>
              </div>
              <div id="divAutomobiles">
                <h5>Automobile Details</h5>
                <div class="row">
                  <div class="col-md-4">
                    <label>Registration Number</label>
                    <input type="text" class="form-control" name="RegistrationNo" id="txtRegistrationNo">
                  </div>
                  <div class="col-md-4">
                    <label>Mileage</label>
                    <input type="text" class="form-control" name="Mileage" id="txtMileage">
                  </div>
                  <div class="col-md-4">
                    <label>Engine Number</label>
                    <input type="text" class="form-control" name="EngineNo" id="txtEngineNo">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label>Document Attachment</label>
                  <input type="file" name="Attachment[]" multiple="" accept=".jpeg, .jpg, .png, .pdf">
                </div>
              </div>
            </div>

            <div id="divCollateralDetails" style="display: none">
              <div class="row">
                <div class="col-md-4">
                  <label>Collateral Type</label>
                  <h6 id="lblCollateralType"></h6>
                </div>
                <div class="col-md-4">
                  <label>Product Name</label>
                  <h6 id="lblProductName"></h6>
                </div>
                <div class="col-md-4">
                  <label>Register Date</label>
                  <h6 id="lblDateRegistered"></h6>
                </div>
                <div class="col-md-4">
                  <label>Value</label>
                  <h6 id="lblValue"></h6>
                </div>
                <div class="col-md-4">
                  <label>Current Status</label>
                  <h6 id="lblCurrentStatus"></h6>
                </div>
                <div class="col-md-4">
                  <label>Date Acquired</label>
                  <h6 id="lblDateAcquired"></h6>
                </div>
                <div class="col-md-4">
                  <label>Download Attachments</label>
                  <h6 id="lblDownloadCollateral"></h6>
                </div>
              </div>
            </div>
            <div id="divCollateralAutomobile"  style="display: none">
              <div class="row">
                <div class="col-md-4">
                  <label>Registration Number</label>
                  <h6 id="lblRegNumber"></h6>
                </div>
                <div class="col-md-4">
                  <label>Mileage</label>
                  <h6 id="lblMileage"></h6>
                </div>
                <div class="col-md-4">
                  <label>Engine Number</label>
                  <h6 id="lblEngineNumber"></h6>
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

  <div class="modal fade" id="modalObligation">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalObligation">Add Obligation</h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/AddObligation/<?php print_r($detail['ApplicationId']) ?>" method="post">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <label>Obligation</label>
                <input type="text" class="form-control" name="Obligation" id="txtObligation">
                <input type="hidden" name="FormType" id="txtFormType" value="1">
                <input type="hidden" name="MonthlyObligationId" id="txtMonthlyObligationId">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Detail</label>
                <textarea class="form-control" name="Detail" id="txtObligationDetail"></textarea>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Amount</label>
                <input type="number" class="form-control" name="Amount" id="txtObligationAmount">
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

  <div class="modal fade" id="modalExpense">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalExpense">Add Expense</h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/AddExpense/<?php print_r($detail['ApplicationId']) ?>" method="post">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <label>Expense</label>
                <input type="text" class="form-control" name="Expense" id="txtExpense">
                <input type="hidden" name="FormTypeExpense" id="txtFormTypeExpense" value="1">
                <input type="hidden" name="ExpenseId" id="txtExpenseId">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Detail</label>
                <textarea class="form-control" name="Detail" id="txtExpenseDetail"></textarea>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Amount</label>
                <input type="number" class="form-control" name="Amount" id="txtExpenseAmount">
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

  <div class="modal fade" id="modalRequirement">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalRequirement">Add Requirement</h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/AddRequirement/<?php print_r($detail['ApplicationId']) ?>" method="post">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <label>Requirement</label>
                <select class="form-control " style="width: 100%" required="" name="Requirements" id="selectRequirement">
                <?php
                  echo $requirements;
                ?>
                <input type="hidden" name="FormType" id="txtFormType" value="1">
                <input type="hidden" name="RequirementId" id="RequirementId">
              </select>
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

  <div class="modal fade" id="modalCharge">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Add Charges</h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/AddRequirement/<?php print_r($detail['ApplicationId']) ?>" method="post">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <label>Charge</label>
                <select class="form-control" style="width: 100%" required="" onchange="displayCharge(this.value)" name="ChargeId" id="selectChargeId">
                <?php
                  echo $selectCharges;
                ?>
                <input type="hidden" name="FormType" id="txtChargeFormType" value="1">
                <input type="hidden" name="RequirementId" id="txtChargeId">
              </select>
              </div>
              <div id="divChargeDisplay" style="display: none">
                <br>
                <br>
                <br>
                <br>
                <div class="col-md-4">
                  <label>Charge Type</label>
                  <h6 id="lblChargeType"></labelh6>
                </div>
                <div class="col-md-4">
                  <label>Charge Amount</label>
                  <h6 id="lblChargeAmount"></labelh6>
                </div>
                <div class="col-md-4">
                  <label>Charge Total</label>
                  <h6 id="lblChargeTotal"></labelh6>
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

  <div class="modal fade" id="modalIncome">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="modalIncome">Add Other Source of Income</h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/AddIncome/<?php print_r($detail['ApplicationId']) ?>" method="post">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <label>Source</label>
                <input type="text" class="form-control" name="Source" id="txtIncomeSource">
                <input type="hidden" name="FormType" id="txtFormTypeIncome" value="1">
                <input type="hidden" name="IncomeId" id="txtIncomeId">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Detail</label>
                <textarea class="form-control" name="Detail" id="txtIncomeDetail"></textarea>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Amount</label>
                <input type="number" class="form-control" name="Amount" id="txtIncomeAmount">
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

  <section class="content">
  	<!-- BORROWER DETAILS -->
	    <div class="box">
	      <div class="box-header with-border">
	        <h3 class="box-title">Borrower Detail</h3>
	      </div>
		    <div class="box-body">
		      <div class="row">
		      	<div class="col-md-2">
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
		          <center><a href="">View</a> | <a href="">Edit</a></center>
		      	</div>
		      	<div class="col-md-4">
		      		<label><?php print_r($detail['Name']) ?></label><br>
		      		<label><?php print_r($detail['BorrowerNumber']) ?></label><br>
		      		<label>Date of Birth:</label> <?php print_r($detail['DOB'] . ' ' . $detail['Age'] . 'yrs old') ?> <br>
		      		<label>Created By:</label> <?php print_r($detail['CreatedBy']) ?><br>
		      		<label>Date Created:</label> <?php print_r($detail['DateCreated']) ?><br>
		      	</div>
		      	<div class="col-md-6">
		      		<label>Contact Number: </label> <?php print_r($detail['ContactNumber']) ?><br>
		      		<label>Email: </label> <?php print_r($detail['EmailAddress']) ?><br>
			    		<label>Date Approved:</label> <?php print_r($detail['DateApproved']) ?><br>
			    		<label>Status:</label> <?php print_r($detail['StatusDescription'] . '/' . $detail['ApprovalType']) ?>  <br>
			    			<?php 
				    			foreach ($approvers as $value) 
				    			{
				    				if($value['StatusId'] == 1)
				    				{
				    					echo "<span class='badge bg-green'>".$value['ApproverName']."</span> ";
				    				}
				    				else
				    				{
				    					echo "<span class='badge bg-blue'>".$value['ApproverName']."</span> ";
				    				}
				    			}
			    			?> 
			    		<br>
		      	</div>
		      </div>
		    </div>
	    </div>
  	<!-- LOAN DETAILS -->
		  <div class="box">
		    <div class="box-header with-border">
		      <h3 class="box-title"><label>Loan Application No:</label> <?php print_r($detail['TransactionNumber']) ?></h3>
          <button class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#modalRestructure">Re-Structure</button>
		    </div>
		    <div class="box-body">
			    <div class="row">
			    	<div class="col-md-4">
			    		<label>Loan Type:</label> <?php print_r($detail['LoanType']) ?><br>
			    		<label>Disbursed By:</label> <?php print_r($detail['DisbursedBy']) ?><br>
			    		<label>Term:</label> <?php print_r($detail['TermNo']) ?> / <?php print_r($detail['TermType']) ?><br>
			    		<label>Repayment:</label> <?php print_r($detail['RepaymentNo']) ?> / <?php print_r($repayment['Name']) ?><br>
			    	</div>
			    	<div class="col-md-4">
			    		<label>Loan Amount:</label> Php <?php print_r($detail['PrincipalAmount']) ?><br>
			    		<label>Interest Rate:</label> <?php print_r($detail['InterestRate']) ?><br>
			    		<label>Interest:</label> 
			    		<?php
			    			$TotalInterest = 0;
				        if($detail['InterestType'] == 'Percentage')
				        {
				        	$TotalInterest = ($detail['RawPrincipalAmount'] * ($detail['Amount']/100)) * $detail['TermNo'];
				        }
				        else
				        {
				        	$TotalInterest = (($detail['Amount'])) * $detail['TermNo'];
				        }
			    			$totalDue = $TotalInterest + $detail['RawPrincipalAmount'];
	    					print_r('Php '. number_format($TotalInterest, 2));
			    		?><br>





			    		<label>Additional Charges:</label> 
			    		<?php 
			    			if($charges['TotalCharges'] != null)
				    		{
				    			print_r('Php ' . number_format($charges['TotalCharges'], 2));
				    		}
				    		else
				    		{
				    			print_r('Php 0.00');
				    		}
				    	?><br>
			    	</div>
			    	<div class="col-md-4">
			    		<label>Penalty:</label>
			    		<?php 
			    			if($penalties['Total'] != null)
				    		{
				    			print_r('Php ' .number_format($penalties['Total'], 2));
				    		}
				    		else
				    		{
				    			print_r('Php 0.00');
				    		}
				    	?><br>
			    		<label>Due: </label> <?php print_r('Php '. number_format($totalDue, 2)); ?><br>
			    		<label>Paid: </label>
			    		<?php 
			    			if($payments['Total'] != null)
				    		{
				    			print_r('Php ' .number_format($payments['Total'], 2));
				    		}
				    		else
				    		{
				    			print_r('Php 0.00');
				    		}
				    	?><br>
			    		<label>Balance: </label> 
			    		<?php 
			    			$balanceDue = $totalDue - $payments['Total'];

			    		print_r('Php ' . number_format($balanceDue, 2)) 

			    		?><br>
			    	</div>
			    </div>
		    </div>
		  </div>
  	<!-- TAB DETAILS -->
	    <div class="box">
	      <div class="box-header with-border">
	      </div>
		    <div class="box-body">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tabHistory" data-toggle="tab" title="History"><span class="fa fa-clipboard"></span></a></li>
              <li><a href="#tabRepayments" data-toggle="tab" title="Repayments"><span class="fa fa-money"></span></a></li>
              <li><a href="#tabPenalty" data-toggle="tab" title="Penalties"><span class="fa fa-institution"></span></a></li>
              <li><a href="#tabCollateral" data-toggle="tab" title="Loan Collateral"><span class="fa fa-navicon "></span></a></li>
              <li><a href="#tabRequirements" data-toggle="tab" title="Loan Requirements"><span class="fa fa-clipboard "></span></a></li>
              <li><a href="#tabCharges" data-toggle="tab" title="Loan Charges"><span class="fa fa-chain "></span></a></li>
              <li><a href="#tabIncome" data-toggle="tab" title="Sources of Other Income"><span class="fa fa-credit-card "></span></a></li>
              <li><a href="#tabExpense" data-toggle="tab" title="Monthly Expense"><span class="fa fa-database "></span></a></li>
              <li><a href="#tabObligations" data-toggle="tab" title="Monthly Obligation"><span class="fa fa-certificate "></span></a></li>
              <li><a href="#tabComments" data-toggle="tab" title="Comments"><span class="fa fa-comment "></span></a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="tabHistory">
              	<h4>History</h4>
              	<br>
                <table id="dtblHistory" class="table table-bordered table-hover" style="width: 100%">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Action</th>
                    <th>By</th>
                    <th>Date Creation</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $rowNumber = 0;
                      foreach ($LoanHistory as $value) 
                      {
                        $rowNumber = $rowNumber + 1;
                        echo "<tr>";
                        echo "<td>".$rowNumber."</td>";
                        echo "<td>".$value['Description']."</td>";
                        echo "<td>".$value['CreatedBy']."</td>";
                        echo "<td>".$value['DateCreated']."</td>";
                        echo "</tr>";
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="tab-pane" id="tabRepayments">
              	<h4>Collections</h4>
              	<a class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modalRepayment" onclick="computePayment()">Add Collection</a>
              	<br>
              	<br>
                <table id="dtblRepayment" class="table table-bordered table-hover" style="width: 100%">
                  <thead>
                  <tr>
                    <th>Reference No</th>
                    <th>Collected By</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Collection Date</th>
                    <th>Payment For</th>
                    <th>Date Creation</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $rowNumber = 0;
                      if($Payments != 0)
                      {
                        foreach ($Payments as $value) 
                        {
                          if($value['StatusId'] == 1)
                          {
                            $status = '<span class="badge bg-green">Active</span>';
                            $action = '<a class="btn btn-default btn-sm" title="View" data-toggle="modal" data-target="#modalPaymentDetails"><span class="fa fa-info-circle"></span></a> <a class="btn btn-danger btn-sm" title="Deactivated" data-toggle="modal" data-target="#modalUpdate" onclick="approvalType(4, '.$value['PaymentMadeId'].')"><span class="fa fa-close"></span></a>';
                          }
                          else 
                          {
                            $status = '<span class="badge bg-red">Deactivated</span>';
                            $action = 'N/A';
                          }
                          echo "<tr>";
                          echo "<td>".$value['ReferenceNo']."</td>";
                          echo "<td>".$value['CreatedBy']."</td>";
                          echo "<td>".$value['BankName']."</td>";
                          echo "<td>".number_format($value['Amount'], 2)."</td>";
                          echo "<td>".$value['DateCollected']."</td>";
                          echo "<td>".$value['PaymentDate']."</td>";
                          echo "<td>".$value['DateCreated']."</td>";
                          echo "<td>".$status."</td>";
                          echo '<td>'.$action.'</td> ';
                          echo "</tr>";
                        }
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="tab-pane" id="tabPenalty">
                <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/penaltySettings/<?php print_r($detail['ApplicationId']) ?>" method="post">
                  <h4>Penalties</h4>
                  <a class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modalPenalty">Add Penalty</a>
                  <br>
                  <br>
                  <div class="row">
                    <div class="col-md-12">
                      <table id="dtblPenalty" class="table table-bordered table-hover" style="width: 100%">
                        <thead>
                        <tr>
                          <th>Reference No</th>
                          <th>Penalty Type</th>
                          <th>Amount/Percentage</th>
                          <th>Grace Period</th>
                          <th>Total Penalty</th>
                          <th>Date Creation</th>
                          <th>Created By</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php 
                          $rowNumber = 0;
                          if($DisplayPenalty != 0)
                          {
                            foreach ($DisplayPenalty as $value) 
                            {
                              if($value['StatusId'] == 1)
                              {
                                $status = '<span class="badge bg-green">Active</span>';
                                $action = '<a class="btn btn-danger btn-sm" title="Deactivated" data-toggle="modal" data-target="#modalUpdate" onclick="confirm" ><span class="fa fa-close"></span></a>';
                              }
                              else 
                              {
                                $status = '<span class="badge bg-red">Deactivated</span>';
                                $action = 'N/A';
                              }
                              echo "<tr>";
                              echo "<td>".$value['ReferenceNo']."</td>";
                              echo "<td>".$value['PenaltyType']."</td>";
                              echo "<td>".$value['Amount']."</td>";
                              echo "<td>".$value['GracePeriod']."</td>";
                              echo "<td>".number_format($value['TotalPenalty'], 2)."</td>";
                              echo "<td>".$value['DateCreated']."</td>";
                              echo "<td>".$value['CreatedBy']."</td>";
                              echo "<td>".$status."</td>";
                              echo '<td>'.$action.'</td> ';
                              echo "</tr>";
                            }
                          }
                        ?>
                      </tbody>
                      </table>
                    </div>
                    <br>
                  </div>
                </form>
              </div>
              <div class="tab-pane" id="tabCollateral">
              	<h4>Collateral</h4>
              	<a class="btn btn-primary btn-sm pull-right" onclick="onCollateralChange(1)" data-toggle="modal" data-target="#modalCollateral">Add Collateral</a>
              	<br>
              	<br>
                <table id="dtblCollateral" class="table table-bordered table-hover" style="width: 100%">
                  <thead>
                  <tr>
                    <th>Reference No.</th>
                    <th>Collateral</th>
                    <th>Current Status</th>
                    <th>Value</th>
                    <th>Type</th>
                    <th>Register Date</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $rowNumber = 0;
                      foreach ($collateral as $value) 
                      {
                        echo "<tr>";
                        echo "<td>".$value['ReferenceNo']."</td>";
                        echo "<td>".$value['ProductName']."</td>";
                        echo "<td>".$value['CurrentStatus']."</td>";
                        echo "<td>".number_format($value['Value'], 2)."</td>";
                        echo "<td>".$value['CollateralType']."</td>";
                        echo "<td>".$value['DateRegistered']."</td>";
                        echo "<td>".$value['DateRegistered']."</td>";
                        echo '<td><a class="btn btn-default btn-sm" data-toggle="modal" data-target="#modalCollateral" title="View" onclick="viewCollateral('.$value['CollateralId'].', 1)"><span class="fa fa-info-circle"></span></a> <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalCollateral" title="Edit" onclick="viewCollateral('.$value['CollateralId'].', 2)"><span class="fa fa-edit"></span></a></td> ';
                        echo "</tr>";
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="tab-pane" id="tabRequirements">
              	<h4>Requirements</h4>
              	<a class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modalRequirement">Add Requirements</a>
              	<br>
              	<br>
                <table id="dtblRequirements" class="table table-bordered table-hover" style="width: 100%">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Date Creation</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $rowNumber = 0;
                      foreach ($requirementList as $value) 
                      {
                        $rowNumber = $rowNumber + 1;
                        echo "<tr>";
                        echo "<td>".$rowNumber."</td>";
                        echo "<td>".$value['Name']."</td>";
                        echo "<td>".$value['DateCreated']."</td>";
                        echo "<td>".$value['Description']."</td>";
                         if ($value['StatusId'] == 7) // Pending 
                         {
                           $action = '<a data-toggle="modal" data-target="#modalUpload" onclick="uploadRequirementsChange('.$value['ApplicationRequirementId'].')" class="btn btn-primary btn-sm" title="Download"><span class="fa fa-download"></span></a> <a onclick="confirm(\'Are you sure you want to deactivate this requirement?\', \''.$value['ApplicationRequirementId'].'\', 6, \'Requirements\')" class="btn btn-danger btn-sm" title="Cancel"><span class="fa fa-close"></span></a>';
                         }
                         else if($value['StatusId'] == 2) // submitted
                        {
                          $action = '<a data-toggle="modal" data-target="#modalUpload" onclick="uploadRequirementsChange('.$value['ApplicationRequirementId'].')" class="btn btn-primary btn-sm" title="Download"><span class="fa fa-download"></span></a>  <a onclick="confirm(\'Are you sure you want to deactivate this requirement?\', '.$value['ApplicationRequirementId'].', 6, \'Requirements\')" class="btn btn-danger btn-sm" title="Cancel"><span class="fa fa-close"></span></a>';
                        }
                        else 
                        {
                          $action = '<a data-toggle="modal" data-target="#modalUpload" onclick="uploadRequirementsChange('.$value['ApplicationRequirementId'].')" class="btn btn-success btn-sm" title="Upload"><span class="fa fa-upload"></span></a> <a class="btn btn-danger btn-sm" title="Cancel"><span class="fa fa-close"></span></a>';
                        }

                        echo '<td>'.$action.'</td>';
                        echo "</tr>";
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="tab-pane" id="tabCharges">
                <h4>Charges</h4>
                <a class="btn btn-primary btn-sm pull-right" data-toggle="modal" onclick="onclickCharge(1)" data-target="#modalCharge">Add Charge</a>
                <br>
                <br>
                <table id="dtblCharges" class="table table-bordered table-hover" style="width: 100%">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Charge</th>
                    <th>Amount</th>
                    <th>Total Charge</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $rowNumber = 0;
                      foreach ($chargeList as $value) 
                      {
                        $rowNumber = $rowNumber + 1;
                        echo "<tr>";
                        echo "<td>".$rowNumber."</td>";
                        echo "<td>".$value['Name']."</td>";
                        echo "<td>".$value['Amount']."</td>";
                        echo "<td>".$value['TotalCharge']."</td>";
                         if ($value['StatusId'] == 7) // Pending 
                         {
                           $status = "<span class='badge bg-orange'>Pending</span>";
                           $action = '<a data-toggle="modal" data-target="#modalUpload" onclick="uploadRequirementsChange('.$value['ApplicationChargeId'].')" class="btn btn-primary btn-sm" title="Download"><span class="fa fa-download"></span></a> <a onclick="confirm(\'Are you sure you want to deactivate this Charge?\', \''.$value['ApplicationChargeId'].'\', 6, \'Charge\')" class="btn btn-danger btn-sm" title="Cancel"><span class="fa fa-close"></span></a>';
                         }
                         else if($value['StatusId'] == 6) // deactivated
                        {
                          $status = "<span class='badge bg-red'>Deactivated</span>";
                          $action = '<a data-toggle="modal" data-target="#modalUpload" onclick="uploadRequirementsChange('.$value['ApplicationChargeId'].')" class="btn btn-primary btn-sm" title="Download"><span class="fa fa-download"></span></a>  <a onclick="confirm(\'Are you sure you want to deactivate this Charge?\', '.$value['ApplicationChargeId'].', 2, \'Charge\')" class="btn btn-danger btn-sm" title="Cancel"><span class="fa fa-close"></span></a>';
                        }
                        else 
                        {
                          $action = '<a data-toggle="modal" data-target="#modalUpload" onclick="uploadRequirementsChange('.$value['ApplicationChargeId'].')" class="btn btn-success btn-sm" title="Upload"><span class="fa fa-upload"></span></a> <a class="btn btn-danger btn-sm" title="Cancel"><span class="fa fa-close"></span></a>';
                        }
                        echo '<td>'.$status.'</td>';
                        echo '<td>'.$action.'</td>';
                        echo "</tr>";
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="tab-pane" id="tabIncome">
              	<h4>Sources of Other Income</h4>
              	<a class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modalIncome">Add Income</a>
              	<br>
              	<br>
                <table id="dtblIncome" class="table table-bordered table-hover" style="width: 100%">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Source</th>
                    <th>Details</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Date Creation</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $rowNumber = 0;
                      foreach ($income as $value) 
                      {
                        $rowNumber = $rowNumber + 1;
                        echo "<tr>";
                        echo "<td>".$rowNumber."</td>";
                        echo "<td>".$value['Source']."</td>";
                        echo "<td>".$value['Details']."</td>";
                        echo "<td>".number_format($value['Amount'], 2)."</td>";
                        echo "<td>".$value['Name']."</td>";
                        echo "<td>".$value['DateCreated']."</td>";
                        if($value['StatusId'] == 2)
                        {
                          $status = "<span class='badge bg-green'>Active</span>";
                          $action = '<a onclick="EditIncome(\''.$value['IncomeId'].'\')" data-toggle="modal" data-target="#modalIncome" class="btn btn-primary btn-sm" title="Edit"><span class="fa fa-edit"></span></a> <a onclick="confirm(\'Are you sure you want to deactivate this Income Source record?\', \''.$value['IncomeId'].'\', 6, \'Incomes\') "class="btn btn-danger btn-sm" title="Deactivate"><span class="fa fa-close"></span></a>';
                        }
                        else
                        {
                          $status = "<span class='badge bg-red'>Deactivated</span>";
                          $action = '<a onclick="confirm(\'Are you sure you want to re-activate this Income Source record?\', \''.$value['IncomeId'].'\', 2, \'Incomes\')" class="btn btn-warning" title="Re-Activate"><span class="fa fa-refresh"></span></a>';
                        }
                        echo "<td>".$status."</td>";
                        echo "<td>".$action."</td>";
                        echo "</tr>";
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="tab-pane" id="tabExpense">
              	<h4>Monthly Expenses</h4>
              	<br>
              	<a class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modalExpense">Add Expense</a>
              	<br>
              	<br>
                <table id="dtblExpense" class="table table-bordered table-hover" style="width: 100%">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Source</th>
                    <th>Details</th>
                    <th>Amount</th>
                    <th>Created By</th>
                    <th>Date Creation</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $rowNumber = 0;
                      foreach ($expense as $value) 
                      {
                        $rowNumber = $rowNumber + 1;
                        echo "<tr>";
                        echo "<td>".$rowNumber."</td>";
                        echo "<td>".$value['Source']."</td>";
                        echo "<td>".$value['Details']."</td>";
                        echo "<td>".number_format($value['Amount'], 2)."</td>";
                        echo "<td>".$value['CreatedBy']."</td>";
                        echo "<td>".$value['DateCreated']."</td>";
                        if($value['StatusId'] == 2)
                        {
                          $status = "<span class='badge bg-green'>Active</span>";
                          $action = '<a onclick="EditExpense(\''.$value['ExpenseId'].'\')" data-toggle="modal" data-target="#modalExpense" class="btn btn-primary btn-sm" title="Edit"><span class="fa fa-edit"></span></a> <a onclick="confirm(\'Are you sure you want to deactivate this expense record?\', \''.$value['ExpenseId'].'\', 6, \'Expenses\') "class="btn btn-danger btn-sm" title="Deactivate"><span class="fa fa-close"></span></a>';
                        }
                        else
                        {
                          $status = "<span class='badge bg-red'>Deactivated</span>";
                          $action = '<a onclick="confirm(\'Are you sure you want to re-activate this Expense record?\', \''.$value['ExpenseId'].'\', 2, \'Expenses\')" class="btn btn-warning" title="Re-Activate"><span class="fa fa-refresh"></span></a>';
                        }
                        echo "<td>".$status."</td>";
                        echo "<td>".$action."</td>";
                        echo "</tr>";
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="tab-pane" id="tabObligations">
              	<h4>Monthly Obligations</h4>
              	<br>
              	<a class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modalObligation">Add Obligations</a>
              	<br>
              	<br>
                <table id="dtblObligations" class="table table-bordered table-hover" style="width: 100%">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Source</th>
                    <th>Details</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Date Creation</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $rowNumber = 0;
                      foreach ($obligations as $value) 
                      {
                        $rowNumber = $rowNumber + 1;
                        echo "<tr>";
                        echo "<td>".$rowNumber."</td>";
                        echo "<td>".$value['Source']."</td>";
                        echo "<td>".$value['Details']."</td>";
                        echo "<td>".number_format($value['Amount'], 2)."</td>";
                        echo "<td>".$value['Description']."</td>";
                        echo "<td>".$value['CreatedBy']."</td>";
                        echo "<td>".$value['DateCreated']."</td>";
                        if($value['StatusId'] == 2)
                        {
                          $status = "<span class='badge bg-green'>Active</span>";
                          $action = '<a onclick="EditObligation(\''.$value['MonthlyObligationId'].'\')" data-toggle="modal" data-target="#modalObligation" class="btn btn-primary btn-sm" title="Edit"><span class="fa fa-edit"></span></a> <a onclick="confirm(\'Are you sure you want to deactivate this obligation record?\', \''.$value['MonthlyObligationId'].'\', 6, \'Obligations\') "class="btn btn-danger btn-sm" title="Deactivate"><span class="fa fa-close"></span></a>';
                        }
                        else
                        {
                          $status = "<span class='badge bg-red'>Deactivated</span>";
                          $action = '<a onclick="confirm(\'Are you sure you want to re-activate this Obligation record?\', \''.$value['MonthlyObligationId'].'\', 2, \'Obligations\')" class="btn btn-warning" title="Re-Activate"><span class="fa fa-refresh"></span></a>';
                        }
                        echo "<td>".$action."</td>";
                        echo "</tr>";                        
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="tab-pane" id="tabComments">
              	<h4>Comments</h4>
              	<br>
              	<a class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modalComment">Add Comment</a>
              	<br>
              	<br>
                <table id="dtblComments" class="table table-bordered table-hover" style="width: 100%">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Comment</th>
                    <th>By</th>
                    <th>Date Creation</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php 
                      $rowNumber = 0;
                      foreach ($comments as $value) 
                      {
                        $rowNumber = $rowNumber + 1;
                        echo "<tr>";
                        echo "<td>".$rowNumber."</td>";
                        echo "<td>".$value['Comment']."</td>";
                        echo "<td>".$value['Name']."</td>";
                        echo "<td>".$value['DateCreated']."</td>";
                        echo '<td><a class="btn btn-primary btn-sm" title="Edit"><span class="fa fa-edit"></span></a> <a class="btn btn-default btn-sm" title="Download"><span class="fa fa-download"></span></a> <a class="btn btn-danger btn-sm" title="Cancel"><span class="fa fa-close"></span></a></td> ';
                        echo "</tr>";
                      }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
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

  $('#dateAcquired').daterangepicker({
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

  $('#dateRegistered').daterangepicker({
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
  
  $('#dtblRepayment').DataTable({
    "order": [[0, "desc"]]
  });
  
  $('#dtblPenalty').DataTable({
    "order": [[3, "asc"]]
  });

  $('#dtblCollateral').DataTable({
    "order": [[3, "asc"]]
  });

  $('#dtblRequirements').DataTable({
    "order": [[0, "desc"]]
  });

  $('#dtblComments').DataTable({
    "order": [[0, "desc"]]
  });
  $('#dtblIncome').DataTable({
    "order": [[0, "desc"]]
  });
  $('#dtblExpense').DataTable({
    "order": [[0, "desc"]]
  });
  $('#dtblObligations').DataTable({
    "order": [[0, "desc"]]
  });

  var rowNumber = 0;
  $('#dtblHistory').DataTable({
    "order": [[0, "desc"]]
  });

  // charges type
  function onclickCharge(type)
  {
    if(type == 1) // add new
    {

    }
    else
    {
      $('#txtChargeId').val();
    }
    $('#txtChargeFormType').val(type);
  }

  function displayCharge(value)
  {
    $.ajax({
      url: '<?php echo base_url()?>' + "/loanapplication_controller/getChargeDetails",
      type: "POST",
      async: false,
      data: {
        Id    : value,
        Type  : 1
      },
      dataType: "JSON",
      beforeSend: function(){
          $('.loading').show();
      },
      success: function(data)
      {
        $('#divChargeDisplay').slideDown();
        if(data['ChargeType'] == 'Percentage')
        {
          $('#lblChargeAmount').html(parseInt(data['Amount']).toLocaleString('en-US', {minimumFractionDigits: 2})+'%');
          $('#lblChargeTotal').html('Php ' + parseInt(data['Amount']/100 * '<?php print_r($detail['RawPrincipalAmount'])?>').toLocaleString('en-US', {minimumFractionDigits: 2}));
        }
        else
        {
          $('#lblChargeAmount').html('Php ' + parseInt(data['Amount']).toLocaleString('en-US', {minimumFractionDigits: 2}));
          $('#lblChargeTotal').html('Php ' + parseInt(data['Amount']).toLocaleString('en-US', {minimumFractionDigits: 2}));
        }
        $('#lblChargeType').html(data['ChargeType']);
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

  // repayment functions
    var varPrincipalCollection = '<?php print_r( round($paymentDues['InterestPerCollection'], 2) + round($paymentDues['PrincipalPerCollection'], 2)) ?>';
    var varTotalPenalty = 0;
    var varTotalLapseDays = 0;
    var varChange = 0; 
    var varTotalAmountDue = '<?php print_r(round($paymentDues['InterestPerCollection'], 2) + round($paymentDues['PrincipalPerCollection'], 2)) ?>';

    var varPrincipalCollection2 = 0;
    var varTotalPenalty2 = 0;
    var varTotalLapseDays2 = 0;
    var varChange2 = 0; 
    var varTotalAmountDue2 = 0;
    function computePayment()
    {
      varChange = $('#txtAmountPaid').val() - varTotalAmountDue;
      $('#lblChange').html('Php ' + Math.abs(varChange).toLocaleString('en-US', {minimumFractionDigits: 2}))
      $('#txtChangeAmount').val(Math.abs(Math.round(varChange*100)/100));
    }

    function computePayment2()
    {
      varChange2 = $('#txtAmountPaid2').val() - varTotalAmountDue2;
      $('#lblChange2').html('Php ' + Math.abs(varChange2).toLocaleString('en-US', {minimumFractionDigits: 2}))
      $('#txtChangeAmount2').val(Math.abs(Math.floor(varChange2)));
    }

    function onchangePrincipalPayment()
    {
      if($('#chkPayment1').is(":checked") == true) // principal collection
      {
        varTotalAmountDue = varTotalAmountDue + <?php print_r(round($paymentDues['PrincipalPerCollection'], 2)) ?>;
        $('#txtAmountDue').val(varTotalAmountDue);
        $('#txtAmountPaid').val(0.00);
        $('#lblChange').html('Php 0.00');
        $('#txtTotalDue').val(varTotalAmountDue);
        $('#txtPrincipalAmountCollected').val(<?php print_r(round($paymentDues['PrincipalPerCollection'], 2)) ?>);
        $('#lblTotalAmountDue').html('Php ' + varTotalAmountDue.toLocaleString('en-US', {minimumFractionDigits: 2}));
      }
      else
      {
        varTotalAmountDue = varTotalAmountDue - <?php print_r(round($paymentDues['PrincipalPerCollection'], 2)) ?>;
        $('#txtAmountDue').val(varTotalAmountDue);
        $('#txtAmountPaid').val(0.00);
        $('#lblChange').html('Php 0.00');
        $('#txtTotalDue').val(varTotalAmountDue);
        $('#txtPrincipalAmountCollected').val(<?php print_r(round($paymentDues['PrincipalPerCollection'], 2)) ?>);
        $('#lblTotalAmountDue').html('Php ' + varTotalAmountDue.toLocaleString('en-US', {minimumFractionDigits: 2}));
      }
    }

    function onchangePrincipalInterestPayment()
    {
      if($('#chkPayment2').is(":checked") == true) // interest
      {
        varTotalAmountDue = varTotalAmountDue + <?php print_r(round($paymentDues['InterestPerCollection'], 2)) ?>;
        $('#txtAmountDue').val(varTotalAmountDue);
        $('#txtTotalDue').val(varTotalAmountDue);
        $('#txtAmountPaid').val(0.00);
        $('#lblChange').html('Php 0.00');
        $('#txtInterestAmountCollected').val(<?php print_r(round($paymentDues['InterestPerCollection'], 2)) ?>);
        $('#lblTotalAmountDue').html('Php ' + varTotalAmountDue.toLocaleString('en-US', {minimumFractionDigits: 2}));
      }
      else // interest
      {
        varTotalAmountDue = varTotalAmountDue - <?php print_r(round($paymentDues['InterestPerCollection'], 2)) ?>;
        $('#txtAmountDue').val(varTotalAmountDue);
        $('#txtTotalDue').val(varTotalAmountDue);
        $('#txtAmountPaid').val(0.00);
        $('#lblChange').html('Php 0.00');
        $('#txtInterestAmountCollected').val(<?php print_r(round($paymentDues['InterestPerCollection'], 2)) ?>);
        $('#lblTotalAmountDue').html('Php ' + varTotalAmountDue.toLocaleString('en-US', {minimumFractionDigits: 2}));
      }
    }

    function onchangePenaltyType()
    {
      var date1 = new Date($('#datePayment').val()); 
      var date2 = new Date($('#dateCollected').val()); 

      var Difference_In_Time = date2.getTime() - date1.getTime(); 
      var totalDays = Difference_In_Time / (1000 * 3600 * 24); 
      varTotalLapseDays = parseInt(totalDays - $('#txtGracePeriod').val());

      if($('#chkPenalty').is(":checked") == true)
      {
        if(varTotalLapseDays > 0)
        {
          $('#txtIsPenalized').val(1);
          $('#divPenalty').show();
          if($('#selectPenaltyType').val() == 'Flat Rate')
          {
            if(varTotalLapseDays > 0)
            {
              $('#inputLblPenaltyType').html('Amount');
              $('#lblChange').val('Php 0.00');
              varTotalPenalty = parseInt(varTotalLapseDays * $('#txtPenaltyAmount').val());
              $('#lblTotalPenalty').html('Php ' + varTotalPenalty.toLocaleString('en-US', {minimumFractionDigits: 2}));

              varTotalAmountDue = parseFloat(varTotalAmountDue, 2)  + parseFloat(varTotalPenalty, 2);
              $('#lblTotalAmountDue').html(varTotalAmountDue.toLocaleString('en-US', {minimumFractionDigits: 2}));
              $('#txtTotalDue').val(varTotalAmountDue);
              $('#txtAmountPaid').val(0);
            }
            else
            {
              varTotalAmountDue = parseInt(varTotalAmountDue)  - parseInt(varTotalPenalty);
              $('#lblTotalPenalty').html('Php 0.00');
              $('#lblChange').val('Php 0.00');
              $('#lblTotalAmountDue').html(varTotalAmountDue.toLocaleString('en-US', {minimumFractionDigits: 2}));
              $('#txtTotalDue').val(varTotalAmountDue);
              $('#txtAmountPaid').val(0);
            }
          }
          else
          {
            $('#inputLblPenaltyType').html('Percentage');
            if(varTotalLapseDays > 0)
            {
              varTotalPenalty = parseInt(varTotalLapseDays * (varTotalAmountDue * ($('#txtPenaltyAmount').val() / 100)));
              $('#lblTotalPenalty').html('Php ' + varTotalPenalty.toLocaleString('en-US', {minimumFractionDigits: 2}));

              varTotalAmountDue = parseInt(varTotalAmountDue)  + parseInt(varTotalPenalty);
              $('#lblTotalAmountDue').html('Php ' + varTotalAmountDue.toLocaleString('en-US', {minimumFractionDigits: 2}));
              $('#txtTotalDue').val(varTotalAmountDue);
              $('#txtAmountPaid').val(0);
              $('#lblChange').val('Php 0.00');
            }
            else
            {
              varTotalAmountDue = parseInt(varTotalAmountDue)  - parseInt(varTotalPenalty);
              $('#lblTotalPenalty').html('Php 0.00');
              $('#lblTotalAmountDue').html('Php ' + varTotalAmountDue.toLocaleString('en-US', {minimumFractionDigits: 2}));
              $('#txtTotalDue').val(varTotalAmountDue);
              $('#txtAmountPaid').val(0);
              $('#lblChange').val('Php 0.00');
            }
          }
          $('#txtTotalPenalty').val(varTotalPenalty);
        }
        else
        {
          document.getElementById("chkPenalty").checked = false;
          $('#divPenalty').slideUp()
          swal({
            title: 'Info!',
            text: 'Collection date must be greater than payment date to compute for penalty!',
            type: 'info',
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary'
          });
          $('#txtIsPenalized').val(0);
          $('#txtGracePeriod').val(0);
          varTotalPenalty = parseInt(0);
          $('#lblTotalAmountDue').html('Php ' + varTotalAmountDue.toLocaleString('en-US', {minimumFractionDigits: 2}));
          $('#txtTotalDue').val(varTotalAmountDue);
          $('#txtAmountPaid').val(0);
        }
      }
      else
      {
        varTotalAmountDue = varTotalAmountDue - varTotalPenalty;
        $('#txtIsPenalized').val(0);
        $('#divPenalty').hide();
        $('#lblTotalAmountDue').html('Php ' + varTotalAmountDue.toLocaleString('en-US', {minimumFractionDigits: 2}));
        $('#txtTotalDue').val(varTotalAmountDue);
        $('#txtAmountPaid').val(0);
      }
    }

    function onchangePenaltyType2()
    {
      var date12 = new Date($('#datePayment2').val()); 
      var date22 = new Date($('#dateCollected2').val());

      var Difference_In_Time2 = date22.getTime() - date12.getTime(); 
      var totalDays2 = Difference_In_Time2 / (1000 * 3600 * 24); 
      varTotalLapseDays2 = parseInt(totalDays2 - $('#txtGracePeriod2').val());
      
      if($('#selectPenaltyType2').val() == 'Flat Rate')
      {
        if(varTotalLapseDays2 > 0)
        {
          $('#inputLblPenaltyType2').html('Amount');
          $('#lblChange2').val('Php 0.00');
          varTotalPenalty2 = parseInt(varTotalLapseDays2 * $('#txtPenaltyAmount2').val());
          $('#lblTotalPenalty2').html('Php ' + varTotalPenalty2.toLocaleString('en-US', {minimumFractionDigits: 2}));

          varTotalAmountDue2 = parseInt(varTotalPenalty2);
          $('#lblTotalAmountDue2').html('Php ' + varTotalAmountDue2.toLocaleString('en-US', {minimumFractionDigits: 2}));
          $('#txtTotalDue2').val(varTotalAmountDue2);
          $('#txtAmountPaid2').val(0);
        }
        else
        {
          $('#lblTotalPenalty2').html('Php 0.00');
          $('#lblChange2').val('Php 0.00');
          $('#lblTotalAmountDue2').html('Php ' + varPrincipalCollection2.toLocaleString('en-US', {minimumFractionDigits: 2}));
          $('#txtTotalDue2').val(varPrincipalCollection2);
          $('#txtAmountPaid2').val(0);
        }
      }
      else
      {
        $('#inputLblPenaltyType2').html('Percentage');

        if(varTotalLapseDays2 > 0)
        {
          varTotalPenalty2 = parseInt(varTotalLapseDays2 * ('<?php print_r($detail['RawPrincipalAmount']) ?>' * ($('#txtPenaltyAmount2').val() / 100)));
          $('#lblTotalPenalty2').html('Php ' + varTotalPenalty2.toLocaleString('en-US', {minimumFractionDigits: 2}));
          varTotalAmountDue2 = parseInt(varTotalPenalty2);
          $('#lblTotalAmountDue2').html('Php ' + varTotalAmountDue2.toLocaleString('en-US', {minimumFractionDigits: 2}));
          $('#txtTotalDue2').val(varTotalAmountDue2);
          $('#txtAmountPaid2').val(0);
          $('#lblChange2').val('Php 0.00');
        }
        else
        {
          varTotalAmountDue2 = varPrincipalCollection2;
          $('#lblTotalPenalty2').html('Php 0.00');
          $('#lblTotalAmountDue2').html('Php ' + varPrincipalCollection2.toLocaleString('en-US', {minimumFractionDigits: 2}));
          $('#txtAmountPaid2').val(0);
          $('#lblChange2').val('Php 0.00');
        }
      }
      $('#txtTotalPenalty2').val(varTotalPenalty2);
    }

  function confirm(Text, Id, updateType, Type)
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
          url: "<?php echo base_url();?>" + "/loanapplication_controller/updateStatus",
          method: "POST",
          data:   {
                    Id : Id
                    , updateType : updateType
                    , Type : Type
                  },
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

  function EditObligation(MonthlyObligationId)
  {
    $.ajax({
      url: '<?php echo base_url()?>' + "/loanapplication_controller/getObligationDetails",
      type: "POST",
      async: false,
      data: {
        Id : MonthlyObligationId
      },
      dataType: "JSON",
      beforeSend: function(){
          $('.loading').show();
      },
      success: function(data)
      {
        $('#txtObligation').val(data['Source']);
        $('#txtObligationDetail').val(data['Details']);
        $('#txtObligationAmount').val(data['Amount']);
        $('#txtMonthlyObligationId').val(MonthlyObligationId);
        $('#txtFormType').val(2);
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

  function EditExpense(ExpenseId)
  { 
    $.ajax({
      url: '<?php echo base_url()?>' + "/loanapplication_controller/getExpenseDetails",
      type: "POST",
      async: false,
      data: {
        Id : ExpenseId
      },
      dataType: "JSON",
      beforeSend: function(){
          $('.loading').show();
      },
      success: function(data)
      {
        $('#txtExpense').val(data['Source']);
        $('#txtExpenseDetail').val(data['Details']);
        $('#txtExpenseAmount').val(data['Amount']);
        $('#txtExpenseId').val(ExpenseId);
        $('#txtFormTypeExpense').val(2);
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

  function EditIncome(IncomeId)
  { 
    $.ajax({
      url: '<?php echo base_url()?>' + "/loanapplication_controller/getIncomeDetails",
      type: "POST",
      async: false,
      data: {
        Id : IncomeId
      },
      dataType: "JSON",
      beforeSend: function(){
          $('.loading').show();
      },
      success: function(data)
      {
        $('#txtIncomeSource').val(data['Source']);
        $('#txtIncomeDetail').val(data['Details']);
        $('#txtIncomeAmount').val(data['Amount']);
        $('#txtIncomeId').val(IncomeId);
        $('#txtFormTypeIncome').val(2);
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

  function viewCollateral(CollateralId, Type)
  {
    $.ajax({
      url: '<?php echo base_url()?>' + "/loanapplication_controller/getCollateralDetails",
      type: "POST",
      async: false,
      data: {
        Id : CollateralId
      },
      dataType: "JSON",
      beforeSend: function(){
          $('.loading').show();
      },
      success: function(data)
      {
        if(Type == 1) // display info
        {
          $('#CollateralTitle').html('Collateral Information');
          $('#divCollateralDetails').show();
          $('#divCollateralForm').hide();

          $('#lblCollateralType').html(data['CollateralType']);
          $('#lblDownloadCollateral').html('<a class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>/home/download/3/'+CollateralId+'" title="Download">Download</a> ');
          $('#lblProductName').html(data['ProductName']);
          $('#lblValue').html('Php ' +  parseInt(data['Value']).toLocaleString('en-US', {minimumFractionDigits: 2}));
          $('#lblDateRegistered').html(data['DateRegistered']);
          $('#lblDateAcquired').html(data['DateAcquired']);
          $('#lblCurrentStatus').html(data['CollateralStatus']);

          if(data['CollateralTypeId'] == 1) // automobiles
          {
            $('#divCollateralAutomobile').show();
            $('#lblRegNumber').html(data['RegistrationNo']);
            $('#lblMileage').html(data['Mileage']);
            $('#lblEngineNumber').html(data['EngineNo']);
          }
          else
          {
            $('#divCollateralAutomobile').hide();
          }
        }
        else // display editing content
        {
          $('#divCollateralDetails').hide();
          $('#divCollateralAutomobile').hide();
          $('#divCollateralForm').show();
          $('#CollateralTitle').html('Edit Collateral')
          $('#SelectCollateralTypeId').val(data['CollateralTypeId']).change();
          $('#txtProductName').val(data['ProductName']);
          $('#txtCollaretalValue').val(data['Value']);
          $('#SelectCollateralStatus').val(data['StatusId']).change();
          $('#txtCollateralId').val(CollateralId);
          $('#txtModalType').val(1);

          $('#dateRegistered').daterangepicker({
              "startDate": moment(data['rawDateRegistered']).format('DD MMM YY'),
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

          $('#dateAcquired').daterangepicker({
              "startDate": moment(data['rawDateAcquired']).format('DD MMM YY'),
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

          if(data['CollateralTypeId'] == 1) // automobiles
          {
            $('#divAutomobiles').show();
            $('#txtRegistrationNo').val(data['RegistrationNo']);
            $('#txtMileage').val(data['Mileage']);
            $('#txtEngineNo').val(data['EngineNo']);
          }
          else
          {
            $('#divAutomobiles').hide();
          }
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

  function collateralTypeChange(value)
  {
    if(value == 1) // automobiles
    {
      $('#divAutomobiles').slideDown();
    }
    else
    {
      $('#divAutomobiles').slideUp();
    }
  }

  function onCollateralChange(value)
  {
    if(value == 1) // add collateral
    {
      $('#CollateralTitle').html('Add Collateral');
    }
    else
    {
      $('#CollateralTitle').html('Edit Collateral');
    }
  }

  function approvalType(Type, ID)
  {
    if(Type == 3) // remove charges added
    {
      $('#modalApprovalUpdateTitle').html('Remove Charge');
      $('#txtChargeId').val(ID);
    }
    else if(Type == 4) // remove payments added
    {
      $('#modalApprovalUpdateTitle').html('Remove Payment');
      $('#txtChargeId').val(ID);
    }
    $('#txtApprovalType').val(Type);
  }

  function uploadRequirementsChange(value)
  {
    $('#txtApplicationRequirementId').val(value)
  }

// RESTRUCTURE
  var varPrincipalAmount, varTermType, varTermNo, varRepaymentType, varRepaymentNo;
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

  $('#dateCollected').daterangepicker({
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

  $('#datePayment').daterangepicker({
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

  $('#dateCollected2').daterangepicker({
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

  $('#datePayment2').daterangepicker({
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

  $("#frmSubmitRepayment").on('submit', function (e) {
    if($('#txtAmountPaid').val() < $('#txtTotalDue').val())
    {
      e.preventDefault();
      swal({
        title: 'Info',
        text: 'Please make sure that total due is paid before submitting',
        type: 'info',
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary'
      });
    }
    else if($('#txtTotalDue').val() == 0)
    {
      e.preventDefault();
      swal({
        title: 'Info',
        text: 'No payment is required for this transaction.',
        type: 'info',
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary'
      });
    }
    else
    {
      e.preventDefault();
      swal({
        title: 'Confirm',
        text: 'Are you sure you want to submit payment?',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-success',
        confirmButtonText: 'Confirm',
        cancelButtonClass: 'btn btn-secondary'
      }).then(function(){
        e.currentTarget.submit();
        $('.loading').show();
      });
    }
  });

  $("#frmRestructure").on('submit', function (e) {
    e.preventDefault();
    swal({
      title: 'Confirm',
      text: 'Are you sure you want to restructure loan application? Once submitted, re-calculation will be done to the application.',
      type: 'warning',
      showCancelButton: true,
      buttonsStyling: false,
      confirmButtonClass: 'btn btn-success',
      confirmButtonText: 'Confirm',
      cancelButtonClass: 'btn btn-secondary'
    }).then(function(){
      e.currentTarget.submit();
      $('.loading').show();
    });
  });

  $("#frmSubmitPenalty").on('submit', function (e) {
    if($('#txtAmountPaid2').val() < $('#txtTotalDue2').val())
    {
      e.preventDefault();
      swal({
        title: 'Info',
        text: 'Please make sure that total due is paid before submitting',
        type: 'info',
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-primary'
      });
    }
    else
    {
      e.preventDefault();
      swal({
        title: 'Confirm',
        text: 'Are you sure you want to submit payment?',
        type: 'warning',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-success',
        confirmButtonText: 'Confirm',
        cancelButtonClass: 'btn btn-secondary'
      }).then(function(){
        e.currentTarget.submit();
        $('.loading').show();
      });
    }
  });

  $('#modalCollateral').on('hidden.bs.modal', function () {
    document.getElementById("frmCollateral").reset();
  });

</script>