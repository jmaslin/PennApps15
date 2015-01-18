<?php
require_once(dirname(__FILE__).'/../basicHTTPRequest.php');

class gplacesPointSource{
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
		if (!isset($location->lat) || !isset($location->lng) ){
			return ["fail" => true];
		}

		$location = (object)$location;
		//build a string URL to request data based on a location and all of the categories (use Gmaps API for syntax)
		
		$urlString = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=$location->lat,$location->lng&radius=$radius&key=$this->API_KEY&types=".implode("|", $categories);
		$response = [];

		try{
			$response = json_decode(request($urlString));
		} catch(Exception $e){}

		if(strcmp($response->status,'OK') !== 0){
			return ["fail" => true];
		}

		$toReturn = $this->formatPlaceJSON($response->results);

		return $toReturn;
	}

	private function formatPlaceJSON($places){
		$places = is_array($places) ? $places : [$places];
		$formatted = [];
		foreach ($places as $place){
			$formatted[] = (object)[
				"title" => $place->name,
				"image" => $place->icon,
				"time" => (object)[
					"arrive" => 0,
					"depart" => 0,
				],
				"otherInfo" => $place->vicinity,
				"category" => $place->category,
				"quality" => $place->rating,
				"location" => [
					"lat" => $place->geometry->location->lat,
					"lng" => $place->geometry->location->lng,
				],
				"cost" => $place->price_level,
			];
		}
		$formatted['fail'] = false;
		return $formatted;
	}

}




?>
