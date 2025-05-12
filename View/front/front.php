<?php
include '../../Controller/ReclamationC.php';

$ReclamationC = new ReclamationC();

$listReclamation = $ReclamationC->AfficherReclamation();

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

        

        
    </head>
    
    <body>
    <style>
              .error-message {
    color: red;
    font-size: 0.8em;
    margin-top: 0.2em;
  }
</style>
       
        <!--header-top start -->
         <!--
        <header id="header-top" class="header-top">
            <ul>
                <li>
                    <div class="header-top-left">
                        <ul>
                            <li class="select-opt">
                                <select name="language" id="language">
                                    <option value="default">EN</option>
                                    <option value="Bangla">BN</option>
                                    <option value="Arabic">AB</option>
                                </select>
                            </li>
                            <li class="select-opt">
                                <select name="currency" id="currency">
                                    <option value="usd">USD</option>
                                    <option value="euro">Euro</option>
                                    <option value="bdt">BDT</option>
                                </select>
                            </li>
                            <li class="select-opt">
                                <a href="#"><span class="lnr lnr-magnifier"></span></a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="head-responsive-right pull-right">
                    <div class="header-top-right">
                        <ul>
                            <li class="header-top-contact">
                                +1 222 777 6565
                            </li>
                            <li class="header-top-contact">
                                <a href="#">sign in</a>
                            </li>
                            <li class="header-top-contact">
                                <a href="#">register</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        -->
        </header>
        <!--header-top end -->
   
        <!-- top-area Start -->
        <section class="top-area">
            <div class="header-area">
                <!-- Start Navigation -->
                <nav class="navbar navbar-default bootsnav  navbar-sticky navbar-scrollspy"  data-minus-value-desktop="70" data-minus-value-mobile="55" data-speed="1000">
                    <div class="container">
                        <!-- Start Header Navigation -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                                <i class="fa fa-bars"></i>
                            </button>
                            <!-- Remplacer list race par localoo -->
                            <a class="navbar-brand" href="front.html">
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
                                <li class="scroll"><a href="#reclamations">Reclamations</a></li>
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
                            search  <i data-feather="search"></i> 
                        </button>
                    </div>
                </div>
            </div>
        </section>
        
        <!--welcome-hero end -->
 
    
      





        <!--explore start -->
        <!--emploi start -->
<section id="explore" class="emploi">
    <div class="container">
        <div class="section-header">
            <h2>OFFRES D'EMPLOI</h2>
            <p>On recrute les talents qui osent réinventer les sorties en Tunisie – à vous de jouer ! ✨</p>
        </div>
        
        <div class="emploi-content">
            <div class="row">
                <div class="col-md-6">
                    <div class="emploi-image">
                        <img src="assets/images/emploi.jpg" alt="Offres d'emploi Localoo" class="img-responsive">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="emploi-description">
                        <h3>Rejoignez l'aventure Localoo</h3>
                        <p>Nous recherchons des profils dynamiques pour renforcer notre équipe :</p>
                        <ul class="emploi-list">
                            <li><i class="fa fa-check"></i> Développeurs Web/Mobile</li>
                            <li><i class="fa fa-check"></i> Secrétaires polyvalents</li>
                            <li><i class="fa fa-check"></i> Agents d'agence de voyage</li>
                            <li><i class="fa fa-check"></i> Organisateurs d'événements</li>
                        </ul>
                        <div class="emploi-buttons">
                            <button class="welcome-hero-btn emploi-btn" data-toggle="modal" data-target="#candidatureModal">
                                Postuler maintenant
                            </button>
                            <button class="welcome-hero-btn emploi-btn emploi-btn-delete" id="deleteFormBtn">
                                Supprimer formulaire
                            </button>
                            <button class="welcome-hero-btn emploi-btn emploi-btn-edit" id="editFormBtn">
                                Modifier formulaire
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--emploi end -->

<!-- Modal Formulaire de Candidature -->
<div class="modal fade" id="candidatureModal" tabindex="-1" role="dialog" aria-labelledby="candidatureModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="candidatureModalLabel">Postuler à Localoo</h4>
            </div>
            <div class="modal-body">
                <form id="formCandidature">
                    <div class="form-group">
                        <label for="nomComplet">Nom Complet *</label>
                        <input type="text" class="form-control" id="nomComplet" required>
                        <div class="invalid-feedback">Veuillez entrer votre nom complet</div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" class="form-control" id="email" required>
                        <div class="invalid-feedback">Veuillez entrer un email valide</div>
                    </div>
                    <div class="form-group">
                        <label for="telephone">Téléphone</label>
                        <input type="tel" class="form-control" id="telephone">
                    </div>
                    <div class="form-group">
                        <label for="poste">Poste souhaité *</label>
                        <select class="form-control" id="poste" required>
                            <option value="">-- Sélectionnez --</option>
                            <option>Développeur Web/Mobile</option>
                            <option>Secrétaire polyvalent</option>
                            <option>Agent d'agence de voyage</option>
                            <option>Organisateur d'événements</option>
                        </select>
                        <div class="invalid-feedback">Veuillez sélectionner un poste</div>
                    </div>
                    <div class="form-group">
                        <label for="cv">CV (PDF uniquement, max 2MB) *</label>
                        <input type="file" class="form-control" id="cv" accept=".pdf" required>
                        <div class="invalid-feedback" id="cvError">Veuillez sélectionner un fichier PDF (max 2MB)</div>
                    </div>
                    <div class="form-group">
                        <label for="message">Lettre de motivation</label>
                        <textarea class="form-control" id="message" rows="5"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer ma candidature</button>
                </form>
            </div>
        </div>
    </div>
</div>


        <!--emploi end -->
        <!--evants start -->
        <section id="events" class="events">
            <div class="section-header">
                <h2>EVENTS</h2>
                <h2>Choisissez votre destination de rêve!</h2>
                <p>Réservez dès maintenant en temps réel</p>
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
        // Animation au survol seulement
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
        
<!-- Section Réservation -->
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
                        <!-- ... (autres articles b2, b3) -->
                    </div>
                </div>
            </div>
        </section>
        <!--blog end -->

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
        <script src="assets/js/Controle.js"></script>
                <!-- ... autres scripts ... -->
                <script src="assets/js/custom.js"></script>

                <script>
function ouvrirModalAjout() {
    document.getElementById('addReclamationForm').reset();
    document.getElementById('modalLabel').innerText = 'Ajouter une Réclamation';
    document.getElementById('addReclamationForm').action = 'store_reclamation.php';
}


function ouvrirModalEdit(id, Type, Message) {
    console.log("Modifier button clicked!");
    console.log("ID:", id);
    console.log("Type:", Type);
    console.log("Message:", Message);

    document.getElementById('idReclamation').value = id;
    document.getElementById('Type').value = Type;
    document.getElementById('Message').value = Message;

    document.getElementById('modalLabel').innerText = 'Modifier réclamation';
    document.getElementById('addReclamationForm').action = 'update_reclamation.php';

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
                        mapImage.onload = function() {
                            console.log('Carte chargée avec succès !');
                        };
                        mapImage.onerror = function() {
                            console.error('Erreur de chargement de la carte');
                        };
                    });
                </script>
            </body>
        </html>
    </body>
</html>
