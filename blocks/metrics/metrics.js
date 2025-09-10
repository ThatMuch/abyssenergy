/**
 * Metrics Block JavaScript
 * Gère l'expansion/contraction des cartes métriques
 */

document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner tous les blocs de métriques sur la page
    const metricsBlocks = document.querySelectorAll('.metrics-block');

    metricsBlocks.forEach(block => {
        const grid = block.querySelector('.metrics-grid');
        const cards = block.querySelectorAll('.metric-card');
        const buttons = block.querySelectorAll('.metric-button');

        if (!grid || !cards.length) return;

        let expandedCard = null;

        buttons.forEach((button, index) => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const clickedCard = cards[index];

                // Si la carte cliquée est déjà étendue, la fermer
                if (expandedCard === clickedCard) {
                    collapseAllCards();
                    return;
                }

                // Sinon, étendre la carte cliquée
                expandCard(clickedCard);
            });
        });

        /**
         * Étend une carte et cache les autres
         */
        function expandCard(cardToExpand) {
            // Marquer la carte comme étendue
            expandedCard = cardToExpand;

            cards.forEach(card => {
                if (card === cardToExpand) {
                    // Étendre la carte sélectionnée
                    card.classList.add('expanded');
                    card.classList.remove('hidden');
                } else {
                    // Cacher les autres cartes
                    card.classList.add('hidden');
                    card.classList.remove('expanded');
                }
            });

            // Ajouter une classe au grid pour les styles globaux
            grid.classList.add('has-expanded-card');

            // Faire défiler vers la carte étendue si nécessaire
            setTimeout(() => {
                cardToExpand.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
            }, 300);
        }

        /**
         * Ferme toutes les cartes et les remet à l'état normal
         */
        function collapseAllCards() {
            expandedCard = null;

            cards.forEach(card => {
                card.classList.remove('expanded', 'hidden');
            });

            grid.classList.remove('has-expanded-card');
        }

        // Fermer les cartes étendues en cliquant en dehors
        document.addEventListener('click', function(e) {
            if (expandedCard && !block.contains(e.target)) {
                collapseAllCards();
            }
        });

        // Fermer avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && expandedCard) {
                collapseAllCards();
            }
        });
    });
});
