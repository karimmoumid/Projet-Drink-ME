const favorites = document.querySelectorAll('.favorite.unselected');

for (let favorite of favorites) {
    favorite.addEventListener('click', function() {
        const addressId = favorite.dataset.id;

        fetch(`/adress/${addressId}/favorite`, {
            method: 'POST'
        })
            .then(function(response) {
                if (!response.ok) throw new Error('Erreur réseau');
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    // Réinitialise tous les boutons
                    let buttons = document.querySelectorAll('.favorite');
                    for (let btn of buttons) {
                        btn.classList.remove('selected');
                        btn.classList.add('unselected');
                        btn.disabled = false;
                        btn.textContent = 'Sélectionner comme adresse principale';
                    }

                    // Met à jour le bouton cliqué
                    favorite.classList.remove('unselected');
                    favorite.classList.add('selected');
                    favorite.disabled = true;
                    favorite.textContent = 'Adresse principale';
                } else {
                    alert('Erreur : ' + (data.error || 'Une erreur est survenue.'));
                }
            })
            .catch(function(error) {
                console.error('Erreur fetch :', error);
                alert('Erreur de connexion au serveur.');
            });
    });
}
