<?php
require 'connexion.php';

$type = $_GET['type'] ?? "";
$filtre = $_GET['filtre'] ?? "";
$ville = $_GET['ville'] ?? "";

// Liste des villes distinctes pour le menu déroulant
try {
    $villes_stmt = $pdo->query("SELECT DISTINCT ville FROM biens_immobiliers WHERE ville IS NOT NULL AND ville != ''");
    $villes = $villes_stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $villes = [];
}

// Construction dynamique de la requete
$sql = "
    SELECT b.*, u.nom AS agent_nom, u.prenom AS agent_prenom
    FROM biens_immobiliers b
    JOIN utilisateurs u ON b.id_agent = u.id_user
    WHERE b.vendu = 0
";

$params = [];

if (!empty($type)) {
    $sql .= " AND b.type_bien = :type";
    $params['type'] = $type;
}

if (!empty($ville)) {
    $sql .= " AND b.ville = :ville";
    $params['ville'] = $ville;
}


switch ($filtre) {
    case "prix_asc": $sql .= " ORDER BY b.prix ASC"; break;
    case "prix_desc": $sql .= " ORDER BY b.prix DESC"; break;
    case "recent": $sql .= " ORDER BY b.id_bien DESC"; break;
}

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
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

    <!-- Liens de categorie (type de bien) -->
    <div class="categories">
        <a href="tout_parcourir.php?type=résidentiel">Résidentiel</a>
        <a href="tout_parcourir.php?type=commercial">Commercial</a>
        <a href="tout_parcourir.php?type=terrain">Terrain</a>
        <a href="tout_parcourir.php?type=appartement">Appartement</a>
        <a href="tout_parcourir.php?type=enchere">Enchère</a>
        <a href="tout_parcourir.php">Tous</a>
    </div>

    <!-- Filtres ville + tri -->
    <div class="filtres-container">
        <form method="get" action="">
            <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">

            <label for="ville">Ville :</label>
            <select name="ville" id="ville">
                <option value="">-- Toutes --</option>
                <?php foreach ($villes as $v): ?>
                    <option value="<?= $v ?>" <?= ($v === $ville) ? 'selected' : '' ?>><?= $v ?></option>
                <?php endforeach; ?>
            </select>

            <label for="filtre">Trier :</label>
            <select name="filtre" id="filtre">
                <option value="">-- Aucun --</option>
                <option value="prix_asc" <?= ($filtre === 'prix_asc') ? 'selected' : '' ?>>Prix croissant</option>
                <option value="prix_desc" <?= ($filtre === 'prix_desc') ? 'selected' : '' ?>>Prix décroissant</option>
                <option value="recent" <?= ($filtre === 'recent') ? 'selected' : '' ?>>Nouveauté</option>
            </select>

            <input type="submit" value="Appliquer">
        </form>
    </div>

    <!-- Affichage des biens -->
    <?php if (count($biens) > 0): ?>
        <?php foreach ($biens as $row): ?>
            <a href="bien.php?id=<?= $row['id_bien'] ?>">
                <div class="carte">
                    <div><img src="<?= htmlspecialchars($row['photos']) ?>" alt="<?= htmlspecialchars($row['titre']) ?>"></div>
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
