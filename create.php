<?php
	session_start();
	
	if(isset($_SESSION['username']))
	{
		header("Location: app.php");
		die();
	}
	
	function sendError($code, $message)
	{
		
		
		if(function_exists("http_response_code"))
		{
			http_response_code($code);
		}
		else
		{
			header("HTTP/1.1 ".$code);
		}
		
		
		$response_array = array("status" => "error", "message" => $message);
		echo json_encode($response_array);
		die();
	}
	
	if($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		if(empty($_POST["username"]) || empty($_POST["pass"]) || empty($_POST["confirm"]))
			{
				sendError(400,"All field are required");
			}
			
		if($_POST["pass"] != $_POST["confirm"])
		{
			sendError(400, "Passwords do not match!");
		}
		
		$dbconnection = require("dbconnect.php");
		require("dbaccess.php");
		
		$userid = getUserId($dbconnection, $_POST["username"]);
		
		if($userid !== NULL)
		{
			sendError(400, "This username is already taken");
		}
		
		$exception = addUser($dbconnection, $_POST["username"], $_POST["pass"]);
		
		if(!empty($exception))
		{
			sendError(400, $exception);
		}
		else
		{
			$_SESSION["username"] = $_POST["username"];
		}
		
		$response = array("status" => 200, "redirect" => "app.php");
		echo json_encode($response);
		die();
		
		
	}
	else
	{
		if(empty($_SESSION['username']))
		{
			header("Location: app.php");
			die();
		}
	}
	
	
?>