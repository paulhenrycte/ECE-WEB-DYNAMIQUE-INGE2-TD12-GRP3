<?php
session_start();

// DEBUG : connexion test client Emma
$_SESSION['id_user'] = 3;
$_SESSION['type_utilisateur'] = 'client';

$database = "omnes_immobilier";
$port = 8889;
$mysqli = new mysqli("localhost", "root", "root", $database, $port);

$id_agent = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_agent <= 0) {
    die("ID agent invalide.");
}

$id_client = $_SESSION['id_user'] ?? null;
$type_utilisateur = $_SESSION['type_utilisateur'] ?? null;

// Réservation ou libération d'un créneau
if (isset($_GET['toggle']) && isset($_GET['date']) && isset($_GET['heure'])) {
    $date = $mysqli->real_escape_string($_GET['date']);
    $heure = $mysqli->real_escape_string($_GET['heure']);

    if (!$id_client || $type_utilisateur != 'client') {
        die("Seuls les clients peuvent réserver un créneau.");
    }

    $check = $mysqli->query("SELECT disponible, pris_par FROM disponibilites_creneaux WHERE id_user = $id_agent AND date = '$date' AND heure = '$heure'")->fetch_assoc();
    if ($check) {
        $new_val = $check['disponible'] ? 0 : 1;
        $pris_par = $new_val ? "NULL" : $id_client;

        $mysqli->query("UPDATE disponibilites_creneaux 
            SET disponible = $new_val, pris_par = $pris_par 
            WHERE id_user = $id_agent AND date = '$date' AND heure = '$heure'");
    }
    header("Location: rdv_creneaux.php?id=$id_agent");
    exit();
}

$semaine = [];
$jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
$aujourdhui = new DateTime();
$debutSemaine = clone $aujourdhui;
$debutSemaine->modify('monday this week');

for ($i = 0; $i < 6; $i++) {
    $date = clone $debutSemaine;
    $date->modify("+{$i} days");
    $date_str = $date->format('Y-m-d');
    $jour_nom = $jours[$i];
    $semaine[$jour_nom] = ['date' => $date_str, 'creneaux' => []];

    $result = $mysqli->query("SELECT * FROM disponibilites_creneaux WHERE id_user = $id_agent AND date = '$date_str' ORDER BY heure ASC");
    while ($row = $result->fetch_assoc()) {
        $semaine[$jour_nom]['creneaux'][] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Prise de rendez-vous</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f6f8fa; padding: 20px; }
        h2 { text-align: center; color: #333; }
        table { border-collapse: collapse; width: 100%; text-align: center; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 10px; }
        th { background-color: #f0f0f0; font-weight: bold; }
        td.dispo a { background-color: #e0ffe0; color: #006600; padding: 5px 10px; border-radius: 5px; text-decoration: none; display: inline-block; }
        td.occupe a { background-color: #007BFF; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; display: inline-block; }
        td.dispo a:hover { background-color: #c0f0c0; }
        .btn-retour { display: inline-block; background: #6c757d; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; transition: background 0.3s ease; }
        .btn-retour:hover { background: #5a6268; }
    </style>
</head>
<body>
    <h2>Calendrier des disponibilités de l'agent #<?php echo $id_agent; ?></h2>
    <table>
        <tr>
            <th>Heure</th>
            <?php foreach ($semaine as $jour => $infos): ?>
                <th><?php echo ucfirst($jour) . "<br>" . $infos['date']; ?></th>
            <?php endforeach; ?>
        </tr>

        <?php
        $heures = [];
        foreach ($semaine as $infos) {
            foreach ($infos['creneaux'] as $c) {
                if (!in_array($c['heure'], $heures)) {
                    $heures[] = $c['heure'];
                }
            }
        }
        sort($heures);

        foreach ($heures as $heure) {
            echo "<tr><td>$heure</td>";
            foreach ($semaine as $infos) {
                $trouve = false;
                foreach ($infos['creneaux'] as $c) {
                    if ($c['heure'] == $heure) {
                        $trouve = true;
                        if ($c['disponible']) {
                            echo "<td class='dispo'><a href='?id=$id_agent&toggle=1&date={$infos['date']}&heure={$c['heure']}'>Disponible</a></td>";
                        } else {
                            echo "<td class='occupe'><a href='?id=$id_agent&toggle=1&date={$infos['date']}&heure={$c['heure']}'>Occupé</a></td>";
                        }
                        break;
                    }
                }
                if (!$trouve) echo "<td></td>";
            }
            echo "</tr>";
        }
        ?>
    </table>
    <div style="text-align:center; margin-top: 20px;">
        <a href="agent.php?id=<?php echo $id_agent; ?>" class="btn-retour">Retour au profil de l'agent</a>
    </div>
</body>
</html>
