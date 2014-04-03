<?php
	session_start();
	if (empty($_SERVER['HTTPS'])) {
    		header("Location: https://forest.cs.purdue.edu/~abazzani/post.php");
	}
// Connects to the Database 
	include('connect.php');
	include('commom.php');
	$mysqli = connect2();
	include 'csrf.class.php';
 
	if(!isset($_SESSION['auth'])){
		header('Location: index.php');
	}
	$csrf = new csrf();
	// Generate Token Id and Valid
	$token_id = $csrf->get_token_id();
	$token_value = $csrf->get_token($token_id);
 
	// Generate Random Form Names
	$form_names = $csrf->form_names(array('title', 'message'), false);
	//if the login form is submitted 
	if (isset($_POST['post_submit']) && isset($_COOKIE['hackme'])) {		
		$_POST[$form_names['title']] = trim($_POST[$form_names['title']]);
		if(!$_POST[$form_names['title']] | !$_POST[$form_names['message']]) {
			include('header.php');
			die('<p>You did not fill in a required field.
			Please go back and try again!</p>');
		}

	
		if( mac_string($_COOKIE['hackme']) == $_SESSION['usernamemac']){
			header('Location: logout.php');
		}
		if($csrf->check_valid('post')) {
		  if (!($stmt = $mysqli->prepare("INSERT INTO threads (username, title, message, date) VALUES (?, ?, ?, ?)"))) {
            		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        	  }
		  if (!$stmt->bind_param('ssss', $_COOKIE['hackme'], $_POST[$form_names['title']], $_POST[$form_names['message']], time())) {
              		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		  }
		  if (!$stmt->execute()) {
	  	 	echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		  }	
		header("Location: members.php");
		}
	}
	$form_names = $csrf->form_names(array('title', 'message'), false);
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
				 header('Location: index.php');
			}else
			{
				print("<p>Logged in as <a>$_COOKIE[hackme]</a></p>");
			}
			?>
            
            <h2 class="title">NEW POST</h2>
            <p class="meta">by <a href="#"><? echo $_COOKIE['hackme'] ?> </a></p>
            <p> do not leave any fields blank... </p>
            
            <form method="post" action="post.php">
            <input type="hidden" name="<?= $token_id; ?>" value="<?= $token_value; ?>" />
            Title: <input type="text" name="<?= $form_names['title']; ?>" maxlength="50"/>
            <br />
            <br />
            Posting:
            <br />
            <br />
            <textarea name="<?= $form_names['message']; ?>" cols="120" rows="10" id="<?= $form_names['message']; ?>"></textarea>
            <br />
            <br />
            <input name="post_submit" type="submit" id="post_submit" value="POST" />
            </form>
        </div>
    </div>
</div>

<?php
	session_write_close();
	include('footer.php');
?>
</body>
</html>
