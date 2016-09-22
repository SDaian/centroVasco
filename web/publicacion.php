<?php
	require_once 'clases/sistema/Controller.php';

	if (isset($_GET['news'])) {
	    $id = \filter_input(\INPUT_GET, 'news');
	    $n = new \estructuras\News();
	    $n->setId($id);
	    \sistema\Controller::fillNews($n);
	}
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
        	<h1 id="titulo"><?php echo $n->getTitle(); ?></h1>
			<div class="publicacion">
				<section class="index-contenido">
					<div class="noticias">
                    	<div class="copete"><?php echo $n->getSummary(); ?></div>
                        	<img src="<?php echo $n->getPic_dir(); ?>"/>
                   		<div class="cuerpo"><?php echo $n->getBody(); ?></div>

                    	<h3>Publicado por <?php echo $n->getAuthor()->getName() . ' ' . $n->getAuthor()->getSname() . ', ' . $n->getDatetime(); ?></h3>
					</div>				

					<div class="sociales">
						<a class="sociales-libro-de-visitas" href="libro-de-visitas.php">Libro de visitas</a>	

						<!-- TWITTER -->
						<a class="twitter-timeline" href="https://twitter.com/centrobasko" height="500" data-widget-id="517434103881154561"></a>
							<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
						
						<!-- FACEBOOK -->
						<iframe class="facebook" src="https://www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2Fcentrobasko&width=220&height=258&colorscheme=light&show_faces=true&header=false&stream=false&show_border=true" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:280px; height:300px;" allowTransparency="true"></iframe>
					</div>

					<div class="cleaner"></div>
				</section>
			</div>
		</div>

		<?php include 'web-footer.php'; ?>	
	</body>
</html>
