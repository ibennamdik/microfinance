<?php

if (!securePage($_SERVER['PHP_SELF'])){die();}
$permissionId = $_GET['id'];

$successes="";
$errors="";

//Check if selected permission level exists
if(!permissionIdExists($permissionId)){
  $url = "index.php?page=1.4&successes=".$successes."&errors=".$errors;
  redirect($url); die();
}

$permissionDetails = fetchPermissionDetails($permissionId); //Fetch information specific to permission level

//Forms posted
if(!empty($_POST)){
  
  //Delete selected permission level
  if(!empty($_POST['delete'])){
    $deletions = $_POST['delete'];
    if ($deletion_count = deletePermission($deletions)){
    //$successes[] = lang("PERMISSION_DELETIONS_SUCCESSFUL", array($deletion_count));
    $successes .= "Permissions deleted Successfully<br>";
    $url = "index.php?page=1.4&successes=".$successes."&errors=".$errors;
    redirect($url);
    }
    else {
      //$errors[] = lang("SQL_ERROR");
      $errors .= "Fatal Data error in SQL, contact developer or try again<br>";   
    }
  }
  else
  {
    //Update permission level name
    if($permissionDetails['name'] != $_POST['name']) {
      $permission = trim($_POST['name']);
      
      //Validate new name
      if (permissionNameExists($permission)){
        $errors .= "Account permission in Use<br>";
      }
      elseif (minMaxRange(1, 50, $permission)){
        $errors .= "Permission must be between 1 and 50 characters in length<br>";  
      }
      else {
        if (updatePermissionName($permissionId, $permission)){
          $successes .="Successfully Updated<br>";
        }
        else {
          $errors .= "Fatal Data error in SQL, contact developer or try again<br>"; 
        }
      }
    }
    
    //Remove access to pages
    if(!empty($_POST['removePermission'])){
      $remove = $_POST['removePermission'];
      if ($deletion_count = removePermission($permissionId, $remove)) {
        $successes .="Successfully removed from user(s)<br>";
      }
      else {
        $errors .= "Fatal Data error in SQL, contact developer or try again<br>"; 
      }
    }
    
    //Add access to pages
    if(!empty($_POST['addPermission'])){
      $add = $_POST['addPermission'];
      if ($addition_count = addPermission($permissionId, $add)) {
        $successes .="Successfully added to user(s)<br>";
      }
      else {
        $errors .= "Fatal Data error in SQL, contact developer or try again<br>"; 
      }
    }
    
    //Remove access to pages
    if(!empty($_POST['removePage'])){
      $remove = $_POST['removePage'];
      if ($deletion_count = removePage($remove, $permissionId)) {
        $successes .="Successfully removed access to page(s)<br>";
      }
      else {
        $errors .= "Fatal Data error in SQL, contact developer or try again<br>"; 
      }
    }
    
    //Add access to pages
    if(!empty($_POST['addPage'])){
      $add = $_POST['addPage'];
      if ($addition_count = addPage($add, $permissionId)) {
        $successes .="Successfully added access to page(s)<br>";
      }
      else {
        $errors .= "Fatal Data error in SQL, contact developer or try again<br>"; 
      }
    }
    $permissionDetails = fetchPermissionDetails($permissionId);
  }

  $url = "index.php?page=1.3&id=".$permissionId."&successes=".$successes."&errors=".$errors;
  redirect($url);
}

$pagePermissions = fetchPermissionPages($permissionId); //Retrieve list of accessible pages
$permissionUsers = fetchPermissionUsers($permissionId); //Retrieve list of users with membership

$userData = fetchAllUsers(); //Fetch all users
$pageData = fetchAllPages(); //Fetch all pages

