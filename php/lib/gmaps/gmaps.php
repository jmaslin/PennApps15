<?
require_once('../basicHTTPRequest.php');

class gmaps{
	private $API_KEY = "AIzaSyCNAbTuXXz4szIEztN-8gdjQRGPKTF_rYw";
	//gets a route from a location

	private function buildResponse($data){
		$response = (object)[
	        	'error' => "",
	        	'success' => false,
		        'data' => (data === false ? (is_array($data) ? $data : array($data) )  : array())  
		];
	}

	public function getRouteFromLocations($from,$to){
		$response = false;
		try{
			$response = json_decode(request("https://maps.googleapis.com/maps/api/directions/json?origin=$from&destination=$to&key=$API_KEY"));
		}
		catch(Exception $e){}
		return $response;
	}
	
	public function getRouteLocationFromTime($route,$time){
		//sanity checks that route is less than total route time, etc

		//iterate over each step of the trip in each leg of the trip and add times until 
		$steps = $route->routes[0]->legs[0]->steps;
		$cumTime = 0;

		foreach($steps as $stepKey => $step){
			$nextTime = $steps[$stepKey+1]->duration->value;
			//we can prograss through some more points
			if (($cumTime + $nextTime) <= $time) {
				$cumTime += $step->duration->value;
			}
			//the cumulative time is as close as possible with steps, move on to points
			else{
				//constants for equations and profit
				$points = $this->decodePolylineToArray($step->polyline->points);
				$stepDistance = $step->distance->value;
				$avgVel = $stepDistance/($step->duration->value);
				
				//this will end up with the coords of the stop location
				$stopLoc = $step->start_location;
				$lineIndex = 0;
				$numPoints = count($points);

				$lineDist = $this->distanceBetweenLatLng($stopLoc,$points[$lineIndex]);

				//increase a factor of granularity to points
				while( ($cumTime + ($lineDist / $avgVel)) <= $time && $lineIndex < $numPoints ){
					echo ($cumTime + ($lineDist / $avgVel))." is less than ".$time."\n";
					$stopLoc = $coordPath[$lineIndex];
					$lineIndex++;
					$cumTime += $lineDist/$avgVel;

					$lineDist = $this->distanceBetweenLatLng($stopLoc,$points[$lineIndex]);
				}

				//now we have our closest point, find a stopping point on the line
				//which is between it and the next point
				echo "time: $time , cumTime: $cumTime , lineDist: $lineDist , avgVel: $avgVel \n";
				$timeLeftRatio = ($time-$cumTime)/($lineDist/$avgVel);
				echo "TLR!!: " . $timeLeftRatio;
				$newLat = $stopLoc->lat + (($points[$lineIndex]->lat - $stopLoc->lat)*$timeLeftRatio);
				$newLng = $stopLoc->lng + (($points[$lineIndex]->lng - $stopLoc->lng)*$timeLeftRatio);
				echo "Stoploc: " . var_dump($stopLoc) . "\nNewStopLoc: " . $newLat . "," . $newLng . "\nendpoints: ";

				return (object)[ 'lat' => $newLat, 'lng' => $newLng ];
			}
		}
	}
	
	public function decodePolylineToArray($encoded){
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

	private function distanceBetweenLatLng($coord1,$coord2){
		$earthRadius = 20890566; // ft
		$lat1 = deg2rad($coord1->lat);
		$lat2 = deg2rad($coord2->lat);
		$latDiff = deg2rad($lat2-$lat1);
		$lonDiff = deg2rad($coord2->lng-$coord1->lng);

		$a = sin($latDiff/2) * sin($latDiff/2) + cos($lat1) * cos($lat2) * sin($lonDiff/2) * sin($lonDiff/2);
		$c = $earthRadius * 2 * atan2(sqrt($a), sqrt(1-$a));

		return $c;
	}
}

?>
