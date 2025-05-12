document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour attacher les gestionnaires d'événements aux boutons
    function attachButtonHandlers() {
        // Gestionnaire pour le bouton "Voir"
        document.querySelectorAll('.view-candidature').forEach(button => {
            // Vérifier si l'événement est déjà attaché pour éviter les doublons
            if (!button.dataset.listenerAdded) {
                button.addEventListener('click', function() {
                    const candidature = JSON.parse(this.getAttribute('data-candidature'));
                    document.getElementById('detail-id').textContent = candidature.id;
                    document.getElementById('detail-nom').textContent = candidature.nom_complet;
                    document.getElementById('detail-email').textContent = candidature.email;
                    document.getElementById('detail-telephone').textContent = candidature.telephone || '-';
                    document.getElementById('detail-date').textContent = candidature.date_postulation;
                    document.getElementById('detail-status').textContent = candidature.status;
                    document.getElementById('detail-cv').setAttribute('href', `../../../Uploads/cvs/${candidature.cv_path}`);

                    const detailsModal = new bootstrap.Modal(document.getElementById('candidateDetailsModal'), {
                        keyboard: false
                    });
                    detailsModal.show();
                });
                button.dataset.listenerAdded = 'true';
            }
        });

        // Gestionnaires pour les boutons "Accepter" et "Rejeter"
        document.querySelectorAll('.accept-candidature, .reject-candidature').forEach(button => {
            // Vérifier si l'événement est déjà attaché pour éviter les doublons
            if (!button.dataset.listenerAdded) {
                button.addEventListener('click', function() {
                    const candidatureId = this.getAttribute('data-candidature-id');
                    const newStatus = this.classList.contains('accept-candidature') ? 'Accepté' : 'Rejeté';

                    fetch('../../../controllers/CandidatureController.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `action=updateStatus&candidature_id=${candidatureId}&status=${encodeURIComponent(newStatus)}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const row = this.closest('tr');
                            const statusCell = row.querySelector('.badge-status');
                            const statusClass = newStatus.toLowerCase().replace('é', 'e').replace(' ', '-');
                            statusCell.className = `badge badge-status ${statusClass}`;
                            statusCell.textContent = newStatus;
                            alert(`Candidature ${newStatus.toLowerCase()} avec succès`);
                            // Recharger les notifications après mise à jour du statut
                            loadNotifications();
                        } else {
                            alert('Erreur : ' + data.message);
                        }
                    })
                    .catch(error => {
                        alert('Erreur lors de la mise à jour du statut : ' + error);
                    });
                });
                button.dataset.listenerAdded = 'true';
            }
        });
    }

    // Attacher les gestionnaires au chargement initial
    attachButtonHandlers();

    // Observer les modifications du DOM pour attacher les gestionnaires aux nouveaux boutons
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                attachButtonHandlers();
            }
        });
    });

    // Observer le conteneur des candidats
    const candidatesList = document.getElementById('candidatesList');
    if (candidatesList) {
        observer.observe(candidatesList, {
            childList: true,
            subtree: true
        });
    }
});