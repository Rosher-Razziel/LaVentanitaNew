<?php 
  date_default_timezone_set('America/Mexico_City');
  header('Content-type: text/html; charset=utf-8');
  require 'conexion.php';
  if ($_POST['accion'] == 'listar') {//TODO: =====LISTAR=====
    // echo $_POST['accion'] . ' - ' . $_POST['buscar'] . ' - ' .  $_POST['tipo'];
    
    $busqueda = $_POST['buscar'];
    $tipo = $_POST['tipo'];
    if ($tipo == "buscador") {
      if ($busqueda != "") {
        $query = "SELECT productos.id, cod_barras, producto, precio_compra, existencias, precio_venta, categoria.des_categoria as categoria, proveedor.des_proveedor as proveedor, fecha_registro, foto FROM productos 
        INNER JOIN categoria ON productos.fk_id_categoria = categoria.id
        INNER JOIN proveedor ON productos.fk_id_proveedor = proveedor.id
        WHERE productos.cod_barras = '$busqueda' OR productos.producto LIKE '%$busqueda%' ORDER BY productos.producto ASC";
      }else{
        $query = "SELECT productos.id, cod_barras, producto, precio_compra, existencias, precio_venta, categoria.des_categoria as categoria, proveedor.des_proveedor as proveedor, fecha_registro, foto FROM productos 
          INNER JOIN categoria ON productos.fk_id_categoria = categoria.id
          INNER JOIN proveedor ON productos.fk_id_proveedor = proveedor.id
          ORDER BY productos.producto ASC";
      }
    }else{
      $query = "SELECT productos.id, cod_barras, producto, precio_compra, existencias, precio_venta, categoria.des_categoria as categoria, proveedor.des_proveedor as proveedor, fecha_registro, foto FROM productos 
        INNER JOIN categoria ON productos.fk_id_categoria = categoria.id
        INNER JOIN proveedor ON productos.fk_id_proveedor = proveedor.id
        ORDER BY productos.producto ASC";
    }
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }
    $json  = array();
    while($row = mysqli_fetch_array($result)){
      $json[] = array(
        'id' => $row['id'],
        'foto' => $row['foto'],
        'cod_barras' => $row['cod_barras'],
        // 'producto' => ucwords(strtolower($row['producto'])),
        'producto' => strtoupper($row['producto']),
        'precio_compra' => $row['precio_compra'],
        'existencias' => $row['existencias'],
        'precio_venta' => $row['precio_venta'],
        'fk_id_categoria' => $row['categoria'],
        'fk_id_proveedor' => $row['proveedor']
      );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring; 
  }else if($_POST['accion'] == 'agregarproducto'){
    // echo $_POST['accion'];
    
    $codigo_barras = $_POST['codigo_barras'];
    $nombre_producto = $_POST['nombre_producto'];
    $precio_compra = $_POST['precio_compra'];
    $existencia = $_POST['existencia'];
    $precio_venta = $_POST['precio_venta'];
    $proveedores = $_POST['proveedores'];
    $categoria = $_POST['categoria'];
    $fecha = date('Y-m-d H:i:s');
    $carpeta = '../fotos_productos/';
    opendir($carpeta);
    $destino = $carpeta.$fecha." ".$_FILES['foto']['name'];
    copy($_FILES['foto']['tmp_name'],$destino);
    
    if(!empty($_FILES['foto']['name'])){
      $destino = $carpeta.$fecha." ".$_FILES['foto']['name'];
    }else{
      $destino = 'fotos_productos/Sin_Foto.jpg';
    }
    // echo $codigo_barras . ' - ' . $nombre_producto . ' - ' . $precio_compra . ' - ' . $existencia . ' - ' . $precio_venta . ' - ' . $proveedores . ' - ' . $categoria . ' - ' . $fecha . ' - ' . $destino; 
    $query = "INSERT INTO productos(id, foto, cod_barras, producto, precio_compra, existencias, precio_venta, fk_id_categoria, fk_id_proveedor, fecha_registro)
    VALUES (NULL, '$destino', '$codigo_barras', '$nombre_producto', '$precio_compra', '$existencia', '$precio_venta', '$categoria', '$proveedores', '$fecha')";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("La consulta a fallado") . mysqli_error($conection);
    }else{
      echo "true";
    }
  }else if($_POST['accion'] == 'listarProveedores'){
    // echo $_POST['accion'];
    $query = "SELECT * FROM proveedor ORDER BY id DESC";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }
    $json  = array();
    while($row = mysqli_fetch_array($result)){
      $json[] = array(
        'id' => $row['id'],
        'des_proveedor' => $row['des_proveedor']
      );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
  }else if($_POST['accion'] == 'listarCategorias'){
    // echo $_POST['accion'];
    $query = "SELECT * FROM categoria ORDER BY id DESC";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }
    $json  = array();
    while($row = mysqli_fetch_array($result)){
      $json[] = array(
        'id' => $row['id'],
        'des_categoria' => $row['des_categoria']
      );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
  }else if($_POST['accion'] == 'eliminarProducto'){
    // echo $_POST['accion'] . ' - ' . $_POST['id'];
    $id = $_POST['id'];
    $query = "SELECT foto FROM productos WHERE id = $id";
    $result = mysqli_query($conection, $query);
    while($row = mysqli_fetch_array($result)){
      $urlFoto = $row['foto'];
    }
    if($urlFoto != "fotos_productos/Sin_Foto.jpg"){
      if (unlink($urlFoto)){
        $query = "DELETE FROM productos WHERE id = $id";
        $result = mysqli_query($conection, $query);
      }else{
       echo "Error al borrar FOTO";
      }
    }else{
      $query = "DELETE FROM productos WHERE id = $id";
      $result = mysqli_query($conection, $query);
    }
  
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }else{
      echo "PRODUCTO ELIMINADO CORRECTAMENTE";
    }
  
  }else if($_POST['accion'] == 'mostrarEditarProducto'){
    // echo $_POST['accion'] . ' - ' . $_POST['id'];
    $id = $_POST['id'];
    $query = "SELECT productos.id, cod_barras, producto, precio_compra, existencias, precio_venta, categoria.des_categoria as categoria, proveedor.des_proveedor as proveedor, fecha_registro, foto FROM productos 
      INNER JOIN categoria ON productos.fk_id_categoria = categoria.id
      INNER JOIN proveedor ON productos.fk_id_proveedor = proveedor.id
      WHERE productos.id = $id";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }
    $json  = array();
    while($row = mysqli_fetch_array($result)){
      $json[] = array(
        'id' => $row['id'],
        'foto' => $row['foto'],
        'cod_barras' => $row['cod_barras'],
        'producto' => $row['producto'],
        'precio_compra' => $row['precio_compra'],
        'existencias' => $row['existencias'],
        'precio_venta' => $row['precio_venta'],
        'fk_id_categoria' => $row['categoria'],
        'fk_id_proveedor' => $row['proveedor']
      );
    }
    $jsonstring = json_encode($json[0]);
    echo $jsonstring; 
  }else if($_POST['accion'] == 'editarProducto'){
    // echo $_POST['accion'] . '' . $_POST['id_producto'];
    $id_producto = $_POST['id_producto'];
    $codigo_barras = $_POST['codigo_barras'];
    $nombre_producto = $_POST['nombre_producto'];
    $precio_compra = $_POST['precio_compra'];
    $existencia = $_POST['existencia'];
    $precio_venta = $_POST['precio_venta'];
    $proveedores = $_POST['proveedores'];
    $categoria = $_POST['categoria'];
    $fecha = date('Y-m-d H:i:s');
    $carpeta = '../fotos_productos/';
    opendir($carpeta);
    $destino = $carpeta.$fecha." ".$_FILES['foto']['name'];
    copy($_FILES['foto']['tmp_name'],$destino);
    $query = "SELECT foto FROM productos WHERE id = $id_producto";
    $result = mysqli_query($conection, $query);
    while($row = mysqli_fetch_array($result)){
      $foto = $row['foto'];
    }
    if(!empty($_FILES['foto']['name'])){
      $destino = $carpeta.$fecha." ".$_FILES['foto']['name'];
    }else{
      if($foto != "fotos_productos/Sin_Foto.jpg"){
        $destino = $foto;
      }else{
        $destino = 'fotos_productos/Sin_Foto.jpg';
      }
    }
    // echo $codigo_barras . ' - ' . $nombre_producto . ' - ' . $precio_compra . ' - ' . $existencia . ' - ' . $precio_venta . ' - ' . $proveedores . ' - ' . $categoria . ' - ' . $fecha . ' - ' . $id_producto . ' - ' . $destino; 
    $query = "UPDATE productos SET foto= '$destino' ,cod_barras='$codigo_barras',producto='$nombre_producto',precio_compra='$precio_compra',existencias='$existencia',precio_venta='$precio_venta',fk_id_categoria='$categoria',fk_id_proveedor='$proveedores',fecha_registro='$fecha' WHERE id = $id_producto";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("La consulta a fallado") . mysqli_error($conection);
    }else{
      echo "true";
    }
  }else if($_POST['accion'] == 'verificarExistencia'){
    // echo $_POST['accion'] . ' - ' . $_POST['codigo'];
    $codigo = $_POST['codigo'];
    $query = "SELECT  *  FROM productos WHERE cod_barras = $codigo";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (mysqli_num_rows($result) > 0) {
      echo "true";
    }else{
      echo "false";
    }
  }else if($_POST['accion'] == 'mostrarExistencias'){
    // echo $_POST['accion'] . ' - ' . $_POST['id'];
    $id = $_POST['id'];
    $query = "SELECT id, existencias FROM productos WHERE id = $id";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }
    $json  = array();
    while($row = mysqli_fetch_array($result)){
      $json[] = array(
        'id' => $row['id'],
        'existencias' => $row['existencias']
      );
    }
    $jsonstring = json_encode($json[0]);
    echo $jsonstring; 
  }else if($_POST['accion'] == 'agregarExistencias'){
    // echo $_POST['accion'] . ' - ' . $_POST['id_producto_add'];
    $id = $_POST['id_producto_add'];
    $existencia = $_POST['add_existencia'];
    $queryExistencia = "SELECT existencias FROM productos WHERE id = $id";
    $resultExistencia = mysqli_query($conection, $queryExistencia);
    while($row = mysqli_fetch_array($resultExistencia)){
      $existenciActual = $row['existencias'];
    }
    $existencia = $existenciActual + $existencia;
    $query = "UPDATE productos SET existencias = '$existencia' WHERE id = $id";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }else{
      echo "EXITENCIA AGREGADA";
    }
  }else if($_POST['accion'] == 'verificarExistenciaProductos'){
    // echo $_POST['accion'] . ' - ' . $_POST['id'] . ' - ' . $_POST['cantidad'];
    // ESTE METODO ESTA MODIFICADO PARA QUE SIEMPRE DEBUELBA TRUE
    $id = $_POST['id'];
    $cantidad = $_POST['cantidad'];
    $query = "SELECT  existencias  FROM productos WHERE id = $id OR cod_barras = $id";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    while($row = mysqli_fetch_array($result)){
      $existencias = $row['existencias'];
    }
    if ($cantidad >= $existencias) {
      echo "true";
    }else{
      echo "true";
    }
    
    // echo "true";
  }else if($_POST['accion'] == 'add_proveedor'){
    // echo $_POST['accion'] . ' - ' . $_POST['proveedor'];
    $proveedor = $_POST['proveedor'];
    $query = "INSERT INTO proveedor (id, des_proveedor) VALUES (NULL, '$proveedor')";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("La consulta a fallado");
    }
    echo "PROVEEDOR AGREGADA CORRECTAMENTE";
  }else if($_POST['accion'] == 'add_categoria'){
    // echo $_POST['accion'] . ' - ' . $_POST['categoria'];
    $categoria = $_POST['categoria'];
    $query = "INSERT INTO categoria (id, des_categoria) VALUES (NULL, '$categoria')";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("La consulta a fallado");
    }
    echo "CATEGORIA AGREGADA CORRECTAMENTE";
  }else if($_POST['accion'] == 'verificarExistenciaCategoria'){
    // echo $_POST['accion'] . ' - ' . $_POST['categoria'];
    $categoria = $_POST['categoria'];
    $query = "SELECT * FROM categoria WHERE des_categoria = '$categoria'";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (mysqli_num_rows($result) > 0) {
      echo "true";
    }else{
      echo "false";
    }
  }else if($_POST['accion'] == 'verificarExistenciaProveedores'){
    // echo $_POST['accion'] . ' - ' . $_POST['proveedor'];
    $proveedor = $_POST['proveedor'];
    $query = "SELECT * FROM proveedor WHERE des_proveedor = '$proveedor'";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (mysqli_num_rows($result) > 0) {
      echo "true";
    }else{
      echo "false";
    }
  }else if($_POST['accion'] == 'eliminarProveedor'){
    // echo $_POST['accion'] . ' - ' . $_POST['id'];
    $id = $_POST['id'];
    $query = "DELETE FROM proveedor WHERE id = $id";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }else{
      echo "PROVEEDOR ELIMINADO CORRECTAMENTE";
    }
  }else if($_POST['accion'] == 'eliminarCategoria'){
    // echo $_POST['accion'] . ' - ' . $_POST['id'];
    $id = $_POST['id'];
    $query = "DELETE FROM categoria WHERE id = $id";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }else{
      echo "CATEGORIA ELIMINADO CORRECTAMENTE";
    }
  }else if($_POST['accion'] == 'masVendido'){
    // echo $_POST['accion'];
    $query = "SELECT producto, SUM(cantidad) as TOTALVENTDIDO FROM `ventas` GROUP BY producto ORDER BY TOTALVENTDIDO DESC LIMIT 50";
    $result = mysqli_query($conection, $query);
    mysqli_close($conection);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($conection));
    }
    $json  = array();
    while($row = mysqli_fetch_array($result)){
      $json[] = array(
        'TOTALVENTDIDO' => $row['TOTALVENTDIDO'],
        'producto' => ucwords($row['producto']),
      );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring; 
  }
 ?>