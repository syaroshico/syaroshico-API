# Syaroshico counter

Count syaroshico tweet using Twitter OAuth2, based on lumen.

live on https://syaroshico.hinaloe.net/

### requirement

- PHP **7.0+** *(least 5.6? but I'd not test and developed on PHP7)*
- Composer
- Twitter API CK/CS
- MySQL or compatible DB 

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