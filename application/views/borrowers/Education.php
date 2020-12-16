
<table id="example10" class="table table-bordered table-hover" style="width: 100%">
  <thead>
  <tr>
    <th>#</th>
    <th>Education Level</th>
    <th>School Name</th>
    <th>Year Graduated</th>
    <th>Date Creation</th>
    <th>Status</th>
    <th>Action</th>
    <th>Action</th>
  </tr>
  </thead>
  <tbody>
    <?php
      if(!empty($Education))
      {
        foreach ($Education as $value) 
        {
          echo "<tr>";
          echo "<td>".$value['rowNumber']."</td>";
          echo "<td>".$value['Name']."</td>";
          echo "<td>".$value['SchoolName']."</td>";
          echo "<td>".$value['YearGraduated']."</td>";

          if($value['StatusId'] == 1)
          {
            $status = "<span class='badge bg-green'>Active</span>";
            $action = '<a onclick="confirm(\'Are you sure you want to deactivate this Education record?\', \''.$value['BorrowerEducationId'].'\', 0, \'BorrowerEducation\')" class="btn btn-danger btn-sm" title="Deactivate"><span class="fa fa-close"></span></a>';
          }
          else if($value['StatusId'] == 0)
          {
            $status = "<span class='badge bg-red'>Deactivated</span>";
            $action = '<a onclick="confirm(\'Are you sure you want to re-activate this Education record?\', \''.$value['BorrowerEducationId'].'\', 1, \'BorrowerEducation\')" class="btn btn-warning btn-sm" title="Re-activate"><span class="fa fa-refresh"></span></a>';
          }
          echo "<td>".$value['DateCreated']."</td>";
          echo "<td>".$status."</td>";
          echo "<td>".$action."</td>";
          echo "<td>".$value['rawDateCreated']."</td>";
          echo "</tr>";
        }
      }
    ?>
  </tbody>
</table>