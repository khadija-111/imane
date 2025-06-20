<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require 'config.php';  

 
$host = 'localhost';
$dbname = 'gestion_vehicules';
$username = 'root';
$password = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
 

 
if (!isset($_SESSION['user']) && isset($_COOKIE['user_email']) && isset($_COOKIE['user_role'])) {
    $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = ? AND role = ?");
    $stmt->execute([$_COOKIE['user_email'], $_COOKIE['user_role']]);
    $user = $stmt->fetch();
    if ($user) {
        $_SESSION['user'] = $user;
    }
}

// Vérification de session
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'client') {
    header("Location: inscription1.php");
    exit();
}

$nom_client = $_SESSION['user']['nom'];  

// Suppression réservation
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $check = $pdo->prepare("SELECT * FROM reservations WHERE id = ? AND nom_client = ?");
    $check->execute([$delete_id, $nom_client]);
    if ($check->rowCount() > 0) {
        $del = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
        $del->execute([$delete_id]);
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
        exit();
    }
}

// Récupération des réservations
$stmt = $pdo->prepare("SELECT * FROM reservations WHERE nom_client = ?");
$stmt->execute([$nom_client]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $titre ?></title>

     
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

 
    <style>
        
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
        }




        .hero {
            position: relative;
            width: 100%;
            height: 100vh;
            background: url(image/bg.jpg);
            background-position: center;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #e39898;
        }

        .welcome {
            position: absolute;
            top: 30px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px 30px;
            background-color: rgba(255, 255, 255, 0.8);
            color: rgb(248, 106, 106);
            font-size: 2.5rem;
            font-weight: bold;
            border: 3px solid #dd0707;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
            z-index: 10;
            text-align: center;
        }


        .hero .text {
            width: 90%;
            margin: auto;
        }

        .hero .text h4 {
            font-size: 40px;
            color: #fff;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .hero .text h1 {
            color: #fff;
            font-size: 65px;
            text-transform: uppercase;
            line-height: 1;
            margin-bottom: 30px;
        }

        .hero .text h1 span {
            color: #dd0707;
            font-size: 80px;
            font-weight: bold;
        }

        .hero .text p {
            color: #fff;
            margin-bottom: 30px;
        }

        .hero .text .btn {
            padding: 10px 30px;
            background-color: #dd0707;
            text-transform: uppercase;
            color: #fff;
            font-weight: bold;
            border-radius: 30px;
            border: 2px solid #dd0707;
            transition: 0.3s;
        }

        .hero .text .btn:hover {
            background-color: transparent;
        }



        .heading {
            text-align: center;
        }

        .heading button {
            font-weight: 500;
            color: hotpink;
        }


        .heading p {
            font-size: 0.938rem;
            font-weight: 300;
        }

        .cars-container {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            margin-top: 2rem;
        }

        .cars-container .box {
            flex: 1 1 17rem;
            position: relative;
            border-radius: 0.5rem;
            overflow: hidden;
            background: #f9f9f9;
            padding: 10px;
            display: flex;
            flex-direction: column;
        }

        .cars-container .box img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: 0.5s;
        }

        .cars-container .box img:hover {
            transform: scale(1.1);
        }

        .box-content p {
            margin: 5px 0;
        }

        .footer {
            background: var(--text-color);
            color: #f6f6f6;
          
        }

        .footer-container {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
        }

        .heading button {
            display: inline-block;
            padding: 8px 20px;
            border: 2px solid hotpink;
            border-radius: 10px;
            background-color: #fff0f5;
            font-weight: 600;
            font-size: 1.2rem;
            color: hotpink;
            margin-bottom: 10px;
        }
        .heading button:hover{
            background-color:#f7bdbd ;
        }

        .btn-reserver {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 20px;
            background-color: #dd0707;
            color: #fff;
            text-transform: uppercase;
            font-weight: bold;
            border: none;
            border-radius: 20px;
            transition: 0.3s;
            font-size: 0.9rem;
            text-align: center;
        }

        .btn-reserver:hover {
            background-color: transparent;
            color: #dd0707;
            border: 2px solid #dd0707;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #eee;
        }

        .btn-supprimer {
            color: red;
            font-weight: bold;
            text-decoration: none;
        }

        .logout {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: rgba(255, 255, 255, 0.9);
             color: white;
            padding: 10px 20px;
            border: 2px solid red;
            border-radius: 10px;
            font-weight: bold;
            text-decoration: none;
            z-index: 20;
            transition: 0.3s;
            background-color: red;
        }

        .logout:hover {
            background-color: #e39898;
            color: white;
        }
        .pdf-button {
    position: absolute;
    top: 70px;
    right: 20px;
    background-color: #fff;
    color: #dd0707;
    padding: 10px 20px;
    border: 2px solid #dd0707;
    border-radius: 10px;
    font-weight: bold;
    text-decoration: none;
    z-index: 20;
    transition: 0.3s;
}

