<?php

$customer_id = $_GET['customer_id'];
$investment_id = $_GET['investment_id'];
$investment_status = $_GET['investment_status'];
$current_investment = $payment->get_investment_amount($customer_id);

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
 
}
catch(PDOException $e)
{
  echo $e->getMessage();
}

      echo "<div class='row'>
                <div class='col-lg-12'>
                    <h3 class='page-header'>Investment Details</h3>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class='row'>
                <div class='col-lg-12'>
                    <div class='panel panel-default'>
                        <div class='panel-heading'>
                            Customer ID: ".$customer_id." (".$customer_status.")
                        </div>
                        <div class='panel-body'>
                            <div class='row'> 
                                <div class='col-lg-5'>
                                    <div class='panel panel-default'>
                                        <div class='panel-heading'>
                                            <i class='fa fa-reorder'></i> Bio Data
                                        </div>
                                        <div class='panel-body'>";
                                          $customer->customer_profile($_GET['customer_id']);
                                          echo"
                                        </div>
                                        <!-- /.panel-body -->
                                    </div>
                                </div>
                                <div class='col-lg-7'>
                                    <div class='panel panel-default'>
                                        <div class='panel-heading'>
                                            <i class='fa fa-reorder'></i> Summary
                                        </div>
                                        <div class='panel-body'>";
                                          $payment->customer_investment($customer_id, $investment_id);
                                          echo"
                                        </div>
                                        <!-- /.panel-body -->
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

            <!-- /.row -->
            <div class='row'>
                <div class='col-lg-12'>
                    <table class='table table-striped table-bordered table-hover'>
                        <tbody>
                            <tr>
                                <td style='text-align:center;'>";
                                if ($investment_status == 1) {
                                    echo "<a class='btn btn-danger' href='#' data-toggle='modal' data-target='#liquidate'>Liquidate</a> <a class='btn btn-success' href='index.php?page=2.2&customer_id=".$customer_id."&record_type=4&record_title=Investments'>Back To Investment Records</a></td>";
                                }
                                else if ($investment_status == 2){
                                    echo "<a class='btn btn-success' href='index.php?page=2.2&customer_id=".$customer_id."&record_type=4&record_title=Investments'>Back To Investment Records</a></td>";
                                }
                                echo"
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Modal Liquidate -->
    <div class='modal fade' id='liquidate' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <form role='form' method='post' action='script_paymentactions.php'>
                    <input name='customer_id' value='".$customer_id."' hidden>
                    <input name='investment_id' value='".$investment_id."' hidden>
                    <div class='modal-header'>
                        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                        <h4 class='modal-title' id='myModalLabel'>Liquidate Investment</h4>
                    </div>
                    <div class='modal-body'>
                        <div class='row'>
                            <div class='col-lg-8 col-lg-offset-2'>
                                <div class='form-group'>
                                    <label>Investor</label>
                                    <p class='form-control-static'>".$name."</p>
                                </div>
                                <div class='form-group'>
                                    <label>Investment Value</label>
                                    <p class='form-control-static'>".$current_investment."</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                        <button type='submit' name='liquidate' class='btn btn-primary'>Liquidate Now</button>
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

