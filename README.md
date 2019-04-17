# silex_rest_api
tutorial => https://www.foulquier.info/tutoriaux/creation-d-une-api-rest-avec-silex

conf nginx =>


server {
        listen 80;
        listen [::]:80;
        server_name traductions.local.s2h.corp, traductions.com;
        location / {
                return 301 https://traductions.local.s2h.corp$request_uri;
        }
}

server {
        listen 443 ssl;
        listen [::]:443 ssl;
        include snippets/self-signed.conf;
        include snippets/ssl-params.conf;

        server_name traductions.local.s2h.corp;

        root /var/www/traductions;
        access_log /var/log/nginx/traductions.local.access.log main;
        error_log /var/log/nginx/traductions.local.error.log crit;

        set $bootstrap "bootstrap.php";
        # Add index.php to the list if you are using PHP
        #index bootstrap.php index.html index.htm index.nginx-debian.html;
        index $bootstrap;

        rewrite ^/bootstrap\.php/?(.*)$ /$1 permanent;



        location / {

                try_files $uri @rewriteapp;
        }

        location @rewriteapp {

            if ($request_method = 'OPTIONS') {
                add_header 'Access-Control-Allow-Origin' '*';
                add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS';
                #
                # Custom headers and headers various browsers *should* be OK with but aren't
                #
                add_header 'Access-Control-Allow-Headers' 'DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range,X-AUTH-TOKEN';
                #
                # Tell client that this pre-flight info is valid for 20 days
                #
                add_header 'Access-Control-Max-Age' 1728000;
                add_header 'Content-Type' 'text/plain; charset=utf-8';
                add_header 'Content-Length' 0;
                return 204;
             }
             if ($request_method = 'POST') {
                add_header 'Access-Control-Allow-Origin' '*';
                add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS';
                add_header 'Access-Control-Allow-Headers' 'DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range,X-AUTH-TOKEN';
                add_header 'Access-Control-Expose-Headers' 'Content-Length,Content-Range';
             }
             if ($request_method = 'GET') {
                add_header 'Access-Control-Allow-Origin' '*';
                add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS';
                add_header 'Access-Control-Allow-Headers' 'DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range,X-AUTH-TOKEN';
                add_header 'Access-Control-Expose-Headers' 'Content-Length,Content-Range';
             }
                rewrite  ^(.*)$  /$bootstrap?$1  last;
        }

        location ~ ^/(bootstrap)\.php(/|$) {

            #if (!-f $document_root$fastcgi_script_name ) {
        #       return 404;
         #   }

          if ($request_method = 'OPTIONS') {
                add_header 'Access-Control-Allow-Origin' '*';
                add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS';
                #
                # Custom headers and headers various browsers *should* be OK with but aren't
                #
                add_header 'Access-Control-Allow-Headers' 'DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range,X-AUTH-TOKEN';
                #
                # Tell client that this pre-flight info is valid for 20 days
                #
                add_header 'Access-Control-Max-Age' 1728000;
                add_header 'Content-Type' 'text/plain; charset=utf-8';
                add_header 'Content-Length' 0;
                return 204;
             }
             if ($request_method = 'POST') {
                add_header 'Access-Control-Allow-Origin' '*';
                add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS';
                add_header 'Access-Control-Allow-Headers' 'DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range,X-AUTH-TOKEN';
                add_header 'Access-Control-Expose-Headers' 'Content-Length,Content-Range';
             }
             if ($request_method = 'GET') {
                add_header 'Access-Control-Allow-Origin' '*';
                add_header 'Access-Control-Allow-Methods' 'GET, POST, OPTIONS';
                add_header 'Access-Control-Allow-Headers' 'DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range,X-AUTH-TOKEN';
                add_header 'Access-Control-Expose-Headers' 'Content-Length,Content-Range';
             }

            fastcgi_pass unix:/var/run/php/php7.2-fpm.sock;
            fastcgi_index $bootstrap;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include fastcgi_params;
            fastcgi_read_timeout 600;

        }


        location ~ /\.ht {
                deny all;
        }

}
