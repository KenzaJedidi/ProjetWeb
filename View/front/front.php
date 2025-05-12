<?php
// Active l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../../Controller/ReclamationC.php';
include '../../Controller/BonPlanC.php';
include '../../Controller/ReservationC.php';
include '../../Controller/UserC.php';
include_once __DIR__ . '/../../Controller/OffreEmploiController.php';
$ReclamationC = new ReclamationC();
$BonPlanC = new BonPlanC();
$ReservationC = new ReservationC();
    
$listReclamation = $ReclamationC->AfficherReclamation();
$listBonPlans = $BonPlanC->AfficherBonPlan();
$listReservation = $ReservationC->AfficherReservation();

$offreController = new OffreEmploiController();
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 4;
$offset = ($page - 1) * $limit;
$offres = $offreController->afficherpag($limit, $offset);
$localisations = $offreController->getLocalisationsDistinctes();
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
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Localoo - Trouvez les meilleurs endroits, restaurants, hôtels et plus encore">
        <meta name="author" content="Localoo">
        <meta name="keywords" content="Localoo, directory, restaurant, hotel, events, emploi">

        <!-- title of site -->
        <title>Localoo – Directory Landing Page</title>
        <!-- For favicon png -->
        <link rel="shortcut icon" type="image/icon" href="assets/images/logolocaloo.png"/>
        <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
        <link rel="manifest" href="assets/images/favicon/site.webmanifest">
        <link rel="mask-icon" href="assets/images/favicon/safari-pinned-tab.svg" color="#0abab5">
        <meta name="msapplication-TileColor" content="#0abab5">
        <meta name="theme-color" content="#ffffff">



         <!-- Préchargement des polices -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/speech-input.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/linearicons.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/css/flaticon.css">
    <link rel="stylesheet" href="assets/css/slick.css">
    <link rel="stylesheet" href="assets/css/slick-theme.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/bootsnav.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    
    <!-- Modernizr -->
    <script src="assets/js/modernizr-2.8.3.min.js"></script>
        
    </head>
    
    <body>
    <style>
              .error-message {
    color: red;
    font-size: 0.8em;
    margin-top: 0.2em;
  }
</style>
       

        </header>
        <!--header-top end -->

                    
        <!-- top-area Start -->
        <section class="top-area">
            <div class="header-area">
                <!-- Start Navigation -->
                <nav class="navbar navbar-default bootsnav navbar-sticky navbar-scrollspy"  data-minus-value-desktop="70" data-minus-value-mobile="55" data-speed="1000">
                    <div class="container">
                        <!-- Start Header Navigation -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                                <i class="fa fa-bars"></i>
                            </button>
                            <!-- Remplacer list race par localoo -->
                             <a class="navbar-brand" href="front.php">
                               
    <!-- Top Area End -->
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
                            <li class="scroll"><a href="#reservation">Bon Plan</a></li>
                            <li class="scroll"><a href="#reclamations">Reclamations</a></li>
                            <li class="scroll"><a href="#blog">forum</a></li>
                            <li class="scroll"><a href="#contact">contact</a></li>
                            <li class="scroll"><a href="#account">my account</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
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
                            search  <i data-feather="search"></i> 
                        </button>
                    </div>
                </div>
            </div>
        </section>
        
        <!--welcome-hero end -->
 
    
      





        <!--explore start -->
        <!-- Emploi Start -->
  <!--emploi start -->
<section id="explore" class="emploi">
    <div class="container">
        <div class="section-header">
            <h2>OFFRES D'EMPLOI</h2>
             <p>On recrute les talents qui osent réinventer les sorties en Tunisie - à vous de jouer ! ✨</p>
            <!-- Section de filtres déplacée ici -->
            <div class="filtre-section mb-4">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="localisation-filter">Localisation</label>
                <select class="form-control" id="localisation-filter">
                    <option value="">Toutes les localisations</option>
                    <?php foreach ($localisations as $loc): ?>
                        <option value="<?= htmlspecialchars($loc) ?>"><?= htmlspecialchars($loc) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
                <label for="salaire-filter">Tranche de salaire</label>
                <select class="form-control" id="salaire-filter">
                    <option value="">Tous les salaires</option>
                    <option value="0-999">Moins de 1000 TND</option>
                    <option value="1000-1499">1000 à 1499 TND</option>
                    <option value="1500-1999">1500 à 1999 TND</option>
                    <option value="2000-2499">2000 à 2499 TND</option>
                    <option value="2500-4999">2500 à 4999 TND</option>
                    <option value="5000-999999">Plus de 5000 TND</option>
                </select>
            </div>
        </div>
    </div>
    <div class="text-center mt-2">
        <button id="appliquer-filtre" class="emploi-btn">Appliquer les filtres</button>
        <button id="reinitialiser-filtre" class="emploi-btn btn-secondary">Réinitialiser</button>
    </div>
