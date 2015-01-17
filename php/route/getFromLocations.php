<?
require_once('../lib/gmaps.php');
//gets a route from a location

$fromLoc = isset($_REQUEST['from']) ? $_REQUEST['from'] : 'Philadelphia';;
$toLoc = isset($_REQUEST['to']) ? $_REQUEST['to'] : 'New York';

$response = [
	'error' => "",
	'success' => false,
	'data' => []
];

//write a function that echoes a json-encoded error array
$response = request("https://maps.googleapis.com/maps/api/directions/json?origin=$toLoc&destination=$fromLoc&key=$API_KEY");
echo $response;

//write a function to make a GET request to google taking params (to,from)



//return a structure 





?>
