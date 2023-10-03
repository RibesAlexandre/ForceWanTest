<?php
/**
 * Nom du fichier : DatabaseConnectionInterface.php
 *
 * Auteur : Alexandre Ribes
 * Email : alexandre@laboiteacode.fr
 * Site Web : https://alexandre-ribes.fr
 */

namespace Core\Database;

interface DatabaseConnectionInterface
{
    public static function getInstance(): \PDO;
}