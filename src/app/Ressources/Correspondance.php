<?php
namespace Citadelle\ReferentielApi\app\Ressources;


class Correspondance extends Ressource
{


    /**
     * @param $source_id
     * @param $type
     * @return mixed
     */
    public function get($source_id, $type)
    {
        return $this->api->get('source/'.$source_id.'/correspondance/'.$type);
    }

    /**
     * @param $source_id
     * @param $type
     * @param $datas
     * @return mixed
     */
    public function post($source_id, $type, $datas)
    {
        return $this->api->post('source/'.$source_id.'/correspondance/'.$type, $datas);
    }

}
