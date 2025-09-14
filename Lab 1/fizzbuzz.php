<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 1 - Part 2</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>FizzBuzz</h1>
    <ol>
    <?php
    for ($i = 1; $i <= 100; $i++):
        if ($i % 3 === 0 && $i % 5 === 0):
            echo "<li><strong>FizzBuzz</strong></li>";
        elseif ($i % 3 === 0):
            echo "<li><strong>Fizz</strong></li>";
        elseif ($i % 5 === 0):
            echo "<li><strong>Buzz</strong></li>";
        else:
            echo "<li>$i</li>";
        endif;
    endfor;
    ?>
    </ol>
    <li><a href="main.php">Part 1 – Show Lists (Associative arrays and Filterting Invalids)</a></li>
</body>
</html>