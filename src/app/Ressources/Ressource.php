<?php

namespace Citadelle\ReferentielApi\app\Ressources;


use Citadelle\ReferentielApi\app\Api;

class Ressource
{
    /**
     * @var Api
     */
    public $api;


    /**
     * Ressource constructor.
     */
    public function __construct()
    {
        $this->api = new Api();
    }

}
