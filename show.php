<?php
	session_start(); 
	include('connect.php');
	$mysqli = connect2();
	include('commom.php');
	
	if(!isset($_SESSION['auth'])){
		header('Location: index.php');
	}
	//if the login form is submitted 
	if (!isset($_GET['pid'])) {
		
		if (isset($_GET['delpid'])){

			if (!($stmt = $mysqli->prepare("DELETE FROM threads WHERE id = ?"))) {
                		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
              		}
			if (!$stmt->bind_param('s', $_GET[delpid])) {
             	 	  	echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
		
			if (!$stmt->execute()) {
			    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
		}
			header("Location: members.php");
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
	if(!isset($_COOKIE['hackme'])){
		header('Location: index.php');
		 //die('Why are you not logged in?!');
	}else
	{
		print("<p>Logged in as <a>$_COOKIE[hackme]</a></p>");
	}
?>
<?php
	if (!($stmt = $mysqli->prepare("SELECT * FROM threads WHERE id = ?"))) {
             	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
	if (!$stmt->bind_param('s', $_GET['pid'])) {
             	echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	if (!$stmt->execute()) {
	    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	bind_array($stmt, $thisthread);
    	$stmt->fetch();
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
         
         <div class="entry">
			
            <? echo makeSafe($thisthread[message]) ?>
            					
		 </div>
         
	</div>
	</div>
	</div>
    
    <?php
	
		if ($_COOKIE['hackme'] == $threadUsername)
		{
	?>
    	<a href="show.php?delpid=<? echo $threadId?>">DELETE</a>
    <?php
		}
	?> 

<?php
	include('footer.php');
?>
</body>
</html>
