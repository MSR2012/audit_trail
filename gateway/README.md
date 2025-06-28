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
docker exec -it at-gateway-app bash
```

Then run composer to install all dependencies inside container.

```
composer install
```

After all microservices is up and running, then follow api docs under Docs folder.
