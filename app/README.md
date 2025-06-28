# Gateway microservices

Accepts requests and forwards them to other services as needed.

## Deployment

### Build and run

### First create `.env` file and copy the contents from `.env.example`

#### Build and run docker images (using docker tools):

```
docker compose up -d --build
```

Connect to docker container

```
docker exec -it at-app bash
```

Then run composer to install all dependencies inside container.

```
composer install
```
Then run migration and seeder inside container.

```
php artisan migrate
```
