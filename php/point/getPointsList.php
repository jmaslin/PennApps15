<?
//this is the file that ties it all together. Requests are made based on starting and ending points, and a list of points are returned.
require_once('../lib/gmaps/gmaps.php');
require_once('../lib/gmaps/gmaps.php');

//location
$from = isset($_REQUEST['from']) ? $_REQUEST['from'] : "Philadelphia,PA";
$to = isset($_REQUEST['to']) ? $_REQUEST['to'] : "";

//if false, calc numstops from total time
$numStops = isset($_REQUEST['numstops']) ? $_REQUEST['numstops'] : false;

//preferred categories
$categories = isset($_REQUEST['cat']) ? explode(",",$_REQUEST['cat']) : false;

$mapAPI = new gmaps();

$route = mapAPI->getRouteFromLocations($from,$to);

//if no number of stops is configured, let's stop every 1.75h (6300 s)
if($numStops === false){
	$totalDuration = $route->routes[0]->legs[0]->duration->value;
	$numStops = (int)$totalDuration/6300;
}

if($categories === false){
	//build default list of categories, this is a stub for now

}

?>