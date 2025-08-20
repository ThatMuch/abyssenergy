# 🔧 Corrections des avertissements de dépréciation Sass

## Problème résolu

Les avertissements de dépréciation Sass `[mixed-decls]` ont été corrigés. Ce problème survenait lorsque des déclarations CSS apparaissaient après des règles imbriquées (comme les media queries).

## Corrections apportées

### 1. Dans `scss/layout/_layout.scss`

**Problème :** Déclarations CSS après `@include container()` et `@include flex-grid()`

**Solution :** Enveloppement des déclarations dans des blocs `& {}`

```scss
// ❌ Avant (générait des avertissements)
.header-container {
  @include container();
  display: flex;
  align-items: center;
  // ...
}

// ✅ Après (sans avertissements)
.header-container {
  @include container();

  & {
    display: flex;
    align-items: center;
    // ...
  }
}
```

### 2. Dans `scss/pages/_pages.scss`

**Problème :** Déclarations CSS après `@include aspect-ratio()`

**Solution :** Même principe avec des blocs `& {}`

```scss
// ❌ Avant
.post-thumbnail {
  @include aspect-ratio(16, 9);
  margin-bottom: $spacing-md;
  // ...
}

// ✅ Après
.post-thumbnail {
  @include aspect-ratio(16, 9);

  & {
    margin-bottom: $spacing-md;
    // ...
  }
}
```

## Scripts npm mis à jour

Nouveaux scripts disponibles :

```bash
npm run build:dev    # Compilation en mode développement (expanded)
npm run compile      # Compilation avec message de succès
npm run build        # Compilation en mode production (compressed)
npm run watch        # Mode surveillance pour développement
```

## Résultat

✅ **Compilation SCSS sans avertissements**
✅ **CSS généré correctement**
✅ **Header WordPress préservé**
✅ **Compatibilité future avec Sass garantie**

Le thème enfant est maintenant entièrement compatible avec les dernières versions de Sass et les futures mises à jour.
