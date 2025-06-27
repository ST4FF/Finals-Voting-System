<?php
    $conn = mysqli_connect('localhost', 'root', '', 'voting_system');

    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }
?>