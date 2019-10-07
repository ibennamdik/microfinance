  <?php
  class REPORT
  {
      private $customer;
      private $payment;
      private $staff;
      
      function __construct($customer, $payment, $staff)
      {
        $this->customer = $customer;
        $this->payment = $payment;
        $this->staff = $staff;
      }

      //--------------------------------------------------------------------------------------------------------------
      public function single_customer_savings($customer_id)
      {
          try
          {

            echo"
            <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                <thead>
                    <tr>
                        <th>Time/Date</th>
                        <th>Last Payed</th>
                        <th>Processed By</th>
                    </tr>
                </thead>
                <tbody>";
              global $mysqli;
              $stmt = $mysqli->query("SELECT * FROM mc_savings WHERE customer_id = $customer_id");
              while ($row = $stmt->fetch_assoc()) 
              {
                echo"
                  <tr>
                      <td>".$row['timestamp_created']."</td>
                      <td>".number_format($row['amount'],2)."</td>
                      <td>".$row['staff']."</td>
                  </tr>";
                  
              }
              echo "</tbody>
                  </table>
                <!-- /.table-responsive -->";

              return true;
          } catch (Exception $e) { 
            echo $e->errorMessage(); 
          }
      }

      //--------------------------------------------------------------------------------------------------------------
      public function single_customer_loans($customer_id)
      {
          try
          {
            echo"
            <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                <thead>
                    <tr>
                        <th>Date Disbursed</th>
                        <th>Repayment Date</th>
                        <th>Principal</th>
                        <th>Amount to Repay</th>
                        <th>Status</th>
                        <th>Processed By</th>
                    </tr>
                </thead>
                <tbody>";

              global $mysqli;
              $stmt = $mysqli->query("SELECT * FROM mc_loans WHERE customer_id = $customer_id");
              while ($row = $stmt->fetch_assoc()) 
              {
                  $days_left = $this->payment->get_days_to_refund($customer_id);
                  $loan_status = $this->payment->get_loan_status($row['loan_status']);

                  echo"
                    <tr>
                        <td>".$row['disburse_date']."</td>
                        <td>".$row['repayment_date']."</td>
                        <td>".number_format($row['disburse_amount'],2)."</td>
                        <td>".number_format($row['repayment_amount'],2)."</td>
                        <td>".$loan_status."</td>
                        <td>".$row['disburse_staff']."</td>
                    </tr>";
              }
              return true;
        } 
        catch (Exception $e) 
        { 
            echo $e->errorMessage(); 
        }
      }

      //--------------------------------------------------------------------------------------------------------------
      public function single_customer_investments($customer_id)
      {
          try
          { 
            echo"
            <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                <thead>
                    <tr>
                        <th>Investment Date</th>
                        <th>Investment</th>
                        <th>Interest Rate</th>
                        <th>Current Value</th>
                        <th>Liquidation Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>";

              global $mysqli;
              $stmt = $mysqli->query("SELECT * FROM mc_investments WHERE customer_id = $customer_id");
              while ($row = $stmt->fetch_assoc()) 
              {
                  $investment_status = $this->payment->get_investment_status($row['investment_status']);
                  echo"
                    <tr>
                        <td>".$row['investment_date']."</td>
                        <td>".number_format($row['investment_amount'],2)."</td>
                        <td>".$row['interest_rate']."%</td>
                        <td>".number_format($row['current_value'],2)."</td>
                        <td>".$row['liquidation_date']."</td>
                        <td>".$investment_status."</td>
                        <td><a class='btn btn-default btn-sm' href='index.php?page=5&customer_id=".$customer_id."&investment_id=".$row['investment_id']."&investment_status=".$row['investment_status']."'>Manage</a></td>
                    </tr>";
                  
              }
              return true;
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
      }

      //--------------------------------------------------------------------------------------------------------------
      public function single_customer_deposits($customer_id)
      {
          try
          {
            echo"
            <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                <thead>
                    <tr>
                        <th>Date/Time</th>
                        <th>Amount Paid</th>
                        <th>Processed By</th>
                    </tr>
                </thead>
                <tbody>";

              global $mysqli;
              $stmt = $mysqli->query("SELECT * FROM mc_deposits WHERE customer_id = $customer_id");
              while ($row = $stmt->fetch_assoc()) 
              {
                      echo"
                        <tr>
                            <td>".$row['timestamp_created']."</td>
                            <td>".number_format($row['amount'],2)."</td>
                            <td>".$row['staff']."</td>
                        </tr>";
              }
              return true;
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
      }

      //--------------------------------------------------------------------------------------------------------------
      public function single_customer_receipts($customer_id)
      {
          try
          {
            echo"
            <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                <thead>
                    <tr>
                        <th>Date/Time</th>
                        <th>Receipt No</th>
                        <th>Transaction id</th>
                        <th>Transaction Type</th>
                        <th>Amount Paid</th>
                        <th>Processed By</th>
                    </tr>
                </thead>
                <tbody>";

              global $mysqli;
              $stmt = $mysqli->query("SELECT * FROM mc_receipts WHERE customer_id = $customer_id");
              while ($row = $stmt->fetch_assoc()) 
              {
                    echo"
                    <tr>
                        <td>".$row['timestamp_created']."</td>
                        <td>".$row['receipt_id']."</a></td>
                        <td>".$row['transaction_id']."</a></td>
                        <td>".$row['transaction_type']."</td>
                        <td>".number_format($row['amount'],2)."</td>
                        <td>".$row['staff']."</td>
                    </tr>";
              }
              return true;
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
      }

      //--------------------------------------------------------------------------------------------------------------
      public function all_customer_savings()
      {
          try
          {
            echo"
            <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                <thead>
                    <tr>
                        <th>Time/Date</th>
                        <th>Account</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Processed By</th>
                    </tr>
                </thead>
                <tbody>";

              global $mysqli;
              $stmt = $mysqli->query("SELECT * FROM mc_savings");
              while ($row = $stmt->fetch_assoc()) 
              {
                  $customer_name = $this->customer->get_customer_name($row['customer_id']);
                  echo"
                    <tr>
                        <td>".$row['timestamp_created']."</td>
                        <td>".$row['customer_id']."</td>
                        <td><a href='index.php?page=2.1&customer_id=".$row['customer_id']."'>".$customer_name."</a></td>
                        <td>".number_format($row['amount'],2)."</td>
                        <td>".$row['staff']."</td>
                    </tr>";
              }
              echo "</tbody>
                  </table>";

              return true;
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
      }

      //--------------------------------------------------------------------------------------------------------------
      public function all_customer_loans_by_status($loan_status)
      {
          try
          {
            echo"
            <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                <thead>
                    <tr>
                        <th>Repayment Date</th>
                        <th>Account</th>
                        <th>Name</th>
                        <th>Principal</th>
                        <th>Repayment</th>
                    </tr>
                </thead>
                <tbody>";

              global $mysqli;
              $stmt = $mysqli->query("SELECT * FROM mc_loans WHERE loan_status = $loan_status");
              while ($row = $stmt->fetch_assoc()) 
              {
                  $customer_name = $this->customer->get_customer_name($row['customer_id']);
                  echo"
                    <tr>
                        <td>".$row['repayment_date']."</td>
                        <td>".$row['customer_id']."</td>
                        <td><a href='index.php?page=2.1&customer_id=".$row['customer_id']."'>".$customer_name."</a></td>
                        <td>".number_format($row['disburse_amount'],2)."</td>
                        <td>".number_format($row['repayment_amount'],2)."</td>
                    </tr>";
              }
              return true;
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
      }

      //--------------------------------------------------------------------------------------------------------------
      public function all_customer_loans()
      {
          try
          {
            echo"
            <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                <thead>
                    <tr>
                        <th>Date Disbursed</th>
                        <th>Account</th>
                        <th>Name</th>
                        <th>Principal</th>
                        <th>Repayment Date</th>
                        <th>Repayment</th>
                        <th>Processed By</th>
                    </tr>
                </thead>
                <tbody>";

              global $mysqli;
              $stmt = $mysqli->query("SELECT * FROM mc_loans");
              while ($row = $stmt->fetch_assoc()) 
              {
                  $customer_name = $this->customer->get_customer_name($row['customer_id']);
                  echo"
                    <tr>
                        <td>".$row['disburse_date']."</td>
                        <td>".$row['customer_id']."</td>
                        <td><a href='index.php?page=2.1&customer_id=".$row['customer_id']."'>".$customer_name."</a></td>
                        <td>".number_format($row['disburse_amount'],2)."</td>
                        <td>".$row['repayment_date']."</td>
                        <td>".number_format($row['repayment_amount'],2)."</td>
                        <td>".$row['disburse_staff']."</td>
                    </tr>";
              }
              return true;
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
      }

      //--------------------------------------------------------------------------------------------------------------
      public function all_customer_deposits()
      {
          try
          {
            echo"
            <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                <thead>
                    <tr>
                        <th>Date/Time</th>
                        <th>Account</th>
                        <th>Name</th>
                        <th>Amount Paid</th>
                        <th>Processed By</th>
                    </tr>
                </thead>
                <tbody>";

              global $mysqli;
              $stmt = $mysqli->query("SELECT * FROM mc_deposits");
              while ($row = $stmt->fetch_assoc()) 
              {
                  $customer_name = $this->customer->get_customer_name($row['customer_id']);
                  echo"
                    <tr>
                        <td>".$row['timestamp_created']."</td>
                        <td>".$row['customer_id']."</td>
                        <td><a href='index.php?page=2.1&customer_id=".$row['customer_id']."'>".$customer_name."</a></td>
                        <td>".number_format($row['amount'],2)."</td>
                        <td>".$row['staff']."</td>
                    </tr>";
              
              }
              return true;
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
      }

      //--------------------------------------------------------------------------------------------------------------
      public function all_customer_receipts()
      {
          try
          {
            echo"
            <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                <thead>
                    <tr>
                        <th>Date/Time</th>
                        <th>Receipt No</th>
                        <th>Account</th>
                        <td>Name</td>
                        <th>Amount Paid</th>
                        <th>Processed By</th>
                    </tr>
                </thead>
                <tbody>";

              global $mysqli;
              $stmt = $mysqli->query("SELECT * FROM mc_receipts");
              while ($row = $stmt->fetch_assoc()) 
              {
                  $customer_name = $this->customer->get_customer_name($row['customer_id']);
                  echo"
                    <tr>
                        <td>".$row['timestamp_created']."</td>
                        <td>".$row['receipt_id']."</a></td>
                        <td>".$row['customer_id']."</td>
                        <td>".$customer_name."</td>
                        <td>".number_format($row['amount'],2)."</td>
                        <td>".$row['staff']."</td>
                    </tr>";
              
              }
              return true;
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
      }
      
      //--------------------------------------------------------------------------------------------------------------
      public function all_staff_returns()
      {
          try
          {
            echo"
            <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                <thead>
                    <tr>
                        <th>Staff No</th>
                        <th>Username</th>
                        <th>Display Name</th>
                        <th>Designation</th>
                        <th>Collections</th>
                        <th>Profit</th>
                        <th>Reg. Accounts</th>
                    </tr>
                </thead>
                <tbody>";

                global $mysqli;
                $stmt = $mysqli->query("SELECT * FROM uc_users");
                while ($row = $stmt->fetch_assoc()) 
                {
                    echo"
                      <tr>
                          <td>".$row['id']."</a></td>
                          <td>".$row['user_name']."</a></td>
                          <td>".$row['display_name']."</a></td>
                          <td>".$row['title']."</a></td>
                          <td>".number_format($row['income_contributions'],2)."</td>
                          <td>".$row['income_profit']."</td>
                          <td>".$row['income_customers']."</td>
                      </tr>";
                  
                }
                return true;
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
      }

      //--------------------------------------------------------------------------------------------------------------
      public function all_investors()
      {
          try
          {
            echo"
            <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                <thead>
                    <tr>
                        <th>Account</th>
                        <th>Investment Date</th>
                        <th>Investment</th>
                        <th>Interest Rate</th>
                        <th>Current Value</th>
                        <th>Liquidation Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>";

              global $mysqli;
              $stmt = $mysqli->query("SELECT * FROM mc_investments");
              while ($row = $stmt->fetch_assoc()) 
              {
                  $investment_status = $this->payment->get_investment_status($row['investment_status']);
                  echo"
                    <tr>
                        <td>".$row['customer_id']."</td>
                        <td>".$row['investment_date']."</td>
                        <td>".number_format($row['investment_amount'],2)."</td>
                        <td>".$row['interest_rate']."</td>
                        <td>".number_format($row['current_value'],2)."</td>
                        <td>".$row['liquidation_date']."</td>
                        <td>".$investment_status."</td>
                        <td><a class='btn btn-default btn-sm' href='index.php?page=4&customer_id=".$row['customer_id']."&investment_id=".$row['investment_id']."&investment_status=".$row['investment_status']."'>Manage</a></td>
                    </tr>";
                  
              }
              return true;
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
      }

       //--------------------------------------------------------------------------------------------------------------
      public function staff_profile($user_id)
      {
          try
          {   global $mysqli;
              $stmt = $mysqli->query("SELECT user_name, display_name, email, income_customers, income_profit, income_contributions FROM uc_users WHERE uc_users.id = $user_id");
              while ($row = $stmt->fetch_assoc()) 
              {
                  echo"
                    <div class='list-group'>
                      <p class='list-group-item'>
                          <b>USERNAME</b>: ".$row['user_name']."
                      </p>
                      <p class='list-group-item'>
                          <b>NAME</b>: ".$row['display_name']."
                      </p>
                      <p class='list-group-item'>
                          <b>EMAIL</b>: ".$row['email']."
                      </p>
                      <p class='list-group-item'>
                          <b>CUSTOMERS</b>: ".$row['income_customers']."</em>
                      </p>
                      <p class='list-group-item'>
                          <b>PROFIT</b>: ".number_format($row['income_profit'],2)."</em>
                      </p>
                      <p class='list-group-item'>
                         <b>SAVINGS</b>: ".number_format($row['income_contributions'],2)."</em>
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

      //-------------------------------------------------------------------------------------------------
      public function logs()
      {
          $staff = $_SESSION['staff'];
          try
          {
              global $mysqli;
              $stmt = $mysqli->query("SELECT * FROM mc_logs");
              while ($row = $stmt->fetch_assoc()) 
              {
                echo"
                <tr>
                    <td>".$row['timestamp_created']."</td>
                    <td>".$row['staff']."</td>
                    <td>".$row['activity']."</td>
                </tr>";
                  
                    
              }
              return true;
              
          } 
          catch (Exception $e) 
          { 
              echo $e->errorMessage(); 
          }
      }
  }
  ?>