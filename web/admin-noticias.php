<?php
require_once 'clases/sistema/Controller.php';

session_start();

$newline = "</p><p>";
$begin_of_news = "<p>";
$end_of_news = "</p>";

if (!isset($_SESSION['user'])) {
    header("HTTP/1.0 403 Forbidden");
    header("Location: login.php");
    die();
}

if (isset($_GET['delete'])) {
    $id = \filter_input(\INPUT_GET, 'delete');
    $n = new \estructuras\News();
    $n->setId($id);
    \sistema\Controller::deleteNews($n);
	header('Location: admin-noticias.php');
}

$edit = isset($_GET['edit']);
if ($edit) {
    $id = \filter_input(\INPUT_GET, 'edit');
    $e = new \estructuras\News();
    $e->setId($id);
    \sistema\Controller::fillNews($e);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['titulo'], $_POST['categoria'], $_POST['resumen'], $_POST['cuerpo'], $_POST['accion'])) {
        $pic_dir = '';
        if (isset($_FILES['imagen'])) {
            if (!($_FILES['imagen']['error'] > 0)) {
                $allowed_exts = array("gif", "jpeg", "jpg", "png");
                $temp = explode(".", $_FILES['imagen']['name']);
                $extension = end($temp);
                $pic_dir = '';

                if ((($_FILES['imagen']['type'] == "image/gif")
                        || ($_FILES['imagen']['type'] == "image/jpeg")
                        || ($_FILES['imagen']['type'] == "image/jpg")
                        || ($_FILES['imagen']['type'] == "image/pjpeg")
                        || ($_FILES['imagen']['type'] == "image/x-png")
                        || ($_FILES['imagen']['type'] == "image/png"))
                        && ($_FILES['imagen']['size'] < 10485760)
                        && in_array($extension, $allowed_exts)) {


                    $i = 0;
                    while (file_exists("uploads/" . $i . "_" . $_FILES['imagen']['name'])) {
                        $i++;
                    }
                    move_uploaded_file($_FILES['imagen']['tmp_name'], "uploads/" . $i . "_" . $_FILES['imagen']['name']);
                    $pic_dir = "uploads/" . $i . "_" . $_FILES['imagen']['name'];

                } else {
                    die("ERROR: TIPO/TAMAÑO DE IMAGEN INVALIDO: " . $_FILES['imagen']['type'] . " / " . $_FILES['imagen']['size']);
                }
            }
        }
        
        $category_id = $_POST['categoria'];
        
        $post = new \estructuras\News();
        $post->setAuthor($_SESSION['user']);
        $body = '<p>' . str_replace("\n", $newline, $_POST['cuerpo']) . '</p>';
        $summary = '<p>' . str_replace("\n", $newline, $_POST['resumen']) . '</p>';
        $post->setBody($body);
        $category = new \estructuras\Category();
        $category->setId($category_id);
        $post->setCategory($category);
        $post->setImportant((isset($_POST['destacada']) && $_POST['destacada'] =='destacada')? true: false);
        $post->setSummary($summary);
        $post->setTitle($_POST['titulo']);
        $post->setPic_dir($pic_dir);
        
        if ($_POST['accion'] == 'edit') {
            $post->setId($_POST['flush']);
            \sistema\Controller::modifyNews($post);
        } elseif ($_POST['accion'] == 'create') {
            \sistema\Controller::createNews($post);
        } else {
            die("ACCION INVALIDA.");
        }
    } else {
        if (!isset($_POST['titulo'])) {
            echo "ERROR: FALTA TITULO PARA REALIZAR LA PUBLICACION.";
        }
        if (!isset($_POST['categoria'])) {
            echo "ERROR: FALTA CATEGORIA PARA REALIZAR LA PUBLICACION.";
        }
        if (!isset($_POST['resumen'])) {
            echo "ERROR: FALTA RESUMEN PARA REALIZAR LA PUBLICACION.";
        }
        if (!isset($_POST['cuerpo'])) {
            echo "ERROR: FALTA CUERPO PARA REALIZAR LA PUBLICACION.";
        }
        if (!isset($_POST['accion'])) {
            echo "ERROR: FALTA ACCION PARA REALIZAR LA PUBLICACION.";
        }
        die();
    }
}

