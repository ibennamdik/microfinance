    <?php
   
    echo"
            <div class='row'>
                <div class='col-lg-12'>
                    <h1 class='page-header'>Dashboard</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class='row'>
                <div class='col-lg-4 col-md-6'>
                <h4 class='page-header'>Staff Profile</h4>
                    <div class='panel panel'>
                        <div class='panel-heading'>
                            <div class='row'>";
                                $report->staff_profile($_SESSION['id']); 
                            echo"
                            </div>
                        </div>
                    </div>
                </div>
                <div class='col-lg-8 col-md-6'>
                <h4 class='page-header'>Active Loans</h4>
                    <div class='panel panel'>
                        <div class='panel-heading'>
                            <div class='row'>";
                                $report->all_customer_loans_by_status(1);
                            echo"
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>";