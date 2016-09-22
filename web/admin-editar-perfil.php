<?php

require_once 'clases/sistema/Controller.php';

session_start();

if (!isset($_SESSION['user'])) {
    header("HTTP/1.0 403 Forbidden");
    header("Location: login.php");
    die();
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['password'], $_POST['confirmacion'])) {
        if ($_POST['password'] == $_POST['confirmacion']) {
            $user->setName(\filter_input(INPUT_POST, 'nombre'));
            $user->setSname(\filter_input(INPUT_POST, 'apellido'));
            $user->setMail($_POST['email']);
            $user->setPass($_POST['password']);
            \sistema\Controller::modifyUser($user);
            \sistema\Controller::logout();
            header('Location: login.php');
        } else {
            header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']) . '?incorrect=1');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8"/>

    <!-- Le styles -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/admin-editar-perfil.css" rel="stylesheet">

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
    <script type="text/javascript" src="js/admin-libro-de-visitas.js"></script>
    
    <title>Administración</title>
  </head>
 
  <body> 
    <div class="container">
      <nav class="navbar navbar-inverse" role="navigation">
        <div class="container-fluid">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><?php echo $_SESSION['user']->getName() . ' ' . $_SESSION['user']->getSname(); ?></a>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
              <li><a href="admin-noticias.php">Noticias</a></li>
              <li><a href="admin-libro-de-visitas.php">Libro de visitas</a></li>   
              <li><a href="admin-usuarios.php">Administrar usuarios</a></li>     
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li class="active dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Sesión<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <li>
                    <a href="admin-editar-perfil.php">
                      <i class="glyphicon glyphicon-cog"></i>
                      Editar perfil
                    <a/>
                  </li>
                  <li class="divider"></li>
                  <li>
                    <a href="login.php?logout">
                      <i class="glyphicon glyphicon-off"></i>
                      Salir
                    </a>
                  </li>            
                </ul>
              </li>
            </ul>
          </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
      </nav>

      <h1>Editar perfil</h1>
      <!--
      nombre
      apellido
      mail
      contraseña
    -->
    <form role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="form-group">
          <label for="nombre">Nombre</label>
          <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="<?php echo $user->getName(); ?>">
        </div>
        <div class="form-group">
          <label for="apellido">Apellido</label>
          <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" value="<?php echo $user->getSname(); ?>">
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo $user->getMail(); ?>">
        </div>
        <div class="form-group">
          <label for="password">Contraseña</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña">
        </div>
         <div class="form-group">
          <label for="confirmacion">Confirmación</label>
          <input type="password" class="form-control" id="confirmacion" name="confirmacion" placeholder="Confirme contraseña">
        </div>
        
        <button type="submit" class="btn btn-primary">Submit</button>

      </form>
    </div> <!-- container --> 

    <script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>
  </body>
</html>