<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=location_voitures", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (!empty($nom) && !empty($adresse) && !empty($telephone) && !empty($email) && !empty($password) && !empty($confirmPassword)) {
        if ($password === $confirmPassword) {
            try {
                $stmt = $pdo->prepare("INSERT INTO clients (nom, adresse, telephone, email, mot_de_passe) 
                                       VALUES (:nom, :adresse, :telephone, :email, :password)");
                $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
                $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
                $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);


                if ($stmt->execute()) {
                    $successMessage = "Inscription effectuée avec succès !";
                } else {
                    $errorMessage = "Erreur lors de l'inscription !";
                }
            } catch (PDOException $e) {
                $errorMessage = "Erreur : " . $e->getMessage();
            }
        } else {
            $errorMessage = "Les mots de passe ne correspondent pas.";
        }
    } else {
        $errorMessage = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-bg {
            background-image: url('bgvoitures.jpeg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <div class="form-bg">
        <div class="container">
            <div class="form-card mx-auto" style="max-width: 500px;">
                <h3 class="text-center mb-4">Inscription</h3>



                <!-- Formulaire d'inscription -->
                <form method="POST" action="signup.php">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom et prénom </label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="adresse" name="adresse" required>
                    </div>
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" id="telephone" name="telephone" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmez le mot de passe</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                            required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-danger border-danger">S'inscrire</button><br>
                    </div>
                    <?php if ($successMessage): ?>
                        <div class="alert alert-success"><?php echo $successMessage; ?></div>

                    <?php endif; ?>
                </form>

                <div class="text-center mt-3">
                    <a href="login.php">Déjà inscrit ? Connectez-vous ici</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>