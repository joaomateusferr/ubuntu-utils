# Demystifying OpenSSL, certificates and PKCS#12

This document outlines the process of generating self sign root, intermediate, and certificates deepening knowledge in PKCS#12.

**All** cryptographically strong random **passwords generated in the steps above must be stored safely**  for potential future. Please use the description of the generation command when saving them for future reference.

## Generating a new self sign root certificate:

### Creating a cryptographically strong random password for the root key:
```shell
echo "Root Key Password: $(openssl rand -base64 45 | tr -dc 'A-Za-z0-9-().!@?#,/;+' | head -c30)"
```

### Creating root key:
```shell
openssl genrsa -des3 -out root-ca.key 4096
```
Use the password generated above and verify the password so that the key can be generated correctly.

### Creating and self sign the root certificate:

```shell
openssl req -x509 -new -nodes -key root-ca.key -sha512 -days 7305 -out root-ca.crt -subj "/C=BR/ST=SP/L=Jundiai/O=Test Org/CN=test.com Root" -addext "subjectKeyIdentifier=hash" -addext "authorityKeyIdentifier=keyid:always,issuer:always" -addext "basicConstraints=CA:true"
```

Use the root key password generated above so the root certificate can be generated correctly.

## Generating a identity certificate directly from root (possible but not recommended):

### Creating the identity certificate key:
```shell
openssl genrsa -out identity.key 4096
```
### Creating the identity certificate csr:
```shell
openssl req -new -sha512 -key identity.key -subj "/C=BR/ST=SP/L=Jundiai/O=Test Org/CN=test.com Identity" -out identity.csr
```
### Generating the identity certificate:
```shell
openssl x509 -req -in identity.csr -CA root-ca.crt -CAkey root-ca.key -CAcreateserial -out identity.crt -days 3653 -sha512
```
Use the root key password so the identity certificate can be generated correctly.

### Creating a cryptographically strong random password for the identity p12 export:
```shell
echo "Identity Export Password: $(openssl rand -base64 45 | tr -dc 'A-Za-z0-9-().!@?#,/;+' | head -c30)"
```

### Generating the identity p12:
```shell
openssl pkcs12 -export -in identity.crt -CAfile root-ca.crt -chain -inkey identity.key -out identity.p12 -name identity
```
Use the p12 export password generated above and verify the password so that the p12 can be generated correctly.

### Checking the identity.p12 content:
```shell
openssl pkcs12 -info -in identity.p12 -nokeys
```
Use the p12 export password generated above so we can verify the p12 content.

## Generating  identity certificate from the intermediate certificate (recommended):

### Creating a cryptographically strong random password for the intermediate key:
```shell
echo "Intermediate Key Password: $(openssl rand -base64 45 | tr -dc 'A-Za-z0-9-().!@?#,/;+' | head -c30)"
```

### Creating intermediate key:
```shell
openssl genrsa -des3 -out intermediate.key 4096
```
Use the password generated above and verify the password so that the key can be generated correctly.

### Creating the intermediate certificate csr:
```shell
openssl req -new -sha512 -key intermediate.key -subj "/C=BR/ST=SP/L=Jundiai/O=Test Org/CN=Intermediate test.com" -out intermediate.csr
```

### Creating the intermediate extension file:
```shell
echo "basicConstraints=CA:TRUE" > intermediate.ext
```

### Generating the intermediate certificate:
```shell
openssl x509 -req -in intermediate.csr -CA root-ca.crt -CAkey root-ca.key -CAcreateserial -out intermediate.crt -days 3653 -sha512 -extfile intermediate.ext
```
Use the root key password so the intermediate certificate can be generated correctly.

### Creating intermediate identity key:
```shell
openssl genrsa -out intermediate-identity.key 4096
```

### Creating the intermediate identity certificate csr:
```shell
openssl req -new -sha512 -key intermediate-identity.key -subj "/C=BR/ST=SP/L=Jundiai/O=Test Org/CN=Intermediate test.com Identity" -out intermediate-identity.csr
```

### Generating the intermediate identity certificate:
```shell
openssl x509 -req -in intermediate-identity.csr -CA intermediate.crt -CAkey intermediate.key -CAcreateserial -out intermediate-identity.crt -days 3652 -sha512
```
Use the intermediate key password so the intermediate identity certificate can be generated correctly.

### Creating a cryptographically strong random password for the intermediate identity p12 export:
```shell
echo "Intermediate Identity Export Password: $(openssl rand -base64 45 | tr -dc 'A-Za-z0-9-().!@?#,/;+' | head -c30)"
```

### Creating a bundle with the root and intermediate certificates (the complete chain) to build the intermediate identity p12:
```shell
cat intermediate.crt root-ca.crt > chain.crt
```

### Generating the intermediate identity p12:
```shell
openssl pkcs12 -export -in intermediate-identity.crt -inkey intermediate-identity.key -certfile chain.crt -out intermediate-identity.p12 -name intermediate-identity
```

Use the p12 export password generated above and verify the password so that the p12 can be generated correctly.

### Checking the intermediate-identity.p12 content:
```shell
openssl pkcs12 -info -in intermediate-identity.p12 -nokeys
```
Use the p12 export password generated above so we can verify the p12 content.