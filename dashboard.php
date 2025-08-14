<?php
require_once "auth.php";
require_once "../config/db.php";

if (isset($_GET['status']) && isset($_GET['id'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$_GET['status'], $_GET['id']]);
    header("Location: dashboard.php");
}

if (isset($_GET['delete']) && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header("Location: dashboard.php");
}

$orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Allô K-bine</title>
</head>
<body>
  <h2>Bienvenue, <?= $_SESSION['admin'] ?> | <a href="logout.php">Déconnexion</a></h2>
  <h3>Commandes enregistrées</h3>
  <table border="1" cellpadding="8">
    <tr>
      <th>ID</th>
      <th>Nom</th>
      <th>Téléphone</th>
      <th>Réseau</th>
      <th>Montant</th>
      <th>Statut</th>
      <th>Date</th>
      <th>Actions</th>
    </tr>
    <?php foreach ($orders as $order): ?>
      <tr>
        <td><?= $order['id'] ?></td>
        <td><?= htmlspecialchars($order['client_name']) ?></td>
        <td><?= htmlspecialchars($order['phone_number']) ?></td>
        <td><?= $order['network'] ?></td>
        <td><?= $order['amount'] ?></td>
        <td><?= $order['status'] ?></td>
        <td><?= $order['created_at'] ?></td>
        <td>
          <a href="?status=Traité&id=<?= $order['id'] ?>">Traiter</a> |
          <a href="?delete=1&id=<?= $order['id'] ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
