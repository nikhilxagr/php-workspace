<?php
echo "Welcome to world of Funtions in php";

function processMark($markArr){
    $sum = 0;

    foreach($markArr as $value){
        $sum += $value;
    }
    return $value;

}

$Ram = [34,54,68,98,70,60];
$sumMarks = processMark($Ram);
echo "Total marks scored by rohan out of 600 is $sumMarks";

?>