echo "    

            <div class='row'>
                <div class='col-lg-12'>
                    <h3 class='page-header'>Staff Permissions & Settings</h3>
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
                                <div class='col-lg-4'>
                                    <form role='form' name='adminPermission' action='index.php?page=1.3&id=".$permissionId."' method='post'>
                                        <div class='table-responsive'>
                                            <table class='table'>
                                                <tbody>
                                                    <tr>
                                                        <td><label>Id</label></td>
                                                        <td>".$permissionDetails['id']."</td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Name</label></td>
                                                        <td>
                                                            <div class='form-group'>
                                                                <input class='form-control' type='text' name='name' required='' value='".$permissionDetails['name']."'>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Delete</label></td>
                                                        <td><input type='checkbox' name='delete[".$permissionDetails['id']."]' value='".$permissionDetails['id']."'></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type='submit' class='btn btn-default'>Update</button>

                                </div>
                                <!-- /.col-lg-6 (nested) -->

                                <div class='col-lg-4'>
                                        <div class='table-responsive'>
                                            <table class='table'>
                                                <tbody>
                                                    <tr>
                                                        <th colspan='2'><label>Remove Members</label></th>
                                                    </tr>";

                                                  foreach ($userData as $v1) {
                                                      if(isset($permissionUsers[$v1['id']])){
                                                        echo "
                                                        <tr>
                                                          <td>
                                                            <input id='option' type='checkbox' value='".$v1['id']."' name='removePermission[".$v1['id']."]' id='removePermission[".$v1['id']."]'>
                                                          </td>
                                                          <td>".$v1['display_name']."</td>
                                                        </tr>
                                                        ";
                                                      }
                                                  }
                                                  echo "
                                                </tbody>
                                            </table>
                                            
                                            <table class='table'>
                                                <tbody>
                                                    <tr>
                                                        <th colspan='2'><label>Add Members</label></th>
                                                    </tr>";

                                                      foreach ($userData as $v1) {
                                                          if(!isset($permissionUsers[$v1['id']])){
                                                            echo "
                                                            <tr>
                                                              <td>
                                                                <div>
                                                                  <input type='checkbox' name='addPermission[".$v1['id']."]' id='addPermission[".$v1['id']."]' value='".$v1['id']."'>
                                                                </div>
                                                              </td>
                                                              <td>".$v1['display_name']."
                                                              </td>
                                                            </tr>";
                                                          }
                                                      }

                                                      echo "
                                                </tbody>
                                            </table>

                                        </div>
                                </div>
                                <!-- /.col-lg-6 (nested) -->

                                <div class='col-lg-4'>
                                        <div class='table-responsive'>
                                            <table class='table'>
                                                <tbody>
                                                    <tr>
                                                        <th colspan='2'><label>Public Access</label></th>
                                                    </tr>
                                                    ";
                                                      foreach ($pageData as $v1) 
                                                      {
                                                        if($v1['private'] != 1)
                                                        {
                                                          echo"<tr>
                                                                  <td><div>".$v1['page']."</div></td>
                                                               </tr>";
                                                        }
                                                      }
                                                      echo "
                                                </tbody>
                                            </table>
                                            <table class='table'>
                                                <tbody>
                                                    <tr>
                                                        <th colspan='2'><label>Remove Access</label></th>
                                                    </tr>";
                                                      foreach ($pageData as $v1) {
                                                          if(isset($pagePermissions[$v1['id']]) AND $v1['private'] == 1){
                                                            echo "
                                                            <tr>
                                                              <td>
                                                                <div>
                                                                  <input type='checkbox' name='removePage[".$v1['id']."]' id='removePage[".$v1['id']."]' value='".$v1['id']."'>
                                                                </div>
                                                              </td>


                                                              <td>".$v1['page']."
                                                              </td>
                                                            </tr>
                                                            ";
                                                          }
                                                      }
                                                      echo "
                                                </tbody>
                                            </table>
                                             <table class='table'>
                                                <tbody>
                                                    <tr>
                                                        <th colspan='2'><label>Add Access</label></th>
                                                    </tr>";
                                                      foreach ($pageData as $v1) {
                                                          if(!isset($pagePermissions[$v1['id']]) AND $v1['private'] == 1){
                                                            echo "
                                                            <tr>
                                                              <td>
                                                                <div>
                                                                  <input type='checkbox' name='addPage[".$v1['id']."]' id='addPage[".$v1['id']."]' value='".$v1['id']."'>
                                                                </div>
                                                              </td>


                                                              <td>".$v1['page']."
                                                              </td>
                                                            </tr>
                                                            ";
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
    <!-- /#wrapper -->";