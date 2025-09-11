/**
 * Single Fixed Job Sidebar JavaScript
 * Gère le chargement dynamique des postes dans la sidebar
 */

document.addEventListener('DOMContentLoaded', function() {
    const showMoreButtons = document.querySelectorAll('.btn-show-more');

    showMoreButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            const loaded = parseInt(this.getAttribute('data-loaded'));
            const card = this.closest('.job-category-card');
            const jobsContainer = card.querySelector('.category-jobs');

            // État de chargement
            this.disabled = true;
            this.classList.add('loading');
            this.textContent = 'Loading';

            // Préparer les données pour la requête AJAX
            const formData = new FormData();
            formData.append('action', 'load_more_category_jobs');
            formData.append('category', category);
            formData.append('loaded', loaded);
            formData.append('nonce', fixedJobsSidebar?.nonce || '');

            // Requête AJAX
            fetch(fixedJobsSidebar?.ajaxurl || '/wp-admin/admin-ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.html) {
                    // Créer un conteneur temporaire pour les nouveaux éléments
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.data.html;

                    // Ajouter les nouveaux éléments avec animation
                    const newJobs = tempDiv.querySelectorAll('.job-item');
                    newJobs.forEach((job, index) => {
                        job.classList.add('newly-loaded');
                        jobsContainer.appendChild(job);

                        // Déclencher l'animation après un court délai
                        setTimeout(() => {
                            job.style.animationDelay = `${index * 0.1}s`;
                        }, 50);
                    });

                    // Mettre à jour le nombre d'éléments chargés
                    const newLoaded = loaded + newJobs.length;
                    this.setAttribute('data-loaded', newLoaded);

                    // Mettre à jour ou cacher le bouton
                    if (data.data.has_more) {
                        const remaining = data.data.total - newLoaded;
                        this.textContent = `Show More (${remaining} more)`;
                        this.disabled = false;
                        this.classList.remove('loading');
                    } else {
                        // Plus d'éléments à charger, cacher le bouton
                        this.closest('.category-actions').style.display = 'none';
                    }
                } else {
                    // Erreur de chargement
                    this.textContent = 'Error loading';
                    this.classList.remove('loading');
                    setTimeout(() => {
                        const remaining = parseInt(this.textContent.match(/\d+/)[0]);
                        this.textContent = `Show More (${remaining} more)`;
                        this.disabled = false;
                    }, 2000);
                }
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                this.textContent = 'Error loading';
                this.classList.remove('loading');
                setTimeout(() => {
                    const remaining = parseInt(this.textContent.match(/\d+/)[0]);
                    this.textContent = `Show More (${remaining} more)`;
                    this.disabled = false;
                }, 2000);
            });
        });
    });

    // Smooth scroll vers les éléments cliqués
    const jobLinks = document.querySelectorAll('.job-sidebar .job-item a');
    jobLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Laisser le comportement par défaut du lien
            // Mais ajouter un effet visuel
            this.closest('.job-item').style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.closest('.job-item').style.transform = '';
            }, 150);
        });
    });
});
