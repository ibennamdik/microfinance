<?php
require_once("models/config.php");

$firstname;
$lastname;
$customer_type;
$phone;
$email;
$address;

//enable/disable flag
$flag;

$error_count = 0;
$successes = "";
$errors = "";

if(isset($_POST['newCustomer']))
{
  $firstname = trim($_POST["firstname"]);
  $lastname = trim($_POST["lastname"]);
  $phone = trim($_POST["phone"]);
  $email = trim($_POST["email"]);
  $address = trim($_POST["address"]);
  $type_flag = trim($_POST["type_flag"]);
  
  
  if(minMaxRange(2,25,$firstname))
  {
    $errors .= "First Name is too long, max 25 characters</br>";
    $error_count++;
  }

  if(!ctype_alnum($firstname)){
    $errors .= "First Name contains invalid characters</br>";
    $error_count++;
  }

  if(minMaxRange(2,25,$lastname))
  {
    $errors .= "Last Name is too long, max 25 characters</br>";
    $error_count++;
  }

  if(!ctype_alnum($lastname)){
    $errors .= "Last Name contains invalid characters</br>";
    $error_count++;
  }

  if(!isValidEmail($email))
  {
    $errors .= "Email is invalid</br>";
    $error_count++;
  }

  if(minMaxRange(11,11,$phone))
  {
    $errors .= "Phone Number is too long, max 11 characters</br>";
    $error_count++;
  }
  if(phoneNumberExists($phone))
  {
    $errors .= "Phone already taken<br>";
    $error_count++;
  }

  if(minMaxRange(5,64,$address))
  {
    $errors .= "Address is too long, max 64 characters</br>";
    $error_count++;
  }

  //End data validation
  if($error_count == 0)
  { 
    //call register function
    echo $customer->customer_register($firstname, $lastname, $phone, $email, $address, $type_flag);
    $successes .= "Registeration Successful";
  }
  $url = "index.php?page=2&successes=".$successes."&errors=".$errors;
  redirect($url);
}

if(isset($_POST['updateCustomer']))
{
  $customer_id = $_POST["customer_id"];
  $firstname = trim($_POST["firstname"]);
  $lastname = trim($_POST["lastname"]);
  $email = trim($_POST["email"]);
  $address = trim($_POST["address"]);
  $phone = trim($_POST["phone"]);
  $type_flag = trim($_POST["type_flag"]);
  
  if(minMaxRange(2,25,$firstname))
  {
    $errors .= "First Name is too long, max 25 characters</br>";
    $error_count++;
  }

  if(!ctype_alnum($firstname)){
    $errors .= "First Name contains invalid characters</br>";
    $error_count++;
  }

  if(minMaxRange(2,25,$lastname))
  {
    $errors .= "Last Name is too long, max 25 characters</br>";
    $error_count++;
  }

  if(!ctype_alnum($lastname)){
    $errors .= "Last Name contains invalid characters</br>";
    $error_count++;
  }

  if(!isValidEmail($email))
  {
    $errors .= "Email is invalid</br>";
    $error_count++;
  }

  if(minMaxRange(11,11,$phone))
  {
    $errors .= "Phone Number is too long, max 11 characters</br>";
    $error_count++;
  }

  if(minMaxRange(5,64,$address))
  {
    $errors .= "Address is too long, max 64 characters</br>";
    $error_count++;
  }

  //echo $firstname." ". $lastname." ". $email." ". $address." ". $client_image;
  //End data validation
  if($error_count == 0)
  { 
    //call update function
    $customer->customer_update($customer_id, $firstname, $lastname, $phone, $email, $address, $type_flag);  
    $successes .= "Update Successful"; 
    $url = "index.php?page=2.1&customer_id=".$customer_id."&successes=".$successes."&errors=".$errors;
    redirect($url);
  }
  else{
    $url = "index.php?page=2.1&customer_id=".$customer_id."&successes=".$successes."&errors=".$errors;
    redirect($url);
  }
}

if(isset($_GET['deleteCustomer']))
{
  $customer_id = $_GET["customer_id"];
  $url = "index.php?page=2.4&customer_id=".$customer_id;
  redirect($url);
}
else if (isset($_GET['ConfirmCustomerDelete']) && isset($_GET['customer_id'])) {
  
  $customer_id = $_GET["customer_id"];
  if ($customer->customer_delete($customer_id)) {
    $successes .= "Customer Deleted Successfully<br>"; 
  }
  else
  {
    $errors .= "Customer Not Deleted<br>";
  }
  $url = "index.php?page=2&customer_id=".$customer_id."&successes=".$successes."&errors=".$errors;
  redirect($url);
}

if(isset($_POST['updateImage']))
{
    $customer_id = $_POST["customer_id"];
    $imgFile = $_FILES['user_image']['name'];
    $tmp_dir = $_FILES['user_image']['tmp_name'];
    $imgSize = $_FILES['user_image']['size'];
          
    if($imgFile)
    {
      $upload_dir = 'img/customers/'; // upload directory 
      $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
      $valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
      $userpic = $customer_id.".".$imgExt;

      if(in_array($imgExt, $valid_extensions))
      {     
        echo "extension is good<br>";
        if($imgSize < 1000000)
        {
            if($customer->update_image($upload_dir.$userpic, $customer_id))
            {
                unlink($upload_dir.$userPic);
                move_uploaded_file($tmp_dir,$upload_dir.$userpic);
                $successes .= "Image Updated Successfully";
                $url = "index.php?page=2.1&customer_id=".$customer_id."&successes=".$successes."&errors=".$errors;
                redirect($url);
            }
            else
            {
                $errors .= "Sorry Data Could Not Updated!";
                $url = "index.php?page=2.1&customer_id=".$customer_id."&successes=".$successes."&errors=".$errors;
                redirect($url);
            } 
        }
        else
        {
            $errors .= "Sorry, your file is too large it should be less then 1MB";
        }
      }
      else
      {
          $errors .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";    
      } 
    }    
  }
?>