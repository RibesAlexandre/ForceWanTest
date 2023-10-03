<?php
/**
 * Nom du fichier : index.php
 *
 * Auteur : Alexandre Ribes
 * Email : alexandre@laboiteacode.fr
 * Site Web : https://alexandre-ribes.fr
 */

require_once(__DIR__ . '/../helpers.php');
require_once(__DIR__ . '/../autoloader.php');

$app = App\App::getInstance();
$app->run();

//echo $app::$title;

$app->route()->get('/', 'Home@index', 'home');
$app->route()->get('/api/good-event', 'Api/Events@addGoodEvent', 'goodEvent');
$app->route()->get('/api/bad-event', 'Api/Events@addBadEvent', 'badEvent');
$app->route()->get('/api/remove-last-event', 'Api/Events@removeLastEvent', 'removeLastEvent');
$app->route()->run();