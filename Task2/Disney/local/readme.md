# Disney Cartoons Showcase - Local Nginx Deployment

A beautiful showcase website displaying Disney and Pixar animated movies with local image hosting using Nginx.

## 📁 Project Structure

```
disney-showcase/
├── disney_local.html          # Main HTML file
├── nginx/
│   └── disney.conf           # Nginx server configuration
└── images/                   # Movie poster images
    ├── ratatouille.jpeg
    ├── bugs-life.webp
    ├── wall-e.jpeg
    ├── cars.jpeg
    ├── toy-story.webp
    └── finding-nemo.webp
```

## 🚀 Quick Setup

### Prerequisites

- Nginx installed on your system
- Root/sudo access for configuration

### Installation Steps

1. **Clone or download the project files**
   ```bash
   mkdir disney-showcase
   cd disney-showcase
   ```

2. **Create the web directory structure**
   ```bash
   sudo mkdir -p /var/www/html
   sudo mkdir -p /var/www/html/images
   ```

3. **Copy the HTML file**
   ```bash
   sudo cp disney_local.html /var/www/html/
   ```

4. **Copy all movie images to the images directory**
   ```bash
   sudo cp images/* /var/www/html/images/
   ```

5. **Set proper permissions**
   ```bash
   sudo chown -R www-data:www-data /var/www/html
   sudo chmod -R 755 /var/www/html
   ```

## ⚙️ Nginx Configuration

### 1. Create the server configuration

Create a new configuration file for the Disney showcase:

```bash
sudo nano /etc/nginx/sites-available/disney
```

Copy the following configuration:

```nginx
server {
    listen 82;
    server_name disney;
    root /var/www/html;
    index disney_local.html;
    
    location /images/ {
        autoindex on;
        add_header Cache-Control "no-store, no-cache, must-revalidate, proxy-revalidate, max-age=0";
    }
    
    location / {
        try_files $uri $uri/ =404;
    }
}
```

### 2. Enable the site

```bash
sudo ln -s /etc/nginx/sites-available/disney /etc/nginx/sites-enabled/
```

### 3. Test the configuration

```bash
sudo nginx -t
```

### 4. Reload Nginx

```bash
sudo systemctl reload nginx
```

## 🌐 Accessing the Website

### Method 1: Using localhost (Recommended)
Open your browser and navigate to:
```
http://localhost:82
```

### Method 2: Using custom domain (Optional)
Add the following line to your `/etc/hosts` file:
```
127.0.0.1    disney
```

Then access via:
```
http://disney:82
```

## 📂 File Placement Summary

| File | Destination | Purpose |
|------|-------------|---------|
| `disney_local.html` | `/var/www/html/` | Main website file |
| `disney.conf` | `/etc/nginx/sites-available/` | Nginx server configuration |
| Movie images | `/var/www/html/images/` | Static image assets |

## 🎨 Featured Movies

The showcase includes these beloved animated films:
- **Ratatouille** - A rat dreams of becoming a chef in Paris
- **A Bug's Life** - An ant saves his colony from grasshoppers  
- **WALL·E** - A robot finds love while cleaning Earth
- **Cars** - A hotshot race car learns what really matters
- **Toy Story** - Toys come to life when you're not looking
- **Finding Nemo** - A clownfish's journey across the ocean

## 🔧 Configuration Details

### Server Settings
- **Port**: 82 (to avoid conflicts with default web servers)
- **Server Name**: disney
- **Document Root**: `/var/www/html`
- **Index File**: `disney_local.html`

### Image Directory Features
- **Auto-indexing**: Enabled for browsing images directly
- **Cache Control**: Images are served with no-cache headers for development
- **Direct Access**: Images accessible at `http://localhost:82/images/`

## 🛠️ Troubleshooting

### Common Issues

1. **Permission Denied**
   ```bash
   sudo chown -R www-data:www-data /var/www/html
   sudo chmod -R 755 /var/www/html
   ```

2. **Port 82 Already in Use**
   - Change the port in the Nginx configuration
   - Or stop the conflicting service

3. **Images Not Loading**
   - Check file paths and permissions
   - Ensure images are in `/var/www/html/images/`
   - Verify image file extensions match HTML references

4. **Site Not Accessible**
   - Check if Nginx is running: `sudo systemctl status nginx`
   - Verify configuration: `sudo nginx -t`
   - Check firewall settings for port 82



---

**Happy coding! 🎬✨**
