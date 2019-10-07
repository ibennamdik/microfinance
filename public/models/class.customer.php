  <?php
  class CUSTOMER
  {
      private $db;
   
      function __construct($DB_con)
      {
        $this->db = $DB_con;
      }

      //--------------------------------------------------------------------------------------------------------------
      public function customer_profile($customer_id)
      {
          try
          {
              $stmt = $this->db->prepare("SELECT * FROM mc_customers WHERE customer_id = $customer_id");
              $stmt->execute();
              $row = $stmt->fetch(PDO::FETCH_ASSOC);
              
              $customer_status = $this->get_customer_status($row['customer_id']);
              $customer_type = $this->get_customer_type_name($row['type_flag']);
              echo"
                


              <div class='col-lg-6'>
                <div class='panel panel-default'>
                    <div class='panel-heading'>
                        <i class='fa fa-user fa-fw'></i> Profile
                    </div>
                    <!-- /.panel-heading -->
                    <div class='panel-body'>
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
                      </div>
                    </div>
                </div>
              </div>
              <div class='col-lg-6'>
                <div class='panel panel-default'>
                    <div class='panel-heading'>
                        <i class='fa fa-bell fa-fw'></i> Information
                    </div>
                    <!-- /.panel-heading -->
                    <div class='panel-body'>
                      <div class='list-group'>
                          <p class='list-group-item'>
                              Customer Status: ".$this->get_customer_type_name($row['type_flag'])."
                          </p>
                          <p class='list-group-item'>
                              Savings : N".number_format($row['savings_balance'],2)."
                          </p>
                          <p class='list-group-item'>
                              Registered: <i>".$row['timestamp_created']."</i>
                          </p>
                          <p class='list-group-item'>
                              &nbsp
                          </p>
                          
                      </div>
                    </div>
                </div>
              </div>

                        ";
                 
              return true;
              
          }
          catch(PDOException $e)
          {
              echo $e->getMessage();
              return false;
          }
      }

      public function customer_savings($customer_id)
      {
          try
          {
            $stmt = $this->db->prepare("SELECT * FROM mc_savings WHERE customer_id = $customer_id");
            $stmt->execute();

            if($stmt->rowCount()>0)
            {
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                  {
                      echo"
                        <tr>
                            <td>".$row['savings_id']."</td>
                            <td>".$row['transaction_id']."</td>
                            <td>".$row['savings_date']."</td>
                            <td>".$row['balance']."</td>
                            <td>".$row['amount']."</td>
                            <td>".$row['timestamp_updated']."</td>
                        </tr>";
                  }
              }
              return true;
          }
          catch(PDOException $e)
          {
              echo $e->getMessage();
              return false;
          }
      }
      
       //Get customers current status name
       public function get_customer_status_name($status_flag)
       {
          try
          {
            $stmt = $this->db->prepare("SELECT status_name FROM mc_customer_status WHERE status_flag = $status_flag");
            $stmt->execute(); 
            $row=$stmt->fetch(PDO::FETCH_ASSOC);
        
            return $row['status_name'];
          }
          catch(PDOException $e)
          {
              echo $e->getMessage();
              return false;
          }
       }

       public function get_customer_status($customer_id)
       {
          try
          {
            $stmt = $this->db->prepare("SELECT customer_status FROM mc_customers WHERE customer_id = $customer_id");
            $stmt->execute(); 
            $row=$stmt->fetch(PDO::FETCH_ASSOC);
        
            $status_flag = $row['customer_status'];

            $stmt = $this->db->prepare("SELECT status_name FROM mc_customer_status WHERE status_flag = $status_flag");
            $stmt->execute(); 
            $row=$stmt->fetch(PDO::FETCH_ASSOC);
        
            return $row['status_name'];
          }
          catch(PDOException $e)
          {
              echo $e->getMessage();
              return false;
          }
       }

       public function get_customer_type_flag($customer_id)
       {
          try
          {
            $stmt = $this->db->prepare("SELECT type_flag FROM mc_customers WHERE customer_id = '$customer_id'");
            $stmt->execute(); 
            $row=$stmt->fetch(PDO::FETCH_ASSOC);
        
            return $row['type_flag'];
          }
          catch(PDOException $e)
          {
              echo $e->getMessage();
              return false;
          }
       }

       public function get_customer_type_name($type_flag)
       {
          try
          {
            $stmt = $this->db->prepare("SELECT type_name FROM mc_customer_types WHERE type_flag = '$type_flag'");
            $stmt->execute(); 
            $row=$stmt->fetch(PDO::FETCH_ASSOC);
        
            return $row['type_name'];
          }
          catch(PDOException $e)
          {
              echo $e->getMessage();
              return false;
          }
       }


      //function fetches one customer name from the database
       public function get_customer_name($customer_id)
       {
          try
          {
       
            $stmt = $this->db->prepare("SELECT * FROM mc_customers WHERE customer_id = '$customer_id'");
            $stmt->execute();
            $row =$stmt->fetch(PDO::FETCH_ASSOC);
            $customer_name = $row['firstname']." ".$row['lastname'];
             
          return $customer_name; 
         }
         catch(PDOException $e)
         {
             echo $e->getMessage();
             return false;
         }          
       }

       //function fetches one customer name from the database
       public function get_customer_email($customer_id)
       {
          try
          {
       
            $stmt = $this->db->prepare("SELECT email FROM mc_customers WHERE customer_id = '$customer_id'");
            $stmt->execute();
            $row =$stmt->fetch(PDO::FETCH_ASSOC);
            $customer_email = $row['email'];
             
          return $customer_email; 
         }
         catch(PDOException $e)
         {
             echo $e->getMessage();
             return false;
         }          
       }

        //function fetches one customer name from the database
       public function get_customer_password($customer_id)
       {
          try
          {
       
            $stmt = $this->db->prepare("SELECT password FROM mc_customers WHERE customer_id = '$customer_id'");
            $stmt->execute();
            $row =$stmt->fetch(PDO::FETCH_ASSOC);
            $password = $row['password'];
             
          return $password; 
         }
         catch(PDOException $e)
         {
             echo $e->getMessage();
             return false;
         }          
       }

       //function fetches one customer name from the database
       public function update_password($customer_id, $password)
       {
          try
          {
       
            $stmt = $this->db->prepare("UPDATE mc_customers SET password = $password WHERE customer_id = '$customer_id'");
            $stmt->execute();
             
            return true; 
         }
         catch(PDOException $e)
         {
             echo $e->getMessage();
             return false;
         }          
       }

       
  }
  ?>