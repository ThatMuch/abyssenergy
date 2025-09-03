# Guide de migration vers l'architecture modulaire

Cette restructuration divise l'ancien fichier functions.php monolithique en plusieurs fichiers spécialisés pour une meilleure maintenabilité.

## Comment effectuer la migration

### Étape 1 : Sauvegarde

- Faites une sauvegarde complète du thème.
- Gardez une copie de l'ancien fichier `functions.php` pour référence (renommez-le par exemple en `functions.php.old`).

### Étape 2 : Remplacer le fichier functions.php

```bash
# Dans le terminal, à la racine du thème
mv functions.php functions.php.old
mv functions.php.new functions.php
```

### Étape 3 : Vérification

- Vérifiez que le site fonctionne correctement après le changement.
- Testez les différentes fonctionnalités du site (navigation, pages, fonctionnalités spéciales).

## Architecture des fichiers

Voici comment le code a été réorganisé :

1. `/inc/setup.php` - Configuration de base du thème (supports, menus, etc.)
2. `/inc/enqueue.php` - Gestion des styles et scripts
3. `/inc/scss.php` - Compilation des fichiers SCSS
4. `/inc/acf.php` - Fonctions liées à Advanced Custom Fields
5. `/inc/blocks.php` - Blocs Gutenberg personnalisés
6. `/inc/admin.php` - Personnalisations de l'interface d'administration
7. `/inc/widgets.php` - Widgets et sidebars personnalisés
8. `/inc/shortcodes.php` - Shortcodes personnalisés
9. `/inc/utils.php` - Fonctions utilitaires diverses

## Avantages de cette structure

1. **Meilleure organisation** - Code divisé par domaine fonctionnel
2. **Maintenance simplifiée** - Plus facile de trouver et modifier du code spécifique
3. **Modularité** - Possibilité d'activer/désactiver des fonctionnalités facilement
4. **Collaboration améliorée** - Plusieurs développeurs peuvent travailler sur différents modules

## Remarques importantes

- La constante `WP_ENV` est maintenant définie pour gérer la compilation SCSS selon l'environnement.
- Les noms de fonctions ont été préfixés avec `abyssenergy_` pour éviter les conflits.

## Pour ajouter de nouvelles fonctionnalités

Pour ajouter de nouvelles fonctionnalités :

1. Choisissez le fichier approprié selon la fonctionnalité
2. Si nécessaire, créez un nouveau fichier dans `/inc/` pour une catégorie distincte
3. N'oubliez pas d'ajouter ce nouveau fichier dans le tableau `$abyssenergy_includes` dans `functions.php`
