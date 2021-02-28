<?php

namespace Citadelle\ReferentielApi\app\Ressources;


class Type extends Ressource
{

    /**
     * @return array
     * @throws \Citadelle\ReferentielApi\app\ReferentielApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get()
    {
        return $this->api->get('referentiel/type/');
    }

}
