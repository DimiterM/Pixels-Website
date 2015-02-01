<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "models/paypal.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "models/config.php";
global $PRICE_PER_10x10_IN_EUR, $PAYPAL_RETURN_URL;

session_start();

$amount = $PRICE_PER_10x10_IN_EUR * $_POST['qty'];
$_SESSION['amount'] = $amount;


//Our request parameters
$requestParams = array(
   'RETURNURL' => $PAYPAL_RETURN_URL,
   'CANCELURL' => $PAYPAL_RETURN_URL
);

$orderParams = array(
   'PAYMENTREQUEST_0_AMT' => $amount,
   'PAYMENTREQUEST_0_SHIPPINGAMT' => 0,
   'PAYMENTREQUEST_0_CURRENCYCODE' => "EUR",
   'PAYMENTREQUEST_0_ITEMAMT' => $amount
);

$item = array(
   'L_PAYMENTREQUEST_0_NAME0' => "10x10 Pixels",
   'L_PAYMENTREQUEST_0_DESC0' => "Ad on website",
   'L_PAYMENTREQUEST_0_AMT0' => $PRICE_PER_10x10_IN_EUR,
   'L_PAYMENTREQUEST_0_QTY0' => $_POST['qty']
);

$paypal = new Paypal();
$response = $paypal -> request('SetExpressCheckout', $requestParams + $orderParams + $item);


if(is_array($response) && $response['ACK'] == "Success")
{
    //Request successful
    $token = $response['TOKEN'];
    global $PAYPAL_CHECKOUT_URL;
    header( "Location: " . $PAYPAL_CHECKOUT_URL . "webscr?cmd=_express-checkout&token=" . urlencode($token) );
}


?>