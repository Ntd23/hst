--- Tắt Auto Virtual Host trong Laragon
--- Vào D:\Duong\src\laragon\bin\apache\httpd-2.4.63-250207-win64-VS17\conf\httpd.conf
    #LoadModule proxy_module modules/mod_proxy.so
    #LoadModule proxy_http_module modules/mod_proxy_http.so
    --> Bỏ dấu #
--- Vào C:\laragon\etc\apache2\sites-enabled
   --- Tạo file hst.test.conf. Paste nội dung bên dưới:
<VirtualHost *:80>
    ServerName hst.test
    ServerAlias *.hst.test
    ProxyPreserveHost On
	Alias /admin "D:/Duong/src/laragon/www/hst/admin/public"
    DocumentRoot "C:/laragon/www/hst/admin/public"
    <Directory "C:/laragon/www/hst/admin/public">
        AllowOverride All
        Require all granted
        Options -Indexes -MultiViews
    </Directory>
    
    ProxyPass        /admin  http://127.0.0.1:8000/admin
ProxyPassReverse /admin  http://127.0.0.1:8000/admin

ProxyPass /vendor http://127.0.0.1:8000/vendor
ProxyPassReverse /vendor http://127.0.0.1:8000/vendor

ProxyPass        /api    http://127.0.0.1:8000/api
ProxyPassReverse /api    http://127.0.0.1:8000/api
    
	ProxyPass        /storage http://127.0.0.1:8000/storage
ProxyPassReverse /storage http://127.0.0.1:8000/storage

   ProxyPass        / http://127.0.0.1:3000/ 
    ProxyPassReverse / http://127.0.0.1:3000/
</VirtualHost>

*** Nhớ chạy đồng thời laragon và php artisan serve