# Limitation des avis Google - Solutions alternatives

## Problème

L'API Google Places ne retourne que **5 avis maximum** par entreprise, même si vous en avez 21 ou plus sur Google My Business. C'est une limitation officielle de Google.

## Solutions alternatives

### 1. **Utiliser l'API Google My Business** (Recommandé)

- Plus d'avis disponibles (jusqu'à 200)
- Authentification OAuth2 requise
- Plus complexe à implémenter
- URL: https://developers.google.com/my-business/reference/rest

### 2. **Scraping web** (Non recommandé)

- Violer les conditions d'utilisation de Google
- Risque de blocage IP
- Code fragile (structure HTML peut changer)

### 3. **Widget Google intégré**

- Utiliser le widget officiel Google
- Affiche plus d'avis
- Moins de contrôle sur le design
- Code: `<div class="g-reviews-widget">`

### 4. **Saisie manuelle d'avis supplémentaires**

- Copier-coller manuellement les avis depuis Google My Business
- Mélanger avis API + avis manuels
- Plus de travail, mais plus de contrôle

### 5. **Utiliser un service tiers**

- Services comme ReviewsOnMyWebsite, EmbedSocial
- Payant mais plus d'avis
- Intégration plus simple

## Recommandation actuelle

Garder l'API Places pour sa simplicité et fiabilité, mais informer l'utilisateur de la limitation. Les 5 avis les plus récents sont généralement suffisants pour la crédibilité.

## Code alternatif (Widget Google)

```html
<script
  src="https://static.elfsight.com/platform/platform.js"
  data-use-service-core
  defer
></script>
<div class="elfsight-app-XXXXXX-XXXXXX"></div>
```

## Configuration actuelle optimisée

- Récupère les 5 avis les plus récents
- Cache intelligent 24h
- Affichage en carrousel
- Filtrage par note minimum
