# Gateway microservices

Accepts requests and forwards them to other services as needed.

## Deployment

### Build and run

#### Build and run docker images (using docker tools):

```
docker compose up -d --build
```
Run below command to check container name

```
docker ps
```
## if container yet to start successfully, then run below command outside container
```
npm install
```
And then below command again
```
docker compose up -d --build
```
## Connect to docker container

```
docker exec -it frontend-container sh
```

Then run npm to install all dependencies inside container.

```
npm install
```

After all microservices is up and running, then browse below url.

```
http://localhost:8888/
```

## Credentials for sample login
### Admin
```
email: admin@admin.com
pass: admin@123456
```
### User
```
email: user@user.com
pass: user@123456
```
### User 2
```
email: user2@user.com
pass: admin@123456
```