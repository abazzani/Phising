<?php
	if (empty($_SERVER['HTTPS'])) {
    		header("Location: https://forest.cs.purdue.edu/~abazzani/index.php");
	}	
	if(isset($_COOKIE['hackme']))
	{
		header("Location: members.php");
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
	           	<form method="post" action="beforemembers.php" action="form-handler" onsubmit="">
				<h2> LOGIN </h2>
                <? require_once('recaptchalib.php');
  				$publickey = "6LduCegSAAAAAAxp_oww5y8HzPnS9z-SI_driBC4"; // you got this from the signup page
  				echo recaptcha_get_html($publickey);
				?>
				<table>
					<tr> <td> Username </td> <td> <input type="text" name="username" maxlength="20"/> </td> </tr>                 
                    <td> <input type="submit" name = "submit" value="Login" /> </td></tr>
				</table>
				</form>
					
				<hr style=\"color:#000033\" />
			<p></p><p>If you are not a member yet, please click <a href="register.php">here</a> to register.</p>
           <?php
				}					
		?>
	</div>
	</div>
	</div>
</div>
<!-- end #sidebar -->
	<?php
		include('footer.php');
	?>
    
</body>
</html>
