<?
//this is the file that ties it all together. Requests are made based on starting and ending points, and a list of points are returned.
require_once('../lib/gmaps/gmaps.php');
require_once('../lib/pointsource/gplacesPointSource.php');

//location
$from = isset($_REQUEST['from']) ? $_REQUEST['from'] : "Philadelphia,PA";
$to = isset($_REQUEST['to']) ? $_REQUEST['to'] : "Pittsburgh,PA";

//if false, calc numstops from total time
$numStops = isset($_REQUEST['numstops']) ? $_REQUEST['numstops'] : false;

//preferred categories
$categories = isset($_REQUEST['cat']) ? explode(",",$_REQUEST['cat']) : false;

$mapAPI = new gmaps();
$placeAPI = new gplacesPointSource();

//get a route
$route = mapAPI->getRouteFromLocations($from,$to);

//if no number of stops is configured, let's stop every 1.75h (6300 s)
if($numStops === false){
	$totalDuration = $route->routes[0]->legs[0]->duration->value;
	$numStops = (int)$totalDuration/6300;
}

//build default list of categories, this is a stub for now
if($categories === false){
	$categories = ['zoo','gas_station','lodging'];
}

//determine some points to stop at
$stoppingCoords = [];
$stoppingPoints = [[
	"title" => "Starting Point",
	"image" => '',
	"time" => [
		"arrive" => 0,
		"depart" => 0,
	],
	"otherInfo" => "Your starting point!",
	"category" => '',
	"quality" => 5,
	"location" => [
		"lat" => $route->routes[0]->legs[0]->start_location->lat,
		"long" => $route->routes[0]->legs[0]->start_location->lon,
	],
	"cost" => 0,
]];
$tempCoords = [];

for ($i=0; $i < $numStops ; $i++ ) { 
	$time = (int)($totalDuration/$numstops)*$i+(60*$i);

	$tempCoords = $mapAPI->getRouteLocationFromTime($route,$time);
	$stoppingCoords[] = $tempCoords;

	$stoppingPoints[] = ($placeAPI->getPlaceFromLocation($tempCoords,$categories,2000))[0];
	$stoppingPoints[$i]->time->arrive = $time;
	$stoppingPoints[$i]->time->arrive = $time+60;
}

$stoppingPoints[] = [
	"title" => "Destination",
	"image" => '',
	"time" => [
		"arrive" => 0,
		"depart" => 0,
	],
	"otherInfo" => "Your destination!",
	"category" => '',
	"quality" => 5,
	"location" => [
		"lat" => $route->routes[0]->legs[0]->start_location->lat,
		"long" => $route->routes[0]->legs[0]->start_location->lon,
	],
	"cost" => 0,
];


?>