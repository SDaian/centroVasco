<!DOCTYPE html>
<html>
	<head>
		<title>reCaptcha</title>
	</head>
	    
	<body>
		<form method="post" action="verify.php">
        	<?php
	          require_once('recaptchalib.php');
	          $publickey = "6LfG7_gSAAAAANWlVFUZt_Yx-ANWFH_31ugV-FHO"; // you got this from the signup page
	          echo recaptcha_get_html($publickey);
        	?>
        	<input type="submit" />
      	</form>
    </body>
</html>