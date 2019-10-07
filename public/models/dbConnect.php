<?php
session_start();

    $DB_HOST = 'localhost';
    $DB_USER = 'root';
    $DB_PASS = "12345678";
    $DB_NAME = 'namlcomn_db';
    GLOBAL $DB_con;

    try {

    $DB_con = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME}",$DB_USER,$DB_PASS);
    $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $ex){

    echo $ex->getMessage();

    }
    include_once ("class.customer.php");
    include_once ("class.report.php");
    include_once ("class.payment.php");
    include_once("funcs.php");

    $customer = new CUSTOMER($DB_con);
    $payment = new PAYMENT($DB_con, $customer);
    $report = new REPORT($DB_con, $customer, $payment);
?>