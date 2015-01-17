function request($url){
	$response = false;
	try{
		$response = json_decode(file_get_contents($url,false,stream_context_create(array('http' => array('method' => 'GET','header' => 'Content-type: application/x-www-form-urlencoded')))));
	}
	catch(Exception $e){
		$response = false;
	}
	return $response;
}
