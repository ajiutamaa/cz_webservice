<?php
	//Connection information for MySQL database
	$host = "ap-cdbr-azure-southeast-a.cloudapp.net";
	$dbname = "dbcrimezone";
	$username = "b64e15843be88e";
	$password = "027d297fad7814f ";
	
	// //Set communication with MySQL server with UTF-8
	// $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

	try
	{
		//Opens a connection to database using PDO library
		$db = new PDO("mysql:host={$host};dbname={$dbname}", $username, $password, $option);
	}
	catch(PDOException $e){
		die("Failed to connect to the database" . $e->getMessage());
	}

	//Configures PDO to throw exception when it encounters an error
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//Configures PDO to return database rows from the database using
	//an associative array
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	
	//Tells browser that content is encoded using UTF-8
	//and it should submit content back using UTF-8
	header('Content-Type: text/html; charset=utf-8');
	
	//Initializes a session. 
	session_start();
?>
