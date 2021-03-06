<?php


namespace Citadelle\ReferentielApi\app;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;

/**
 * Class Api
 * @package App\Citadelle
 */
class Api
{
    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    protected $token;


    /**
     * Api constructor.
     */
    public function __construct()
    {
        $this->url = config('citadelle.referentiel.api.url');
        $this->token = config('citadelle.referentiel.api.token');
    }


    /**
     * @param string $ressource_path
     * @return array
     * @throws ReferentielApiException
     */
    public function get(string $ressource_path): array
    {
        $client = new Client([
                'base_uri' => $this->url
            ]
        );

        $headers = [
            'query' => ['api_token' => $this->token],
            'headers' => [
                'Accept' => 'application/json'
            ]
        ];

        try {
            $response = $client->get($ressource_path, $headers);

            if ($response->getStatusCode() != 200 or empty($response->getBody()->getContents())) {
                throw new ReferentielApiException("Api::get : code http error (" . $response->getStatusCode() . ") ou body vide : " . $ressource_path);
            }

        } catch (RequestException $exception) {
            /*$errors['request'] = Psr7\Message::toString($e->getRequest());
            if ($e->hasResponse()) {
                $errors['response'] = Psr7\Message::toString($e->getResponse());
            }*/

            throw new ReferentielApiException("Api::get : " . $exception->getMessage());
        }

        return json_decode($response->getBody(), true);

    }

    /**
     * @param string $ressource_path
     * @param array $params
     * @return bool
     * @throws GuzzleException
     * @throws ReferentielApiException
     */
    public function post(string $ressource_path, array $params): bool
    {
        $client = new Client(['base_uri' => $this->url]);
        $headers = [
            'query' => ['api_token' => $this->token],
            'headers' => [
                'Accept' => 'application/json'
            ],
            'form_params' => $params
        ];

        try {

            $response = $client->request('POST', $ressource_path, $headers);
            return true;

        } catch (RequestException $exception) {

            throw new ReferentielApiException("Api::post : " . $exception->getMessage());

        }
    }
}
