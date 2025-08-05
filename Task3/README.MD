# Docker Setup Guide

## Installation

To install Docker, please follow the official installation guide: [Docker Installation Documentation](https://docs.docker.com/get-docker/)

## Pulling Docker Images

To pull a Docker image from a registry, use the following command:

```bash
docker pull <image-name>
```

### Example: Pulling Our DevOps Image

```bash
docker pull shebl22/devops
```

## Accessing Docker Hub

Docker Hub is the official registry for Docker images. You can access it at: [https://hub.docker.com](https://hub.docker.com)

To search for images directly from the command line:

```bash
docker search <image-name>
```

To view our specific image on Docker Hub, visit: [shebl22/devops on Docker Hub](https://hub.docker.com/r/shebl22/devops)

## Running Docker Containers

### Basic Run Command

To run a Docker container from an image:

```bash
docker run <image-name>
```

### Running with Port Mapping

To run a container with port mapping (exposing container ports to host):

```bash
docker run -p <host-port>:<container-port> <image-name>
```

Example with our DevOps image on port 8080:

```bash
docker run -p 8080:8080 shebl22/devops
```

### Running with Interactive Shell

To run a container with an interactive bash shell:

```bash
docker run -it <image-name> /bin/bash
```

### Complete Example: Port Mapping + Interactive Shell

To run our DevOps image with port mapping and access to bash shell:

```bash
docker run -it -p 8080:8080 shebl22/devops /bin/bash
```

**Command breakdown:**
- `-it` : Interactive mode with pseudo-TTY (allows interaction with the container)
- `-p 8080:8080` : Maps port 8080 from container to host port 8080
- `shebl22/devops` : The image name
- `/bin/bash` : Command to run inside the container (bash shell)

## Saving Changes and Publishing

### Committing Container Changes to an Image

After making changes to a running container, save those changes to a new image:

```bash
docker commit <container-id> <new-image-name>
```

Example:
```bash
docker commit abc123def456 shebl22/devops-updated
```

### Tagging Images for Docker Hub

Before pushing to Docker Hub, tag your image with the proper repository name:

```bash
docker tag <image-name> <dockerhub-username>/<repository-name>:<tag>
```

Example:
```bash
docker tag shebl22/devops-updated shebl22/devops:latest
```

### Pushing to Docker Hub

Push your tagged image to Docker Hub:

```bash
docker push <dockerhub-username>/<repository-name>:<tag>
```

Example:
```bash
docker push shebl22/devops:latest
```

**Note:** You need to be logged in to Docker Hub first using `docker login`

## Saving and Loading Images

### Saving Images to File

Export a Docker image to a tar file:

```bash
docker save <image_name> -o <filename>.tar
```

Example:
```bash
docker save shebl22/devops -o ./devops.tar
```

### Loading Images from File

Import a Docker image from a tar file:

```bash
docker load -i <filename>.tar
```

Example:
```bash
docker load -i ./devops.tar
```

**Use cases:**
- Transferring images between systems without internet
- Creating backups of Docker images
- Sharing images via file transfer