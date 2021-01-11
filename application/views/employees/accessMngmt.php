<style type="text/css">
  .select2-container--default .select2-selection--multiple .select2-selection__choice{color:black;}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <?php if(in_array('60', $subModule) && $Detail['BranchId'] == $this->session->userdata('BranchId')) { ?>

    <section class="content-header">
      <h1>
        Access Management
      </h1>
      <ol class="breadcrumb">
        <li><a href="#" class="active"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li>Employee List</a></li>
        <li>Access Management</a></li>
      </ol>
    </section>

    <section class="content">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Access Rights for <?php print_r($Detail['Name']) ?> <br> (<?php print_r($Detail['EmployeeNumber']) ?>)</h3>
        </div>
        <div class="box-body">
          <form autocomplete="off" action="<?php echo base_url(); ?>employee_controller/accessmanagement/<?php print_r($Detail['EmployeeId']) ?>" id="frmInsert" method="post">
            <table  id="example1" class="table table-bordered table-hover">
              <tr>
                <th>Module</th>
              </tr>
              <tbody>
                <?php
                  $rowNo = 0;
                  $moduleNo = 0;
                  $selectedModule = 0;
                  foreach ($Modules as $value) 
                  {
                    $moduleNo++;
                    echo '<tr>';
                    echo '<td><h4><input type="checkbox" value="'.$moduleNo.'" onclick="chkModule(this.value, '.$value['ModuleId'].')" id="selectModule'.$moduleNo.'" class="checkCharges"></label> '.$value['Description'].' <label></h4></td>';

                    foreach ($SubModule as $sub) 
                    {
                      $rowNo++;
                      if($sub['ModuleId'] == $value['ModuleId'])
                      {
                        $isSelected = '';
                        $selectedValue = 0;
                        foreach ($UserAccess as $hasAccess) 
                        {
                          if($hasAccess['SubModuleId'] == $sub['SubModuleId'])
                          {
                            $isSelected = 'checked';
                            $selectedValue = 1;
                            $selectedModule = $selectedModule + 1;
                          }
                        }
                        echo '<tr>';
                        echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>
                          <input type="checkbox" '.$isSelected.' value="'.$rowNo.'" onclick="chkSelected(this.value, '.$sub['SubModuleId'].')" id="selectCheck'.$sub['SubModuleId'].'" class="checkCharges"> '.$sub['Description'].'</label> 
                          <input type="hidden" value="'.$sub['Code'].'" name="Code[]">
                          <input type="hidden" id="txtIsSelected'.$sub['SubModuleId'].'" value="'.$selectedValue.'" class="" name="isSelected[]">
                          <input type="hidden" class="'.$value['ModuleId'].'" value="'.$sub['SubModuleId'].'" name="SubModuleId[]">
                          <input type="hidden" value="'.$rowNo.'" name="countRow[]">
                        </td>';
                        echo '</tr>';
                      }
                    }
                    echo '</tr>';
                  }
                  // $moduleNo++;
                  // $branchRow = 0;
                  // echo '<tr>';
                  // echo '<td><h4></label> Branch Management <label></h4></td>';
                  //   foreach ($Branch as $brnch) 
                  //   {
                  //     $rowNo++;
                  //     $branchRow++;

                  //     foreach ($branchAccess as $sub) 
                  //     {
                  //       if($brnch['BranchId'] == $sub['BranchId'])
                  //       {
                  //         echo '<tr>';
                  //         echo '<td>'.$branchRow.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>
                  //           <input type="checkbox" checked value="'.$branchRow.'" onclick="chkSelected2(this.value, '.$branchRow.')" id="selectCheck2'.$branchRow.'" class="checkCharges"> '.$brnch['Name'].'</label> 
                  //           <input type="" value="'.$brnch['Code'].'" name="Code[]">
                  //           <input type="" id="txtIsSelected2'.$branchRow.'" value="1" class="" name="isSelected[]">
                  //           <input type="" class="12" value="'.$brnch['BranchId'].'" name="BranchId[]">
                  //           <input type="" value="'.$rowNo.'" name="countRow[]">
                  //           <input type="" value="Branch" name="countRow[]">
                  //         </td>';
                  //         echo '</tr>';
                  //       }
                  //       else
                  //       {
                  //         echo '<tr>';
                  //         echo '<td>'.$branchRow.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label>
                  //           <input type="checkbox" value="'.$branchRow.'" onclick="chkSelected2(this.value, '.$branchRow.')" id="selectCheck2'.$branchRow.'" class="checkCharges"> '.$brnch['Name'].'</label> 
                  //           <input type="" value="'.$brnch['Code'].'" name="Code[]">
                  //           <input type="" id="txtIsSelected2'.$branchRow.'" value="0" class="" name="isSelected[]">
                  //           <input type="" class="12" value="'.$brnch['BranchId'].'" name="BranchId[]">
                  //           <input type="" value="'.$rowNo.'" name="countRow[]">
                  //         </td>';
                  //         echo '</tr>';
                  //       }
                  //     }
                  //   }
                  // echo '</tr>';
                ?>
              </tbody>
            </table>
            <br>
            <div class="pull-right">
              <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </section>

  <?php } else { ?>
    <br>
    <br>
    <div class="col-md-12">
      <div class="callout callout-danger">
        <h4>You have no access to this module!</h4>
        <p>Please contact your admin to request for access!</p>
      </div>
    </div>
  <?php } ?>

</div>

<footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>Version</b> 1.0.0
  </div>
  <strong>Copyright &copy; 2020 <a href="https://giatechph.com" target="_blank">GIA Tech.</a></strong> All rights
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

  $("#frmInsert").on('submit', function (e) {
      e.preventDefault(); 
      swal({
        title: 'Confirm',
        text: 'Are you sure want to give access to user?',
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

  function chkSelected(rowNo, SubModuleId)
  {
    var checkBox = document.getElementById("selectCheck"+SubModuleId+"");
    // If the checkbox is checked, display the output text
    if (checkBox.checked == true){
      $('#txtIsSelected'+SubModuleId+'').val(1);
    } else {
      $('#txtIsSelected'+SubModuleId+'').val(0);
    }
  }

  function chkSelected2(rowNo, SubModuleId)
  {
    var checkBox = document.getElementById("selectCheck2"+SubModuleId+"");
    // If the checkbox is checked, display the output text
    if (checkBox.checked == true){
      $('#txtIsSelected2'+SubModuleId+'').val(1);
    } else {
      $('#txtIsSelected2'+SubModuleId+'').val(0);
    }
    alert($('#txtIsSelected2'+SubModuleId+'').val())
  }

  function chkModule(rowNo, ModuleId, value)
  {
    var checkBox = document.getElementById("selectModule"+rowNo+"");
    // If the checkbox is checked, display the output text
    if (checkBox.checked == true){
      $("."+ModuleId+"").each(function( index ) {
        $('#txtIsSelected'+this.value+'').val(1);
        document.getElementById("selectCheck"+this.value+"").checked = true;
        alert(this.value)
      });
    } else {
      $("."+ModuleId+"").each(function( index ) {
        $('#txtIsSelected'+this.value+'').val(0);
        document.getElementById("selectCheck"+this.value+"").checked = false;
        alert(this.value)
      });
    }
  }

  $(function () {
  });

</script>