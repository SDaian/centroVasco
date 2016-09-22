<?php
require_once 'clases/sistema/Controller.php';

session_start();

if (!isset($_SESSION['user'])) {
    header("HTTP/1.0 403 Forbidden");
    header("Location: login.php");
    die();
}

if (isset($_GET['remove'])) {
    $id = \filter_input(INPUT_GET, 'remove');
    $u = new \estructuras\User();
    $u->setId($id);
    \sistema\Controller::deleteUser($u);
    if ($u->getId() == $_SESSION['user']->getId()) {
        header('Location: login.php?logout');
    } else {
        header('Location: admin-usuarios.php');
    }
    die();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['password'], $_POST['confirmpassword'], $_POST['privilegios'])) {
        if ($_POST['password'] == $_POST['confirmpassword']) {
            $privilegios_id = $_POST['privilegios'];
            $tipo = new \estructuras\Type();
            $tipo->setId($privilegios_id);
            $new_user = new \estructuras\User();
            $new_user->setMail($_POST['email']);
            $new_user->setName($_POST['nombre']);
            $new_user->setPass($_POST['password']);
            $new_user->setSname($_POST['apellido']);
            $new_user->setType($tipo);
            \sistema\Controller::createUser($new_user);
        }
    } else {
        die("ERROR: DEBE COMPLETAR TODOS LOS DATOS PARA CREAR UN NUEVO USUARIO DEL SISTEMA.");
    }
}

$users = \sistema\Controller::listUsers();

?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <title>Administración</title>
    <meta charset="UTF-8"/>
    <!-- CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/admin-libro-de-visitas.css" rel="stylesheet">
    <!-- Javascript -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
    <script type="text/javascript" src="js/admin-libro-de-visitas.js"></script>    
    <link href="css/admin-noticias.css" rel="stylesheet">
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
              <li class="active"><a href="admin-usuarios.php">Administrar usuarios</a></li>       
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
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

      <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
        <li class="active"><a href="#pendientes" data-toggle="tab">Todos los usuarios</a></li>
        <li><a href="#ver-todos" data-toggle="tab">Crear usuario</a></li>
      </ul>

      <div id="my-tab-content" class="tab-content">
        <div class="tab-pane active" id="pendientes">
          <!-- CUSTOM BOOTSTRAP ELEMENT -->
          <div class="collapse-custom">
            <!-- Todas las noticias :: HEADING -->
            <nav class="navbar navbar-default navbar-heading" role="navigation">
              <div class="navbar-collapse">
                <ul class="nav navbar-nav">
                  <li class="one"><a>Usuario</a></li>
                  <li class="two"><a>Nombre</a></li>
                  <li class="three"><a>Apellido</a></li>
                  <li class="four"></li>
                  <li class="five"></li>
                </ul>
              </div>
            </nav>

        <?php
        foreach ($users as $u) {
		if ($u->getType()->getName() != "datcode") {
        ?>
        <nav class="navbar navbar-default" role="navigation">
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-3" data-toggle="collapse">
            <ul class="nav navbar-nav">
              <li class="one"><a><?php echo $u->getMail() ?></a></li>
              <li class="two"><a><?php echo $u->getName() ?></a></li>
              <li class="three"><a><?php echo $u->getSname() ?></a></li>
              <li class="four"><a></a></li>
              <li class="five"><?php if ($_SESSION['user']->getType()->getName() == "Administrador" || $_SESSION['user']->getType()->getName() == "datcode") { ?><a><button type="button" class="btn btn-danger" onClick="; window.location.href=this.getAttribute('href'); return false;" <?php echo 'href=' . htmlspecialchars($_SERVER['PHP_SELF']) . '?remove=' . $u->getId(); ?>>Eliminar</button></a><?php } ?></li>
            </ul>
          </div>
        </nav>
        <?php
		}
        }
        ?>

          </div>
        </div>

        <div class="tab-pane" id="ver-todos">
          <div class="collapse-custom">
            <!-- Crear noticia :: -->
            <h1>Complete el siguiente formulario</h1>
      
            <form role="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
              <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
              </div>

              <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Apellido" required>
              </div>

              <div class="form-group">
                <label for="email">Dirección de correo electrónico actual</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Dirección de correo electrónico actual" required>
              </div>

              <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
              </div>

              <div class="form-group">
                <label for="confirm-password">Confirmar contraseña *</label>
                <input type="password" class="form-control" id="confirm-password" name="confirmpassword" placeholder="Confirmar contraseña" required>
              </div>

              <div class="form-group">
                <label for="privilegios">Privilegios</label>
                <br/>
                <select id ="privilegios" name="privilegios">
                  <option value="1">Administrador</option>
                  <option value="2">Normal</option>
                </select>
              </div>
                
              <button type="submit" class="btn btn-primary">Crear usuario</button>

            </form>
          </div>
        </div> 
      </div>
    </div> <!-- container --> 

    <script type="text/javascript" src="bootstrap/js/bootstrap.js"></script>

    <script type="text/javascript">
      jQuery(document).ready(function ($) {
        $('#tabs').tab();
      });
    </script> 
  </body>
</html>
