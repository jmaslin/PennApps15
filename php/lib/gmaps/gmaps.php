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
		$bestStep = false;

		foreach($steps as $stepKey => $step){
			$nextTime = $steps[$stepKey+1]->duration->value;
			$cumTime += $step->duration->value;


			if(abs($cumTime - $time)  < abs(($cumTime + $nextTime) - $time)){
				$bestStep = $step;
				break;
			}
		}
		if($bestStep){
			$offset = 0;
			while(){

			}
			$stopRatio = (()/$bestStep->duration->value);
		}
	}


	function decodePolylineToArray($encoded){
		$length = strlen($encoded);
		$index = 0;
		$points = array();
		$lat = 0;
		$lng = 0;

		while ($index < $length){
			$b = 0;
			$shift = 0;
			$result = 0;
			do{
				$b = ord(substr($encoded, $index++)) - 63;
				$result |= ($b & 0x1f) << $shift;
				$shift += 5;
			} while ($b >= 0x20);

			$dlat = (($result & 1) ? ~($result >> 1) : ($result >> 1));
			$lat += $dlat;
			$shift = 0;
			$result = 0;
			
			do{
				$b = ord(substr($encoded, $index++)) - 63;
				$result |= ($b & 0x1f) << $shift;
				$shift += 5;
			} while ($b >= 0x20);

			$dlng = (($result & 1) ? ~($result >> 1) : ($result >> 1));
			$lng += $dlng;

			$points[] = array($lat * 1e-5, $lng * 1e-5);
		}
		return $points;
	}
}

?>
