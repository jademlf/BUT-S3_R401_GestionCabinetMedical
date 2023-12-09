<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout d'un Médecin</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <!-- Navigation -->
    <?php include 'menu.php'; ?>

    <div class="container">
        <h1>Ajout d'un Médecin</h1>

        <?php
             include 'connexion_bd.php';

            // Vérification si le formulaire a été soumis
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Récupération des données du formulaire
                $civilite = $_POST['civilite'];
                $nom = $_POST['nom'];
                $prenom = $_POST['prenom'];

                // Vérification si le médecin existe déjà dans la base de données
                $checkQuery = $linkpdo->prepare('SELECT COUNT(*) AS count FROM Medecins WHERE Nom = :nom AND Prénom = :prenom AND Civilité = :civilite');
                $checkQuery->execute(array('nom' => $nom, 'prenom' => $prenom, 'civilite' => $civilite));
                $result = $checkQuery->fetchColumn();

                // Si le médecin existe, affiche un message d'erreur
                if ($result != 0) {
                    echo "<p>Le médecin existe déjà dans la base de données.</p>";
                } else {
                    // Prépare la requête d'insertion
                    $insertQuery = $linkpdo->prepare('INSERT INTO Medecins (Civilité, Nom, Prénom) VALUES (:civilite, :nom, :prenom)');

                    // Exécute la requête d'insertion avec les données du formulaire
                    $insertResult = $insertQuery->execute(array(
                        'civilite' => $_POST['civilite'],
                        'nom' => $_POST['nom'],
                        'prenom' => $_POST['prenom']
                    ));

                    // Affiche le résultat de l'insertion
                    if ($insertResult) {
                        echo "<p>Le médecin a été ajouté avec succès.</p>";
                    } else {
                        echo "<p>Erreur lors de l'ajout du médecin.</p>";
                    }
                }
            }
        ?>

        <!-- Formulaire d'ajout d'un médecin -->
        <form action="ajoutMedecin.php" method="post">
            <label>Civilité :</label>
            <input type="radio" name="civilite" value="M" required>
            <label for="civiliteM">M</label>

            <input type="radio" name="civilite" value="F" required>
            <label for="civiliteF">F</label><br />

            <label for="nom">Nom :</label>
            <input type="text" name="nom" required><br />

            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" id="prenom" required pattern="^[a-zA-ZÀ-ÿ\s]+$">

            <input type="submit" value="Ajouter le médecin">
            <input type="reset" value="Effacer">
        </form>
    </div>
</body>
</html>
