<?php

/**
 * Flush Permalinks Script
 *
 * Ce script temporaire force la réactualisation des règles de réécriture
 * Pour l'utiliser, accédez à /wp-content/themes/abyssenergy/flush-permalinks.php
 * dans votre navigateur, puis supprimez ce fichier après utilisation.
 */

// Charger WordPress
require_once('../../../../wp-load.php');

// Vérifier si l'utilisateur est connecté et est administrateur
if (!current_user_can('manage_options')) {
	die('Vous devez être connecté en tant qu\'administrateur pour exécuter ce script.');
}

// Mettre à jour l'option directement
update_option('rewrite_rules', '');

// Afficher un message de confirmation
echo '<h1>Règles de réécriture réinitialisées</h1>';
echo '<p>Les permaliens ont été réinitialisés avec succès. Vous pouvez maintenant accéder aux pages individuelles des jobs.</p>';
echo '<p>IMPORTANT : Supprimez ce fichier après utilisation pour des raisons de sécurité.</p>';
echo '<p><a href="' . esc_url(home_url('/')) . '">Retour au site</a></p>';
