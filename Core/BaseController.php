<?php
/**
 * Nom du fichier : BaseController.php
 *
 * Auteur : Alexandre Ribes
 * Email : alexandre@laboiteacode.fr
 * Site Web : https://alexandre-ribes.fr
 */

namespace Core;

use App\App;
use JetBrains\PhpStorm\NoReturn;

class BaseController
{
    protected Config $config;

    protected Database\DatabaseConnection $database;

    protected Layout $layout;

    public function __construct()
    {
        $this->config = App::config();
        $this->database = App::database();
        $this->layout = App::layout();
    }

    /**
     * @throws \Exception
     */
    public function render($file, $data = []): void
    {
        $this->layout->render($file, $data);
    }

    /**
     * @param string $path
     * @return void
     */
    #[NoReturn] public function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit();
    }

    /**
     * @return void
     */
    #[NoReturn] public function back(): void
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    /**
     * @param int $code
     * @return void
     * @throws \Exception
     */
    #[NoReturn] public function abort(int $code): void
    {
        http_response_code($code);
        $this->layout->render('errors/' . $code);
        exit();
    }
}