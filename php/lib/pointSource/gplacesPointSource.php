<?
require_once('../basicHTTPRequest.php');

class gplaces{
	private $API_KEY = "AIzaSyCNAbTuXXz4szIEztN-8gdjQRGPKTF_rYw";
	//gets a route from a location

	private function buildResponse($data){
		$response = [
	        	'error' => "",
	        	'success' => false,
		        'data' => (data === false ? (is_array($data) ? $data : array($data) )  : array())  
		];
	}

	public function getPlaceFromLocation($location,$categories,$radius){

		//check if categories is an array
		$categories = ($categories !== false ? (is_array($categories) ? $categories : array($categories) ) : array());
		if (!isset($location['lat']) || !isset($location['lng']) ){
			return array();
		}

		$location = (object)$location;
		//build a string URL to request data based on a location and all of the categories (use Gmaps API for syntax)
		
		$urlString = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=$location->lat,$location->lng&radius=$radius&key=$this->API_KEY&types=".implode("|", $categories);
		$response = [];

		try{
			$tempResp = json_decode(request($urlString));
		} catch(Exception $e){}

		return $this->formatPlaceJSON($response);
	}

	private function formatPlaceJSON($places){
		$places = is_array($places) ? $places : [$places];
		$formatted = [];
		foreach ($places as $place){
			$formatted[] = [
				"title" => $place->name,
				"image" => $place->icon,
				"time" => [
					"arrive" => 0,
					"depart" => 0,
				],
				"otherInfo" => $place->vicinity,
				"category" => $place->category[0],
				"quality" => $place->rating,
				"location" => [
					"lat" => $place->geometry->location->lat,
					"long" => $place->geometry->location->lon,
				],
				"cost" => $place->price_level,
			];
		}
	}

}




?>
