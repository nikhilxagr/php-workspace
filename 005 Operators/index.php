<!-- Learn about operators in PHP. -->


<?php


//  PHP OPERATORS 

// 1. Arithmetic Operators

echo "<br>";
/*

+   Addition
-   Subtraction
*   Multiplication
/   Division
%   Modulus (Remainder)
**  Exponentiation

*/

$a = 10;
$b = 3;

echo "Addition: " . ($a + $b) . PHP_EOL;
echo "<br>";
echo "Subtraction: " . ($a - $b) . PHP_EOL;
echo "<br>";
echo "Multiplication: " . ($a * $b) . PHP_EOL;
echo "<br>";
echo "Division: " . ($a / $b) . PHP_EOL;
echo "<br>";
echo "Modulus: " . ($a % $b) . PHP_EOL;
echo "<br>";
echo "Power: " . ($a ** $b) . PHP_EOL;

echo PHP_EOL;

echo "<br>";

// 2. Assignment Operators

/*
=
+=
-=
*=
/=
%=
.=
*/

$x = 5;

$x += 5;

echo "Assignment Operator\n";
echo $x . PHP_EOL;

echo PHP_EOL;


// 3. Comparison Operators

/*
==
===
!=
!==
>
<
>=
<=
<=>
*/

$a = 10;
$b = "10";

echo "Comparison Operators\n";

var_dump($a == $b);     // true
var_dump($a === $b);    // false

echo PHP_EOL; 

echo "<br>";

// 4. Logical Operators

/*
&&
||
!
xor
*/

$age = 20;
$student = true;

echo "Logical Operators\n";

if ($age >= 18 && $student) {
    echo "Eligible\n";
}

echo PHP_EOL;
echo "<br>";

//  5. Increment & Decrement
 

/*
++
--
*/

$x = 5;

echo "Increment Operators\n";

echo ++$x . PHP_EOL;
echo $x++ . PHP_EOL;
echo $x . PHP_EOL;

echo PHP_EOL;

echo "<br>";


//  6. Bitwise Operators
 

/*
&
|
^
~
<<
>>
*/

echo "Bitwise Operators\n";

echo (5 & 3) . PHP_EOL;
echo (5 | 3) . PHP_EOL;

echo PHP_EOL;
echo "<br>";

//  7. Ternary Operator
 

/*
?:
*/

$age = 17;

$status = ($age >= 18) ? "Adult" : "Minor";

echo $status . PHP_EOL;

echo PHP_EOL;
echo "<br>";


//  Best Practices

/*

 Prefer === over ==
 Use ?? for default values
 Avoid @ error suppression
 Write readable variable names
 Keep code simple

*/

?>