<?php
session_start();

try {
    $bdd = new PDO('mysql:host=localhost;dbname=carMeeting;charset=utf8', 'root', 'cesi');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$sqlQuery = "SELECT color, COUNT(*) as count FROM Car GROUP BY color";
$sth = $bdd->prepare($sqlQuery);
$sth->execute();

$colorsData = $sth->fetchAll(PDO::FETCH_ASSOC);

usort($colorsData, function($a, $b) {
    return $b['count'] - $a['count'];
});

$labels = [];
$counts = [];
$colors = [];
$otherCount = 0;
$otherColors = [];

$colorMap = [
    'Rouge'   => '#FF0000',
    'Bleu'    => '#0000FF',
    'Vert'    => '#008000',
    'Jaune'   => '#FFFF00',
    'Noir'    => '#000000',
    'Blanc'   => '#FFFFFF',
    'Gris'    => '#808080',
    'Orange'  => '#FFA500',
    'Rose'    => '#FFC0CB',
    'Violet'  => '#800080',
    'Marron'  => '#A52A2A',
    'Cyan'    => '#00FFFF',
    'Magenta' => '#FF00FF',
    'Argent'  => '#C0C0C0',
    'Or'      => '#FFD700'
];

foreach ($colorsData as $index => $row) {
    if ($index < 7) {
        $labels[] = $row['color'];
        $counts[] = $row['count'];
        $colors[] = isset($colorMap[$row['color']]) ? $colorMap[$row['color']] : '#CCCCCC';
    }
    else {
        $otherCount += $row['count'];
        $otherColors[] = $row['color'];
    }
}

if ($otherCount > 0) {
    $labels[] = 'Autres';
    $counts[] = $otherCount;
    $colors[] = '#CCCCCC';
}

echo json_encode([
    'labels' => $labels,
    'counts' => $counts,
    'colors' => $colors
]);

$bdd = null;
?>