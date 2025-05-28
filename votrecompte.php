<?php
session_start();
if (isset($_SESSION['email'])) {
    header("Location: page.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion ou Inscription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .button-group {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container {
            width: 300px;
            margin: auto;
            background: white;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            display: none;
        }
        h2 {
            margin-bottom: 20px;
        }
        label, input {
            display: block;
            width: 100%;
            margin-top: 10px;
        }
        input {
            padding: 8px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background-color: #77B5FE;
            color: white;
            border: none;
            cursor: pointer;
        }
        .active {
            display: block;
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
<script>
    function showForm(formId) {
        document.getElementById('connexion').classList.remove('active');
        document.getElementById('inscription').classList.remove('active');
        document.getElementById(formId).classList.add('active');
    }
</script>
</head>
<body>
    <?php include 'navigation.php'; ?>
    <div id="wrapper">
        <div class="button-group">
            <button onclick="showForm('connexion')">Connexion</button>
            <button onclick="showForm('inscription')">Inscription</button>
        </div>

        <div class="form-container" id="connexion">
            <h2>Connexion</h2>
            <form action="login.php" method="post">
                <label>Email :</label>
                <input type="email" name="email" required>
                <label>Mot de passe :</label>
                <input type="password" name="mot_de_passe" required>
                <button type="submit">Se connecter</button>
            </form>
        </div>

        <div class="form-container" id="inscription">
            <h2>Inscription Client</h2>
            <form action="inscription.php" method="post">
                <label>Nom :</label>
                <input type="text" name="nom" required>
                <label>Prénom :</label>
                <input type="text" name="prenom" required>
                <label>Email :</label>
                <input type="email" name="email" required>
                <label>Mot de passe :</label>
                <input type="password" name="mot_de_passe" required>
                <label>Adresse :</label>
                <input type="text" name="adresse">
                <button type="submit">S'inscrire</button>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
