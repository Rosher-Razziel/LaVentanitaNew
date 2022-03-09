<!DOCTYPE html>
<html lang="es">
<head>
  <?php include_once'includes/header.php'; ?>
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous"> -->
  
  <title>La ventanita</title>
</head>
<body>
  
  <?php include_once 'includes/menu.php'; ?>
  <div class="container">
    <div class="row">
      <form class="was-validated" id="perfil">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 toppad" >
          <div class="panel panel-success">
            <h2 class="panel-title">
              <center>
                <font size="5"><i class='glyphicon glyphicon-user'></i>PERFIL</font>
              </center>
            </h2>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-4 col-lg-4 " align="center"> 
                  <div id="load_img">
                    <img class="img-responsive" src="img/laventanita.jpg" alt="Logo" width="200" height="200">
                  </div>
                  <div class="row pt-3">
                    <div class="mb-3">
                      <label class="btn btn-primary">
                        <i class="fa fa-image"></i>
                        Elige una imagen
                        <input type="file" style="display: none;" name="image_perfil" id="image_perfil">
                      </label>
                    </div>
                  </div>
                </div>
                <div class=" col-md-8 col-lg-8 "> 
                  <table class="table table-condensed">
                    <tbody>
                      <tr>
                        <td>Nombres y Apellidos:</td>
                        <td><input type="text" class="form-control input-sm" name="nombre_apellido" value="<?php //echo $row['nombre_apellido']?>" required></td>
                      </tr>
                      <tr>
                        <td>Puesto:</td>
                        <td><input type="text" class="form-control input-sm" name="ocupacion" value="<?php //echo $row['ocupacion']?>" required></td>
                      </tr>
                      <tr>
                        <td>Correo:</td>
                        <td><input type="email" class="form-control input-sm" name="correo" value="<?php //echo $row['correo']?>" ></td>
                      </tr>
                      <tr>
                        <td>Contraseña:</td>
                        <td><input type="text" class="form-control input-sm" required name="telefono" value="<?php //echo $row['telefono']?>"></td>
                      </tr>
                      <tr>
                        <td>Verificar Contraseña:</td>
                        <td><input type="text" class="form-control input-sm" required name="salario" value="<?php //echo $row['salario']?>"></td>
                      </tr>
                       <tr>
                        <td>Salario:</td>
                        <td><input type="text" class="form-control input-sm" required name="salario" value="<?php //echo $row['salario']?>"></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class='col-md-12' id="resultados_ajax"></div><!-- Carga los datos ajax -->
              </div>
            </div>
            <div class="panel-footer text-center">    
              <button type="submit" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-refresh">  
              Actualizar Datos
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  <?php include_once 'includes/footer.php'; ?>
  <!-- SCRIPT PARA CONTROL DE LAS VENTAS -->
  <script src="js/editar_imagen_perfil.js"></script>
</body>
</html>