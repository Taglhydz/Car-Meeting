document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('formulaire').addEventListener('submit', function(event) {
        event.preventDefault();
        
        const formData = new FormData(this);
        const params = new URLSearchParams(formData).toString();
        
        fetch(`./scripts/recherche.php?${params}`)
            .then(response => response.text())
            .then(data => {
                document.querySelector('.meetings').innerHTML = data;
                attachEventListeners();
            })
            .catch(error => console.error('Error:', error));
    });

    function attachEventListeners() {
        document.querySelectorAll('.inscrire-visiteur').forEach(button => {
            button.addEventListener('click', function() {
                inscrireVisiteur(this.dataset.meetingId);
            });
        });

        document.querySelectorAll('.inscrire-exposant').forEach(button => {
            button.addEventListener('click', function() {
                inscrireExposant(this.dataset.meetingId);
            });
        });
    }

    function inscrireVisiteur(meetingId) {
        fetch(`./scripts/inscription.php?type=visiteur&meetingId=${meetingId}`)
            .then(response => response.text())
            .then(data => {
                alert(data);
            })
            .catch(error => console.error('Error:', error));
    }

    function inscrireExposant(meetingId) {
        fetch(`./scripts/inscription.php?type=exposant&meetingId=${meetingId}`)
            .then(response => response.text())
            .then(data => {
                alert(data);
            })
            .catch(error => console.error('Error:', error));
    }

    attachEventListeners();
});