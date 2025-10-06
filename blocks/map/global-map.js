(function($) {
    // Fonction d'initialisation de la carte SVG personnalisée
    function initGlobalMap(mapContainerId, tooltipId, mapData) {
        // Si l'élément DOM n'existe pas, sortir
        const mapContainer = document.getElementById(mapContainerId);
        if (!mapContainer) {
            return;
        }



        // Vérifier que abyss_map_params est défini
        if (typeof abyss_map_params === 'undefined') {
            mapContainer.innerHTML = '<p>Erreur: Configuration de la carte non chargée</p>';
            return;
        }

        const svgPath = mapData.svgPath || `${abyss_map_params.theme_url}/blocks/map/svg/world-map.svg`;

        // Charger la carte SVG dans le conteneur
        fetch(svgPath)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }
                return response.text();
            })
            .then(svgContent => {
                // Injecter le SVG dans le conteneur
                mapContainer.innerHTML = svgContent;

                // Obtenir l'élément SVG
                const svgElement = mapContainer.querySelector('svg');
                if (!svgElement) {
                    mapContainer.innerHTML = `<p>Erreur: Le fichier SVG ne contient pas d'élément SVG valide</p>`;
                    return;
                }

                // Configurer la carte SVG pour qu'elle soit responsive
                svgElement.setAttribute('width', '100%');
                svgElement.setAttribute('height', 'auto');
                svgElement.style.maxWidth = '100%';

                // Ajouter un fond transparent par défaut (si nécessaire)
                if (!svgElement.getAttribute('background')) {
                    svgElement.style.background = 'transparent';
                }

                // Désactiver le drag et le sélection sur le SVG
                svgElement.style.userSelect = 'none';
                svgElement.style.webkitUserSelect = 'none';
                svgElement.style.msUserSelect = 'none';
                svgElement.setAttribute('draggable', 'false');

                // Empêcher les événements de drag sur l'ensemble du SVG
                svgElement.addEventListener('dragstart', (e) => e.preventDefault());
                svgElement.addEventListener('drag', (e) => e.preventDefault());
                svgElement.addEventListener('dragend', (e) => e.preventDefault());

                // Ajouter les marqueurs
                if (mapData.markers && mapData.markers.length > 0) {
                    addMarkers(svgElement, mapData.markers, tooltipId);
                }
            })
            .catch(error => {
                mapContainer.innerHTML = `<p>Erreur lors du chargement de la carte: ${error.message}</p>`;
            });
    }

    // Fonction pour ajouter les marqueurs à la carte SVG
    function addMarkers(svgElement, markers, tooltipId) {
        // Obtenir les dimensions du SVG
        const svgWidth = svgElement.viewBox?.baseVal?.width || 1026; // Largeur du SVG actuel
        const svgHeight = svgElement.viewBox?.baseVal?.height || 505; // Hauteur du SVG actuel

        // Variable pour suivre si l'animation a été déclenchée
        let animationTriggered = false;

        // Fonction pour convertir les coordonnées géographiques en coordonnées SVG
        function geoToSvgCoords(lat, lng) {
            // Cette fonction utilise maintenant l'équateur comme référence pour un meilleur placement

            // Coordonnées du centre de la carte (le méridien de Greenwich à l'équateur)
            // Ces valeurs sont basées sur l'analyse du SVG avec la ligne d'équateur
            const equatorY = svgHeight * 0.62; // Position Y de l'équateur dans le SVG (60% de la hauteur depuis le haut)
            const centerX = svgWidth / 2; // Le méridien de Greenwich est au centre horizontal

            // Facteurs d'échelle pour la conversion (ajustés selon les proportions du SVG)
            const horizontalScale = svgWidth / 360; // Une unité de longitude = x pixels horizontalement

            // Calcul des échelles verticales en tenant compte de la position asymétrique de l'équateur
            // L'hémisphère nord dispose de 60% de la hauteur du SVG, l'hémisphère sud de 40%
            const northVerticalScale = (equatorY) / 90; // Échelle pour les latitudes nord (de l'équateur au pôle nord)
            const southVerticalScale = (svgHeight - equatorY) / 90; // Échelle pour les latitudes sud (de l'équateur au pôle sud)

            // Facteurs de correction pour mieux correspondre à la projection du SVG
			const offsetX = -13; // Ajustement horizontal global

            // Correction pour la déformation de la projection Mercator aux latitudes élevées
            // Plus on s'éloigne de l'équateur, plus la déformation augmente
            const northLatFactor = 0.95; // Facteur de correction pour les hautes latitudes nord
            const southLatFactor = 0.120; // Facteur de correction pour les hautes latitudes sud

            // Conversion de la longitude en position X
            let x = centerX + (lng * horizontalScale) + offsetX;

            // Conversion de la latitude en position Y en utilisant des échelles et facteurs différents selon l'hémisphère
            let y;
            if (lat >= 0) {
                // Hémisphère nord (latitudes positives)
                // Utilise l'échelle nord et applique une correction progressive avec la latitude
                // (plus on s'approche du pôle nord, plus la correction est importante)
                const correctionFactor = 1 - ((1 - northLatFactor) * (lat / 90));
                y = equatorY - (lat * northVerticalScale * correctionFactor);
            } else {
                // Hémisphère sud (latitudes négatives)
                // Utilise l'échelle sud et applique une correction progressive avec la latitude
                // (plus on s'approche du pôle sud, plus la correction est importante)
                const absLat = Math.abs(lat);
				const correctionFactor = 1 - ((1 - southLatFactor) * (absLat / 90));
                y = equatorY + (absLat * southVerticalScale * correctionFactor);
            }

            // Garder le marqueur dans les limites de la carte
            x = Math.max(20, Math.min(svgWidth - 20, x));
            y = Math.max(20, Math.min(svgHeight - 20, y));

            return { x, y };
        }

        // Création d'une échelle visuelle de marqueurs pour déboguer (optionnel)
        // Cette grille peut aider à visualiser les positions sur la carte
        const debugGrid = false; // Mettre à true pour afficher la grille de débogage
        if (debugGrid) {
            const gridStep = 100;
            for (let x = gridStep; x < svgWidth; x += gridStep) {
                for (let y = gridStep; y < svgHeight; y += gridStep) {
                    const debugMarker = document.createElementNS("http://www.w3.org/2000/svg", "circle");
                    debugMarker.setAttribute('cx', x);
                    debugMarker.setAttribute('cy', y);
                    debugMarker.setAttribute('r', '3');
                    debugMarker.setAttribute('fill', 'rgba(0,0,255,0.3)');
                    debugMarker.setAttribute('class', 'debug-marker');
                    svgElement.appendChild(debugMarker);

                    // Ajouter des coordonnées visibles
                    const debugText = document.createElementNS("http://www.w3.org/2000/svg", "text");
                    debugText.setAttribute('x', x + 5);
                    debugText.setAttribute('y', y - 5);
                    debugText.setAttribute('font-size', '8px');
                    debugText.setAttribute('fill', 'rgba(0,0,0,0.5)');
                    debugText.textContent = `${x},${y}`;
                    svgElement.appendChild(debugText);
                }
            }
        }

        // Fonction utilitaire pour supprimer toutes les classes qui commencent par "sector"
        function removeSectorClasses(element) {
            const classesToRemove = [];
            element.classList.forEach(className => {
                if (className.startsWith('sector-')) {
                    classesToRemove.push(className);
                }
            });
            classesToRemove.forEach(className => {
                element.classList.remove(className);
            });
        }

        // Tableau pour stocker les éléments marqueurs
        const markerElements = [];

        // Parcourir les marqueurs à afficher
        markers.forEach((marker, index) => {
            // Vérifier si les coordonnées sont valides
            if (!marker.lat || !marker.lng) {
                return; // Passer au marqueur suivant
            }

            // Convertir les coordonnées géographiques en coordonnées SVG
            const svgCoords = geoToSvgCoords(parseFloat(marker.lat), parseFloat(marker.lng));
			// Couleurs pour chaque secteur
			const sectorColors = {
        process: "#06508bff",
        renewable: "#008e99ff",
        conventional: "#F70",
      };
            // Créer un cercle pour le marqueur
            const pinElement = document.createElementNS("http://www.w3.org/2000/svg", "circle");
            pinElement.setAttribute('cx', svgCoords.x);
            pinElement.setAttribute('cy', svgCoords.y);
            pinElement.setAttribute('r', '8');
            pinElement.setAttribute('fill', sectorColors[marker.sector.value] || '#F70');
            pinElement.setAttribute('stroke', '#fff');
            pinElement.setAttribute('stroke-width', '2');
            pinElement.setAttribute('class','map-marker');
            pinElement.setAttribute('pointer-events', 'all');
            pinElement.style.cursor = 'pointer';
            pinElement.style.position = 'static';

            // S'assurer que le marqueur ne bouge pas et ajouter l'animation fade
            pinElement.style.transform = 'none';
            pinElement.style.transition = 'transform 0.3s ease, filter 0.3s ease, opacity 0.6s ease';

            // Commencer invisible pour l'animation fade-in
            pinElement.style.opacity = '0';

            // Obtenir l'élément tooltip
            const tooltip = document.getElementById(tooltipId);
            if (!tooltip) return;

            // Ajouter un attribut data pour lier le tooltip à son marqueur
            tooltip.setAttribute('data-marker-index', index);

            const tooltipContent = tooltip.querySelector('.tooltip-content');
            const tooltipArrow = tooltip.querySelector('.tooltip-arrow');

            // Empêcher tout comportement de suivi du curseur
            pinElement.addEventListener('mousemove', (e) => {
                e.stopPropagation();
                e.preventDefault();
            });

            // Gérer l'affichage du tooltip au clic
            pinElement.addEventListener('click', (e) => {
                e.stopPropagation(); // Empêcher la propagation du clic
                e.preventDefault(); // Empêcher le comportement par défaut
				//add the class to the .global-map-tooltip
                // Fermer tous les autres tooltips ouverts et supprimer classe active des marqueurs
                document.querySelectorAll('.global-map-tooltip.active').forEach((t) => {
                    if (t !== tooltip) {
                        t.classList.remove('active');
                        // Supprimer toutes les classes de secteur
                        removeSectorClasses(t);
                    }
                });
                document.querySelectorAll('.map-marker.active').forEach(m => {
                    if (m !== pinElement) {
                        m.classList.remove('active');
                    }
                });

                // Si le tooltip est déjà ouvert, le fermer
                if (tooltip.classList.contains('active')) {
                    tooltip.classList.remove('active');
                    // Supprimer toutes les classes de secteur
                    removeSectorClasses(tooltip);
                    pinElement.classList.remove('active');
                    return;
                }

                // Construire le contenu du tooltip
                let content = `
                    <div class="tooltip-header">
                        <h4 class="tooltip-sector">${marker.sector.label}</h4>
                    </div>
					<div class="tooltip-body">
					<p class="tooltip-title">Projet</p>
					        ${marker.project_name}
					</div>
                `;

                if (marker.country) {
                  content += `
                        <div class="tooltip-country">
                            <i class="fa fa-map-marker-alt mr-2"></i> ${marker.country}
                        </div>
                    `;
                }

                tooltipContent.innerHTML = content;

                // Positionner le tooltip
                positionTooltip(e.target, tooltip, tooltipArrow);

                // Afficher le tooltip et marquer le marqueur comme actif
                tooltip.classList.add('active');
                if (marker.sector && marker.sector.value) {
                    tooltip.classList.add(`sector-${marker.sector.value}`);
                }
                pinElement.classList.add('active');

                // Gérer le bouton de fermeture
                const closeBtn = tooltip.querySelector('.tooltip-close');
                if (closeBtn) {
                    closeBtn.onclick = (e) => {
                        e.stopPropagation();
                        tooltip.classList.remove('active');
                        // Supprimer toutes les classes de secteur
                        removeSectorClasses(tooltip);
                        pinElement.classList.remove('active');
                    };
                }
            });

                // Ajouter le marqueur à la carte
                svgElement.appendChild(pinElement);

                // Stocker le marqueur pour l'animation
                markerElements.push(pinElement);
            }
        );

        /**
         * Animation des marqueurs en fade-in séquentiel
         */
        function animateMarkers() {
            if (animationTriggered || markerElements.length === 0) return;
            animationTriggered = true;

            markerElements.forEach((marker, index) => {
                setTimeout(() => {
                    marker.style.opacity = '1';
                }, index * 50); // Délai de 150ms entre chaque marqueur
            });
        }

        /**
         * Observer d'intersection pour déclencher l'animation quand la carte est visible
         */
        const mapContainer = svgElement.closest('.global-map-wrapper') || svgElement.closest('.abyss-global-map');
        if (mapContainer) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !animationTriggered) {
                        animateMarkers();
                    }
                });
            }, {
                threshold: 0.2 // Déclencher quand 20% du bloc est visible
            });

            observer.observe(mapContainer);
        }

        // Fermer les tooltips quand on clique ailleurs
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.global-map-tooltip') && !e.target.classList.contains('map-marker')) {
                document.querySelectorAll('.global-map-tooltip.active').forEach((tooltip) => {
                    tooltip.classList.remove('active');
                    // Supprimer toutes les classes de secteur
                    removeSectorClasses(tooltip);
                });
                document.querySelectorAll('.map-marker.active').forEach(marker => {
                    marker.classList.remove('active');
                });
            }
        });
    }

    // Fonction pour positionner intelligemment le tooltip
    function positionTooltip(markerElement, tooltip, arrow) {
        const markerRect = markerElement.getBoundingClientRect();
        const mapContainer = markerElement.closest('.global-map-wrapper');
        const mapRect = mapContainer.getBoundingClientRect();

        // Position par défaut : AU-DESSUS du marqueur
        let tooltipX = markerRect.left - mapRect.left + (markerRect.width / 2);
        let tooltipY = markerRect.top - mapRect.top - tooltip.offsetHeight - 10;

        // Réinitialiser les classes de flèche
        arrow.className = 'tooltip-arrow';

        // Positionner le tooltip au-dessus par défaut
        tooltip.style.left = tooltipX + 'px';
        tooltip.style.top = tooltipY + 'px';
        tooltip.style.transform = 'translateX(-50%)';

        // Vérifier si le tooltip dépasse à droite
        const tooltipRect = tooltip.getBoundingClientRect();
        const mapRightEdge = mapRect.right;
        const tooltipRightEdge = tooltipRect.right;

        // Vérifier si le tooltip dépasse vers le haut
        if (tooltipRect.top < mapRect.top) {
            // Repositionner EN BAS du marqueur si pas de place au-dessus
            tooltipY = markerRect.top - mapRect.top + markerRect.height + 10;
            tooltip.style.top = tooltipY + 'px';
            arrow.classList.add('arrow-top'); // Flèche vers le haut quand tooltip en bas
        } else if (tooltipRightEdge > mapRightEdge) {
            // Positionner à gauche du marqueur
            tooltipX = markerRect.left - mapRect.left - tooltip.offsetWidth - 10;
            tooltipY = markerRect.top - mapRect.top + (markerRect.height / 2);
            tooltip.style.left = tooltipX + 'px';
            tooltip.style.top = tooltipY + 'px';
            tooltip.style.transform = 'translateY(-50%)';
            arrow.classList.add('arrow-right');
        } else if (tooltipRect.left < mapRect.left) {
            // Positionner à droite du marqueur
            tooltipX = markerRect.left - mapRect.left + markerRect.width + 10;
            tooltipY = markerRect.top - mapRect.top + (markerRect.height / 2);
            tooltip.style.left = tooltipX + 'px';
            tooltip.style.top = tooltipY + 'px';
            tooltip.style.transform = 'translateY(-50%)';
            arrow.classList.add('arrow-left');
        }
    }

    /**
     * Gestion des modales pour le contenu détaillé de la carte
     */
    function initModalHandlers() {
        let scrollPosition = 0;

        /**
         * Sauvegarde la position de scroll actuelle
         */
        function saveScrollPosition() {
            scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
        }

        /**
         * Restaure la position de scroll sauvegardée
         */
        function restoreScrollPosition() {
            window.scrollTo(0, scrollPosition);
        }

        /**
         * Affiche le modal
         */
        function showModal(modal) {
            // Sauvegarder la position avant d'ouvrir le modal
            saveScrollPosition();
            document.body.style.top = `-${scrollPosition}px`;

            modal.classList.add('active');
            document.body.classList.add('modal-open');

            // Focus sur le bouton fermer pour l'accessibilité
            const closeButton = modal.querySelector('.modal-close');
            if (closeButton) {
                closeButton.focus();
            }
        }

        /**
         * Cache le modal
         */
        function hideModal(modal) {
            modal.classList.remove('active');
            document.body.classList.remove('modal-open');

            // Restaurer la position après avoir fermé le modal
            document.body.style.top = '';
            restoreScrollPosition();
        }

        // Gérer tous les boutons "See more" des cartes globales
        const mapButtons = document.querySelectorAll('.global-map-button');

        mapButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Trouver le bloc parent pour identifier la modale correspondante
                const mapBlock = button.closest('.abyss-global-map');
                if (!mapBlock) return;

                // Construire l'ID de la modale à partir de l'ID du bloc
                const blockId = mapBlock.id;
                const modalId = blockId + '-modal';
                const modal = document.getElementById(modalId);

                if (modal) {
                    showModal(modal);
                }
            });
        });

        // Gérer la fermeture des modales
        const modals = document.querySelectorAll('.global-map-modal');

        modals.forEach(modal => {
            // Fermer en cliquant sur le backdrop
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideModal(modal);
                }
            });

            // Fermer avec le bouton X
            const closeButton = modal.querySelector('.modal-close');
            if (closeButton) {
                closeButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    hideModal(modal);
                });
            }
        });

        // Fermer avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const activeModal = document.querySelector('.global-map-modal.active');
                if (activeModal) {
                    hideModal(activeModal);
                }
            }
        });
    }

    // Initialiser toutes les cartes sur la page
    $(document).ready(function() {
        // Rechercher toutes les variables de carte injectées
        const vars = Object.keys(window).filter(function(key) {
            return key.startsWith('mapData_') && key.includes('abyss_global_map');
        });

        // Initialiser chaque carte
        vars.forEach(function(varName) {
            const idParts = varName.split('_');
            idParts.shift(); // Enlever "mapData_"

            const mapVarName = 'mapId_' + idParts.join('_');
            const tooltipVarName = 'tooltipId_' + idParts.join('_');

            if (window[mapVarName] && window[tooltipVarName] && window[varName]) {
                initGlobalMap(window[mapVarName], window[tooltipVarName], window[varName]);
            }
        });

        // Initialiser les gestionnaires de modale
        initModalHandlers();
    });
})(jQuery);
