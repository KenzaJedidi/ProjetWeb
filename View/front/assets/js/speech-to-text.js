document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si l'API Web Speech est disponible
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
        const buttons = document.querySelectorAll('#start-speech, #start-speech-modifier');
        buttons.forEach(button => {
            button.disabled = true;
        });
        const statuses = document.querySelectorAll('#speech-status, #speech-status-modifier');
        statuses.forEach(status => {
            status.textContent = 'Reconnaissance vocale non supportée dans ce navigateur. Utilisez Chrome ou Edge.';
        });
        return;
    }

    // Configurer la reconnaissance vocale
    const recognition = new SpeechRecognition();
    recognition.lang = 'fr-FR'; // Langue française
    recognition.interimResults = true; // Afficher les résultats intermédiaires
    recognition.continuous = true; // Continuer la reconnaissance jusqu'à l'arrêt

    // Fonction pour initialiser la reconnaissance pour un formulaire
    function initializeSpeechRecognition(buttonId, statusId, textareaId) {
        const startButton = document.getElementById(buttonId);
        const status = document.getElementById(statusId);
        const messageField = document.getElementById(textareaId);
        let isRecording = false;

        if (!startButton || !status || !messageField) return;

        // Gestion du bouton d'enregistrement
        startButton.addEventListener('click', function() {
            if (!isRecording) {
                // Démarrer l'enregistrement
                recognition.start();
                isRecording = true;
                startButton.innerHTML = '<i class="fa fa-stop"></i> Arrêter l\'enregistrement';
                startButton.classList.remove('btn-outline-primary');
                startButton.classList.add('btn-outline-danger');
                status.textContent = 'Enregistrement en cours...';
            } else {
                // Arrêter l'enregistrement
                recognition.stop();
                isRecording = false;
                startButton.innerHTML = '<i class="fa fa-microphone"></i> Enregistrer un message vocal';
                startButton.classList.remove('btn-outline-danger');
                startButton.classList.add('btn-outline-primary');
                status.textContent = 'Enregistrement arrêté.';
            }
        });

        // Gestion des résultats de la transcription
        recognition.onresult = function(event) {
            let interimTranscript = '';
            let finalTranscript = '';

            for (let i = event.resultIndex; i < event.results.length; i++) {
                const transcript = event.results[i][0].transcript;
                if (event.results[i].isFinal) {
                    finalTranscript += transcript + ' ';
                } else {
                    interimTranscript += transcript;
                }
            }

            // Mettre à jour le champ de message
            messageField.value = finalTranscript + interimTranscript;
        };

        // Gestion des erreurs
        recognition.onerror = function(event) {
            status.textContent = 'Erreur de reconnaissance vocale : ' + event.error;
            isRecording = false;
            startButton.innerHTML = '<i class="fa fa-microphone"></i> Enregistrer un message vocal';
            startButton.classList.remove('btn-outline-danger');
            startButton.classList.add('btn-outline-primary');
        };

        // Réinitialiser l'état après l'arrêt
        recognition.onend = function() {
            if (isRecording) {
                // Redémarrer automatiquement si l'enregistrement est toujours actif
                recognition.start();
            } else {
                status.textContent = 'Enregistrement arrêté.';
            }
        };
    }

    // Initialiser pour le formulaire de candidature (front.php)
    initializeSpeechRecognition('start-speech', 'speech-status', 'message');

    // Initialiser pour le formulaire de modification (mes_candidatures.php)
    initializeSpeechRecognition('start-speech-modifier', 'speech-status-modifier', 'modifierMessage');
});