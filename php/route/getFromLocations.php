<?
include('../lib/basicHTTPRequest.php');
//gets a route from a location

$fromLoc = isset($_REQUEST['from']) ? $_REQUEST['from'] : false;
$toLoc = isset($_REQUEST['to']) ? $_REQUEST['to'] : false;

$response = [
	'error' => "",
	'success' => false,
	'data' => []
];

//write a function that echoes a json-encoded error array
echo request("google.com");

//write a function to make a GET request to google taking params (to,from)



//return a structure 





?>
