<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=location_voitures", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
        $deleteId = $_POST['delete_id'];

        $deleteStmt = $pdo->prepare("DELETE FROM reservations WHERE id = :id AND email_client = :email");
        $deleteStmt->bindParam(':id', $deleteId, PDO::PARAM_INT);
        $deleteStmt->bindParam(':email', $_SESSION['user_email'], PDO::PARAM_STR);
        if ($deleteStmt->execute()) {
            $successMessage = "Réservation supprimée avec succès.";
        } else {
            $errorMessage = "Erreur lors de la suppression de la réservation.";
        }
    }

    $stmt = $pdo->prepare("SELECT r.id, r.date_debut, r.date_fin, r.nom_client, r.email_client, v.marque, v.modele 
                           FROM reservations r
                           INNER JOIN voitures v ON r.id_voiture = v.id
                           WHERE r.email_client = :email");
    $stmt->bindParam(':email', $_SESSION['user_email'], PDO::PARAM_STR);
    $stmt->execute();
    $reservations = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bg-image {
            background-image: url('bgvoitures.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
        }

        .table-container {
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #fff;
        }

        table {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="bg-image">
        <div class="container table-container">
            <h1 class="mb-4" style="color: black;">Liste des Réservations</h1>

            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success"><?php echo $successMessage; ?></div>
            <?php endif; ?>

            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
            <?php endif; ?>

            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID Réservation</th>
                        <th>Nom Client</th>
                        <th>Email Client</th>
                        <th>Marque Voiture</th>
                        <th>Modèle Voiture</th>
                        <th>Date de Début</th>
                        <th>Date de Fin</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reservations)): ?>
                        <?php foreach ($reservations as $reservation): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reservation['id']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['nom_client']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['email_client']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['marque']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['modele']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['date_debut']); ?></td>
                                <td><?php echo htmlspecialchars($reservation['date_fin']); ?></td>
                                <td>
                                    <form method="POST" action=""
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?');">
                                        <input type="hidden" name="delete_id" value="<?php echo $reservation['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Annuler la réservation </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Aucune réservation trouvée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>