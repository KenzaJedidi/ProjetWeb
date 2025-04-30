$(document).ready(function() {
    // Variables pour stocker les réclamations
    let reclamations = [];
    let nextId = 1;

    // Fonction pour afficher les réclamations dans le tableau
    function displayReclamations() {
        const tbody = $('#reclamationsTable tbody');
        tbody.empty();
        
        reclamations.forEach(reclamation => {
            const stars = '★'.repeat(reclamation.rating) + '☆'.repeat(5 - reclamation.rating);
            
            const row = `
                <tr>
                    <td>${reclamation.id}</td>
                    <td>${reclamation.nom}</td>
                    <td>${reclamation.prenom}</td>
                    <td>${stars}</td>
                    <td>${reclamation.message.substring(0, 50)}...</td>
                    <td>${new Date(reclamation.date).toLocaleDateString()}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-primary btn-edit" data-id="${reclamation.id}" title="Modifier">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-delete" data-id="${reclamation.id}" title="Supprimer">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Ajouter une réclamation
    $('#addReclamationForm').submit(function(e) {
        e.preventDefault();
        
        const nom = $('#nom').val().trim();
        const prenom = $('#prenom').val().trim();
        const rating = $('input[name="rating"]:checked').val();
        const message = $('#message').val().trim();
        
        // Validation
        if (!nom || !prenom || !rating || !message) {
            showAlert('Veuillez remplir tous les champs obligatoires', 'danger');
            return;
        }
        
        // Créer la réclamation
        const newReclamation = {
            id: nextId++,
            nom,
            prenom,
            rating: parseInt(rating),
            message,
            date: new Date().toISOString()
        };
        
        reclamations.push(newReclamation);
        displayReclamations();
        
        // Réinitialiser le formulaire
        $('#addReclamationForm')[0].reset();
        $('#addReclamationModal').modal('hide');
        
        showAlert('Réclamation ajoutée avec succès!', 'success');
    });

    // Pré-remplir le formulaire de modification
    $(document).on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        const reclamation = reclamations.find(r => r.id === id);
        
        if (reclamation) {
            $('#edit_id').val(reclamation.id);
            $('#edit_nom').val(reclamation.nom);
            $('#edit_prenom').val(reclamation.prenom);
            $(`#edit_star${reclamation.rating}`).prop('checked', true);
            $('#edit_message').val(reclamation.message);
            
            $('#editReclamationModal').modal('show');
        }
    });

    // Modifier une réclamation
    $('#editReclamationForm').submit(function(e) {
        e.preventDefault();
        
        const id = parseInt($('#edit_id').val());
        const nom = $('#edit_nom').val().trim();
        const prenom = $('#edit_prenom').val().trim();
        const rating = $('input[name="edit_rating"]:checked').val();
        const message = $('#edit_message').val().trim();
        
        // Validation
        if (!nom || !prenom || !rating || !message) {
            showAlert('Veuillez remplir tous les champs obligatoires', 'danger');
            return;
        }
        
        // Mettre à jour la réclamation
        const index = reclamations.findIndex(r => r.id === id);
        if (index !== -1) {
            reclamations[index] = {
                ...reclamations[index],
                nom,
                prenom,
                rating: parseInt(rating),
                message,
                date: new Date().toISOString()
            };
            
            displayReclamations();
            $('#editReclamationModal').modal('hide');
            showAlert('Réclamation modifiée avec succès!', 'success');
        } else {
            showAlert('Réclamation non trouvée', 'danger');
        }
    });

    // Supprimer une réclamation
    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        $('#delete_id').val(id);
        $('#deleteReclamationModal').modal('show');
    });

    $('#confirmDelete').click(function() {
        const id = parseInt($('#delete_id').val());
        reclamations = reclamations.filter(r => r.id !== id);
        
        displayReclamations();
        $('#deleteReclamationModal').modal('hide');
        showAlert('Réclamation supprimée avec succès!', 'success');
    });

    // Fonction pour afficher des messages d'alerte
    function showAlert(message, type) {
        const alert = $(`
            <div class="alert alert-${type} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        `);
        
        $('body').append(alert);
        
        setTimeout(() => {
            alert.alert('close');
        }, 5000);
    }

    // Initialiser avec quelques données de test
    reclamations = [
        {
            id: nextId++,
            nom: 'Dupont',
            prenom: 'Jean',
            rating: 4,
            message: 'Problème avec ma réservation, le service était lent.',
            date: '2023-05-15T10:30:00Z'
        },
        {
            id: nextId++,
            nom: 'Martin',
            prenom: 'Sophie',
            rating: 2,
            message: 'Très déçue par la qualité du service.',
            date: '2023-05-10T14:45:00Z'
        }
    ];
    
    displayReclamations();
});
