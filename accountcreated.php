<?php

include 'config.php';
include 'iteroapi.php';

//Get event data
$json = json_decode(file_get_contents('php://input'));;
$event = $json->Event;
$contractid = $json->ContractId;

//Exit if received event is not an "account created" event
if ($event != 'AccountCreated')
	handle_error(422,"Not an 'account created' event");

//Fetch contract and customer data from Itero
$itero = new IteroAPI($clientid, $clientsecret);

if (!$itero)
	handle_error(500,"Could not initialise itero");
	
$contract = $itero->get_contract($contractid);
if (!$contract)
	handle_error(500,"Could not fetch contract");
$customer = $itero->get_customer($contract->CustomerId);
if (!$customer)
	handle_error(500,"Could not fetch customer");

//Get mongodb instance
$m = new MongoClient($GLOBALS['mongodb'],array(
		"connect" => TRUE
		));
//$m = new MongoClient();
if (!$m)
	handle_error(500,"Could not open mongodb");

$db = $m->$GLOBALS['dbname'];
if (!$db)
	handle_error(500,"Could not open db");
	
//Store new customer
$customer->contractid = $contractid;
$c_customers = $db->customers;
$c_customers->save($customer);

//Get new id and pass it to Itero as external id  
$ownId =  (string)$customer->_id;
$customer->ExternalCustomerId = $ownId;
$itero->put_customer($customer); 

function handle_error($httpcode,$message)
{
	http_response_code($httpcode);
	exit($message);	
}
?>