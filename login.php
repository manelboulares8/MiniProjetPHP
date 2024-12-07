<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=location_voitures", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = :email AND mot_de_passe = :password");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();

        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($client) {
            $_SESSION['user_email'] = $client['email'];
            $_SESSION['user_role'] = $client['role'];

            if ($client['role'] === 'admin') {
                header("Location: liste-voitures.php");
            } else {
                header("Location: liste-voitures.php");
            }
            exit;
        } else {
            $errorMessage = "Email ou mot de passe incorrect.";
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
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-bg {
            background-image: url('bgvoitures.jpeg');
            /* Chemin de l'image */
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
            <div class="form-card mx-auto" style="max-width: 400px;">
                <h3 class="text-center mb-4">Connexion</h3>
                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary"
                            style="background-color: red; color: white; border: 2px solid red;">Se connecter</button>

                    </div>
                </form>
                <div class="text-center mt-3">
                    <a href="signup.php" style="color: red; text-decoration: none;"
                        onmouseover="this.style.color='darkred'" onmouseout="this.style.color='red'">Pas encore inscrit
                        ? Créez un compte ici</a>
                </div>


                <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-danger mt-3">
                        <?php echo $errorMessage; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>