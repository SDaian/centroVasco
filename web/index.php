<?php
    require_once 'clases/sistema/Controller.php';
    $important_news = \sistema\Controller::getImportantNews(0);

    if (isset($_GET['page'])) {
        $page = \filter_input(\INPUT_GET, 'page');
    } else {
        $page = 0;
    }

    $regular_news = \sistema\Controller::getNews($page);
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
			<!-- zAccordion -->
			<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
			<script src="js/zaccordion/js/jquery.zaccordion.js"></script>
			<script type="text/javascript">
				$(document).ready(function() {
					$("#example10").zAccordion({
						timeout: 3000,
						width: 884,
						speed: 600,
						slideClass: "slider",
						slideWidth: 600,
						height: 400
					});
				});
			</script>			
	</head>

	<body>
		<?php include 'web-header.php'; ?>
		<?php include 'web-menu.php'; ?>

		<div class="contenedor">
			<section class="slider">
				<ul id="example10">
                    <?php foreach ($important_news as $in) { ?>
						<li>
                        	<img src=<?php echo '"'.$in->getPic_dir().'"' ?> width="600" height="400"/>
							<div class="slider-bg"></div>
							<div class="slider-info">
	                            <a href="publicacion.php?news=<?php echo $in->getId(); ?>"><strong><?php echo substr($in->getTitle(), 0, 50) . (strlen($in->getTitle()) > 50? "...": ""); ?></strong></a>
	                            <p class="slider-text"><?php echo substr($in->getSummary(), 0, 200) . (strlen($in->getSummary()) > 200? "...": ""); ?></p>
							</div>				
						</li>
                    <?php } ?>
				</ul>
			</section>

			<section class="index-contenido">
				<div class="noticias">
					<?php foreach ($regular_news as $rn) { ?>
						<article>
							<img src=<?php echo '"'.$rn->getPic_dir().'"'?> alt="" width="218px" height="159px"/>
	                        <h3><a href="publicacion.php?news=<?php echo $rn->getId(); ?>"><?php echo substr($rn->getTitle(), 0, 110) . (strlen($rn->getTitle()) > 110? "...": ""); ?></a></h3>
	                        <p><?php echo substr($rn->getSummary(), 0, 220) . (strlen($rn->getSummary()) > 220? "...": ""); ?></p>
						</article>
                    <?php } ?>
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
				
				<!-- Navegador de noticias -->
				<ul class="pager">
					<?php if ($page > 0) { ?>
						<li><a style="color: #fff" title="Anterior" href="?page=<?php echo $page > 0? $page - 1: 0; ?>">Anterior</a></li>
                    <?php }
                        $max = \sistema\Controller::getMaxPage();
                        if ($page < $max) { ?>
						<li><a style="color: #fff" title="Siguiente" href="?page=<?php echo $page < $max? $page + 1: $max; ?>">Siguiente</a></li>                    
                    <?php } ?>
				</ul>
				<!-- Fin navegador de noticias -->
			</section>
		</div>

		<?php include 'web-footer.php'; ?>		
	</body>
</html>