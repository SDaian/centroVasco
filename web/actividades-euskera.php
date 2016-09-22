<?php
    require_once 'clases/sistema/Controller.php';

    if (isset($_GET['page'])) {
        $page = \filter_input(\INPUT_GET, 'page');
    } else {
        $page = 0;
    }

    $sports_news = \sistema\Controller::getLanguageNews($page);
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

		<div id="actividades" class="contenedor">
			<h1 id="titulo"><b>Actividades ></b> Euskera > Noticias</h1>

			<section class="index-contenido">
				<div class="actividades-noticias">
					<?php foreach ($sports_news as $rn) { ?>
						<article>
							<h3><a href="publicacion.php?news=<?php echo $rn->getId(); ?>"><?php echo substr($rn->getTitle(), 0, 110) . (strlen($rn->getTitle()) > 110? "...": ""); ?></a></h3>
		            		<?php echo substr($rn->getSummary(), 0, 220) . (strlen($rn->getSummary()) > 220? "...": ""); ?>
						</article>
					<?php } ?>
				</div>		
			</section>

			<div class="cleaner"></div>

			<!-- Navegador de noticias -->
				<ul class="pager" id="actividades-pager">
					<?php if ($page > 0) { ?>
						<li><a style="color: #fff" title="Anterior" href="?page=<?php echo $page > 0? $page - 1: 0; ?>">Anterior</a></li>
                    <?php }
                        $max = \sistema\Controller::getMaxLanguagePage();
                        if ($page < $max) { ?>
						<li><a style="color: #fff" title="Siguiente" href="?page=<?php echo $page < $max? $page + 1: $max; ?>">Siguiente</a></li>                    
                    <?php } ?>
				</ul>
			<!-- Fin navegador de noticias -->

			<h1 id="titulo"><b>Actividades ></b> Euskera > Horarios</h1>			

			<table>
				<tr>
					<th>Categoría</th>
					<th>Lunes</th>
					<th>Martes</th>
					<th>Miércoles</th>
					<th>Jueves</th>
					<th>Viernes</th>
					<th>Sábado</th>
				</tr>

				<tr>
					<td class="ambiente">------</td>
					<td>17 a 19 hs.</td>
					<td></td>
					<td>18 a 20 hs.</td>
					<td>18 a 20 hs.</td>
					<td>17 a 19 hs.</td>
					<td></td>
				</tr>

				<tr>
					<td class="ambiente">Txikis</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>15 a 16:30 hs.</td>
				</tr>
			</table>
		</div>

		<?php include 'web-footer.php'; ?>	
	</body>
</html>