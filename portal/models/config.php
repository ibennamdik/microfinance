<?php
session_start();
//Database Information
$db_host = "localhost"; //Host address (most likely localhost)
$db_name = "namlcomn_db"; //Name of Database
$db_user = "root"; //Name of database user
$db_pass = "12345678"; //Password for database user
$db_table_prefix = "uc_";

GLOBAL $errors;
GLOBAL $successes;
GLOBAL $mysqli;

try
{
	/* Create a new mysqli object with database connection parameters */
	$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
	
	if(mysqli_connect_errno()) {
		//echo "Oops! Connection Failed, Check your Network connection and try refreshing the page or <a href='../portal/login.php'>Click here</a>";
		echo "Connection Failed: ".mysqli_connect_errno();
		exit();
	}
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

include_once("class.mail.php");
include_once("funcs.php");

include_once ("class.staff.php");
include_once ("class.customer.php");
include_once ("class.report.php");
include_once ("class.payment.php");

$staff = new STAFF();
$customer = new CUSTOMER($staff);
$payment = new PAYMENT($customer, $staff);
$report = new REPORT($customer, $payment, $staff);

?>
