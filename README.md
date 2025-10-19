# 🗞️ Cross-Origin Newspaper

**Cross-Origin Newspaper** est une application Laravel permettant de centraliser, gérer et afficher des articles de presse provenant de plusieurs sources.  
Le projet expose une **API RESTful** (versionnée `v1`) et propose une interface d'inscription/connexion avec gestion des rôles (utilisateur et administrateur).


## Fichier `.env` –
Créer un fichier .env, pour vous faciliter la tâche, j'ai laissé les identifiants dans le .env.example, donc vous pouvez
tout copier. (! Attention, Pratique à ne pas faire, ici c'est exceptionnel, ne jamais mettre des données sensibles, 
identifiants, etc dans un fichier .env.example)

##Installation & Lancement

### Prérequis
- **Docker** et **Docker Compose** installés
- **PHP 8.3+**, **Composer** et **Node.js** (uniquement si tu veux exécuter Laravel sans Docker)

---

### Lancement via Docker

Si tu disposes de Docker, lance simplement :

```bash
docker compose up --build -d
```

Cela va :
- Construire les conteneurs (app, base de données, etc.)
- Lancer le projet en arrière-plan

---

### Initialisation du projet

Une fois les conteneurs démarrés, exécute les commandes suivantes à l’intérieur du conteneur PHP (ou depuis ton terminal si tu n’utilises pas Docker) :

```bash
php artisan migrate
```

> Cette commande exécute les migrations et crée les tables nécessaires dans la base de données.

---

### Données de test (compte administrateur)

Pour insérer les données de test (dont un **compte admin complet**), exécute :

```bash
php artisan db:seed
```

Identifiants du compte admin :
```
email : admin@example.com
password : password
```

---

## API - Endpoints disponibles

### Récupérer les articles
**Méthode :** `GET`  
**URL :** `http://localhost:8080/api/v1/articles`

#### Query Parameters disponibles :
| Paramètre | Description | Exemple |
|------------|--------------|----------|
| `page` | Numéro de page pour la pagination | `page=1` |
| `min_id` | Filtrer les articles dont l’ID est supérieur ou égal à une valeur donnée | `min_id=3` |
| `sort` | Tri des résultats (`champ,ordre`) | `sort=id,asc` |
| `date` | Filtrer par date précise | `date=2025-10-15` |
| `created_after` | Articles créés après une date précise (ISO8601) | `created_after=2025-10-15T23:51:32+07:00` |
| `publish_date_gte` | Articles publiés après un timestamp donné | `publish_date_gte=1730304142` |

Exemple d’appel complet :
```
GET http://localhost:8080/api/v1/articles?page=1&sort=id,asc
```

---

## Gestion des utilisateurs

### Création de compte
Les utilisateurs peuvent créer un compte via le formulaire d’inscription.

### Compte administrateur
Le compte administrateur est généré automatiquement via la commande :
```bash
php artisan db:seed
```
Il possède **tous les droits** sur la plateforme.

---

## Récupération des articles externes

Une commande artisan permet de **récupérer les articles depuis toutes les sources configurées** et de les insérer en base de données :

```bash
php artisan press:fetch
```

> ⚠️ Assure-toi d’avoir configuré les clés API et sources nécessaires dans ton `.env`.

---

## Commandes utiles

| Commande | Description |
|-----------|--------------|
| `php artisan migrate:fresh --seed` | Réinitialise complètement la base de données |
| `php artisan press:fetch` | Récupère et insère les articles |
| `php artisan serve` | Lance le serveur Laravel localement (si pas via Docker) |
| `php artisan tinker` | Console interactive pour tester le code |

---


## Tests (optionnel)
Pour lancer les tests unitaires :
Attention, IL faut rentrer dans le container php-fpm appelé laravel_app

```bash
docker exec -it laravel_app bash
```

ensuite vous pouvez jouer la commande test

```bash
php artisan test
```
