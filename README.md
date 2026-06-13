# cyber-deck-back

API backend du jeu Cyber Deck, construite avec Symfony 7.4, Doctrine ORM, PostgreSQL et authentification JWT.

## Stack

- PHP 8.3
- Symfony 7.4
- Doctrine ORM
- PostgreSQL 16
- LexikJWTAuthenticationBundle
- Docker / Docker Compose
- Nginx

## Fonctionnalités

- Authentification API avec inscription, login JWT et endpoint profil
- Consultation des cartes en lecture seule
- CRUD complet sur les decks du joueur connecté
- Historique de parties en lecture + création, sans modification ni suppression
- Persistance PostgreSQL via Doctrine

## Structure API

L’API est exposée sous le préfixe /api.

Règles d’accès:

- Public: /api/auth/register, /api/auth/login
- Authentifié JWT: tout le reste sous /api

## Lancer le projet

### 1. Démarrer les conteneurs

```bash
docker compose up --build -d
```

Services exposés:

- API HTTP via Nginx: http://localhost:8080
- PostgreSQL: localhost:5432

### 2. Installer les dépendances si nécessaire

Si le vendor n’est pas déjà présent:

```bash
docker compose exec backend composer install
```

### 3. Initialiser la base

```bash
docker compose exec backend php bin/console doctrine:migrations:migrate -n
```

### 4. Vérifier que l’API répond

```bash
curl http://localhost:8080/api/cards
```

Cette route exige un JWT, donc une réponse 401 est normale tant que vous n’êtes pas authentifié.

## Variables d’environnement

Les variables principales sont définies dans .env et .env.dev.

En développement, les valeurs actuellement utilisées sont:

- DATABASE_URL=postgresql://cardgame:cardgame@db:5432/cardgame?serverVersion=16&charset=utf8
- JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
- JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
- JWT_PASSPHRASE défini dans .env.dev

Note: les clés JWT sont attendues dans config/jwt.

## Authentification

### Inscription

Route:

```http
POST /api/auth/register
```

Exemple de payload:

```json
{
	"email": "player@example.com",
	"username": "player1",
	"password": "secret123"
}
```

### Login

Route:

```http
POST /api/auth/login
```

Important: le login attend username et password, pas email.

Exemple de payload:

```json
{
	"username": "player1",
	"password": "secret123"
}
```

Le token JWT retourné doit être envoyé dans l’en-tête Authorization:

```http
Authorization: Bearer <token>
```

### Profil courant

```http
GET /api/auth/me
```

## Endpoints

## Cards

Les cartes sont fixes en base. Aucun endpoint de création, modification ou suppression n’est exposé.

### Lister toutes les cartes

```http
GET /api/cards
```

### Voir une carte

```http
GET /api/cards/{id}
```

Réponse type:

```json
{
	"id": 1,
	"name": "Firewall Sentinel",
	"type": "unit",
	"color": "blue",
	"attack": 3,
	"hp": 5,
	"heal": null,
	"description": "Protects the core network."
}
```

## Decks

Les decks sont toujours filtrés par utilisateur connecté. Un joueur ne peut accéder qu’à ses propres decks.

### Lister mes decks

```http
GET /api/decks
```

### Créer un deck

```http
POST /api/decks
```

Payload:

```json
{
	"name": "Blue Control",
	"color": "blue",
	"isActive": true,
	"cardIds": [1, 2, 3, 4]
}
```

### Voir un deck

```http
GET /api/decks/{id}
```

### Modifier un deck

```http
PUT /api/decks/{id}
```

Payload:

```json
{
	"name": "Blue Control v2",
	"color": "blue",
	"isActive": false,
	"cardIds": [1, 3, 5, 8]
}
```

### Supprimer un deck

```http
DELETE /api/decks/{id}
```

Réponse de détail type:

```json
{
	"id": 4,
	"name": "Blue Control",
	"color": "blue",
	"isActive": true,
	"createdAt": "2026-06-13T12:00:00+00:00",
	"cards": [
		{
			"id": 1,
			"name": "Firewall Sentinel",
			"type": "unit",
			"color": "blue"
		}
	]
}
```

## Games

Les parties sont immuables après création. Aucun endpoint update/delete n’est exposé.

### Lister mon historique

```http
GET /api/games
```

### Sauvegarder une partie

```http
POST /api/games
```

Payload minimal:

```json
{
	"result": "win",
	"turnsCount": 12
}
```

Payload avec date explicite:

```json
{
	"result": "loss",
	"turnsCount": 9,
	"playedAt": "2026-06-13T14:00:00+00:00"
}
```

### Voir une partie

```http
GET /api/games/{id}
```

Réponse type:

```json
{
	"id": 7,
	"result": "win",
	"turnsCount": 12,
	"playedAt": "2026-06-13T14:00:00+00:00"
}
```

## Validation et erreurs

Codes de réponse typiques:

- 200: succès
- 201: ressource créée
- 204: suppression réussie
- 400: payload invalide ou cardIds inconnus
- 401: non authentifié
- 404: ressource introuvable ou hors périmètre utilisateur

Exemple d’erreur:

```json
{
	"error": "Deck not found."
}
```

## Commandes utiles

```bash
docker compose exec backend php bin/console debug:router
docker compose exec backend php bin/console doctrine:migrations:status
docker compose exec backend php bin/console cache:clear
docker compose exec backend php bin/console doctrine:query:sql "SELECT * FROM card"
```

## Notes d’implémentation

- Les endpoints métier Card, Deck et Game sont implémentés avec des DTO et des handlers dans src/Dto et src/Handler.
- Les contrôleurs REST sont dans src/Controller.
- Les decks et games sont résolus par utilisateur connecté côté backend, pas seulement côté client.

## État actuel

Routes actuellement disponibles:

- /api/auth/register
- /api/auth/login
- /api/auth/me
- /api/cards
- /api/cards/{id}
- /api/decks
- /api/decks/{id}
- /api/games
- /api/games/{id}
