<table id="example3" class="table table-bordered table-hover" style="width: 100%">
  <thead>
  <tr>
    <th>Full Name</th>
    <th>Employer/Business</th>
    <th>Cellphone No.</th>
    <th>Status</th>
    <th>Action</th>
  </tr>
  </thead>
  <tbody>
    <?php
      if($CoMaker != 0)
      {
        foreach ($CoMaker as $value) 
        {
          if($value['StatusId'] == 1)
          {
            $status = "<span class='badge bg-green'>Active</span>";
            $action = '<a onclick="viewComaker('.$value['BorrowerComakerId'].')" class="btn btn-sm btn-default" data-toggle="modal" data-target="#modalNewCoMaker" title="View"><span class="fa fa-info-circle"></span></a> <a class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalNewCoMaker" title="Edit"><span class="fa fa-edit"></span></a>  <a onclick="confirm(\'Are you sure you want to deactivate this reference?\', \''.$value['BorrowerComakerId'].'\', 0, \'BorrowerCoMaker\')" class="btn btn-sm btn-danger" title="Deactivate"><span class="fa fa-close"></span></a>';
          }
          else
          {
            $status = "<span class='badge bg-red'>Deactivated</span>";
            $action = '<a onclick="confirm(\'Are you sure you want to re-activate this reference?\', \''.$value['BorrowerComakerId'].'\', 1, \'BorrowerCoMaker\')" class="btn btn-warning" title="Re-Activate"><span class="fa fa-refresh"></span></a>';
          }
          echo "<tr>";
          echo "<td>".$value['Name']."</td>";
          echo "<td>".$value['Employer']."</td>";
          echo "<td>".$value['MobileNo']."</td>";
          echo "<td>".$status."</td>";
          echo "<td>".$action."</td>";
          echo "</tr>";
        }
      }
    ?>
  </tbody>
</table>