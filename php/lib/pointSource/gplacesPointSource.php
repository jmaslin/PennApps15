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
		$categories = (categories !== false ? (is_array($categories) ? $categories : array($categories) ) : array());
		if (!isset($location['lat']) || !isset($location['lng']) ){
			return array();
		}

		$location = (object)$location;
		//build a string URL to request data based on a location and all of the categories (use Gmaps API for syntax)
		
		$response = [];
		$urlString = "https://maps.googleapis.com/maps/api/place/radarsearch/json?location=$location->lat,$location->long&radius=$radius&key=$this->API_KEY&types=";

		// $urlString = "https://maps.googleapis.com/maps/api/place/radarsearch/json?location=$location['lat'],$location['long']&radius=$radius&key=$API_KEY&types=";
		foreach($categories as $category){

			try{
				echo $category;	
				$response[$category] = json_decode(request($urlString.$category));
			}
			catch(Exception $e){
				$response[$category] = array();
			}
		}
		return $response;
	}

}




?>
