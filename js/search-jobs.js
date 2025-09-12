/**
 * Script pour la recherche d'emplois
 * Améliore l'expérience utilisateur avec des fonctionnalités de recherche avancées
 */

document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('job-filters');
    const searchInput = document.getElementById('job_search');
    const locationSelect = document.getElementById('location');
    const skillSelect = document.getElementById('skill');

    if (!searchForm) return;

    // Soumission automatique du formulaire lors du changement des filtres
    if (locationSelect) {
        locationSelect.addEventListener('change', function() {
            searchForm.submit();
        });
    }

    if (skillSelect) {
        skillSelect.addEventListener('change', function() {
            searchForm.submit();
        });
    }

    // Recherche avec délai pour éviter trop de requêtes
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                // Optionnel: soumission automatique après 1 seconde d'inactivité
                // searchForm.submit();
            }, 1000);
        });

        // Soumission sur Entrée
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchForm.submit();
            }
        });
    }

    // Animation des filtres actifs
    const filterTags = document.querySelectorAll('.filter-tag .remove-filter');
    filterTags.forEach(function(removeBtn) {
        removeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const filterTag = this.closest('.filter-tag');
            filterTag.style.opacity = '0.5';
            filterTag.style.transform = 'scale(0.9)';

            setTimeout(function() {
                window.location.href = removeBtn.href;
            }, 200);
        });
    });

    // Animation des cartes d'emploi au scroll
    const jobCards = document.querySelectorAll('.job-card');
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    jobCards.forEach(function(card, index) {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        card.style.transitionDelay = (index * 0.1) + 's';
        observer.observe(card);
    });

    // Indicateur de chargement pour la soumission du formulaire
    searchForm.addEventListener('submit', function() {
        const submitBtn = searchForm.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Recherche...';
        }
    });

    // Gestion du bouton "Effacer tous les filtres"
    const clearAllBtn = document.querySelector('.clear-all-filters');
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function(e) {
            e.preventDefault();

            // Animation de nettoyage
            const activeFilters = document.querySelector('.active-filters');
            if (activeFilters) {
                activeFilters.style.opacity = '0';
                activeFilters.style.transform = 'scale(0.95)';
            }

            setTimeout(function() {
                window.location.href = clearAllBtn.href;
            }, 300);
        });
    }

    // Amélioration de l'accessibilité
    const filterInputs = searchForm.querySelectorAll('input, select');
    filterInputs.forEach(function(input) {
        input.addEventListener('focus', function() {
            this.closest('.filter-group').classList.add('focused');
        });

        input.addEventListener('blur', function() {
            this.closest('.filter-group').classList.remove('focused');
        });
    });
});

// Fonction utilitaire pour mettre à jour l'URL sans recharger la page
function updateURLWithoutReload(params) {
    const url = new URL(window.location);
    Object.keys(params).forEach(key => {
        if (params[key]) {
            url.searchParams.set(key, params[key]);
        } else {
            url.searchParams.delete(key);
        }
    });
    window.history.pushState({}, '', url);
}

// Gestion du bouton retour du navigateur
window.addEventListener('popstate', function() {
    location.reload();
});
