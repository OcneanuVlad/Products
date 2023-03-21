<?php
// connect to database
$conn = mysqli_connect('localhost', 'vlad', 'test', 'products');

// check connection
if(!$conn) {
    echo 'connection error';
}
?>