<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>La ventanita</title>
  <link rel="stylesheet" href="css/estilosLogin.css">
  
  <?php include_once("includes/header.php"); ?>
</head>
<body>
  <div class="limiter">
    <div class="container-login100">
      <div class="wrap-login100">
        <div class="login100-pic js-tilt" data-tilt>
          <!-- <img src="https://colorlib.com/etc/lf/Login_v1/images/img-01.png" alt="IMG"> -->
          <img src="img/laventanita.jpg" alt="La ventanita">
        </div>
        <form id="form_acceso">
          <span class="login100-form-title">
            Inisio de Sesion
          </span>
          <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
            <input class="input100" type="text" name="user" id="user" placeholder="Usuario" required="">
            <span class="focus-input100"></span>
            <span class="symbol-input100">
              <i class="fa fa-envelope" aria-hidden="true"></i>
            </span>
          </div>
          <div class="wrap-input100 validate-input" data-validate = "Password is required">
            <input class="input100" type="password" name="clave" id="clave" placeholder="Contaseña" required="">
            <span class="focus-input100"></span>
            <span class="symbol-input100">
              <i class="fa fa-lock" aria-hidden="true"></i>
            </span>
          </div>
          <div class="container-login100-form-btn">
            <button class="login100-form-btn" type="submit">
              Acceder
            </button>
          </div>
          <div class="text-center p-t-12">
            <span class="txt1">
              Olvide
            </span>
            <a class="txt2" href="#">
              Usuario / Contaseña?
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="libs/jquery-3.6.0.min.js"></script>
  <!-- ALERTAS -->
  <script src="libs/sweetalert.js"></script>
  <script src="js/login.js"></script>
</body>
</html>
