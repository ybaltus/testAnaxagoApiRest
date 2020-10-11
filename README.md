# Pré requis

Pour lancer le projet vous aurez besoin de la configuration suivante :
* [Apache](http://httpd.apache.org/docs/2.4/fr/install.html) >= 2
* [MySql](https://dev.mysql.com/doc/mysql-installation-excerpt/5.7/en/) >= 5.7 ou [MariaDB](https://mariadb.com/kb/en/where-to-download-mariadb/#the-latest-packages) >=10.2
* [Php](https://www.php.net/manual/fr/install.php) >= 7.4 (important pour le package ocramius/proxy-manager)

 [Aide Linux](https://www.digitalocean.com/community/tutorials/comment-installer-la-pile-linux-apache-mysql-php-lamp-sur-un-serveur-ubuntu-18-04-fr)
  ou [Aide Mac](https://documentation.mamp.info/en/MAMP-Mac/Installation/) 
  
# Outils
* [Symfony5](https://symfony.com/4)
* [Jms/serializer-bundle](https://packagist.org/packages/jms/serializer-bundle) pour sérialiser et désérialiser les données. Elle l’offre des fonctionnalités pratique pour travailler. 
* [SwiftMailer](https://packagist.org/packages/swiftmailer/swiftmailer) pour gérer les envois d’emails.
* (Optionnel) [Maildev](https://maildev.com/) pour capturer les envois d’emails.
* (Optionnel) [PostMan](https://www.postman.com/) pour exécuter des requêtes http.

# Initialiser le projet

#### :warning: Créer son fichier .env.local a partir des informations de la base de données. Sinon les commandes suivantes ne pourront pas fonctionner!

```
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```
> Pour les tests avec phpunit il faut configuer les informations de la base de données dans le fichier .env.test

# Utiliser l'API REST
#### Les URI doivent être préfixés de "/api/v1".

1. **Récupérer la liste des projets et leurs status**
<br>- Méthode: GET
<br>- URI: /list/projects
<br>- Format: JSON

2. **Investir dans un projet en précisant un montant**
<br>- Méthode: POST
<br>- URI: /investment/add
<br>- Paramètres: email, password, amount, slug
<br>- Format: JSON

3. **Récupérer la liste des projets investit par utilisateur**
<br>- Méthode: GET
<br>- URI: /list/user/projects
<br>- Paramètres: email, password
<br>- Format: JSON

# Lancer le projet avec start-project (optionnel)

#### :warning: Créer son fichier .env.local a partir des informations manquantes Sinon la commande suivante ne pourra fonctionner!

```
make start-project
```

# Effacer le host (optionnel)

```
make start-project
```
