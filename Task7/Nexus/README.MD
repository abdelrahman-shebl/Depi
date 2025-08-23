# Nexus Docker Registry Setup - Complete Guide

This guide walks you through setting up Nexus Repository Manager as a Docker registry using Docker Compose, from initial setup to pushing and pulling images.

## Table of Contents
- [Prerequisites](#prerequisites)
- [Phase 1: Setup Nexus with Docker Compose](#phase-1-setup-nexus-with-docker-compose)
- [Phase 2: Push Images to Nexus](#phase-2-push-images-to-nexus)
- [Phase 3: Pull Images from Nexus](#phase-3-pull-images-from-nexus)
- [Phase 4: Switch Between Nexus & Docker Hub](#phase-4-switch-between-nexus--docker-hub)
- [Phase 5: Optional Security Enhancements](#phase-5-optional-secure-with-ssl--auth)
- [Troubleshooting](#troubleshooting)

## Prerequisites

- Docker and Docker Compose installed
- At least 4GB of available RAM for Nexus
- Ports 8081 and 5000 available on your system

## **PHASE 1: Setup Nexus with Docker Compose**

### 1. Create `docker-compose.yml`

Create a new directory for your Nexus setup and add the following `docker-compose.yml`:

```yaml
version: '3.8'
services:
  nexus:
    image: sonatype/nexus3
    container_name: nexus
    restart: always
    ports:
      - "8081:8081"  # Nexus UI
      - "6000:6000"  # Docker Registry (hosted)
    volumes:
      - nexus-data:/nexus-data

volumes:
  nexus-data:
```

### 2. Start Nexus

```bash
docker compose up -d
```

Wait for Nexus to fully start (this can take 2-3 minutes). Check the logs:

```bash
docker compose logs -f nexus
```

### 3. Access Nexus UI

Open your browser and navigate to:
```
http://localhost:8081
```

### 4. Get Initial Admin Password

```bash
docker exec -it nexus cat /nexus-data/admin.password
```

Login with:
- **Username:** `admin`
- **Password:** (the output from the command above)

Complete the setup wizard and change the admin password when prompted.

### 5. Create a Docker (hosted) repository in Nexus

In the Nexus UI:

1. Go to **Administration** (gear icon) → **Repository** → **Repositories**
2. Click **Create repository**
3. Select **docker (hosted)**
4. Configure the repository:
   - **Name:** `docker-hosted`
   - **HTTP Port:** `5000`
   - **Allow anonymous docker pull:** Check this for easier access
   - **Enable Docker V1 API:** Uncheck (recommended)
5. Click **Create repository**

### 6. Configure Docker to Trust Nexus (HTTP mode)

Since we're using HTTP (not HTTPS), Docker needs to trust our registry as "insecure."

#### On Linux:
```bash
sudo nano /etc/docker/daemon.json
```

Add or modify the file to include:
```json
{
  "insecure-registries": ["localhost:5000"]
}
```

Restart Docker:
```bash
sudo systemctl restart docker
```

#### On Docker Desktop (Windows/Mac):
1. Open Docker Desktop
2. Go to **Settings** → **Docker Engine**
3. Add the insecure registry configuration:
```json
{
  "insecure-registries": ["localhost:5000"]
}
```
4. Click **Apply & restart**

## **PHASE 2: Push Images to Nexus**

### 1. Build Your Image

Example with a simple Dockerfile:

```dockerfile
FROM alpine:latest
RUN echo "Hello from Nexus!" > /hello.txt
CMD cat /hello.txt
```

Build the image:
```bash
docker build -t myapp:latest .
```

### 2. Tag for Nexus

```bash
docker tag myapp:latest localhost:5000/myapp:latest
```

### 3. Push to Nexus

If you enabled anonymous access, you can push directly:
```bash
docker push localhost:5000/myapp:latest
```

If authentication is required, login first:
```bash
docker login localhost:5000
# Enter your Nexus username and password
docker push localhost:5000/myapp:latest
```

### 4. Verify in Nexus UI

1. Go to **Browse** in Nexus UI
2. Click on **docker-hosted**
3. You should see your `myapp` image listed

## **PHASE 3: Pull Images from Nexus**

### Pull Your Image

```bash
docker pull localhost:5000/myapp:latest
```

### Run the Image

```bash
docker run --rm localhost:5000/myapp:latest
```

## **PHASE 4: Switch Between Nexus & Docker Hub**

### Option 1: Use Different Tags

Tag and push to both registries:

```bash
# For Nexus
docker tag myapp:latest localhost:5000/myapp:latest
docker push localhost:5000/myapp:latest

# For Docker Hub
docker tag myapp:latest your-dockerhub-username/myapp:latest
docker push your-dockerhub-username/myapp:latest
```