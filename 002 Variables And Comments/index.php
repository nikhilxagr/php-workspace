
<?php

echo "Let's learn about variables and comments in PHP.<br>";
echo "<br>";

// This is a single-line comment in PHP
# This is also a single-line comment in PHP

/*
This is a multi-line comment in PHP.
You can write comments that span multiple lines.
*/

// Variables in PHP start with a dollar sign ($) followed by the variable name

$Greeting = "Hello, World!";
$name = "Nihkil Bhai";
$age = 21;
$isStd = true;
$pi = 3.14;

echo $Greeting . "<br>"; 
echo "My name is " . $name . " and I am " . $age . " years old.<br>"; 
echo "The value of pi is " . $pi . ".<br>"; 
echo "Am I a student? " . ($isStd ? "Yes" : "No") . ".<br>";
echo "You can use variables to store different types of data in PHP.<br>";

?>

