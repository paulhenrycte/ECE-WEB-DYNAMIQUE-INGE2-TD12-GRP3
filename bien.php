<?php
$conn = mysqli_connect('localhost', 'root', '', 'omnes_immobilier');
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT b.*, u.nom AS agent_nom, u.prenom AS agent_prenom, u.email AS agent_email
        FROM biens_immobiliers b
        JOIN utilisateurs u ON b.id_agent = u.id_user
        WHERE b.id_bien = $id";

$res = mysqli_query($conn, $sql);
$bien = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Détail du bien</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
 <!-- NAVIGATION -->
<?php include 'navigation.php'; ?>

<?php if ($bien): ?>
  <div class="fiche-bien">
    <h1><?= htmlspecialchars($bien['titre']) ?></h1>
    <img src="<?= htmlspecialchars($bien['photos']) ?>" alt="Photo du bien">

    <p><strong>Description :</strong> <?= htmlspecialchars($bien['description']) ?></p>
    <p><strong>Adresse :</strong> <?= htmlspecialchars($bien['adresse']) ?>, <?= htmlspecialchars($bien['ville']) ?></p>
    <p><strong>Superficie :</strong> <?= $bien['superficie'] ?> m²</p>
    <p><strong>Nombre de pièces :</strong> <?= $bien['nb_pieces'] ?></p>
    <p><strong>Nombre de chambres :</strong> <?= $bien['nb_chambres'] ?></p>
    <p><strong>Étage :</strong> <?= $bien['etage'] ?></p>
    <p><strong>Balcon :</strong> <?= $bien['balcon'] ? 'Oui' : 'Non' ?> | <strong>Parking :</strong> <?= $bien['parking'] ? 'Oui' : 'Non' ?></p>
    <p><strong>Prix :</strong> <?= number_format($bien['prix'], 2, ',', ' ') ?> €</p>

    <h3>Agent en charge :</h3>
    <p><?= $bien['agent_prenom'] . ' ' . $bien['agent_nom'] ?></p>
    <p>Email : <?= $bien['agent_email'] ?></p>
  </div>

<?php else: ?>
  <p>Bien introuvable.</p>
<?php endif; ?>

<?php include 'footer.php'; ?>
</body>
</html>