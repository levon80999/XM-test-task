<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CompanySymbolsRepository;
use App\Repository\HistoricalDataRepository;
use App\Requests\HistoricalDataRequest;
use App\Service\FetchHistoricalData;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class HomeController extends AbstractController
{
    private Mailer $mailer;
    private FetchHistoricalData $fetchHistoricalData;
    private HistoricalDataRepository $historicalDataRepository;
    private CompanySymbolsRepository $companySymbolsRepository;



    /**
     * @param Mailer $mailer
     * @param FetchHistoricalData $fetchHistoricalData
     * @param HistoricalDataRepository $historicalDataRepository
     * @param CompanySymbolsRepository $companySymbolsRepository
     */
    public function __construct(
        Mailer $mailer,
        FetchHistoricalData $fetchHistoricalData,
        HistoricalDataRepository $historicalDataRepository,
        CompanySymbolsRepository $companySymbolsRepository
    )
    {
        $this->mailer = $mailer;
        $this->fetchHistoricalData = $fetchHistoricalData;
        $this->historicalDataRepository = $historicalDataRepository;
        $this->companySymbolsRepository = $companySymbolsRepository;
    }

    /**
     * @Route("/", name="app_home")
     * @param HistoricalDataRequest $request
     * @return Response
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function index(HistoricalDataRequest $request): Response
    {
        $errors = $historicalData = [];

        if ($request->isMethod('POST')) {
            $errors = $request->validate();

            if ($errors === null) {

                $data = $request->getRequest()->request->all();


                try {
                    $historicalData = $this->historicalDataRepository->getDataBySymbol($data['companySymbol'], [
                        'startDate' => strtotime($data['startDate']),
                        'endDate' => strtotime($data['endDate']),
                    ]);

                    if (empty($historicalData)) {
                        $this->fetchHistoricalData->getData($data['companySymbol']);

                        $historicalData = $this->historicalDataRepository->getDataBySymbol($data['companySymbol'], [
                            'startDate' => strtotime($data['startDate']),
                            'endDate' => strtotime($data['endDate']),
                        ]);
                    }

                    $this->fetchHistoricalData->getData($data['companySymbol']);

                    $result = $this->companySymbolsRepository->findBySymbol($data['companySymbol']);
                    $data['companyName'] = $result[0]->getCompanyName();
                    $this->mailer->sendReportMail($data);
                } catch (\Exception $exception) {
                    // this is commit 
                    dd($exception);
                }
            }
        }

        return $this->render('home/index.html.twig', [
            'errors' => $errors,
            'historicalData' => $historicalData,
        ]);
    }
}
