<?php

$record_type;
$record_title;

if (isset($_GET['record_type'])) {
  $record_type = $_GET['record_type'];
}
else{
  $record_type = 1;
}

if (isset($_GET['record_title'])) {
  $record_title = $_GET['record_title'];
}
else{
  $record_title = "Savings";
}

$page = 2.1;

echo " 
            <div class='row'>
                <div class='col-lg-12'>
                    <h3 class='page-header'>Reports</h3>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class='row'>

                <ul class='nav nav-tabs'>
                    <li><a href='index.php?page=3&record_type=1&record_title=Savings'>Savings</a></li>
                    <li><a href='index.php?page=3&record_type=2&record_title=Loans'>Loans</a></li>
                    <li><a href='index.php?page=3&record_type=3&record_title=Receipts'>Receipts</a></li>
                    <li><a href='index.php?page=3&record_type=4&record_title=Staff Returns'>Staff Returns</a></li>
                    <li><a href='index.php?page=3&record_type=6&record_title=Investments'>Investments</a></li>
                    <li><a href='index.php?page=3&record_type=5&record_title=Summary'>Summary</a></li>
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
                              $table_title = "All Savings";
                              $report->all_customer_savings();
                          }
                          else 
                            if ($record_type == 2) {
                              $table_title = "All Loans";
                              $report->all_customer_loans();
                          }
                          else 
                            if ($record_type == 3) {
                              $table_title = "All Receipts";
                              $report->all_customer_receipts();
                          }
                          else 
                            if ($record_type == 4) {
                              $table_title = "Staff Returns";
                              $report->all_staff_returns();
                          }

                          else 
                            if ($record_type == 6) {
                              $table_title = "Summary";
                              $report->all_investors();
                          }

                          else 
                            if ($record_type == 5) {
                              $table_title = "Investments";
                              $report->all_investors();
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