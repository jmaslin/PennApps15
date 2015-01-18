<?
$places = array(
	0 => [
		"title" => "philadelphia zoo",
		"image" => "http://i.imgur.com/vuXlPBy.png",
		"time" => [
			"arrive" => 1,
			"depart" => 2,
		],
		"otherInfo" => "cool zoo",
		"category" => "zoo",
		"quality" => 3,
		"location" => [
			"lat" => 40.09011,
			"long" => -75.40746,
		],
		"cost" => 1,

	],
);

echo json_encode($places);
echo "\n";
?>