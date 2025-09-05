(function($) {
    // Fonction d'initialisation de la carte SVG personnalisée
    function initGlobalMap(mapContainerId, popupId, mapData) {
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

                // Ajouter les marqueurs
                if (mapData.markers && mapData.markers.length > 0) {
                    addMarkers(svgElement, mapData.markers, popupId);
                }
            })
            .catch(error => {
                mapContainer.innerHTML = `<p>Erreur lors du chargement de la carte: ${error.message}</p>`;
            });
    }

    // Fonction pour ajouter les marqueurs à la carte SVG
    function addMarkers(svgElement, markers, popupId) {
        // Obtenir les dimensions du SVG
        const svgWidth = svgElement.viewBox?.baseVal?.width || 1026; // Largeur du SVG actuel
        const svgHeight = svgElement.viewBox?.baseVal?.height || 505; // Hauteur du SVG actuel

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
            const offsetY = 0; // Pas d'ajustement vertical depuis que nous utilisons l'équateur comme référence

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
        renewables: "#008e99ff",
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
            pinElement.style.cursor = 'pointer';

                // Afficher le popup au clic
                pinElement.addEventListener('click', () => {
                    // Construire le contenu du popup
                    let popupContent = `
                        <div class="map-popup-header">
                            <h3>${marker.project_name}</h3>
                            <p class="map-popup-country">${marker.country}</p>
                        </div>
                    `;
					if (marker.sector) {

                        popupContent += `
                            <div class="map-popup-sector">
                                Secteur: ${marker.sector.label}
                            </div>
                        `;
                    }

                    // Afficher le popup personnalisé
                    const popup = document.getElementById(popupId);
                    if (popup) {
                        const popupContentDiv = popup.querySelector('.global-map-popup-content');
                        popupContentDiv.innerHTML = popupContent;
                        popup.classList.add('active');

                        // Gérer la fermeture du popup
                        const closeBtn = popup.querySelector('.global-map-popup-close');
                        if (closeBtn) {
                            closeBtn.addEventListener('click', function() {
                                popup.classList.remove('active');
                            });
                        }
                    }
                });

                // Ajouter le marqueur à la carte
                svgElement.appendChild(pinElement);
            }
        );
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
            const popupVarName = 'popupId_' + idParts.join('_');

            if (window[mapVarName] && window[popupVarName] && window[varName]) {
                initGlobalMap(window[mapVarName], window[popupVarName], window[varName]);
            }
        });
    });
})(jQuery);
