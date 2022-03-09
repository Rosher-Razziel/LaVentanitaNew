<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="principal.php">Nix-Eos</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor02">
      <?php //if ($_SESSION['rolUser'] == 'Administrador') { ?><!-- SE MUESTRA TODOS LOS APARTADOS-->
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="principal.php">Vender
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="productos.php">Productos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="ventas.php">Ventas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="productos_fiados.php">Productos Fiados</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Otros</a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="facturas.php">Facturas</a>
              <a class="dropdown-item" href="usuarios.php">Usuarios</a>
              <!-- <a class="dropdown-item" href="ventas_diarias.php">Ventas Diarias</a> -->
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="reporte_ventas.php">Reporte Ventas</a>
              <a class="dropdown-item" href="termometro.php">Termometro</a>
            </div>
          </li>
        </ul>
        <!-- <div id="reloj" class="p-2 m-2" style="color: #fff; font-size: 20px;"></div> -->
      <?php //}elseif($_SESSION['rolUser'] == 'Vendedor'){?> <!-- SOLO SE MUESTRA EL MENU DE LOS VENDEDORES--> 
        <!-- <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="principal.php">Vender
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="ventas.php">Ventas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="productos_fiados.php">Productos Fiados</a>
          </li>
        </ul> -->
      <?php //} ?>
      <form class="d-flex" id="buscarprecio">
        <input class="form-control me-sm-2" type="text" id="codigoBarrasProducto" placeholder="Buscar Producto">
        <button class="btn btn-secondary my-2 my-sm-0" type="submit">Buscar</button>
      </form>
      <div class="">
        <ul class="nav nav-pills">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Perfil Usuario</a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="perfil_user.php"><?php echo $_SESSION['user']; ?></a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="salir.php">Salir</a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>