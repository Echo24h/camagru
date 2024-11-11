# Utilisez l'image officielle NGINX
FROM nginx

# Copiez la configuration NGINX personnalisée dans le conteneur
COPY nginx.conf /etc/nginx/nginx.conf


COPY certs/certificate.pem /etc/nginx/certs/fullchain.pem
COPY certs/private-key.pem /etc/nginx/certs/privkey.pem

# Copiez les certificats SSL générés en amont dans le conteneur
# COPY certs/fullchain.pem /etc/nginx/certs/fullchain.pem
# COPY certs/privatekey.pem /etc/nginx/certs/privkey.pem

# Exposez les ports 80 et 443 pour HTTP et HTTPS
EXPOSE 80
EXPOSE 443

# Commande pour démarrer NGINX en mode non démon
ENTRYPOINT ["nginx", "-g", "daemon off;"]