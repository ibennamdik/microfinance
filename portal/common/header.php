 <?php
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

    <div id='wrapper'>

        <!-- Navigation -->
        <nav class='navbar navbar-default navbar-static-top' role='navigation' style='margin-bottom: 0'>
            <div class='navbar-header'>
                <button type='button' class='navbar-toggle' data-toggle='collapse' data-target='.navbar-collapse'>
                    <span class='sr-only'>Toggle navigation</span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                    <span class='icon-bar'></span>
                </button>
                <a class='navbar-brand' href='index.php'>SAVINGS CLUB PORTAL</a>
            </div>
            <!-- /.navbar-header -->

            <div class='row'>
                <div class='col-lg-12' style='text-align: right; padding-bottom: 2px;'>
                    <a class='btn btn-default btn-sm' href='#'> ".$_SESSION['display_name']."[".$_SESSION['title']."]</a>
                    <a class='btn btn-default btn-sm' href='#'>".date('d M, Y,  h:i a')."</a>
                    <a class='btn btn-default btn-sm' href='login.php?logOut=True'>Logout</a>
                </div>
            </div>

            <div class='navbar-default sidebar' role='navigation'>
                <div class='sidebar-nav navbar-collapse'>";
                    
                          if ($staff->checkPermission(array(1), $_SESSION['id'])){
                            //new member
                            permission1();
                          }
                          else if ($staff->checkPermission(array(2), $_SESSION['id'])){
                            //admin
                            permission2();
                          }
                          else if ($staff->checkPermission(array(3), $_SESSION['id'])){
                            //manager
                            permission3();
                          }
                          else{
                            //others
                            permission4();
                          }
                    echo"
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
        <div id='page-wrapper'>
            
            <div class='row'>
                <div class='col-lg-12' style='margin-top: 5px;'>";
                    
                        if (isset($_GET['errors']) || isset($_GET['successes'])){
                          $errors = $_GET['errors'];
                          $successes = $_GET['successes'];
                          resultBlock($errors,$successes);
                        }
                    echo"
                </div>
            </div>";