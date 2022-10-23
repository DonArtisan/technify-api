<?php
echo "Welcome in to the php funtion tutorial<br>";

#sum of two number
function sum($b, $a)
{
    return ($b+$a);
}


$c=sum(10,20);
echo $c;

function checkEvenOdd($n)
{
    if($n%2==0)
    {
        echo "<br>The number is Even";
    }
    else
    {
        echo "<br>The number is Odd";
    }
}
checkEvenOdd(12);
checkEvenOdd(3);
checkEvenOdd(123);
checkEvenOdd(1230);
?>