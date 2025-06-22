 
 
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Inscription et Connexion</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      background: url('image/bg.jpg') no-repeat center center fixed;
      background-size: cover;
      backdrop-filter: blur(5px);
    }

    .form-container {
      margin: 50px auto;
      max-width: 500px;
      background-color: rgba(255, 255, 255, 0.9);
      padding: 30px;
      border-radius: 10px;
    }

    h2 {
      margin-bottom: 25px;
    }

    footer {
      margin-top: 15px;
    }
  </style>
</head>

<body>

  <div class="form-container">
    <h2 class="text-center">Formulaire d'inscription</h2>
    <form action="enregistrer_client.php" method="POST">
      <div class="mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" class="form-control" name="nom" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" required>
      </div>
      <div class="mb-3">
        <label for="ville" class="form-label">Ville</label>
        <input type="text" class="form-control" name="ville" required>
      </div>
      <div class="mb-3">
        <label for="telephone" class="form-label">Téléphone</label>
        <input type="text" class="form-control" name="telephone" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password" class="form-control" name="password" required>
      </div>


      <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
    </form>
  </div>

  <div class="form-container">
    <h2 class="text-center">authentification</h2>
    <form action="authentification2.php" method="POST">
      <div class="mb-3">
        <label for="email">Email :</label>
        <input type="email" class="form-control" name="email" id="email" required>
      </div>
      <div class="mb-3">
        <label for="password">Mot de passe :</label>
        <input type="password" class="form-control" name="password" id="password" required>
      </div>
      <div class="mb-3">
        <label for="role">Rôle :</label>
        <select name="role" id="role" class="form-control" required>
          <option value="client">Client</option>
          <option value="admin">Admin</option>
        </select>
      </div>
        <label>
    <input type="checkbox" name="remember" /> Se souvenir de moi
  </label>

      <button type="submit" class="btn btn-success w-100">Se connecter</button>
      <?php if (isset($message))
        echo "<p class='text-danger mt-2'>$message</p>"; ?>
      <footer class="text-center mt-3">
        <p>Pas encore inscrit ? <a href="inscription1.php">Inscrivez-vous ici</a></p>
        <a href="client.php"></a>
      </footer>
    </form>
  </div>
  <?php if (isset($message)) echo "<p class='text-danger mt-2'>$message</p>"; ?>



</body>

</html>