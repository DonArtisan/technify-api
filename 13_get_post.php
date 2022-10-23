<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testing get & post</title>
</head>
<body>
    <form action="" method="post">
       
       Name : <input type="text" name="name" id="" placeholder="Enter Your Name " >
       password : <input type="password" name="pass" id="">
       <!-- <input type="submit" value="Submit"> -->
       <button type="submit">Submit</button>

   </form>
   
</body>
</html>

<?php
    if ($_SERVER['REQUEST_METHOD']=='POST')
    {
        $name=$_POST['name'];
        $pass=$_POST['pass'];
        echo "You Have successfully Enter Name : $name and password is : $pass";
    }

?>