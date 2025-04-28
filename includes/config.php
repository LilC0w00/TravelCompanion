<?php
// Informations de connexion à la base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // À modifier selon votre configuration MySQL
define('DB_PASS', ''); // À modifier selon votre configuration MySQL
define('DB_NAME', 'travel_companion');

// Configuration du site
define('SITE_NAME', 'Travel.Companion');
define('SITE_URL', 'http://localhost/travelcompanion'); // À modifier selon votre environnement

// Fuseau horaire
date_default_timezone_set('Europe/Paris');

// Gestion des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1); // Mettre à 0 en production
