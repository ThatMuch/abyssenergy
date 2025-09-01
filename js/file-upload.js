/**
 * Script pour personnaliser le comportement des champs de téléchargement de fichier Gravity Forms
 *
 * Ce script remplace le texte du label par le nom du fichier lorsqu'un fichier est téléchargé
 */
(function($) {
    'use strict';

    // S'exécute lorsque le document est prêt
    $(document).ready(function() {
        // Initialise les gestionnaires d'événements pour les champs de téléchargement
        initFileUploadHandlers();

        // Ajoute un gestionnaire pour les formulaires Gravity Forms chargés via AJAX
        $(document).on('gform_post_render', function() {
            initFileUploadHandlers();
        });
    });

    /**
     * Initialise les gestionnaires d'événements pour tous les champs de téléchargement de fichier
     */
    function initFileUploadHandlers() {
        // Pour chaque champ de type fichier
        $('.gfield--type-fileupload').each(function() {
            const fieldContainer = $(this);
            const label = fieldContainer.find('.gfield_label');
            const inputFile = fieldContainer.find('input[type="file"]');
            const originalLabelText = label.text();

            // Ajoute une classe au label pour le rendre plus clairement cliquable
            label.addClass('file-upload-label');

            // Gestion du changement de fichier
            inputFile.on('change', function(e) {
                const files = e.target.files;

                if (files.length > 0) {
                    // Affiche le nom du fichier sélectionné
                    label.text(files[0].name);
                    label.addClass('file-selected');
                } else {
                    // Remet le texte original si aucun fichier n'est sélectionné
                    label.text(originalLabelText);
                    label.removeClass('file-selected');
                }
            });

            // Ajout d'une indication visuelle lors du glisser-déposer
            label.on('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                label.addClass('file-dragover');
            });

            label.on('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                label.removeClass('file-dragover');
            });

            label.on('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                label.removeClass('file-dragover');

                // Transfère les fichiers déposés à l'input file
                if (e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files.length) {
                    inputFile[0].files = e.originalEvent.dataTransfer.files;
                    inputFile.trigger('change');
                }
            });
        });
    }

})(jQuery);
