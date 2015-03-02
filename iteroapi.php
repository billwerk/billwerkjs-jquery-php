<?php

class IteroAPI {
	static $oAuth = "https://sandbox.pactas.com/oauth/token";
	static $iteroBase = "https://sandbox.pactas.com/api/v1/";
	static $contractResource = "contracts/";
	static $customerResource = "customers/";
	
	//Authorization string
	static $auth;
		
	function __construct($clientid,$secret)
	{
		//Get oauth token to access Itero API
		$verbose = fopen('php://stderr', 'w');		
		$curl = curl_init();
		$data = http_build_query(array(
				'grant_type' => 'client_credentials'
		));
		curl_setopt_array($curl, array(
			CURLOPT_URL => self::$oAuth,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
			CURLOPT_USERPWD => "$clientid:$secret",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_VERBOSE => true,
			CURLOPT_STDERR => $verbose)
		);
		$response = curl_exec($curl);

		$this->exit_on_error($curl);
		
		$token = json_decode($response); 
		self::$auth = "Authorization: Bearer ".$token->access_token;
	}
	
	// Fetch contract by id from Itero 
	public function get_contract($contractId) {
		return $this->get(self::$contractResource.$contractId);		
	}
	
	// Fetch customer by id from Itero 
	public function get_customer($customerId) {
		return $this->get(self::$customerResource.$customerId);
	}
	
	// Update customer data in Itero
	public function put_customer($customer) {
		return $this->put(self::$customerResource.$customer->Id,$customer);
	}	
	
	public function get_selfservice_token($contractId) {
		return $this->get(self::$contractResource.$contractId."/selfServiceToken");
	}
	
	// Itero API GET request 
	private function get($resource)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(self::$auth));
		$response = $this->request($curl,$resource);
		curl_close($curl);
		return json_decode($response);
	}
	
	// Itero API PUT request 
	private function put($resource, $data)
	{
		$json = json_encode($data);
		$curl = curl_init();
		curl_setopt_array($curl, array(
				CURLOPT_HTTPHEADER => array(
						'Content-Type: application/json',
						'Content-Length: ' . strlen($json),
						self::$auth),
				CURLOPT_POSTFIELDS => $json,
				CURLOPT_CUSTOMREQUEST => "PUT")
		);
		$response = $this->request($curl,$resource);
		curl_close($curl);
		return json_decode($response);
	}

	// Execute Itero API request 
	private function request($curl,$resource)
	{
		$verbose = fopen('php://stderr', 'w');
		curl_setopt_array($curl, array(
				CURLOPT_URL => self::$iteroBase.$resource,
				CURLOPT_HTTPAUTH => CURLAUTH_ANY,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_VERBOSE => true,
				CURLOPT_STDERR => $verbose));
		$response = curl_exec($curl);
		$this->exit_on_error($curl);
		return $response;
	}
	
	// Exit if Http response code is not 200
	private function exit_on_error($curl)
	{
		$responseInfo = curl_getinfo($curl);
		$httpResponseCode = $responseInfo['http_code'];
		if ($httpResponseCode!=200)
		{
			http_response_code(422);
			exit;
		}
	}
}

?>
