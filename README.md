# Thème WordPress Abyss Energy

Thème WordPress premium avec architecture SCSS modulaire, blocs Gutenberg personnalisés et intégration ACF pour Abyss Energy.

## 🚀 Fonctionnalités

- ✅ **Thème WordPress** complet et fonctionnel
- ✅ **Architecture SCSS modulaire** avec organisation professionnelle
- ✅ **Compilation automatique** SCSS → CSS avec surveillance
- ✅ **Système de variables** et mixins réutilisables
- ✅ **Components réutilisables** (boutons, cartes, alertes, etc.)
- ✅ **Responsive design** avec breakpoints configurables
- ✅ **Classes utilitaires** pour un développement rapide
- ✅ **Linting SCSS** avec Stylelint
- ✅ **Blocs Gutenberg personnalisés** (carte interactive, témoignages, offres d'emploi, etc.)
- ✅ **Intégration ACF** pour une gestion flexible du contenu
- ✅ **BrowserSync** pour le rechargement automatique en développement

## 📦 Installation et Configuration

### 1. Activation du thème

1. Activez le thème depuis l'administration WordPress (Apparence > Thèmes)
2. Le thème est prêt à l'emploi avec tous ses blocs personnalisés

### 2. Installation des dépendances

```bash
cd /path/to/abyssenergy/
npm install
```

### 3. Scripts disponibles

#### Avec le script dev.sh (recommandé)

```bash
./dev.sh watch    # Compilation avec surveillance
./dev.sh build    # Compilation pour production
./dev.sh lint     # Vérification du code SCSS
./dev.sh fix      # Correction automatique des erreurs
./dev.sh clean    # Supprime les fichiers CSS générés
./dev.sh start    # Lance SCSS watch + BrowserSync
./dev.sh help     # Affiche l'aide complète
```

#### Avec npm

```bash
npm run watch           # Compilation avec surveillance
npm run build           # Compilation pour production
npm run lint            # Vérification du code SCSS
npm run lint:fix        # Correction automatique
npm run start           # SCSS watch + BrowserSync
npm run browser-sync    # BrowserSync seul
npm run zip             # Créer une archive du thème
```

## 🎨 Développement avec SCSS

### Structure des fichiers

```
abyssenergy/
├── style.css                 ✅ CSS compilé avec en-tête WordPress
├── style.min.css            ✅ CSS minifié pour production
├── functions.php             ✅ Fonctions du thème
├── dev.sh                    ✅ Script de développement
├── package.json              ✅ Dépendances npm
└── scss/                     ✅ Sources SCSS modulaires
    ├── style.scss            ✅ Point d'entrée principal
    ├── abstracts/            ✅ Variables et mixins
    │   ├── _variables.scss
    │   └── _mixins.scss
    ├── base/                 ✅ Styles de base
    ├── components/           ✅ Composants réutilisables
    ├── layout/               ✅ Structure générale
    └── pages/                ✅ Styles spécifiques aux pages
```

### Variables personnalisables

Dans `scss/abstracts/_variables.scss` :

```scss
// Couleurs principales
$color-secondary: #ff6900;
$color-primary-dark: #09497a;
$color-lightorange: #f1d5c1;
$color-lightgrey: #dcd7d4;

// Typographie
$font-title: "Baloo 2", sans-serif;
$font-main: "Baloo 2", sans-serif;

// Espacements
$spacing-md: 1rem;
$spacing-lg: 2rem;
$spacing-xl: 3rem;
```

### Mixins utiles

```scss
// Responsive design
@include respond-to(md) {
  /* styles responsive */
}

// Composants
@include button-style($color-secondary);
@include card-style($spacing-lg, $shadow-md);
```

### Exemple d'ajout de styles

```scss
// Dans scss/components/_components.scss
.ma-classe-custom {
  background: $color-secondary;
  padding: $spacing-lg;
  border-radius: $border-radius-md;

  @include respond-to(md) {
    padding: $spacing-xl;
  }
}
```

## 🧩 Blocs Gutenberg Personnalisés

Le thème inclut plusieurs blocs personnalisés :

- **Carte interactive** - Carte SVG interactive avec zones cliquables
- **Slider de témoignages** - Carrousel de témoignages clients
- **Liste d'offres d'emploi** - Affichage des postes disponibles
- **Clients** - Grille de logos clients
- **Avis Google** - Affichage des avis Google
- **Projets showcase** - Mise en avant de projets
- **Fonctionnalités** - Présentation de fonctionnalités
- **Métriques** - Affichage de statistiques
- **Timeline** - Chronologie d'événements
- **Tabs** - Système d'onglets
- **Secteurs** - Présentation des secteurs d'activité
- **Recherche d'emplois** - Moteur de recherche d'offres
- **Boutons personnalisés** - Boutons stylisés

## 🔧 Architecture Modulaire

Le thème utilise une architecture modulaire pour une meilleure maintenabilité :

```
inc/
├── setup.php              # Configuration principale
├── enqueue.php            # Scripts et styles
├── blocks.php             # Blocs Gutenberg
├── acf.php                # Intégration ACF
├── jobs.php               # Gestion des offres d'emploi
├── widgets.php            # Widgets personnalisés
├── shortcodes.php         # Shortcodes
└── customizer.php         # Customizer WordPress
```

## 🌐 Développement avec BrowserSync

Pour un développement avec rechargement automatique :

```bash
./dev.sh start
```

Cela lance :
- La compilation SCSS en mode surveillance
- BrowserSync pour le rechargement automatique du navigateur

## 📋 Variables CSS Disponibles

Le thème utilise des variables CSS personnalisables :

```css
:root {
  --title-font: "Baloo 2";
  --main-font: "Baloo 2";
  --color-orange: #ff6900;
  --color-lightorange: #f1d5c1;
  --color-darkblue: #09497a;
  --color-main: #09497a;
  --color-lightgrey: #dcd7d4;
}
```

## 🎯 Quick Start

1. **Activez le thème** dans WordPress (Apparence > Thèmes)
2. **Installez les dépendances** : `npm install`
3. **Lancez le mode développement** : `./dev.sh start`
4. **Personnalisez** selon vos besoins dans les fichiers SCSS
5. **Utilisez les blocs** Gutenberg personnalisés dans l'éditeur

## 📚 Aide Rapide

- **Watch mode** : `./dev.sh watch` ou `npm run watch`
- **Build production** : `./dev.sh build` ou `npm run build`
- **Lint SCSS** : `./dev.sh lint` ou `npm run lint`
- **Dev avec BrowserSync** : `./dev.sh start` ou `npm run start`
- **Créer une archive** : `npm run zip`
- **Aide complète** : `./dev.sh help`

## 🛠️ Intégrations

- **ACF (Advanced Custom Fields)** - Gestion flexible des champs personnalisés
- **Gravity Forms** - Formulaires avec support des optgroups
- **Search & Filter** - Filtrage dynamique des offres d'emploi
- **BrowserSync** - Rechargement automatique en développement

## 📝 Notes de Développement

- Les styles SCSS sont compilés vers `style.min.css`
- Le fichier `style.css` contient l'en-tête WordPress requis
- Les blocs personnalisés ont leurs propres fichiers SCSS dans `scss/components/`
- Les images supportent le lazy loading automatique
- Le thème est optimisé pour les performances

## 🎉 Prêt à l'Emploi

Le thème est entièrement fonctionnel et prêt pour la production. Tous les composants, blocs et fonctionnalités sont opérationnels dès l'activation.

---

**Version** : 1.0.0
**Auteur** : THATMUCH
**License** : GPL-2.0+
