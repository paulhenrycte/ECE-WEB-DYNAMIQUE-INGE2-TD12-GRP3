<?php
require 'connexion.php'; // utilise $pdo (PDO)

$filtre = $_GET['filtre'] ?? "";
$order = "";

// Détermine l'ordre de tri
switch ($filtre) {
    case "type": $order = "ORDER BY b.type_bien"; break;
    case "ville": $order = "ORDER BY b.ville"; break;
    case "prix_asc": $order = "ORDER BY b.prix ASC"; break;
    case "prix_desc": $order = "ORDER BY b.prix DESC"; break;
    default: $order = ""; // Aucun filtre
}

$sql = "
    SELECT b.*, u.nom AS agent_nom, u.prenom AS agent_prenom
    FROM biens_immobiliers b
    JOIN utilisateurs u ON b.id_agent = u.id_user
    $order
";

try {
    $stmt = $pdo->query($sql);
    $biens = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tout Parcourir</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'navigation.php'; ?>

<div id="wrapper">

    <h2>Tous les biens disponibles</h2>

    <!-- Formulaire de tri -->
    <form method="get" action="tout_parcourir.php">
        <label for="filtre">Trier par :</label>
        <select name="filtre" id="filtre">
            <option value="">-- Aucun filtre --</option>
            <option value="type" <?= ($filtre === 'type') ? 'selected' : '' ?>>Type de bien</option>
            <option value="ville" <?= ($filtre === 'ville') ? 'selected' : '' ?>>Ville</option>
            <option value="prix_asc" <?= ($filtre === 'prix_asc') ? 'selected' : '' ?>>Prix croissant</option>
            <option value="prix_desc" <?= ($filtre === 'prix_desc') ? 'selected' : '' ?>>Prix décroissant</option>
        </select>
        <input type="submit" value="Appliquer">
    </form>

    <?php if (count($biens) > 0): ?>
        <?php foreach ($biens as $row): ?>
            <a href="bien.php?id=<?= $row['id_bien'] ?>">
                <div class="carte">
                    <div><img src="<?= htmlspecialchars($row['photos']) ?>" alt="photo"></div>
                    <div class="infos">
                        <p><strong><?= htmlspecialchars($row['titre']) ?></strong></p>
                        <p><?= htmlspecialchars($row['adresse']) ?>, <?= htmlspecialchars($row['ville']) ?></p>
                        <p><?= htmlspecialchars($row['description']) ?></p>
                        <?php if (!empty($row['prix'])): ?>
                            <p><strong>Prix :</strong> <?= number_format($row['prix'], 2, ',', ' ') ?> €</p>
                        <?php else: ?>
                            <p><strong>Prix :</strong> Non précisé</p>
                        <?php endif; ?>
                    </div>
                    <div class="agent">
                        <p><strong>Agent :</strong><br><?= htmlspecialchars($row['agent_prenom']) . " " . htmlspecialchars($row['agent_nom']) ?></p>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun bien trouvé.</p>
    <?php endif; ?>

</div>

<?php include 'footer.php'; ?>
</body>
</html>