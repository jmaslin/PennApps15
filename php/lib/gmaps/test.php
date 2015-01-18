<?
require_once('gmaps.php');

$mapAPI = new gmaps();

$response = $mapAPI->getRouteFromLocations("Philadelphia","California");
echo var_dump($response);

/*
$location = array(
		'lat' => '51.503186',
		'long' => '-0.126446'
	);
$radius = 50;

$categories = array(
		"food" => "food",
		"zoo" => "zoo"
	);

//$places_response = $mapPlacesAPI->getPlaceFromLocation($location,$categories,$radius);
//echo var_dump($places_response);

$stepInt = $mapAPI->getLocationFromRouteTime($response,10000);
$step = $response->routes[0]->legs[0]->steps[$stepInt-1];
//echo var_dump($route->routes[0]->legs[0]);
echo var_dump($mapAPI->decodePolylineToArray($step->polyline->points));
*/
?>
