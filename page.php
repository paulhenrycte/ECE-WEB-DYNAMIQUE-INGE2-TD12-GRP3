<?php
session_start();

if (!isset($_SESSION['email']) || !isset($_SESSION['type_utilisateur'])) {
    header("Location: index.html");
    exit();
}

$database = "omnes_immobilier";
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
            background-color: #77B5FE;
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
            background-color: #77B5FE;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }
        .buttons a:hover {
            background-color: navy;
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

        <div class="header">
            <h1>Compte <?php echo ucfirst($type); ?></h1>

            <a href="logout.php" style="position:absolute; top:160px; right:100px;">
                <button style="padding: 10px 20px; background-color: crimson; color: white; border: none; border-radius: 5px;">
                    Se déconnecter
                </button>
            </a>
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
                    <a href="messagerie.php">📨 Messagerie</a>
                    <a href="#">📜 Historique</a>
                <?php elseif ($type === 'agent'): ?>
                    <a href="#">📅 Emploi du temps</a>
                    <a href="messagerie.php  ">📨 Messagerie</a>
                    <a href="#">📜 Historique</a>
                <?php elseif ($type === 'admin'): ?>
                    <a href="#">➕ Ajouter un agent</a>
                    <a href="#">🏠 Ajouter un bien</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
