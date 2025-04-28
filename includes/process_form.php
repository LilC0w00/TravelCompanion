<?php
// process_form.php
// Script qui traite la soumission du formulaire

// Inclusion de la configuration de la base de données
require_once 'config/database.php';
session_start();

// Fonction pour nettoyer les entrées utilisateur
function cleanInput($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Récupération et nettoyage des données du formulaire
  $nom = cleanInput($_POST['nom']);
  $prenom = cleanInput($_POST['prenom']);
  $age = intval($_POST['age']);
  $email = cleanInput($_POST['email']);
  $telephone = cleanInput($_POST['telephone']);
  $pays = cleanInput($_POST['pays']);
  $mot_de_passe = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashage du mot de passe

  $date_debut = $_POST['date_debut'];
  $date_fin = $_POST['date_fin'];
  $chambres = intval($_POST['chambres']);
  $prix = floatval($_POST['prix']);
  $description = cleanInput($_POST['description']);

  // Validation basique des données
  $errors = [];

  if (empty($nom) || strlen($nom) > 100) {
    $errors[] = "Le nom est requis et ne doit pas dépasser 100 caractères.";
  }

  if (empty($prenom) || strlen($prenom) > 100) {
    $errors[] = "Le prénom est requis et ne doit pas dépasser 100 caractères.";
  }

  if ($age <= 0) {
    $errors[] = "L'âge doit être supérieur à 0.";
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Format d'email invalide.";
  }

  if (empty($telephone)) {
    $errors[] = "Le numéro de téléphone est requis.";
  }

  if (strtotime($date_fin) <= strtotime($date_debut)) {
    $errors[] = "La date de fin doit être postérieure à la date de début.";
  }

  // Si aucune erreur, procéder à l'enregistrement
  if (empty($errors)) {
    try {
      $conn = getDbConnection();

      // Commencer une transaction
      $conn->beginTransaction();

      // Vérifier si l'utilisateur existe déjà
      $stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
      $stmt->execute([$email]);

      if ($stmt->rowCount() > 0) {
        // L'utilisateur existe déjà, récupérer son ID
        $user = $stmt->fetch();
        $utilisateur_id = $user['id'];
      } else {
        // Insérer le nouvel utilisateur
        $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, prenom, age, email, telephone, pays, mot_de_passe) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $age, $email, $telephone, $pays, $mot_de_passe]);

        $utilisateur_id = $conn->lastInsertId();
      }

      // Insérer la réservation
      $stmt = $conn->prepare("INSERT INTO reservations (utilisateur_id, date_debut, date_fin, nombre_chambres, prix_nuit, description) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->execute([$utilisateur_id, $date_debut, $date_fin, $chambres, $prix, $description]);

      // Valider la transaction
      $conn->commit();

      // Créer une session pour l'utilisateur
      $_SESSION['user_id'] = $utilisateur_id;
      $_SESSION['user_email'] = $email;
      $_SESSION['user_name'] = $prenom;

      // Rediriger vers la page de confirmation
      header("Location: confirmation.php");
      exit();
    } catch (PDOException $e) {
      // En cas d'erreur, annuler la transaction
      $conn->rollBack();
      $errors[] = "Erreur lors de l'enregistrement: " . $e->getMessage();
    }
  }

  // S'il y a des erreurs, les stocker dans la session pour les afficher
  if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['form_data'] = $_POST; // Stocker les données pour pré-remplir le formulaire

    // Rediriger vers le formulaire
    header("Location: index.php");
    exit();
  }
}
