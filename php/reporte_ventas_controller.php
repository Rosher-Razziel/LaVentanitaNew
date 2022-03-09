<?php 
  date_default_timezone_set('America/Mexico_City');
  require 'conexion.php';
  if ($_POST['accion'] == 'ventaDiaria') {//TODO: =====LISTAR=====
    // echo $_POST['accion'];
    $query = "SELECT nombre_venta, SUM(total) as total FROM venta_total GROUP BY nombre_venta DESC LIMIT 30";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }
    $json  = array();
    while($row = mysqli_fetch_array($result)){
      $json[] = array(
        'fecha' => $row['nombre_venta'],
        'total' => $row['total']
      );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring; 
  }elseif($_POST['accion'] == 'ventaSemana'){
     // echo $_POST['accion'];
    $mont = date('F');
    // echo $mont;
    //  $query = "SELECT nombre_venta, yearweek(nombre_venta) AS week_date, sum(total) AS week_total from venta_total WHERE MONTHNAME(nombre_venta) = '$mont' GROUP BY week_date, yearweek(nombre_venta)";
    $query = "SELECT nombre_venta, yearweek(nombre_venta) as week_date, sum(total) as week_total from venta_total group by week_date, yearweek(nombre_venta)";
     $result = mysqli_query($conection, $query);
     mysqli_close($conection);
 
     if (!$result) {
       die("Consulta fallida" . mysqli_error($conection));
     }
 
     $json  = array();
 
     while($row = mysqli_fetch_array($result)){
       $json[] = array(
         'nombre_venta' => $row['nombre_venta'],
         'week_date' => $row['week_date'],
         'week_total' => $row['week_total']
       );
     }
 
     $jsonstring = json_encode($json);
 
     echo $jsonstring; 
  }elseif($_POST['accion'] == 'ventaMensual'){
    // echo $_POST['accion'];
    $year = date('Y');
    $query = "SELECT MONTH(v.nombre_venta) AS mes, SUM(v.total) AS total FROM venta_total v WHERE YEAR(v.nombre_venta) = '$year' GROUP BY Mes ORDER BY Mes ASC";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }
    $json  = array();
    while($row = mysqli_fetch_array($result)){
      $json[] = array(
        'mes' => $row['mes'],
        'total' => $row['total']
      );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring; 
  }
?>
