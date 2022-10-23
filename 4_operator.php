<?php
// operator in php
// 1.Assignment operator 
// 2.logical operator
// 3.compersion operator
// 4.arthimetic operator

$a=50;
$b=5;
// arthmetic operator
echo "The value of a+b : ".($a+$b)."<br>";
echo "The value of a-b : ".($a-$b)."<br>";
echo "The value of a*b : ".($a*$b)."<br>";
echo "The value of a/b : ".($a/$b)."<br>";
echo "The value of a%b : ".($a%$b)."<br>";
echo "The value of a**b : ".($a**$b)."<br>";
echo "The value of a***b : ".($a**$b)."<br>";

// assignment operator
$a+=10;
echo $a;
echo "<br>";
$d=0;
echo $d;
$b=50;
// compersion operator
echo "a==b : ".var_dump($a==$b);
// not equal operator
echo "a<>b : ".var_dump($a<>$b);

// logical operator
$e=true;
$f=false;
echo "<br>";
echo "a and b : ".var_dump($e and $f);
echo "<br>";
echo "a or b : ".var_dump($e or $f);
echo "<br>";
echo "a && b : ".var_dump($e && $f);
echo "<br>";
echo "a || b : ".var_dump($e || $f);


?>