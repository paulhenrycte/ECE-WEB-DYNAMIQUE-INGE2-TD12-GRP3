<?php
session_start();
$database = "omnes_immobilier";
$mysqli = new mysqli("localhost", "root", "root", $database);

// DEBUG : connexion test client Emma
$_SESSION['id_user'] = 2;
$_SESSION['type_utilisateur'] = 'agent';

$id_user = $_SESSION['id_user'] ?? null;
$type_utilisateur = $_SESSION['type_utilisateur'] ?? null;

if (!$id_user || !in_array($type_utilisateur, ['client', 'agent'])) {
    die("Accès non autorisé.");
}

// Annulation d'un rendez-vous par un client
if (isset($_GET['annuler']) && $type_utilisateur === 'client') {
    $id_rdv = intval($_GET['annuler']);
    $mysqli->query("UPDATE disponibilites_creneaux SET disponible = 1, pris_par = NULL WHERE id = $id_rdv AND pris_par = $id_user");
    header("Location: rendez_vous.php");
    exit();
}

// Récupération des rendez-vous
if ($type_utilisateur === 'client') {
    $result = $mysqli->query("SELECT d.*, a.nom AS agent_nom, a.prenom AS agent_prenom FROM disponibilites_creneaux d 
                              JOIN utilisateurs a ON d.id_user = a.id_user
                              WHERE d.pris_par = $id_user
                              ORDER BY d.date, d.heure");
} else {
    $result = $mysqli->query("SELECT d.*, c.nom AS client_nom, c.prenom AS client_prenom FROM disponibilites_creneaux d 
                              JOIN utilisateurs c ON d.pris_par = c.id_user
                              WHERE d.id_user = $id_user AND d.pris_par IS NOT NULL
                              ORDER BY d.date, d.heure");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes rendez-vous</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f9f9f9; padding: 30px; }
        h2 { text-align: center; color: #333; }
        table { border-collapse: collapse; width: 90%; margin: auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; font-size: 14px; }
        th { background-color: #f0f0f0; }
        .btn-annuler { color: white; background-color: #dc3545; padding: 5px 10px; border-radius: 4px; text-decoration: none; }
        .btn-annuler:hover { background-color: #c82333; }
    </style>
</head>
<body>
    <h2>Mes rendez-vous</h2>
    <table>
        <tr>
            <th>Date</th>
            <th>Heure</th>
            <?php if ($type_utilisateur === 'client'): ?>
                <th>Agent</th>
                <th>Action</th>
            <?php else: ?>
                <th>Client</th>
            <?php endif; ?>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['date']); ?></td>
                <td><?php echo htmlspecialchars($row['heure']); ?></td>
                <?php if ($type_utilisateur === 'client'): ?>
                    <td><?php echo htmlspecialchars($row['agent_prenom'] . ' ' . $row['agent_nom']); ?></td>
                    <td><a class="btn-annuler" href="?annuler=<?php echo $row['id']; ?>">Annuler</a></td>
                <?php else: ?>
                    <td><?php echo htmlspecialchars($row['client_prenom'] . ' ' . $row['client_nom']); ?></td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
