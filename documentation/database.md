# Database

##### For config database go to .env file and config de database server and then execute this comand in terminal:

```bash
php bin/console doctrine:database:create
```

##### For build an Entity (table), execute this and answers the questions:

```bash
php bin/console make:entity
```

##### For generate an version, only generate scripts was different by your database, generate all tables scripts of not exists in your database, execute this:

```bash
php bin/console doctrine:migrations:diff
# or
php bin/console make:migration
```

That it's perfect case for an new Entity in Symfony. With this new version generated you can update your database after.

##### For update your database, execute this:

```bash
php bin/console doctrine:migrations:migrate
```

or choose an especific version:

```bash
php bin/console doctrine:migrations:migrate 20180207231217
```

#### Observation:

When you must update your database after your development, just execute two commands:
```bash
# Compare your current database and generate sql for you need update:
php bin/console doctrine:migrations:diff

# Execute the version with that same SQL scripts and update your database:
php bin/console doctrine:migrations:migrate

# Or choose the version for update your database
php bin/console doctrine:migrations:migrate <version-number>
```

For more details, then visit the documentation [here](https://symfony.com/doc/current/doctrine.html).

#### Optional:
When you need some fixtures data, then execute this command below:
```bash
# Require fixtures bundle
composer require --dev orm-fixtures

# Generate fixtures data
php bin/console doctrine:fixtures:load
```
