<?php

/**
 * Test du thème enfant squarechilli-child
 *
 * Ce fichier peut être utilisé pour tester que le thème enfant fonctionne correctement.
 * Placez ce code dans un template WordPress pour voir les styles appliqués.
 */

// Vérifier que nous sommes dans WordPress
if (!defined('ABSPATH')) {
	exit('Accès direct interdit');
}

get_header(); ?>

<div class="container mt-5">
	<div class="alert alert--primary">
		<strong>Test du thème enfant :</strong> Si vous voyez ce message stylisé, le thème enfant SCSS fonctionne correctement !
	</div>

	<div class="row">
		<div class="col-md-6">
			<div class="card">
				<div class="card__header">
					<h3 class="card__title">Test des composants</h3>
				</div>
				<div class="card__content">
					<p>Cette carte utilise les styles SCSS compilés du thème enfant.</p>
					<div class="mb-3">
						<span class="badge badge--primary">SCSS</span>
						<span class="badge badge--success">Fonctionnel</span>
					</div>
				</div>
				<div class="card__footer">
					<button class="btn btn--primary mr-2">Bouton principal</button>
					<button class="btn btn--outline">Bouton outline</button>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="card">
				<div class="card__header">
					<h3 class="card__title">Classes utilitaires</h3>
				</div>
				<div class="card__content">
					<p class="text-orange">Texte orange</p>
					<p class="text-blue">Texte bleu</p>
					<div class="d-flex justify-content-between align-items-center bg-light p-3">
						<span>Flex layout</span>
						<span class="spinner"></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
