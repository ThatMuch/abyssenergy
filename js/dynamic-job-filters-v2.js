/**
 * Filtres dynamiques pour les jobs
 * Auto-submission et mise à jour des compteurs en temps réel
 */

jQuery(document).ready(function($) {
    console.log('=== FILTRES DYNAMIQUES INITIALISÉS ===');

    // Attendre l'initialisation des multiselects
    setTimeout(function() {
        initDynamicFilters($);
    }, 1000);
});

function initDynamicFilters($) {
    const $form = $('.jobs-filter-form');

    if ($form.length === 0) {
        console.log('Formulaire de filtres non trouvé');
        return;
    }

    let isUpdating = false;
    let updateTimeout;

    // Selects disponibles
    const selects = {
        sector: $('#job-sector'),
        skill: $('#job-skill'),
        location: $('#job-location'),
        country: $('#job-country')
    };

    console.log('Selects trouvés:', Object.keys(selects).map(k => selects[k].length));

    // Fonction pour obtenir les valeurs sélectionnées
    function getSelectedValues($select) {
        const values = $select.val();
        return Array.isArray(values) ? values.filter(v => v !== '') : [];
    }

    // Fonction pour obtenir tous les filtres actifs
    function getActiveFilters() {
        return {
            sectors: getSelectedValues(selects.sector),
            skills: getSelectedValues(selects.skill),
            locations: getSelectedValues(selects.location),
            countries: getSelectedValues(selects.country)
        };
    }

    // Fonction pour vérifier si au moins un filtre est sélectionné
    function hasActiveFilters(filters) {
        return filters.sectors.length > 0 ||
               filters.skills.length > 0 ||
               filters.locations.length > 0 ||
               filters.countries.length > 0;
    }

    // Fonction de mise à jour des compteurs via AJAX
    function updateFilterCounts(changedFilterType) {
        if (isUpdating || !jobFiltersAjax) return;

        const activeFilters = getActiveFilters();

        // Vérifier s'il y a au moins un filtre sélectionné
        if (!hasActiveFilters(activeFilters)) {
            console.log('⏭️ Aucun filtre sélectionné, pas de mise à jour nécessaire');
            return;
        }

        console.log('Mise à jour des compteurs pour:', activeFilters);

        isUpdating = true;

        // Afficher l'indicateur de chargement
        showLoadingIndicator();

        $.ajax({
            url: jobFiltersAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'get_job_filter_counts',
                nonce: jobFiltersAjax.nonce,
                sectors: activeFilters.sectors,
                skills: activeFilters.skills,
                locations: activeFilters.locations,
                countries: activeFilters.countries
            },
            success: function(response) {
                console.log('Réponse AJAX:', response);

                if (response.success && response.data) {
                    updateSelectCounts('sector', response.data.sectors);
                    updateSelectCounts('skill', response.data.skills);
                    updateSelectCounts('location', response.data.locations);
                    updateSelectCounts('country', response.data.countries);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur AJAX:', error);
            },
            complete: function() {
                hideLoadingIndicator();
                isUpdating = false;
            }
        });
    }

    // Cache pour éviter les mises à jour multiples
    let updateCache = {};

    // Fonction pour mettre à jour les compteurs d'un select
    function updateSelectCounts(filterType, counts) {
        const $select = selects[filterType];
        if (!$select || $select.length === 0 || !counts) return;

        // Vérifier si cette mise à jour est identique à la précédente
        const cacheKey = filterType + JSON.stringify(counts);
        if (updateCache[filterType] === cacheKey) {
            console.log(`⏭️ Mise à jour ignorée (cache) pour ${filterType}`);
            return;
        }
        updateCache[filterType] = cacheKey;

        console.log(`🔄 Mise à jour des compteurs pour ${filterType}:`, counts);

        // Mettre à jour le select original
        $select.find('option[value]:not([value=""])').each(function() {
            const $option = $(this);
            const value = $option.val();
            const count = counts[value] !== undefined ? counts[value] : 0;

            // Stocker le texte original s'il n'est pas déjà stocké
            let originalText = $option.data('original-text');
            if (!originalText) {
                const currentText = $option.text().trim();
                // Regex plus robuste pour enlever (nombre) à la fin, même avec des espaces
                originalText = currentText.replace(/\s*\(\d+\)\s*$/, '').trim();
                $option.data('original-text', originalText);
                console.log(`📝 Texte original stocké pour ${value}:`, `"${originalText}"`, 'depuis:', `"${currentText}"`);
            }

            const newText = `${originalText} (${count})`;
            console.log(`🔤 Mise à jour option ${value}: "${$option.text()}" → "${newText}"`);

            // Mettre à jour le texte avec le nouveau compteur
            $option.text(newText);

            // Griser les options avec 0 résultat
            if (count === 0) {
                $option.addClass('disabled-option');
                $option.prop('disabled', true);
            } else {
                $option.removeClass('disabled-option');
                $option.prop('disabled', false);
            }
        });

        // Mettre à jour aussi le multiselect transformé
        const selectName = $select.attr('name');
        const $multiselect = $(`.multiselect[data-name="${selectName}"]`);

        if ($multiselect.length > 0) {
            console.log(`🎨 Mise à jour multiselect pour ${filterType}`);

            $multiselect.find('.multiselect__dropdown-option').each(function() {
                const $option = $(this);
                const value = $option.data('value');
                const count = counts[value] !== undefined ? counts[value] : 0;

                // Stocker le texte original s'il n'est pas déjà stocké
                let originalText = $option.data('original-text');
                if (!originalText) {
                    const currentText = ($option.find('.option-text, .text').text() || $option.text()).trim();
                    // Regex plus robuste pour enlever (nombre) à la fin, même avec des espaces
                    originalText = currentText.replace(/\s*\(\d+\)\s*$/, '').trim();
                    $option.data('original-text', originalText);
                    console.log(`📝 Texte original multiselect stocké pour ${value}:`, `"${originalText}"`, 'depuis:', `"${currentText}"`);
                }

                // Mettre à jour le texte avec le nouveau compteur
                const newText = `${originalText} (${count})`;
                console.log(`🎨 Mise à jour multiselect ${value}: "${originalText}" → "${newText}"`);
                if ($option.find('.option-text').length > 0) {
                    $option.find('.option-text').text(newText);
                } else if ($option.find('.text').length > 0) {
                    $option.find('.text').text(newText);
                } else {
                    // Préserver la checkbox et mettre à jour seulement le texte
                    const $checkbox = $option.find('.checkbox');
                    $option.empty().append($checkbox).append(`<span class="text">${newText}</span>`);
                }

                // Appliquer le style grisé
                if (count === 0) {
                    $option.addClass('disabled-option');
                    $option.css({
                        'color': '#ccc',
                        'opacity': '0.5',
                        'pointer-events': 'none'
                    });
                } else {
                    $option.removeClass('disabled-option');
                    $option.css({
                        'color': '',
                        'opacity': '',
                        'pointer-events': ''
                    });
                }
            });
        }
    }

    // Fonction pour désactiver les selects pendant la mise à jour
    function showLoadingIndicator() {
        $form.addClass('updating-filters');

        // Désactiver tous les selects et multiselects
        $form.find('select').prop('disabled', true);
        $('.multiselect').addClass('disabled');

        console.log('🔒 Selects désactivés pendant la mise à jour');
    }

    // Fonction pour réactiver les selects après la mise à jour
    function hideLoadingIndicator() {
        $form.removeClass('updating-filters');

        // Réactiver tous les selects et multiselects
        $form.find('select').prop('disabled', false);
        $('.multiselect').removeClass('disabled');

        console.log('🔓 Selects réactivés après la mise à jour');
    }

    // Fonction de soumission automatique
    function autoSubmitForm() {
        if (isUpdating) return;

        console.log('Auto-submission du formulaire');
        $form.css('opacity', '0.7');

        setTimeout(function() {
            $form[0].submit();
        }, 500);
    }

    // Écouter les changements sur tous les selects avec plus de debugging
    Object.keys(selects).forEach(filterType => {
        const $select = selects[filterType];
        if ($select.length > 0) {
            console.log(`Configuration listener pour ${filterType}:`, $select[0].id);

            // Essayer plusieurs types d'événements
            $select.on('change.dynamicFilter', function() {
                console.log(`🎯 CHANGEMENT jQuery détecté sur ${filterType}`, $(this).val());
                handleFilterChange(filterType);
            });

            // Backup avec événement natif
            $select[0].addEventListener('change', function() {
                console.log(`🎯 CHANGEMENT natif détecté sur ${filterType}`, this.value);
                handleFilterChange(filterType);
            });
        }
    });

    // Fonction centralisée pour gérer les changements
    function handleFilterChange(filterType) {
        console.log(`Traitement changement pour ${filterType}`);

        clearTimeout(updateTimeout);
        updateTimeout = setTimeout(function() {
            const activeFilters = getActiveFilters();

            // Vérifier s'il y a des filtres actifs
            if (hasActiveFilters(activeFilters)) {
                console.log('Démarrage mise à jour compteurs...');
                updateFilterCounts(filterType);

                // Auto-submit après la mise à jour des compteurs
                setTimeout(function() {
                    console.log('Auto-submission...');
                    autoSubmitForm();
                }, 1500);
            } else {
                console.log('⏭️ Aucun filtre actif, pas de mise à jour ni de soumission');
            }
        }, 300);
    }

    // Listener global de backup
    $form.on('change.globalFilter', 'select', function() {
        console.log('🌍 CHANGEMENT GLOBAL détecté:', this.id, $(this).val());
        const filterType = this.id.replace('job-', '');
        if (filterType === 'skill') filterType = 'skill';
        handleFilterChange(filterType);
    });

    // Écouter les clics sur les multiselects comme backup
    $('.multiselect').on('click', '.multiselect__dropdown-option', function() {
        const $multiselect = $(this).closest('.multiselect');
        const selectName = $multiselect.data('name');
        const filterType = selectName.replace('job_', '').replace('[]', '');

        console.log('👆 CLIC sur multiselect détecté:', filterType);

        // Attendre que la sélection se fasse
        setTimeout(function() {
            console.log('🔄 Traitement après clic multiselect');
            handleFilterChange(filterType === 'sector' ? 'sectors' :
                              filterType === 'skill' ? 'skills' :
                              filterType === 'location' ? 'locations' :
                              filterType === 'country' ? 'countries' : filterType);
        }, 200);
    });

    // CSS pour les options désactivées
    if ($('#dynamic-filters-css').length === 0) {
        $('head').append(`
            <style id="dynamic-filters-css">
                .disabled-option {
                    color: #ccc !important;
                    font-style: italic;
                }
            </style>
        `);
    }

    console.log('=== FILTRES DYNAMIQUES PRÊTS ===');

    // Test manuel de l'AJAX
    setTimeout(function() {
        console.log('🧪 TEST AJAX MANUEL');
        updateFilterCounts('test');
    }, 3000);
}
