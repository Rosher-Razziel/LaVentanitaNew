$(document).ready(function () {
  // let productosVenta = new Array();
  let productosVenta = [], cambio = 0, enviando = false;;
  $('#totalProductos').html('<h4 class="card-title text-center" style="font-size: 40px;">$0.00</h4>');
  $("#codBarras").focus();
  $('#cont_productos').on('click', function(e){
    $("#codBarras").focus();
  });
  // ACCION AL DAR ENTER
  $('#codBarras').keypress(function(e) {
    let agregarProducto = false, indice;
    let keycode = (e.keyCode ? e.keyCode : e.which);
    if (keycode == '13') {
      if ($('#codBarras').val().length >= 5) {
        let codigo = $('#codBarras').val();
        $.post('php/ventas_controller.php',{accion:'verificarStok', coddigoBarras: codigo}, function (response){
          // console.log(response);
          if (response == 'true') {
            $.ajax({
              type: "POST",
              url: "php/ventas_controller.php",
              data: {accion: 'producto', codigo: codigo},
              success: function (response) {
                // console.log(response);
                let productos = JSON.parse(response);
              
                if (productosVenta.length === 0) { 
                  // console.log("Está vacío!");
                  productos.forEach(producto => {
                    productosVenta.push([producto.cod_barras,producto.producto, producto.precio, 1]);
                  });
                }else{
                  // console.log("Agregar nuevo o sumar cantidad");
                  for (var i = 0; i < productosVenta.length; i++) {
                    if (productosVenta[i][0] == codigo) {
                      agregarProducto = true;
                      indice = i;
                      break;
                    }
                  }
                  if (agregarProducto) {
                    // productosVenta[indice][3] += 1; 
                    $.ajax({
                      type: "POST",
                      url: "php/ventas_controller.php",
                      data: {accion: 'verificarExistencias', coddigoBarras:  productosVenta[indice][0], cantidadProducto: productosVenta[indice][3]},
                      success: function (response) {
                        // console.log(response);
                        if (response == 'true') {
                          productosVenta[indice][3] += 1;
                          mostrarProductos(); 
                        }else{
                          Swal.fire(
                            'Alerta Producto Agotado',
                            'Ya no tiene mas en stok',
                            'warning'
                          );
                        }
                      }
                    });
                  }else{
                    // console.log("Agregamos NUevo");
                    productos.forEach(producto => {
                      productosVenta.push([producto.cod_barras,producto.producto, producto.precio, 1]);
                    });
                  }
                }
                // console.log(productosVenta);
                mostrarProductos();
                $('#cobrarPedido').prop('disabled', true);
              }
            });
          }else{
            Swal.fire(
              'Alerta Producto Agotado',
              'Ya no tiene mas en stok',
              'warning'
            );
          }
        });
        $('#cod_barras').trigger('reset');
      }
      e.preventDefault();
    }
  });
  // MOSTRAR PORODUCTOS EN LA TABAL PROCUTOS
  function mostrarProductos(){
    let template = '', templateTotal = '';
    let importe = 0, total = 0;
    for (var i = 0; i < productosVenta.length; i++) {
      importe = parseFloat(productosVenta[i][2]) * parseInt(productosVenta[i][3]);
      template += `
        <tr comentarioId="${productosVenta[i][0]}">
          <td class="text-center">${productosVenta[i][3]}</td>
          <td>${productosVenta[i][1]}</td>
          <td class="text-center">${productosVenta[i][2]}</td>
          <td class="text-center">$` + parseFloat(importe).toFixed(2) +`</td>
        </tr>`;
      total += importe;
      cambio = total;
    }
    //REDONDEAR SIEMPRE HACIA EL ENTERO MAS CERCANO
    // total = Math.round(total);
    //REDONDEAR SIEMPRE HACIA ARRIBA
    // total = Math.round(total);
    comision = total * 0.035;
    iva = comision * 0.16;
    comicionTotal = comision + iva + total;
    templateTotal = '<h3 class="card-title text-center" style="font-size: 40px;">$' + total +'</h3> <p>Pago con Tarjeta: $'+comicionTotal.toFixed(2)+'</p>'
    $('#tabla_productos').html(template);
    $('#totalProductos').html(templateTotal)
    $('#total').val(total);
  }
  // CAMBIAR DE COLOR LA FILA
  $('#tabla_productos').on('click','tr', function(evt){
    if(!$(this).hasClass('bg-success')){
      $('.bg-success').each(function(index, element){
        $(element).removeClass('bg-success editarCantidad');
      });
      $(this).addClass('bg-success editarCantidad');
    }
    // console.log("msg");
  });
  // MORSTAT CAMBIO
  $("#recibido").keyup(function(tecla){
      if ($('#recibido').val() >= cambio) {
        $('#cambio').val($('#recibido').val() - cambio);
        $('#cobrarPedido').prop('disabled', false);
      }
      if (tecla.keyCode == 8) {
        if ($('#recibido').val() >= cambio) {
          $('#cambio').val($('#recibido').val() - cambio); 
          $('#cobrarPedido').prop('disabled', false);
        }
      }
  }); 
  // 
  $(document).keydown(function (tecla) {
    // console.log("LETRA ",  String.fromCharCode(tecla.keyCode));
    // console.log("VALOR ",  tecla.keyCode);
    if (tecla.keyCode == 187) {
      let element = $('.editarCantidad')[0];
      let id = $(element).attr('comentarioId');
      for (var i = 0; i < productosVenta.length; i++) {
        if (productosVenta[i][0] == id) {
          agregarProducto = true;
          indice = i;
          break;
        }
      }
      
      $.ajax({
        type: "POST",
        url: "php/ventas_controller.php",
        data: {accion: 'verificarExistencias', coddigoBarras:  productosVenta[indice][0], cantidadProducto: productosVenta[indice][3]},
        success: function (response) {
          // console.log(response);
          if (response == 'true') {
            productosVenta[indice][3] += 1;
            mostrarProductos(); 
          }else{
            Swal.fire(
              'Alerta Producto Agotado',
              'Ya no tiene mas en stok',
              'warning'
            );
          }
        }
      });
      $('#codBarras').val('');
    }else if(tecla.keyCode == 189){
      // console.log("Tecla - Precionada");
      let element = $('.editarCantidad')[0];
      let id = $(element).attr('comentarioId');
      // console.log(id);
      for (var i = 0; i < productosVenta.length; i++) {
        if (productosVenta[i][0] == id) {
          agregarProducto = true;
          indice = i;
          break;
        }
      }
      
      productosVenta[indice][3] -= 1; 
      
      if (productosVenta[indice][3] == 0) {
        productosVenta.splice(indice, 1);
      }
      mostrarProductos();
      
      $('#codBarras').val('');
    }
  });
  // COBRAR VENTA
  $('#cobrar').submit(function(e) {
    const postData = {
      total: $('#total').val(),
      recibido: $('#recibido').val(),
      cambio: $('#cambio').val()
    };
    $("#codBarras").focus();
    if (!enviando) {
      enviando= true;
      if(productosVenta.length > 0 && postData.total != "" && postData.recibido != "" && postData.cambio != "" && parseInt(postData.recibido) >= parseInt(postData.total)){
        $.post('php/ventas_controller.php',{arreglo: productosVenta, accion:'cobrar', total: postData.total,recibido: postData.recibido,cambio: postData.cambio}, function (response){
          // console.log(response);
          productosVenta = [];
          mostrarProductos();
          $('#total').val('');
          $('#recibido').val('');
          $('#cambio').val('');
          $('#cobrarPedido').prop('disabled', true);
          // $("#codBarras").focus();
        });
         Swal.fire({
            position: 'top',
            icon: 'success',
            // title: 'Venta Agregada',
            showConfirmButton: false,
            timer: 1500
          });
          enviando= false;
      }else{
        Swal.fire(
          'Alerta Importe Recibido',
          'El importe recibido es menor al total de la venta',
          'warning'
        );
      }
    } else {
        //Si llega hasta aca significa que pulsaron 2 veces el boton submit
        Swal.fire({
          position: 'center',
          icon: 'warnig',
          title: 'Formulario Ya enviado ESPERE...',
          showConfirmButton: false,
          timer: 1000
        });
        enviando= false;
        // return false;
      }
    e.preventDefault();
  });
});