<?php
session_start();

try {
    $bdd = new PDO('mysql:host=localhost;dbname=carMeeting;charset=utf8', 'root', 'cesi');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$type = isset($_GET['type']) ? $_GET['type'] : '';
$meetingId = isset($_GET['meetingId']) ? $_GET['meetingId'] : '';

if ($type && $meetingId) {
    $userId = $_SESSION['userId'];

    if ($type == 'visiteur') {
        $stmt = $bdd->prepare("INSERT INTO Participation (userId, meetingId, registrationDateParticipation) VALUES (:userId, :meetingId, CURDATE())");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':meetingId', $meetingId);

        if ($stmt->execute()) {
            echo "Inscription réussie en tant que visiteur.";
        }
        else {
            echo "Erreur lors de l'inscription.";
        }
    }
    elseif ($type == 'exposant') {
        $stmt = $bdd->prepare("SELECT carId FROM Car WHERE userId = :userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $car = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($car) {
            $carId = $car['carId'];

            $stmt = $bdd->prepare("INSERT INTO Enrollment (carId, meetingId) VALUES (:carId, :meetingId)");
            $stmt->bindParam(':carId', $carId);
            $stmt->bindParam(':meetingId', $meetingId);

            if ($stmt->execute()) {
                echo "Inscription réussie en tant qu'exposant.";
            }
            else {
                echo "Erreur lors de l'inscription.";
            }
        }
        else {
            echo "Aucune voiture trouvée pour cet utilisateur.";
        }
    }
    else {
        echo "Type d'inscription invalide.";
    }
}
else {
    echo "Paramètres manquants.";
}

$bdd = null;
?>