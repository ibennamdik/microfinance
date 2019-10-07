<?php

if (!securePage($_SERVER['PHP_SELF'])){die();}
$successes="";
$errors="";
//Forms posted
if(!empty($_POST))
{
  //Delete permission levels
  if(!empty($_POST['delete'])){
    $deletions = $_POST['delete'];
    if ($deletion_count = deletePermission($deletions)){
        $successes .="Successfully Deleted<br>";
    }
  }
  
  //Create new permission level
  if(!empty($_POST['newPermission'])) {
    $permission = trim($_POST['newPermission']);
    
    //Validate request
    if (permissionNameExists($permission)){
      $errors .= "Permission in Use<br>";
    }
    elseif (minMaxRange(1, 50, $permission)){
      $errors .= "Permission must be between 1 and 50 characters in length<br>"; 
    }
    else{
      if (createPermission($permission)) {
      $successes .="Successfully Created<br>";
    }
      else {
        $errors .= "Fatal Data error in SQL, contact developer or try again<br>"; 
      }
    }
  }
}

$permissionData = fetchAllPermissions(); //Retrieve list of all permission levels

echo "
            <div class='row'>
                <div class='col-lg-12'>
                    <h3 class='page-header'>Staff Permissions</h3>
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
                                <div class='col-lg-6 col-lg-offset-3'>
                                    <form role='form' action='#' method='post'>
                                        <div class='table-responsive'>
                                            <table class='table'>
                                                <thead>
                                                    <tr>
                                                        <th>Delete</th>
                                                        <th>Permission Name</th>
                                                    </tr>
                                                </thead>
                                                <tbody>";
                                                    foreach ($permissionData as $v1) {
                                                      echo "
                                                      <tr>
                                                        <td>
                                                          <div>
                                                            <input type='checkbox' name='delete[".$v1['id']."]' id='delete[".$v1['id']."]' value='".$v1['id']."'>
                                                          </div>
                                                        </td>
                                                        <td><a href='index.php?page=1.3&id=".$v1['id']."'>".$v1['name']."</a></td>
                                                      </tr>
                                                      ";
                                                    }

                                                    if ($staff->checkPermission(array(2), $_SESSION['id'])){
                                                        //only admin can see this
                                                        echo "
                                                        <tr>
                                                            <td colspan='2'>
                                                                <div class='form-group'>
                                                                    <label>Permission Name</label>
                                                                    <input class='form-control' name='newPermission' placeholder='Enter text'>
                                                                </div>
                                                            </td>
                                                        </tr>";
                                                    }

                                                    echo "
                                                </tbody>
                                            </table>
                                        </div>";
                                        if ($staff->checkPermission(array(2), $_SESSION['id'])){
                                            //only admin can see this
                                            echo "
                                            <button type='submit' class='btn btn-default'>Submit</button>";
                                        }
                                        echo"
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
