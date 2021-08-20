<?php 
require_once "db_classes.php";

// localhost
$localhost = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "fswd13_cr11_petadoption_philip";

// // CF Live Page
// $localhost = "173.212.235.205";
// $username = "mahlberg_root";
// $password = "1q2w3e_Pdblogin";
// $dbname = "mahlberg_crudhotel";


$db = new Database($localhost, $username, $password, $dbname);


