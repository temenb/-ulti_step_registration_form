cp .env.example .env

php artisan key:generate

cp docker-compose.yml.dist docker-compose.yml

cp docker/containers/nginx/config/site.conf.dist docker/containers/nginx/config/site.conf

cp vite.config.js.dist vite.config.js

docker compose up -d





