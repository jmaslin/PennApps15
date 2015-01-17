<?
require_once('../basicHTTPRequest.php');

class gmaps{
	private $API_KEY = "AIzaSyDRX-KmL574LOnX3fr58DPkOTwjvxvgJVQ";

	//gets a route from a location

	private buildResponse($data){
		$response = [
	        	'error' => "",
	        	'success' => false,
		        'data' => data == false ? (is_array($data) ? $data : [$data] )  : [] ; 
		];
	}

	public getRouteFromLocations($from,$to){
		$response = json_decode(request("https://maps.googleapis.com/maps/api/directions/json?origin=$from&destination=$to&key=$API_KEY"););
		return $response;
	}

}




?>
