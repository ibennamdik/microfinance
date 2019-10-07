<?php

if (!securePage($_SERVER['PHP_SELF'])){die();}
$successes="";
$errors="";

//Prevent the user visiting the logged in page if he is not logged in
if(!empty($_POST))
{
    $successes="";
    $errors="";

    $password = $_POST["password"];
    $password_new = $_POST["passwordc"];
    $password_confirm = $_POST["passwordcheck"];
    $email = $_POST["email"];
    
    //Confirm the hashes match before updating a users password
    $entered_pass = generateHash($password,$_SESSION['hash_pw']);
    
    if (trim($password) == ""){
        $errors .= "Please enter your Password<br>";
    }
    else if($entered_pass != $_SESSION['hash_pw'])
    {
        //No match
        $errors .= "Invalid Password<br>";

    }   
    if($email != $_SESSION['email'])
    {
        if(trim($email) == "")
        {
            $errors .= "Please enter your email<br>";
        }
        else if(!isValidEmail($email))
        {
            $errors .= "Invalid Email<br>";
        }
        else if(!emailExists($email))
        {
            $errors .= "Email in Doesnt exist in records<br>";  
        }
        //End data validation
    }
    
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
            $errors .= "Passwords do not match<br>";
        }
        if(!emailUsernameLinked($email, $_SESSION['username'])) {
            $errors .= "The Email, Password do not belong to this Logged in User<br>"; 
        }
                
        
        //End data validation
        if($errors == "")
        {
            //Also prevent updating if someone attempts to update with the same password
            $entered_pass_new = generateHash($password_new, $_SESSION['hash_pw']);
            
            if($entered_pass_new == $_SESSION['hash_pw'])
            {
                //Don't update, this fool is trying to update with the same password Â¬Â¬
                $errors .= "You cannot update with the same password<br>";  
            }
            else
            {
                //This function will create the new hash and update the hash_pw property.
                $staff->updatePassword($password_new);
                    
                $successes .= "Password Updated<br>"; 
                //The message, In case any of our lines are larger than 70 characters, we should use wordwrap()
                $message = "Your NAML login details are \r\n ".$email.", ".$password_confirm;
                $message = wordwrap($message, 70, "\r\n");
                mail($email, 'NAML Micro Credit Scheme', $message);
            }
        }
    }
    // if($errors == "" AND $successes == ""){
    //     $errors .= "Nothing to Update<br>";
    // }
    echo $errors;
}

echo "
            <div class='row'>
                <div class='col-lg-12'>
                    <h3 class='page-header'>User Password</h3>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class='row'>
                <div class='col-lg-12'>
                    <div class='panel panel-default'>
                        <div class='panel-heading'>
                            Basic Form Elements
                        </div>
                        <div class='panel-body'>
                            <div class='row'>
                                <form role='form' method='post' action='index.php?page=1.8' name='updateAccount'>
                                    <div class='col-lg-6 col-lg-offset-3'>
                                    
                                        <div class='form-group'>
                                            <label>Password</label>
                                            <input class='form-control' type='password' name='password' required placeholder='Enter text'>
                                        </div>
                                        <div class='form-group'>
                                            <label>Email</label>
                                            <input class='form-control' name='email' value='".$_SESSION['email']."' required>
                                        </div>
                                        <div class='form-group'>
                                            <label>Password</label>
                                            <input class='form-control' type='password' name='passwordc' required  placeholder='Enter text'>
                                        </div>
                                        <div class='form-group'>
                                            <label>Password Again</label>
                                            <input class='form-control' type='password' name='passwordcheck' required  placeholder='Enter text'>
                                        </div>
                                            
                                        <button type='submit' name='submit'  value='Update' >Update</button>                           
                                    </div>
                                </form>

                                </div>
                                <!-- /.col-lg-6 (nested) -->
                                
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->";
