<?php
// auth.php
// Fonctions pour gérer l'authentification des utilisateurs

require_once '../includes/config.php';

// Fonction pour vérifier si un utilisateur est connecté
function isLoggedIn()
{
  return isset($_SESSION['user_id']);
}

// Fonction pour connecter un utilisateur
function loginUser($email, $password)
{
  $conn = getDbConnection();

  // Rechercher l'utilisateur par email
  $stmt = $conn->prepare("SELECT id, prenom, mot_de_passe FROM utilisateurs WHERE email = ?");
  $stmt->execute([$email]);

  if ($stmt->rowCount() > 0) {
    $user = $stmt->fetch();

    // Vérifier le mot de passe
    if (password_verify($password, $user['mot_de_passe'])) {
      // Créer une session pour l'utilisateur
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_email'] = $email;
      $_SESSION['user_name'] = $user['prenom'];

      // Mettre à jour l'enregistrement de session dans la base de données
      updateSession($user['id']);

      return true;
    }
  }

  return false;
}

// Fonction pour déconnecter un utilisateur
function logoutUser()
{
  // Supprimer la session de la base de données si nécessaire
  if (isset($_SESSION['user_id'])) {
    deleteSession(session_id());
  }

  // Détruire toutes les données de session
  $_SESSION = array();

  // Détruire le cookie de session si nécessaire
  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
      session_name(),
      '',
      time() - 42000,
      $params["path"],
      $params["domain"],
      $params["secure"],
      $params["httponly"]
    );
  }

  // Détruire la session
  session_destroy();
}

// Fonction pour mettre à jour l'enregistrement de session dans la base de données
function updateSession($user_id)
{
  $conn = getDbConnection();

  $session_id = session_id();
  $ip_address = $_SERVER['REMOTE_ADDR'];
  $timestamp = time();
  $data = serialize($_SESSION);

  // Vérifier si cette session existe déjà
  $stmt = $conn->prepare("SELECT id FROM sessions WHERE id = ?");
  $stmt->execute([$session_id]);

  if ($stmt->rowCount() > 0) {
    // Mettre à jour la session existante
    $stmt = $conn->prepare("UPDATE sessions SET timestamp = ?, data = ? WHERE id = ?");
    $stmt->execute([$timestamp, $data, $session_id]);
  } else {
    // Créer une nouvelle session
    $stmt = $conn->prepare("INSERT INTO sessions (id, utilisateur_id, ip_address, timestamp, data) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$session_id, $user_id, $ip_address, $timestamp, $data]);
  }
}

// Fonction pour supprimer une session de la base de données
function deleteSession($session_id)
{
  $conn = getDbConnection();

  $stmt = $conn->prepare("DELETE FROM sessions WHERE id = ?");
  $stmt->execute([$session_id]);
}

// Fonction pour nettoyer les sessions expirées
function cleanSessions($max_lifetime = 3600)
{
  $conn = getDbConnection();

  $old_timestamp = time() - $max_lifetime;

  $stmt = $conn->prepare("DELETE FROM sessions WHERE timestamp < ?");
  $stmt->execute([$old_timestamp]);
}

// Fonction pour récupérer les informations de l'utilisateur connecté
function getCurrentUser()
{
  if (!isLoggedIn()) {
    return null;
  }

  $conn = getDbConnection();

  $stmt = $conn->prepare("SELECT id, nom, prenom, email, age, telephone, pays FROM utilisateurs WHERE id = ?");
  $stmt->execute([$_SESSION['user_id']]);

  if ($stmt->rowCount() > 0) {
    return $stmt->fetch();
  }

  return null;
}
