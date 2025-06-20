 <?php
session_start();  

$host = 'localhost';
$dbname = 'gestion_vehicules';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nom_client = htmlspecialchars(trim($_POST['nom_client'] ?? ''));
        $email = htmlspecialchars(trim($_POST['email'] ?? ''));
        $ville = htmlspecialchars(trim($_POST['ville'] ?? ''));
        $telephone = htmlspecialchars(trim($_POST['telephone'] ?? ''));
        $modele = htmlspecialchars(trim($_POST['modele'] ?? ''));
        $date_debut = $_POST['date_debut'] ?? '';
        $date_fin = $_POST['date_fin'] ?? '';

        if ($nom_client && $email && $ville && $telephone && $modele && $date_debut && $date_fin) {
            $stmt = $pdo->prepare("INSERT INTO reservations (nom_client, email, ville, telephone, modele, date_debut, date_fin)
                                   VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nom_client, $email, $ville, $telephone, $modele, $date_debut, $date_fin]);

 
            header("Location: client.php?nom_client=" . urlencode($nom_client));
            exit();
        } else {
            echo "Veuillez remplir tous les champs.";
        }
    } else {
        echo "Accès non autorisé.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
