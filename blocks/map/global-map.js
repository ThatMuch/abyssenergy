(function($) {
    // Fonction d'initialisation de la carte SVG personnalisée
    function initGlobalMap(mapContainerId, popupId, mapData) {
        // Si l'élément DOM n'existe pas, sortir
        const mapContainer = document.getElementById(mapContainerId);
        if (!mapContainer) {
            console.error('Conteneur de carte non trouvé:', mapContainerId);
            return;
        }

        console.log('Initialisation de la carte:', mapContainerId);
        console.log('Chemin SVG:', mapData.svgPath);

        // Vérifier que abyss_map_params est défini
        if (typeof abyss_map_params === 'undefined') {
            console.error('abyss_map_params n\'est pas défini. Le script wp_localize_script n\'a pas été exécuté correctement.');
            mapContainer.innerHTML = '<p>Erreur: Configuration de la carte non chargée</p>';
            return;
        }

        const svgPath = mapData.svgPath || `${abyss_map_params.theme_url}/blocks/map/svg/world-map.svg`;
        console.log('Chemin SVG complet:', svgPath);

        // Charger la carte SVG dans le conteneur
        fetch(svgPath)
            .then(response => {
                console.log('Réponse SVG status:', response.status);
                if (!response.ok) {
                    throw new Error(`Erreur HTTP: ${response.status}`);
                }
                return response.text();
            })
            .then(svgContent => {
                console.log('SVG chargé, longueur:', svgContent.length);
                // Injecter le SVG dans le conteneur
                mapContainer.innerHTML = svgContent;

                // Obtenir l'élément SVG
                const svgElement = mapContainer.querySelector('svg');
                if (!svgElement) {
                    console.error('Carte SVG non trouvée dans le contenu chargé');
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
                console.error('Erreur lors du chargement de la carte SVG:', error);
                mapContainer.innerHTML = `<p>Erreur lors du chargement de la carte: ${error.message}</p>`;
            });
    }

    // Fonction pour ajouter les marqueurs à la carte SVG
    function addMarkers(svgElement, markers, popupId) {
        console.log('Ajout des marqueurs, nombre:', markers.length);

        // Obtenir les dimensions du SVG
        const svgWidth = svgElement.viewBox?.baseVal?.width || 1026; // Largeur du SVG actuel
        const svgHeight = svgElement.viewBox?.baseVal?.height || 505; // Hauteur du SVG actuel

        console.log('Dimensions SVG:', svgWidth, 'x', svgHeight);

        // Fonction pour convertir les coordonnées géographiques en coordonnées SVG
        function geoToSvgCoords(lat, lng) {
            // Cette formule considère que la carte est une projection Mercator simple
            // Ajustée spécifiquement pour le SVG de la carte du monde fournie

            // Coefficients d'ajustement pour cette carte SVG particulière
            const centerX = svgWidth / 2;
            const centerY = svgHeight / 2;
            const scale = svgWidth / 360; // échelle horizontale approximative

            // Facteurs de correction basés sur les retours d'utilisation
            // Décalage vers la gauche (-) pour corriger le déplacement vers la droite
            const offsetX = -15;
            // Décalage vers le bas (+) pour corriger le déplacement vers le haut
            const offsetY = +20;

            // Correction supplémentaire pour améliorer la précision aux différentes latitudes
            // La projection Mercator déforme les latitudes élevées
            const latFactor = 0.95; // Réduction légère de l'impact de la latitude

            // Conversion en coordonnées SVG avec ajustements
            let x = centerX + (lng * scale) + offsetX;
            // Pour y, nous inversons car les coordonnées SVG ont y=0 en haut
            let y = centerY - ((lat / 90) * (svgHeight / 2) * latFactor) + offsetY;

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
            console.log(`Traitement du marqueur ${index}:`, marker.project_name);

            // Vérifier si les coordonnées sont valides
            if (!marker.lat || !marker.lng) {
                console.warn(`Marqueur ${index} sans coordonnées valides:`, marker);
                return; // Passer au marqueur suivant
            }

            // Convertir les coordonnées géographiques en coordonnées SVG
            const svgCoords = geoToSvgCoords(parseFloat(marker.lat), parseFloat(marker.lng));
            console.log(`Coordonnées du marqueur ${index}:`, marker.lat, marker.lng, '->', svgCoords.x, svgCoords.y);

            // Créer un cercle pour le marqueur
            const pinElement = document.createElementNS("http://www.w3.org/2000/svg", "circle");
            pinElement.setAttribute('cx', svgCoords.x);
            pinElement.setAttribute('cy', svgCoords.y);
            pinElement.setAttribute('r', '8');
                pinElement.setAttribute('fill', '#F70');
                pinElement.setAttribute('stroke', '#fff');
                pinElement.setAttribute('stroke-width', '2');
                pinElement.setAttribute('class', 'map-marker');
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

                    if (marker.description) {
                        popupContent += `
                            <div class="map-popup-description">
                                ${marker.description}
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
