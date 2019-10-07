<?php
require_once("models/config.php");
$successes = "";
$errors = "";
$errorcount = 0;

//Forms posted
if(isset($_POST['newStaff']))
{
    $email = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $displayname = trim($_POST["displayname"]);
    $password = trim($_POST["password"]);
    $confirm_pass = trim($_POST["passwordc"]);
    $captcha = md5($_POST["captcha"]);
    
    //capatcha validation
    if ($captcha != $_SESSION['captcha'])
    {
      $errors .= "Captcha verification failed<br>";
      $errorcount++;
    }

    //username validation
    if(minMaxRange(5,25,$username))
    {
      $errors .= "Username must be between 5 and 25 characters in length<br>";
      $errorcount++;
    }
    if(usernameExists($username))
    {
      $errors .= "Username already taken<br>";
      $errorcount++;
    }
    if(!ctype_alnum($username)){
      $errors .= "User Name contains invalid characters<br>";
      $errorcount++;
    }

    //display name validation
    if(minMaxRange(5,25,$displayname))
    {
      $errors .= "Display Name must be between 5 and 25 characters in length<br>";
      $errorcount++;
    }
    if(displayNameExists($displayname))
    {
      $errors .= "Display already taken<br>";
      $errorcount++;
    }

    //password Validation
    if(minMaxRange(8,50,$password) && minMaxRange(8,50,$confirm_pass))
    {
      $errors .= "Password must be between 8 and 50 characters in length<br>";
      $errorcount++;
    }
    else if($password != $confirm_pass)
    {
      $errors .= "Passwords do not match<br>";
      $errorcount++;
    }

    //Email validation
    if(!isValidEmail($email))
    {
      $errors .= "Invalid Email<br>";
      $errorcount++;
    }
    if(emailExists($email))
    {
      $errors .= "Email already taken<br>";
      $errorcount++;
    }

    //echo "Before DB: ".$successes." ".$errors." ".$username." ".$displayname." ".$password." ".$email;
    //End data validation
    if($errorcount == 0 && $staff->staff_register($username, $displayname, $password, $email))
    { 
      $successes = "Registration Successful";  
    }
    $url = "index.php?page=1.7&successes=".$successes."&errors=".$errors;
    redirect($url); 
}
?>