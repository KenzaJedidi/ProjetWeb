<!doctype html>
<html class="no-js" lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Réservations - Localoo</title>
    <link rel="shortcut icon" type="image/icon" href="assets/images/logolocaloo.png"/>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/bootsnav.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* Styles spécifiques à la page de réservation */
        .page-header {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('assets/images/reservation-bg.jpg');
            background-size: cover;
            background-position: center;
            color: #fff;
            padding: 100px 0;
            text-align: center;
            margin-bottom: 50px;
        }
        
        .page-header h1 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .reservation-form-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .reservation-list {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 30px;
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .table th {
            background-color: #81D8D0;
            color: white;
        }
        
        .badge-hotel { background-color: #4e73df; }
        .badge-restaurant { background-color: #1cc88a; }
        .badge-activity { background-color: #f6c23e; color: #000; }
        
        @media (max-width: 768px) {
            .page-header {
                padding: 60px 0;
            }
            
            .page-header h1 {
                font-size: 32px;
            }
        }
    </style>
</head>

<body>
    <!-- Top Area Start -->
    <section class="top-area">
        <div class="header-area">
            <nav class="navbar navbar-default bootsnav navbar-sticky">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                            <i class="fa fa-bars"></i>
                        </button>
                        <a class="navbar-brand" href="index.html">
                            <img src="assets/images/logolocaloo.png" alt="Localoo Logo">
                        </a>
                    </div>
                    <div class="collapse navbar-collapse menu-ui-design" id="navbar-menu">
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="index.html">Accueil</a></li>
                            <li><a href="emploi.html">Emploi</a></li>
                            <li><a href="events.html">Events</a></li>
                            <li class="active"><a href="reservation.html">Réservation</a></li>
                            <li><a href="reclamation.html">Réclamation</a></li>
                            <li><a href="forum.html">Forum</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </section>
    <!-- Top Area End -->
    
    <!-- Page Header Start -->
    <section class="page-header">
        <div class="container">
            <h1>Gestion des Réservations</h1>
            <p>Gérez vos réservations en ligne</p>
        </div>
    </section>
    <!-- Page Header End -->

    <!-- Reservation Section Start -->
    <section class="reservation-section">
        <div class="container">
            <!-- Formulaire de réservation -->
            <div class="reservation-form-container">
                <h3 id="formTitle">Nouvelle Réservation</h3>
                <form id="reservationForm">
                    <input type="hidden" id="reservationId">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="prenom" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="telephone">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="destination_id" class="form-label">ID Destination</label>
                                <input type="number" class="form-control" id="destination_id" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="type" class="form-label">Type</label>
                                <select class="form-control" id="type" required>
                                    <option value="hotel">Hôtel</option>
                                    <option value="restaurant">Restaurant</option>
                                    <option value="activity">Activité</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_depart" class="form-label">Date de départ</label>
                                <input type="date" class="form-control" id="date_depart" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_retour" class="form-label">Date de retour</label>
                                <input type="date" class="form-control" id="date_retour">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nombre_personnes" class="form-label">Nombre de personnes</label>
                                <input type="number" class="form-control" id="nombre_personnes" min="1" value="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="commentaires" class="form-label">Commentaires</label>
                                <textarea class="form-control" id="commentaires" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <button type="button" class="btn btn-secondary" id="cancelBtn" style="display:none;">Annuler</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Liste des réservations -->
            <div class="reservation-list">
                <h3>Liste des Réservations</h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Destination</th>
                                <th>Départ</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reservationsList">
                            <!-- Les réservations seront ajoutées ici dynamiquement -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- Reservation Section End -->

    <!-- Footer Start -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="footer-about">
                        <h3>À propos de Localoo</h3>
                        <p>La plateforme locale tunisienne qui connecte les gens aux services et opportunités près de chez eux.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="footer-links">
                        <h3>Liens rapides</h3>
                        <ul>
                            <li><a href="index.html">Accueil</a></li>
                            <li><a href="emploi.html">Emploi</a></li>
                            <li><a href="events.html">Events</a></li>
                            <li><a href="reservation.html">Réservations</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="footer-social">
                        <h3>Suivez-nous</h3>
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                        <a href="#"><i class="fa fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 Localoo. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
    <!-- Footer End -->

    <!-- JS Files -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/bootsnav.js"></script>
    <script>
        // Stockage temporaire (remplacer par une vraie base de données en production)
        let reservations = [];
        
        <script>
    let currentReservationId = null;

    // Chargement initial
    $(document).ready(function() {
        loadReservations();
        setupForm();
        
        // Highlight le menu actif
        var current = location.pathname.split('/').pop();
        $('.nav li a').each(function() {
            var $this = $(this);
            if($this.attr('href') === current) {
                $this.parent().addClass('active');
            }
        });
    });

    // Charger les réservations depuis la base de données
    function loadReservations() {
        $.ajax({
            url: 'api/reservations.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#reservationsList').html(
                    data.map(reservation => `
                        <tr>
                            <td>${reservation.id}</td>
                            <td>${reservation.nom}</td>
                            <td>${reservation.prenom}</td>
                            <td>${reservation.email}</td>
                            <td><span class="badge ${getBadgeClass(reservation.type)}">${getTypeName(reservation.type)}</span></td>
                            <td>${reservation.destination_id}</td>
                            <td>${new Date(reservation.date_depart).toLocaleDateString('fr-FR')}</td>
                            <td>
                                <button class="btn btn-sm btn-info me-2" onclick="viewReservation(${reservation.id})">Voir</button>
                                <button class="btn btn-sm btn-warning me-2" onclick="editReservation(${reservation.id})">Modifier</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteReservation(${reservation.id})">Supprimer</button>
                            </td>
                        </tr>
                    `).join('') || '<tr><td colspan="8" class="text-center">Aucune réservation trouvée</td></tr>'
                );
            },
            error: function(xhr, status, error) {
                console.error("Erreur lors du chargement des réservations:", error);
                alert("Erreur lors du chargement des réservations");
            }
        });
    }

    // Configurer le formulaire
    function setupForm() {
        $('#reservationForm').submit(function(e) {
            e.preventDefault();
            saveReservation();
        });

        $('#cancelBtn').click(function() {
            resetForm();
        });
    }

    // Enregistrer une réservation
    function saveReservation() {
        const formData = {
            id: currentReservationId || null,
            nom: $('#nom').val(),
            prenom: $('#prenom').val(),
            email: $('#email').val(),
            telephone: $('#telephone').val(),
            destination_id: $('#destination_id').val(),
            type: $('#type').val(),
            date_depart: $('#date_depart').val(),
            date_retour: $('#date_retour').val(),
            nombre_personnes: $('#nombre_personnes').val(),
            commentaires: $('#commentaires').val()
        };

        $.ajax({
            url: 'api/reservations.php',
            type: 'POST',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            success: function(response) {
                loadReservations();
                resetForm();
                alert("Réservation enregistrée avec succès!");
            },
            error: function(xhr, status, error) {
                console.error("Erreur lors de l'enregistrement:", error);
                alert("Erreur lors de l'enregistrement de la réservation");
            }
        });
    }

    // Voir une réservation
    function viewReservation(id) {
        $.ajax({
            url: 'api/reservations.php?id=' + id,
            type: 'GET',
            dataType: 'json',
            success: function(reservation) {
                alert(`
                    ID: ${reservation.id}
                    Nom: ${reservation.nom}
                    Prénom: ${reservation.prenom}
                    Email: ${reservation.email}
                    Téléphone: ${reservation.telephone || 'Non renseigné'}
                    Type: ${getTypeName(reservation.type)}
                    Destination ID: ${reservation.destination_id}
                    Date de départ: ${new Date(reservation.date_depart).toLocaleDateString('fr-FR')}
                    Date de retour: ${reservation.date_retour ? new Date(reservation.date_retour).toLocaleDateString('fr-FR') : 'Non définie'}
                    Nombre de personnes: ${reservation.nombre_personnes}
                    Commentaires: ${reservation.commentaires || 'Aucun'}
                    Statut: ${reservation.statut ? reservation.statut.replace('_', ' ') : 'Non défini'}
                `);
            },
            error: function(xhr, status, error) {
                console.error("Erreur lors de la récupération:", error);
                alert("Erreur lors de la récupération de la réservation");
            }
        });
    }

    // Modifier une réservation
    function editReservation(id) {
        $.ajax({
            url: 'api/reservations.php?id=' + id,
            type: 'GET',
            dataType: 'json',
            success: function(reservation) {
                currentReservationId = id;

                $('#formTitle').text('Modifier Réservation');
                $('#reservationId').val(reservation.id);
                $('#nom').val(reservation.nom);
                $('#prenom').val(reservation.prenom);
                $('#email').val(reservation.email);
                $('#telephone').val(reservation.telephone || '');
                $('#destination_id').val(reservation.destination_id);
                $('#type').val(reservation.type);
                $('#date_depart').val(reservation.date_depart);
                $('#date_retour').val(reservation.date_retour || '');
                $('#nombre_personnes').val(reservation.nombre_personnes);
                $('#commentaires').val(reservation.commentaires || '');
                $('#cancelBtn').show();
            },
            error: function(xhr, status, error) {
                console.error("Erreur lors de la récupération:", error);
                alert("Erreur lors de la récupération de la réservation");
            }
        });
    }

    // Supprimer une réservation
    function deleteReservation(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?')) {
            $.ajax({
                url: 'api/reservations.php?id=' + id,
                type: 'DELETE',
                success: function(response) {
                    loadReservations();
                    alert("Réservation supprimée avec succès!");
                },
                error: function(xhr, status, error) {
                    console.error("Erreur lors de la suppression:", error);
                    alert("Erreur lors de la suppression de la réservation");
                }
            });
        }
    }

    // Réinitialiser le formulaire
    function resetForm() {
        $('#reservationForm')[0].reset();
        $('#formTitle').text('Nouvelle Réservation');
        $('#cancelBtn').hide();
        currentReservationId = null;
    }

    // Helper functions
    function getTypeName(type) {
        const types = {
            'hotel': 'Hôtel',
            'restaurant': 'Restaurant',
            'activity': 'Activité'
        };
        return types[type] || type;
    }

    function getBadgeClass(type) {
        const classes = {
            'hotel': 'badge-hotel',
            'restaurant': 'badge-restaurant',
            'activity': 'badge-activity'
        };
        return classes[type] || 'bg-secondary';
    }
</script>
</body>
</html>