# Pet Clinic Multi-Database Architecture

A Spring Boot Pet Clinic application demonstrating multi-database connectivity across multiple Docker networks using MySQL databases and Nginx proxy for cross-network database access.

## Architecture Overview 

```
Network 1 (net1)                    Network 2 (net2)
┌─────────────────────────┐         ┌─────────────────────────┐
│  petclinic1 (port 55)   │         │  petclinic3 (port 57)   │
│  └─> mysql-db1:3306     │         │  └─> mysql-db3:3306     │
│                         │         │       OR                │
│  petclinic2 (port 56)   │         │  └─> nginx-proxy:3306   │
│  └─> mysql-db2:3306     │         │     └─> mysql-db1:3306  │
│                         │         │                         │
│  mysql-db1 (port 3307)  │         │  mysql-db3 (port 3308)  │
│  mysql-db2 (port 3308)  │         │                         │
│                         │         │                         │
│  nginx-proxy            │◄────────┤  nginx-proxy            │
└─────────────────────────┘         └─────────────────────────┘
```

## Features

- **Multi-Network Setup**: Applications distributed across two Docker networks
- **Database Isolation**: Each application can connect to its dedicated database
- **Cross-Network Database Access**: Nginx proxy enables database connectivity across networks
- **Host-Direct Connection**: Applications can connect to databases via host networking
- **Volume Persistence**: MySQL data persisted using Docker volumes
- **Hot Configuration Reload**: Nginx configuration can be updated without container rebuild

## Prerequisites

- Docker installed and running
- Docker networks created:
  ```bash
  docker network create net1
  docker network create net2
  ```

## Quick Start

### 1. Setup Network 1 (net1)

#### Database Containers
```bash
# MySQL Database 1
docker run -d \
  --name mysql-db1 \
  --network net1 \
  -p 3307:3306 \
  -v mysql1:/var/lib/mysql \
  -e MYSQL_ROOT_PASSWORD=root \
  -e MYSQL_DATABASE=petclinic \
  -e MYSQL_USER=petclinic \
  -e MYSQL_PASSWORD=petclinic \
  mysql:9.2

# MySQL Database 2
docker run -d \
  --name mysql-db2 \
  --network net1 \
  -p 3308:3306 \
  -v mysql2:/var/lib/mysql \
  -e MYSQL_ROOT_PASSWORD=root \
  -e MYSQL_DATABASE=petclinic \
  -e MYSQL_USER=petclinic \
  -e MYSQL_PASSWORD=petclinic \
  mysql:9.2
```

#### Application Containers
```bash
# Pet Clinic App 1 (connecting to mysql-db1)
docker run -d \
  --name petclinic1 \
  --network net1 \
  -p 55:55 \
  -e SPRING_DATASOURCE_URL=jdbc:mysql://mysql-db1:3306/petclinic \
  -e SPRING_PROFILES_ACTIVE=mysql \
  petclinic --server.port=55

# Pet Clinic App 2 (connecting to mysql-db2)
docker run -d \
  --name petclinic2 \
  --network net1 \
  -p 56:56 \
  -e SPRING_DATASOURCE_URL=jdbc:mysql://mysql-db2:3306/petclinic \
  -e SPRING_PROFILES_ACTIVE=mysql \
  petclinic --server.port=56
```

### 2. Setup Network 2 (net2)

#### Database Container
```bash
# MySQL Database 3
docker run -d \
  --name mysql-db3 \
  --network net2 \
  -p 3309:3306 \
  -v mysql3:/var/lib/mysql \
  -e MYSQL_ROOT_PASSWORD=root \
  -e MYSQL_DATABASE=petclinic \
  -e MYSQL_USER=petclinic \
  -e MYSQL_PASSWORD=petclinic \
  mysql:9.2
```

#### Application Container (Option 1: Direct Connection)
```bash
# Pet Clinic App 3 (direct connection to mysql-db3)
docker run -d \
  --name petclinic3 \
  --network net2 \
  -p 57:57 \
  -e SPRING_DATASOURCE_URL=jdbc:mysql://mysql-db3:3306/petclinic \
  -e SPRING_PROFILES_ACTIVE=mysql \
  petclinic --server.port=57
```

