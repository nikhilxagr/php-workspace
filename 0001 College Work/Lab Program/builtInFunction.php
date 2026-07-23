<!-- Built-in Functions -->

<?php

echo "<h2>PHP Built-in Functions</h2>";

$str = "Hello PHP";

echo "Original String: $str <br>";
echo "Length: " . strlen($str) . "<br>";
echo "Uppercase: " . strtoupper($str) . "<br>";
echo "Lowercase: " . strtolower($str) . "<br>";
echo "Replace: " . str_replace("PHP", "World", $str) . "<br>";
echo "Reverse: " . strrev($str) . "<br><br>";

// Math Functions
$num = 25.7;

echo "Square Root: " . sqrt($num) . "<br>";
echo "Power: " . pow(2,5) . "<br>";
echo "Random Number: " . rand(1,100) . "<br>";
echo "Absolute Value: " . abs(-45) . "<br>";
echo "Maximum: " . max(10,20,30) . "<br>";
echo "Minimum: " . min(10,20,30) . "<br>";

?>