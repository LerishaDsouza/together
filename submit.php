<html>
<head>
<style>
body,html
{
height:100%;
}
body {
font-family: Arial, Helvetica, sans-serif;
background-image:
  url("("http://www.jclibrary.org/images/newimages/donate.jpg");
    background-size:100% 100%;
    background-repeat:no-repeat;
   width:100%;
}
@media screen and (max-width: 600px) {
    }
   body
{
background-image:
    url("("http://www.jclibrary.org/images/newimages/donate.jpg");
}

}
    </style>
</head>
<body>
<?php
//check whether stripe token is not empty
if(!empty($_POST['stripeToken'])){
    //get token, card and user info from the form
    $token  = $_POST['stripeToken'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $card_num = $_POST['card_num'];
    $card_cvc = $_POST['cvc'];
    $card_exp_month = $_POST['exp_month'];
    $card_exp_year = $_POST['exp_year'];
    $itemPrice = $_POST['amountt'];
    
    //include Stripe PHP library
    require_once('stripe-php/stripe-php/init.php');
    
    //set api key
    $stripe = array(
      "secret_key"      => "sk_test_Yfzae6f7wR8OELTXPophtcIF",
      "publishable_key" => "pk_test_UAuNPqAvFdgVAbN3BCkwCOZy"
    );
    
    \Stripe\Stripe::setApiKey($stripe['secret_key']);
    
    //add customer to stripe
    $customer = \Stripe\Customer::create(array(
        'email' => $email,
        'source'  => $token
    ));
    
    //item information
    $itemName = "Donation";
   
    $currency = "usd";
    
    //charge a credit or a debit card
    $charge = \Stripe\Charge::create(array(
        'customer' => $customer->id,
        'amount'   => $itemPrice,
        'currency' => $currency,
        'description' => $itemName,
    ));
    
    //retrieve charge details
    $chargeJson = $charge->jsonSerialize();

    //check whether the charge is successful
    if($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1){
        //order details 
        $amount = $chargeJson['amount'];
        $balance_transaction = $chargeJson['balance_transaction'];
        $currency = $chargeJson['currency'];
        $status = $chargeJson['status'];
        $date = date("Y-m-d H:i:s");
        
        //include database config file
        include_once 'dbConfig.php';
        
        //insert tansaction data into the database
        $sql = "INSERT INTO donate(name,email,card_num,card_cvc,card_exp_month,card_exp_year,paid_amount,paid_amount_currency,txn_id,payment_status,created,modified) VALUES('".$name."','".$email."','".$card_num."','".$card_cvc."','".$card_exp_month."','".$card_exp_year."','".$amount."','".$currency."','".$balance_transaction."','".$status."','".$date."','".$date."')";
        $insert = $db->query($sql);
        $last_insert_id = $db->insert_id;
        //if order inserted successfully
      if($last_insert_id && $status == 'succeeded'){
            $statusMsg = "<h2 align=center>The transaction was successful.</h2><h4 align=center>Order ID: {$last_insert_id}</h4>";
        }else{
            $statusMsg = "<h2 align=center>Transaction has been failed</h2>";
        }
    }else{
        $statusMsg = "<h2 align=center>Transaction has been failed</h2>";
    }
}else{
    $statusMsg = "<h2 align=center>Form submission error.......</h2>";
}

//show success or error message
echo $statusMsg;
echo "<div align='center'><a href=index.php><b>Click Here To Continue</b></a></div>"
?>
</body>
</html>
