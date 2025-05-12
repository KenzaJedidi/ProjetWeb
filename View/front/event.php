<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$ville = filter_input(INPUT_GET, 'city', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (empty(trim($ville))) {
    header('Location: front.php');
    exit;
}

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

    $sql = "
        SELECT e.*,
               c.nom AS categorie_nom,
               c.icone
        FROM evenements AS e
        LEFT JOIN categorie AS c
          ON e.categorie_id = c.id
        WHERE e.ville = :ville
    ";

    $requete = $bdd->prepare($sql);
    $requete->execute([':ville' => $ville]);
    $evenements = $requete->fetchAll();

} catch (PDOException $e) {
    exit("Erreur base de données : " . $e->getMessage());
}

$favoris = $_SESSION['favoris'] ?? [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Localoo – Événements à <?= htmlspecialchars($ville) ?></title>
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
        :root {
            --primary-color: #00CED1;
            --secondary-color: #AFEEEE;
            --dark-color: #2D3436;
            --light-color: #F8F9FA;
        }

        .event-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            padding: 120px 0 80px;
            margin-bottom: 60px;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 50% 100%, 0 85%);
            animation: headerSlide 1s ease-out;
        }

        @keyframes headerSlide {
            0% { transform: translateY(-50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .event-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            height: 100%;
            position: relative;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 206, 209, 0.2);
        }

        .card-title {
            color: var(--dark-color);
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 15px;
            line-height: 1.3;
            flex-grow: 1;
        }

        .reserve-btn {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            transition: all 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .reserve-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 206, 209, 0.4);
        }

        .event-img-container {
            position: relative;
            overflow: hidden;
            height: 220px;
        }

        .event-img {
            height: 100%;
            width: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .event-card:hover .event-img {
            transform: scale(1.05);
        }

        .card-content {
            padding: 20px;
            position: relative;
        }

        .title-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 15px;
        }

        .category-badge {
            background: rgba(0, 206, 209, 0.1);
            color: var(--primary-color);
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .category-icon {
            width: 20px;
            height: 20px;
            object-fit: contain;
        }

        .favorite-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 100;
            background: rgba(255, 255, 255, 0.95);
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .favorite-btn:hover {
            transform: scale(1.1);
        }

        .fa-heart {
            color: #ff4757 !important;
            font-size: 1.1rem;
        }

        .date-badge {
            background: rgba(0, 206, 209, 0.1);
            color: var(--primary-color);
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
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
                        <a class="navbar-brand" href="front.php">
                            <img src="assets/images/logolocaloo.png" alt="Localoo Logo" class="navbar-logo">
                        </a>
                    </div>
                    <div class="collapse navbar-collapse menu-ui-design" id="navbar-menu">
                        <ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
                            <li class="scroll active"><a href="../front/front.php">home</a></li>
                            <li class="scroll"><a href="../front/front.php#emploi">emploi</a></li>
                            <li class="scroll"><a href="../front/front.php#events">events</a></li>
                            <li class="scroll"><a href="../front/front.php">Bon Plan</a></li>
                            <li class="scroll"><a href="../front/front.php">reclmation</a></li>
                            <li class="scroll"><a href="../front/front.php">forum</a></li>
                            <li class="scroll"><a href="#contact">contact</a></li>
                            <li class="scroll"><a href="../front/front.php">my account</a></li>
                            <li class="scroll"><a href="mes-favoris.php">mes favoris</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </section>

    <div class="event-header">
        <div class="container">
            <h1 class="text-center text-white mb-3" style="font-weight:900;text-shadow:0 4px 15px rgba(0,0,0,0.2);font-size:3.5rem">
                <i class="fa fa-calendar-star mr-2"></i>Événements à <?= htmlspecialchars($ville) ?>
            </h1>
        </div>
    </div>

    <div class="container mb-5 pt-5">
        <?php if (!empty($evenements)): ?>
            <div class="row g-4">
                <?php foreach ($evenements as $event): 
                    $isFav = in_array($event['id'], $favoris);
                ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card event-card">
                            <button class="favorite-btn" data-event-id="<?= $event['id'] ?>">
                                <i class="fa <?= $isFav ? 'fa-heart' : 'fa-heart-o' ?>"></i>
                            </button>
                            <div class="event-img-container">
    <?php if (!empty($event['image'])): ?>
        <img src="/localoo/uploads/<?= htmlspecialchars($event['image']) ?>" class="event-img" alt="">
    <?php endif; ?>
</div>
                            <div class="card-content">
                                <div class="title-container">
                                    <h3 class="card-title"><?= htmlspecialchars($event['titre']) ?></h3>
                                    <div class="category-badge">
                                        <?php if (!empty($event['icone'])): ?>
                                            <img src="/localoo/uploads/<?= htmlspecialchars($event['icone']) ?>" class="category-icon" alt="">
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

                                <?php if (!empty($event['participants'])): ?>
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fa fa-users mr-2 text-muted"></i>
                                        <small class="text-muted"><?= htmlspecialchars($event['participants']) ?> participants</small>
                                    </div>
                                <?php endif; ?>

                                <p class="text-secondary mb-4" style="font-size: 0.95rem;">
                                    <?= nl2br(htmlspecialchars($event['description'] ?: 'Description à venir')) ?>
                                </p>

                            <!-- Modifiez votre bouton comme ceci -->
<a href="front.php#bonplans" class="reserve-btn w-100">
    <i class="fa fa-ticket"></i>
    Réserver maintenant
</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-events text-center py-5">
                <div class="alert alert-info" style="max-width:600px;margin:0 auto;">
                    <i class="fa fa-calendar-xmark fa-4x mb-4" style="color:var(--primary-color)"></i>
                    <h3 style="color:var(--dark-color)">Aucun événement disponible à <?= htmlspecialchars($ville) ?></h3>
                    <p class="mt-3">Nous n'avons trouvé aucun événement dans cette zone pour le moment.</p>
                    <a href="../front/front.php#events" class="btn btn-primary mt-3"><i class="fa fa-arrow-left"></i> Retour à l'accueil</a>
                </div>
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
                            <li class="scroll"><a href="../front/front.php">Home</a></li>
                            <li class="scroll"><a href="../front/front.php#events">events</a></li>
                            <li class="scroll"><a href="../front/front.php">Bon Plan</a></li>
                            <li class="scroll"><a href="../front/front.php">reclmation</a></li>
                            <li class="scroll"><a href="../front/front.php">forum</a></li>
                            <li class="scroll"><a href="#contact">contact</a></li>
                            <li class="scroll"><a href="../front/front.php">my account</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-12 text-center mt-3">
                        <p>&copy;copyright. designed and developed by <a href="https://www.themesine.com/">themesine</a></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

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