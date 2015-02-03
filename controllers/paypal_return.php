<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "models/paypal.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "models/ads_new.php";

session_start();

if( isset($_GET['token']) && !empty($_GET['token']) )
{
    // Token parameter exists
    // Get checkout details, including buyer information.
	  // We can save it for future reference or cross-check with the data we have
	  $paypal = new Paypal();
	  $checkoutDetails = $paypal -> request('GetExpressCheckoutDetails', array('TOKEN' => $_GET['token']));

	  // Complete the checkout transaction
	  $requestParams = array(
        'TOKEN' => $_GET['token'],
        'PAYMENTACTION' => 'Sale',
        'PAYERID' => $_GET['PayerID'],
        'PAYMENTREQUEST_0_AMT' => $_SESSION['amount'], // Same amount as in the original request
        'PAYMENTREQUEST_0_CURRENCYCODE' => 'EUR' // Same currency as the original request
    );

    $response = $paypal -> request('DoExpressCheckoutPayment',$requestParams);
    if( is_array($response) && $response['ACK'] == 'Success')
    {
        // Payment successful
        // We'll fetch the transaction ID for internal bookkeeping
        $transactionId = $response['PAYMENTINFO_0_TRANSACTIONID'];


        // now we move the ad to the ads table
        accept_new_ad($_SESSION['id']);
        echo "Success! <br/>";
        echo "<a href='/index.php'>Go back to main page</a>";
    }
    else
    {
        echo "Fail! <br/>";
        echo "There was a problem with your PayPal transaction: <br/>"
            . $response['L_ERRORCODE0'] . " - " . $response['L_LONGMESSAGE0'];
    }
}


?>