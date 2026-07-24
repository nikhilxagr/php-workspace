<?php
$fruits = array("Apple", "Banana", "Mango");

// OR

$fruits = ["Apple", "Banana", "Mango"];
?>


<!-- 1. Indexed Array

Stores values with numeric indexes. -->

<?php
$colors = ["Red", "Green", "Blue"];

echo $colors[0]; // Red
echo $colors[1]; // Green
echo $colors[2]; // Blue
?>

<!-- Loop through an indexed array:  -->

<?php
$colors = ["Red", "Green", "Blue"];

foreach ($colors as $color) {
    echo $color . "<br>";
}
?>

<!-- 2. Associative Array

Uses named keys instead of numbers. -->

<?php
$student = [ 
    "name" => "Rahul",
    "age" => 20,
    "course" => "BCA"
];

echo $student["name"];
echo $student["course"];
?>

<!-- 3. Multidimensional Array

An array containing other arrays. -->

<?php
$students = [
    ["Rahul", 20],
    ["Priya", 21],
    ["Amit", 22]
];

echo $students[0][0];
echo $students[1][1];

?>

<?php

echo "Welcome to multi dimensional arrays in php <br>";

// Creating a 2 D array

$multiDim = array(array(2,5,7,8),
                array(1,2,3,1),
                array(4,5,0,1));

for ($i = 0; $i < count($multiDim); $i++) {
    for ($j = 0; $j < count($multiDim[$i]); $j++) {
        echo $multiDim[$i][$j] . " ";
    }
    echo "<br>";
}

?>