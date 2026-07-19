<?php

declare(strict_types=1);

/**
 * =============================================================================
 *                           PHP CONDITIONAL STATEMENTS
 * =============================================================================
 *
 * File Name:
 *      05_conditional_statements.php
 *
 * Description:
 *      This file explains Conditional Statements in PHP with executable
 *      examples. Conditional statements allow your program to make decisions
 *      based on different conditions.
 *
 * PHP Version:
 *      PHP 8.x
 *
 * Topics Covered:
 *      1. if Statement
 *      2. if...else Statement
 *      3. if...elseif...else Statement
 *      4. Nested if
 *      5. switch Statement
 *      6. match Expression (PHP 8+)
 *      7. Ternary Operator
 *      8. Null Coalescing Operator
 *
 * =============================================================================
 * WHAT IS A CONDITIONAL STATEMENT?
 * =============================================================================
 *
 * A conditional statement allows a program to execute different blocks of
 * code depending on whether a condition is TRUE or FALSE.
 *
 * Think of it like making a decision in real life.
 *
 * Example:
 *
 *      If it is raining
 *          Take an umbrella
 *      Otherwise
 *          Wear sunglasses
 *
 * The same idea is used in programming.
 *
 * =============================================================================
 * COMPARISON OPERATORS
 * =============================================================================
 *
 * ==   Equal
 * ===  Identical (value + type)
 * !=   Not Equal
 * !==  Not Identical
 * >    Greater Than
 * <    Less Than
 * >=   Greater Than or Equal
 * <=   Less Than or Equal
 *
 * Logical Operators
 *
 * &&   AND
 * ||   OR
 * !    NOT
 *
 * =============================================================================
 */

echo "\n==============================\n";
echo "Example 1 : if Statement\n";
echo "==============================\n";

/*
The if statement executes its block only when
the condition is TRUE.
*/

// Store user's age.
$age = 20;

// Check if age is greater than or equal to 18.
if ($age >= 18) {
    // This line executes because the condition is true.
    echo "You are eligible to vote.\n";
}

/*
Expected Output

You are eligible to vote.

*/

echo "\n==============================\n";
echo "Example 2 : if...else Statement\n";
echo "==============================\n";

// Store marks.
$marks = 45;

// Check if marks are greater than or equal to 50.
if ($marks >= 50) {
    echo "Result : Pass\n";
} else {
    // Executes because condition is false.
    echo "Result : Fail\n";
}

/*
Expected Output

Result : Fail

*/

echo "\n==============================\n";
echo "Example 3 : if...elseif...else\n";
echo "==============================\n";

// Store percentage.
$percentage = 82;

// Determine grade.
if ($percentage >= 90) {
    echo "Grade : A+\n";
} elseif ($percentage >= 75) {
    echo "Grade : A\n";
} elseif ($percentage >= 60) {
    echo "Grade : B\n";
} elseif ($percentage >= 40) {
    echo "Grade : C\n";
} else {
    echo "Grade : Fail\n";
}

/*
Expected Output

Grade : A

*/

echo "\n==============================\n";
echo "Example 4 : Nested if\n";
echo "==============================\n";

// Store age.
$age = 22;

// Citizenship status.
$isCitizen = true;

// First condition.
if ($age >= 18) {

    // Second condition.
    if ($isCitizen) {
        echo "Eligible to vote.\n";
    } else {
        echo "Citizenship required.\n";
    }

} else {
    echo "Not eligible.\n";
}

/*
Expected Output

Eligible to vote.

*/

echo "\n==============================\n";
echo "Example 5 : switch Statement\n";
echo "==============================\n";

// Store day number.
$day = 3;

// Execute matching case.
switch ($day) {

    case 1:
        echo "Monday\n";
        break;

    case 2:
        echo "Tuesday\n";
        break;

    case 3:
        echo "Wednesday\n";
        break;

    default:
        echo "Invalid Day\n";
}

/*
Expected Output

Wednesday

*/

echo "\n==============================\n";
echo "Example 6 : match Expression (PHP 8+)\n";
echo "==============================\n";

/*
match is introduced in PHP 8.

Advantages:

✔ No break keyword
✔ Strict comparison (===)
✔ Returns a value
*/

$day = 5;

$dayName = match ($day) {
    1 => "Monday",
    2 => "Tuesday",
    3 => "Wednesday",
    4 => "Thursday",
    5 => "Friday",
    6 => "Saturday",
    7 => "Sunday",
    default => "Invalid Day",
};

echo $dayName . PHP_EOL;

/*
Expected Output

Friday

*/

echo "\n==============================\n";
echo "Example 7 : Ternary Operator\n";
echo "==============================\n";

/*
Syntax

condition ? trueValue : falseValue;

*/

$age = 16;

// Short form of if...else.
$message = ($age >= 18)
    ? "Adult"
    : "Minor";

echo $message . PHP_EOL;

/*
Expected Output

Minor

*/

echo "\n==============================\n";
echo "Example 8 : Null Coalescing Operator\n";
echo "==============================\n";

/*
Introduced in PHP 7.

Returns the first value that exists and is not null.
*/

$username = null;

// If username is null, use Guest.
echo $username ?? "Guest";
echo PHP_EOL;

/*
Expected Output

Guest

*/

/**
 * =============================================================================
 * BEST PRACTICES
 * =============================================================================
 *
 * ✔ Keep conditions simple and readable.
 *
 * ✔ Use === instead of == whenever possible.
 *
 * ✔ Use match in PHP 8+ for cleaner code.
 *
 * ✔ Avoid deeply nested if statements.
 *
 * ✔ Use descriptive variable names.
 *
 * =============================================================================
 * COMMON MISTAKES
 * =============================================================================
 *
 * ❌ Using = instead of ==
 *
 * Wrong:
 *
 *      if ($age = 18)
 *
 * Correct:
 *
 *      if ($age == 18)
 *
 * Better:
 *
 *      if ($age === 18)
 *
 * ------------------------------------------------------------
 *
 * ❌ Forgetting break in switch
 *
 * Without break, execution continues to the next case.
 *
 * ------------------------------------------------------------
 *
 * ❌ Comparing different data types unintentionally
 *
 * == performs type juggling.
 *
 * === checks both value and type.
 *
 * =============================================================================
 * PRACTICE EXERCISE
 * =============================================================================
 *
 * 1. Store your age in a variable.
 *
 * 2. Print:
 *
 *      Child
 *      Teenager
 *      Adult
 *      Senior
 *
 * using if...elseif...else.
 *
 * =============================================================================
 * CHALLENGE EXERCISE
 * =============================================================================
 *
 * Build a simple calculator using switch.
 *
 * Input:
 *
 *      Number 1
 *      Number 2
 *      Operator (+, -, *, /)
 *
 * Print the calculated result.
 *
 * Bonus:
 *
 * Rewrite the same calculator using match.
 *
 * =============================================================================
 * SUMMARY
 * =============================================================================
 *
 * ✔ if executes code when a condition is true.
 *
 * ✔ if...else chooses between two blocks.
 *
 * ✔ if...elseif...else handles multiple conditions.
 *
 * ✔ Nested if allows checking conditions inside conditions.
 *
 * ✔ switch is useful for multiple fixed values.
 *
 * ✔ match (PHP 8+) is cleaner, safer, and uses strict comparison.
 *
 * ✔ Ternary operator is a shorthand for simple if...else.
 *
 * ✔ Null coalescing (??) provides default values.
 *
 * =============================================================================
 */