<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="profile.php">Mon profil</a></li>
            <li><a href="logout.php">Déconnexion</a></li>
        </ul>
    </nav>

    <h1>Bienvenue, <?= htmlspecialchars($user['username']); ?>!</h1>
    <p>Email: <?= htmlspecialchars($user['email']); ?></p>
    <p>Rôle: <?= htmlspecialchars($user['role']); ?></p>
</body>
</html>
