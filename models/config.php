<?php

// Picture dimensions
$PIC_WIDTH = 1340;
$PIC_HEIGHT = 750;


// Database connection
$HOST = "localhost"; 
$PORT = 3306;
$DB = "pixelsdb";
$USER = "root";
$PASS = "";


// Directories
$ADS_IMAGES_DIR = $_SERVER['DOCUMENT_ROOT'] . "images/ads/";
$NEWADS_IMAGES_DIR = $_SERVER['DOCUMENT_ROOT'] . "images/newads/";


// Paypal
$PAYPAL_CREDENTIALS = array(
      'USER' => '',
      'PWD' => '',
      'SIGNATURE' => '',
   );
$PAYPAL_VERSION = '74.0';
$PAYPAL_ENDPOINT = 'https://api-3t.sandbox.paypal.com/nvp';
$PAYPAL_CHECKOUT_URL = 'https://www.sandbox.paypal.com/';
$CACERTFILE = $_SERVER['DOCUMENT_ROOT'] . "static/cacert.pem";
$PAYPAL_RETURN_URL = "http://localhost/controllers/paypal_return.php";
$PRICE_PER_10x10_IN_EUR = 100.0;

?>