# Installation

1. Configure .env.local

2. Commands:
```
composer install
bin/console doctrine:database:create
bin/console doctrine:schema:create
bin/console doctrine:fixtures:load -n
```