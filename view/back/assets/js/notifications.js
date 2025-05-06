function startNotificationPolling() {
    // Appeler loadNotifications toutes les 10 secondes
    setInterval(() => {
        loadNotifications();
    }, 10000); // 10 secondes
}

// Lancer le polling au chargement de la page
document.addEventListener('DOMContentLoaded', startNotificationPolling);