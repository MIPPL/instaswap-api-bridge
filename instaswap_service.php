<?php
require "./instaswap_api.php";

$service = $_GET["s"];
$params = $_GET;
unset($params["s"]); 

if ( $service!="" && class_exists($service) )	{
	$sobj = new $service($params );
	if ( $sobj->Validate() )	{
		print $sobj->Run();
	}
	else 	{
		printError( "Service not available");
	}
}
else {
	printError( "Service not found");
}

function printError($error)	{
	print '{"error":"'.$error.'"}';
}
?>
