<?	
require_once('../lib/gmaps/gmaps.php');
//gets a route from a location

$fromLoc = isset($_REQUEST['from']) ? $_REQUEST['from'] : 'Philadelphia';;
$toLoc = isset($_REQUEST['to']) ? $_REQUEST['to'] : 'California';

$response = (Object)[
	'error' => "",
	'success' => false,
	'data' => []
];

//write a function that echoes a json-encoded error array
$response->data[] = json_decode( request("https://maps.googleapis.com/maps/api/directions/json?origin=$toLoc&destination=$fromLoc&key=$API_KEY"));
echo var_dump( $response );


//write a function to make a GET request to google taking params (to,from)



//return a structure 





?>
