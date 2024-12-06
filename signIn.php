<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Car Meeting</title>
	<link rel="stylesheet" href="./style/signin-styles.css">
	<link rel="icon" type="image/png" href="./favicon/favicon-96x96.png" sizes="96x96" />
	<link rel="icon" type="image/svg+xml" href="./favicon/favicon.svg" />
	<link rel="shortcut icon" href="./favicon/favicon.ico" />
	<link rel="apple-touch-icon" sizes="180x180" href="./favicon/apple-touch-icon.png" />
	<link rel="manifest" href="./favicon/site.webmanifest" />
</head>
	<body>
		<header>
			<div class="logo">
				<img src="./ui/image/icon.png" alt="car">
			</div>
			<div><h1>Car Meeting</h1></div>
			<div></div>
		</header>
		<main>
			<h2>Inscription</h2>
			<section class="signIn">
				<form id="formSign" method="post">
					<input type="text"     name="firstName"   placeholder="Prénom" 			  id="firstName" 	required value="testFirstName">
					<input type="text" 	   name="lastName"    placeholder="Nom" 			  id="lastName" 	required value="testLastName">
					<input type="date" 	   name="dateOfBirth" placeholder="Date de naissance" id="dateOfBirth" 	required>
					<input type="email"    name="email"       placeholder="Email" 			  id="email" 		required>
					<input type="password" name="password"    placeholder="Mot de passe" 	  id="password" 	required>
					<input type="submit"   name="submit" value="S'inscrire" class="submit">
					<a class="switchChoice" href="./logIn.php">Se connecter ?</a>
				</form>
				<p id="messageError"></p>
				<?php
					if ($_SERVER["REQUEST_METHOD"] == "POST") {
						$firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
						$lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
						$dateOfBirth = filter_input(INPUT_POST, 'dateOfBirth', FILTER_SANITIZE_STRING);
						$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
						$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

						if ($firstName && $lastName && $dateOfBirth && $email && $password) {
							$registrationDate = date('Y-m-d');

							try {
								$bdd = new PDO('mysql:host=localhost;dbname=carMeeting;charset=utf8', 'root', 'cesi');
								$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							}
							catch (Exception $e) {
								die('Erreur : ' . $e->getMessage());
							}

							$req = $bdd->prepare('SELECT * FROM User WHERE email = :email');
							$req->execute(array('email' => $email));

							$user = $req->fetch();

							if (!$user) {
								$req = $bdd->prepare('INSERT INTO User(firstName, lastName, dateOfBirth, email, registrationDate, password) VALUES(:firstName, :lastName, :dateOfBirth, :email, :registrationDate, :password)');
								$req->execute(array(
									'firstName' 		=> $firstName,
									'lastName' 			=> $lastName,
									'dateOfBirth' 		=> $dateOfBirth,
									'email' 			=> $email,
									'registrationDate' 	=> $registrationDate,
									'password' 			=> password_hash($password, PASSWORD_DEFAULT)
								));

								session_start();
								$_SESSION['userId'			] = $bdd->lastInsertId();
								$_SESSION['firstName'		] = $firstName;
								$_SESSION['lastName'		] = $lastName;
								$_SESSION['email'			] = $email;
								$_SESSION['dateOfBirth'		] = $dateOfBirth;
								$_SESSION['registrationDate'] = $registrationDate;


								header('Location: ./logIn.php');
							}
							else {
								echo '<p id="messageError">Cet email est déjà utilisé.</p>';
							}
						}
						else {
							echo '<p id="messageError">Veuillez remplir tous les champs correctement.</p>';
						}
					}
					?>
			</section>
		</main>
		<footer>
			<section>
				<p>Site réalisé par Tom Vaillant</p>
			</section>
		</footer>
	</body>
</html>