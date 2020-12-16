
<table id="dtblAudit" class="table table-bordered table-hover" style="width: 100%">
  <thead>
  <tr>
    <th>#</th>
    <th>Description</th>
    <th>Created By</th>
    <th>Date Created</th>
    <th>Date Created</th>
  </tr>
  </thead>
  <tbody>
    <?php
      $row = 0;
      foreach ($Audit as $value) 
      {
        $row = $row + 1;
        echo "<tr>";
        echo "<td>".$row."</td>";
        echo "<td>".$value['Description']."</td>";
        echo "<td>".$value['LastName'].", ".$value['FirstName']." ".$value['MiddleInitial']."</td>";
        echo "<td>".$value['DateCreated']."</td>";
        echo "<td>".$value['rawDateCreated']."</td>";
        echo "</tr>";
      }
    ?>
  </tbody>
</table>