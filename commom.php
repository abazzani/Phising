<?php

// These constants may be changed without breaking existing hashes.
define("HASH_ALGORITHM", "sha256");
define("SALT_BYTE_SIZE", 20);
  
function create_salt()
{
    // format: algorithm:iterations:salt:hash
    $salt = base64_encode(mcrypt_create_iv(SALT_BYTE_SIZE, MCRYPT_DEV_URANDOM));
    return  $salt ;        
}

function hash_password($salt, $password)
{    	
	$passwordHash = hash(HASH_ALGORITHM, $salt . $password);
    return  $passwordHash;        
}

//Function to generate the hash of the username to ensure that the user cookie is valid and it hasn't changed
function mac_string($username)
{    	
	$MAC_KEY = 'JP6A5jUTnADATI';
	$string_MAC = hash_hmac(HASH_ALGORITHM, $username, $MAC_KEY);
	return  $string_MAC;  
}

//$personalString is used to avoid phising; is like the picture in some banks
//The other fields are already scaped.
function store_user($username, $password, $firstName, $lastName, $mysqli, $personalString)
{	
	$salt = create_salt();
	$salt = substr($salt, 0, SALT_BYTE_SIZE);
	$passwordHash = hash_password($salt, $password);
	
	if (!($stmt = $mysqli->prepare("INSERT INTO users (username, pass, fname, lname, extra, extra2) VALUES (?, ?, ?, ?, ?, ?)"))) {
            	echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
	if (!$stmt->bind_param('ssssss', $username, $passwordHash, $firstName, $lastName, $salt, $personalString)) {
              	echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	if (!$stmt->execute()) {
	    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	$stmt->close();
}

function validate_username($input,$pattern = '[^A-Za-z0-9]')
{
	return !ereg($pattern,$input);
}

function redirect($file){
	$host = $_SERVER["HTTP_HOST"];
	$path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
	header("Location: https://forest.cs.purdue.edu/~abazzani/$file");
	exit;	
}

//Gets a random word from a text file
function getWord()
{
	$file = file("phrases");
	$newLines = array();
	$firstRecord = 1;
	foreach ($file as $line){
    	if ($firstRecord == 1){
			$firstline=chop($line);
			$firstRecord = 0;
	    }
		else{
        	$newLines[] = chop($line);
			$newFile = implode("\n", $newLines);
			file_put_contents("phrases2", $newFile);	
		}
	}
	unlink('phrases');
	rename("phrases2", "phrases");
	return $firstline;
}

function makeSafe($value) {
	$value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	return $value;
}

//If an attacker wants to guess users, every new user that does not exist will be assign a random string
function storeUser($username, $word)
{	
	$file="invalidusers";
	$data = $username . ',' . $word . "\n";	
	file_put_contents($file, $data, FILE_APPEND);	
}

function bind_array($stmt, &$row) {
    $md = $stmt->result_metadata();
    $params = array();
    while($field = $md->fetch_field()) {
        $params[] = &$row[$field->name];
    }

    call_user_func_array(array($stmt, 'bind_result'), $params);
}
 
?>
