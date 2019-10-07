<?php

$customer_id = $_GET['customer_id'];
$type_flag = $customer->get_customer_type_flag($customer_id);

$firstname;
$surname;
$employer;
$customer_type = $customer->get_customer_type_name($type_flag);
$phone;
$email;
$address;
$customer_image;
$customer_status;
$flag;
$flag_label;
$savings_balance;

$current_loan = $payment->get_customer_current_loan($customer_id);
$loan_days_left = $payment->loan_days($customer_id);
$repayment_date = $payment->get_loan_repayment_date($customer_id);

//$current_investment = $payment->get_customer_current_investment($customer_id);
$investment_date = $payment->get_investment_date($customer_id);
$inv_days_left = $payment->investment_days($customer_id);


try
{   
    global $mysqli;
    $stmt = $mysqli->prepare("SELECT * FROM mc_customers WHERE customer_id = $customer_id");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $name = $lastname." ".$firstname;
    
    $phone = $row['phone'];
    $email = $row['email'];
    $address = $row['address'];
    $customer_image = $row['customer_image'];
    $status_flag = $customer->get_customer_status($row['customer_id']);

    $customer_status = $customer->get_customer_status_name($row['customer_status']);
    $flag_label = $row['customer_status'] == 1 ? "Disable" : "Enable";
    $savings_balance = $row['savings_balance'];
 
} catch (Exception $e) { 
  echo $e->errorMessage(); 
}

      echo "<div class='row'>
                <div class='col-lg-12'>
                    <h3 class='page-header'>Customer Profile</h3>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class='row'>
                <div class='col-lg-12'>
                    <div class='panel panel-default'>
                        <div class='panel-heading'>
                            Customer ID: ".$customer_id." (".$customer_type.")
                        </div>
                        <div class='panel-body'>
                            <div class='col-lg-8'>

                                <div class='row'> 

                                    <div class='col-lg-6'>
                                          <a href='#' class='list-group-item' data-toggle='modal' data-target='#editImage'>
                                              <img src='".$customer_image."' height='180px' width='150px' style='margin:auto;'>
                                          </a>
                                    </div>

                                    <div class='col-lg-6'>";
                                        $customer->customer_profile($_GET['customer_id']);
                                      echo"
                                    </div>
                                </div>

                                <div class='row'>";

                                    if ($type_flag == 1) {
                                        echo"
                                        <div class='col-lg-6'>

                                            <table class='table table-striped table-bordered table-hover'>
                                                <thead>
                                                    <tr>
                                                        <th>Savings Balance</th>
                                                    </tr>
                                                    <tr>
                                                        <td>N".number_format($savings_balance,2)."</td>
                                                    </tr>
                                                    ";

                                                    echo"
                                                </thead>
                                            </table>
                                        </div>

                                        <div class='col-lg-6'>

                                            <table class='table table-striped table-bordered table-hover'>
                                                <thead>
                                                    <tr>
                                                        <th>Current Loan</th>
                                                    </tr>
                                                    <tr>
                                                        <td>N".number_format($current_loan,2)." due in ".$loan_days_left."</td>
                                                    </tr>";

                                                    echo"
                                                </thead>
                                            </table>
                                        </div>";
                                    }

                                    if ($type_flag == 2) {
                                        echo "
                                        <div class='col-lg-6'>
                                            <table class='table table-striped table-bordered table-hover'>
                                                <thead>
                                                    <tr>
                                                        <th>Initial Investment</th><th>Current Value</th>
                                                    </tr>
                                                </thead>";
                                            
                                            $payment->get_customer_current_investment($customer_id);

                                            echo "
                                            </table>
                                        </div>";
                                    }
                                echo"
                                </div>

                            </div>
                            <div class='row'>
                                <div class='col-lg-4'>
                                    <div class='list-group'>";
                                        if ($type_flag == 1) {
                                            echo"
                                              <p class='list-group-item'>
                                                  <a href='index.php?page=2.2&customer_id=".$customer_id."'>View Records</a>
                                              </p>
                                              <p class='list-group-item'>
                                                  <a href='#' data-toggle='modal' data-target='#editProfile'>Edit Profile</a>
                                              </p>
                                              <p class='list-group-item'>
                                                  <a href='#' data-toggle='modal' data-target='#payContribution'>Make Deposit</a>
                                              </p>
                                              <p class='list-group-item'>
                                                 <a href='#' data-toggle='modal' data-target='#withdrawSavings'>Withdraw Funds</a>
                                              </p>
                                              <p class='list-group-item'>
                                                  <a href='#' data-toggle='modal' data-target='#refundLoan'>Refund Loan</a>
                                              </p>
                                              <p class='list-group-item'>
                                                  <a href='#' data-toggle='modal' data-target='#disburseLoan'>Disburse Loan</a>
                                              </p>";
                                        }

                                        if ($type_flag == 2) {
                                            echo "
                                                <p class='list-group-item'>
                                                    <a href='index.php?page=2.2&customer_id=".$customer_id."'>View Records</a>
                                                </p>
                                                <p class='list-group-item'>
                                                    <a href='#' data-toggle='modal' data-target='#editProfile'>Edit Profile</a>
                                                </p>

                                                <p class='list-group-item'>
                                                    <a href='#' data-toggle='modal' data-target='#makeInvestment'>Make Investment</a>
                                                </p>
                                                
                                                <p class='list-group-item'>
                                                    <a href='index.php?page=2.2&customer_id=".$customer_id."&record_type=4&record_title=Investments'>Liquidate</a>
                                                </p>";
                                        }
                                        echo"
                                      </div>

                                </div> 
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
    <!-- /#wrapper -->

    <!-- Modal Edit Profile -->
    <div class='modal fade' id='editProfile' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                    <h4 class='modal-title' id='myModalLabel'>Update Customer, ID : ".$customer_id."</h4>
                </div>
                <form role='form' method='post' action='script_customeractions.php'>
                <input name='customer_id' value='".$customer_id."' hidden>

                <div class='modal-body'>
                    <div class='row'>
                        <div class='col-lg-6 col-lg-offset-3'>
                            <div class='form-group'>
                                <label>Firstname</label>
                                <input class='form-control' name='firstname' value='".$firstname."'>
                            </div>
                            <div class='form-group'>
                                <label>Surname</label>
                                <input class='form-control' name='lastname' value='".$lastname."'>
                            </div>
                            <div class='form-group'>
                                <label>Phone</label>
                                <input class='form-control' type='phone' name='phone' value='".$phone."'>
                            </div>
                            <div class='form-group'>
                                <label>Email</label>
                                <input class='form-control' type='email' name='email' value='".$email."'>
                            </div>
                            <div class='form-group'>
                                <label>Address</label>
                                <input class='form-control' type='text' name='address' value='".$address."'>
                            </div>
                            <div class='form-group'>
                                <label>Type</label>
                                <select name='type_flag' required class='form-control'>
                                    <option value='1'>Saver</option>
                                    <option value='2'>Investor</option>
                                </select>
                            </div>                
                        </div>
                    </div>
                </div>
                <div class='modal-footer'>
                    <a href='script_customeractions.php?customer_id=".$customer_id."&deleteCustomer=True' class='btn btn-danger'>Delete</a>
                    <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                    <button type='submit' name='updateCustomer' class='btn btn-success'>Update</button>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Modal Edit Image -->
    <div class='modal fade' id='editImage' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                    <h4 class='modal-title' id='myModalLabel'>Update Customer, ID : ".$customer_id."</h4>
                </div>
                <form role='form' method='post' action='script_customeractions.php' enctype='multipart/form-data'>
                <input name='customer_id' value='".$customer_id."' hidden>

                <div class='modal-body'>
                    <div class='row'>
                        <div class='col-lg-6'>
                            <div class='panel panel-default'>
                                <div class='panel-heading'>
                                    <i class='fa fa-image'></i> Passport
                                </div>
                                <div class='panel-body'>
                                    <div class='list-group'>
                                      <a href='#' class='list-group-item'>
                                          <img src='".$customer_image."' height='180px' width='150px' style='margin:auto;'>
                                      </a>
                                  </div>
                                </div>
                                <!-- /.panel-body -->
                            </div>
                        </div>

                        <div class='col-lg-6'>
                            <div class='form-group'>
                                <label>Image</label>
                                <div id='audio-preview'>No file selected</div><br />
                                <input type='file' name='user_image' id='audio-upload' accept='image/*'/>
                            </div>               
                        </div>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                    <button type='submit' name='updateImage' class='btn btn-success'>Update</button>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Modal Refund Loan -->
    <div class='modal fade' id='refundLoan' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <form role='form' method='post' action='script_paymentactions.php'>
                    <input name='customer_id' value='".$customer_id."' hidden>
                    <input name='refund_amount' value='".$current_loan."' hidden>
                    <div class='modal-header'>
                        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                        <h4 class='modal-title' id='myModalLabel'>Refund Loan</h4>
                    </div>
                    <div class='modal-body'>
                        <div class='row'>
                            <div class='col-lg-8 col-lg-offset-2'>
                                <div class='form-group'>
                                    <label>Customer</label>
                                    <p class='form-control-static'>".$name."</p>
                                </div>
                                <div class='form-group'>
                                    <label>Amount</label>
                                    <p class='form-control-static'>N".number_format($current_loan,2)."</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                        <button type='submit' name='refundLoan' class='btn btn-primary'>Pay Now</button>
                    </div>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Modal Disburse Loan-->
    <div class='modal fade' id='disburseLoan' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <form role='form' method='post' action='script_paymentactions.php'>
                    <input name='customer_id' value='".$customer_id."' hidden>
                    <div class='modal-header'>
                        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                        <h4 class='modal-title' id='myModalLabel'>Disburse Loan</h4>
                    </div>
                    <div class='modal-body'>
                        <div class='row'>
                            <div class='col-lg-8 col-lg-offset-2'>
                                <div class='form-group'>
                                    <label>Customer</label>
                                    <p class='form-control-static'>".$name."</p>
                                </div>
                                <div class='form-group'>
                                    <label>Amount</label>
                                    <input class='form-control' type='number' min='0' name='disburse_amount'>
                                </div>

                                <div class='form-group'>
                                    <label>Interest Rate</label>
                                    <select name='interest_rate' required class='form-control'>
                                        <option value='5'>5%</option>
                                        <option value='10'>10%</option>
                                        <option value='15'>15%</option>
                                        <option value='20'>20%</option>
                                        <option value='25'>25%</option>
                                        <option value='30'>30%</option>
                                        <option value='35'>35%</option>
                                        <option value='40'>40%</option>
                                        <option value='45'>45%</option>
                                        <option value='50'>50%</option>
                                        <option value='55'>55%</option>
                                        <option value='60'>60%</option>
                                        <option value='65'>65%</option>
                                        <option value='70'>70%</option>
                                        <option value='75'>75%</option>
                                        <option value='80'>80%</option>
                                        <option value='85'>85%</option>
                                        <option value='90'>90%</option>
                                        <option value='100'>100%</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                        <button type='submit' name='disburseLoan' class='btn btn-primary'>Disburse Now</button>
                    </div>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

     <!-- Modal Pay Contribution-->
    <div class='modal fade' id='payContribution' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <form role='form' method='post' action='script_paymentactions.php'>
                    <input name='customer_id' value='".$customer_id."' hidden>
                    <div class='modal-header'>
                        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                        <h4 class='modal-title' id='myModalLabel'>Pay Contribution</h4>
                    </div>
                    <div class='modal-body'>
                        <div class='row'>
                            <div class='col-lg-8 col-lg-offset-2'>
                                <div class='form-group'>
                                    <label>Customer</label>
                                    <p class='form-control-static'>".$name."</p>
                                </div>
                                <div class='form-group'>
                                    <label>Amount</label>
                                    <input class='form-control' type='number' min='0' name='dues_amount'>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                        <button type='submit' name='payContribution' class='btn btn-primary'>Pay Now</button>
                    </div>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Modal Withdraw Savings -->
    <div class='modal fade' id='withdrawSavings' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <form role='form' method='post' action='script_paymentactions.php'>
                    <input name='customer_id' value='".$customer_id."' hidden>
                    <div class='modal-header'>
                        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                        <h4 class='modal-title' id='myModalLabel'>Withdraw Savings</h4>
                    </div>
                    <div class='modal-body'>
                        <div class='row'>
                            <div class='col-lg-8 col-lg-offset-2'>
                                <div class='form-group'>
                                    <label>Customer</label>
                                    <p class='form-control-static'>".$name."</p>
                                </div>
                                <div class='form-group'>
                                    <label>Amount to Withdraw</label>
                                    <input class='form-control' type='number' name='withdrawal_amount' value='".$savings_balance."' min='0' max='".$savings_balance."'>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                        <button type='submit' name='withdrawSavings' class='btn btn-primary'>Withdraw Now</button>
                    </div>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Modal Make Investment-->
    <div class='modal fade' id='makeInvestment' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <form role='form' method='post' action='script_paymentactions.php'>
                    <input name='customer_id' value='".$customer_id."' hidden>
                    <div class='modal-header'>
                        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                        <h4 class='modal-title' id='myModalLabel'>Make Investment</h4>
                    </div>
                    <div class='modal-body'>
                        <div class='row'>
                            <div class='col-lg-8 col-lg-offset-2'>
                                <div class='form-group'>
                                    <label>Customer</label>
                                    <p class='form-control-static'>".$name."</p>
                                </div>
                                <div class='form-group'>
                                    <label>Amount</label>
                                    <input class='form-control' type='number' min='0' name='investment_amount'>
                                </div>

                                <div class='form-group'>
                                    <label>Interest Rate</label>
                                    <select name='interest_rate' required class='form-control'>
                                        <option value='5'>5%</option>
                                        <option value='10'>10%</option>
                                        <option value='15'>15%</option>
                                        <option value='20'>20%</option>
                                        <option value='25'>25%</option>
                                        <option value='30'>30%</option>
                                        <option value='35'>35%</option>
                                        <option value='40'>40%</option>
                                        <option value='45'>45%</option>
                                        <option value='50'>50%</option>
                                        <option value='55'>55%</option>
                                        <option value='60'>60%</option>
                                        <option value='65'>65%</option>
                                        <option value='70'>70%</option>
                                        <option value='75'>75%</option>
                                        <option value='80'>80%</option>
                                        <option value='85'>85%</option>
                                        <option value='90'>90%</option>
                                        <option value='100'>100%</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                        <button type='submit' name='makeInvestment' class='btn btn-primary'>Invest Now</button>
                    </div>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

";