</div>
            <!-- Fin de la section de filtres -->
            
           
        </div>
        
        <div class="emploi-content">
            <div class="row" id="offres-container">
                <?php if (empty($offres)): ?>
                    <div class="col-12 text-center">
                        <p>Aucune offre d'emploi disponible pour le moment.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($offres as $offre): ?>
                        <div class="col-md-3 col-sm-6 mb-4 offre-item" 
                             data-localisation="<?= htmlspecialchars($offre->getLocalisation() ?: '') ?>"
                             data-salaire="<?= $offre->getSalaire() ?: '0' ?>">
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
                        <div class="form-group speech-input-group">
    <label for="message">Lettre de motivation</label>
    <textarea class="form-control" id="message" name="message" rows="5"></textarea>
    <button type="button" id="start-speech" class="btn btn-outline-primary mt-2">
        <i class="fa fa-microphone"></i> Enregistrer un message vocal
    </button>
    <span id="speech-status" class="text-muted ml-2"></span>
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
    // Fonction de filtrage
    function filtrerOffres() {
    const localisation = $('#localisation-filter').val().toLowerCase();
    const salaireRange = $('#salaire-filter').val();
    
    let salaireMin = 0;
    let salaireMax = Infinity;
    
    if (salaireRange && salaireRange !== '') {
        const rangeParts = salaireRange.split('-');
        salaireMin = parseInt(rangeParts[0]);
        salaireMax = parseInt(rangeParts[1]);
    }
    
    $('.offre-item').each(function() {
        const offreLocalisation = $(this).data('localisation') ? $(this).data('localisation').toLowerCase() : '';
        const offreSalaire = parseInt($(this).data('salaire')) || 0;
        
        const matchLocalisation = !localisation || offreLocalisation.includes(localisation);
        const matchSalaire = offreSalaire >= salaireMin && offreSalaire <= salaireMax;
        
        if (matchLocalisation && matchSalaire) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
    
    if ($('.offre-item:visible').length === 0) {
        $('#offres-container').append(
            '<div class="col-12 text-center no-results">' +
            '   <p>Aucune offre ne correspond à vos critères de recherche.</p>' +
            '</div>'
        );
    } else {
        $('.no-results').remove();
    }
}
    
    // Appliquer le filtre
    $('#appliquer-filtre').click(filtrerOffres);
    
    // Réinitialiser le filtre
    $('#reinitialiser-filtre').click(function() {
        $('#localisation-filter').val('');
        $('#salaire-filter').val('');
        $('.offre-item').show();
        $('.no-results').remove();
    });
    
    // Optionnel: Filtrer automatiquement quand les sélecteurs changent
    // $('#localisation-filter, #salaire-filter').change(filtrerOffres);
});




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
                url: '../../Controllers/CandidatureController.php',
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

    <!-- Events Start -->
    <section id="events" class="events">
        <div class="section-header">
            <h2>EVENTS</h2>
            <h2>Choisissez votre destination de rêve!</h2>
            <p>Réservez dès maintenant en temps réel</p>
        </div>
        <div class="interactive-map-container">
            <div class="tunisia-map-wrapper">
                <img src="assets/images/carte.png" alt="Carte de la Tunisie" class="img-responsive centered-map">
                <a href="../front/event.php?city=Tunis" class="map-marker" style="top:32%; left:11.7%">
                    <div class="marker-pulse"></div>
                    <span class="marker-label">Tunis</span>
                </a>
                <a href="../front/event.php?city=Hammamet" class="map-marker" style="top:40%; left:40.2%">
                    <div class="marker-pulse"></div>
                    <span class="marker-label">Hammamet</span>
                </a>
                <a href="../front/event.php?city=Sousse" class="map-marker" style="top:76%; left:41%">
                    <div class="marker-pulse"></div>
                    <span class="marker-label">Sousse</span>
                </a>
                <a href="../front/event.php?city=Djem" class="map-marker" style="top:60%; left:16.8%">
                    <div class="marker-pulse"></div>
                    <span class="marker-label">Djem</span>
                </a>
                <a href="../front/event.php?city=Djerba" class="map-marker" style="top:85%; left:66.6%">
                    <div class="marker-pulse"></div>
                    <span class="marker-label">Djerba</span>
                </a>
            </div>
            <div id="event-results" class="container mt-5"></div>
        </div>
    </section>
    <!-- Events End -->







       <!-- Reservation Section Start -->
    <section id="reservation" class="reservation-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="section-header">Réservations</h2>
                    <p>Gérez vos réservations en toute simplicité</p>
                </div>
            </div>
            <div class="filter-box">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Statut</label>
                            <select class="form-control status-filter" id="statusFilter">
                                <option value="">Tous statuts</option>
                                <option value="confirmed">Confirmé</option>
                                <option value="pending">En attente</option>
                                <option value="cancelled">Annulé</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-7">
                    <div class="reservation-list-card">
                        <h3><i class="lnr lnr-list"></i> Mes Réservations 
                            <?php if (!empty($listReservation) && is_array($listReservation)): ?>
                                <span class="badge"><?php echo count($listReservation); ?></span>
                            <?php else: ?>
                                <span class="badge">0</span>
                            <?php endif; ?>
                        </h3>
                        <div class="reservation-list">
                            <?php if (!empty($listReservation) && is_array($listReservation) && count($listReservation) > 0): ?>
                                <?php foreach($listReservation as $reservation): 
                                    $bonPlanInfo = null;
                                    if (!empty($reservation['idBonPlan'])) {
                                        $bonPlanInfo = $BonPlanC->RecupererBonPlan($reservation['idBonPlan']);
                                    }
                                    $destination = $bonPlanInfo ? $bonPlanInfo['destination'] : 'Destination inconnue';
                                    $statusClass = '';
                                    $statutDisplay = $reservation['statut'];
                                    if ($statutDisplay == 'Confirmée' || $statutDisplay == 'confirmed') {
                                        $statusClass = 'confirmed';
                                        $statutDisplay = 'Confirmé';
                                    } else if ($statutDisplay == 'En attente' || $statutDisplay == 'pending') {
                                        $statusClass = 'pending';
                                        $statutDisplay = 'En attente';
                                    } else if ($statutDisplay == 'Annulée' || $statutDisplay == 'cancelled') {
                                        $statusClass = 'cancelled';
                                        $statutDisplay = 'Annulé';
                                    }
                                ?>
                                <div class="reservation-item">
                                    <div class="reservation-info">
                                        <h4><?php echo $destination; ?></h4>
                                        <p><i class="lnr lnr-calendar-full"></i> Du: <?php echo $reservation['dateDepart']; ?></p>
                                        <p><i class="lnr lnr-calendar-full"></i> Au: <?php echo $reservation['dateRetour']; ?></p>
                                        <p><i class="lnr lnr-users"></i> <?php echo $reservation['nbPersonne']; ?> personne(s)</p>
                                        <?php if (!empty($reservation['commentaire'])): ?>
                                        <p class="requests"><?php echo $reservation['commentaire']; ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="reservation-status <?php echo $statusClass; ?>">
                                        <?php echo $statutDisplay; ?>
                                    </div>
                                    <div class="reservation-actions">
                                        <button class="btn-delete" onclick="if(confirm('Voulez-vous supprimer cette réservation?')) window.location.href='delete_reservation.php?id=<?php echo $reservation['idReservation']; ?>'">
                                            <i class="lnr lnr-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <p>Vous n'avez encore aucune réservation.</p>
                                    <p>Parcourez nos bons plans et réservez votre prochain voyage !</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Reservation Section End -->

    <!-- BonPlans Section Start -->
    <section id="bonplans" class="bonplans-section">
        <div class="container">
            <div class="section-header text-center">
                <h2>Nos Bons Plans</h2>
                <p>Découvrez nos meilleures destinations et réservez dès maintenant</p>
            </div>
            <?php if (!empty($listBonPlans) && is_array($listBonPlans)): ?>
                <div class="alert alert-info">
                    Nombre de BonPlans trouvés: <?php echo count($listBonPlans); ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    Aucun BonPlan trouvé. Vérifiez votre base de données.
                </div>
            <?php endif; ?>
            <div class="row">
                <?php foreach($listBonPlans as $bonplan): ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="bonplan-card">
                        <div class="destination-header">
                            <span class="destination-badge">Top Destination</span>
                            <h4 class="destination-title"><?php echo $bonplan['destination']; ?></h4>
                        </div>
                        <div class="bonplan-content">
                            <div class="bonplan-info">
                                <div class="info-item">
                                    <i class="fa fa-cutlery" aria-hidden="true"></i>
                                    <div class="info-text">
                                        <span class="info-label">Restaurant</span>
                                        <span class="info-value"><?php echo $bonplan['restaurant']; ?></span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <i class="fa fa-building" aria-hidden="true"></i>
                                    <div class="info-text">
                                        <span class="info-label">Hôtel</span>
                                        <span class="info-value"><?php echo $bonplan['hotel']; ?></span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                    <div class="info-text">
                                        <span class="info-label">Ajouté le</span>
                                        <span class="info-value"><?php echo $bonplan['dateCreation']; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="bonplan-actions">
                                <a href="#" class="btn-reserve" data-toggle="modal" data-target="#reservationModal" data-bonplan-id="<?php echo $bonplan['idBonplan']; ?>" data-destination="<?php echo $bonplan['destination']; ?>">
                                    <i class="fa fa-calendar-check-o"></i> Réserver maintenant
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- BonPlans Section End -->

