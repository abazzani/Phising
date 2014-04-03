<?php
	
	function connect()
	{
		// Connects to the Database 
		mysql_connect("localhost", "cs526f13", "Th1sN*T9asS", false, 65536) or die(mysql_error());
		mysql_select_db("cs526p1") or die(mysql_error());
	}

	function connect2()
	{		
		// Connects to the Database 
		$mysqli = new MySQLi('localhost', 'cs526f13', 'Th1sN*T9asS', 'cs526p1') or die($mysqli->errno);
		
		return $mysqli;	
	}
?>
