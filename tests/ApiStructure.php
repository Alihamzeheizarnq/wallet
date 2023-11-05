<?php

namespace Tests;

trait ApiStructure
{
    public function responseStructure(): array
    {
        return [
            'data' => [
                'pagination' => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages',
                    'links' => [
                        'first',
                        'last',
                        'prev',
                        'next'
                    ]
                ]
            ]
        ];
    }
}
