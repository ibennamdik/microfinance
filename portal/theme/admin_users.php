<?php

if (!securePage($_SERVER['PHP_SELF'])){die();}

//Forms posted
if(!empty($_POST))
{
  $deletions = $_POST['delete'];
  if ($deletion_count = deleteUsers($deletions)){
    $successes[] = lang("ACCOUNT_DELETIONS_SUCCESSFUL", array($deletion_count));
  }
  else {
    $errors[] = lang("SQL_ERROR");
  }
}

$userData = fetchAllUsers(); //Fetch information for all users

// $breadcrumb_array =  array('Page Management' => 'index.php?page=1.2', 'Settings' => '#');
// $page_title = "Staff List";
// title_block($page_title, $breadcrumb_array);

resultBlock($errors,$successes);
echo "
        <div id='page-wrapper'>
            <div class='row'>
                <div class='col-lg-12'>
                    <h3 class='page-header'>Admin Users</h3>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class='row'>
                <div class='col-lg-12'>
                    <div class='panel panel-default'>
                        <div class='panel-heading'>
                            DataTables Advanced Tables
                        </div>
                        <!-- /.panel-heading -->
                        <div class='panel-body'>
                            <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>User Name</th>
                                        <th>Staff Name</th>
                                        <th>Title</th>
                                        <th>Last Sign In</th>
                                    </tr>
                                </thead>
                                <tbody>";
                                    foreach ($userData as $v1) {
                                      echo "
                                      <tr>
                                        <td style='width: 5%;' scope='row'>".$v1['id']."</td>
                                        <td><a href='index.php?page=1.6&id=".$v1['id']."'>".$v1['user_name']."</a></td>
                                        <td>".$v1['display_name']."</td>
                                        <td>".$v1['title']."</td>
                                        <td>";
                                        //Interprety last login
                                        if ($v1['last_sign_in_stamp'] == '0'){
                                          echo "Never"; 
                                        }
                                        else {
                                          echo date("j M, Y", $v1['last_sign_in_stamp']);
                                        }
                                        echo " 
                                        </td>
                                      </tr>";
                                    }
                                        echo " 
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                            
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