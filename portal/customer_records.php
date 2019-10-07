<?php

$customer_id;
$record_type;
$record_title;

$customer_id = $_GET['customer_id'];

$type_flag = $customer->get_customer_type_flag($customer_id);

$name = $customer->get_customer_name($customer_id);

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

$page = 2.1;

echo "      <div class='row'>
                <div class='col-lg-12'>
                    <h3 class='page-header'>Customer Records <tag style='font-size:15px;'>(".$name.", ".$customer_id.")</tag></h3>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class='row'>

                <ul class='nav nav-tabs'>";
                if ($type_flag == 1) {
                  echo"
                    <li><a href='index.php?page=2.2&customer_id=".$customer_id."&record_type=1&record_title=Savings'>Savings</a>
                    </li>
                    <li><a href='index.php?page=2.2&customer_id=".$customer_id."&record_type=2&record_title=Loans'>Loans</a>
                    </li>";
                }

                if ($type_flag == 2) {
                  echo "<li><a href='index.php?page=2.2&customer_id=".$customer_id."&record_type=4&record_title=Investments'>Investments</a>
                    </li>";
                }
                  echo"
                    <li><a href='index.php?page=2.2&customer_id=".$customer_id."&record_type=3&record_title=Receipts'>Receipts</a>
                    </li>

                    <li><a href='index.php?page=2.1&customer_id=".$customer_id."'>Profile</a>
                    </li>
                </ul>


                <h4 style='margin-top:30px; margin-bottom:30px;'>".$record_title."</h4>
                <div class='row'>
                  <div class='col-lg-12'>
                      <div class='panel panel-default'>
                          <div class='panel-heading'>
                              &nbsp
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
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->";