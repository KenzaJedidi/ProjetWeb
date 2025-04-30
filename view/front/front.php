<?php
// Active l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once __DIR__ . '/../../controllers/OffreEmploiController.php';

$offreController = new OffreEmploiController();
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 4;
$offset = ($page - 1) * $limit;
$offres = $offreController->afficherpag($limit, $offset);

// Nombre total d'offres
$db = config::getConnexion();
$totalOffres = $db->query("SELECT COUNT(*) FROM offres_emploi WHERE status = 'Active'")->fetchColumn();
$totalPages = ceil($totalOffres / $limit);
?>

<!doctype html> 
<html class="no-js" lang="en">
<head>
    <!-- meta data -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- title of site -->
    <title>Localoo – Directory Landing Page</title>
    
    <!-- For favicon png -->
    <link rel="shortcut icon" type="image/icon" href="assets/images/logolocaloo.png"/>

    <!--font-awesome.min.css-->
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <!--linear icon css-->
    <link rel="stylesheet" href="assets/css/linearicons.css">
    <!--animate.css-->
    <link rel="stylesheet" href="assets/css/animate.css">
    <!--flaticon.css-->
    <link rel="stylesheet" href="assets/css/flaticon.css">
    <!--slick.css-->
    <link rel="stylesheet" href="assets/css/slick.css">
    <link rel="stylesheet" href="assets/css/slick-theme.css">
    <!--bootstrap.min.css-->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- bootsnav -->
    <link rel="stylesheet" href="assets/css/bootsnav.css">
    <!--style.css-->
    <link rel="stylesheet" href="assets/css/style.css">
    <!--responsive.css-->
    <link rel="stylesheet" href="assets/css/responsive.css">
    
    <style>
        /* Style supplémentaire pour les messages d'erreur */
        .error-message {
            display: block;
            margin-top: 5px;
            font-size: 0.85em;
        }
        .is-invalid {
            border-color: #dc3545 !important;
        }
        .text-danger {
            color: #dc3545 !important;
        }
        .form-control-file.is-invalid {
            border: 1px solid #dc3545;
            border-radius: 4px;
            padding: 5px;
        }
        
        /* Style pour tous les boutons d'emploi */
        .emploi-btn {
            padding: 10px 20px;
            font-size: 14px;
            width: auto;
            min-width: 180px;
            margin: 10px auto;
            display: inline-block;
            background-color: rgb(16, 196, 172);
            border: none;
            border-radius: 4px;
            color: white;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .emploi-btn:hover {
            background-color: rgb(13, 170, 150);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .emploi-btn:active {
            transform: translateY(0);
        }
        
        .emploi-offre {
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .emploi-list {
            flex-grow: 1;
        }
    </style>
</head>

<body>
    <!-- top-area Start -->
    <section class="top-area">
        <div class="header-area">
            <!-- Start Navigation -->
            <nav class="navbar navbar-default bootsnav navbar-sticky navbar-scrollspy" data-minus-value-desktop="70" data-minus-value-mobile="55" data-speed="1000">
                <div class="container">
                    <!-- Start Header Navigation -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                            <i class="fa fa-bars"></i>
                        </button>
                        <a class="navbar-brand" href="index.php">
                            <img src="assets/images/logolocaloo.png" alt="Localoo Logo" class="navbar-logo">
                        </a>
                    </div>
                    <!-- End Header Navigation -->

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse menu-ui-design" id="navbar-menu">
                        <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
                            <li class="scroll active"><a href="#home">home</a></li>
                            <li class="scroll"><a href="#explore">emploi</a></li>
                            <li class="scroll"><a href="#events">events</a></li>
                            <li class="scroll"><a href="#reservation">réservation</a></li>
                            <li class="scroll"><a href="#works">reclmation</a></li>
                            <li class="scroll"><a href="#blog">forum</a></li>
                            <li class="scroll"><a href="#contact">contact</a></li>
                            <li class="scroll"><a href="#account">my account</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navigation -->
        </div>
        <div class="clearfix"></div>
    </section>
    <!-- top-area End -->

    <!--welcome-hero start -->
    <section id="home" class="welcome-hero">
        <div class="container">
            <div class="welcome-hero-txt">
                <h2>Localoo is the best place to find and explore <br></h2>
                <p>Find Best Place, Restaurant, Hotel, Real State and many more things in just One click</p>
            </div>
            <div class="welcome-hero-serch-box">
                <div class="welcome-hero-form">
                    <div class="single-welcome-hero-form">
                        <h3>what?</h3>
                        <form action="#">
                            <input type="text" placeholder="Ex: place, restaurant, food, automobile" />
                        </form>
                        <div class="welcome-hero-form-icon">
                            <i class="flaticon-list-with-dots"></i>
                        </div>
                    </div>
                    <div class="single-welcome-hero-form">
                        <h3>location</h3>
                        <form action="#">
                            <input type="text" placeholder="Ex: london, newyork, rome" />
                        </form>
                        <div class="welcome-hero-form-icon">
                            <i class="flaticon-gps-fixed-indicator"></i>
                        </div>
                    </div>
                </div>
                <div class="welcome-hero-serch">
                    <button class="welcome-hero-btn" onclick="window.location.href='#'">
                        search <i data-feather="search"></i> 
                    </button>
                </div>
            </div>
        </div>
    </section>
    <!--welcome-hero end -->

    <!--works start -->
    <section id="works" class="works">
        <div class="container">
            <div class="section-header">
                <br>
                <br>
                <h2>how it works</h2>
                <p>Learn More about how our website works</p>
            </div>
            <div class="works-content">
                <div class="row">
                    <div class="col-md-4 col-sm-6">
                        <div class="single-how-works">
                            <div class="single-how-works-icon">
                                <i class="flaticon-lightbulb-idea"></i>
                            </div>
                            <h2><a href="#">choose <span> what to</span> do</a></h2>
                            <p>Lorem ipsum dolor sit amet, consecte adipisicing elit, sed do eiusmod tempor incididunt ut laboremagna aliqua.</p>
                            <button class="welcome-hero-btn how-work-btn" onclick="window.location.href='#'">read more</button>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="single-how-works">
                            <div class="single-how-works-icon">
                                <i class="flaticon-networking"></i>
                            </div>
                            <h2><a href="#">find <span> what you want</span></a></h2>
                            <p>Lorem ipsum dolor sit amet, consecte adipisicing elit, sed do eiusmod tempor incididunt ut laboremagna aliqua.</p>
                            <button class="welcome-hero-btn how-work-btn" onclick="window.location.href='#'">read more</button>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="single-how-works">
                            <div class="single-how-works-icon">
                                <i class="flaticon-location-on-road"></i>
                            </div>
                            <h2><a href="#">explore <span> amazing</span> place</a></h2>
                            <p>Lorem ipsum dolor sit amet, consecte adipisicing elit, sed do eiusmod tempor incididunt ut laboremagna aliqua.</p>
                            <button class="welcome-hero-btn how-work-btn" onclick="window.location.href='#'">read more</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--works end -->

    <!--emploi start -->
    <section id="explore" class="emploi">
        <div class="container">
            <div class="section-header">
                <h2>OFFRES D'EMPLOI</h2>
                <p>On recrute les talents qui osent réinventer les sorties en Tunisie - à vous de jouer ! ✨</p>
            </div>
            
            <div class="emploi-content">
                <div class="row">
                    <?php if (empty($offres)): ?>
                        <div class="col-12 text-center">
                            <p>Aucune offre d'emploi disponible pour le moment.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($offres as $offre): ?>
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="emploi-offre">
                                    <h3><?= htmlspecialchars($offre->getTitre()) ?></h3>
                                    <p><?= htmlspecialchars($offre->getDescription()) ?></p>
                                    <ul class="emploi-list">
                                        <?php 
                                        $competences = $offre->getCompetences() ? explode(',', $offre->getCompetences()) : [];
                                        foreach ($competences as $competence): ?>
                                            <li><i class="fa fa-check"></i> <?= trim(htmlspecialchars($competence)) ?></li>
                                        <?php endforeach; ?>
                                        <li><i class="fa fa-check"></i> <?= htmlspecialchars($offre->getTypeContrat()) ?></li>
                                        <li><i class="fa fa-check"></i> Localisation: <?= htmlspecialchars($offre->getLocalisation() ?: 'Non spécifié') ?></li>
                                    </ul>
                                    <div class="text-center">
                                        <button class="emploi-btn postuler-btn" 
                                                data-poste="<?= htmlspecialchars($offre->getTitre()) ?>" 
                                                data-offre-id="<?= $offre->getId() ?>">
                                            Postuler maintenant
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <div class="text-center mt-5">
                    <nav aria-label="Pagination">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                </div>

                <div class="text-center mt-4">
                    <a href="candidature.html" class="emploi-btn">
                        <i class="fa fa-eye"></i> Voir mes candidatures
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Formulaire -->
    <div class="modal fade" id="candidatureModal" tabindex="-1" role="dialog" aria-labelledby="candidatureModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="candidatureModalLabel">Postuler à Localoo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formCandidature" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nomComplet">Nom Complet *</label>
                            <input type="text" class="form-control" id="nomComplet" name="nom_complet">
                            <small class="text-danger error-message" id="nomCompletError"></small>
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="text" class="form-control" id="email" name="email">
                            <small class="text-danger error-message" id="emailError"></small>
                        </div>
                        <div class="form-group">
                            <label for="telephone">Téléphone</label>
                            <input type="text" class="form-control" id="telephone" name="telephone">
                            <small class="text-danger error-message" id="telephoneError"></small>
                        </div>
                        <div class="form-group">
                            <label for="cv">CV (PDF uniquement, max 2MB) *</label>
                            <input type="file" class="form-control-file" id="cv" name="cv" accept=".pdf">
                            <small class="text-danger error-message" id="cvError"></small>
                        </div>
                        <div class="form-group">
                            <label for="message">Message de motivation</label>
                            <textarea class="form-control" id="message" name="message" rows="5"></textarea>
                            <small class="text-danger error-message" id="messageError"></small>
                        </div>
                        <input type="hidden" id="offreId" name="offre_id">
                        <input type="hidden" id="poste" name="poste">
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Envoyer ma candidature</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts ESSENTIELS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script>
    $(document).ready(function() {
        // Gestion du clic sur "Postuler"
        $('.postuler-btn').click(function() {
            var poste = $(this).data('poste');
            var offreId = $(this).data('offre-id');
            $('#poste').val(poste);
            $('#offreId').val(offreId);
            $('#candidatureModal').modal('show');
            
            // Réinitialiser les messages d'erreur et les styles
            $('.error-message').text('');
            $('.form-control').removeClass('is-invalid');
            $('.form-control-file').removeClass('is-invalid');
        });
        
        // Fonctions de validation
        function validateNomComplet(nom) {
            if (!nom || nom.trim() === '') {
                return "Le nom complet est requis";
            }
            if (nom.length < 3 || nom.length > 50) {
                return "Le nom doit contenir entre 3 et 50 caractères";
            }
            if (!/^[a-zA-ZÀ-ÿ\s\-']+$/.test(nom)) {
                return "Le nom ne doit contenir que des lettres et espaces";
            }
            return "";
        }

        function validateEmail(email) {
            if (!email || email.trim() === '') {
                return "L'email est requis";
            }
            var re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!re.test(email)) {
                return "Veuillez entrer une adresse email valide";
            }
            return "";
        }

        function validateTelephone(telephone) {
            if (telephone && telephone.trim() !== '') {
                if (!/^[0-9]{8,15}$/.test(telephone)) {
                    return "Le téléphone doit contenir 8 à 15 chiffres";
                }
            }
            return "";
        }

        function validateCV(cvFile) {
            if (!cvFile || !cvFile.name) {
                return "Le CV est requis";
            }
            if (cvFile.size > 2097152) { // 2MB
                return "Le fichier ne doit pas dépasser 2MB";
            }
            var extension = cvFile.name.split('.').pop().toLowerCase();
            if (extension !== 'pdf') {
                return "Seuls les fichiers PDF sont acceptés";
            }
            return "";
        }

        function validateMessage(message) {
            if (message && message.length > 500) {
                return "Le message ne doit pas dépasser 500 caractères";
            }
            return "";
        }

        // Validation en temps réel
        $('#nomComplet').on('blur', function() {
            var error = validateNomComplet($(this).val());
            if (error) {
                $(this).addClass('is-invalid');
                $('#nomCompletError').text(error);
            } else {
                $(this).removeClass('is-invalid');
                $('#nomCompletError').text('');
            }
        });

        $('#email').on('blur', function() {
            var error = validateEmail($(this).val());
            if (error) {
                $(this).addClass('is-invalid');
                $('#emailError').text(error);
            } else {
                $(this).removeClass('is-invalid');
                $('#emailError').text('');
            }
        });

        $('#telephone').on('blur', function() {
            var error = validateTelephone($(this).val());
            if (error) {
                $(this).addClass('is-invalid');
                $('#telephoneError').text(error);
            } else {
                $(this).removeClass('is-invalid');
                $('#telephoneError').text('');
            }
        });

        $('#cv').on('change', function() {
            var error = validateCV(this.files[0]);
            if (error) {
                $(this).addClass('is-invalid');
                $('#cvError').text(error);
            } else {
                $(this).removeClass('is-invalid');
                $('#cvError').text('');
            }
        });

        $('#message').on('blur', function() {
            var error = validateMessage($(this).val());
            if (error) {
                $(this).addClass('is-invalid');
                $('#messageError').text(error);
            } else {
                $(this).removeClass('is-invalid');
                $('#messageError').text('');
            }
        });

        // Soumission du formulaire
        $('#formCandidature').submit(function(e) {
            e.preventDefault();
            
            // Réinitialiser les messages d'erreur
            $('.error-message').text('');
            $('.form-control').removeClass('is-invalid');
            $('.form-control-file').removeClass('is-invalid');
            
            // Récupérer les valeurs
            var nomComplet = $('#nomComplet').val();
            var email = $('#email').val();
            var telephone = $('#telephone').val();
            var cvFile = $('#cv')[0].files[0];
            var message = $('#message').val();
            var isValid = true;

            // Valider chaque champ
            var nomError = validateNomComplet(nomComplet);
            if (nomError) {
                $('#nomComplet').addClass('is-invalid');
                $('#nomCompletError').text(nomError);
                isValid = false;
            }

            var emailError = validateEmail(email);
            if (emailError) {
                $('#email').addClass('is-invalid');
                $('#emailError').text(emailError);
                isValid = false;
            }

            var telephoneError = validateTelephone(telephone);
            if (telephoneError) {
                $('#telephone').addClass('is-invalid');
                $('#telephoneError').text(telephoneError);
                isValid = false;
            }

            var cvError = validateCV(cvFile);
            if (cvError) {
                $('#cv').addClass('is-invalid');
                $('#cvError').text(cvError);
                isValid = false;
            }

            var messageError = validateMessage(message);
            if (messageError) {
                $('#message').addClass('is-invalid');
                $('#messageError').text(messageError);
                isValid = false;
            }

            if (!isValid) {
                // Faire défiler jusqu'au premier champ invalide
                $('html, body').animate({
                    scrollTop: $('.is-invalid').first().offset().top - 100
                }, 500);
                return false;
            }
            
            // Création FormData
            const formData = new FormData();
            formData.append('action', 'add');
            formData.append('nom_complet', nomComplet);
            formData.append('email', email);
            formData.append('telephone', telephone);
            formData.append('poste', $('#poste').val());
            formData.append('message', message);
            formData.append('offre_id', $('#offreId').val());
            formData.append('cv', cvFile);
            
            // Afficher un indicateur de chargement
            var submitBtn = $('#formCandidature').find('button[type="submit"]');
            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Envoi en cours...');
            
            // Envoi au serveur
            $.ajax({
                url: '../../controllers/CandidatureController.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    submitBtn.prop('disabled', false).text('Envoyer ma candidature');
                    
                    if (response.success) {
                        alert('Candidature envoyée avec succès !');
                        $('#candidatureModal').modal('hide');
                        $('#formCandidature')[0].reset();
                        
                        // Stockage local
                        const candidatures = JSON.parse(localStorage.getItem('candidaturesLocaloo')) || [];
                        candidatures.push({
                            poste: $('#poste').val(),
                            nom: nomComplet,
                            email: email,
                            telephone: telephone,
                            date: new Date().toLocaleDateString('fr-FR'),
                            status: 'En attente'
                        });
                        localStorage.setItem('candidaturesLocaloo', JSON.stringify(candidatures));
                    } else {
                        alert('Erreur : ' + (response.message || 'Une erreur est survenue'));
                    }
                },
                error: function(xhr, status, error) {
                    submitBtn.prop('disabled', false).text('Envoyer ma candidature');
                    alert('Une erreur est survenue : ' + error);
                }
            });
        });
    });
    </script>

    <!--events start -->
    <section id="events" class="events">
        <div class="section-header">
            <h2>EVENTS</h2>
            <h2>Choisissez votre destination de rêve!</h2>
            <p>Réservez dès maintenant en temps réel</p>
        </div>
        <!-- Carte Interactive Modifiée -->
        <div class="interactive-map-container">
            <div class="tunisia-map-wrapper">
                <img src="assets/images/carte.png" 
                     alt="Carte de la Tunisie" 
                     class="img-responsive centered-map"
                     usemap="#tunisiaMap">
                
                <!-- Nouveaux marqueurs alignés avec liens -->
                <a href="cities/tunis.html" class="map-marker" 
                   style="top:32%; left:11.7%" 
                   data-city="tunis"
                   aria-label="Événements à Tunis">
                    <div class="marker-pulse"></div>
                    <span class="marker-label">Tunis</span>
                </a>
                <a href="cities/hammamet.html" class="map-marker" 
                   style="top:40%; left:40.2%" 
                   data-city="hammamet"
                   aria-label="Événements à Hammamet">
                    <div class="marker-pulse"></div>
                    <span class="marker-label">Hammamet</span>
                </a>
                <a href="cities/djem.html" class="map-marker" 
                   style="top:60%; left:16.8%" 
                   data-city="djem"
                   aria-label="Événements à Djem">
                    <div class="marker-pulse"></div>
                    <span class="marker-label">Djem</span>
                </a>
                <a href="cities/sousse.html" class="map-marker" 
                   style="top:76%; left:41%" 
                   data-city="sousse"
                   aria-label="Événements à Sousse">
                    <div class="marker-pulse"></div>
                    <span class="marker-label">Sousse</span>
                </a>
                <a href="cities/djerba.html" class="map-marker" 
                   style="top:85%; left:66.6%" 
                   data-city="djerba"
                   aria-label="Événements à Djerba">
                    <div class="marker-pulse"></div>
                    <span class="marker-label">Djerba</span>
                </a>
            </div>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.map-marker').forEach(marker => {
                marker.addEventListener('mouseenter', function() {
                    this.style.transform = 'translate(-50%, -50%) scale(1.1)';
                    this.style.zIndex = '3';
                });
                
                marker.addEventListener('mouseleave', function() {
                    this.style.transform = 'translate(-50%, -50%) scale(1)';
                    this.style.zIndex = '2';
                });
            });
        });

        </script>
    </section>
    <!--events end -->

    <!--reservation start -->
    <section id="reservation" class="reservation-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="section-header">Réservations</h2>
                    <p>Gérez vos réservations en toute simplicité</p>
                </div>
            </div>
            
            <!-- Filtres -->
            <div class="filter-box">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <label>Date</label>
                            <input type="text" class="form-control date-filter" placeholder="Toutes dates">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <label>Service</label>
                            <select class="form-control service-filter">
                                <option value="">Tous services</option>
                                <option value="restaurant">Restaurant</option>
                                <option value="hotel">Hôtel</option>
                                <option value="event">Événement</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="form-group">
                            <label>Statut</label>
                            <select class="form-control status-filter">
                                <option value="">Tous statuts</option>
                                <option value="confirmed">Confirmé</option>
                                <option value="pending">En attente</option>
                                <option value="cancelled">Annulé</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <button class="btn btn-filter">Filtrer</button>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Formulaire -->
                <div class="col-lg-5">
                    <div class="reservation-form-card">
                        <h3><i class="lnr lnr-plus-circle"></i> <span id="form-title">Nouvelle réservation</span></h3>
                        <form id="reservation-form">
                            <input type="hidden" id="reservation-id">
                            <div class="form-group">
                                <label>Type de service</label>
                                <select class="form-control" id="service-type" required>
                                    <option value="">Choisir un service</option>
                                    <option value="restaurant">Restaurant</option>
                                    <option value="hotel">Hôtel</option>
                                    <option value="event">Événement</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Date</label>
                                <input type="text" class="form-control date-picker" id="reservation-date" required>
                            </div>
                            <div class="form-group">
                                <label>Heure</label>
                                <input type="time" class="form-control" id="reservation-time" required>
                            </div>
                            <div class="form-group">
                                <label>Nombre de personnes</label>
                                <input type="number" class="form-control" id="guest-number" min="1" required>
                            </div>
                            <div class="form-group">
                                <label>Demandes spéciales</label>
                                <textarea class="form-control" id="special-requests" rows="3"></textarea>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-submit">Enregistrer</button>
                                <button type="button" class="btn btn-cancel">Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Liste -->
                <div class="col-lg-7">
                    <div class="reservation-list-card">
                        <h3><i class="lnr lnr-list"></i> Mes Réservations <span class="badge">2</span></h3>
                        <div class="reservation-list">
                            <div class="reservation-item">
                                <div class="reservation-info">
                                    <h4>Restaurant</h4>
                                    <p><i class="lnr lnr-calendar-full"></i> 15/07/2023 à 19:30</p>
                                    <p><i class="lnr lnr-users"></i> 4 personnes</p>
                                    <p class="requests">Table près de la fenêtre</p>
                                </div>
                                <div class="reservation-status confirmed">
                                    Confirmé
                                </div>
                                <div class="reservation-actions">
                                    <button class="btn-edit"><i class="lnr lnr-pencil"></i></button>
                                    <button class="btn-delete"><i class="lnr lnr-trash"></i></button>
                                </div>
                            </div>
                            <div class="reservation-item">
                                <div class="reservation-info">
                                    <h4>Hôtel</h4>
                                    <p><i class="lnr lnr-calendar-full"></i> 20/07/2023 à 15:00</p>
                                    <p><i class="lnr lnr-users"></i> 2 personnes</p>
                                    <p class="requests">Chambre avec vue mer</p>
                                </div>
                                <div class="reservation-status pending">
                                    En attente
                                </div>
                                <div class="reservation-actions">
                                    <button class="btn-edit"><i class="lnr lnr-pencil"></i></button>
                                    <button class="btn-delete"><i class="lnr lnr-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- statistics start -->
    <section id="statistics" class="statistics">
        <div class="container">
            <div class="statistics-counter">
                <div class="col-md-3 col-sm-6">
                    <div class="single-ststistics-box">
                        <div class="statistics-content">
                            <div class="counter">90 </div> <span>K+</span>
                        </div>
                        <h3>listings</h3>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="single-ststistics-box">
                        <div class="statistics-content">
                            <div class="counter">40</div> <span>k+</span>
                        </div>
                        <h3>listing categories</h3>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="single-ststistics-box">
                        <div class="statistics-content">
                            <div class="counter">65</div> <span>k+</span>
                        </div>
                        <h3>visitors</h3>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="single-ststistics-box">
                        <div class="statistics-content">
                            <div class="counter">50</div> <span>k+</span>
                        </div>
                        <h3>happy clients</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- statistics end -->

    <!--blog start -->
    <section id="blog" class="blog">
        <div class="container">
            <div class="section-header">
                <h2>news and articles</h2>
                <p>Always up to date with our latest News and Articles</p>
            </div>
            <div class="blog-content">
                <div class="row">
                    <div class="col-md-4 col-sm-6">
                        <div class="single-blog-item">
                            <div class="single-blog-item-img">
                                <img src="assets/images/blog/b1.jpg" alt="blog image">
                            </div>
                            <div class="single-blog-item-txt">
                                <h2><a href="#">How to find your Desired Place more quickly</a></h2>
                                <h4>posted <span>by</span> <a href="#">admin</a> march 2018</h4>
                                <p>Lorem ipsum dolor sit amet, consectetur de adipisicing elit, sed do eiusmod tempore incididunt ut labore et dolore magna.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--blog end -->

    <!--subscription start -->
    <section id="contact" class="subscription">
        <div class="container">
            <div class="subscribe-title text-center">
                <h2>do you want to add your business listing with us?</h2>
                <p>Listrace offer you to list your business with us and we very much able to promote your Business.</p>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="subscription-input-group">
                        <form action="#">
                            <input type="email" class="subscription-input-form" placeholder="Enter your email here">
                            <button class="appsLand-btn subscribe-btn" onclick="window.location.href='#'">create account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--subscription end -->

    <!--footer start-->
    <footer id="footer" class="footer">
        <div class="container">
            <div class="footer-menu">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="navbar-header">
                            <a class="navbar-brand" href="index.php">local<span>oo</span></a>
                        </div>
                    </div>
                    <div class="col-sm-9">
                        <ul class="footer-menu-item">
                            <li class="scroll"><a href="#works">how it works</a></li>
                            <li class="scroll"><a href="#explore">explore</a></li>
                            <li class="scroll"><a href="#reservation">réservation</a></li>
                            <li class="scroll"><a href="#blog">blog</a></li>
                            <li class="scroll"><a href="#contact">contact</a></li>
                            <li class="scroll"><a href="#account">my account</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="hm-footer-copyright">
                <div class="row">
                    <div class="col-sm-5">
                        <p>©copyright. designed and developed by <a href="https://www.themesine.com/">themesine</a></p>
                    </div>
                    <div class="col-sm-7">
                        <div class="footer-social">
                            <span><i class="fa fa-phone"> +1 (222) 777 8888</i></span>
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-linkedin"></i></a>
                            <a href="#"><i class="fa fa-google-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="scroll-Top">
            <div class="return-to-top">
                <i class="fa fa-angle-up" id="scroll-top" data-toggle="tooltip" data-placement="top" title="" data-original-title="Back to Top" aria-hidden="true"></i>
            </div>
        </div>
    </footer>
    <!--footer end-->
    
    <!-- JS Files -->
    <script src="assets/js/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/bootsnav.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/js/jquery.counterup.min.js"></script>
    <script src="assets/js/waypoints.min.js"></script>
    <script src="assets/js/slick.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>