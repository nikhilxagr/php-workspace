<?php

echo "Learn about the for loops in php";

// for(initialization; condition; increment/decrement)

for ($i=0; $i < 10; $i++) {
    echo "Count is: ";
    echo $i + 1;
    echo "<br>";
}

for ($i = 2; $i <= 10; $i += 2) {
    echo $i . "<br>";
}

// Count down from 5 to 1
for ($i = 5; $i >= 1; $i--) {
    echo $i . "<br>";
}

// Calculate the sum of numbers from 1 to 5

$sum = 0;

for ($i = 1; $i <= 5; $i++) {
    $sum += $i;
}

echo "Sum = " . $sum;

?>