<?php
        require_once('recaptcha-php-1.11/recaptchalib.php');
        
	session_start();	
	require_once 'helpers/security.php';
	$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
	$fields = isset($_SESSION['fields']) ? $_SESSION['fields'] : [];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $privatekey = "6LfG7_gSAAAAAJ4uwBJPyTs3-y2zd4sMAa0WVFgX";

	    if (isset($_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"])) {
	        $resp = recaptcha_check_answer ($privatekey,
	                                    	$_SERVER["REMOTE_ADDR"],
		                                    $_POST["recaptcha_challenge_field"],
		                                    $_POST["recaptcha_response_field"]);

	        if (!$resp->is_valid) {
                    header("Location: contacto-error.php");
	            die();
	        } else {
	            if (isset($_POST['nombre'], $_POST['email'], $_POST['mensaje'])) {
	                $to = 'oby_lacueva@hotmail.com';
                        $subject = 'Contacto www.centrovasco.com';
                        $message = filter_input(INPUT_POST, 'mensaje');
                        $headers = "MIME-Version: 1.0\r\n" .
                                    "Content-type: text/html; charset=utf-8\r\n" .
                                    "From: " . $_POST['nombre'] . "<contacto@centrovasco.com>\r\n" .
                                    "Reply-To: " . $_POST['email'] . "\r\n" .
                                    "X-Mailer: PHP/" . phpversion();
                        if (mail($to, $subject, $message, $headers)) {
                            header("Location: contacto-ok.php");
                        } else {
                            header("Location: contacto-error.php");
                        }
	                die();
	            } else {
	                header("Location: contacto-error.php");
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
			<h1 id="titulo">Contacto</h1>
			<div class="contenedor_servicios">
				<div class="col1_nosotros">
					<?php if(!empty($errors)): ?>
						<div class="panel panel-danger">
						    <div class="panel-heading">Error</div>
						    <div class="panel-body">
						    	<ul>
									<li><?php echo implode('</li><li>', $errors); ?></li>
								</ul>
						    </div>
					  	</div>
					<?php endif; ?>

                                    <form class="contact_form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
						<ul>
							<li>
								<label for="name">Nombre:</label>
								<input name="nombre" type="text" <?php echo isset($fields['nombre']) ? 'value="' . e($fields['nombre']) . '"' : '' ?>/>								
							</li>

							<li>
								<label for="email">Email:</label>
								<input name="email" type="email" <?php echo isset($fields['email']) ? 'value="' . e($fields['email']) . '"' : '' ?>/>
							</li>

							<li>
								<label for="mensaje">Mensaje:</label>
								<textarea name="mensaje" cols="73" rows="15"  id="mensaje"><?php echo isset($fields['mensaje']) ? e($fields['mensaje']) : '' ?></textarea>
							</li>

                                                        <li class="recaptcha">
                                                            <?php
                                                                $publickey = "6LfG7_gSAAAAANWlVFUZt_Yx-ANWFH_31ugV-FHO"; // you got this from the signup page
                                                                echo recaptcha_get_html($publickey);
                                                            ?>
                                                        </li>
                                                        
							<li>
								<button class="submit" type="submit">Enviar</button>
							</li>
						</ul>
					</form>
					<div class="cleaner"></div>
				</div>

				<div class="col2_nosotros">
					<div class="col2_nosotros_contenido"><span id="donde-estamos">¿Dónde estamos?</span>
						<iframe width="220" height="500" frameborder="0" style="border:0; padding-top: 10px"
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

<?php
	unset($_SESSION['errors']);
	unset($_SESSION['fields']);
?>