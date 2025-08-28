# PetClinic Application - Docker Compose

This Docker Compose configuration runs the Spring PetClinic application with a MySQL database backend.

## Services

### PetClinic Application (`petclinic-app`)
- **Image**: `petclinic` (custom application image)
- **Port**: 30:30 (application accessible on port 30)
- **Dependencies**: MySQL database service
- **Configuration**: Loaded from `config.env`

### MySQL Database (`db`)
- **Image**: `mysql:9.2`
- **Port**: 3306:3306 (standard MySQL port)
- **Health Check**: Configured with ping test every 30 seconds
- **Data Persistence**: Uses named volume `mysql`
- **Configuration**: Loaded from `secrets.env`

## Prerequisites

1. Docker and Docker Compose installed
2. Custom `petclinic` image built or available
3. Environment configuration files (see below)

## Configuration Files

### `config.env` (Application Configuration)
Create this file with the following content:
```env
SPRING_DATASOURCE_URL=jdbc:mysql://db:3306/petclinic
SPRING_DATASOURCE_USERNAME=petclinic
SPRING_DATASOURCE_PASSWORD=petclinic
SPRING_PROFILES_ACTIVE=mysql
SERVER_PORT=30
```

### `secrets.env` (Database Configuration)
Create this file with the following content:
```env
MYSQL_USER=petclinic
MYSQL_PASSWORD=petclinic
MYSQL_ROOT_PASSWORD=root
MYSQL_DATABASE=petclinic
```

## Usage

### Starting the Application
```bash
docker-compose up -d
```

### Stopping the Application
```bash
docker-compose down
```

### Viewing Logs
```bash
# All services
docker-compose logs

# Specific service
docker-compose logs petclinic-app
docker-compose logs db
```

### Health Check
The MySQL service includes a health check that verifies the database is responsive. You can check the status with:
```bash
docker-compose ps
```

## Access Points

- **PetClinic Application**: http://localhost:30
- **MySQL Database**: localhost:3306 (for external database tools)

## Data Persistence

The MySQL database data is persisted using a named Docker volume (`mysql`). This ensures data survives container restarts and updates.

## Network

Both services communicate through a custom bridge network named `net`, allowing the application to connect to the database using the service name `db` as the hostname.

## Troubleshooting

1. **Port conflicts**: Ensure ports 30 and 3306 are not in use by other applications
2. **Database connection issues**: Wait for the MySQL health check to pass before the application fully starts
3. **Missing environment files**: Ensure both `config.env` and `secrets.env` files exist in the same directory as `docker-compose.yml`
4. **Custom image**: Verify the `petclinic` image is available locally or in your registry

## Security Notes

- The database credentials are stored in plain text in the environment files
- Consider using Docker secrets or external secret management for production deployments
- The MySQL root password is set to a simple value - change this for production use