<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Accès refusé, veuillez vous connecter en tant qu'administrateur.");
}

$host = 'localhost';
$dbname = 'gestion_vehicules';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

 
$id = $_GET['id'] ?? 0;
if (!$id) {
    die("ID du véhicule manquant.");
}

 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modele = $_POST['modele'] ?? '';
    $annee = $_POST['annee'] ?? '';
    $prix = $_POST['prix'] ?? '';

    if ($modele && $annee && $prix) {
        $stmt = $pdo->prepare("UPDATE vehicules SET modele = ?, annee = ?, prix = ? WHERE id = ?");
        $stmt->execute([$modele, $annee, $prix, $id]);
        header("Location: admin.php");
        exit;
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}

 $stmt = $pdo->prepare("SELECT * FROM vehicules WHERE id = ?");
$stmt->execute([$id]);
$vehicule = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vehicule) {
    die("Véhicule introuvable.");
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Modifier Véhicule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .form-container {
            max-width: 500px;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input {
            width: 100%;
            padding: 6px;
            box-sizing: border-box;
            margin-top: 5px;
        }
        .btn-submit {
            margin-top: 15px;
            background-color: #2980b9;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 3px;
        }
        .btn-submit:hover {
            background-color: #1f5f8b;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        a {
            display: inline-block;
            margin-top: 15px;
            color: #2980b9;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Modifier le véhicule</h2>

<div class="form-container">
    <?php if (isset($error)) : ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label for="modele">Modèle *</label>
        <input type="text" id="modele" name="modele" required value="<?= htmlspecialchars($vehicule['modele']) ?>" />

        <label for="annee">Année *</label>
        <input type="number" id="annee" name="annee" required value="<?= htmlspecialchars($vehicule['annee']) ?>" />

        <label for="prix">Prix (€) *</label>
        <input type="number" step="0.01" id="prix" name="prix" required value="<?= htmlspecialchars($vehicule['prix']) ?>" />

        <button type="submit" class="btn-submit">Enregistrer</button>
    </form>

    <a href="admin.php">&laquo; Retour à la liste des véhicules</a>
</div>

</body>
</html>
