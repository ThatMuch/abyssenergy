# Bloc Fixed Jobs

## Description

Ce bloc affiche tous les postes fixes (post type "fixed-job") filtrés par catégorie d'emploi dans des onglets interactifs.

## Fonctionnalités

### Onglets dynamiques

- **Onglet "Tous"** : Affiche tous les postes fixes (optionnel)
- **Onglets par catégorie** : Un onglet pour chaque catégorie d'emploi (job-category) qui contient des postes
- **Compteur** : Chaque onglet affiche le nombre de postes dans cette catégorie
- **Navigation clavier** : Support des flèches et touches Entrée/Espace

### Affichage des postes

- **Grille responsive** : Adaptation automatique selon la taille d'écran
- **Image à la une** : Affichage de l'image du poste si disponible
- **Titre cliquable** : Lien vers la page détaillée du poste
- **Catégories** : Affichage des catégories d'emploi en badges
- **Extrait** : Description courte du poste (optionnel)
- **Bouton d'action** : Lien "Voir le poste" (optionnel)

### Chargement dynamique

- **Pagination AJAX** : Bouton "Charger plus" pour afficher plus de postes
- **Animation fluide** : Transition en fondu lors du chargement
- **Gestion d'erreur** : Messages d'erreur en cas de problème

## Configuration ACF

### Champs disponibles

1. **Titre** : Titre principal du bloc
2. **Sous-titre** : Texte d'introduction
3. **Description** : Description détaillée (optionnelle)
4. **Afficher l'onglet "Tous"** : Active/désactive l'onglet global
5. **Nombre de postes par page** : Limite d'affichage initial (1-20)
6. **Afficher l'extrait** : Active/désactive l'affichage des extraits
7. **Afficher le bouton candidater** : Active/désactive le bouton d'action

### Valeurs par défaut

- Titre : "Nos Postes Fixes"
- Sous-titre : "Opportunités de Carrière"
- Onglet "Tous" : Activé
- Postes par page : 6
- Extrait : Activé
- Bouton candidater : Activé

## Structure technique

### Post Type utilisé

- **fixed-job** : Type de contenu principal

### Taxonomie utilisée

- **job-category** : Catégories d'emploi pour le filtrage

### Fichiers

- `fixed-jobs-init.php` : Enregistrement du bloc et fonctions AJAX
- `block-fixed-jobs.php` : Template d'affichage
- `fixed-jobs.css` : Styles du bloc
- `fixed-jobs.js` : Interactions JavaScript

## Utilisation

### Dans l'éditeur Gutenberg

1. Ajouter le bloc "Fixed Jobs"
2. Configurer les options dans le panneau de droite
3. Prévisualiser le résultat

### Prérequis

- Post type "fixed-job" configuré
- Taxonomie "job-category" configurée
- Au moins un poste fixe publié avec une catégorie

## Responsive Design

### Desktop (>768px)

- Grille multi-colonnes
- Onglets horizontaux centrés
- Cartes avec effet de survol

### Tablet et Mobile (≤768px)

- Grille simple colonne
- Onglets défilables horizontalement
- Interface tactile optimisée

## Accessibilité

### Clavier

- Navigation avec Tab
- Onglets : Flèches gauche/droite
- Activation : Entrée ou Espace

### Screen readers

- Labels appropriés
- Structure sémantique
- États des onglets annoncés

## Performance

### Optimisations

- Chargement AJAX pour la pagination
- Cache des requêtes
- Images optimisées (format medium)
- Transitions CSS fluides

### Sécurité

- Nonce de vérification pour AJAX
- Sanitisation des données
- Échappement des sorties

## Personnalisation

### CSS

Toutes les classes sont préfixées `.fixed-jobs-` pour éviter les conflits.

### Couleurs principales

- Primaire : `#2c5d85` (bleu Abyss Energy)
- Accent : `#FF8E38` (orange)
- Neutre : `#6b7280` (gris)

### Points de rupture

- Mobile : ≤480px
- Tablet : ≤768px
- Desktop : >768px
