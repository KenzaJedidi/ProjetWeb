<?php foreach ($reservations as $reservation): ?>
    <tr>
        <!-- ... autres colonnes ... -->
        <td>
            <form method="POST" action="delete_reservation.php" class="d-inline">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                <button type="submit" class="btn btn-danger btn-sm" 
                        onclick="return confirm('Confirmez la suppression de la réservation #<?= $reservation['id'] ?>?')">
                    Supprimer
                </button>
            </form>
        </td>
    </tr>
<?php endforeach; ?>