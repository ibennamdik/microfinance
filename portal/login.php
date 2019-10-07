<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
//Prevent the user visiting the logged in page if he/she is already logged in
$successes="";
$errors="";
global $user_id;

if(isset($_GET['logOut'])) { 
    $staff->userLogOut();
}
//Forms posted
if(isset($_POST['LoginStaff']))
{
  //$errors = array();
  $errors="";
  $username = sanitize(trim($_POST["username"]));
  $password = trim($_POST["password"]);

  //Perform some validation
  //Feel free to edit / change as required
  if($username == "")
  {
    $errors .= "Please enter your username<br>";
  }
  if($password == "")
  {
    $errors .= "Please enter your Password<br>";
  }
  
  if($errors == "")
  {
      //fetch user data from db
      $userdetails = fetchUserDetails($username);
      if(usernameExists($username))
      {   
          $entered_pass = generateHash($password,$userdetails["password"]);
          if ($entered_pass == $userdetails["password"])
          {
              //Transfer some db data to the session object
              $user_id = $userdetails["id"];
              $_SESSION["userCakeUserNaml"] = "LoggedIn";
              $_SESSION['email'] = $userdetails["email"];
              $_SESSION['id'] = $userdetails["id"];
              $_SESSION['hash_pw'] = $userdetails["password"];
              $_SESSION['title'] = $userdetails["title"];
              $_SESSION['username'] = $userdetails["user_name"];
              $_SESSION['staff'] = $userdetails["user_name"];
              $_SESSION['display_name'] = $userdetails["display_name"];

              //Update last sign in
              $staff->updateLastSignIn();
              redirect("index.php"); die();
          }
          else
          {
              //A security note here, never tell the user which credential was incorrect
              $errors .= "Username or password is invalid<br>";
          }
      }
      else
      {
          //A security note here, never tell the user which credential was incorrect
          $errors .= "Username or password is invalid<br>";
      }
  }
}

echo "
<!DOCTYPE html>
<html lang='en'>

<head>

    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='description' content=''>
    <meta name='author' content=''>

    <title>NAML - PORTAL</title>

    <!-- Bootstrap Core CSS -->
    <link href='vendor/bootstrap/css/bootstrap.min.css' rel='stylesheet'>

    <!-- MetisMenu CSS -->
    <link href='vendor/metisMenu/metisMenu.min.css' rel='stylesheet'>

    <!-- Custom CSS -->
    <link href='dist/css/sb-admin-2.css' rel='stylesheet'>

    <!-- Morris Charts CSS -->
    <link href='vendor/morrisjs/morris.css' rel='stylesheet'>

    <!-- Custom Fonts -->
    <link href='vendor/font-awesome/css/font-awesome.min.css' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src='https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js'></script>
        <script src='https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js'></script>
    <![endif]-->
</head>

<body>
    <div class='container'>
      <div class='row'>
          <div class='col-lg-12' style='margin-top: 5px;'>";
             
                  if (isset($_GET['errors']) || isset($_GET['successes'])){
                    $errors = $_GET['errors'];
                    $successes = $_GET['successes'];
                    resultBlock($errors,$successes);
                  }
              
              echo"
          </div>
      </div>

        <div class='row'>
            <div class='col-md-4 col-md-offset-4'>
                <div class='login-panel panel panel-default'>
                    <div class='panel-heading'>
                        <h3 class='panel-title' align='center'>NAML: Staff Sign In</h3>
                    </div>
                    <div class='panel-body'>".resultBlock($errors,$successes)."
                        <form role='form' action='#' method='post' name='login'>
                            <fieldset>
                                <div class='form-group'>
                                    <input class='form-control' type='text' name='username' required autofocus>
                                </div>
                                <div class='form-group'>
                                    <input class='form-control' type='password' name='password' required>
                                </div>
                                <div style='text-align:center;'>
                                    <label>
                                        <a href='http://".$_SERVER['HTTP_HOST']."/public/'>Customer Login</a> | <a href='http://".$_SERVER['HTTP_HOST']."/'>Go to Website</a>
                                    </label>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button class='btn btn-success' type='submit' name='LoginStaff' value='True'>Login</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src='vendor/jquery/jquery.min.js'></script>

    <!-- Bootstrap Core JavaScript -->
    <script src='vendor/bootstrap/js/bootstrap.min.js'></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src='vendor/metisMenu/metisMenu.min.js'></script>

    <!-- Custom Theme JavaScript -->
    <script src='dist/js/sb-admin-2.js'></script>
</body>

</html>";
?>