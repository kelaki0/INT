<?php

define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'int_db');

//Establishing connection to the database
function get_connection() {
    $connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);


    //Checking connection
    if ($connection -> connect_error) {
        die("Connection failed: " . $connection -> connect_error);
    }

    return $connection;
}

?>
