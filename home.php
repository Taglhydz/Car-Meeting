<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Car Meeting</title>
	<link rel="stylesheet" href="./style/home-styles.css">
	<link rel="icon" type="image/png" href="./favicon/favicon-96x96.png" sizes="96x96" />
	<link rel="icon" type="image/svg+xml" href="./favicon/favicon.svg" />
	<link rel="shortcut icon" href="./favicon/favicon.ico" />
	<link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png" />
	<link rel="manifest" href="./favicon/site.webmanifest" />
	<script src="./scripts/home.js" defer></script>
	<script src="./scripts/ajax.js" defer></script>
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
					<li><a href="./signIn.php">Inscription </a></li>
					<li><a href="./logIn.php" >Connexion   </a></li>
					<li><a href="./profil.php">Profil	   </a></li>
					<li><a href="./stats.php" >Statistiques</a></li>
					<li><a href="./logIn.php" >Déconnexion </a></li>
				</ul>
			</nav>
		</header>
		<main>
			<section class="formulaires">
				<form id="formulaire" action="./scripts/recherche.php" method="get">
					<h2>Filtrer</h2>
					<label for="categorie">Catégorie de Meetings:</label>
					<select id="categorie" name="categorie">
						<option value="tous"				  >Tous			</option>
						<option value="Voitures de Sport"	  >Sportives	</option>
						<option value="Voitures Classiques"	  >Classiques	</option>
						<option value="Voitures de Collection">Collection	</option>
						<option value="Voitures Tuning"		  >Tuning		</option>
						<option value="Véhicules Tout-Terrain">Tout-Terrain	</option>
						<option value="Voitures de Luxe"	  >Luxe			</option>
						<option value="Muscle Cars"			  >Muscle Cars	</option>
					</select>
					<br>
					<label for="lieu">Lieu:</label>
					<select id="lieu" name="lieu">
						<option value="tous">Tous</option>
						<?php
						try {
							$bdd = new PDO('mysql:host=localhost;dbname=carMeeting;charset=utf8', 'root', 'cesi');
							$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						}
						catch (Exception $e) {
							die('Erreur : ' . $e->getMessage());
						}

						$sql = "SELECT DISTINCT city FROM Address";
						$result = $bdd->query($sql);

						if ($result->rowCount() > 0) {
							while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
								echo "<option value='" . $row["city"] . "'>" . $row["city"] . "</option>";
							}
						}

						$bdd = null;
						?>
					</select>
					<br>
					<label for="etat">État:</label>
					<br>
					<div class="checkbox-container">
						<input type="checkbox" id="complet" name="etat[]" value="complet">
						<label for="complet">Complet</label>
						<br>
						<input type="checkbox" id="reserve" name="etat[]" value="reserve">
						<label for="reserve">Déjà inscrit</label>
						<br>
						<input type="checkbox" id="passe" name="etat[]" value="passe">
						<label for="passe">Meeting passé</label>
						<br>
					</div>
					<label for="date">Date après:</label>
					<input type="date" id="date" name="date">
					<br>
					<input type="submit" value="Rechercher">
					<input type="reset" value="Réinitialiser">
				</form>
				
			</section>
			<section class="resultats">
				<h2>Les prochains rassemblements</h2>
				<ul class="meetings">
					<?php
					try {
						$bdd = new PDO('mysql:host=localhost;dbname=carMeeting;charset=utf8', 'root', 'cesi');
						$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					} catch (Exception $e) {
						die('Erreur : ' . $e->getMessage());
					}

					$sql = "SELECT * FROM Meeting WHERE meetingDate > CURDATE()";
					$result = $bdd->query($sql);

					if ($result->rowCount() > 0) {
						while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
							echo "<li>" . $row["meetingDate"] . " - " . $row["description"] . " - " . $row["startTime"] . " - " . $row["endTime"];
							echo "<div>";
							echo " <p>S'inscrire en tant que:</p>";
							echo " <button class='inscrire-visiteur' data-meeting-id='" . $row['meetingId'] . "'>visiteur</button>";
							echo " <button class='inscrire-exposant' data-meeting-id='" . $row['meetingId'] . "'>exposant</button>";
							echo "</div>";
							echo "</li>";
						}
					}
					else {
						echo "Aucun rassemblement trouvé.";
					}

					$bdd = null;
					?>
				</ul>
			</section>
		</main>
		<footer>
			<section>
				<p>Site réalisé par Tom Vaillant</p>
			</section>
		</footer>
	</body>
</html>