<!DOCTYPE html>
<html lang="es">
<head>
  <?php include_once'includes/header.php'; ?>
  <title>La ventanita</title>
</head>
<body>
  <?php include_once 'includes/menu.php'; ?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8 mt-2">
        <button type="button" class="btn btn-primary mt-1 pt-1 pl-2 pb-1 pr-2 nuevoProducto" id="nuevoProducto" data-toggle="modal" data-target=".producto">
          Nuevo
          <i class="fas fa-plus-square"></i>
        </button>
         <button type="button" class="btn btn-info mt-1 pt-1 pl-2 pb-1 pr-2" data-toggle="modal" data-target=".proveedor">
          Proveedor 
          <i class="fas fa-plus-square"></i>
        </button>
        <button type="button" class="btn btn-warning mt-1 pt-1 pl-2 pb-1 pr-2" data-toggle="modal" data-target=".categoria">
          Categoria 
          <i class="fas fa-plus-square"></i>
        </button>
         <button type="button" class="btn btn-danger mt-1 pt-1 pl-2 pb-1 pr-2" data-toggle="modal" data-target=".productoAgotado">
          Producto Agotado 
         <i class="fas fa-minus-circle"></i>
        </button>
        <button type="button" class="btn btn-secondary mt-1 pt-1 pl-2 pb-1 pr-2" id="masVendido" data-toggle="modal" data-target=".productoMasVendido">
          Productos mas Vendidos 
          <i class="fas fa-cart-plus"></i>
        </button>
      </div>
      <div class="col-md-4 mt-2">
        <form class="d-flex" id="buscarProducto">
          <input class="form-control me-sm-2" type="text" id="buscarCodBarras" placeholder="Buscar Producto">
          <button class="btn btn-primary my-2 my-sm-0" type="submit">Buscar</button>
        </form>
      </div>
      <div class="wrap">
        <h1>Catalogo de Productos</h1>
        <div class="store-wrapper">
          <section class="products-list" id="productoslist">
          </section>
        </div>
      </div>
    </div>
  </div>
  <!-- MODAL ADD PRODUCTO -->
  <div class="modal fade producto" id="ModalCerrar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Agregar Producto</h5>
          <button type="button" class="close" id="cerrarRegistro" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-content">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form class="form-inline form-control-file" enctype="multipart/form-data" id="importar_archivo">
                  <input type="hidden" id="id_producto" name="id_producto">  
                  <input type="hidden" name="accion" id="accion">
                  <div class="row">
                    <div class="col">
                      <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="codigo_barras" name="codigo_barras" placeholder="Cidigo de Barras" required="" min="5" pattern="^[0-9]+" step="any">
                        <label for="floatingInput">Codigo de Barras</label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control"  onkeydown="noComa( event )" id="nombre_producto" name="nombre_producto" placeholder="Nombre del Producto" required="">
                        <label for="floatingInput">Nombre del Producto</label>
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="precio_compra" name = "precio_compra" placeholder="Precio Compra" required="" min="0.5" pattern="^[0-9]+" step="any">
                        <label for="floatingInput">Precio Compra</label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="existencia" name = "existencia" placeholder="Existencia" required="" min="0" pattern="^[0-9]+" step="any">
                        <label for="floatingInput">Existencias</label>
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="precio_venta" name="precio_venta" placeholder="Precio Venta" required="" min="1" pattern="^[0-9]+" step="any">
                        <label for="floatingInput">Precio Venta</label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="form-floating mb-3">
                        <select class="form-select" id="proveedores" name = "proveedores"></select>
                        <label for="floatingInput">Proveedores</label>
                      </div>   
                    </div>
                    <div class="col">
                      <div class="form-floating mb-3">
                        <select class="form-select" id="categoria" name = "categoria"></select>
                        <label for="floatingInput">Categorias</label>
                      </div>
                    </div>
                  </div>
                  <div class="photo">
                      <label for="foto">Foto</label>
                      <div class="prevPhoto">
                        <span class="delPhoto notBlock">X</span>
                        <label for="foto"></label>
                      </div>
                      <div class="upimg">
                        <input type="file" id="foto" class="form-control " name="foto" accept=".jpg, .png, .jpeg">
                      </div>
                      <div id="form_alert"></div>
                    </div>
                  <div class="col-md-12 text-center pt-3">
                    <button type="submit" class="btn btn-primary text-center" id="guardarDisabled">Guardar Producto</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- MODAL ADD PLUS PRODUCTOS -->
  <div class="modal fade add_plus_producto" id="ModalCerrar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Agregar Cantidad De productos</h5>
          <button type="button" class="close" id="cerrarModalExitencias" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-content">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form id="form_add_existencia">
                  <div class="row">
                    <div class="col">
                      <div class="form-floating mb-3">
                        <input type="hidden" id="id_producto_add">
                        <input type="number" class="form-control" id="add_existencia" placeholder="Existencia" required="" min="0" pattern="^[0-9]+" step="any">
                        <label for="floatingInput">Existencias</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 text-center pt-3">
                    <button type="submit" class="btn btn-primary text-center" id="agregarProducto">Agregar Producto</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- MODAL ADD PROVEEDOR -->
  <div class="modal fade proveedor" id="ModalCerrar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Agregar Prveedor</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-content">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <div class="col-md-12">
                  <form id="form_proveedores">
                    <div class="row">
                      <div class="col">
                         <div class="form-floating mb-3">
                          <input type="text" class="form-control" id="add_proveedores" placeholder="Proveedor" required="">
                          <label for="floatingInput">Proveedor</label>
                        </div>
                      </div>
                      </div>
                    </div>
                    <div class="col-md-12 text-center pb-3">
                      <button type="submit" class="btn btn-primary text-center">Guardar Proveedor</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="col-md-12" id="contProveedores">
                <table class="table table-hover table-striped" id="contenidoProveedores">
                  <thead>
                    <tr class="table-info">
                      <th scope="col" class="text-center">Proveedor</th>
                      <th scope="col" class="text-center">Acciones</th>
                    </tr>
                  </thead>
                  <tbody id="tableproveedores"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- MODAL ADD PROVEEDOR -->
  <div class="modal fade categoria" id="ModalCerrar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Agregar Categoria</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-content">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
               <div class="col-md-12">
                  <form id="form_categorias">
                    <div class="row">
                      <div class="col">
                         <div class="form-floating mb-3">
                          <input type="text" class="form-control" id="add_categorias" placeholder="Categorias" required="">
                          <label for="floatingInput">Categorias</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12 text-center pb-3">
                      <button type="submit" class="btn btn-primary text-center">Guardar Categorias</button>
                    </div>
                  </form>
               </div>
               <div class="col-md-12" id="contCategorias">
                 <table class="table table-hover table-striped" id="contenidoCategorias">
                    <thead>
                      <tr class="table-info">
                        <th scope="col" class="text-center">Categoria</th>
                        <th scope="col" class="text-center">Acicones</th>
                      </tr>
                    </thead>
                    <tbody id="tablecategorias"></tbody>
                  </table>
               </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- MODAL PRODUCTOS AGOTADOS -->
  <div class="modal fade productoAgotado" id="ModalCerrar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Productos Agotados</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-content">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
               <div class="col-md-12" id="contCategorias">
                <h1 class="text-center">Lista de productos Agotados</h1>
                 <table class="table table-hover table-striped" id="contenidoCategorias">
                    <thead>
                      <tr class="table-info">
                        <th scope="col" class="text-center">Codigo de Barras</th>
                        <th scope="col" class="text-center">Producto</th>
                        <!-- <th scope="col" class="text-center">Existencias</th> -->
                      </tr>
                    </thead>
                    <tbody id="productoAgotado"></tbody>
                  </table>
               </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
 <!-- MODAL PRODUCTOS MAS VENDIDOS -->
 <div class="modal fade productoMasVendido" id="ModalCerrar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Productos Mas Vendidos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-content">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
               <div class="col-md-12" id="contCategorias">
                <h1 class="text-center">Lista de productos Mas vendidos</h1>
                 <table class="table table-hover table-striped" id="contenidoCategorias">
                    <thead>
                      <tr class="table-info">
                        <!-- <th scope="col" class="text-center">Codigo de Barras</th> -->
                        <th scope="col" class="text-center">Producto</th>
                        <th scope="col" class="text-center">Cantidad Vendida</th>
                        <!-- <th scope="col" class="text-center">Producto</th> -->
                        <!-- <th scope="col" class="text-center">Existencias</th> -->
                      </tr>
                    </thead>
                    <tbody id="productoMasVendido"></tbody>
                  </table>
               </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include_once 'includes/footer.php'; ?>
  <!-- SCRIPT PARA CONTROL DE LAS VENTAS -->
  <script src="js/productos_controller.js"></script>
  <script>
    // FUNCINO PARA EVITAR LAS COMAS EN EL NOMBRE
    function noComa( event ) {
      var e = event || window.event;
      var key = e.keyCode || e.which;
      if (key === 188 ) {     
        e.preventDefault();     
      }
    }
  </script>
</body>
</html>