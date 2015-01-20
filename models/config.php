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
      'USER' => 'admin2_api1.pixels.bg',
      'PWD' => '6LHND3H5K68PTUKA',
      'SIGNATURE' => 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-AeSsxZEYXqRVET-abkewsLp6rxy-',
   );
$PAYPAL_VERSION = '74.0';
$PAYPAL_ENDPOINT = 'https://api-3t.sandbox.paypal.com/nvp';
$PAYPAL_CHECKOUT_URL = 'https://www.sandbox.paypal.com/';
$CACERTFILE = $_SERVER['DOCUMENT_ROOT'] . "static/cacert.pem";
$PRICE_PER_10x10_IN_EUR = 100.0;

?>