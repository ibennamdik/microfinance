<?php

if (!securePage($_SERVER['PHP_SELF'])){die();}
$successes="";
$errors="";

echo "

            <div class='row'>
                <div class='col-lg-12'>
                    <h3 class='page-header'>Customer Registeration</h3>
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
                            
                            <form role='form' method='post' action='script_customeractions.php'>
                                <div class='row'>
                                  <div class='col-lg-6 col-lg-offset-3'>
                                      <div class='form-group'>
                                          <label>First Name</label>
                                          <input class='form-control' name='firstname' required placeholder='Abdulahi'>
                                      </div>
                                      <div class='form-group'>
                                          <label>Last Name</label>
                                          <input class='form-control' name='lastname' required placeholder='Samaila Idris'>
                                      </div>
                                      <div class='form-group'>
                                          <label>Phone</label>
                                          <input class='form-control' type='phone' name='phone' required placeholder='08012345678'>
                                      </div>
                                      <div class='form-group'>
                                          <label>Address</label>
                                          <input class='form-control' type='text' name='address' required placeholder='Tafawa Balewa Street'>
                                      </div>
                                      <div class='form-group'>
                                          <label>Email</label>
                                          <input class='form-control' type='email' name='email' required placeholder='ixnoteservices@gmail.com'>
                                      </div>
                                      <div class='form-group'>
                                        <label>Type</label>
                                        <select name='type_flag' required class='form-control'>
                                            <option value='1'>Saver</option>
                                            <option value='2'>Investor</option>
                                        </select>
                                      </div>


                                      <button type='submit' name='newCustomer' class='btn btn-primary'>Save</button>
                                                     
                                  </div>
                              </div>
                            </form>
                            <!-- /.col-lg-6 (nested) -->
 
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