### 3. Setup Nginx Proxy for Cross-Network Access

#### Create Nginx Configuration
Create a directory for Nginx configuration (e.g., `/home/shebl/DEPI/task5/`) and add the following files:

**Dockerfile**
```dockerfile
FROM nginx:1.28-alpine3.21-otel 

WORKDIR /etc/nginx/

COPY . .

CMD [ "nginx", "-g", "daemon off;" ]
```

**nginx.conf**
```nginx
events { }

stream { 
    upstream mysql_backend {
        server mysql-db1:3306;
    }

    server {
        listen 3306;
        proxy_pass mysql_backend;
    }
}
```

#### Build and Run Nginx Proxy
```bash
# Build the Nginx proxy image
docker build -t nginx-proxy /path/to/nginx/config

# Run Nginx proxy connected to both networks
docker run -d \
  --name=nginx-proxy \
  --network net1 \
  -v /home/shebl/DEPI/task5:/etc/nginx \
  nginx-proxy

# Connect to second network
docker network connect net2 nginx-proxy
```

#### Application Container (Option 2: Cross-Network Connection)
```bash
# Pet Clinic App 3 (connecting to mysql-db1 via nginx proxy)
docker run -d \
  --name petclinic3 \
  --network net2 \
  -p 57:57 \
  -e SPRING_DATASOURCE_URL=jdbc:mysql://nginx-proxy:3306/petclinic \
  -e SPRING_PROFILES_ACTIVE=mysql \
  petclinic --server.port=57
```

### 4. Host-Direct Database Access (Alternative Method)

You can also connect applications directly to databases running on different networks using Docker's host networking feature. This method uses `host.docker.internal` to access databases via their host-mapped ports.

#### Database Setup with Unique Names
To avoid conflicts when using host-direct access, use unique database names:

```bash
# Update MySQL Database 1 with unique database name
docker run -d \
  --name mysql-db1 \
  --network net1 \
  -p 3307:3306 \
  -v mysql1:/var/lib/mysql \
  -e MYSQL_ROOT_PASSWORD=root \
  -e MYSQL_DATABASE=petclinic1 \
  -e MYSQL_USER=petclinic \
  -e MYSQL_PASSWORD=petclinic \
  mysql:9.2

# Update MySQL Database 2 with unique database name
docker run -d \
  --name mysql-db2 \
  --network net1 \
  -p 3308:3306 \
  -v mysql2:/var/lib/mysql \
  -e MYSQL_ROOT_PASSWORD=root \
  -e MYSQL_DATABASE=petclinic2 \
  -e MYSQL_USER=petclinic \
  -e MYSQL_PASSWORD=petclinic \
  mysql:9.2

# Update MySQL Database 3 with unique database name
docker run -d \
  --name mysql-db3 \
  --network net2 \
  -p 3309:3306 \
  -v mysql3:/var/lib/mysql \
  -e MYSQL_ROOT_PASSWORD=root \
  -e MYSQL_DATABASE=petclinic3 \
  -e MYSQL_USER=petclinic \
  -e MYSQL_PASSWORD=petclinic \
  mysql:9.2
```

#### Application with Host-Direct Connection
```bash
# Pet Clinic App connecting to mysql-db1 via host network
docker run -d \
  --name petclinic3-host-direct \
  --network net2 \
  -p 58:58 \
  -e SPRING_DATASOURCE_URL=jdbc:mysql://host.docker.internal:3307/petclinic1 \
  -e SPRING_PROFILES_ACTIVE=mysql \
  petclinic --server.port=58

# Alternative: Connect to mysql-db2 via host network
docker run -d \
  --name petclinic4-host-direct \
  --network net2 \
  -p 59:59 \
  -e SPRING_DATASOURCE_URL=jdbc:mysql://host.docker.internal:3308/petclinic2 \
  -e SPRING_PROFILES_ACTIVE=mysql \
  petclinic --server.port=59
```

