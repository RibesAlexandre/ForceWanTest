<?php
/**
 * Nom du fichier : App.php
 *
 * Auteur : Alexandre Ribes
 * Email : alexandre@laboiteacode.fr
 * Site Web : https://alexandre-ribes.fr
 */

namespace App;

use Core\Config;
use Core\Database\DatabaseConnection;
use Core\Layout;
use Core\Router\Router;

class App
{
    /**
     * @var string
     */
    public static string $title = '';

    /**
     * @var \Core\Config|null
     */
    private static ?Config $config = null;

    /**
     * @var \Core\Database\DatabaseConnection|null
     */
    private static ?DatabaseConnection $databaseConnection = null;

    /**
     * @var \Core\Layout|null
     */
    private static ?Layout $layout = null;

    private static ?Router $router = null;

    /**
     * @var \App\App|null
     */
    private static ?self $instance = null;

    /**
     * @return \App\App
     */
    public static function getInstance(): App
    {
        if (is_null(self::$instance)) {
            self::$instance = new App();
        }

        return self::$instance;
    }

    /**
     * Run Application
     *
     * @return void
     */
    public static function run(): void
    {
        self::$config = new Config();
        self::$databaseConnection = new DatabaseConnection();
        self::$title = self::config()->get('app.site_name');
    }

    public static function getTitle(): string
    {
        return self::$title;
    }

    /**
     * @return \Core\Config
     */
    public static function config(): Config
    {
        return self::$config;
    }

    /**
     * @return \Core\Database\DatabaseConnection
     */
    public static function database(): DatabaseConnection
    {
        return self::$databaseConnection;
    }

    public static function layout(): Layout
    {
        if( is_null(self::$layout) ) {
            self::$layout = new Layout();
        }
        return self::$layout;
    }

    public static function route(): Router
    {
        if( is_null(self::$router) ) {
            self::$router = new Router(isset($_GET['url']) ? htmlspecialchars($_GET['url']) : '/');
        }
        return self::$router;
    }
}