<?php
/**
 * Nom du fichier : EventsCpntroller.php
 *
 * Auteur : Alexandre Ribes
 * Email : alexandre@laboiteacode.fr
 * Site Web : https://alexandre-ribes.fr
 */
namespace App\Controllers\Api;

use App\Models\Event;
use Core\BaseController;

class EventsController extends BaseController
{
    /**
     * @return void
     */
    public function addGoodEvent(): void
    {
        $this->addEvent('good');
    }

    /**
     * @return void
     */
    public function addBadEvent(): void
    {
        $this->addEvent('bad');
    }

    /**
     * @return void
     */
    public function removeLastEvent(): void
    {
        $lastEvent = (new Event())->latest()->first();

        if( !$lastEvent ) {
            echo json_encode([
                'status'    =>  'error',
                'message'   =>  'Aucun évènement à supprimer',
            ]);
            exit;
        }

        $lastId = $lastEvent->id;
        (new Event())->delete($lastEvent->id);

        echo json_encode(array_merge([
            'status'    =>  'success',
            'id'        =>  $lastId,
        ], $this->generateChartsData()));
    }

    /**
     * @param $status
     * @return void
     */
    private function addEvent($status = 'good'): void
    {
        if( !in_array($status, ['good', 'bad']) ) {
            $status = 'good';
        }

        $event = new Event();
        $lastEvent = (new Event())->latest()->first();

        $event->setData('name', $lastEvent ? 'Evènement n°' . ($lastEvent->id + 1) : 'Evènement n°1');
        $event->setData('session', $_SERVER['HTTP_USER_AGENT']);
        $event->setData('status', $status);

        $event->save();

        echo json_encode(array_merge([
            'status' => 'success',
            'event' => [
                'name'  =>  $event->getAttribute('name'),
                'session'   =>  $event->getAttribute('session'),
                'status'    =>  $event->getAttribute('status'),
                'created_at'    =>  formattedDate($event->getAttribute('created_at')),
            ],
        ], $this->generateChartsData()));
    }

    /**
     * @return array
     */
    private function generateChartsData(): array
    {
        $pieCharts = (new Event())->getEventsStatusPourcentages();
        $lineCharts = (new Event())->getEventsLineEvolution();

        return [
            'pieCharts' =>  [
               [
                   'name'   =>  'Bons',
                   'y'      =>   (int) $pieCharts['good'],
               ],
               [
                   'name'   =>  'Mauvais',
                   'y'      =>   (int) $pieCharts['bad'],
               ]
            ],
            'lineCharts' => [
                'categories'    =>  $lineCharts['minutes'],
                'series'        =>  [
                    [
                        'name'  =>  'Bons évènements',
                        'data'  =>  array_values($lineCharts['good']),
                    ],
                    [
                        'name'  =>  'Mauvais évènements',
                        'data'  =>  array_values($lineCharts['bad']),
                    ],
                ]
            ]
        ];
    }
}