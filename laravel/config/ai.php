<?php

return [

    'anthropic_api_key' => env('ANTHROPIC_API_KEY'),
    'anthropic_model' => env('ANTHROPIC_MODEL', 'claude-haiku-4-5-20251001'),

    'budget_profiles' => [
        'gaming' => [
            'cpu' => 0.20,
            'cpu-cooler' => 0.05,
            'maticna-ploca' => 0.12,
            'ram' => 0.10,
            'gpu' => 0.33,
            'storage' => 0.07,
            'napajanje' => 0.06,
            'kuciste' => 0.07,
        ],
        'office' => [
            'cpu' => 0.25,
            'cpu-cooler' => 0.03,
            'maticna-ploca' => 0.15,
            'ram' => 0.12,
            'gpu' => 0.10,
            'storage' => 0.15,
            'napajanje' => 0.10,
            'kuciste' => 0.10,
        ],
        'content-creation' => [
            'cpu' => 0.28,
            'cpu-cooler' => 0.06,
            'maticna-ploca' => 0.13,
            'ram' => 0.15,
            'gpu' => 0.18,
            'storage' => 0.08,
            'napajanje' => 0.06,
            'kuciste' => 0.06,
        ],
    ],

    'max_requests_per_hour' => 30,

];
