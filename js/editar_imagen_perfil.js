$(document).ready(function(){
  $('#image_perfil').change(function (e) {
    console.log("foto subida");
    $('#image_perfil').val('');
    e.preventDefault();
  });
  
  $('#perfil').submit(function(e) {
    let Form = new FormData($('#perfil')[0]);
    
    // console.log(Form);
    $.ajax({
      url: "php/control_documentos_controller.php",
      type: "POST",
      data : Form,
      processData: false,
      contentType: false,
      success: function(response){
        console.log(response);
        if (response == 'true') {
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Importacion Exitosa.',
            showConfirmButton: false,
            timer: 1500
          });
          obtenerContratos(id);
          $('#ordenVenta').val('');
          $('#contrato').val('');
          $('#dropbox').val('');
        }else{
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Error al importar.',
            showConfirmButton: false,
            timer: 1500
          });
        }
        // $('#fileConseptos').val("");    
      }
    });
  
    e.preventDefault();
    // console.log("SUBIR DATOS EXCEL");
  });
})