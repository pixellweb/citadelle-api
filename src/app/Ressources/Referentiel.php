<?php

namespace Citadelle\ReferentielApi\app\Ressources;


use Citadelle\ReferentielApi\app\ReferentielApiException;

class Referentiel extends Ressource
{


    /**
     * @param $type
     * @return array
     * @throws ReferentielApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($type)
    {
        return $this->api->get('referentiel/' . $type);
    }

    /**
     * @return array
     * @throws ReferentielApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function energy()
    {
        return $this->get('energy');
    }

}
