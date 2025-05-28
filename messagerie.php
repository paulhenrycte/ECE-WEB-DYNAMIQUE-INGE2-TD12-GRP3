<?php
session_start();
$db = mysqli_connect("localhost", "root", "", "omnes_immobilier");

if (!isset($_SESSION['email'])) {
    header("Location: index.html");
    exit();
}

$email = $_SESSION['email'];
$user = [];
$conversations = [];
$messages = [];
$target = null;
$selected_bien = null;

$res = mysqli_query($db, "SELECT * FROM utilisateurs WHERE email = '$email'");
if ($res && mysqli_num_rows($res) == 1) {
    $user = mysqli_fetch_assoc($res);
    $id_user = $user['id_user'];
}

$id_dest = isset($_GET['id']) ? intval($_GET['id']) : null;
$id_bien = isset($_GET['bien']) ? intval($_GET['bien']) : null;

// 🔴 Marquer messages comme lus
if ($id_dest && $id_bien) {
    mysqli_query($db, "UPDATE messages SET lu = TRUE 
        WHERE id_destinataire = $id_user 
        AND id_expediteur = $id_dest 
        AND id_bien = $id_bien");
}

// 📜 Liste des conversations avec compteur de non-lus
$query = "
SELECT 
u.id_user, u.nom, u.prenom, u.photo_profil,
b.id_bien, b.titre, b.photos,
COUNT(CASE WHEN m.id_destinataire = $id_user AND m.lu = FALSE THEN 1 END) AS non_lus
FROM messages m
JOIN utilisateurs u ON (u.id_user = IF(m.id_expediteur = $id_user, m.id_destinataire, m.id_expediteur))
JOIN biens_immobiliers b ON b.id_bien = m.id_bien
WHERE m.id_expediteur = $id_user OR m.id_destinataire = $id_user
GROUP BY u.id_user, b.id_bien
ORDER BY MAX(m.date_envoi) DESC
";
$res_conv = mysqli_query($db, $query);
while ($row = mysqli_fetch_assoc($res_conv)) {
    $conversations[] = $row;
}

// 📬 Envoi du message
if ($id_dest && $id_bien && $_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['contenu'])) {
    $contenu = mysqli_real_escape_string($db, $_POST['contenu']);
    $send = "INSERT INTO messages (id_expediteur, id_destinataire, id_bien, contenu)
    VALUES ($id_user, $id_dest, $id_bien, '$contenu')";
    mysqli_query($db, $send);
}

// 🎯 Infos de l’interlocuteur et bien
if ($id_dest) {
    $target = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM utilisateurs WHERE id_user = $id_dest"));
}
if ($id_bien) {
    $selected_bien = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM biens_immobiliers WHERE id_bien = $id_bien"));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messagerie</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial; }
        .container { display: flex; height: 100vh; }

        .sidebar {
            width: 25%;
            background: #111;
            color: white;
            overflow-y: auto;
            padding: 10px;
        }
        .contact-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .contact {
            display: flex;
            align-items: center;
            background: #222;
            border-radius: 5px;
            margin-bottom: 10px;
            padding: 10px;
            transition: background 0.3s;
        }
        .contact:hover {
            background: #333;
            cursor: pointer;
        }

        .contact img {
            width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px;
        }
        .contact a {
            text-decoration: none;
            color: white;
            display: flex;
            flex-direction: column;
        }
        .contact .bien { font-size: 12px; color: #ccc; }
        .contact .badge { color: red; font-size: 20px; margin-left: auto; }

        .chat {
            width: 75%;
            display: flex;
            flex-direction: column;
            background: #f0f0f0;
        }

        .chat-header {
            background: #77B5FE;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-header-left {
            display: flex;
            align-items: center;
        }

        .chat-header-left img {
            height: 60px;
            width: 80px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 15px;
        }

        .voir-bien {
            text-decoration: none;
            background: white;
            color: #77B5FE;
            padding: 8px 12px;
            border-radius: 5px;
            font-weight: bold;
        }

        .messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .message {
            max-width: 80%;
            padding: 12px;
            border-radius: 12px;
            margin: 8px 0;
            font-size: 15px;
        }

        .me { background: #cce5ff; align-self: flex-end; }
        .other { background: #e8e8e8; align-self: flex-start; }

        .chat-form {
            display: flex;
            padding: 15px;
            background: white;
            border-top: 1px solid #ccc;
        }

        .chat-form input {
            flex: 1;
            padding: 10px;
            font-size: 15px;
        }

        .chat-form button {
            background: #77B5FE;
            color: white;
            padding: 10px 20px;
            border: none;
            margin-left: 10px;
            font-size: 15px;
        }
        /* ===== HEADER AVEC LOGO ===== */
#header {
    padding: 20px 0;
    border-bottom: 2px solid lightgray;
}

.header-container {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.header-container .logo {
    position: absolute;
    left: 0;
    width: 70px;
    margin-left: 20px;
}

.header-container h1 {
    font-size: 32px;
    color: navy;
    margin: 0;
}


/* ===== NAVIGATION ===== */
nav {
    background-color: navy;
    padding: 15px;
    text-align: center;
}

nav a {
    color: white;
    text-decoration: none;
    margin: 0 15px;
    font-weight: bold;
    font-size: 16px;
    transition: color 0.3s;
}

nav a:hover {
    color: gold;
}

/* ===== FOOTER ===== */
#footer {
    background-color: navy;
    color: white;
    text-align: center;
    padding: 20px 15px;
    margin-top: 20px;
    font-size: 14px;
}

#footer h4 {
    margin-bottom: 10px;
    font-size: 16px;
    color: gold;
}

#footer p {
    margin: 5px 0;
    line-height: 1.4;
}

#footer a {
    color: gold;
    text-decoration: none;
}

