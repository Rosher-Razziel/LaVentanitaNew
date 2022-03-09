<?php 
  session_set_cookie_params(60*60*24*14);
  session_start();
  date_default_timezone_set('America/Mexico_City');
  require 'conexion.php';
  if ($_POST['accion'] == 'listar') {//TODO: =====LISTAR=====
    // echo $_POST['accion'];
  
    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];
    $hoy = date('Y-m-d');
    if (!empty($desde) && !empty($hasta)) {
      // echo $desde . ' - ' . $hasta;
      $query = "SELECT venta_total.nombre_venta, venta_total.total,venta_total.hora, GROUP_CONCAT(ventas.producto,'..',ventas.cantidad,'..',ventas.fecha,'..',ventas.precio) as productos FROM venta_total 
        INNER JOIN ventas ON venta_total.id = ventas.fk_id_venta 
        WHERE venta_total.nombre_venta BETWEEN '$desde' AND '$hasta'
        GROUP BY ventas.fk_id_venta ORDER BY ventas.id DESC";
    }else{
      $query = "SELECT venta_total.nombre_venta, venta_total.total, venta_total.hora, GROUP_CONCAT(ventas.producto,'..',ventas.cantidad,'..',ventas.fecha,'..',ventas.precio) as productos FROM venta_total INNER JOIN ventas ON venta_total.id = ventas.fk_id_venta WHERE ventas.fecha = '$hoy' GROUP BY ventas.fk_id_venta  ORDER BY ventas.id DESC";
    }
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }
    $json  = array();
    while($row = mysqli_fetch_array($result)){
      $json[] = array(
        'nombre_venta' => $row['nombre_venta'],
        'total' => $row['total'],
        'hora' => $row['hora'],
        'productos' => $row['productos']
      );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring; 
  }else if ($_POST['accion'] == 'ventaDiaria') {
    // echo $_POST['accion'];
    $fecha = date('Y-m-d');
  
    $query = "SELECT SUM(total_retirado) as retirado FROM cretiro_dinero WHERE fecha = '$fecha'";
    $result = mysqli_query($conection, $query);  
    
    while($row = mysqli_fetch_array($result)){
      $retirado = (float)$row['retirado'];
    }
    $query = "SELECT SUM(importe) as importe FROM ventas WHERE fecha = '$fecha'";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }
    $json  = array();
    while($row = mysqli_fetch_array($result)){
      if ($row['importe'] != null) {
      
        $json[] = array(
          'importe' => (float)$row['importe'],
          'retirado' => $retirado,
          'fecha' => $fecha
        );
      }else{
        echo "false";
        exit();
      }
    }
    $jsonstring = json_encode($json);
    echo $jsonstring; 
  }else if ($_POST['accion'] == 'corteCaja') {
    // echo $_POST['accion'];
    $fecha = date('Y-m-d');
    $query = "SELECT * FROM corteCaja ORDER BY id DESC";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }
    $json  = array();
    while($row = mysqli_fetch_array($result)){
      // if ($row['importe'] != null) {
        $json[] = array(
          'fecha' => $row['fecha'],
          'cambio_inicial' => $row['cambio_inicial'],
          'venta_dia' => $row['venta_dia'],
          'resurtir' => $row['resurtir'],
          'ganancia' => $row['ganancia'],
          'ganancia_efra' => $row['ganancia_efra'],
          'ganancia_roger' => $row['ganancia_roger'],
        );
      // }else{
      //   echo "false";
      //   exit();
      // }
    }
    $jsonstring = json_encode($json);
    echo $jsonstring; 
  }else if ($_POST['accion'] == 'agregarCorte') {
    // echo $_POST['accion'] . '--' . $_POST['importe'] .'--'. $_POST['fecha'].'--'. $_POST['dineroInicial'];
    // $fecha = date('Y-m-d');
    $fecha = $_POST['fecha'];
    $dineroInicial = (float)$_POST['dineroInicial'];
    $retirado = (float)$_POST['retirado'];
    $ventaTotal = (float)$_POST['importe'];
    $ventaTotal = $ventaTotal - $retirado;
    $resurtir = $ventaTotal * 0.7;
    $gananciaTotal = $ventaTotal * 0.3;
    $gananciaE1 = $gananciaTotal / 2;
    $gananciaE2 = $gananciaTotal / 2;    
    $query = "INSERT INTO corteCaja(id, cambio_inicial, venta_dia, resurtir, ganancia, ganancia_efra, ganancia_roger, fecha) 
    VALUES (null,$dineroInicial,$ventaTotal,$resurtir,$gananciaTotal,$gananciaE1,$gananciaE2, '$fecha')";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }else{
      echo "true";
    }
  }else if ($_POST['accion'] == 'validarRegistro') {
    // echo $_POST['accion'];
    $fecha = date('Y-m-d');
    $query = "SELECT * FROM corteCaja WHERE fecha = '$fecha'";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (mysqli_num_rows($result)>0){
      // print(Exite al menos un registro);
      echo "false";
    } else {
      // print(No Existen registros);
       echo "true";
    }
  }else if ($_POST['accion'] == 'listarRetiros'){
    // echo $_POST['accion'];
    $query = "SELECT * FROM cretiro_dinero ORDER BY idcretiro_dinero DESC";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }
    $json  = array();
    while($row = mysqli_fetch_array($result)){
      $json[] = array(
        'dinero_disponible' => $row['dinero_disponible'],
        'total_retirado' => $row['total_retirado'],
        'fecha' => $row['fecha'],
        'descripcion' => $row['descripcion']
      );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
  }else if ($_POST['accion'] == 'agergarRetiro'){
    // echo $_POST['accion'] . ' - ' . $_SESSION['user'];
    $usuario = $_SESSION['user'];
    $dineroDisponible = (float)$_POST['dineroDisponible'];
    $dineroTotal = (float)$_POST['dineroTotal'];
    $dineroRetirado = (float)$_POST['retirarDinero'];
    $descripcionRetiro = $_POST['descripcionRetiro'];
    $fecha = date('Y-m-d');
    $dineroTotal = $dineroTotal - $dineroRetirado;
    
    // $query = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
    // $result = mysqli_query($conection, $query);
    // while($row = mysqli_fetch_array($result)){
    //   $idUser = $row['id'];
    // }
    $idUser = 3;
    $query = "INSERT INTO cretiro_dinero (idcretiro_dinero, dinero_disponible, total_retirado, fecha, descripcion, fk_id_usuario) 
    VALUES (NULL, '$dineroTotal', '$dineroRetirado', '$fecha', '$descripcionRetiro', '$idUser');";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      echo "Consulta fallida" . mysqli_error($conection);
    }else{
      echo "true";
    }
  }
?>