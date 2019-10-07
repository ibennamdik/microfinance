<?php
    require_once("models/dbConnect.php");

    $customer_id;
    $type_flag;

    if(!isset($_SESSION['user']))
    {
        redirect("login.php"); 
    }
    else
    {
        $customer_id = $_SESSION['user'];
        $type_flag = $customer->get_customer_type_flag($customer_id);
    }

    $record_type;
    $record_title;

    if (isset($_GET['record_type'])) {
      $record_type = $_GET['record_type'];
    }
    else{
      $record_type = 3;
    }

    if (isset($_GET['record_title'])) {
      $record_title = $_GET['record_title'];
    }
    else{
      $record_title = "Receipts";
    }

    $page = 2;

    echo"
    <!DOCTYPE html>
    <html lang='en'>

    <head>

    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='description' content=''>
    <meta name='author' content=''>

    <title>SAVINGS CLUB - PORTAL</title>

    <!-- Bootstrap Core CSS -->
    <link href='vendor/bootstrap/css/bootstrap.min.css' rel='stylesheet'>

    <!-- MetisMenu CSS -->
    <link href='vendor/metisMenu/metisMenu.min.css' rel='stylesheet'>

    <!-- Custom CSS -->
    <link href='dist/css/sb-admin-2.css' rel='stylesheet'>
    <link href='dist/css/custom.css' rel='stylesheet'>

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
            <div class='row'>
                <div class='col-lg-8 col-lg-offset-2'>   
                    <div class='row'>
                        <div class='col-lg-12'>
                            <h1 class='page-header'>Dashboard</h1>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>

                    <div class='row'>
                        ";
                        if (isset($_GET['errors']) || isset($_GET['successes'])){
                          $errors = $_GET['errors'];
                          $successes = $_GET['successes'];
                          resultBlock($errors,$successes);
                        }
                    echo"
                    </div>

                    <div class='row'>
                        <div class='col-lg-12' style='text-align: right; padding-bottom: 10px;'>
                            <a class='btn btn-default btn-sm' href='logout.php?logout=true'>Logout</a>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
                    
                    <!-- /.row -->
                    <div class='row'>";
                        $customer->customer_profile($customer_id);
                    echo"
                    </div>
                    <!-- /.row -->
                
                    <div class='row'>
                        <div class='col-lg-12' style='text-align: left; padding-bottom: 10px;'>";

                            if ($type_flag == 1) {
                              echo"
                                <a class='btn btn-success btn-sm' href='index.php?page=2&customer_id=".$customer_id."&record_type=1&record_title=Savings'>Savings</a>
                                <a class='btn btn-primary btn-sm' href='index.php?page=2&customer_id=".$customer_id."&record_type=2&record_title=Loans'>Loans</a>
                                ";
                            }

                            if ($type_flag == 2) {
                              echo "<a class='btn btn-info btn-sm' href='index.php?page=2&customer_id=".$customer_id."&record_type=4&record_title=Investments'>Investments</a>";
                            }
                              echo"
                                
                            <a class='btn btn-warning btn-sm' href='index.php?page=2&customer_id=".$customer_id."&record_type=3&record_title=Receipts'>Receipts</a>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>
                    

                    <div class='row'>
                      <div class='col-lg-12'>
                          <div class='panel panel-default'>
                              <div class='panel-heading'>
                                  ".$record_title."
                              </div>
                              <!-- /.panel-heading -->
                              <div class='panel-body'>";
                              if ($record_type == 1) {
                                  $table_title = "Savings";
                                  $report->single_customer_savings($customer_id);
                              }
                              else 
                                if ($record_type == 2) {
                                  $table_title = "Loans";
                                  $report->single_customer_loans($customer_id);
                              }
                              else 
                                if ($record_type == 3) {
                                  $table_title = "Receipts";
                                  $report->single_customer_receipts($customer_id);
                              }
                              else 
                                if ($record_type == 4) {
                                  $table_title = "Investments";
                                  $report->single_customer_investments($customer_id);
                              }
                                  
                              echo"
                              </div>
                              <!-- /.panel-body -->
                          </div>
                          <!-- /.panel -->
                      </div>
                      <!-- /.col-lg-12 -->
                    </div>
                </div>
            </div>
           
            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Modal Disburse Loan-->
        <div class='modal fade' id='changePassword' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
            <div class='modal-dialog'>
                <div class='modal-content'>
                    <form role='form' method='post' action='changepassword.php'>
                        <input name='customer_id' value='".$customer_id."' hidden>
                        <div class='modal-header'>
                            <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                            <h4 class='modal-title' id='myModalLabel'>Change Password</h4>
                        </div>
                        <div class='modal-body'>
                            <div class='row'>
                                <div class='col-lg-8 col-lg-offset-2'>
                                    <div class='form-group'>
                                        <label>Customer</label>
                                        <p class='form-control-static'>".$customer->get_customer_name($customer_id)."</p>
                                    </div>
                                    <div class='form-group'>
                                        <label>Old Password</label>
                                        <input class='form-control' type='password' name='password' required>
                                    </div>
                                    <div class='form-group'>
                                        <label>New Password</label>
                                        <input class='form-control' type='password' name='passwordc' required>
                                    </div>
                                    <div class='form-group'>
                                        <label>Repeat Password</label>
                                        <input class='form-control' type='password' name='passwordcheck' required>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                            <button type='submit' name='changePassword' class='btn btn-primary'>Disburse Now</button>
                        </div>
                    </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!-- jQuery -->
    <script src='vendor/jquery/jquery.min.js'></script>

    <!-- Bootstrap Core JavaScript -->
    <script src='vendor/bootstrap/js/bootstrap.min.js'></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src='vendor/metisMenu/metisMenu.min.js'></script>

    <!-- Morris Charts JavaScript -->
    <script src='vendor/raphael/raphael.min.js'></script>
    <script src='vendor/morrisjs/morris.min.js'></script>
    <script src='data/morris-data.js'></script>

    <!-- DataTables JavaScript -->
    <script src='vendor/datatables/js/jquery.dataTables.min.js'></script>
    <script src='vendor/datatables-plugins/dataTables.bootstrap.min.js'></script>
    <script src='vendor/datatables-responsive/dataTables.responsive.js'></script>

    <!-- Custom Theme JavaScript -->
    <script src='dist/js/sb-admin-2.js'></script>

    <!-- Flot Charts JavaScript -->
    <script src='vendor/flot/excanvas.min.js'></script>
    <script src='vendor/flot/jquery.flot.js'></script>
    <script src='vendor/flot/jquery.flot.pie.js'></script>
    <script src='vendor/flot/jquery.flot.resize.js'></script>
    <script src='vendor/flot/jquery.flot.time.js'></script>
    <script src='vendor/flot-tooltip/jquery.flot.tooltip.min.js'></script>
    <script src='data/flot-data.js'></script>

    <!-- Page-Level Demo Scripts - Notifications - Use for reference -->
    <script>
    // tooltip demo
    $('.tooltip-demo').tooltip({
        selector: '[data-toggle=tooltip]',
        container: 'body'
    })
    // popover demo
    $('[data-toggle=popover]')
        .popover()
    </script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
    </script>

    </body>

    </html>";
    ?>
