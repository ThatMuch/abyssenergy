/**
 * Fixed Jobs Block JavaScript
 * Gère l'interactivité des onglets et le chargement dynamique
 */

document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner tous les blocs fixed-jobs sur la page
    const fixedJobsBlocks = document.querySelectorAll('.fixed-jobs-block');

    fixedJobsBlocks.forEach(block => {
        const tabs = block.querySelectorAll('.fixed-jobs-tab');
        const tabContents = block.querySelectorAll('.fixed-jobs-tab-content');
        const loadMoreButtons = block.querySelectorAll('.load-more-jobs');

        if (!tabs.length || !tabContents.length) return;

        // Gestion des onglets
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetCategory = this.getAttribute('data-category');

                // Mettre à jour les onglets actifs
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                // Mettre à jour le contenu actif
                tabContents.forEach(content => {
                    content.classList.remove('active');
                    if (content.getAttribute('data-category') === targetCategory) {
                        content.classList.add('active');
                    }
                });

                // Animation fluide
                const activeContent = block.querySelector(`.fixed-jobs-tab-content[data-category="${targetCategory}"]`);
                if (activeContent) {
                    activeContent.style.opacity = '0';
                    activeContent.style.transform = 'translateY(20px)';

                    setTimeout(() => {
                        activeContent.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                        activeContent.style.opacity = '1';
                        activeContent.style.transform = 'translateY(0)';
                    }, 50);
                }
            });
        });

        // Gestion du bouton "Charger plus"
        loadMoreButtons.forEach(button => {
            button.addEventListener('click', function() {
                const category = this.getAttribute('data-category');
                const currentPage = parseInt(this.getAttribute('data-page'));
                const nextPage = currentPage + 1;

                // Désactiver le bouton pendant le chargement
                this.disabled = true;
                this.textContent = 'Chargement...';

                // Préparer les données pour la requête AJAX
                const formData = new FormData();
                formData.append('action', 'load_more_fixed_jobs');
                formData.append('category', category);
                formData.append('page', nextPage);
                formData.append('posts_per_page', getPostsPerPage(block));
                formData.append('show_excerpt', getShowExcerpt(block));
                formData.append('show_apply_button', getShowApplyButton(block));
                formData.append('nonce', fixedJobsAjax?.nonce || '');

                // Requête AJAX
                fetch(fixedJobsAjax?.ajaxurl || '/wp-admin/admin-ajax.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.html) {
                        // Ajouter les nouveaux éléments
                        const grid = this.closest('.fixed-jobs-tab-content').querySelector('.fixed-jobs-grid');
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = data.data.html;

                        // Animation d'apparition pour les nouveaux éléments
                        const newCards = tempDiv.querySelectorAll('.fixed-job-card');
                        newCards.forEach((card, index) => {
                            card.style.opacity = '0';
                            card.style.transform = 'translateY(30px)';
                            grid.appendChild(card);

                            setTimeout(() => {
                                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                                card.style.opacity = '1';
                                card.style.transform = 'translateY(0)';
                            }, index * 100);
                        });

                        // Mettre à jour le bouton
                        this.setAttribute('data-page', nextPage);
                        this.textContent = 'Charger plus';
                        this.disabled = false;

                        // Cacher le bouton s'il n'y a plus de contenu
                        if (!data.data.has_more) {
                            this.style.display = 'none';
                        }
                    } else {
                        // Erreur de chargement
                        this.textContent = 'Erreur de chargement';
                        setTimeout(() => {
                            this.textContent = 'Charger plus';
                            this.disabled = false;
                        }, 2000);
                    }
                })
                .catch(error => {
                    console.error('Erreur AJAX:', error);
                    this.textContent = 'Erreur de chargement';
                    setTimeout(() => {
                        this.textContent = 'Charger plus';
                        this.disabled = false;
                    }, 2000);
                });
            });
        });
    });

    /**
     * Fonctions utilitaires pour récupérer les paramètres du bloc
     */
    function getPostsPerPage(block) {
        // Récupérer depuis les données du bloc ou valeur par défaut
        return block.dataset.postsPerPage || 6;
    }

    function getShowExcerpt(block) {
        return block.dataset.showExcerpt !== 'false';
    }

    function getShowApplyButton(block) {
        return block.dataset.showApplyButton !== 'false';
    }
});

/**
 * Gestion du clavier pour l'accessibilité
 */
document.addEventListener('keydown', function(e) {
    if (e.target.classList.contains('fixed-jobs-tab')) {
        const tabs = Array.from(e.target.parentNode.children);
        const currentIndex = tabs.indexOf(e.target);
        let nextIndex;

        switch (e.key) {
            case 'ArrowLeft':
                e.preventDefault();
                nextIndex = currentIndex > 0 ? currentIndex - 1 : tabs.length - 1;
                tabs[nextIndex].focus();
                break;
            case 'ArrowRight':
                e.preventDefault();
                nextIndex = currentIndex < tabs.length - 1 ? currentIndex + 1 : 0;
                tabs[nextIndex].focus();
                break;
            case 'Enter':
            case ' ':
                e.preventDefault();
                e.target.click();
                break;
        }
    }
});
