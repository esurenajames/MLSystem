<table id="example7" class="table table-bordered table-hover" style="width: 100%">
  <thead>
  <tr>
    <th>#</th>
    <th>Address</th>
    <th>Type</th>
    <th>Is Primary?</th>
    <th>Status</th>
    <th>Action</th>
  </tr>
  </thead>
  <tbody>
    <?php
      foreach ($Address as $value)
      {
        echo "<tr>";
        echo "<td>".$value['rowNumber']."</td>";
        echo "<td>".$value['HouseNo'].", ".$value['brgyDesc'].", ".$value['cityMunDesc'].", ".$value['provDesc'].", ".$value['regDesc']."</td>";
        echo "<td>".$value['AddressType']."</td>";
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
          $action = '<a onclick="confirm(\'Are you sure you want to deactivate this address?\', \''.$value['BorrowerAddressHistoryId'].'\', 0,\'BorrowerAddress\')" class="btn btn-danger btn-sm" title="Deactivate"><span class="fa fa-close"></span></a>';
        }
        else if($value['StatusId'] == 1 && $value['IsPrimary'] == 0)
        {
          $action = '<a onclick="confirm(\'Are you sure you want to deactivate this address?\', \''.$value['BorrowerAddressHistoryId'].'\', 0, \'BorrowerAddress\')" class="btn btn-danger btn-sm" title="Deactivate"><span class="fa fa-close"></span></a> <a onclick="confirm(\'Are you sure you want to make this address your primary address?\', \''.$value['BorrowerAddressHistoryId'].'\', 2, \'BorrowerAddress\')" class="btn btn-success btn-sm" title="Make as primary"><span class="fa fa-check-circle"></span></a>';
        }
        else
        {
          $action = '<a onclick="confirm(\'Are you sure you want to re-activate this address?\', \''.$value['BorrowerAddressHistoryId'].'\', 1, \'BorrowerAddress\')" class="btn btn-warning btn-sm" title="Reactivate"><span class="fa fa-refresh"></span></a>';
        }
        echo "<td>".$status."</td>";
        echo "<td>".$action."</td>";
        echo "</tr>";
      }
    ?>
  </tbody>
</table>