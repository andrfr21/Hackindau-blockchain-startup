<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=users_db;charset=utf8';
$username = 'root'; // Remplace par ton utilisateur
$password = 'root'; // Remplace par ton mot de passe
$pdo = new PDO($dsn, $username, $password);

// Vérification de la méthode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérification que le champ de recherche est rempli
    if (!empty($_POST['search_query'])) {
        $searchQuery = $_POST['search_query'];

        // Requête pour rechercher des patients
        $stmt = $pdo->prepare("SELECT * FROM patients WHERE username LIKE :search_query");
        $stmt->execute(['search_query' => '%' . $searchQuery . '%']);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $numResults = count($results);
    } else {
        // Si la requête est vide
        $results = [];
        $numResults = 0;
        $error = "Veuillez entrer un terme de recherche.";
    }
} else {
    // Si ce n'est pas une requête POST, redirige vers l'accueil
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de recherche</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Centrage du conteneur de recherche */
        .search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        /* Style du champ de recherche */
        .search-input {
            width: 300px;
            padding: 10px;
            margin-right: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            text-align: center; /* Centrer le texte à l'intérieur */
        }

        /* Style du bouton de recherche */
        .search-button {
            padding: 10px 70px;
            background-color: #007bff; /* Couleur bleue plus intense */
            color: white;
            border: 2px solid #007bff; /* Bordure assortie */
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;

            /* Ajout pour centrer le texte */
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center; /* Assure que le texte est bien centré */
        }

        /* Centrage du conteneur global */
        .container {
            text-align: center;
            margin-top: 50px; /* Espace pour centrer verticalement */
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            padding: 10px 0;
        }

        /* Styles pour le bouton de contact */
        .contact-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        .contact-button:hover {
            background-color: #218838;
        }

        /* Styles pour le formulaire de contact modal */
        .modal {
            display: none; /* Cacher par défaut */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Résultats de recherche</h1>

        <!-- Formulaire de recherche pour ré-effectuer une recherche -->
        <div class="search-container">
            <form method="POST" action="">
                <input type="text" name="search_query" class="search-input" placeholder="Recherche..." value="<?= isset($searchQuery) ? htmlspecialchars($searchQuery) : '' ?>" required>
                <button type="submit" class="search-button">Rechercher</button>
            </form>
        </div>

        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error); ?></p>
        <?php else: ?>
            <p>Nombre de résultats trouvés : <?= $numResults; ?></p>

            <?php if ($numResults > 0): ?>
                <ul>
                    <?php foreach ($results as $patient): ?>
                        <li><?= htmlspecialchars($patient['username']); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Aucun résultat trouvé.</p>
            <?php endif; ?>
        <?php endif; ?>

        <a href="index.php">Retourner à l'accueil</a>

        <!-- Bouton pour contacter -->
        <br><br>
        <a href="mailto:contact@votresite.com" class="contact-button">Contactez-nous</a>

        <!-- Formulaire modal -->
        <div id="contactModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Formulaire de Contact</h2>
                <form action="mailto:contact@votresite.com" method="post" enctype="text/plain">
                    <label for="name">Nom :</label>
                    <input type="text" id="name" name="name" required><br><br>
                    <label for="email">Email :</label>
                    <input type="email" id="email" name="email" required><br><br>
                    <label for="message">Message :</label>
                    <textarea id="message" name="message" rows="5" required></textarea><br><br>
                    <button type="submit" class="contact-button">Envoyer</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Ouvrir le formulaire modal
        var modal = document.getElementById("contactModal");
        var btn = document.getElementById("contactBtn");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
