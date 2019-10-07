<?php

if (!securePage($_SERVER['PHP_SELF'])){die();}

$pages = getPageFiles(); //Retrieve list of pages in root usercake folder
$dbpages = fetchAllPages(); //Retrieve list of pages in pages table
$creations = array();
$deletions = array();

//Check if any pages exist which are not in DB
foreach ($pages as $page){
  if(!isset($dbpages[$page])){
    $creations[] = $page; 
  }
}

//Enter new pages in DB if found
if (count($creations) > 0) {
  createPages($creations) ;
}

if (count($dbpages) > 0){
  //Check if DB contains pages that don't exist
  foreach ($dbpages as $page){
    if(!isset($pages[$page['page']])){
      $deletions[] = $page['id']; 
    }
  }
}

//Delete pages from DB if not found
if (count($deletions) > 0) {
  deletePages($deletions);
}

//Update DB pages
$dbpages = fetchAllPages();

// $breadcrumb_array =  array('Page Management' => 'index.php?page=1.2', 'Settings' => '#');
// $page_title = "Staff Access Management";
// title_block($page_title, $breadcrumb_array);
resultBlock($errors,$successes);

echo "  <div id='page-wrapper'>
            <div class='row'>
                <div class='col-lg-12'>
                    <h3 class='page-header'>Admin Page management</h3>
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
                                    
                                        <div class='table-responsive'>
                                            <table class='table'>
                                                <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Page</th>
                                                        <th>Access</th>
                                                    </tr>
                                                </thead>
                                                <tbody>";

                                                  foreach ($dbpages as $page){
                                                      echo "  
                                                        <tr>
                                                          <td>".$page['id']."</td>
                                                          <td><a href ='index.php?page=1.1&id=".$page['id']."'>".$page['page']."</a></td>
                                                          <td>";

                                                            if($page['private'] == 0){
                                                              echo "Public ";
                                                            }
                                                            else {
                                                              echo "Private"; 
                                                            }

                                                            echo "
                                                          </td>
                                                        </tr>";
                                                    }
                                                    echo "
                                                   
                                                </tbody>
                                            </table>
                                        </div>
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