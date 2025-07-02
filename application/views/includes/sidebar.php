

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="<?php echo base_url(); ?>resources/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">ML System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo base_url(); ?>resources/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $this->session->userdata('Name') ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a <?php if($sidebar == 'Dashboard' && $sidebarMenu == 'Dashboard'){echo 'class="nav-link active"';} else {echo 'class="nav-link"';}?> href="<?php echo base_url(); ?>home/Dashboard">
              <i class="fa fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <?php if($this->session->userdata('RoleId') == 1) { /* FACULTY */ ?>
            <li class="nav-item">
              <a <?php if($sidebar == 'Dashboard' && $sidebarMenu == 'Class list'){echo 'class="nav-link active"';} else {echo 'class="nav-link"';}?> href="<?php echo base_url(); ?>home/FacultyClassList">
                <i class="fa fa-list"></i>
                <p>Class List</p>
              </a>
            </li>
            <li class="nav-item">
              <a <?php if($sidebar == 'Faculty' && $sidebarMenu == 'Exam Retake'){echo 'class="nav-link active"';} else {echo 'class="nav-link"';}?> href="<?php echo base_url(); ?>home/FacultyRetakeApproval">
                <i class="fa fa-list-alt"></i>
                <p>Exam Retake Approval</p>
              </a>
            </li>
          <?php } ?>
          <?php if($this->session->userdata('RoleId') == 4) { /* STUDENT */ ?>
            <li class="nav-item">
              <a <?php if($sidebar == 'Dashboard' && $sidebarMenu == 'Class list'){echo 'class="nav-link active"';} else {echo 'class="nav-link"';}?> href="<?php echo base_url(); ?>home/StudentClassList">
                <i class="fa fa-list"></i>
                <p>Class List</p>
              </a>
            </li>
          <?php } ?>
          <?php if($this->session->userdata('RoleId') == 3) { /* ADMIN */ ?>
            <li <?php if($sidebar == 'Admin'){echo 'class="nav-item menu-open"';} else {echo 'class="nav-item"';}?> >
              <a href="#" <?php if($sidebar == 'Admin' && $sidebarMenu == 'Users List'){echo 'class="nav-link active"';} else {echo 'class="nav-link"';}?>>
                <i class="nav-icon fas fa-cogs"></i>
                <p>
                  Admin
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a <?php if($sidebar == 'Admin' && $sidebarMenu == 'Users List'){echo 'class="nav-link active"';} else {echo 'class="nav-link"';}?> href="<?php echo base_url(); ?>home/Users">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Users</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a <?php if($sidebar == 'Admin' && $sidebarMenu == 'Employee List'){echo 'class="nav-link active"';} else {echo 'class="nav-link"';}?> href="<?php echo base_url(); ?>home/Employees">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Employees</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a <?php if($sidebar == 'Admin' && $sidebarMenu == 'AuditLogs'){echo 'class="nav-link active"';} else {echo 'class="nav-link"';}?> href="<?php echo base_url(); ?>home/Audit">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Audit Logs</p>
                  </a>
                </li>
              </ul>
            </li>
          <?php } ?>
          <?php if($this->session->userdata('RoleId') == 2) { /* REGISTRAR */ ?>
            <li <?php if($sidebar == 'Registrar'){echo 'class="nav-item menu-open"';} else {echo 'class="nav-item"';}?> >
              <a href="#" <?php if($sidebar == 'Registrar' && $sidebarMenu == 'Users List'){echo 'class="nav-link active"';} else {echo 'class="nav-link"';}?>>
                <i class="nav-icon fas fa-users-cog"></i>
                <p>
                  Registrar
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a <?php if($sidebar == 'Registrar' && $sidebarMenu == 'Class list'){echo 'class="nav-link active"';} else {echo 'class="nav-link"';}?> href="<?php echo base_url(); ?>home/ClassList">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Class List</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a <?php if($sidebar == 'Registrar' && $sidebarMenu == 'Subject list'){echo 'class="nav-link active"';} else {echo 'class="nav-link"';}?> href="<?php echo base_url(); ?>home/SubjectList">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Subjects</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a <?php if($sidebar == 'Registrar' && $sidebarMenu == 'Student list'){echo 'class="nav-link active"';} else {echo 'class="nav-link"';}?> href="<?php echo base_url(); ?>home/StudentList">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Students</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a <?php if($sidebar == 'Registrar' && $sidebarMenu == 'Schedule list'){echo 'class="nav-link active"';} else {echo 'class="nav-link"';}?> href="<?php echo base_url(); ?>home/ScheduleExam">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Schedule Examinations</p>
                  </a>
                </li>
              </ul>
            </li>


            <li <?php if($sidebar == 'Registrar Reports'){echo 'class="nav-item menu-open"';} else {echo 'class="nav-item"';}?> >
              <a href="#" <?php if($sidebar == 'Registrar' && $sidebarMenu == 'Users List'){echo 'class="nav-link active"';} else {echo 'class="nav-link"';}?>>
                <i class="nav-icon fas fa-clipboard-list"></i>
                <p>
                  Reports
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a <?php if($sidebar == 'Registrar Reports' && $sidebarMenu == 'Student List'){echo 'class="nav-link active"';} else {echo 'class="nav-link"';}?> href="<?php echo base_url(); ?>admin_controller/generateStudentList">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Student List</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a <?php if($sidebar == 'Registrar Reports' && $sidebarMenu == 'Students with Subjects'){echo 'class="nav-link active"';} else {echo 'class="nav-link"';}?> href="<?php echo base_url(); ?>home/generateStudents">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Students with Subjects</p>
                  </a>
                </li>
              </ul>
            </li>
          <?php } ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>