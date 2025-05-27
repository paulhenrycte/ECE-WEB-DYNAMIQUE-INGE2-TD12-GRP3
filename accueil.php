<?php
require 'connexion.php'; // Connexion à la BDD (au cas où)
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Omnes Immobilier</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>

     <!-- NAVIGATION -->
    <?php include 'navigation.php'; ?>
    
    <div id="wrapper">

        <!-- SECTION PRINCIPALE -->
        <div id="section">

            <!-- Introduction -->
            <div id="introduction">
                <h2>Bienvenue chez Omnes Immobilier</h2>
                <p>Au service des besoins immobiliers de la communauté Omnes Education.</p>
            </div>

            <!-- Événement de la semaine / Bulletin -->
            <div id="evenement">
                <h3>Évènement de la semaine</h3>
                <p>Portes ouvertes le samedi 31 mai : venez découvrir nos nouveaux biens à Paris !</p>
            </div>

            <!-- Carrousel intégré -->
            <div class="section-carrousel">
                <?php include 'carrousel.php'; ?>
            </div>

        </div>

        <?php include("maps.php"); ?>
    </div>
    <?php include 'footer.php'; ?>

</body>
</html>
