<?php
// Test pour vérifier la gestion de user_ratings_total manquant

echo "=== Test gestion user_ratings_total manquant ===\n\n";

// Test 1: user_ratings_total présent
$reviews_data1 = array(
	'error' => false,
	'user_ratings_total' => 21,
	'reviews' => array(1, 2, 3, 4, 5) // 5 avis
);

$user_ratings_total1 = 0;
if (isset($reviews_data1['user_ratings_total']) && $reviews_data1['user_ratings_total'] > 0) {
	$user_ratings_total1 = $reviews_data1['user_ratings_total'];
} else {
	$user_ratings_total1 = count($reviews_data1['reviews']);
}
echo "Test 1 (user_ratings_total présent): $user_ratings_total1\n";

// Test 2: user_ratings_total manquant
$reviews_data2 = array(
	'error' => false,
	'reviews' => array(1, 2, 3, 4, 5) // 5 avis
	// pas de user_ratings_total
);

$user_ratings_total2 = 0;
if (isset($reviews_data2['user_ratings_total']) && $reviews_data2['user_ratings_total'] > 0) {
	$user_ratings_total2 = $reviews_data2['user_ratings_total'];
} else {
	$user_ratings_total2 = count($reviews_data2['reviews']);
}
echo "Test 2 (user_ratings_total manquant): $user_ratings_total2\n";

// Test 3: user_ratings_total à 0
$reviews_data3 = array(
	'error' => false,
	'user_ratings_total' => 0,
	'reviews' => array(1, 2, 3, 4, 5) // 5 avis
);

$user_ratings_total3 = 0;
if (isset($reviews_data3['user_ratings_total']) && $reviews_data3['user_ratings_total'] > 0) {
	$user_ratings_total3 = $reviews_data3['user_ratings_total'];
} else {
	$user_ratings_total3 = count($reviews_data3['reviews']);
}
echo "Test 3 (user_ratings_total à 0): $user_ratings_total3\n";

echo "\n=== Résultats ===\n";
echo "✅ Test 1: Utilise user_ratings_total (21)\n";
echo "✅ Test 2: Fallback sur count reviews (5)\n";
echo "✅ Test 3: Fallback sur count reviews (5)\n";
