<?php
session_start();

$database = "omnes_immobilier";
$db_handle = mysqli_connect('localhost', 'root', '');
$db_found = mysqli_select_db($db_handle, $database);

if ($db_found) {
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $mot_de_passe = isset($_POST['mot_de_passe']) ? $_POST['mot_de_passe'] : "";

    $email = mysqli_real_escape_string($db_handle, $email);
    $sql = "SELECT * FROM utilisateurs WHERE email = '$email'";
    $result = mysqli_query($db_handle, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $data = mysqli_fetch_assoc($result);

        if ($mot_de_passe == $data['mot_de_passe']) {
            $_SESSION['id_user'] = $data['id_user'];
            $_SESSION['email'] = $data['email'];
            $_SESSION['type_utilisateur'] = $data['type_utilisateur'];

            header("Location: page.php");
            exit();
        } else {
            echo "<p style='color:red; text-align:center;'>❌ Mot de passe incorrect.</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>❌ Email non trouvé.</p>";
    }
} else {
    echo "<p style='color:red; text-align:center;'>❌ Base '$database' non trouvée.</p>";
}

mysqli_close($db_handle);
?>
