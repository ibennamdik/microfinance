<?php

if (!securePage($_SERVER['PHP_SELF'])){die();}

//Forms posted
if(!empty($_POST))
{
  $errors = array();
  $email = trim($_POST["email"]);
  $username = trim($_POST["username"]);
  $displayname = trim($_POST["displayname"]);
  $password = trim($_POST["password"]);
  $confirm_pass = trim($_POST["passwordc"]);
  $captcha = md5($_POST["captcha"]);
  
  
  if ($captcha != $_SESSION['captcha'])
  {
    $errors[] = lang("CAPTCHA_FAIL");
  }
  if(minMaxRange(5,25,$username))
  {
    $errors[] = lang("ACCOUNT_USER_CHAR_LIMIT",array(5,25));
  }
  if(!ctype_alnum($username)){
    $errors[] = lang("ACCOUNT_USER_INVALID_CHARACTERS");
  }
  if(minMaxRange(5,25,$displayname))
  {
    $errors[] = lang("ACCOUNT_DISPLAY_CHAR_LIMIT",array(5,25));
  }
  // if(!ctype_alnum($displayname)){
  //   $errors[] = lang("ACCOUNT_DISPLAY_INVALID_CHARACTERS");
  // }
  if(minMaxRange(8,50,$password) && minMaxRange(8,50,$confirm_pass))
  {
    $errors[] = lang("ACCOUNT_PASS_CHAR_LIMIT",array(8,50));
  }
  else if($password != $confirm_pass)
  {
    $errors[] = lang("ACCOUNT_PASS_MISMATCH");
  }
  if(!isValidEmail($email))
  {
    $errors[] = lang("ACCOUNT_INVALID_EMAIL");
  }
  //End data validation
  if(count($errors) == 0)
  { 
    //Construct a user object
    $user = new User($username,$displayname,$password,$email);
    
    //Checking this flag tells us whether there were any errors such as possible data duplication occured
    if(!$user->status)
    {
      if($user->username_taken) $errors[] = lang("ACCOUNT_USERNAME_IN_USE",array($username));
      if($user->displayname_taken) $errors[] = lang("ACCOUNT_DISPLAYNAME_IN_USE",array($displayname));
      if($user->email_taken)    $errors[] = lang("ACCOUNT_EMAIL_IN_USE",array($email));   
    }
    else
    {
      //Attempt to add the user to the database, carry out finishing  tasks like emailing the user (if required)
      if(!$user->userCakeAddUser())
      {
        if($user->mail_failure) $errors[] = lang("MAIL_ERROR");
        if($user->sql_failure)  $errors[] = lang("SQL_ERROR");
      }
    }
  }
  if(count($errors) == 0) {
    $successes[] = $user->success;
    $url = "index.php?page=1.7";
      redirect($url);

  }
}

// $breadcrumb_array =  array('Page Management' => 'index.php?page=1.2', 'Settings' => '#');
// $page_title = "Staff Registration";
// title_block($page_title, $breadcrumb_array);
resultBlock($errors,$successes);

echo "

        <div id='page-wrapper'>
            <div class='row'>
                <div class='col-lg-12'>
                    <h3 class='page-header'>Staff Registeration</h3>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class='row'>
                <div class='col-lg-12'>
                    <div class='panel panel-default'>
                        <div class='panel-heading'>
                            User Information
                        </div>
                        <div class='panel-body'>
                            <div class='row'>
                                <form role='form' name='newUser' method='post' action='index.php?page=1.5'>
                                    <div class='col-lg-6'>
                                    
                                        <div class='form-group'>
                                            <label>User Name</label>
                                            <input class='form-control' name='username' required placeholder='Enter text'>
                                        </div>
                                        <div class='form-group'>
                                            <label>Display Name</label>
                                            <input class='form-control' name='displayname' required placeholder='Enter text'>
                                        </div>
                                        <div class='form-group'>
                                            <label>Password</label>
                                            <input class='form-control' type='password' name='password' required  placeholder='Enter text'>
                                        </div>
                                        <div class='form-group'>
                                            <label>Password Again</label>
                                            <input class='form-control' type='password' name='passwordc' required  placeholder='Enter text'>
                                        </div>
                                    </div>
                                    <!-- /.col-lg-6 (nested) -->
                                    <div class='col-lg-6'>
                                        <div class='form-group'>
                                            <label>Email</label>
                                            <input class='form-control' type='email' name='email'  placeholder='Enter text'>
                                        </div>
                                        <div>
                                            <label>Security Code</label>
                                            <p class='form-control-static'><img src='models/captcha.php'></p>
                                        </div>
                                        <div class='form-group'>
                                            <label>Enter Security Code</label>
                                            <input class='form-control' type='text' name='captcha' required placeholder='Enter text'>
                                        </div>
                                        <div class='form-group'>
                                            <label></label>
                                        </div>
                                            
                                        <button type='submit' class='btn btn-default'>Register</button>                           
                                    </div>
                                </form>
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