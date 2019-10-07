<?php

if (!securePage($_SERVER['PHP_SELF'])){die();}

//Forms posted
if(!empty($_POST))
{
  //Delete permission levels
  if(!empty($_POST['delete'])){
    $deletions = $_POST['delete'];
    if ($deletion_count = deletePermission($deletions)){
    $successes[] = lang("PERMISSION_DELETIONS_SUCCESSFUL", array($deletion_count));
    }
  }
  
  //Create new permission level
  if(!empty($_POST['newPermission'])) {
    $permission = trim($_POST['newPermission']);
    
    //Validate request
    if (permissionNameExists($permission)){
      $errors[] = lang("PERMISSION_NAME_IN_USE", array($permission));
    }
    elseif (minMaxRange(1, 50, $permission)){
      $errors[] = lang("PERMISSION_CHAR_LIMIT", array(1, 50));  
    }
    else{
      if (createPermission($permission)) {
      $successes[] = lang("PERMISSION_CREATION_SUCCESSFUL", array($permission));
    }
      else {
        $errors[] = lang("SQL_ERROR");
      }
    }
  }
}

$permissionData = fetchAllPermissions(); //Retrieve list of all permission levels

// $breadcrumb_array =  array('Page Management' => 'index.php?page=1.2', 'Settings' => '#');
// $page_title = "Staff Permissions";
// title_block($page_title, $breadcrumb_array);
resultBlock($errors,$successes);


echo "
ad>

<body>

    <div id='wrapper'>

       

        <div id='page-wrapper'>
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
                                    <form role='form'>
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
                                            echo "
                                            <tr>
                                                <td colspan='2'>
                                                    <div class='form-group'>
                                                        <label>Permission Name</label>
                                                        <input class='form-control' name='newPermission' placeholder='Enter text'>
                                                    </div>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                        <button type='submit' class='btn btn-default'>Submit</button>
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
