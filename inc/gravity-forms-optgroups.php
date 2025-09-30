<?php
/**
 * Gravity Forms Optgroup Support - Version Simplifiée
 * 
 * Cette version utilise uniquement JavaScript côté client pour éviter les conflits
 * avec les versions de Gravity Forms et les erreurs de clés manquantes.
 */

// Empêcher l'accès direct au fichier
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Note : Les optgroups sont gérés entièrement côté client via JavaScript
 * 
 * Le fichier optgroup-handler.js se charge de :
 * - Détecter tous les selects avec des options "optgroup"
 * - Convertir ces options en vraies balises <optgroup>
 * - Gérer les selects Gravity Forms ajoutés dynamiquement
 * - Surveiller les mutations DOM pour les nouveaux selects
 * 
 * Configuration dans Gravity Forms :
 * 1. Créez vos options normalement
 * 2. Pour créer un groupe, ajoutez une option avec la valeur "optgroup"
 * 3. Les options suivantes seront automatiquement groupées
 * 
 * Exemple :
 * Libellé: "Secteur d'activité" | Valeur: "optgroup"
 * Libellé: "Énergie conventionnelle" | Valeur: "conventional" 
 * Libellé: "Énergie renouvelable" | Valeur: "renewable"
 */

// Ajouter une classe CSS aux formulaires Gravity Forms pour le styling des optgroups
function abyssenergy_gf_add_optgroup_class($form_tag, $form) {
    // Ajouter une classe pour identifier les formulaires avec support optgroup
    $form_tag = str_replace('gform_wrapper', 'gform_wrapper gform-optgroup-support', $form_tag);
    return $form_tag;
}

// Attendre que Gravity Forms soit chargé avant d'ajouter le filtre
function abyssenergy_init_gf_optgroup_class() {
    if (class_exists('GFForms')) {
        add_filter('gform_form_tag', 'abyssenergy_gf_add_optgroup_class', 10, 2);
    }
}
add_action('plugins_loaded', 'abyssenergy_init_gf_optgroup_class', 20);