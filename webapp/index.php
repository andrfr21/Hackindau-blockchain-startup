<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$role = $user['role'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - <?= htmlspecialchars($user['username']); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="profile.php">Mon profil</a></li>
            <li><a href="logout.php">Déconnexion</a></li>
            <!-- Ajouter l'option "Mon historique de documents" uniquement pour les patients -->
            <?php if ($role == 'patient'): ?>
                <li><a href="historique_documents.php">Mon historique de documents</a></li>
            <?php endif; ?>
            <li><a href="mailto:contact@votresite.com" class="contact-button">Contactez-nous</a></li>
        </ul>
        <div class="center-container">
            <button id="mode-sombre">Mode sombre</button>
        <div class="center-container">
    </nav>

    <h1>Bienvenue, <?= htmlspecialchars($user['username']); ?>!</h1>

    <?php if ($role == 'patient'): ?>
        <section>
            <h2>Plateforme Patients</h2>
            <p>Bienvenue sur notre plateforme dédiée aux patients.</p>
            <p>Dans le cadre de recherches médicales, vous avez la possibilité de partager vos documents de santé à des fins de recherche. Ces documents seront partagés de manière partielle et anonyme avec votre accord. En contrepartie, vous pourrez être rémunéré.</p>
            <div class="center-container">
                <button onclick="window.location.href='deposer_document.php'">Déposer mes documents</button>
            </div>
        </section>
    <?php elseif ($role == 'chercheur'): ?>
        <section>
            <h2>Plateforme Chercheurs</h2>
            <p>Bienvenue sur la plateforme de recherche.</p>
            <p>Ici, vous pouvez rechercher une pathologie ou une maladie spécifique, et nous vous indiquerons le nombre de données disponibles.</p>
            <p> Si vous le souhaitez, nous faciliterons ensuite la mise en contact avec les participants. </p>
            <form method="POST" action="search.php">
                <input type="text" name="search_query" placeholder="Rechercher..." required>
                <button type="submit">Rechercher</button>
            </form>
        </section>
    <?php endif; ?>

    <script src="script.js"></script>
</body>
</html>
