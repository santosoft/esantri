<h1>E-Santri</h1>

#### A Web-based Pesantren Management System built using [filament v3](https://filamentphp.com).

### Requirements:

- RDBMS (MySQL / PostgreSQL)
- PHP ^8.1
- PHP Extensions: bz2, curl, fileinfo, gd, mbstring, xml, zip
### Requirement for Development:
- NodeJS ^16
---

### Installation：

> ```
> composer install
> cp .env.example .env
> php artisan key:generate
> php artisan storage:link
> php artisan migrate --seed
> ```
