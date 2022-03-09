$(document).ready(function () {
  // console.log("Hola que tal");
  //================================================= AGREGAR CONCEPTOS ==========================================
  $('#form_acceso').submit(function(e) {
    const postData = {
      user: $('#user').val(),
      clave: $('#clave').val(),
    };
    if(postData.partida != "" && postData.descripcion != ""){
      $.post('php/login_controller.php', postData, function (response){
        // console.log(response);
        if (response == 'true') {
          window.location.href = 'principal.php';
        }else{
          Swal.fire({
            icon: 'error',
             title: 'Oops... Usuario o Contraseña Incorrectos',
            text: 'Verifica que tu usuario y contraseña estes escritos correctamente.',
            footer: '<a href="">Olvide mi Contraseña?</a>'
          });
        }
        $('#form_acceso').trigger('reset');
      });    
    }
    // console.log("ENVIADO", postData.user, postData.clave);
    e.preventDefault();
  });
});