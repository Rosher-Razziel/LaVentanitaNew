$(document).ready(function(){
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 1000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })
  $('#buscarCodBarras').focus();
  // console.log("YA EXISTO");
  obtenerProductos();
  listarProveedores();
  listarCategorias();
  listarProductosAgotados();
  // BUSCAR PRODCUTO CODUGI DE BARRAS
  $('#buscarProducto').submit(function(e) {
    let search = $('#buscarCodBarras').val();
    
    if (search != null) {
      obtenerProductos(search, 'buscador');
      // console.log($('#buscarCodBarras').val());
      $('#buscarCodBarras').val('');  
    }
    e.preventDefault();
  });
  // MOSTRAR PORODUCTOS EN LA TABAL PROCUTOS
  function obtenerProductos(buscar = "", tipo = ""){
      // MODAL DE CARGA
      Swal.fire({
        title: 'Obteniendo Catalogo',
        allowEscapeKey: false,
        allowOutsideClick: false,
        background: '#fff',
        showConfirmButton: false,
        timerProgressBar: true
      });
      Swal.showLoading();
    $.ajax({
      type: "POST",
      url: "php/productos_controller.php",
      data: {accion: 'listar', buscar: buscar, tipo: tipo},
      success: function (response) {
        // console.log(response);
        
        let productos = JSON.parse(response);
        let template = '';
        if (parseInt(productos.length) > 0) {
          productos.forEach(producto => {
            let texto = '';
            if(producto.existencias == 0){
              color = 'tituloProductosAgotado';
              texto = '(AGOTADO)'
            //   CORREGIR PARA QUE ELIJA LA FOTO POR DEFECTO
            //   if(producto.foto != ''){
            //       foto = producto.foto;
            //   }else{
            //       foto = 'fotos_productos/Sin_Foto.jpg';
            //   }
              agotado = `  
                <div id="abajo">
                  <img src="fotos_productos/Sin_Foto.jpg" alt="${producto['producto']}"/>
                  <img class="sobre" src="fotos_productos/productoAgotado.png" width="auto" height="150" />
                </div>`
            }else{
              color = 'tituloProductos';
              agotado = `<img src="fotos_productos/Sin_Foto.jpg" alt="${producto['producto']}">`;
            }
            
            template += `
              <div class="product-item" category="laptops" productoId="${producto['id']}">
               <span class="`+color+`" id="titulo"><b>${producto['producto']} `+texto+`</b></span>
               `+agotado+`
                <div class="acciones">
                  <button type="button" class="btn btn-info editar_producto" data-toggle="modal" data-target=".producto">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button type="button" class="btn btn-danger delete_producto">
                    <i class='fas fa-trash-alt'></i> 
                  </button>
                  <button type="button" class="btn btn-success add_product_existencia" data-toggle="modal" data-target=".add_plus_producto">
                    <i class="fas fa-plus-square"></i>
                  </button>
                  <br><br>
                  <p>
                    Codigo: <b>${producto['cod_barras']}</b><br>
                    Existencias: <span class="colorPzas"><b>${producto['existencias']} PZAS.</b></span><br>
                    Precio: <b>$${producto['precio_venta']} MXN.</b><br>
                    Categoria: <b>${producto['fk_id_categoria']}</b>
                  </p>
                </div>
              </div>`;
          })
          $('#productoslist').html(template);
        }else{
          Swal.fire(
            'Lista de Productos Vacia',
            'La lista de productos esta vacia o el producto buscado no Existe',
            'warning'
          );
        }
        // ALERTA DE CATALOGO OBTENIDO
        Toast.fire({
          icon: 'success',
          title: 'Catalogo Cargado'
        })
        
      }
    });
  }
  // AGREGAR PROVEEDORES
  function listarProveedores(){
    $.ajax({
      type: "POST",
      url: "php/productos_controller.php",
      data: {accion: 'listarProveedores'},
      success: function (response) {
        // console.log(response);
        let proveedores = JSON.parse(response);
        let template = 'option value="0" id="remover">Selecciona</option>';
        let template2 = '';
        proveedores.forEach(proveedor => {
          //================ TEMPLATE VISTE PROVEDORES ============================================== 
          template2 += `
          <tr proveedorId="${proveedor.id}">
            <td class="text-center">${proveedor['des_proveedor']}</td>
            <td class="text-center">
              <button type="button" class="btn btn-danger pb-1 pl-1 pr-1 pt-1 proveedor_delete"><i class="fas fa-trash-alt"></i></button>
            </td>
          </tr>`;
          //================ TEMPLATE DE PROVEEDORES ==============================================
          template += `
            <option value="${proveedor['id']}">${proveedor['des_proveedor']}</option>
          `;
        });
    
        $('#tableproveedores').html(template2);
        $('#proveedores').html(template);
        $('#editar_proveedores').html(template);
         
      }
    });
  }
  // AGREGAR CATEGPRIAS
  function listarCategorias(){
    $.ajax({
      type: "POST",
      url: "php/productos_controller.php",
      data: {accion: 'listarCategorias'},
      success: function (response) {
        // console.log(response);
        let categorias = JSON.parse(response);
        let template = '';
         let template2 = '';
        categorias.forEach(categoria => {
          //================ TEMPLATE VISTE PROVEDORES ============================================== 
          template2 += `
          <tr categoriaId="${categoria.id}">
            <td class="text-center">${categoria['des_categoria']}</td>
            <td class="text-center">
              <button type="button" class="btn btn-danger pb-1 pl-1 pr-1 pt-1 categoria_delete"><i class="fas fa-trash-alt"></i></button>
            </td>
          </tr>`;
          //================ TEMPLATE DE PROVEEDORES ==============================================
          template += `
            <option value="${categoria['id']}">${categoria['des_categoria']}</option>
          `;
        });
        
        $('#tablecategorias').html(template2);
        $('#categoria').html(template);
        $('#editar_categoria').html(template);
      }
    });
  }
  //LISTAR PRODUCTOS AGOTADOS
  function listarProductosAgotados(){
    $.ajax({
      type: "POST",
      url: "php/ventas_controller.php",
      data: {accion: 'productoAgotado'},
      success: function (response) {
        // console.log(response);
        let productosAgotados = JSON.parse(response);
        let template = '';
        productosAgotados.forEach(proctoAgotado => {
          template += `
          <tr>
            <td class="text-left">${proctoAgotado['cod_barras']}</td>
            <td class="text-left">${proctoAgotado['producto']}</td>
            <!--<td class="text-center">${proctoAgotado['existencias']} Pzas.</td>-->
          </tr>`;
        });
        
        $('#productoAgotado').html(template);
      }
    });
  } 
  // VERIFICAR SI EL CODIGO DE BARRAS YA EXISTE
  $('#codigo_barras').keypress(function(e) {
    let keycode = (e.keyCode ? e.keyCode : e.which);
    if (keycode == '13') {
      if ($('#codigo_barras').val().length >= 5) {
        let codigo = $('#codigo_barras').val();
        // console.log(codigo);
        $.ajax({
          type: "POST",
          url: "php/productos_controller.php",
          data: {accion: 'verificarExistencia', codigo: codigo},
          success: function (response) {
            // console.log(response);
            if (response == "true") {
              Swal.fire(
                'El producto ya existe',
                'Producto existente en la tabla.',
                'warning'
              );
               $('#guardarDisabled').prop('disabled', true);
              $('#codigo_barras').val('');
            }
          }
        });
      }
    }
  });
  // ACTIVAR EL BOTON DE GUARDAR PRODCUTO 
  $(document).on('click','.nuevoProducto', function(){
    $('#guardarDisabled').prop('disabled', false);
  });
  // MOSTRAR EXITENCIAS
  $(document).on('click','.add_product_existencia', function(){
    let element = $(this)[0].parentElement.parentElement;
    let id = $(element).attr('productoId');
    // console.log(id);
    $.post('php/productos_controller.php',{id: id, accion: 'mostrarExistencias'} , function (response){
      // console.log(response);
      
      let productos = JSON.parse(response);
      $('#id_producto_add').val(productos.id);
      $('#add_existencia').val(0);
    });
  });
  // AGREGAR EXIDTENCIAAS DE PRODCUTO
  $('#form_add_existencia').submit(function(e) {
    const postData = {
      id_producto_add: $('#id_producto_add').val(),
      add_existencia: $('#add_existencia').val(),
      accion: 'agregarExistencias'
    };
    $.ajax({
      type: "POST",
      url: "php/productos_controller.php",
      data: {accion: 'verificarExistenciaProductos', id: postData.id_producto_add, cantidad: postData.add_existencia},
      success: function (response) {
        // console.log(response);
        if (response == 'true') {
          if(postData.id_producto_add != "" && postData.add_existencia != ""){
            $.post('php/productos_controller.php', postData, function (response){
              // console.log(response);
              obtenerProductos();
              $('#form_add_existencia').trigger('reset');
              Swal.fire({
                position: 'top',
                icon: 'success',
                title: 'Existencias Agregada',
                showConfirmButton: false,
                timer: 1500
              });
              $("#cerrarModalExitencias").trigger("click");
            });    
          }
        }else{
          Swal.fire({
          position: 'center',
          icon: 'warning',
          title: 'No puedes agregar menos del producto existente.',
          showConfirmButton: false,
          timer: 3000
        })
        }
      }
    });
    e.preventDefault();
  });
  // GUARDAR EL PRODUCTO
  let enviando = false;
  $('#importar_archivo').submit(function(e) {
    let Form = new FormData($('#importar_archivo')[0]);
    
    // console.log(Form);
    
    $.ajax({
      url: "php/productos_controller.php",
      type: "POST",
      data : Form,
      processData: false,
      contentType: false,
      success: function(response){
        // console.log(response);
        if (response == 'true') {
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Registro Exitoso.',
            showConfirmButton: false,
            timer: 1500
          });
          obtenerProductos();  
          $('#cerrarRegistro').trigger('click');
          $("#importar_archivo")[0].reset();
          $('#foto').val('');
          $(".delPhoto").addClass('notBlock');
          $("#img").remove();
        }else{
          Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Error al registrar.',
            showConfirmButton: false,
            timer: 1500
          });
        }
      }
    });
    e.preventDefault();
  });
  //LIMPIAR CAMPOS
  $(document).on('click','.nuevoProducto', function(){
    $("#importar_archivo")[0].reset();
    $("#accion").val('agregarproducto');
  })
  // MOSTAR DATOS EL PRIDUCTO A EDITAR
  $(document).on('click','.editar_producto', function(){
    let element = $(this)[0].parentElement.parentElement;
    let id = $(element).attr('productoId');
    // console.log(id);
    $("#img").remove();
    $(".delPhoto").removeClass('notBlock');
    $("#accion").val('editarProducto');
    $.post('php/productos_controller.php',{id: id, accion: 'mostrarEditarProducto'} , function (response){
      // console.log(response);
      
      let productos = JSON.parse(response);
      $('#id_producto').val(id);
      $('#codigo_barras').val(productos.cod_barras);
      $('#nombre_producto').val(productos.producto);
      $('#precio_compra').val(productos.precio_compra);
      $('#existencia').val(productos.existencias);
      $('#precio_venta').val(productos.precio_venta);
      $("#proveedores option[value='"+ productos.fk_id_proveedor +"']").attr("selected",true);
      $("#categoria option[value='"+ productos.fk_id_categoria +"']").attr("selected",true);
      
      ruta = productos.foto.replace('../', '');
      // console.log(ruta);
      $('#foto').val('');
      $("#img").remove();
  
      $(".prevPhoto").append("<img id='img' src='"+ruta+"'>");
     
    });
    $(".upimg label").remove();
  });
  // EDITAR PRODUCTO
  $('#form_editar_producto').submit(function(e) {
    const postData = {
      id_producto: $('#id_producto').val(),
      codigo_barras: $('#codigo_barras').val(),
      nombre_producto: $('#nombre_producto').val(),
      precio_compra: $('#precio_compra').val(),
      existencia: $('#existencia').val(),
      precio_venta: $('#precio_venta').val(),
      proveedores: $('#proveedores').val(),
      categoria: $('#categoria').val(),
      accion: 'editarProducto'
    };
     $.ajax({
      type: "POST",
      url: "php/productos_controller.php",
      data: {accion: 'verificarExistenciaProductos', id: postData.id_producto, cantidad: postData.existencia},
      success: function (response) {
        // console.log(response);
        if (response == 'true') {
          if(postData.codigo_barras != "" && postData.nombre_producto != "" && postData.precio_compra != "" 
            && postData.existencia != "" && postData.precio_venta != "" && postData.proveedores != "" 
            && postData.categoria != "" && postData.id_producto != ""){
            $.post('php/productos_controller.php', postData, function (response){
              // console.log(response);
              obtenerProductos();
              $('#form_editar_producto').trigger('reset');
              Swal.fire({
                position: 'top',
                icon: 'success',
                title: 'Existencias Agregada',
                showConfirmButton: false,
                timer: 1500
              });
                $('#cerrarModalEditar').trigger('click');
            });    
          }else{
          Swal.fire(
            'Alerta',
            'Todos los campoos son obligatorios.',
            'warning'
          );
        }
        }else{
          Swal.fire(
            'Alerta',
            'No puedes agregar menos del producto existente.',
            'warning'
          );
        }
      }
    });
    e.preventDefault();
  });
  // ELIMINAR PRODUCTO
  $(document).on('click','.delete_producto', function(){
    Swal.fire({
      title: 'Eliminar Producto?',
      text: "Seguro de Eliminar el producto.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Si, Elimniar!'
    }).then((result) => {
      if (result.isConfirmed) {
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('productoId');
        // console.log(id);
        $.post('php/productos_controller.php',{id: id, accion: 'eliminarProducto'} , function (response){
          // console.log(response);
          obtenerProductos();
        });
        // Swal.fire(
        //   'Eliminado!',
        //   'Producto Eliminado Correctamente.',
        //   'success'
        // );
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'Eliminado',
          showConfirmButton: false,
          timer: 1000
        })
      }
    });
  });
  // AGREGAR PROVEEDORES
  $('#form_proveedores').submit(function(e) {
    proveedor = $('#add_proveedores').val();
    if(proveedor != ""){
      $.ajax({
        type: "POST",
        url: "php/productos_controller.php",
        data: {accion: 'verificarExistenciaProveedores', proveedor: proveedor},
        success: function (response) {
          // console.log(response);
          if (response == 'false') {
            if(proveedor != ""){
               $.post('php/productos_controller.php', {proveedor: proveedor, accion: 'add_proveedor'}, function (response){
                  // console.log(response);
                  listarProveedores();
                  $('#form_proveedores').trigger('reset');
                  $('#add_proveedores').val('');
                  Swal.fire(
                    'Proveedor Agregado',
                    'Proveedor Agregado Correctamente.',
                    'success'
                  )
                });       
            }
          }else{
            $('#add_proveedores').val('');
            Swal.fire(
              'Alerta',
              'NO puedes agregar dos veces el proveedor.',
              'warning'
            );
          }
        }
      });
    }
    // console.log("msg");
    e.preventDefault();
  });
  // AGREGAR CATEGORIAS
  $('#form_categorias').submit(function(e) {
    categoria = $('#add_categorias').val();
    if(categoria != ""){
      $.ajax({
        type: "POST",
        url: "php/productos_controller.php",
        data: {accion: 'verificarExistenciaCategoria', categoria: categoria},
        success: function (response) {
          // console.log(response);
          if (response == 'false') {
            if(categoria != ""){
              $.post('php/productos_controller.php', {categoria: categoria, accion: 'add_categoria'}, function (response){
                // console.log(response);
                listarCategorias();
                $('#form_proveedores').trigger('reset');
                $('#add_categorias').val('');
                Swal.fire(
                  'Categoria Agregada',
                  'Categoria Agregada Correctamente.',
                  'success'
                )
              });    
            }
          }else{
            $('#add_categorias').val('');
            Swal.fire(
              'Alerta',
              'NO puedes agregar la misma categoria dos veces.',
              'warning'
            );
          }
        }
      });
    }
    e.preventDefault();
  });
  // ELIMINAR PROVEEDORES
  $(document).on('click','.proveedor_delete', function(){
    Swal.fire({
      title: 'Eliminar Proveedor?',
      text: "Seguro de Eliminar el proveedor al hacerlo se eliminara todos los productos asociados a este provedor",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Si, Elimniar!'
    }).then((result) => {
      if (result.isConfirmed) {
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('proveedorId');
        // console.log(id);
        $.post('php/productos_controller.php',{id: id, accion: 'eliminarProveedor'} , function (response){
            // console.log(response);
            listarProveedores();
            obtenerProductos();
        });
        Swal.fire(
          'Eliminado!',
          'Proveedor Eliminado Correctamente.',
          'success'
        )
      }
    });
  });
  // ELIMINAR CATEGORIAS
  $(document).on('click','.categoria_delete', function(){
    Swal.fire({
      title: 'Eliminar Categoria?',
      text: "Seguro de Eliminar la categoria al hacerlo se eliminaran todos los productos asociados a esta categoria.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Cancelar',
      confirmButtonText: 'Si, Elimniar!'
    }).then((result) => {
      if (result.isConfirmed) {
        let element = $(this)[0].parentElement.parentElement;
        let id = $(element).attr('categoriaId');
        // console.log(id);
        $.post('php/productos_controller.php',{id: id, accion: 'eliminarCategoria'} , function (response){
          // console.log(response);
          listarCategorias();
          obtenerProductos();
        });
        Swal.fire(
          'Eliminado!',
          'Categoria Eliminada Correctamente.',
          'success'
        )
      }
    });
  });
  // OBTENER EL PRECIO DE VNETA RECOMENDADO EN EL PRODUCTO
  $("#precio_compra").keyup(function(tecla){
    let precioCompra = parseFloat($('#precio_compra').val());
    
    if (!isNaN(precioCompra)){
      if (precioCompra >= 0 && precioCompra <= 6.9) {
        precioVenta = precioCompra / 0.7;
      }else if(precioCompra >= 7 && precioCompra <= 19.9){
        precioVenta = precioCompra + 3;
      }else if(precioCompra >= 20 && precioCompra <= 29.9){
        precioVenta = precioCompra + 4;
      }else if(precioCompra >= 30 && precioCompra <= 39.9){
        precioVenta = precioCompra + 5;
      }else if(precioCompra >= 40 && precioCompra <= 69.9){
        precioVenta = precioCompra + 6;
      }else if(precioCompra >= 70 && precioCompra <= 79.9){
        precioVenta = precioCompra + 10;
      }else{
        precioVenta = precioCompra / 0.85;
      }
      precioVenta = precioVenta.toFixed(2);

      precioVenta = redondeado(precioVenta,2);

      $('#precio_venta').val(precioVenta.toFixed(2));
    }else{
      $('#precio_venta').val(0);
    }
    // console.log(precioVenta);
  }); 
  //FUNCION PARA PTENER DECIMALES REDONDEADOS CORRECTAMETNE
  function redondeado(numero, decimales) {
    let factor = Math.pow(10, decimales);
    let resultado = (Math.round(numero*factor)/factor);
    let entero  = Math.trunc(resultado);
    let decimal = resultado - entero;

    if(decimal < 0.24){
      return entero;
    }else if(decimal >= 0.25 && decimal <= 0.69){
      return entero + 0.50;
    }else if(decimal >= 0.70){
      return entero + 1;
    }
  }
  // OBTERNR LOS PRODUCTOS MAS VENDIDOS
  $(document).on('click','#masVendido', function(){
    // console.log("Mas cendidos");
    $.post('php/productos_controller.php', {accion: 'masVendido'}, function (response){
      // console.log(response);
      let productos = JSON.parse(response);
      let template = '';
      if (parseInt(productos.length) > 0) {
        productos.forEach(producto => {
          template += `
            <tr productoId="${producto.id}">
              <td class="text-left">${producto['producto']}</td>
              <td class="text-center">${producto['TOTALVENTDIDO']}</td>
          </tr>`;
        });
        $('#productoMasVendido').html(template);
      }
    });   
  });
  // FUNCIONES DE LA FOTO
  //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------
  $("#foto").on("change",function(){
    var uploadFoto = document.getElementById("foto").value;
    var foto       = document.getElementById("foto").files;
    var nav = window.URL || window.webkitURL;
    var contactAlert = document.getElementById('form_alert');
      
    if(uploadFoto !=''){
      var type = foto[0].type;
      var name = foto[0].name;
      if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png'){
        contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';                        
        $("#img").remove();
        $(".delPhoto").addClass('notBlock');
        $('#foto').val('');
        return false;
      }else{  
        contactAlert.innerHTML='';
        $("#img").remove();
        $(".delPhoto").removeClass('notBlock');
        var objeto_url = nav.createObjectURL(this.files[0]);
        $(".prevPhoto").append("<img id='img' src="+objeto_url+">");
        $(".upimg label").remove();
                      
      }
    }else{
      alert("No selecciono foto");
      $("#img").remove();
    }              
  });
  $('.delPhoto').click(function(){
    $('#foto').val('');
    $(".delPhoto").addClass('notBlock');
    $("#img").remove();
  });
});