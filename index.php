<?php
require_once("header.php");

	if(isset($_GET['page']))
    {   
        if($_GET['page']==1){
            include "agents.php";
        }
        else if($_GET['page']==2){
            include "microcredit.php";
        }
        else if($_GET['page']==4){
            include "businesscenter.php";
        }
        else if($_GET['page']==3){
            include "computerschool.php";
        }
        else if($_GET['page']==5){
            include "contact.php";
        }
        else if($_GET['page']==6){
            include "consulting.php";
        }              
    }
    else
    {
        include "home.php";
    }
	require_once("footer.php");
?>