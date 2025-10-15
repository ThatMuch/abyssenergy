/**
 * Version simple pour debug des filtres auto-submit - VERSION JQUERY
 */

jQuery(document).ready(function($) {
    console.log('=== DEBUT DEBUG FILTRES (jQuery) ===');

    // Attendre un peu pour que les multiselects s'initialisent
    setTimeout(function() {
        initDebugFilters($);
    }, 1000);
});

function initDebugFilters($) {
    console.log('=== INIT DEBUG FILTRES (après délai) ===');

    // Trouver le formulaire avec jQuery
    const $form = $('.jobs-filter-form');
    console.log('Formulaire trouvé:', $form.length > 0);

    if ($form.length === 0) {
        console.log('ERREUR: Formulaire non trouvé avec le sélecteur .jobs-filter-form');
        return;
    }

    // Trouver les selects avec jQuery
    const selects = {
        sector: $('#job-sector'),
        skill: $('#job-skill'),
        location: $('#job-location'),
        country: $('#job-country')
    };

    console.log('Selects jQuery trouvés:', {
        sector: selects.sector.length,
        skill: selects.skill.length,
        location: selects.location.length,
        country: selects.country.length
    });

    // Fonction de soumission simple
    function submitForm() {
        console.log('SOUMISSION du formulaire !');
        $form.css('opacity', '0.5');

        setTimeout(() => {
            $form[0].submit();
        }, 100);
    }

    // Ajouter les listeners jQuery sur les selects originaux
    Object.keys(selects).forEach(key => {
        const $select = selects[key];
        if ($select.length > 0) {
            console.log(`Ajout listener jQuery sur ${key}:`, $select[0].id);

            $select.on('change', function() {
                console.log(`CHANGEMENT jQuery détecté sur ${key}:`, $(this).val());
                console.log('Valeurs sélectionnées:', $(this).val());
                submitForm();
            });
        } else {
            console.log(`MANQUE: Select ${key} non trouvé`);
        }
    });

    // Listener global jQuery pour détecter tous les changements
    $form.on('change', 'select', function() {
        console.log('CHANGEMENT jQuery global détecté:', $(this)[0].id, $(this).val());
        submitForm();
    });

    // Cacher le bouton submit
    const $submitBtn = $form.find('button[type="submit"]');
    if ($submitBtn.length > 0) {
        console.log('Bouton submit trouvé et caché:', $submitBtn.text().trim());
        $submitBtn.hide();
    } else {
        console.log('ATTENTION: Bouton submit non trouvé');
    }

    console.log('=== FIN DEBUG FILTRES ===');
}
