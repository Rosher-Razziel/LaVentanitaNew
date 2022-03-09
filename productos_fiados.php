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
      <!-- TABLA DE PRODUCTOS -->
      <div class="col-md-9 pt-2">
        <div class="col-md-12 table-responsive" id="cont_productos_fiados">
          <table class="table" id="contenidoProductos">
            <thead>
              <tr class="table-success">
                <td colspan="5" class="text-center">
                  <form class="form-group form-inline" id="fiados">
                    <div class="row">
                      <div class="col-md-3"> 
                        <input type="text" class="form-control" id="codBarras" name="codBarras" placeholder="Codigo de Barras">
                      </div>
                      <div class="col-md-3">
                        <input type="text" class="form-control" id="cantidad" name="cantidad" min="1" placeholder="Cantidad">
                      </div>
                       <div class="col-md-3">
                        <select class="form-select" id="clientes">
                        </select>
                      </div>
                      <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Guardar Venta</button>
                      </div>
                    </div>
                  </form>
                </td>
              </tr>
              <tr class="table-info">
                <th scope="col" class="text-center">Cliente</th>
                <th scope="col" class="text-center">Productos</th>
                <th scope="col" class="text-center">Total</th>
              </tr>
            </thead>
            <tbody id="tabla_productos"></tbody>
          </table>
        </div>
      </div>
      <!-- TABLA DE TOTAL Y MAS -->
      <div class="col-md-3">
        <div class="card border-primary mb-3 mt-2" style="max-width: 50rem;">
          <div class="card-header text-center bg-warning p-0 m-0" style="font-size: 40px;">
            <b>TOTAL</b>
          </div>
          <div class="card-body" id="totalProductos">
          </div>
        </div>
        <div class="card border-primary mb-3" style="max-width: 50rem;">
          <div class="card-body">
            <form id="cobrar" class="form">
              <div class="form-group">
                <label class="col-form-label col-form-label-lg">Importe Requerido</label>
                <input class="form-control form-control-lg" id="total" type="number" placeholder="Importe Requerido" disabled="">
                <label class="col-form-label col-form-label-lg">Importe Recibido</label>
                <input class="form-control form-control-lg" id="recibido" type="number" placeholder="Importe Recibido" step="any">
                <label class="col-form-label col-form-label-lg">Cambio</label>
                <input class="form-control form-control-lg" id="cambio" type="bumber" placeholder="Cambio" disabled="">
                <div class="d-grid gap-2 mt-3">
                  <button type="submit" class="btn btn-primary" id="cobrarPedido" disabled="">Cobrar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- <div class="col-12 mt-4 text-center">
        <h2>REPARACION DE PAGINA DE PRODUCTOS FIADOS</h2>
        <img src="img/reparacion.gif" alt="Reparacion de pagina">
      </div> -->
    </div>
  </div>
  <?php include_once 'includes/footer.php'; ?>
  <!-- SCRIPT PARA CONTROL DE LAS VENTAS -->
  <script src="js/productos_fiados_controller.js"></script>
</body>
</html>