<!-- Reclamations Section Start -->
<section id="reclamations" class="reclamations">
    <div class="container">
        <div class="section-header">
            <h2>Gestion des Réclamations</h2>
            <p>Créer, voir, modifier ou supprimer vos réclamations</p>
        </div>

        <!-- Button to trigger Add Modal -->
        <div class="text-center mb-4">
            <button class="welcome-hero-btn" data-toggle="modal" data-target="#addReclamationModal" onclick="ouvrirModalAjout()">
                <i class="fa fa-plus"></i> Ajouter une Réclamation
                                    </button>
                                </div>

        <!-- Reclamations Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="reclamationsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Message</th>
                        <th>Statut</th>
                        <th>Date Reclamation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <?php foreach($listReclamation as $reclamation){
                ?>
                <tbody>
                    <td><?php echo $reclamation['idReclamation']; ?></td>
                    <td><?php echo $reclamation['Type']; ?></td>
                    <td><?php echo $reclamation['Message']; ?></td>
                    <td class="align-middle text-center text-sm">
                                            <?php
                        $statut = $reclamation['statut'];
                        $badgeClass = '';

                        if ($statut === 'En Cours') {
                            $badgeClass = 'text text-warning';
                        } elseif ($statut === 'Répondu') {
                            $badgeClass = 'text text-info';
                        }
                      ?>
                      <span class="<?php echo $badgeClass; ?>"><?php echo $statut; ?></span>

                      </td>                    <td><?php echo $reclamation['dateReclamation']; ?></td>
                    <td>
                    <button class="btn btn-warning btn-sm" onclick='ouvrirModalEdit(
                                                        <?= json_encode($reclamation["idReclamation"]) ?>,
                                                        <?= json_encode($reclamation["Type"]) ?>,
                                                        <?= json_encode($reclamation["Message"]) ?>,
                                                    )'
                                                    >Modifier</button>
                        <a href="SupprimerReclamation.php?idReclamation=<?php echo $reclamation['idReclamation']; ?>" class="btn btn-danger">Supprimer</a>
                        </td>

                </tbody>
                <?php } ?>
            </table>
                            </div>
                            </div>
</section>

<!-- Add Reclamation Modal -->
<div class="modal fade" id="addReclamationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalLabel">Ajouter une Réclamation</h4>
                    </div>
            <div class="modal-body">
            <form method="POST" action="store_reclamation.php" id="addReclamationForm" onsubmit="return validateForm();">
            <input type="hidden" name="id" id="idReclamation">

                <div class="form-group">
                    <label for="nom">Type :</label>
                    <select name="Type" id="Type" class="form-select">
                    <option value="Problème de réservation">Problème de réservation</option>
                    <option value="Lieu fermé">Lieu fermé</option>
                    <option value="Mauvais service">Mauvais service</option>
                    <option value="Problème technique">Problème technique</option>
                    <option value="Contenu inapproprié">Contenu inapproprié</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="prenom">Message :</label>
                    <textarea class="form-control" id="Message" name="Message" rows="4" placeholder="Ecrire votre Message"></textarea>
                    <div id="messageError" class="error-message"></div>
            </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteReclamationModal" tabindex="-1" role="dialog" aria-labelledby="deleteReclamationModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="deleteReclamationModalLabel">Confirmer la suppression</h4>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette réclamation ? Cette action est irréversible.</p>
                <input type="hidden" id="delete_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Supprimer</button>
            </div>
        </div>
    </div>
</div>


