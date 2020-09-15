
<table id="example4" class="table table-bordered table-hover" style="width: 100%">
  <thead>
  <tr>
    <th>#</th>
    <th>Full Name</th>
    <th>Birthdate</th>
    <th>Gender</th>
    <th>Status</th>
    <th>Action</th>
  </tr>
  </thead>
  <tbody>
    <?php
      foreach ($Spouse as $value) 
      {
        echo "<tr>";
        echo "<td>".$value['rowNumber']."</td>";
        echo "<td>".$value['Name']."</td>";
        echo "<td>".$value['DateOfBirth']."</td>";
        echo "<td>".$value['Sex']."</td>";

        if($value['IsBorrower'] == 0)
        {
          $addBorrower = '<a class="btn btn-primary btn-sm" title="Add to Borrowers"><span class="fa fa-user-plus"></span></a>';
        }
        else
        {
          $addBorrower = '';
        }
        if($value['StatusId'] == 1)
        {
          $status = "<span class='badge bg-green'>Active</span>";
          $action = '<a class="btn btn-sm btn-default" onclick="viewSpouse('.$value['SpouseId'].')" title="View" data-toggle="modal" data-target="#modalBorrowerDetails"><span class="fa fa-info-circle"></span></a> ' . $addBorrower . ' <a onclick="confirm(\'Are you sure you want to deactivate this spouse?\', \''.$value['SpouseId'].'\', 0, \'BorrowerSpouse\')" class="btn btn-danger btn-sm" title="Deactivate"><span class="fa fa-close"></span></a>';
        }
        else if($value['StatusId'] == 0)
        {
          $status = "<span class='badge bg-red'>Deactivated</span>";
          $action = '<a onclick="confirm(\'Are you sure you want to re-activate this spouse?\', \''.$value['SpouseId'].'\', 1, \'BorrowerSpouse\')" class="btn btn-warning btn-sm" title="Re-activate"><span class="fa fa-refresh"></span></a> <a onclick="confirm(\'Are you sure you want to deactivate this id?\', \''.$value['SpouseId'].'\', 0, \'BorrowerSpouse\')" class="btn btn-danger btn-sm" title="Deactivate"><span class="fa fa-close"></span></a>';
        }

        echo "<td>".$status."</td>";
        echo "<td>".$action."</td>";
        echo "</tr>";
      }
    ?>
  </tbody>
</table>