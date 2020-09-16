
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
                <input type="hidden" name="FormType" id="txtFormType" value="1">
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
          <h4 class="modal-title" id="CollateralType"></h4>
        </div>
        <form autocomplete="off" action="<?php echo base_url(); ?>loanapplication_controller/AddComment/<?php print_r($detail['ApplicationId']) ?>" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <label>Type</label>
                <select class="form-control" name="CollateralType" onchange="collateralTypeChange(this.value)">
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
                <input type="text" name="ProductName" class="form-control">
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
                  <!-- /.input group -->
                </div>
              </div>
              <div class="col-md-12">
                <label>Value</label>
                <input type="number" name="CollateralValue" class="form-control">
              </div>
              <div class="col-md-6">
                <label>Current Status</label>
                <select class="form-control" name="CollateralType">
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
                    <input type="text" placeholder="DateDate Acquired" class="form-control" name="dateAcquired" required="" id="dateAcquired">
                  </div>
                  <!-- /.input group -->
                </div>
              </div>
            </div>
            <div id="divAutomobiles">
              <h5>Automobile Details</h5>
              <div class="row">
                <div class="col-md-4">
                  <label>Registration Number</label>
                  <input type="text" class="form-control" name="RegistrationNo">
                </div>
                <div class="col-md-4">
                  <label>Mileage</label>
                  <input type="text" class="form-control" name="Mileage">
                </div>
                <div class="col-md-4">
                  <label>Engine Number</label>
                  <input type="text" class="form-control" name="EngineNo">
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
                <input type="text" class="form-control" name="Obligation">
                <input type="hidden" id="txtObligation">
                <input type="hidden" name="FormType" id="txtFormType" value="1">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Detail</label>
                <textarea class="form-control" name="Detail"></textarea>
                <input type="hidden" id="txtDetail">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Amount</label>
                <input type="number" class="form-control" name="Amount">
                <input type="hidden" id="txtAmount">
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
                <input type="text" class="form-control" name="Expense">
                <input type="hidden" id="txtExpense">
                <input type="hidden" name="FormType" id="txtFormType" value="1">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Detail</label>
                <textarea class="form-control" name="Detail"></textarea>
                <input type="hidden" id="txtDetail">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Amount</label>
                <input type="number" class="form-control" name="Amount">
                <input type="hidden" id="txtAmount">
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
                <input type="text" class="form-control" name="Source">
                <input type="hidden" id="txtSource">
                <input type="hidden" name="FormType" id="txtFormType" value="1">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Detail</label>
                <textarea class="form-control" name="Detail"></textarea>
                <input type="hidden" id="txtDetail">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label>Amount</label>
                <input type="number" class="form-control" name="Amount">
                <input type="hidden" id="txtAmount">
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
		      		<label><?php print_r($detail['FileName']) ?></label><br>
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
			    		<label>Principal Amount:</label> Php <?php print_r($detail['PrincipalAmount']) ?><br>
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
				    			print_r('Php ' .$penalties['Total']);
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
				    			print_r('Php ' .$payments['Total']);
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
              <li><a href="#tabPenalty" data-toggle="tab" title="Penalty Settings"><span class="fa fa-institution"></span></a></li>
              <li><a href="#tabCollateral" data-toggle="tab" title="Loan Collateral"><span class="fa fa-navicon "></span></a></li>
              <li><a href="#tabRequirements" data-toggle="tab" title="Loan Requirements"><span class="fa fa-clipboard "></span></a></li>
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
                  </tbody>
                </table>
              </div>
              <div class="tab-pane" id="tabRepayments">
              	<h4>Repayments</h4>
              	<a class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modelRepayment">Add Repayment</a>
              	<br>
              	<br>
                <table id="dtblRepayment" class="table table-bordered table-hover" style="width: 100%">
                  <thead>
                  <tr>
                    <th>Reference No</th>
                    <th>Collection Date</th>
                    <th>Collected By</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              <div class="tab-pane" id="tabPenalty">
              	<h4>Penalty Settings</h4>
              	<div class="row">
	                <div class="col-md-12">
	                  <label></label>
	                  <label><input id='chkPenalty' onclick='onchangeIsPenalized()' type='checkbox'> Enable Late Repayment Penalty?</label>
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
	                    <input type="number" min="0" class="form-control" name="PenaltyAmount" id="txtPenaltyAmount">
	                  </div>
	                </div>
              	</div>
              </div>
              <div class="tab-pane" id="tabCollateral">
              	<h4>Collateral</h4>
              	<a class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modalCollateral">Add Collateral</a>
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
                        echo '<td><a class="btn btn-primary btn-sm" title="Edit"><span class="fa fa-edit"></span></a> <a class="btn btn-default btn-sm" title="Download"><span class="fa fa-download"></span></a> <a class="btn btn-danger btn-sm" title="Cancel"><span class="fa fa-close"></span></a></td> ';
                        echo "</tr>";
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <div class="tab-pane" id="tabRequirements">
              	<h4>Requirements</h4>
              	<a class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#modelRepayment">Add Requirements</a>
              	<br>
              	<br>
                <table id="dtblRequirements" class="table table-bordered table-hover" style="width: 100%">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
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
                        echo "<td>".$value['Description']."</td>";
                        echo "<td>".$value['Name']."</td>";
                        echo "<td>".$value['DateCreated']."</td>";
                        echo '<td><a class="btn btn-primary btn-sm" title="Edit"><span class="fa fa-edit"></span></a> <a class="btn btn-default btn-sm" title="Download"><span class="fa fa-download"></span></a> <a class="btn btn-danger btn-sm" title="Cancel"><span class="fa fa-close"></span></a></td> ';
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
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Date Creation</th>
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
                        echo "<td>".$value['Description']."</td>";
                        echo "<td>".$value['DateCreated']."</td>";
                        echo "<td>".$value['DateCreated']."</td>";
                        echo '<td><a class="btn btn-primary btn-sm" title="Edit"><span class="fa fa-edit"></span></a> <a class="btn btn-default btn-sm" title="Download"><span class="fa fa-download"></span></a> <a class="btn btn-danger btn-sm" title="Cancel"><span class="fa fa-close"></span></a></td> ';
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
                        echo "<td>".$value['DateCreated']."</td>";
                        echo "<td>".$value['DateCreated']."</td>";
                        echo '<td><a class="btn btn-primary btn-sm" title="Edit"><span class="fa fa-edit"></span></a> <a class="btn btn-default btn-sm" title="Download"><span class="fa fa-download"></span></a> <a class="btn btn-danger btn-sm" title="Cancel"><span class="fa fa-close"></span></a></td> ';
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
    "order": [[3, "asc"]]
  });

  $('#dtblCollateral').DataTable({
    "order": [[3, "asc"]]
  });

  $('#dtblRequirements').DataTable({
    "pageLength": 10,
    "ajax": { url: '<?php echo base_url()."/datatables_controller/displayRequirements/"; ?><?php print_r($detail['ApplicationId']) ?>', type: 'POST', "dataSrc": "" },
    "columns": [  
      { data: "ApplicationId", "render": function (data, type, b) {
          rowNumber = rowNumber + 1;
          return rowNumber;
        }
      }
      , { data: "Name" }
      , { data: "Description" }
      , { data: "DateCreated" }
    ],
    // "aoColumnDefs": [{ "bVisible": false, "aTargets": [7] }],
    "order": [[0, "desc"]]
  });

  $('#dtblComments').DataTable({
    // "aoColumnDefs": [{ "bVisible": false, "aTargets": [7] }],
    "order": [[0, "desc"]]
  });
  $('#dtblIncome').DataTable({
    // "aoColumnDefs": [{ "bVisible": false, "aTargets": [7] }],
    "order": [[0, "desc"]]
  });
  $('#dtblExpense').DataTable({
    // "aoColumnDefs": [{ "bVisible": false, "aTargets": [7] }],
    "order": [[0, "desc"]]
  });
  $('#dtblObligations').DataTable({
    // "aoColumnDefs": [{ "bVisible": false, "aTargets": [7] }],
    "order": [[0, "desc"]]
  });

  var rowNumber = 0;
  $('#dtblHistory').DataTable({
    "pageLength": 10,
    "ajax": { url: '<?php echo base_url()."/datatables_controller/displayLoanHistory/"; ?><?php print_r($detail['ApplicationId']) ?>', type: 'POST', "dataSrc": "" },
    "columns": [  
      { data: "ApplicationId", "render": function (data, type, b) {
          rowNumber = rowNumber + 1;
          return rowNumber;
        }
      }
      , { data: "Description" }
      , { data: "CreatedBy" }
      , { data: "DateCreated" }
    ],
    // "aoColumnDefs": [{ "bVisible": false, "aTargets": [7] }],
    "order": [[0, "desc"]]
  });

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
              text: 'Net Income Status successfully updated!',
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

  function Edit(Id)
  {
    $.ajax({
      url: '<?php echo base_url()?>' + "/loanapplication_controller/getObligationDetails",
      type: "POST",
      async: false,
      data: {
        Id : Id
      },
      dataType: "JSON",
      beforeSend: function(){
          $('.loading').show();
      },
      success: function(data)
      {
        $('#txtObligation').val(data['Obligation']);
        $('#txtDetail').val(data['Details']);
        $('#txtAmount').val(data['Amount']);
        $('#txtMonthlyObligationId').val(Id);
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

  function collateralTypeChange(value)
  {
    if(value == 1) // automobiles
    {
      $('#divAutomobiles').slideUp();
    }
    else
    {
      $('#divAutomobiles').slideDown();
    }
  }


</script>