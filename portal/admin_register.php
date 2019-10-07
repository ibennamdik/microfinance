<?php

if (!securePage($_SERVER['PHP_SELF'])){die();}
$successes="";
$errors="";

echo "

            <div class='row'>
                <div class='col-lg-12'>
                    <h3 class='page-header'>Staff Registeration</h3>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class='row'>
                <div class='col-lg-12'>
                    <div class='panel panel-default'>
                        <div class='panel-heading'>
                            User Information
                        </div>
                        <div class='panel-body'>
                            <div class='row'>
                                <form role='form' method='post' action='script_staffactions.php'>
                                    <div class='col-lg-6'>
                                    
                                        <div class='form-group'>
                                            <label>User Name</label>
                                            <input class='form-control' name='username' required placeholder='Enter User Name'>
                                        </div>
                                        <div class='form-group'>
                                            <label>Display Name</label>
                                            <input class='form-control' type='text' name='displayname' required placeholder='Enter Name'>
                                        </div>
                                        <div class='form-group'>
                                            <label>Password</label>
                                            <input class='form-control' type='password' name='password' required>
                                        </div>
                                        <div class='form-group'>
                                            <label>Password Again</label>
                                            <input class='form-control' type='password' name='passwordc' required>
                                        </div>
                                    </div>
                                    <!-- /.col-lg-6 (nested) -->
                                    <div class='col-lg-6'>
                                        <div class='form-group'>
                                            <label>Email</label>
                                            <input class='form-control' type='email' name='email'  placeholder='Enter text'>
                                        </div>
                                        <div>
                                            <label>Security Code</label>
                                            <p class='form-control-static'><img src='models/captcha.php'></p>
                                        </div>
                                        <div class='form-group'>
                                            <label>Enter Security Code</label>
                                            <input class='form-control' type='text' name='captcha' required placeholder='Enter text'>
                                        </div>
                                        <div class='form-group'>
                                            <label></label>
                                        </div>
                                            
                                        <button type='submit' name='newStaff' class='btn btn-primary'>Save</button>                           
                                    </div>
                                </form>
                                <!-- /.col-lg-6 (nested) -->
 
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
    <!-- /#wrapper -->";