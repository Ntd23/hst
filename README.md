--- C:\laragon\etc\apache2\sites-enabled
-<VirtualHost *:80>
    ServerName hst.test
    ServerAlias *.hst.test
    ProxyPreserveHost On
	
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