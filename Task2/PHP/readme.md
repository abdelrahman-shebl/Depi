# DevOps Tools Map - Interactive Showcase

An interactive web application showcasing DevOps tools in a visual loop diagram with clickable hotspots, built with PHP and served via Nginx with PHP-FPM.

## 📁 Project Structure

```
devops-tools-map/
├── index.php                 # Main PHP application file
└── php.conf                  # Nginx server configuration
│   
└── images/
    └── DevOps.jpg           # Interactive DevOps loop diagram
```

## 🚀 Quick Setup

### Prerequisites

- Nginx installed on your system
- PHP-FPM (PHP FastCGI Process Manager)
- Root/sudo access for configuration
- DevOps.jpg image file

### Installation Steps

1. **Clone or download the project files**
   ```bash
   mkdir devops-tools-map
   cd devops-tools-map
   ```

2. **Create the project directory**
   ```bash
   sudo mkdir -p /home/shebl/DEPI/task2/php
   sudo mkdir -p /home/shebl/DEPI/task2/php/images
   ```

3. **Copy the PHP file**
   ```bash
   sudo cp index.php /home/shebl/DEPI/task2/php/
   ```

4. **Copy the DevOps image**
   ```bash
   sudo cp images/DevOps.jpg /home/shebl/DEPI/task2/php/images/
   ```

5. **Set proper permissions**
   ```bash
   sudo chown -R www-data:www-data /home/shebl/DEPI/task2/php
   sudo chmod -R 755 /home/shebl/DEPI/task2/php
   ```

## ⚙️ PHP-FPM Configuration

### 1. Install PHP-FPM (if not already installed)

**Ubuntu/Debian:**
```bash
sudo apt update
sudo apt install php-fpm nginx
```

**CentOS/RHEL:**
```bash
sudo yum install php-fpm nginx
```

### 2. Start PHP-FPM service
```bash
sudo systemctl start php-fpm
sudo systemctl enable php-fpm
```

### 3. Verify PHP-FPM is running on port 9000
```bash
sudo netstat -tulpn | grep :9000
```

## 🌐 Nginx Configuration

### 1. Create the server configuration

Create a new configuration file:

```bash
sudo nano /etc/nginx/sites-available/devops-php
```

Copy the following configuration:

```nginx
server {
    listen 8000;
    server_name localhost;

    root /home/shebl/DEPI/task2/php;
    index index.php;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass 127.0.0.1:9000;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### 2. Enable the site

```bash
sudo ln -s /etc/nginx/sites-available/devops-php /etc/nginx/sites-enabled/
```

### 3. Test the configuration

```bash
sudo nginx -t
```

### 4. Reload Nginx

```bash
sudo systemctl reload nginx
```

## 🌐 Accessing the Application

Open your browser and navigate to:
```
http://localhost:8000
```

## 📂 File Placement Summary

| File | Destination | Purpose |
|------|-------------|---------|
| `index.php` | `/home/shebl/DEPI/task2/php/` | Main PHP application |
| `php.conf` | `/etc/nginx/sites-available/` | Nginx server configuration |
| `DevOps.jpg` | `/home/shebl/DEPI/task2/php/images/` | Interactive DevOps diagram |

## 🛠️ Interactive Features

### DevOps Tools Hotspots
The application includes clickable areas on the DevOps loop diagram that link to:

**Development Tools:**
- **GitLab** - Version control and CI/CD platform
- **Git** - Distributed version control system
- **AWS** - Cloud computing services
- **Docker** - Containerization platform
- **Gradle** - Build automation tool
- **Nexus** - Artifact repository manager

**Operations Tools:**
- **Chef** - Infrastructure automation
- **Ansible** - Configuration management
- **Kubernetes** - Container orchestration
- **Graylog** - Log management
- **Grafana** - Monitoring and observability
- **Jenkins** - CI/CD automation server
- **OpenStack** - Cloud computing platform
- **Azure** - Microsoft cloud platform

### Sidebar Features
- **DevOps Tips** - Best practices and principles
- **Related Resources** - Links to additional learning materials

## 🔧 Configuration Details

### Server Settings
- **Port**: 8000
- **Server Name**: localhost
- **Document Root**: `/home/shebl/DEPI/task2/php`
- **Index File**: `index.php`
- **PHP Processing**: FastCGI via PHP-FPM on port 9000

### Security Features
- Hidden `.htaccess` files protection
- PHP file processing via FastCGI
- Proper file permissions

## 🛠️ Troubleshooting

### Common Issues

1. **PHP-FPM Not Running**
   ```bash
   sudo systemctl status php-fpm
   sudo systemctl start php-fpm
   ```

2. **Permission Denied**
   ```bash
   sudo chown -R www-data:www-data /home/shebl/DEPI/task2/php
   sudo chmod -R 755 /home/shebl/DEPI/task2/php
   ```

3. **Port 8000 Already in Use**
   - Change the port in the Nginx configuration
   - Or stop the conflicting service

4. **PHP Not Processing (Downloading Instead)**
   - Verify PHP-FPM is running: `sudo systemctl status php-fpm`
   - Check FastCGI configuration in Nginx
   - Ensure `fastcgi_pass 127.0.0.1:9000;` is correct

5. **Image Not Loading**
   - Verify `DevOps.jpg` exists in `/home/shebl/DEPI/task2/php/images/`
   - Check file permissions
   - Ensure image path in PHP matches actual location

