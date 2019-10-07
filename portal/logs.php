<?php
echo "
            <div class='row'>
                <div class='col-lg-12'>
                    <h3 class='page-header'>User Logs</h3>
                    <h5 align='right'><a href='#' class='btn btn-primary btn-xs' data-toggle='modal' data-target='#newCustomer'>New Customer</a><h5>
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
                        <div class='panel-body'>
                            <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                                <thead>
                                    <tr>
                                        <th>Time/Date Of Activity</th>
                                        <th>Staff Name</th>
                                        <th>Activity</th>
                                    </tr>
                                </thead>
                                <tbody>";
                                $report->logs();
                                echo"
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                            
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
    <!-- /#wrapper -->";