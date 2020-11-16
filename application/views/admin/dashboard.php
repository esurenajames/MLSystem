<style type="text/css">
  .tab-content>.tab-pane {
    display: block;
    height: 0;
    overflow: hidden;
  }
  .tab-content>.tab-pane.active {
      height: auto;
  }
</style>
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Dashboard
    </h1>
    <ol class="breadcrumb">
      <li><a href="#" class="active"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    </ol>
  </section>

  <?php 
    $record = $this->maintenance_model->passwordValidity();
    $password = $this->maintenance_model->getPassword();
    if($record['IsNew'] == 1) { ?>
      <div class="modal fade" data-backdrop="static" id="modalNewPassword">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">CHANGE TEMPORARY PASSWORD</h4>
            </div>
            <form autocomplete="off" action="<?php echo base_url(); ?>admin_controller/addUser/1" id="frmInsert" method="post">
              <div class="modal-body">
                <div class="row">
                  <input type="hidden" value="<?php print_r($password['Password']) ?>" id="txtOldPassword" class="form-control" id="exampleInputEmail1">
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">New Password<span class="text-red">*</span></label>
                      <div class="form-group" id="colorSuccess">
                        <label class="control-label" id="lblSuccess" style="display: none" for="inputSuccess"><i class="fa fa-check"></i></label>
                        <input type="password" class="form-control" name="NewPassword" id="txtNewPassword" oninput="checkNewPassword(this.value);" placeholder="Enter New password">
                        <span id="successMessage" style="display: none" class="help-block"></span>
                      </div>
                      <!-- <input type="password" id="txtNewPassword" class="form-control" id="exampleInputEmail1"> -->
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Confirm Password<span class="text-red">*</span></label>
                      <div class="form-group" id="colorSuccess2">
                        <label class="control-label" id="lblSuccess2" style="display: none" for="txtConfirmPassword"><i class="fa fa-check"></i></label>
                        <input type="password" class="form-control" id="txtConfirmPassword" oninput="checkPasswordMatch(this.value);">
                        <span id="successMessage2" style="display: none" class="help-block"></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputName" class="control-label">Question No. 1</label><br>
                      <select type="text" class="form-control" id="inputName" name="Question1" placeholder="Name">
                        <?php 
                          foreach($securityQuestions as $row)
                          {
                            echo "<option value='".$row['SecurityQuestionId']."'>".$row['Name']."</option>";
                          }
                        ?>
                      </select> 
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputExperience" class="control-label">Answer</label><br>
                      <input class="form-control" required="" name="Answer1" placeholder="Answer for question number 1">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputName" class="control-label">Question No. 2</label><br>
                      <select type="text" class="form-control" name="Question2">
                        <?php 
                          foreach($securityQuestions as $row)
                          {
                            echo "<option value='".$row['SecurityQuestionId']."'>".$row['Name']."</option>";
                          }
                        ?>
                      </select> 
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputExperience" class="control-label">Answer</label><br>
                      <input class="form-control" required="" name="Answer2" placeholder="Answer for question number 2">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputName" class="control-label">Question No. 3</label><br>
                      <select type="text" class="form-control" name="Question3">
                        <?php 
                          foreach($securityQuestions as $row)
                          {
                            echo "<option value='".$row['SecurityQuestionId']."'>".$row['Name']."</option>";
                          }
                        ?>
                      </select> 
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="inputExperience" class="control-label">Answer</label><br>
                      <input class="form-control" required="" placeholder="Answer for question number 3" name="Answer3">
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
    <?php } else { ?> 
    <?php }  ?> 

    <!-- Main content -->
    <section class="content"> 
      <div class="row">
        <!-- 56 -->
        <?php if(in_array('48', $subModule)) { ?>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3><?php print_r(number_format($totalFund['Total'] - $totalExpenses['Total'], 2)) ?></h3>
                <p>Current Fund</p>
              </div>
              <div class="icon">
                <i class="fa fa-tachometer"></i>
              </div>
              <a href="<?php echo base_url(); ?>/home/AddInitialCapital" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        <?php } ?>
        <?php if(in_array('45', $subModule)) { ?>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
              <div class="inner">
                <h3><?php print_r(number_format($dailyIncome['Total'] + $dailyPenalties['Total'], 2)) ?></h3>
                <p>Daily Income</p>
              </div>
              <div class="icon">
                <i class="fa fa-money"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        <?php } ?>
        <?php if(in_array('46', $subModule)) { ?>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
              <div class="inner">
                <h3><?php print_r(number_format($TotalInterest['Total'], 2)) ?></h3>
                <p>Daily Interest Collected</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        <?php } ?>
        <?php if(in_array('47', $subModule)) { ?>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
              <div class="inner">
                <h3><?php print_r($TotalExpense['Total']) ?></h3>
                <p>Daily Expense</p>
              </div>
              <div class="icon">
                <i class="fa fa-dollar"></i>
              </div>
              <a href="<?php echo base_url(); ?>/home/AddExpense" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        <?php } ?>
        <!-- 60 -->
        <?php if(in_array('48', $subModule)) { ?>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3><?php print_r(number_format($dailyDisbursement['Total'], 2)) ?></h3>
                <p>Daily Disbursement</p>
              </div>
              <div class="icon">
                <i class="fa fa-tachometer"></i>
              </div>
              <a href="<?php echo base_url(); ?>/home/AddInitialCapital" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        <?php } ?>
        <?php if(in_array('48', $subModule)) { ?>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3><?php print_r($dailyApprovedLoans['Total']) ?></h3>
                <p>Daily Approved Loans</p>
              </div>
              <div class="icon">
                <i class="fa fa-calendar-check-o"></i>
              </div>
              <a href="<?php echo base_url(); ?>/home/AddExpense" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        <?php } ?>
        <!--  57 -->
        <?php if(in_array('48', $subModule)) { ?>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3><?php print_r($totalActiveLoans['Total']) ?></h3>
                <p>Active Loans</p>
              </div>
              <div class="icon">
                <i class="fa fa-list-alt"></i>
              </div>
              <a href="<?php echo base_url(); ?>/home/ViewLoans" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        <?php } ?>
        <?php if(in_array('44', $subModule)) { ?>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3><?php print_r($totalBorrower['Total']) ?></h3>
                <p>Total Borrowers</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="<?php echo base_url(); ?>/home/borrowers" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        <?php } ?>
        <!-- 58 -->
        <?php if(in_array('48', $subModule)) { ?>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3><?php print_r($totalEmployees['Total']) ?></h3>
                <p>Total Employees</p>
              </div>
              <div class="icon">
                <i class="fa fa-list-alt"></i>
              </div>
              <a href="<?php echo base_url(); ?>/home/addEmployees" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        <?php } ?>
        <!-- 59 -->
        <?php if(in_array('48', $subModule)) { ?>
          <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3><?php print_r($totalUsers['Total']) ?></h3>
                <p>Total Users</p>
              </div>
              <div class="icon">
                <i class="fa fa-list-alt"></i>
              </div>
              <a href="<?php echo base_url(); ?>/home/addUser" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
        <?php } ?>
      </div>

      <!-- <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">POPULATION (AGE)</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="chart">
            <canvas id="barChart" style="height:230px"></canvas>
          </div> -->
          <!-- <div id="drilldowns" style="min-width: 310px; height: 400px; margin: 0 auto"></div> -->
        <!-- </div>
      </div> -->

      <?php if(in_array('49', $subModule)) { ?>
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">DEMOGRAPHICS</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
          </div>
          <div class="box-body">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <!-- <li class="active"><a href="#tabLoanApplications" data-toggle="tab">Loan Applications</a></li> -->
                <li class="active"><a href="#tabAge" data-toggle="tab" title="Age">Age</a></li>
                <li><a href="#tabEd" data-toggle="tab" title="Education" onclick="selectEducationFilter(0)">Education</a></li>
                <li><a href="#tabGE" data-toggle="tab" title="Gender" onclick="selectGenderFilter(0)">Gender</a></li>
                <li><a href="#tabOC" data-toggle="tab" title="Occupation" onclick="selectOccupationFilter(0)">Occupation</a></li>
                <li><a href="#tabIL" data-toggle="tab" title="Income Level" onclick="selectIncomeFilter(0)">Income Level</a></li>
                <li><a href="#tabMS" data-toggle="tab" title="Marital Status" onclick="selectMaritalFilter(0)">Marital Status</a></li>
              </ul>
              <div class="tab-content">
                <div class="active tab-pane" id="tabAge">
                  <h5>AGE</h5>
                  <div class="pull-left">
                    <label>Report Type</label>
                    <select class="form-control" onchange="changeAgeReport(this.value)">
                      <option selected="">Bar Graph</option>
                      <option>Pie Chart</option>
                      <option>Line Graph</option>
                    </select>
                  </div>
                  <div class="pull-right">
                    <select class="form-control" id="yearAge" onchange="selectAgeFilter(this.value)">
                      <?php 
                        foreach ($ageYear as $value) 
                        {
                          $selected = (date("Y") == $value['Year']) ? 'selected' : '';
                          echo '<option '.$selected.'>'.$value['Year'].'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <div class="chart">
                    <div id="divBarChart">
                    </div>
                  </div>
                </div>
                <div class="tab-pane" id="tabEd">
                  <h5>EDUCATION</h5>
                  <div class="pull-right">
                    <select class="form-control" onchange="selectEducationFilter(this.value)" id="yearEducation">
                      <?php 
                        foreach ($educationYear as $value) 
                        {
                          $selected = (date("Y") == $value['Year']) ? 'selected' : '';
                          echo '<option '.$selected.'>'.$value['Year'].'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <br>
                  <hr>
                  <div class="chart">
                    <canvas id="chartEducation" style="height:300px"></canvas>
                  </div>
                </div>
                <div class="tab-pane" id="tabGE">
                  <h5>GENDER</h5>
                  <div class="pull-right">
                    <select class="form-control" id="selectGenderYear" onchange="selectGenderFilter(this.value)">
                      <?php 
                        foreach ($genderYear as $value) 
                        {
                          $selected = (date("Y") == $value['Year']) ? 'selected' : '';
                          echo '<option '.$selected.'>'.$value['Year'].'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <br>
                  <hr>
                  <div class="chart">
                    <canvas id="chartGender" style="height:230px"></canvas>
                  </div>
                </div>
                <div class="tab-pane" id="tabOC">
                  <h5>OCCUPATION</h5>
                  <div class="pull-right">
                    <select class="form-control" id="selectGenderYear" onchange="selectOccupationFilter(this.value)">
                      <?php 
                        foreach ($occupationYear as $value) 
                        {
                          $selected = (date("Y") == $value['Year']) ? 'selected' : '';
                          echo '<option '.$selected.'>'.$value['Year'].'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <br>
                  <hr>
                  <div class="chart">
                    <canvas id="chartOccupation" style="height:50%"></canvas>
                  </div>
                </div>
                <div class="tab-pane" id="tabIL">
                  <h5>INCOME LEVEL</h5>
                  <div class="pull-right">
                    <select class="form-control" id="selectGenderYear" onchange="selectIncomeFilter(this.value)">
                      <?php 
                        foreach ($IncomeYear as $value) 
                        {
                          $selected = (date("Y") == $value['Year']) ? 'selected' : '';
                          echo '<option '.$selected.'>'.$value['Year'].'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <br>
                  <hr>
                  <div class="chart">
                    <canvas id="chartIncome" style="height:250px"></canvas>
                  </div>
                </div>
                <div class="tab-pane" id="tabMS">
                  <h5>MARITAL STATUS</h5>
                  <div class="pull-right">
                    <select class="form-control" onchange="selectMaritalFilter(this.value)">
                      <?php 
                        foreach ($MaritalYear as $value) 
                        {
                          $selected = (date("Y") == $value['Year']) ? 'selected' : '';
                          echo '<option '.$selected.'>'.$value['Year'].'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <br>
                  <hr>
                  <div class="chart">
                    <canvas id="chartMarital" style="height:250px"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>

      <?php if(in_array('50', $subModule)) { ?>
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">HISTORICAL DATA ON LOANS EXTENDED BY THE COMPANY</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="box-body">

            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <!-- <li class="active"><a href="#tabLoanApplications" data-toggle="tab">Loan Applications</a></li> -->
                <li class="active"><a href="#tabBorrower" data-toggle="tab" title="TOTAL NUMBER OF BORROWERS">TOTAL NUMBER OF BORROWERS</a></li>
                <li><a href="#tabLoanTotal" data-toggle="tab" title="TOTAL NUMBER OF LOANS" onclick="selectEducationFilter(0)">TOTAL NUMBER OF LOANS</a></li>
                <li><a href="#tabLoanType" data-toggle="tab" title="TYPE OF LOANS" onclick="selectGenderFilter(0)">TYPE OF LOANS</a></li>
                <li><a href="#tabLoanTotalAmount" data-toggle="tab" title="TOTAL LOAN AMOUNT" onclick="selectOccupationFilter(0)">TOTAL LOAN AMOUNT</a></li>
                <li><a href="#tabTotalTenors" data-toggle="tab" title="TENORS" onclick="selectTotalTenors(0)">TENORS</a></li>
                <li><a href="#tabTotalInterest" data-toggle="tab" title="INTEREST RATES" onclick="getTotalInterest()">INTEREST RATES</a></li>
              </ul>
              <div class="tab-content">
                <div class="active tab-pane" id="tabBorrower">
                  <h5>TOTAL NUMBER OF BORROWERS</h5>
                  <div class="chart">
                    <canvas id="lineChart" style="height:250px"></canvas>
                  </div>
                </div>
                <div class="tab-pane" id="tabLoanTotal">
                  <h5>TOTAL NUMBER OF LOANS</h5>
                  <div class="chart">
                    <canvas id="lineChartTotalLoan" style="height:250px"></canvas>
                  </div>
                </div>
                <div class="tab-pane" id="tabLoanType">
                  <h5>TYPE OF LOANS</h5>
                  <div class="pull-right">
                    <select class="form-control" id="selectGenderYear" onchange="selectLoanTypes(this.value)">
                      <?php 
                        foreach ($LoanYear as $value) 
                        {
                          $selected = (date("Y") == $value['Year']) ? 'selected' : '';
                          echo '<option '.$selected.'>'.$value['Year'].'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <br>
                  <hr>
                  <div class="chart">
                    <canvas id="chartLoans" style="height:200px"></canvas>
                  </div>
                </div>
                <div class="tab-pane" id="tabLoanTotalAmount">
                  <h5>TOTAL LOAN AMOUNT</h5>
                  <div class="chart">
                    <canvas id="lineTotalLoanAmount" style="height:250px"></canvas>
                  </div>
                </div>
                <div class="tab-pane" id="tabTotalTenors">
                  <h5>TENORS</h5>
                  <div class="pull-right">
                    <select class="form-control" onchange="selectTotalTenors(this.value)">
                      <?php 
                        foreach ($TenorYear as $value) 
                        {
                          $selected = (date("Y") == $value['Year']) ? 'selected' : '';
                          echo '<option '.$selected.'>'.$value['Year'].'</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <br>
                  <hr>
                  <div class="chart">
                    <div id="divChartTenors">
                    </div>
                  </div>
                </div>
                <div class="tab-pane" id="tabTotalInterest">
                  <h5>INTEREST COLLECTED</h5>
                  <div class="chart">
                    <div id="divChartInterestRate">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- <h5>GEOGRAPHICAL CONCENTRATION</h5>
            <div class="pull-right">
              <select class="form-control" onchange="selectGeo(this.value)">
                <?php 
                  foreach ($LoanYear as $value) 
                  {
                    $selected = (date("Y") == $value['Year']) ? 'selected' : '';
                    echo '<option '.$selected.'>'.$value['Year'].'</option>';
                  }
                ?>
              </select>
            </div>
            <br>
            <hr>
            <div class="chart">
              <canvas id="chartLoans" style="height:200px"></canvas>
            </div> 
            <hr> -->

          </div>
        </div>
      <?php } ?>

      <!-- <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">List of Users</h3>
        </div>
        <div class="box-body">
          <form name="ApproverDocForm" method="post" id="ApproverDocForm">
            <table id="example1" class="table table-bordered table-hover">
              <thead>
              <tr>
                <th>Employee Number</th>
                <th>Name</th>
                <th>Renewed Password?</th>
                <th>Status</th>
                <th>Date Created</th>
                <th>Date Updated</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </form>
        </div>
      </div> -->

    </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0
  </div>
  <strong>Copyright &copy; 2020 <a href="https://adminlte.io">GIA Tech.</a>.</strong> All rights
  reserved.
</footer>

<div class="loading" style="display: none">Loading&#8230;</div>
<?php $this->load->view('includes/footer'); ?>

<script>
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

// for report types 
  function changeAgeReport(value)
  {
    if(value == 'Bar Graph')
    {
      selectAgeFilter($('#yearAge').val());
    }
    else if(value == 'Pie Graph')
    {
      alert('sdad')
    }
  }

// for password
  var varStatus = 0;
  var varNewPassword = 0;
  
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
          url: "<?php echo base_url();?>" + "/employee_controller/updateStatus",
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
              text: 'User role successfully updated!',
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
    var url = '<?php echo base_url()."datatables_controller/Users/"; ?>';
    UserTable.ajax.url(url).load();
  }

  function checkPasswordMatch(Password)
  {
    var element = document.getElementById("colorSuccess2");
    if($('#txtNewPassword').val() != Password)
    {
      element.classList.remove("has-success");
      element.classList.add("has-error");
      $('#successMessage2').slideDown();
      $('#successMessage2').html('Password does not match');
      varStatus = 0;
    }
    else
    {
      element.classList.remove("has-error");
      element.classList.add("has-success");
      $('#successMessage2').slideDown();
      $('#successMessage2').html('Password matching');
      varStatus = 1;
    }
  }

  function checkNewPassword(Password)
  {
    var element = document.getElementById("colorSuccess2");
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W\_])[A-Za-z\d\W\_]{8,}$/;
    const str = $('#txtNewPassword').val();
    let m;
    if($('#txtConfirmPassword').val() != Password)
    {
      element.classList.remove("has-success");
      element.classList.add("has-error");
      $('#successMessage2').slideDown();
      $('#successMessage2').html('Password does not match');
      varStatus = 0;
    }
    else
    {
      element.classList.remove("has-error");
      element.classList.add("has-success");
      $('#successMessage2').slideDown();
      $('#successMessage2').html('Password matching');
      varStatus = 1;
    }

    if ((m = regex.exec(str)) !== null) {
        // The result can be accessed through the `m`-variable.
        m.forEach((match, groupIndex) => {
          var element = document.getElementById("colorSuccess");
          element.classList.remove("has-error");
          element.classList.add("has-success");
          $('#successMessage').slideDown();
          $('#successMessage').html('Valid Password');
          varNewPassword = 1;
        });
    }
    else
    {
      var element = document.getElementById("colorSuccess");
      element.classList.remove("has-success");
      element.classList.add("has-error");
      $('#successMessage').slideDown();
      $('#successMessage').html('Password must contain a special, numeric and an uppercase character');
      varNewPassword = 0;
    }
  }

// for bar charts
  var d = new Date();
  var varCurrentYear = d.getFullYear();

  function selectAgeFilter(value)
  {
    $.ajax({
      url: "<?php echo base_url(); ?>admin_controller/getAgePopulation",
      type: "POST",
      async: false,
      dataType: "JSON",
      success: function(data) {
        var bracket = [];
        var age = [];

        for(var i in data) {
          bracket.push("Age " + data[i].AgeBracket);
          age.push(data[i].TotalAge);
        }

        var chartdata = {
          labels: bracket,
          datasets : [
            {
              label: 'Age Bracket',
              fillColor           : 'rgb(54, 145, 236)',
              strokeColor         : 'rgb(26, 114, 203)',
              pointColor          : 'rgba(210, 214, 222, 1)',
              data: age
            }
          ]
        };
        $('#divBarChart').html('');
        $('#divBarChart').html('<canvas id="barChart" style="height:230px"></canvas>');
        var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
        var barChart                         = new Chart(barChartCanvas)
        var barChartData                     = chartdata
        var barChartOptions                  = {
          //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
          scaleBeginAtZero        : true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines      : true,
          //String - Colour of the grid lines
          scaleGridLineColor      : 'rgba(0,0,0,.05)',
          //Number - Width of the grid lines
          scaleGridLineWidth      : 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines  : true,
          //Boolean - If there is a stroke on each bar
          barShowStroke           : true,
          //Number - Pixel width of the bar stroke
          barStrokeWidth          : 2,
          //Number - Spacing between each of the X value sets
          barValueSpacing         : 5,
          //Number - Spacing between data sets within X values
          barDatasetSpacing       : 1,
          //Boolean - whether to make the chart responsive
          responsive              : true,
          maintainAspectRatio     : true
        }

        barChartOptions.datasetFill = false
        barChart.Bar(barChartData, barChartOptions)
      },
      error: function(data) {
        console.log(data);
      }
    });
  }

  function selectEducationFilter(value)
  {
    if(value == 0)
    {
      value = varCurrentYear;
    }
    else
    {
      value = value;
    }
    $.ajax({
      url: "<?php echo base_url(); ?>admin_controller/getEducationPopulation",
      type: "POST",
      async: false,
      dataType: "JSON",
      data : {
        yearFilter : value
      },
      success: function(data) {
        var bracket = [];
        var age = [];

        for(var i in data) {
          bracket.push(data[i].Level + ' : ' + data[i].TotalBorrower);
          age.push(data[i].TotalBorrower);
        }

        var chartdata = {
          labels: bracket,
          datasets : [
            {
              label: 'Occupation Population',
              fillColor           : 'rgb(54, 145, 236)',
              strokeColor         : 'rgb(26, 114, 203)',
              pointColor          : 'rgba(210, 214, 222, 1)',
              data: age
            }
          ]
        };

        var barChartCanvas                   = $('#chartEducation').get(0).getContext('2d')
        var barChart                         = new Chart(barChartCanvas)
        var barChartData                     = chartdata
        var barChartOptions                  = {
          //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
          scaleBeginAtZero        : true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines      : true,
          //String - Colour of the grid lines
          scaleGridLineColor      : 'rgba(0,0,0,.05)',
          //Number - Width of the grid lines
          scaleGridLineWidth      : 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines  : true,
          //Boolean - If there is a stroke on each bar
          barShowStroke           : true,
          //Number - Pixel width of the bar stroke
          barStrokeWidth          : 2,
          //Number - Spacing between each of the X value sets
          barValueSpacing         : 5,
          //Number - Spacing between data sets within X values
          barDatasetSpacing       : 1,
          //Boolean - whether to make the chart responsive
          responsive              : true,
          maintainAspectRatio     : true,
        }

        barChartOptions.datasetFill = false
        barChart.Bar(barChartData, barChartOptions)
      },
      error: function(data) {
        console.log(data);
      }
    });
  }

  function selectGenderFilter(value)
  {
    if(value == 0)
    {
      value = varCurrentYear;
    }
    else
    {
      value = value;
    }

    $.ajax({
      url: "<?php echo base_url(); ?>admin_controller/getGenderPopulation",
      type: "POST",
      async: false,
      dataType: "JSON",
      data : {
        yearFilter : value
      },
      success: function(data) {
        var bracket = [];
        var age = [];

        for(var i in data) {
          bracket.push(data[i].name);
          age.push(data[i].TotalGender);
        }

        var chartdata = {
          labels: bracket,
          datasets : [
            {
              label: 'Gender',
              fillColor           : 'rgb(54, 145, 236)',
              strokeColor         : 'rgb(26, 114, 203)',
              pointColor          : 'rgba(210, 214, 222, 1)',
              data: age
            }
          ]
        };

        var barChartCanvas                   = $('#chartGender').get(0).getContext('2d')
        var barChart                         = new Chart(barChartCanvas)
        var barChartData                     = chartdata
        var barChartOptions                  = {
          //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
          scaleBeginAtZero        : true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines      : true,
          //String - Colour of the grid lines
          scaleGridLineColor      : 'rgba(0,0,0,.05)',
          //Number - Width of the grid lines
          scaleGridLineWidth      : 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines  : true,
          //Boolean - If there is a stroke on each bar
          barShowStroke           : true,
          //Number - Pixel width of the bar stroke
          barStrokeWidth          : 2,
          //Number - Spacing between each of the X value sets
          barValueSpacing         : 5,
          //Number - Spacing between data sets within X values
          barDatasetSpacing       : 1,
          //Boolean - whether to make the chart responsive
          responsive              : true,
          maintainAspectRatio     : true
        }

        barChartOptions.datasetFill = false
        barChart.Bar(barChartData, barChartOptions)
      },
      error: function(data) {
        console.log(data);
      }
    });
  }

  function selectOccupationFilter(value)
  {
    if(value == 0)
    {
      value = varCurrentYear;
    }
    else
    {
      value = value;
    }
    $.ajax({
      url: "<?php echo base_url(); ?>admin_controller/getOccupationPopulation",
      type: "POST",
      async: false,
      dataType: "JSON",
      data : {
        yearFilter : value
      },
      success: function(data) {
        var bracket = [];
        var age = [];

        for(var i in data) {
          bracket.push(data[i].Occupation);
          age.push(data[i].TotalBorrowers);
        }

        var chartdata = {
          labels: bracket,
          datasets : [
            {
              label: 'Occupation',
              fillColor           : 'rgb(54, 145, 236)',
              strokeColor         : 'rgb(26, 114, 203)',
              pointColor          : 'rgba(210, 214, 222, 1)',
              data: age
            }
          ]
        };

        var barChartCanvas                   = $('#chartOccupation').get(0).getContext('2d')
        var barChart                         = new Chart(barChartCanvas)
        var barChartData                     = chartdata
        var barChartOptions                  = {
          //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
          scaleBeginAtZero        : true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines      : true,
          //String - Colour of the grid lines
          scaleGridLineColor      : 'rgba(0,0,0,.05)',
          //Number - Width of the grid lines
          scaleGridLineWidth      : 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines  : true,
          //Boolean - If there is a stroke on each bar
          barShowStroke           : true,
          //Number - Pixel width of the bar stroke
          barStrokeWidth          : 2,
          //Number - Spacing between each of the X value sets
          barValueSpacing         : 5,
          //Number - Spacing between data sets within X values
          barDatasetSpacing       : 1,
          //Boolean - whether to make the chart responsive
          responsive              : true,
          maintainAspectRatio     : true
        }

        barChartOptions.datasetFill = false
        barChart.Bar(barChartData, barChartOptions)
      },
      error: function(data) {
        console.log(data);
      }
    });
  }

  function selectIncomeFilter(value)
  {
    if(value == 0)
    {
      value = varCurrentYear;
    }
    else
    {
      value = value;
    }
    $.ajax({
      url: "<?php echo base_url(); ?>admin_controller/getIncomeLevelPopulation",
      type: "POST",
      async: false,
      dataType: "JSON",
      data : {
        yearFilter : value
      },
      success: function(data) {
        var bracket = [];
        var age = [];

        for(var i in data) {
          bracket.push(data[i].IncomeLevel);
          age.push(data[i].TotalBorrowers);
        }

        var chartdata = {
          labels: bracket,
          datasets : [
            {
              label: 'Income Level',
              fillColor           : 'rgb(54, 145, 236)',
              strokeColor         : 'rgb(26, 114, 203)',
              pointColor          : 'rgba(210, 214, 222, 1)',
              data: age
            }
          ]
        };

        var barChartCanvas                   = $('#chartIncome').get(0).getContext('2d')
        var barChart                         = new Chart(barChartCanvas)
        var barChartData                     = chartdata
        var barChartOptions                  = {
          //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
          scaleBeginAtZero        : true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines      : true,
          //String - Colour of the grid lines
          scaleGridLineColor      : 'rgba(0,0,0,.05)',
          //Number - Width of the grid lines
          scaleGridLineWidth      : 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines  : true,
          //Boolean - If there is a stroke on each bar
          barShowStroke           : true,
          //Number - Pixel width of the bar stroke
          barStrokeWidth          : 2,
          //Number - Spacing between each of the X value sets
          barValueSpacing         : 5,
          //Number - Spacing between data sets within X values
          barDatasetSpacing       : 1,
          //Boolean - whether to make the chart responsive
          responsive              : true,
          maintainAspectRatio     : true
        }

        barChartOptions.datasetFill = false
        barChart.Bar(barChartData, barChartOptions)
      },
      error: function(data) {
        console.log(data);
      }
    });
  }

  function selectMaritalFilter(value)
  {
    if(value == 0)
    {
      value = varCurrentYear;
    }
    else
    {
      value = value;
    }
    $.ajax({
      url: "<?php echo base_url(); ?>admin_controller/getMaritalStatusPopulation",
      type: "POST",
      async: false,
      dataType: "JSON",
      data : {
        yearFilter : value
      },
      success: function(data) {
        var bracket = [];
        var age = [];

        for(var i in data) {
          bracket.push(data[i].Name);
          age.push(data[i].TotalBorrowers);
        }

        var chartdata = {
          labels: bracket,
          datasets : [
            {
              label: 'Marital Status',
              fillColor           : 'rgb(54, 145, 236)',
              strokeColor         : 'rgb(26, 114, 203)',
              pointColor          : 'rgba(210, 214, 222, 1)',
              data: age
            }
          ]
        };

        var barChartCanvas                   = $('#chartMarital').get(0).getContext('2d')
        var barChart                         = new Chart(barChartCanvas)
        var barChartData                     = chartdata
        var barChartOptions                  = {
          //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
          scaleBeginAtZero        : true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines      : true,
          //String - Colour of the grid lines
          scaleGridLineColor      : 'rgba(0,0,0,.05)',
          //Number - Width of the grid lines
          scaleGridLineWidth      : 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines  : true,
          //Boolean - If there is a stroke on each bar
          barShowStroke           : true,
          //Number - Pixel width of the bar stroke
          barStrokeWidth          : 2,
          //Number - Spacing between each of the X value sets
          barValueSpacing         : 5,
          //Number - Spacing between data sets within X values
          barDatasetSpacing       : 1,
          //Boolean - whether to make the chart responsive
          responsive              : true,
          maintainAspectRatio     : true
        }

        barChartOptions.datasetFill = false
        barChart.Bar(barChartData, barChartOptions)
      },
      error: function(data) {
        console.log(data);
      }
    });
  }

  function selectLoanTypes(value)
  {
    if(value == 0)
    {
      value = varCurrentYear;
    }
    else
    {
      value = value;
    }
    $.ajax({
      url: "<?php echo base_url(); ?>admin_controller/getLoanType",
      type: "POST",
      async: false,
      dataType: "JSON",
      data : {
        yearFilter : value
      },
      success: function(data) {
        var bracket = [];
        var age = [];

        for(var i in data) {
          bracket.push(data[i].Name);
          age.push(data[i].Total);
        }

        var chartdata = {
          labels: bracket,
          datasets : [
            {
              label: 'Marital Status',
              fillColor           : 'rgb(54, 145, 236)',
              strokeColor         : 'rgb(26, 114, 203)',
              pointColor          : 'rgba(210, 214, 222, 1)',
              data: age
            }
          ]
        };

        var barChartCanvas                   = $('#chartLoans').get(0).getContext('2d')
        var barChart                         = new Chart(barChartCanvas)
        var barChartData                     = chartdata
        var barChartOptions                  = {
          //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
          scaleBeginAtZero        : true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines      : true,
          //String - Colour of the grid lines
          scaleGridLineColor      : 'rgba(0,0,0,.05)',
          //Number - Width of the grid lines
          scaleGridLineWidth      : 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines  : true,
          //Boolean - If there is a stroke on each bar
          barShowStroke           : true,
          //Number - Pixel width of the bar stroke
          barStrokeWidth          : 2,
          //Number - Spacing between each of the X value sets
          barValueSpacing         : 5,
          //Number - Spacing between data sets within X values
          barDatasetSpacing       : 1,
          //Boolean - whether to make the chart responsive
          responsive              : true,
          maintainAspectRatio     : true
        }

        barChartOptions.datasetFill = false
        barChart.Bar(barChartData, barChartOptions)
      },
      error: function(data) {
        console.log(data);
      }
    });
  }

  function selectGeo(value)
  {
  }

  function selectTotalTenors(value)
  {
    if(value == 0)
    {
      value = varCurrentYear;
    }
    else
    {
      value = value;
    }

    $.ajax({
      url: "<?php echo base_url(); ?>admin_controller/getTenors",
      type: "POST",
      async: false,
      dataType: "JSON",
      data : {
        yearFilter : value
      },
      success: function(data) {
        var bracket = [];
        var age = [];

        for(var i in data) {
          bracket.push(data[i].TermType);
          age.push(data[i].TermTypes);
        }

        var chartdata = {
          labels: bracket,
          datasets : [
            {
              label: 'Tenors',
              fillColor           : 'rgb(54, 145, 236)',
              strokeColor         : 'rgb(26, 114, 203)',
              data: age
            }
          ]
        };

        $('#divChartTenors').html('');
        $('#divChartTenors').html('<canvas id="chartTenors" style="height:100px"></canvas>');

        var barChartCanvas                   = $('#chartTenors').get(0).getContext('2d')
        var barChart                         = new Chart(barChartCanvas)        
        var barChartData                     = chartdata
        var barChartOptions                  = {
          //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
          scaleBeginAtZero        : true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines      : true,
          //String - Colour of the grid lines
          scaleGridLineColor      : 'rgba(0,0,0,.05)',
          //Number - Width of the grid lines
          scaleGridLineWidth      : 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines  : true,
          //Boolean - If there is a stroke on each bar
          barShowStroke           : true,
          //Number - Pixel width of the bar stroke
          barStrokeWidth          : 2,
          //Number - Spacing between each of the X value sets
          barValueSpacing         : 5,
          //Number - Spacing between data sets within X values
          barDatasetSpacing       : 1,
          //Boolean - whether to make the chart responsive
          responsive              : true,
          maintainAspectRatio     : true
        }

        barChartOptions.datasetFill = false
        barChart.Bar(barChartData, barChartOptions);
      },
      error: function(data) {
        console.log(data);
      }
    });
  }

  function getTotalInterest()
  {
    $.ajax({
      url: "<?php echo base_url(); ?>admin_controller/getTotalInterest",
      type: "POST",
      async: false,
      dataType: "JSON",
      success: function(data) {
        var userid = [];
        var facebook_follower = [];
        var total_label = [];

        for(var i in data) {
          userid.push(data[i].Year);
          facebook_follower.push(data[i].Total);
        }

        $('#divChartInterestRate').html('');
        $('#divChartInterestRate').html('<canvas id="lineTotalInterest" style="height:100px"></canvas>');

        var chartdata = {
          labels: userid,
          datasets: [
            {
              fill: false,
              lineTension: 0.1,
              fillColor           : 'rgba(60,141,188,0.9)',
              strokeColor         : 'rgba(60,141,188,0.8)',
              pointColor          : '#3b8bba',
              pointStrokeColor    : 'rgba(60,141,188,1)',
              pointHighlightFill  : '#fff',
              pointHighlightStroke: 'rgba(60,141,188,1)',
              data: facebook_follower
            }
          ]
        };

        var areaChartOptions = {
          //Boolean - If we should show the scale at all
          showScale               : true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines      : false,
          //String - Colour of the grid lines
          scaleGridLineColor      : 'rgb(89, 97, 247)',
          //Number - Width of the grid lines
          scaleGridLineWidth      : 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines  : true,
          //Boolean - Whether the line is curved between points
          bezierCurve             : true,
          //Number - Tension of the bezier curve between points
          bezierCurveTension      : 0.3,
          //Boolean - Whether to show a dot for each point
          pointDot                : false,
          //Number - Radius of each point dot in pixels
          pointDotRadius          : 4,
          //Number - Pixel width of point dot stroke
          pointDotStrokeWidth     : 1,
          //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
          pointHitDetectionRadius : 20,
          //Boolean - Whether to show a stroke for datasets
          datasetStroke           : true,
          //Number - Pixel width of dataset stroke
          datasetStrokeWidth      : 2,
          //Boolean - Whether to fill the dataset with a color
          datasetFill             : true,
          //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio     : true,
          //Boolean - whether to make the chart responsive to window resizing
          responsive              : true
        }

        var lineChartCanvas          = $('#lineTotalInterest').get(0).getContext('2d')
        var lineChart                = new Chart(lineChartCanvas)
        var lineChartOptions         = areaChartOptions
        lineChartOptions.datasetFill = false
        lineChart.Line(chartdata, lineChartOptions)
      },
      error: function(data) {
        console.log(data);
      }
    });
  }

  // function seslectOtherCharges(value)
  // {
  //   $.ajax({
  //     url: "<?php echo base_url(); ?>admin_controller/getChargesTotal",
  //     type: "POST",
  //     async: false,
  //     dataType: "JSON",
  //     data : {
  //       yearFilter : value
  //     },
  //     success: function(data) {
  //       var bracket = [];
  //       var age = [];

  //       for(var i in data) {
  //         bracket.push(data[i].Name);
  //         age.push(data[i].Total);
  //       }

  //       var chartdata = {
  //         labels: bracket,
  //         datasets : [
  //           {
  //             label: 'Marital Status',
  //             fillColor           : 'rgb(54, 145, 236)',
  //             strokeColor         : 'rgb(26, 114, 203)',
  //             pointColor          : 'rgba(210, 214, 222, 1)',
  //             data: age
  //           }
  //         ]
  //       };

  //       var barChartCanvas                   = $('#chartLoans').get(0).getContext('2d')
  //       var barChart                         = new Chart(barChartCanvas)
  //       var barChartData                     = chartdata
  //       var barChartOptions                  = {
  //         //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
  //         scaleBeginAtZero        : true,
  //         //Boolean - Whether grid lines are shown across the chart
  //         scaleShowGridLines      : true,
  //         //String - Colour of the grid lines
  //         scaleGridLineColor      : 'rgba(0,0,0,.05)',
  //         //Number - Width of the grid lines
  //         scaleGridLineWidth      : 1,
  //         //Boolean - Whether to show horizontal lines (except X axis)
  //         scaleShowHorizontalLines: true,
  //         //Boolean - Whether to show vertical lines (except Y axis)
  //         scaleShowVerticalLines  : true,
  //         //Boolean - If there is a stroke on each bar
  //         barShowStroke           : true,
  //         //Number - Pixel width of the bar stroke
  //         barStrokeWidth          : 2,
  //         //Number - Spacing between each of the X value sets
  //         barValueSpacing         : 5,
  //         //Number - Spacing between data sets within X values
  //         barDatasetSpacing       : 1,
  //         //Boolean - whether to make the chart responsive
  //         responsive              : true,
  //         maintainAspectRatio     : true
  //       }

  //       barChartOptions.datasetFill = false
  //       barChart.Bar(barChartData, barChartOptions)
  //     },
  //     error: function(data) {
  //       console.log(data);
  //     }
  //   });
  // }

  $(function () {
    // selectPieChartAge();
    var d = new Date();
    selectLoanTypes(d.getFullYear());
    changeAgeReport('Bar Graph');
    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/datatables_controller/Users/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "EmployeeNumber" }
                    , { data: "Name" }
                    , {
                      data: "IsNew", "render": function (data, type, row) {
                        if(row.IsNew == 1){
                          return "<span class='badge bg-warning'>No</span>";
                        }
                        else if(row.IsNew == 0){
                          return "<span class='badge bg-green'>Yes</span>";
                        }
                        else{
                          return "N/A";
                        }
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        if(row.StatusId == 1){
                          return "<span class='badge bg-green'>Active</span>";
                        }
                        else if(row.StatusId == 2){
                          return "<span class='badge bg-red'>Deactivated</span>";
                        }
                        else{
                          return "N/A";
                        }
                      }
                    },
                    { data: "DateCreated" }, 
                    { data: "DateUpdated" }, 
                    {
                      data: "StatusId", "render": function (data, type, row) {
                      if(row.StatusId == 1){
                          return '<a onclick="confirm(\'Are you sure you want to deactivate this user?\', \''+row.UserRoleId+'\', 1)" class="btn btn-danger" title="Deactivate"><span class="fa fa-close"></span></a> <a onclick="confirm(\'Are you sure you want to reset this user password?\', \''+row.UserRoleId+'\', 3)" class="btn btn-warning" title="Reset Password"><span class="fa fa-refresh"></span></a>';
                        }
                        else if(row.StatusId == 2){
                          return '<a onclick="confirm(\'Are you sure you want to re-activate this user?\', \''+row.UserRoleId+'\', 2)" class="btn btn-success" title="Deactivate"><span class="fa fa-refresh"></span></a>';
                        }
                        else{
                          return "N/A";
                        }
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[3, "asc"]]
    });

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
    $('#modalNewPassword').modal('show')

    // TOTAL NUMBER OF BORROWERS
      $.ajax({
        url: "<?php echo base_url(); ?>admin_controller/getTotalBorrowers",
        type: "POST",
        async: false,
        dataType: "JSON",
        success: function(data) {
          var userid = [];
          var facebook_follower = [];

          for(var i in data) {
            userid.push(data[i].DateCreated);
            facebook_follower.push(data[i].TotalBorrowers);
          }


          var chartdata = {
            labels: userid,
            datasets: [
              {
                fill: false,
                lineTension: 0.1,
                fillColor           : 'rgba(210, 214, 222, 1)',
                strokeColor         : 'rgba(210, 214, 222, 1)',
                pointColor          : 'rgba(210, 214, 222, 1)',
                pointStrokeColor    : '#c1c7d1',
                pointHighlightFill  : '#fff',
                pointHighlightStroke: 'rgba(220,220,220,1)',
                data: facebook_follower
              }
            ]
          };

          var areaChartOptionssss = {
            //Boolean - If we should show the scale at all
            showScale               : true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines      : false,
            //String - Colour of the grid lines
            scaleGridLineColor      : 'rgba(0,0,0,.05)',
            //Number - Width of the grid lines
            scaleGridLineWidth      : 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines  : true,
            //Boolean - Whether the line is curved between points
            bezierCurve             : true,
            //Number - Tension of the bezier curve between points
            bezierCurveTension      : 0.3,
            //Boolean - Whether to show a dot for each point
            pointDot                : false,
            //Number - Radius of each point dot in pixels
            pointDotRadius          : 4,
            //Number - Pixel width of point dot stroke
            pointDotStrokeWidth     : 1,
            //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
            pointHitDetectionRadius : 20,
            //Boolean - Whether to show a stroke for datasets
            datasetStroke           : true,
            //Number - Pixel width of dataset stroke
            datasetStrokeWidth      : 2,
            //Boolean - Whether to fill the dataset with a color
            datasetFill             : true,
            //String - A legend template
            legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
            //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio     : true,
            //Boolean - whether to make the chart responsive to window resizing
            responsive              : true
          }

          var lineChartCanvas          = $('#lineChart').get(0).getContext('2d')
          var lineChart                = new Chart(lineChartCanvas)
          var lineChartOptions         = areaChartOptionssss
          lineChartOptions.datasetFill = false
          lineChart.Line(chartdata, lineChartOptions)
        },
        error: function(data) {
          console.log(data);
        }
      });
    // TOTAL LOANS
      $.ajax({
        url: "<?php echo base_url(); ?>admin_controller/getTotalLoans",
        type: "POST",
        async: false,
        dataType: "JSON",
        success: function(data) {
          var userid = [];
          var facebook_follower = [];

          for(var i in data) {
            userid.push(data[i].DateCreated);
            facebook_follower.push(data[i].Total);
          }


          var chartdata = {
            labels: userid,
            datasets: [
              {
                fill: false,
                lineTension: 0.1,
                backgroundColor: "rgba(59, 89, 152, 0.75)",
                data: facebook_follower
              }
            ]
          };

          var areaChartOptions = {
            //Boolean - If we should show the scale at all
            showScale               : true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines      : false,
            //String - Colour of the grid lines
            scaleGridLineColor      : 'rgba(0,0,0,.05)',
            //Number - Width of the grid lines
            scaleGridLineWidth      : 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines  : true,
            //Boolean - Whether the line is curved between points
            bezierCurve             : true,
            //Number - Tension of the bezier curve between points
            bezierCurveTension      : 0.3,
            //Boolean - Whether to show a dot for each point
            pointDot                : false,
            //Number - Radius of each point dot in pixels
            pointDotRadius          : 4,
            //Number - Pixel width of point dot stroke
            pointDotStrokeWidth     : 1,
            //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
            pointHitDetectionRadius : 20,
            //Boolean - Whether to show a stroke for datasets
            datasetStroke           : true,
            //Number - Pixel width of dataset stroke
            datasetStrokeWidth      : 2,
            //Boolean - Whether to fill the dataset with a color
            datasetFill             : true,
            //String - A legend template
            legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
            //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio     : true,
            //Boolean - whether to make the chart responsive to window resizing
            responsive              : true
          }

          var lineChartCanvas          = $('#lineChartTotalLoan').get(0).getContext('2d')
          var lineChart                = new Chart(lineChartCanvas)
          var lineChartOptions         = areaChartOptions
          lineChartOptions.datasetFill = false
          lineChart.Line(chartdata, lineChartOptions)
        },
        error: function(data) {
          console.log(data);
        }
      });
    // TOTAL LOAN AMOUNT
      $.ajax({
        url: "<?php echo base_url(); ?>admin_controller/getTotalLoanAmount",
        type: "POST",
        async: false,
        dataType: "JSON",
        success: function(data) {
          var userid = [];
          var facebook_follower = [];
          var total_label = [];

          for(var i in data) {
            userid.push(data[i].DateCreated);
            facebook_follower.push(data[i].Total);
          }


          var chartdata = {
            labels: userid,
            datasets: [
              {
                fill: false,
                lineTension: 0.1,
                backgroundColor: "rgba(59, 89, 152, 0.75)",
                data: facebook_follower
              }
            ]
          };

          var areaChartOptions = {
            //Boolean - If we should show the scale at all
            showScale               : true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines      : false,
            //String - Colour of the grid lines
            scaleGridLineColor      : 'rgba(0,0,0,.05)',
            //Number - Width of the grid lines
            scaleGridLineWidth      : 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines  : true,
            //Boolean - Whether the line is curved between points
            bezierCurve             : true,
            //Number - Tension of the bezier curve between points
            bezierCurveTension      : 0.3,
            //Boolean - Whether to show a dot for each point
            pointDot                : false,
            //Number - Radius of each point dot in pixels
            pointDotRadius          : 4,
            //Number - Pixel width of point dot stroke
            pointDotStrokeWidth     : 1,
            //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
            pointHitDetectionRadius : 20,
            //Boolean - Whether to show a stroke for datasets
            datasetStroke           : true,
            //Number - Pixel width of dataset stroke
            datasetStrokeWidth      : 2,
            //Boolean - Whether to fill the dataset with a color
            datasetFill             : true,
            //String - A legend template
            legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
            //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio     : true,
            //Boolean - whether to make the chart responsive to window resizing
            responsive              : true
          }

          var lineChartCanvas          = $('#lineTotalLoanAmount').get(0).getContext('2d')
          var lineChart                = new Chart(lineChartCanvas)
          var lineChartOptions         = areaChartOptions
          lineChartOptions.datasetFill = false
          lineChart.Line(chartdata, lineChartOptions)
        },
        error: function(data) {
          console.log(data);
        }
      });
    
    // Create the chart
      var brands = {}, brandsData = [], versions = {}, drilldownSeries = [];

      $.ajax({
        url: "<?php echo base_url(); ?>admin_controller/getAgePopulation",
        type: "POST",
        async: false,
        dataType: "JSON",
        success: function(data) {
          $.each(brands, function (name, y) {
            brandsData.push({
               name: name,
               y: y,
               drilldown: versions[name] ? name : null
            });
          });
        },
        error: function(data) {
          console.log(data);
        }
      });

      // Highcharts.chart('drilldowns', {
      //     chart: {
      //         type: 'column',
      //         events: {
      //             drilldown: function (e) {
      //                 if (!e.seriesOptions) {
      //                     var chart = this,
      //                         drilldowns = {
      //                             Animals: {
      //                                 name: 'Animals',
      //                                 data: [
      //                                     ['Cows', 2],
      //                                     ['Sheep', 3]
      //                                 ]
      //                             },
      //                             Fruits: {
      //                                 name: 'Fruits',
      //                                 data: [
      //                                     ['Apples', 5],
      //                                     ['Oranges', 7],
      //                                     ['Bananas', 2]
      //                                 ]
      //                             },
      //                             Cars: {
      //                                 name: 'Cars',
      //                                 data: [
      //                                     ['Toyota', 1],
      //                                     ['Volkswagen', 2],
      //                                     ['Opel', 5]
      //                                 ]
      //                             }
      //                         },
      //                         series = drilldowns[e.point.name];

      //                     // Show the loading label
      //                     chart.showLoading('Simulating Ajax ...');

      //                     setTimeout(function () {
      //                         chart.hideLoading();
      //                         chart.addSeriesAsDrilldown(e.point, series);
      //                     }, 1000);
      //                 }

      //             }
      //         }
      //     },
      //     title: {
      //         text: 'Async drilldown'
      //     },
      //     xAxis: {
      //         type: 'category'
      //     },

      //     legend: {
      //         enabled: false
      //     },

      //     plotOptions: {
      //         series: {
      //             borderWidth: 0,
      //             dataLabels: {
      //                 enabled: true
      //             }
      //         }
      //     },

      //     series: [{
      //         name: 'Things',
      //         colorByPoint: true,
      //         data: [{
      //             name: 'Animals',
      //             y: 5,
      //             drilldown: true
      //         }, {
      //             name: 'Fruits',
      //             y: 2,
      //             drilldown: true
      //         }, {
      //             name: 'Cars',
      //             y: 4,
      //             drilldown: true
      //         }]
      //     }],

      //     drilldown: {
      //         series: []
      //     }
      // });


     // Highcharts.data({
     //    csv: document.getElementById('tsv').innerHTML,
     //    itemDelimiter: '\t',
     //    parsed: function (columns) {
     //       var brands = {}, brandsData = [], versions = {}, drilldownSeries = [];
           
     //   // Parse percentage strings
     //       columns[1] = $.map(columns[1], function (value) {
     //          if (value.indexOf('%') === value.length - 1) {
     //             value = parseFloat(value);
     //          }
     //          return value;
     //       });

     //       $.each(columns[0], function (i, name) {
     //          var brand, version;

     //          if (i > 0) {

     //             // Remove special edition notes
     //             name = name.split(' -')[0];

     //             // Split into brand and version
     //             version = name.match(/([0-9]+[\.0-9x]*)/);
     //             if (version) {
     //                version = version[0];
     //             }
     //             brand = name.replace(version, '');

     //             // Create the main data
     //             if (!brands[brand]) {
     //                brands[brand] = columns[1][i];
     //             } else {
     //                brands[brand] += columns[1][i];
     //             }

     //             // Create the version data
     //             if (version !== null) {
     //                if (!versions[brand]) {
     //                   versions[brand] = [];
     //                }
     //                versions[brand].push(['v' + version, columns[1][i]]);
     //             }
     //          }

     //       });

     //       $.each(brands, function (name, y) {
     //          brandsData.push({
     //             name: name,
     //             y: y,
     //             drilldown: versions[name] ? name : null
     //          });
     //       });
     //       $.each(versions, function (key, value) {
     //          drilldownSeries.push({
     //              name: key,
     //              id: key,
     //              data: value
     //          });
     //       }); 
   
     //       var chart = {
     //          type: 'column'
     //       };
     //       var title = {
     //          text: '2013 年 11 月份 浏览器市场占有率'   
     //       };    
     //       var subtitle = {
     //          text: '点击条形图查看具体月份 Source: w3big.com.'
     //       };
     //       var xAxis = {
     //          type: 'category'      
     //       };
     //       var yAxis ={
     //          title: {
     //            text: '市场占有率百分比'
     //          }
     //       };  
     //       var tooltip = {
     //          headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
     //          pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
     //       };   
     //       var credits = {
     //          enabled: false
     //       };
     //       var series= [{
     //          name: 'Brands',
     //          colorByPoint: true,
     //          data: brandsData
     //       }];
     //       var drilldown= {
     //          series: drilldownSeries
     //       }   
        
     //       var json = {};   
     //       json.chart = chart; 
     //       json.title = title;   
     //       json.subtitle = subtitle;
     //       json.xAxis = xAxis;
     //       json.yAxis = yAxis;   
     //       json.tooltip = tooltip;   
     //       json.credits = credits;
     //       json.series = series;
     //       json.drilldown = drilldown;
     //       $('#container').highcharts(json);
     //   }
     // });


  })
</script>