.pdf-button:hover {
    background-color: #dd0707;
    color: white;
}

    </style>

</head>

<body>
    <div class="hero">
        <a href="deconnexion.php" class="logout">Déconnexion</a>
        <a href="export_pdf.php?type=reservations" target="_blank" class="pdf-button">📄 imprimer</a>


        <div class="welcome">
            Bonjour, <?= htmlspecialchars($_SESSION['user']['nom']) ?> !
        </div>

        <div class="text">
            <h4>Puissante, Plaisante et </h4>
            <h1>Audacieuse à <br> <span> Conduire</span></h1>
            <p> Élégance réelle, Puissance réelle, Performance réelle.</p>
            <a href="#cars" class="btn"> Réserver un essai routier</a>
        </div>
    </div>

    <script>

        let heroBg = document.querySelector('.hero');

        setInterval(() => {
            heroBg.style.backgroundImage = "url(image/bg-light.jpg)"

            setTimeout(() => {
                heroBg.style.backgroundImage = "url(image/bg.jpg)"
            }, 1000);
        }, 2200);
    </script>
    <section class="cars" id="cars">

        <div class="heading">
            <h2> All Cars </h2>
            <h2>We have all types</h2>
            <p style="color:rgb(220, 93, 93);">N’attendez plus pour vivre l’expérience au volant ! <br> Réservez dès
                maintenant votre véhicule et profitez d’un confort, d’une puissance et d’une liberté inégalés.</p>
        </div>


        <!--   Cars -->
        <div class="heading"><button>Porsche Cars</button></div>

        <div class="cars-container container">
            <div class="box">
                <img src="image/car1.jpg" alt="Porsche 911">
                <p><strong>Modèle:</strong> Porsche 911 Carrera S</p>
                <p><strong>Année:</strong> 2023</p>
                <p><strong>Prix:</strong> 280 dhs/jour</p>
                <p>Sportive classique, puissante et élégante.</p>
                <a href="formulaire_reservation.php" class="btn-reserver">Réserver</a>


            </div>
            <div class="box">
                <img src="image/car2.jpg" alt="Porsche Cayenne">
                <p><strong>Modèle:</strong> Porsche Taycan Turbo S</p>
                <p><strong>Année:</strong> 2023</p>
                <p><strong>Prix:</strong> 260 dhs/jour</p>
                <p> Voiture électrique rapide et moderne.</p>
                <a href="formulaire_reservation.php" class="btn-reserver">Réserver</a>


            </div>
            <div class="box">
                <img src="image/car3.jpg" alt="Porsche Cayenne">
                <p><strong>Modèle:</strong> Porsche Macan S</p>
                <p><strong>Année:</strong> 2023</p>
                <p><strong>Prix:</strong> 230 dhs/jour</p>
                <p>SUV compact sportif et confortable.</p>
                <a href="formulaire_reservation.php" class="btn-reserver">Réserver</a>


            </div>
        </div>

     
        <div class="heading"><button>Rolls Royce Cars</button></div>
        <div class="cars-container container">
            <div class="box">
                <img src="image/car1.jpg" alt="Rolls Royce Phantom">
                <p><strong>Modèle:</strong> Rolls-Royce Phantom</p>
                <p><strong>Année:</strong> 2023</p>
                <p><strong>Prix:</strong> 300 dhs/jour</p>
                <p> Berline ultra-luxueuse, confort absolu et silence total.

                </p>
                <a href="formulaire_reservation.php" class="btn-reserver">Réserver</a>


            </div>
            <div class="box">
                <img src="image/car2.jpg" alt="Rolls Royce Phantom">
                <p><strong>Modèle:</strong> Rolls-Royce Ghost </p>
                <p><strong>Année:</strong> 2023</p>
                <p><strong>Prix:</strong> 250 dhs/jour</p>
                <p> Élégance discrète, performance raffinée. </p>
                <a href="formulaire_reservation.php" class="btn-reserver">Réserver</a>


            </div>
            <div class="box">
                <img src="image/car3.jpg" alt="Rolls Royce Phantom">
                <p><strong>Modèle:</strong>Rolls-Royce Cullinan </p>
                <p><strong>Année:</strong> 2023</p>
                <p><strong>Prix:</strong> 230 dhs/jour</p>
                <p> SUV de luxe, alliant puissance et confort royal.</p>
                <a href="formulaire_reservation.php" class="btn-reserver">Réserver</a>


            </div>
        </div>

       
        <div class="heading"><button>Tesla Cars</button></div>
        <div class="cars-container container">
            <div class="box">
                <img src="image/car1.jpg" alt="Tesla Model S">
                <p><strong>Modèle:</strong> Tesla Model S Plaid</p>
                <p><strong>Année:</strong> 2023</p>
                <p><strong>Prix:</strong> 220 dhs/jour</p>
                <p> Berline électrique ultra-rapide avec plus de 1000 ch.</p>
                <a href="formulaire_reservation.php" class="btn-reserver">Réserver</a>


            </div>
            <div class="box">
                <img src="image/car2.jpg" alt="Tesla Model S">
                <p><strong>Modèle:</strong> Tesla Model 3</p>
                <p><strong>Année:</strong> 2023</p>
                <p><strong>Prix:</strong> 280 dhs/jour</p>
                <p> Compacte, performante et accessible.</p>
                <a href="formulaire_reservation.php" class="btn-reserver">Réserver</a>


            </div>
            <div class="box">
                <img src="image/car3.jpg" alt="Tesla Model S">
                <p><strong>Modèle:</strong> Tesla Model X</p>
                <p><strong>Année:</strong> 2023</p>
                <p><strong>Prix:</strong> 240 dhs/jour</p>
                <p>SUV électrique avec portes Falcon et grande autonomie.</p>
                <a href="formulaire_reservation.php" class="btn-reserver">Réserver</a>


            </div>
        </div>

       
        <div class="heading"><button>Ferrari Cars</button></div>
        <div class="cars-container container">
            <div class="box">
                <img src="image/car1.jpg" alt="Ferrari F8">
                <p><strong>Modèle:</strong> Ferrari 296 GTB</p>
                <p><strong>Année:</strong> 2023</p>
                <p><strong>Prix:</strong> 220 dhs/jour</p>
                <p> Hybride V6, sportive, légère et très rapide. </p>
                <a href="formulaire_reservation.php" class="btn-reserver">Réserver</a>


            </div>
            <div class="box">
                <img src="image/car2.jpg" alt="Ferrari F8">
                <p><strong>Modèle:</strong> Ferrari SF90 Stradale </p>
                <p><strong>Année:</strong> 2023</p>
                <p><strong>Prix:</strong> 320 dhs/jour</p>
                <p>Hypercar hybride avec plus de 1000 ch. </p>
                <a href="formulaire_reservation.php" class="btn-reserver">Réserver</a>


            </div>
            <div class="box">
                <img src="image/car3.jpg" alt="Ferrari F8">
                <p><strong>Modèle:</strong>Ferrari Roma </p>
                <p><strong>Année:</strong> 2023</p>
                <p><strong>Prix:</strong> 290 dhs/jour</p>
                <p> Coupé élégant, confortable et puissant.</p>
                <a href="formulaire_reservation.php" class="btn-reserver">Réserver</a>


            </div>
        </div>

    </section>
 
    <h2 style="color:red;padding: 8px 20px;
            border: 2px solid hotpink;
            border-radius: 10px;
            background-color:rgb(244, 234, 130);
            font-weight: 600;
             display: inline-block;
            color: hotpink;
            margin-bottom: 10px;" >Réservations de <?= htmlspecialchars($nom_client) ?></h2>

    <?php if (count($reservations) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Modèle</th>
                    <th>Ville</th>
                    <th>Téléphone</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['modele']) ?></td>
                        <td><?= htmlspecialchars($r['ville']) ?></td>
                        <td><?= htmlspecialchars($r['telephone']) ?></td>
                        <td><?= htmlspecialchars($r['date_debut']) ?></td>
                        <td><?= htmlspecialchars($r['date_fin']) ?></td>
                        <td><?= htmlspecialchars($r['statut'] ?? 'en attente') ?></td>
                        <td>
                            <a href="?delete_id=<?= $r['id'] ?>" class="btn-supprimer"
                                onclick="return confirm('Voulez-vous vraiment supprimer cette réservation ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune réservation trouvée.</p>
    <?php endif; ?>

    <p><a style=" color:red;padding: 8px 20px;
            border: 2px solid  ;
            border-radius: 10px;
             
            font-weight: 600;
             display: inline-block;
             background-color:#f7d4bd;
            margin-bottom: 10px; " href="formulaire_reservation.php">← Retour à la réservation</a></p>








</body>

</html>
</body>