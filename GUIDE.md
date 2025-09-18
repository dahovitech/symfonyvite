# Guide d'utilisation - CMS Multilingue

## URLs de l'application

### Frontend Public
- **`/`** - Page d'accueil avec liste des services publiés
- **`/service/{slug}`** - Page détail d'un service (ex: `/service/consultation-web`)
- **`/switch-language/{code}`** - Changement de langue (ex: `/switch-language/en`)

### Administration
- **`/admin`** - Tableau de bord administrateur
- **`/admin/languages`** - Gestion des langues
- **`/admin/languages/new`** - Création d'une nouvelle langue
- **`/admin/languages/{id}/edit`** - Édition d'une langue
- **`/admin/languages/{id}/set-default`** - Définir comme langue par défaut
- **`/admin/languages/{id}/toggle-active`** - Activer/désactiver une langue
- **`/admin/services`** - Gestion des services
- **`/admin/services/new`** - Création d'un nouveau service
- **`/admin/services/{id}/edit`** - Édition d'un service
- **`/admin/services/{id}/toggle-published`** - Publier/dépublier un service

### Développement
- **`/welcome`** - Page de test (vérification de l'installation)

## Structure des données

### Entité Language
```sql
- id (int, auto-increment)
- code (varchar(10), unique) - Code ISO de la langue (ex: 'fr', 'en')
- name (varchar(255)) - Nom affiché de la langue (ex: 'Français')
- is_default (boolean) - Si c'est la langue par défaut
- is_active (boolean) - Si la langue est active sur le site
```

### Entité Service
```sql
- id (int, auto-increment)
- slug (varchar(255), unique) - Identifiant URL du service
- is_published (boolean) - Statut de publication
- created_at (datetime) - Date de création
- updated_at (datetime, nullable) - Date de dernière modification
```

### Entité ServiceTranslation
```sql
- id (int, auto-increment)
- service_id (int, foreign key) - Référence vers Service
- language_id (int, foreign key) - Référence vers Language
- title (varchar(255)) - Titre du service dans cette langue
- description (text, nullable) - Description courte
- detail (text, nullable) - Contenu détaillé
```

## Fonctionnalités principales

### 1. Système de fallback intelligent
```php
// Dans Service.php
public function getTranslationWithFallback(Language $language, Language $defaultLanguage): ?ServiceTranslation
{
    $translation = $this->getTranslation($language);
    if (!$translation) {
        $translation = $this->getTranslation($defaultLanguage);
    }
    return $translation;
}
```

### 2. Gestion des sessions de langue
```php
// Dans LanguageService.php
public function setCurrentLanguage(Language $language): void
{
    $session = $this->getSession();
    $session?->set('current_language', $language->getCode());
}
```

### 3. Interface d'administration en onglets
Le JavaScript dans `base.html.twig` gère l'affichage des onglets pour les différentes langues lors de la saisie de contenu.

## Commandes utiles

### Doctrine
```bash
# Créer une migration
php bin/console make:migration

# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# Charger les fixtures
php bin/console doctrine:fixtures:load

# Vider le cache
php bin/console cache:clear
```

### Développement
```bash
# Serveur de développement PHP
php -S localhost:8000 -t public/

# Watch des assets Vite
npm run dev

# Build de production
npm run build
```

## Personnalisation

### Ajouter un nouveau type de contenu

1. **Créer l'entité principale** (ex: `Article.php`)
```php
#[ORM\Entity]
class Article
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;
    
    #[ORM\OneToMany(mappedBy: 'article', targetEntity: ArticleTranslation::class)]
    private Collection $translations;
}
```

2. **Créer l'entité de traduction** (ex: `ArticleTranslation.php`)
```php
#[ORM\Entity]
class ArticleTranslation
{
    #[ORM\ManyToOne(inversedBy: 'translations')]
    private ?Article $article = null;
    
    #[ORM\ManyToOne]
    private ?Language $language = null;
    
    #[ORM\Column(length: 255)]
    private ?string $title = null;
}
```

3. **Créer le contrôleur d'administration**
4. **Créer les templates Twig**
5. **Exécuter la migration**

### Modifier l'apparence

- **CSS global** : `assets/styles/app.css`
- **Templates** : `templates/` (structure Twig)
- **Layout admin** : `templates/admin/base.html.twig`
- **Layout public** : `templates/base.html.twig`

### Ajouter de nouvelles langues programmatiquement

```php
$language = new Language();
$language->setCode('de');
$language->setName('Deutsch');
$language->setIsActive(true);
$language->setIsDefault(false);

$entityManager->persist($language);
$entityManager->flush();
```

## Sécurité et bonnes pratiques

### Validation des slugs
Les slugs sont automatiquement générés et nettoyés via le service `SluggerInterface`.

### Contraintes de base de données
- Les codes de langue sont uniques
- Un seul langue peut être définie par défaut
- Les combinaisons service/langue sont uniques pour les traductions

### Gestion des erreurs
- Redirection automatique vers la langue par défaut si une langue n'existe pas
- Affichage de contenu de fallback si une traduction n'est pas disponible
- Messages flash pour les actions administratives

## Support et dépannage

### Problèmes courants

1. **Base de données verrouillée (SQLite)**
   ```bash
   rm var/data.db
   php bin/console doctrine:migrations:migrate
   php bin/console doctrine:fixtures:load
   ```

2. **Erreur Vite - assets non trouvés**
   ```bash
   npm run build
   ```

3. **Permissions sur les fichiers**
   ```bash
   chmod -R 755 var/
   chmod -R 755 public/
   ```

### Logs de débogage
- **Symfony** : `var/log/dev.log`
- **Serveur PHP** : Sortie directe dans le terminal

---

Pour toute question ou contribution, consultez le README.md principal ou créez une issue sur GitHub.
