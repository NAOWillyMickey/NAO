NAO
===

Projet 5 : Gestion de projet en équipe

# Installation

## 1. Récupérer le code

Vous avez deux solutions pour le faire :

1. Via Git, en clonant ce dépôt ;

    * Tout d'abord, ouvrez le terminal et placez-vous dans le répertoire prêt à recevoir l'application.

    * Lancez la commande suivante :

            git clone https://github.com/NAOWillyMickey/NAO.git
        
    * Toujours dans le terminal, placez-vous à la racine du projet.

2. Via le téléchargement du code source en une archive ZIP, à cette adresse : [lien](https://codeload.github.com/NAOWillyMickey/NAO/zip/master)

    * Décompressez ce fichier et placez-le dans le répertoire prêt à recevoir l'application.
    
    * Ouvrez votre terminal et placez-vous à la racine du projet fraichement créé.

## 2. Télécharger les vendors

Avec Composer bien évidemment :

    composer install

Renseignez les champs demandés par composer lors de l'installation :

* database_host:
* database_port:
* database_name:
* database_user:
* database_password:
* mailer_transport:
* mailer_host: Si vous utilisez gmail [Gmail avec Symfony](https://symfony.com/doc/3.4/email/gmail.html)
* mailer_user:
* mailer_password:
* secret:

## 3. Créez la base de données

Si la base de données que vous avez renseignée dans l'étape 2 n'existe pas déjà, créez-la :

    php bin/console doctrine:database:create

Puis créez les tables correspondantes au schéma Doctrine :

    php bin/console doctrine:schema:update --force
    
## 4. Téléchargez la base de données

Afin de remplir la base de données correctement, téléchargez le fichier suivant : [TAXREF](https://www.dropbox.com/s/1w8f20o6brzbyxv/TAXREFv11.txt?dl=0)

Placez ce fichier dans le repertoire suivant :

`NAO/web/`

Toujours dans votre terminal, lancez la commande suivante et répondez par yes à la question posée :

    php bin/console doctrine:fixtures:load

Maintenant que la base de données est à jour, supprimez désormais le fichier TAXREF du répertoire afin de ne pas alourdir l'application.

## 4. Publiez les assets

Publiez les assets dans le répertoire web :

    php bin/console assets:install web

### ENJOY !!!