<style>
        /* Styles pour la section emploi */
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
        .filtre-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .no-results {
            padding: 30px;
            font-size: 1.2em;
            color: #6c757d;
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

        /* Styles pour la section réservation */
        .reservation-section {
            padding: 80px 0;
            background: #f8fafb;
        }
        .section-header {
            color: #505866;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .filter-box {
            background: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .reservation-form-card,
        .reservation-list-card {
            background: white;
            border-radius: 5px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .reservation-form-card h3,
        .reservation-list-card h3 {
            color: #505866;
            margin-bottom: 20px;
            font-size: 18px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #505866;
        }
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #e1e5eb;
            border-radius: 3px;
        }
        .reservation-item {
            display: flex;
            padding: 15px;
            border-bottom: 1px solid #f1f1f1;
            align-items: center;
        }
        .bonplans-section {
            padding: 80px 0;
            background: #f9f9f9;
        }
        .bonplan-card {
            position: relative;
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            height: 100%;
            background: #fff;
            margin-bottom: 30px;
            border: 1px solid #eaeaea;
        }
        .bonplan-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .destination-header {
            background: linear-gradient(135deg, #81D8D0, #00a99e);
            color: white;
            padding: 20px 25px;
            position: relative;
        }
        .destination-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.2);
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            backdrop-filter: blur(5px);
        }
        .destination-title {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            margin-top: 5px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .bonplan-content {
            padding: 25px;
        }
        .bonplan-info {
            margin-bottom: 25px;
        }
        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .info-item i {
            background: #f0f8f7;
            color: #00a99e;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 18px;
        }
        .info-text {
            flex: 1;
        }
        .info-label {
            display: block;
            font-size: 13px;
            color: #a0a0a0;
            margin-bottom: 2px;
        }
        .info-value {
            display: block;
            font-weight: 600;
            color: #505866;
            font-size: 16px;
        }
        .bonplan-actions {
            text-align: center;
            margin-top: 10px;
        }
        .btn-reserve {
            display: inline-block;
            background: linear-gradient(135deg, #81D8D0, #00a99e);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 5px 15px rgba(0,169,158,0.15);
        }
        .btn-reserve:hover {
            background: linear-gradient(135deg, #00a99e, #81D8D0);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,169,158,0.2);
            color: white;
            text-decoration: none;
        }
        .btn-reserve i {
            margin-right: 8px;
        }
        .reservation-info {
            flex: 1;
        }
        .reservation-info h4 {
            color: #505866;
            margin-bottom: 5px;
        }
        .reservation-info p {
            color: #767f86;
            font-size: 13px;
            margin-bottom: 5px;
        }
        .reservation-status {
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            margin: 0 15px;
        }
        .reservation-status.confirmed {
            background: #d4edda;
            color: #155724;
        }
        .reservation-status.pending {
            background: #fff3cd;
            color: #856404;
        }
        .reservation-status.cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        .reservation-actions button {
            background: none;
            border: none;
            color: #767f86;
            cursor: pointer;
        }
        .btn-filter {
            background: #81D8D0;
            color: white;
            border: none;
            margin-top: 24px;
            width: 100%;
        }
        .btn-submit {
            background: #81D8D0;
            color: white;
            border: none;
        }
        .btn-cancel {
            background: #f1f1f1;
            color: #505866;
            border: none;
            margin-left: 10px;
        }
        .status-filter {
            transition: all 0.3s ease;
            padding: 10px 15px;
        }
        .status-filter:focus {
            border-color: #81D8D0;
            box-shadow: 0 0 0 2px rgba(129, 216, 208, 0.2);
        }
        .reservation-item {
            transition: all 0.3s ease;
        }
        .tunisia-map-wrapper {
            transform: scale(0.7);
            transform-origin: center;
            margin: 0 auto;
            display: block;
        }
    </style>




<style>
/* Style de base pour la section réservation */
.reservation-section {
    padding: 80px 0;
    background: #f8fafb;
}

.section-header {
    color: #505866;
    font-size: 24px;
    margin-bottom: 15px;
}

.filter-box {
    background: white;
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 30px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

/* Cartes */
.reservation-form-card,
.reservation-list-card {
    background: white;
    border-radius: 5px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.reservation-form-card h3,
.reservation-list-card h3 {
    color: #505866;
    margin-bottom: 20px;
    font-size: 18px;
}

/* Formulaire */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #505866;
}

.form-control {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #e1e5eb;
    border-radius: 3px;
}

/* Liste des réservations */
.reservation-item {
    display: flex;
    padding: 15px;
    border-bottom: 1px solid #f1f1f1;
    align-items: center;
}

/* Style pour la section BonPlans */
.bonplans-section {
    padding: 80px 0;
    background: #f9f9f9;
}

.bonplan-card {
    position: relative;
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    height: 100%;
    background: #fff;
    margin-bottom: 30px;
    border: 1px solid #eaeaea;
}

.bonplan-card:hover {
    transform: translateY(-7px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.destination-header {
    background: linear-gradient(135deg, #81D8D0, #00a99e);
    color: white;
    padding: 20px 25px;
    position: relative;
}

.destination-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(255, 255, 255, 0.2);
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    backdrop-filter: blur(5px);
}

.destination-title {
    font-size: 24px;
    font-weight: 700;
    margin: 0;
    margin-top: 5px;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.bonplan-content {
    padding: 25px;
}

.bonplan-info {
    margin-bottom: 25px;
}

.info-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 15px;
}

.info-item i {
    background: #f0f8f7;
    color: #00a99e;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 18px;
}

.info-text {
    flex: 1;
}

.info-label {
    display: block;
    font-size: 13px;
    color: #a0a0a0;
    margin-bottom: 2px;
}

.info-value {
    display: block;
    font-weight: 600;
    color: #505866;
    font-size: 16px;
}

.bonplan-actions {
    text-align: center;
    margin-top: 10px;
}

.btn-reserve {
    display: inline-block;
    background: linear-gradient(135deg, #81D8D0, #00a99e);
    color: white;
    border: none;
    border-radius: 50px;
    padding: 12px 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    text-decoration: none;
    box-shadow: 0 5px 15px rgba(0,169,158,0.15);
}

.btn-reserve:hover {
    background: linear-gradient(135deg, #00a99e, #81D8D0);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,169,158,0.2);
    color: white;
    text-decoration: none;
}

.btn-reserve i {
    margin-right: 8px;
}

.reservation-info {
    flex: 1;
}

.reservation-info h4 {
    color: #505866;
    margin-bottom: 5px;
}

.reservation-info p {
    color: #767f86;
    font-size: 13px;
    margin-bottom: 5px;
}

.reservation-status {
    padding: 5px 10px;
    border-radius: 3px;
    font-size: 12px;
    margin: 0 15px;
}

.reservation-status.confirmed {
    background: #d4edda;
    color: #155724;
}

.reservation-status.pending {
    background: #fff3cd;
    color: #856404;
}

.reservation-actions button {
    background: none;
    border: none;
    color: #767f86;
    cursor: pointer;
}

/* Boutons */
.btn {
    padding: 10px 20px;
    border-radius: 3px;
    cursor: pointer;
}

.btn-filter {
    background: #81D8D0;
    color: white;
    border: none;
    margin-top: 24px;
    width: 100%;
}

.btn-submit {
    background: #81D8D0;
    color: white;
    border: none;
}

.btn-cancel {
    background: #f1f1f1;
    color: #505866;
    border: none;
    margin-left: 10px;
}

/* Modal */
.modal-confirm {
    display: none;
    position: fixed;
    /* ... styles existants ... */
}
</style>

<script>
// Script pour la gestion des réservations
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation du datepicker
    flatpickr(".date-picker", {
        dateFormat: "d/m/Y",
        locale: "fr"
    });
    
    // Gestion des événements...
    // (Le code JavaScript complet serait ici)
});
</script>




<!--reservation end -->






        <!-- statistics strat -->
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
                    <h2>FORUMS AND COMMENTS</h2>
                    <p>Always up to date with our latest Forums and Comments</p>
                    <button class="welcome-hero-btn" data-toggle="modal" data-target="#addPostModal">
                        <i class="fa fa-plus"></i> Add New Post
                    </button>
                </div>
                <div class="blog-content">
                    <div class="row">
                        <?php
                        include '../../Controller/PostC.php';
                        include '../../Controller/CommentC.php';
                        
                        $PostC = new PostC();
                        $CommentC = new CommentC();
                        
                        // Pagination settings
                        $postsPerPage = 3; // Display 3 posts per page
                        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                        if ($currentPage < 1) $currentPage = 1;
                        
                        // Get all posts to count total posts
                        $allPosts = $PostC->AfficherPosts();
                        $totalPosts = count($allPosts);
                        $totalPages = ceil($totalPosts / $postsPerPage);
                        
                        // Ensure current page is within valid range
                        if ($currentPage > $totalPages && $totalPages > 0) {
                            $currentPage = $totalPages;
                        }
                        
                        // Calculate the offset for the current page
                        $offset = ($currentPage - 1) * $postsPerPage;
                        
                        // Get posts for the current page
                        $paginatedPosts = array_slice($allPosts, $offset, $postsPerPage);
                        $comments = $CommentC->AfficherComments();
                        
                        foreach($paginatedPosts as $post) {
                            // Count comments for this post
                            $commentCount = 0;
                            foreach($comments as $comment) {
                                if($comment['post_id'] == $post['id']) {
                                    $commentCount++;
                                }
                            }
                        ?>
                       <div class="col-md-4 col-sm-6 mb-4">
                            <div class="single-blog-item">
                                <div class="single-blog-item-img">
                                    <a href="post_info.php?id=<?php echo $post['id']; ?>">
                                        <img src="../back/pages/uploads/<?php echo $post['image']; ?>" alt="<?php echo $post['title']; ?>">
                                    </a>
                                </div>
                                <div class="single-blog-item-txt">
                                    <h2><a href="post_info.php?id=<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a></h2>
                                    <h4>Posted by <span>User ID: <?php  
                                        $userC = new UserC();
                                        $user = $userC->RecupererUser($post['user_id']);
                                        echo $user['username']; ?></span></h4>
                                    <p><?php echo $post['content']; ?></p>
                                    <div class="blog-actions">
                                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#commentModal" 
                                                data-post-id="<?php echo $post['id']; ?>">
                                            <i class="fa fa-comment"></i> Add Comment
                                        </button>
                                        <span class="comment-count">
                                            <i class="fa fa-comments"></i> <?php echo $commentCount; ?> Comments
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    
                    <!-- Pagination Controls -->
                    <?php if ($totalPages > 1): ?>
                    <div class="row">
                        <div class="col-md-12">
                            <nav aria-label="Page navigation" class="text-center">
                                <ul class="pagination">
                                    <?php if ($currentPage > 1): ?>
                                    <li>
                                        <a href="?page=<?php echo $currentPage - 1; ?>#blog" aria-label="Previous">
                                            <span aria-hidden="true">&laquo; Previous</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="<?php echo $i === $currentPage ? 'active' : ''; ?>">
                                        <a href="?page=<?php echo $i; ?>#blog"><?php echo $i; ?></a>
                                    </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($currentPage < $totalPages): ?>
                                    <li>
                                        <a href="?page=<?php echo $currentPage + 1; ?>#blog" aria-label="Next">
                                            <span aria-hidden="true">Next &raquo;</span>
                                        </a>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Add Post Modal -->
        <div class="modal fade" id="addPostModal" tabindex="-1" role="dialog" aria-labelledby="addPostModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="addPostModalLabel">Add New Post</h4>
                    </div>
                    <div class="modal-body">
                        <form id="addPostForm" action="store_post.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title">
                                <div class="error-message" id="title-error"></div>
                            </div>
                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea class="form-control" id="content" name="content" rows="4"></textarea>
                                <div class="error-message" id="content-error"></div>
                            </div>
                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                <div class="error-message" id="image-error"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Comment Modal -->
        <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="commentModalLabel">Add Comment</h4>
                    </div>
                    <div class="modal-body">
                        <form id="commentForm">
                            <input type="hidden" id="post_id" name="post_id">
                            <div class="form-group">
                                <label for="comment_content">Your Comment</label>
                                <textarea class="form-control" id="comment_content" name="content" rows="4"></textarea>
                                <div class="error-message" id="comment-content-error"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add Comment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .blog-actions {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .comment-count {
            color: #666;
            font-size: 0.9em;
        }

        .comment-count i {
            margin-right: 5px;
        }

        .single-blog-item {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            margin-bottom: 30px;
        }

        .single-blog-item:hover {
            transform: translateY(-5px);
        }

        .single-blog-item-img img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }

        .single-blog-item-txt {
            padding: 20px;
        }

        .single-blog-item-txt h2 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .single-blog-item-txt h4 {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 15px;
        }

        .section-header button {
            margin-top: 20px;
        }
        
        .error-message {
            color: #d9534f;
            font-size: 0.85em;
            margin-top: 5px;
            min-height: 18px;
        }
        </style>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle post submission
            const addPostForm = document.getElementById('addPostForm');
            if (addPostForm) {
                addPostForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Clear previous error messages
                    clearErrors();
                    
                    // Validate form
                    let isValid = true;
                    
                    // Validate title
                    const title = document.getElementById('title').value.trim();
                    if (!title) {
                        displayError('title-error', 'Title is required');
                        isValid = false;
                    }
                    
                    // Validate content
                    const content = document.getElementById('content').value.trim();
                    if (!content) {
                        displayError('content-error', 'Content is required');
                        isValid = false;
                    }
                    
                    // Validate image
                    const image = document.getElementById('image').files[0];
                    if (!image) {
                        displayError('image-error', 'Please select an image');
                        isValid = false;
                    } else {
                        // Check file type
                        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                        if (!validTypes.includes(image.type)) {
                            displayError('image-error', 'Please select a valid image file (JPG, JPEG, PNG, GIF)');
                            isValid = false;
                        }
                        
                        // Check file size (5MB max)
                        if (image.size > 5000000) {
                            displayError('image-error', 'Image size must be less than 5MB');
                            isValid = false;
                        }
                    }
                    
                    // If form is valid, submit it
                    if (isValid) {
                        addPostForm.submit();
                    }
                });
            }
            
            // Handle comment modal
            const commentModal = document.getElementById('commentModal');
            const commentForm = document.getElementById('commentForm');
            const postIdInput = document.getElementById('post_id');
            
            // Add click event listeners to all "Add Comment" buttons
            document.querySelectorAll('[data-toggle="modal"][data-target="#commentModal"]').forEach(button => {
                button.addEventListener('click', function() {
                    const postId = this.getAttribute('data-post-id');
                    postIdInput.value = postId;
                    commentForm.reset();
                    clearCommentError();
                });
            });
            
            // Handle comment submission
            if (commentForm) {
                commentForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Clear previous error
                    clearCommentError();
                    
                    const postId = postIdInput.value;
                    const content = document.getElementById('comment_content').value.trim();
                    
                    // Validate comment content
                    if (!content) {
                        displayError('comment-content-error', 'Please enter a comment');
                        return false;
                    }
                    
                    // Submit the form
                    fetch('store_comment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            post_id: postId,
                            content: content
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Close modal using jQuery (since Bootstrap 3/4 uses jQuery)
                            $('#commentModal').modal('hide');
                            // Reload page
                            window.location.reload();
                        } else {
                            displayError('comment-content-error', data.message || 'Error adding comment');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        displayError('comment-content-error', 'Error adding comment. Please try again.');
                    });
                });
            }
            
            // Utility functions for error handling
            function displayError(elementId, message) {
                const errorElement = document.getElementById(elementId);
                if (errorElement) {
                    errorElement.textContent = message;
                }
            }
            
            function clearErrors() {
                const errorElements = document.getElementsByClassName('error-message');
                for (let i = 0; i < errorElements.length; i++) {
                    errorElements[i].textContent = '';
                }
            }
            
            function clearCommentError() {
                const commentErrorElement = document.getElementById('comment-content-error');
                if (commentErrorElement) {
                    commentErrorElement.textContent = '';
                }
            }
        });
        </script>
        <!--blog end -->

        <!-- Comments Table Section -->
        <div class="container mt-5">
            <div class="section-header">
                <h2>All Comments</h2>
                <p>Manage and moderate all comments</p>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="commentsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Post Title</th>
                            <th>User</th>
                            <th>Content</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($comments as $comment) {
                            // Get post title
                            $post = $PostC->RecupererPost($comment['post_id']);
                            $postTitle = $post ? $post['title'] : 'Unknown Post';
                            
                            // Get username
                            $user = $userC->RecupererUser($comment['user_id']);
                            $username = $user ? $user['username'] : 'Unknown User';
                        ?>
                        <tr>
                            <td><?php echo $comment['id']; ?></td>
                            <td><?php echo $postTitle; ?></td>
                            <td><?php echo $username; ?></td>
                            <td><?php echo $comment['content']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick='openEditCommentModal(
                                    <?php echo $comment["id"]; ?>,
                                    <?php echo json_encode($comment["content"]); ?>,
                                    <?php echo $comment["post_id"]; ?>
                                )'>
                                    <i class="fa fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteComment(<?php echo $comment['id']; ?>)">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Comment Modal -->
        <div class="modal fade" id="editCommentModal" tabindex="-1" role="dialog" aria-labelledby="editCommentModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="editCommentModalLabel">Edit Comment</h4>
                    </div>
                    <div class="modal-body">
                        <form id="editCommentForm">
                            <input type="hidden" id="edit_comment_id" name="id">
                            <input type="hidden" id="edit_post_id" name="post_id">
                            <div class="form-group">
                                <label for="edit_comment_content">Comment Content</label>
                                <textarea class="form-control" id="edit_comment_content" name="content" rows="4" required></textarea>
                                <div id="editCommentError" class="error-message"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <style>
        /* Table Styles */
        #commentsTable {
            margin-top: 20px;
            background: white;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }

        #commentsTable thead th {
            background: #81D8D0;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9em;
            padding: 12px 15px;
        }

        #commentsTable tbody td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        #commentsTable tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn-sm {
            margin: 0 2px;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }

        .modal-header {
            background: #81D8D0;
            color: white;
            border-radius: 8px 8px 0 0;
        }

        .modal-header .close {
            color: white;
            opacity: 0.8;
        }

        .modal-title {
            font-weight: 600;
        }
        </style>

        <script>
        // Function to open edit comment modal
        function openEditCommentModal(commentId, content, postId) {
            document.getElementById('edit_comment_id').value = commentId;
            document.getElementById('edit_comment_content').value = content;
            document.getElementById('edit_post_id').value = postId;
            $('#editCommentModal').modal('show');
        }

        // Function to show alert message
        function showAlert(message, type = 'success') {
            const alertsContainer = document.getElementById('alerts-container');
            if (!alertsContainer) {
                const container = document.createElement('div');
                container.id = 'alerts-container';
                container.style.position = 'fixed';
                container.style.top = '20px';
                container.style.right = '20px';
                container.style.zIndex = '9999';
                document.body.appendChild(container);
            }
            
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.innerHTML = `
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            `;
            document.getElementById('alerts-container').appendChild(alert);

            // Auto remove after 5 seconds
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 150);
            }, 5000);
        }

        // Function to delete comment
        function deleteComment(commentId) {
            if (confirm('Are you sure you want to delete this comment?')) {
                fetch('delete_comment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + commentId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('Comment deleted successfully!');
                        window.location.reload();
                    } else {
                        showAlert(data.message || 'Error deleting comment', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Error deleting comment. Please try again.', 'danger');
                });
            }
        }

        // Handle edit comment form submission
        document.getElementById('editCommentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const commentId = document.getElementById('edit_comment_id').value;
            const content = document.getElementById('edit_comment_content').value.trim();
            const postId = document.getElementById('edit_post_id').value;

            if (!content) {
                document.getElementById('editCommentError').textContent = 'Comment content is required';
                return;
            }

            fetch('update_comment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    id: commentId,
                    content: content,
                    post_id: postId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Comment updated successfully!');
                    $('#editCommentModal').modal('hide');
                    window.location.reload();
                } else {
                    showAlert(data.message || 'Error updating comment', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error updating comment. Please try again.', 'danger');
            });
        });
        </script>
        <!--subscription strat -->
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
                                <button class="appsLand-btn subscribe-btn" onclick="window.location.href='#'">creat account</button>
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
                                <a class="navbar-brand" href="index.html">local<span>oo</span></a>
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <ul class="footer-menu-item">
                                <li class="scroll"><a href="#reclamation">reclamation</a></li>
                                <li class="scroll"><a href="#explore">explore</a></li>
                                <li class="scroll"><a href="#reservation">réservation</a></li>
                                <li class="scroll"><a href="#blog">blog</a></li>
                                <li class="scroll"><a href="#contact">contact</a></li>
                                <li class="scroll"><a href="#account">my account</a></li>
                                <li class="scroll"><a href="#reclamations">reclamation</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="hm-footer-copyright">
                    <div class="row">
                        <div class="col-sm-5">
                            <p>&copy;copyright. designed and developed by <a href="https://www.themesine.com/">themesine</a></p>
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

