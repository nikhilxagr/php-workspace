<?php

echo " welcome to scope of variable in php <br>";

function test() {
    $x = 10; // local variable
    echo "The value of x inside the function is: " . $x . "<br>";
}

$a = 90; // global variable
echo "The value of a outside the function is: " . $a . "<br>";
test();



function printValue(){

    // $a = 97; // Local Variable
    global $a, $b; // Give me the access to this global variable
    $a = 100;
    $b = 200;

    echo "<br>The value of your variable a is $a and b is $b";
    echo $a;
    printValue();

    echo "<br>The value of your variable a is $a and b is $b";
}

?>