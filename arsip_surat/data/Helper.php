<?php

namespace Helper;

require_once __DIR__ . "/../include/config.php";
use PDO;

function getConnection(): PDO
{
    global $host, $port, $database, $username, $password;
    return new PDO("mysql:host=$host:$port;dbname=$database", $username, $password);
}

