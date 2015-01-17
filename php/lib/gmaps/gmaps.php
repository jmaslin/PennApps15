<?
require_once('../basicHTTPRequest.php');

class gmaps{
	private $API_KEY = "AIzaSyCNAbTuXXz4szIEztN-8gdjQRGPKTF_rYw";
	//gets a route from a location

	private function buildResponse($data){
		$response = [
	        	'error' => "",
	        	'success' => false,
		        'data' => (data === false ? (is_array($data) ? $data : array($data) )  : array())  
		];
	}

	public function getRouteFromLocations($from,$to){
		$response = json_decode(request("https://maps.googleapis.com/maps/api/directions/json?origin=$from&destination=$to&key=$API_KEY"));
		return $response;
	}
	
	public function getLocationFromRouteTime($route,$time){
		//sanity checks that route is less than total route time, etc


		//iterate over each step of the trip in each leg of the trip and add times until 
		$steps = $route->routes[0]->legs[0]->steps;
		$cumTime = 0;
		foreach($steps as $stepKey => $step){
			$nextTime = $steps[$stepKey+1]->duration->value;
			$cumTime += $step->duration->value;


			if(abs($cumTime - $time)  < abs(($cumTime + $nextTime) - $time)){
				echo "index of step at time $time is $stepKey\n";
				break;
			} else {
				echo "Whateves bro";
			}
		}
	}
}




?>
