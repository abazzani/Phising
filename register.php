<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>hackme</title>
<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
<?php
	if (empty($_SERVER['HTTPS'])) {
    		header("Location: https://forest.cs.purdue.edu/~abazzani/register.php");
	}
	include('connect.php');
	include('header.php');
	$mysqli = connect2();
	include('commom.php');
?>
<div class="post">
	<div class="post-bgtop">
		<div class="post-bgbtm">
        <h2 class = "title">hackme Registration</h2>
        <?php
		//if the registration form is submitted 
		if (isset($_POST['submit'])) {
			
			$_POST['uname'] = trim($_POST['uname']);
			if(!$_POST['uname'] | !$_POST['password'] |
				!$_POST['fname'] | !$_POST['lname'] | !$_POST['personalstring']) {
 				die('<p>You did not fill in a required field.
				Please go back and try again!</p>');
				redirect("index.php");
 			}
			
			$username = $_POST['uname'];
			$password = $_POST['password'];
			$firstName = $_POST['fname'];
			$lastName = $_POST['lname'];
			$personalString = $_POST['personalstring'];	
			

			if (!($stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?"))) {
                		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
               		 }
			if (!$stmt->bind_param('s', $username)) {
                		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
		
			if (!$stmt->execute()) {
		 	  	 echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
		$check = $stmt->result_metadata();
		$stmt->store_result();
 		//Gives error if user does not exist
 		$check2 = $stmt->num_rows;
		if ($check2 != 0) {
			die('<p>Sorry, user name already exists.</p>');
		}
		else
		{
			store_user($username, $password, $firstName, $lastName, $mysqli, $personalString);
			
			echo "<h3> Registration Successful!</h3> <p>Welcome ". $firstName ."! Please log in...</p>";
		} 
        ?>    
        <?php
		}else{
        ?>
        	<form  method="post" action="register.php">
            <table>
                <tr>
                    <td> Username </td> 
                    <td> <input type="text" name="uname" maxlength="20"/> </td>
                    <td> <em>choose a login id</em> </td>
                </tr>
                <tr>
                    <td> Password </td>
                    <td> <input type="password" name="password" maxlength="40" /> </td>
                </tr>
                <tr>
                    <td> First Name </td>
                    <td> <input type="text" name="fname" maxlength="25"/> </td>
                </tr>
                 <tr>
                    <td> Last Name </td>
                    <td> <input type="text" name="lname" maxlength="25"/> </td>
                </tr>
                <tr>
                    <td> Personal String </td>
                    <td> <input type="text" name="personalstring" maxlength="25"/> </td>
                </tr>
                <tr>
                    <td> <input type="submit" name="submit" value="Register" /> </td>
                </tr>
            </table>
            </form>
        <?php
		}
		?>
        </div>
    </div>
</div>
<?php
	include('footer.php');
?>
</body>
</html>
