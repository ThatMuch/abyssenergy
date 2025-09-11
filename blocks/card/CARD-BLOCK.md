# Documentation du Bloc Carte Simple

## Description

Le bloc **Carte Simple** est un bloc Gutenberg personnalisé qui permet d'afficher une carte avec une image, un titre et une description. Il est inspiré du design des cartes métriques du site.

## Fonctionnalités

### Champs disponibles

1. **Image** (optionnelle)

   - Type: Image média
   - Format de retour: Array
   - Taille d'aperçu: Medium

2. **Titre** (requis)

   - Type: Texte
   - Valeur par défaut: "Titre de la carte"

3. **Description** (optionnelle)

   - Type: Textarea
   - 4 lignes
   - Support des sauts de ligne

4. **Lien** (optionnel)

   - Type: Link
   - Format de retour: Array
   - Permet de rendre la carte cliquable

5. **Style de carte** (optionnel)
   - Type: Select
   - Options:
     - `default`: Style par défaut
     - `compact`: Style compact (horizontal)
     - `featured`: Style mise en avant (fond dégradé)

## Utilisation

### Dans l'éditeur Gutenberg

1. Cliquer sur "+" pour ajouter un nouveau bloc
2. Rechercher "Carte Simple" ou naviguer dans la catégorie "Abyss Blocks"
3. Remplir les champs souhaités
4. Choisir le style de carte approprié

### Styles disponibles

#### Style par défaut (`default`)

- Layout vertical avec image en haut
- Fond blanc avec ombre
- Titre en bleu foncé (#0a3f6a)
- Description en gris
- Indicateur de lien en orange (#FF8E38)

#### Style compact (`compact`)

- Layout horizontal sur desktop
- Image à gauche, contenu à droite
- Largeur maximale de 600px
- Responsive: revient en vertical sur mobile

#### Style mise en avant (`featured`)

- Fond dégradé bleu
- Texte en blanc
- Image avec overlay semi-transparent
- Indicateur de lien toujours en orange

## Responsive Design

### Desktop (> 768px)

- Style compact: image à gauche (200px), contenu à droite
- Hauteur d'image: 200px par défaut

### Tablet (≤ 768px)

- Style compact revient en vertical
- Padding réduit dans le contenu
- Taille de titre ajustée

### Mobile (≤ 480px)

- Hauteur d'image réduite à 180px
- Gaps et padding optimisés
- Taille de police ajustée

## Alignements Gutenberg

- **Alignement normal**: Largeur par défaut
- **Alignement large**: Largeur maximale de 1200px
- **Alignement pleine largeur**: 100% de la largeur d'écran

## Animations et Interactions

### Effets de survol

- Translation verticale (-2px)
- Ombre renforcée
- Zoom de l'image (scale 1.05)
- Animation de la flèche du lien

### Transitions

- Duration: 0.3s
- Easing: cubic-bezier(0.4, 0, 0.2, 1)

### Accessibilité

- Focus outline en orange
- Support du clavier
- Alt text pour les images
- Liens sémantiques

## Structure HTML

```html
<div class="card-block card-{style}">
  <a class="card-wrapper" href="..." target="..." title="...">
    <div class="card-image">
      <img src="..." alt="..." loading="lazy" />
    </div>
    <div class="card-content">
      <h3 class="card-title">...</h3>
      <div class="card-description">...</div>
      <div class="card-link-indicator">
        <span class="card-link-text">...</span>
        <svg class="card-link-arrow">...</svg>
      </div>
    </div>
  </a>
</div>
```

## Classes CSS principales

- `.card-block`: Container principal
- `.card-wrapper`: Wrapper de la carte (peut être `<a>` ou `<div>`)
- `.card-image`: Container de l'image
- `.card-content`: Container du contenu textuel
- `.card-title`: Titre de la carte
- `.card-description`: Description de la carte
- `.card-link-indicator`: Indicateur de lien avec flèche

## Fichiers

- `card-init.php`: Enregistrement du bloc et des champs ACF
- `block-card.php`: Template de rendu du bloc
- `card.css`: Styles CSS du bloc

## Compatibilité

- WordPress 5.0+
- Advanced Custom Fields Pro
- Navigateurs modernes (support CSS Grid et Flexbox)

## Exemple d'utilisation

```php
// Dans un template PHP
if (have_rows('cards_repeater')):
    while (have_rows('cards_repeater')): the_row();
        // Les champs sont automatiquement disponibles
        // via get_field() dans le contexte du bloc
    endwhile;
endif;
```
