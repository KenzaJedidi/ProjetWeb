<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$serveur = 'localhost';
$dbname = 'oussema';
$utilisateur = 'root';
$motdepasse = '';

try {
    $bdd = new PDO(
        "mysql:host=$serveur;dbname=$dbname;charset=utf8",
        $utilisateur,
        $motdepasse,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("<div class='alert alert-danger'>Erreur de connexion : " . $e->getMessage() . "</div>");
}

$favoris = $_SESSION['favoris'] ?? [];
$evenements = [];

if (!empty($favoris)) {
    try {
        $placeholders = implode(',', array_fill(0, count($favoris), '?'));
        $sql = "SELECT e.*, c.nom AS categorie_nom, c.icone 
                FROM evenements AS e
                LEFT JOIN categorie AS c ON e.categorie_id = c.id
                WHERE e.id IN ($placeholders)";
        
        $requete = $bdd->prepare($sql);
        $requete->execute($favoris);
        $evenements = $requete->fetchAll();

    } catch (PDOException $e) {
        die("<div class='alert alert-danger'>Erreur : " . $e->getMessage() . "</div>");
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Localoo – Favoris</title>
    <link rel="shortcut icon" type="image/icon" href="assets/images/logolocaloo.png"/>
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
    <style>
        .favoris-header {
            background: linear-gradient(135deg, #00CED1 0%, #AFEEEE 100%);
            padding: 120px 0 80px;
            margin-bottom: 60px;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 50% 100%, 0 85%);
        }

        .favoris-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            height: 100%;
        }

        .no-favoris {
            text-align: center;
            padding: 100px 0;
        }

        .no-favoris-icon {
            font-size: 80px;
            color: #00CED1;
            margin-bottom: 30px;
        }

        .category-icon {
            width: 16px !important;
            height: 16px !important;
            object-fit: contain !important;
        }
    </style>
</head>
<body>
   
    <section class="top-area">
        <div class="header-area">
            <nav class="navbar navbar-default bootsnav navbar-sticky navbar-scrollspy" 
                 data-minus-value-desktop="70" 
                 data-minus-value-mobile="55" 
                 data-speed="1000">
                <div class="container">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                            <i class="fa fa-bars"></i>
                        </button>
                        <a class="navbar-brand" href="front.html">
                            <img src="assets/images/logolocaloo.png" alt="Localoo Logo" class="navbar-logo">
                        </a>
                    </div>
                    <div class="collapse navbar-collapse menu-ui-design" id="navbar-menu">
                        <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
                            <li class="scroll"><a href="front.html">home</a></li>
                            <li class="scroll"><a href="front.html#emploi">emploi</a></li>
                            <li class="scroll"><a href="front.html#events">events</a></li>
                            <li class="scroll"><a href="front.html">réservation</a></li>
                            <li class="scroll"><a href="front.html">reclmation</a></li>
                            <li class="scroll"><a href="front.html">forum</a></li>
                            <li class="scroll"><a href="#contact">contact</a></li>
                            <li class="scroll"><a href="front.html">my account</a></li>
                            <li class="scroll active"><a href="mes-favoris.php">mes favoris</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </section>

   
    <div class="favoris-header">
        <div class="container">
            <h1 class="text-center text-white mb-3" style="font-weight:900;text-shadow:0 4px 15px rgba(0,0,0,0.2);font-size:3.5rem">
                </i>Mes Événements Favoris
            </h1>
        </div>
    </div>

    
    <div class="container mb-5 pt-5">
        <?php if (!empty($evenements)): ?>
            <div class="row g-4">
                <?php foreach ($evenements as $event): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card favoris-card">
                            <div class="event-img-container">
                                <?php if (!empty($event['image'])): ?>
                                    <img src="/web/uploads/<?= htmlspecialchars($event['image']) ?>" class="event-img" alt="">
                                <?php endif; ?>
                            </div>
                            <div class="card-content">
                                <div class="title-container">
                                    <h3 class="card-title"><?= htmlspecialchars($event['titre']) ?></h3>
                                    <div class="category-badge">
                                        <?php if (!empty($event['icone'])): ?>
                                            <img src="/web/uploads/<?= htmlspecialchars($event['icone']) ?>" class="category-icon" alt="">
                                        <?php endif; ?>
                                        <span><?= htmlspecialchars($event['categorie_nom']) ?></span>
                                    </div>
                                </div>
                                
                                <div class="date-badge">
                                    <i class="fa fa-calendar"></i>
                                    <?= date('d/m/Y', strtotime($event['date_debut'])) ?>
                                    <?php if (!empty($event['date_fin'])): ?>
                                        - <?= date('d/m/Y', strtotime($event['date_fin'])) ?>
                                    <?php endif; ?>
                                </div>

                                <p class="text-secondary mb-4"><?= nl2br(htmlspecialchars($event['description'])) ?></p>

                                <button class="reserve-btn w-100">
                                    <i class="fa fa-ticket"></i>
                                    Réserver maintenant
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-favoris">
                <i class="fa fa-heart-o no-favoris-icon"></i>
                <h3 class="text-muted">Aucun événement en favoris</h3>
                <a href="event.php#events" class="btn btn-primary mt-4">
                    <i class="fa fa-arrow-left mr-2"></i>Explorer les événements
                </a>
            </div>
        <?php endif; ?>
    </div>

    
    <footer id="footer" class="footer">
        <div class="container">
            <div class="footer-menu">
                <div class="row">
                    <div class="col-sm-3">
                        <a class="navbar-brand" href="index.html">local<span>oo</span></a>
                    </div>
                    <div class="col-sm-9">
                        <ul class="footer-menu-item">
                            <li class="scroll"><a href="front.html">Home</a></li>
                            <li class="scroll"><a href="front.html#events">events</a></li>
                            <li class="scroll"><a href="front.html">réservation</a></li>
                            <li class="scroll"><a href="front.html">reclmation</a></li>
                            <li class="scroll"><a href="front.html">forum</a></li>
                            <li class="scroll"><a href="#contact">contact</a></li>
                            <li class="scroll"><a href="front.html">my account</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-12 text-center mt-3">
                        <p>©copyright. designed and developed by <a href="https://www.themesine.com/">themesine</a></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

   
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/bootsnav.js"></script>
    <script>
    
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const eventId = btn.dataset.eventId;
                
                try {
                    const response = await fetch('favorite.php?action=toggle', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'event_id=' + encodeURIComponent(eventId)
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        location.reload();
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                }
            });
        });
    });
    </script>
</body>
</html>