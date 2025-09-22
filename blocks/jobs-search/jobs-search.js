/**
 * Jobs Search Block JavaScript
 * Gère l'interactivité du formulaire de recherche
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialiser tous les blocs jobs search sur la page
    const jobsSearchBlocks = document.querySelectorAll('.jobs-search-block');

    jobsSearchBlocks.forEach(block => {
        initJobsSearchBlock(block);
    });

    /**
     * Initialise un bloc jobs search
     */
    function initJobsSearchBlock(block) {
        const form = block.querySelector('.jobs-search-form');
        const input = block.querySelector('.jobs-search-input');
        const button = block.querySelector('.jobs-search-button');

        if (!form || !input || !button) {
            return;
        }

        // Améliorer l'expérience utilisateur avec des animations
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });

        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });

        // Validation simple côté client
        form.addEventListener('submit', function(e) {
            const searchTerm = input.value.trim();

            if (searchTerm.length === 0) {
                e.preventDefault();
                input.focus();
                showInputError(input, 'Veuillez saisir un terme de recherche');
                return false;
            }

            if (searchTerm.length < 2) {
                e.preventDefault();
                input.focus();
                showInputError(input, 'Veuillez saisir au moins 2 caractères');
                return false;
            }

            // Animation du bouton pendant la soumission
            button.classList.add('loading');
            button.disabled = true;
        });

        // Nettoyage des erreurs lors de la saisie
        input.addEventListener('input', function() {
            clearInputError(this);
            if (button.classList.contains('loading')) {
                button.classList.remove('loading');
                button.disabled = false;
            }
        });

        // Support de la touche Entrée
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                form.dispatchEvent(new Event('submit', { bubbles: true }));
            }
        });
    }

    /**
     * Affiche un message d'erreur sur le champ
     */
    function showInputError(input, message) {
        clearInputError(input);

        const errorElement = document.createElement('div');
        errorElement.className = 'jobs-search-error';
        errorElement.textContent = message;
        errorElement.setAttribute('role', 'alert');

        input.parentElement.appendChild(errorElement);
        input.classList.add('error');
        input.setAttribute('aria-invalid', 'true');

        // Supprimer l'erreur après 5 secondes
        setTimeout(() => {
            clearInputError(input);
        }, 5000);
    }

    /**
     * Nettoie les messages d'erreur
     */
    function clearInputError(input) {
        const existingError = input.parentElement.querySelector('.jobs-search-error');
        if (existingError) {
            existingError.remove();
        }
        input.classList.remove('error');
        input.removeAttribute('aria-invalid');
    }

    // Réinitialiser les blocs lors de l'édition dans Gutenberg
    if (window.acf) {
        window.acf.addAction('render_block_preview/type=jobs-search', function() {
            // Attendre que le DOM soit mis à jour
            setTimeout(() => {
                const newBlocks = document.querySelectorAll('.jobs-search-block');
                newBlocks.forEach(block => {
                    // Éviter la double initialisation
                    if (!block.dataset.jobsSearchInitialized) {
                        block.dataset.jobsSearchInitialized = 'true';
                        initJobsSearchBlock(block);
                    }
                });
            }, 100);
        });
    }
});
