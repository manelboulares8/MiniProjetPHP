<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=location_voitures", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

        $sql = "DELETE FROM voitures WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: liste-voitures.php");
            exit;
        } else {
            echo "Erreur lors de la suppression.";
        }
    } else {
        echo "Aucun ID fourni.";
        exit;
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>