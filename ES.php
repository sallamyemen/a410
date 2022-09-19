<?php

namespace app\models;
use yii\base\Model;

use Yii;

class ES extends Model
{


    public $key;

    public function search()
    {
        $res = [];
        $indexes = ['product', 'catalogue', 'content', 'accii'];
        // $indexes = ['product', 'catalogue', 'content'];

        $query = 'product/_search';
        $json = [
            'query' => [
                'multi_match' => [
                    'query' => $this->key,
                    'fields' => [
                        'title', 'tags'
                    ],
                    'operator' => 'and',
                ],
            ],
        ];

        $json = json_encode($json);
        $res['product'] = json_decode($this->send($query, $json));

        $query = 'catalogue/_search';
        $json = [
            'query' => [
                'match' => [
                    'title' => [
                        'query' => $this->key,
                        'operator' => 'or',
                    ],
                ],
            ],
        ];

        $json = json_encode($json);
        $res['catalogue'] = json_decode($this->send($query, $json));


        $query = 'content/_search';
        $json = [
            'query' => [
                'multi_match' => [
                    'query' => $this->key,
                    'fields' => [
                        'title', 'content'
                    ],
                    'operator' => 'and',
                ],
            ],
        ];
   

        $json = json_encode($json);
        $res['content'] = json_decode($this->send($query, $json));

        // $query = 'accii/_search';
        // $json = [
        //     'query' => [
        //         'multi_match' => [
        //             'query' => $this->key,
        //             'fields' => [
        //                 'title', 'content'
        //             ],
        //             'operator' => 'or',
        //         ],
        //     ],
        // ];

        // $json = json_encode($json);
        // $res['accii'] = json_decode($this->send($query, $json));

        return $res;
    }

    public function send($query, $json = false, $type = false) {

        $headers = [];
        $headers[] = "Content-Type: application/json";

        $ch = curl_init('http://localhost:9200/' . $query);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        if(!$type)
            $type = 'GET';

        if($type == 'GET' || $type == 'DELETE' || $type == 'PATCH' || $type == 'PUT' || $type == 'POST') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);

           /* if($json)
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);*/
        }
        if($json) curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);

        //$response = json_decode($response);
        return $response;

    }
}