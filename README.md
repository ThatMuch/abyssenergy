# Thème Enfant Abyss Energy avec SCSS (squarechilli-child)

Ce thème enfant est basé sur le thème parent `squarechilli` et inclut un workflow complet SCSS pour un développement moderne et efficace.

## 🚀 Fonctionnalités

- ✅ **Thème enfant WordPress** complet et fonctionnel
- ✅ **Architecture SCSS modulaire** avec organisation professionnelle
- ✅ **Compilation automatique** SCSS → CSS
- ✅ **Système de variables** et mixins réutilisables
- ✅ **Components réutilisables** (boutons, cartes, alertes, etc.)
- ✅ **Responsive design** avec breakpoints configurables
- ✅ **Classes utilitaires** pour un développement rapide
- ✅ **Linting SCSS** avec Stylelint

## 📦 Installation et Configuration

### 1. Activation du thème

1. Activez le thème enfant depuis l'administration WordPress (Apparence > Thèmes)
2. Le thème héritera automatiquement de toutes les fonctionnalités du thème parent

### 2. Installation des dépendances SCSS (optionnel)

```bash
cd /path/to/squarechilli-child/
npm install
```

### 3. Scripts disponibles

```bash
npm run watch    # Compilation avec surveillance
npm run build    # Compilation pour production
npm run lint     # Vérification du code SCSS
```

## 🎨 Développement avec SCSS

### Variables personnalisables dans `scss/abstracts/_variables.scss` :

```scss
$color-secondary: #ff6900;
$color-primary-dark: #09497a;
$font-title: "Baloo 2", sans-serif;
```

### Mixins utiles :

```scss
@include respond-to(md) {
  /* styles responsive */
}
@include button-style($color-secondary);
@include card-style($spacing-lg, $shadow-md);
```

## Avantages du thème enfant

- ✅ Préserve vos personnalisations lors des mises à jour du thème parent
- ✅ Hérite de toutes les fonctionnalités du thème parent
- ✅ Permet des personnalisations sûres et modulaires
- ✅ Facilite la maintenance et le débogage

## Variables CSS disponibles

Le thème parent utilise des variables CSS que vous pouvez redéfinir :

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
