<?php

if (!isset($_SESSION['user_email'])) {

    exit;
}

$user_role = $_SESSION['user_role'];
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">MB Rent</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link " href="liste-voitures.php">Voitures</a>
                </li>
                <?php if ($user_role === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link " href="ajouter-voiture.php">Ajouter une Voiture</a>
                    </li>
                <?php endif ?>
                <li class="nav-item">
                    <a class="nav-link" href="rechercher_voiture.php">Réserver</a>
                </li>
                <?php if ($user_role === 'admin'): ?>

                    <li class="nav-item">
                        <a class="nav-link" href="lister_reservations.php">Liste des réservations</a>
                    </li>
                <?php endif ?>
                <?php if ($user_role === 'user'): ?>

                    <li class="nav-item">
                        <a class="nav-link" href="liste_reservation_user.php">Liste des réservations</a>
                    </li>
                <?php endif ?>

            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="btn btn-danger" href="index.html">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>