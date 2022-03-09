<!DOCTYPE html>
<html lang="es">
<head>
  <?php include_once'includes/header.php'; ?>
  <title>La ventanita</title>
</head>
<body>
  
  <?php include_once 'includes/menu.php'; ?>
  <div class="container">
    <div class="row">
      <div class="col-md-8 mt-4">
        <div class="card border-primary mb-3" style="max-width: 100rem;">
          <div class="card-header"><h4 class="text-center">Ventas del Dia</h4></div>
          <div class="card-body">
            <form id="corteCaja" class="form-group">
              <div class="form-group">
                <div class="row">
                  <div class="col-md-8">
                     <h3 class="text-center">Venta <?php echo date("d/m/Y"); ?></h3>
                  </div>
                  <div class="col-md-4" id="ventaDelDia">
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-4 mt-4">
        <div class="card border-primary mb-3" style="max-width: 25rem;">
          <div class="card-header"><h4 class="text-center">Corte de Caja</h4></div>
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <form id="corteCaja1" class="form-group">
                  <div class="form-group">
                    <div class="d-grid gap-2">
                      <button class="btn btn-lg btn-primary mt-1 mb-0" type="button" data-toggle="modal" data-target=".corte">Corte</button>
                    </div>
                  </div>
                </form>
              </div>
              <div class="col-6">
                <form id="retirar" class="form-group">
                  <div class="form-group">
                    <div class="d-grid gap-2">
                      <button class="btn btn-lg btn-primary mt-1 mb-0" type="button" id="mostrarDatos" data-toggle="modal" data-target=".retirar">Retirar</button>
                    </div>
                  </div>
                </form>
              </div> 
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 table-responsive">
        <table class="table table-hover table-striped table-responsive">
            <thead>
              <tr class="table-success">
                <td colspan="5" class="text-center">
                  <form class="form-group form-inline" id="fechasFiltrar">
                    <div class="row">
                      <div class="col-md-3"> 
                        <input class="form-control" type="date" id="desde" placeholder="Seleciona Fecha">
                      </div>
                      <div class="col-md-3">
                        <input class="form-control" type="date" id="hasta" placeholder="Seleciona Fecha">
                      </div>
                      <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                      </div>
                      <div class="col-md-4" id="totalVentaDia">
                        
                      </div>
                    </div>
                  </form>
                </td>
              </tr>
              <tr class="table-info">
                <th scope="col" class="text-center">Fecha Compra</th>
                <th scope="col" class="text-center">Hora</th>
                <th scope="col" class="text-center">Productos Comprados</th>
                <th scope="col" class="text-center">Total de Compra</th>
                <!-- <th scope="col" class="text-center">Importe Total</th>
                <th scope="col" class="text-center">Fecha</th> -->
              </tr>
            </thead>
            <tbody id="lista_ventas"></tbody>
          </table>
      </div>
    </div>
  </div>
  <!-- ALERTA PRODUCTOS AGOTADOS -->
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
                        <th scope="col" class="text-center">Categoria</th>
                        <th scope="col" class="text-center">Acicones</th>
                        <th scope="col" class="text-center">Categoria</th>
                        <th scope="col" class="text-center">Acicones</th>
                        <th scope="col" class="text-center">Categoria</th>
                        <th scope="col" class="text-center">Acicones</th>
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
  <!-- MODAL CORTE DE CAJA -->
  <div class="modal fade corte" id="ModalCerrar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">CORTE DE CAJA</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-content">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form id="form_corteCaja">
                  <div class="row">
                    <div class="col">
                      <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="cajaInicial" placeholder="Existencia" required="" min="0" pattern="^[0-9]+" step="any">
                        <label for="floatingInput">Caja Inicial</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 text-center pt-3">
                    <button type="submit" class="btn btn-primary text-center">GUARDAR CORTE DE CAJA</button>
                  </div>
                </form>
                <div class="col-md-12 pt-3" id="corteCajaContenido">
                  <table class="table table-hover table-striped">
                    <thead>
                      <tr class="table-info">
                        <th scope="col" class="text-center">Fecha</th>
                        <th scope="col" class="text-center">Cambio Inicial</th>
                        <th scope="col" class="text-center">Venta del Dia</th>
                        <th scope="col" class="text-center">Resustir</th>
                        <th scope="col" class="text-center">Ganancia</th>
                        <!-- <th scope="col" class="text-center">Ganancia Efra</th>
                        <th scope="col" class="text-center">Ganancia Roger</th> -->
                      </tr>
                    </thead>
                    <tbody id="corteCajaDatos"></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- MODAL RETIRAR DINERO -->
  <div class="modal fade retirar" id="ModalCerrar" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">RETIRAR DINERO</h5>
          <button type="button" class="close" id="cerrarRetiro" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-content">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form id="formRetrirarDinero">
                  <div class="row">
                    <input type="hidden" id="dineroTotal">
                    <div class="col">
                      <div class="form-floating mb-3">
                        <input type="number" class="form-control" value="100.00" disabled id="dineroDisponible" required min="0" pattern="^[0-9]+" step="any">
                        <label for="floatingInput">Dinero Disponible</label>
                      </div>
                    </div>
                    <div class="col">
                      <div class="form-floating mb-3">
                        <input type="number" class="form-control" id="retirarDinero" required="" min="0" pattern="^[0-9]+" step="any">
                        <label for="floatingInput">Cantidad a Retirar</label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="descripcionRetiro" required="">
                        <label for="floatingInput">Descripciopn de retiro</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 text-center pt-3">
                    <button type="submit" class="btn btn-primary text-center">RETIRAR DINERO</button>
                  </div>
                </form>
                <div class="col-md-12 pt-3" id="corteCajaContenido">
                  <table class="table table-hover table-striped">
                    <thead>
                      <tr class="table-info">
                      <th scope="col" class="text-center">Acciones</th>
                        <th scope="col" class="text-center">Fecha</th>
                        <th scope="col" class="text-center">Total Retirado</th>
                        <!-- <th scope="col" class="text-center">Total Restante</th> -->
                        <th scope="col" class="text-center">Descipcion Retiro</th>
                      </tr>
                    </thead>
                    <tbody id="mostrarRetiros"></tbody>
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
  <script src="js/lista_ventas_controller.js"></script>
  <script>
    // $(".productoAgotado").modal("show");
  </script>
</body>
</html>