<!-- Reservation Modal -->
<div class="modal fade" id="reservationModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="reservationModalLabel">Réserver pour <span id="destination-name"></span></h4>
            </div>
            <div class="modal-body">
                <form method="POST" action="store_reservation.php" id="modal-reservation-form" onsubmit="return validateReservationForm();">
                    <input type="hidden" name="idBonPlan" id="modal-bonplan-id">
                    
                    <div class="form-group">
                        <label for="dateDepart">Date de départ</label>
                        <input type="date" class="form-control" id="modal-date-depart" name="dateDepart" >
                        <div id="departError" class="error-message"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="dateRetour">Date de retour</label>
                        <input type="date" class="form-control" id="modal-date-retour" name="dateRetour" >
                        <div id="retourError" class="error-message"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="nbPersonne">Nombre de personnes</label>
                        <input type="number" class="form-control" id="modal-nb-personne" name="nbPersonne" min="1" >
                        <div id="personneError" class="error-message"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="commentaire">Demandes spéciales</label>
                        <textarea class="form-control" id="modal-commentaire" name="commentaire" rows="3"></textarea>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Réserver</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
    <script src="assets/js/speech-to-text.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
<script src="assets/js/Controle.js"></script>
                <!-- ... autres scripts ... -->
                <script src="assets/js/custom.js"></script>

            

