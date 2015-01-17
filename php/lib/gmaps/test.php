<?
require_once('gmaps.php');

$mapAPI = new gmaps();

$response = $mapAPI->getRouteFromLocations("Philadelphia","California");
echo "blaH";
echo var_dump($response);

?>
