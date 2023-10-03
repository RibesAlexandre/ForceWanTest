<?php

namespace App\Models;

use Core\BaseModel;
use Core\Database\QueryBuilder;

class Event extends BaseModel
{
    public array $fields = [
        'name' ,
        'session' ,
        'created_at' ,
        'updated_at' ,
        'status' ,
    ];

    /**
     * @return array
     */
    public function getEventsStatusPourcentages(): array
    {
        //  Pour aller plus loin il faudrait développer les Models pour qu'ils puissent intégrer ce genre de requête plus facilement.
        $stats   = (new QueryBuilder())->executeRaw(
            "SELECT status, COUNT(id) as total, (COUNT(id) / (SELECT COUNT(id) FROM fw_events WHERE status IN ('good', 'bad')) * 100) as pourcentage FROM fw_events WHERE status IN ('good', 'bad') GROUP BY status;");
        $results = $stats->fetchAll();

        return [
            'good' => $results[0]['pourcentage'] ?? '0' ,
            'bad'  => $results[1]['pourcentage'] ?? '0' ,
        ];
    }

    public function getEventsLineEvolution(): array
    {
        $stats   = (new QueryBuilder())->executeRaw(
            "SELECT DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') as minute, status, COUNT(*) as total FROM fw_events WHERE status IN ('good', 'bad') GROUP BY minute, status ORDER BY minute ASC, status DESC;");
        $results = $stats->fetchAll();

        $data = [
            'minutes' => [] ,
            'good'    => [] ,
            'bad'     => [] ,
        ];

        foreach ($results as $result) {
            $minutes = date_parse($result['minute'])['hour'] . ':' . date_parse($result['minute'])['minute'];

            if( !in_array($minutes, $data['minutes']) ) {
                $data['minutes'][] = $minutes;
            }

            if ($result['status'] === 'good') {
                $data['good'][$minutes] = (int)$result['total'];
                if (!array_key_exists($minutes , $data['bad'])) {
                    $data['bad'][$minutes] = 0;
                }
            } else {
                $data['bad'][$minutes] = (int)$result['total'];
                if (!array_key_exists($minutes , $data['good'])) {
                    $data['good'][$minutes] = 0;
                }
            }
        }

        return $data;
    }
}