#footer a:hover {
    text-decoration: underline;
    </style>
</head>
<body>
     <?php include 'navigation.php'; ?>
    <div id="wrapper">
    <div class="container">
        <div class="sidebar">
            <h2 style="text-align:center;">Messagerie</h2>
            <?php foreach ($conversations as $c): ?>
                <a href="messagerie.php?id=<?= $c['id_user'] ?>&bien=<?= $c['id_bien'] ?>" class="contact-link">
                    <div class="contact">
                        <img src="<?= htmlspecialchars($c['photo_profil'] ?? 'default.jpg') ?>">
                        <div>
                            <strong><?= htmlspecialchars($c['prenom'] . ' ' . $c['nom']) ?></strong><br>
                            <span class="bien"><?= htmlspecialchars($c['titre']) ?></span>
                        </div>
                        <?php if ($c['non_lus'] > 0): ?>
                            <span class="badge">🔴</span>
                        <?php endif; ?>
                    </div>
                </a>

            <?php endforeach; ?>
        </div>

        <div class="chat">
            <div class="chat-header">
                <div class="chat-header-left">
                    <?php if ($selected_bien): ?>
                        <img src="<?= htmlspecialchars($selected_bien['photos']) ?>">
                    <?php endif; ?>
                    <div>
                        <?php if ($target && $selected_bien): ?>
                            <strong><?= htmlspecialchars($target['prenom'] . ' ' . $target['nom']) ?></strong><br>
                            <small><?= htmlspecialchars($selected_bien['titre']) ?></small>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($selected_bien): ?>
                    <a class="voir-bien" href="bien.php?id=<?= $selected_bien['id_bien'] ?>">📄 Voir le bien</a>
                <?php endif; ?>
            </div>

            <div class="messages" id="msgbox">
                <!-- Messages seront injectés par AJAX -->
            </div>

            <?php if ($target && $selected_bien): ?>
                <form class="chat-form" method="post">
                    <input type="text" name="contenu" placeholder="Votre message..." required>
                    <button type="submit">Envoyer</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
        </div>
    <?php include 'footer.php'; ?>

    <script>
        function refreshMessages() {
            const msgbox = document.getElementById("msgbox");
            const id = new URLSearchParams(window.location.search).get("id");
            const bien = new URLSearchParams(window.location.search).get("bien");
            if (!id || !bien || !msgbox) return;

            fetch(`ajax.php?id=${id}&bien=${bien}`)
            .then(response => response.text())
            .then(html => {
                msgbox.innerHTML = html;
                msgbox.scrollTop = msgbox.scrollHeight;
            });
        }
        setInterval(refreshMessages, 3000);
refreshMessages(); // appel initial
</script>
</body>
</html>
