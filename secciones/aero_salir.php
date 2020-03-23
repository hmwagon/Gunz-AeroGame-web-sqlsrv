<?php 

require_once 'facebooktallback.php';

/*if ($_SESSION['AID'] != "") {

	$Nova->logout($_SESSION['AID']);
}*/
session_unset();
session_destroy();
// Deshacer la sesión
unset($_SESSION['facebook_access_token']);
// Deshacer la información del usuario
unset($_SESSION['userData']);
//$Nova->logout($_SESSION['AID']);
$AERO->redirect('index.php');

 ?>