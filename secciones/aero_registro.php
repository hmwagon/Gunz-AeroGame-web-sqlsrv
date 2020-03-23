<?php 

  if (isset($_SESSION['AID']) != "") {
    $AERO->redirect("index.php");
    die();
  }

    // datos de usuario que iran a  la base de datos
    @$fbUserData = array(
        'Facebook_UserID'     => $fbUserProfile['id'],
        'Fiirst_name'         => $fbUserProfile['first_name'],
        'Last_name'           => $fbUserProfile['last_name'],
        'Email'               => $fbUserProfile['email'],
        'Picture'             => $fbUserProfile['picture']['url']
    );

    $userData = $AERO->checkUser($fbUserData);

    // Poner datos de usuario en variables de Session
    $_SESSION['userData'] = $userData;
    // Obtener el url para cerrar sesión
    //$logoutURL = $helper->getLogoutUrl($accessToken, $redirectURL.'index.php?mod=logout');
    $loginURL = $helper->getLoginUrl($redirectURL, $fbPermissions);

  // Build POST request:
  $recaptcha_key =    '6LfRUpwUAAAAAG9WtmOKCxcRJEeE_E64a_2VcwPY';
  $recaptcha_secret = "6LfRUpwUAAAAAGu8iU4A9ukcfAlfuj5sw6CNKqC_";

if (isset($_POST['registrar'])) {

  $userid           = $_POST['userid'];
  $pass[0]          = $_POST['pass1'];
  $pass[1]          = $_POST['pass2'];
  $recaptcha        = $_POST['g-recaptcha-response'];
  $ip               = $_SERVER['REMOTE_ADDR'];


      if ($userid == "") {
        $AERO->SetMessage(3, array("Debe agregar un UserID."));
      }elseif ($pass[0] == "") {
        $AERO->SetMessage(3, array("Debe agregar una contraseña."));
      }elseif ($pass[1] == "") {
        $AERO->SetMessage(3, array("Debe agregar una contraseña."));
      }elseif ($pass[0] != $pass[1]) {
        $AERO->SetMessage(3, array("Las contraseña no coinciden."));
      }elseif ($AERO->caracteres_permitidos($userid) == true) {
        $AERO->SetMessage(3, array("El UserID no puede tener caracteres especiales, porfavor agregue letras de a-z y numeros de 0 al 9 o _-."));
      }elseif ($AERO->caracteres_permitidos($pass[0]) and $AERO->caracteres_permitidos($pass[1]) == true) {
        $AERO->SetMessage(3, array("La contraseña no puede tener caracteres especiales, porfavor agregue letras de a-z y numeros de 0 al 9 o _-."));
      }elseif ($AERO->checkUserGunz($userid) == true) {
        $AERO->SetMessage(3, array("El Nombre de usuario, <b>$userid</b> ya esta en uso."));
      }elseif($AERO->verific_facebook($userData['Facebook_UserID']) != ""){
        $AERO->SetMessage(3, array("Este facebook ya esta agregado a una cuenta."));
      }else{

        function getCaptcha($SecretKey){
        $Response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfRUpwUAAAAAGu8iU4A9ukcfAlfuj5sw6CNKqC_&response={$SecretKey}");
        $Return = json_decode($Response);
        return $Return;
        }

        $Return = getCaptcha($recaptcha);

        if ($Return->success == true && $Return->score >= 0.5) {

          $AERO->user($userid, $pass[0], $userData['Facebook_UserID'], $userData['Fiirst_name'], $userData['Last_name'], $userData['Email'], $userData['Picture']);

          if ($AERO->confirmlogin($userid, $pass[0], $ip) == false) {

            $AERO->SetMessage(3, array("Error al confirmar la cuenta ('false')."));

          }else{
             $AERO->redirect("index.php");
          }

    }else{
      $AERO->SetMessage(2, array("reCAPTCHA, a detectado actividad de robot, porfavor intente otra vez.")); 
    }


  }


}
 ?>


<!-- MODULO DE REGISTRO -->
<br />
<table width="200" border="0" cellpadding="0" cellspacing="0" align="center"> 
  <tr>
    <td>
      <tr>
        <td><img src="imgs/registro-title.jpg" alt="Registro title" /></td>
      </tr>
      <tr>
        <td>
          <?php 

            if (isset($_SESSION['SiteMessage']) != "" && isset($_SESSION['Article']) != "") {

            $AERO->SetMessArticle($_SESSION['Article'], $_SESSION['SiteMessage']);

            }
            $_SESSION['SiteMessage'] = "";
            $_SESSION['Article'] = "";

          ?>
          <div style="margin-top:10px;">
            <?php 
            if (isset($fbUserProfile['id']) == true) {
            ?>
            <form name="registrar" action="" method="POST">
            <table class="rboxform" width="100%">
              <tr>
                <td colspan="2" align="center"><img src="<?php echo $userData['Picture']; ?>"></td>
              </tr>
              <tr>
                <td colspan="2" align="center"><strong><?php echo $userData['Fiirst_name']." ".$userData['Last_name']; ?></strong></td>
              </tr>
              <tr>
                <td>Usuario: </td>
                <td><input name="userid" type="text" class="iptbox1" size="10" maxlength="25" placeholder="Nombre de Usuario" required="" /></td>
              </tr>
              <tr>
                <td>Contraseña: </td>
                <td><input name="pass1" type="password" class="iptbox2" size="10" placeholder="Contraseña" required="" /></td>
              </tr>
              <tr>
                <td>Repite contraseña: </td>
                <td><input name="pass2" type="password" class="iptbox3" size="" placeholder="Repetir Contraseña" required="" /></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td align="center" colspan="2"><input name="acepto" type="checkbox" required="" />He leído y acepto los <a href="https://aerogame.net/index.php?mod=terminos">Términos de Uso</a>.</td>
              </tr>
              <tr>
                <td colspan="2"><input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" /></td>
              </tr>
              <tr>
                <td colspan="2" align="center" valign="middle"><input name="registrar" type="submit" class="iptsub1" value=" "> <input type="submit" class="iptres1" onclick="window.location.href = 'index.php?mod=salir';" value=" "></td>
              </tr>
            </table>
            </form>
            <?php }?>

            <?php if (isset($fbUserProfile['id']) == false) { ?>
              <table  border='0' cellspacing='0' cellpadding='0' class='msgbar1' style='margin-top: 5px;'>
                  <tr>
                    <td width='28' align='center' valign='middle'><img src='imgs/icons/iconalert.jpg' alt='alert' width='14' height='14' /></td>
                    <td width='397' align='left' valign='middle'>Para unirse a la comunidad debe registrar su cuenta con facebook.</td>
                  </tr>
              </table>
              <br /><br />
              <div align="center"><a   href="<?php echo htmlspecialchars($loginURL); ?>"><span style="background:#255a89; color:#fff; padding: 10px; -webkit-box-shadow: inset 0 0 0 2px #103357;
    -moz-box-shadow: inset 0 0 0 2px #103357; box-shadow: inset 0 0 0 2px #103357;"><strong>FACEBOOK</strong></span></a></div>
            <?php } ?>

          </div>
        </td>
      </tr>
      
	      
      </td>
	  </td>
  </tr>
</table>
    <!-- reCAPTCHA google-->
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $recaptcha_key; ?>"></script>
<script>
    grecaptcha.ready(function() {
    grecaptcha.execute('<?php echo $recaptcha_key; ?>', {action: 'contact'}).then(function(token) {
        //console.log(token);
        document.getElementById('g-recaptcha-response').value=token;
    });
    });
</script>
<!-- FIN MODULO DE REGISTRO -->