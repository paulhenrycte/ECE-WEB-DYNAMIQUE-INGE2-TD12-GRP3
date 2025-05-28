<?php
session_start();

$database = "omnes_immobilier";
$db_handle = mysqli_connect('localhost', 'root', '');
$db_found = mysqli_select_db($db_handle, $database);

if ($db_found) {
    $nom = isset($_POST["nom"]) ? $_POST["nom"] : "";
    $prenom = isset($_POST["prenom"]) ? $_POST["prenom"] : "";
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $mot_de_passe = isset($_POST["mot_de_passe"]) ? $_POST["mot_de_passe"] : "";
    $adresse = isset($_POST["adresse"]) ? $_POST["adresse"] : "";

    // Vérifie si l'email est déjà utilisé
    $check_query = "SELECT * FROM utilisateurs WHERE email = '$email'";
    $check_result = mysqli_query($db_handle, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "❌ Cet email est déjà utilisé. Veuillez en choisir un autre.";
    } else {
        $sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, type_utilisateur, adresse)
                VALUES ('$nom', '$prenom', '$email', '$mot_de_passe', 'client', '$adresse')";

        if (mysqli_query($db_handle, $sql)) {
            $_SESSION['email'] = $email;
            $_SESSION['type_utilisateur'] = "client";
            header("Location: page.php");
            exit();
        } else {
            echo "❌ Erreur lors de l'inscription : " . mysqli_error($db_handle);
        }
    }
} else {
    echo "❌ Base de données '$database' non trouvée.";
}

mysqli_close($db_handle);
?>
