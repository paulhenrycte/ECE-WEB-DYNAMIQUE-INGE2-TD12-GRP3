<?php
session_start();

if (!isset($_SESSION['email']) || !isset($_SESSION['type_utilisateur'])) {
    header("Location: index.html");
    exit();
}

$database = "home";
$db_handle = mysqli_connect('localhost', 'root', '');
$db_found = mysqli_select_db($db_handle, $database);

$email = $_SESSION['email'];
$type = $_SESSION['type_utilisateur'];

$infos = [];

if ($db_found) {
    $sql = "SELECT * FROM utilisateurs WHERE email = '$email'";
    $result = mysqli_query($db_handle, $sql);
    if ($result && mysqli_num_rows($result) == 1) {
        $infos = mysqli_fetch_assoc($result);
    } else {
        echo "Utilisateur introuvable.";
        exit();
    }
} else {
    echo "Base de données introuvable.";
    exit();
}
mysqli_close($db_handle);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Compte <?php echo ucfirst($type); ?></title>
    <style>
        body { font-family: Arial; background-color: #f2f2f2; margin: 0; padding: 0; }
        .header {
            background-color: seagreen;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
        }
        .profile-pic {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
            background-color: white;
        }
        .info-box {
            width: 500px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px gray;
        }
        .info-box p {
            font-size: 16px;
            margin: 8px 0;
        }
        .buttons {
            text-align: center;
            margin-top: 20px;
        }
        .buttons a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background-color: darkblue;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        .buttons a:hover {
            background-color: navy;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Compte <?php echo ucfirst($type); ?></h1>
    <?php if (!empty($infos['photo_profil'])): ?>
        <img class="profile-pic" src="<?php echo htmlspecialchars($infos['photo_profil']); ?>" alt="Photo de profil">
    <?php else: ?>
        <img class="profile-pic" src="default.jpg" alt="Photo de profil">
    <?php endif; ?>
</div>

<div class="info-box">
    <h2>Informations du compte</h2>
    <p><strong>Nom :</strong> <?php echo htmlspecialchars($infos['nom']); ?></p>
    <p><strong>Prénom :</strong> <?php echo htmlspecialchars($infos['prenom']); ?></p>
    <p><strong>Email :</strong> <?php echo htmlspecialchars($infos['email']); ?></p>
    <p><strong>Adresse :</strong> <?php echo htmlspecialchars($infos['adresse']); ?></p>
    <p><strong>Type de compte :</strong> <?php echo htmlspecialchars($infos['type_utilisateur']); ?></p>

    <div class="buttons">
        <a href="modifier.php">⚙️ Modifier mes informations</a>

        <?php if ($type === 'client'): ?>
            <a href="#">📨 Messagerie</a>
            <a href="#">📜 Historique</a>
        <?php elseif ($type === 'agent'): ?>
            <a href="#">📅 Emploi du temps</a>
            <a href="#">📨 Messagerie</a>
            <a href="#">📜 Historique</a>
        <?php elseif ($type === 'admin'): ?>
            <a href="#">➕ Ajouter un agent</a>
            <a href="#">🏠 Ajouter un bien</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
