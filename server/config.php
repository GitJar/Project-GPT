<?php
// server/config.php
$DB_HOST = 'localhost';
$DB_USER = 'db_user';
$DB_PASS = 'db_pass';
$DB_NAME = 'resep_nusantara';
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
  http_response_code(500);
  die('DB connect error: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');
session_start();
