<?php

namespace App\Services\Tomoni;

use App\Services\Tomoni\Exceptions\CommunicationException;
use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;

abstract class ApiService
{
    protected \GuzzleHttp\Client $client;

    protected $token = null;

    protected $prefix = 'api';

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $this->getUrl(),
            'headers' => $this->getHeaders(),
        ]);
    }

    public function getToken()
    {
        return $this->token ?: request()->header('X-Firebase-IDToken');
    }

    public function setToken($token)
    {
        return $this->token = $token;
    }

    abstract public function serviceName(): string;

    public function getBaseUrl()
    {
        $host = config('tomoni.' . $this->serviceName() . '.host');
        if (!$host) {
            throw new \Exception('Service not found');
        }
        return $host;
    }

    public function getUrl()
    {
        return Str::finish($this->getBaseUrl(), '/') . Str::finish($this->prefix, '/');
    }

    public function getHeaders()
    {
        return [
            'Accept'     => 'application/json',
            'X-Firebase-IDToken' => $this->getToken(),
            'Accept-Language' => app()->getLocale(),
        ];
    }

    public function get(string $path, array $query = [])
    {
        $options = [
            RequestOptions::QUERY => $query,
        ];

        try {
            return $this->parserResult($this->client->get($path, $options));
        } catch (ClientException $ex) {
            throw $this->getException($ex);
        }
    }

    public function post(string $path, array $data)
    {
        $options = [
            RequestOptions::FORM_PARAMS => $data,
        ];

        try {
            return $this->parserResult($this->client->post($path, $options));
        } catch (ClientException $ex) {
            throw $this->getException($ex);
        }
    }

    public function put(string $path, array $data = [])
    {
        $options = [
            RequestOptions::FORM_PARAMS => $data,
        ];

        try {
            return $this->parserResult($this->client->put($path, $options));
        } catch (ClientException $ex) {
            throw $this->getException($ex);
        }
    }

    public function delete(string $path, array $query = [])
    {
        $options = [
            RequestOptions::QUERY => $query,
        ];

        try {
            return $this->parserResult($this->client->delete($path, $options));
        } catch (ClientException $ex) {
            throw $this->getException($ex);
        }
    }

    protected function parserResult(ResponseInterface $response)
    {
        return json_decode($response->getBody()->getContents());
    }

    protected function getException(ClientException $ex): Exception
    {
        $error = json_decode($ex->getResponse()->getBody());
        return new CommunicationException($error->message ?? $error, $ex->getResponse()->getStatusCode());
    }
}
