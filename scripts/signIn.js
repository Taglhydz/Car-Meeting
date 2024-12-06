let formSign, messageError, prenom, nom, email, password, dateNaissance;

function init() {
    formSign 	  = document.getElementById("formSign");
    messageError  = document.getElementById("messageError");
	nom 		  = document.getElementById("last_name");
	prenom 		  = document.getElementById("first_name");
	dateNaissance = document.getElementById("date_of_birth");
	email		  = document.getElementById("email");
	password 	  = document.getElementById("password");
    formSign	  .addEventListener("submit", function(event) {
		event.preventDefault();
		verif(event);
	});

	// loadData();
}



function verif(event) {
	event.preventDefault();
	if (nom.value == "") {
		messageError.textContent = "";
		messageError.textContent = "Veuillez entrer un nom";
	}
	else if(email.value == "") {
		messageError.textContent = "";
		messageError.textContent = "Veuillez entrer un email";
	}
	else if(email.value.indexOf("@") == -1 || email.value.indexOf(".") == -1) {
		messageError.textContent = "";
		messageError.textContent = "Veuillez rentrer un email valide";
	}
	else if(password.value == "") {
		messageError.textContent = "";
		messageError.textContent = "Veuillez entrer un mot de passe";
	}
	else if(password.value.length < 8) {
		messageError.textContent = "";
		messageError.textContent = "Veuillez rentrer un mot de passe de 8 caractères ou plus";
	}
	else {
		messageError.textContent = "";
		window.location.href = './logIn.php';
	}
}


// function saveData() {
// 	const data = {
// 		nb: parseInt(nb.textContent),
// 		prixClk: parseInt(prixClk.textContent),
// 		prixSec: parseInt(prixSec.textContent),
// 		clique: clique,
// 		cliqueAuto: cliqueAuto,
// 	};
// 	localStorage.setItem('gameData', JSON.stringify(data));
// 	alert("Votre score a bien été sauvegardé");
// }

// function loadData() {
// 	const savedData = localStorage.getItem('gameData');
// 	if(savedData) {
// 		const data = JSON.parse(savedData);
// 		nb.textContent = data.nb;
// 		prixClk.textContent = data.prixClk;
// 		prixSec.textContent = data.prixSec;
// 		clique = data.clique;
// 		cliqueAuto = data.cliqueAuto;
// 		visuel();
// 	}
// }

document.addEventListener("DOMContentLoaded", init);