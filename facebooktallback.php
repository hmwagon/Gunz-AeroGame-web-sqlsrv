<?php
if(!session_id()){
    session_start();
}

// Incluir el autoloader del the SDK
require_once __DIR__ . '/facebook-php-sdk/autoload.php';

// Include required libraries
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

/*
 * Configuración de Facebook SDK
 */
$appId         = '347018619259983'; //Identificador de la Aplicación
$appSecret     = '04dbf87aa400022cf7758e1ce2f28d51'; //Clave secreta de la aplicación
$redirectURL   = 'https://aerogame.net/'; //Callback URL
$fbPermissions = ['email']; //Permisos opcionales


$fb = new Facebook(array(
    'app_id' => $appId,
    'app_secret' => $appSecret,
    'default_graph_version' => 'v3.2',
));

// Obtener el apoyo de logueo
$helper = $fb->getRedirectLoginHelper();
if (isset($_GET['state'])) {
    $helper->getPersistentDataHandler()->set('state', $_GET['state']);
}
//$_SESSION['FBRLH_state']=$_GET['state'];
// Try para obtener el token
try {
    if(isset($_SESSION['facebook_access_token'])){
        $accessToken = $_SESSION['facebook_access_token'];
    }else{
          $accessToken = $helper->getAccessToken('https://aerogame.net/');
    }
} catch(FacebookResponseException $e) {
     echo 'Graph returned an error: ' . $e->getMessage();
      exit;
} catch(FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
      exit;
}


?>