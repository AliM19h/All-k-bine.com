<?php
$host = "localhost";
$dbname = "allo_kbine";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}
?>
