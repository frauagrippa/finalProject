<?php
	
	function checkError($db)
	{
		$exception = mysqli_error($db);
		
		if(!empty($exception))
		{
			echo $exception;
			die();
		}
	}

try 
{

	$connection = new mysqli("127.0.0.1","rxc4763","fr1end","rxc4763");

	return $connection;
} 

catch (Exception $e ) {

	echo "Service unavailable";
	echo "Failed to connect to MySQL:".$mysqli->connect_error;
	die();
}
?>