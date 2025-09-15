/**
 * Timeline Block JavaScript
 * Gère l'ouverture des modales pour afficher les descriptions complètes
 */

document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner tous les blocs timeline sur la page
    const timelineBlocks = document.querySelectorAll('.timeline-block');

    timelineBlocks.forEach(block => {
        const buttons = block.querySelectorAll('.timeline-button');

        if (!buttons.length) return;

        // Créer le modal une seule fois par bloc
        const modal = createModal();
        document.body.appendChild(modal);

        buttons.forEach((button, index) => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const step = button.closest('.timeline-step');
                if (!step) return;

                // Récupérer les données de l'étape
                const title = step.querySelector('.timeline-step-title').textContent;
                const image = step.querySelector('.timeline-step-image img');
                const description = getStepDescription(block, index);

                // Remplir le modal avec les données
                populateModal(modal, title, image, description);

                // Afficher le modal
                showModal(modal);
            });
        });

        // Fermer le modal en cliquant sur le backdrop ou le bouton fermer
        modal.addEventListener('click', function(e) {
            if (e.target === modal || e.target.classList.contains('modal-close')) {
                hideModal(modal);
            }
        });

        // Fermer avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                hideModal(modal);
            }
        });
    });

    /**
     * Crée la structure HTML du modal
     */
    function createModal() {
        const modal = document.createElement('div');
        modal.className = 'timeline-modal';
        modal.innerHTML = `
            <div class="timeline-modal-content timeline-step">
			<div class="timeline-step-header">
			<div class="timeline-step-image">
                            <img src="" alt="" loading="lazy">
                        </div>
                        <h3 class="timeline-step-title h4"></h3>
                <button class="modal-close" aria-label="Fermer">
                    <i class="fa fa-times"></i>
                </button>
				</div>
                    <div class="timeline-step-content">
                        <div class="timeline-step-description"></div>
                    </div>
            </div>
        `;
        return modal;
    }

    /**
     * Récupère la description complète d'une étape depuis les données PHP
     */
    function getStepDescription(block, stepIndex) {
        // Essayer de récupérer les données depuis un attribut data ou une variable globale
        const blockId = block.getAttribute('data-block-id') || 'timeline';
        const timelineData = window[`timelineData_${blockId}`];

        if (timelineData && timelineData.steps && timelineData.steps[stepIndex]) {
            return timelineData.steps[stepIndex].description;
        }

        // Fallback : récupérer depuis les éléments cachés ou data attributes
        const stepElement = block.querySelectorAll('.timeline-step')[stepIndex];
        const hiddenDescription = stepElement?.querySelector('[data-description]');

        if (hiddenDescription) {
            return hiddenDescription.getAttribute('data-description');
        }

        return 'Description non disponible.';
    }

    /**
     * Remplit le modal avec les données de l'étape
     */
    function populateModal(modal, title, imageElement, description) {
        const modalTitle = modal.querySelector('.timeline-step-title');
        const modalImage = modal.querySelector('.timeline-step-image img');
        const modalImageContainer = modal.querySelector('.timeline-step-image');
        const modalDescription = modal.querySelector('.timeline-step-description');

        modalTitle.textContent = title;
        modalDescription.innerHTML = description;

        if (imageElement) {
            modalImage.src = imageElement.src;
            modalImage.alt = imageElement.alt;
            modalImageContainer.style.display = 'block';
        } else {
            modalImageContainer.style.display = 'none';
        }
    }

    /**
     * Affiche le modal
     */
    function showModal(modal) {
        modal.classList.add('active');
        document.body.classList.add('modal-open');

        // Focus sur le modal pour l'accessibilité
        const closeButton = modal.querySelector('.modal-close');
        if (closeButton) {
            closeButton.focus();
        }
    }

    /**
     * Cache le modal
     */
    function hideModal(modal) {
        modal.classList.remove('active');
        document.body.classList.remove('modal-open');
    }
});
