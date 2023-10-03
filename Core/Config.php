<?php
/**
 * Nom du fichier : Config.php
 *
 * Auteur : Alexandre Ribes
 * Email : alexandre@laboiteacode.fr
 * Site Web : https://alexandre-ribes.fr
 */

namespace Core;

class Config
{
    /**
     * @var array
     */
    private array $settings = [];

    /**
     * @var \Core\Config|null
     */
    private static ?self $_instance = null;

    /**
     * Config constructor.
     */
    public function __construct()
    {
        $files = scandir(dirname(__DIR__) . '/config');

        foreach( $files as $file ) {
            if( $file === '.' || $file === '..' ) {
                continue;
            }

            if( is_file(dirname(__DIR__) . '/config/' . $file) === false ) {
                continue;
            }

            $this->settings[basename($file, '.php')] = require dirname(__DIR__) . '/config/' . $file;
        }
    }

    /**
     * @return \Core\Config
     */
    public static function getInstance(): Config
    {
        if( is_null(self::$_instance) ) {
            self::$_instance = new Config();
        }
        return self::$_instance;
    }

    /**
     * @param $key
     * @param $default
     * @return string|null
     */
    public function get($key, $default = null): ?string
    {
        $segments = explode('.', $key);
        $settings = $this->settings;

        foreach( $segments as $segment ) {
            if( isset($settings[$segment]) ) {
                $settings = $settings[$segment];
            } else {
                return $default;
            }
        }


        return $settings;
    }

    /**
     * @return array
     */
    public function getSettings(): array
    {
        return $this->settings;
    }
}