<?php
$host = 'localhost';
$dbname = 'users_db'; // Remplace par le nom de ta base de données
$username = 'root';        // Nom d'utilisateur par défaut sous MAMP
$password = 'root';        // Mot de passe par défaut sous MAMP
$port = 8889;              // Port MySQL sous MAMP

try {
    // Connexion via le port 8889
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
