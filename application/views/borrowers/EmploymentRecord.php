
<table id="example5" class="table table-bordered table-hover" style="width: 100%">
  <thead>
  <tr>
    <th>#</th>
    <th>Employer Type</th>
    <th>Employer/Business</th>
    <th>Date Hired</th>
    <th>Tel. No.</th>
    <th>Status</th>
    <th>Action</th>
  </tr>
  </thead>
  <tbody>
    <?php
      foreach ($Employment as $value) 
      {
        echo "<tr>";
        echo "<td>".$value['rowNumber']."</td>";
        echo "<td>".$value['EmployerStatus']."</td>";
        echo "<td>".$value['EmployerName']."</td>";
        echo "<td>".$value['DateHired']."</td>";
        echo "<td>".$value['EmployerId']."</td>";

        if($value['StatusId'] == 1)
        {
          $status = "<span class='badge bg-green'>Active</span>";
          $action = '<a onclick="confirm(\'Are you sure you want to deactivate this employer?\', \''.$value['EmployerId'].'\',0, \'BorrowerEmployer\')" class="btn btn-danger btn-sm" title="Deactivate"><span class="fa fa-close"></span></a>';
        }
        else if($value['StatusId'] == 0)
        {
          $status = "<span class='badge bg-red'>Deactivated</span>";
          $action = '<a onclick="confirm(\'Are you sure you want to re-activate this employer?\', \''.$value['EmployerId'].'\', 1, \'BorrowerEmployer\')" class="btn btn-success btn-sm" title="Deactivate"><span class="fa fa-refresh"></span></a> <a class="btn btn-sm btn-info" href="' . base_url() .'/admin_controller/download/2/'.$value['EmployerId'].'" title="Download"><span class="fa fa-download"></span></a>';
        }

        echo "<td>".$status."</td>";
        echo "<td>".$action."</td>";
        echo "</tr>";
      }
    ?>
  </tbody>
</table>