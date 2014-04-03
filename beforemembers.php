<?php
	if (empty($_SERVER['HTTPS'])) {
    		header("Location: https://forest.cs.purdue.edu/~abazzani/beforemembers.php");
	}
	include('connect.php');
	$mysqli = connect2();
	include('commom.php');	
	if(isset($_COOKIE['hackme']))
	{
		header("Location: members.php");
	}
	if(!$_POST['username']) {
		die('<p>You did not fill in a required field.
			Please go back and try again!</p>');
			redirect("index.php");
	}
	else{				    
		require_once('recaptchalib.php');
		$privatekey = "6LduCegSAAAAAOMD80eD2xVZEiXyaR0kA4L_iaNk";
		$resp = recaptcha_check_answer ($privatekey,
								$_SERVER["REMOTE_ADDR"],
								$_POST["recaptcha_challenge_field"],		
								$_POST["recaptcha_response_field"]);
	}		
	if (isset($_POST['submit'])) {		
		$_POST['username'] = trim($_POST['username']);		
		

  		if (!$resp->is_valid) {
     	//What happens when the CAPTCHA was entered incorrectly
    		die ("Please enter the information provided in the image.");
  		}
		
		$username = $_POST['username'];
			
		$personalString = "";			
		
		if (!($stmt = $mysqli->prepare("SELECT username, pass, extra, extra2 FROM users WHERE username = ?"))) {
                	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }
		if (!$stmt->bind_param('s', $username)) {
                	echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		
		if (!$stmt->execute()) {
		    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$stmt->store_result();
 		//Gives error if user does not exist
 		$check2 = $stmt->num_rows;		
		if ($check2 == 0) {
				$personalString = getWord();			
				storeUser($username, $personalString);
		}
		else
		{	
			bind_array($stmt, $info);
    			$stmt->fetch();			
			$personalString  = $info['extra2'];											
		}
	}
	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>HackMe</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
<?php
	include('header.php');
?>
<div class="post">
	<div class="post-bgtop">
		<div class="post-bgbtm">
			<h2 class="title"><a href="#">Welcome to hackme </a></h2>
				<div class="entry">
		<?php
			if(!isset($_COOKIE['hackme']))
				{
				?>
	           	<form method="post" action="members.php" action="form-handler" onsubmit="">
				<h2> Verify your Personal String </h2>               
				<table>
                	<tr> <td> Username </td> <td> <input type="text" name="username" value="<? echo $username ?>" maxlength="20"/> </td> </tr>
					<tr> <td> Password </td> <td> <input type="password" name="password" maxlength="40"/> </td>  	
					<tr> <td> Your personal string is: </td> <td><strong><? echo $personalString ?></strong></td> </tr>					                  
                    <td> <input type="submit" name = "submit" value="Login" /> </td></tr>
				</table>
				</form>
					
				<hr style=\"color:#000033\" />		
           <?php
				}					
		?>
	</div>
	</div>
	</div>
</div>
<!-- end #sidebar -->
	<?php
		$mysqli->close();
		include('footer.php');
	?>    

</body>
</html>
