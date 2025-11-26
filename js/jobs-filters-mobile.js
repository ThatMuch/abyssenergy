/**
 * Mobile Filters Toggle for Jobs Page
 */

(function ($) {
	'use strict';

	$(document).ready(function () {
		console.log('Mobile filters script loaded');

		const $body = $('body');

		// Ouvrir les filtres
		function openFilters() {
			const $filtersSidebar = $('.jobs-filters-sidebar');
			const $filtersOverlay = $('.filters-overlay');
			const $filterToggle = $('.filters-toggle');

			$filtersSidebar.addClass('active');
			$filtersOverlay.addClass('active');
			$body.addClass('filters-open');
			$filterToggle.attr('aria-expanded', 'true');
		}

		// Fermer les filtres
		function closeFilters() {
			const $filtersSidebar = $('.jobs-filters-sidebar');
			const $filtersOverlay = $('.filters-overlay');
			const $filterToggle = $('.filters-toggle');

			$filtersSidebar.removeClass('active');
			$filtersOverlay.removeClass('active');
			$body.removeClass('filters-open');
			$filterToggle.attr('aria-expanded', 'false');
		}

		// Click sur le bouton toggle (délégation d'événement)
		$(document).on('click', '.filters-toggle', function (e) {
			e.preventDefault();
			const $filtersSidebar = $('.jobs-filters-sidebar');

			if ($filtersSidebar.hasClass('active')) {
				closeFilters();
			} else {
				openFilters();
			}
		});

		// Click sur le bouton fermer (délégation d'événement)
		$(document).on('click', '.filters-close', function (e) {
			e.preventDefault();
			closeFilters();
		});

		// Click sur l'overlay (délégation d'événement)
		$(document).on('click', '.filters-overlay', function () {
			closeFilters();
		});

		// Fermer avec la touche Escape
		$(document).on('keydown', function (e) {
			const $filtersSidebar = $('.jobs-filters-sidebar');
			if (e.key === 'Escape' && $filtersSidebar.hasClass('active')) {
				closeFilters();
			}
		});

		// Mettre à jour le compteur de filtres actifs
		function updateFilterCount() {
			const $searchFilter = $('.searchandfilter');
			const $filterToggle = $('.filters-toggle');

			if ($searchFilter.length) {
				// Compter les filtres sélectionnés (inputs, selects avec valeur)
				let activeCount = 0;

				// Compter les selects avec valeur
				$searchFilter.find('select').each(function () {
					if ($(this).val() && $(this).val() !== '') {
						activeCount++;
					}
				});

				// Compter les inputs texte avec valeur
				$searchFilter.find('input[type="text"]').each(function () {
					if ($(this).val() && $(this).val().trim() !== '') {
						activeCount++;
					}
				});

				// Compter les checkboxes cochées
				activeCount += $searchFilter.find('input[type="checkbox"]:checked').length;

				// Compter les radio boutons cochés (exclure les valeurs par défaut)
				$searchFilter.find('input[type="radio"]:checked').each(function () {
					if ($(this).val() && $(this).val() !== '') {
						activeCount++;
					}
				});

				// Mettre à jour l'affichage du compteur
				const $filterCount = $('.filter-count');
				if (activeCount > 0) {
					$filterCount.text('(' + activeCount + ')').show();
					$filterToggle.addClass('has-filters');
				} else {
					$filterCount.text('').hide();
					$filterToggle.removeClass('has-filters');
				}
			}
		}

		// Mettre à jour le compteur au chargement
		updateFilterCount();

		// Mettre à jour le compteur quand les filtres changent
		$(document).on('change', '.searchandfilter input, .searchandfilter select', function () {
			updateFilterCount();
		});

		// Fermer les filtres après soumission (optionnel)
		$(document).on('submit', '.searchandfilter form', function () {
			setTimeout(function () {
				closeFilters();
			}, 300);
		});

		// Si Ajax est activé, fermer après le filtrage
		$(document).on('sf:ajaxfinish', '.searchandfilter', function () {
			// closeFilters(); // Peut-être trop agressif si l'utilisateur veut affiner
			updateFilterCount();
		});
	});

})(jQuery);
