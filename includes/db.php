<?php
// Connexion à la base de données
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Vérifier la connexion
if ($conn->connect_error) {
  die('Erreur de connexion à la base de données : ' . $conn->connect_error);
}
