<?php
echo "Welcome to the Date Function Game !<br>";

$date = date("d S F Y h:i:s A");
echo "Current Date and Time: " . $date . "<br>";


// Prints something like: Monday 8th of August 2005 03:12:46 PM
echo date('1 js \of FY h:i:s A');
$year= date("y")."<br>";
echo "Copyright $year All rights reserved <br>";


echo "Let's explore different date formats:<br>";


// Example 1
echo "Example 1 (Y-m-d): " . date("Y-m-d") . "<br>";

// Example 2
echo "Example 2 (l, F d, Y): " . date("l, F d, Y") . "<br>";

// Example 3
echo "Example 3 (d/m/Y h:i A): " . date("d/m/Y h:i A") . "<br>";

// Example 4
echo "Example 4 (M d, Y): " . date("M d, Y") . "<br>";

// Example 5
echo "Example 5 (jS F Y, l): " . date("jS F Y, l") . "<br>";


?>