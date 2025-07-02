
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Exam Details <a href="<?php echo base_url() ?>home/facultyClassDetails/<?php print_r($detail['ClassId']) ?>"><?php print_r($detail['ClassName']) ?> - <?php print_r($detail['SubjectCode']) ?></a></h1>
          </div>
        </div>
      </div>
    </div>

      <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Category</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url(); ?>admin_controller/addExamCategory/<?php print_r($this->uri->segment(3)); ?>" class="frminsert2" method="post">
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <h6>Category Name</h6>
                    <input class="form-control" name="CategoryName" required="" type="text">
                  </div>
                  <div class="col-md-12">
                    <h6>Percentage</h6>
                    <input class="form-control" name="Percentage" required="" type="number" min="0" max="100">
                  </div>
                  <div class="col-md-12">
                    <h6>Instructions</h6>
                    <textarea class="form-control" name="Instructions"></textarea>
                  </div>
                </div>
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="modal fade" id="modalSubcategory">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add Sub-Category</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo base_url(); ?>admin_controller/addExamSubCategory/<?php print_r($this->uri->segment(3)); ?>" class="frminsert2" method="post">
              <div class="modal-body">
                <div class="float-right">
                  <a  class="btn btn-success" title="Add Row" onclick="addRow()">Add Row</a>
                </div>
                <br>
                <br>
                <div class="row">
                  <input type="hidden" class="form-control" id="txtId" required="" name="CategoryId">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <th>Sub-Category Name</th>
                      <th>Percentage</th>
                      <th>Instructions</th>
                      <th>Action</th>
                    </thead>
                    <tbody>
                      <tr id="row1">
                        <td>
                            <input type="hidden" class="form-control" value="1" required="" name="rowCount[]">
                            <input type="text" class="form-control" required="" name="SubName[]">
                        </td>
                        <td><input class="form-control" name="Percentage[]" required="" type="number" min="0" max="100"></td>
                        <td><textarea class="form-control" name="Instructions[]"></textarea></td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>
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
                <h5 class="m-0">Subject Details</h5>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3">
                    <label>Subject Code</label>
                    <h6><?php print_r($detail['SubjectCode']) ?></h6>
                  </div>
                  <div class="col-md-3">
                    <label>Subject Name</label>
                    <h6><?php print_r($detail['SubjectName']) ?></h6>
                  </div>
                  <div class="col-md-3">
                    <label>Subject Description</label>
                    <h6><?php print_r($detail['SubjectDescription']) ?></h6>
                  </div>
                  <div class="col-md-3">
                    <label>Max no. of Students</label>
                    <h6><?php print_r($detail['MaxStudents']) ?></h6>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <h5 class="m-0">Sub-categories for category <strong><a href="<?php echo base_url() ?>home/examDetails/<?php print_r($detail['ExamId']) ?>/<?php print_r($detail['ClassSubjectId']) ?>"><?php print_r($detail['Category']); ?> (<?php print_r($detail['Percentage']); ?>%)</a></strong> in exam #<?php print_r($detail['ExamCode']); ?></h5>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Sub Category</th>
                    <th># of Questions</th>
                    <th>Instructions</th>
                    <th>Percentage</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                </table>
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

  var rowNo = 2;
  function refreshPage(){
    var url = '<?php echo base_url()."/admin_controller/getExamSubCategories/".$this->uri->segment(4); ?>';
    UserTable.ajax.url(url).load();
  }

  function updateRecord(Id)
  {
    text = 'Are you sure you want to remove record?';

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
        url: "<?php echo base_url();?>" + "/admin_controller/removeSubCategory",
        method: "POST",
        async: false,
        data:   {
                  Id : Id
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

  function addRow()
  {
    newRow = '';
    newRow += '<tr id="row'+rowNo+'">';
    newRow += '<td><input type="hidden" class="form-control" value="'+rowNo+'" required="" name="rowCount[]"><input type="text" class="form-control" required="" name="SubName[]"></td>';
    newRow += '<td><input class="form-control" name="Percentage[]" required="" type="number" min="0" max="100"></td>';
    newRow += '<td><textarea class="form-control" name="Instructions[]"></textarea></td>';
    newRow += '<td>'+rowNo+'<a class="btn btn-danger" title="Remove" onclick="removeRow('+rowNo+')"><span class="fa fa-window-close"></span></a></td>';
    newRow += '</tr>';
    $('#example2').append(newRow);
    rowNo++;
  }

  function addSubCategory (Id)
  {
    $('#txtId').val(Id)
  }

  function removeRow(id)
  {
    $('#row'+id+'').remove();
    rowNo--;
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
    $('#selectStudent').select2({
      maximumSelectionLength: <?php print_r($detail['MaxStudents']) ?>
    })

    UserTable = $('#example1').DataTable({
      "pageLength": 10,
      "ajax": { url: '<?php echo base_url()."/admin_controller/getExamSubCategories/".$this->uri->segment(4); ?>', type: 'POST', "dataSrc": "" },
      "columns": [  { data: "SubCategory" }
                    , { data: "TotalQuestions" }
                    , { data: "Instructions" }
                    , {
                      data: "SubCategoryId", "render": function (data, type, row) {
                        return '<?php echo $detail['PercentageBySubCategory']; ?>';
                      }
                    }
                    , {
                      data: "StatusId", "render": function (data, type, row) {
                        return '<a href="<?php echo base_url() ?>home/subCategoryDetails/'+<?php echo $this->uri->segment(3); ?>+'/'+row.CategoryId+'/'+row.SubCategoryId+'" class="btn btn-success" title="Questionnaire"><span class="fa fa-question-circle"></span></a> <a onclick="updateRecord('+row.SubCategoryId+')" class="btn btn-danger" title="Remove"><span class="fa fa-window-close"></span></a>';
                      }
                    },
      ],
      // "aoColumnDefs": [{ "bVisible": false, "aTargets": [0] }],
      "order": [[0, "asc"]]
    });

  });
</script>