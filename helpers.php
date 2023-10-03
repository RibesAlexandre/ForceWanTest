<?php
/**
 * Nom du fichier : helpers.php
 *
 * Auteur : Alexandre Ribes
 * Email : alexandre@laboiteacode.fr
 * Site Web : https://alexandre-ribes.fr
 */

/**
 * Dump Custom Function
 *
 * @param $data
 * @return void
 */
function dump($data): void
{
    print '<pre>';
    print_r($data);
    print '</pre>';
}

/**
 * Dump and Die Custom Function
 *
 * @param $data
 * @return void
 */
function dd($data): void
{
    dump($data);
    exit;
}