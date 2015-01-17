<?
class car{
	function drive(){
		echo "vroom";
	}
}

$chrysler = [
	"wheels" => 2,
	"motorsize" => 500,
	"brand" => "Chrysler"
];
$maseratti = [
	"wheels" => 4,
	"motorsize" => 1500,
	"brand" => ["maseratti","chrysler"]
];

$car = [$chrysler,$maseratti];

echo json_encode($car);

echo "\n"


?>
