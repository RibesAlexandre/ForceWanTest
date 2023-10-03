<img src="https://banners.beyondco.de/Test%20Force%20Wan.png?theme=light&packageManager=&packageName=ForceWanTest&pattern=floatingCogs&style=style_1&description=Test+D%C3%A9veloppement+Force+Wan&md=1&showWatermark=0&fontSize=100px&images=https://alexandre-ribes.fr/build/assets/logo_a_dark.de112076.svg&widths=600" alt="Forcer Wan Test">

# Installation 

## Prérequis

- PHP 8.1 ou supérieur
- Mysql 5.5 ou supérieur

## Configuration 

Mettre à jour le fichier

```
config/database.php
```

pour renseigner vos identifiants de connexion MySQL.

Dans votre base de donnée lancez le script SQL suivant :

```
CREATE TABLE `fw_events` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `session` text,
  `status` enum('good','bad') DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

Le script est disponible en import dans le fichier database.sql si besoin

## Installation avec Virtual Host

Faîtes pointer votre virtualHost vers le dossier public du projet. 

## Installation sans Virtual Host

Rendez vous sur votre localhost / ForceWanTest / public 

## Ré-écriture d'url

Si la ré-écriture d'url ne fonctionne pas. Il vous suffira de rajouter index.php?url=/ en fin de votre url.