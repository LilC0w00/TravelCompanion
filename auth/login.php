<?php
// login.php
// Page de connexion pour les utilisateurs existants

session_start();
require_once 'auth.php';

// Si l'utilisateur est déjà connecté, le rediriger
if (isLoggedIn()) {
  header("Location: dashboard.php");
  exit();
}

$errors = [];

// Traitement du formulaire de connexion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  // Validation basique
  if (empty($email)) {
    $errors[] = "L'email est requis.";
  }

  if (empty($password)) {
    $errors[] = "Le mot de passe est requis.";
  }

  // Si pas d'erreurs, essayer de connecter l'utilisateur
  if (empty($errors)) {
    if (loginUser($email, $password)) {
      // Redirection vers le tableau de bord
      header("Location: dashboard.php");
      exit();
    } else {
      $errors[] = "Email ou mot de passe incorrect.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - Travel Companion</title>
  <!-- Insérez ici vos styles CSS -->
  <style>
    /* Styles de base pour le formulaire */
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
    }

    .login-container {
      max-width: 400px;
      margin: 50px auto;
      background-color: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      color: #00BCD4;
      margin-bottom: 30px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      color: #FF9800;
    }

    input {
      width: 100%;
      padding: 12px;
      border: 1px solid #e0e0e0;
      border-radius: 4px;
      font-size: 14px;
    }

    .btn {
      background-color: #00BCD4;
      color: white;
      border: none;
      padding: 12px;
      font-size: 16px;
      cursor: pointer;
      border-radius: 4px;
      width: 100%;
    }

    .btn:hover {
      background-color: #0097A7;
    }

    .errors {
      background-color: #ffebee;
      color: #c62828;
      padding: 10px;
      border-radius: 4px;
      margin-bottom: 20px;
    }

    .register-link {
      text-align: center;
      margin-top: 20px;
    }

    .register-link a {
      color: #00BCD4;
      text-decoration: none;
    }

    .register-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <div class="login-container">
    <h1>Connexion</h1>

    <?php if (!empty($errors)): ?>
      <div class="errors">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?php echo $error; ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form action="login.php" method="post">
      <div class="form-group">
        <label for="email">Adresse Email</label>
        <input type="email" id="email" name="email" placeholder="Votre adresse email" required>
      </div>

      <div class="form-group">
        <label for="password">Mot de Passe</label>
        <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
      </div>

      <button type="submit" class="btn">Se connecter</button>
    </form>

    <div class="register-link">
      Pas encore de compte? <a href="index.php">S'inscrire</a>
    </div>
  </div>
</body>

</html>