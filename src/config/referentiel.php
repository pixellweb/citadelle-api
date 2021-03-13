<?php

return [

    'api' => [
        "url"       =>  "https://referentiel.groupecitadelle.com/api/v1/",
        "token" => env('CITADELLE_REFERENTIEL_API_TOKEN'),
    ],

    'referentiel' => [
        'types' => [
            'energy' => \App\Models\Vehicule\Referentiel\Energie::class,
            'transmission' => \App\Models\Vehicule\Referentiel\Transmission::class,
            'category' => \App\Models\Vehicule\Referentiel\Categorie::class,
            /*'couleur' => \App\Models\Vehicule\Referentiel\Couleur::class,*/
            'marque' => \App\Models\Vehicule\Referentiel\Marque::class,
            'modele' => \App\Models\Vehicule\Referentiel\Modele::class,
            'caracteristique' => \App\Models\Vehicule\Referentiel\Caracteristique::class,
        ],
    ]

];
