  var base_url = window.location.origin;

  var baseUrl = base_url + '/ELendingTool'; // initial url for javascripts


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