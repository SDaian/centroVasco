<?php
	require_once 'clases/sistema/Controller.php';
	require_once('recaptcha-php-1.11/recaptchalib.php');

	error_reporting(\E_ALL);

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	    $privatekey = "6LfG7_gSAAAAAJ4uwBJPyTs3-y2zd4sMAa0WVFgX";

	    if (isset($_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"])) {
	        $resp = recaptcha_check_answer ($privatekey,
	                                    	$_SERVER["REMOTE_ADDR"],
		                                    $_POST["recaptcha_challenge_field"],
		                                    $_POST["recaptcha_response_field"]);

	        if (!$resp->is_valid) {
				header("Location: libro-de-visitas-error.php");
	            die();
	        } else {
	            if (isset($_POST['nombre'], $_POST['email'], $_POST['mensaje'])) {
	                $c = new \estructuras\Comment();
	                $c->setNombre($_POST['nombre']);
	                $c->setMail($_POST['email']);
	                $mensaje = str_replace("\n", "<br>", $_POST['mensaje']);
	                $c->setComentario($mensaje);

	                \sistema\Controller::postComment($c);
	                header("Location: libro-de-visitas-ok.php");
	                die();
	            } else {
	                header("Location: libro-de-visitas-error.php");
	                die();
	            }
	        }
	    }
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
			<h1 id="titulo">Realizar publicación en el libro de visitas</h1>
			<div class="contenedor_servicios">
				<div class="col1_nosotros">
	                <form class="contact_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
						<ul>
							<li>
								<label for="nombre">Nombre:</label>
			                    <input name="nombre" type="text" maxlength="32" required/>								
							</li>

							<li>
								<label for="email">Email:</label>
			                    <input name="email" type="email" maxlength="35" required/>
							</li>

							<li>
								<label for="mensaje">Mensaje a publicar:</label>
			                    <textarea name="mensaje" cols="73" rows="15"  id="mensaje" maxlength="4096" required></textarea>
							</li>

			                <li class="recaptcha">
			                	<?php
			                        $publickey = "6LfG7_gSAAAAANWlVFUZt_Yx-ANWFH_31ugV-FHO"; // you got this from the signup page
			                        echo recaptcha_get_html($publickey);
			                    ?>
			                </li>

							<li>
			                    <button class="submit" type="submit">Publicar</button>
							</li>
						</ul>
					</form>

					<div class="cleaner"></div>
				</div>

				<div class="col2_nosotros">
					<div class="col2_nosotros_contenido"><span id="donde-estamos">¿Dónde estamos?</span>
						<iframe width="220" height="500" frameborder="0" style="border:0;padding-top: 10px"
						src="https://www.google.com/maps/embed/v1/place?q=Centro+Basko+Eusko+Etxea+-+Calle+14,+La+Plata,+Buenos+Aires,+Argentina&key=AIzaSyAkr9Kn9DE8e0vxpthPayLjZPm3Gt3VekA"></iframe>			
					</div>
				</div>
				
				<div class="cleaner"></div>
			</div>
		</div>

		<footer class="libro-de-visitas-publicar">
			<div class="contenedor">	
				<div class="titulo">Sitios web relacionados</div>	

				<ul>
					<li><a target="_blank" href="http://www.euskadi.net/r33-2220/es">Gobierno Vasco</a></li>
					<li><a target="_blank" href="https://www.facebook.com/pages/Delegaci%C3%B3n-de-Euskadi-en-Argentina-Mercosur/170689846306722">Delegación del Gobierno Vasco en Argentina-Mercosur</a></li>
					<li><a target="_blank" href="http://www.fevaonline.org.ar/FrontEnd/Inicio.aspx">FEVA</a></li>
					<li><a target="_blank" href="http://www.fevaonline.org.ar/FrontEnd/ListadoCentroVasco.aspx">Centros vascos en Argentina</a></li>
				</ul>

				<ul class="ul-ultimo">
					<li><a target="_blank" href="http://www.euskalkultura.com/portadako_albisteak_plantilla?set_language=es">Euskalkultura</a></li>
					<li><a target="_blank" href="http://www.euskaletxeak.net">Euskal Etxeak</a></li>
					<li><a target="_blank" href="http://www.euskaletxeak.net/index.php?option=com_content&task=blogsection&id=21&Itemid=394">Euskal Etxeak Digital</a></li>
					<li><a target="_blank" href="http://www.eitb.com/es">EITB</a></li>
				</ul>

				<div class="cleaner"></div>

				<p>© Copyright :: DatCode</p>
			</div>
		</footer>
	</body>
</html>