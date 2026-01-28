# Guide : Ajouter de nouveaux paramètres dans les Réglages

Ce guide explique comment ajouter de nouveaux champs de configuration dans la page de réglages admin (`/admin/reglages`).

## Table des matières
1. [Vue d'ensemble](#vue-densemble)
2. [Ajouter un nouveau champ](#ajouter-un-nouveau-champ)
3. [Utiliser les settings dans votre code](#utiliser-les-settings-dans-votre-code)
4. [Exemples pratiques](#exemples-pratiques)

---

## Vue d'ensemble

Le système de réglages utilise le package **CodeIgniter4 Settings** qui stocke les paramètres dans une table `settings` en base de données.

### Fichiers concernés

- **Controller** : `app/Controllers/Admin/Settings.php` - Gère l'affichage et la sauvegarde
- **Vue** : `app/Views/admin/settings.php` - Formulaire HTML
- **Service** : `service('settings')` - Service pour lire/écrire les settings
- **Helper** : `setting()` - Fonction helper pour lire facilement les settings

---

## Ajouter un nouveau champ

Suivez ces 3 étapes pour ajouter un nouveau paramètre :

### Étape 1 : Ajouter le champ dans la vue

Éditez `app/Views/admin/settings.php` et ajoutez votre champ dans le formulaire :

```php
<div class="col-md-6">
    <label for="mon_nouveau_champ" class="form-label">
        Mon nouveau paramètre <span class="text-danger">*</span>
    </label>
    <input type="text"
           class="form-control"
           id="mon_nouveau_champ"
           name="mon_nouveau_champ"
           value="<?= old('mon_nouveau_champ', esc($mon_nouveau_champ)) ?>"
           required
           maxlength="255">
    <small class="text-muted">Description de ce paramètre.</small>
</div>
```

**Types de champs possibles :**

- `type="text"` - Texte simple
- `type="email"` - Email
- `type="number"` - Nombre
- `type="url"` - URL
- `type="date"` - Date
- `<textarea>` - Texte long
- `<select>` - Liste déroulante
- `<input type="checkbox">` - Case à cocher

### Étape 2 : Charger la valeur dans le controller

Éditez `app/Controllers/Admin/Settings.php`, méthode `index()` :

```php
public function index()
{
    // Charger les paramètres actuels
    $settings = service('settings');

    $data = [
        'site_title' => $settings->get('App.siteName', 'Mon Site'),
        'contact_email' => $settings->get('App.contactEmail', ''),
        // Ajoutez votre nouveau champ ici :
        'mon_nouveau_champ' => $settings->get('App.monNouveauChamp', 'Valeur par défaut'),
    ];

    return $this->render('admin/settings', $data);
}
```

**Format de la clé** : `App.monNouveauChamp`
- Première partie (`App`) = namespace (pour regrouper les settings)
- Deuxième partie (`monNouveauChamp`) = nom du paramètre (camelCase recommandé)

### Étape 3 : Ajouter la validation et la sauvegarde

Dans la même classe, méthode `save()` :

**3a. Ajouter la règle de validation :**

```php
$validation->setRules([
    'site_title' => 'required|min_length[3]|max_length[255]',
    'contact_email' => 'required|valid_email|max_length[255]',
    // Ajoutez votre règle de validation :
    'mon_nouveau_champ' => 'required|min_length[3]|max_length[255]',
], [
    // Messages d'erreur personnalisés
    'mon_nouveau_champ' => [
        'required' => 'Ce champ est obligatoire.',
        'min_length' => 'Le champ doit contenir au moins 3 caractères.',
        'max_length' => 'Le champ ne peut pas dépasser 255 caractères.',
    ],
]);
```

**Règles de validation courantes :**
- `required` - Obligatoire
- `min_length[n]` - Longueur minimale
- `max_length[n]` - Longueur maximale
- `valid_email` - Format email
- `valid_url` - Format URL
- `numeric` - Nombre uniquement
- `alpha_numeric` - Alphanumérique uniquement

**3b. Sauvegarder la valeur :**

```php
try {
    $settings->set('App.siteName', $this->request->getPost('site_title'));
    $settings->set('App.contactEmail', $this->request->getPost('contact_email'));
    // Ajoutez votre sauvegarde :
    $settings->set('App.monNouveauChamp', $this->request->getPost('mon_nouveau_champ'));

    $this->success('Les réglages ont été enregistrés avec succès.');
} catch (\Exception $e) {
    log_message('error', 'Erreur lors de la sauvegarde des réglages: ' . $e->getMessage());
    $this->error('Une erreur est survenue lors de la sauvegarde des réglages.');
}
```

---

## Utiliser les settings dans votre code

### Méthode 1 : Avec le helper `setting()` (recommandé)

```php
// Dans vos vues ou controllers
$siteTitle = setting('App.siteName');
$contactEmail = setting('App.contactEmail');
$monChamp = setting('App.monNouveauChamp');

// Avec valeur par défaut si le setting n'existe pas
$siteTitle = setting('App.siteName') ?? 'Mon Site par défaut';
```

**Avantage** : Syntaxe simple et courte
**Note** : Le helper `setting` est déjà chargé automatiquement dans `app/Config/Autoload.php`

### Méthode 2 : Avec le service

```php
// Dans vos controllers
$settings = service('settings');
$siteTitle = $settings->get('App.siteName', 'Défaut');
```

**Avantage** : Plus de contrôle, possibilité de set/forget/etc.

### Dans les vues

```php
<!-- Afficher un setting dans une vue -->
<h1><?= setting('App.siteName') ?? 'Mon Site' ?></h1>

<footer>
    Contact : <?= setting('App.contactEmail') ?>
</footer>
```

---

## Exemples pratiques

### Exemple 1 : Ajouter un champ "Description du site"

**Vue** (`app/Views/admin/settings.php`) :
```php
<div class="col-12">
    <label for="site_description" class="form-label">Description du site</label>
    <textarea class="form-control"
              id="site_description"
              name="site_description"
              rows="4"
              maxlength="500"><?= old('site_description', esc($site_description)) ?></textarea>
    <small class="text-muted">Description affichée dans les meta tags (SEO).</small>
</div>
```

**Controller - index()** :
```php
$data = [
    'site_title' => $settings->get('App.siteName', 'Mon Site'),
    'contact_email' => $settings->get('App.contactEmail', ''),
    'site_description' => $settings->get('App.siteDescription', ''),
];
```

**Controller - save()** :
```php
// Validation
'site_description' => 'max_length[500]',

// Sauvegarde
$settings->set('App.siteDescription', $this->request->getPost('site_description'));
```

**Utilisation** :
```php
<!-- Dans app/Views/templates/admin/head.php par exemple -->
<meta name="description" content="<?= setting('App.siteDescription') ?>">
```

---

### Exemple 2 : Ajouter une option "Maintenance activée" (checkbox)

**Vue** :
```php
<div class="col-md-6">
    <div class="form-check">
        <input class="form-check-input"
               type="checkbox"
               id="maintenance_mode"
               name="maintenance_mode"
               value="1"
               <?= old('maintenance_mode', $maintenance_mode) ? 'checked' : '' ?>>
        <label class="form-check-label" for="maintenance_mode">
            Activer le mode maintenance
        </label>
    </div>
    <small class="text-muted">Le site affichera une page de maintenance aux visiteurs.</small>
</div>
```

**Controller - index()** :
```php
$data = [
    // ...
    'maintenance_mode' => $settings->get('App.maintenanceMode', false),
];
```

**Controller - save()** :
```php
// Validation (optionnel pour checkbox)
'maintenance_mode' => 'permit_empty|in_list[1]',

// Sauvegarde (checkbox : si pas coché = null)
$maintenanceMode = $this->request->getPost('maintenance_mode') === '1';
$settings->set('App.maintenanceMode', $maintenanceMode);
```

**Utilisation** :
```php
// Dans un filtre ou controller
if (setting('App.maintenanceMode')) {
    return view('maintenance');
}
```

---

### Exemple 3 : Ajouter un sélecteur de langue

**Vue** :
```php
<div class="col-md-6">
    <label for="site_language" class="form-label">Langue du site</label>
    <select class="form-control" id="site_language" name="site_language" required>
        <option value="fr" <?= $site_language === 'fr' ? 'selected' : '' ?>>Français</option>
        <option value="en" <?= $site_language === 'en' ? 'selected' : '' ?>>English</option>
        <option value="es" <?= $site_language === 'es' ? 'selected' : '' ?>>Español</option>
    </select>
</div>
```

**Controller - index()** :
```php
$data = [
    // ...
    'site_language' => $settings->get('App.language', 'fr'),
];
```

**Controller - save()** :
```php
// Validation
'site_language' => 'required|in_list[fr,en,es]',

// Sauvegarde
$settings->set('App.language', $this->request->getPost('site_language'));
```

**Utilisation** :
```php
// Dans app/Config/App.php ou dans un controller
$language = setting('App.language') ?? 'fr';
service('request')->setLocale($language);
```

---

## Bonnes pratiques

1. **Naming convention** : Utilisez le namespace `App.` suivi d'un nom en camelCase
   - ✅ `App.siteName`
   - ✅ `App.maintenanceMode`
   - ❌ `site_name` (pas de namespace)
   - ❌ `App.site_name` (snake_case déconseillé)

2. **Valeurs par défaut** : Toujours fournir une valeur par défaut
   ```php
   $title = setting('App.siteName') ?? 'Mon Site';
   ```

3. **Validation** : Validez toujours les données avant de les sauvegarder

4. **Sécurité** : Utilisez `esc()` lors de l'affichage dans les vues
   ```php
   value="<?= old('site_title', esc($site_title)) ?>"
   ```

5. **Documentation** : Ajoutez des `<small class="text-muted">` pour expliquer chaque champ

---

## Dépannage

### Le setting ne se sauvegarde pas

Vérifiez que :
- La table `settings` existe dans votre base de données
- La validation passe (pas d'erreurs dans les logs)
- Le nom du champ dans la vue (`name="..."`) correspond au `getPost('...')` du controller

### Le setting retourne toujours la valeur par défaut

Vérifiez que :
- La clé utilisée est exactement la même (sensible à la casse : `App.siteName` ≠ `App.SiteName`)
- La sauvegarde s'est bien exécutée (vérifiez dans la table `settings`)

### Erreur "Table 'settings' doesn't exist"

Lancez les migrations :
```bash
php spark migrate
```

---

## Support

Pour plus d'informations sur le package Settings de CodeIgniter4 :
- Documentation officielle : https://github.com/codeigniter4/settings

---


---

### Exemple 4 : Ajouter un champ de type image avec le système de médias (Logo du site)

**Important** : Pour les uploads de fichiers, vous devez utiliser `form_open_multipart()` au lieu de `form_open()`.

**Important** : Le projet utilise un système de gestion de médias centralisé via `MediaModel` et la fonction helper `upload_file()`. Cette approche est recommandée pour tous les uploads.

**Vue** (`app/Views/admin/settings.php`) - Identique à avant :
```php
<!-- Changer form_open en form_open_multipart -->
<?= form_open_multipart('admin/reglages/save', ['id' => 'settings_form']) ?>

<!-- Ajouter une carte pour le logo -->
<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-image"></i> Logo du site
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="<?= $site_logo_url ?>"
                         alt="Logo du site"
                         id="logoPreview"
                         class="rounded border"
                         style="max-width: 200px; max-height: 100px; object-fit: contain;">
                    <?php if ($has_logo): ?>
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteLogo()">
                            <i class="fas fa-trash"></i> Supprimer le logo
                        </button>
                    <?php endif; ?>
                </div>
                <input type="file"
                       class="form-control"
                       id="site_logo"
                       name="site_logo"
                       accept="image/*">
                <small class="text-muted">Formats acceptés : JPG, PNG, SVG, GIF (Max: 2MB)</small>
            </div>
        </div>
    </div>
</div>

<!-- Script JS pour la preview -->
<script>
$(document).ready(function() {
    initImagePreview('#site_logo', '#logoPreview', '<?= $site_logo_url ?>', 2);
});
</script>
```

**Controller - index()** avec système de médias :
```php
public function index()
{
    $settings = service('settings');
    $mediaModel = model('MediaModel');

    // Récupérer l'ID du logo depuis les settings
    $logoId = $settings->get('App.siteLogoId', null);
    $hasLogo = false;
    $logoUrl = base_url('assets/img/logo.png');

    // Charger le media si un ID est défini
    if ($logoId) {
        $media = $mediaModel->find($logoId);
        if ($media && $media->fileExists()) {
            $hasLogo = true;
            $logoUrl = $media->getUrl(); // Utilise la méthode de l'entité Media
        }
    }

    $data = [
        'site_title' => $settings->get('App.siteName', 'Mon Site'),
        'contact_email' => $settings->get('App.contactEmail', ''),
        'site_logo_url' => $logoUrl,
        'has_logo' => $hasLogo,
    ];

    return $this->render('admin/settings', $data);
}
```

**Controller - save()** avec `upload_file()` :
```php
// Validation (identique)
'site_logo' => 'permit_empty|max_size[site_logo,2048]|is_image[site_logo]',

// Sauvegarde avec le système de médias
$logoFile = $this->request->getFile('site_logo');
if ($logoFile && $logoFile->isValid() && !$logoFile->hasMoved()) {
    helper('utils'); // Charger le helper contenant upload_file()
    
    // Utiliser upload_file() du système de médias
    $media = upload_file(
        file: $logoFile,
        subfolder: 'logos',
        customName: 'site-logo',
        mediaData: [
            'entity_id' => 1,              // ID de l'entité (1 pour settings globales)
            'entity_type' => 'settings',   // Type d'entité
            'title' => 'Logo du site',
            'alt' => $this->request->getPost('site_title'),
        ],
        isMultiple: false,  // false = remplace l'ancien média automatiquement
        acceptedMimeTypes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'],
        maxSize: 2048       // en Ko
    );

    // Vérifier si l'upload a réussi
    if (is_array($media) && isset($media['status']) && $media['status'] === 'error') {
        $this->error($media['message']);
    } else {
        // Sauvegarder l'ID du media dans les settings (PAS le chemin)
        $settings->set('App.siteLogoId', $media->id);
    }
}
```

**Controller - deleteLogo()** avec MediaModel :
```php
public function deleteLogo()
{
    if (!$this->request->isAJAX()) {
        return $this->response->setStatusCode(403)->setJSON([
            'success' => false,
            'message' => 'Requête non autorisée'
        ]);
    }

    $settings = service('settings');
    $mediaModel = model('MediaModel');
    
    $logoId = $settings->get('App.siteLogoId', null);

    if (empty($logoId)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Aucun logo à supprimer'
        ]);
    }

    try {
        // Supprimer le média (fichier + enregistrement en base)
        $result = $mediaModel->deleteMedia($logoId);
        
        if ($result) {
            // Supprimer l'entrée dans les settings
            $settings->forget('App.siteLogoId');

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Le logo a été supprimé avec succès',
                'defaultLogoUrl' => base_url('assets/img/logo.png')
            ]);
        }
    } catch (\Exception $e) {
        log_message('error', 'Erreur lors de la suppression du logo: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Une erreur est survenue'
        ]);
    }
}
```

**Utilisation** :
```php
// Récupérer le logo du site
$logoId = setting('App.siteLogoId');
if (!empty($logoId)) {
    $mediaModel = model('MediaModel');
    $media = $mediaModel->find($logoId);
    if ($media && $media->fileExists()) {
        echo '<img src="' . $media->getUrl() . '" alt="' . $media->alt . '">';
    }
} else {
    echo '<img src="' . base_url('assets/img/logo.png') . '" alt="Logo par défaut">';
}
```

**Avantages du système de médias** :
- ✅ Gestion centralisée des fichiers via `MediaModel`
- ✅ Organisation automatique par année/mois : `uploads/logos/2024/11/`
- ✅ Remplacement automatique de l'ancien média avec `isMultiple: false`
- ✅ Suppression automatique du fichier physique lors de la suppression en base
- ✅ Méthodes pratiques sur l'entité : `getUrl()`, `fileExists()`, `getFileSize()`
- ✅ Association entity_type/entity_id pour retrouver facilement les médias
- ✅ Stockage de metadata (title, alt) pour l'accessibilité

**Points clés** :
- Stockez l'**ID du média** dans settings, pas le chemin
- Utilisez `upload_file()` au lieu de gérer manuellement
- L'ancien média est automatiquement remplacé avec `isMultiple: false`
- Pensez à ajouter votre `entity_type` dans `MediaModel` validation si nécessaire

