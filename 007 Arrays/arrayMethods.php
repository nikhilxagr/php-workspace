<?php

echo "<h1>PHP Array Methods </h1><hr>";

// Original Array

$fruits = ["Apple", "Banana", "Mango"];
echo "<h3>Original Array</h3>";
print_r($fruits);
echo "<hr>";

/* 1. count() */

echo "<h3>1. count()</h3>";
echo "Total Elements: " . count($fruits);
echo "<hr>";

/* 2. array_push() */

echo "<h3>2. array_push()</h3>";
array_push($fruits, "Orange", "Grapes");
print_r($fruits);
echo "<hr>";

/* 3. array_pop() */

echo "<h3>3. array_pop()</h3>";
array_pop($fruits);
print_r($fruits);
echo "<hr>";

/* 4. array_unshift() */

echo "<h3>4. array_unshift()</h3>";
array_unshift($fruits, "Pineapple");
print_r($fruits);
echo "<hr>";

/* 5. array_shift() */

echo "<h3>5. array_shift()</h3>";
array_shift($fruits);
print_r($fruits);
echo "<hr>";

/* 6. sort() */

echo "<h3>6. sort()</h3>";
sort($fruits);
print_r($fruits);
echo "<hr>";

/* 7. rsort() */

echo "<h3>7. rsort()</h3>";
rsort($fruits);
print_r($fruits);
echo "<hr>";

/* 8. in_array() */

echo "<h3>8. in_array()</h3>";
if (in_array("Apple", $fruits)) {
    echo "Apple Found";
} else {
    echo "Apple Not Found";
}
echo "<hr>";

/* 9. array_merge() */

echo "<h3>9. array_merge()</h3>";
$moreFruits = ["Kiwi", "Papaya"];
$merged = array_merge($fruits, $moreFruits);
print_r($merged);
echo "<hr>";

/* 10. array_reverse() */

echo "<h3>10. array_reverse()</h3>";
print_r(array_reverse($merged));
echo "<hr>";

/* 11. array_unique() */

echo "<h3>11. array_unique()</h3>";
$duplicate = ["Apple", "Banana", "Apple", "Orange", "Banana"];
print_r(array_unique($duplicate));
echo "<hr>";

/* 12. array_keys() */

echo "<h3>12. array_keys()</h3>";
$student = [
    "name" => "Rahul",
    "age" => 20,
    "course" => "BCA"
];
print_r(array_keys($student));
echo "<hr>";

/* 13. array_values() */

echo "<h3>13. array_values()</h3>";
print_r(array_values($student));
echo "<hr>";

/* 14. array_search() */

echo "<h3>14. array_search()</h3>";
echo "Apple Index: " . array_search("Apple", $merged);
echo "<hr>";


/* 15. foreach Loop */

echo "<h3>15. foreach Loop</h3>";
foreach ($merged as $fruit) {
    echo $fruit . "<br>";
}
echo "<hr>";

/* 16. array_slice() */

echo "<h3>16. array_slice()</h3>";
print_r(array_slice($merged, 1, 3));
echo "<hr>";

/* 17. array_sum() */
echo "<h3>17. array_sum()</h3>";
$numbers = [10, 20, 30, 40];
echo array_sum($numbers);
echo "<hr>";

/* 18. max() */
echo "<h3>18. max()</h3>";
echo max($numbers);
echo "<hr>";

/* 19. min() */
echo "<h3>19. min()</h3>";
echo min($numbers);
echo "<hr>";

/* 20. shuffle() */
echo "<h3>20. shuffle()</h3>";
shuffle($numbers);
print_r($numbers);
echo "<hr>";