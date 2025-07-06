FROM nginx:1.28-alpine

# Copier la configuration nginx
COPY ../nginx/conf.d /etc/nginx/conf.d
RUN chmod -R 555 /etc/nginx/conf.d  # Lecture/exécution uniquement

# Copier les fichiers front-end statiques
COPY ../front-end/dist /var/www/react
