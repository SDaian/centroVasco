<?php

require_once 'clases/sistema/Controller.php';

session_start();

if (!isset($_SESSION['user'])) {
    header("HTTP/1.0 403 Forbidden");
    header("Location: login.php");
    die();
}

if (isset($_GET['remove'])) {
    $id = \filter_input(\INPUT_GET, 'remove');
    $comment = new \estructuras\Comment();
    $comment->setId($id);
    \sistema\Controller::deleteComment($comment);
	header('Location: admin-libro-de-visitas.php');
}
if (isset($_GET['validate'])) {
    $id = \filter_input(\INPUT_GET, 'validate');
    $comment = new \estructuras\Comment();
    $comment->setId($id);
    \sistema\Controller::validateComment($comment);
	header('Location: admin-libro-de-visitas.php');
}

$pendientes = \sistema\Controller::getUncheckedComments();
$todas = \sistema\Controller::getCheckedComments();

?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8"/>

    <!-- Le styles -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/admin-libro-de-visitas.css" rel="stylesheet">

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
              <li class="active"><a href="admin-libro-de-visitas.php">Libro de visitas</a></li>        
              <li><a href="admin-usuarios.php">Administrar usuarios</a></li>
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
        <li class="active"><a href="#pendientes" data-toggle="tab">Pendientes</a></li>
        <li><a href="#ver-todos" data-toggle="tab">Ver todos</a></li>
      </ul>

      <div id="my-tab-content" class="tab-content">
        <div class="tab-pane active" id="pendientes">
          <!-- CUSTOM BOOTSTRAP ELEMENT -->
          <div class="collapse-custom">
            <!-- HEADING -->
            <nav class="navbar navbar-default navbar-heading" role="navigation">
              <div class="navbar-collapse">
                <ul class="nav navbar-nav">
                  <li class="one"><a>Nombre</a></li>
                  <li class="two"><a>Email</a></li>
                  <li class="three"><a>Fecha y hora de publicación</a></li>
                  <li class="four"></li>
                  <li class="five"></li>
                </ul>
              </div>
            </nav>

            <?php
            $i = 0;
            foreach ($pendientes as $c) {
                echo "<!-- ROW $i -->";
            ?>

            <nav class="navbar navbar-default" role="navigation">
              <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-3" data-toggle="collapse" <?php echo "href=#collapse$i" ?>>
                <ul class="nav navbar-nav">
                    <li class="one"><a><?php echo $c->getNombre(); ?></a></li>
                    <li class="two"><a><?php echo $c->getMail(); ?></a></li>
                    <li class="three"><a><?php echo $c->getFecha() . ' | ' . $c->getHora() . ' hs.'; ?></a></li>
                    <li class="four"><a><?php if (!$c->getRevisado()) { ?><button type="button" class="btn btn-success" onClick="; window.location.href=this.getAttribute('href'); return false;" <?php echo 'href=' . htmlspecialchars($_SERVER['PHP_SELF']) . '?validate=' . $c->getId(); ?>>Publicar</button><?php } ?></a></li>
                  <li class="five"><a><button type="button" class="btn btn-danger" onClick="; window.location.href=this.getAttribute('href'); return false;" <?php echo 'href=' . htmlspecialchars($_SERVER['PHP_SELF']) . '?remove=' . $c->getId(); ?>>Eliminar</button></a></li>
                </ul>
              </div>
            </nav>
            <div <?php echo 'id=collapse' . $i++; ?> class="collapse" data-parent="bs-example-navbar-collapse-3">
              <div class="panel-body">
                  <?php echo $c->getComentario(); ?>
              </div>
            </div>
            
            <?php
            }
            ?>


          </div>
      </div>

       <div class="tab-pane" id="ver-todos">
          <div class="collapse-custom">
            <!-- HEADING -->
            <nav class="navbar navbar-default navbar-heading" role="navigation">
              <div class="navbar-collapse">
                <ul class="nav navbar-nav">
                  <li class="one"><a>Nombre</a></li>
                  <li class="two"><a>Email</a></li>
                  <li class="three"><a>Fecha y hora de publicación</a></li>
                  <li class="four"></li>
                  <li class="five"></li>
                </ul>
              </div>
            </nav>

            <?php
            $i = 0;
            foreach ($todas as $c) {
                echo "<!-- ROW $i -->";
            ?>
            
            <nav class="navbar navbar-default" role="navigation">
              <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-3" data-toggle="collapse" <?php echo "href=#todos-collapse$i" ?>>
                <ul class="nav navbar-nav">
                  <li class="one"><a><?php echo $c->getNombre(); ?></a></li>
                  <li class="two"><a><?php echo $c->getMail(); ?></a></li>
                  <li class="three"><a><?php echo $c->getFecha() . ' a las ' . $c->getHora() . ' hs.'; ?></a></li>
                  <li class="four"><a><?php if (!$c->getRevisado()) { ?><button type="button" class="btn btn-success" onClick="; window.location.href=this.getAttribute('href'); return false;" <?php echo 'href=' . htmlspecialchars($_SERVER['PHP_SELF']) . '?validate=' . $c->getId(); ?>>Publicar</button><?php } ?></a></li>
                  <li class="five"><a><button type="button" class="btn btn-danger" onClick="; window.location.href=this.getAttribute('href'); return false;" <?php echo 'href=' . htmlspecialchars($_SERVER['PHP_SELF']) . '?remove=' . $c->getId(); ?>>Eliminar</button></a></li>
                </ul>
              </div>
            </nav>
            <div <?php echo 'id=todos-collapse' . $i++; ?> class="collapse" data-parent="bs-example-navbar-collapse-3">
              <div class="panel-body">
                <?php echo $c->getComentario(); ?>
              </div>
            </div>
            
            <?php
            }
            ?>

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