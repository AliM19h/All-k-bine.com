<?php
// backend/save_order.php

// Configuration base de données
$host = 'localhost';
$db   = 'allokbine';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

header('Content-Type: application/json');

try {
    // Connexion PDO
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de connexion à la base de données']);
    exit;
}

// Vérification des données
$name    = trim($_POST['name'] ?? '');
$number  = trim($_POST['number'] ?? '');
$network = trim($_POST['network'] ?? '');
$amount  = trim($_POST['amount'] ?? '');

if (empty($name) || empty($number) || empty($network) || empty($amount)) {
    echo json_encode(['error' => 'Tous les champs sont requis.']);
    exit;
}

if (!preg_match('/^[0-9]{10}$/', $number)) {
    echo json_encode(['error' => 'Le numéro doit contenir exactement 10 chiffres.']);
    exit;
}

$networks = ['Orange', 'MTN', 'Moov'];
if (!in_array($network, $networks)) {
    echo json_encode(['error' => 'Réseau invalide.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO commandes (nom, numero, reseau, montant, statut, created_at)
                           VALUES (:nom, :numero, :reseau, :montant, 'en attente', NOW())");

    $stmt->execute([
        'nom'     => htmlspecialchars($name),
        'numero'  => $number,
        'reseau'  => $network,
        'montant' => htmlspecialchars($amount)
    ]);

    echo json_encode(['success' => 'Commande enregistrée avec succès.']);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de l\'enregistrement.']);
}