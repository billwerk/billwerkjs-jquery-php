<?php

include 'config.php';
include 'iteroapi.php';

$contractid = $_GET['contractid'];

// Get a default self service portal URL
// from billwerk for a specific contract
$itero = new IteroAPI($clientid, $clientsecret);
$token = $itero->get_selfservice_token($contractid);

if (!$token)
	handle_error(500,"Could not fetch token");
$portalurl = $token->Url; 

?>

<!doctype html>
<html>
	<head>
	    <title>Customer Portal</title>
	</head>
	<body>
		<a href="/customers.php">Back to customer list</a>
		<iframe width="100%" height="2000px" src="<?php echo $portalurl ?>"></iframe>
	</body>
</html>