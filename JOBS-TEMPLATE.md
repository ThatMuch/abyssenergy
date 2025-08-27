# 💼 Page Template des Emplois - Documentation

## 🎯 Objectif

Ce template permet d'afficher tous les posts de type "job" avec un système de filtrage avancé, une interface utilisateur moderne et des fonctionnalités complètes de recherche.

## 📁 Fichiers créés

### Templates WordPress

- `page-jobs.php` - Template principal pour une page personnalisée
- `archive-job.php` - Template automatique pour l'archive des emplois (/job/)

### Styles SCSS

- `scss/pages/_jobs.scss` - Styles spécifiques aux pages d'emplois

### Fonctionnalités PHP

- Variables de requête personnalisées
- Shortcode `[jobs_list]`
- Widget "Emplois récents"

## 🚀 Utilisation

### 1. Template de page personnalisé

**Créer une page avec le template :**

1. Allez dans `Pages > Ajouter`
2. Créez une page (ex: "Nos emplois")
3. Dans les attributs de page, sélectionnez "Jobs Listing"
4. Publiez la page

**URL recommandée :** `/emplois/` ou `/careers/`

### 2. Archive automatique

L'archive est accessible automatiquement à l'URL `/job/` et utilise le template `archive-job.php`.

### 3. Shortcode pour intégrer les emplois

```php
// Afficher 6 emplois récents
[jobs_list]

// Afficher 10 emplois d'un secteur spécifique
[jobs_list number="10" sector="energie-renouvelable"]

// Afficher en mode liste
[jobs_list layout="list" number="8"]

// Filtrer par localisation
[jobs_list location="paris,lyon" number="12"]
```

**Paramètres disponibles :**

- `number` : Nombre d'emplois à afficher (défaut: 6)
- `sector` : Slug du secteur (séparés par virgules)
- `location` : Slug de la localisation (séparés par virgules)
- `type` : Type d'emploi
- `layout` : "grid" ou "list" (défaut: grid)

### 4. Widget "Emplois récents"

1. Allez dans `Apparence > Widgets`
2. Ajoutez le widget "Emplois récents" à votre sidebar
3. Configurez le titre et le nombre d'emplois

## 🎨 Fonctionnalités

### ✅ Système de filtrage

- **Recherche textuelle** dans le titre et contenu
- **Filtre par secteur** (taxonomie job-sector)
- **Filtre par localisation** (taxonomie job-location)
- **Filtre par type** (champ personnalisé)

### ✅ Interface utilisateur

- **Vue grille et liste** commutable
- **Pagination** intégrée
- **Badges** pour les métadonnées
- **Design responsive**
- **Animations** CSS

### ✅ Optimisations

- **SEO-friendly** avec métadonnées
- **Performance** optimisée
- **Accessibilité** respectée
- **Mobile-first** design

## 🛠️ Personnalisation

### Modifier les styles

Les styles se trouvent dans `scss/pages/_jobs.scss` :

```scss
.jobs-listing-page {
  // Modifier la grille
  .jobs-grid {
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 2rem;
  }

  // Personnaliser les cartes
  .job-card {
    border-radius: 1rem;
    // Vos styles...
  }
}
```

### Ajouter des champs personnalisés

Dans `functions.php`, modifiez la fonction `abyssenergy_child_jobs_shortcode()` pour inclure vos champs ACF :

```php
// Exemple : afficher le salaire
$salary = get_field('salary');
if ($salary) {
    echo '<p class="job-salary">💰 ' . esc_html($salary) . '</p>';
}
```

### Personnaliser les filtres

Modifiez le template `page-jobs.php` pour ajouter de nouveaux filtres :

```php
// Exemple : filtre par type de contrat
<select name="contract_type">
    <option value="">Tous les contrats</option>
    <option value="cdi">CDI</option>
    <option value="cdd">CDD</option>
    <option value="stage">Stage</option>
</select>
```

## 📊 Taxonomies utilisées

### Job Sectors (`job-sector`)

- Secteurs d'activité des emplois
- Exemples : Énergie renouvelable, Consulting, R&D

### Job Locations (`job-location`)

- Localisations géographiques
- Exemples : Paris, Lyon, Remote

### Job Skills (`job-skill`)

- Compétences requises (si disponible)

## 🎭 Classes CSS disponibles

### Conteneurs principaux

```css
.jobs-listing-page      /* Page principale */
/* Page principale */
/* Page principale */
/* Page principale */
.jobs-archive-page      /* Archive des emplois */
.jobs-grid              /* Grille des emplois */
.jobs-grid.list-view; /* Vue en liste */
```

### Cartes d'emploi

```css
.job-card               /* Carte individuelle */
/* Carte individuelle */
/* Carte individuelle */
/* Carte individuelle */
.job-badges             /* Conteneur des badges */
.job-title              /* Titre de l'emploi */
.job-location           /* Localisation */
.job-excerpt            /* Extrait de description */
.job-meta; /* Métadonnées */
```

### Filtres et navigation

```css
.jobs-filters           /* Formulaire de filtrage */
/* Formulaire de filtrage */
/* Formulaire de filtrage */
/* Formulaire de filtrage */
.jobs-view-toggle       /* Boutons de vue */
.jobs-pagination        /* Navigation des pages */
.jobs-results-info; /* Informations sur les résultats */
```

## 📱 Responsive Design

Le template est entièrement responsive avec des breakpoints :

- **Mobile** (< 768px) : Vue liste automatique
- **Tablette** (768px - 992px) : 2 colonnes
- **Desktop** (> 992px) : 3+ colonnes

## 🔍 SEO et Performance

### Métadonnées incluses

- Titre de page optimisé
- Description de page
- Breadcrumbs automatiques
- Schema.org JobPosting (peut être ajouté)

### Performance

- CSS minifié
- Lazy loading des images (si supporté)
- Pagination pour éviter les pages lourdes

## 🚨 Points d'attention

1. **Taxonomies** : Assurez-vous que les taxonomies `job-sector` et `job-location` existent
2. **Champs ACF** : Les champs personnalisés doivent être configurés
3. **Permaliens** : Videz les permaliens après installation
4. **Cache** : Purgez le cache après modifications

## 📞 Support

Pour des personnalisations avancées :

1. Modifiez les fichiers SCSS dans `scss/pages/_jobs.scss`
2. Compilez avec `./dev.sh build`
3. Testez sur différentes tailles d'écran
4. Vérifiez la compatibilité avec les plugins existants

---

**Développé pour Abyss Energy par abyssenergy** 🚀
