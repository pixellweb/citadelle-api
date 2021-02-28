<?php
namespace Citadelle\ReferentielApi\app\Ressources;


class Source extends Ressource
{


    /**
     * @return array
     * @throws \Citadelle\ReferentielApi\app\ReferentielApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get()
    {
        return $this->api->get('source/');
    }

    /**
     * @param $source_id
     * @param $type
     * @return array
     * @throws \Citadelle\ReferentielApi\app\ReferentielApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function correspondance($source_id, $type)
    {
        return $this->api->get('source/'.$source_id.'/correspondance/'.$type);
    }

}
