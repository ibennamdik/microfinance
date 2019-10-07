<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

//Functions that do not interact with DB
//Checks if an email is valid
//redirect page
function redirect($url)
{
	if (!headers_sent()) 
	{
		header('Location:'.$url);
		exit;	
	}
	else
	{
		 echo "<script>window.location.href='".$url."'</script>";
		 echo "<noscript><meta http-equiv='refresh' content='0; url=".$url."'></noscript>";
		 exit;
	}
}

function isValidEmail($email)
{
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return true;
	}
	else {
		return false;
	}
}

//Checks if a string is within a min and max length
function minMaxRange($min, $max, $what)
{
	if(strlen(trim($what)) < $min)
		return true;
	else if(strlen(trim($what)) > $max)
		return true;
	else
	return false;
}

//Completely sanitizes text
function sanitize($str)
{
	return strtolower(strip_tags(trim(($str))));
}

//Check if an email exists in the DB
function emailExists($email)
{
	global $mysqli,$db_table_prefix;
	$stmt = $mysqli->prepare("SELECT email
		FROM mc_customers
		WHERE
		email = ?
		LIMIT 1");
	$stmt->bind_param("s", $email);	
	$stmt->execute();
	$stmt->store_result();
	$num_returns = $stmt->num_rows;
	$stmt->close();
	
	if ($num_returns > 0)
	{
		return true;
	}
	else
	{
		return false;	
	}
}

//remove special characters
function clean($string) {
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   	return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}


//Displays error and success messages
function resultBlock($errors,$successes){
	//Error block
	if(isset($errors) && $errors != "")
	{
		echo '<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';				
		echo $errors;
	    echo '</div>';
	}
	//Success block
	if(isset($successes) && $successes != "")
	{
		echo '<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
		echo $successes;
		echo '</div>';
	}
}

function generateHash($plainText, $salt = null)
{
	if ($salt === null)
	{
		$salt = substr(md5(uniqid(rand(), true)), 0, 25);
	}
	else
	{
		$salt = substr($salt, 0, 25);
	}
	
	return $salt . sha1($salt . $plainText);
}


?>