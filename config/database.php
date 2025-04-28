<?php
// Paramètres de connexion à la base de données
$host = "localhost";
$dbname = "travel_companion";
$username = "root"; // Change selon ton environnement
$password = ""; // Change selon ton environnement

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
  // Configurer PDO pour qu'il lance des exceptions en cas d'erreur
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Erreur de connexion à la base de données: " . $e->getMessage());
}
