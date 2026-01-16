# Hướng dẫn Triển khai Laravel lên VPS Linux

Tài liệu này hướng dẫn chi tiết cách triển khai Studio AI lên một VPS Linux mới (Ubuntu 22.04/24.04).

---

## 🏗️ Kiến trúc Hệ thống

| Thành phần | Công nghệ | Ghi chú |
| :--- | :--- | :--- |
| **Web Server** | **Nginx** | Nhẹ, hiệu năng cao |
| **PHP** | **PHP 8.3 + FPM** | Laravel 12 yêu cầu PHP 8.2+ |
| **Database** | **MySQL 8** | Có thể dùng MariaDB |
| **Process Manager** | **Supervisor** | Quản lý Queue Worker |
| **SSL** | **Let's Encrypt (Certbot)** | Miễn phí |

---

## 🛠️ Giai đoạn 1: Chuẩn bị VPS

### 1.1. Kết nối SSH
```bash
ssh root@YOUR_VPS_IP
# Hoặc nếu dùng key: ssh -i your_key.pem root@YOUR_VPS_IP
```

### 1.2. Cập nhật hệ thống
```bash
apt update && apt upgrade -y
```

### 1.3. Tạo User mới (Khuyên dùng)
```bash
adduser deploy
usermod -aG sudo deploy
su - deploy
```

---

## 🛠️ Giai đoạn 2: Cài đặt LEMP Stack

### 2.1. Cài Nginx
```bash
sudo apt install nginx -y
sudo systemctl enable nginx
```

### 2.2. Cài MySQL 8
```bash
sudo apt install mysql-server -y
sudo mysql_secure_installation
# Làm theo hướng dẫn để đặt password root và bảo mật
```

**Tạo Database và User:**
```bash
sudo mysql -u root -p
```
```sql
CREATE DATABASE db_studio_ai CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'studio_user'@'localhost' IDENTIFIED BY 'YOUR_STRONG_PASSWORD';
GRANT ALL PRIVILEGES ON db_studio_ai.* TO 'studio_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 2.3. Cài PHP 8.3 + Extensions
```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.3-fpm php8.3-cli php8.3-mysql php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip php8.3-gd php8.3-bcmath php8.3-intl php8.3-redis -y
```

### 2.4. Cài Composer
```bash
cd ~
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version
```

### 2.5. Cài Node.js (cho Vite build)
```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install nodejs -y
node -v && npm -v
```

---

## 🛠️ Giai đoạn 3: Triển khai Code

### 3.1. Clone Repository
```bash
cd /var/www
sudo git clone https://github.com/YOUR_USERNAME/YOUR_REPO.git studio-ai
sudo chown -R deploy:www-data studio-ai
cd studio-ai
```

### 3.2. Cài đặt Dependencies
```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### 3.3. Cấu hình Environment
```bash
cp .env.example .env
nano .env
```
**Cập nhật các giá trị quan trọng trong `.env`:**
```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_studio_ai
DB_USERNAME=studio_user
DB_PASSWORD=YOUR_STRONG_PASSWORD

FILESYSTEM_DISK=public
```

### 3.4. Thiết lập Laravel
```bash
php artisan key:generate
php artisan storage:link
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3.5. Phân quyền thư mục
```bash
sudo chown -R deploy:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

---

## 🛠️ Giai đoạn 4: Cấu hình Nginx

### 4.1. Tạo file config
```bash
sudo nano /etc/nginx/sites-available/studio-ai
```

**Nội dung file:**
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/studio-ai/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 4.2. Kích hoạt site
```bash
sudo ln -s /etc/nginx/sites-available/studio-ai /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 🛠️ Giai đoạn 5: Cài đặt SSL (HTTPS)

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
# Làm theo hướng dẫn, chọn redirect HTTP to HTTPS
```

**Tự động gia hạn:**
```bash
sudo certbot renew --dry-run
```

---

## 🛠️ Giai đoạn 6: Cấu hình Queue Worker (Supervisor)

### 6.1. Cài Supervisor
```bash
sudo apt install supervisor -y
```

### 6.2. Tạo config cho Laravel Queue
```bash
sudo nano /etc/supervisor/conf.d/studio-ai-worker.conf
```
**Nội dung:**
```ini
[program:studio-ai-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/studio-ai/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=deploy
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/studio-ai/storage/logs/worker.log
stopwaitsecs=3600
```

### 6.3. Kích hoạt
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start studio-ai-worker:*
```

---

## 🔄 Quy trình Cập nhật Code (Khi có thay đổi mới)

Mỗi khi bạn push code mới lên GitHub, SSH vào VPS và chạy:

```bash
cd /var/www/studio-ai
git pull origin main
composer install --optimize-autoloader --no-dev
npm install && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo supervisorctl restart studio-ai-worker:*
```

**Tip:** Tạo script `deploy.sh` để tự động hóa:
```bash
nano ~/deploy.sh
```
```bash
#!/bin/bash
cd /var/www/studio-ai
git pull origin main
composer install --optimize-autoloader --no-dev
npm install && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo supervisorctl restart studio-ai-worker:*
echo "Deploy complete!"
```
```bash
chmod +x ~/deploy.sh
```
**Để deploy:** `~/deploy.sh`

---

## 💡 Tổng kết

| Bước | Mô tả |
| :--- | :--- |
| 1 | SSH vào VPS, cập nhật hệ thống |
| 2 | Cài LEMP (Nginx, MySQL, PHP, Composer, Node) |
| 3 | Clone code, cài dependencies, cấu hình `.env` |
| 4 | Cấu hình Nginx Virtual Host |
| 5 | Bật HTTPS với Let's Encrypt |
| 6 | Cấu hình Supervisor cho Queue |
| 7 | Truy cập `https://yourdomain.com` và đăng nhập! |

---

## ⚠️ Lưu ý Bảo mật
- Đổi port SSH mặc định (22) sang port khác.
- Cấu hình UFW Firewall: `sudo ufw allow 80,443,22/tcp && sudo ufw enable`
- Không push file `.env` lên GitHub.
- Sử dụng SSH Key thay vì password.
