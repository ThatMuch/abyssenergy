/**
 * Optgroup Handler
 * Convertit automatiquement les options avec la valeur "optgroup" en balises <optgroup>
 * Fonctionne avec tous les selects du site, y compris Gravity Forms
 */

document.addEventListener('DOMContentLoaded', function() {
    // Traiter tous les selects existants
    processAllSelects();

    // Observer les changements DOM pour les selects ajoutés dynamiquement (comme Gravity Forms)
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        // Chercher les nouveaux selects dans le noeud ajouté
                        const selects = node.querySelectorAll ? node.querySelectorAll('select') : [];
                        selects.forEach(processSelect);

                        // Vérifier si le noeud lui-même est un select
                        if (node.tagName === 'SELECT') {
                            processSelect(node);
                        }
                    }
                });
            }
        });
    });

    // Observer tout le document
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    // Hook spécifique pour Gravity Forms
    if (typeof gform !== 'undefined') {
        // Traiter les selects après le rendu des formulaires Gravity Forms
        document.addEventListener('gform_post_render', function(event) {
            setTimeout(() => {
                const form = document.getElementById('gform_' + event.detail.formId);
                if (form) {
                    const selects = form.querySelectorAll('select');
                    selects.forEach(processSelect);
                }
            }, 100);
        });
    }
});

/**
 * Traite tous les selects existants sur la page
 */
function processAllSelects() {
    const selects = document.querySelectorAll('select');
    selects.forEach(processSelect);
}

/**
 * Traite un select individuel pour convertir les options optgroup
 */
function processSelect(selectElement) {
    // Éviter le double traitement
    if (selectElement.dataset.optgroupProcessed === 'true') {
        return;
    }

    const options = Array.from(selectElement.options);
    let hasOptgroups = false;
    let currentOptgroup = null;
    const newElements = [];

    // Parcourir toutes les options
    for (let i = 0; i < options.length; i++) {
        const option = options[i];

        if (option.value === 'optgroup' || option.classList.contains('optgroup-header')) {
            // Cette option doit devenir un optgroup
            hasOptgroups = true;

            // Fermer l'optgroup précédent s'il existe
            if (currentOptgroup) {
                newElements.push(currentOptgroup);
            }

            // Créer un nouveau optgroup
            currentOptgroup = document.createElement('optgroup');
            currentOptgroup.label = option.textContent.trim();

            // Copier les attributs de l'option vers l'optgroup si nécessaire
            if (option.dataset.label) {
                currentOptgroup.label = option.dataset.label;
            }

        } else {
            // Option normale
            const newOption = option.cloneNode(true);

            if (currentOptgroup) {
                // Ajouter à l'optgroup courant
                currentOptgroup.appendChild(newOption);
            } else {
                // Ajouter directement aux nouveaux éléments
                newElements.push(newOption);
            }
        }
    }

    // Ajouter le dernier optgroup s'il existe
    if (currentOptgroup) {
        newElements.push(currentOptgroup);
    }

    // Si on a trouvé des optgroups, reconstruire le select
    if (hasOptgroups) {
        // Sauvegarder la valeur sélectionnée
        const selectedValues = Array.from(selectElement.selectedOptions).map(opt => opt.value);

        // Vider le select
        selectElement.innerHTML = '';

        // Ajouter les nouveaux éléments
        newElements.forEach(element => {
            selectElement.appendChild(element);
        });

        // Restaurer les sélections
        selectedValues.forEach(value => {
            const option = selectElement.querySelector(`option[value="${CSS.escape(value)}"]`);
            if (option) {
                option.selected = true;
            }
        });

        // Déclencher un événement de changement pour notifier les autres scripts
        const changeEvent = new Event('change', { bubbles: true });
        selectElement.dispatchEvent(changeEvent);
    }

    // Marquer comme traité
    selectElement.dataset.optgroupProcessed = 'true';
}

/**
 * Fonction utilitaire pour créer des options avec optgroup dans PHP/JavaScript
 * Peut être utilisée pour créer dynamiquement des selects avec optgroups
 */
function createSelectWithOptgroups(selectElement, optionsData) {
    selectElement.innerHTML = '';

    optionsData.forEach(item => {
        if (item.type === 'optgroup') {
            const optgroup = document.createElement('optgroup');
            optgroup.label = item.label;

            item.options.forEach(optionData => {
                const option = document.createElement('option');
                option.value = optionData.value;
                option.textContent = optionData.text;
                if (optionData.selected) option.selected = true;
                optgroup.appendChild(option);
            });

            selectElement.appendChild(optgroup);
        } else {
            const option = document.createElement('option');
            option.value = item.value;
            option.textContent = item.text;
            if (item.selected) option.selected = true;
            selectElement.appendChild(option);
        }
    });
}
