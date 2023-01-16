<?php

namespace App\Services;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class RHService
{
    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getPeople(): array
    //aprés consultation de la doc de l'api,renvoit l'ensemble du personnel
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://rh.ptitlabo.xyz?method=people');
        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        return $response->toArray();
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getDayTeam(): array
    //renvoit l'ensemble du personnel de la journée
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://rh.ptitlabo.xyz?method=planning');
        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        return $response->toArray();
    }

    public function setOrderPayed($order): array
    {
        $client = HttpClient::create();
        $response = $client->request('POST', 'http://rh.ptitlabo.xyz?method=order', [
            'body' => [
                'id' => $order->getId(),
                'price' => $order->getPrixCommmande(),
            ],
        ]);
        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        return $response->toArray();
    }
}