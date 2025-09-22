/**
 * Tabs Block JavaScript
 * Gère l'interactivité des onglets
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialiser tous les blocs tabs sur la page
    const tabsBlocks = document.querySelectorAll('.tabs-block');

    tabsBlocks.forEach(block => {
        initTabsBlock(block);
    });

    /**
     * Initialise un bloc tabs
     */
    function initTabsBlock(block) {
        const tabsNav = block.querySelector('.tabs-nav');
        const tabButtons = block.querySelectorAll('.tabs-nav-link');
        const tabPanels = block.querySelectorAll('.tabs-panel');

        if (!tabsNav || !tabButtons.length || !tabPanels.length) {
            return;
        }

        // Ajouter les événements de clic sur les onglets
        tabButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const targetPanelId = this.getAttribute('data-tab-target');
                switchToTab(block, this, targetPanelId);
            });

            // Support clavier
            button.addEventListener('keydown', function(e) {
                handleKeyboardNavigation(e, block, tabButtons);
            });
        });
    }

    /**
     * Change l'onglet actif
     */
    function switchToTab(block, activeButton, targetPanelId) {
        const allButtons = block.querySelectorAll('.tabs-nav-link');
        const allPanels = block.querySelectorAll('.tabs-panel');
        const targetPanel = block.querySelector('#' + targetPanelId);

        if (!targetPanel) {
            return;
        }

        // Désactiver tous les onglets
        allButtons.forEach(button => {
            button.classList.remove('active');
            button.setAttribute('aria-selected', 'false');
        });

        allPanels.forEach(panel => {
            panel.classList.remove('active');
            panel.setAttribute('hidden', '');
        });

        // Activer l'onglet sélectionné
        activeButton.classList.add('active');
        activeButton.setAttribute('aria-selected', 'true');

        targetPanel.classList.add('active');
        targetPanel.removeAttribute('hidden');

        // Déclencher l'animation d'entrée
        setTimeout(() => {
            targetPanel.classList.add('animated');
        }, 10);
    }

    /**
     * Gestion de la navigation au clavier
     */
    function handleKeyboardNavigation(e, block, tabButtons) {
        const currentIndex = Array.from(tabButtons).indexOf(e.target);
        let targetIndex = currentIndex;

        switch (e.key) {
            case 'ArrowDown':
            case 'ArrowRight':
                e.preventDefault();
                targetIndex = currentIndex + 1;
                if (targetIndex >= tabButtons.length) {
                    targetIndex = 0;
                }
                break;
            case 'ArrowUp':
            case 'ArrowLeft':
                e.preventDefault();
                targetIndex = currentIndex - 1;
                if (targetIndex < 0) {
                    targetIndex = tabButtons.length - 1;
                }
                break;
            case 'Home':
                e.preventDefault();
                targetIndex = 0;
                break;
            case 'End':
                e.preventDefault();
                targetIndex = tabButtons.length - 1;
                break;
            default:
                return;
        }

        // Déplacer le focus et activer l'onglet
        const targetButton = tabButtons[targetIndex];
        targetButton.focus();
        const targetPanelId = targetButton.getAttribute('data-tab-target');
        switchToTab(block, targetButton, targetPanelId);
    }

    // Réinitialiser les blocs lors de l'édition dans Gutenberg
    if (window.acf) {
        window.acf.addAction('render_block_preview/type=tabs', function() {
            // Attendre que le DOM soit mis à jour
            setTimeout(() => {
                const newBlocks = document.querySelectorAll('.tabs-block');
                newBlocks.forEach(block => {
                    // Éviter la double initialisation
                    if (!block.dataset.tabsInitialized) {
                        block.dataset.tabsInitialized = 'true';
                        initTabsBlock(block);
                    }
                });
            }, 100);
        });
    }
});
