# Installation

## Local Environment

##### Git clone 

```bash
git clone https://github.com/5i1/symfony-blog.git
```

##### Install composer

```bash
composer install
```

##### Install yarn package

```bash
yarn install
```

##### Build the assets files

```bash
yarn encore dev
yarn encore production
```

##### Initialize envoirment dev:
```bash
composer require --dev server
```

Before start to code, when you local then create an file `.env.local` in root path and write all necessary configuration (secret, database...) in your environment.

See [here](https://symfony.com/blog/new-in-symfony-4-2-define-env-vars-per-environment) to explain more.

This file is in `.gitignore` and not will be save in repository.

##### Create and update the database structure with this:
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

##### Generate fixtures data to begin development:
```bash
bin/console doctrine:fixtures:load
```

Authentication access:<br>
**Login:** admin<br> 
**Password:** admin

##### For execute the website then just follow steps bellow: 

##### Start server:
```bash
$ php bin/console server:run
```

---

## Production Environment (Web Server)

##### Git clone or you own project

```bash
git clone https://github.com/5i1/symfony-study-blog .
```

##### Configure your .env
Exist more way to configure variable in your environment, but the easiest way is just create an simple `.env.local` file and inform all variable you needed.

```bash
# Config
APP_ENV=prod
APP_SECRET=f9c53050608c5d72494b9asdas6e331sda
DATABASE_URL=mysql://root:12345@127.0.0.1:3306/database_name

# Costum variables
APP_NAME='Project'
ADMIN_NAME='Admin Project'
ADMIN_NAME_SIGLA='TKA'
FACEBOOK_URL='https://www.facebook.com'
INSTAGRAM_URL='https://www.instagram.com'
GITHUB_URL='https://github.com'
LINKEDIN_URL='https://www.linkedin.com'
BASE_URL='http://localhost:8000'
APP_VERSION='0.1.0'

```
Obs:  
You can just make an copy from [.env.test](../.env.test) file and configure your own environment.  
To generate the secret key parameter `APP_SECRET` access [here](http://nux.net/secret).


##### Install composer (only used the necessary dependences)

```bash
composer install --no-dev --prefer-dist --optimize-autoloader
```

##### Install yarn package

```bash
yarn install
```

##### Build the assets files

```bash
yarn encore production
```

##### Adding Rewrite Rules

```bash
composer require symfony/apache-pack
```
OBS:  
By question: *Do you want to execute this recipe?*  You should choice **Yes**

##### Create and update the database structure with this:
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
```

#### Folders and Permissions
Must be create two folders and set permission `777`, follow the commands below and execute this in root path of the project:
```bash
mkdir public/uploads
sudo chmod -R 777 public/uploads
mkdir public/media
sudo chmod -R 777 public/media
```

That's all.

For more information about the web server configuration and how improvement performance, access [here](https://symfony.com/doc/current/setup/web_server_configuration.html).

## Common Issues

##### 1) Unable to create the cache

In the prod environment it's possible to encountred this error:  
*PHP Fatal error:  Uncaught RuntimeException: Unable to create the cache directory...*  
The only thing you need to do is to execute this two commands below:
```bash
rm -rf var/cache/*
php bin/console cache:warmup
```
More details see [here](https://symfony.com/doc/current/setup/file_permissions.html)

##### 2) .htaccess is gone. And now?
For some reason when the `.htaccess` file is gone in your `public/` folder 
you can generate them again with this three commands below:
```bash
composer remove symfony/apache-pack
composer clearcache
composer require symfony/apache-pack
```

