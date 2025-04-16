$(document).ready(function() {
    // Gestion du clic sur "Postuler"
    $('.postuler-btn').click(function() {
        var poste = $(this).data('poste');
        $('#poste').val(poste);
        $('#candidatureModal').modal('show');
    });
    
    // Soumission du formulaire
    $('#formCandidature').submit(function(e) {
        e.preventDefault();
        
        // Validation
        if ($('#nomComplet').val() === '' || $('#email').val() === '' || $('#cv').val() === '') {
            alert('Veuillez remplir tous les champs obligatoires');
            return false;
        }
        
        // Validation fichier
        const file = $('#cv')[0].files[0];
        if (file && file.size > 2097152) {
            alert('Le fichier ne doit pas dépasser 2MB');
            return false;
        }
        
        // Envoi des données
        const formData = new FormData(this);
        formData.append('action', 'add');
        
        $.ajax({
            url: 'emploi.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    alert('Candidature envoyée!');
                    $('#candidatureModal').modal('hide');
                    $('#formCandidature')[0].reset();
                    
                    // Stockage local
                    const candidatures = JSON.parse(localStorage.getItem('candidaturesLocaloo')) || [];
                    candidatures.push({
                        poste: $('#poste').val(),
                        nom: $('#nomComplet').val(),
                        email: $('#email').val(),
                        telephone: $('#telephone').val(),
                        date: new Date().toLocaleDateString('fr-FR'),
                        status: 'En attente'
                    });
                    localStorage.setItem('candidaturesLocaloo', JSON.stringify(candidatures));
                } else {
                    alert('Erreur: ' + (response.message || 'Une erreur est survenue'));
                }
            },
            error: function() {
                alert('Erreur de connexion au serveur');
            }
        });
    });
});