 <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Réservation</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: url('image/background-home.png') no-repeat center center fixed;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-color: rgba(0, 0, 0, 0.6);
            position: relative;
            min-height: 100vh;
        }

 
        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 400px;
            margin: 80px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #dd0707;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            color: #333;
            font-weight: 600;
        }

        input[type="text"],
        input[type="email"],
        input[type="ville"],
        input[type="tel"],
        input[type="date"],


        select {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            background-color: #dd0707;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 18px;
            cursor: pointer;
            font-weight: 700;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #a00404;
        }

        /* رابط الرجوع */
        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            background: rgba(221, 7, 7, 0.8);
            padding: 8px 16px;
            border-radius: 8px;
            display: inline-block;
            transition: background 0.3s ease;
        }

        .back-link a:hover {
            background: rgba(221, 7, 7, 1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Formulaire de Réservation</h2>
        <form action="reserver.php" method="POST">
            <label for="nom">Nom complet</label>
            <input type="text" id="nom_client" name="nom_client" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="ville">ville</label>
            <input type="text" id="ville" name="ville" required>

            <label for="telephone">Téléphone</label>
            <input type="tel" id="telephone" name="telephone" required>

            <label for="modele">Modèle de voiture</label>
            <select id="modele" name="modele" required>
                <option value="" disabled selected>Choisir un modèle</option>
                <option value="Porsche 911 Carrera S">Porsche 911 Carrera S</option>
                <option value="Porsche Taycan Turbo S">Porsche Taycan Turbo S</option>
                <option value=" Porsche Macan S">Porsche Macan S</option>
                <option value="Rolls-Royce Phantom">Rolls-Royce Phantom</option>
                <option value="Rolls-Royce Ghost">Rolls-Royce Ghost</option>
                <option value="Rolls-Royce Cullinan">Rolls-Royce Cullinan</option>
                <option value="Tesla Model S Plaid">Tesla Model S Plaid</option>
                <option value="Tesla Model 3">Tesla Model 3</option>
                <option value="Tesla Model X">Tesla Model X</option>
                <option value="Ferrari 296 GTB">Ferrari 296 GTB</option>
                <option value="Ferrari SF90 Stradale">Ferrari SF90 Stradale</option>
                <option value="Ferrari Roma">Ferrari Roma</option>
            </select>

            <label for="date">date debut</label>
            <input type="date" id="date_debut" name="date_debut" required>
            <label for="date">date fin</label>
            <input type="date" id="date_fin" name="date_fin" required>

            <button type="submit">Envoyer</button>
            <p><a style=" color:red;padding: 8px 20px;
            border: 2px solid  ;
            border-radius: 10px;
             
            font-weight: 600;
             display: inline-block;
             background-color:#f7d4bd;
            margin-bottom: 10px; " href="client.php"><-retour</a></p>
        </form>
    </div>

     
    <?php
$host = 'localhost';
$dbname = 'gestion_vehicules';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

            echo "Réservation enregistrée avec succès !";
        } else {
            echo "Veuillez remplir tous les champs.";
        }
    }  
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

</body>
</html>
