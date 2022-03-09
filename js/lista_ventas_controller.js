$(document).ready(function(){
  // console.log("YA EXISTO VENTAS CONTROLLERS");
  obtenerProductos();
  ventasDiarias();
  corteCaja();
 
  // MOSTRAR PORODUCTOS EN LA TABAL PROCUTOS
  function obtenerProductos( desde = '', hasta = ''){
    $.ajax({
      type: "POST",
      url: "php/lista_ventas_controller.php",
      data: {accion: 'listar', desde: desde, hasta: hasta},
      success: function (response) {
        // console.log(response);
        
        let productos = JSON.parse(response);
        let template = '', producttt= [], contentTable = '';
        let totaVneta = 0;
        if (productos.length > 0) {
          productos.forEach(producto => {
            product = producto.productos.split(',');
            for (var i = 0; i < product.length; i++) {
              producttt[i] = product[i].split('..');           
            }
            for (var i = 0; i < producttt.length; i++) {
              // console.log(producttt[i][3]);
              totalProducto = parseFloat(producttt[i][1]) * parseFloat(producttt[i][3]);
              contentTable += ` 
                <tr>
                  <td class="text-center">`+ producttt[i][0].charAt(0).toUpperCase() + producttt[i][0].slice(1) +`</td>
                  <td class="text-center">`+ producttt[i][1] + `x` + producttt[i][3] +`</td>
                  <td class="text-center">$ `+ totalProducto.toFixed(2) +`</td>
                </tr>
               `;
            }
            template += `
              <tr>
                <td class="text-center">${producto['nombre_venta']}</td>
                <td class="text-center">${producto['hora']}</td>
                <td class="text-center">
                   <table class="table table-hover table-striped" id="contenidoCategorias">
                    <thead>
                      <tr class="table-warning">
                        <th scope="col" class="text-center">Producto</th>
                        <th scope="col" class="text-center">Cantidad X Precio</th>
                        <th scope="col" class="text-center">Total Producto</th>
                      </tr>
                    </thead>
                    <tbody>`+contentTable+`</tbody>
                  </table>
                </td>
                <td class="text-center">${producto['total']}</td>
            </tr>`;
            totaVneta += parseFloat(producto['total']);
            
            contentTable = '';
            producttt = [];
          });
          $('#lista_ventas').html(template);
          $('#totalVentaDia').html(`<span style="font-size: 20px;">Venta del dia seleccionado</span>
                        <span class="badge bg-warning m-2 p-2" style="font-size: 20px;">`+totaVneta.toFixed(2)+`</span>`);
        }else{
          Swal.fire(
            'Sin ventas',
            'No ha realizado ninguna venta.',
            'warning'
          );
        }
      }
    });
  }
  // VENTAS DEL DIA ACTUAL
  function ventasDiarias(){
    $.ajax({
      type: "POST",
      url: "php/lista_ventas_controller.php",
      data: {accion: 'ventaDiaria'},
      success: function (response) {
        // console.log(response);
        
        if (response != 'false') {
          let productos = JSON.parse(response);
          let template = '', importe = 0;
          productos.forEach(producto => {
            importe = producto['importe'] - producto['retirado'];
            
            if(importe >= 1300){
                 template += `<h4 class="card-title"><span class="badge bg-success m-0 p-2">$` + importe.toFixed(2) +`</span></h4>`;
            }else{
                 template += `<h4 class="card-title"><span class="badge bg-warning m-0 p-2">$` + importe.toFixed(2) +`</span></h4>`;
            }        
              $('#dineroDisponible').val(parseFloat(producto['importe']) - parseFloat(producto['retirado']));
              $('#dineroTotal').val(parseFloat(producto['importe']));
          });
          $('#ventaDelDia').html(template);
          // $('#ganancia').val(gananciaDiaria.toFixed(2));
        }
        else{
          let template = '';
          template += `
            <h2 class="card-title"><span class="badge bg-warning">$ 00.00</span></h2>`;
        
            $('#ganancia').val(0);
            $('#ventaDelDia').html(template);
        }
      }
    });
  }
  // BUSCAR POR RANGO DE FECHAS
  $('#fechasFiltrar').submit(function(e) {
    const postData = {
      desde: $('#desde').val(),
      hasta: $('#hasta').val()
    };
    if (postData.desde != '' && postData.hasta != '') {
      if (postData.desde <= postData.hasta) {
          $('#desde').val('');
          $('#hasta').val('');
          obtenerProductos(postData.desde, postData.hasta);
      }else{
        Swal.fire(
          'Alerta',
          'Debe seleccionar una fecha no mayor a la Actual ' + postData.desde,
          'warning'
        );
        $('#desde').val('');
        $('#hasta').val('');
      }
    }else{
      Swal.fire(
        'Alerta Seleccione Fechas',
        'Debe seleccionar un rango de fechas para realizar la busqueda',
        'warning'
      );
      $('#desde').val('');
      $('#hasta').val('');
    }
    e.preventDefault();
  });
  // CORTE DE CAJA
  function corteCaja(){
    $.ajax({
      type: "POST",
      url: "php/lista_ventas_controller.php",
      data: {accion: 'corteCaja'},
      success: function (response) {
        // console.log(response);  
        if (response != 'false') {
          let productos = JSON.parse(response);
          let template = '';
          productos.forEach(producto => {
            template += ` 
              <tr>
                <td class="text-center">${producto['fecha']}</td>
                <td class="text-center">${producto['cambio_inicial']}</td>
                <td class="text-center">${producto['venta_dia']}</td>
                <td class="text-center">${producto['resurtir']}</td>
                <td class="text-center">${producto['ganancia']}</td>
                <!--<td class="text-center">${producto['ganancia_efra']}</td>
                <td class="text-center">${producto['ganancia_roger']}</td>-->
              </tr>`;
          });
          $('#corteCajaDatos').html(template);
        }
      }
    });
  }
  // AGREGAR CORTE DE CAJA
  $('#form_corteCaja').submit(function(e){
    // console.log("msg");
    let dinero =  $('#cajaInicial').val();
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
            retirado = producto['retirado'];
            fecha = producto['fecha'];
          });
          $.post('php/lista_ventas_controller.php', {accion: 'validarRegistro'}, function (response){
            console.log(response);
            if (response == 'true') {
              $.post('php/lista_ventas_controller.php', {dineroInicial: dinero, importe: importe, retirado: retirado, fecha: fecha, accion: 'agregarCorte'}, function (response){
                // console.log(response);
                corteCaja();
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
    e.preventDefault();
  });
  // MOSTAR LOS RETIROS
  $('#mostrarDatos').on('click',function(){
    // console.log("bien");
   
    obtenerRetiros();
    $('#retirarDinero').val(0);
    $('#descripcionRetiro').val("");
    // $('#descripcionRetiro').val('Ejemplo: Comprar productos (2 Cajas de Cigarros, 7 Cocas de 600ml y 2 Cocas de 2.5 LT)');
  });
  // LISTAR LOS RETIROS REALIZADOS
  function obtenerRetiros(){
    $.ajax({
      type: "POST",
      url: "php/lista_ventas_controller.php",
      data: {accion: 'listarRetiros'},
      success: function (response) {
        // console.log(response);  
        if (response != 'false') {
          let productos = JSON.parse(response);
          let template = '';
          productos.forEach(producto => {
            template += ` 
              <tr>
                <td class="text-center" width="15%">
                    <button type="button" class="btn btn-info editar_producto p-1 disabled">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger delete_producto p-1 disabled" type="submit">
                      <i class='fas fa-trash-alt'></i> 
                    </button>
                  </td>
                <td class="text-center" width="15%">${producto['fecha']}</td>
                <td class="text-center" width="10%">${producto['total_retirado']}</td>
                <td class="text-left" width="60%">${producto['descripcion']}</td>
              </tr>`;
          });
          $('#mostrarRetiros').html(template);
        }
      }
    });
  }
  // RETIRAR DINERO
  $('#formRetrirarDinero').submit(function(e){
    const postData = {
      'dineroDisponible': $('#dineroDisponible').val(),
      'retirarDinero': $('#retirarDinero').val(),
      'dineroTotal': $('#dineroTotal').val(),
      'descripcionRetiro': $('#descripcionRetiro').val(),
      'accion': 'agergarRetiro'
    }
    // console.log(postData);
    if(parseFloat(postData.retirarDinero) >= 10){
      if(parseFloat(postData.retirarDinero) <= parseFloat(postData.dineroDisponible)){
        // console.log("CORRECO");
        $.post('php/lista_ventas_controller.php', postData, function (response){
          console.log(response);
          if(response == 'true'){
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'Retiro exitoso',
              text: 'El retiro fue exitoso',
              showConfirmButton: false,
              timer: 2000
            });
            obtenerRetiros();
            ventasDiarias();
            // $('#cerrarRetiro').trigger('click');
          }else{
            Swal.fire({
              position: 'center',
              icon: 'error',
              title: 'Error al retirarl efectivo',
              text: 'Contacte con el administrador para solucionar le error',
              showConfirmButton: false,
              timer: 2000
            });
          }
        });
      }else{
        // console.log("INCORRECTO");
        Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'Error al retirar efectivo',
          text: 'No se puede retirar mas dinero del disponible',
          showConfirmButton: false,
          timer: 2500
        });
        $('#retirarDinero').val($('#dineroDisponible').val())
      }
    }else{
      Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Error al retirar efectivo',
        text: 'No se puede retirar menos de $10.00 pesos ',
        showConfirmButton: false,
        timer: 2500
      });
    }
    e.preventDefault();
  })
});