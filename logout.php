<?php
session_start();
session_destroy(); // Supprime toutes les variables de session
header("Location: votrecompte.php"); // Redirige vers la page d'accueil
exit();
?>
