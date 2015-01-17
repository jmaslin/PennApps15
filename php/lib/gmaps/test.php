<?
require_once('gmaps.php');
require_once('gplaces.php');

$mapAPI = new gmaps();
$mapPlacesAPI = new gplaces();

$response = $mapAPI->getRouteFromLocations("Philadelphia","California");
//echo var_dump($response);


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
$mapAPI->getLocationFromRouteTime($response,10000);

?>
