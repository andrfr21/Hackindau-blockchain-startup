<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Vérification si les mots de passe correspondent
    if ($password !== $confirm_password) {
        echo "Les mots de passe ne correspondent pas.";
    } else {
        // Hachage du mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Vérification si l'utilisateur existe déjà
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            echo "L'utilisateur existe déjà.";
        } else {
            // Insertion dans la base de données
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password, $role]);
            header("Location: login.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
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

        /* Ajout de styles pour les marges et alignements */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
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

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"], input[type="email"], input[type="password"], select {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
        }

        input[type="submit"] {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <!-- Bouton Home en haut à gauche -->
    <a href="home.php" class="home-button">Home</a>

    <div class="container">
        <h2>Inscription</h2>
        <form method="POST" action="">
            <label for="username">Nom d'utilisateur:</label>
            <input type="text" name="username" id="username" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Mot de passe:</label>
            <input type="password" name="password" id="password" required>

            <label for="confirm_password">Confirmer le mot de passe:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <label for="role">Vous êtes:</label>
            <select name="role" id="role" required>
                <option value="patient">Patient</option>
                <option value="chercheur">Chercheur</option>
            </select>

            <input type="submit" value="S'inscrire">
        </form>
    </div>

</body>
</html>
