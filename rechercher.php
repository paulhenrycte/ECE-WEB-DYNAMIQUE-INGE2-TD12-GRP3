<?php
require 'connexion.php'; // connexion via PDO
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Recherche immobilière</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'navigation.php'; ?>

<div id="wrapper">

  <h2>Recherche immobilière</h2>

  <!-- Formulaire de recherche -->
  <form method="post" action="">
    <label for="motcle">Rechercher un ID de bien, une ville ou un nom d'agent :</label><br>
    <input type="text" name="motcle" id="motcle" required>
    <input type="submit" value="Rechercher">
  </form>

  <?php
  if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["motcle"])) {
      $motcle = trim($_POST["motcle"]);
      $resultats = [];

      try {
          // Recherche par ID
          if (is_numeric($motcle)) {
              $stmt = $pdo->prepare("
                  SELECT b.*, u.nom AS agent_nom, u.prenom AS agent_prenom
                  FROM biens_immobiliers b
                  JOIN utilisateurs u ON b.id_agent = u.id_user
                  WHERE b.id_bien = ?
              ");
              $stmt->execute([$motcle]);
              $resultats = $stmt->fetchAll();
          }

          // Recherche par ville
          if (empty($resultats)) {
              $stmt = $pdo->prepare("
                  SELECT b.*, u.nom AS agent_nom, u.prenom AS agent_prenom
                  FROM biens_immobiliers b
                  JOIN utilisateurs u ON b.id_agent = u.id_user
                  WHERE b.ville LIKE ?
              ");
              $stmt->execute(["%$motcle%"]);
              $resultats = $stmt->fetchAll();
          }

          // Recherche par nom d’agent
          if (empty($resultats)) {
              $stmt = $pdo->prepare("
                  SELECT b.*, u.nom AS agent_nom, u.prenom AS agent_prenom
                  FROM biens_immobiliers b
                  JOIN utilisateurs u ON b.id_agent = u.id_user
                  WHERE u.nom LIKE ?
              ");
              $stmt->execute(["%$motcle%"]);
              $resultats = $stmt->fetchAll();
          }

          // Affichage des résultats
          if (count($resultats) > 0) {
              foreach ($resultats as $row) {
                  echo "<a href='bien.php?id={$row['id_bien']}'>
                          <div class='carte'>
                              <div><img src='" . htmlspecialchars($row['photos']) . "' alt='photo'></div>
                              <div class='infos'>
                                  <p><strong>" . htmlspecialchars($row['titre']) . "</strong></p>
                                  <p>" . htmlspecialchars($row['adresse']) . ", " . htmlspecialchars($row['ville']) . "</p>
                                  <p>" . htmlspecialchars($row['description']) . "</p>";

                  if (!empty($row['prix'])) {
                      echo "<p><strong>Prix :</strong> " . number_format($row['prix'], 2, ',', ' ') . " €</p>";
                  } else {
                      echo "<p><strong>Prix :</strong> Non précisé</p>";
                  }

                  echo "        </div>
                              <div class='agent'>
                                  <p><strong>Agent :</strong><br>" . htmlspecialchars($row['agent_prenom']) . " " . htmlspecialchars($row['agent_nom']) . "</p>
                              </div>
                          </div>
                        </a>";
              }
          } else {
              echo "<p>Aucun résultat trouvé pour « <em>" . htmlspecialchars($motcle) . "</em> ».</p>";
          }

      } catch (PDOException $e) {
          echo "<p>Erreur lors de la recherche : " . $e->getMessage() . "</p>";
      }
  }
  ?>

</div>

<?php include 'footer.php'; ?>
</body>
</html>