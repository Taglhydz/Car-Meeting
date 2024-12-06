<?php
session_start();

try {
    $bdd = new PDO('mysql:host=localhost;dbname=carMeeting;charset=utf8', 'root', 'cesi');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$categorie = isset($_GET['categorie']) ? $_GET['categorie'] : 'tous';
$lieu = isset($_GET['lieu']) ? $_GET['lieu'] : 'tous';
$etat = isset($_GET['etat']) ? $_GET['etat'] : [];
$date = isset($_GET['date']) ? $_GET['date'] : '';
$userId = $_SESSION['userId'];

$conditions = [];

if ($categorie != 'tous') {
    $conditions[] = "meetingId IN ( SELECT Meeting.meetingId FROM Meeting JOIN Categorization ctz ON Meeting.meetingId = ctz.meetingId JOIN Category c ON ctz.categoryId = c.categoryId WHERE c.categoryName = :categorie)";
}
if ($lieu != 'tous') {
    $conditions[] = "meetingId IN ( SELECT Meeting.meetingId FROM Meeting JOIN Address a ON Meeting.addressId = a.addressId WHERE a.city = :lieu)";
}
if (!empty($etat)) {
    $etatConditions = [];
    if (in_array('passe', $etat)) {
        $etatConditions[] = "meetingDate < CURDATE()";
    }
    if (in_array('complet', $etat)) {
        $etatConditions[] = "(SELECT COUNT(*) FROM Participation WHERE Participation.meetingId = Meeting.meetingId) >= maxParticipants";
    }
    if (in_array('reserve', $etat)) {
        $etatConditions[] = "Meeting.meetingId IN (SELECT meetingId FROM Participation WHERE userId = :userId)";
    }
    if (in_array('reserve', $etat)) {
        $etatConditions[] = "Meeting.meetingId IN (SELECT meetingId FROM Enrollment WHERE carId = :userId)";
    }
    if (!empty($etatConditions)) {
        if (stripos($etatConditions[0] . $etatConditions[1] . $etatConditions[2] . $etatConditions[3], "Enrollment")) {
            $exposant = true;
        }
        if (stripos($etatConditions[0] . $etatConditions[1] . $etatConditions[2] . $etatConditions[3], "Participation WHERE userId = :userId")) {
            $visiteur = true;
        }
        $conditions[] = '(' . implode(' OR ', $etatConditions) . ')';
    }
}
if (!empty($date)) {
    $conditions[] = "meetingDate >= :date";
}

$where = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';

$query = "SELECT * FROM Meeting $where";

$stmt = $bdd->prepare($query);

$params = [];
if ($categorie != 'tous') {
    $params[':categorie'] = $categorie;
}
if ($lieu != 'tous') {
    $params[':lieu'] = $lieu;
}
if (!empty($etat) && in_array('reserve', $etat)) {
    $params[':userId'] = $userId;
}
if (!empty($date)) {
    $params[':date'] = $date;
}

$stmt->execute($params);

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>" . $row["meetingDate"] . " - " . $row["description"] . " - " . $row["startTime"] . " - " . $row["endTime"];
        echo "<div>";
        if ($visiteur) {
            echo " <p>Se désinscrire en tant que:</p>";
            echo " <button class='desinscrire'       data-meeting-id='" . $row['meetingId'] . "'>se désinscrire<br>(visiteur)</button>";
        }
        else {
            echo " <p>S'inscrire en tant que:</p>";
            echo " <button class='inscrire-visiteur' data-meeting-id='" . $row['meetingId'] . "'>visiteur</button>"                    ;
        }
        if ($exposant) {
            echo " <p>Se désinscrire en tant que:</p>";
            echo " <button class='desinscrire'       data-meeting-id='" . $row['meetingId'] . "'>se désinscrire<br>(exposant)</button>";
        }
        else {
            echo " <p>S'inscrire en tant que:</p>";
            echo " <button class='inscrire-exposant' data-meeting-id='" . $row['meetingId'] . "'>exposant</button>"                    ;
        }
        echo "</div>";
        echo "</li>";
    }
}
else {
    echo "Aucun rassemblement trouvé.";
}

$bdd = null;
?>