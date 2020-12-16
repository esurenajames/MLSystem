
<table id="dtblEmailAddress" class="table table-bordered table-hover" style="width: 100%">
  <thead>
  <tr>
    <th>#</th>
    <th>Email Address</th>
    <th>Created By</th>
    <th>Is Primary?</th>
    <th>Date Creation</th>
    <th>Date Updated</th>
    <th>Status</th>
    <th>Action</th>
    <th>Action</th>
  </tr>
  </thead>
  <tbody>
    <?php
      $emailNo = 0;
      foreach ($EmailAddress as $value) 
      {
        echo "<tr>";
        echo "<td>".$value['rowNumber']."</td>";
        echo "<td>".$value['EmailAddress']."</td>";
        echo "<td>".$value['LastName'].", ".$value['FirstName']." ".$value['MiddleInitial']."</td>";
        if($value['IsPrimary'] == 1)
        {
          echo "<td>Yes</td>";
        }
        else
        {
          echo "<td>No</td>";
        }
        echo "<td>".$value['DateCreated']."</td>";
        echo "<td>".$value['DateUpdated']."</td>";

        if($value['StatusId'] == 1)
        {
          $status = "<span class='badge bg-green'>Active</span>";
        }
        else if($value['StatusId'] == 0)
        {
          $status = "<span class='badge bg-red'>Deactivated</span>";
        }


        if($value['StatusId'] == 1 && $value['IsPrimary'] == 1)
        {
          $action = '<a onclick="confirmEmail(\'Are you sure you want to deactivate this email?\', \''.$value['EmployeeEmailId'].'\', 0)" class="btn btn-danger btn-sm" title="Deactivate"><span class="fa fa-close"></span></a>';
        }
        else if($value['StatusId'] == 1 && $value['IsPrimary'] == 0)
        {
          $action = '<a onclick="confirmEmail(\'Are you sure you want to deactivate this email?\', \''.$value['EmployeeEmailId'].'\', 0)" class="btn btn-danger btn-sm" title="Deactivate"><span class="fa fa-close"></span></a> <a onclick="confirmEmail(\'Are you sure you want to set this email as your primary email?\', \''.$value['EmployeeEmailId'].'\', 2)" class="btn btn-success btn-sm" title="Make as primary"><span class="fa fa-check-circle"></span></a>';
        }
        else
        {
          $action = '<a onclick="confirmEmail(\'Are you sure you want to re-activate this email?\', \''.$value['EmployeeEmailId'].'\', 1)" class="btn btn-warning btn-sm" title="Deactivate"><span class="fa fa-refresh"></span></a>';
        }
        echo "<td>".$status."</td>";
        echo "<td>".$action."</td>";
        echo "<td>".$value['rawDateCreated']."</td>";
        echo "</tr>";
      }
    ?>
  </tbody>
</table>