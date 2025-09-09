<?php
// Test file pour vérifier la logique du bloc Google Reviews
// Ce fichier peut être supprimé après vérification

function test_google_reviews_logic()
{
	echo "=== Test de la logique de limitation Google Reviews ===\n\n";

	// Test 1: Champ vide (affiche tous)
	$reviews_count1 = '';
	$limit_count1 = (!empty($reviews_count1) && is_numeric($reviews_count1) && $reviews_count1 > 0) ? intval($reviews_count1) : null;
	echo "Test 1 - Champ vide: limit_count = " . ($limit_count1 ?: 'null (tous les avis)') . "\n";

	// Test 2: Limite à 3
	$reviews_count2 = '3';
	$limit_count2 = (!empty($reviews_count2) && is_numeric($reviews_count2) && $reviews_count2 > 0) ? intval($reviews_count2) : null;
	echo "Test 2 - Limite à 3: limit_count = " . ($limit_count2 ?: 'null') . "\n";

	// Test 3: Limite à 0 (doit afficher tous)
	$reviews_count3 = '0';
	$limit_count3 = (!empty($reviews_count3) && is_numeric($reviews_count3) && $reviews_count3 > 0) ? intval($reviews_count3) : null;
	echo "Test 3 - Limite à 0: limit_count = " . ($limit_count3 ?: 'null (tous les avis)') . "\n";

	// Test 4: Valeur null
	$reviews_count4 = null;
	$limit_count4 = (!empty($reviews_count4) && is_numeric($reviews_count4) && $reviews_count4 > 0) ? intval($reviews_count4) : null;
	echo "Test 4 - Valeur null: limit_count = " . ($limit_count4 ?: 'null (tous les avis)') . "\n";

	// Test 5: Limite à 10
	$reviews_count5 = 10;
	$limit_count5 = (!empty($reviews_count5) && is_numeric($reviews_count5) && $reviews_count5 > 0) ? intval($reviews_count5) : null;
	echo "Test 5 - Limite à 10: limit_count = " . ($limit_count5 ?: 'null') . "\n";
}

// Exécuter les tests
test_google_reviews_logic();
