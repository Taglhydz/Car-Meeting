<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<title>Car Meeting</title>
	<link rel="stylesheet" href="./style/login-styles.css">
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
			<h2>Connexion</h2>
			<section class="logIn">
				<form id="formLog" method="post">
					<input type="email"    name="email"    placeholder="Email" 		  id="email"    required>
					<input type="password" name="password" placeholder="Mot de passe" id="password" required>
					<input type="submit" value="Connexion" class="submit">
					<div class="checkbox-container">
						<input type="checkbox" class="cbox" id="stayConnected">
						<label for="stayConnected">rester connecté</label>
					</div>
					<a class="switchChoice" href="./signIn.php">S'inscrire ?</a>
				</form>
				<p id="messageError"></p>

				<?php
					if(isset($_POST['email']) && isset($_POST['password'])) {
						$email = $_POST['email'];
						$password = $_POST['password'];
						
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

						if($user && password_verify($password, $user['password'])) {
							session_start();
							$_SESSION['userId'	   ] = $user['userId'     ];
							$_SESSION['firstName'  ] = $user['firstName'  ];
							$_SESSION['lastName'   ] = $user['lastName'   ];
							$_SESSION['email'      ] = $user['email'	  ];
							$_SESSION['dateOfBirth'] = $user['dateOfBirth'];

							if(isset($_POST['stayConnected'])) {
								setcookie('email'   , $email   , time() + 365*24*3600, null, null, false, true);
								setcookie('password', $password, time() + 365*24*3600, null, null, false, true);
							}

							header('Location: ./home.php');
						}
						else {
							echo '<p id="messageError">Email ou mot de passe incorrect</p>';
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