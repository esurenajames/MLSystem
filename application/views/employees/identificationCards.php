
<table id="dtblIDs" class="table table-bordered table-hover" style="width: 100%">
  <thead>
  <tr>
    <th>#</th>
    <th>Type</th>
    <th>Number</th>
    <th>File Name</th>
    <th>Created By</th>
    <th>Date Creation</th>
    <th>Date Updated</th>
    <th>Status</th>
    <th>Action</th>
  </tr>
  </thead>
  <tbody>
    <?php
      foreach ($Ids as $value) 
      {
        echo "<tr>";
        echo "<td>".$value['rowNumber']."</td>";
        echo "<td>".$value['Name']."</td>";
        echo "<td>".$value['IdNumber']."</td>";
        echo "<td>".$value['Attachment']."</td>";
        echo "<td>".$value['LastName'].", ".$value['FirstName']." ".$value['MiddleInitial']."</td>";
        echo "<td>".$value['DateCreated']."</td>";
        echo "<td>".$value['DateUpdated']."</td>";

        if($value['StatusId'] == 1)
        {
          $status = "<span class='badge bg-green'>Active</span>";
          $action = '<a onclick="confirmID(\'Are you sure you want to deactivate this id?\', \''.$value['EmployeeIdentificationId'].'\', 0)" class="btn btn-danger btn-sm" title="Deactivate"><span class="fa fa-close"></span></a> <a class="btn btn-sm btn-success" href="' . base_url() .'/admin_controller/download/1/'.$value['EmployeeIdentificationId'].'" title="Download"><span class="fa fa-download"></span></a>';
        }
        else if($value['StatusId'] == 0)
        {
          $status = "<span class='badge bg-red'>Deactivated</span>";
          $action = '<a class="btn btn-sm btn-success" href="' . base_url() .'/admin_controller/download/1/'.$value['EmployeeIdentificationId'].'" title="Download"><span class="fa fa-download"></span></a>';
        }

        echo "<td>".$status."</td>";
        echo "<td>".$action."</td>";
        echo "</tr>";
      }
    ?>
  </tbody>
</table>