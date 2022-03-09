<!DOCTYPE html>
<html lang="es">
<head>
  <?php include_once'includes/header.php'; ?>
  <title>La ventanita</title>
</head>
<body>
  
  <?php include_once 'includes/menu.php'; ?>
  <div class="container-fluid">
    <div class="row justify-content-center align-items-center">
      <div class="col-md-12">
        <!-- <h3 class="text-center">VENTAS POR DIA</h3> -->
        <canvas id="grafica" width="200" height="90"></canvas>
      </div>
      <div class="col-md-12 pt-1">
        <!-- <h3 class="text-center">VENTA POR SEMANA</h3> -->
         <canvas id="semanal" width="200" height="90"></canvas>
      </div>
      <div class="col-md-12 pt-1">
        <!-- <h3 class="text-center">VENTA POR SEMANA</h3> -->
         <canvas id="prueba" width="200" height="90"></canvas>
      </div>
      <div class="col-md-12">
        <!-- <h3 class="text-center">VENTAS POR DIA</h3> -->
        <canvas id="graficap" width="200" height="90"></canvas>
      </div>
    </div>
  </div>
  <?php include_once 'includes/footer.php'; ?>
  <!-- SCRIPT PARA CONTROL DE LAS VENTAS -->
  <script src="js/reporte_Ventas_controller.js"></script>
</body>
</html>