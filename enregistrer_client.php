 <?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start(); 

require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $ville = $_POST['ville'];
    $telephone = $_POST['telephone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  

    $sql = "INSERT INTO clients (nom, email, ville, telephone, password, role) 
        VALUES (:nom, :email, :ville, :telephone, :password, 'client')";


    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nom' => $nom,
        ':email' => $email,
        ':ville' => $ville,
        ':telephone' => $telephone,
        ':password' => $password
    ]);

     $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user'] = $user;   
    }

    header("Location: client.php");
    exit();
}
?>
