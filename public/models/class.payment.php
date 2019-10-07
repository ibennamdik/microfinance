  <?php
  class PAYMENT
  {
      private $db;
      private $customer;

      function __construct($DB_con, $customer)
      {
        $this->db = $DB_con;
        $this->customer = $customer;
      }

      //when is customer expected to return their loans?
      public function get_repayment_date($disburse_date)
      {  
          $expected_return_date = strtotime('+ 30 days', strtotime($disburse_date));
          $expected_return_date = date('Y/m/d H:i:s', $expected_return_date);
          return $expected_return_date;
      }

      public function get_repayment_amount($disburse_amount, $loan_profit)
      {
          $repayment_amount = $disburse_amount + $loan_profit;
          return $repayment_amount;
      }

      public function get_loan_profit($disburse_amount, $rate)
      {
          $loan_profit = ($rate/100) * $disburse_amount;
          return $loan_profit;
      }

       //--------------------------------------------------------------------------------------------------------------  
       public function get_customer_current_loan($customer_id)
       {
          try
          {
              $stmt = $this->db->prepare("SELECT disburse_amount FROM mc_loans WHERE customer_id = '$customer_id' AND loan_status = 1");
              $stmt->execute();
              $row = $stmt->fetch(PDO::FETCH_ASSOC);
              
              $disburse_amount = $row['disburse_amount'];
              
            return $disburse_amount;
          }
          catch(PDOException $e)
          {
              echo $e->getMessage();
              return false;
          }        
       }


       //--------------------------------------------------------------------------------------------------------------  
       public function get_customer_current_investment($customer_id)
       {
          try
          {   $stmt = $this->db->prepare("SELECT investment_amount, current_value FROM mc_investments WHERE customer_id = '$customer_id' AND investment_status = 1");
              $stmt->execute();
              if($stmt->rowCount()>0)
              {
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                  {
                      echo "N".$row['investment_amount']."(".$row['current_value']."), ";
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

        //--------------------------------------------------------------------------------------------------------------  
       public function get_customer_current_investment_customer_view($customer_id)
       {
          try
          {   $stmt = $this->db->prepare("SELECT * FROM mc_investments WHERE customer_id = '$customer_id' AND investment_status = 1");
              $stmt->execute();
              if($stmt->rowCount()>0)
              {
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                  {
                      echo "<tr><td>N".$row['investment_amount']."</td><td>".$row['current_value']."</td><td>".$row['interest_rate']."%</td></tr>";
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

       //--------------------------------------------------------------------------------------------------------------  
       public function get_days_to_refund($customer_id)
       {
          $stmt = $this->db->prepare("SELECT * FROM mc_loans WHERE customer_id = '$customer_id'");
          $stmt->execute();
          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          $disburse_date = strtotime($row['disburse_date']);
          $today = date("Y/m/d H:i:s"); //today = strtotime($row['disburse_date']);
          $difference = ($disburse_date - $today)/60/60/24;
         
          return $difference; 
       }

        //Loan countdown
         public function loan_days($customer_id)
         {  
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM mc_loans WHERE customer_id = '$customer_id' AND loan_status = 1");
              $stmt->execute();
              $row = $stmt->fetch(PDO::FETCH_ASSOC);
              $repayment_date = $row['repayment_date'];

              $current_date = new DateTime("now");
              $repayment_date = new DateTime($repayment_date);
              $time_balance = date_diff($current_date, $repayment_date);
              $time_balance = $time_balance->format("%R%a days");
              return $time_balance;
            }
            catch(PDOException $e)
            {
                 echo $e->getMessage();
                 return false;
            }
         }  

         //investment age
         public function investment_days($customer_id)
         {  
            try
            {
              $stmt = $this->db->prepare("SELECT * FROM mc_investments WHERE customer_id = '$customer_id' AND investment_status = 1");
              $stmt->execute();
              $row = $stmt->fetch(PDO::FETCH_ASSOC);
              $investment_date = $row['investment_date'];

              $current_date = new DateTime("now");
              $investment_date = new DateTime($investment_date);
              $time_balance = date_diff($investment_date, $current_date);
              $time_balance = $time_balance->format(" %R%a days");
              return $time_balance;
            }
            catch(PDOException $e)
            {
                 echo $e->getMessage();
                 return false;
            }
         }  


       public function get_loan_status($status_flag)
       {
          try
          {
            $stmt = $this->db->prepare("SELECT status_name FROM mc_loan_status WHERE status_flag = $status_flag");
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

       //--------------------------------------------------------------------------------------------------------------  
       public function get_loan_repayment_date($customer_id)
       {
          $stmt = $this->db->prepare("SELECT repayment_date FROM mc_loans WHERE customer_id = '$customer_id'");
          $stmt->execute();
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          return $row['repayment_date']; 
       }

       public function get_investment_date($customer_id)
       {
          $stmt = $this->db->prepare("SELECT investment_date FROM mc_investments WHERE customer_id = '$customer_id'");
          $stmt->execute();
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          return $row['investment_date']; 
       }

       public function get_investment_amount($customer_id)
       {
          $stmt = $this->db->prepare("SELECT investment_amount FROM mc_investments WHERE customer_id = '$customer_id'");
          $stmt->execute();
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          return $row['investment_amount']; 
       }

        public function get_investment_status($status_flag)
       {
          try
          {
            $stmt = $this->db->prepare("SELECT status_name FROM mc_investment_status WHERE status_flag = $status_flag");
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
  }
  ?>