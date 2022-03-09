<?php 
  date_default_timezone_set('America/Mexico_City');
  require 'conexion.php';
  if ($_POST['accion'] == 'listar') {//TODO: =====LISTAR=====
    // echo $_POST['accion'];
    $hoy = date('Y-m-d');
    $query = "SELECT clientes.nombre_cliente, GROUP_CONCAT(productos_fiados.producto,'..',productos_fiados.fecha,'..',productos_fiados.hora,'..',productos_fiados.cantidad,'..',productos_fiados.precio,'..',productos_fiados.importe) as productos FROM clientes INNER JOIN productos_fiados ON productos_fiados.fk_id_cliente = clientes.id WHERE estado = 0 GROUP BY productos_fiados.fk_id_cliente ORDER BY clientes.id DESC";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }
    $json  = array();
    while($row = mysqli_fetch_array($result)){
      $json[] = array(
        'nombre_cliente' => $row['nombre_cliente'],
        'productos' => $row['productos']
      );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring; 
  }elseif ($_POST['accion'] == 'listarClientes') {
    // echo $_POST['accion'];
    $query = "SELECT * FROM clientes ORDER BY id DESC";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }
    $json  = array();
    while($row = mysqli_fetch_array($result)){
      $json[] = array(
        'id' => $row['id'],
        'nombre_cliente' => $row['nombre_cliente']
      );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
  }elseif ($_POST['accion'] == 'agregarProducto') {
    // echo $_POST['accion'];
    $cliente = $_POST['cliente'];
    $cantidad = $_POST['cantidad'];
    $codigo = $_POST['codigo'];
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');
    $query = "SELECT * FROM productos WHERE cod_barras = $codigo";
    $result = mysqli_query($conection, $query);
    while($row = mysqli_fetch_array($result)){
      $producto = $row['producto'];
      $precio = $row['precio_venta'];
    }
    $importe = $cantidad * $precio;
    $query = "INSERT INTO productos_fiados(id, producto, cantidad, fecha, hora, precio, importe, fk_id_cliente, estado) VALUES (null, '$producto', '$cantidad', '$fecha', '$hora','$precio','$importe','$cliente', '0')";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("La consulta a fallado");
    }
    // echo "PRODUCTO AGREGADO CORRECTAMENTE";
  }elseif ($_POST['accion'] == 'verificarExistenciaProductos') {
    
    $id = $_POST['id'];
    $cantidad = $_POST['cantidad'];
    $query = "SELECT  existencias  FROM productos WHERE cod_barras = $id";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    while($row = mysqli_fetch_array($result)){
      $existencias = $row['existencias'];
    }
    if ($cantidad <= $existencias) {
      echo "true";
    }else{
      echo "false";
    }
    
  }
 ?>