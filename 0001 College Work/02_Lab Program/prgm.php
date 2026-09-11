<?php

echo "<h2>Array Programs</h2>";

$arr = array(10, 20, 30, 40, 50);

echo "Array Elements:<br>";

foreach($arr as $value){
    echo $value . "<br>";
}

?>

<?php 

echo "<h2>Associative Array Programs</h2>";

$Arr2 = array("Name" => "Nikhil", "Age" => 20, "City" => "Lucknow");

foreach($Arr2 as $key => $value){
    echo $key . ": " . $value . "<br>";
}

?>