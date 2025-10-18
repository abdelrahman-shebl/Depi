# Kubernetes PetClinic Deployment

This repository contains Kubernetes manifests for deploying a PetClinic application with a MySQL database backend.

## Architecture Overview

The application consists of:
- **Frontend**: Spring Boot PetClinic application (3 replicas)
- **Database**: MySQL 8.0 StatefulSet (3 replicas with persistent storage)
- **Ingress**: Routes external traffic to the frontend service
- **Secrets Management**: Sealed Secrets for secure secret storage

## Project Structure

```
├── namespace/
│   └── ns.yaml                    # petclinic namespace
├── configmaps/
│   └── cm.yaml                    # Frontend configuration
├── secrets/
│   ├── db-secrets.yaml           # Database secrets (plain)
│   ├── db-sealed-secret.yaml     # Database secrets (sealed)
│   ├── front-secrets.yaml        # Frontend secrets (plain)
│   └── front-sealed-secret.yaml  # Frontend secrets (sealed)
├── services/
│   ├── db-svc.yaml               # Headless service for StatefulSet
│   └── front-svc.yaml            # ClusterIP service for frontend
├── deployments/
│   └── front.yaml                # Frontend deployment
├── statefullsets/
│   └── ss.yaml                   # MySQL StatefulSet
├── ingress/
│   └── ingress.yaml              # Ingress configuration
└── kustomization.yaml            # Kustomize configuration
```

## Component Details

### Namespace
- **Name**: `petclinic`
- All resources are deployed in this namespace

### Frontend Deployment
- **Image**: `petclinic:latest`
- **Replicas**: 3
- **Port**: 5000
- **Configuration**: Loaded from ConfigMap and Secrets
- **Profile**: MySQL (Spring profile)

### Database StatefulSet
- **Image**: `mysql:8.0`
- **Replicas**: 3
- **Port**: 3306
- **Storage**: 50Mi per replica (persistent volume)
- **Service**: Headless service for StatefulSet pod discovery

### Services
- **front-svc**: ClusterIP service exposing port 80 (maps to container port 5000)
- **db-svc**: Headless service for MySQL StatefulSet (ClusterIP: None)

### Ingress
- **Host**: `shebl.com`
- **Path**: `/` (all traffic)
- **Backend**: Routes to `front-svc:80`

### Configuration
**ConfigMap** (`front-cm`):
- `SERVER_PORT`: 5000
- `SPRING_PROFILES_ACTIVE`: mysql

**Secrets** (Base64 encoded):
- Database credentials (user, password, root password, database name)
- Frontend datasource configuration (JDBC URL, username, password)

## Prerequisites

- Kubernetes cluster (1.19+)
- kubectl CLI
- Helm 3.x
- kubeseal CLI

## Installation

### 1. Install Helm

#### Linux
```bash
curl https://raw.githubusercontent.com/helm/helm/main/scripts/get-helm-3 | bash
```

#### macOS
```bash
brew install helm
```

#### Windows
```powershell
choco install kubernetes-helm
```

Verify installation:
```bash
helm version
```

### 2. Install Sealed Secrets Controller

Add the Sealed Secrets Helm repository:
```bash
helm repo add sealed-secrets https://bitnami-labs.github.io/sealed-secrets
helm repo update
```

Install the Sealed Secrets controller:
```bash
helm install sealed-secrets sealed-secrets/sealed-secrets \
  --namespace kube-system 
```

Verify the controller is running:
```bash
kubectl get pods -n kube-system | grep sealed-secrets
```

### 3. Install kubeseal CLI

#### Linux
```bash
KUBESEAL_VERSION='0.24.0'
wget "https://github.com/bitnami-labs/sealed-secrets/releases/download/v${KUBESEAL_VERSION}/kubeseal-${KUBESEAL_VERSION}-linux-amd64.tar.gz"
tar -xvzf kubeseal-${KUBESEAL_VERSION}-linux-amd64.tar.gz kubeseal
sudo install -m 755 kubeseal /usr/local/bin/kubeseal
```

#### macOS
```bash
brew install kubeseal
```

#### Windows
```powershell
choco install kubeseal
```

Verify installation:
```bash
kubeseal --version
```

## Sealing Secrets

Sealed Secrets encrypt your Kubernetes secrets so they can be safely stored in Git. Only the Sealed Secrets controller in your cluster can decrypt them.

### How It Works

1. You create a standard Kubernetes Secret manifest
2. Use `kubeseal` to encrypt it into a SealedSecret
3. The SealedSecret can be safely committed to Git
4. When applied to the cluster, the controller decrypts it back into a Secret

### Seal Frontend Secrets

```bash
kubeseal \
  --controller-name=sealed-secrets \
  --controller-namespace=kube-system \
  -n petclinic \
  --format yaml \
  < secrets/front-secrets.yaml > secrets/front-sealed-secret.yaml
```

### Seal Database Secrets

```bash
kubeseal \
  --controller-name=sealed-secrets \
  --controller-namespace=kube-system \
  -n petclinic \
  --format yaml \
  < secrets/db-secrets.yaml > secrets/db-sealed-secret.yaml
```

### Parameters Explained

- `--controller-name`: Name of the Sealed Secrets controller
- `--controller-namespace`: Namespace where the controller is installed
- `-n petclinic`: Target namespace for the secret (important for namespace-scoped encryption)
- `--format yaml`: Output format (YAML)

## Deployment

### Using kubectl with Kustomize

Deploy all resources:
```bash
kubectl apply -k .
```

### Verify Deployment

Check all resources:
```bash
kubectl get all -n petclinic
```

Check secrets are created:
```bash
kubectl get secrets -n petclinic
```

Check persistent volumes:
```bash
kubectl get pvc -n petclinic
```

### Access the Application

1. Add `shebl.com` to your `/etc/hosts` file pointing to your ingress controller IP
2. Access the application at `http://shebl.com`

## Secret Values

The sealed secrets contain (Base64 encoded):

**Database Secrets**:
- MYSQL_USER: `shebl`
- MYSQL_PASSWORD: `P@ssWord`
- MYSQL_ROOT_PASSWORD: `root`
- MYSQL_DATABASE: `petclinic`

**Frontend Secrets**:
- SPRING_DATASOURCE_URL: `jdbc:mysql://db-svc:3306/petclinic`
- SPRING_DATASOURCE_USERNAME: `shebl`
- SPRING_DATASOURCE_PASSWORD: `P@ssWord`

> ⚠️ **Security Note**: Never commit plain secret files to Git. Always use sealed secrets in version control.

## Troubleshooting

### Check pod logs
```bash
kubectl logs -n petclinic <pod-name>
```

### Check sealed secrets controller
```bash
kubectl logs -n kube-system -l app.kubernetes.io/name=sealed-secrets
```

### Reseal secrets after changes
If you modify the plain secrets, you must reseal them before applying:
```bash
# Make changes to secrets/db-secrets.yaml
# Then reseal:
kubeseal --controller-name=sealed-secrets \
  --controller-namespace=kube-system \
  -n petclinic --format yaml \
  < secrets/db-secrets.yaml > secrets/db-sealed-secret.yaml
```

## Cleanup

Remove all resources:
```bash
kubectl delete -k .
```

Remove namespace:
```bash
kubectl delete namespace petclinic
```

## Notes

- StatefulSet uses persistent volumes; data persists across pod restarts
- The ingress requires an Ingress Controller (e.g., nginx-ingress) to be installed
- Sealed secrets are namespace-scoped by default for security
- The frontend uses `imagePullPolicy: IfNotPresent` - ensure the image exists locally or in a registry