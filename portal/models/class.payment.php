  <?php
  class PAYMENT
  {
      private $customer;
      private $staff;

      function __construct($customer, $staff)
      {
        $this->customer = $customer;
        $this->staff = $staff;
      }

      //--------------------------------------------------------------------------------------------------------------
      public function pay_contribution($customer_id, $amount)
      {
          $transaction_id = generate_transaction_id();
          $staff = $_SESSION['staff'];
          global $mysqli;
           try
           {
              $stmt = $mysqli->prepare("UPDATE mc_customers SET savings_balance = savings_balance + $amount, customer_status = 2 WHERE customer_id = '$customer_id'");
              $stmt->execute();$stmt->close();

              $stmt = $mysqli->prepare("INSERT INTO mc_savings (customer_id, transaction_id, amount, staff)VALUES ($customer_id, '$transaction_id', $amount, '$staff')");
              $stmt->execute(); $stmt->close();

              $this->customer->update_returns_contributions($amount);          

              $this->create_receipt($customer_id, $transaction_id, "Periodic Contributions", $amount, $staff);

              $name = $this->customer->get_customer_name($customer_id);
              $this->staff->create_log_entry($staff." Received Payment of ".$amount." from ".$name, $staff);
              
              return true; 
           } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }    
      }

      //--------------------------------------------------------------------------------------------------------------
      public function disburse_loan($customer_id, $disburse_amount, $rate)
      {
          if ($disburse_amount < 0) {
            return false;
          }

          $transaction_id = generate_transaction_id();
          $disburse_staff = $_SESSION['staff'];
          $customer_status = $this->customer->get_customer_status($customer_id);
          
          $disburse_date = date("Y/m/d H:i:s"); //today
          $repayment_date = $this->get_repayment_date($disburse_date); //plus 30days //receive months as select input param from form
          
          $loan_profit = $this->get_loan_profit($disburse_amount, $rate);
          $this->customer->update_returns_profit($loan_profit);

          $repayment_amount = $this->get_repayment_amount($disburse_amount, $loan_profit);

          $transaction_type = "Loan Disbursal";
           try
           {
              global $mysqli;
              $stmt = $mysqli->prepare("SELECT customer_id FROM mc_loans WHERE customer_id = $customer_id AND loan_status = 1");
              $stmt->execute();
              $result = $stmt->get_result();
              $row = $result->fetch_assoc();
              $available = $row['customer_id'];

              if($available == NULL)
              {

                  $mysqli->query("INSERT INTO mc_loans (customer_id, transaction_id, disburse_amount, loan_profit, disburse_date, repayment_date, repayment_amount, disburse_staff, loan_status) VALUES ($customer_id, $transaction_id, $disburse_amount, $loan_profit, '$disburse_date', '$repayment_date', $repayment_amount, '$disburse_staff', 1)");

                  $mysqli->query("INSERT INTO mc_activeloans(customer_id, loan_id) VALUES('$customer_id', (SELECT MAX(loan_id) FROM mc_loans))");

                  $this->create_receipt($customer_id, $transaction_id, $transaction_type, $disburse_amount, $disburse_staff);
                  
                  $name = $this->customer->get_customer_name($customer_id);
                  
                  $this->staff->create_log_entry($disburse_staff." Disbursed Loan of ".$disburse_amount." to ".$name, $disburse_staff);
              }
              return true;
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }    
      }

      //--------------------------------------------------------------------------------------------------------------
      public function refund_loan($customer_id, $payed_amount)
      {
          if ($payed_amount < 0) {
            return false;
          }

          $repayment_staff = $_SESSION['staff'];
          $payed_date = date("Y/m/d H:i:s"); //today
          $transaction_type = "Loan Refunded";

          try
          {
                //1. check if the customer has already been disbursed
                global $mysqli;
                $stmt = $mysqli->prepare("SELECT * FROM mc_loans WHERE customer_id = $customer_id AND loan_status = 1");
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $transaction_id = $row['transaction_id'];

                if($transaction_id != NULL)
                {
                    $mysqli->query("UPDATE mc_loans SET payed_amount = $payed_amount, payed_date = '$payed_date', repayment_staff = '$repayment_staff', loan_status = 2  WHERE customer_id = '$customer_id' AND loan_status = 1");
                    
                    $mysqli->query("DELETE FROM mc_activeloans WHERE customer_id = '$customer_id'");
                    
                    $mysqli->query("UPDATE mc_customers SET customer_status = 2 WHERE customer_id = '$customer_id'");
                    
                    $this->create_receipt($customer_id, $transaction_id, $transaction_type, $payed_amount, $repayment_staff);

                    $name = $this->customer->get_customer_name($customer_id);
                    $this->staff->create_log_entry($repayment_staff." Received a Loan refund of ".$payed_amount." from ".$name, $repayment_staff);

                    return true;
                }
                else
                {
                    return false;
                } 
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }    
      }

      public function make_investment($customer_id, $investment_amount, $interest_rate)
      {   
          if ($investment_amount < 0) {
            return false;
          }

          $transaction_id = generate_transaction_id();
          $investment_staff = $_SESSION['staff'];
          $investment_date = date("Y/m/d H:i:s"); //today

          $next_update_date = strtotime('+ 30 days', strtotime($investment_date));
          $next_update_date = date('Y/m/d H:i:s', $next_update_date);
          
          $interest_value = $this->get_loan_profit($investment_amount, $interest_rate);
          
          $transaction_type = "Investment";
           try
           {    global $mysqli;
                $mysqli->query("INSERT INTO mc_investments(customer_id, transaction_id, investment_amount, investment_date, interest_value, next_update_date, interest_rate, current_value, liquidation_date, investment_staff, liquidation_staff, investment_status) VALUES($customer_id, '$transaction_id', $investment_amount, '$investment_date', $interest_value, '$next_update_date', $interest_rate, $investment_amount, NULL, '$investment_staff', NULL, 1)");

                $mysqli->query("INSERT INTO mc_activeinvestments(customer_id, investment_id) VALUES('$customer_id', (SELECT MAX(investment_id) FROM mc_investments))");
                
                $this->create_receipt($customer_id, $transaction_id, $transaction_type, $investment_amount, $investment_staff);

                $name =$this->customer->get_customer_name($customer_id);
                $this->staff->create_log_entry($investment_staff." Received an Investment Fund from ".$name, $investment_staff);

                return true;
                
           } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }    
      }

      //--------------------------------------------------------------------------------------------------------------
      public function liquidate_investment($customer_id, $investment_id)
      {
          $liquidation_staff = $_SESSION['staff'];
          $liquidation_date = date("Y/m/d H:i:s"); //today
          $transaction_type = "Liquidation of Investment";

          try
          {
                //1. check if the customer has investment
                //if there are mustiple investments allowed, search by customer id and investment id and investment status
                global $mysqli;
                $stmt = $mysqli->prepare("SELECT * FROM mc_investments WHERE customer_id = '$customer_id' AND investment_id = '$investment_id' AND investment_status = 1");
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $transaction_id = $row['transaction_id'];
                $liquidation_amount = $row['current_value'];

                if($transaction_id != NULL)
                {
                    $mysqli->query("UPDATE mc_investments SET investment_status = 2, liquidation_date = '$liquidation_date', liquidation_staff = '$liquidation_staff' WHERE customer_id = $customer_id AND investment_id = $investment_id");
                    
                    $mysqli->query("DELETE FROM mc_activeinvestments WHERE customer_id = $customer_id");

                    $this->create_receipt($customer_id, $transaction_id, $transaction_type, $liquidation_amount, $liquidation_staff);

                    $name = $this->customer->get_customer_name($customer_id);
                    $this->staff->create_log_entry($liquidation_staff." Liquidated an Investment of ".$liquidation_amount." from ".$name, $liquidation_staff);
                
                    return true;
                }
                else
                {
                    return false;
                } 
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }    
      }

      //--------------------------------------------------------------------------------------------------------------
      public function withdraw_saving($customer_id, $amount)
      {
          
          if ($amount < 0) {
            return false;
          }
          $transaction_id = generate_transaction_id();
          $staff = $_SESSION['staff'];
           try
           {
              global $mysqli;
              $mysqli->query("UPDATE mc_customers SET savings_balance = savings_balance - $amount, customer_status = 2 WHERE customer_id = '$customer_id'");
              
              $mysqli->query("INSERT INTO mc_withdrawals(customer_id, transaction_id, amount, staff) VALUES($customer_id, $transaction_id, $amount, '$staff')");
              
              $this->create_receipt($customer_id, $transaction_id, "Savings Withdrawal", $amount, $staff);

              $name = $this->customer->get_customer_name($customer_id);
              $this->staff->create_log_entry($staff." Made a withdrawal of ".$amount." for ".$name, $staff);
              
              return true; 
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }    
      }

      //--------------------------------------------------------------------------------------------------------------
      public function customer_investment($customer_id, $investment_id)
      {
          try
          {
              global $mysqli;
              $stmt = $mysqli->prepare("SELECT * FROM mc_investments WHERE customer_id = '$customer_id' AND investment_id = $investment_id");
              $stmt->execute();
              $result = $stmt->get_result();
              $row = $result->fetch_assoc();
              
              $investment_status = $this->get_investment_status($row['investment_status']);
              echo"
                <div class='list-group'>
                  <p class='list-group-item'>
                      <b>Principal:</b> ".$row['investment_amount'].", <b>on</b> ".$row['investment_date']."
                  </p>
                  <p class='list-group-item'>
                      <b>Interest:</b> ".$row['interest_rate']."%, <b>Value:</b> N".$row['interest_value']." 
                  </p>
                  <p class='list-group-item'>
                      <b>Current Value:</b> ".number_format($row['current_value'],2)."
                  </p>
                  <p class='list-group-item'>
                      <b>Liquidation Date:</b> ".$row['liquidation_date']."</em>
                  </p>
                  <p class='list-group-item'>
                      <b>Investment Status:</b> ".$investment_status."</em>
                  </p>
              </div>";
              $stmt->close();
              return true;
              
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
      }
      
      //---------------------------------------------------------------------------------------------------
      public function create_receipt($customer_id, $transaction_id, $transaction_type, $amount, $staff)
      {
           try
           {
              global $mysqli;
              $mysqli->query("INSERT INTO mc_receipts(customer_id, transaction_id, transaction_type, amount, staff)
               VALUES($customer_id, '$transaction_id', '$transaction_type', $amount, '$staff')");
              
              return true; 
           } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }    
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
              global $mysqli;
              $stmt = $mysqli->prepare("SELECT disburse_amount FROM mc_loans WHERE customer_id = '$customer_id' AND loan_status = 1");
              $stmt->execute();
              $result = $stmt->get_result();
              $row = $result->fetch_assoc();

              $disburse_amount = $row['disburse_amount'];
              
              return $disburse_amount;
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }        
       }


       //--------------------------------------------------------------------------------------------------------------  
       public function get_customer_current_investment($customer_id)
       {
          try
          {   
              global $mysqli;
              $stmt = $mysqli->prepare("SELECT investment_amount, current_value FROM mc_investments WHERE customer_id = '$customer_id' AND investment_status = 1");
              $stmt->execute();
              $result = $stmt->get_result();

              
              while ($row = $result->fetch_assoc()) 
              {
                  echo "<tr>
                          <td><b>N</b>".number_format($row['investment_amount'],2)."</td>
                          <td><b>N</b>".number_format($row['current_value'],2)." </td>
                        </tr>";
              }
                  
              $stmt->close();
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }        
       }

       //--------------------------------------------------------------------------------------------------------------  
       public function get_days_to_refund($customer_id)
       {
          global $mysqli;
          $stmt = $mysqli->prepare("SELECT * FROM mc_loans WHERE customer_id = '$customer_id'");
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();

          $disburse_date = strtotime($row['disburse_date']);
          $today = strtotime(date("Y/m/d H:i:s")); //today = strtotime($row['disburse_date']);
          $difference = ($disburse_date - $today)/60/60/24;
         
          return $difference; 
       }

        //Loan countdown
         public function loan_days($customer_id)
         {  
            try
            {
              global $mysqli;
              $stmt = $mysqli->prepare("SELECT * FROM mc_loans WHERE customer_id = '$customer_id' AND loan_status = 1");
              $stmt->execute();
              $result = $stmt->get_result();
              $row = $result->fetch_assoc();

              $repayment_date = $row['repayment_date'];

              $current_date = new DateTime("now");
              $repayment_date = new DateTime($repayment_date);
              $time_balance = date_diff($current_date, $repayment_date);
              $time_balance = $time_balance->format("%R%a days");
              return $time_balance;
            } 
            catch (Exception $e) 
            { 
                echo $e->errorMessage(); 
            }
         }  

         //investment age
         public function investment_days($customer_id)
         {  
            try
            {
              global $mysqli;
              $stmt = $mysqli->prepare("SELECT * FROM mc_investments WHERE customer_id = '$customer_id' AND investment_status = 1");
              $stmt->execute();
              $result = $stmt->get_result();
              $row = $result->fetch_assoc();

              $investment_date = $row['investment_date'];

              $current_date = new DateTime("now");
              $investment_date = new DateTime($investment_date);
              $time_balance = date_diff($investment_date, $current_date);
              $time_balance = $time_balance->format(" %R%a days");
              return $time_balance;
            } 
            catch (Exception $e) 
            { 
                echo $e->errorMessage(); 
            }
         }  

        //investment update
         public function investment_update()
         {  
            try
            {
              global $mysqli;
              $stmt = $mysqli->prepare("UPDATE mc_investments SET current_value = current_value + ((interest_rate/100)*investment_amount), next_update_date = ADDDATE(CURDATE(), INTERVAL 30 DAY) WHERE DAYOFMONTH(CURDATE()) = DAYOFMONTH(next_update_date)"); 
              $stmt->execute();$stmt->close();

              return true;
            } 
            catch (Exception $e) 
            { 
                echo $e->errorMessage(); 
            }
         }  

         //investment update
         public function update_staff_contributions()
         {  
            try
            {
              $stmt = $mysqli->prepare("UPDATE mc_investments SET current_value = current_value + ((interest_rate/100)*investment_amount), next_update_date = ADDDATE(CURDATE(), INTERVAL 30 DAY) WHERE DAYOFMONTH(CURDATE()) = DAYOFMONTH(next_update_date)"); 
              $stmt->execute();$stmt->close();

              return true;
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
            global $mysqli;
            $stmt = $mysqli->prepare("SELECT status_name FROM mc_loan_status WHERE status_flag = $status_flag");
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
        
            return $row['status_name'];
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
       }

       //--------------------------------------------------------------------------------------------------------------  
       public function get_loan_repayment_date($customer_id)
       {
          global $mysqli;
          $stmt = $mysqli->prepare("SELECT repayment_date FROM mc_loans WHERE customer_id = '$customer_id'");
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          return $row['repayment_date']; 
       }

       public function get_investment_date($customer_id)
       {
          global $mysqli;
          $stmt = $mysqli->prepare("SELECT investment_date FROM mc_investments WHERE customer_id = '$customer_id'");
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          return $row['investment_date']; 
       }

       public function get_investment_amount($customer_id)
       {
          global $mysqli;
          $stmt = $mysqli->prepare("SELECT investment_amount FROM mc_investments WHERE customer_id = '$customer_id'");
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          return $row['investment_amount']; 
       }

        public function get_investment_status($status_flag)
       {
          try
          {
            global $mysqli;
            $stmt = $mysqli->prepare("SELECT status_name FROM mc_investment_status WHERE status_flag = $status_flag");
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            return $row['status_name'];
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
       }
  }
  ?>