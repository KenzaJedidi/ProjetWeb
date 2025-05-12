<?php
include_once '../../Controller/PostC.php';
include_once '../../Controller/CommentC.php';
include_once '../../Controller/VoteC.php';

// Récupérer l'ID du post
$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($postId <= 0) {
    header('Location: front.php');
    exit;
}

// Récupérer les données
$postC = new PostC();
$post = $postC->RecupererPost($postId);
$commentC = new CommentC();

if (!$post) {
    header('Location: front.php');
    exit;
}

$comments = $commentC->AfficherCommentairesParPost($postId);

// Récupérer les votes
$voteC = new VoteC();
// $upvotes = $voteC->CountVotes($postId, 'up');
// $downvotes = $voteC->CountVotes($postId, 'down');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - Détails</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .post-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .post-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .post-title {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .post-meta {
            color: #7f8c8d;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        .post-content {
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .comment-card {
            border-left: 3px solid #3498db;
            margin-bottom: 15px;
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 0 5px 5px 0;
            position: relative;
        }
        .comment-date {
            font-size: 0.8rem;
            color: #7f8c8d;
        }
        .no-comments {
            color: #7f8c8d;
            font-style: italic;
            padding: 20px;
            text-align: center;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .add-comment-btn {
            margin-bottom: 30px;
        }
        .comment-actions {
            margin-top: 10px;
            text-align: right;
        }
        .comment-actions .btn {
            margin-left: 5px;
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        .vote-section {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .vote-btn {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            margin: 0 10px;
            display: flex;
            align-items: center;
        }
        .vote-btn:hover {
            color: #007bff;
        }
        .vote-count {
            font-size: 1rem;
            margin: 0 5px;
        }
        .metier-badge {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="front.php">Mon Blog</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="front.php"><i class="fas fa-home"></i> Accueil</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu Principal -->
    <div class="container my-5">
        <div class="post-container">
            <!-- Image du Post -->
            <img src="../back/pages/uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="post-image">

            <!-- Bouton Ajouter Commentaire sous l'image -->
            <div class="text-center add-comment-btn">
                <div class="btn-group" role="group">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#commentModal">
                        <i class="fas fa-plus"></i> Ajouter un commentaire
                    </button>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editPostModal">
                        Modifier le post
                    </button>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deletePostModal">
                        <i class="fas fa-trash"></i> Supprimer le post
                    </button>
                </div>
            </div>

            <!-- Titre et Métadonnées -->
            <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>
            <div class="post-meta">
                Posté le <?php echo date('d/m/Y à H:i', strtotime($post['created_at'])); ?>
            </div>

            <?php if (!empty($post['metier'])): ?>
                <div class="metier-badge">
                    <i class="fas fa-briefcase"></i> Métier Avancé React: <?php echo htmlspecialchars($post['metier']); ?>
                </div>
            <?php endif; ?>

            <!-- Vote Section -->
            <div class="vote-section">
                <form method="post" action="vote_action.php" class="d-inline">
                    <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                    <input type="hidden" name="vote_type" value="up">
                    <button type="submit" class="vote-btn text-success">
                        <i class="fas fa-thumbs-up"></i>
                        <span class="vote-count">+</span>
                    </button>
                </form>
                <form method="post" action="vote_action.php" class="d-inline">
                    <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                    <input type="hidden" name="vote_type" value="down">
                    <button type="submit" class="vote-btn text-danger">
                        <i class="fas fa-thumbs-down"></i>
                        <span class="vote-count"><?php echo $downvotes; ?></span>
                    </button>
                </form>
            </div>

            <!-- Contenu du Post -->
            <div class="post-content">
                <?php echo nl2br(htmlspecialchars($post['content'])); ?>
            </div>

            <!-- Section Commentaires -->
            <div class="comments-section mt-5">
                <h3 class="mb-4"><i class="fas fa-comments"></i> Commentaires (<?php echo count($comments); ?>)</h3>
                
                <?php if (empty($comments)): ?>
                    <div class="no-comments">Aucun commentaire pour le moment.</div>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment-card" id="comment-<?php echo $comment['id']; ?>">
                            <div class="comment-date">
                                <?php echo date('d/m/Y à H:i', strtotime($comment['created_at'])); ?>
                            </div>
                            <div class="comment-content mt-2">
                                <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                            </div>
                            <div class="comment-actions">
                                <button class="btn btn-warning btn-sm edit-comment" 
                                        data-comment-id="<?php echo $comment['id']; ?>"
                                        data-content="<?php echo htmlspecialchars($comment['content']); ?>">
                                    <i class="fas fa-edit"></i> Modifier
                                </button>
                                <button class="btn btn-danger btn-sm delete-comment" 
                                        data-comment-id="<?php echo $comment['id']; ?>">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Modal Modification Post -->
<!-- Modal Modification Post -->
<div class="modal fade" id="editPostModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Modifier le Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form id="editPostForm" method="POST" action="edit_store.php" enctype="multipart/form-data">
                <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editPostTitle" class="form-label">Titre</label>
                        <input type="text" class="form-control" id="editPostTitle" name="title"
                               value="<?php echo htmlspecialchars($post['title']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPostContent" class="form-label">Contenu</label>
                        <textarea class="form-control" id="editPostContent" name="content"
                                  rows="6" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editPostImage" class="form-label">Image (laisser vide pour ne pas changer)</label>
                        <input type="file" class="form-control" id="editPostImage" name="image" accept="image/*">
                        <div class="form-text">Image actuelle :
                            <a href="../back/pages/uploads/<?php echo htmlspecialchars($post['image']); ?>" target="_blank">
                                <?php echo htmlspecialchars($post['image']); ?>
                            </a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editPostMetier" class="form-label">Métier Avancé React (optionnel)</label>
                        <input type="text" class="form-control" id="editPostMetier" name="metier" 
                               value="<?php echo htmlspecialchars($post['metier'] ?? ''); ?>">
                        <div class="form-text">Spécifiez votre domaine d'expertise en React.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('editPostForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        const form = event.target;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message); // Show success message
                window.location.href = data.redirect; // Redirect
            } else {
                alert(data.message); // Show error message
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the post.');
        });
    });
