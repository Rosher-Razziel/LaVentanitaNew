<?php
  require_once "conexion.php";
  
  date_default_timezone_set('America/Mexico_City');
  session_set_cookie_params(60*60*14*1);
  session_start();
  // echo $_POST['user'] . ' - ' . $_POST['clave'];
  $pass = md5(mysqli_real_escape_string($conection,$_POST['clave']));
  $query="SELECT usuario FROM usuarios WHERE usuario='" . $_POST["user"] . "' and pass='$pass'";
  $result = mysqli_query($conection, $query);
  if (mysqli_num_rows($result) > 0) {
    
    $query = "SELECT * FROM usuarios INNER JOIN rol ON usuarios.fk_id_rol = rol.id 
    WHERE usuario = '" . $_POST["user"] . "' AND pass = '$pass'";
    $result =  mysqli_query($conection, $query);
    
    while ($row = mysqli_fetch_array($result)) {
      if ($row['des_rol'] <> ""){
        $_SESSION['user'] = $row['usuario'];
        $_SESSION['rolUser'] = $row['des_rol'];
        $_SESSION['time'] = time();
        $date = date("Y-m-d H:i:s");
        
        $query = "INSERT INTO termometro (id, usuario, hora_entrada) 
        VALUES (NULL, '" . $_POST["user"] . "', '$date')";
        $result = mysqli_query($conection, $query);
        echo 'true';
        mysqli_close($conection);
        exit();
      }
    }
  }else{
    echo 'false';
  }
?>
