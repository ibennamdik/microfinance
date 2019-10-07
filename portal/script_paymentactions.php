<?php
require_once("models/config.php");

$error_count = 0;
$successes = "";
$errors = "";


if(isset($_POST['payContribution']))
{
  $customer_id = $_POST["customer_id"];
  $dues_amount = $_POST["dues_amount"];

  if ($payment->pay_contribution($customer_id, $dues_amount)) {
    $successes .= "Payment Successful";
  }
  else{
    $errors .= "Payment Failed, Please try again";
  }
  $url = "index.php?page=2.1&customer_id=".$customer_id."&successes=".$successes."&errors=".$errors;
  redirect($url);  
}


if(isset($_POST['disburseLoan']))
{
  $customer_id = $_POST["customer_id"];
  $interest_rate = $_POST["interest_rate"];
  $disburse_amount = $_POST["disburse_amount"];

  // echo $customer_id." ".$interest_rate." ".$disburse_amount;
  if ($payment->disburse_loan($customer_id, $disburse_amount, $interest_rate)) {
    $successes .= "Disbursal Successful";
  }
  else{
    $errors .= "Customer has a running Loan, or Disbursal Failed, Please try again";
  }
  $url = "index.php?page=2.1&customer_id=".$customer_id."&successes=".$successes."&errors=".$errors;
  redirect($url);
}


if(isset($_POST['refundLoan']))
{
  $customer_id = $_POST["customer_id"];
  $refund_amount = $_POST["refund_amount"];

  if ($payment->refund_loan($customer_id, $refund_amount)) {
    $successes .= "Refund Successful";
  }
  else{
    $errors .= "Refund Failed, Please try again";
  }
  $url = "index.php?page=2.1&customer_id=".$customer_id."&successes=".$successes."&errors=".$errors;
  redirect($url);
}

if(isset($_POST['withdrawSavings']))
{
  $customer_id = $_POST["customer_id"];
  $withdrawal_amount = $_POST["withdrawal_amount"];

  if ($payment->withdraw_saving($customer_id, $withdrawal_amount)) {
    $successes .= "Withdrawal Successful";
  }
  else{
    $errors .= "Withdrawal Failed, Please try again";
  }
  $url = "index.php?page=2.1&customer_id=".$customer_id."&successes=".$successes."&errors=".$errors;
  redirect($url);
}

if(isset($_POST['makeInvestment']))
{
  $customer_id = $_POST["customer_id"];
  $interest_rate = $_POST["interest_rate"];
  $investment_amount = $_POST["investment_amount"];

  // echo $customer_id." ".$interest_rate." ".$disburse_amount;
  if ($payment->make_investment($customer_id, $investment_amount, $interest_rate)) {
    $successes .= "Investment Successful";
  }
  else{
    $errors .= "Error while updating records, Please try again";
  }
  $url = "index.php?page=2.1&customer_id=".$customer_id."&successes=".$successes."&errors=".$errors;
  redirect($url);
}

if(isset($_POST['liquidate']))
{
  $customer_id = $_POST["customer_id"];
  $investment_id = $_POST["investment_id"];
  
  //echo $customer_id." ".$refund_amount;
  if ($payment->liquidate_investment($customer_id, $investment_id)) {
    $successes .= "Liquidation Successful";
  }
  else{
    $errors .= "Liquidation Failed, Please try again";
  }
  $url = "index.php?page=2.2&customer_id=".$customer_id."&record_type=4&record_title=Investments&successes=".$successes."&errors=".$errors;

  redirect($url);
}
?>