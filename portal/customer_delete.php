<?php

$customer_id = $_GET['customer_id'];
$name = $customer->get_customer_name($customer_id);


echo "      <div class='row'>
                <div class='col-lg-12'>
                    <h3 class='page-header'>&nbsp</h3>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class='row'>

                <div class='row'>
                  <div class='col-lg-6 col-lg-offset-3'>
                      <div class='panel panel-default'>
                          <!-- /.panel-heading -->
                          <div class='panel-body'>
                            <h2 style='text-align:center;'>ARE YOU SURE YOU WANT TO DELETE THIS CUSTOMER RECORDS?</h2>
                            <h5 style='text-align:center;'>You will loose all transaction records for this customer.</h2>
                            
                            <div style='text-align:center;'>
                              <a href='script_customeractions.php?customer_id=".$customer_id."&ConfirmCustomerDelete=True' class='btn btn-danger'>Delete</a>
                              <a href='index.php?page=2&customer_id=".$customer_id."' class='btn btn-success'>Cancel</a>
                            <div>

                          </div>
                          <!-- /.panel-body -->
                      </div>
                      <!-- /.panel -->
                  </div>
                  <!-- /.col-lg-12 -->
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->";