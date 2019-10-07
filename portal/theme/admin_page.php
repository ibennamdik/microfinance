<?php

if (!securePage($_SERVER['PHP_SELF'])){die();}
$pageId = $_GET['id'];

//Check if selected pages exist
if(!pageIdExists($pageId)){
  //header("Location: admin_pages.php"); die(); 
  $url = "index.php?page=1.2";
    redirect($url); die();
}

$pageDetails = fetchPageDetails($pageId); //Fetch information specific to page

//Forms posted
if(!empty($_POST)){
  $update = 0;
  
  if(!empty($_POST['private'])){ $private = $_POST['private']; }
  
  //Toggle private page setting
  if (isset($private) AND $private == 'Yes'){
    if ($pageDetails['private'] == 0){
      if (updatePrivate($pageId, 1)){
        $successes[] = lang("PAGE_PRIVATE_TOGGLED", array("private"));
        $url = "index.php?page=1.2";
        redirect($url); die();
      }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }
  }
  elseif ($pageDetails['private'] == 1){
    if (updatePrivate($pageId, 0)){
      $successes[] = lang("PAGE_PRIVATE_TOGGLED", array("public"));
    }
    else {
      $errors[] = lang("SQL_ERROR");  
    }
  }
  
  //Remove permission level(s) access to page
  if(!empty($_POST['removePermission'])){
    $remove = $_POST['removePermission'];
    if ($deletion_count = removePage($pageId, $remove)){
      $successes[] = lang("PAGE_ACCESS_REMOVED", array($deletion_count));
    }
    else {
      $errors[] = lang("SQL_ERROR");  
    }
    
  }
  
  //Add permission level(s) access to page
  if(!empty($_POST['addPermission'])){
    $add = $_POST['addPermission'];
    if ($addition_count = addPage($pageId, $add)){
      $successes[] = lang("PAGE_ACCESS_ADDED", array($addition_count));
    }
    else {
      $errors[] = lang("SQL_ERROR");  
    }
  }
  
  $pageDetails = fetchPageDetails($pageId);
}

$pagePermissions = fetchPagePermissions($pageId);
$permissionData = fetchAllPermissions(); 


// $breadcrumb_array =  array('Page Management' => 'index.php?page=1.2', 'Settings' => '#');
// $page_title = "Staff Access Management";
// title_block($page_title, $breadcrumb_array);
resultBlock($errors,$successes);

      echo"

        <div id='page-wrapper'>
            <div class='row'>
                <div class='col-lg-12'>
                    <h3 class='page-header'>Staff List</h3>
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
                                <div class='col-lg-6'>
                                    <form role='form' name='adminPage' action='".$_SERVER['PHP_SELF']."?page=1.1&id=".$pageId."' method='post'>
                                        <div class='table-responsive'>
                                            <table class='table'>
                                                <tbody>
                                                    <tr>
                                                        <td><label>Id</label></td>
                                                        <td>
                                                        ".$pageDetails['id']."
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Name</label></td>
                                                        <td>
                                                            ".$pageDetails['page']."
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><label>Private</label></td>
                                                        <td>";

                                                            //Display private checkbox
                                                            if ($pageDetails['private'] == 1){
                                                              echo "<input type='checkbox' name='private' id='private' value='Yes' checked>";
                                                            }
                                                            else {
                                                              echo "<input type='checkbox' name='private' id='private' value='Yes'>"; 
                                                            }

                                                          echo"
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
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
                                                        <th colspan='2'><label>Remove Access</label></th>
                                                    </tr>
                                                    ";

                                                      foreach ($permissionData as $v1) {
                                                        if(isset($pagePermissions[$v1['id']])){
                                                          echo "
                                                            <tr>
                                                              <td>
                                                                <div>
                                                                  <input type='checkbox' name='removePermission[".$v1['id']."]' id='removePermission[".$v1['id']."]' value='".$v1['id']."'>
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
                                                        <th colspan='2'><label>Add Access</label></th>
                                                    </tr>";
                                                        foreach ($permissionData as $v1) {
                                                          if(!isset($userPermission[$v1['id']])){
                                                            echo "
                                                              <tr>
                                                                <td>
                                                                  <div>
                                                                    <input type='checkbox' name='addPermission[".$v1['id']."]' id='addPermission[".$v1['id']."]' value='".$v1['id']."'>
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
    <!-- /#wrapper -->";