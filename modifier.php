<?php
session_start();

// Affichage des erreurs pour debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$database = "home";
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
            background-color: seagreen;
            color: white;
            cursor: pointer;
            border: none;
        }
    </style>
</head>
<body>
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
</body>
</html>
