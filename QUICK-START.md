# 🚀 Quick Start - Thème Enfant SCSS

## ✅ Problème résolu !

Le thème enfant est maintenant correctement configuré avec l'en-tête WordPress requis.

## 🔧 Activation

1. **Allez dans l'administration WordPress**

   - `Apparence > Thèmes`
   - Activez "Abyss Energy "

2. **Vérifiez l'activation**
   - Le thème devrait maintenant être reconnu par WordPress
   - L'erreur "Template is missing" devrait avoir disparu

## ⚡ Développement rapide

```bash
# Surveillez vos modifications SCSS
./dev.sh watch

# Ou avec npm
npm run watch

# Compilation pour production
./dev.sh build
```

## 🎨 Personnalisation

### Variables principales (scss/abstracts/\_variables.scss)

```scss
$color-secondary: #ff6900; // Couleur principale
$color-primary-dark: #09497a; // Couleur secondaire
$spacing-md: 1rem; // Espacement moyen
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

## 🛠️ Structure des fichiers

```
squarechilli-child/
├── style.css                 ✅ CSS compilé avec en-tête WordPress
├── functions.php             ✅ Fonctions du thème enfant
├── dev.sh                    ✅ Script de développement
└── scss/                     ✅ Sources SCSS modulaires
    ├── style.scss            ✅ Point d'entrée principal
    ├── abstracts/            ✅ Variables et mixins
    ├── base/                 ✅ Styles de base
    ├── components/           ✅ Composants réutilisables
    ├── layout/               ✅ Structure générale
    └── pages/                ✅ Styles spécifiques aux pages
```

## 🎯 Prochaines étapes

1. **Activez le thème** dans WordPress
2. **Lancez la surveillance SCSS** : `./dev.sh watch`
3. **Personnalisez** selon vos besoins
4. **Utilisez les composants** inclus (boutons, cartes, etc.)

## 📚 Aide rapide

- **Watch mode** : `./dev.sh watch`
- **Build production** : `./dev.sh build`
- **Lint SCSS** : `./dev.sh lint`
- **Aide** : `./dev.sh help`

---

🎉 **Votre thème enfant SCSS est prêt !**
