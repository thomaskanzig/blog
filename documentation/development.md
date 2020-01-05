# Development

##### Commands for update web server (production environment)
```bash
git pull origin master
composer install --no-dev --prefer-dist --optimize-autoloader
yarn install
yarn encore production
```
