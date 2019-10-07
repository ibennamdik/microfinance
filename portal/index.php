<?php
require_once("models/config.php");

if(isset($_SESSION["userCakeUserNaml"])) 
{
    $payment->investment_update();
    require_once("common/header.php");

    if(isset($_GET['page']))
    {   
        if($_GET['page']==1){
            include "activate-account.php";
        }
        else if($_GET['page']==1.1){
            include "admin_page.php";
        }
        else if($_GET['page']==1.2){
            include "admin_pages.php";
        }
        else if($_GET['page']==1.3){
            include "admin_permission.php";
        }
        else if($_GET['page']==1.4){
            include "admin_permissions.php";
        }
        else if($_GET['page']==1.5){
            include "admin_register.php";
        }
        else if($_GET['page']==1.6){
            include "admin_user.php";
        }
        else if($_GET['page']==1.7){
            include "admin_users.php";
        }
        else if($_GET['page']==1.8){
            include "user_settings.php";
        }
        else if($_GET['page']==1.9){
            include "accounts.php";
        }
        else if($_GET['page']==2){
            include "customers.php";  
        }
        else if($_GET['page']==2.1){
            include "customer.php";  
        }
        else if($_GET['page']==2.2){
            include "customer_records.php";  
        }
        else if($_GET['page']==2.3){
            include "customer_register.php";  
        }
        else if($_GET['page']==2.4){
            include "customer_delete.php";  
        }
        else if($_GET['page']==2.5){
            include "investor.php";  
        }
        else if($_GET['page']==3){
            include "reports.php";  
        }
        else if($_GET['page']==4){
            include "logs.php";  
        }
        else if($_GET['page']==5){
            include "investments.php";  
        }           
    }
    else
    {
        include "dashboard.php";
    }
    
    include_once 'common/footer.php';
    // echo "Logged in";
}
else
{
    redirect("login.php"); die();
    // echo "Logged Out";
}
         
?>
