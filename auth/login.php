<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  if (empty($email) || empty($password)) {
    $error = "Veuillez remplir tous les champs";
  } else {
    // Vérifier l'utilisateur dans la base de données
    $sql = "SELECT id, nom, prenom, email, password FROM utilisateurs WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $user = $result->fetch_assoc();

      // Vérifier le mot de passe
      if (password_verify($password, $user['password'])) {
        // Créer la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
        $_SESSION['user_email'] = $user['email'];

        // Rediriger vers la page d'accueil
        header('Location: ../index.php');
        exit;
      } else {
        $error = "Email ou mot de passe incorrect";
      }
    } else {
      $error = "Email ou mot de passe incorrect";
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
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montagu+Slab:wght@400;700&family=Unbounded:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
  <?php include_once '../includes/header.php'; ?>

  <div class="auth-container">
    <h2>Connexion</h2>

    <?php if ($error): ?>
      <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="" method="POST" class="auth-form">
      <div class="form-group">
        <label for="email">Adresse email</label>
        <input type="email" id="email" name="email" required>
      </div>

      <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>
      </div>

      <button type="submit">Se connecter</button>
    </form>

    <div class="auth-links">
      <p>Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
    </div>
  </div>

  <?php include_once '../includes/footer.php'; ?>

  <script src="../assets/js/main.js"></script>
</body>

</html>