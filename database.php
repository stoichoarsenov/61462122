<?php
/**
 * Created by PhpStorm.
 * User: stoicho
 * Date: 07.02.18
 * Time: 16:29
 */

$servername = "localhost";
$username = "root";
$password = "root";
$database = "myDB";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


// Create database
$sql = "CREATE DATABASE IF NOT EXISTS myDB";
?>