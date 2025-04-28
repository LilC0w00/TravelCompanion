<?php
// config/database.php
// Fichier de configuration pour la connexion à la base de données

define('DB_HOST', 'localhost');
define('DB_NAME', 'travel_companion');
define('DB_USER', 'root'); // À remplacer par votre nom d'utilisateur MySQL
define('DB_PASSWORD', ''); // À remplacer par votre mot de passe

// Fonction pour établir une connexion à la base de données
function getDbConnection()
{
  try {
    $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    // Configuration pour que PDO lance des exceptions en cas d'erreur
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Pour récupérer les résultats sous forme d'objets
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $conn;
  } catch (PDOException $e) {
    // En production, vous voudrez peut-être journaliser cette erreur plutôt que de l'afficher
    die("Erreur de connexion à la base de données: " . $e->getMessage());
  }
}
