<table id="example2" class="table table-bordered table-hover" style="width: 100%">
  <thead>
  <tr>
    <th>Name</th>
    <th>Address</th>
    <th>Contact Number</th>
  </tr>
  </thead>
  <tbody>
    <?php
      if($Reference != 0)
      {
        foreach ($Reference as $value) 
        {
          if($value['StatusId'] == 1)
          {
            $action = '<a onclick="confirm(\'Are you sure you want to deactivate this reference?\', \''.$value['ReferenceId'].'\', 0, \'BorrowerPersonal\')" class="btn btn-danger" title="Deactivate"><span class="fa fa-close"></span></a>';
            $status = "<span class='badge bg-green'>Active</span>";
          }
          else
          {
            $action = '<a onclick="confirm(\'Are you sure you want to re-activate this reference?\', \''.$value['ReferenceId'].'\', 1, \'BorrowerPersonal\')" class="btn btn-warning" title="Re-Activate"><span class="fa fa-refresh"></span></a>';
            $status = "<span class='badge bg-red'>Deactivated</span>";
          }
          echo "<tr>";
          echo "<td>".$value['Name']."</td>";
          echo "<td>".$value['Address']."</td>";
          echo "<td>".$value['ContactNumber']."</td>";
          echo "</tr>";
        }
      }
    ?>
  </tbody>
</table>