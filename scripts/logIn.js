let formLog, messageError, email, password, stayConnected;

function init() {
    formLog		  = document.getElementById("formLog");
	messageError  = document.getElementById("messageError");
	email		  = document.getElementById("email");
	password 	  = document.getElementById("password");
    formLog		  .addEventListener("submit", function(event) {
		event.preventDefault();
		verif(event);
	});

	// loadData();
}



function verif(event) {
	event.preventDefault();
	if (email.value == "" || email.value.indexOf("@") == -1 || email.value.indexOf(".") == -1) {
		messageError.textContent = "";
		messageError.textContent = "Veuillez entrer un email valide";
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
		window.location.href = './home.html';
	}
}


// function saveData() {
// 	const data = {
// 		// nb: parseInt(nb.textContent),
// 		// prixClk: parseInt(prixClk.textContent),
// 		// prixSec: parseInt(prixSec.textContent),
// 		// clique: clique,
// 		// cliqueAuto: cliqueAuto,
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