<?php

echo "<h2>PHP Array </h2>";

// 1. Indexed Array

echo "<h3>1. Indexed Array</h3>";
$fruits = array("Apple", "Banana", "Mango", "Orange");

echo "First Fruit: " . $fruits[0] . "<br>";
echo "Second Fruit: " . $fruits[1] . "<br>";

echo "<b>All Fruits:</b><br>";
foreach ($fruits as $fruit) {
    echo $fruit . "<br>";
}

// 2. Associative Array

echo "<h3>2. Associative Array</h3>";
$student = array(
    "Name" => "Rahul",
    "Age" => 20,
    "Course" => "BCA"
);

echo "Name: " . $student["Name"] . "<br>";
echo "Age: " . $student["Age"] . "<br>";
echo "Course: " . $student["Course"] . "<br>";

// 3. Multidimensional Array

echo "<h3>3. Multidimensional Array</h3>";
$students = array(
    array("Rahul", 20, "BCA"),
    array("Priya", 21, "BSc"),
    array("Amit", 22, "BTech")
);

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Name</th><th>Age</th><th>Course</th></tr>";

foreach ($students as $row) {
    echo "<tr>";
    echo "<td>" . $row[0] . "</td>";
    echo "<td>" . $row[1] . "</td>";
    echo "<td>" . $row[2] . "</td>";
    echo "</tr>";
}

echo "</table>";
?>

// Array Methods

$languages = array("PHP", "JavaScript", "Python", "Java");

echo "<h3>Array Methods</h3>";

echo "Number of elements: " . count($languages) . "<br>";

echo "Is 'Python' in the array? " . (in_array("Python", $languages) ? "Yes" : "No") . "<br>";

sort($languages);
echo "Sorted Array:<br>";

foreach ($languages as $language) {
    echo $language . "<br>";
}