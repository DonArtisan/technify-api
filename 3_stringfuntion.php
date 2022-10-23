<?php
// Here are some funtion of String variable in php
// 1.strlen();
// 2.str_word_count();
// 3.strrev();
// 4.str_repeat();
// 5.str_replace();
// 6.strpos();
// 7.rtrim();

$name="      sandeep is good boy";
echo "The length of variable" .strlen($name);
echo "<br>";
echo str_word_count($name);
echo "<br>";
echo strrev($name);
echo "<br>";
echo str_replace("sandeep", "Ravi",$name);
echo "<br>";
echo str_repeat($name,5);
echo "<br>";
echo strpos($name,"boy");
echo "<br>";
echo rtrim("    sandep     ");
echo "<br>";
echo ltrim("      saneep        ");
echo "<br>";
// echo str_split($name [2]);
?>