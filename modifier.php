<?php
session_start();

// Affichage des erreurs pour debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$database = "omnes_immobilier";
$db_handle = mysqli_connect('localhost', 'root', '');
$db_found = mysqli_select_db($db_handle, $database);

if (!isset($_SESSION['email'])) {
    header("Location: index.html");
    exit();
}

$ancien_email = $_SESSION['email'];

if ($db_found) {
    // Traitement du formulaire
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nom = mysqli_real_escape_string($db_handle, $_POST["nom"]);
        $prenom = mysqli_real_escape_string($db_handle, $_POST["prenom"]);
        $email = mysqli_real_escape_string($db_handle, $_POST["email"]);
        $mot_de_passe = mysqli_real_escape_string($db_handle, $_POST["mot_de_passe"]);
        $adresse = mysqli_real_escape_string($db_handle, $_POST["adresse"]);
        $photo_profil = mysqli_real_escape_string($db_handle, $_POST["photo_profil"]);

        $sql = "UPDATE utilisateurs SET 
            nom = '$nom',
            prenom = '$prenom',
            email = '$email',
            mot_de_passe = '$mot_de_passe',
            adresse = '$adresse',
            photo_profil = '$photo_profil'
            WHERE email = '$ancien_email'";

        if (mysqli_query($db_handle, $sql)) {
            $_SESSION['email'] = $email;
            header("Location: page.php");
            exit();
        } else {
            echo "<p style='color:red'>❌ Erreur SQL : " . mysqli_error($db_handle) . "</p>";
        }
    }

    // Récupération des données à afficher
    $sql = "SELECT * FROM utilisateurs WHERE email = '$ancien_email'";
    $result = mysqli_query($db_handle, $sql);
    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
    } else {
        echo "❌ Utilisateur introuvable.";
        exit();
    }
} else {
    echo "❌ Base de données introuvable.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier mes informations</title>
    <style>
        body { font-family: Arial; background-color: #f2f2f2; text-align: center; }
        form {
            background-color: white;
            width: 400px;
            margin: auto;
            padding: 25px;
            border-radius: 10px;
            margin-top: 50px;
            box-shadow: 0 0 10px gray;
        }
        input, label {
            width: 100%;
            margin-top: 10px;
            padding: 10px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #77B5FE;
            color: white;
            cursor: pointer;
            border: none;
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
    <h1>Modifier mes informations</h1>
    <form method="post" action="">
        <label>Nom :</label>
        <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom'] ?? ''); ?>" required>

        <label>Prénom :</label>
        <input type="text" name="prenom" value="<?php echo htmlspecialchars($user['prenom'] ?? ''); ?>" required>

        <label>Email :</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>

        <label>Mot de passe :</label>
        <input type="text" name="mot_de_passe" value="<?php echo htmlspecialchars($user['mot_de_passe'] ?? ''); ?>" required>

        <label>Adresse :</label>
        <input type="text" name="adresse" value="<?php echo htmlspecialchars($user['adresse'] ?? ''); ?>">

        <label>Photo de profil (URL) :</label>
        <input type="text" name="photo_profil" value="<?php echo htmlspecialchars($user['photo_profil'] ?? ''); ?>">

        <input type="submit" value="Enregistrer les modifications">
    </form>
       </div>
    <?php include 'footer.php'; ?>
</body>
</html>
