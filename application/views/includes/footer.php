
  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      v.0.0.1
    </div>
    <!-- Default to the left -->
    All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?php echo base_url(); ?>resources/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?php echo base_url(); ?>resources/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="<?php echo base_url(); ?>resources/dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="<?php echo base_url(); ?>resources/plugins/chart.js/Chart.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?php echo base_url(); ?>resources/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>resources/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>resources/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url(); ?>resources/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>resources/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url(); ?>resources/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo base_url(); ?>resources/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo base_url(); ?>resources/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo base_url(); ?>resources/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo base_url(); ?>resources/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url(); ?>resources/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url(); ?>resources/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Select2 -->
<script src="<?php echo base_url(); ?>resources/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo base_url(); ?>resources/bower_components/sweetalert2/dist/sweetalert2.min.js"></script>
<!-- InputMask -->
<script src="<?php echo base_url(); ?>resources/plugins/moment/moment.min.js"></script>
<!-- date-range-picker -->
<script src="<?php echo base_url(); ?>resources/plugins/daterangepicker/daterangepicker.js"></script>
<?php
  $alertTitle = $this->session->flashdata('alertTitle');
  $alertText = $this->session->flashdata('alertText');
  $alertType = $this->session->flashdata('alertType');
?>
<?php if (!empty($alertTitle)): ?>
<script>
Swal.fire({
  title: '<?php echo $alertTitle; ?>',
  text: '<?php echo $alertText; ?>',
  icon: '<?php echo $alertType; ?>'
});
</script>
<?php endif; ?>
</body>
</html>
