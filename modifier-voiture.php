<?php
session_start();

try {

    $pdo = new
        PDO(
        "mysql:host=localhost;dbname=location_voitures",
        "root",
        ""
    );
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $stmt = $pdo->prepare("SELECT * FROM voitures WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $voiture = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$voiture) {
            echo "Voiture introuvable.";
            exit;
        }
    } else {
        echo "Aucun ID fourni.";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $marque = $_POST['marque'];
        $modele = $_POST['modele'];
        $annee = $_POST['annee'];
        $immatriculation = $_POST['immatriculation'];
        $disponibilite = isset($_POST['disponibilite']) ? 1 : 0;

        $sql = "UPDATE voitures SET marque = :marque, modele = :modele, annee = :annee, immatriculation = :immatriculation, disponibilite = :disponibilite WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':marque', $marque);
        $stmt->bindParam(':modele', $modele);
        $stmt->bindParam(':annee', $annee);
        $stmt->bindParam(':immatriculation', $immatriculation);
        $stmt->bindParam(':disponibilite', $disponibilite);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $successMessage = "Voiture mise à jour avec succès.";
            header("Location: liste-voitures.php");
            exit;
        } else {
            $errorMessage = "Erreur lors de la mise à jour.";
        }
    }
} catch (PDOException $e) {
    $errorMessage = "Erreur : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Voiture</title>
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
                <h1 class="text-center mb-4">Modifier une Voiture</h1>

                <form method="POST">
                    <div class="mb-3">
                        <label for="marque" class="form-label">Marque</label>
                        <input type="text" class="form-control" id="marque" name="marque"
                            value="<?php echo htmlspecialchars($voiture['marque']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="modele" class="form-label">Modèle</label>
                        <input type="text" class="form-control" id="modele" name="modele"
                            value="<?php echo htmlspecialchars($voiture['modele']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="annee" class="form-label">Année</label>
                        <input type="number" class="form-control" id="annee" name="annee"
                            value="<?php echo htmlspecialchars($voiture['annee']); ?>" required min="1900"
                            max="<?php echo date('Y'); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="immatriculation" class="form-label">Immatriculation</label>
                        <input type="text" class="form-control" id="immatriculation" name="immatriculation"
                            value="<?php echo htmlspecialchars($voiture['immatriculation']); ?>" required>
                    </div>

                    <button type="submit" class="btn btn-danger border-danger b">Mettre à Jour</button>
                    <a href="liste-voitures.php" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>

</body>

</html>