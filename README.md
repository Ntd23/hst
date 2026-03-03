# 🚀 Setup hst.test (Laragon + Apache + Laravel + Next.js)

Tài liệu này hướng dẫn cấu hình Apache trong Laragon để:

- `/admin`, `/api`, `/storage` → Laravel (port 8000)
- `/` → Next.js (port 3000)
- Truy cập chung qua: http://hst.test

---

# 1️⃣ Tắt Auto Virtual Host trong Laragon

Vào:

Laragon → Menu → Settings  

👉 Bỏ tick **Auto Virtual Hosts**


---

# 2️⃣ Enable mod_proxy trong Apache

Mở file:

D:\Duong\src\laragon\bin\apache\httpd-2.4.63-250207-win64-VS17\conf\httpd.conf

Tìm và bỏ dấu `#` ở 2 dòng sau:

```apache
LoadModule proxy_module modules/mod_proxy.so
LoadModule proxy_http_module modules/mod_proxy_http.so
```


---

# 3️⃣ Tạo Virtual Host

Vào thư mục:

C:\laragon\etc\apache2\sites-enabled

Tạo file mới:

hst.test.conf

Dán toàn bộ nội dung bên dưới vào file đó:

```apache
<VirtualHost *:80>
    ServerName hst.test
    ServerAlias *.hst.test

    ProxyPreserveHost On

    # ==================================
    # Laravel admin public directory
    # ==================================
    Alias /admin "D:/Duong/src/laragon/www/hst/admin/public"

    DocumentRoot "C:/laragon/www/hst/admin/public"

    <Directory "C:/laragon/www/hst/admin/public">
        AllowOverride All
        Require all granted
        Options -Indexes -MultiViews
    </Directory>

    # ==================================
    # Proxy Laravel (php artisan serve :8000)
    # ==================================
    ProxyPass        /admin    http://127.0.0.1:8000/admin
    ProxyPassReverse /admin    http://127.0.0.1:8000/admin

    ProxyPass        /vendor   http://127.0.0.1:8000/vendor
    ProxyPassReverse /vendor   http://127.0.0.1:8000/vendor

    ProxyPass        /api      http://127.0.0.1:8000/api
    ProxyPassReverse /api      http://127.0.0.1:8000/api

    ProxyPass        /storage  http://127.0.0.1:8000/storage
    ProxyPassReverse /storage  http://127.0.0.1:8000/storage

    # ==================================
    # Proxy Next.js (npm run dev :3000)
    # ==================================
    ProxyPass        /         http://127.0.0.1:3000/
    ProxyPassReverse /         http://127.0.0.1:3000/
</VirtualHost>
```

Sau khi tạo xong file → restart Apache.

---

# 4️⃣ Chạy hệ thống

Chạy đồng thời:

### Laravel

```bash
php artisan serve
```

→ http://127.0.0.1:8000

---

### Next.js

```bash
npm run dev
```

→ http://127.0.0.1:3000

---

# 5️⃣ Truy cập

Frontend:  
http://hst.test  

Admin:  
http://hst.test/admin  

---

# ⚠️ Lỗi thường gặp

### 403 Forbidden
- Sai đường dẫn DocumentRoot
- Chưa restart Apache

### 502 Bad Gateway
- Laravel chưa chạy port 8000
- Next chưa chạy port 3000