<?php
session_start();
include('config.php'); // Assure-toi que 'config.php' contient la connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérification des identifiants dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user; // Stocke les informations utilisateur dans la session
        header("Location: index.php"); // Redirection vers l'accueil après connexion
        exit;
    } else {
        echo "Identifiants incorrects.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Positionnement du bouton Home en haut à gauche */
        .home-button {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 16px;
        }

        .home-button:hover {
            background-color: #0056b3;
        }

        /* Style du conteneur */
        .container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label, input[type="email"], input[type="password"] {
            margin-bottom: 10px;
            width: 100%;
            padding: 10px;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <!-- Bouton Home en haut à gauche -->
    <a href="home.php" class="home-button">Home</a>

    <div class="container">
        <h2>Connexion</h2>
        <form method="POST" action="login.php">
            <label for="email">Email:</label>
            <input type="email" name="email" required><br>

            <label for="password">Mot de passe:</label>
            <input type="password" name="password" required><br>

            <input type="submit" value="Se connecter">
        </form>
    </div>
</body>
</html>
