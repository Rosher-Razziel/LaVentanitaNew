$(document).ready(function(){
  // console.log("YA EXISTO");
  // muestraReloj();
  // obtenerTipoUser();
  $('#buscarprecio').submit(function(e) {
    let codBarras =  $('#codigoBarrasProducto').val();
    
    if(codBarras != ""){
      $.post('php/ventas_controller.php',{accion:'buscarProducto', codBarras: codBarras}, function (response){
        // console.log(response);
        let productos = JSON.parse(response);
        if (productos != '') {
          productos.forEach(producto => {
            Swal.fire(
              `${producto['producto']} $${producto['precio_venta']}`,
              `Gracias por su consulta.`,
              'success'
            );
          });
          $('#codigoBarrasProducto').val('');
        }else{
          Swal.fire(
            'Alerta El producto No existe en la Base de Datos',
            'Inserte un codigo de barras valido o agregue el producto al inventario',
            'warning'
          );
          $('#codigoBarrasProducto').val('');
        }
      });
    }else{
      Swal.fire(
        'Alerta Codigo de barras Vacio',
        'Inserte un codigo de barras valido',
        'warning'
      )
    }
    e.preventDefault();
  });
  // MOSTRAR MENI POR ROL DE USUARIO
  // function obtenerTipoUser(){
  //   $.ajax({
  //     type: "POST",
  //     url: "php/ventas_controller.php",
  //     data: {accion: 'tipoUser'},
  //     success: function (response) {
  //       console.log(response);
        
  //       // let productos = JSON.parse(response);
  //       if (productos.length > 0) {
  //         // productos.forEach(producto => {
  //         // });
  //       }else{
  //         // Swal.fire(
  //         //   'Sin ventas',
  //         //   'No ha realizado ninguna venta.',
  //         //   'warning'
  //         // );
  //       }
  //     }
  //   });
  // }
  // RELOJ
  function muestraReloj() {
    var fechaHora = new Date();
    var horas = fechaHora.getHours();
    var minutos = fechaHora.getMinutes();
    var segundos = fechaHora.getSeconds();
    	
    var horaActual = fechaHora.getHours() + ':' + fechaHora.getMinutes() + ':' + fechaHora.getSeconds();
    if(horas < 10) { 
      horas = '0' + horas; 
    }
    if(minutos < 10) { 
      minutos = '0' + minutos;
    }
    
    if(segundos < 10) { 
      segundos = '0' + segundos; 
    }
    if (minutos == 59 && segundos == 59) {
      // console.log("ALERTA: " + minutos);
      $.post('php/ventas_controller.php',{accion:'productoAgotado'}, function (response){
        // console.log(response);
        let productos = JSON.parse(response);
        let template = '';
        if (productos != '') {
          productos.forEach(producto => {
            template += ` 
              <tr>
                <td>${producto['cod_barras']}</td>
                <td>${producto['producto']}</td>
              </tr>
             `;
          });
          tableAgotador = `
            <table class="table table-hover table-striped">
              <thead>
                <tr class="table-info">
                  <th scope="col" class="text-center">Codigo de Barras</th>
                  <th scope="col" class="text-center">Nombre Producto</th>
                </tr>
              </thead>
              <tbody>` + template + `</tbody>
            </table>
           `;
          Swal.fire(
              `Productos Agotados`,
              tableAgotador,
              'error'
            );
        }
      });
    }else if(horas == 23 && minutos == 59 && segundos == 59){
      //console.log("ALERTA: " + horas, "Tipo: " + typeof(horas));
      //console.log("ALERTA: " + minutos, "Tipo: " + typeof(minutos));
      //console.log("ALERTA: " + segundos, "Tipo: " + typeof(segundos));
      $.ajax({
        type: "POST",
        url: "php/lista_ventas_controller.php",
        data: {accion: 'ventaDiaria'},
        success: function (response) {
          // console.log(response);  
          if (response != 'false') {
            let productos = JSON.parse(response);
            let importe = 0, fecha = '';
  
            productos.forEach(producto => {
              importe = producto['importe'];
              fecha = producto['fecha'];
            });
  
            $.post('php/lista_ventas_controller.php', {accion: 'validarRegistro'}, function (response){
              // console.log(response);
              if (response == 'true') {
                $.post('php/lista_ventas_controller.php', {dineroInicial: 200, importe: importe, fecha: fecha, accion: 'agregarCorte'}, function (response){
                  // console.log(response);
                  Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Agregado Correctamente',
                    showConfirmButton: false,
                    timer: 2000
                  });
                  $('#cajaInicial').val('');
                });   
              }else{
                Swal.fire({
                  position: 'center',
                  icon: 'error',
                  title: 'Solo se puede hacer un corte de caja al dia.',
                  showConfirmButton: false,
                  timer: 2000
                });
                $('#cajaInicial').val('');
              }
            });  
          }
        }
      });
    }
    // document.getElementById("reloj").innerHTML = horas+':'+minutos+':'+segundos;
  }
  window.onload = function() {
    setInterval(muestraReloj, 1000);
  }
});