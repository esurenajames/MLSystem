
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Student List with Subjects</h1>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="m-0"></h5>
              </div>
              <div class="card-body">
                <form action="<?php echo base_url(); ?>admin_controller/generateStudentListSubjects/" class="frminsert2" method="post">
                  <div class="row">
                    <div class="col-lg-6">
                      <label>Grade From</label><br>
                      <input class="form-control" type="number" min="0" value="0" max="100" name="GradeFrom">
                    </div>
                    <div class="col-lg-6">
                      <label>Grade To</label><br>
                      <input class="form-control" type="number" min="0" value="0" max="100" name="GradeTo">
                    </div>
                    <div class="col-lg-12">
                      <label>Subjects</label><br>
                      <select class="form-control select2" name="SubjectId">
                        <option>All</option>
                        <?php 
                          foreach ($subjects as $key => $value) 
                          {
                            echo '<option value="'.$value['ClassSubjectId'].'">'.$value['SubjectCode'].' - '.$value['SubjectName'].'</option>'; 
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                  <br>
                  <br>
                  <button type="submit" class="btn btn-primary">Generate Report</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content -->

  </div>

  <?php $this->load->view('includes/footer'); ?>

<script type="text/javascript">

  function refreshPage(){
    var url = '<?php echo base_url()."admin_controller/getStudentList/"; ?>';
    UserTable.ajax.url(url).load();
  }

  function updateRecord(Id, Type, FirstName, MiddleName, LastName, ExtName)
  {
    if(Type == 4) // update role
    {
      $('#txtId').val(Id)
      $('#txtFirstName').val(FirstName)
      $('#txtMiddleName').val(MiddleName)
      $('#txtLastName').val(LastName)
      $('#txtExtName').val(ExtName)
    }
    else
    {
      var text = '';
      if(Type == 1) // deactivate
      {
        text = 'Are you sure you want to deactivate record?';
      }
      else // reactivate
      {
        text = 'Are you sure you want to re-activate record?';
      }

      swal({
        title: 'Confirm',
        text: text,
        type: 'info',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-success',
        confirmButtonText: 'Confirm',
        cancelButtonClass: 'btn btn-secondary'
      }).then(function(){
        $.ajax({                
          url: "<?php echo base_url();?>" + "/admin_controller/updateStudentListRecord",
          method: "POST",
          async: false,
          data:   {
                    Id : Id
                    , Type : Type
                  },  
          dataType: "JSON",
          beforeSend: function(){
              $('.loading').show();
          },
          success: function(data)
          {
            swal({
              title: 'Success!',
              text: 'Record successfully updated!',
              type: 'success',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });
            refreshPage();
          },
          error: function (response) 
          {
            swal({
              title: 'Warning!',
              text: 'Something went wrong, please contact the administrator or refresh page!',
              type: 'warning',
              buttonsStyling: false,
              confirmButtonClass: 'btn btn-primary'
            });
          }
        });
      });
    }
  }

  $(function () {

    $(".frminsert2").on('submit', function (e) {
      e.preventDefault(); 
      swal({
        title: 'Confirm',
        text: 'Are you sure you want to confirm?',
        type: 'info',
        showCancelButton: true,
        buttonsStyling: false,
        confirmButtonClass: 'btn btn-success',
        confirmButtonText: 'Confirm',
        cancelButtonClass: 'btn btn-secondary'
      }).then(function(){
        e.currentTarget.submit();
      });
    });


    $('.select2').select2();

    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/admin_controller/getStudentList/"; ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "StudentNumber" }
                    , { data: "StudentName" }
                    , { data: "CreatedBy" }
                    , { data: "DateCreated" }
                    , { data: "rawDateCreated" }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        return "<span class='badge bg-"+row.Color+"'>"+row.StatusDescription+"</span>";
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        if(row.StatusId == 1){
                          return '<a onclick="updateRecord('+row.Id+', 4, \''+row.FirstName+'\', \''+row.MiddleName+'\', \''+row.LastName+'\', \''+row.ExtName+'\')"  data-toggle="modal" data-target="#modalEdit" class="btn btn-primary" title="Edit"><span class="fa fa-edit"></span></a> <a onclick="updateRecord('+row.Id+', 1)" class="btn btn-danger" title="Deactivate"><span class="fa fa-window-close"></span></a>';
                        }
                        else
                        {
                          return '<a onclick="updateRecord('+row.Id+', 2)" class="btn btn-warning" title="Re-activate"><span class="fa fa-retweet"></span></a>';
                        }
                      }
                    },
      ],
      "aoColumnDefs": [{ "bVisible": false, "aTargets": [4] }],
      "order": [[4, "DESC"]]
    });

  });
</script>