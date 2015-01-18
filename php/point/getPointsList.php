<?php
//this is the file that ties it all together. Requests are made based on starting and ending points, and a list of points are returned.
require_once(dirname(__FILE__).'/../lib/gmaps/gmaps.php');
require_once(dirname(__FILE__).'/../lib/pointSource/gplacesPointSource.php');

//location
$from = isset($_REQUEST['from']) ? $_REQUEST['from'] : "Philadelphia,PA";
$to = isset($_REQUEST['to']) ? $_REQUEST['to'] : "Charleston,SC";

//if false, calc numstops from total time
$numStops = isset($_REQUEST['numstops']) ? $_REQUEST['numstops'] : false;

//preferred categories
$categories = isset($_REQUEST['cat']) ? explode(",",$_REQUEST['cat']) : false;

$mapAPI = new gmaps();
$placeAPI = new gplacesPointSource();

//get a route
$route = $mapAPI->getRouteFromLocations($from,$to);

//if no number of stops is configured, let's stop every 1.75h (6300 s)
if($numStops === false){
	$totalDuration = $route->routes[0]->legs[0]->duration->value;
	$numStops = $totalDuration > 12600 ? (int)$totalDuration/6300 : 2;
}

//build default list of categories, this is a stub for now
if($categories === false){
	$categories = ['zoo','gas_station','lodging'];
}

//determine some points to stop at
$stoppingCoords = [];
$stoppingPoints = [(object)[
	"title" => "Starting Point",
	"image" => '',
	"time" => [
		"arrive" => 0,
		"depart" => 0,
	],
	"otherInfo" => "Your starting point!",
	"category" => '',
	"quality" => 5,
	"location" => (object)[
		"lat" => $route->routes[0]->legs[0]->start_location->lat,
		"lng" => $route->routes[0]->legs[0]->start_location->lng,
	],
	"cost" => 0,
]];
$tempCoords = [];
$tempStops = false;

for ($i=1; $i <= $numStops ; $i++ ) {
	$time = (int)($totalDuration/$numStops)*$i+(60*$i);

	$tempCoords = $mapAPI->getRouteLocationFromTime($route,$time);
	$stoppingCoords[] = $tempCoords;

	$tempStops = ($placeAPI->getPlaceFromLocation($tempCoords,$categories,2000));

	if (!empty($tempStops[0])){
		$tempStops[0]->time->arrive = $time;
		$tempStops[0]->time->depart = $time+60;
		$stoppingPoints[] = $tempStops[0];
	}
}


$stoppingPoints[] = (object)[
	"title" => "Destination",
	"image" => '',
	"time" => [
		"arrive" => $route->routes[0]->legs[0]->duration->value,
		"depart" => 0,
	],
	"otherInfo" => "Your destination!",
	"category" => '',
	"quality" => 5,
	"location" => (object)[
		"lat" => $route->routes[0]->legs[0]->end_location->lat,
		"lng" => $route->routes[0]->legs[0]->end_location->lng,
	],
	"cost" => 0,
];

echo json_encode($stoppingPoints);

?>