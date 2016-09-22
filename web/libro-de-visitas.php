<?php
	require_once 'clases/sistema/Controller.php';

	if (isset($_GET['page'])) {
	    $page = \filter_input(\INPUT_GET, 'page');
	} else {
	    $page = 0;
	}

	$comments = \sistema\Controller::getComments($page);    
?>

<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="UTF-8"/>
		<title>Centro Basko :: EUZKO ETXEA</title>	
		<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico"/>	
		<!-- Fonts ======================================================== -->
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext">
		<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.1.0/css/font-awesome.min.css">
		<!-- CSS ========================================================== -->
		<link rel="stylesheet" type="text/css" href="css/reset.css"/>
		<link rel="stylesheet" type="text/css" href="css/estilos.css"/>
		<!-- Javascript =================================================== -->
		<script src="js/jquery-1.11.1.min.js"></script>		
	</head>

	<body>
		<?php include 'web-header.php'; ?>
		<?php include 'web-menu.php'; ?>		

		<div class="contenedor">			
			<h1 id="titulo">Libro de visitas</h1>
			<div class="libro-de-visitas">
				<div class="col1">
                	<?php for ($i=0; $i<count($comments); $i++) { ?>
						<div class="<?php echo $i % 2 == 0? 'bubble-red': 'bubble-green'; ?>">
	                        <h3>Nombre: <?php echo $comments[$i]->getNombre(); ?></h3>
							<p><?php echo $comments[$i]->getComentario(); ?></p>
	                        <h3 class="fecha-de-publicacion">Publicado el <?php echo $comments[$i]->getFecha(); ?> a las <?php echo $comments[$i]->getHora(); ?> hs.</h3>
						</div>

						<div class="white-space"></div>                                        
					<?php } ?>
				</div>                 		

				<div class="col2">
					<a href="libro-de-visitas-publicar.php">Publicar</a>
					<div class="col2_contenido"><span id="donde-estamos">¿Dónde estamos?</span>
						<iframe width="220" height="500" frameborder="0" style="border:0; padding-top: 10px"
						src="https://www.google.com/maps/embed/v1/place?q=Centro+Basko+Eusko+Etxea+-+Calle+14,+La+Plata,+Buenos+Aires,+Argentina&key=AIzaSyAkr9Kn9DE8e0vxpthPayLjZPm3Gt3VekA"></iframe>			
					</div>
				</div>
			</div>

			<div class="cleaner"></div>
			
			<!-- Navegador de noticias -->
			<ul class="pager">
                <?php if ($page > 0) { ?>
                	<li><a style="color: #fff" title="Anterior" href="?page=<?php echo $page > 0? $page - 1: 0; ?>">Anterior</a></li>
                <?php }
                    $max = \sistema\Controller::getMaxCommentPage();
                    if ($page < $max) { ?>
					<li><a style="color: #fff" title="Siguiente" href="?page=<?php echo $page < $max? $page + 1: $max; ?>">Siguiente</a></li>
                <?php } ?>
			</ul>
			<!-- Fin navegador de noticias -->			
		</div>

		<?php include 'web-footer.php'; ?>	
	</body>
</html>