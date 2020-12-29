
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      New Customer
    </h1>
    <ol class="breadcrumb">
      <li><a href="#" class="active"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      <li><a href="#">Customers</a></li>
      <li><a href="#">New Customer</a></li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Customer Details</h3>
      </div>
      <div class="box-body">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">Customer Details</a></li>
            <li><a href="#tab_2" data-toggle="tab">Address</a></li>
            <li><a href="#tab_3" data-toggle="tab">Occupation</a></li>
            <li><a href="#tab_4" data-toggle="tab">Supporting Documents</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">First Name</label>
                    <br><label>Amity Faith</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Middle Name</label>
                    <br><label>-</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Last Name</label>
                    <br><label>Arcega</label>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Ext. Name</label>
                    <br><label>-</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Gender</label>
                    <br><label>Female</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Nationality</label>
                    <br><label>Filipino</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Civil Status</label>
                    <br><label>Single</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Mobile Number</label>
                    <br><label>09351222952</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Phone Number</label>
                    <br><label>-</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Email Address</label>
                    <br><label>arcegaamityfaith@gmail.com</label>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_2">
              <a onclick="newCustomer()" class="btn btn-sm btn-primary pull-right" title="View">Add New Record</a>
              <br>
              <br>
              <table id="example1" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Address</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>123 Abc Street Manila</td>
                  <td><span class="label label-success">Active</span></td>
                  <td>
                    <a href="javascript:void(0)" class="btn btn-sm btn-info" title="Edit"><span class="fa fa-edit"></span></a>
                    <a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Deactivate"><span class="fa fa-trash"></span></a>
                    <!-- <div class="progress-sm">
                      <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
                      </div>
                    </div> -->
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
            <!-- /.tab-pane -->
            <div class="tab-pane" id="tab_3">
              <a onclick="newCustomer()" class="btn btn-sm btn-primary pull-right" title="View">Add New Record</a>
              <br>
              <br>
              <table id="example2" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Company Name</th>
                  <th>Employment Status</th>
                  <th>Time in Employment</th>
                  <th>Position</th>
                  <th>Salary</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>GIA Tech</td>
                  <td>Regular</td>
                  <td>1 year(s)</td>
                  <td>Lead Developer</td>
                  <td>Php 20,000.00</td>
                  <td><span class="label label-success">Active</span></td>
                  <td>
                    <a href="javascript:void(0)" class="btn btn-sm btn-default" title="Download"><span class="fa fa-download"></span></a>
                    <a href="javascript:void(0)" class="btn btn-sm btn-info" title="Edit"><span class="fa fa-edit"></span></a>
                    <a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Deactivate"><span class="fa fa-trash"></span></a>
                    <!-- <div class="progress-sm">
                      <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
                      </div>
                    </div> -->
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
            <div class="tab-pane" id="tab_4">
              <a onclick="newCustomer()" class="btn btn-sm btn-primary pull-right" title="View">Add New Record</a>
              <br>
              <br>
              <table id="example3" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Document Code</th>
                  <th>File Name</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>PGBG-2020-06-0001</td>
                  <td>Pag-ibig document</td>
                  <td><span class="label label-success">Active</span></td>
                  <td>
                    <a href="javascript:void(0)" class="btn btn-sm btn-default" title="Download"><span class="fa fa-download"></span></a>
                    <a href="javascript:void(0)" class="btn btn-sm btn-info" title="Edit"><span class="fa fa-edit"></span></a>
                    <a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Deactivate"><span class="fa fa-trash"></span></a>
                    <!-- <div class="progress-sm">
                      <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
                      </div>
                    </div> -->
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
            <!-- /.tab-pane -->
          </div>
          <!-- /.tab-content -->
        </div>
      </div>
    </div>
    <!-- /.box -->

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0
  </div>
  <strong>Copyright &copy; 2020 <a href="#">GIA Tech.</a>.</strong> All rights
  reserved.
</footer>

<div class="loading" style="display: none">Loading&#8230;</div>
<?php $this->load->view('includes/footer'); ?>

<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({})
    $('#example3').DataTable({})
  })
</script>