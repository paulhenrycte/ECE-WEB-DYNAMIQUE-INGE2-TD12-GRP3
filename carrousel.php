<?php
$conn = mysqli_connect('localhost', 'root', '', 'omnes_immobilier');
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

$sql = "SELECT photos FROM biens_immobiliers ORDER BY RAND()";
$res = mysqli_query($conn, $sql);
?>

<!-- Carrousel dynamique avec affichage aléatoire -->
<h1> Une belle France </h1>
<div class="conteneur">
    <div id="carrousel">
        <ul>
            <?php
            while ($row = mysqli_fetch_assoc($res)) {
                echo "<li><img src='" . htmlspecialchars($row['photos']) . "' width='700' height='400'></li>";
            }
            ?>
        </ul>
    </div>

    <div class="boutons">
        <button id="precedent">Précédent</button>
        <button id="suivant">Suivant</button>
    </div>
</div>

<script>
    $(document).ready(function(){
        let index = 0;
        const images = $("#carrousel ul li");
        const total = images.length;

        images.hide();
        images.eq(index).show();

        function maBoucle() {
            setTimeout(function() {
                images.eq(index).hide();
                index = (index + 1) % total;
                images.eq(index).show();
                maBoucle();
            }, 3000);
        }

        maBoucle();

        $("#suivant").click(function() {
            images.eq(index).hide();
            index = (index + 1) % total;
            images.eq(index).show();
        });

        $("#precedent").click(function() {
            images.eq(index).hide();
            index = (index - 1 + total) % total;
            images.eq(index).show();
        });
    });
</script>
