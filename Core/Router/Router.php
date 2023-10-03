<?php
/**
 * Nom du fichier : Router.php
 *
 * Auteur : Alexandre Ribes
 * Email : alexandre@laboiteacode.fr
 * Site Web : https://alexandre-ribes.fr
 */
namespace Core\Router;

use App\Exceptions\RouterException;

class Router
{
    /**
     * @var string
     */
    private string $url = '';

    /**
     * @var array
     */
    private array $routes = [];

    /**
     * @var array
     */
    private array $namedRoutes = [];

    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * @param $path
     * @param $callable
     * @param $name
     * @return \Core\Router\Route
     */
    public function get($path, $callable, $name = null): Route
    {
        return $this->add($path, $callable, $name, 'GET');
    }

    /**
     * @param $path
     * @param $callable
     * @param $name
     * @return \Core\Router\Route
     */
    public function post($path, $callable, $name = null): Route
    {
        return $this->add($path, $callable, $name, 'POST');
    }

    //  TODO: Compléter avec PUT, PATCH, DELETE si besoin, inutile dans le cadre de l'exercice

    /**
     * @param $path
     * @param $callable
     * @param $name
     * @param $method
     * @return \Core\Router\Route
     */
    public function add($path, $callable, $name, $method): Route
    {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;

        if( is_string($callable) && $name === null ) {
            $name = $callable;
        }

        if( $name ) {
            $this->namedRoutes[$name] = $route;
        }

        return $route;
    }

    /**
     * @throws \App\Exceptions\RouterException
     */
    public function run(): mixed
    {
        if( !isset($this->routes[$_SERVER['REQUEST_METHOD']]) ) {
            throw new RouterException('La méthode demandée n\'est pas supportée');
        }

        foreach( $this->routes[$_SERVER['REQUEST_METHOD']] as $route ) {
            if( $route->match($this->url) ) {
                return $route->call();
            }
        }

        throw new RouterException('Aucune route correspondante');
    }

    /**
     * @throws \App\Exceptions\RouterException
     */
    public function url($name, $params = []): string
    {
        if( !isset($this->namedRoutes[$name]) ) {
            throw new RouterException('Aucune route ne correspond à ce nom');
        }

        return $this->namedRoutes[$name]->getUrl($params);
    }
}