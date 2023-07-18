# bilemo

# env php 8.1.19
# env Symfony 5.4

# Pour clone le projet git
## Il faut ouvrir un terminal et taper la commande suivante :
    git clone https://github.com/PepinoFr/bilemo.git
# si vous n'avez pas composer dans un terminal taper les commandes suivantes
     curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
     php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
     composer init
     composer require symfony/http-foundation
# Il faut créer une base de données donc taper la commande suivante :
    php bin/console doctrine:database:create
# modifier la variable "DATABASE_URL" dans le fichier .env pour modifier "bilemo" par le nom donné


#Pour créer les tables taper les commandes suivantes :
    php bin/console make:migration
    php bin/console doctrine:migrations:migrate

#Pour ajouter le contenu taper la commande suivante :
    php bien/console doctrine:fixtures:load 

# Puis pour lancer le serveur taper la commande 
    php bin/console server:run
 