**Note**: When using `host.docker.internal`, make sure to:
- Use the host-mapped port (3307, 3308, 3309) not the container port (3306)
- Match the database name in the connection URL with the one created in the database container
- Ensure your Docker version supports `host.docker.internal` (Docker Desktop on Windows/Mac, or Docker 20.10+ on Linux with additional configuration)

## Configuration Management

### Hot Reload Nginx Configuration
When you need to change the database backend (e.g., switch from mysql-db1 to mysql-db2):

1. Update the `nginx.conf` file:
```nginx
upstream mysql_backend {
    server mysql-db2:3306;  # Changed from mysql-db1
}
```

2. Reload Nginx without restarting:
```bash
docker exec nginx-proxy nginx -s reload
```

## Access Points

| Application | Port | Database Connection | Method |
|-------------|------|-------------------|---------|
| petclinic1  | 55   | mysql-db1 (direct) | Network |
| petclinic2  | 56   | mysql-db2 (direct) | Network |
| petclinic3  | 57   | mysql-db3 (direct) OR mysql-db1 (via nginx-proxy) | Network/Proxy |
| petclinic3-host-direct | 58 | mysql-db1 (via host) | Host-Direct |
| petclinic4-host-direct | 59 | mysql-db2 (via host) | Host-Direct |

| Database | Host Port | Internal Port | Volume | Database Name |
|----------|-----------|---------------|--------|---------------|
| mysql-db1 | 3307 | 3306 | mysql1 | petclinic1 |
| mysql-db2 | 3308 | 3306 | mysql2 | petclinic2 |
| mysql-db3 | 3309 | 3306 | mysql3 | petclinic3 |

## Monitoring and Management

### Check Container Status
```bash
docker ps -a
```

### View Logs
```bash
# Application logs
docker logs petclinic1
docker logs petclinic2
docker logs petclinic3

# Database logs
docker logs mysql-db1
docker logs mysql-db2
docker logs mysql-db3

# Nginx proxy logs
docker logs nginx-proxy
```

### Database Connection Testing
```bash
# Test direct database connection
docker exec -it mysql-db1 mysql -u petclinic -ppetclinic petclinic1

# Test proxy connection
docker run -it --rm --network net2 mysql:9.2 \
  mysql -h nginx-proxy -P 3306 -u petclinic -ppetclinic petclinic1

# Test host-direct connection
docker run -it --rm mysql:9.2 \
  mysql -h host.docker.internal -P 3307 -u petclinic -ppetclinic petclinic1
```

## Troubleshooting

### Common Issues

1. **Container won't start**: Check if ports are already in use
   ```bash
   netstat -tlnp | grep :PORT_NUMBER
   ```

2. **Database connection failed**: Verify network connectivity
   ```bash
   docker exec petclinic1 ping mysql-db1
   ```

3. **Nginx proxy not working**: Check configuration syntax
   ```bash
   docker exec nginx-proxy nginx -t
   ```



### Cleanup
```bash
# Stop and remove all containers
docker stop petclinic1 petclinic2 petclinic3 petclinic3-host-direct petclinic4-host-direct \
  mysql-db1 mysql-db2 mysql-db3 nginx-proxy
docker rm petclinic1 petclinic2 petclinic3 petclinic3-host-direct petclinic4-host-direct \
  mysql-db1 mysql-db2 mysql-db3 nginx-proxy

# Remove volumes (WARNING: This will delete all data)
docker volume rm mysql1 mysql2 mysql3

# Remove networks
docker network rm net1 net2
```

## Use Cases

This architecture is useful for:

- **Development Environment**: Testing applications with different database configurations
- **Load Distribution**: Spreading database load across multiple instances  
- **Network Segmentation**: Isolating applications while maintaining selective connectivity
- **Migration Testing**: Gradually moving applications between different database backends
- **Disaster Recovery**: Quick failover between database instances
