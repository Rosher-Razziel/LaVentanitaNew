$(document).ready(function(){
  // console.log("YA EXISTO");
  // MOSTRAR PORODUCTOS EN LA TABAL PROCUTOS
  obtenerProductos();
  listarClientes();
  
  function obtenerProductos(){
    $.ajax({
      type: "POST",
      url: "php/productos_fiados_controller.php",
      data: {accion: 'listar', desde: '', hasta: ''},
      success: function (response) {
        // console.log(response);
        
        let productos = JSON.parse(response);
        let template = '', producttt= [], aux = 0, contentTable = '';
        let totaVneta = 0;
        if (productos.length > 0) {
          productos.forEach(producto => {
            product = producto.productos.split(',');
            for (var i = 0; i < product.length; i++) {
              producttt[i] = product[i].split('..');           
            }
            for (var i = 0; i < producttt.length; i++) {
              
              totalProducto = parseInt(producttt[i][3]) * parseFloat(producttt[i][4]);

              contentTable += ` 
                <tr>
                  <td class="text-center">`+ producttt[i][1] +`</td>
                  <td class="text-center">`+ producttt[i][2] +`</td>
                  <td class="text-center">`+ producttt[i][0] +`</td>
                  <!--<td class="text-center">`+ producttt[i][3] +`</td>-->
                  <td class="text-center">`+ producttt[i][3] + `x` + producttt[i][4] +`</td>
                  <td class="text-center"><b>$ `+ totalProducto.toFixed(2) +`</b>
                   <br> <button type="button" class="btn btn-primary">Pagar</button>
                    <button type="button" class="btn btn-info">Abonar</button>
                  </td>
                </tr>
               `;
              totaVneta += totalProducto; 
            }
            template += `
              <tr>
                <td class="text-center"><b>` + producto['nombre_cliente'] +`</b></td>
                <!--<td class="text-center">${producto['hora']}</td>-->
                <td class="text-center">
                   <table class="table table-hover table-striped">
                    <thead>
                      <tr class="table-warning">
                        <th scope="col" class="text-center">Fecha</th>
                        <th scope="col" class="text-center">Hora</th>
                        <th scope="col" class="text-center">Producto</th>
                        <!--<th scope="col" class="text-center">Cantidad</th>-->
                        <th scope="col" class="text-center">Cantidad X Precio</th>
                        <th scope="col" class="text-center">Total Producto</th>
                      </tr>
                    </thead>
                    <tbody>`+contentTable+`</tbody>
                  </table>
                </td>
                <td class="text-center"><b>$ `+totaVneta.toFixed(2)+`</b>
                 <br><button type="button" class="btn btn-success">Liquidar</button>
                </td>
            </tr>`;
            totaVneta = 0; 
            contentTable = '';
            producttt = [];
          });
          $('#tabla_productos').html(template);
          $('#totalVentaDia').html(`<span style="font-size: 20px;">Venta del dia seleccionado</span>
                        <span class="badge bg-warning m-2 p-2" style="font-size: 20px;">`+totaVneta.toFixed(2)+`</span>`);
        }else{
          // Swal.fire(
          //   'Sin ventas',
          //   'No ha realizado ninguna venta.',
          //   'warning'
          // );
        }
      }
    });
  }
  // MOSTRAR PORODUCTOS EN LA TABAL PROCUTOS
  function listarClientes(){
    $.ajax({
      type: "POST",
      url: "php/productos_fiados_controller.php",
      data: {accion: 'listarClientes'},
      success: function (response) {
        // console.log(response);
        let clientes = JSON.parse(response);
        let template = '<option value="0">Selecciona Cliente</option>';
        clientes.forEach(cliente => {
          //================ TEMPLATE DE PROVEEDORES ==============================================
          template += `
            <option value="${cliente['id']}">${cliente['nombre_cliente']}</option>
          `;
        });
        
        $('#clientes').html(template);
      }
    });
  }
  // AGREGAR NUEVO VENTA QUE DEBEN
  $('#fiados').submit(function(e) {
   
    const postData = {
      codigo: $('#codBarras').val(),
      cantidad: $('#cantidad').val(),
      cliente: $('#clientes').val(),
      accion: 'agregarProducto'
    };
    if (postData.cantidad  != ''  && postData.codigo != '') {
      if(postData.codigo != "" && postData.cantidad != "" && postData.cliente != ""){
        $.post('php/productos_fiados_controller.php', postData, function (response){
          console.log(response);
          obtenerProductos();
          // $('#fiados').trigger('reset');
          $('#codBarras').val('');
          $('#cantidad').val('');
          $('#clientes').val('');
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Venta Agregada',
            showConfirmButton: false,
            timer: 1500
          });
        });    
      }
    }
    e.preventDefault();
  });
  // VERIFICAR CANTIDAD DE EXTENCIAS
  $('#cantidad').keyup(function(){
    let cantidad = $('#cantidad').val();
    let codigo = $('#codBarras').val();
    // console.log(cantidad);
    if (cantidad  != '') {
      $.ajax({
        type: "POST",
        url: "php/productos_fiados_controller.php",
        data: {accion: 'verificarExistenciaProductos', id: codigo, cantidad: cantidad},
        success: function (response) {
          // console.log(response);
          if (response == 'true') {
            console.log("SI HAY");
          }else{
            $('#cantidad').val('');
            Swal.fire({
            position: 'center',
            icon: 'warning',
            title: 'No Hay Producto en Stok',
            showConfirmButton: false,
            timer: 1500
          })
          }
        }
      });
    }
  });
});