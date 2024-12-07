<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=location_voitures", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];

        $stmt = $pdo->prepare("
            SELECT * FROM voitures
            WHERE disponibilite = 1
            AND id NOT IN (
                SELECT id_voiture FROM reservations
                WHERE (date_debut BETWEEN :date_debut AND :date_fin)
                OR (date_fin BETWEEN :date_debut AND :date_fin)
            )
        ");
        $stmt->bindParam(':date_debut', $date_debut);
        $stmt->bindParam(':date_fin', $date_fin);
        $stmt->execute();

        $voitures_disponibles = $stmt->fetchAll();
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
    <title>Rechercher une Voiture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bg-image {
            background-image: url('bgvoitures.jpeg');
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            height: 100vh;
            min-height: 100%;
            flex-direction: column;
            justify-content: flex-start;
            padding-bottom: 50px;
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

        .voitures-container {
            margin-top: 30px;
        }

        .voiture-card {
            margin-bottom: 20px;
        }

        .b {
            margin-right: 65%;
        }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="bg-image">
        <div class="container">
            <div class="form-container">
                <h1 class="text-center mb-4">Rechercher une Voiture</h1>
                <form method="POST">
                    <div class="mb-3">
                        <label for="date_debut" class="form-label">Date de début</label>
                        <input type="date" class="form-control" id="date_debut" name="date_debut" required>
                    </div>
                    <div class="mb-3">
                        <label for="date_fin" class="form-label">Date de fin</label>
                        <input type="date" class="form-control" id="date_fin" name="date_fin" required>
                    </div>

                    <button button type="submit" class="btn btn-danger border-danger b">Rechercher</button>
                    <a href="liste-voitures.php" class="btn btn-secondary">Annuler</a>
                </form>
            </div>

            <?php if (isset($voitures_disponibles) && !empty($voitures_disponibles)): ?>

                <div class="voitures-container">
                    <h2 class="text-center mb-4" style="color : white;">Voitures Disponibles</h2>
                    <div class="row">
                        <?php foreach ($voitures_disponibles as $voiture): ?>
                            <div class="col-md-4 voiture-card">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($voiture['marque']); ?> -
                                            <?php echo htmlspecialchars($voiture['modele']); ?>
                                        </h5>
                                        <p class="card-text">Année: <?php echo htmlspecialchars($voiture['annee']); ?></p>
                                        <p class="card-text">Immatriculation:
                                            <?php echo htmlspecialchars($voiture['immatriculation']); ?>
                                        </p>
                                        <p class="card-text">Disponibilité:
                                            <?php echo $voiture['disponibilite'] ? 'Disponible' : 'Non disponible'; ?>
                                        </p>
                                        <a href="reserver_voiture.php?id=<?php echo $voiture['id']; ?>&date_debut=<?php echo $date_debut; ?>&date_fin=<?php echo $date_fin; ?>"
                                            class="btn btn-primary">Réserver</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php elseif (isset($voitures_disponibles)): ?>
                <div class="alert alert-warning mt-4" role="alert">
                    Aucune voiture disponible pour ces dates.
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>