<script>
$(document).ready(function() {
    // Fonction de filtrage
    function filtrerOffres() {
    const localisation = $('#localisation-filter').val().toLowerCase();
    const salaireRange = $('#salaire-filter').val();
    
    let salaireMin = 0;
    let salaireMax = Infinity;
    
    if (salaireRange && salaireRange !== '') {
        const rangeParts = salaireRange.split('-');
        salaireMin = parseInt(rangeParts[0]);
        salaireMax = parseInt(rangeParts[1]);
    }
    
    $('.offre-item').each(function() {
        const offreLocalisation = $(this).data('localisation') ? $(this).data('localisation').toLowerCase() : '';
        const offreSalaire = parseInt($(this).data('salaire')) || 0;
        
        const matchLocalisation = !localisation || offreLocalisation.includes(localisation);
        const matchSalaire = offreSalaire >= salaireMin && offreSalaire <= salaireMax;
        
        if (matchLocalisation && matchSalaire) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
    
    if ($('.offre-item:visible').length === 0) {
        $('#offres-container').append(
            '<div class="col-12 text-center no-results">' +
            '   <p>Aucune offre ne correspond à vos critères de recherche.</p>' +
            '</div>'
        );
    } else {
        $('.no-results').remove();
    }
}
    
    // Appliquer le filtre
    $('#appliquer-filtre').click(filtrerOffres);
    
    // Réinitialiser le filtre
    $('#reinitialiser-filtre').click(function() {
        $('#localisation-filter').val('');
        $('#salaire-filter').val('');
        $('.offre-item').show();
        $('.no-results').remove();
    });
    
    // Optionnel: Filtrer automatiquement quand les sélecteurs changent
    // $('#localisation-filter, #salaire-filter').change(filtrerOffres);
});




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
                url: '../../Controller/CandidatureController.php',
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





 // Gestion des réservations
        const statusMap = {
            'confirmed': ['confirmed', 'confirmé'],
            'pending': ['pending', 'en attente'],
            'cancelled': ['cancelled', 'annulé']
        };

        $('#statusFilter').change(function() {
            const selectedStatus = $(this).val();
            let visibleCount = 0;
            $('.reservation-item').each(function() {
                const statusElement = $(this).find('.reservation-status');
                const statusText = statusElement.text().trim().toLowerCase();
                const statusClass = statusElement.attr('class').toLowerCase();
                const shouldShow = !selectedStatus || 
                    statusMap[selectedStatus].some(term => 
                        statusText.includes(term) || 
                        statusClass.includes(term)
                    );
                $(this).toggle(shouldShow);
                if (shouldShow) visibleCount++;
            });
            $('.reservation-list-card .badge').text(visibleCount);
        }).trigger('change');

        // Gestion du modal de réservation
        $('#reservationModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const bonplanId = button.data('bonplan-id');
            const destination = button.data('destination');
            const modal = $(this);
            modal.find('#destination-name').text(destination);
            modal.find('#modal-bonplan-id').val(bonplanId);
            modal.find('form')[0].reset();
            modal.find('.error-message').text('');
        });

        window.validateReservationForm = function() {
            let isValid = true;
            const dateDepart = document.getElementById('modal-date-depart').value;
            const dateRetour = document.getElementById('modal-date-retour').value;
            const nbPersonne = document.getElementById('modal-nb-personne').value;
            document.getElementById('departError').innerText = '';
            document.getElementById('retourError').innerText = '';
            document.getElementById('personneError').innerText = '';
            if (!dateDepart) {
                document.getElementById('departError').innerText = 'La date de départ est requise';
                isValid = false;
            }
            if (!dateRetour) {
                document.getElementById('retourError').innerText = 'La date de retour est requise';
                isValid = false;
            }
            if (dateDepart && dateRetour) {
                const depart = new Date(dateDepart);
                const retour = new Date(dateRetour);
                if (retour <= depart) {
                    document.getElementById('retourError').innerText = 'La date de retour doit être après la date de départ';
                    isValid = false;
                }
            }
            if (!nbPersonne || nbPersonne < 1) {
                document.getElementById('personneError').innerText = 'Veuillez indiquer un nombre de personnes valide';
                isValid = false;
            }
            return isValid;
        };













 window.ouvrirModalAjout = function() {
            document.getElementById('addReclamationForm').reset();
            document.getElementById('modalLabel').innerText = 'Ajouter une Réclamation';
            document.getElementById('addReclamationForm').action = 'store_reclamation.php';
        };

        window.ouvrirModalEdit = function(id, Type, Message) {
            document.getElementById('idReclamation').value = id;
            document.getElementById('Type').value = Type;
            document.getElementById('Message').value = Message;
            document.getElementById('modalLabel').innerText = 'Modifier réclamation';
            document.getElementById('addReclamationForm').action = 'update_reclamation.php';
            $('#addReclamationModal').modal('show');

   

    // For Bootstrap 4 (if you're using it)
    $('#addReclamationModal').modal('show');
}




</script>

                <!-- Nouveau script pour la carte -->
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Gestion des clics sur les marqueurs
                        const markers = document.querySelectorAll('.map-marker');
                        
                        markers.forEach(marker => {
                            marker.addEventListener('click', function(e) {
                                const city = this.dataset.city;
                                window.location.href = `#${city}-events`;
                            });
                        });
        
                        // Vérification du chargement de l'image
                        const mapImage = document.querySelector('.map-container img');
                        if (mapImage) {
                            mapImage.onload = function() {
                                console.log('Carte chargée avec succès !');
                            };
                            mapImage.onerror = function() {
                                console.error('Erreur de chargement de la carte');
                            };
                        }
                        
                        // Gestion du modal de réservation
                        $('#reservationModal').on('show.bs.modal', function (event) {
                            const button = $(event.relatedTarget);
                            const bonplanId = button.data('bonplan-id');
                            const destination = button.data('destination');
                            
                            const modal = $(this);
                            modal.find('#destination-name').text(destination);
                            modal.find('#modal-bonplan-id').val(bonplanId);
                            
                            // Reset form
                            modal.find('form')[0].reset();
                            
                            // Clear error messages
                            modal.find('.error-message').text('');
                        });
                        
                        // Valider le formulaire de réservation
                        window.validateReservationForm = function() {
                            let isValid = true;
                            
                            // Get values
                            const dateDepart = document.getElementById('modal-date-depart').value;
                            const dateRetour = document.getElementById('modal-date-retour').value;
                            const nbPersonne = document.getElementById('modal-nb-personne').value;
                            
                            // Reset error messages
                            document.getElementById('departError').innerText = '';
                            document.getElementById('retourError').innerText = '';
                            document.getElementById('personneError').innerText = '';
                            
                            // Validate date depart
                            if (!dateDepart) {
                                document.getElementById('departError').innerText = 'La date de départ est requise';
                                isValid = false;
                            }
                            
                            // Validate date retour
                            if (!dateRetour) {
                                document.getElementById('retourError').innerText = 'La date de retour est requise';
                                isValid = false;
                            }
                            
                            // Validate that date retour is after date depart
                            if (dateDepart && dateRetour) {
                                const depart = new Date(dateDepart);
                                const retour = new Date(dateRetour);
                                
                                if (retour <= depart) {
                                    document.getElementById('retourError').innerText = 'La date de retour doit être après la date de départ';
                                    isValid = false;
                                }
                            }
                            
                            // Validate nb personne
                            if (!nbPersonne || nbPersonne < 1) {
                                document.getElementById('personneError').innerText = 'Veuillez indiquer un nombre de personnes valide';
                                isValid = false;
                            }
                            
                            return isValid;
                        };
                    });
                </script>
            </body>
        </html>
    </body>
</html>
