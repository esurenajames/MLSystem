<table id="example3" class="table table-bordered table-hover" style="width: 100%">
  <thead>
  <tr>
    <th>Full Name</th>
    <th>Employer/Business</th>
    <th>Cellphone No.</th>
  </tr>
  </thead>
  <tbody>
    <?php
      if($CoMaker != 0)
      {
        foreach ($CoMaker as $value) 
        {
          echo "<tr>";
          echo "<td>".$value['Name']."</td>";
          echo "<td>".$value['Employer']."</td>";
          echo "<td>".$value['MobileNo']."</td>";
          echo "</tr>";
        }
      }
    ?>
  </tbody>
</table>