<?php 

require 'facebooktallback.php';
include 'class.php';
$AERO = new AERO();

if(isset($accessToken)){
    if(isset($_SESSION['facebook_access_token'])){
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }else{
        // Token de acceso de corta duración en sesión
        $_SESSION['facebook_access_token'] = (string) $accessToken;
        
          // Controlador de cliente OAuth 2.0 ayuda a administrar tokens de acceso
        $oAuth2Client = $fb->getOAuth2Client();
        
        // Intercambia una ficha de acceso de corta duración para una persona de larga vida
        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
        $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
        
        // Establecer token de acceso predeterminado para ser utilizado en el script
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }

    // Redirigir el usuario de nuevo a la misma página si url tiene "code" parámetro en la cadena de consulta
    if(isset($_GET['code'])){
        //header('Location: $_SERVER[HTTP_REFERER]');
        $AERO->redirect($_SERVER['HTTP_REFERER']);
        die();
    }

    // Obtener información sobre el perfil de usuario facebook
    try {
        $profileRequest = $fb->get('/me?fields=id,first_name,last_name,email,picture');
        $fbUserProfile = $profileRequest->getGraphNode()->asArray();
    } catch(FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        session_destroy();
        // Redirigir usuario a la página de inicio de sesión de la aplicación
        header("Location: index.php");
        exit;
    } catch(FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

}

?>


<!DOCTYPE  html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AeroGame GunZ ♥</title>
<style> 
p.test
{
width:11em; 
border:1px solid #000000;
word-wrap:break-word;
}
</style>
<link rel="stylesheet" href="css/aerogunz.css" type="text/css" media="screen">
<link rel="stylesheet" href="css/webshop.css" type="text/css" media="screen"/>
<link href="favicon.png" rel="shortcut icon" />
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<meta name="author" content="AeroGame 2012-2013">
<meta name="description" content="Diviértete en el servidor de GunZ : The Duel de AeroGame, ¡Es completamente gratis!">
<link href="slide/themes/aeroslide/js-image-slider.css" rel="stylesheet" type="text/css" />
<link href="slide/generic.css" rel="stylesheet" type="text/css" />

</head>
<body>
<center>
<div class="header"></div>
<div class="navbar">
  <ul class="group" id="example-one">
      <li class="current_page_item">
      	<a href="/" onmouseover='inicio.src="imgs/nav/inicio_on.png"' onmouseout='inicio.src="imgs/nav/inicio_off.png"'><img name="inicio" src="imgs/nav/inicio_off.png"/></a>
      </li>
      <li><a href="index.php?mod=registro" onmouseover='registro.src="imgs/nav/registro_on.png"' onmouseout='registro.src="imgs/nav/registro_off.png"'><img name="registro" src="imgs/nav/registro_off.png"/></a></li>
      <li><a href="index.php?mod=descargas" onmouseover='descargas.src="imgs/nav/descargas_on.png"' onmouseout='descargas.src="imgs/nav/descargas_off.png"'><img name="descargas" src="imgs/nav/descargas_off.png"/></a></li>
      <li><a href="index.php?mod=rankings" onmouseover='ranking.src="imgs/nav/ranking_on.png"' onmouseout='ranking.src="imgs/nav/ranking_off.png"'><img name="ranking" src="imgs/nav/ranking_off.png"/></a></li>
      <li><a href="index.php?mod=tienda" onmouseover='tienda.src="imgs/nav/tienda_on.png"' onmouseout='tienda.src="imgs/nav/tienda_off.png"'><img name="tienda" src="imgs/nav/tienda_off.png"/></a></li>
  </ul>
</div>
<div class="section_blanco2">


<table style="width: 968px; height: 164px; text-align: left; margin-left: auto; margin-right: auto;" border="0" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td style="white-space: nowrap; text-align: left; vertical-align: top;">
<br>
<img style="width: 189px; height: 23px;" alt="Panel de usuario" src="imgs/panel-title.jpg">

<!--BOX PANEL LOGIN -->
<?php 
if (isset($_SESSION['UserID']) != "") { 

$query = $AERO->query_Account("AID",$_SESSION['AID']);
?>


<!--BOX PANEL USER ACCOUNT -->

<table width="192" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial;color:#646464; font-size: 13px;">
  <tr>
    <td>
  <strong><img src="<?php echo $query['Picture']; ?>" width="20" height="20"> <font style="color:#3779cb;font-size:14px;" ><?php echo $query['Name']; ?></font></strong><br /> 
  <a href="#"><font style="color:#7c7c7c; font-size: 11px;" >Editar datos</font></a> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;<a href="index.php?mod=salir"><font style="color:#7c7c7c;font-size:11px;" ><strong> Salir</strong></font></a><br />
  <br />
  Donator Coins: <font style="color:#0ba000;" ><strong> <?php echo $query['Coins']; ?> </strong></font> <br />
  Event Coins: <font style="color:#0ba000;" ><strong> <?php echo $query['EventCoins']; ?> </strong></font> <br />
  <br />
  <a href="#"><font style="color:#646464;" >Clanes</font></a> - <a href="#"><font style="color:#646464;" >Personajes</font></a>
  <br />
  <a href="#"><img src="imgs/icons/carrito.jpg" alt="Carrito de Compra" width="14" height="13" /> <font style="color:#646464;" ><strong>Carrito de compra</strong></font></a></td>
    <td align="right" valign="top"></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
    </tr>
</table>



<?php } if(isset($_SESSION['UserID']) == "") { ?>

<table width="273" border="0" cellspacing="0" cellpadding="0" style="margin-top:6px;">
  <tr>
    <td width="180"><div class="InputBoxBG1">&nbsp;&nbsp;<input placeholder="Nombre de Usuario" name="" type="text" required="required" style="border: 0pt none ; background-color: transparent; height: 27px; color: rgb(100, 100, 100); text-shadow: 0px 1px 0px #ffffff; font-weight: bold; font-size:11px; outline:none;" size="25" maxlength="15"></div></td>
	
    <td width="86" rowspan="2"><input style="box-shadow: 3px 3px 5px #c0c0c0;" name="" alt="Iniciar sesion" title="Iniciar sesion" src="imgs/boton/panel_entrar_off.jpg" value="" height="59" type="image" width="85"></td>
  </tr>
  <tr>
    <td><div class="InputBoxBG2">&nbsp;&nbsp;<input name="" type="password" placeholder="Contraseña" required="required" size="25" style="text-shadow: 0px 1px 0px #ffffff; border: 0pt none ; background-color: transparent; font-size:11px;font-weight: bold; height: 27px; color: rgb(100, 100, 100); outline:none;" maxlength="25"></div></td>
    </tr>
  <tr>
    <td colspan="2"><input name="" value="Recu&eacute;rdame" type="checkbox"> <span style="font-family:Arial, Helvetica, sans-serif;color:#646464;font-weight: bold;font-size:12px;">Recuérdame</span></td>
    </tr>
  <tr>
    <td colspan="2"><a href=""><img style="border: 0px solid ; width: 128px; height: 20px;" alt="Crea tu cuenta" src="imgs/boton/panel_registro_off.jpg"></a> &nbsp; <a href=""><img style="border: 0px solid ; width: 128px; height: 20px;" alt="Restablecer contrase&ntilde;a" src="imgs/boton/panel_olvido_off.jpg"></a></td>
    </tr>
</table>
<?php } ?>

<!-- FIN PANEL LOGIN -->




</td>
<td width="476" style="white-space: nowrap; text-align: left; vertical-align: middle;">
 
 <div id="sliderFrame">      
        <div id="slider">
            <a href="" target="_blank"><img src="slide/images/1.jpg"  /></a>
            <a href="" target="_blank"><img src="slide/images/2.jpg" /></a>
            <a href="" target="_blank"><img src="slide/images/3.jpg" /></a>              
        </div>
    </div>
</td>
<td style="white-space: nowrap; text-align: center; vertical-align: middle;">
<div style="text-align: left;">


<!-- Box Item del momento-->
 <table width="100" border="0" cellspacing="0" cellpadding="0" style="margin-top: 25px;">
  <tr>
    <td align="center" valign="top">
	
	<table width="143px" height="95px" border="0" cellspacing="0" cellpadding="0" style="background: url(imgs/item-boxbg.jpg) top left no-repeat;">
  <tr>
    <td ><img src="imgs/item.png" alt="TopItem" width="143px" height="95px" /> </td>
    </tr>
</table>




    <table width="158px" height="26px" border="0" cellspacing="0" cellpadding="0" style="background: url(imgs/item-title.jpg) top left no-repeat;">
  <tr>
    <td valign="middle" align="center"> <center>
	
				<table width="143px" height="95px" border="0" cellspacing="0" cellpadding="0" style="background: url(imgs/item-boxbg2.jpg) top left no-repeat;font-family:Arial;color:#646464; font-size: 11px; padding-left:10px;padding-right:10px;" class="Bxtpitem">
 				 	<tr>
   						 <td height="28" colspan="2" align="left" valign="bottom" style="color:#000000;text-shadow:0px 1px 0px #ffffff;font-weight:bold;">Shotgun 467 </td>
   					</tr>
  					<tr>
    					<td height="20" colspan="2" align="left" valign="top" style="color:#169c00;text-shadow:0px 1px 0px #ffffff;font-weight:bold;">$ 75 </td>
    				</tr>
  					<tr>
  						<td align="left" valign="middle">HP <strong>+5</strong> </td>
   	 					<td align="right" valign="middle">W - <strong>16</strong> </td>
  					</tr>
  					<tr>
    					<td align="left" valign="middle">AP <strong>+2</strong> </td>
   						<td align="right" valign="middle">DMG - <strong>25</strong> </td>
  					</tr>
  					<tr>
    					<td height="21" colspan="2" align="left" valign="middle">
						<a href="#"><img src="imgs/icons/carrito.jpg" alt="Carrito de compra" width="14" height="13" style="position: relative; top: 2px;" /> <font style="font-family:Arial;color:#646464; font-size: 10px;font-weight:bold;">Enviar al carrito</font></a>
						 </td>
    				</tr>
				</table>	
	
	
	
	</center></td>
  </tr>
</table>

	
	
	</td>
  </tr>
</table>
<!-- Box Item del momento-->


</div>
<div style="text-align: center;"><br>
</div>
<br>
</td>
</tr>
</tbody>
</table>
<br>
</div>

<div class="contenido">


<table style="text-align: left; height: 424px;" border="0" width="974px" cellpadding="0" cellspacing="0">
  <tbody width="974px">
    <tr>
      <td width="235px" style="white-space: nowrap; text-align: left; vertical-align: top;"><br>
        <img style="width: 235px; height: 25px;" alt="Ranking" src="imgs/ranking-title.jpg"><br>
        <div style="position: relative; top: -10px;">
          <section class="tabs">
            <input id="tab-1" type="radio" name="radio-set" class="tab-selector-1" checked="checked"  />
            <label for="tab-1" class="tab-label-1"><img src="imgs/boton/changeranking-izq.jpg" style="cursor: pointer; cursor: hand;"></label>
            <input id="tab-2" type="radio" name="radio-set" class="tab-selector-2" />
            <label for="tab-2" class="tab-label-2"><img src="imgs/boton/changeranking-der.jpg" style="cursor: pointer; cursor: hand;"></label>
            <div class="clear-shadow"></div>
            <div class="contex">
              <div class="contex-1">
                <table class="TopRanking" border="0">
                  <tr>
                    <td>
                      <table style="text-align: left; width: 230px; height: 25px;" border="0" cellpadding="2" cellspacing="2">
                        <tbody>
                          <tr>
                            <td height="22" align="left" valign="middle" style="white-space: nowrap;"><span class="TextoBlanco">&nbsp;Individual</span></td>
                            <td style="white-space: nowrap; text-align: right;"></td>
                            <td align="undefined" nowrap="nowrap" valign="undefined"></td>
                          </tr>
                        </tbody>
                      </table>
                      <table height="19" border="0" cellpadding="0" cellspacing="0" style="text-align: left; width: 230px; height: 25px;">
                        <tbody>
                          <tr>
                            <td height="18" align="left" valign="middle" nowrap="nowrap">
                              <font size="-2">&nbsp; &nbsp;<span class="TextoGrisClaro2">Pos.</span></font>
                            </td>
                            <td align="left" valign="middle">
                              <font size="-2"><span class="TextoGrisClaro2">Nombre</span> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;</font>
                            </td>
                            <td align="left" nowrap="nowrap" valign="middle">
                              <font size="-2"><span class="TextoGrisClaro2">Nivel</span></font>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <table width="225" height="117" border="0" class="trank" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="54" height="21">&nbsp;</td>
                          <td width="141" height="28" align="left" valign="middle"> Zetik</td>
                          <td width="30" align="left" valign="middle">99</td>
                        </tr>
                        <tr>
                          <td height="21">&nbsp;</td>
                          <td height="28" align="left" valign="middle"> Roan </td>
                          <td align="left" valign="middle">99</td>
                        </tr>
                        <tr>
                          <td height="21">&nbsp;</td>
                          <td height="30" align="left" valign="middle"> Andres </td>
                          <td align="left" valign="middle">99</td>
                        </tr>
                        <tr>
                          <td height="21">&nbsp;</td>
                          <td height="28" align="left"> Inhuman </td>
                          <td align="left" valign="middle">99</td>
                        </tr>
                        <tr>
                          <td height="21">&nbsp;</td>
                          <td height="30" valign="middle"> DkrX </td>
                          <td align="left" valign="middle">99</td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>     
              </div>
              <div class="contex-2">
                <table class="TopRanking" border="0">
                  <tr>
                    <td>
                      <table style="text-align: left; width: 230px; height: 25px;" border="0" cellpadding="2" cellspacing="2">
                        <tbody>
                          <tr>
                            <td height="22" align="left" valign="middle" style="white-space: nowrap;">
                              <span class="TextoBlanco">&nbsp;Clanes</span>
                            </td>
                            <td style="white-space: nowrap; text-align: right;"></td>
                            <td align="undefined" nowrap="nowrap" valign="undefined"></td>
                          </tr>
                        </tbody>
                      </table>
                      <table height="19" border="0" cellpadding="0" cellspacing="0" style="text-align: left; width: 230px; height: 25px;">
                        <tbody>
                          <tr>
                            <td width="50" height="18" align="left" valign="middle" nowrap="nowrap">
                              <font size="-2">&nbsp; &nbsp;<span class="TextoGrisClaro2">Pos.</span></font>
                            </td>
                            <td width="128" align="left" valign="middle">
                              <font size="-2"><span class="TextoGrisClaro2">Nombre</span> &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;</font>
                            </td>
                            <td width="52" align="left" valign="middle" nowrap="nowrap">
                              <font size="-2"><span class="TextoGrisClaro2">Puntos</span></font>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <table width="225" height="117" border="0" class="trank" cellpadding="0" cellspacing="0">
                        <tr>
                          <td width="54" height="21">&nbsp;</td>
                          <td width="123" height="28" align="left" valign="middle"> Sweat </td>
                          <td width="48" align="left" valign="middle">1432</td>
                        </tr>
                        <tr>
                          <td height="21">&nbsp;</td>
                          <td height="28" align="left" valign="middle"> Bangarang </td>
                          <td align="left" valign="middle">1255</td>
                        </tr>
                        <tr>
                          <td height="21">&nbsp;</td>
                          <td height="30" align="left" valign="middle"> School </td>
                          <td align="left" valign="middle">1245</td>
                        </tr>
                        <tr>
                          <td height="21">&nbsp;</td>
                          <td height="28" align="left"> Rasengan </td>
                          <td align="left" valign="middle">1198</td>
                        </tr>
                        <tr>
                          <td height="21">&nbsp;</td>
                          <td height="30" valign="middle"> Elite </td>
                          <td align="left" valign="middle">1237</td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>                             
              </div>                           
            </div>
          </section>
        </div>

        <a href=""><img style="margin-top:12px;border: 0px solid ; width: 230px; height: 49px;" alt="" src="imgs/banner-donacion.jpg"></a><br>
        <a href=""><img style="margin-top:12px;border: 0px solid ; width: 230px; height: 49px;" alt="" src="imgs/banner-foro.jpg"></a><br>
        <div style="margin-top:12px;text-align: center; line-height: 26px;" class="ServerStatus"><big><font size="-3"><big><span class="TextoJugadoresOnline">0 Jugadores online &nbsp; &nbsp; &nbsp;</span></big></font></big><font size="-1"><span class="TextoVerde">Operativo</span></font></div>
      </td>


  <!-- INICIO MEDIO COLUMNA 2 -->

  <td align="center" valign="top" style="white-space: nowrap;">
    <table width="200" border="0" cellpadding="0" cellspacing="0" align="center"> 
      <tr>
        <td align="center" valign="middle">

<!-- php -->
<?php

  if(isset($_GET['mod'])) {

    $mod = $_GET['mod'];

  }else{

    $mod = "index";

  }
  if (file_exists("secciones/aero_$mod.php")) {

    include "secciones/aero_$mod.php";

  }else{
        
    //Go_URL($URL_BASE . "error_log/404.php");

  }
    


?>

          </td>
        </tr>
      </table>
    </td>
 
 <!-- COLUMNA DERECHA 3 -->
<td style="white-space: nowrap; vertical-align: top;" width="244px">
  <br>
  <img style="width: 234px; height: 25px;" alt="Trailer oficial de GunZ" src="imgs/trailer-title.jpg">
  <br>
  <div class="trailerbox" align="center">
    <video width="220" height="124" id="video1" controls>
      <source src="trailer.mp4" type="video/mp4">
    Your browser does not support the video tag.
    </video> 
  </div>
  <br />
  <img style="width: 234px; height: 25px;" alt="Trailer oficial de GunZ" src="imgs/imagenes-title.jpg"><br>
  <div class="imagenesbox" vertical-align="bottom" align="center">
    <table width="222" style="padding-top: 5px;" height="104" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" valign="top">
          <a href="#" title="Ver imagen 1">
            <img class="ImgBoxBorder" src="imgs/imgbox1.jpg" width="66" height="66" alt="Imagen 1" />
          </a>
        </td>
        <td align="center" valign="top">
          <a href="#" title="Ver imagen 2">
            <img class="ImgBoxBorder" src="imgs/imgbox2.jpg" width="66" height="66" alt="Imagen 2" />
          </a>
        </td>
        <td align="center" valign="top">
          <a href="#" title="Ver imagen 3">
            <img class="ImgBoxBorder" src="imgs/imgbox3.jpg" width="66" height="66" alt="Imagen 3" />
          </a>
        </td>
      </tr>
      <tr>
        <td height="30" colspan="3" align="center" valign="top">
          <a href="#" title="Mas imagenes">
            <img src="imgs/moreimages.jpg" width="222" height="29" alt="Mas imagenes" style="margin-top: 1px;"/>
          </a>
        </td>
      </tr>
    </table>
  </div>

  <a href="" title="Descarga el cliente de AeroGunz">
    <img src="imgs/descargacliente.jpg" alt="Descarga Cliente" style="margin-top: 12px;"/>
  </a>
</td>
</tr>
  <!-- FIN COLUMNA DERECHA 3-->
</tbody>
</table>

<br>
</div>
<div class="footer">
  <table style="text-align: left; width: 706px; height: 91px;" border="0" cellpadding="2" cellspacing="2">
    <tbody>
      <tr>
        <td align="undefined" valign="undefined">
          <img style="width: 53px; height: 74px;" alt="Logotipo AeroGame" src="imgs/cr-logo.jpg" />
        </td>
        <td align="undefined" valign="undefined">
          <font size="-1"><span class="TextoNaranjaBold">AeroGame</span></font>
          <font size="-2"><span class="TextoGrisClaro">&nbsp;&nbsp; 2012 - 2019</span></font><br />
          <font size="-1"><span class="TextoGris2">®&nbsp;Todos los derechos reservados <br /> Web Programada por <a href="https://www.facebook.com/Gunzmiguel23" target="_blank">Miguel_23</a></span></font>
        </td>
        <td style="text-align: right;">
          <font style="font-family: Arial; font-weight: bold; color: rgb(127, 127, 127);" size="-1">
            <a href=""><span style="color: rgb(102, 102, 102);">Portal</span></a> | 
            <a href=""><span style="color: rgb(102, 102, 102);">Foro</span></a> |
            <a href=""><span style="color: rgb(102, 102, 102);">Contáctanos</span></a> | 
            <a href=""><span style="color: rgb(102, 102, 102);">Equipo</span></a> | 
            <a href=""><span style="color: rgb(102, 102, 102);">Donaciones</span></a> | 
            <a href=""><span style="color: rgb(102, 102, 102);">Normativas</span></a>
          </font>
          <br>
          <font size="-1">
            <span style="font-family: Arial; font-weight: bold;">
              <a href="" target="_blank"><img style="width: 16px; height: 16px;" alt="AeroGame en Facebook" src="imgs/social/fb.jpg" align="top" /></a> <a href="" target="_blank"><span style="color: rgb(102, 102, 102);">Facebook</span></a>&nbsp;
            </span>
            <span style="font-family: Arial; font-weight: bold;">
              <a href="" target="_blank">
                <img style="color: rgb(102, 102, 102); width: 16px; height: 16px;" alt="AeroGame en Twitter" src="imgs/social/twit.jpg" align="top" /><span style="color: rgb(102, 102, 102);"></span>
              </a>
              <a href="" target="_blank"><span style="color: rgb(102, 102, 102);"> Twitter &nbsp;</span></a>
            </span>
            <span style="font-family: Arial; font-weight: bold;">
              <a href="" target="_blank">
                <img style="color: rgb(102, 102, 102); width: 16px; height: 16px;" alt="AeroGame en YouTube" src="imgs/social/yt.jpg" align="top" /><span style="color: rgb(102, 102, 102);"></span>
              </a>
              <a href="" target="_blank"><span style="color: rgb(102, 102, 102);"> Youtube &nbsp;</span></a>
            </span>
          </font>
        </td>
      </tr>
    </tbody>
  </table>
</div>
</center> 
</body>
  <script src="slide/themes/aeroslide/js-image-slider.js" type="text/javascript"></script>
  <script src='http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js'></script>
  <script src='js/jquery.color-RGBa-patch.js'></script>
  <script src='js/example.js'></script>
  <script src='js/jquery.color-RGBa-patch.js'></script>
  <script type="text/javascript" src="js/jquery.tinyscrollbar.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('#scrollbar1').tinyscrollbar(); 
    });
  </script>
</html>