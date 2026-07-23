<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Break and Continue</title>
</head>
<body>
    <h1>Break and Continue in PHP</h1>
    <h2>Break Example</h2>

<?php

echo "<h3>Program for Break Statement </h3>";

for($i=1;$i<=10;$i++)
    {
        if($i==6)
    {
        break;
    }

    echo $i."<br>";
}

echo "<hr>";

echo "<h3> Program for Continue</h3>";

for($i=1;$i<=10;$i++)
{
    if($i==6)
    {
        continue;
    }

    echo $i."<br>";
}

?>
    
</body>
</html>