  <?php
  class REPORT
  {
      private $db;
      private $customer;
      private $payment;
   
      function __construct($DB_con, $customer, $payment)
      {
        $this->db = $DB_con;
        $this->customer = $customer;
        $this->payment = $payment;
      }

      //--------------------------------------------------------------------------------------------------------------
      public function single_customer_savings($customer_id)
      {
          try
          {
            $stmt = $this->db->prepare("SELECT * FROM mc_savings WHERE customer_id = $customer_id");
            $stmt->execute();
            
            echo"
            <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                <thead>
                    <tr>
                        <th>Transaction</th>
                        <th>Time/Date</th>
                        <th>Last Payed</th>
                        <th>Processed By</th>
                    </tr>
                </thead>
                <tbody>";

              if($stmt->rowCount()>0)
              {
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                  {
                      echo"
                        <tr>
                            <td>".$row['transaction_id']."</a></td>
                            <td>".$row['timestamp_created']."</td>
                            <td>".number_format($row['amount'],2)."</td>
                            <td>".$row['staff']."</td>
                        </tr>";
                  }
              }
              echo "</tbody>
                  </table>
                <!-- /.table-responsive -->";

              return true;
          }
          catch(PDOException $e)
          {
              echo $e->getMessage();
              return false;
          }
      }

      //--------------------------------------------------------------------------------------------------------------
      public function single_customer_loans($customer_id)
      {
          try
          {
            $stmt = $this->db->prepare("SELECT * FROM mc_loans WHERE customer_id = $customer_id");
            $stmt->execute();

            echo"
            <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                <thead>
                    <tr>
                        <th>Transaction id</th>
                        <th>Principal</th>
                        <th>Date Disbursed</th>
                        <th>Repayment Date</th>
                        <th>Amount to Repay</th>
                        <th>Status</th>
                        <th>Processed By</th>
                    </tr>
                </thead>
                <tbody>";

            if($stmt->rowCount()>0)
            {
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                  {
                      $days_left = $this->payment->get_days_to_refund($customer_id);
                      $loan_status = $this->payment->get_loan_status($row['loan_status']);

                      echo"
                        <tr>
                            <td>".$row['transaction_id']."</a></td>
                            <td>".number_format($row['disburse_amount'],2)."</td>
                            <td>".$row['disburse_date']."</td>
                            <td>".$row['repayment_date']."</td>
                            <td>".number_format($row['repayment_amount'],2)."</td>
                            <td>".$loan_status."</td>
                            <td>".$row['disburse_staff']."</td>
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

      //--------------------------------------------------------------------------------------------------------------
      public function single_customer_investments($customer_id)
      {
          try
          {
            $stmt = $this->db->prepare("SELECT * FROM mc_investments WHERE customer_id = $customer_id");
            $stmt->execute();

            echo"
            <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                <thead>
                    <tr>
                        <th>Transaction id</th>
                        <th>Investment</th>
                        <th>Investment Date</th>
                        <th>Rate</th>
                        <th>Interest</th>
                        <th>Current Value</th>
                        <th>Liquidation Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>";

            if($stmt->rowCount()>0)
            {     
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                  {
                      $investment_status = $this->payment->get_investment_status($row['investment_status']);
                      echo"
                        <tr>
                            <td>".$row['transaction_id']."</a></td>
                            <td>".number_format($row['investment_amount'],2)."</td>
                            <td>".$row['investment_date']."</td>
                            <td>".$row['interest_rate']."</td>
                            <td>".number_format($row['interest_value'],2)."</td>
                            <td>".number_format($row['current_value'],2)."</td>
                            <td>".$row['liquidation_date']."</td>
                            <td>".$investment_status."</td>
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

      //--------------------------------------------------------------------------------------------------------------
      public function single_customer_deposits($customer_id)
      {
          try
          {
            $stmt = $this->db->prepare("SELECT * FROM mc_deposits WHERE customer_id = $customer_id");
            $stmt->execute();

            echo"
            <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                <thead>
                    <tr>
                        <th>Transaction id</th>
                        <th>Amount Paid</th>
                        <th>Processed By</th>
                        <th>Date/Time</th>
                    </tr>
                </thead>
                <tbody>";

            if($stmt->rowCount()>0)
            {
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                  {
                      echo"
                        <tr>
                            <td>".$row['transaction_id']."</a></td>
                            <td>".number_format($row['amount'],2)."</td>
                            <td>".$row['staff']."</td>
                            <td>".$row['timestamp_created']."</td>
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

      //--------------------------------------------------------------------------------------------------------------
      public function single_customer_receipts($customer_id)
      {
          try
          {
            $stmt = $this->db->prepare("SELECT * FROM mc_receipts WHERE customer_id = $customer_id");
            $stmt->execute();

            echo"
            <table width='100%' class='table table-striped table-bordered table-hover' id='dataTables-example'>
                <thead>
                    <tr>
                        <th>Receipt No</th>
                        <th>Transaction id</th>
                        <th>Transaction Type</th>
                        <th>Amount Paid</th>
                        <th>Processed By</th>
                        <th>Date/Time</th>
                    </tr>
                </thead>
                <tbody>";

            if($stmt->rowCount()>0)
            {
                  while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                  {
                      echo"
                        <tr>
                            <td>".$row['receipt_id']."</a></td>
                            <td>".$row['transaction_id']."</a></td>
                            <td>".$row['transaction_type']."</td>
                            <td>".number_format($row['amount'],2)."</td>
                            <td>".$row['staff']."</td>
                            <td>".$row['timestamp_created']."</td>
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
  }
  ?>