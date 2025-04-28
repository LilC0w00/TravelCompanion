<?php
// confirmation.php
// Page de confirmation après soumission du formulaire

session_start();
require_once '../auth/auth.php';

// Rediriger si l'utilisateur n'est pas connecté
if (!isLoggedIn()) {
  header("Location: ../auth/login.php");
  exit();
}

$user = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Confirmation - Travel Companion</title>
  <!-- Insérez ici vos styles CSS -->
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
    }

    .confirmation-container {
      max-width: 800px;
      margin: 50px auto;
      background-color: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: #00BCD4;
      margin-bottom: 20px;
    }

    .success-message {
      background-color: #e8f5e9;
      color: #2e7d32;
      padding: 15px;
      border-radius: 4px;
      margin-bottom: 30px;
    }

    .user-info {
      margin-bottom: 30px;
    }

    .user-info h2 {
      color: #FF9800;
      margin-bottom: 15px;
    }

    .info-item {
      margin-bottom: 10px;
    }

    .info-label {
      font-weight: bold;
      display: inline-block;
      width: 150px;
    }

    .btn {
      background-color: #00BCD4;
      color: white;
      border: none;
      padding: 12px 24px;
      font-size: 16px;
      cursor: pointer;
      border-radius: 4px;
      text-decoration: none;
      display: inline-block;
    }

    .btn:hover {
      background-color: #0097A7;
    }
  </style>
</head>

<body>
  <div class="confirmation-container">
    <h1>Confirmation de réservation</h1>

    <div class="success-message">
      <p>Félicitations <?php echo htmlspecialchars($user['prenom']); ?>! Votre réservation a été enregistrée avec succès.</p>
    </div>

    <div class="user-info">
      <h2>Vos informations</h2>

      <div class="info-item">
        <span class="info-label">Nom:</span>
        <span><?php echo htmlspecialchars($user['nom']); ?></span>
      </div>

      <div class="info-item">
        <span class="info-label">Prénom:</span>
        <span><?php echo htmlspecialchars($user['prenom']); ?></span>
      </div>

      <div class="info-item">
        <span class="info-label">Email:</span>
        <span><?php echo htmlspecialchars($user['email']); ?></span>
      </div>

      <div class="info-item">
        <span class="info-label">Téléphone:</span>
        <span><?php echo htmlspecialchars($user['telephone']); ?></span>
      </div>

      <div class="info-item">
        <span class="info-label">Pays:</span>
        <span><?php echo htmlspecialchars($user['pays']); ?></span>
      </div>
    </div>

    <?php
    // Récupérer les détails de la dernière réservation
    if (isset($_SESSION['user_id'])) {
      require_once 'config/database.php';
      $conn = getDbConnection();

      $stmt = $conn->prepare("
                SELECT * FROM reservations 
                WHERE utilisateur_id = ? 
                ORDER BY date_creation DESC 
                LIMIT 1
            ");
      $stmt->execute([$_SESSION['user_id']]);

      if ($stmt->rowCount() > 0) {
        $reservation = $stmt->fetch();
    ?>
        <div class="user-info">
          <h2>Détails de votre réservation</h2>

          <div class="info-item">
            <span class="info-label">Date de début:</span>
            <span><?php echo date('d/m/Y', strtotime($reservation['date_debut'])); ?></span>
          </div>

          <div class="info-item">
            <span class="info-label">Date de fin:</span>
            <span><?php echo date('d/m/Y', strtotime($reservation['date_fin'])); ?></span>
          </div>

          <div class="info-item">
            <span class="info-label">Nombre de chambres:</span>
            <span><?php echo $reservation['nombre_chambres']; ?></span>
          </div>

          <div class="info-item">
            <span class="info-label">Prix par nuit:</span>
            <span><?php echo $reservation['prix_nuit']; ?> €</span>
          </div>

          <?php if (!empty($reservation['description'])): ?>
            <div class="info-item">
              <span class="info-label">Description:</span>
              <span><?php echo nl2br(htmlspecialchars($reservation['description'])); ?></span>
            </div>
          <?php endif; ?>

          <div class="info-item">
            <span class="info-label">Total:</span>
            <span>
              <?php
              $date1 = new DateTime($reservation['date_debut']);
              $date2 = new DateTime($reservation['date_fin']);
              $interval = $date1->diff($date2);
              $days = $interval->days;
              $total = $days * $reservation['prix_nuit'] * $reservation['nombre_chambres'];
              echo $total . ' €';
              ?>
            </span>
          </div>
        </div>
    <?php
      }
    }
    ?>

    <a href="dashboard.php" class="btn">Accéder à votre espace</a>
  </div>
</body>

</html>