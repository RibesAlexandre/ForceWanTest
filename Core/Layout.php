<?php

namespace Core;

use App\Exceptions\LayoutException;

class Layout
{
    private string $path = '';

    private static ?self $instance = null;

    public function __construct()
    {
        $this->path = dirname(__DIR__) . '/resources/views/';
    }

    public static function getInstance(): self
    {
        if( is_null(self::$instance) ) {
            self::$instance = new Layout();
        }
        return self::$instance;
    }

    /**
     * @throws \Exception
     */
    private function getFile(string $file, $data): string
    {
        if( is_file($this->path . $file . '.php') ) {
            ob_start();
            extract($data);
            require_once($this->path . $file . '.php');
            $content = ob_get_contents();
            ob_end_clean();

            return $content;
        }
        throw new LayoutException("Le fichier $file n'existe pas");
    }

    /**
     * @throws \Exception
     */
    public function render($file, $data = []): void
    {
        $content = $this->getFile($file, $data);
        require_once($this->path . 'layout.php');
    }
}