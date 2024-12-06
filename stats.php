<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Car Meeting</title>
	<link rel="stylesheet" href="./style/stats-styles.css">
	<link rel="icon" type="image/png" href="./favicon/favicon-96x96.png" sizes="96x96" />
	<link rel="icon" type="image/svg+xml" href="./favicon/favicon.svg" />
	<link rel="shortcut icon" href="./favicon/favicon.ico" />
	<link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png" />
	<link rel="manifest" href="./favicon/site.webmanifest" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
	<body>
		<header>
			<div class="logo">
				<img src="./ui/image/icon.png" alt="car">
			</div>
			<h1>Car Meeting</h1>
			<nav class="menu">
				<h3 class="titre-menu">Menu</h3>
				<ul>
					<li><a href="./home.php"  >Accueil	   </a></li>
					<li><a href="./signIn.php">Inscription </a></li>
					<li><a href="./logIn.php" >Connexion   </a></li>
					<li><a href="./profil.php">Profil	   </a></li>
					<li><a href="./logIn.php" >Déconnexion </a></li>
				</ul>
			</nav>
		</header>
        <main>
        <section class="resultats">
				<h2>Statistiques</h2>
				<ul class="meetings">
                    <li>Nombre de rassemblements à venir :
                    <?php
                    try {
                        $bdd = new PDO('mysql:host=localhost;dbname=carMeeting;charset=utf8', 'root', 'cesi');
                        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    }
                    catch (Exception $e) {
                        die('Erreur : ' . $e->getMessage());
                    }

                    $sql = "SELECT COUNT(*) FROM Meeting WHERE meetingDate > CURDATE()";
                    $result = $bdd->query($sql);

                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    echo $row["COUNT(*)"];

                    $bdd = null;
                    ?>
                    </li>
                    <li>Nombre de rassemblements passés :
                    <?php
                    try {
                        $bdd = new PDO('mysql:host=localhost;dbname=carMeeting;charset=utf8', 'root', 'cesi');
                        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    }
                    catch (Exception $e) {
                        die('Erreur : ' . $e->getMessage());
                    }

                    $sql = "SELECT COUNT(*) FROM Meeting WHERE meetingDate < CURDATE()";
                    $result = $bdd->query($sql);

                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    echo $row["COUNT(*)"];

                    $bdd = null;
                    ?>
                    </li>
                    <li>Nombre de personnes inscrites :
                    <?php
                    try {
                        $bdd = new PDO('mysql:host=localhost;dbname=carMeeting;charset=utf8', 'root', 'cesi');
                        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    }
                    catch (Exception $e) {
                        die('Erreur : ' . $e->getMessage());
                    }

                    $sql = "SELECT COUNT(*) FROM User";
                    $result = $bdd->query($sql);

                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    echo $row["COUNT(*)"];

                    $bdd = null;
                    ?>
                    </li>
                    <li>Pourcentage de personnes inscrites qui ont une voiture :
                    <?php
                    try {
                        $bdd = new PDO('mysql:host=localhost;dbname=carMeeting;charset=utf8', 'root', 'cesi');
                        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    } catch (Exception $e) {
                        die('Erreur : ' . $e->getMessage());
                    }

                    $sql = "SELECT COUNT(*) FROM User WHERE carId IS NOT NULL";
                    $result = $bdd->query($sql);

                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    $carOwners = $row["COUNT(*)"];

                    $sql = "SELECT COUNT(*) FROM User";
                    $result = $bdd->query($sql);

                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    $totalUsers = $row["COUNT(*)"];

                    if ($totalUsers > 0) {
                        echo round($carOwners / $totalUsers * 100, 2) . "%";
                    }
                    else {
                        echo "0%";
                    }

                    $bdd = null;
                    ?>
                    </li>
                    <li>Diagramme cicurlaire des couleurs des voitures :
                        <canvas id="carColorsChart" width="400" height="400"></canvas>
                    </li>
				</ul>
			</section>
        </main>
        <footer>
			<section>
				<p>Site réalisé par Tom Vaillant</p>
			</section>
		</footer>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('./scripts/getCarColors.php')
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('carColorsChart').getContext('2d');
                    const carColorsChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Couleurs des voitures',
                                data: data.counts,
                                backgroundColor: data.colors,
                                borderColor: 'rgba(255, 255, 255, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: {
                                        color: 'white'
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            return tooltipItem.label + ': ' + tooltipItem.raw;
                                        }
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error:', error));
        });
        </script>
	</body>
</html>