<?php


if (!securePage($_SERVER['PHP_SELF'])){die();}
$userId = $_GET['id'];
$successes="";
$errors="";

//Check if selected user exists
if(!userIdExists($userId)){
  $url = "index.php?page=1.7&successes=".$successes."&errors=".$errors;
    redirect($url); die();
}

$userdetails = fetchUserDetails(NULL, NULL, $userId); //Fetch user details

//Forms posted
if(!empty($_POST))
{ 
  //Delete selected account
  if(!empty($_POST['delete'])){
    $deletions = $_POST['delete'];
    if ($deletion_count = deleteUsers($deletions)) {
      $successes .="Account Successfully deleted<br>";

    }
    else {
      $errors .= "Fatal Data error in SQL, contact developer or try again<br>";
    }
  }
  else
  {
    //Update display name
    if ($userdetails['display_name'] != $_POST['display']){
      $displayname = trim($_POST['display']);
      
      //Validate display name
      if(displayNameExists($displayname))
      {
        $errors .= "Display Name in Use<br>";

      }
      elseif(minMaxRange(5,25,$displayname))
      {
        $errors .= "Display Name must be between 5 and 25 characters in length<br>";
      }
      elseif(!ctype_alnum($displayname)){
        $errors .= "Display Name contains invalid characters<br>";
      }
      else {
        if (updateDisplayName($userId, $displayname)){
          $successes .="Display Name Successfully updated<br>";
        }
        else {
          $errors .= "Fatal Data error in SQL, contact developer or try again<br>";
        }
      }
      
    }
    else {
      $displayname = $userdetails['display_name'];
    }
    
    //Activate account
    if(isset($_POST['activate']) && $_POST['activate'] == "activate"){
      if (setUserActive($userdetails['activation_token'])){
        $successes .="Account manually updated<br>";
      }
      else {
        $errors .= "Fatal Data error in SQL, contact developer or try again<br>";
      }
    }
    
    //Update email
    if ($userdetails['email'] != $_POST['email']){
      $email = trim($_POST["email"]);
      
      //Validate email
      if(!isValidEmail($email))
      {
        $errors .= "Invalid Email<br>";
      }
      elseif(emailExists($email))
      {
        $errors .= "Email in Use<br>";
      }
      else {
        if (updateEmail($userId, $email)){
          $successes .="Email Successfully Updated<br>";
        }
        else {
          $errors .= "Fatal Data error in SQL, contact developer or try again<br>";
        }
      }
    }
    
    //Update title
    if ($userdetails['title'] != $_POST['title']){
      $title = trim($_POST['title']);
      
      //Validate title
      if(minMaxRange(1,50,$title))
      {
        $errors .= "Title must be between 1 and 50 characters in length<br>";
      }
      else {
        if (updateTitle($userId, $title)){
          $successes .="Account Title Successfully Updated<br>";
        }
        else {
          $errors .= "Fatal Data error in SQL, contact developer or try again<br>";
        }
      }
    }
    
    //Remove permission level
    if(!empty($_POST['removePermission'])){
      $remove = $_POST['removePermission'];
      if ($deletion_count = removePermission($remove, $userId)){
        $successes .="Permission Successfully Removed<br>";
      }
      else {
        $errors .= "Fatal Data error in SQL, contact developer or try again<br>";
      }
    }
    
    if(!empty($_POST['addPermission'])){

      $add = $_POST['addPermission'];
      if ($addition_count = addPermission($add, $userId)){
        $successes .="Permission Successfully Added<br>";
      }
      else {
        $errors .= "Fatal Data error in SQL, contact developer or try again<br>";
      }
    }
    
    $userdetails = fetchUserDetails(NULL, NULL, $userId);
  }

  $url = "index.php?page=1.6&id=".$userId."&successes=".$successes."&errors=".$errors;
  redirect($url);
}

$userPermission = fetchUserPermissions($userId);
$permissionData = fetchAllPermissions();

echo "
            <div class='row'>
                <div class='col-lg-12'>
                    <h3 class='page-header'>Staff Permission & Settings</h3>
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
                                <div class='col-lg-6'>
                                    <form role='form' name='adminUser' action='index.php?page=1.6&id=".$userId."' method='post'>
                                        <div class='form-group'>
                                            <label>Id</label>
                                            <input class='form-control' value='".$userdetails['id']."' disabled>
                                        </div>
                                        <div class='form-group'>
                                            <label>User Name</label>
                                            <input class='form-control' value='".$userdetails['user_name']."' disabled>
                                        </div>
                                         <div class='form-group'>
                                            <label>Display Name</label>
                                            <input class='form-control' type='text' name='display' value='".$userdetails['display_name']."'>
                                        </div>
                                        <div class='form-group'>
                                            <label>Email</label>
                                            <input class='form-control' type='email' name='email' value='".$userdetails['email']."' required>
                                        </div>
                                        <button type='submit' class='btn btn-default'>Update</button>                    
                                </div>
                                <!-- /.col-lg-6 (nested) -->

                                <div class='col-lg-6'>
                                    
                                        <div class='form-group'>
                                            <label>Active</label>
                                            <p class='form-control-static'>";

                                                if ($userdetails['active'] == '1'){
                                                    echo "Yes"; 
                                                }
                                                else
                                                {
                                                    echo "No";
                                                }
                                    echo "</p>
                                        </div>
                                        
                                        <div class='form-group'>
                                            <label>Title</label>
                                            <select class='form-control' name='title'>";
                                                  foreach ($permissionData as $v1) {
                                                      if ($v1['name'] == $userdetails['title'])
                                                      { echo "<option value='".$v1['name']."' selected>".$v1['name']."</option>"; }
                                                      else
                                                      { echo "<option value='".$v1['name']."'>".$v1['name']."</option>"; }
                                                  }
                                                echo"
                                            </select>
                                        </div>

                                        <div class='form-group'>
                                            <label>Last Sign In</label>
                                            <p class='form-control-static'>";

                                                //Last sign in, interpretation
                                                if ($userdetails['last_sign_in_stamp'] == '0'){
                                                  echo "Never"; 
                                                }
                                                else 
                                                {
                                                  echo date("j M, Y", $userdetails['last_sign_in_stamp']);
                                                }

                                      echo "</p>
                                        </div>

                                        <div class='form-group'>
                                            <label>Last Sign Up</label>
                                            <p class='form-control-static'>".date("j M, Y", $userdetails['sign_up_stamp'])."</p>
                                        </div>

                                        <div class='form-group'>
                                            <div class='checkbox'>
                                                <label>
                                                    <input type='checkbox' name='delete[".$userdetails['id']."]' id='delete[".$userdetails['id']."]' value='".$userdetails['id']."'>  Delete
                                                </label>
                                            </div>
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
