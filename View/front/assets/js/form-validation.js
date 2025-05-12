// Form validation and alerts handling
class FormHandler {
    constructor() {
        this.initializeAlerts();
        this.initializeFormValidation();
    }

    // Initialize alerts container
    initializeAlerts() {
        // Create alerts container if it doesn't exist
        if (!document.getElementById('alerts-container')) {
            const alertsContainer = document.createElement('div');
            alertsContainer.id = 'alerts-container';
            alertsContainer.style.position = 'fixed';
            alertsContainer.style.top = '20px';
            alertsContainer.style.right = '20px';
            alertsContainer.style.zIndex = '9999';
            document.body.appendChild(alertsContainer);
        }
    }

    // Show alert message
    showAlert(message, type = 'success') {
        const alertsContainer = document.getElementById('alerts-container');
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        `;
        alertsContainer.appendChild(alert);

        // Auto remove after 5 seconds
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }, 5000);
    }

    // Initialize form validation
    initializeFormValidation() {
        // Post form validation
        const postForm = document.getElementById('addPostForm');
        if (postForm) {
            postForm.addEventListener('submit', (e) => this.validatePostForm(e));
        }

        // Comment form validation
        const commentForm = document.getElementById('commentForm');
        if (commentForm) {
            commentForm.addEventListener('submit', (e) => this.validateCommentForm(e));
        }
    }

    // Validate post form
    validatePostForm(e) {
        e.preventDefault();
        const title = document.getElementById('title').value.trim();
        const content = document.getElementById('content').value.trim();
        const image = document.getElementById('image').files[0];
        let isValid = true;
        let errorMessage = '';

        // Clear previous error messages
        this.clearErrors('post');

        // Validate title
        if (!title) {
            this.showError('title', 'Title is required');
            isValid = false;
        } else if (title.length < 3) {
            this.showError('title', 'Title must be at least 3 characters');
            isValid = false;
        }

        // Validate content
        if (!content) {
            this.showError('content', 'Content is required');
            isValid = false;
        } else if (content.length < 10) {
            this.showError('content', 'Content must be at least 10 characters');
            isValid = false;
        }

        // Validate image
        if (image) {
            const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!validTypes.includes(image.type)) {
                this.showError('image', 'Only JPG, PNG and GIF images are allowed');
                isValid = false;
            }
            if (image.size > 5 * 1024 * 1024) { // 5MB
                this.showError('image', 'Image size must be less than 5MB');
                isValid = false;
            }
        }

        if (isValid) {
            // Submit form
            const formData = new FormData(e.target);
            fetch('store_post.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showAlert('Post added successfully!');
                    $('#addPostModal').modal('hide');
                    window.location.reload();
                } else {
                    this.showAlert(data.message || 'Error adding post', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.showAlert('Error adding post. Please try again.', 'danger');
            });
        }
    }

    // Validate comment form
    validateCommentForm(e) {
        e.preventDefault();
        const content = document.getElementById('comment_content').value.trim();
        let isValid = true;

        // Clear previous error messages
        this.clearErrors('comment');

        // Validate content
        if (!content) {
            this.showError('comment_content', 'Comment is required');
            isValid = false;
        } else if (content.length < 3) {
            this.showError('comment_content', 'Comment must be at least 3 characters');
            isValid = false;
        }

        if (isValid) {
            // Submit form
            fetch('store_comment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    post_id: document.getElementById('post_id').value,
                    content: content
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showAlert('Comment added successfully!');
                    $('#commentModal').modal('hide');
                    window.location.reload();
                } else {
                    this.showAlert(data.message || 'Error adding comment', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.showAlert('Error adding comment. Please try again.', 'danger');
            });
        }
    }

    // Show error message
    showError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
        field.classList.add('is-invalid');
    }

    // Clear error messages
    clearErrors(formType) {
        const form = formType === 'post' ? document.getElementById('addPostForm') : document.getElementById('commentForm');
        const errorMessages = form.getElementsByClassName('error-message');
        while (errorMessages.length > 0) {
            errorMessages[0].remove();
        }
        const invalidFields = form.getElementsByClassName('is-invalid');
        while (invalidFields.length > 0) {
            invalidFields[0].classList.remove('is-invalid');
        }
    }
}

// Initialize form handler when document is ready
document.addEventListener('DOMContentLoaded', () => {
    window.formHandler = new FormHandler();
}); 