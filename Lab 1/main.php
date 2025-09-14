<?php
// Lab 1, part 1

/**
 * Remove invalid shows from the assoc. array that is passed as a parameter, 
 * and return an array which contains only valid entries.
 * 
 * Hint: look into https://www.php.net/manual/en/function.unset.php
 * 
 * @param array $shows: an associative array of shows in a format following: 
 *              ['name' => '<date string>', ...]
 * @return array: an associative array containing shows that don't have 
 *                empty strings or null values for their names and dates
 */
function filterInvalidShows($shows) {
    $valid = [];
    foreach ($shows as $name => $dates) {
        $nameIsValid = (is_string($name) && $name !== '');
        $datesIsValid = (is_string($dates) && $dates !== '');
        if ($nameIsValid && $datesIsValid) {
            $valid[$name] = $dates;
        }
    }
    return $valid;
}

/**
 * Prints the show data in a "name: <aired dates>" format.
 * 
 * @param array $shows: the shows to print
 * @return void
 */
function displayShowInfo($shows) {
    echo "<ul>";
    foreach ($shows as $name => $dates) {
        echo "<li><strong>$name</strong>: $dates</li>";
    }
    echo "</ul>";
}

// An associative array of show names and dates
$shows = [
    'Curb Your Enthusiasm' => 'October 15, 2000 - Current',
    'The Simpsons' => 'December 17, 1989 - Current',
    'Seinfeld' => 'July 5, 1989 - May 14, 1998',
    'Invalid data1' => '',
    'Invalid data2' => null,
    null => 'December 17, 1999 - Current',
    '' => 'December 30, 1999 - Current',
    'Rick and Morty' => 'December 2, 2013',
    'White Lotus' => 'July 11, 2021 - Current',
    'One Piece' => null,
];

// Filter out invalid entries
$validShows = filterInvalidShows($shows);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 1 - Part 1</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Favourite Shows</h1>
    <?php
    displayShowInfo($validShows);
    ?>
    <li><a href="fizzbuzz.php">Part 2 – FizzBuzz (alternative syntax)</a></li>
</body>
</html>