<?php
	session_start();	 
	if (empty($_SERVER['HTTPS'])) {
    		header("Location: https://forest.cs.purdue.edu/~abazzani/members.php");
	}
	// Connects to the Database 
	include('connect.php');
	$mysqli = connect2();
	include('commom.php');
	if (isset($_POST['submit'])) {
		
		$_POST['username'] = trim($_POST['username']);
		
		if(!$_POST['username'] | !$_POST['password']) {
			die('<p>You did not fill in a required field. Please go back and try again!</p>');
			header("Location: index.php");
		}		  	
		
		$username = $_POST['username'];
		$password = $_POST['password'];				
		

		if (!($stmt = $mysqli->prepare("SELECT username, pass, extra FROM users WHERE username = ?"))) {
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
			die("<p>Please verified the information.</p>");
		}
		else
		{			
			bind_array($stmt, $info);
    			$stmt->fetch();	
			$passwordHash = hash_password($info['extra'], $password);
			if ($passwordHash != $info['pass']) {
				die('Incorrect password, please try again.');
			}
			$usernameMAC =  mac_string($username);
			$hour = time() + 36000;				
			$_SESSION['usernamemac'] = $usernameMAC;
			$_SESSION['auth'] = 1;
			session_write_close();			
			setcookie(hackme, $username, $hour); 
			setcookie(hackme_pass, $passwordHash, $hour);
			setcookie(hackme_username, $usernameMAC, $hour);						
			header("Location: members.php");				
		}
	}
		?>  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>hackme</title>
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
<?php
	include('header.php');
?>
<div class="post">
	<div class="post-bgtop">
		<div class="post-bgbtm">
        <h2 class = "title">hackme bulletin board</h2>
        	<?php
            if(!isset($_COOKIE['hackme'])){
				session_destroy();
				header("Location: index.php");
			}else
			{
				print("<p>Logged in as <a>$_COOKIE[hackme]</a></p>");
			}
			?>
        </div>
    </div>
</div>

<?php
	$threads = $mysqli->query("SELECT * FROM threads ORDER BY date DESC", MYSQLI_USE_RESULT);
	while($thisthread = $threads->fetch_array()){
		
		$threadId = makeSafe($thisthread['id']);
		$threadTitle = makeSafe($thisthread['title']);	
		$threadUsername = makeSafe($thisthread['username']);
		$threadDate = makeSafe($thisthread[date]);
?>
	<div class="post">
	<div class="post-bgtop">
	<div class="post-bgbtm">
		<h2 class="title"><a href="show.php?pid=<? echo $threadId ?>"><? echo $threadTitle?></a></h2>
							<p class="meta"><span class="date"> <? echo date('l, d F, Y',$threadDate) ?> - Posted by <a href="#"><? echo $threadUsername ?> </a></p>

	</div>
	</div>
	</div> 

<?php
}
	mysqli_free_result($threads);
	$mysqli->close();
	include('footer.php');
?>
</body>
</html>
