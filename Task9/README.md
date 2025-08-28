# Docker Installation Ansible Playbook

This Ansible playbook automates the installation of Docker on Ubuntu and CentOS systems, and deploys a Petclinic application container.

## Overview

The playbook performs the following tasks:
- Installs Docker CE on Ubuntu and CentOS systems
- Configures Docker repositories and GPG keys
- Starts and enables the Docker service
- Deploys a Petclinic application from a pre-built Docker image

## Requirements

- Ansible installed on the control machine
- Target hosts running Ubuntu or CentOS
- SSH access to target hosts with sudo privileges
- Petclinic Docker image tar file located at `/home/abdelrahman/Desktop/DEPI/Submissions/Task9/petclinic.tar`

## Supported Operating Systems

- **Ubuntu**: All versions supported by Docker CE
- **CentOS**: All versions supported by Docker CE

## Playbook Structure

### Ubuntu Installation Block
- Updates package cache
- Installs prerequisites (ca-certificates, curl)
- Adds Docker's official GPG key and repository
- Installs Docker CE packages and plugins

### CentOS Installation Block  
- Installs DNF plugins
- Adds Docker CE repository
- Installs Docker CE packages and plugins
- Starts and enables Docker service

### Docker Run Block
- Creates directory structure for image storage
- Copies Petclinic image tar file to target
- Loads the Docker image
- Stops any conflicting containers
- Runs Petclinic container on port 8080

## Usage

1. Ensure your inventory file contains the target hosts
2. Verify the Petclinic image tar file exists at the specified source path
3. Run the playbook:

```bash
ansible-playbook -i inventory.ini play.yml --ask-become-pass 
```

## Configuration

### Variables
The playbook uses hardcoded paths that may need adjustment:
- Source image path: `/home/abdelrahman/Desktop/DEPI/Submissions/Task9/petclinic.tar`
- Destination directory: `/home/abdelrahman/Desktop/Image`

### Ports
The Petclinic application will be accessible on port 8080 of the target hosts.

## Post-Installation

After successful execution:
- Docker will be installed and running
- The current user will be added to the docker group (requires re-login to take effect)
- Petclinic application will be running at `http://target-host:8080`

## Notes

- The playbook uses `become: true` to execute tasks with sudo privileges
- Existing Docker containers are stopped to avoid port conflicts
- The image tar file is removed after loading to save disk space
- Facts gathering is enabled to detect the operating system

