!#/bin/bash

# Generate a self-signed certificate for localhost
openssl req -x509 -out localhost.crt -keyout localhost.key \
  -newkey rsa:2048 -nodes -sha256 \
  -subj '/CN=localhost' -extensions EXT -config <( \
   printf "[dn]\nCN=localhost\n[req]\ndistinguished_name = dn\n[EXT]\nsubjectAltName=DNS:localhost\nkeyUsage=digitalSignature\nextendedKeyUsage=serverAuth")

mkdir -p /etc/nginx/ssl

# Move the certificate and key to the appropriate location
mv localhost.crt /etc/nginx/ssl/localhost.crt
mv localhost.key /etc/nginx/ssl/localhost.key