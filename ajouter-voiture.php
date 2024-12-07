<?php
session_start();

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=location_voitures",
        "root",
        ""
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $successMessage = "";
    $errorMessage = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $marque = $_POST['marque'];
        $modele = $_POST['modele'];
        $annee = $_POST['annee'];
        $immatriculation = $_POST['immatriculation'];

        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM voitures WHERE immatriculation = :immatriculation");
        $checkStmt->bindParam(':immatriculation', $immatriculation, PDO::PARAM_STR);
        $checkStmt->execute();
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            $errorMessage = "Une voiture avec cette immatriculation existe déjà.";
        } else {
            $sql = "INSERT INTO voitures (marque, modele, annee, immatriculation) VALUES (:marque, :modele, :annee, :immatriculation)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':marque', $marque);
            $stmt->bindParam(':modele', $modele);
            $stmt->bindParam(':annee', $annee);
            $stmt->bindParam(':immatriculation', $immatriculation);

            if ($stmt->execute()) {
                $successMessage = "Voiture ajoutée avec succès !";
            } else {
                $errorMessage = "Erreur lors de l'ajout.";
            }
        }
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Voiture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bg-image {
            background-image: url('bgvoitures.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
        }

        .container {
            max-width: 800px;
            padding-top: 80px;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background-color: #17202a;
            border: none;
        }

        .btn-primary:hover {
            background-color: #1a242f;
        }

        .custom-btn {
            background-color: red;
            border: 2px solid red;
            color: white;
        }

        .custom-btn:hover {
            background-color: darkred;
        }

        .custom-btn-annuler {
            margin-left: 10px;
            border: 2px solid red;
            color: red;
        }

        .custom-btn-annuler:hover {
            background-color: red;
            color: white;
        }

        .b {
            margin-right: 60%;
        }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="bg-image">
        <div class="container">
            <div class="form-container">
                <h1 class="text-center mb-4">Ajouter une Voiture</h1>

                <form method="POST">
                    <div class="mb-3">
                        <label for="marque" class="form-label">Marque</label>
                        <input type="text" class="form-control" id="marque" name="marque" required>
                    </div>
                    <div class="mb-3">
                        <label for="modele" class="form-label">Modèle</label>
                        <input type="text" class="form-control" id="modele" name="modele" required>
                    </div>
                    <div class="mb-3">
                        <label for="annee" class="form-label">Année</label>
                        <input type="number" class="form-control" id="annee" name="annee" required min="1900"
                            max="<?php echo date('Y'); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="immatriculation" class="form-label">Immatriculation</label>
                        <input type="text" class="form-control" id="immatriculation" name="immatriculation" required>
                    </div>

                    <button type="submit" class="btn btn-danger border-danger b">Ajouter la Voiture</button>
                    <a href="liste-voitures.php" class="btn btn-outline-danger custom-btn-annuler">Annuler</a>
                    <br>
                    <?php if ($successMessage): ?>
                        <div class="alert alert-success"><?php echo $successMessage; ?></div>
                    <?php endif; ?>
                    <?php if ($errorMessage): ?>
                        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

</body>

</html>