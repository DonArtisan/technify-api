<?php
// while loop in php
#these are porblem 
echo "the value  : 1<br>";
echo "the value  : 2<br>";
echo "the value  : 3<br>";
echo "the value  : 4<br>";
echo "the value  : 5<br>";
#problem solved through loop


$arr= array("sandeep ","ravi","nayan","pallavi", "sakshi","Roji");
// for ($i=0; $i <count($arr) ; $i++) { 
//     # code...
//     echo $arr[$i];
//     echo "<br>";
// }
// better way for array iteriting
foreach($arr as $content)
{
    echo "$content <br>";

}

?>