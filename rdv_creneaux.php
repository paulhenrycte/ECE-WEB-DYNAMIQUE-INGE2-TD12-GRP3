<?php
$database = "omnes_immobilier";
$port = 8889;
$mysqli = new mysqli("localhost", "root", "root", $database, $port);

if (!isset($_GET['id'])) {
    die("Agent non spécifié.");
}
$id_agent = intval($_GET['id']);

// Récupérer l'agent
$agent_result = $mysqli->query("SELECT * FROM utilisateurs WHERE id_user = $id_agent");
if (!$agent_result || $agent_result->num_rows === 0) {
    die("Agent introuvable.");
}
$agent = $agent_result->fetch_assoc();

// Définir la semaine (fixe ou calculée dynamiquement)
$semaine = isset($_GET['semaine']) ? $_GET['semaine'] : date('Y-m-d', strtotime('monday this week'));
$jours = ["lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi"];
$dates = [];

for ($i = 0; $i < count($jours); $i++) {
    $date = date('Y-m-d', strtotime("$semaine +$i days"));
    $dates[$jours[$i]] = $date;
}

// Créneaux de 30 minutes entre 09:00 et 18:00
$creneaux = [];
$h = strtotime("09:00");
while ($h <= strtotime("18:00")) {
    $creneaux[] = date("H:i", $h);
    $h += 30 * 60;
}

// AFFICHAGE
echo "<h2>{$agent['nom']} {$agent['prenom']}</h2>";
echo "<p><strong>Spécialité :</strong> Immobilier Résidentiel</p>";
echo "<p><strong>Semaine du :</strong> " . date('d/m/Y', strtotime($semaine)) . "</p>";

echo "<table border='1' cellspacing='0' cellpadding='5'>";
echo "<tr><th>Créneaux</th>";
foreach ($dates as $jour => $date) {
    echo "<th>" . ucfirst($jour) . "<br><small>" . date('d/m', strtotime($date)) . "</small></th>";
}
echo "</tr>";

// Affichage des lignes heure par heure
foreach ($creneaux as $heure) {
    echo "<tr><td>$heure</td>";
    foreach ($dates as $jour => $date) {
        $query = "SELECT * FROM disponibilites_creneaux 
                  WHERE id_user = $id_agent AND date = '$date' AND heure = '$heure' LIMIT 1";
        $res = $mysqli->query($query);
        $style = "";
        $contenu = "";

        if ($res && $row = $res->fetch_assoc()) {
            if ($row['disponible'] == 0) {
                // Créneau bloqué (noir)
                $style = "background-color:black;color:white;";
                $contenu = "Bloqué";
            } elseif ($row['pris_par']) {
                // Créneau réservé (gris)
                $style = "background-color:gray;color:white;";
                $contenu = "Réservé";
            } else {
                // Disponible (blanc, cliquable)
                $style = "background-color:white;";
                $contenu = "<a href='confirmer_rdv.php?id_agent=$id_agent&date=$date&heure=$heure'>Réserver</a>";
            }
        } else {
            // Pas de ligne → par défaut : bloqué
            $style = "background-color:black;color:white;";
            $contenu = "—";
        }

        echo "<td style='$style; text-align:center;'>$contenu</td>";
    }
    echo "</tr>";
}
echo "</table>";

// Navigation semaine
$prev = date('Y-m-d', strtotime("$semaine -7 days"));
$next = date('Y-m-d', strtotime("$semaine +7 days"));
echo "<br><a href='rdv_creneaux.php?id=$id_agent&semaine=$prev'>← Semaine précédente</a> | ";
echo "<a href='rdv_creneaux.php?id=$id_agent&semaine=$next'>Semaine suivante →</a>";
?>
