/**
 * Script pour assurer la transmission du post ID lors des soumissions Gravity Forms
 * Nécessaire pour l'intégration avec le plugin squarechilli-jobboard
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        // Récupérer le post ID depuis les variables disponibles
        var postId = 0;
        
        // Essayer plusieurs sources pour le post ID
        if (typeof abyssenergy_job_context !== 'undefined' && abyssenergy_job_context.post_id) {
            postId = abyssenergy_job_context.post_id;
        } else if (typeof window.abyssenergy_job_post_id !== 'undefined') {
            postId = window.abyssenergy_job_post_id;
        }
        
        // Définir une variable globale pour la compatibilité
        window.abyssenergy_job_post_id = postId;

        console.log('Post ID detected:', postId);

        // Hook après le rendu du formulaire pour s'assurer que le champ caché est présent
        $(document).on('gform_post_render', function(event, form_id, current_page) {
            if (form_id == 1 && postId > 0) { // Formulaire de candidature
                console.log('Setting up hidden field for post ID:', postId);
                
                // Vérifier s'il y a déjà un champ post_id
                var existingPostIdField = $('#gform_1 input[name="post_id"]');
                
                if (existingPostIdField.length === 0) {
                    // Ajouter un champ caché avec le post ID
                    $('#gform_1').append('<input type="hidden" name="post_id" value="' + postId + '">');
                    console.log('Added hidden post_id field');
                } else {
                    // Mettre à jour la valeur existante
                    existingPostIdField.val(postId);
                    console.log('Updated existing post_id field');
                }
                
                // Ajouter aussi avec un nom différent pour plus de compatibilité
                if ($('#gform_1 input[name="input_999"]').length === 0) {
                    $('#gform_1').append('<input type="hidden" name="input_999" value="' + postId + '">');
                }
            }
        });

        // Hook sur l'événement de soumission du formulaire
        $(document).on('submit', '#gform_1', function() {
            if (postId > 0) {
                console.log('Form submission - ensuring post ID is included:', postId);
                
                // S'assurer qu'il y a un champ post_id
                var postIdField = $(this).find('input[name="post_id"]');
                if (postIdField.length === 0) {
                    $(this).append('<input type="hidden" name="post_id" value="' + postId + '">');
                }
                
                // Ajouter également input_999 pour compatibilité
                var input999Field = $(this).find('input[name="input_999"]');
                if (input999Field.length === 0) {
                    $(this).append('<input type="hidden" name="input_999" value="' + postId + '">');
                }
            }
        });

        // Hook avant validation
        $(document).on('gform_pre_validation', function(event, data, form) {
            if (form.id == 1 && postId > 0) {
                console.log('Pre-validation - ensuring post ID is in data');
                // Ajouter le post ID aux données
                if (typeof data === 'object') {
                    data.post_id = postId;
                }
            }
        });
    });

})(jQuery);