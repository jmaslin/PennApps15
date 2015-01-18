<?php
function request($url){
	$url = $url;
	$response = false;
	try{
		$response = file_get_contents($url,false,stream_context_create(array('http' => array('method' => 'GET','header' => 'Content-type: application/x-www-form-urlencoded'))));
	}
	catch(Exception $e){}
	return $response;
}
?>
