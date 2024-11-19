# Utiliser une image MySQL officielle
FROM mysql:8.1

# Exposer le port 3306
EXPOSE 3306

COPY ./init-db.sql /docker-entrypoint-initdb.d/
