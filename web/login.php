<?php
require_once 'clases/sistema/Controller.php';

session_start();

if (isset($_GET['logout'])) {
    \sistema\Controller::logout();
	header('Location: login.php');
        die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username'], $_POST['password'])) {
        $login_ok = false;
        $_SESSION['user'] = \sistema\Controller::login($_POST['username'], $_POST['password']);
        if ($_SESSION['user'] == null) {
            die("No se encontro el usuario.");
        }
    }
}

if (isset($_SESSION['user'])) {
    header('Location: admin-noticias.php');
    die();
}

?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrador</title>

    <!-- Bootstrap -->          
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
  </head>

  <body>
    <div class="wrapper">
        <form class="form-signin" action="login.php" method="POST">       
        <h2 class="form-signin-heading">Inicie sesión</h2>
        <input type="text" class="form-control" name="username" placeholder="Nombre de usuario" required="" autofocus="" <?php if (isset($login_ok) && !$login_ok) { echo 'value="' . $_POST['username'] . '"'; } ?> />
        <div id="separador"></div>
        <input type="password" class="form-control" name="password" placeholder="Contraseña" required=""/>      
        <!--<label class="checkbox">
          <input type="checkbox" value="remember-me" id="rememberMe" name="rememberMe"> Recordar
        </label>-->
        <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>   
      </form>
    </div>
  </body>
</html>