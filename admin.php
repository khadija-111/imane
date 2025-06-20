 <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
 

if (!isset($_SESSION['user']) && isset($_COOKIE['user_email']) && isset($_COOKIE['user_role'])) {
     $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = ? AND role = ?");
    $stmt->execute([$_COOKIE['user_email'], $_COOKIE['user_role']]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user'] = $user;
    }
}

 if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}


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

// Gestion des véhicules
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_vehicule') {
        $modele = $_POST['modele'] ?? '';
        $annee = $_POST['annee'] ?? '';
        $prix = $_POST['prix'] ?? '';
        if ($modele && $annee && $prix) {
            $stmt = $pdo->prepare("INSERT INTO vehicules (modele, annee, prix) VALUES (?, ?, ?)");
            $stmt->execute([$modele, $annee, $prix]);
            header("Location: admin.php");
            exit;
        }
    } elseif ($_POST['action'] === 'update_vehicule') {
        $id = $_POST['id'] ?? 0;
        $modele = $_POST['modele'] ?? '';
        $annee = $_POST['annee'] ?? '';
        $prix = $_POST['prix'] ?? '';
        if ($id && $modele && $annee && $prix) {
            $stmt = $pdo->prepare("UPDATE vehicules SET modele = ?, annee = ?, prix = ? WHERE id = ?");
            $stmt->execute([$modele, $annee, $prix, $id]);
            header("Location: admin.php");
            exit;
        }
    } elseif ($_POST['action'] === 'delete_vehicule') {
        $id = $_POST['id'] ?? 0;
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM vehicules WHERE id = ?");
            $stmt->execute([$id]);
            header("Location: admin.php");
            exit;
        }
    }
    // Gestion des réservations
    elseif ($_POST['action'] === 'add_reservation') {
        $nom_client = $_POST['nom_client'] ?? '';
        $email = $_POST['email'] ?? '';
        $ville = $_POST['ville'] ?? '';
        $telephone = $_POST['telephone'] ?? '';
        $modele = $_POST['modele'] ?? '';
        $date_debut = $_POST['date_debut'] ?? '';
        $date_fin = $_POST['date_fin'] ?? '';
        $statut = $_POST['statut'] ?? 'En attente';

        if ($nom_client && $email && $modele && $date_debut && $date_fin) {
            $stmt = $pdo->prepare("INSERT INTO reservations (nom_client, email, ville, telephone, modele, date_debut, date_fin, statut) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nom_client, $email, $ville, $telephone, $modele, $date_debut, $date_fin, $statut]);
            header("Location: admin.php");
            exit;
        }
    } elseif ($_POST['action'] === 'update_statut') {
        $id = $_POST['id'] ?? 0;
        $statut = $_POST['statut'] ?? '';
        if ($id && in_array($statut, ['En attente', 'Confirmé'])) {
            $stmt = $pdo->prepare("UPDATE reservations SET statut = ? WHERE id = ?");
            $stmt->execute([$statut, $id]);
            header("Location: admin.php");
            exit;
        }
    } elseif ($_POST['action'] === 'delete_reservation') {
        $id = $_POST['id'] ?? 0;
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
            $stmt->execute([$id]);
            header("Location: admin.php");
            exit;
        }
    }
}

// Récupération données
$vehicules = $pdo->query("SELECT * FROM vehicules ORDER BY modele ASC")->fetchAll(PDO::FETCH_ASSOC);