</script>

<!-- Modal de confirmation pour la suppression du post -->
<div class="modal fade" id="deletePostModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer définitivement ce post ? Cette action supprimera également tous les commentaires associés.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="delete_post.php?id=<?php echo $postId; ?>" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Confirmer la suppression
                </a>
            </div>
        </div>
    </div>
</div>
    <!-- Modal Ajout Commentaire -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un commentaire</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addCommentForm" method="POST">
                    <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="commentContent" class="form-label">Votre commentaire</label>
                            <textarea class="form-control" id="commentContent" name="content" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Publier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Modification Commentaire -->
    <div class="modal fade" id="editCommentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le commentaire</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCommentForm" method="POST">
                    <input type="hidden" name="comment_id" id="editCommentId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editCommentContent" class="form-label">Votre commentaire</label>
                            <textarea class="form-control" id="editCommentContent" name="content" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
$(document).ready(function() {
    const alertsContainer = $('<div id="alerts-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1100"></div>');
    $('body').prepend(alertsContainer);

    // 🔸 AJOUT
    $('#addCommentForm').on('submit', function(e) {
        e.preventDefault();

        let formData = $(this).serialize(); // Serialize les champs du formulaire
        formData += '&action=add'; // Ajoute l'action

        $.ajax({
            url: 'store_comment.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#commentModal').modal('hide');
                    $('#addCommentForm')[0].reset();
                    showAlert('success', response.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert('danger', 'Erreur: ' + response.message);
                }
            },
            error: function(xhr) {
                showAlert('danger', 'Une erreur est survenue: ' + xhr.statusText);
            }
        });
    });

    // 🔸 MODIFICATION
    $(document).on('click', '.edit-comment', function() {
        $('#editCommentId').val($(this).data('comment-id'));
        $('#editCommentContent').val($(this).data('content'));
        $('#editCommentModal').modal('show');
    });

    $('#editCommentForm').on('submit', function(e) {
        e.preventDefault();

        const formData = {
            action: 'edit',
            comment_id: $('#editCommentId').val(),
            content: $('#editCommentContent').val()
        };

        $.ajax({
            url: 'store_comment.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#editCommentModal').modal('hide');
                    showAlert('success', response.message);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showAlert('danger', 'Erreur: ' + response.message);
                }
            },
            error: function(xhr) {
                showAlert('danger', 'Une erreur est survenue: ' + xhr.statusText);
            }
        });
    });

    // 🔸 SUPPRESSION
    $(document).on('click', '.delete-comment', function() {
        const commentId = $(this).data('comment-id');

        if (confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
            $.ajax({
                url: 'store_comment.php',
                type: 'POST',
                data: {
                    action: 'delete',
                    comment_id: commentId
                },
                dataType: 'json',
                contentType: 'application/x-www-form-urlencoded', // Important pour $_POST
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showAlert('danger', 'Erreur: ' + response.message);
                    }
                },
                error: function(xhr) {
                    showAlert('danger', 'Une erreur est survenue: ' + xhr.statusText);
                }
            });
        }
    });

    // 🔸 UTILITAIRE
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        $('#alerts-container').html(alertHtml);
        setTimeout(() => $('.alert').alert('close'), 4000);
    }
});

// Gestion de la suppression du post
// Gestion de la suppression du post
$('#deletePostModal a.btn-danger').on('click', function(e) {
    e.preventDefault();
    const deleteUrl = $(this).attr('href');
    
    $.ajax({
        url: deleteUrl,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showAlert('success', response.message);
                // Redirection vers front.php après 1.5 secondes
                setTimeout(() => {
                    window.location.href = response.redirect || 'front.php';
                }, 1500);
            } else {
                $('#deletePostModal').modal('hide');
                showAlert('danger', 'Erreur: ' + response.message);
            }
        },
        error: function(xhr) {
            $('#deletePostModal').modal('hide');
            showAlert('danger', 'Une erreur est survenue: ' + xhr.statusText);
        }
    });
});
</script>


</body>
</html>