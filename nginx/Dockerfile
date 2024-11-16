# Utilisez l'image officielle NGINX
FROM nginx

# Copiez la configuration NGINX personnalisée dans le conteneur
COPY nginx.conf /etc/nginx/nginx.conf

# Créez un répertoire pour stocker les certificats SSL

COPY certs.sh /certs.sh
RUN bash certs.sh
RUN rm /certs.sh

# Exposez les ports 80 et 443 pour HTTP et HTTPS
EXPOSE 80
EXPOSE 443

# Commande pour démarrer NGINX en mode non démon
ENTRYPOINT ["nginx", "-g", "daemon off;"]