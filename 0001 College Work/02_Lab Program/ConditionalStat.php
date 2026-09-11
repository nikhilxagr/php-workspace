<!-- If and Nested If Statements -->

<?php

$age = 20;
$marks = 85;

if($age >= 18)
{
    echo "Eligible by Age<br>";

    if($marks >= 60)
    {
        echo "Eligible by Marks";
    }
    else
    {
        echo "Marks are too low";
    }
}
else
{
    echo "Not Eligible";
}

?>