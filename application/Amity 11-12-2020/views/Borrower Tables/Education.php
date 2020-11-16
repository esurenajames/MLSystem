
<table id="example4" class="table table-bordered table-hover" style="width: 100%">
  <thead>
  <tr>
    <th>Education Level</th>
    <th>School Name</th>
    <th>Year Graduated</th>
  </tr>
  </thead>
  <tbody>
    <?php
      foreach ($Education as $value) 
      {
        echo "<tr>";
        echo "<td>".$value['Level']."</td>";
        echo "<td>".$value['SchoolName']."</td>";
        echo "<td>".$value['YearGraduated']."</td>";
        echo "</tr>";
      }
    ?>
  </tbody>
</table>