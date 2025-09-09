<?php
// Test pour vérifier la logique de user_ratings_total

// Simulation des données de l'API Google
$mock_api_response = array(
	'result' => array(
		'name' => 'Abyss Energy',
		'rating' => 4.8,
		'user_ratings_total' => 21, // Le vrai nombre total d'avis
		'reviews' => array(
			// Seulement 5 avis retournés par l'API
			array('author_name' => 'Client 1', 'rating' => 5, 'text' => 'Excellent service!'),
			array('author_name' => 'Client 2', 'rating' => 4, 'text' => 'Très bon travail.'),
			array('author_name' => 'Client 3', 'rating' => 5, 'text' => 'Je recommande!'),
			array('author_name' => 'Client 4', 'rating' => 5, 'text' => 'Parfait!'),
			array('author_name' => 'Client 5', 'rating' => 4, 'text' => 'Bien fait.'),
		)
	)
);

// Test de notre logique
$place_data = array(
	'error' => false,
	'name' => $mock_api_response['result']['name'],
	'rating' => $mock_api_response['result']['rating'],
	'user_ratings_total' => $mock_api_response['result']['user_ratings_total'],
	'reviews' => $mock_api_response['result']['reviews']
);

echo "=== Test user_ratings_total ===\n";
echo "Nom de l'entreprise: " . $place_data['name'] . "\n";
echo "Note moyenne: " . $place_data['rating'] . "\n";
echo "Nombre total d'avis sur Google: " . $place_data['user_ratings_total'] . "\n";
echo "Nombre d'avis récupérés par l'API: " . count($place_data['reviews']) . "\n\n";

// Test du message
$user_ratings_total = $place_data['user_ratings_total'];
echo "Message affiché: 'Basé sur $user_ratings_total avis'\n";
echo "Message admin: 'Affichage de 5 avis sur $user_ratings_total total sur Google'\n";
