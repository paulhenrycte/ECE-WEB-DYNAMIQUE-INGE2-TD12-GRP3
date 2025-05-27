<?php
$host = 'localhost';
$dbname = 'omnes_immobilier';
$user = 'root'; // ou autre identifiant si tu l'as changé
$pass = '';     // vide par défaut sous WAMP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Pour voir les erreurs SQL plus facilement :
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
