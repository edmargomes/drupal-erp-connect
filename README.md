# drupal-erp-connect
ERPs Integration for Drupal 8 commerce, starting with Tiny ERP

## Setup
1 - Clone this repository with folder's name erp_connect in your modules folder.

2 - Added your tiny token in settings file
```
$settings['tiny'] = [
  'token' => 'your_token'
];
```

3 - Add from-to table settings to get field user value and send to TinyERP: 
`'drupal-field' => 'tiny-field'`

Example
```
$settings['tiny'] = [
  'token' => 'your_token',
  'user_fields' => [
    'field_cnpj' => 'cpf_cnpj',
    'field_cpf' => 'cpf_cnpj',
  ],
];
```
Tiny fields reference: https://erp.tiny.com.br/help?p=api2-contatos-incluir

4 - Add from-to table settings to get roles value and send to TinyERP like contact type: 
`'drupal-role' => 'tiny-type'`

Example
```
$settings['tiny'] = [
  'token' => 'your_token',
  'user_fields' => [
    'field_cnpj' => 'cpf_cnpj',
    'field_cpf' => 'cpf_cnpj',
  ],
  'roles' => [
    'client' => 'Cliente',
    'provider' => 'Fornecedor',
  ]
];

```

5 - Add from-to table settings to get field product value and send to TinyERP
`'drupal-field' => 'tiny-field'`

Example
```
$settings['tiny'] = [
  'token' => 'your_token',
  'user_fields' => [
    'field_cnpj' => 'cpf_cnpj',
    'field_cpf' => 'cpf_cnpj',
  ],
  'roles' => [
    'client' => 'Cliente',
    'provider' => 'Fornecedor',
  ],
  'product_fields' => [
    'body' => 'descricao_complementar',
    'field_EAN' => 'gtin',
  ],
];

```

###Example docker-compose to test.

```docker-compose
version: "3"

services:
  mariadb:
    image: wodby/mariadb:10.1-2.1.0
    environment:
      MYSQL_ROOT_PASSWORD: nofouejef
      MYSQL_DATABASE: drupal
      MYSQL_USER: drupal
      MYSQL_PASSWORD: lnvoerohfauiy
    labels:
      - 'traefik.docker.network=xingu'
      - 'traefik.backend=mariadb'
      - 'traefik.port=3306'
      - 'traefik.frontend.rule=Host:mariadb.docker.localhost'

  drupal:
    image: wodby/drupal-php:7.1-3.3.1
    environment:
      PHP_SENDMAIL_PATH: /usr/sbin/sendmail -t -i -S mailhog:1025
      DB_HOST: mariadb
      DB_USER: drupal
      DB_PASSWORD: lnvoerohfauiy
      DB_NAME: drupal
      DB_DRIVER: mysql
      PHP_XDEBUG: 1
      PHP_XDEBUG_DEFAULT_ENABLE: 1
      PHP_XDEBUG_REMOTE_CONNECT_BACK: 1         # This is needed to respect remote.host setting bellow
      # PHP_XDEBUG_REMOTE_HOST: "10.254.254.254"  # You will also need to 'sudo ifconfig lo0 alias 10.254.254.254'
    volumes:
      - ./:/var/www/html
      - ./erp_connect/:/var/www/html/web/modules/contrib/erp_connect
#      - docker-sync:/var/www/html # Docker-sync for macOS users

  nginx:
    image: wodby/drupal-nginx:8-1.13-3.0.2
    depends_on:
      - drupal
    environment:
      NGINX_STATIC_CONTENT_OPEN_FILE_CACHE: "off"
      NGINX_ERROR_LOG_LEVEL: debug
      NGINX_BACKEND_HOST: drupal
      NGINX_SERVER_ROOT: /var/www/html/web
    volumes:
      - ./:/var/www/html
      - ./erp_connect/:/var/www/html/web/modules/contrib/erp_connect
#      - docker-sync:/var/www/html # Docker-sync for macOS users
    labels:
      - 'traefik.docker.network=xingu'
      - 'traefik.backend=nginx'
      - 'traefik.port=80'
      - 'traefik.frontend.rule=Host:erp.docker.localhost'

  traefik:
    image: traefik
    command: -c /dev/null --web --docker --logLevel=INFO
    ports:
      - '8000:80'
      - '8080:8080' # Dashboard
    volumes:
      - /var/run/docker.sock:/var/run/docker.sock
    labels:
      - "traefik.docker.network=erp"

volumes:
  codebase:
## Docker-sync for macOS users
#  docker-sync:
#    external: true

#networks:
#  proxy:
#    driver: bridge
#  drupal:
#    external: true
```