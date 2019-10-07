<?php 
require_once("models/dbConnect.php");
$error = "";

if (isset($_POST['login'])) 
{
    $email = $_POST['email'];
    $entered_pass = $_POST['password'];
    
    try
    {
        $statement = $DB_con->prepare("SELECT * FROM mc_customers WHERE email = '$email'");
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        $entered_pass = generateHash($entered_pass, $row["password"]);
        

        if ($row['password'] == $entered_pass) 
        {
            $_SESSION['user'] = $row['customer_id'];

            $success = "Login Successfull";
            $url = "index.php?errors=".$error."&successes=".$success;
    		redirect($url);
        } 
        else 
        {
            $error = "Invalid Details";
            $url = "login.php?errors=".$error."&successes=".$success;
    		redirect($url); 
        }

        return true;

    } catch (PDOException $ex){
        echo $ex->getMessage();
    }    
}


if(isser($_POST['changePassword']))
{
    $successes = "";
    $errors = "";

    $customer_id = $_POST["customer_id"];
    $password = $_POST["password"];
    $password_new = $_POST["passwordc"];
    $password_confirm = $_POST["passwordcheck"];
    $old_password = $customer->get_customer_password($customer_id);
    
    //password feilds where not empty
    if ($password_new != "" OR $password_confirm != "")
    {
        if(trim($password_new) == "")
        {
        	$errors .= "Please enter your new password<br>";
        }
        else if(trim($password_confirm) == "")
        {
            $errors .= "Please enter your password confirmation<br>";
        }
        else if(minMaxRange(8,50,$password_new))
        {   
            $errors .= "Password must be between 8 and 50 characters in length<br>";  
        }
        else if($password_new != $password_confirm)
        {
            $errors .= "New Passwords do not match<br>";
        }
        else if($password != $old_password)
        {
            $password_new = generateHash($password_new, $old_password);
        	$errors .= "Incorect Password<br>";
        }
		else
		{
			$password_new = generateHash($password_new, $old_password);
			if($password_new == $old_password)
	        {
	            //Don't update, this guy is trying to update with the same password Â¬Â¬
	            $errors .= "You cannot update with the same password<br>";  
	        }
        }
        
        //End data validation
        if($errors == "")
        {
            //This function will create the new hash and update the hash_pw property.
            $customer->update_password($customer_id, $password_new);
            $successes .= "Password Updated<br>";            
        }
    }
    $url = "index.php?customer_id=".$customer_id."&successes=".$successes."&errors=".$errors;
    redirect($url); 
}       
?>