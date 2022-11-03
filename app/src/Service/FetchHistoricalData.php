<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\HistoricalData;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FetchHistoricalData
{
    private HttpClientInterface $client;
    private string $dataSourceUrl;
    private string $dataSourceApiKey;
    private string $dataSourceApiHost;
    private ObjectManager $em;

    /**
     * @param HttpClientInterface $client
     * @param string $dataSourceUrl
     * @param string $dataSourceApiKey
     * @param string $dataSourceApiHost
     */
    public function __construct(HttpClientInterface $client, ManagerRegistry $doctrine, string $dataSourceUrl, string $dataSourceApiKey, string $dataSourceApiHost)
    {
        $this->client = $client;
        $this->dataSourceUrl = $dataSourceUrl;
        $this->dataSourceApiKey = $dataSourceApiKey;
        $this->dataSourceApiHost = $dataSourceApiHost;
        $this->em = $doctrine->getManager();
    }

    /**
     * @param string $symbol
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getData(string $symbol)
    {
        $result = $this->client->request("GET", "{$this->dataSourceUrl}?symbol={$symbol}", [
            'headers' => [
                'X-RapidAPI-Key' => $this->dataSourceApiKey,
                'X-RapidAPI-Host' => $this->dataSourceApiHost,
            ],
        ]);

        $data = json_decode($result->getContent())->prices ?? [];

        $batchSize = 50;
        for ($i = 1; $i <= count($data); ++$i) {
            if (isset($data[$i])) {
                $user = new HistoricalData();
                $user->setDate($data[$i]->date);
                $user->setOpen($data[$i]->open ?? null);
                $user->setHigh($data[$i]->high ?? null);
                $user->setLow($data[$i]->low ?? null);
                $user->setClose($data[$i]->close ?? null);
                $user->setVolume($data[$i]->volume ?? null);
                $user->setAdjclose($data[$i]->adjclose ?? null);
                $user->setSymbol($symbol);
                $this->em->persist($user);

                if ($i % $batchSize === 0) {
                    $this->em->flush();
                    $this->em->clear();
                }
            }
        }

        $this->em->flush();
    }
}