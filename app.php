<?php
	session_start();
        
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
	
	if(empty($_SESSION['username']))
	{
		header("Location: login.html");
		die();
	}
	else
	{
            //get username
            $user = $_SESSION['username'];
            
            //get a connection
            $dbconnection = require("dbconnect.php");
            require("dbaccess.php");
            
            //get userid
            $idName = getUserID($dbconnection, $user);
            
            //check if post
            if($_SERVER['REQUEST_METHOD'] === 'POST')
            {
                $url = $_POST['imageurl'];
                
                if($url == "")
                {
                    sendError(400, "You suck");
                    die();
                }
                
                $command = 'INSERT INTO images (imageurl, userid) VALUES("'.$url.'", '.$idName.')';
                $results = mysqli_query($dbconnection, $command);
                
                $exception = mysqli_error($dbconnection);
		
				if(!empty($exception))
				{
								sendError(400, "You broke it");
					die();
				}
                
                
                $response = array(
                  "redirect"  => "app.php"
                );
                
                echo json_encode($response);
                
                die();
            }
            
            $command = "SELECT * FROM images WHERE userId = '$idName'";
            $results = mysqli_query($dbconnection, $command);
            
            checkError($dbconnection);
            
            
            
            //stuff
            require("appheader.html");
            
            while($row = mysqli_fetch_assoc($results))
            {
                $url = $row['imageurl'];
                echo '<div class="note"> <img class="hidden" src="'.$url.'" alt=""> </div>';           
                
            }
            
            
            require("appfooter.html");
	}

?>