$reservations = $pdo->query("SELECT * FROM reservations ORDER BY date_debut DESC")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <title>Administration - Véhicules & Réservations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        body { font-family: Arial, sans-serif; margin: 20px;   background: linear-gradient(rgba(255,255,255,0.7), rgba(255,255,255,0.7)), url("image/bg.jpg");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat; }
    body::before {
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url("image/bg.jpg") center/cover no-repeat;
    opacity: 0.4;  
    z-index: -1;
}
.welcome-admin {
    position: relative;
    margin: 30px auto;
    padding: 15px 30px;
    background-color: rgba(255, 255, 255, 0.85);
    color: #c0392b;
    font-size: 2rem;
    font-weight: bold;
    border: 3px solid #c0392b;
    border-radius: 15px;
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
    text-align: center;
    width: fit-content;
}

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #aaa; padding: 8px; text-align: left; }
        th { background-color: #eee; }
        form.inline { display: inline; }
        button { cursor: pointer; border-radius: 3px; border: none; padding: 5px 10px; }
        .btn-delete { background-color: #e74c3c; color: white; }
        .btn-delete:hover { background-color: #c0392b; }
        .btn-add { background-color: #27ae60; color: white; padding: 10px 15px; margin-top: 10px; }
        .btn-add:hover { background-color: #1e8449; }
        .form-container { max-width: 600px; border: 1px solid #ddd; padding: 20px; border-radius: 5px; margin-bottom: 40px; }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
        input, select { width: 100%; padding: 6px; box-sizing: border-box; margin-bottom: 10px; }
    </style>
</head>

<body>
    <a style=" color:red;padding: 8px 20px;
            border: 2px solid  ;
            border-radius: 10px;
             
            font-weight: 600;
             
             background-color:#f7d4bd;
            margin-bottom: 10px;  text-decoration: none;" href="deconnexion.php" style="color:red;">Déconnexion</a>

            <a  href="export_pdf.php?type=vehicules" target="_blank" style="display:inline-block; margin-left:10px; color:#fff; background-color:#2980b9; padding:10px 20px; border-radius:10px; text-decoration:none; font-weight:bold;">
📄 Exporter Véhicules
</a>

<a href="export_pdf.php?type=reservations" target="_blank" style="display:inline-block; margin-left:10px; color:#fff; background-color:#27ae60; padding:10px 20px; border-radius:10px; text-decoration:none; font-weight:bold;">
📄 Exporter Réservations
</a>


<div class="welcome-admin">Bienvenue, <?= htmlspecialchars($_SESSION['user']['nom']) ?> !</div>

    <h2 style="color:rgb(172, 11, 11);">Ajouter un véhicule</h2>
    <div class="form-container">
        <form method="post" action="admin.php">
            <input type="hidden" name="action" value="add_vehicule" />
            <label for="modele">Modèle *</label>
            <input type="text" id="modele" name="modele" required />
            <label for="annee">Année *</label>
            <input type="number" id="annee" name="annee" required />
            <label for="prix">Prix (€) *</label>
            <input type="number" step="0.01" id="prix" name="prix" required />
            <button type="submit" class="btn-add">Ajouter</button>
        </form>
    </div>

    <h2 style="color:rgb(172, 11, 11);">Liste des véhicules</h2>
<table>
    <thead>
        <tr>
            <th>Modèle</th>
            <th>Année</th>
            <th>Prix (€)</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($vehicules)) : ?>
            <tr>
                <td colspan="4">Aucun véhicule trouvé.</td>
            </tr>
        <?php else : ?>
            <?php foreach ($vehicules as $v) : ?>
                <tr>
                    <td><?= htmlspecialchars($v['modele']) ?></td>
                    <td><?= htmlspecialchars($v['annee']) ?></td>
                    <td><?= htmlspecialchars($v['prix']) ?></td>
                    <td>
                        <!-- Bouton Modifier -->
                        <a href="modifier_vehicule.php?id=<?= $v['id'] ?>" class="btn-edit" 
                           style="margin-right:10px; padding:5px 10px; background:#2980b9; color:#fff; border-radius:3px; text-decoration:none;" 
                           title="Modifier">
                            <i class="fa fa-edit"></i> Modifier
                        </a>

                        <!-- Formulaire Supprimer -->
                        <form method="post" action="admin.php" class="inline" onsubmit="return confirm('Confirmer la suppression ?');" style="display:inline;">
                            <input type="hidden" name="action" value="delete_vehicule" />
                            <input type="hidden" name="id" value="<?= $v['id'] ?>" />
                            <button type="submit" class="btn-delete" title="Supprimer">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

      

    <h2 style="color:rgb(172, 11, 11);">Liste des réservations</h2>
    <table>
        <thead>
            <tr>
                <th>Nom client</th>
                <th>Email</th>
                <th>Ville</th>
                <th>Téléphone</th>
                <th>Modèle réservé</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($reservations)) : ?>
                <tr><td colspan="9">Aucune réservation trouvée.</td></tr>
            <?php else: foreach ($reservations as $res): ?>
                <tr>
                    <td><?= htmlspecialchars($res['nom_client']) ?></td>
                    <td><?= htmlspecialchars($res['email']) ?></td>
                    <td><?= htmlspecialchars($res['ville']) ?></td>
                    <td><?= htmlspecialchars($res['telephone']) ?></td>
                    <td><?= htmlspecialchars($res['modele']) ?></td>
                    <td><?= htmlspecialchars($res['date_debut']) ?></td>
                    <td><?= htmlspecialchars($res['date_fin']) ?></td>
                    <td>
                        <form method="post" action="admin.php" class="inline">
                            <input type="hidden" name="action" value="update_statut" />
                            <input type="hidden" name="id" value="<?= $res['id'] ?>" />
                            <select name="statut" onchange="this.form.submit()">
                                <option value="En attente" <?= $res['statut'] === 'En attente' ? 'selected' : '' ?>>En attente</option>
                                <option value="Confirmé" <?= $res['statut'] === 'Confirmé' ? 'selected' : '' ?>>Confirmé</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <form method="post" action="admin.php" class="inline" onsubmit="return confirm('Confirmer la suppression ?');">
                            <input type="hidden" name="action" value="delete_reservation" />
                            <input type="hidden" name="id" value="<?= $res['id'] ?>" />
                            <button type="submit" class="btn-delete" title="Supprimer"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>

</body>
</html>
