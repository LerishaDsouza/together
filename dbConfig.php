<?php
//Database credentials
$dbHost     = 'localhost';
$dbUsername = 'id8842210_root';
$dbPassword = '123456';
$dbName     = 'id8842210_mine';

//Connect with the database
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

//Display error if failed to connect
if ($db->connect_errno) {
    printf("Connect failed: %s\n", $db->connect_error);
    exit();
}