
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

        <li <?php if($sidebar == 'Loans'){echo 'class="treeview active"';} else {echo 'class="treeview"';}?>>
          <a href="#">
            <i class="fa fa-file-text"></i> <span>Loans</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($sidebar == 'Loans' && $sidebarMenu == 'View Loans'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/ViewLoans"><i class="fa fa-circle-o"></i> View Loans</a></li>
            <li <?php if($sidebar == 'Loans' && $sidebarMenu == 'Loan Application'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/LoanApplication"><i class="fa fa-circle-o"></i> Loan Application</a></li>
            <li <?php if($sidebar == 'Loans' && $sidebarMenu == 'Loan Calculator'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/LoanCalculator"><i class="fa fa-circle-o"></i> Loan Calculator</a></li>
            <li <?php if($sidebar == 'Loans' && $sidebarMenu == 'Loan Approvals'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/LoanApprovals"><i class="fa fa-circle-o"></i>Approvals</a></li>
          </ul>
        </li>

        <li <?php if($sidebar == 'Asset Management' && $sidebarMenu == 'Asset Management'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddAssetManagement"><i class="fa fa-archive"></i> Asset Management</a></li>

        <li <?php if($sidebar == 'BorrowerManagement' && $sidebarMenu == 'BorrowerManagement'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/borrowers"><i class="fa fa-users"></i>Borrower Management</a></li>

        <li <?php if($sidebar == 'EmployeeManagement' && $sidebarMenu == 'EmployeeManagement'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/addEmployees"><i class="fa fa-black-tie"></i>Employee Management</a></li>

        <li <?php if($sidebar == 'Finance'){echo 'class="treeview active"';} else {echo 'class="treeview"';}?>>
          <a href="#">
            <i class="fa fa-dollar"></i> <span>Finance Management</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php if($sidebar == 'Finance' && $sidebarMenu == 'Expense'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddExpense"><i class="fa fa-circle-o"></i>Add Expense</a></li>

            <li <?php if($sidebar == 'Finance' && $sidebarMenu == 'Withdrawal'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddWithdrawal"><i class="fa fa-circle-o"></i>Add Withdrawal</a></li>
          </ul>
        </li>

        <li <?php if($sidebar == 'HistoryLog' && $sidebarMenu == 'HistoryLog'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/HistoryLogs"><i class="fa fa-history"></i>History Logs</a></li>


        <?php if(in_array('1', $access)) { ?>
          <li <?php if($sidebar == 'SystemSetup'){echo 'class="treeview active"';} else {echo 'class="treeview"';}?>>
            <a href="#">
              <i class="fa fa-cogs"></i> <span>System Setup</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'Users'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/addUser"><i class="fa fa-circle-o"></i> Users</a></li>
              
              <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'Banks'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddBank"><i class="fa fa-circle-o"></i>Banks</a></li>
              
              <li ><a href="<?php echo base_url();?>home/AddBranch"><i class="fa fa-circle-o"></i> <span>Branches</span></a></li>
              
              <li ><a href="<?php echo base_url();?>home/AddIndustry"><i class="fa fa-circle-o"></i> <span>Industries</span></a></li>
              
              <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'Occupations'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddOccupation"><i class="fa fa-circle-o"></i>Occupations</a></li>
              
              <li ><a href="<?php echo base_url();?>home/AddRequirement"><i class="fa fa-circle-o"></i> <span>Requirements</span></a></li>
              
              <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'RepaymentCycle'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddRepaymentCycle"><i class="fa fa-circle-o"></i>Repayment Cycles</a></li>
              
              <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'Disbursement'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddDisbursement"><i class="fa fa-circle-o"></i>Disbursements</a></li>
              
              <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'InitialCapital'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddInitialCapital"><i class="fa fa-circle-o"></i>Set Initial Capital</a></li>

              <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'ExpenseType'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddExpenseType"><i class="fa fa-circle-o"></i>Types of Expense</a></li>

              <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'WithdrawalType'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddWithdrawalType"><i class="fa fa-circle-o"></i>Types of Withdrawal</a></li>

              <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'Education'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddEducation"><i class="fa fa-circle-o"></i>Education Levels</a></li>
              <li ><a href="<?php echo base_url();?>home/AddPosition"><i class="fa fa-circle-o"></i> <span>Positions</span></a></li>
              <li ><a href="<?php echo base_url();?>home/AddPurpose"><i class="fa fa-circle-o"></i> <span>Purposes</span></a></li>
              <li ><a href="<?php echo base_url();?>home/AddLoanType"><i class="fa fa-circle-o"></i> <span>Loan Types</span></a></li>
              <li ><a href="<?php echo base_url();?>home/AddLoanStatus"><i class="fa fa-circle-o"></i> <span>Loan Status</span></a></li>
              <li ><a href="<?php echo base_url();?>home/AddMethod"><i class="fa fa-circle-o"></i> <span>Methods for Payment</span></a></li>
              <li ><a href="<?php echo base_url();?>home/AddConditional"><i class="fa fa-circle-o"></i> <span>Conditional Charges</span></a></li>
              <li ><a href="<?php echo base_url();?>home/AddOptional"><i class="fa fa-circle-o"></i> <span>Optional Charges</span></a></li>
              <li ><a href="<?php echo base_url();?>home/AddCategory"><i class="fa fa-circle-o"></i> <span>Asset Categories</span></a></li>
              <li ><a href="<?php echo base_url();?>home/AddBorrowerStatus"><i class="fa fa-circle-o"></i> <span>Borrower's Status</span></a></li>
            </ul>
          </li>
          <!-- <li <?php if($sidebar == 4 && $sidebarMenu == 2){echo 'class="active"';}?>><a href="<?php echo base_url();?>home/adminAuditLogs"><i class="fa fa-list-alt"></i> <span>Audit Logs</span></a></li> -->
        <?php } ?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>