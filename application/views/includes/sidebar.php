
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


        <!-- <?php if(in_array('3', $access) || in_array('1', $access)) { ?>
          <li><a href="<?php echo base_url();?>home/Customers"><i class="fa fa-users"></i> <span>Clients</span></a></li>
        <?php } ?> -->
        <?php if(in_array('3', $access) || in_array('1', $access)) { ?>
          <!-- <li <?php if($sidebar == 4){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/Clients"><i class="fa fa-users"></i> <span>Clients</span></a></li> -->
        <?php } ?>
        
        <!-- <?php if(in_array('2', $access) || in_array('1', $access)) { ?>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-money"></i> <span>Payments</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="<?php echo base_url(); ?>resources/index2.html"><i class="fa fa-circle-o"></i> Payment</a></li>
              <li><a href="<?php echo base_url(); ?>resources/index2.html"><i class="fa fa-circle-o"></i> Missed Payments</a></li>
              <li><a href="<?php echo base_url(); ?>resources/index2.html"><i class="fa fa-circle-o"></i> List of Payments</a></li>
            </ul>
          </li>
        <?php } ?> -->

        <!-- <?php if(in_array('3', $access) || in_array('1', $access)) { ?>
          <li <?php if($sidebar == 3){echo 'class="treeview active"';} else {echo 'class="treeview"';}?> >
            <a href="#">
              <i class="fa fa-credit-card"></i> <span>Transactions <span class="badge bg-yellow">2</span></span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li <?php if($sidebar == 3 && $sidebarMenu == 2){echo 'class="active"';}?>><a href="<?php echo base_url();?>home/LoanCalculator"><i class="fa fa-circle-o"></i> Loan Calculator</a></li>
              <li><a href="<?php echo base_url();?>home/LoanCalculator"><i class="fa fa-circle-o"></i> Risk Assessments</a></li>
              <li><a href="<?php echo base_url(); ?>resources/index2.html"><i class="fa fa-circle-o"></i> Loan Applications <span class="badge bg-yellow">2</span></a></li>
            </ul>
          </li>
        <?php } ?>

        <?php if(in_array('4', $access) || in_array('1', $access)) { ?>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-calendar"></i> <span>Report Generation</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="<?php echo base_url(); ?>resources/index.html"><i class="fa fa-circle-o"></i>Collection</a></li>
              <li><a href="<?php echo base_url();?>home/Customers"><i class="fa fa-circle-o"></i> <span>Loan Applications</span></a></li>
              <li><a href="<?php echo base_url(); ?>resources/index2.html"><i class="fa fa-circle-o"></i>Loan Products</a></li>
              <li><a href="<?php echo base_url(); ?>resources/index2.html"><i class="fa fa-circle-o"></i>Customers</a></li>
              <li><a href="<?php echo base_url(); ?>resources/index2.html"><i class="fa fa-circle-o"></i>Risk Assessments</a></li>
            </ul>
          </li>
        <?php } ?> -->

        <?php if(in_array('1', $access)) { ?>
          <li <?php if($sidebar == 'SystemSetup'){echo 'class="treeview active"';} else {echo 'class="treeview"';}?>>
            <a href="#">
              <i class="fa fa-cogs"></i> <span>System Setup</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li <?php if($sidebar == 'SystemSetup' && $sidebarMenu == 'Borrowers'){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/borrowers"><i class="fa fa-circle-o"></i> Borrower</a></li>
              <!-- <li ><a href="<?php echo base_url();?>home/borrowers"><i class="fa fa-circle-o"></i> <span>Borrower</span></a></li> -->
              <!-- <li ><a href="<?php echo base_url();?>home/DashboardM"><i class="fa fa-circle-o"></i> <span>Manager</span></a></li> -->
              <!-- <li><a href="<?php echo base_url(); ?>resources/index2.html"><i class="fa fa-circle-o"></i> Loan Products</a></li> -->
              <li <?php if($sidebar == 2 && $sidebarMenu == 1){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/addEmployees"><i class="fa fa-circle-o"></i> Employees</a></li>
              <!-- <li <?php if($sidebar == 2 && $sidebarMenu == 3){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddBanks"><i class="fa fa-circle-o"></i>Managers</a></li> -->
              <!-- <li ><a href="<?php echo base_url();?>home/Customers"><i class="fa fa-circle-o"></i> <span>Customers</span></a></li> -->
              <!-- <li <?php if($sidebar == 2 && $sidebarMenu == 2){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/LoanList"><i class="fa fa-circle-o"></i> Loan Products</a></li> -->
              <li <?php if($sidebar == 2 && $sidebarMenu == 3){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/addUser"><i class="fa fa-circle-o"></i> Users</a></li>
              <li <?php if($sidebar == 2 && $sidebarMenu == 3){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddBank"><i class="fa fa-circle-o"></i>Banks</a></li>
              <li ><a href="<?php echo base_url();?>home/AddBranch"><i class="fa fa-circle-o"></i> <span>Branches</span></a></li>
              <li ><a href="<?php echo base_url();?>home/AddIndustry"><i class="fa fa-circle-o"></i> <span>Industries</span></a></li>
              <li ><a href="<?php echo base_url();?>home/AddRequirement"><i class="fa fa-circle-o"></i> <span>Requirements</span></a></li>
              <li <?php if($sidebar == 2 && $sidebarMenu == 3){echo 'class="active"';}?> ><a href="<?php echo base_url(); ?>home/AddEducation"><i class="fa fa-circle-o"></i>Education Levels</a></li>
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