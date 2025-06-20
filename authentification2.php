 <?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = ? AND role = ?");
    $stmt->execute([$email, $role]);
    $user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;

        if (isset($_POST['remember'])) {
            setcookie('user_email', $email, time() + (30 * 24 * 60 * 60), "/");
            setcookie('user_role', $role, time() + (30 * 24 * 60 * 60), "/");
        } else {
            setcookie('user_email', '', time() - 3600, "/");
            setcookie('user_role', '', time() - 3600, "/");
        }

        if ($role === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: client.php");
        }
        exit();
    } else {
        $message = "Email, mot de passe ou rôle incorrect.";
    }
}
?>
