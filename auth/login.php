<?php
session_start();
require_once '../config/database.php';

$errors = [];

// Si l'utilisateur est déjà connecté, rediriger vers la page d'accueil
if (isset($_SESSION['user_id'])) {
  header("Location: /index.html");
  exit();
}

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Récupérer les données du formulaire
  $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
  $password = $_POST['password'] ?? '';

  // Validation des champs
  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email invalide";
  if (empty($password)) $errors[] = "Le mot de passe est requis";

  // Si pas d'erreurs, vérifier les identifiants
  if (empty($errors)) {
    try {
      $stmt = $pdo->prepare("SELECT id, nom, prenom, email, password FROM users WHERE email = ?");
      $stmt->execute([$email]);
      $user = $stmt->fetch();

      if ($user && password_verify($password, $user['password'])) {
        // Connexion réussie, stocker les infos dans la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['user_prenom'] = $user['prenom'];
        $_SESSION['user_email'] = $user['email'];

        // Rediriger vers la page d'accueil
        header("Location: /index.html");
        exit();
      } else {
        $errors[] = "Email ou mot de passe incorrect";
      }
    } catch (PDOException $e) {
      $errors[] = "Erreur lors de la connexion: " . $e->getMessage();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - Travel.Companion</title>

  <!-- FONT GOOGLE -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montagu+Slab:opsz,wght@16..144,100..700&family=Unbounded:wght@200..900&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="../assets/css/form.css">
  <link rel="stylesheet" href="../assets/css/auth.css">
</head>

<body>
  <header>
    <nav>
      <h3>Travel.Companion</h3>
      <ul>
        <li><a href="/index.html">accueil</a></li>
        <li><a href="/voyage/rechercher.php">découvertes</a></li>
        <li><a href="/voyage/creer.php">créer un voyage</a></li>
      </ul>
      <div id="seco-accueil">
        <a href="/auth/login.php" id="monprofil">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
            <path d="M222-255q63-44 125-67.5T480-346q71 0 133.5 23.5T739-255q44-54 62.5-109T820-480q0-145-97.5-242.5T480-820q-145 0-242.5 97.5T140-480q0 61 19 116t63 109Zm257.81-195q-57.81 0-97.31-39.69-39.5-39.68-39.5-97.5 0-57.81 39.69-97.31 39.68-39.5 97.5-39.5 57.81 0 97.31 39.69 39.5 39.68 39.5 97.5 0 57.81-39.69 97.31-39.68 39.5-97.5 39.5Zm.66 370Q398-80 325-111.5t-127.5-86q-54.5-54.5-86-127.27Q80-397.53 80-480.27 80-563 111.5-635.5q31.5-72.5 86-127t127.27-86q72.76-31.5 155.5-31.5 82.73 0 155.23 31.5 72.5 31.5 127 86t86 127.03q31.5 72.53 31.5 155T848.5-325q-31.5 73-86 127.5t-127.03 86Q562.94-80 480.47-80Zm-.47-60q55 0 107.5-16T691-212q-51-36-104-55t-107-19q-54 0-107 19t-104 55q51 40 103.5 56T480-140Zm0-370q34 0 55.5-21.5T557-587q0-34-21.5-55.5T480-664q-34 0-55.5 21.5T403-587q0 34 21.5 55.5T480-510Zm0-77Zm0 374Z" id="profil-photo" />
          </svg>
        </a>
        <p id="seconnecter">S'identifier</p>
      </div>
    </nav>
    <div class="hero-content">
      <h2 id="text-un">Bon retour parmi nous !</h2>
      <h2 id="text-deux">Connectez-vous pour<br>continuer votre aventure</h2>
    </div>
  </header>

  <main>
    <div class="form-container">
      <h2 style="color: #FF9800; margin-bottom: 20px; text-align: center;">Connexion</h2>

      <?php if (!empty($errors)): ?>
        <div style="background-color: #ffebee; color: #c62828; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
          <ul>
            <?php foreach ($errors as $error): ?>
              <li><?php echo $error; ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['success_message'])): ?>
        <div style="background-color: #e8f5e9; color: #2e7d32; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
          <?php echo $_SESSION['success_message']; ?>
          <?php unset($_SESSION['success_message']); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="login.php">
        <div class="form-row">
          <div class="form-group" style="width: 100%;">
            <label for="email">Adresse mail</label>
            <input type="email" id="email" name="email" placeholder="Votre adresse mail" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group" style="width: 100%;">
            <label for="password">Mot de passe</label>
            <div class="password-input">
              <input type="password" id="password" name="password" placeholder="Mot de passe" required>
              <button type="button" class="toggle-password" onclick="togglePassword()">👁️</button>
            </div>
          </div>
        </div>

        <button type="submit" class="btn">Se connecter</button>

        <div class="or-divider">OU</div>

        <div class="form-row">
          <div class="form-group" style="width: 100%;">
            <a href="register.php" class="connect-btn" style="display: block; text-align: center; text-decoration: none;">Créer un compte</a>
          </div>
        </div>
      </form>
    </div>
  </main>

  <footer>
    <div class="footer-text">
      <h4>Explore</h4>
      <p>Destination</p>
      <p>Destination</p>
      <p>Destination</p>
    </div>
    <div class="footer-text">
      <h4>About us</h4>
      <p>Destination</p>
      <p>Destination</p>
      <p>Destination</p>
    </div>
    <div class="footer-text">
      <h4>Support</h4>
      <p>Destination</p>
      <p>Destination</p>
      <p>Destination</p>
    </div>
    <div class="footer-text">
      <h4>Avis</h4>
      <p>Destination</p>
      <p>Destination</p>
      <p>Destination</p>
    </div>
  </footer>

  <!-- JS -->
</body>

</html>