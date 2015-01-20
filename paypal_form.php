<?php
$_POST['qty'] = 1;
require_once "models/paypal.php";
require_once "models/config.php";

session_start();

global $PRICE_PER_10x10_IN_EUR;
$amount = $PRICE_PER_10x10_IN_EUR * $_POST['qty'];
$_SESSION['amount'] = $amount;


//Our request parameters
$requestParams = array(
   'RETURNURL' => 'http://localhost/paypal_return.php',
   'CANCELURL' => 'http://localhost/paypal_return.php'
);

$orderParams = array(
   'PAYMENTREQUEST_0_AMT' => $amount,
   'PAYMENTREQUEST_0_SHIPPINGAMT' => 0,
   'PAYMENTREQUEST_0_CURRENCYCODE' => 'EUR',
   'PAYMENTREQUEST_0_ITEMAMT' => $amount
);

$item = array(
   'L_PAYMENTREQUEST_0_NAME0' => '10x10 Pixels',
   'L_PAYMENTREQUEST_0_DESC0' => 'Ad on website',
   'L_PAYMENTREQUEST_0_AMT0' => $PRICE_PER_10x10_IN_EUR,
   'L_PAYMENTREQUEST_0_QTY0' => $_POST['qty']
);

$paypal = new Paypal();
$response = $paypal -> request('SetExpressCheckout', $requestParams + $orderParams + $item);


if(is_array($response) && $response['ACK'] == 'Success')
{
    //Request successful
    $token = $response['TOKEN'];
    global $PAYPAL_CHECKOUT_URL;
    header( 'Location: ' . $PAYPAL_CHECKOUT_URL . 'webscr?cmd=_express-checkout&token=' . urlencode($token) );
}


?>