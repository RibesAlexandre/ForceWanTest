<?php
/**
 * Nom du fichier : Route.php
 *
 * Auteur : Alexandre Ribes
 * Email : alexandre@laboiteacode.fr
 * Site Web : https://alexandre-ribes.fr
 */
namespace Core\Router;

use App\Exceptions\RouterException;

class Route
{
    private string $path;
    private $callable;

    private $matches;

    private $params = [];

    public function __construct($path, $callable)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    /**
     * @param $url
     * @return bool
     */
    public function match($url): bool
    {
        $url = trim($url, '/');
        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);
        $regex = "#^$path$#i";

        if( !preg_match($regex, $url, $matches) ) {
            return false;
        }

        array_shift($matches);
        $this->matches = $matches;

        return true;
    }

    /**
     * @param $match
     * @return string
     */
    private function paramMatch($match): string
    {
        if( isset($this->params[$match[1]]) ) {
            return '(' . $this->params[$match[1]] . ')';
        }
        return '([^/]+)';
    }

    /**
     * @return mixed
     * @throws \App\Exceptions\RouterException
     */
    public function call(): mixed
    {
        if( is_string($this->callable) ) {
            $params = explode('@', $this->callable);
            $controller = str_replace('/', '\\', $params[0]);
            $controller = "App\\Controllers\\" . $controller . "Controller";

            if( !class_exists($controller) ) {
                throw new RouterException("Le controller $controller n'existe pas");
            }

            $controller = new $controller();

            $method = $params[1];
            if( !method_exists($controller, $method) ) {
                throw new RouterException("La méthode $method n'existe pas dans le controller $controller");
            }

            return call_user_func_array([$controller, $method], $this->matches);
        }

        return call_user_func_array($this->callable, $this->matches);
    }

    /**
     * @param $params
     * @return string
     */
    public function getUrl($params): string
    {
        $path = $this->path;
        foreach( $params as $k => $v ) {
            $path = str_replace(":$k", $v, $path);
        }
        return $path;
    }

}