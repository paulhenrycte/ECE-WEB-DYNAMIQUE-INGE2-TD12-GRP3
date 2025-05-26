<?php

$database = "omnes_immobilier";
$port = 8889;
$mysqli = new mysqli("localhost", "root", "root", $database, $port);

$id_agent = $_GET['id']; // Exemple : ?id=2
$agent = $mysqli->query("SELECT * FROM utilisateurs WHERE id_user = $id_agent")->fetch_assoc();
$dispo = $mysqli->query("SELECT * FROM emplois_du_temps WHERE id_user = $id_agent");

$jours = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
?>

<h2><?php echo $agent['nom'] . " " . $agent['prenom']; ?></h2>
<p><strong>Spécialité :</strong> Immobilier Résidentiel</p>
<p><strong>Téléphone :</strong> <?php echo $agent['email']; ?></p>
<p><strong>Email :</strong> <?php echo $agent['email']; ?></p>
<img src="<?php echo $agent['photo_profil']; ?>" width="150"><br><br>

<h3>Emploi du temps hebdomadaire (AM / PM)</h3>
<table border="1">
    <tr>
        <th>Jour</th>
        <th>Matin (AM)</th>
        <th>Après-midi (PM)</th>
    </tr>
    <?php while ($row = $dispo->fetch_assoc()): ?>
    <tr>
        <td><?php echo ucfirst($row['jour_semaine']); ?></td>
        <td><?php echo ($row['AM']) ? "Disponible" : "Indisponible"; ?></td>
        <td><?php echo ($row['PM']) ? "Disponible" : "Indisponible"; ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<br>
<a href="rdv_creneaux.php?id=<?php echo $id_agent; ?>">Prendre un rendez-vous</a>
