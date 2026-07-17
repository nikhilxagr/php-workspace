<!-- Learning Data Types in PHP -->

<!--

1.string
2. integer
3. float
4. boolean
5. array
6. object
7. null

-->

<!-- String: A string is a sequence of characters, like "Hello, World!" or 'PHP is great!'. -->

<?php

$name = "Nikhil";
$friends = 'Saurabh';

echo "My name is $name and my friend is $friends <br>";

#Integer: An integer is a whole number, like 42 or -7.

$age = -21;
echo "I am $age years old. <br>";

#Float: A float is a number with a decimal point, like 3.14 or -0.001.

$price = 20.99;
echo "The price is $price rupees. <br>";


#Boolean: A boolean can only have two values: true or false.

$is_logged_in = true;
$friend_logged_in = false;
echo $is_logged_in ,"<br>";
echo $friend_logged_in ,"<br>";

// object: An object is an instance of a class, which can have properties and methods.

class Person {
    public $name;
    public $age;

    public function __construct($name, $age) {
        $this->name = $name;
        $this->age = $age;
    }
}

$person = new Person("Rahul", 30);
echo "Name: " . $person->name . ", Age: " . $person->age . "<br>";

// array: An array is a collection of values, which can be of different types.

$fruits = array("apple", "banana", "orange");
echo "I like " . $fruits[0] . ", " . $fruits[1] . ", and " . $fruits[2] . ".<br>";


// null: The null data type represents a variable with no value.

$empty_variable = null;
echo "The value of the empty variable is: ";
var_dump($empty_variable);

?>
