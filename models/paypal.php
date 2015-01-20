<?php


require_once "config.php";

class Paypal
{
	protected $_errors;
	protected $_credentials;
	protected $_endPoint;
	protected $_version;

	public function __construct()
    {
      global $PAYPAL_CREDENTIALS, $PAYPAL_VERSION, $PAYPAL_ENDPOINT;
    	$this->_errors = array();
    	$this->_credentials = $PAYPAL_CREDENTIALS;
    	$this->_endPoint = $PAYPAL_ENDPOINT;
    	$this->_version = $PAYPAL_VERSION;
    }

    /**
     * Make API request
     *
     * @param string $method string API method to request
     * @param array $params Additional request parameters
     * @return array / boolean Response array / boolean false on failure
     */
    public function request($method,$params = array())
    {
    	$this -> _errors = array();
    	if( empty($method) )
    	{ //Check if API method is not empty
    		$this -> _errors = array('API method is missing');
         	echo "API method is missing";
         	return false;
        }

      	//Our request parameters
      	$requestParams = array(
      	   'METHOD' => $method,
      	   'VERSION' => $this -> _version
      	) + $this -> _credentials;

      	//Building our NVP string
      	$request = http_build_query($requestParams + $params);

      	//cURL settings
        global $CACERTFILE;
      	$curlOptions = array(
      	   CURLOPT_URL => $this -> _endPoint,
      	   CURLOPT_VERBOSE => 1,
      	   CURLOPT_SSL_VERIFYPEER => true,
      	   CURLOPT_SSL_VERIFYHOST => 2,
      	   CURLOPT_CAINFO => $CACERTFILE,
      	   CURLOPT_RETURNTRANSFER => 1,
      	   CURLOPT_POST => 1,
      	   CURLOPT_POSTFIELDS => $request
      	);

      	$ch = curl_init();
      	curl_setopt_array($ch,$curlOptions);

      	//Sending our request - $response will hold the API response
      	$response = curl_exec($ch);

      	//Checking for cURL errors
      	if (curl_errno($ch))
      	{
      		$this -> _errors = curl_error($ch);
         	curl_close($ch);
         	var_dump($this->_errors);
         	echo "cURL errors";
         	return false;
         	//Handle errors
        }
        else
        {
         	curl_close($ch);
         	$responseArray = array();
         	parse_str($response, $responseArray); // Break the NVP string to an array
         	return $responseArray;
        }
    }
}


?>