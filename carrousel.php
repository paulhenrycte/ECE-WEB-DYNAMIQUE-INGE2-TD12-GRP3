<?php
$conn = mysqli_connect('localhost', 'root', '', 'omnes_immobilier');
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

// Récupération des images depuis la BDD
$sql = "SELECT id_bien, photos FROM biens_immobiliers ORDER BY RAND()";
$res = mysqli_query($conn, $sql);

$images = [];
while ($row = mysqli_fetch_assoc($res)) {
    $images[] = [
        'src' => htmlspecialchars($row['photos']),
        'id' => $row['id_bien']
    ];
}
?>

<!-- Carrousel avec miniatures -->
<h1 class="carrousel-titre">Nos biens à découvrir</h1>
<div id="carrousel">
    <?php foreach ($images as $index => $img): ?>
        <a href="bien.php?id=<?= $img['id'] ?>">
            <img src="<?= $img['src'] ?>" alt="Bien <?= $img['id'] ?>" style="<?= $index === 0 ? 'display: block;' : '' ?>">
        </a>
    <?php endforeach; ?>
    <div class="controls">
        <button id="prev">&lt;</button>
        <button id="next">&gt;</button>
    </div>
</div>

<div id="miniature">
    <?php foreach ($images as $index => $img): ?>
        <img src="<?= $img['src'] ?>" alt="Mini <?= $img['id'] ?>" class="thumbnail <?= $index === 0 ? 'active' : '' ?>">
    <?php endforeach; ?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        let currentIndex = 0;
        const images = $('#carrousel img');
        const thumbnails = $('#miniature img');
        const imageCount = images.length;
        let interval;

        function showImage(index) {
            images.hide();
            images.eq(index).show();
            thumbnails.removeClass('active');
            thumbnails.eq(index).addClass('active');
        }

        function nextImage() {
            currentIndex = (currentIndex + 1) % imageCount;
            showImage(currentIndex);
        }

        function prevImage() {
            currentIndex = (currentIndex - 1 + imageCount) % imageCount;
            showImage(currentIndex);
        }

        function startCarousel() {
            interval = setInterval(nextImage, 4000);
        }

        function stopCarousel() {
            clearInterval(interval);
        }

        $('#next').click(function() {
            stopCarousel();
            nextImage();
            startCarousel();
        });

        $('#prev').click(function() {
            stopCarousel();
            prevImage();
            startCarousel();
        });

        thumbnails.click(function() {
            stopCarousel();
            currentIndex = thumbnails.index(this);
            showImage(currentIndex);
            startCarousel();
        });

        startCarousel();
    });
</script>
