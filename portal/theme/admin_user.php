<?php


if (!securePage($_SERVER['PHP_SELF'])){die();}
$userId = $_GET['id'];

//Check if selected user exists
if(!userIdExists($userId)){
  //header("Location: admin_users.php"); die();
  $url = "index.php?page=1.7";
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
      $successes[] = lang("ACCOUNT_DELETIONS_SUCCESSFUL", array($deletion_count));
    }
    else {
      $errors[] = lang("SQL_ERROR");
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
        $errors[] = lang("ACCOUNT_DISPLAYNAME_IN_USE",array($displayname));
      }
      elseif(minMaxRange(5,25,$displayname))
      {
        $errors[] = lang("ACCOUNT_DISPLAY_CHAR_LIMIT",array(5,25));
      }
      elseif(!ctype_alnum($displayname)){
        $errors[] = lang("ACCOUNT_DISPLAY_INVALID_CHARACTERS");
      }
      else {
        if (updateDisplayName($userId, $displayname)){
          $successes[] = lang("ACCOUNT_DISPLAYNAME_UPDATED", array($displayname));
        }
        else {
          $errors[] = lang("SQL_ERROR");
        }
      }
      
    }
    else {
      $displayname = $userdetails['display_name'];
    }
    
    //Activate account
    if(isset($_POST['activate']) && $_POST['activate'] == "activate"){
      if (setUserActive($userdetails['activation_token'])){
        $successes[] = lang("ACCOUNT_MANUALLY_ACTIVATED", array($displayname));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }
    
    //Update email
    if ($userdetails['email'] != $_POST['email']){
      $email = trim($_POST["email"]);
      
      //Validate email
      if(!isValidEmail($email))
      {
        $errors[] = lang("ACCOUNT_INVALID_EMAIL");
      }
      elseif(emailExists($email))
      {
        $errors[] = lang("ACCOUNT_EMAIL_IN_USE",array($email));
      }
      else {
        if (updateEmail($userId, $email)){
          $successes[] = lang("ACCOUNT_EMAIL_UPDATED");
        }
        else {
          $errors[] = lang("SQL_ERROR");
        }
      }
    }
    
    //Update title
    if ($userdetails['title'] != $_POST['title']){
      $title = trim($_POST['title']);
      
      //Validate title
      if(minMaxRange(1,50,$title))
      {
        $errors[] = lang("ACCOUNT_TITLE_CHAR_LIMIT",array(1,50));
      }
      else {
        if (updateTitle($userId, $title)){
          $successes[] = lang("ACCOUNT_TITLE_UPDATED", array ($displayname, $title));
        }
        else {
          $errors[] = lang("SQL_ERROR");
        }
      }
    }
    
    //Remove permission level
    if(!empty($_POST['removePermission'])){
      $remove = $_POST['removePermission'];
      if ($deletion_count = removePermission($remove, $userId)){
        $successes[] = lang("ACCOUNT_PERMISSION_REMOVED", array ($deletion_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }
    
    if(!empty($_POST['addPermission'])){
      $add = $_POST['addPermission'];
      if ($addition_count = addPermission($add, $userId)){
        $successes[] = lang("ACCOUNT_PERMISSION_ADDED", array ($addition_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }
    
    $userdetails = fetchUserDetails(NULL, NULL, $userId);
  }

  $url = "index.php?page=1.7";
  redirect($url);
}

$userPermission = fetchUserPermissions($userId);
$permissionData = fetchAllPermissions();

// $breadcrumb_array =  array('Page Management' => 'index.php?page=1.2', 'Settings' => '#');
// $page_title = "Staff & Permission settings";
// title_block($page_title, $breadcrumb_array);
resultBlock($errors,$successes);

echo "

        <div id='page-wrapper'>
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
                                            <p class='form-control-static'>".$userdetails['title']."</p>
                                        </div>
                                        <div class='form-group'>
                                            <label>New Title</label>
                                            <select class='form-control' name='title'>
                                                <option value='Admin'>Admin</option>
                                                <option value='Consultant'>Consultant</option>
                                                <option value='Receptionist'>Receptionist</option>
                                                <option value='Accountant'>Accountant</option>
                                                
                                            </select>
                                        </div>
                                        <div class='form-group'>
                                            <label>Last Sign In</label>
                                            <input class='form-control' placeholder='Enter text'>
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
                                        
                                        <button type='submit' class='btn btn-default'>Update</button>
                                    </form>
                                </div>
                                <!-- /.col-lg-6 (nested) -->

                                <div class='col-lg-6'>
                                    <form role='form'>
                                        <div class='table-responsive'>
                                            <table class='table'>
                                                <tbody>
                                                    <tr>
                                                        <th colspan='2'><label>Remove Permission</label></th>
                                                    </tr>";

                                                  //List of permission levels user is apart of
                                                  foreach ($permissionData as $v1) {
                                                    if(isset($userPermission[$v1['id']])){
                                                      echo "
                                                        <tr>
                                                          <td>
                                                            <div>
                                                              <input id='option' type='checkbox' name='removePermission[".$v1['id']."]' id='removePermission[".$v1['id']."]' value='".$v1['id']."'>
                                                            </div>
                                                          </td>
                                                          <td>".$v1['name']."</td>
                                                        </tr>";
                                                      }
                                                    }
                                                    echo "
                                                </tbody>
                                            </table>
                                            <table class='table'>
                                                <tbody>
                                                    <tr>
                                                        <th colspan='2'><label>Add Permission</label></th>
                                                    </tr>";

                                                      foreach ($permissionData as $v1) {
                                                        if(!isset($userPermission[$v1['id']])){
                                                          echo "
                                                          <tr>
                                                            <td>
                                                              <div>
                                                                <input id='option' type='checkbox' name='addPermission[".$v1['id']."]' id='addPermission[".$v1['id']."]' value='".$v1['id']."'>
                                                              </div>
                                                            </td>
                                                            <td>".$v1['name']."</td>
                                                          </tr>";
                                                            
                                                        }
                                                      }
                                                      echo "
                                                </tbody>
                                            </table>
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
    <!-- /#wrapper -->
