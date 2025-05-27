<?php
$conn = mysqli_connect('localhost', 'root', '', 'omnes_immobilier');
if (!$conn) {
    die("Erreur de connexion à la base de données.");
}

$sql = "SELECT id_bien, titre, latitude, longitude FROM biens_immobiliers WHERE latitude IS NOT NULL AND longitude IS NOT NULL";
$res = mysqli_query($conn, $sql);

$biens = [];
while ($row = mysqli_fetch_assoc($res)) {
    $biens[] = $row;
}
?>

<!-- Carte interactive avec Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<div id="map"></div>

<script>
    const map = L.map('map').setView([46.5, 2.5], 6); // Centre France

    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.fr/">OpenStreetMap France</a>',
        minZoom: 1,
        maxZoom: 20
    }).addTo(map);

    const biens = <?php echo json_encode($biens); ?>;

    biens.forEach(bien => {
        const marker = L.marker([bien.latitude, bien.longitude]).addTo(map);
        marker.bindPopup(
            `<a href='bien.php?id=${bien.id_bien}' style='text-decoration:none; font-weight:bold;'>${bien.titre}</a>`
        );
    });
</script>
