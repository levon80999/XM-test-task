<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\CompanySymbols;
use App\Repository\CompanySymbolsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SyncCompanySymbolsCommand extends Command
{
    protected static $defaultName = 'app:sync-company-symbols';
    protected static $defaultDescription = 'Check Api last updated time and update data if data was updated.';

    private HttpClientInterface $client;
    private string $companySymbolUrl;
    private CompanySymbolsRepository $companySymbolsRepository;
    private ObjectManager $em;

    /**
     * @param CompanySymbolsRepository $companySymbolsRepository
     * @param HttpClientInterface $client
     * @param ManagerRegistry $doctrine
     * @param string $companySymbolUrl
     */
    public function __construct(
        CompanySymbolsRepository $companySymbolsRepository,
        HttpClientInterface $client,
        ManagerRegistry $doctrine,
        string $companySymbolUrl
    )
    {
        $this->client = $client;
        $this->companySymbolUrl = $companySymbolUrl;
        $this->companySymbolsRepository = $companySymbolsRepository;
        $this->em = $doctrine->getManager();

        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $response = $this->client->request('HEAD', $this->companySymbolUrl);
            $lastModification = $response->getHeaders()['last-modified'][0];

            $exists = $this->companySymbolsRepository->getCurrentLastModifyDate();

            if ($exists === null || $exists->getLastModified() !== $lastModification) {
                $this->companySymbolsRepository->truncate();
                $response = $this->client->request('GET', $this->companySymbolUrl);
                $data = json_decode($response->getContent());

                $batchSize = 50;
                for ($i = 1; $i <= count($data); ++$i) {
                    if (isset($data[$i])) {
                        $user = new CompanySymbols();
                        $user->setCompanyName($data[$i]->{"Company Name"});
                        $user->setFinancialStatus($data[$i]->{"Financial Status"});
                        $user->setMarketCategory($data[$i]->{"Market Category"});
                        $user->setRoundLotSize($data[$i]->{"Round Lot Size"});
                        $user->setSecurityName($data[$i]->{"Security Name"});
                        $user->setSymbol($data[$i]->{"Symbol"});
                        $user->setTestIssue($data[$i]->{"Test Issue"});
                        $user->setLastModified($lastModification);
                        $this->em->persist($user);

                        if ($i % $batchSize === 0) {
                            $this->em->flush();
                            $this->em->clear();
                        }
                    }
                }

                $this->em->flush();
            }
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }

        return Command::SUCCESS;
    }
}