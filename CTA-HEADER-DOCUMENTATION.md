# 🎯 CTA Header - Call to Action dans l'en-tête

## Vue d'ensemble

Le CTA Header est un bouton d'appel à l'action qui s'intègre intelligemment dans l'en-tête du site, après le menu principal. Il est entièrement configurable via le **Customizer WordPress** et s'adapte automatiquement au design existant.

## ✨ Fonctionnalités

- **🎛️ Configuration intuitive** via le Customizer WordPress
- **🎨 4 styles visuels** différents (primary, secondary, outline, ghost)
- **📱 Responsive design** avec option de masquage mobile
- **🔗 Injection intelligente** dans l'en-tête existant
- **⚡ Preview en temps réel** dans le Customizer
- **📊 Analytics intégré** (Google Analytics/GTM)
- **♿ Accessibilité optimisée**

## 🎛️ Configuration via le Customizer

### Accès à la configuration

1. Allez dans **Apparence > Personnaliser**
2. Cliquez sur **"CTA Header"**
3. Configurez les options selon vos besoins
4. Cliquez sur **"Publier"** pour sauvegarder

### Options disponibles

#### ✅ **Activation**

- **Activer le CTA dans le header** : Active/désactive l'affichage du bouton

#### 📝 **Contenu**

- **Texte du bouton** : Texte affiché sur le bouton (ex: "Nous contacter")
- **URL de destination** : Lien vers lequel le bouton redirige
- **Icône (optionnel)** : Code HTML pour une icône (ex: `<i class="fas fa-phone"></i>`)

#### 🎨 **Apparence**

- **Style du bouton** :

  - `Bleu principal` : Fond bleu, texte blanc
  - `Orange secondaire` : Fond orange, texte blanc
  - `Contour` : Fond transparent, bordure colorée
  - `Fantôme` : Fond semi-transparent avec effet blur

- **Taille du bouton** :
  - `Petit` : Compact, idéal pour les headers étroits
  - `Moyen` : Taille standard (recommandé)
  - `Grand` : Plus visible, pour les headers larges

#### ⚙️ **Comportement**

- **Ouvrir dans un nouvel onglet** : Le lien s'ouvre dans une nouvelle fenêtre
- **Masquer sur mobile** : Cache le bouton sur les écrans mobiles

## 🎨 Styles disponibles

### `primary` (Bleu principal)

```scss
background-color: $color-primary;
color: $white-100;
border: 2px solid $color-primary;
```

- Effet hover avec assombrissement et élévation
- Style professionnel et moderne

### `secondary` (Orange secondaire)

```scss
background-color: $color-secondary;
color: $white-100;
border: 2px solid $color-secondary;
```

- Plus accrocheur et dynamique
- Idéal pour les CTA importants

### `outline` (Contour)

```scss
background-color: transparent;
color: $color-primary;
border: 2px solid $color-primary;
```

- Style épuré et élégant
- Se fond bien dans le design

### `ghost` (Fantôme)

```scss
background-color: rgba($white-100, 0.1);
backdrop-filter: blur(10px);
border: 2px solid rgba($color-primary, 0.3);
```

- Effet moderne avec transparence
- Parfait pour les headers colorés

## 🔧 Intégration technique

### Injection automatique

Le CTA est injecté automatiquement via JavaScript dans l'en-tête existant :

1. **Détection intelligente** du conteneur header
2. **Positionnement optimal** après le menu principal
3. **Adaptation automatique** à la structure existante
4. **Fallback** si le header n'est pas trouvé

### Sélecteurs supportés

Le script recherche ces éléments dans l'ordre :

```javascript
[
  ".header .header-container",
  ".site-header .container",
  ".header-container",
  ".site-header",
  ".header",
  "header",
];
```

## 📱 Responsive Design

### Desktop

- Bouton aligné à droite du header
- Espacement automatique avec le menu

### Tablet

- Espacement réduit mais conservé
- Bouton toujours visible

### Mobile

- Option de masquage disponible
- Si visible : bouton pleine largeur sous le menu
- Centrage automatique

## 🛠️ Personnalisation avancée

### Classes CSS disponibles

```scss
.header-cta {
  // Conteneur principal

  .header-cta-btn {
    // Bouton CTA

    &--hide-mobile {
      /* Masqué sur mobile */
    }

    &__icon {
      /* Conteneur icône */
    }
    &__text {
      /* Texte du bouton */
    }
  }
}

.header-container.has-cta {
  // Header avec CTA actif
}
```

### Surcharge de styles

```scss
// Personnaliser le style ghost
.header-cta-btn.btn--ghost {
  background-color: rgba(your-color, 0.2);
  border-color: your-color;

  &:hover {
    background-color: rgba(your-color, 0.4);
  }
}
```

### Hooks JavaScript

```javascript
// Écouter l'injection du CTA
document.addEventListener("headerCTA:injected", function (e) {
  console.log("CTA injecté:", e.detail.element);
});

// Écouter les clics sur le CTA
document.addEventListener("headerCTA:click", function (e) {
  console.log("CTA cliqué:", e.detail);
});
```

## 📊 Analytics et suivi

### Événements trackés automatiquement

```javascript
// Google Analytics 4
gtag("event", "header_cta_click", {
  cta_text: "Nous contacter",
  cta_url: "/contact/",
  cta_style: "primary",
  cta_location: "header",
});

// Google Tag Manager
dataLayer.push({
  event: "header_cta_click",
  cta_data: {
    /* données du CTA */
  },
});
```

## 🚀 API JavaScript

### Contrôle programmatique

```javascript
// Accéder à l'instance
const headerCTA = window.HeaderCTAInstance;

// Méthodes disponibles
headerCTA.refresh(); // Réinjecter le CTA
headerCTA.hide(); // Masquer temporairement
headerCTA.show(); // Réafficher
headerCTA.removeCTA(); // Supprimer complètement
```

## 🎯 Exemples d'utilisation

### Pour un site corporate

```
Texte: "Demander un devis"
URL: /contact/
Style: Primary
Taille: Medium
```

### Pour un site e-commerce

```
Texte: "☎️ Appelez-nous"
URL: tel:+33123456789
Style: Secondary
Taille: Small
Ouvrir dans nouvel onglet: Oui
```

### Pour un site de services

```
Texte: "Consultation gratuite"
URL: /rendez-vous/
Style: Outline
Taille: Large
Masquer sur mobile: Non
```

## 🔍 Dépannage

### Le CTA n'apparaît pas

1. Vérifiez que l'option est activée dans le Customizer
2. Vérifiez que le texte et l'URL sont renseignés
3. Inspectez la console pour les erreurs JavaScript
4. Vérifiez que le header container est détecté

### Le CTA apparaît au mauvais endroit

1. La structure de votre header est peut-être non-standard
2. Ajoutez la classe `.header-container` à votre conteneur header
3. Ou utilisez l'API pour un positionnement manuel

### Preview ne fonctionne pas dans le Customizer

1. Assurez-vous que le JavaScript est activé
2. Rechargez la page de preview
3. Vérifiez les erreurs dans la console

Le CTA Header est maintenant prêt ! Configurez-le via **Apparence > Personnaliser > CTA Header**.
