<?php

	session_start();
	
	//I had to comment this out when I uploaded it because the host I was using does not support https
	/*
	if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off')
	{
		// NOT HTTPS
		header('Location: https://' .$_SERVER["SERVER_NAME"].
									 $_SERVER['REQUEST_URI']);
		
		die();
	}*/
	
	if(isset($_SESSION['username']))
	{
		header("Location: app.php");
		die();
	}
	
	
	function sendUnauthorized()
	{
		
		if(function_exists("http_response_code"))
		{
			http_response_code(401);
		}
		else
		{
			header("HTTP/1.1 ". 401);
		}
		
		$response_array = array (
							"status" => "error",
							"message" => "Invalid username or password");
		
		echo json_encode($response_array);
		
		die();
	}
	
	if($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		if(empty($_POST["username"]) || empty($_POST["pass"]))
		{
			sendUnauthorized();
		}
		
		$username = $_POST["username"];
		$pass = $_POST["pass"];
		
		$dbconnection = require("dbconnect.php");
		
		require("dbaccess.php");
		
		$loginStatus = dblogin($dbconnection, $username, $pass);
		
		if($loginStatus === true)
		{
			$_SESSION['username'] = $username;
			
			$response = array("status" => 200, "redirect" => "app.php");
			echo json_encode($response);
			die();
		}
		else
		{
			sendUnauthorized();
		}
	}
	else
	{
		$loginpage = file_get_contents("login.html");
		echo $loginpage;
	}


?>