<?php
require "config.php";

abstract class InstaswapRequest  {

    protected $fn;
	protected $params;

	function __construct( $fn, $params )    {
        $this->fn = $fn;
        $this->params = $params;
    }

	abstract function Validate();

    function Run()  {
		$baseUrl = API_URL;
		$path = "/" . $this->fn . "?" . $this->getParams() ;
 		$nonce = round(microtime(true) * 1000);
 		$key = API_KEY;
 		$message = $path . $nonce;

//print ($baseUrl . $path);
 		$ch = curl_init($baseUrl . $path);
 		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
 		curl_setopt($ch, CURLOPT_HTTPHEADER, [
 			'X-INSTASWAP-API-KEY:' . $key,
 			'X-INSTASWAP-API-NONCE:' . $nonce,
 			'X-INSTASWAP-API-MESSAGE:' . $message
 		]);
 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 		$server_output = curl_exec($ch);
 		curl_close ($ch);
 		return $server_output; 
    }

	function getParams()	{
		return http_build_query($this->params);
	}
}

class InstaswapReportAllowedPairs extends InstaswapRequest {

	function __construct( $params )    {
        $this->fn = "report";
        $this->params = array( "allowedPairs" => "" );
    }

	function Validate()	{
		return true;
	}

	function getParams()    {
        return "allowedPairs";
    }
}

class InstaswapTickers extends InstaswapRequest {

    function __construct( $params )    {
        $this->fn = "tickers";
        $this->params = $params;
    }

    function Validate() {
        return count($this->params) == 3 
			&& isset($this->params["giveCoin"])
			&& isset($this->params["getCoin"])
			&& isset($this->params["sendAmount"]);
    }
}

class InstaswapSwap extends InstaswapRequest {

    function __construct( $params )    {
        $this->fn = "swap";
        $this->params = $params;
    }

    function Validate() {
        return count($this->params) >= 5
            && isset($this->params["giveCoin"])
            && isset($this->params["getCoin"])
            && isset($this->params["sendAmount"])
			&& isset($this->params["receiveWallet"])
			&& isset($this->params["refundWallet"])
			//&& isset($this->params["memo"] // optional
		;
    }
}


?>
