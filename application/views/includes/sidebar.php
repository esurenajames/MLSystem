
  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <?php
            if(!isset($profilePicture[0]['FileName']))
            {
              echo '<img src="'.base_url().'borrowerpicture/default.gif" class="profile-user-img img-responsive img-circle" alt="User Image" style="width: 100px">';
            }
            else
            {
              echo '<img src="'.base_url().'profilepicture/'.$profilePicture[0]["FileName"].'" class="profile-user-img img-responsive img-circle" alt="User Image" style="width: 100px">';
            }
          ?>
        </div>
        <div class="pull-left info">
          <p>Welcome, <br> <?php print_r($this->session->userdata('Name')) ?> <br> <small><?php print_r($this->session->userdata('Branch') . ' Branch') ?></small> </p>
        </div>
          <br>
          <br>
          <br>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>

        <li <?php if($sidebar == 'Dashboard' && $sidebarMenu == 'Dashboard'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/Dashboard"><i class="fa fa-dashboard"></i> Dashboard</a></li>

        <?php if(in_array('3', $access)) { ?>
          <li <?php if($sidebar == 'Loans'){echo 'class="treeview active"';} else {echo 'class="treeview"';}?>>
            <a href="#">
              <i class="fa fa-file-text"></i> <span>Loan Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <?php if(in_array('8', $subModule)) { ?>
                <li <?php if($sidebar == 'Loans' && $sidebarMenu == 'View Loans'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/ViewLoans"><i class="fa fa-circle-o"></i>View Loans</a></li>
              <?php } ?>
              <?php if(in_array('9', $subModule)) { ?>
                <li <?php if($sidebar == 'Loans' && $sidebarMenu == 'Loan Application'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/LoanApplication"><i class="fa fa-circle-o"></i>Create Loan Application</a></li>
                <li <?php if($sidebar == 'Loans' && $sidebarMenu == 'Loan Calculator'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/LoanCalculator"><i class="fa fa-circle-o"></i>Loan Calculator</a></li>
              <?php } ?>
              <?php if(in_array('16', $subModule)) { ?>
                <li <?php if($sidebar == 'Loans' && $sidebarMenu == 'Loan Approvals'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/LoanApprovals"><i class="fa fa-circle-o"></i>Approvals</a></li>
              <?php } ?>
              <?php if(in_array('29', $subModule)) { ?>
                <li <?php if($sidebar == 'Loans' && $sidebarMenu == 'RepaymentCycle'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddRepaymentCycle"><i class="fa fa-circle-o"></i>Repayment Cycles</a></li>
              <?php } ?>
              <?php if(in_array('36', $subModule)) { ?>
                <li <?php if($sidebar == 'Loans' && $sidebarMenu == 'Purposes'){echo 'class="active"';}?> ><a href="<?php echo base_url();?>home/AddPurpose"><i class="fa fa-circle-o"></i>Purposes</a></li>
              <?php } ?>
              <?php if(in_array('37', $subModule)) { ?>
                <li <?php if($sidebar == 'Loans' && $sidebarMenu == 'LoanTypes'){echo 'class="active"';}?> ><a href="<?php echo base_url();?>home/AddLoanType"><i class="fa fa-circle-o"></i>Loan Types</a></li>
              <?php } ?>
              <?php if(in_array('38', $subModule)) { ?>
                <li <?php if($sidebar == 'Loans' && $sidebarMenu == 'LoanStatus'){echo 'class="active"';}?> ><a href="<?php echo base_url();?>home/AddLoanStatus"><i class="fa fa-circle-o"></i>Loan Status</a></li>
              <?php } ?>
              <?php if(in_array('40', $subModule)) { ?>
                <li <?php if($sidebar == 'Loans' && $sidebarMenu == 'Conditional'){echo 'class="active"';}?> ><a href="<?php echo base_url();?>home/AddConditional"><i class="fa fa-circle-o"></i>Additional Charges</a></li>
              <?php } ?>
            </ul>
          </li>
        <?php } ?>

        <?php if(in_array('7', $access)) { ?>
          <li <?php if($sidebar == 'Asset Management' && $sidebarMenu == 'Asset Management'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddAssetManagement"><i class="fa fa-archive"></i> Asset Management</a></li>
        <?php } ?>
        <?php if(in_array('2', $access)) { ?>
          <li <?php if($sidebar == 'Collection Management' && $sidebarMenu == 'Collection Management'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/collectionManagement"><i class="fa fa-bank"></i> Collection Management</a></li>
        <?php } ?>
        <!-- <?php if(in_array('8', $access)) { ?>
          <li <?php if($sidebar == 'BorrowerManagement' && $sidebarMenu == 'BorrowerManagement'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/borrowers"><i class="fa fa-users"></i>Borrower Management</a></li>
        <?php } ?> -->
        <!-- <?php if(in_array('1', $access)) { ?>
          <li <?php if($sidebar == 'EmployeeManagement' && $sidebarMenu == 'EmployeeManagement'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/addEmployees"><i class="fa fa-black-tie"></i>Employee Management</a></li>
        <?php } ?> -->


        <?php if(in_array('1', $access)) { ?>
          <li <?php if($sidebar == 'BorrowerManagement'){echo 'class="treeview active"';} else {echo 'class="treeview"';}?>>
            <a href="#">
              <i class="fa fa-users"></i> <span>Borrower Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <?php if(in_array('1', $subModule)) { ?>
                <li <?php if($sidebar == 'BorrowerManagement' && $sidebarMenu == 'BorrowerManagement'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/borrowers"><i class="fa fa-circle-o"></i>Borrowers</a></li>
              <?php } ?>
              <?php if(in_array('26', $subModule)) { ?>
                <li <?php if($sidebar == 'BorrowerManagement' && $sidebarMenu == 'Industries'){echo 'class="active"';}?> ><a href="<?php echo base_url();?>home/AddIndustry"><i class="fa fa-circle-o"></i>Industries</a></li>
              <?php } ?>
              <?php if(in_array('27', $subModule)) { ?>
                <li <?php if($sidebar == 'BorrowerManagement' && $sidebarMenu == 'Occupations'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddOccupation"><i class="fa fa-circle-o"></i>Occupations</a></li>
              <?php } ?>
              <?php if(in_array('34', $subModule)) { ?>
                <li <?php if($sidebar == 'BorrowerManagement' && $sidebarMenu == 'Education'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddEducation"><i class="fa fa-circle-o"></i>Education Levels</a></li>
              <?php } ?>
              <?php if(in_array('42', $subModule)) { ?>
                <li <?php if($sidebar == 'BorrowerManagement' && $sidebarMenu == 'BorrowerStatus'){echo 'class="active"';}?> ><a href="<?php echo base_url();?>home/AddBorrowerStatus"><i class="fa fa-circle-o"></i>Borrower's Status</a></li>
              <?php } ?>
            </ul>
          </li>
        <?php } ?>


        <?php if(in_array('1', $access)) { ?>
          <li <?php if($sidebar == 'EmployeeManagement'){echo 'class="treeview active"';} else {echo 'class="treeview"';}?>>
            <a href="#">
              <i class="fa fa-black-tie"></i> <span>Employee Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <?php if(in_array('1', $subModule)) { ?>
                <li <?php if($sidebar == 'EmployeeManagement' && $sidebarMenu == 'EmployeeManagement'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/addEmployees"><i class="fa fa-circle-o"></i>Employees</a></li>
              <?php } ?>
              <?php if(in_array('60', $subModule)) { ?>
                <li <?php if($sidebar == 'EmployeeManagement' && $sidebarMenu == 'Users'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/addUser"><i class="fa fa-circle-o"></i>Users</a></li>
              <?php } ?>
              <?php if(in_array('61', $subModule)) { ?>
                <li <?php if($sidebar == 'EmployeeManagement' && $sidebarMenu == 'Positions'){echo 'class="active"';}?> ><a href="<?php echo base_url();?>home/AddPosition"><i class="fa fa-circle-o"></i>Positions</a></li>
              <?php } ?>
            </ul>
          </li>
        <?php } ?>



        <?php if(in_array('5', $access)) { ?>
          <li <?php if($sidebar == 'Finance'){echo 'class="treeview active"';} else {echo 'class="treeview"';}?>>
            <a href="#">
              <i class="fa fa-dollar"></i> <span>Finance Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <?php if(in_array('32', $subModule)) { ?>
                <li <?php if($sidebar == 'Finance' && $sidebarMenu == 'ExpenseType'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddExpenseType"><i class="fa fa-circle-o"></i>Types of Expense</a></li>
              <?php } ?>
              <?php if(in_array('13', $subModule)) { ?>
                <li <?php if($sidebar == 'Finance' && $sidebarMenu == 'Expenses'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddExpense"><i class="fa fa-circle-o"></i>Add Expense</a></li>
              <?php } ?>
              <?php if(in_array('33', $subModule)) { ?>
                <li <?php if($sidebar == 'Finance' && $sidebarMenu == 'DepositType'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/addDepositType"><i class="fa fa-circle-o"></i>Types of Deposit</a></li>
              <?php } ?>
              <?php if(in_array('14', $subModule)) { ?>
                <li <?php if($sidebar == 'Finance' && $sidebarMenu == 'Deposit'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/addDeposit"><i class="fa fa-circle-o"></i>Add Deposit</a></li>
              <?php } ?>
            </ul>
          </li>
        <?php } ?>
        <?php if(in_array('9', $access)) { ?>
          <?php if(in_array('43', $subModule)) { ?>
            <li <?php if($sidebar == 'HistoryLog' && $sidebarMenu == 'HistoryLog'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/HistoryLogs"><i class="fa fa-history"></i>History Logs</a></li>
          <?php } ?>
        <?php } ?>
        <?php if(in_array('9', $access)) { ?>
          <li <?php if($sidebar == 'SystemSetup'){echo 'class="treeview active"';} else {echo 'class="treeview"';}?>>
            <a href="#">
              <i class="fa fa-cogs"></i> <span>System Setup</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <!-- <?php if(in_array('9', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'Users'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/addUser"><i class="fa fa-circle-o"></i>Users</a></li>
              <?php } ?> -->
              <?php if(in_array('24', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'Banks'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddBank"><i class="fa fa-circle-o"></i>Banks</a></li>
              <?php } ?>
              <?php if(in_array('25', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'Branches'){echo 'class="active"';}?> ><a href="<?php echo base_url();?>home/AddBranch"><i class="fa fa-circle-o"></i><span>Branches</span></a></li>
              <?php } ?>
              <!-- <?php if(in_array('26', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'Industries'){echo 'class="active"';}?> ><a href="<?php echo base_url();?>home/AddIndustry"><i class="fa fa-circle-o"></i> <span>Industries</span></a></li>
              <?php } ?> -->
              <!-- <?php if(in_array('27', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'Occupations'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddOccupation"><i class="fa fa-circle-o"></i>Occupations</a></li>
              <?php } ?> -->
              <?php if(in_array('28', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'Requirements'){echo 'class="active"';}?> ><a href="<?php echo base_url();?>home/AddRequirement"><i class="fa fa-circle-o"></i><span>Requirements</span></a></li>
              <?php } ?>
              <!-- <?php if(in_array('29', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'RepaymentCycle'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddRepaymentCycle"><i class="fa fa-circle-o"></i>Repayment Cycles</a></li>
              <?php } ?> -->
              <?php if(in_array('30', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'Disbursement'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddDisbursement"><i class="fa fa-circle-o"></i>Disbursements</a></li>
              <?php } ?>
              <?php if(in_array('31', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'InitialCapital'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddInitialCapital"><i class="fa fa-circle-o"></i>Set Initial Capital</a></li>
              <?php } ?>
              <!-- <?php if(in_array('32', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'ExpenseType'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddExpenseType"><i class="fa fa-circle-o"></i>Types of Expense</a></li>
              <?php } ?> -->
              <!-- <?php if(in_array('33', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'DepositType'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/addDepositType"><i class="fa fa-circle-o"></i>Types of Deposit</a></li>
              <?php } ?> -->
              <!-- <?php if(in_array('34', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'Education'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddEducation"><i class="fa fa-circle-o"></i>Education Levels</a></li>
              <?php } ?> -->
              <!-- <?php if(in_array('35', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'Positions'){echo 'class="active"';}?> ><a href="<?php echo base_url();?>home/AddPosition"><i class="fa fa-circle-o"></i> <span>Positions</span></a></li>
              <?php } ?> -->
              <!-- <?php if(in_array('36', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'Purposes'){echo 'class="active"';}?> ><a href="<?php echo base_url();?>home/AddPurpose"><i class="fa fa-circle-o"></i> <span>Purposes</span></a></li>
              <?php } ?> -->
              <!-- <?php if(in_array('37', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'LoanTypes'){echo 'class="active"';}?> ><a href="<?php echo base_url();?>home/AddLoanType"><i class="fa fa-circle-o"></i> <span>Loan Types</span></a></li>
              <?php } ?> -->
              <!-- <?php if(in_array('38', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'LoanStatus'){echo 'class="active"';}?> ><a href="<?php echo base_url();?>home/AddLoanStatus"><i class="fa fa-circle-o"></i> Loan Status</a></li>
              <?php } ?> -->
              <?php if(in_array('39', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'Methods'){echo 'class="active"';}?> ><a href="<?php echo base_url();?>home/AddMethod"><i class="fa fa-circle-o"></i> <span>Modes of Payment</span></a></li>
              <?php } ?>
              <!-- <?php if(in_array('40', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'Conditional'){echo 'class="active"';}?> ><a href="<?php echo base_url();?>home/AddConditional"><i class="fa fa-circle-o"></i> <span>Additional Charges</span></a></li>
              <?php } ?> -->
              <?php if(in_array('41', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'Categories'){echo 'class="active"';}?> ><a href="<?php echo base_url();?>home/AddCategory"><i class="fa fa-circle-o"></i> <span>Asset Categories</span></a></li>
              <?php } ?>
              <!-- <?php if(in_array('42', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'BorrowerStatus'){echo 'class="active"';}?> ><a href="<?php echo base_url();?>home/AddBorrowerStatus"><i class="fa fa-circle-o"></i> <span>Borrower's Status</span></a></li>
              <?php } ?> -->
              <?php if(in_array('54', $subModule)) { ?>
                <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'DB'){echo 'class="active"';}?> ><a href="<?php echo base_url();?>home/branchDatabase"><i class="fa fa-circle-o"></i> <span>Database Management</span></a></li>
              <?php } ?>
            </ul>
          </li>
        <?php } ?>

        <?php if(in_array('11', $access)) { ?>
          <li <?php if($sidebar == 'Reports'){echo 'class="treeview active"';} else {echo 'class="treeview"';}?>>
            <a href="#">
              <i class="fa fa-building"></i> <span>Reports</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <?php if(in_array('52', $subModule)) { ?>
                <!-- <li <?php if($sidebar == 'Reports' && $sidebarMenu == 'Borrowers'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddExpense"><i class="fa fa-circle-o"></i>Borrowers</a></li> -->
              <?php } ?>
              <?php if(in_array('53', $subModule)) { ?>
                <!-- <li <?php if($sidebar == 'Reports' && $sidebarMenu == 'Loans'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddExpense"><i class="fa fa-circle-o"></i>Loans</a></li> -->
              <?php } ?>
              <?php if(in_array('53', $subModule)) { ?>
                <li <?php if($sidebar == 'Reports' && $sidebarMenu == 'Loan Collections'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/generateLoanCollection"><i class="fa fa-circle-o"></i>Loan Collections</a></li>
              <?php } ?>
              <?php if(in_array('53', $subModule)) { ?>
                <li <?php if($sidebar == 'Reports' && $sidebarMenu == 'Expenses'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/generateExpenses"><i class="fa fa-circle-o"></i>Expenses</a></li>
              <?php } ?>
              <?php if(in_array('53', $subModule)) { ?>
                <li <?php if($sidebar == 'Reports' && $sidebarMenu == 'Expenses'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/generateIncomeStatement"><i class="fa fa-circle-o"></i>Income Statement</a></li>
              <?php } ?>
              <?php if(in_array('53', $subModule)) { ?>
                <li <?php if($sidebar == 'Reports' && $sidebarMenu == 'Demographics'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>loanapplication_controller/generateReport/4"><i class="fa fa-circle-o"></i>Download Demographics</a></li>
              <?php } ?>
              <?php if(in_array('53', $subModule)) { ?>
                <li <?php if($sidebar == 'Reports' && $sidebarMenu == 'Loans'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>loanapplication_controller/generateReport/5"><i class="fa fa-circle-o"></i>Download Loans Extended</a></li>
              <?php } ?>
              <?php if(in_array('53', $subModule)) { ?>
                <li <?php if($sidebar == 'Reports' && $sidebarMenu == 'Loans'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>loanapplication_controller/generateReport/6"><i class="fa fa-circle-o"></i>Download Financial Health</a></li>
              <?php } ?>
              <?php if(in_array('53', $subModule)) { ?>
                <!-- <li <?php if($sidebar == 'Reports' && $sidebarMenu == 'Transactions'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/generateExpenses"><i class="fa fa-circle-o"></i>Employee Transactions</a></li> -->
              <?php } ?>
            </ul>
          </li>
        <?php } ?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>