<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit;
}

$user_role = $_SESSION['user_role'];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=location_voitures", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
        $deleteId = $_POST['delete_id'];

        $deleteStmt = $pdo->prepare("DELETE FROM voitures WHERE id = :id");
        $deleteStmt->bindParam(':id', $deleteId, PDO::PARAM_INT);
        if ($deleteStmt->execute()) {
            $successMessage = "Voiture supprimée avec succès.";
        } else {
            $errorMessage = "Erreur lors de la suppression de la voiture.";
        }
    }

    $stmt = $pdo->prepare("SELECT * FROM voitures");
    $stmt->execute();
    $voitures = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Voitures</title>
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
            <h1 class="mb-4" style="color: black;">Liste des Voitures</h1>

            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success"><?php echo $successMessage; ?></div>
            <?php endif; ?>

            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
            <?php endif; ?>

            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Année</th>
                        <th>Immatriculation</th>
                        <?php if ($user_role === 'admin'): ?>
                            <th>Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($voitures)): ?>
                        <?php foreach ($voitures as $voiture): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($voiture['marque']); ?></td>
                                <td><?php echo htmlspecialchars($voiture['modele']); ?></td>
                                <td><?php echo htmlspecialchars($voiture['annee']); ?></td>
                                <td><?php echo htmlspecialchars($voiture['immatriculation']); ?></td>
                                <?php if ($user_role === 'admin'): ?>
                                    <td>
                                        <a href="modifier-voiture.php?id=<?php echo $voiture['id']; ?>"
                                            class="btn btn-primary btn-sm">Modifier</a>
                                        <form method="POST" action="" style="display:inline;"
                                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette voiture ?');">
                                            <input type="hidden" name="delete_id" value="<?php echo $voiture['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                        </form>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Aucune voiture disponible.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>