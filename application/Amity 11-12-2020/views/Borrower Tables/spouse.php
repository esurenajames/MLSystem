
<table id="example4" class="table table-bordered table-hover" style="width: 100%">
  <thead>
  <tr>
    <th>Full Name</th>
    <th>Birthdate</th>
    <th>Gender</th>
  </tr>
  </thead>
  <tbody>
    <?php
      foreach ($Spouse as $value) 
      {
        echo "<tr>";
        echo "<td>".$value['Name']."</td>";
        echo "<td>".$value['DateOfBirth']."</td>";
        echo "<td>".$value['Sex']."</td>";
        echo "</tr>";
      }
    ?>
  </tbody>
</table>