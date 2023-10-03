<?php
/**
 * Nom du fichier : autoloader.php
 *
 * Auteur : Alexandre Ribes
 * Email : alexandre@laboiteacode.fr
 * Site Web : https://alexandre-ribes.fr
 */

spl_autoload_register(function ($className) {
    $autoloadDirectories = ['App', 'Core'];

    foreach( $autoloadDirectories as $directory ) {

        $parts = explode('\\', $className);
        $baseName = array_shift($parts);

        if( $baseName === $directory ) {
            $className = implode('/', $parts);
            $fileName = __DIR__ . '/' . $directory . '/' . $className . '.php';

            if( file_exists($fileName) ) {
                require $fileName;
                return;
            } else {
                echo "Le fichier n'existe pas : $fileName\n";
            }
        }
    }
});