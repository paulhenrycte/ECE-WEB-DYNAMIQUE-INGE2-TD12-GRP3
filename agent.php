<?php
$database = "omnes_immobilier";
$port = 8889;
$mysqli = new mysqli("localhost", "root", "root", $database, $port);

$id_agent = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_agent <= 0) {
    die("ID agent invalide.");
}

$agent = $mysqli->query("SELECT * FROM utilisateurs WHERE id_user = $id_agent")->fetch_assoc();
$dispo = $mysqli->query("SELECT * FROM emplois_du_temps WHERE id_user = $id_agent");
$infos = $mysqli->query("SELECT * FROM infos_agents WHERE id_user = $id_agent")->fetch_assoc();
$jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil de l'agent</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f6f8fa; padding: 20px; font-size: 14px; }
        .carte-agent { display: flex; align-items: center; gap: 20px; background: white; border-radius: 12px; padding: 20px; box-shadow: 0 0 8px rgba(0,0,0,0.1); width: fit-content; margin: auto; }
        .carte-agent img.photo { width: 120px; height: auto; border-radius: 8px; }
        .infos-agent { flex: 1; }
        .infos-agent h2 { margin: 0; font-size: 20px; color: #222; }
        .infos-agent p { margin: 4px 0; }
        .infos-agent .role { color: red; font-weight: bold; }
        .calendrier { font-size: 12px; margin-top: 10px; }
        table { border-collapse: collapse; width: 100%; text-align: center; font-size: 12px; }
        th, td { border: 1px solid #999; padding: 4px; }
        th { background: #e0e0e0; }
        .disponible { background-color: #e0ffe0; color: #006600; font-weight: bold; }
        .indisponible { background-color: #f8f8f8; color: #999; }
        .actions { margin-top: 15px; text-align: center; display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
        .btn { display: inline-block; background: #007BFF; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 14px; transition: background 0.3s ease; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="carte-agent">
        <img class="photo" src="<?php echo $agent['photo_profil']; ?>" alt="photo agent">
        <div class="infos-agent">
            <h2><?php echo $agent['nom'] . " " . $agent['prenom']; ?></h2>
            <p class="role">Agent Immobilier agréé</p>
            <p><strong>Téléphone:</strong> <?php echo $infos['telephone']; ?></p>
            <p><strong>Email:</strong> <?php echo $agent['email']; ?></p>
            <div class="calendrier">
                <table>
                    <tr>
                        <th>Jour</th>
                        <th>AM</th>
                        <th>PM</th>
                    </tr>
                    <?php while ($row = $dispo->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo ucfirst($row['jour_semaine']); ?></td>
                        <td class="<?php echo ($row['AM']) ? 'disponible' : 'indisponible'; ?>">
                            <?php echo ($row['AM']) ? "Disponible" : "Indisponible"; ?>
                        </td>
                        <td class="<?php echo ($row['PM']) ? 'disponible' : 'indisponible'; ?>">
                            <?php echo ($row['PM']) ? "Disponible" : "Indisponible"; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
            <div class="actions">
                <a class="btn" href="rdv_creneaux.php?id=<?php echo $id_agent; ?>">Prendre un rendez-vous</a>
                <a class="btn" href="<?php echo $infos['cv_url']; ?>" target="_blank">Voir son CV</a>
                <a class="btn" href="contact_agent.php?id=<?php echo $id_agent; ?>">Communiquer avec l'agent immobilier</a>
            </div>
        </div>
    </div>
</body>
</html>
