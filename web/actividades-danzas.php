<?php
    require_once 'clases/sistema/Controller.php';

    if (isset($_GET['page'])) {
        $page = \filter_input(\INPUT_GET, 'page');
    } else {
        $page = 0;
    }

    $sports_news = \sistema\Controller::getSportsNews($page);
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
			<h1 id="titulo"><b>Actividades ></b> Danzas > Noticias</h1>

			<section class="index-contenido">
				<div class="actividades-noticias">					

				</div>		
			</section>

			<div class="cleaner"></div>

			<!-- Navegador de noticias -->				
			<!-- Fin navegador de noticias -->

			<h1 id="titulo"><b>Actividades ></b> Danzas > Horarios</h1>			

			<h3>Cuerpo de bailes</h3>
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
					<td class="ambiente">Zaharrak</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>18 hs.</td>
				</tr>

				<tr>
					<td class="ambiente">Medianos</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>17 hs.</td>
				</tr>

				<tr>
					<td class="ambiente">Txikis</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td>17 hs.</td>
				</tr>
			</table>
		</div>

		<?php include 'web-footer.php'; ?>	
	</body>
</html>