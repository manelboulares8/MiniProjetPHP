<?php
session_start();

if (isset($_GET['id']) && isset($_GET['date_debut']) && isset($_GET['date_fin'])) {
    $id_voiture = $_GET['id'];
    $date_debut = $_GET['date_debut'];
    $date_fin = $_GET['date_fin'];
    $email_client = $_SESSION['user_email'];
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=location_voitures", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("
            SELECT * FROM voitures
            WHERE id = :id_voiture AND disponibilite = 1
            AND id NOT IN (
                SELECT id_voiture FROM reservations
                WHERE (date_debut BETWEEN :date_debut AND :date_fin)
                OR (date_fin BETWEEN :date_debut AND :date_fin)
            )
        ")
        ;
        $successMessage = "";
        $stmt->bindParam(':id_voiture', $id_voiture);
        $stmt->bindParam(':date_debut', $date_debut);
        $stmt->bindParam(':date_fin', $date_fin);

        $stmt->execute();
        $voiture = $stmt->fetch();

        if ($voiture) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $nom_client = $_POST['nom_client'];
                // $email_client = $_POST['email_client'];

                $stmt = $pdo->prepare("
                    INSERT INTO reservations (id_voiture, date_debut, date_fin, nom_client,email_client)
                    VALUES (:id_voiture, :date_debut, :date_fin, :nom_client,:email_client)
                ");
                $stmt->bindParam(':id_voiture', $id_voiture);
                $stmt->bindParam(':date_debut', $date_debut);
                $stmt->bindParam(':date_fin', $date_fin);
                $stmt->bindParam(':nom_client', $nom_client);
                $stmt->bindParam(':email_client', $email_client);
                if ($stmt->execute()) {
                    $successMessage = "Inscription effectuée avec succès !";
                } else {
                    echo "<div class='alert alert-danger'>Erreur lors de la réservation.</div>";
                }

            }
        } else {
            echo "<div class='alert alert-warning'>La voiture n'est pas disponible pour cette période.</div>";
        }
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Erreur : " . $e->getMessage() . "</div>";
    }
} else {
    echo "<div class='alert alert-warning'>Informations manquantes pour la réservation.</div>";
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation de Voiture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }

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
            /* Fond semi-transparent */
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
            margin-right: 36%;
        }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="bg-image">
        <div class="container">
            <div class="form-container">
                <h1 class="text-center mb-4">Réservation</h1>

                <form method="POST">
                    <div class="mb-3">
                        <label for="nom_client" class="form-label">Votre Nom et prénom</label>
                        <input type="text" class="form-control" id="nom_client" name="nom_client" required>
                    </div>
                    <div class="mb-3">
                        <label for="email_client" class="form-label">Votre Email</label>
                        <input type="email" class="form-control" id="email_client" name="email_client" required
                            value="<?php echo $_SESSION['user_email']; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="date_debut" class="form-label">Date de début</label>
                        <input type="date" class="form-control" id="date_debut" name="date_debut"
                            value="<?php echo $date_debut; ?>" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="date_fin" class="form-label">Date de fin</label>
                        <input type="date" class="form-control" id="date_fin" name="date_fin"
                            value="<?php echo $date_fin; ?>" required readonly>
                    </div>
                    <button type="submit" class="btn btn-danger border-danger b">Confirmer la Réservation</button>
                    <a href="rechercher_voiture.php" class="btn btn-secondary">Rechercher une autre voiture</a>
                    <br>
                    <?php if ($successMessage): ?>
                        <div class="alert alert-success"><?php echo $successMessage; ?></div>

                    <?php endif; ?>

                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>