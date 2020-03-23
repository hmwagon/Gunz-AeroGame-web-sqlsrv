<?php

date_default_timezone_set('America/Santo_Domingo');

class AERO {
	
	//Datos del SQLServer
	public $serverName 		= "GUNZ\SQLSRV";
    public $dbname 			= "AeroGamersST";
    public $user 			= "miguel_23";
    public $password 		= "Lorean21@@";
    public $characterSet 	= "UTF-8";
    public $connection;
    protected $statement 	= null;
    protected $status 		= null;
    //conexion
	    function __construct(){
			
	        $connectionInfo = array(
	            "UID" => $this->user,
	            "PWD" => $this->password,
	            "Database" => $this->dbname,
	            "CharacterSet" => $this->characterSet
	        );
	        $this->connection = sqlsrv_connect($this->serverName, $connectionInfo);
	        if ($this->connection) {
	            $this->status = true;
	        } else {
	            $this->status = false;
	        }
	    }//Fin Conexion


	public function query_Account($value, $valor){

	    	$query = sqlsrv_query($this->connection, "SELECT * FROM Account WHERE $value = '".$valor."'") or die( print_r( sqlsrv_errors(), true));
	    	$acc_info = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
	    	return $acc_info;
	    }
	    public function query_login($value, $valor){

	    	$query = sqlsrv_query($this->connection, "SELECT * FROM Login WHERE $value = '".$valor."'") or die( print_r( sqlsrv_errors(), true));
	    	$login_info = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);
	    	return $login_info;
	    }
	public function caracteres_numeros($num){
		$permitidos = "1234567890"; 
		for ($i=0; $i<strlen($num); $i++){ 
		  if (strpos($permitidos, substr($num,$i,1))===false){ 
		     return true; 
		  } 
		}
	}
    public function caracteres_permitidos($text){
		$permitidos = "qwertyuiopasdfghjklñzxcvbnmQWERTYUIOPASDFGHJKLÑZXCVBNM0123456789-_ "; 
		for ($i=0; $i<strlen($text); $i++){ 
		  if (strpos($permitidos, substr($text,$i,1))===false){ 
		     return true; 
		  } 
		} 
	}
    public function SetMessage($active, $Elements){
		foreach($Elements as $value)
		{
			$output = $value;
		}
		$_SESSION['SiteMessage'] 	= $output;
		$_SESSION['Article'] 		= $active;
    }
    public function SetMessArticle($actives, $mess){

			switch ($actives) {
				case 1:
					echo "<table  border='0' cellspacing='0' cellpadding='0' class='msgbar1' style='margin-top: 5px;'>
			            <tr>
			              <td width='28' align='center' valign='middle'><img src='imgs/icons/iconalert.jpg' alt='alert' width='14' height='14' /></td>
			              <td width='397' align='left' valign='middle'>$mess</td>
			            </tr>
			        </table>";
					break;
				case 2:
					echo "<table  border='0' cellspacing='0' cellpadding='0' class='msgbar1' style='margin-top: 5px;'>
			            <tr>
			              <td width='28' align='center' valign='middle'><img src='imgs/icons/iconalert.jpg' alt='alert' width='14' height='14' /></td>
			              <td width='397' align='left' valign='middle'>$mess</td>
			            </tr>
			        </table>";
					break;
				case 3:
					echo "<table  border='0' cellspacing='0' cellpadding='0' class='msgbar1' style='margin-top: 5px;'>
			            <tr>
			              <td width='28' align='center' valign='middle'><img src='imgs/icons/iconalert.jpg' alt='alert' width='14' height='14' /></td>
			              <td width='397' align='left' valign='middle'>$mess</td>
			            </tr>
			        </table>";
					break;
			}

	}
	public function redirect($url){
		echo "<script>window.location = '$url' </script>";
	}
	public function checkUser($userData = array()){

		if(!empty($userData)){

			$prevQuery = "SELECT * FROM Web_Account_Face_register WHERE  Facebook_UserID = '".$userData['Facebook_UserID']."'";
			$prevResult = sqlsrv_query( $this->connection, $prevQuery);
			$printt = sqlsrv_fetch_array( $prevResult, SQLSRV_FETCH_ASSOC);

			//condicion para actualizar si
			if($printt['Facebook_UserID'] != ""){
			// actualizar información si es que existe
			sqlsrv_query( $this->connection, "UPDATE Web_Account_Face_register 
				SET Fiirst_name = '".$userData['Fiirst_name']."', 
					Last_name = '".$userData['Last_name']."', 
					Email = '".$userData['Email']."', 
					Picture = '".$userData['Picture']."',
					Modified = '".date("Y-m-d H:i:s")."' WHERE Facebook_UserID = '".$userData['Facebook_UserID']."'");

			}elseif($userData['Facebook_UserID'] != "" ){
			// Insertar información del usuario
			sqlsrv_query( $this->connection, 
				"INSERT INTO Web_Account_Face_register (Facebook_UserID, Fiirst_name, Last_name, Email, Picture, Created, Modified)
				VALUES('".$userData['Facebook_UserID']."', '".$userData['Fiirst_name']."', '".$userData['Last_name']."', '".$userData['Email']."', '".$userData['Picture']."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')");
			}

			// Tomar la información de la BD
			$result = sqlsrv_query($this->connection, $prevQuery);
			$userData = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
		}

    	// return
        return $userData;


    }
    public function checkUserGunz($UserID){	
    	$res = sqlsrv_has_rows(sqlsrv_query($this->connection, "SELECT * FROM Account WHERE UserID = '".$UserID."' "));
    	$res1 = sqlsrv_has_rows(sqlsrv_query($this->connection, "SELECT * FROM Login WHERE UserID = '".$UserID."' "));
		if ($res <> 0 or $res1 <> 0) {
			
			return true;
		}else{
			
			return false;
		}
		
    }
	public function intento_sesion($ip){
		    // Borrar fails antiguos tablas de esto son  IP, UserID, Time las 3 en varchar(500)

		    sqlsrv_query($this->connection, "DELETE FROM Web_Intento_sesion WHERE Time < " . (time() - 30) );

		    // Buscar fails para la ip actual
		    $strikeq = sqlsrv_query($this->connection, "SELECT COUNT(*) as Strikes, MAX(Time) as Lasttime FROM Web_Intento_sesion WHERE IP = '$ip'");

		    $strikedata 	= sqlsrv_fetch_array($strikeq, SQLSRV_FETCH_ASSOC);

		    if( $strikedata['Strikes'] >= 5 && $strikedata['Lasttime'] > ( time() - 30 ) ){
		    	return false;
			}

			return true;
	    }
	    public function logout($AID){

			// Redireccionar a página de inicio
			$p = sqlsrv_query( $this->connection, "SELECT * FROM Account WHERE AID = '$AID'");
			$acc_info 	= sqlsrv_fetch_array($p, SQLSRV_FETCH_ASSOC);
			$AID 		= $acc_info['AID'];
			sqlsrv_query($this->connection, "UPDATE Account SET Web_LogIn = 0 WHERE AID = '$AID' ") or die( print_r( sqlsrv_errors(), true));
			echo "<script>window.location = 'index.php' </script>";
			
	    }
	    public function facebookupdate($userData = array()){

	    	if(!empty($userData)){

	    		$p 			= sqlsrv_query( $this->connection, "SELECT * FROM Account WHERE Facebook_UserID = '".$userData['Facebook_UserID']."'");
				$acc_info 	= sqlsrv_fetch_array($p, SQLSRV_FETCH_ASSOC);
				$UserID 	= $acc_info['UserID'];
				$AID 		= $acc_info['AID'];
				$p1 		= sqlsrv_query( $this->connection, "SELECT * FROM Login WHERE AID = '".$AID."'");
				$acc_info1  = sqlsrv_fetch_array($p1, SQLSRV_FETCH_ASSOC);
				$pass 		= $acc_info1['Password'];

	    		$Login_Query = sqlsrv_query($this->connection, "SELECT l.UserID, l.AID, c.UGradeID, l.Password FROM Login(nolock) l INNER JOIN Account(nolock) c ON l.AID = c.AID WHERE l.UserID = '".$UserID."' AND l.Password = '".$pass."'") or die( print_r( sqlsrv_errors(), true));
	   			if(sqlsrv_has_rows($Login_Query) == 1){

			   		$logindata = sqlsrv_fetch_array($Login_Query, SQLSRV_FETCH_NUMERIC);

			        $_SESSION['UserID'] 		= $logindata[0];
			        $_SESSION['AID'] 			= $logindata[1];
			        $_SESSION['UGradeID'] 		= $logindata[2];
			        $_SESSION['Password'] 		= md5(md5($logindata[3]));

			        //$url = ($_SESSION['URL'] == "") ? "index.php" : $_SESSION['URL'];
			        //$_SESSION['URL'] = "";
			        $ip = $_SERVER['REMOTE_ADDR'];

			        sqlsrv_query($this->connection, "UPDATE Account SET Web_IP = '$ip', Web_LogIn = 1 WHERE UserID = '$UserID' ") or die( print_r( sqlsrv_errors(), true));

			        echo "<script>window.location = 'index.php' </script>";

			    }
			}
	    }
	    public function verific_facebook($Facebook_UserID){
				$facebook_query = sqlsrv_query( $this->connection, "SELECT * FROM Account WHERE  Facebook_UserID = '".$Facebook_UserID."'");
				$printt = sqlsrv_fetch_array( $facebook_query, SQLSRV_FETCH_ASSOC);
				return $printt['Facebook_UserID'];
	    }
	    public function user($userid, $password, $Facebook_UserID, $Fiirst_name, $Last_name, $Email, $Picture){
	    		
	    	$nombre_completo = $Fiirst_name ." ". $Last_name;

    		//Inserta la linea nueva del registro completo de la web.
    		sqlsrv_query( $this->connection, 
    			"INSERT INTO Account (UserID, UGradeID, PGradeID, RegDate, Name, Email, Facebook_UserID, Picture) VALUES('".$userid."', 0, 0, GETDATE(), '".$nombre_completo."', '".$Email."', '".$Facebook_UserID."', '".$Picture."') ") or die( print_r( sqlsrv_errors(), true));

			$p = sqlsrv_query( $this->connection, "SELECT * FROM Account WHERE Facebook_UserID = '".$Facebook_UserID."'");
			$acc_info = sqlsrv_fetch_array($p, SQLSRV_FETCH_ASSOC);
			$AID = $acc_info['AID'];

			sqlsrv_query( $this->connection, "INSERT INTO Login (UserID, AID, Password) VALUES('".$userid."', '".$AID."', '".$password."')") or die( print_r( sqlsrv_errors(), true));

    		//Elimina la linea de los datos de facebook que guardo a lo primero.
    		sqlsrv_query($this->connection, "DELETE FROM  Web_Account_Face_register WHERE Facebook_UserID = '$Facebook_UserID' ");
	    	
	    }
	   public function confirmlogin($userid, $pass, $ip){

	   		$Login_Query = sqlsrv_query($this->connection, "SELECT l.UserID, l.AID, c.UGradeID, l.Password FROM Login(nolock) l INNER JOIN Account(nolock) c ON l.AID = c.AID WHERE l.UserID = '".$userid."' AND l.Password = '".$pass."'") or die( print_r( sqlsrv_errors(), true));
	   	if(sqlsrv_has_rows($Login_Query) == 1){

	   		$logindata = sqlsrv_fetch_array($Login_Query, SQLSRV_FETCH_NUMERIC);

	        $_SESSION['UserID'] 		= $logindata[0];
	        $_SESSION['AID'] 			= $logindata[1];
	        $_SESSION['UGradeID'] 		= $logindata[2];
	        $_SESSION['Password'] 		= md5(md5($logindata[3]));

	        /*$url = ($_SESSION['URL'] == "") ? "index.php" : $_SESSION['URL'];
	        $_SESSION['URL'] = "";*/

	        //sqlsrv_query($this->connection, "UPDATE Account SET Web_LogIn = 1 AND Web_IP = '$ip' WHERE UserID = '$userid' ") or die( print_r( sqlsrv_errors(), true));
	        $var = sqlsrv_query($this->connection, "SELECT * FROM Web_Intento_sesion WHERE UserID = '$userid' ");
	        $vardate = sqlsrv_fetch_array($var, SQLSRV_FETCH_ASSOC);

	        sqlsrv_query($this->connection, "DELETE FROM Web_Intento_sesion WHERE UserID = '".$vardate['UserID']."' ");
        	return true;
        	echo "<script>window.location = 'index.php' </script>";
	    }else{

	        sqlsrv_query($this->connection, "INSERT INTO Web_Intento_sesion (IP, UserID, Strikes, Time, Lasttime) VALUES ('$ip', '$userid', 1, '".time()."', '".time()."')");
	        return false;
	    }

	   }


}

?>