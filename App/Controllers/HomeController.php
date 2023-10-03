<?php

namespace App\Controllers;

use App\Models\Event;
use Core\BaseController;
use Core\Database\QueryBuilder;

class HomeController extends BaseController
{
    /**
     * @throws \Exception
     */
    public function index()
    {
        $events = (new Event())->latest()->get();

        $pieStats = (new Event())->getEventsStatusPourcentages();
        $lineStats = (new Event())->getEventsLineEvolution();

        $this->render('home', compact('events', 'pieStats', 'lineStats'));
    }
}