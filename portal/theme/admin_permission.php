<?php

if (!securePage($_SERVER['PHP_SELF'])){die();}
$permissionId = $_GET['id'];

//Check if selected permission level exists
if(!permissionIdExists($permissionId)){
  //header("Location: admin_permissions.php"); die();
  $url = "index.php?page=1.4";
    redirect($url); die();
}

$permissionDetails = fetchPermissionDetails($permissionId); //Fetch information specific to permission level

//Forms posted
if(!empty($_POST)){
  
  //Delete selected permission level
  if(!empty($_POST['delete'])){
    $deletions = $_POST['delete'];
    if ($deletion_count = deletePermission($deletions)){
    $successes[] = lang("PERMISSION_DELETIONS_SUCCESSFUL", array($deletion_count));
    $url = "index.php?page=1.4";
    redirect($url);
    }
    else {
      $errors[] = lang("SQL_ERROR");  
    }
  }
  else
  {
    //Update permission level name
    if($permissionDetails['name'] != $_POST['name']) {
      $permission = trim($_POST['name']);
      
      //Validate new name
      if (permissionNameExists($permission)){
        $errors[] = lang("ACCOUNT_PERMISSIONNAME_IN_USE", array($permission));
      }
      elseif (minMaxRange(1, 50, $permission)){
        $errors[] = lang("ACCOUNT_PERMISSION_CHAR_LIMIT", array(1, 50));  
      }
      else {
        if (updatePermissionName($permissionId, $permission)){
          $successes[] = lang("PERMISSION_NAME_UPDATE", array($permission));
        }
        else {
          $errors[] = lang("SQL_ERROR");
        }
      }
    }
    
    //Remove access to pages
    if(!empty($_POST['removePermission'])){
      $remove = $_POST['removePermission'];
      if ($deletion_count = removePermission($permissionId, $remove)) {
        $successes[] = lang("PERMISSION_REMOVE_USERS", array($deletion_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }
    
    //Add access to pages
    if(!empty($_POST['addPermission'])){
      $add = $_POST['addPermission'];
      if ($addition_count = addPermission($permissionId, $add)) {
        $successes[] = lang("PERMISSION_ADD_USERS", array($addition_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }
    
    //Remove access to pages
    if(!empty($_POST['removePage'])){
      $remove = $_POST['removePage'];
      if ($deletion_count = removePage($remove, $permissionId)) {
        $successes[] = lang("PERMISSION_REMOVE_PAGES", array($deletion_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }
    
    //Add access to pages
    if(!empty($_POST['addPage'])){
      $add = $_POST['addPage'];
      if ($addition_count = addPage($add, $permissionId)) {
        $successes[] = lang("PERMISSION_ADD_PAGES", array($addition_count));
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }
      $permissionDetails = fetchPermissionDetails($permissionId);
  }
}

$pagePermissions = fetchPermissionPages($permissionId); //Retrieve list of accessible pages
$permissionUsers = fetchPermissionUsers($permissionId); //Retrieve list of users with membership
$userData = fetchAllUsers(); //Fetch all users
$pageData = fetchAllPages(); //Fetch all pages

// $breadcrumb_array =  array('Page Management' => 'index.php?page=1.2', 'Settings' => '#');
// $page_title = "Staff & Permission settings";
// title_block($page_title, $breadcrumb_array);
resultBlock($errors,$successes);

echo "    

        <div id='page-wrapper'>
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

                                    </form>
                                </div>
                                <!-- /.col-lg-6 (nested) -->

                                <div class='col-lg-4'>
                                    <form role='form'>
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
                                                            <div>
                                                              <input id='option' type='checkbox' value='".$v1['id']."' name='removePermission[".$v1['id']."]' id='removePermission[".$v1['id']."]'>
                                                            </div>
                                                          </td>
                                                          <td>".$v1['display_name']."
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

                                <div class='col-lg-4'>
                                    <form role='form'>
                                        <div class='table-responsive'>
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
                                                                  <input id='option' type='checkbox' value='".$v1['id']."' name='addPermission[".$v1['id']."]' id='addPermission[".$v1['id']."]'>
                                                                </div>
                                                              </td>
                                                              <td>".$v1['display_name']."
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
                                                        <th colspan='2'><label>Public Access</label></th>
                                                    </tr>
                                                    ";
                                                      foreach ($pageData as $v1) {
                                                        if($v1['private'] != 1){
                                                          echo "<br>".$v1['page'];
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
                                        <button type='submit' class='btn btn-default'>Submit Button</button>
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