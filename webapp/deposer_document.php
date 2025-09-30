<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

include('config.php'); // Connexion à la base de données

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user']['id']; // ID de l'utilisateur connecté
    $type_document = $_POST['type_document'];
    $autre_document = $_POST['autre_document'] ?? null; // Si le type "autre" est sélectionné
    $accepter_partage = isset($_POST['accepter_partage']) ? 1 : 0;

    // Gérer l'upload du fichier
    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        // Chemin d'upload (à adapter)
        $upload_dir = 'uploads/';
        $file_name = basename($_FILES['document']['name']);
        $file_path = $upload_dir . $file_name;

        // Déplacer le fichier téléchargé
        if (move_uploaded_file($_FILES['document']['tmp_name'], $file_path)) {
            // Sauvegarder les informations du document dans la base de données
            $stmt = $pdo->prepare("
                INSERT INTO documents (user_id, file_path, type_document, autre_document, accepter_partage) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$user_id, $file_path, $type_document, $autre_document, $accepter_partage]);

            // Redirection après succès
            header("Location: index.php");
            exit;
        } else {
            echo "Erreur lors de l'upload du document.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déposer un Document</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Centrage global du contenu */
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }

        /* Centrage du formulaire et des éléments */
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Personnalisation du label pour le fichier */
        .custom-file-label {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            margin-bottom: 10px;
        }

        .custom-file-input {
            display: none;
        }

        .file-selected {
            margin-top: 10px;
            font-size: 14px;
            color: #555;
        }

        label, select, input[type="checkbox"], button {
            text-align: center;
            margin-bottom: 15px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 200px;
        }

        button:hover {
            background-color: #0056b3;
        }

        nav {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        nav button {
            padding: 10px 15px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <!-- Boutons pour revenir à la page précédente ou à l'accueil -->
    <nav>
        <button onclick="window.history.back()">Retour</button>
        <button onclick="window.location.href='index.php'">Accueil</button>
    </nav>

    <h2>Déposer un Document de Santé</h2>

    <form method="POST" enctype="multipart/form-data" action="">
        <label for="document" class="custom-file-label">Importer un document</label>
        <input type="file" name="document" id="document" class="custom-file-input" required>
        <span class="file-selected" id="file-selected-text">Aucun fichier sélectionné</span><br><br>

        <label for="type_document">Type de document :</label>
        <select name="type_document" id="type_document" required>
            <option value="ordonnance">Ordonnance</option>
            <option value="carnet_sante">Carnet de santé</option>
            <option value="carnet_vaccination">Carnet de vaccination</option>
            <option value="autre">Autre</option>
        </select><br>

        <div id="autre_document_field" style="display:none;">
            <label for="autre_document">Veuillez préciser :</label>
            <input type="text" name="autre_document" id="autre_document">
        </div><br>

        <label>
            <input type="checkbox" name="accepter_partage" required> J'accepte que mes documents soient partagés de manière partielle et anonyme à des fins médicales.
        </label><br>

        <button type="submit">Envoyer le document</button>
    </form>

    <script>
        // Afficher le champ "autre" uniquement si le type "autre" est sélectionné
        document.getElementById('type_document').addEventListener('change', function () {
            const autreField = document.getElementById('autre_document_field');
            if (this.value === 'autre') {
                autreField.style.display = 'block';
            } else {
                autreField.style.display = 'none';
            }
        });

        // Afficher le nom du fichier sélectionné dans le texte
        const fileInput = document.getElementById('document');
        const fileSelectedText = document.getElementById('file-selected-text');

        fileInput.addEventListener('change', function () {
            if (fileInput.files.length > 0) {
                fileSelectedText.textContent = fileInput.files[0].name;
            } else {
                fileSelectedText.textContent = 'Aucun fichier sélectionné';
            }
        });
    </script>

</body>
</html>
