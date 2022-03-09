<?php 
  date_default_timezone_set('America/Mexico_City');
  require 'conexion.php';
  if ($_POST['accion'] == 'producto') {//TODO: =====LISTAR=====
    // echo $_POST['accion'] . ' - ' . $_POST['codigo'];
    
    $codigo = $_POST['codigo'];
    $query = "SELECT producto, precio_venta, cod_barras FROM productos WHERE cod_barras = $codigo";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }
    $json  = array();
    while($row = mysqli_fetch_array($result)){
      $json[] = array(
        'cod_barras' => $row['cod_barras'],
        'producto' => $row['producto'],
        'precio' => $row['precio_venta']
      );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring; 
  }else if($_POST['accion'] == 'cobrar'){
    $total = $_POST['total'];
    $recibido = $_POST['recibido'];
    $cambio = $_POST['cambio'];
    $productos = $_POST['arreglo'];
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');
    $query = "INSERT INTO venta_total(id, nombre_venta, hora, total) VALUES (NULL, '$fecha', '$hora' ,'$total')";
    $result = mysqli_query($conection, $query);
    $ultimoid = mysqli_insert_id($conection);
    for ($i=0; $i < count($productos); $i++) { 
      $nombre = $productos[$i][1];
      $precio = $productos[$i][2];
      $cantidad = $productos[$i][3];
      $importe = (float)$precio * (int)$cantidad;
      $query = "INSERT INTO ventas (id, cantidad, producto, precio, importe, fecha, hora, fk_id_venta)
      VALUES (NULL, '$cantidad', '$nombre', '$precio', '$importe', '$fecha', '$hora','$ultimoid')";
      $result = mysqli_query($conection, $query);
      // OBTENEMOS LAS EXITENCIAS DEL PROXUTO VENTIDO
      $codigoBarras = $productos[$i][0];
      $query = "SELECT existencias FROM productos WHERE cod_barras = '$codigoBarras'";
      $result = mysqli_query($conection, $query);
      while($row = mysqli_fetch_array($result)){
        $totalExistencias_1 = $row['existencias'];
      }
      
      // ACUTLIZAMOS LAS EXISTENCIAS DEL PRODUCTO VENDIDO
      $existenciaActual = $totalExistencias_1 - $cantidad;
      if($existenciaActual == 0 || $existenciaActual < 0){
        $existenciaActual = 1;
      }
      $query = "UPDATE productos SET existencias='$existenciaActual' WHERE cod_barras = '$codigoBarras'";
      $result = mysqli_query($conection, $query);
    }
    if (!$result) {
      echo "LA INSERCION DE DATOS FALLO";
    }else{
      echo "CORRECTO";
    }
     mysqli_close($conection);
  }else if($_POST['accion'] == 'verificarExistencias'){
    // echo $_POST['accion'] . ' - ' . $_POST['coddigoBarras'] . ' - ' . $_POST['cantidadProducto'];
    $codigo = $_POST['coddigoBarras'];
    $cantidadPedida = $_POST['cantidadProducto'];
    $query = "SELECT existencias FROM productos WHERE cod_barras = $codigo";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    while($row = mysqli_fetch_array($result)){
      $totalExistencias = $row['existencias'];
    }
    // if ($totalExistencias > $cantidadPedida) {
    //   echo "true";
    // }else{
    //   echo "false";
    // }
    echo "true";
  }else if($_POST['accion'] == 'verificarStok'){
    // echo $_POST['accion'] . ' - ' . $_POST['coddigoBarras'];
    $codigo = $_POST['coddigoBarras'];
    $query = "SELECT existencias FROM productos WHERE cod_barras = $codigo";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    while($row = mysqli_fetch_array($result)){
      $totalExistencias = $row['existencias'];
    }
    if ($totalExistencias >= 0) {
      echo "true";
    }else{
      echo "false";
    }
  }else if($_POST['accion'] == 'buscarProducto'){
    // echo $_POST['accion'] . ' - ' . $_POST['codBarras'] ;
    $codigo = $_POST['codBarras'];
    $query = "SELECT producto, precio_venta FROM productos WHERE cod_barras = $codigo";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }
    $json  = array();
    while($row = mysqli_fetch_array($result)){
      $json[] = array(
        'producto' => $row['producto'],
        'precio_venta' => $row['precio_venta']
      );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring; 
  }else if($_POST['accion'] == 'productoAgotado'){
    // echo $_POST['accion']; 
    $query = "SELECT producto, cod_barras, existencias FROM productos WHERE existencias = 0 ORDER BY producto ASC";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }
    $json  = array();
    while($row = mysqli_fetch_array($result)){
      $json[] = array(
        'cod_barras' => $row['cod_barras'],
        'producto' => strtoupper($row['producto']),
        'existencias' => $row['existencias'],
      );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring; 
  }
 ?>