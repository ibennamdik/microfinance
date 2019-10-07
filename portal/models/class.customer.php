  <?php
  class CUSTOMER
  {
      private $staff;
   
      function __construct($staff)
      {
        $this->staff = $staff;
      }

     //--------------------------------------------------------------------------------------------------------------
      public function customer_register($firstname, $lastname, $phone, $email, $address, $type_flag)
      {
          $staff = $_SESSION['staff'];
          //$passwordplain = randomPassword();
          $password = generateHash($phone);
          
           try
           {   
               global $mysqli;
               $mysqli->query("INSERT INTO mc_customers(firstname, lastname, password, phone, email, address, customer_status, type_flag) VALUES('$firstname', '$lastname', '$password', '$phone', '$email', '$address', 1, $type_flag)");

               $this->update_returns_customer();

               $name = $firstname." ".$lastname;
               $this->staff->create_log_entry($staff." Registered a new customer: ".$name, $staff);

               //The message, In case any of our lines are larger than 70 characters, we should use wordwrap()
               $message = "Your NAML login details are \r\n ".$email.", ".$password;
               $message = wordwrap($message, 70, "\r\n");
               mail($email, 'NAML Micro Credit Scheme', $message);
          
               return true; 
           } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }    
      }
      
       //--------------------------------------------------------------------------------------------------------------
       public function customer_update($customer_id, $firstname, $lastname, $phone, $email, $address, $type_flag)
       {
          $staff = $_SESSION['staff'];
          try
          {
              global $mysqli;
              $mysqli->query("UPDATE mc_customers SET firstname = '$firstname', lastname = '$lastname', phone = '$phone', email = '$email', address = '$address', type_flag = '$type_flag' WHERE customer_id = $customer_id");

              $name = $this->get_customer_name($customer_id);
              $this->staff->create_log_entry($staff." Updated ".$name."'s record", $staff);
              
              return true;

          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
       }

       //--------------------------------------------------------------------------------------------------------------
       public function customer_delete($customer_id)
       {
          $staff = $_SESSION['staff'];
          try
          {
              global $mysqli;
              $mysqli->query("DELETE FROM mc_customers WHERE customer_id = $customer_id");

              $name = $this->get_customer_name($customer_id);
              $this->staff->create_log_entry($staff." Deleted ".$name." from records", $staff);
              
              return true;

          }
          catch(PDOException $e)
          {
              echo $e->getMessage();
              return false;
          }
       }

       
       //--------------------------------------------------------------------------------------------------------------
       public function update_image($userpic, $customer_id)
       {
          $staff = $_SESSION['staff'];
          try
          {
              global $mysqli;
              $mysqli->query("UPDATE mc_customers SET customer_image = '$userpic' WHERE customer_id = '$customer_id'");
              
              return true;
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
       }

       //------------------------------------------------------------------------------------------------
       public function update_customer_status($customer_id, $new_status)
       {
            $staff = $_SESSION['staff'];
            try
            {

              global $mysqli;
              $mysqli->query("UPDATE mc_customers SET customer_status = '$new_status' WHERE customer_id = '$customer_id'");
              
              $name = $this->get_customer_name($customer_id);
              $this->staff->create_log_entry($staff." Updated ".$name."'s' Status", $staff);

              return true;
            } 
            catch (Exception $e) 
            { 
                echo $e->errorMessage(); 
            }
       }

      //-------------------------------------------------------------------------------------------------
      public function customer_list()
      {
          try
          {
                echo "
                <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                  <thead>
                      <tr>
                          <th>Id</th>
                          <th>Name</th>
                          <th>Phone</th>
                          <th>Address</th>
                          <th>Email</th>
                          <th>Customer Type</th>
                      </tr>
                  </thead>
                  <tbody>";

              global $mysqli;
              $stmt = $mysqli->query("SELECT * FROM mc_customers");
              while ($row = $stmt->fetch_assoc()) 
              {
                      $customer_status = $this->get_customer_status($row['customer_id']);
                      echo"
                        <tr>
                            <td>".$row['customer_id']."</td>
                            <td><a href='index.php?page=2.1&customer_id=".$row['customer_id']."'>".$row['firstname']." ".$row['lastname']."</a></td>
                            <td>".$row['phone']."</td>
                            <td>".$row['address']."</td>
                            <td>".$row['email']."</td>
                            <td>".$this->get_customer_type_name($row['type_flag'])."</td>
                        </tr>";
                }
                echo"
                  </tbody>
              </table>";
              return true;
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
      }

      //--------------------------------------------------------------------------------------------------------------
      public function customer_profile($customer_id)
      {
          try
          {   
              global $mysqli;
              $stmt = $mysqli->query("SELECT * FROM mc_customers WHERE customer_id = $customer_id");
              while ($row = $stmt->fetch_assoc()) 
              {

                  $customer_status = $this->get_customer_status($row['customer_id']);
                  $customer_type = $this->get_customer_type_name($row['type_flag']);
                  echo"
                    <div class='list-group'>
                      <p class='list-group-item'>
                          ".$row['firstname']." ".$row['lastname']."
                      </p>
                      <p class='list-group-item'>
                          ".$row['phone']."
                      </p>
                      <p class='list-group-item'>
                          ".$row['email']."
                      </p>
                      <p class='list-group-item'>
                          ".$row['address']."</em>
                      </p>
                      <p class='list-group-item'>
                          Registered: <i>".$row['timestamp_created']."</i>
                      </p>
                      
                  </div>";
                }
                 
              return true;
              
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
      }

       //Get customers current status name
       public function get_customer_status_name($status_flag)
       {
          try
          {
            global $mysqli;
            $stmt = $mysqli->query("SELECT status_name FROM mc_customer_status WHERE status_flag = $status_flag");
            $row = $stmt->fetch_assoc(); 
            
            return $row['status_name'];
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
       }

       public function get_customer_status($customer_id)
       {
          try
          {
            global $mysqli;
            $stmt = $mysqli->query("SELECT customer_status FROM mc_customers WHERE customer_id = $customer_id");
            $row = $stmt->fetch_assoc();
            $status_flag = $row['customer_status'];

            $stmt = $mysqli->query("SELECT status_name FROM mc_customer_status WHERE status_flag = $status_flag");
            $row = $stmt->fetch_assoc();
            
            return $row['status_name'];
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
       }

       public function get_customer_type_flag($customer_id)
       {
          try
          {
            global $mysqli;
            $stmt = $mysqli->prepare("SELECT type_flag FROM mc_customers WHERE customer_id = '$customer_id'");
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
        
            return $row['type_flag'];
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
       }

       public function get_customer_type_name($type_flag)
       {
          try
          {
            global $mysqli;
            $stmt = $mysqli->prepare("SELECT type_name FROM mc_customer_types WHERE type_flag = '$type_flag'");
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
        
            return $row['type_name'];
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
       }

       //--------------------------------------------------------------------------------------------------------------
      //function fetches one customer name from the database
       public function get_customer_name($customer_id)
       {
          try
          {
            global $mysqli;
            $stmt = $mysqli->prepare("SELECT * FROM mc_customers WHERE customer_id = '$customer_id'");
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $customer_name = $row['firstname']." ".$row['lastname'];
             
            return $customer_name; 
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }          
       }

       //check and update group status if expected return date has been exceeded
       public function update_customer_status_by_time()
       {  
          try
          {   
              //update to defaulting client
              global $mysqli;
              $mysqli->query("UPDATE mc_customers, mc_loans SET mc_customers.current_status = 4, mc_loans.loan_status = 3 WHERE CURDATE() > mc_loans.repayment_date");
              
              return true;
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
       } 

        public function update_returns_customer()
        {
            $id = $_SESSION['id'];
            try
            {
                global $mysqli;
                $mysqli->query("UPDATE uc_users SET income_customers = income_customers + 1 WHERE id = $id");
                
                return true;
            } 
            catch (Exception $e) 
            { 
                echo $e->errorMessage(); 
            }        
        } 

        public function update_returns_profit($profit)
        {
            $id = $_SESSION['id'];
            try
            {
                global $mysqli;
                $mysqli->query("UPDATE uc_users SET income_profit =  income_profit + $profit WHERE id = $id");
                
                return true;
            } 
            catch (Exception $e) 
            { 
                echo $e->errorMessage(); 
            }        
        }

        public function update_returns_contributions($contributions)
        {
            $id = $_SESSION['id'];
            try
            {
                global $mysqli;
                $mysqli->query("UPDATE uc_users SET income_contributions =  income_contributions + $contributions WHERE id = $id");
                
                return true;
            } 
            catch (Exception $e) 
            { 
                echo $e->errorMessage(); 
            }        
        } 
  }
  ?>