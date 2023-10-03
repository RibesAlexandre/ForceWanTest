<?php
/**
 * Nom du fichier : DatabaseConnection.php
 *
 * Auteur : Alexandre Ribes
 * Email : alexandre@laboiteacode.fr
 * Site Web : https://alexandre-ribes.fr
 */

namespace Core\Database;

use PDO;
use PDOException;

class DatabaseConnection implements DatabaseConnectionInterface
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if( is_null(self::$instance) ) {
            try {
                $config = \App\App::config();

                self::$instance = new PDO(
                    'mysql:host=' . $config->get('database.db_host') . ';dbname=' . $config->get('database.db_name') . ';charset=utf8',
                    $config->get('database.db_user'),
                    $config->get('database.db_pass'),
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch( PDOException $e ) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}