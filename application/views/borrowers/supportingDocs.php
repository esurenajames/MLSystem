
<table id="example9" class="table table-bordered table-hover" style="width: 100%">
  <thead>
  <tr>
    <th>#</th>
    <th>Type</th>
    <th>Description</th>
    <th>File Name</th>
    <th>Date Created</th>
    <th>Status</th>
    <th>Action</th>
  </tr>
  </thead>
  <tbody>
    <?php
      foreach ($Ids as $value) 
      {
        echo "<tr>";
        echo "<td>".$value['rowNumber']."</td>";
        echo "<td>".$value['Name']."</td>";
        echo "<td>".$value['Description']."</td>";
        echo "<td>".$value['Attachment']."</td>";

        if($value['StatusId'] == 1)
        {
          $status = "<span class='badge bg-green'>Active</span>";
          $action = '<a onclick="confirm(\'Are you sure you want to deactivate this document?\', \''.$value['BorrowerIdentificationId'].'\', 0, \'BorrowerDocuments\')" class="btn btn-danger btn-sm" title="Deactivate"><span class="fa fa-close"></span></a> <a class="btn btn-sm btn-success" href="' . base_url() .'/admin_controller/download/2/'.$value['BorrowerIdentificationId'].'" title="Download"><span class="fa fa-download"></span></a>';
        }
        else if($value['StatusId'] == 0)
        {
          $status = "<span class='badge bg-red'>Deactivated</span>";
          $action = '<a onclick="confirm(\'Are you sure you want to re-activate this document?\', \''.$value['BorrowerIdentificationId'].'\', 1, \'BorrowerDocuments\')" class="btn btn-warning btn-sm" title="Deactivate"><span class="fa fa-refresh"></span></a> <a class="btn btn-sm btn-success" href="' . base_url() .'/admin_controller/download/2/'.$value['BorrowerIdentificationId'].'" title="Download"><span class="fa fa-download"></span></a>';
        }
        echo "<td>".$value['DateCreated']."</td>";
        echo "<td>".$status."</td>";
        echo "<td>".$action."</td>";
        echo "</tr>";
      }
    ?>
  </tbody>
</table>