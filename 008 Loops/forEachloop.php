<?php

echo "Learn about the for each loop in php";
echo "<br>";
//syntax

// foreach ($array as $value) {
//     // code to be executed
// }


$colors = array("red", "green", "blue", "yellow");

for ($i = 0; $i < count($colors); $i++) {
    echo $colors[$i] . "<br>";
}

// better way to  do this 
foreach ($colors as $value) {
    echo "$value <br>";
}


?>