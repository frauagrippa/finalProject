<?php
	function dblogin($db, $user, $pass)
	{
		$command = "SELECT username, password FROM users
					where username = '" . $user . "' ";
					
		$results = mysqli_query($db, $command);
		checkError($db);
		
		if($results->num_rows === 0)
		{
			return false;
		}
		else
		{
			$hash = hash("sha512", $pass);
			$row = mysqli_fetch_assoc($results);
			
			if($hash === $row['password'])
			{
				return true;
			}
			else
			{
				return false;
			}
			
		}
		
	}
	
	function getUserID($db, $user)
	{
		$command = "SELECT id from users where username ='".$user."'";
		$results = mysqli_query($db,$command);
		
		checkError($db);
		
		if($results->num_rows === 0)
		{
			return NULL;
		}
		
		$row = mysqli_fetch_assoc($results);
		
		return $row['id'];
		
		
	}
	
	function addUser($db, $user, $pass)
	{
		$hash = hash("sha512", $pass);
		
		$command = "INSERT INTO users(username, password)
					VALUES('".$user."', '".$hash."')";
					
		mysqli_query($db,$command);
		
		$exception = mysqli_error($db);
		
		if(!empty($exception))
		{
			return $exception;
		}
		
		return NULL;
	}

?>