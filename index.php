<html>
<head>
<title>Together</title>
<style>
      body, html {
  height: 100%;
}
body {
  background-image: url("http://www.jclibrary.org/images/newimages/donate.jpg");
  background-repeat: no-repeat;
  background-size:100% 100%;
   width:100%;
}
form
{

text-align:center;

}
.container{
padding:20px;
}
@media screen and (max-width: 600px) {
    }
   body
{
background-image:
    url("http://www.jclibrary.org/images/newimages/donate.jpg");
}

}
</style>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
//set your publishable key
Stripe.setPublishableKey('pk_test_UAuNPqAvFdgVAbN3BCkwCOZy');

//callback to handle the response from stripe
function stripeResponseHandler(status, response) {
    if (response.error) {
        //enable the submit button
        $('#payBtn').removeAttr("disabled");
        //display the errors on the form
        $(".payment-errors").html(response.error.message);
    } else {
        var form$ = $("#paymentFrm");
        //get token id
        var token = response['id'];
        //insert the token into the form
        form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
        //submit form to the server
        form$.get(0).submit();
    }
}
$(document).ready(function() {
    //on form submit
    $("#paymentFrm").submit(function(event) {
        //disable the submit button to prevent repeated clicks
        $('#payBtn').attr("disabled", "disabled");
        
        //create single-use token to charge the user
        Stripe.createToken({
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
        }, stripeResponseHandler);
        
        //submit from callback
        return false;
    });
});
</script>
</head>
<body>
<h1 align="center">Together</h1>
<h2>About Us</h2>
<p>There is nothing more beautiful than someone who goes out of their way to make life beautiful for others but often we can't reach out to people who are in need due to several reasons.Together aims to reach out to people in need to make their life a litte better.Do scroll down to donate a penny.
<p>
</p>
<div class="container">

<!-- display errors returned by createToken -->
<span class="payment-errors"></span>

<!-- stripe payment form -->
<form action="submit.php" method="POST" id="paymentFrm">
    <p>
        <label>Name</label>
        <input type="text" name="name" size="50" />
    </p>
    <p>
        <label>Email</label>
        <input type="text" name="email" size="50" />
    </p>
    <p>
        <label>Card Number</label>
        <input type="text" name="card_num" size="20" autocomplete="off" class="card-number" />
    </p>
    <p>
        <label>CVC</label>
        <input type="text" name="cvc" size="4" autocomplete="off" class="card-cvc" />
    </p>
    <p>
        <label>Expiration (MM/YYYY)</label>
        <input type="text" name="exp_month" size="2" class="card-expiry-month"/>
        <span> / </span>
        <input type="text" name="exp_year" size="4" class="card-expiry-year"/>
    </p>
    <p>
     <label>Amount</label>
     <input type="text" name="amountt" size="2" class="amount">
    </p>
     <p>
    <button type="submit" id="payBtn" onclick="closeForm()">DONATE</button>
     <p>
</form>
</div>
</body>
</html>