<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Accès refusé.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['reservation_id'] ?? null;
    $statut = $_POST['statut'] ?? null;

    if ($id && $statut) {
        try {
            $pdo = new PDO("mysql:host=localhost;dbname=gestion_vehicules;charset=utf8", "root", "");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $pdo->prepare("UPDATE reservations SET statut = ? WHERE id = ?");
            $stmt->execute([$statut, $id]);
        } catch (PDOException $e) {
            die("Erreur: " . $e->getMessage());
        }
    }
}
header("Location: admin.php");
exit;
