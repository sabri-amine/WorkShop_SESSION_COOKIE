<?php
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $language_preference = isset($_COOKIE['user_language']) ? $_COOKIE['user_language'] : 'fr';
} else {
    $username = '';
    $language_preference = isset($_COOKIE['user_language']) ? $_COOKIE['user_language'] : 'fr';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $input_username = $_POST['username'];
        $input_password = $_POST['password'];
        $remember = isset($_POST['remember']) ? true : false;

        if ($input_username && $input_password) {
            $_SESSION['username'] = $input_username;
            if ($remember) {
                setcookie('username', $input_username, time() + (30 * 24 * 60 * 60), '/');
            }
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $login_error = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    }

    if (isset($_POST['save_language'])) {
        $language = $_POST['language'];
        setcookie('user_language', $language, time() + (30 * 24 * 60 * 60), '/');
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    if (isset($_POST['logout'])) {
        session_destroy();
        setcookie('username', '', time() - 3600, '/');
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Sessions et Cookies</title>
</head>
<body>
    <?php if (isset($_SESSION['username'])): ?>
        <h2>Bienvenue, <?php echo htmlspecialchars($username); ?>!</h2>
        <p>Préférence de langue actuelle : <?php echo htmlspecialchars($language_preference); ?></p>

        <form method="POST">
            <label for="language">Choisissez votre langue préférée : </label>
            <select name="language" id="language">
                <option value="fr" <?php echo $language_preference == 'fr' ? 'selected' : ''; ?>>Français</option>
                <option value="en" <?php echo $language_preference == 'en' ? 'selected' : ''; ?>>Anglais</option>
                <option value="es" <?php echo $language_preference == 'es' ? 'selected' : ''; ?>>Espagnol</option>
            </select><br><br>
            <input type="submit" name="save_language" value="Sauvegarder la langue">
        </form>

        <form method="POST">
            <input type="submit" name="logout" value="Se déconnecter">
        </form>
    <?php else: ?>
        <h2>Connexion</h2>
        <form method="POST">
            <label for="username">Nom d'utilisateur : </label>
            <input type="text" name="username" required><br><br>

            <label for="password">Mot de passe : </label>
            <input type="password" name="password" required><br><br>

            <label for="remember">Se souvenir de moi</label>
            <input type="checkbox" name="remember"><br><br>

            <input type="submit" name="login" value="Se connecter">
        </form>

        <?php if (isset($login_error)): ?>
            <p style="color:red;"><?php echo $login_error; ?></p>
        <?php endif; ?>
    <?php endif; ?>

</body>
</html>
