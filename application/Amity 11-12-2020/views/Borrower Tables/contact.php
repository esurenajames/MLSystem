
<table id="example6" class="table table-bordered table-hover" style="width: 100%">
  <thead>
  <tr>
    <th>Type</th>
    <th>Number</th>
    <th>Is Primary?</th>
    <th>Status</th>
    <th>Action</th>
  </tr>
  </thead>
  <tbody>
    <?php
      foreach ($ContactNumber as $value) 
      {
        echo "<tr>";
        echo "<td>".$value['PhoneType']."</td>";
        echo "<td>".$value['Number']."</td>";
        if($value['IsPrimary'] == 1)
        {
          echo "<td>Yes</td>";
        }
        else
        {
          echo "<td>No</td>";
        }

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
          $action = '<a onclick="confirm(\'Are you sure you want to deactivate this contact detail?\', \''.$value['BorrowerContactId'].'\', 0 , \'BorrowerContact\')" class="btn btn-danger btn-sm" title="Deactivate"><span class="fa fa-close"></span></a>';
        }
        else if($value['StatusId'] == 1 && $value['IsPrimary'] == 0)
        {
          $action = '<a onclick="confirm(\'Are you sure you want to deactivate this contact detail?\', \''.$value['BorrowerContactId'].'\', 0 , \'BorrowerContact\')" class="btn btn-danger btn-sm" title="Deactivate"><span class="fa fa-close"></span></a> <a onclick="confirm(\'Are you sure you want to this number your primary contact detail?\', \''.$value['BorrowerContactId'].'\', 2, \'BorrowerContact\')" class="btn btn-success btn-sm" title="Make as primary"><span class="fa fa-check-circle"></span></a>';
        }
        else 
        {
          $action = '<a onclick="confirm(\'Are you sure you want to re-activate this contact detail?\', \''.$value['BorrowerContactId'].'\', 1 , \'BorrowerContact\')" class="btn btn-warning btn-sm" title="Deactivate"><span class="fa fa-refresh"></span></a>';
        }
        echo "<td>".$status."</td>";
        echo "<td>".$action."</td>";
        echo "</tr>";
      }
    ?>
  </tbody>
</table>