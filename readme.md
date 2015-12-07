# Syaroshico counter

Count syaroshico tweet using Twitter OAuth2, based on lumen.

live on https://syaroshico.hinaloe.net/

### Setup

```shell-session
$ git clone https://github.com/hinaloe.net/syaroshico
$ composer install
$ cp .env.example
$ vim .env
$ artisan migrate:install
$ artisan db:seed
$ artisan serve
```

### API Endpoint

#### [GET] /api/v1/count.json

- param 
    - (bool) url
    - (bool) syaroshico
    - (bool) shico