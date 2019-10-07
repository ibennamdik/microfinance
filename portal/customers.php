<?php
echo "
            <div class='row'>
                <div class='col-lg-12'>
                    <h3 class='page-header'>Customers</h3>
                    <h5 align='right'><a href='index.php?page=2.3' class='btn btn-primary btn-xs'>New Customer</a><h5>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class='row'>
                <div class='col-lg-12'>
                    <div class='panel panel-default'>
                        <div class='panel-heading'>
                            Registered customers
                        </div>
                        <!-- /.panel-heading -->
                        <div class='panel-body'>";
                            $customer->customer_list();
                            echo"
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
";