FROM nginx:1.28-alpine

# Copier la configuration nginx
COPY ./nginx/conf.d/nginx.conf.k8s /etc/nginx/conf.d/default.conf
RUN chmod -R 555 /etc/nginx/conf.d  # Lecture/exécution uniquement

# Copier les fichiers front-end statiques
COPY dist /var/www/react
