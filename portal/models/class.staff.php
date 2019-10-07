  <?php
  class STAFF
  {
      private $db;
      private $hash_pw;
      private $email;

     //--------------------------------------------------------------------------------------------------------------
      public function staff_register($user, $display, $pass, $email)
      {
          //Construct a secure hash for the plain text password
          $secure_pass = generateHash($pass);
          //Construct a unique activation token
          $activation_token = generateActivationToken();
          $user_active = 0;

           try
           {
              global $mysqli;
              $stmt = $mysqli->prepare("INSERT INTO uc_users (
              user_name,
              display_name,
              password,
              email,
              activation_token,
              last_activation_request,
              lost_password_request, 
              active,
              title,
              sign_up_stamp,
              last_sign_in_stamp
              )
              VALUES (
              ?,
              ?,
              ?,
              ?,
              ?,
              '".time()."',
              '0',
              ?,
              'New Member',
              '".time()."',
              '0'
              )");
            
              $stmt->bind_param("sssssi", $user, $display, $secure_pass, $email, $activation_token, $user_active);
              $stmt->execute();
              $inserted_id = $stmt->insert_id;
              $stmt->close();


              //Insert default permission into matches table
              $stmt = $this->mysqli->prepare("INSERT INTO uc_user_permission_matches  (
                user_id,
                permission_id
                )
                VALUES (
                ?,
                '1'
                )");
              $stmt->bind_param("s", $inserted_id);
              $stmt->execute();
              $stmt->close();

              return true; 
           }
           catch(PDOException $e)
           {
               echo $e->getMessage();
               return false;
           }    
      }

      //Simple function to update the last sign in of a user
  public function updateLastSignIn()
  {
    global $mysqli;
    $time = time();
    $stmt = $mysqli->prepare("UPDATE uc_users
      SET
      last_sign_in_stamp = ?
      WHERE
      id = ?");
    $stmt->bind_param("ii", $time, $user_id);
    $stmt->execute();
    $stmt->close(); 
  }
  
  //Return the timestamp when the user registered
  public function signupTimeStamp()
  {
      global $mysqli;
      $stmt = $mysqli->prepare("SELECT sign_up_stamp
        FROM uc_users
        WHERE id = ?");
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $stmt->bind_result($timestamp);
      $stmt->fetch();
      $stmt->close();
      return ($timestamp);
  }
  
  //Update a users password
  public function updatePassword($pass)
  {
      global $mysqli;
      $secure_pass = generateHash($pass);
      $this->hash_pw = $secure_pass;
      $stmt = $mysqli->prepare("UPDATE uc_users
        SET
        password = ? 
        WHERE
        id = ?");
      $stmt->bind_param("si", $secure_pass, $_SESSION['id']);
      $stmt->execute();
      $stmt->close();
  }
  
  //Update a users email
  public function updateEmail($email)
  {
      global $mysqli;
      $this->email = $email;
      $stmt = $mysqli->prepare("UPDATE uc_users
        SET 
        email = ?
        WHERE
        id = ?");
      $stmt->bind_param("si", $email, $this->user_id);
      $stmt->execute();
      $stmt->close(); 
  }
  
  //Is a user has a permission
  public function checkPermission($permission, $user_id)
  {
      global $mysqli;
      //Grant access if master user
      $stmt = $mysqli->prepare("SELECT id 
        FROM uc_user_permission_matches
        WHERE user_id = ?
        AND permission_id = ?
        LIMIT 1
        ");
      $access = 0;
      foreach($permission as $check){
        if ($access == 0){
          $stmt->bind_param("ii", $user_id, $check);
          $stmt->execute();
          $stmt->store_result();
          if ($stmt->num_rows > 0){
            $access = 1;
          }
        }
      }
      if ($access == 1)
      {
        return true;
      }
      else
      {
        return false; 
      }
      $stmt->close();
  }
  
  //Logout
  public function userLogOut()
  {
    destroySession("userCakeUserNaml");
    destroySession("email");
    destroySession("id");
    destroySession("hash_pw");
    destroySession("title");
    destroySession("username");
    destroySession("display_name");
    destroySession("name");
    destroySession("staff");
  }

  //create Log entry
  public function create_log_entry($activity, $staff)
  {
      try
      {
          global $mysqli;
          $stmt = $mysqli->prepare("INSERT INTO mc_logs(activity, staff) VALUES('$activity', '$staff')");
          $stmt->execute();
          

          $stmt = $mysqli->prepare("INSERT INTO mc_logs  (
                activity,
                staff
                )
                VALUES (
                ?,
                ?
                )");
              $stmt->bind_param("ss", $activity, $staff);
              $stmt->execute();
              $stmt->close();

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