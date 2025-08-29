# Système de grille responsif

Ce document explique comment utiliser le système de grille personnalisé, similaire à celui de Bootstrap, dans le thème Abyss Energy.

## Structure de base

Le système utilise une structure à 12 colonnes et inclut des points d'arrêt responsifs cohérents avec ceux du mixin `respond-to` existant :

- `xs` : < 576px (mobile)
- `sm` : ≥ 576px
- `md` : ≥ 768px (tablette)
- `lg` : ≥ 1024px
- `xl` : ≥ 1280px
- `xxl` : ≥ 1440px

## Conteneurs

Deux types de conteneurs sont disponibles :

```html
<div class="container">
  <!-- Contenu avec largeur maximale à chaque breakpoint -->
</div>

<div class="container-fluid">
  <!-- Contenu occupant toute la largeur disponible -->
</div>
```

## Lignes et colonnes

Structure de base :

```html
<div class="container">
  <div class="row">
    <div class="col-md-6">Colonne de 50% sur tablette et plus</div>
    <div class="col-md-6">Colonne de 50% sur tablette et plus</div>
  </div>
</div>
```

## Classes de colonnes

Les classes suivent le format : `col-{breakpoint}-{taille}` où la taille va de 1 à 12.

Exemples :

- `col-12` : Occupe 100% de la largeur sur tous les écrans
- `col-sm-6` : Occupe 50% de la largeur sur les écrans ≥ 576px
- `col-md-4` : Occupe 33.33% de la largeur sur les écrans ≥ 768px
- `col-lg-3` : Occupe 25% de la largeur sur les écrans ≥ 1024px

## Alignement et ordre

### Alignement horizontal

```html
<div class="row justify-content-center">
  <!-- Contenu centré horizontalement -->
</div>
```

Options disponibles :

- `justify-content-start`
- `justify-content-end`
- `justify-content-center`
- `justify-content-between`
- `justify-content-around`
- `justify-content-evenly`

### Alignement vertical

```html
<div class="row align-items-center">
  <!-- Contenu centré verticalement -->
</div>
```

Options disponibles :

- `align-items-start`
- `align-items-end`
- `align-items-center`
- `align-items-baseline`
- `align-items-stretch`

### Ordre des colonnes

```html
<div class="col-md-6 order-md-2">
  <!-- Cette colonne apparaîtra en deuxième position sur md et plus -->
</div>
<div class="col-md-6 order-md-1">
  <!-- Cette colonne apparaîtra en première position sur md et plus -->
</div>
```

### Décalage de colonnes

```html
<div class="col-md-4 offset-md-2">
  <!-- Colonne de 33.33% avec décalage de 16.67% à gauche -->
</div>
```

## Classes utilitaires d'affichage

Pour contrôler l'affichage selon les breakpoints :

```html
<div class="d-none d-md-block">
  <!-- Visible uniquement à partir de md -->
</div>
```

Options disponibles :

- `d-none`
- `d-block`
- `d-flex`
- `d-inline`
- `d-inline-block`
- `d-grid`

Chaque classe peut être préfixée avec un breakpoint : `d-md-block`, `d-lg-flex`, etc.

## Exemple complet

```html
<div class="container">
  <div class="row">
    <div class="col-12 col-md-6 col-lg-4">
      <!-- Pleine largeur sur mobile, 50% sur tablette, 33.33% sur desktop -->
    </div>
    <div class="col-12 col-md-6 col-lg-8">
      <!-- Pleine largeur sur mobile, 50% sur tablette, 66.67% sur desktop -->
    </div>
  </div>
</div>
```
