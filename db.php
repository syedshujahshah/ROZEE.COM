<?php
$host = "localhost"; // Replace with your actual host if different
$dbname = "dbgl0nyezwkzho";
$username = "uac1gp3zeje8t";
$password = "hk8ilpc7us2e";

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
