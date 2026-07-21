<?php
$fruits = array("Apple", "Banana", "Mango");

// OR (recommended)

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