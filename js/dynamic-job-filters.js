/**
 * Filtres dynamiques pour les jobs
 * Auto-submission et mise à jour des compteurs
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Script dynamic-job-filters chargé');

    const filterForm = document.querySelector('.jobs-filter-form');

    if (!filterForm) {
        console.log('Formulaire .jobs-filter-form non trouvé');
        return;
    }

    console.log('Formulaire trouvé:', filterForm);

    const sectorSelect = document.getElementById('job-sector');
    const skillSelect = document.getElementById('job-skill');
    const locationSelect = document.getElementById('job-location');
    const countrySelect = document.getElementById('job-country');

    console.log('Selects trouvés:', {
        sector: !!sectorSelect,
        skill: !!skillSelect,
        location: !!locationSelect,
        country: !!countrySelect
    });

    let isUpdating = false; // Empêcher les boucles infinies
    let submitTimeout;

    // Fonction pour récupérer les valeurs sélectionnées d'un multiselect
    function getSelectedValues(select) {
        if (!select) return [];
        const selectedOptions = Array.from(select.selectedOptions);
        return selectedOptions.map(option => option.value).filter(value => value !== '');
    }

    // Fonction pour récupérer tous les filtres actifs
    function getActiveFilters() {
        return {
            sectors: getSelectedValues(sectorSelect),
            skills: getSelectedValues(skillSelect),
            locations: getSelectedValues(locationSelect),
            countries: getSelectedValues(countrySelect)
        };
    }

    // Fonction pour mettre à jour les compteurs d'un select
    function updateSelectCounts(select, counts, type) {
        if (!select || !counts) return;

        const options = select.querySelectorAll('option[value]:not([value=""])');

        options.forEach(option => {
            const value = option.value;
            const count = counts[type] && counts[type][value] !== undefined ? counts[type][value] : 0;

            // Extraire le texte de l'option sans le compteur
            let optionText = option.textContent;
            const countMatch = optionText.match(/^(.*?)\s*\(\d+\)$/);
            if (countMatch) {
                optionText = countMatch[1].trim();
            }

            // Mettre à jour le texte avec le nouveau compteur
            option.textContent = `${optionText} (${count})`;

            // Désactiver les options avec 0 résultat (optionnel)
            if (count === 0) {
                option.disabled = true;
                option.style.color = '#ccc';
            } else {
                option.disabled = false;
                option.style.color = '';
            }
        });

        // Réactiver le multiselect si c'est un plugin
        if (select.classList.contains('abyss-multiselect')) {
            // Trigger un événement pour que le plugin multiselect se mette à jour
            const event = new Event('change', { bubbles: true });
            select.dispatchEvent(event);
        }
    }

    // Fonction pour soumettre le formulaire automatiquement
    function autoSubmitForm() {
        console.log('autoSubmitForm appelée, isUpdating:', isUpdating);

        if (isUpdating) {
            console.log('Déjà en cours de mise à jour, annulation');
            return;
        }

        isUpdating = true;
        console.log('Soumission du formulaire dans 500ms...');

        // Ajouter un indicateur de chargement visuel
        filterForm.style.opacity = '0.7';

        // Soumettre le formulaire après un court délai
        clearTimeout(submitTimeout);
        submitTimeout = setTimeout(() => {
            console.log('Soumission du formulaire maintenant');
            filterForm.submit();
        }, 500);
    }

    // Fonction pour mettre à jour tous les compteurs (optionnelle, pour plus tard)
    function updateAllFilterCounts() {
        if (!jobFiltersAjax || isUpdating) return;

        const activeFilters = getActiveFilters();

        // Vérifier s'il y a au moins un filtre actif
        const hasActiveFilters = Object.values(activeFilters).some(filters => filters.length > 0);

        if (!hasActiveFilters) {
            return;
        }

        isUpdating = true;
        showLoadingIndicator();

        // Faire la requête AJAX
        const formData = new FormData();
        formData.append('action', 'get_job_filter_counts');
        formData.append('nonce', jobFiltersAjax.nonce);

        // Ajouter les filtres actifs
        Object.keys(activeFilters).forEach(filterType => {
            activeFilters[filterType].forEach(value => {
                formData.append(`${filterType}[]`, value);
            });
        });

        fetch(jobFiltersAjax.ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                // Mettre à jour les compteurs pour chaque filtre
                updateSelectCounts(sectorSelect, data.data, 'sectors');
                updateSelectCounts(skillSelect, data.data, 'skills');
                updateSelectCounts(locationSelect, data.data, 'locations');
                updateSelectCounts(countrySelect, data.data, 'countries');
            }
        })
        .catch(error => {
            console.error('Erreur lors de la mise à jour des filtres:', error);
        })
        .finally(() => {
            hideLoadingIndicator();
            isUpdating = false;
        });
    }

    // Fonction pour afficher l'indicateur de chargement
    function showLoadingIndicator() {
        // Ajouter une classe de chargement au formulaire
        filterForm.classList.add('loading-filters');

        // Optionnel: ajouter un indicateur visuel
        const existingLoader = filterForm.querySelector('.filter-loader');
        if (!existingLoader) {
            const loader = document.createElement('div');
            loader.className = 'filter-loader';
            loader.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mise à jour des filtres...';
            loader.style.cssText = `
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: rgba(255, 255, 255, 0.9);
                padding: 10px 20px;
                border-radius: 5px;
                z-index: 1000;
                font-size: 14px;
                color: #666;
            `;
            filterForm.style.position = 'relative';
            filterForm.appendChild(loader);
        }
    }

    // Fonction pour cacher l'indicateur de chargement
    function hideLoadingIndicator() {
        filterForm.classList.remove('loading-filters');
        const loader = filterForm.querySelector('.filter-loader');
        if (loader) {
            loader.remove();
        }
    }

    // Debounce function pour éviter trop de requêtes
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Écouter les changements sur tous les selects pour auto-submission
    [sectorSelect, skillSelect, locationSelect, countrySelect].forEach(select => {
        if (select) {
            select.addEventListener('change', function() {
                console.log('Filtre changé:', select.id, 'Valeurs:', getSelectedValues(select));
                autoSubmitForm();
            });
        }
    });

    // CSS pour l'état de chargement
    const style = document.createElement('style');
    style.textContent = `
        .loading-filters {
            opacity: 0.7;
            pointer-events: none;
        }
        .loading-filters select {
            opacity: 0.5;
        }
    `;
    document.head.appendChild(style);

    // Cacher le bouton Apply Filters s'il existe
    const submitButton = filterForm.querySelector('button[type="submit"], input[type="submit"]');
    if (submitButton) {
        submitButton.style.display = 'none';
    }

    console.log('Filtres dynamiques initialisés');
});
