# Disney Cartoons Showcase - Local Nginx Deployment

A beautiful showcase website displaying Disney and Pixar animated movies using external image sources and Nginx.

## 📁 Project Structure

```
disney-showcase/
├── disney.html               # Main HTML file
└── disney.conf               # Nginx server configuration
    
```

## 🚀 Quick Setup

### Prerequisites

- Nginx installed on your system
- Root/sudo access for configuration
- Internet connection (for external images)

### Installation Steps

1. **Clone or download the project files**
   ```bash
   mkdir disney-showcase
   cd disney-showcase
   ```

2. **Create the web directory**
   ```bash
   sudo mkdir -p /var/www/html
   ```

3. **Copy the HTML file**
   ```bash
   sudo cp disney.html /var/www/html/
   ```

4. **Set proper permissions**
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
    listen 84;
    server_name disney;
 
    root /var/www/html;
    index disney.html;

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
http://localhost:84
```

### Method 2: Using custom domain (Optional)
Add the following line to your `/etc/hosts` file:
```
127.0.0.1    disney
```

Then access via:
```
http://disney:84
```

## 📂 File Placement Summary

| File | Destination | Purpose |
|------|-------------|---------|
| `disney.html` | `/var/www/html/` | Main website file |
| `disney.conf` | `/etc/nginx/sites-available/` | Nginx server configuration |

## 🎨 Featured Movies

The showcase includes these beloved animated films with high-quality images from AlphaCoders:

- **Ratatouille** - A rat dreams of becoming a chef in Paris
- **A Bug's Life** - An ant saves his colony from grasshoppers  
- **WALL·E** - A robot finds love while cleaning Earth
- **Cars** - A hotshot race car learns what really matters
- **Toy Story** - Toys come to life when you're not looking
- **Finding Nemo** - A clownfish's journey across the ocean

## 🔧 Configuration Details

### Server Settings
- **Port**: 84 (to avoid conflicts with default web servers)
- **Server Name**: disney
- **Document Root**: `/var/www/html`
- **Index File**: `disney.html`

### Image Sources
- Images are loaded from external sources (AlphaCoders)
- No local image storage required
- Requires internet connection for images to display

## 🛠️ Troubleshooting

### Common Issues

1. **Permission Denied**
   ```bash
   sudo chown -R www-data:www-data /var/www/html
   sudo chmod -R 755 /var/www/html
   ```

2. **Port 84 Already in Use**
   - Change the port in the Nginx configuration
   - Or stop the conflicting service

3. **Images Not Loading**
   - Check internet connection
   - Verify external image URLs are accessible
   - Check browser console for CORS or network errors

4. **Site Not Accessible**
   - Check if Nginx is running: `sudo systemctl status nginx`
   - Verify configuration: `sudo nginx -t`
   - Check firewall settings for port 84

---

**Happy coding! 🎬✨**
