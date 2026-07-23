<?php
// Set the default timezone
date_default_timezone_set("Asia/Kolkata");

// Display the current date
echo "Current Date: " . date("Y-m-d") . "<br>";

// Display the current time
echo "Current Time: " . date("h:i:s A") . "<br>";

// Display the full date and time
echo "Current Date & Time: " . date("Y-m-d h:i:s A") . "<br>";

// Display day, month, and year separately
echo "Day: " . date("d") . "<br>";
echo "Month: " . date("F") . "<br>";
echo "Year: " . date("Y") . "<br>";
?>