$noticias = \sistema\Controller::getAllNews();

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
              <li class="active"><a href="admin-noticias.php">Noticias</a></li>
              <li><a href="admin-libro-de-visitas.php">Libro de visitas</a></li>
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
        <li class="active"><a href="#pendientes" data-toggle="tab">Todas las noticias</a></li>
        <li><a href="#ver-todos" data-toggle="tab">Crear noticia</a></li>
      </ul>

      <div id="my-tab-content" class="tab-content">
        <div class="tab-pane active" id="pendientes">
          <!-- CUSTOM BOOTSTRAP ELEMENT -->
          <div class="collapse-custom">
            <!-- Todas las noticias :: HEADING -->
            <nav class="navbar navbar-default navbar-heading" role="navigation">
              <div class="navbar-collapse">
                <ul class="nav navbar-nav">
                  <li class="one"><a>Título</a></li>
                  <li class="two"><a>Autor</a></li>
                  <li class="three"><a>Fecha y hora de publicación</a></li>
                  <li class="four"></li>
                  <li class="five"></li>
                </ul>
              </div>
            </nav>

            <?php
            $i = 0;
            foreach ($noticias as $n) {
            ?>
            
            <!-- ROW -->
            <nav class="navbar navbar-default" role="navigation">
              <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2" data-toggle="collapse" href="#collapse<?php echo $i; ?>">
                <ul class="nav navbar-nav">
                    <li class="one"><a><?php echo substr($n->getTitle(), 0, 30) . (strlen($n->getTitle()) > 30? "...": ""); ?></a></li>
                    <li class="two"><a><?php echo $n->getAuthor()->getName() . ' ' . $n->getAuthor()->getSname() ?></a></li>
                    <li class="three"><a><?php echo $n->getDatetime() ?></a></li>
                    <li class="four"><a><button type="button" class="btn btn-success" onClick="; window.location.href=this.getAttribute('href'); return false;" <?php echo "href='admin-noticias.php?edit=" . $n->getId() . "'"; ?>>Editar</button></a></li>
                  <li class="five"><a><button type="button" class="btn btn-danger" onClick="; window.location.href=this.getAttribute('href'); return false;" <?php echo "href='admin-noticias.php?delete=" . $n->getId() . "'"; ?>>Eliminar</button></a></li>
                </ul>
              </div>
            </nav>
            <div id="collapse<?php echo $i++; ?>" class="collapse" data-parent="bs-example-navbar-collapse-2">
              <div class="panel-body">
                <?php echo $n->getSummary(); ?>
              </div>
            </div>   
            
            <?php
            }
            ?>

          </div>
        </div>

        <div class="tab-pane" id="ver-todos">
          <div class="collapse-custom">
            <!-- Crear noticia :: -->
            <h1>Complete el siguiente formulario</h1>
      
            <form role="form" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
              <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Ingrese un título para la noticia.." <?php if ($edit){ echo 'value="' . $e->getTitle() . '"';} ?> maxlength="128"  required>
              </div>
              <div class="form-group">
                <label for="categoria">Categoría</label>
                <br/>
                <select id ="categoria" name="categoria">
                  <option value="1">Institucional</option>
                  <option value="2">Deportes</option>
                  <option value="3">Canto</option>
                  <option value="4">Idiomas</option>
                  <option value="5">Otros</option>
                </select>
              </div>
              <div class="form-group">
                <label for="resumen">Resumen</label>
                <textarea type ="text" class="form-control" id="resumen" name="resumen" placeholder="Ingrese un breve resumen.."><?php if ($edit){ echo substr(str_replace($newline, "\n", $e->getSummary()), strlen($begin_of_news), -strlen($end_of_news));} ?></textarea>
              </div>
              <div class="form-group">
                  <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                <label for="imagen">Imagen</label>
                <input type="file" id="imagen" name="imagen">
              </div>
              <div class="form-group">
                <label for="cuerpo">Cuerpo</label>
                <textarea type ="text" class="form-control" id="cuerpo" name="cuerpo" placeholder="Ingrese el contenido de la noticia.." required><?php if ($edit){ echo substr(str_replace($newline, "\n", $e->getBody()), strlen($begin_of_news), -strlen($end_of_news));} ?></textarea>
              </div>
              <div class="form-group">
                <label for="destacada">
                  <input type="checkbox" id="destacada" name="destacada" value="destacada"/>
                  Noticia destacada
                </label>                
              </div>
              
                <input type="hidden" name="accion" value="<?php echo $edit? 'edit': 'create'; ?>" />
                <input type="hidden" name="flush" value="<?php echo $edit? $e->getId(): '0'; ?>" />
                
              <button type="submit" class="btn btn-primary">Publicar noticia</button>

            </form>
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